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

class qs181Mapping2014 extends qs181MappingAbstract
{
    /**
     * @access
     * @var null
     */
    protected $_accommodation = null;


    /**
     * for 2013
     *
     * @access
     * @var array
     */
    protected $_inclusionOpsCodes = array(
        '5-401.10','5-401.11','5-401.12','5-401.13','5-401.1x','5-402.10','5-402.11','5-402.12','5-402.13',
        '5-402.1x','5-404.00','5-404.01','5-404.02','5-404.03','5-404.0x','5-406.10','5-406.11','5-406.12',
        '5-406.13','5-406.1x','5-407.00','5-407.01','5-407.02','5-407.03','5-407.0x','5-870.20','5-870.21',
        '5-870.60','5-870.61','5-870.90','5-870.91','5-870.a0','5-870.a1','5-870.a2','5-870.a3','5-870.a4',
        '5-870.a5','5-870.a6','5-870.a7','5-870.ax','5-870.x','5-870.y','5-872.0','5-872.1','5-872.x','5-872.y',
        '5-874.0','5-874.1','5-874.2','5-874.4','5-874.5','5-874.6','5-874.7','5-874.8','5-874.x','5-874.y',
        '5-877.0','5-877.10','5-877.11','5-877.12','5-877.1x','5-877.20','5-877.21','5-877.22','5-877.2x',
        '5-877.x','5-877.y','5-879.0','5-879.x','5-879.y','5-882.1'
    );


