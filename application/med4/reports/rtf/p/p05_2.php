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

class reportContentP05_2 extends reportExtensionP
{
    /**
     * @access  protected
     * @var     array
     */
    protected $_relFields = array(
        'ges_meldung',
        'iciq_meldung',
        'iciq',
        'iciq_0',
        'iciq_5',
        'iciq_10',
        'iciq_11',
        'iciq_avg',
        'iief_meldung',
        'iief',
        'iief_g22',
        'iief_l22',
        'iief_avg',
        'leben_meldung',
        'leben_lq',
        'leben_gz',
        'leben_lq_avg',
        'leben_gz_avg'
    );


    /**
     *
     *
     * @access protected
     * @return array
     */
    protected function _initalizeMatrix()
    {
        $array = array('pr_pr' => 0);
        foreach (array('pr', 'fu') as $p) {
            foreach ($this->_relFields as $field) {
                $array["{$p}_{$field}"] = 0;
            }
        }

        return $array;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    public function generate()
    {
        if ($this->getParam('datumsbezug') === '0_erstvorstellung') {
            $this->setTemplate('p05_2a');
        } else {
            $this->setTemplate('p05_2b');
        }

        $bezugsjahr = $this->getParam('jahr');
        $bezug      = substr($this->getParam('datumsbezug'), 0, 1);

        $queryJahr = $bezugsjahr - $bezug;
        $folgeJahr = $queryJahr + 3;

        $additionalContent['condition'] = "anlass = 'p'";

        $additionalContent['fields'] = array(
            "GROUP_CONCAT(IF(YEAR(n.datum) = {$folgeJahr} AND n.iciq_ui IS NOT NULL, CONCAT_WS('|', n.datum, n.iciq_ui),   NULL) SEPARATOR ',') AS 'nachsorge_iciqui'",
            "GROUP_CONCAT(IF(YEAR(n.datum) = {$folgeJahr} AND n.iief5   IS NOT NULL, CONCAT_WS('|', n.datum, n.iief5),     NULL) SEPARATOR ',') AS 'nachsorge_iief5'",
            "GROUP_CONCAT(IF(YEAR(n.datum) = {$folgeJahr} AND n.lq_dkg  IS NOT NULL, CONCAT_WS('|', n.datum, n.lq_dkg),    NULL) SEPARATOR ',') AS 'nachsorge_lq_dkg'",
            "GROUP_CONCAT(IF(YEAR(n.datum) = {$folgeJahr} AND n.gz_dkg  IS NOT NULL, CONCAT_WS('|', n.datum, n.gz_dkg),    NULL) SEPARATOR ',') AS 'nachsorge_gz_dkg'"
        );

        $datasets = $this->loadRessource('p01', $additionalContent);

        $matrix = $this->_initalizeMatrix();

        $matrix['bezugsjahr']     = $queryJahr;
        $matrix['auditjahr']      = $queryJahr + 1;
        $matrix['folgejahr']      = $folgeJahr;
        $matrix['folgeauditjahr'] = $folgeJahr + 1;

        foreach ($datasets as $dataset) {

            if (substr($dataset['bezugsdatum'], 0, 4) == $queryJahr  && $this->_cpz_ges($dataset) == '1') {

                $count_in_aftercare = false;

                $matrix['pr_pr']++;

                $cond1 = strlen($dataset['iciq_ui_gesamtscore_prae']) > 0;
                $cond2 = strlen($dataset['iief_5_score_prae']) > 0;
                $cond3 = strlen($dataset['lebensqualitaet_lq_prae']) > 0 || strlen($dataset['gesundheitszustand_gz_prae']) > 0;

                if ($cond1 == true || $cond2 == true || $cond3 == true) {
                    $matrix['pr_ges_meldung'] ++;
                    $count_in_aftercare = true;
                }

                if ($cond1 == true) {
                    $iciq_ui = (int) $dataset['iciq_ui_gesamtscore_prae'];
                    $matrix['pr_iciq_meldung']    ++;
                    $matrix['pr_iciq']            += $iciq_ui;
                    $matrix['pr_iciq_0']          += $iciq_ui == 0 ? 1 : 0;
                    $matrix['pr_iciq_5']          += $iciq_ui >= 1 && $iciq_ui <= 5  ? 1 : 0;
                    $matrix['pr_iciq_10']         += $iciq_ui >= 6 && $iciq_ui <= 10 ? 1 : 0;
                    $matrix['pr_iciq_11']         += $iciq_ui >= 11 ? 1 : 0;
                }

                if ($cond2 == true) {
                    $iief5 = (int) $dataset['iief_5_score_prae'];
                    $matrix['pr_iief_meldung']    ++;
                    $matrix['pr_iief']            += $iief5;
                    $matrix['pr_iief_g22']        += $iief5 >= 22 ? 1 : 0;
                    $matrix['pr_iief_l22']        += $iief5 < 22 ? 1 : 0;
                }

                if ($cond3 == true) {
                    $matrix['pr_leben_meldung']   ++;
                }

                if (strlen($dataset['lebensqualitaet_lq_prae']) > 0) {
                    $matrix['pr_leben_lq']        += $dataset['lebensqualitaet_lq_prae'];
                    $matrix['pr_leben_lq_avg']    ++;
                }

                if (strlen($dataset['gesundheitszustand_gz_prae']) > 0) {
                    $matrix['pr_leben_gz']        += $dataset['gesundheitszustand_gz_prae'];
                    $matrix['pr_leben_gz_avg']    ++;
                }

                //nur nach nachsorgedaten gucken, wenn als bezug erstdiagnosedatum gewählt wurde
                if ($folgeJahr <= date('Y') && $count_in_aftercare == true) {

                    $cond4 = strlen($dataset['nachsorge_iciqui']) > 0;
                    $cond5 = strlen($dataset['nachsorge_iief5']) > 0;
                    $cond6 = strlen($dataset['nachsorge_lq_dkg']) > 0 || strlen($dataset['nachsorge_gz_dkg']) > 0;

                    if ($cond4 == true || $cond5 == true || $cond6 == true) {
                        $matrix['fu_ges_meldung'] ++;
                    }

                    if ($cond4 == true) {
                        $iciq_ui = (int) $this->_getLastNachsorgeValue($dataset['nachsorge_iciqui']);
                        $matrix['fu_iciq_meldung']    ++;
                        $matrix['fu_iciq']            += $iciq_ui;
                        $matrix['fu_iciq_0']          += $iciq_ui === 0 ? 1 : 0;
                        $matrix['fu_iciq_5']          += $iciq_ui >= 1 && $iciq_ui <= 5  ? 1 : 0;
                        $matrix['fu_iciq_10']         += $iciq_ui >= 6 && $iciq_ui <= 10 ? 1 : 0;
                        $matrix['fu_iciq_11']         += $iciq_ui >= 11 ? 1 : 0;
                    }

                    if ($cond5 == true) {
                        $iief5 = (int) $this->_getLastNachsorgeValue($dataset['nachsorge_iief5']);
                        $matrix['fu_iief_meldung']    ++;
                        $matrix['fu_iief']            += $iief5;
                        $matrix['fu_iief_g22']        += $iief5 >= 22 ? 1 : 0;
                        $matrix['fu_iief_l22']        += $iief5 < 22 ? 1 : 0;
                    }

                    $matrix['fu_leben_meldung']   += $cond6 == true ? 1 : 0;

                    if (strlen($dataset['nachsorge_lq_dkg']) > 0) {
                        $matrix['fu_leben_lq']        += $this->_getLastNachsorgeValue($dataset['nachsorge_lq_dkg']);
                        $matrix['fu_leben_lq_avg']    ++;
                    }

                    if (strlen($dataset['nachsorge_gz_dkg']) > 0) {
                        $matrix['fu_leben_gz']        += $this->_getLastNachsorgeValue($dataset['nachsorge_gz_dkg']);
                        $matrix['fu_leben_gz_avg']    ++;
                    }
                } else {
                    foreach ($this->_relFields as $field) {
                        $matrix["{fu_$field"] = '-';
                    }
                }
            }
        }

        $matrix['pr_iciq_avg']     = $matrix['pr_iciq_meldung'] > 0 ? round($matrix['pr_iciq'] / $matrix['pr_iciq_meldung'],1) : 0;
        $matrix['pr_iief_avg']     = $matrix['pr_iief_meldung'] > 0 ? round($matrix['pr_iief'] / $matrix['pr_iief_meldung'],1) : 0;
        $matrix['pr_leben_lq_avg'] = $matrix['pr_leben_lq_avg'] > 0 ? round($matrix['pr_leben_lq'] / $matrix['pr_leben_lq_avg'],1) : 0;
        $matrix['pr_leben_gz_avg'] = $matrix['pr_leben_gz_avg'] > 0 ? round($matrix['pr_leben_gz'] / $matrix['pr_leben_gz_avg'],1) : 0;

        if ($folgeJahr <= date('Y')) {
            $matrix['fu_iciq_avg']       = $matrix['fu_iciq_meldung'] > 0 ? round($matrix['fu_iciq'] / $matrix['fu_iciq_meldung'],1) : 0;
            $matrix['fu_iief_avg']       = $matrix['fu_iief_meldung'] > 0 ? round($matrix['fu_iief'] / $matrix['fu_iief_meldung'],1) : 0;
            $matrix['fu_leben_lq_avg']   = $matrix['fu_leben_lq_avg'] > 0 ? round($matrix['fu_leben_lq'] / $matrix['fu_leben_lq_avg'],1) : 0;
            $matrix['fu_leben_gz_avg']   = $matrix['fu_leben_gz_avg'] > 0 ? round($matrix['fu_leben_gz'] / $matrix['fu_leben_gz_avg'],1) : 0;
        }

        $this->_data = $matrix;

        $this->writePDF(true);
    }


    /**
     * _getLastNachsorgeValue
     *
     * @access  protected
     * @param   string $string
     * @return  string
     */
    protected function _getLastNachsorgeValue($string = '')
    {
        $return = '';

        if (strlen($string) > 0) {
            $nachsorgeForms = explode(',', $string);

            $values = array();

            foreach ($nachsorgeForms as $form) {
                $data = explode('|', $form);

                $date    = reset($data);
                $value   = end($data);

                $values[$date][] = $value;
            }

            krsort($values);

            if (count($values) > 0) {
                $nachsorgeValues = reset($values);

                if (count($nachsorgeValues) > 0) {
                    rsort($nachsorgeValues);

                    $return = reset($nachsorgeValues);
                }
            }
        }

        return $return;
    }
}
?>
