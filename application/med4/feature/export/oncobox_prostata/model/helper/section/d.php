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

class Concobox_prostata_e_5_3_1_Model_Helper_Section_D extends Concobox_prostata_e_5_3_1_Model_Helper_Section_Abstract
{

    /**
     * @access
     * @var null
     */
    protected $_cacheVorstellungImZentrumTp = null;


    /**
     * @access
     * @var null
     */
    protected $_cachePostoperativeTumorkonferenzTp = null;


    /**
     * @access
     * @var null
     */
    protected $_cacheProstatectomy = null;


    /**
     * @access
     * @var null
     */
    protected $_cacheComplication = null;


    /**
     * @access
     * @var null
     */
    protected $_revisionseingriffDatum = null;


    /**
     * render renderD1DatumDiagnoseProgress
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD1DatumDiagnoseProgress($patient, $records)
    {
        return $this->getLastFilled($records, 'tumorstatus', 'datum_sicherung');
    }


    /**
     * render renderD1TumordiagnoseICD10
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD1TumordiagnoseICD10($patient, $records)
    {
        return $this->getLastFilled($records, 'tumorstatus', 'diagnose');
    }


    /**
     * render renderD1HauptlokalisationICDO3
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD1HauptlokalisationICDO3($patient, $records)
    {
        $lokalisation = $this->getLastFilled($records, 'tumorstatus', 'lokalisation');
        if (strlen($lokalisation) > 0) {
            return str_replace('.', '', $lokalisation);
        }
        return false;
    }


    /**
     * render renderD1PSAWert
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD1PSAWert($patient, $records)
    {
        $ts = $records['tumorstatus']->getLast();
        if ((null !== $ts) && strlen($ts['psa']) > 0) {
            return $ts['psa'];
        }
        return false;
    }


    /**
     * render renderD1BiopsieDurchgefuehrt
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD1BiopsieDurchgefuehrt($patient, $records)
    {
        foreach ($records['eingriff'] as $eingriff) {
            foreach ($eingriff['eingriff_ops'] as $ops) {
                if (str_starts_with($ops['prozedur'], '1-460.4') ||
                    str_starts_with($ops['prozedur'], '1-462.4') ||
                    str_starts_with($ops['prozedur'], '1-463.1') ||
                    str_starts_with($ops['prozedur'], '1-464.0') ||
                    str_starts_with($ops['prozedur'], '1-464.1') ||
                    str_starts_with($ops['prozedur'], '1-465.1')) {
                    return 'Ja';
                }
            }
        }
        foreach ($records['untersuchung'] as $untersuchung) {
            if (str_starts_with($untersuchung['art'], '1-460.4') ||
                str_starts_with($untersuchung['art'], '1-462.4') ||
                str_starts_with($untersuchung['art'], '1-463.1') ||
                str_starts_with($untersuchung['art'], '1-464.0') ||
                str_starts_with($untersuchung['art'], '1-464.1') ||
                str_starts_with($untersuchung['art'], '1-465.1')) {
                return 'Ja';
            }
        }
        return 'Nein';
    }


    /**
     * render renderD1BiopsiePerineuraleInvasion
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD1BiopsiePerineuraleInvasion($patient, $records)
    {
        return $this->getLastFilled($records, 'tumorstatus', 'ppn');
    }


    /**
     * render renderD1ICDOHistologieMorphologie
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD1ICDOHistologieMorphologie($patient, $records)
    {
        return $this->getLastFilled($records, 'tumorstatus', 'morphologie');
    }


    /**
     * render renderD1GleasonScoreWert1
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD1GleasonScoreWert1($patient, $records)
    {
        return $this->getLastFilled($records, 'tumorstatus', 'gleason1');
    }


    /**
     * render renderD1GleasonScoreWert2
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD1GleasonScoreWert2($patient, $records)
    {
        return $this->getLastFilled($records, 'tumorstatus', 'gleason2');
    }


    /**
     * render renderD1Grading
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD1Grading($patient, $records)
    {
        return $this->getLastFilled($records, 'tumorstatus', 'g');
    }


    /**
     * render renderD1DKGPatientenfragebogenDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD1DKGPatientenfragebogenDatum($patient, $records)
    {
        return $this->getLastFilled($records, 'anamnese', 'datum');
    }


    /**
     * render renderD1Kontinenz
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD1Kontinenz($patient, $records)
    {
        return $this->getLastFilled($records, 'anamnese', 'iciq_ui');
    }


    /**
     * render renderD1Potenz
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD1Potenz($patient, $records)
    {
        return $this->getLastFilled($records, 'anamnese', 'iief5');
    }


    /**
     * render renderD1Lebensqualitaet
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD1Lebensqualitaet($patient, $records)
    {
        return $this->getLastFilled($records, 'anamnese', 'lq_dkg');
    }


    /**
     * render renderD1Gesundheitszustand
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD1Gesundheitszustand($patient, $records)
    {
        return $this->getLastFilled($records, 'anamnese', 'gz_dkg');
    }


    /**
     * render renderD2Zentrumspatient
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD2Zentrumspatient($patient, $records)
    {
        foreach ($records['tumorstatus'] as $ts) {
            if (('1' == $ts['nur_zweitmeinung']) ||
                ('1' == $ts['nur_diagnosesicherung']) ||
                ('1' == $ts['kein_fall'])) {
                return 'KZF';
            }
        }
        return 'ZF';
    }


    /**
     *
     *
     * @access
     * @param $records
     * @return bool
     */
    protected function _getPraethTumorkonfTherapieplan($records)
    {
        foreach ($records['therapieplan']->reverse() as $tp) {
            if (('tk' == $tp['grundlage']) && ('prae' == $tp['zeitpunkt'])) {
                return $tp;
            }
        }
        return false;
    }


