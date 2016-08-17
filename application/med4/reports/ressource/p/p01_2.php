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

    $relevantSelectWhere = "ts.erkrankung_id = t.erkrankung_id AND ts.anlass = t.anlass";

    $relevantSelects = array(
       "(
           SELECT
                IF(MAX(ts.nur_zweitmeinung) IS NOT NULL OR MAX(ts.nur_diagnosesicherung) IS NOT NULL OR MAX(ts.kein_fall) IS NOT NULL, 1, NULL)
           FROM tumorstatus ts
           WHERE
           {$relevantSelectWhere}
        ) AS nicht_zaehlen
        "
    );

    $preQuery = $this->_getPreQuery("diagnose = 'C61'", array_merge($relevantSelects,$additionalTsSelects));

    $bezugsjahr = isset($this->_params['jahr']) && strlen($this->_params['jahr']) ? $this->_params['jahr'] : date('Y');
    $vorjahr    = $bezugsjahr - 1;

    $query = "
        SELECT
            sit.nachname,
            sit.vorname,
            sit.anlass,
            sit.patient_id,
            GROUP_CONCAT(DISTINCT(kp_post.konferenz_id) SEPARATOR '|') AS 'konferenzen',

            IF(
                 sit.anlass = 'p' AND (
                     COUNT(DISTINCT IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = 1, s.form_id, NULL)) = 0 AND
                     COUNT(DISTINCT th_sys.therapie_systemisch_id) = 0 AND
                     COUNT(DISTINCT th_str.strahlentherapie_id) = 0 AND
                     COUNT(DISTINCT th_son.sonstige_therapie_id) = 0 AND
                     COUNT(DISTINCT IF('1' IN (tp.watchful_waiting, tp.active_surveillance, tp.palliative_versorgung), tp.therapieplan_id, NULL)) = 0
             ), 1, 0)                                                              AS 'nz',

             COUNT(DISTINCT kp_post.konferenz_patient_id) > 0 AS 'countit'

        FROM ($preQuery) sit
            {$this->_innerStatus()}

            {$this->_statusJoin('strahlentherapie th_str')}
            {$this->_statusJoin('therapie_systemisch th_sys')}
            {$this->_statusJoin('sonstige_therapie th_son')}

            LEFT JOIN therapieplan tp  ON s.form = 'therapieplan' AND tp.therapieplan_id = s.form_id
                                          AND (tp.org_id IS NULL OR tp.org_id = '{$this->_params['org_id']}')

            LEFT JOIN konferenz_patient kp_post ON s.form = 'konferenz_patient' AND
                                                   kp_post.konferenz_patient_id = s.form_id AND
                                                   LEFT(s.report_param, 4) = 'post' AND
                                                   SUBSTRING(s.report_param, 6) != '' AND
                                                   SUBSTRING(s.report_param, 6) BETWEEN '{$vorjahr}-01-01' AND '{$bezugsjahr}-12-31'
        WHERE
           {$this->_getNcState()}
        GROUP BY
           sit.patient_id,
           sit.erkrankung_id,
           sit.anlass
        HAVING
           countit = '1'
           {$this->_getNcState('p01_2')}
    ";

    $patienten = sql_query_array($this->_db, $query);

    $patientPool = array();

    $konferenzPatienten = array();

    $konferenzIds = array(0 => 1);

    foreach ($patienten as $patient) {
        if (array_key_exists($patient['patient_id'], $patientPool) === false) {
            $patientPool[$patient['patient_id']] = concat(array($patient['nachname'], $patient['vorname']),', ');
        }

        $kids = explode('|', $patient['konferenzen']);

        foreach ($kids as $kid) {
            $konferenzIds[$kid] = 1;

            $konferenzPatienten[$kid][$patient['patient_id']] = 1;
        }
    }

    $konferenzIds = implode(',', array_keys($konferenzIds));

    $query = "
        SELECT
            k.konferenz_id,
            YEAR(k.datum) AS 'year',
            k.bez,
            datum,
            COUNT(DISTINCT IF(kt_user.fachabteilung IN ('2200', 'Z4700'), kt_user.user_id, NULL))         AS 'user_urologie',
            COUNT(DISTINCT IF(kt_user.fachabteilung IN ('3300', '3305'), kt_user.user_id, NULL))          AS 'user_strahlen',
            COUNT(DISTINCT IF(kt_user.fachabteilung IN ('0500', '0510', 'Z4700'), kt_user.user_id, NULL)) AS 'user_haema',
            COUNT(DISTINCT IF(kt_user.fachabteilung IN ('Z4400'), kt_user.user_id, NULL))                 AS 'user_patho',
            null                                                                                          AS 'patients'
        FROM konferenz k
            LEFT JOIN konferenz_teilnehmer kt ON kt.konferenz_id = k.konferenz_id AND kt.teilgenommen IS NOT NULL
                LEFT JOIN user kt_user ON kt_user.user_id = kt.user_id
        WHERE
            k.konferenz_id IN ({$konferenzIds}) AND k.final IS NOT NULL
        GROUP BY
            k.konferenz_id
    ";

    $data = sql_query_array($this->_db, $query);

    foreach ($data as $i => $dataset) {
        $patients = array();

        foreach ($konferenzPatienten[$dataset['konferenz_id']] as $kp => $dummy) {
            if (array_key_exists($kp, $patientPool) === true) {
                $patients[] = $patientPool[$kp];
            }
        }

        $data[$i]['patients'] = concat($patients, '; ');
    }

?>
