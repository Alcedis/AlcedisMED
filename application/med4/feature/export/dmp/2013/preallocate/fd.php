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

class dmp2013PreallocateFd extends dmp2013PreallocateAbstract
{
    /**
     * @access
     * @var null
     */
    protected $_lastEDPNP = null;


    /**
     * @param $db
     * @param $params
     */
    public function __construct($db, $params)
    {
        $this
            ->ignoreField('dmp_brustkrebs_fd_2013_id')
            ->ignoreField('arztwechsel')
            ->ignoreField('unterschrift_datum')
            ->ignoreField('einschreibung_grund')
            ->ignoreField('kv_iknr')
            ->ignoreField('kv_abrechnungsbereich')
            ->ignoreField('versich_nr')
            ->ignoreField('versich_status')
            ->ignoreField('versich_statusergaenzung')
            ->ignoreField('vk_gueltig_bis')
            ->ignoreField('aktueller_status')
            ->ignoreField('kvk_einlesedatum')
            ->ignoreField('rez_status_cr')
            ->ignoreField('rez_status_pr')
            ->ignoreField('rez_status_nc')
            ->ignoreField('rez_status_pd')
            ->ignoreField('rez_th_praeop')
            ->ignoreField('rez_th_exzision')
            ->ignoreField('rez_th_mastektomie')
            ->ignoreField('rez_th_strahlen')
            ->ignoreField('rez_th_chemo')
            ->ignoreField('rez_th_endo')
            ->ignoreField('rez_th_andere')
            ->ignoreField('rez_th_keine')
            ->ignoreField('metast_th_operativ')
            ->ignoreField('metast_th_strahlen')
            ->ignoreField('metast_th_chemo')
            ->ignoreField('metast_th_endo')
            ->ignoreField('metast_th_andere')
            ->ignoreField('metast_th_keine')
            ->ignoreField('termin_datum')
        ;

        parent::__construct($db, $params);
    }


    /**
     *
     *
     * @access
     * @return array
     */
    protected function _load()
    {
        $id = $this->_getFormId();

        $this
            ->setFilter('end', $this->getDocumentationDate())
            ->setFilter('diseaseId', $this->getParam('erkrankung_id'))
        ;

        $where = $this->_getDateFilter('doku_datum');

        $query = "
            SELECT
                doku_datum AS 'date',
                dmp_brustkrebs_ed_2013_id AS 'id',
                'ed' AS 'type',
                anam_brust_links,
                anam_brust_rechts,
                null as primaer_strahlen,
                null as primaer_chemo,
                null as primaer_endo,
                null as primaer_ah
            FROM dmp_brustkrebs_ed_2013
            WHERE
                {$where}
            UNION
            SELECT
                doku_datum AS 'date',
                dmp_brustkrebs_fd_2013_id AS 'id',
                'fd' AS 'type',
                null as 'anam_brust_links',
                null as 'anam_brust_rechts',
                primaer_strahlen,
                primaer_chemo,
                primaer_endo,
                primaer_ah
            FROM dmp_brustkrebs_fd_2013
            WHERE
                {$where} AND
                dmp_brustkrebs_fd_2013_id != '{$id}'
            UNION
            SELECT
                doku_datum AS 'date',
                dmp_brustkrebs_ed_pnp_2013_id AS 'id',
                'ed_pnp' AS 'type',
                anam_brust_links,
                anam_brust_rechts,
                null as primaer_strahlen,
                null as primaer_chemo,
                null as primaer_endo,
                null as primaer_ah
            FROM dmp_brustkrebs_ed_pnp_2013
            WHERE
                {$where}
            ORDER BY
                date DESC
        ";

        $result = sql_query_array($this->_db, $query);

        $cond1 = false;
        $cond2 = false;

        foreach ($result as $index => $entry) {
            if ($index == 0) {
                $this->setFilter('start', $entry['date']);
            }

            if ($cond1 === false && in_array($entry['type'], array('ed', 'ed_pnp')) === true) {
                if ((strlen($entry['anam_brust_links']) > 0 || strlen($entry['anam_brust_rechts']) > 0 ) &&
                    (strlen($entry['anam_brust_links']) > 0 && strlen($entry['anam_brust_rechts']) > 0) === false
                ) {

                    $entry['side'] = strlen($entry['anam_brust_links']) > 0 ? 'L' : 'R';

                    $this->_lastEDPNP = $entry;
                }

                $cond1 = true;
            }

            if ($cond2 === false && $entry['type'] === 'fd') {
                $this
                    ->setParam('strahlen',  $entry['primaer_strahlen'])
                    ->setParam('chemo',     $entry['primaer_chemo'])
                    ->setParam('endo',      $entry['primaer_endo'])
                    ->setParam('ah',        $entry['primaer_ah'])
                ;

                $cond2 = true;
            }
        }

        $this->_prepareTreatmentRegime(true);

        return $this;
    }