    /**
     * render renderD2PraetherapeutischeVorstellung
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD2PraetherapeutischeVorstellung($patient, $records)
    {
        $tp = $this->_getPraethTumorkonfTherapieplan($records);
        if (false !== $tp) {
            return 'V';
        }
        return 'NV';
    }


    /**
     * render renderD2DatumVorstellungImZentrum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD2DatumVorstellungImZentrum($patient, $records)
    {
        $tp = $this->_getPraethTumorkonfTherapieplan($records);
        if (false !== $tp) {
            $this->_cacheVorstellungImZentrumTp = $tp;
            return $tp['datum'];
        }
        return false;
    }


    /**
     * render renderD2VorstellungUeberLeistungserbringer
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD2VorstellungUeberLeistungserbringer($patient, $records)
    {
        if (null !== $this->_cacheVorstellungImZentrumTp) {
            return $this->_cacheVorstellungImZentrumTp['leistungserbringer'];
        }
        return false;
    }


    /**
     * render renderD2FalldatensatzVollstaendig
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD2FalldatensatzVollstaendig($patient, $records)
    {
        return $this->getLastFilled($records, 'tumorstatus', 'fall_vollstaendig') === null ? '0' : '1';
    }


    /**
     * render renderD3Datum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD3Datum($patient, $records)
    {
        $eingriffe = Concobox_prostata_e_5_3_1_Model_Helper::getProstatectomyByPriority($records);
        if (false !== $eingriffe) {
            foreach ($eingriffe as $eingriff) {
                if ('1' == $eingriff['art_rezidiv']) {
                    $this->_cacheProstatectomy = $eingriff;
                    return $eingriff['datum'];
                }
            }
        }
        return false;
    }


    /**
     * render renderD3OPSCode
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD3OPSCode($patient, $records)
    {
        if (null !== $this->_cacheProstatectomy) {
            $opsCodes = Concobox_prostata_e_5_3_1_Model_Helper::getOpsCodesByPriority($this->_cacheProstatectomy, true);
            if (false !== $opsCodes) {
                return $opsCodes;
            }
        }
        return false;
    }


    /**
     * render renderD3Verfahren
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD3Verfahren($patient, $records)
    {
        $eingriffe = Concobox_prostata_e_5_3_1_Model_Helper::getProstatectomyByPriority($records);
        if (false !== $eingriffe) {
            foreach ($eingriffe as $eingriff) {
                if (('1' == $eingriff['art_rezidiv']) && (strlen($eingriff['op_verfahren']) > 0)) {
                    return $eingriff['op_verfahren'];
                }
            }
        }
        return false;
    }


    /**
     * render renderD3Erstoperateur
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD3Erstoperateur($patient, $records)
    {
        $eingriffe = Concobox_prostata_e_5_3_1_Model_Helper::getProstatectomyByPriority($records);
        if (false !== $eingriffe) {
            foreach ($eingriffe as $eingriff) {
                if (('1' == $eingriff['art_rezidiv']) && (strlen($eingriff['operateur1_id']) > 0)) {
                    return $eingriff['operateur1_id'];
                }
            }
        }
        return false;
    }


    /**
     * render renderD3Zweitoperateur
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD3Zweitoperateur($patient, $records)
    {
        $eingriffe = Concobox_prostata_e_5_3_1_Model_Helper::getProstatectomyByPriority($records);
        if (false !== $eingriffe) {
            foreach ($eingriffe as $eingriff) {
                if (('1' == $eingriff['art_rezidiv']) && (strlen($eingriff['operateur2_id']) > 0)) {
                    return $eingriff['operateur2_id'];
                }
            }
        }
        return false;
    }


    /**
     * render renderD3Revisionseingriff
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD3Revisionseingriff($patient, $records)
    {
        $primaerOp = false;
        foreach ($patient['erkrankung']['eingriff'] as $eingriff) {
            if ('1' == $eingriff['art_primaertumor']) {
                $primaerOp = $eingriff;
                break;
            }
        }
        if (false !== $primaerOp) {
            foreach ($records['komplikation'] as $komplikation) {
                if ('1' == $komplikation['revisionsoperation']) {
                    $dateDiff = date_diff_days($primaerOp['datum'], $komplikation['datum']);
                    if ($dateDiff <= 90 && $dateDiff > 0) {
                        $this->_revisionseingriffDatum = $eingriff['datum'];
                        return 'J';
                    }
                }
            }
            $eingriffe = Concobox_prostata_e_5_3_1_Model_Helper::getProstatectomyByPriority($records);
            if (false !== $eingriffe) {
                foreach ($eingriffe as $eingriff) {
                    if ('1' == $eingriff['art_revision']) {
                        $dateDiff = date_diff_days($primaerOp['datum'], $eingriff['datum']);
                        if ($dateDiff <= 90 && $dateDiff > 0) {
                            $this->_revisionseingriffDatum = $eingriff['datum'];
                            return 'J';
                        }
                    }
                }
            }
        }
        return 'N';
    }


    /**
     * render renderD3RevisionseingriffDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD3RevisionseingriffDatum($patient, $records)
    {
        if (null !== $this->_revisionseingriffDatum) {
            return $this->_revisionseingriffDatum;
        }
        return false;
    }


    /**
     * render renderD3PostoperativeWundinfektion
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD3PostoperativeWundinfektion($patient, $records)
    {
        foreach ($records['komplikation'] as $komplikation) {
            if (true === in_array($komplikation['komplikation'], array('wi', 'wa1', 'wa2', 'wa3', 'wctc2'))) {
                $this->_cacheComplication = $komplikation;
                return '1';
            }
        }
        return '0';
    }


    /**
     * render renderD3PostoperativeWundinfektionDatum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD3PostoperativeWundinfektionDatum($patient, $records)
    {
        return $this->_cacheComplication['datum'];
    }


    /**
     * render renderD3NervenerhaltendeOperation
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD3NervenerhaltendeOperation($patient, $records)
    {
        /*
        $eingriffe = Concobox_prostata_e_5_3_1_Model_Helper::getProstatectomyByPriority($records);
        if (false !== $eingriffe) {
            foreach ($eingriffe as $eingriff) {
                if (('1' == $eingriff['art_rezidiv']) && (strlen($eingriff['nerverhalt_seite']) > 0)) {
                    $result = $eingriff['nerverhalt_seite'];
                    switch($eingriff['nerverhalt_seite']) {
                        case 'RL' :
                            $result = 'E';
                            break;
                    }
                    return $result;
                }
            }
        }
        return 'N';
        */
        // TODO: Wieder aktivieren wenn das Problem mit Onkozert geklärt ist!!!
        return '';
    }


    /**
     * render renderD4PostopResidualtumorLokale
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD4PostopResidualtumorLokale($patient, $records)
    {
        return $this->getLastFilled($records, 'tumorstatus', 'r_lokal');
    }


    /**
     * render renderD5Vorstellung
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD5Vorstellung($patient, $records)
    {
        $result = null;
        $rezidivEingriff = false;
        foreach ($records['eingriff'] as $eingriff) {
            if ('1' == $eingriff['art_rezidiv']) {
                $rezidivEingriff = true;
            }
            $result = 'NV';
        }
        if (true === $rezidivEingriff) {
            foreach ($records['therapieplan']->reverse() as $tp) {
                if (('tk' == $tp['grundlage']) && ('post' == $tp['zeitpunkt'])) {
                    $this->_cachePostoperativeTumorkonferenzTp = $tp;
                    $result = 'V';
                    break;
                }
            }
        }
        return $result;
    }


    /**
     * render renderD5Datum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD5Datum($patient, $records)
    {
        if (null !== $this->_cachePostoperativeTumorkonferenzTp) {
            return $this->_cachePostoperativeTumorkonferenzTp['datum'];
        }
        return false;
    }


    /**
     * render renderD6Studientyp
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD6Studientyp($patient, $records)
    {
        return $this->renderStudytype($records, 'D6');
    }


    /**
     * render renderD6DatumStudie
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD6DatumStudie($patient, $records)
    {

        return $this->renderStudyDate($records, 'D6');
    }


    /**
     * render renderD6PsychoonkologischeBetreuung
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD6PsychoonkologischeBetreuung($patient, $records)
    {
        return $this->renderPsychoOncology($records);
    }


    /**
     * render renderD6BeratungSozialdienst
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD6BeratungSozialdienst($patient, $records)
    {
        return $this->renderSocialService($records);
    }


    /**
     * render renderD6PatientInMorbiditaetskonferenzvorgestellt
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderD6PatientInMorbiditaetskonferenzvorgestellt($patient, $records)
    {
        return $this->renderMorbidityConference($records);
    }

}