    /**
     *
     *
     * @access
     * @return $this
     */
    protected function _processBaseForm()
    {
        $baseForm = array(
            'patient_id'    => $this->getParam('patientId'),
            'erkrankung_id' => $this->getParam('diseaseId'),
            'aufenthalt_id' => $this->getParam('abodeId'),
            'idnrpat'       => $this->getParam('abodeNr'),
            'aufndatum'     => $this->getParam('abodeStart'),
            'entldatum'     => $this->getParam('abodeEnd'),
            'meldungkrebsregister' => 0, // default
            'adjutherapieplanung' => 0  // default
        );

        $tumorstate = $this->_loadTumorstate($this->getParam('abodeStart'), $this->getParam('abodeEnd'));

        if ($tumorstate !== null) {
            $firstTumorstate = reset($tumorstate);

            $side = $firstTumorstate['diagnose_seite'];
            $case = $firstTumorstate['anlass'];

            if (in_array($side, array('R', 'L')) === true) {
                $baseForm['aufndiag_1']         = $firstTumorstate['diagnose'];
                $baseForm['aufndiag_1_version'] = $firstTumorstate['diagnose_version'];
                $baseForm['aufndiag_1_text']    = $firstTumorstate['diagnose_text'];

                $earliestOppositeTumorstate = $this->findRecord($tumorstate,
                    "record->anlass == '{$case}' && in_array(record->diagnose_seite, array('R', 'L')) === true && record->diagnose_seite != '{$side}'"
                );

                if ($earliestOppositeTumorstate !== null) {
                    $baseForm['aufndiag_2']         = $earliestOppositeTumorstate['diagnose'];
                    $baseForm['aufndiag_2_version'] = $earliestOppositeTumorstate['diagnose_version'];
                    $baseForm['aufndiag_2_text']    = $earliestOppositeTumorstate['diagnose_text'];
                }

                $caseRecord = $this->findRecord($this->_situation['raw'], "record->diagnose_seite == '{$side}' && record->anlass == '{$case}'");

                if (str_starts_with($caseRecord['anlass'], 'r') === true) {
                    $startDate = $caseRecord['start_date'] == '0000-00-00' ? $caseRecord['start_date'] : $caseRecord['start_date_rezidiv'];
                } else {
                    $startDate = $caseRecord['start_date'];
                }

                $this->_accommodation = array(
                    'case'      => $case,
                    'side'      => $side,
                    'startDate' => $startDate,
                    'endDate'   => $this->getParam('abodeEnd')
                );
            }
        }

        $diagnose = $this->_loadDiagnose($this->getParam('abodeStart'), $this->getParam('abodeEnd'));

        if ($diagnose !== null) {
            foreach ($diagnose as $record) {
                for ($i=1; $i <= 5; $i++) {
                    if (array_key_exists("aufndiag_{$i}", $baseForm) === false) {
                        $baseForm["aufndiag_{$i}"] = $record['diagnose'];
                        $baseForm["aufndiag_{$i}_version"] = $record['diagnose_version'];
                        $baseForm["aufndiag_{$i}_text"] = $record['diagnose_text'];

                        break;
                    }
                }
            }
        }

        if (($therapy = $this->_loadTherapy($this->getParam('abodeStart'), $this->getParam('abodeEnd'))) !== null) {
            foreach ($therapy as $record) {
                if (strlen($record['asa']) > 0) {
                    if ($record['asa'] != '6') {
                        $baseForm['asa'] = $record['asa'];
                    }

                    break;
                }
            }
        }

        if ($this->_accommodation !== null) {
            $conferences   = $this->_loadConference($this->_accommodation['startDate'], $this->_accommodation['endDate']);
            $therapyRegime = $this->_loadTherapyRegime($this->_accommodation['startDate'], $this->_accommodation['endDate']);

            $conferenceRecord    = $this->findRecord($conferences, "record->art == 'post'");
            $therapyRegimeRecord = $this->findRecord($therapyRegime, "record->grundlage == 'tuk' && record->zeitpunkt == 'post'");

            if ($conferenceRecord !== null || $therapyRegimeRecord !== null) {
                $baseForm['adjutherapieplanung'] = 1;
            }
        }

        if ($this->_loadEkr() !== null) {
            $baseForm['meldungkrebsregister'] = 1;
        }

        if ($this->_accommodation !== null) {
            $revertedTumorstates = array_reverse($tumorstate);

            $latestTumorstate = $this->findRecord($revertedTumorstates,
                "record->anlass == '{$this->_accommodation['case']}' && in_array(record->diagnose_seite, array('R', 'L')) === true && record->diagnose_seite == '{$this->_accommodation['side']}'"
            );

            $baseForm['entldiag_1']         = $latestTumorstate['diagnose'];
            $baseForm['entldiag_1_version'] = $latestTumorstate['diagnose_version'];
            $baseForm['entldiag_1_text']    = $latestTumorstate['diagnose_text'];

            $latestOppositeTumorstate = $this->findRecord($revertedTumorstates,
                "record->anlass == '{$this->_accommodation['case']}' && in_array(record->diagnose_seite, array('R', 'L')) === true && record->diagnose_seite != '{$this->_accommodation['side']}'"
            );

            if ($latestOppositeTumorstate !== null) {
                $baseForm['aufndiag_2']         = $latestOppositeTumorstate['diagnose'];
                $baseForm['aufndiag_2_version'] = $latestOppositeTumorstate['diagnose_version'];
                $baseForm['aufndiag_2_text']    = $latestOppositeTumorstate['diagnose_text'];
            }
        }

        if ($diagnose !== null) {
            $revertedDiagnose = array_reverse($diagnose);

            foreach ($revertedDiagnose as $record) {
                for ($i=1; $i <= 3; $i++) {
                    if (array_key_exists("entldiag_{$i}", $baseForm) === false) {
                        $baseForm["entldiag_{$i}"] = $record['diagnose'];
                        $baseForm["entldiag_{$i}_version"] = $record['diagnose_version'];
                        $baseForm["entldiag_{$i}_text"] = $record['diagnose_text'];

                        break;
                    }
                }
            }
        }

        if (($conclusion = $this->_loadConclusion()) !== null) {
            if ($conclusion['abschluss_grund'] == 'tot' && $conclusion['autopsie'] == '1') {
                $baseForm['sektion'] = 1;
            } else if ($conclusion['abschluss_grund'] == 'tot' && $conclusion['autopsie'] == '0') {
                $baseForm['sektion'] = 0;
            }
        }

        $this->_addForm('b', $baseForm);

        return $this;
    }


