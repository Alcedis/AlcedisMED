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
 * Class Concobox_prostata_e_5_3_1_Model_Helper_Section_B
 */
class Concobox_prostata_e_5_3_1_Model_Helper_Section_B extends Concobox_prostata_e_5_3_1_Model_Helper_Section_Abstract
{
    /**
     * render renderB1DatumErstdiagnosePrimaertumor
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1DatumErstdiagnosePrimaertumor($patient, $records)
    {
        foreach ($records['tumorstatus'] as $record) {
            if (strlen($record['zufall']) > 0) {
                return null;
            }
        }

        foreach ($records['histologie']->reverse() as $record) {
            if ($record['art'] === 'pr' && $record['unauffaellig'] === null && $record['eingriff'] !== null) {
                return $record['eingriff']['datum'];
            }
        }

        foreach ($records['histologie']->reverse() as $record) {
            if ($record['art'] === 'pr' && $record['unauffaellig'] === null) {
                return $record['datum'];
            }
        }

        foreach ($records['tumorstatus']->reverse() as $record) {
            if ($record['anlass'] === 'p') {
                return $record['datum_sicherung'];
            }
        }

        return null;
    }


    /**
     * render renderB1Diagnosesicherheit
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1Diagnosesicherheit($patient, $records)
    {
        $map = array(
            '0' => 'NTS',
            '1' => 'K',
            '2' => 'KD',
            '4' => 'ST',
            '5' => 'Z',
            '6' => 'HUM',
            '7' => 'HUP',
            '9' => 'X'
        );

        $value = $this->getFirstFilled($records, 'tumorstatus', 'diagnosesicherung');

        return $this->map($value, $map);
    }


    /**
     * render renderB1TumordiagnoseICD10
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1TumordiagnoseICD10($patient, $records)
    {
        return $this->getFirstFilled($records, 'tumorstatus', 'diagnose');
    }


    /**
     * render renderB1HauptlokalisationICDO3
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1HauptlokalisationICDO3($patient, $records)
    {
        return $this->getFirstFilled($records, 'tumorstatus', 'lokalisation');
    }


    /**
     * render renderB1praeTcp
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1praeTcp($patient, $records)
    {
        foreach ($records['tumorstatus'] as $record) {
            if (strlen($record['t']) > 0 && in_array($record['tnm_praefix'], array('y', 'yr')) === false) {
                return substr($record['t'], 0, 1);
            }
        }

        return null;
    }


    /**
     * render renderB1praeT
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1praeT($patient, $records)
    {
        foreach ($records['tumorstatus'] as $record) {
            if (strlen($record['t']) > 0 && in_array($record['tnm_praefix'], array('y', 'yr')) === false) {
                return substr($record['t'], 1);
            }
        }

        return null;
    }


    /**
     * render renderB1praeNcp
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1praeNcp($patient, $records)
    {
        foreach ($records['tumorstatus'] as $record) {
            if (strlen($record['n']) > 0 && in_array($record['tnm_praefix'], array('y', 'yr')) === false) {
                return substr($record['n'], 0, 1);
            }
        }

        return null;
    }


    /**
     * render renderB1praeN
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1praeN($patient, $records)
    {
        foreach ($records['tumorstatus'] as $record) {
            if (strlen($record['n']) > 0 && in_array($record['tnm_praefix'], array('y', 'yr')) === false) {
                return substr($record['n'], 1);
            }
        }

        return null;
    }


    /**
     * render renderB1praeMcp
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1praeMcp($patient, $records)
    {
        foreach ($records['tumorstatus'] as $record) {
            if (strlen($record['m']) > 0 && in_array($record['tnm_praefix'], array('y', 'yr')) === false) {
                return substr($record['m'], 0, 1);
            }
        }

        return null;
    }


    /**
     * render renderB1praeM
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1praeM($patient, $records)
    {
        foreach ($records['tumorstatus'] as $record) {
            if (strlen($record['m']) > 0 && in_array($record['tnm_praefix'], array('y', 'yr')) === false) {
                return substr($record['m'], 1);
            }
        }

        return null;
    }


    /**
     * render renderB1PSADatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1PSADatum($patient, $records)
    {
        return $this->getFirstFilled($records, 'tumorstatus', 'datum_psa');
    }


    /**
     * render renderB1PSAWert
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1PSAWert($patient, $records)
    {
        return $this->getConditionalFirstFilled($records, 'tumorstatus', 'psa', 'datum_psa');
    }


    /**
     * render renderB1BiopsieDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1BiopsieDatum($patient, $records)
    {
        $dates = array();

        $ops = array('1-460.4', '1-462.4', '1-463.1', '1-464.0', '1-464.1', '1-465.1');

        foreach ($records['eingriff'] as $record) {
            foreach ($record['eingriff_ops'] as $opsRecord) {
                if (str_starts_with($opsRecord['prozedur'], $ops) === true) {
                    $dates[] = $record['datum'];

                    break 2;
                }
            }
        }

        foreach ($records['untersuchung'] as $record) {
            if (str_starts_with($record['art'], $ops) === true) {
                $dates[] = $record['datum'];
            }
        }

        if (count($dates) > 0) {
            rsort($dates);

            return reset($dates);
        }

        return null;
    }


    /**
     * render renderB1BiopsiePerineuraleInvasion
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1BiopsiePerineuraleInvasion($patient, $records)
    {
        return $this->getFirstFilled($records, 'tumorstatus', 'ppn');
    }


    /**
     * render renderB1ICDOHistologie
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1ICDOHistologie($patient, $records)
    {
        return $this->getFirstFilled($records, 'tumorstatus', 'morphologie');
    }


    /**
     * render renderB1BiopsieAS
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1BiopsieAS($patient, $records)
    {
        return null;
    }


    /**
     * render renderB1GleasonScoreWert1
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1GleasonScoreWert1($patient, $records)
    {
        return $this->getFirstFilled($records, 'tumorstatus', 'gleason1');
    }


    /**
     * render renderB1GleasonScoreWert2
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1GleasonScoreWert2($patient, $records)
    {
        return $this->getConditionalFirstFilled($records, 'tumorstatus', 'gleason2', 'gleason1');
    }


    /**
     * render renderB1Grading
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1Grading($patient, $records)
    {
        $map = array(
            '1' => 'G1',
            '2' => 'G2',
            '3' => 'G3',
            '4' => 'G4',
            'X' => 'GX'
        );

        $value = $this->getFirstFilled($records, 'tumorstatus', 'g');

        return $this->map($value, $map);
    }


    /**
     * render renderB1BefundPathologieVollstaendig
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1BefundPathologieVollstaendig($patient, $records)
    {
        return null;
    }


    /**
     * render renderB1Blasenkarzinom
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1Blasenkarzinom($patient, $records)
    {
        return null;
    }


    /**
     * render renderB1DKGPatientenfragebogenDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1DKGPatientenfragebogenDatum($patient, $records)
    {
        $fields = array('iciq_ui', 'iief5', 'lq_dkg', 'gz_dkg');

        foreach ($records['anamnese'] as $record) {
            foreach ($fields as $field) {
                if (strlen($record[$field]) > 0) {
                    return $record['datum'];
                }
            }
        }

        return null;
    }


    /**
     * render renderB1Kontinenz
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1Kontinenz($patient, $records)
    {
        return $this->getFirstFilled($records, 'anamnese', 'iciq_ui');
    }


    /**
     * render renderB1Potenz
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1Potenz($patient, $records)
    {
        return $this->getFirstFilled($records, 'anamnese', 'iief5');
    }


    /**
     * render renderB1Lebensqualitaet
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1Lebensqualitaet($patient, $records)
    {
        return $this->getFirstFilled($records, 'anamnese', 'lq_dkg');
    }


    /**
     * render renderB1Gesundheitszustand
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB1Gesundheitszustand($patient, $records)
    {
        return $this->getFirstFilled($records, 'anamnese', 'gz_dkg');
    }


    /**
     * render renderB2FamilienangehoerigeGrad1PCa
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB2FamilienangehoerigeGrad1PCa($patient, $records)
    {
        $grad = array('va', 'so');

        foreach ($records['anamnese'] as $record) {
            if ($record['anamnese_familie']->count() > 0) {
                $count = 0;

                foreach ($record['anamnese_familie'] as $fRecord) {
                    if ($fRecord['karzinom'] === 'pro' && in_array($fRecord['verwandschaftsgrad'], $grad) === true) {
                        $count++;
                    }
                }

                return $count;
            }
        }

        return null;
    }


    /**
     * render renderB2Grad1juengerals60
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB2Grad1juengerals60($patient, $records)
    {
        $grad = array('va', 'so');

        foreach ($records['anamnese'] as $record) {
            if ($record['anamnese_familie']->count() > 0) {
                $count = 0;

                foreach ($record['anamnese_familie'] as $fRecord) {
                    if ($fRecord['karzinom'] === 'pro' &&
                        in_array($fRecord['verwandschaftsgrad'], $grad) === true &&
                        (strlen($fRecord['erkrankungsalter']) > 0 && $fRecord['erkrankungsalter'] < 60)
                    ) {
                        $count++;
                    }
                }

                return $count;
            }
        }

        return null;
    }


    /**
     * render renderB2FamilienangehoerigeGrad2PCa
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB2FamilienangehoerigeGrad2PCa($patient, $records)
    {
        $grad = array('gv', 'br', 'ze', 'zz');

        foreach ($records['anamnese'] as $record) {
            if ($record['anamnese_familie']->count() > 0) {
                $count = 0;

                foreach ($record['anamnese_familie'] as $fRecord) {
                    if ($fRecord['karzinom'] === 'pro' && in_array($fRecord['verwandschaftsgrad'], $grad) === true) {
                        $count++;
                    }
                }

                return $count;
            }
        }

        return null;
    }


    /**
     * render renderB2FamilienangehoerigeGrad3PCa
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB2FamilienangehoerigeGrad3PCa($patient, $records)
    {
        $grad = array('on');

        foreach ($records['anamnese'] as $record) {
            if ($record['anamnese_familie']->count() > 0) {
                $count = 0;

                foreach ($record['anamnese_familie'] as $fRecord) {
                    if ($fRecord['karzinom'] === 'pro' && in_array($fRecord['verwandschaftsgrad'], $grad) === true) {
                        $count++;
                    }
                }

                return $count;
            }
        }


        return null;
    }


    /**
     * render renderB3RelevanteKrebserkrankungen
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB3RelevanteKrebserkrankungen($patient, $records)
    {
        $codings = array();

        $dates = array();

        foreach ($records['anamnese'] as $record) {
            foreach ($record['anamnese_erkrankung'] as $aeRecord) {
                if (str_starts_with($aeRecord['erkrankung'], 'C') === true &&
                    str_starts_with($aeRecord['erkrankung'], 'C44') === false
                ) {
                    $codings[] = 1;
                    $dates[] = $aeRecord['jahr'];
                } else if (str_starts_with($aeRecord['erkrankung'], 'C44') === true &&
                    ($aeRecord['morphologie'] === null || str_starts_with($aeRecord['morphologie'], array('809', '810', '811')) === false)
                ) {
                    $codings[] = 1;
                    $dates[] = $aeRecord['jahr'];
                } else if (str_starts_with($aeRecord['erkrankung'], 'C44') === true &&
                    str_starts_with($aeRecord['morphologie'], array('809', '810', '811')) === true
                ) {
                    $codings[] = 0;
                } else if (str_starts_with($aeRecord['erkrankung'], 'C') === false) {
                    $codings[] = 0;
                }
            }
        }

        $this->setCache('B3RelevanteKrebserkrankungenDates', $dates);

        if (count($codings) > 0) {
            return max($codings);
        }

        return 0;
    }


    /**
     * render renderB3JahrRelevanteKrebserkrankungen
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB3JahrRelevanteKrebserkrankungen($patient, $records)
    {
        $dates = $this->getCache('B3RelevanteKrebserkrankungenDates');

        $parsedDates = array();

        $currentYear = date('y');

        foreach ($dates as $date) {
            if (strlen($date) === 2) {
                $parsedDates[] = $date > 1 && $date <= $currentYear ? '20' : '19' . $date;
            } else if (strlen($date) === 4) {
                $parsedDates[] = $date;
            }
        }

        rsort($parsedDates);

        if (count($parsedDates) > 0) {
            return reset($parsedDates);
        }

        return null;
    }


    /**
     * render renderB3NichtRelevanteKrebserkrankungen
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB3NichtRelevanteKrebserkrankungen($patient, $records)
    {
        $codings = array();

        $dates = array();

        $range = range(37, 48);

        foreach ($range as &$entry) {
            $entry = 'D' . $entry;
        }

        foreach ($records['anamnese'] as $record) {
            foreach ($record['anamnese_erkrankung'] as $aeRecord) {
                if (str_starts_with($aeRecord['erkrankung'], $range) === true) {
                    $codings[] = 1;
                    $dates[] = $aeRecord['jahr'];
                } else if (str_starts_with($aeRecord['erkrankung'], 'C44') === true &&
                    str_starts_with($aeRecord['morphologie'], array('809', '810', '811')) === true
                ) {
                    $codings[] = 1;
                    $dates[] = $aeRecord['jahr'];
                }
            }
        }

        $this->setCache('B3NichtRelevanteKrebserkrankungenDates', $dates);

        return (int) (count($codings) > 0);
    }


    /**
     * render renderB3JahrNichtRelevanteKrebserkrankungen
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB3JahrNichtRelevanteKrebserkrankungen($patient, $records)
    {
        $dates = $this->getCache('B3NichtRelevanteKrebserkrankungenDates');

        $parsedDates = array();

        $currentYear = date('y');

        foreach ($dates as $date) {
            if (strlen($date) === 2) {
                $parsedDates[] = $date > 1 && $date <= $currentYear ? '20' : '19' . $date;
            } else if (strlen($date) === 4) {
                $parsedDates[] = $date;
            }
        }

        rsort($parsedDates);

        if (count($parsedDates) > 0) {
            return reset($parsedDates);
        }

        return null;
    }


    /**
     * render renderB4Zentrumspatient
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB4Zentrumspatient($patient, $records)
    {
        $exportOrgId = $this->getParameter('org_id');

        foreach ($records['therapieplan'] as $record) {
            if ($record['zeitpunkt'] === 'prae' && ($record['watchful_waiting'] === '1' || $record['active_surveillance'] === '1')) {
                $orgId = $record['org_id'];

                return ((strlen($orgId) === 0 || $orgId === $exportOrgId) ? 'ZF' : 'KZF');
            }
        }

        return null;
    }


    /**
     * render renderB4VorstellungImZentrum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB4VorstellungImZentrum($patient, $records)
    {
        foreach ($records['therapieplan'] as $record) {
            if ($record['zeitpunkt'] === 'prae' && ($record['watchful_waiting'] === '1' || $record['active_surveillance'] === '1')) {
                $grundlage = $record['grundlage'] === 'tk' ?  'V' : 'NV';

                if ($grundlage === 'V') {
                    $this->setCache('B4DatumVorstellungImZentrum', $record['datum']);
                    $this->setCache('B4PatientInZentrumEingebracht', $record['leistungserbringer']);
                }

                return $grundlage;
            }
        }

        return null;
    }


    /**
     * render renderB4DatumVorstellungImZentrum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB4DatumVorstellungImZentrum($patient, $records)
    {
        // attention! cache will be filled in method : renderB4VorstellungImZentrum
        return $this->getCache('B4DatumVorstellungImZentrum');
    }


    /**
     * render B4PatientInZentrumEingebracht
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB4PatientInZentrumEingebracht($patient, $records)
    {
        $map = array(
            'luro'    => 'URO',
            'lstrahl' => 'STR'
        );

        // attention! cache will be filled in method : renderB4VorstellungImZentrum
        $cacheValue = $this->getCache('B4PatientInZentrumEingebracht');

        return $this->map($cacheValue, $map);
    }


    /**
     * render B4Therapiestrategie
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB4Therapiestrategie($patient, $records)
    {
        foreach ($records['therapieplan']->reverse() as $record) {
            if ($record['watchful_waiting'] === '1' || $record['active_surveillance'] === '1') {
                return $record['datum'];
            }
        }

        return null;
    }


    /**
     * render B4EinwilligungDokumentationInTumordokumentation
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB4EinwilligungDokumentationInTumordokumentation($patient, $records)
    {
        return (strlen($patient['datenaustausch']) > 0 && strlen($patient['datenspeicherung']) > 0 ? 'LV' : 'LNV');
    }


    /**
     * render B4EinwilligungVersand
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB4EinwilligungVersand($patient, $records)
    {
        return (strlen($patient['datenversand']) > 0 ? 'LV' : 'LNV');
    }


    /**
     * render B4EinwilligigungMeldungKKREKR
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB4EinwilligigungMeldungKKREKR($patient, $records)
    {
        foreach ($records['ekr'] as $record) {
            if (strlen($record['datum_einverstaendnis']) > 0) {
                return 'LV';
            }
        }

        return 'LNV';
    }


    /**
     * render B4FalldatensatzVollstaendig
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB4FalldatensatzVollstaendig($patient, $records)
    {
        $incomplete = array();

        foreach ($records['tumorstatus'] as $record) {
            if ($record['fall_vollstaendig'] === '1') {
                return 'J';
            } else if ($record['fall_vollstaendig'] === '0') {
                $incomplete[] = true;
            }
        }

        return (count($incomplete) > 0 ? 'N' : 'X');
    }


    /**
     * render B5DatumStudie
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB5DatumStudie($patient, $records)
    {
        return $this->renderStudyDate($records, 'B5');
    }


    /**
     * render B5StudienTyp
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB5StudienTyp($patient, $records)
    {
        return $this->renderStudytype($records, 'B5');
    }


    /**
     * render B5PsychoonkologischeBetreuung
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB5PsychoonkologischeBetreuung($patient, $records)
    {
        return $this->renderPsychoOncology($records);
    }


    /**
     * render rB5BeratungSozialdienst
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB5BeratungSozialdienst($patient, $records)
    {
        return $this->renderSocialService($records);
    }


    /**
     * render B5PatientInMorbiditaetskonferenzVorgestellt
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderB5PatientInMorbiditaetskonferenzVorgestellt($patient, $records)
    {
        return $this->renderMorbidityConference($records);
    }


    /**
     * render B6Datum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $record
     * @return  string
     */
    public function renderB6Datum($patient, $record)
    {
        return $record['datum'];
    }


