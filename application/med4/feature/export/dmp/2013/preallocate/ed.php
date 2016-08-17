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

class dmp2013PreallocateEd extends dmp2013PreallocateAbstract
{

    /**
     * @access
     * @var array
     *
     * values [(sn) dropped due 2 incompatibility]
     * copy form bz01 reportExtension
     */
    protected $_wsCheck = array(
        'pn'   => array('pN0','pN0(i+)','pN0(i-)','pN1mi','pN1','pN1a','pN1b','pN1c','pN2','pN2a','pN2b','pN3','pN3a','pN3b','pN3c'),
        'g'    => array('X','1','L','2','M','3','H','4'),
        'pt'   => array('pTis','pT1','pT1mic','pT1a','pT1b','pT1c','pT2','pT3','pT4a','pT4b','pT4c','pT4d','pT4')
    );


    /**
     * @access
     * @var array
     */
    protected $_tumorstates = array();


    /**
     * @param $db
     * @param $params
     */
    public function __construct($db, $params)
    {
        $this
            ->ignoreField('unterschrift_datum')
            ->ignoreField('fall_nr')
            ->ignoreField('einschreibung_grund')
            ->ignoreField('kv_iknr')
            ->ignoreField('kv_abrechnungsbereich')
            ->ignoreField('versich_nr')
            ->ignoreField('versich_status')
            ->ignoreField('versich_statusergaenzung')
            ->ignoreField('vk_gueltig_bis')
            ->ignoreField('aktueller_status')
            ->ignoreField('kvk_einlesedatum')
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
        $this
            ->setFilter('end', $this->getDocumentationDate())
            ->setFilter('diseaseId', $this->getParam('erkrankung_id'))
        ;

        $this->_prepareTreatmentRegime();

        return $this;
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
            ->_processDiagnose()
            ->_processOtherTherapy()
            ->_processTumorstate()
            ->_processNonInterventionalTherapy()
            ->_processWorstSide()
        ;

        $this
            ->setField('doku_datum', todate($this->getDocumentationDate(), 'de'))
        ;

        return $this;
    }


    /**
     *
     *
     * @access
     * @return array|bool
     */
    protected function _loadWorstSide()
    {
        $basicOrder     = 'ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1';
        $basicCondition = "FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.anlass = t.anlass";

        $patientId      = $this->getParam('patient_id');

        $query = "
            SELECT
                t.diagnose_seite AS 'seite',

                MAX(t.datum_sicherung)  AS 'datum_sicherung',

                (SELECT ts.t            {$basicCondition} AND LEFT(ts.t, 1) = 'p' {$basicOrder})        AS 'pt',
                (SELECT ts.n            {$basicCondition} AND LEFT(ts.n, 1) = 'p' {$basicOrder})        AS 'pn',
                (SELECT ts.diagnose     {$basicCondition} AND ts.diagnose IS NOT NULL {$basicOrder})    AS 'diagnose',
                (SELECT ts.morphologie  {$basicCondition} AND ts.morphologie IS NOT NULL {$basicOrder}) AS 'morphologie',
                (SELECT ts.r            {$basicCondition} AND ts.r IS NOT NULL {$basicOrder})           AS 'r',
                (SELECT ts.m            {$basicCondition} AND ts.m IS NOT NULL {$basicOrder})           AS 'm',
                (SELECT ts.g            {$basicCondition} AND ts.g IS NOT NULL {$basicOrder})           AS 'g',

                (SELECT ts.estro_urteil  {$basicCondition} AND ts.estro_urteil IS NOT NULL {$basicOrder}) AS 'estro_urteil',
                (SELECT ts.prog_urteil  {$basicCondition} AND ts.prog_urteil IS NOT NULL {$basicOrder}) AS 'prog_urteil',
                (SELECT ts.her2_urteil  {$basicCondition} AND ts.her2_urteil IS NOT NULL {$basicOrder}) AS 'her2_urteil',

                MIN(h.datum) AS 'min_histo_date'

            FROM patient p
                INNER JOIN tumorstatus t ON {$this->_getDateFilter('datum_sicherung', 't')} AND
                                            t.anlass = 'p' AND
                                            t.diagnose_seite IN ('R', 'L')

                LEFT JOIN histologie h ON {$this->_getDateFilter('datum', 'h')} AND h.diagnose_seite IN (t.diagnose_seite, 'B')
            WHERE p.patient_id = '{$patientId}'
            GROUP BY
                t.erkrankung_id,
                t.diagnose_seite
            ORDER BY NULL
        ";

        return sql_query_array($this->_db, $query);
    }