    /**
     *
     *
     * @access
     * @return $this
     */
    protected function _processBreastForm()
    {
        foreach ($this->_situation as $side => $situation) {
            if ($side == 'raw') continue;

            if (count($situation) > 0) {
                $breastForm = array(
                    'patient_id'        => $this->getParam('patientId'),
                    'erkrankung_id'     => $this->getParam('diseaseId'),
                    'zuopseite'         => $side,

                    //default
                    'praehistdiagsicherung' => 0,
                    'praethinterdisztherapieplan' => 0,
                    'praeoptumorth' => 0,
                    'pokomplikatspez' => 0,
                    'bet' => 0,
                    'axlkentfomark' => 0,
                    'slkbiopsie' => 0,
                    'anzahllypmphknotenunb' => 1
                );

                $this
                    ->_processBreastFormAnamnesis($breastForm)
                    ->_processBreastFormHistology($breastForm, $side)
                ;

                if ($this->_accommodation !== null) {
                    $conferences   = $this->_loadConference($this->_accommodation['startDate'], $this->_accommodation['endDate']);
                    $therapyRegime = $this->_loadTherapyRegime($this->_accommodation['startDate'], $this->_accommodation['endDate']);


                    if ($conferences !== null || $therapyRegime !== null) {
                        $conferences     = is_array($conferences) === true ? $conferences : array();
                        $therapyRegime   = is_array($therapyRegime) === true ? $therapyRegime : array();

                        foreach (array_merge($conferences, $therapyRegime) as $record) {
                            if ($record['praethinterdisztherapieplan'] == '1') {
                                $breastForm['praethinterdisztherapieplan'] = 1;
                            }

                            if (array_key_exists('datumtherapieplan', $breastForm) === false) {
                                $breastForm['datumtherapieplan'] = $record['datum'];
                            } elseif ($breastForm['datumtherapieplan'] < $record['datum']) {
                                $breastForm['datumtherapieplan'] = $record['datum'];
                            }
                        }
                    }

                    $case = $this->_accommodation['case'];

                    switch (true) {
                        case ($case == 'p'):

                            $breastForm['arterkrank'] = '1';

                            break;
                        case (str_starts_with($case, 'r')):

                            $tumorstate = $this->_loadTumorstate($this->_accommodation['startDate'], $this->getParam('abodeEnd'), $side);

                            if ($tumorstate !== null) {
                                $revertedTumorstate = array_reverse($tumorstate);

                                $lastTumorstate = reset($revertedTumorstate);

                                if ($lastTumorstate['rezidiv_lokal'] == '1') {
                                    $therapy = $this->_loadTherapy('1900-01-01', $this->getParam('abodeEnd'), $side);

                                    if ($therapy !== null) {
                                        $bet = false;
                                        $mastektomie = false;

                                        foreach ($therapy as $record) {
                                            if (str_contains($record['ops'], array('5-870', '5-871')) === true) {
                                                $bet = true;
                                            }

                                            if (str_contains($record['ops'], array('5-872', '5-877')) === true) {
                                                $mastektomie = true;
                                            }
                                        }

                                        if ($bet === true && $mastektomie === false) {
                                            $breastForm['arterkrank'] = '2';
                                        } elseif ($mastektomie === true) {
                                            $breastForm['arterkrank'] = '3';
                                        }
                                    }
                                }
                            }

                            break;
                    }

                    $this
                        ->_processBreastFormNonInterventionalTherapy($breastForm)
                        ->_processBreastFormComplications($breastForm, $side)
                        ->_processBreastFormTumorstate($breastForm, $side)
                        ->_processBreastFormTherapy($breastForm, $side)
                    ;
                }

                $this->_addForm('brust', $breastForm, $side);
            }
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @return array|null
     */
    protected function _getSortedTherapies()
    {
        $sortedTherapies = array();

        $therapies = $this->_loadTherapy($this->_accommodation['startDate'], $this->getParam('abodeEnd'));

        if ($therapies !== null) {
            $sortedTherapies = array(
                'L' => array(),
                'R' => array()
            );

            //sort forms
            foreach ($therapies as $therapy) {
                if ($therapy['inclusionCriteria'] == '1') {
                    $side = $therapy['diagnose_seite'];
                    $date = $therapy['datum'];

                    if ($side !== 'B') {
                        $sortedTherapies[$side][$date][] = $therapy;
                    } else {
                        $therapy['diagnose_seite'] = 'L';
                        $sortedTherapies['L'][$date][] = $therapy;

                        $therapy['diagnose_seite'] = 'R';
                        $sortedTherapies['R'][$date][] = $therapy;
                    }
                }
            }
        }

        return count($sortedTherapies) > 0 ? $sortedTherapies : null;
    }


    /**
     *
     *
     * @access
     * @return $this
     */
    protected function _processOpForm()
    {
        if ($this->_accommodation !== null) {
            $sortedTherapies = $this->_getSortedTherapies();

            if ($sortedTherapies !== null) {
                $counter = array(
                    'L' => 1,
                    'R' => 1
                );

                foreach ($sortedTherapies as $side => $therapySections) {
                    if ($this->_forms['brust'][$side] == null) {
                        continue;
                    }

                    ksort($therapySections);

                    foreach ($therapySections as $therapyDate => $therapyRecords) {
                        foreach ($therapyRecords as $record) {
                            $opForm = array(
                                'lfdnreingriff'      => $counter[$side],
                                'patient_id'         => $this->getParam('patientId'),
                                'erkrankung_id'      => $this->getParam('diseaseId'),
                                'opdatum'            => $record['datum'],
                                'praeopmarkierung'   => null,
                                'sentinellkeingriff' => '0'
                            );

                            switch ($record['mark']) {
                                case '0':
                                case '1om':
                                    $opForm['praeopmarkierung'] = '0';

                                    break;

                                case '1d':
                                    $opForm['praeopmarkierung'] = '1';

                                    break;
                            }

                            if (strlen($record['mark_mammo']) > 0) {
                                $opForm['praeopmammographiejl'] = '1';
                            }

                            $opForm['intraoppraeparatroentgen'] = $record['intraop_roe'];

                            if (strlen($record['mark_sono']) > 0) {
                                $opForm['praeopsonographiejl'] = '1';
                            }

                            $opForm['intraoppraeparatsono'] = $record['intraop_sono'];

                            if ($opForm['praeopmarkierung'] == '1' && strlen($record['mark_mrt']) > 0) {
                                $opForm['praeopmrtjl'] = '1';
                            }

                            $opsProcedureRecords = explode($this->_separator['opSection'], $record['ops']);

                            $queue  = array();
                            $ops    = array();

                            foreach ($opsProcedureRecords as $i => $opsProcedureRecord) {
                                $opsProcedureParts = explode($this->_separator['opParts'], $opsProcedureRecord);

                                $code = $opsProcedureParts[0];

                                if (str_contains($code, '-e') === true) {
                                    continue;
                                }

                                $data = array(
                                    'code'    => $code,
                                    'side'    => $opsProcedureParts[1],
                                    'version' => $opsProcedureParts[2],
                                    'text'    => $opsProcedureParts[3]
                                );

                                if (in_array($data['code'], $this->_inclusionOpsCodes) === true) {
                                    $ops[] = $data;
                                } else {
                                    $queue[] = $data;
                                }
                            }

                            $finalOpsQueue = array_merge($ops, $queue);

                            foreach ($finalOpsQueue as $i => $data) {
                                $i++;

                                $opsSide = $data['side'] === 'B' ? $side : $data['side'];

                                if ($opsSide === $side) {
                                    if ($i <= 6) {
                                        $opForm["opschluessel_{$i}"]         = $data['code'];
                                        $opForm["opschluessel_{$i}_seite"]   = $opsSide;
                                        $opForm["opschluessel_{$i}_version"] = $data['version'];
                                        $opForm["opschluessel_{$i}_text"]    = $data['text'];
                                    }
                                }
                            }

                            if (str_contains($record['ops'], array('5-401.11', '5-401.12', '5-401.13')) === true) {
                                $opForm['sentinellkeingriff'] = null;
                            } else {
                                if (str_contains($record['ops'], array('5-e21.y', '1-e01.x')) === true) {
                                    $opForm['sentinellkeingriff'] = '1';
                                }
                            }

                            $opForm['antibioprph'] = $record['antibiotikaprophylaxe'];

                            $this->_addForm('o', $opForm, $side);

                            $counter[$side]++;
                        }
                    }
                }
            }
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $breastForm
     * @return $this
     */
    protected function _processBreastFormAnamnesis(&$breastForm)
    {
        //Default
        $breastForm['anlasstumordiag'] = 0;

        $anamnesisRecords = $this->_loadAnamnesis($this->_accommodation['startDate'], $this->getParam('abodeEnd'));

        if ($anamnesisRecords !== null) {
            foreach ($anamnesisRecords as $record) {
                if (strlen($record['entdeckung']) > 0) {
                    $breastForm['anlasstumordiag'] = 1;
                }
            }

            $earliestRecord = reset($anamnesisRecords);

            switch ($earliestRecord['entdeckung']) {
                case 'su':
                    $breastForm['anlasstumordiageigen'] = 1;
                    break;

                case 'ts':
                    $breastForm['anlasstumordiagsympt'] = 1;
                    break;

                case 'ns':
                    $breastForm['anlasstumordiagnachsorge'] = 1;
                    break;

                case 'ze':
                    $breastForm['anlasstumordiagsonst'] = 1;
                    break;

                case 'gf':
                case 'nv':
                case 'sc':
                    if ($earliestRecord['entdeckung'] == 'sc') {
                        $breastForm['mammographiescreening'] = 1;
                    } else {
                        $breastForm['mammographiescreening'] = 0;
                    }

                    $breastForm['anlasstumordiagfrueh'] = 1;

                    break;
            }
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $breastForm
     * @param $side
     * @return $this
     */
    protected function _processBreastFormHistology(&$breastForm, $side)
    {
        $histology = $this->_loadHistology($this->_accommodation['startDate'], $this->getParam('abodeEnd'), $side);

        if ($histology !== null) {
            foreach ($histology as $record) {
                if ($record['art'] == 'pr' && $record['diagnosesicherung'] == '1') {
                    $breastForm['praehistdiagsicherung'] = 1;

                    $morphologie = $record['morphologie'];

                    if (strlen($morphologie) > 0) {
                        $breastForm['ausganghistbefund'] = $record['datum'];
                        $breastForm['praeicdo3'] = $morphologie;
                    }
                }

                if ($record['art'] == 'po') {
                    $morphologie = $record['morphologie'];

                    if (strlen($morphologie) > 0) {
                        $breastForm['posticdo3'] = $morphologie;
                    }
                }
            }
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $breastForm
     * @param $side
     * @return $this
     */
    protected function _processBreastFormTumorstate(&$breastForm, $side)
    {
        //Default
        $breastForm['mnachstaging'] = 9;
        $breastForm['angabensicherabstand'] = 0;
        $breastForm['multizentrizitaet'] = 0;

        $tumorstate = $this->_loadTumorstate($this->_accommodation['startDate'], $this->_accommodation['endDate'], $side);

        if ($tumorstate !== null) {
            $diagnose       = '';
            $morphologie    = '';
            $grading        = '';
            $size           = array('x' => null, 'y' => null, 'z' => null);
            $estro          = null;
            $prog           = null;
            $her2           = null;
            $m              = '';
            $t              = '';
            $y              = null;
            $resectionR     = null;

            foreach ($tumorstate as $record) {
                $y = str_contains($record['tnm_praefix'], 'y') === true ? 'y' : null;
                $t = $record['t'];

                if (str_starts_with($t, 'pT') === true) {
                    $breastForm['tnmptmamma'] = $y . $record['t'];
                }

                if (str_starts_with($record['n'], 'pN') === true) {
                    $breastForm['tnmpnmamma'] = $y . $record['n'];
                }

                if (strlen($record['lk_entf']) > 0) {
                    $breastForm['anzahllypmphknoten'] = $record['lk_entf'];
                    unset($breastForm['anzahllypmphknotenunb']);
                }

                $diagnose    = $record['diagnose'];
                $morphologie = $record['morphologie'];
                $grading     = $record['g'];

                $size = array(
                    'x' => strlen($record['groesse_x']) > 0 ? $record['groesse_x'] : null,
                    'y' => strlen($record['groesse_y']) > 0 ? $record['groesse_y'] : null,
                    'z' => strlen($record['groesse_z']) > 0 ? $record['groesse_z'] : null
                );

                $estro = $record['estro_urteil'];
                $prog  = $record['prog_urteil'];
                $her2  = $record['her2_urteil'];

                if ($record['multizentrisch'] == '1') {
                    $breastForm['multizentrizitaet'] = 1;
                }

                $resectionR = $record['resektionsrand'];

                if (strlen($record['m']) > 0) {
                    $m = $record['m'];
                }
            }

            //DCIS / last tumorstate
            if (str_starts_with($diagnose, array('D05.1', 'D05.7', 'D05.9')) === true &&
                str_ends_with($morphologie, '/2') === true && $morphologie != '8520/2'
            ) {
                if (strlen($grading) > 0) {
                    $breastForm['graddcis'] = 'G' . $grading;
                }

                $breastForm['gesamttumorgroesse'] = max($size);
            }

            //Invasive Carzinome
            if (str_starts_with($diagnose, array('C50')) === true && str_ends_with($morphologie, '/3') === true) {
                $breastForm['rezeptorstatus'] = 9;
                $breastForm['her2neustatus'] = 9;

                if (strlen($grading) > 0) {
                    $breastForm['tnmgmamma'] = $grading;
                }

                if (in_array('p', array($estro, $prog)) === true) {
                    $breastForm['rezeptorstatus'] = 1;
                } else if ($estro == 'n' && $prog == 'n') {
                    $breastForm['rezeptorstatus'] = 0;
                }

                if ($her2 == 'p') {
                    $breastForm['her2neustatus'] = 1;
                } else if ($her2 == 'n') {
                    $breastForm['her2neustatus'] = 0;
                }
            }

            if (str_ends_with($m, '0') === true) {
                $breastForm['mnachstaging'] = 0;
            } elseif(str_ends_with($m, '1') === true) {
                $breastForm['mnachstaging'] = 1;
            }

            if ($resectionR !== null && strlen($resectionR) > 0) {
                $breastForm['angabensicherabstand'] = 1;
                $breastForm['sicherabstand'] = floor($resectionR);
            } else if ($y !== null && str_ends_with($t, '0') === true) {
                $breastForm['angabensicherabstand'] = 2;
            }
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $breastForm
     * @return $this
     */
    protected function _processBreastFormNonInterventionalTherapy(&$breastForm)
    {
        $noInTh = $this->_loadNonInterventionalTherapy($this->_accommodation['startDate'], $this->getParam('abodeEnd'));

        if ($noInTh !== null) {
            foreach ($noInTh as $record) {
                if (in_array($record['intention'], array('kurna', 'palna')) === true) {
                    $breastForm['praeoptumorth'] = 1;

                    if (in_array($record['vorlage_therapie_art'], array('ci', 'cst', 'c')) === true) {
                        $breastForm['systchemoth'] = 1;
                    }

                    if (in_array($record['vorlage_therapie_art'], array('ah', 'ahst')) === true) {
                        $breastForm['endokrinth'] = 1;
                    }

                    if (in_array($record['vorlage_therapie_art'], array('ci', 'ist', 'i')) === true) {
                        $breastForm['spezifantiktherapie'] = 1;
                    }

                    if (in_array($record['vorlage_therapie_art'], array('st', 'cst', 'ist', 'ahst')) === true) {
                        $breastForm['strahlenth'] = 1;
                    }

                    if ($record['type'] == 'sonstige_therapie') {
                        $breastForm['sonstth'] = 1;
                    }
                }
            }
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $breastForm
     * @param $side
     * @return $this
     */
    protected function _processBreastFormComplications(&$breastForm, $side)
    {
        $complications = $this->_loadComplications($this->_accommodation['startDate'], $this->getParam('abodeEnd'), $side);

        if ($complications !== null) {
            $poko = array('wa1', 'wa2', 'wa3');
            $nach = array('blut', 'blutv', 'nbl', 'opn');
            $ser  = array('sal', 'sth', 'sha');

            foreach ($complications as $record) {
                if ($record['pokomplikatspez'] == '1') {
                    $breastForm['pokomplikatspez'] = 1;
                }

                if (in_array($record['komplikation'], $poko) === true) {
                    $breastForm['pokowundinfektion'] = 1;
                }

                if (in_array($record['komplikation'], $nach) === true) {
                    $breastForm['nachblutung'] = 1;
                }

                if (in_array($record['komplikation'], $ser) === true) {
                    $breastForm['serom'] = 1;
                }

                if (in_array($record['komplikation'], array_merge($poko, $nach, $ser)) === false) {
                    $breastForm['pokosonst'] = 1;
                }
            }
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $breastForm
     * @param $side
     * @return $this
     */
    protected function _processBreastFormTherapy(&$breastForm, $side)
    {
        $therapy = $this->_loadTherapy($this->_accommodation['startDate'], $this->getParam('abodeEnd'), $side);

        $axillaOPS = array('5-402.1', '5-404.0', '5-406.1', '5-407.02', '5-871', '5-873', '5-875.0', '5-875.1', '5-875.2');
        $biopsOps  = array('5-401.11', '5-401.12', '5-e21.y');

        if ($therapy !== null) {
            $bet = false;
            $mastektomie = false;

            foreach ($therapy as $record) {
                if (str_contains($record['ops'], array('5-870', '5-871')) === true) {
                    $bet = true;
                }

                if (str_contains($record['ops'], array('5-872', '5-877')) === true) {
                    $mastektomie = true;
                }

                if (str_contains($record['ops'], $biopsOps) === true) {
                    $breastForm['slkbiopsie'] = 1;
                }

                if (str_contains($record['ops'], $axillaOPS) === true) {
                    $breastForm['axlkentfomark'] = 2;
                }

                if (str_contains($record['sln_markierung'], '99') === true) {
                    $breastForm['radionuklidmarkierung'] = 1;
                }

                if (str_contains($record['sln_markierung'], 'bm') === true) {
                    $breastForm['farbmarkierung'] = 1;
                }
            }

            if ($bet === true && $mastektomie === false) {
                $breastForm['bet'] = 1;
            }
        }

        //check again if not done
        if ($breastForm['axlkentfomark'] == '0' || $breastForm['slkbiopsie'] == '0') {
            $allTherapies = $this->_loadTherapy('1900-01-01', $this->_accommodation['startDate'], $side);

            if ($allTherapies !== null) {
                foreach ($allTherapies as $record) {
                    if (str_contains($record['ops'], $axillaOPS) === true) {
                        $breastForm['axlkentfomark'] = 2;
                    }

                    if (str_contains($record['ops'], $biopsOps) === true) {
                        $breastForm['slkbiopsie'] = 1;
                    }
                }
            }
        }

        return $this;
    }
}

?>