    /**
     * render B6Quelle
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $record
     * @return  string
     */
    public function renderB6Quelle($patient, $record)
    {
        if ($record['form'] === 'nachsorge' && $record['org_id'] === $this->getParameter('org_id')) {
            return 'EZ-Nach';
        }

        return 'X';
    }


    /**
     * render B6Vitalstatus
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $record
     * @return  string
     */
    public function renderB6Vitalstatus($patient, $record)
    {
        if ($record['form'] === 'abschluss') {
            if ($record['todesdatum'] === null) {
                return 'L';
            }

            $auRecords = $record['abschluss_ursache'];

            if (in_array($record['tod_tumorassoziation'], array('tott', 'totn')) === true) {
                if ($auRecords->count() === 0) {
                    return 'TT';
                } else {
                    if ($auRecords->count() >= 2) {
                        $lastDiag = false;

                        foreach ($auRecords as $auRecord) {
                            $disease = $auRecord['krankheit'];

                            if (str_starts_with($disease, 'C') === true) {
                                if ($lastDiag !== false && $disease !== $lastDiag) {
                                    return 'TT';
                                }

                                $lastDiag = $disease;
                            }
                        }
                    }

                    foreach ($auRecords as $auRecord) {
                        $disease = $auRecord['krankheit'];

                        if (str_starts_with($disease, 'C61') === true) {
                            return 'TBT';
                        }
                    }

                    return 'TAT';
                }
            } else {
                return 'TU';
            }
        }

        return 'L';
    }