    /**
     *
     *
     * @access
     * @return mixed
     */
    protected function _processWorstSide()
    {
        $result = $this->_loadWorstSide();

        if (count($result) > 0) {
            $ws = reset($this->_getWorstSide($result));

            //mani_primaer
            if (strlen($ws['min_histo_date']) > 0) {
                $this->setField('mani_primaer', todate($ws['min_histo_date'], 'de'));
            }

            //mani kontra
            if (count($result) == 2) {
                $oppositeSide = $ws['seite'] == 'L' ? 'R' : 'L';

                foreach ($result as $entry) {
                    if ($entry['seite'] == $oppositeSide && strlen($entry['min_histo_date']) > 0) {
                        $this->setField('mani_kontra', todate($entry['min_histo_date'], 'de'));

                        break;
                    }
                }
            }

            // PT
            switch (true) {
                case str_starts_with($ws['pt'], 'pTis'): $this->setField('bef_pt_tis', true); break;
                case str_starts_with($ws['pt'], 'pT0'):  $this->setField('bef_pt_0', true);   break;
                case str_starts_with($ws['pt'], 'pT1'):  $this->setField('bef_pt_1', true);   break;
                case str_starts_with($ws['pt'], 'pT2'):  $this->setField('bef_pt_2', true);   break;
                case str_starts_with($ws['pt'], 'pT3'):  $this->setField('bef_pt_3', true);   break;
                case str_starts_with($ws['pt'], 'pT4'):  $this->setField('bef_pt_4', true);   break;
                case str_starts_with($ws['pt'], 'pTX'):  $this->setField('bef_pt_x', true);   break;
            }

            // PN
            switch (true) {
                case str_starts_with($ws['pn'], 'pN0'):  $this->setField('bef_pn_0', true); break;
                case str_starts_with($ws['pn'], 'pN1'):  $this->setField('bef_pn_1', true); break;
                case str_starts_with($ws['pn'], 'pN2'):  $this->setField('bef_pn_2', true); break;
                case str_starts_with($ws['pn'], 'pN3'):  $this->setField('bef_pn_3', true); break;
                case str_starts_with($ws['pn'], 'pNX'):  $this->setField('bef_pn_x', true); break;
            }

            //m
            switch (true) {
                case (str_contains($ws['m'], '0') === true): $this->setField('bef_m', '0'); break;
                case (str_contains($ws['m'], '1') === true): $this->setField('bef_m', '1'); break;
                case (str_contains($ws['m'], 'X') === true): $this->setField('bef_m', 'X'); break;
            }

            //g
            switch (true) {
                case ($ws['g'] == '1'):  $this->setField('bef_g', 1);   break;
                case ($ws['g'] == '2'):  $this->setField('bef_g', 2);   break;
                case ($ws['g'] == '3'):  $this->setField('bef_g', 3);   break;
                case ($ws['g'] == 'X'):  $this->setField('bef_g', 'X'); break;
            }

            //r
            switch (true) {
                case ($ws['r'] == '0'):  $this->setField('bef_r_0', true);          break;
                case ($ws['r'] == '1'):  $this->setField('bef_r_1', true);          break;
                case ($ws['r'] == '2'):  $this->setField('bef_r_2', true);          break;
                case ($ws['r'] == 'X'):  $this->setField('bef_r_unbekannt', true);  break;
            }

            // receptorstate
            if (in_array('p', array($ws['estro_urteil'], $ws['prog_urteil'])) === true) {
                $this->setField('bef_rezeptorstatus', 'p');
            } elseif (in_array('n', array($ws['estro_urteil'], $ws['prog_urteil'])) === true) {
                $this->setField('bef_rezeptorstatus', 'n');
            } else {
                $this->setField('bef_rezeptorstatus', 'u');
            }

            //her2
            if (strlen($ws['her2_urteil']) > 0) {
                $this->setField('bef_her2', $ws['her2_urteil']);
            } else {
                $this->setField('bef_her2', 'u');
            }

            $this->_processIntervention($ws);
        } else {
            $this->setField('bef_pn_keine', true);
            $this->setField('bef_pt_keine', true);
            $this->setField('bef_r_keine', true);
            $this->setField('anam_op_keine', true);
            $this->setField('bef_rezeptorstatus', 'u');
            $this->setField('bef_her2', 'u');
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $worstSide
     * @return $this
     */
    protected function _processIntervention($worstSide)
    {
        $intervention = false;

        $query = "
            SELECT
              e.art_primaertumor,
              e.art_rezidiv,
              e.art_lk,
              e.art_rekonstruktion,
              GROUP_CONCAT(DISTINCT eop.prozedur) as codes
            FROM eingriff e
              LEFT JOIN eingriff_ops eop ON eop.eingriff_id = e.eingriff_id AND eop.prozedur_seite IN ('{$worstSide['seite']}', 'B')
            WHERE {$this->_getDateFilter('datum', 'e')} AND e.diagnose_seite IN ('{$worstSide['seite']}', 'B')
            GROUP BY
                e.eingriff_id
        ";

        foreach (sql_query_array($this->_db, $query) as $entry) {
            if (in_array('1', array($entry['art_primaertumor'], $entry['art_rezidiv'])) === true &&
                str_contains($entry['codes'], array('5-870', '5-871')) === true
            ) {
                $this->setField('anam_op_bet', true);
            }

            if (in_array('1', array($entry['art_primaertumor'], $entry['art_rezidiv'])) === true &&
                str_contains($entry['codes'], array('5-872', '5-873', '5-874', '5-875', '5-876', '5-877')) === true
            ) {
                $this->setField('anam_op_mast', true);
            }

            if (in_array('1', array($entry['art_primaertumor'], $entry['art_rezidiv'])) === true &&
                str_contains($entry['codes'], array('5-401.1', '5-e21.y')) === true) {
                $this->setField('anam_op_sln', true);
            }

            if (in_array('1', array($entry['art_primaertumor'], $entry['art_rezidiv'])) === true &&
                str_contains($entry['codes'], array('5-402.1', '5-404.0', '5-406.1', '5-871', '5-873', '5-875.0', '5-875.1', '5-875.2')) === true) {
                $this->setField('anam_op_axilla', true);
            }

            if ($entry['art_rekonstruktion'] == '1') {
                $this->setField('anam_op_anderes', true);
            }

            if (in_array('1', array($entry['art_primaertumor'], $entry['art_rezidiv'], $entry['art_lk'], $entry['art_rekonstruktion'])) === true) {
                $intervention = true;
            }
        }

        if ($intervention === false) {
            $this->setField('anam_op_keine', true);
            $this->setField('bef_pt_keine', true);
            $this->setField('bef_pn_keine', true);
            $this->setField('bef_r_keine', true);
        }

        return $this;
    }


    /**
     * load Tumorstate datasets
     *
     * @access
     * @return $this
     */
    protected function _processTumorstate()
    {
        $sides = array();

        $data = $this->_loadTumorstate();

        foreach ($data as $form) {
            $date = $form['datum_sicherung'];

            $this->addTimelineEntry('mani', $date, array(
                'type'         => 'tumorstate',
                'rezidiv' => (strlen($form['rezidiv_lokal']) > 0 ? $date : null),
                'metast'  => (strlen($form['rezidiv_metastasen']) > 0 ? $date : null),
            ));

            if (str_contains($form['metastasis'], 'C22.0') === true) {
                $this->setField('metast_lok_leber', true);
            }

            if (str_contains($form['metastasis'], 'C34') === true) {
                $this->setField('metast_lok_lunge', true);
            }

            if (str_contains($form['metastasis'], array('C40', 'C41')) === true) {
                $this->setField('metast_lok_knochen', true);
            }

            if (strlen($form['metastasis']) > 0) {
                foreach (explode('#+#', $form['metastasis']) AS $code) {
                    if (str_contains($code, array('C22.0', 'C34', 'C40', 'C41')) === false) {
                        $this->setField('metast_lok_andere', true);

                        break;
                    }
                }
            }

            // side check
            if (strlen($side = $form['diagnose_seite']) > 0 && $side !== '-' && $form['anlass'] === 'p') {
                $sides[$side] = $side;
            }
        }

        if (count($sides) == 2) {
            $this->setField('anam_brust_beidseits', true);
        } else if (count($sides) == 1) {
            $side = reset($sides);

            if ($side == 'R') {
                $this->setField('anam_brust_rechts', true);
            } else {
                $this->setField('anam_brust_links', true);
            }
        }

        $this
            ->setField('mani_rezidiv', todate($this->getTimelineField('mani', 'rezidiv', true), 'de'))
            ->setField('mani_metast',  todate($this->getTimelineField('mani', 'metast', true), 'de'))
        ;

        $this->_tumorstateProcessed = true;

        return $this;
    }


    /**
     *
     *
     * @access
     * @return dmp2013PreallocateEd
     */
    protected function _processOtherTherapy()
    {
        $data = $this->_loadOtherTherapy();

        if (str_contains($data['intention'], array('kurna', 'palna')) === true) {
            $this->setField('bef_neoadjuvant', true);
        }

        return $this;
    }


    /**
     *
     * @access
     * @return $this
     */
    protected function _processNonInterventionalTherapy()
    {
        if ($this->_tumorstateProcessed === false) {
            die('tumorstate process MUST be called first!!! JCH');
        }

        $bisphosphonate = false;
        $neoadjuvant   = false;

        $data = parent::_processNonInterventionalTherapy();

        foreach ($data as $form) {
            if (str_contains($form['agent'], 'biphosphonate') === true) {
                $bisphosphonate = true;
            }

            if (str_contains($form['intention'], array('kurna', 'palna')) === true) {
                $neoadjuvant = true;
            }
        }

        if ($neoadjuvant === true) {
            $this->setField('bef_neoadjuvant', true);
        }

        if ($this->getField('metast_lok_knochen') === true) {
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
     * @return $this
     */
    protected  function _caseNumber()
    {
        $orgId = $this->getParam('org_id');

        $where = "org_id = '{$orgId}' AND dmp_nr_current != dmp_nr_end ORDER BY dmp_nr_current ASC";

        $lowestNr = dlookup($this->_db, 'dmp_nummern_2013', 'dmp_nr_current', $where);

        if (strlen($lowestNr) > 0) {
            $this->setField('fall_nr', $lowestNr);
        }

        if ($this->getField('fall_nr') === null) {
            $patientId = $this->getParam('patient_id');

            $this
                ->setField('fall_nr', dlookup($this->_db, 'patient', "patient_nr", "patient_id = '{$patientId}'"))
                ->setField('dmp_nr_pool_empty', true)
            ;
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $data
     * @return array
     */
    protected function _getWorstSide($data)
    {
        if (count($data) === 1) {
            return $data;
        }

        $firstSide  = reset($data);
        $secondSide = end($data);

        foreach ($this->_wsCheck as $check_value => $check_params) {
            foreach (array_reverse($check_params) as $check_param) {

                $firstVal  = trim(str_replace('(sn)', '', $firstSide[$check_value] ));
                $secondVal = trim(str_replace('(sn)', '', $secondSide[$check_value]));

                if ($firstVal == $check_param && $secondVal != $check_param) {
                    return array($firstSide['seite'] => $firstSide);
                }
                if ($firstVal != $check_param && $secondVal == $check_param) {
                    return array($secondSide['seite'] => $secondSide);
                }
                if ($firstVal == $check_param && $secondVal == $check_param) {
                    continue 2;
                }
            }
        }

        return array($firstSide['seite'] => $firstSide);
    }


    /**
     *
     *
     * @access
     * @return null
     */
    protected function _getFormId()
    {
        return $this->getParam('dmp_brustkrebs_ed_2013_id');
    }
}
