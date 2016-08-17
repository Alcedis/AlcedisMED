<?php

/*
 * AlcedisMED
 * Copyright (C) 2010-2016  Alcedis GmbH
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */ 

/**
 * Class reportExtensionD
 */
class reportExtensionD extends reportMath
{
    /**
     * _buildKoloskopieClause
     *
     * @access  protected
     * @param   string $on
     * @return  string
     */
    protected function _buildKoloskopieClause($on = "SUBSTRING(s.report_param, 5)")
    {
        return "
            LOCATE('5-452.5',  {$on}) != 0 OR
            LOCATE('5-452.2',  {$on}) != 0 OR
            LOCATE('5-482.01', {$on}) != 0 OR
            LOCATE('5-482.11', {$on}) != 0 OR
            LOCATE('5-482.31', {$on}) != 0 OR
            LOCATE('5-482.41', {$on}) != 0 OR
            LOCATE('5-482.51', {$on}) != 0 OR
            LOCATE('5-482.61', {$on}) != 0 OR
            LOCATE('5-482.71', {$on}) != 0 OR
            LOCATE('5-482.81', {$on}) != 0 OR
            LOCATE('5-482.91', {$on}) != 0 OR
            LOCATE('5-482.b1', {$on}) != 0 OR
            LOCATE('5-482.x1', {$on}) != 0
        ";
    }


    /**
     * _getEarliestDiagnosticOp
     * (prio 1 condition from spec)
     *
     * @access  protected
     * @param   resource $db
     * @param   string   $diseaseIds
     * @param   string   $relation
     * @return  string
     */
    public static function buildEarliestDiagnosticOpQuery($db, $diseaseIds, $relation = 'h')
    {
        $query = 'NULL';

        $earliestDiagnosticOpData = sql_query_array($db, "
            SELECT
                h.erkrankung_id,
                h.histologie_id as id,
                h.datum,
                h.art,
                h.unauffaellig,
                IF (MAX(e.datum) IS NOT NULL, CONCAT_WS('', \"'\", MAX(e.datum), \"'\"), 'NULL') as e_datum
            FROM histologie h
                LEFT JOIN eingriff e ON e.eingriff_id = h.eingriff_id AND e.art_diagnostik IS NOT NULL
            WHERE
                h.erkrankung_id IN ({$diseaseIds})
            GROUP BY
                id
        ");

        $sortedHistologies = array();

        // sort all records
        foreach ($earliestDiagnosticOpData as $record) {
            $sortedHistologies[$record['erkrankung_id']][$record['datum']] = $record;
        }

        // find earliest histo for each disease
        foreach ($sortedHistologies as $diseaseId => $records) {
            ksort($records);

            $earliestHisto = reset($records);

            // only process if following conditions are met
            if ($earliestHisto['art'] === 'pr' && $earliestHisto['unauffaellig'] === null) {
                $sortedHistologies[$diseaseId] = $earliestHisto;
            } else { // remove histo
                unset($sortedHistologies[$diseaseId]);
            }
        }

        // create sql string if histologies exist
        if (count($sortedHistologies) > 0) {
            $query = 'MIN(CASE';

            foreach ($sortedHistologies as $record) {
                $query .= " WHEN {$relation}.histologie_id = " . $record['id'] . " THEN " . $record['e_datum'];
            }

            $query .= ' END)';
        }

        return $query;
    }


    /**
     * _getKoloskopies
     *
     * @access  protected
     * @return  reportExtensionD
     */
    protected function _buildKoloskopies()
    {
        $query = "
            SELECT
                s.form_id,
                IF(e.notfall IS NULL, 1, 0) AS 'elektiv' ,
                IF(e.notfall IS NULL AND e.ther_koloskopie_vollstaendig = 1, 1, 0) AS 'complete'
            FROM `status` s
                INNER JOIN eingriff e ON s.form_id = e.eingriff_id
            WHERE
                s.form = 'eingriff' AND
                s.erkrankung_id IN ({$this->getFilteredDiseases()}) AND
                ({$this->_buildKoloskopieClause()})
         ";

        $result = sql_query_array($this->_db, $query);

        $cache = array(
            'ids'      => array(),
            'elektiv'  => array(),
            'complete' => array()
        );

        foreach ($result as $koloskopie) {
            $cache['ids'][] = $koloskopie['form_id'];

            if ($koloskopie['elektiv'] == '1') {
                $cache['elektiv'][] = $koloskopie['form_id'];
            }

            if ($koloskopie['complete'] == '1') {
                $cache['complete'][] = $koloskopie['form_id'];
            }
        }

        $this->setCache('koloskopie', $cache);

        return $this;
    }


    /**
     * _getKoloskopieIds
     *
     * @access  protected
     * @param   string $type
     * @return  string
     */
    protected function _getKoloskopieIds($type = 'ids')
    {
        $ids = $this->getCache('koloskopie');

        // build cache if not already done
        if ($ids === null) {
            $this->_buildKoloskopies();

            $ids = $this->getCache('koloskopie');
        }

        return (count($ids[$type]) > 0 ? implode(',', $ids[$type]) : '0');
    }
}

?>