    /**
     * render B6KontrolluntersuchungTyp
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $record
     * @return  string
     */
    public function renderB6KontrolluntersuchungTyp($patient, $record)
    {
        // reset cache
        $this->setCache('B6PSAWert', null);

        $opsCodes = array('1-460.4', '1-462.4', '1-463.1', '1-464.1', '1-465.1');

        // Prio 1
        if ($record['form'] === 'tumorstatus') {
            foreach ($record['ops'] as $op) {
                foreach ($op['eingriff_ops'] as $procedure) {
                    if (str_starts_with($procedure['prozedur'], '1-464.0') === true || in_array($procedure, $opsCodes) === true) {
                        return 'BI';
                    }
                }
            }
        } else if ($record['form'] === 'nachsorge') {
            if ($record['nachsorge_biopsie'] == '1') {
                return 'BI';
            }
        }

        // Prio 2
        if ($record['form'] === 'tumorstatus') {
            if (strlen($record['psa']) > 0) {
                $this->setCache('B6PSAWert', $record['psa']);

                return 'PSA';
            }
        } else if ($record['form'] === 'nachsorge') {
            if ($record['psa_bestimmt'] == '1') {
                if ($record['psa_labor_wert'] !== null) {
                    $this->setCache('B6PSAWert', $record['psa_labor_wert']['wert']);
                }

                return 'PSA';
            }
        }

        return 'K';
    }