    /**
     *
     * @access
     * @return $this
     */
    protected function _processTumorstate()
    {
        $data = $this->_loadTumorstate("AND t.anlass LIKE 'r%'");

        $newContralateral = false;

        if ($this->_lastEDPNP !== null && count($data) > 0) {
            foreach ($data as $record) {
                $cond10 = strlen($record['diagnose_seite']) > 0 && $record['diagnose_seite'] != '-';
                $cond11 = $record['diagnose_seite'] != $this->_lastEDPNP['side'];

                $cond20 = strlen($record['lokalisation_seite']) > 0 && $record['lokalisation_seite'] != '-';
                $cond21 = $record['lokalisation_seite'] != $this->_lastEDPNP['side'];

                if ( ($cond10 === true && $cond11 === true) || ($cond20 === true && $cond21 === true)) {
                    $this->addTimelineEntry('rezidivNew', $record['datum_sicherung'], array(
                        'type' => 'tumorstate',
                        'neu_contralateral_datum' => $record['datum_sicherung']
                    ));
                }
            }

            $newContralateralDate = $this->getTimelineField('rezidivNew', 'neu_contralateral_datum', true);

            if ($newContralateralDate !== null) {
                $this->setField('neu_kontra_datum', todate($newContralateralDate, 'de'));
                $newContralateral = true;
            }
        }

        if ($newContralateral === false) {
            $this->setField('neu_kontra_nein', true);
        }

        $new = false;
        $local = false;

        foreach ($data as $form) {
            $currentNew = false;
            $date = $form['datum_sicherung'];

            if ($form['rezidiv_lokal'] == '1' || $form['rezidiv_lk'] == '1'){
                $this->addTimelineEntry('rezidiv', $date, array(
                    'type'              => 'tumorstate',
                    'neu_rezidiv_datum' =>  $date
                ));

                $local = true;
            }

            if (str_contains($form['metastasis'], 'C22.0') === true) {
                $this->setField('neu_metast_leber', true);
                $new = $currentNew = true;
            }

            if (str_contains($form['metastasis'], 'C34') === true) {
                $this->setField('neu_metast_lunge', true);
                $new = $currentNew = true;
            }

            if (str_contains($form['metastasis'], array('C40', 'C41')) === true) {
                $this->setField('neu_metast_knochen', true);

                $this->addTimelineEntry('neu_metast_knochen', $date, array(
                    'type'  => 'tumorstate',
                    'datum' =>  $date
                ));

                $new = $currentNew = true;
            }

            if (strlen($form['metastasis']) > 0) {
                foreach (explode('#+#', $form['metastasis']) AS $code) {
                    if (str_contains($code, array('C22.0', 'C34', 'C40', 'C41')) === false) {
                        $this->setField('neu_metast_andere', true);
                        $new = $currentNew = true;

                        break;
                    }
                }
            }

            if ($currentNew === true) {
                $this->addTimelineEntry('metast', $date, array(
                    'type'             => 'tumorstate',
                    'neu_metast_datum' =>  $date
                ));
            }
        }

        if ($local === false) {
            $this->setField('neu_rezidiv_nein', true);
        }

        if ($new === false) {
            $this->setField('neu_metast_nein', true);
        }

        $this
            ->setField('neu_rezidiv_datum', todate($this->getTimelineField('rezidiv', 'neu_rezidiv_datum', true), 'de'))
            ->setField('neu_metast_datum', todate($this->getTimelineField('metast', 'neu_metast_datum', true), 'de'))
        ;

        $this->_tumorstateProcessed = true;

        return $this;
    }


    protected function _processNonInterventionalTherapy($prefix)
    {
        if ($this->_tumorstateProcessed === false) {
            die('tumorstate process MUST be called first!!! JCH');
        }

        parent::_processNonInterventionalTherapy($prefix);

        // bip special case
        if ($this->getField('neu_metast_knochen') === true) {
            $bisphosphonate = false;

            $bipData = $this->_loadNonInterventionalTherapy(false, true);

            foreach ($bipData as $form) {
                if (str_contains($form['agent'], 'biphosphonate') === true) {
                    $bisphosphonate = true;
                }
            }

            if ($bisphosphonate === true) {
                $this->setField('metast_bip_ja', true);
            } else {
                $this->setField('metast_bip_nein', true);
            }
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param bool $noStartDate
     * @param bool $bipCase
     * @return array|bool
     */
    protected function _loadNonInterventionalTherapy($noStartDate = false, $bipCase = false)
    {
        $where = $this->_getDateFilter('beginn', 'ts', $noStartDate);

        // if bip case was called it means that the "neu_metast_knochen" field is definitely set
        if ($bipCase === true) {
            $start = $this->getTimelineField('neu_metast_knochen', 'datum', true);
            $diseaseId  = $this->getFilter('diseaseId');
            $end        = $this->getFilter('end');

            $where = "ts.erkrankung_id = '{$diseaseId}' AND (ts.beginn > '{$start}' AND ts.beginn <= '{$end}')";
        }

        $where .= " AND ts.intention = 'kura'";

        return parent::_loadNonInterventionalTherapy($noStartDate, $where);
    }


    /**
     *
     *
     * @access
     * @return $this|mixed
     */
    protected function _process()
    {
        $this
            ->_processTumorstate()
            ->_processNonInterventionalTherapy('primaer')
            ->_processDiagnose()
        ;

        $this
            ->setField('doku_datum',          todate($this->getDocumentationDate(), 'de'))
            ->setField('einschreibung_grund', $this->getParam('einschreibung_grund'))
        ;

        return $this;
    }


    /**
     *
     *
     * @access
     * @return null
     */
    protected function _getFormId()
    {
        return $this->getParam('dmp_brustkrebs_fd_2013_id');
    }
}
