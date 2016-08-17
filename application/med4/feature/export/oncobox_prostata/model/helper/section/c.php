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

class Concobox_prostata_e_5_3_1_Model_Helper_Section_C extends Concobox_prostata_e_5_3_1_Model_Helper_Section_Abstract
{
    /**
     * render renderC1praeTcp
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1praeTcp($patient, $records)
    {
        foreach ($records['tumorstatus']->reverse() as $record) {
            if (strlen($record['t']) > 0 && in_array($record['tnm_praefix'], array('y', 'yr')) === false) {
                $this->setCache('C1praeTcp', $record['t']);
                return substr($record['t'], 0, 1);
            }
        }

        return null;
    }


    /**
     * render renderC1praeT
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1praeT($patient, $records)
    {
        return substr($this->getCache('C1praeTcp'), 1);
    }


    /**
     * render renderC1praeNcp
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1praeNcp($patient, $records)
    {
        foreach ($records['tumorstatus']->reverse() as $record) {
            if (strlen($record['n']) > 0 && in_array($record['tnm_praefix'], array('y', 'yr')) === false) {
                $this->setCache('C1praeNcp', $record['n']);
                return substr($record['n'], 0, 1);
            }
        }

        return null;
    }


    /**
     * render renderC1praeN
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1praeN($patient, $records)
    {
        return substr($this->getCache('C1praeNcp'), 1);
    }


    /**
     * render renderC1praeMcp
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1praeMcp($patient, $records)
    {
        foreach ($records['tumorstatus']->reverse() as $record) {
            if (strlen($record['m']) > 0 && in_array($record['tnm_praefix'], array('y', 'yr')) === false) {
                $this->setCache('C1praeMcp', $record['m']);
                return substr($record['m'], 0, 1);
            }
        }

        return null;
    }


    /**
     * render renderC1praeM
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1praeM($patient, $records)
    {
        return substr($this->getCache('C1praeMcp'), 1);
    }


    /**
     * render renderC1PSADatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1PSADatum($patient, $records)
    {
        return $this->getCache('C1PSADatum');
    }


    /**
     * render renderC1PSAWert
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1PSAWert($patient, $records)
    {
        foreach ($records['tumorstatus']->reverse() as $record) {
            if (strlen($record['datum_psa']) > 0) {
                $this->setCache('C1PSADatum', $record['datum_psa']);
                return $record['psa'];
            }
        }

        return null;
    }


    /**
     * render renderC1BiopsieDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1BiopsieDatum($patient, $records)
    {
        $filled = array();

        $relevantOpsCodes = array('1-460.4', '1-462.4', '1-463.1', '1-464.1', '1-465.1');
        foreach ($records['untersuchung'] as $examination) {
            if (str_starts_with($examination['art'], '1-464.0') || in_array($examination['art'], $relevantOpsCodes)) {
                $filled[] = $examination['datum'];
            }
        }

        foreach ($records['eingriff'] as $op) {
            foreach ($op['eingriff_ops'] as $prozedur) {
                if (str_starts_with($prozedur['prozedur'], '1-464.0') ||
                    in_array($prozedur['prozedur'], $relevantOpsCodes)
                ) {
                    $filled[] = $op['datum'];
                }
            }
        }

        sort($filled);

        return reset($filled);
    }


    /**
     * render renderC1BiopsiePerineuraleInvasion
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1BiopsiePerineuraleInvasion($patient, $records)
    {
        return $this->getFirstFilled($records, 'tumorstatus', 'ppn');
    }


    /**
     * render renderC1ICDOHistologie
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1ICDOHistologie($patient, $records)
    {
        return $this->getFirstFilled($records, 'tumorstatus', 'morphologie');
    }


    /**
     * render renderC1GleasonScoreWert1
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1GleasonScoreWert1($patient, $records)
    {
        return $this->getFirstFilled($records, 'tumorstatus', 'gleason1');
    }


    /**
     * render renderC1GleasonScoreWert2
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1GleasonScoreWert2($patient, $records)
    {
        return $this->ifEmpty(
            $this->getConditionalFirstFilled($records, 'tumorstatus', 'gleason2', 'gleason1'),
            $this->getFirstFilled($records, 'tumorstatus', 'gleason2')
        );
    }


    /**
     * render renderC1Grading
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1Grading($patient, $records)
    {
        return $this->getFirstFilled($records, 'tumorstatus', 'g');
    }


    /**
     * render renderC1BefundPathologieVollstaendig
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1BefundPathologieVollstaendig($patient, $records)
    {
        //TODO: Spezifikation wird noch angepasst, wenn Onkozert Angaben gemacht hat.
        return null;
    }


    /**
     * render renderC1Blasenkarzinom
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1Blasenkarzinom($patient, $records)
    {
        //TODO: Spezifikation wird noch angepasst, wenn Onkozert Angaben gemacht hat.
        return null;
    }


    /**
     * render renderC1DKGPatientenfragebogenDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1DKGPatientenfragebogenDatum($patient, $records)
    {
        return $this->getFirstFilled($records, 'anamnese', 'datum');
    }


    /**
     * render renderC1Kontinenz
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1Kontinenz($patient, $records)
    {
        return $this->getFirstFilled($records, 'anamnese', 'iciq_ui');
    }


    /**
     * render renderC1Potenz
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1Potenz($patient, $records)
    {
        return $this->getFirstFilled($records, 'anamnese', 'iief5');
    }


    /**
     * render renderC1Lebensqualitaet
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1Lebensqualitaet($patient, $records)
    {
        return $this->getFirstFilled($records, 'anamnese', 'lq_dkg');
    }


    /**
     * render renderC1Gesundheitszustand
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC1Gesundheitszustand($patient, $records)
    {
        return $this->getFirstFilled($records, 'anamnese', 'gz_dkg');
    }


    /**
     * render renderC2Zentrumspatient
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC2Zentrumspatient($patient, $records)
    {
        $result = null;

        foreach ($records['tumorstatus'] as $form) {
            if (strlen($form['nur_zweitmeinung']) > 0 ||
                strlen($form['nur_diagnosesicherung']) > 0 ||
                strlen($form['kein_fall']) > 0
            ) {
                $result = 'KZF';
                break;
            }
        }

        return $result === null ? 'ZF' : $result;
    }


    /**
     * render renderC2PraetherapeutischeVorstellung
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC2PraetherapeutischeVorstellung($patient, $records)
    {
        foreach ($records['therapieplan'] as $record) {
            if ($record['grundlage'] === 'tk' && $record['zeitpunkt'] === 'prae') {
                $this->setCache('C2PraetherapeutischeVorstellung', $record['datum']);
                return 'V';
            }
        }

        return 'NV';
    }


    /**
     * render renderC2DatumVorstellungImZentrum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC2DatumVorstellungImZentrum($patient, $records)
    {
        return $this->getCache('C2PraetherapeutischeVorstellung');
    }


    /**
     * render renderC2VorstellungUeberLeistungserbringer
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC2VorstellungUeberLeistungserbringer($patient, $records)
    {
        $map = array('luro' => 'URO', 'lstrahl' => 'STR');

        if ($this->getCache('renderC2PraetherapeutischeVorstellung') === 'V') {
            foreach ($records['therapieplan']->reverse() as $record) {
                if (strlen($record['leistungserbringer']) > 0) {
                    return $this->map($record['leistungserbringer'], $map);
                }
            }
        }

        return null;
    }


    /**
     * render renderC2EinwilligungDokumentationInTumordokumentation
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC2EinwilligungDokumentationInTumordokumentation($patient, $records)
    {
        $result = 'LNV';

        if (($patient['datenaustausch'] + $patient['datenspeicherung']) === 2) {
            $result = 'LV';
        }

        return $result;;
    }


    /**
     * render renderC2EinwilligungVersand
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC2EinwilligungVersand($patient, $records)
    {
        return $patient['datenversand'] === '1' ? 'LV' : 'LNV';
    }


    /**
     * render renderC2EinwilligigungMeldungKKREKR
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC2EinwilligigungMeldungKKREKR($patient, $records)
    {
        return strlen($this->getFirstFilled($records, 'ekr', 'datum_einverstaendnis')) > 0 ? 'LV' : 'LNV';
    }


    /**
     * render renderC2FalldatensatzVollstaendig
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC2FalldatensatzVollstaendig($patient, $records)
    {
        $result = 'X';

        if ($this->getConditionalFirstFilled($records, 'tumorstatus', 'fall_vollstaendig', false, '0') === '0') {
            $result = 'N';
        } elseif ($this->getConditionalFirstFilled($records, 'tumorstatus', 'fall_vollstaendig', false, '1') === '1') {
            $result = 'J';
        }

        return $result;
    }


    /**
     * render renderC3DatumOperation
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC3DatumOperation($patient, $records)
    {
        $this->setCache('getPrimaryProstatectomy', $this->getPrimaryProstatectomy($records));

        $op = $this->getCache('getPrimaryProstatectomy');

        if ($op !== null) {
            return $op['datum'];
        }

        return null;
    }


    /**
     * render renderC3OPSCode
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC3OPSCode($patient, $records)
    {
        $result = array();

        $op = $this->getCache('getPrimaryProstatectomy');

        if ($op === null) {
            return null;
        }

        $codes = array(
            '5-604.12'   => '1',
            '5-604.11'   => '2',
            '5-604.1'    => '3',
            '5-604.02'   => '4',
            '5-604.01'   => '5',
            '5-604.0'    => '6',
            '5-604.32'   => '7',
            '5-604.31'   => '8',
            '5-604.3'    => '9',
            '5-604.22'   => '10',
            '5-604.21'   => '11',
            '5-604.2'    => '12',
            '5-604.52'   => '13',
            '5-604.51'   => '14',
            '5-604.5'    => '15',
            '5-604.42'   => '16',
            '5-604.41'   => '17',
            '5-604.4'    => '18'
        );

        $codeStartsWith = array('5-576.2', '5-576.3', '5-576.4', '5-576.5');

        foreach ($op['eingriff_ops'] as $ops) {
            if (array_key_exists($ops['prozedur'], $codes) === true) {
                $result[$codes[$ops['prozedur']]] = $ops['prozedur'];
            } elseif (str_starts_with($ops['prozedur'], '5-604.') === true) {
                $result['19'] = $ops['prozedur'];
            } elseif (str_starts_with($ops['prozedur'], $codeStartsWith) === true) {
                $result['20'] = $ops['prozedur'];
            }
        }

        ksort($result);

        return implode('/', $result);
    }


    /**
     * render renderC3Verfahren
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC3Verfahren($patient, $records)
    {
        $op = $this->getCache('getPrimaryProstatectomy');

        if ($op === null) {
            return null;
        }

        $map = array(
            'offper'   => 'OP',
            'offretro' => 'OR',
            'robtrans' => 'RT',
            'robextra' => 'RE',
            'laptrans' => 'LT',
            'lapextra' => 'LE'
        );

        $op = $this->getCache('getPrimaryProstatectomy');

        return $this->map($op['op_verfahren'], $map);
    }


    /**
     * render renderC3Erstoperateur
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC3Erstoperateur($patient, $records)
    {
        $op = $this->getCache('getPrimaryProstatectomy');

        if ($op === null) {
            return null;
        }

        return $op['operateur1_id'];
    }


    /**
     * render renderC3Zweitoperateur
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC3Zweitoperateur($patient, $records)
    {
        $op = $this->getCache('getPrimaryProstatectomy');

        if ($op === null) {
            return null;
        }

        return $op['operateur2_id'];
    }


    /**
     * render renderC3Revisionseingriff
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC3Revisionseingriff($patient, $records)
    {
        if ($this->getCache('getPrimaryProstatectomy') === null) {
            return null;
        }

        return $this->getCache('renderC3RevisionseingriffDatum') === null ? 'N' : 'J';
    }


    /**
     * render renderC3RevisionseingriffDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC3RevisionseingriffDatum($patient, $records)
    {
        if ($this->getCache('getPrimaryProstatectomy') === null) {
            return null;
        }

        $result = null;

        $opDate = $this->getCache('renderC3DatumOperation');

        if (strlen($opDate) > 0 ) {
            $disease = $patient['erkrankung'];

            $revisionOp = $this->getConditionalAllFilled($disease, 'eingriff', 'datum', 'art_revision');

            foreach ($revisionOp as $revision) {
                $dateDiff = date_diff_days($opDate, $revision);

                if ($dateDiff <= 90 && $dateDiff > 0) {
                    $result = $revision;
                    break;
                }
            }

            if ($result === null) {
                foreach ($disease['komplikation'] as $complication) {
                    if ($complication['revisionsoperation'] === '1') {
                        $dateDiff = date_diff_days($opDate, $complication['datum']);

                        if ($dateDiff <= 90 && $dateDiff > 0) {
                            $result = $complication['datum'];
                        }
                    }
                }
            }
        }

        return $result;
    }


    /**
     * render renderC3PostoperativeWundinfektionDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC3PostoperativeWundinfektionDatum($patient, $records)
    {
        if ($this->getCache('getPrimaryProstatectomy') === null) {
            return null;
        }

        $result = null;

        $codes = array('wi', 'wa1', 'wa2', 'wa3', 'wctc2');

        $complications = $this->getConditionalAllFilled($records, 'komplikation', 'komplikation', false);
        foreach ($complications as $complicationDate => $complication) {
            if (in_array($complication, $codes) === true) {
                $result = $complicationDate;
            }
        }

        return $result;
    }


    /**
     * render renderC3PostoperativeWundinfektion
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC3PostoperativeWundinfektion($patient, $records)
    {
        if ($this->getCache('getPrimaryProstatectomy') === null) {
            return null;
        }

        return $this->getCache('renderC3PostoperativeWundinfektionDatum') === null ? '0' : '1';
    }


    /**
     * render renderC3NervenerhaltendeOperation
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC3NervenerhaltendeOperation($patient, $records)
    {
        $op = $this->getCache('getPrimaryProstatectomy');

        if ($op === null) {
            return null;
        }

        $result = null;

        if (strlen($op['nerverhalt_seite']) > 0) {
            $result = $op['nerverhalt_seite'] === 'RL' ? 'E' : $op['nerverhalt_seite'];
        } else {
            foreach ($op['eingriff_ops'] as $code) {
                $codes = array('5-604.11', '5-604.12', '5-604.31', '5-604.32', '5-604.51', '5-604.52');
                if (str_starts_with($code['prozedur'], $codes) === false) {
                    $result = 'N';
                }
            }
        }

        return $result;
    }


    /**
     * render renderC3CalvienDindoGrad
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC3CalvienDindoGrad($patient, $records)
    {
        //TODO: Spezifikation wird noch angepasst, wenn Onkozert Angaben gemacht hat.
        return null;
    }


    /**
     * render renderC3DatumKomplikation
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC3DatumKomplikation($patient, $records)
    {
        //TODO: Spezifikation wird noch angepasst, wenn Onkozert Angaben gemacht hat.
        return null;
    }


    /**
     * render renderC4PraefixY
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC4PraefixY($patient, $records)
    {
        $result = null;

        foreach ($records['tumorstatus'] as $ts) {
            if (str_starts_with($ts['t'], 'p') === true) {
                $result = $ts['tnm_praefix'];
                break;
            }
        }

        return $result;
    }


    /**
     * render renderC4pT
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC4pT($patient, $records)
    {
        $result = null;

        foreach ($records['tumorstatus'] as $ts) {
            if (str_starts_with($ts['t'], 'p') === true) {
                $result = $ts['t'];
                break;
            }
        }

        return $result;
    }


    /**
     * render renderC4pN
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC4pN($patient, $records)
    {
        $result = null;

        foreach ($records['tumorstatus'] as $ts) {
            if (str_starts_with($ts['n'], 'p') === true) {
                $result = $ts['n'];
                break;
            }
        }

        return $result;
    }


    /**
     * render renderC4pM
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC4pM($patient, $records)
    {
        $result = null;

        foreach ($records['tumorstatus'] as $ts) {
            if (str_starts_with($ts['m'], 'p') === true) {
                $result = $ts['m'];
                break;
            }
        }

        return $result;
    }


    /**
     * render renderC4GleasonScoreWert1
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC4GleasonScoreWert1($patient, $records)
    {
        return $this->getFirstFilledFromTumorstateP($records, 'gleason1');
    }


    /**
     * render renderC4GleasonScoreWert2
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC4GleasonScoreWert2($patient, $records)
    {
        $gleason2 = null;

        foreach ($records['tumorstatus'] as $ts) {
            if (str_starts_with($ts['t'], 'p') === true && strlen($ts['gleason1']) > 0) {
                $gleason2 = $ts['gleason2'];
                break;
            }
        }

        return $this->ifEmpty($gleason2, $this->getFirstFilled($records, 'tumorstatus', 'gleason2'));
    }


    /**
     * render renderC4Grading
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC4Grading($patient, $records)
    {
        return $this->getFirstFilledFromTumorstateP($records, 'g');
    }


    /**
     * render renderC4PerineuraleInvasion
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC4PerineuraleInvasion($patient, $records)
    {
        return $this->getFirstFilledFromTumorstateP($records, 'ppn');
    }


    /**
     * render renderC4AnzahlUntersuchtenLymphknoten
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC4AnzahlUntersuchtenLymphknoten($patient, $records)
    {
        return $this->getFirstFilledFromTumorstateP($records, 'lk_entf');
    }


    /**
     * render renderC4AnzahlMaligneBefallenenLymphknoten
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC4AnzahlMaligneBefallenenLymphknoten($patient, $records)
    {
        return $this->getFirstFilledFromTumorstateP($records, 'lk_bef');
    }


    /**
     * render renderC4Lymphgefaessinvasion
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC4Lymphgefaessinvasion($patient, $records)
    {
        return $this->getFirstFilledFromTumorstateP($records, 'l');
    }


    /**
     * render renderC4Veneninvasion
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC4Veneninvasion($patient, $records)
    {
        return $this->getFirstFilledFromTumorstateP($records, 'v');
    }


    /**
     * render renderC4ICDO3Histologie
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC4ICDO3Histologie($patient, $records)
    {
        return $this->getFirstFilledFromTumorstateP($records, 'morphologie');
    }


    /**
     * render renderC4PSRLokaleRadikalitaet
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC4PSRLokaleRadikalitaet($patient, $records)
    {
        return 'R' . $this->getFirstFilledFromTumorstateP($records, 'r_lokal');
    }


    /**
     * render renderC5TumordiagnoseICD10
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC4_2TumordiagnoseICD10($patient, $records)
    {
        if (strlen($this->getCache('renderC3DatumOperation')) > 0) {
            foreach($records['tumorstatus'] as $record) {
                if ($record['datum'] > $this->getCache('renderC3DatumOperation') &&
                    str_starts_with($record['t'], 'p') &&
                    strlen($record['diagnose']) > 0
                ) {
                    return $record['diagnose'];
                }
            }
        }

        return null;
    }


    /**
     * render renderC5cM
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC4_2cM($patient, $records)
    {
        if (strlen($this->getCache('renderC3DatumOperation')) > 0) {
            foreach($records['tumorstatus'] as $record) {
                if ($record['datum'] > $this->getCache('renderC3DatumOperation') &&
                    str_starts_with($record['m'], 'c')
                ) {
                    return substr($record['m'], 1);
                }
            }
        }

        return null;
    }


    /**
     * render renderC5Vorstellung
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC5Vorstellung($patient, $records)
    {
        $result = null;

        if ($this->getFirstFilled($records, 'eingriff', 'art_primaertumor') === '1') {
            $result = 'NV';
            foreach ($records['therapieplan'] as $record) {
                if ($record['grundlage'] === 'tk' && $record['zeitpunkt'] === 'post') {
                    $result = 'V';
                    $this->setCache('C5Vorstellung', $record['datum']);
                }
            }
        }

        return $result;
    }


    /**
     * render renderC6Datum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC5Datum($patient, $records)
    {
        return $this->getCache('C5Vorstellung');
    }


    /**
     * render renderC6Therapiezeitpunkt
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC6Therapiezeitpunkt($patient, $records)
    {
        foreach ($records['strahlentherapie']->reverse() as $record) {
            if ($record['art'] === 'str_pk') {
                $this->setCache('C6BeginnDatum', $record['datum']);
                $this->setCache('C6GesamtdosisInGray', $record['gesamtdosis']);
                $this->setCache('C6EndeDatum', $record['ende']);
                $this->setCache('C6End', $record['endstatus']);
                $this->setCache('C6Reason', $record['endstatus_grund']);
                switch ($record['intention']) {
                    case 'kur':
                        return 'D';
                        break;
                    case 'kura':
                    case 'pala':
                        return 'A';
                        break;
                    case 'kurna':
                    case 'palna':
                        return 'N';
                        break;
                }
            }
        }

        return null;
    }


    /**
     * render renderC6Therapieintention
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC6Therapieintention($patient, $records)
    {
        foreach ($records['strahlentherapie']->reverse() as $record) {
            if ($record['art'] === 'str_pk') {
                if (str_starts_with($record['intention'], 'kur')) {
                    return 'K';
                } elseif (str_starts_with($record['intention'], 'pal')) {
                    return 'P';
                }
            }
        }

        return null;
    }


    /**
     * render renderC6BeginnDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC6BeginnDatum($patient, $records)
    {
        return $this->getCache('C6BeginnDatum');
    }


    /**
     * render renderC6GesamtdosisInGray
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC6GesamtdosisInGray($patient, $records)
    {
        return $this->getCache('C6GesamtdosisInGray');
    }


    /**
     * render renderC6EndeDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC6EndeDatum($patient, $records)
    {
        return $this->getCache('C6EndeDatum');
    }


    /**
     * render renderC6GrundBeendigungStrahlentherapie
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC6GrundBeendigungStrahlentherapie($patient, $records)
    {
        if (strlen($this->getCache('renderC6Therapiezeitpunkt')) > 0 ) {
            return $this->mapFinalStatus($this->getCache('C6End'), $this->getCache('C6Reason'));
        }

        return null;
    }


    /**
     * render renderC7Datum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC7Datum($patient, $records)
    {
        foreach ($records['strahlentherapie']->reverse() as $record) {
            if ($record['art'] === 'str_ldr') {
                $this->setCache('C7GesamtdosisInGray', $record['gesamtdosis']);
                $this->setCache('C7GrayBeiD90', $record['seed_strahlung_90d']);

                return $record['datum'];
            }
        }

        return null;
    }


    /**
     * render renderC7GesamtdosisInGray
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC7GesamtdosisInGray($patient, $records)
    {
        return $this->getCache('C7GesamtdosisInGray');
    }


    /**
     * render renderC7GrayBeiD90
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC7GrayBeiD90($patient, $records)
    {
        return $this->getCache('C7GrayBeiD90');
    }


    /**
     * render renderC8BeginnDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC8BeginnDatum($patient, $records)
    {
        foreach ($records['strahlentherapie']->reverse() as $record) {
            if ($record['art'] === 'str_hdr') {
                $this->setCache('C8GesamtdosisInGray', $record['gesamtdosis']);
                $this->setCache('C8EndeDatum', $record['ende']);
                $this->setCache('C8End', $record['endstatus']);
                $this->setCache('C8Reason', $record['endstatus_grund']);

                return $record['datum'];
            }
        }

        return null;
    }


    /**
     * render renderC8GesamtdosisInGray
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC8GesamtdosisInGray($patient, $records)
    {
        return $this->getCache('C8GesamtdosisInGray');
    }


    /**
     * render renderC8EndeDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC8EndeDatum($patient, $records)
    {
        return $this->getCache('C8EndeDatum');
    }


    /**
     * render renderC8GrundBeendigungBrachytherapie
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC8GrundBeendigungBrachytherapie($patient, $records)
    {
        if (strlen($this->getCache('renderC8BeginnDatum')) > 0) {
            return $this->mapFinalStatus($this->getCache('C8End'), $this->getCache('C8Reason'));
        }

        return null;
    }


    /**
     * render renderC9BeginnDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC9BeginnDatum($patient, $records)
    {
        foreach ($records['therapie_systemisch']->reverse() as $record) {
            if (str_starts_with($record['vorlage_therapie_art'], 'c')) {
                $this->setCache('C9EndeDatum', $record['ende']);
                $this->setCache('C9End', $record['endstatus']);
                $this->setCache('C9Reason', $record['endstatus_grund']);

                return $record['datum'];
            }

        }

        return null;
    }


    /**
     * render renderC9EndeDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC9EndeDatum($patient, $records)
    {
        return $this->getCache('C9EndeDatum');
    }


    /**
     * render renderC9GrundBeendigungChemotherapie
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC9GrundBeendigungChemotherapie($patient, $records)
    {
        return $this->mapFinalStatus($this->getCache('C9End'), $this->getCache('C9Reason'));
    }


    /**
     * render renderC10Therapiezeitpunkt
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC10Therapiezeitpunkt($patient, $records)
    {
        $map = array(
            'kur'   => 'D',
            'kura'  => 'A',
            'palna' => 'A',
            'kurna' => 'N',
            'pala'  => 'N'
        );

        //TODO '' => 'B' begleitende Therapie???
        foreach ($records['therapie_systemisch']->reverse() as $record) {
            if (str_starts_with($record['vorlage_therapie_art'], 'ah') && strlen($record['intention']) > 0) {
                return $this->map($record['intention'], $map);
            }
        }

        return null;
    }


    /**
     * render renderC10Therapieintention
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC10Therapieintention($patient, $records)
    {
        //TODO '' => 'B' begleitende Therapie???
        foreach ($records['therapie_systemisch']->reverse() as $record) {
            if (str_starts_with($record['vorlage_therapie_art'], 'ah') && strlen($record['intention']) > 0) {
                if (str_starts_with($record['intention'], 'kur')) {
                    return 'K';
                } else {
                    return 'P';
                }
            }
        }

        return null;
    }


    /**
     * render renderC10TherapieArt
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC10TherapieArt($patient, $records)
    {
        return null;
    }


    /**
     * render renderC10BeginnDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC10BeginnDatum($patient, $records)
    {
        foreach ($records['therapie_systemisch']->reverse() as $record) {
            if (str_starts_with($record['vorlage_therapie_art'], 'ah')) {
                $this->setCache('C10EndeDatum', $record['ende']);
                $this->setCache('C10End', $record['endstatus']);
                $this->setCache('C10Reason', $record['endstatus_grund']);

                return $record['datum'];
            }

        }

        return null;
    }


    /**
     * render renderC10EndeDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC10EndeDatum($patient, $records)
    {
        return $this->getCache('C10EndeDatum');
    }


    /**
     * render renderC10GrundBeendigungHormontherapie
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC10GrundBeendigungHormontherapie($patient, $records)
    {
        if (strlen($this->getCache('renderC10BeginnDatum'))> 0) {
            return $this->mapFinalStatus($this->getCache('C10End'), $this->getCache('C10Reason'));
        }

        return null;
    }


    /**
     * render renderC11BeginnDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC11BeginnDatum($patient, $records)
    {
        foreach ($records['therapie_systemisch']->reverse() as $record) {
            if (str_starts_with($record['vorlage_therapie_art'], array('ci', 'i', 'im'))) {
                $this->setCache('C11EndeDatum', $record['ende']);
                $this->setCache('C11End', $record['endstatus']);
                $this->setCache('C11Reason', $record['endstatus_grund']);

                return $record['datum'];
            }

        }

        return null;
    }


    /**
     * render renderC11EndeDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC11EndeDatum($patient, $records)
    {
        return $this->getCache('C11EndeDatum');
    }


    /**
     * render renderC11GrundBeendigungImmuntherapie
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC11GrundBeendigungImmuntherapie($patient, $records)
    {
        if (strlen($this->getCache('renderC11BeginnDatum'))> 0) {
            return $this->mapFinalStatus($this->getCache('C11End'), $this->getCache('C11Reason'));
        }

        return null;
    }


    /**
     * render renderC12SupportiveTherapieDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC12SupportiveTherapieDatum($patient, $records)
    {
        return null;
    }


    /**
     * render renderC12HIFUTherapieDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC12HIFUTherapieDatum($patient, $records)
    {
        return null;
    }


    /**
     * render renderC12KyrotherapieDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC12KyrotherapieDatum($patient, $records)
    {
        return null;
    }


    /**
     * render renderC12HyperthermieDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC12HyperthermieDatum($patient, $records)
    {
        return null;
    }


    /**
     * render renderC13Vorstellung
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC13Vorstellung($patient, $records)
    {
        foreach  ($records['eingriff'] as $record) {
            if ($record['art_primaertumor'] === '1') {
                return null;
            }
        }

        foreach ($records['therapieplan'] as $record) {
            if ($record['grundlage'] === 'tk' && $record['zeitpunkt'] === 'post') {
                $this->setCache('C13Datum', $record['datum']);
                return 'V';
            }
        }

        return 'NV';
    }


    /**
     * render renderC13Datum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC13Datum($patient, $records)
    {
        return $this->getCache('C13Datum');
    }


    /**
     * render renderC14CalvienDindoGrad
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC14CalvienDindoGrad($patient, $records)
    {
        return null;
    }


    /**
     * render renderC14DatumKomplikation
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC14DatumKomplikation($patient, $records)
    {
        return null;
    }


    /**
     * render renderC15DatumStudie
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC15DatumStudie($patient, $records)
    {
        return $this->renderStudyDate($records, 'C15');
    }


    /**
     * render renderC15Studientyp
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC15Studientyp($patient, $records)
    {
        return $this->renderStudytype($records, 'C15');
    }


    /**
     * render renderC15PsychoonkologischeBetreuung
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC15PsychoonkologischeBetreuung($patient, $records)
    {
        return $this->renderPsychoOncology($records);
    }


    /**
     * render renderC15BeratungSozialdienst
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC15BeratungSozialdienst($patient, $records)
    {
        return $this->renderSocialService($records);
    }


    /**
     * render renderC15PatientInMorbiditaetskonferenzvorgestellt
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderC15PatientInMorbiditaetskonferenzvorgestellt($patient, $records)
    {
        return $this->renderMorbidityConference($records);
    }
}