    /**
     * render B6PSAWert
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $record
     * @return  string
     */
    public function renderB6PSAWert($patient, $record)
    {
        return $this->getCache('B6PSAWert');
    }


    /**
     * render B6Tumorstatus
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $record
     * @return  string
     */
    public function renderB6Tumorstatus($patient, $record)
    {
        $map = array(
            'CR' => 'VR',
            'PR' => 'TR',
            'SD' => 'NC',
            'PD' => 'P'
        );

        if ($record['form'] === 'nachsorge') {
            return $this->map($record['response_klinisch'], $map, 'X');
        }

        return 'X';
    }


    /**
     * render B6DKGFragebogenEingereicht
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $record
     * @return  string
     */
    public function renderB6DKGFragebogenEingereicht($patient, $record)
    {
        $fields = array('iciq_ui', 'iief5', 'lq_dkg', 'gz_dkg');

        if ($record['form'] === 'nachsorge') {
            foreach ($fields as $field) {
                if (strlen($record[$field]) > 0) {
                    return 'J';
                }
            }
        }

        return 'N';
    }


    /**
     * render B6Kontinenz
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $record
     * @return  string
     */
    public function renderB6Kontinenz($patient, $record)
    {
        return ($record['form'] === 'nachsorge' && strlen($record['iciq_ui']) > 0 ? $record['iciq_ui'] : null);
    }


    /**
     * render B6Potenz
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $record
     * @return  string
     */
    public function renderB6Potenz($patient, $record)
    {
        return ($record['form'] === 'nachsorge' && strlen($record['iief5']) > 0 ? $record['iief5'] : null);
    }


    /**
     * render B6Lebensqualitaet
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $record
     * @return  string
     */
    public function renderB6Lebensqualitaet($patient, $record)
    {
        return ($record['form'] === 'nachsorge' && strlen($record['lq_dkg']) > 0 ? $record['lq_dkg'] : null);
    }


    /**
     * render B6Gesundheitszustand
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $record
     * @return  string
     */
    public function renderB6Gesundheitszustand($patient, $record)
    {
        return ($record['form'] === 'nachsorge' && strlen($record['gz_dkg']) > 0 ? $record['gz_dkg'] : null);
    }


    /**
     * render B6DiagnoseFernmetastasierung
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $record
     * @return  string
     */
    public function renderB6DiagnoseFernmetastasierung($patient, $record)
    {
        if ($record['form'] === 'tumorstatus') {
            if (strlen($record['rezidiv_metastasen']) > 0) {
                switch ($record['quelle_metastasen']) {
                    case 'fmprim':      return 'J-FMBT'; break;
                    case 'fmanderer':   return 'J-FBAT'; break;
                    case 'fmub':
                    default:
                        return 'J-QFMU';

                        break;
                }
            }
        } elseif ($record['form'] === 'abschluss' && in_array($record['abschluss_grund'], array('lost', 'nnach')) === true) {
            return 'X';
        }

        return 'N';
    }


    /**
     * render B6DiagnoseZweittumor
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $record
     * @return  string
     */
    public function renderB6DiagnoseZweittumor($patient, $record)
    {
        $result =  'N';

        switch ($record['form']) {
            case 'nachsorge':
                if ($record['malignom'] === '1') {
                    $result = 'J';
                }
                break;

            case 'tumorstatus':
                if ($record['zweittumor'] === '1') {
                    $result = 'J';
                }
                break;

            case 'abschluss':
                if (in_array($record['abschluss_grund'], array('lost', 'nnach'))) {
                    $result = 'X';
                }
                break;
        }

        return $result;
    }
}
