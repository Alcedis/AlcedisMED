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

$stageCalc = stageCalc::create($this->_db, $this->_params['sub']);

$relevantSelectWhere = "ts.erkrankung_id = t.erkrankung_id AND ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.anlass = t.anlass";
$relevantSelectOrder = "ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1";

$relevantSelects = array_merge(
  array(
      $this->_notCountSelect(),
      $stageCalc->select(null, 'uicc', true) . "AS 'uicc'",
      "(SELECT ts.m                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.m IS NOT NULL {$relevantSelectOrder}) AS m",
      "(SELECT ts.t                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.t IS NOT NULL {$relevantSelectOrder}) AS t",
      "(SELECT ts.n                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.n IS NOT NULL {$relevantSelectOrder}) AS n",
      "(SELECT ts.diagnose_seite    FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.diagnose IS NOT NULL {$relevantSelectOrder}) AS diagnose_seite",
  ),
  $additionalTsSelects
);

$opHaving = "5-323|5-324|5-325|5-326|5-327|5-328";

/**
 * code arrays
 */
$lungeCodes     = '5-323.13|5-323.23|5-323.33|5-323.43|5-323.53|5-323.63|5-323.73|5-323.83|5-323.93|5-323.x3|5-323.y3|5-324.3|5-324.7|5-324.9|5-324.b|5-327.1|5-327.3|5-327.5|5-327.7|5-328';

$bronchoCodes   = '5-324.22|5-324.23|5-324.32|5-324.33|5-324.34|5-324.62|5-324.a2|5-324.a3|5-324.a4|5-324.b2|5-324.b3|5-324.b4|5-324.x2|5-324.x3|5-324.x4|5-325.1|5-325.2|5-325.3|5-325.6|5-325.7|5-325.8';

$lungenresektion = $this->_eingriffCase(explode('|', $lungeCodes));
$pneumektomie    = $this->_eingriffCase(explode('|', '5-327|5-328'));
$broncho         = $this->_eingriffCase(explode('|', $bronchoCodes));
$anastomose      = $this->_eingriffCase(explode('|', '5-321.1'));

$preQuery = $this->_getPreQuery('diagnose LIKE "C34%"', $relevantSelects);

//Special date filter for lu02 only
$dateF = $this->_params['name'] == 'lu02' ? '1' : $this->_buildHaving('op_datum');

$query = "
    SELECT
        op.*,
        {$lungenresektion}                    AS lungenresektion,
        {$pneumektomie}                       AS pneumektomie,
        {$broncho}                            AS broncho_op,
        {$anastomose}                         AS anastomose,

        IF(COUNT(
            IF(
                ak.komplikation IN ('bsi','ndo','ndt','ani'),
                ak.komplikation_id,
                NULL
            )) > 0,
            1,
            NULL
        )                                     AS anastomoseinsuffizienz,

        IF(
            COUNT(IF(
                k.komplikation IN ('wi','wa1','wa2','wa3','wctc2'),
                k.komplikation_id,
                NULL
            )) > 0,
            1,
            IF(
                MAX(k.komplikation_id) IS NOT NULL,
                0,
                NULL
            )
        )                                     AS wundinfektion,
        IF(
            COUNT(IF(k.reintervention = 1 AND DATEDIFF(k.datum, op.op_datum) <= 90, k.komplikation_id, NULL)) > 0,
            1,
            IF(
                COUNT(IF(k.reintervention = 0, k.komplikation_id, NULL)) > 0 OR
                COUNT(IF(k.reintervention = 1 AND DATEDIFF(k.datum, op.op_datum) > 90, k.komplikation_id, NULL)) > 0,
                0,
                NULL
            )
        )                                                        AS revisions_op,
        MAX(x.todesdatum)                                        AS todesdatum
    FROM (
        SELECT
            sit.erkrankung_id,
            op.eingriff_id,
            op.patient_id,
            {$additionalFields}
            sit.nachname                                        AS nachname,
            sit.vorname                                         AS vorname,
            sit.geburtsdatum                                    AS geburtsdatum,
            sit.patient_nr                                      AS patient_nr,
            IF(
                sit.anlass = 'p' AND (
                    COUNT(th_sys.therapie_systemisch_id) > 0 OR
                    COUNT(IF(op.art_primaertumor IS NOT NULL, 1, NULL)) > 0 OR
                    COUNT(th_str.strahlentherapie_id) > 0 OR
                    COUNT(th_son.sonstige_therapie_id) > 0 OR
                    COUNT(IF(tp.palliative_versorgung = '1', 1, NULL)) > 0
                ),
                1,
                null
            )                                                   AS primaerfall,

            IFNULL(sit.diagnose_seite,'-')                       AS seite,

            IF(
               sit.anlass LIKE 'r%' AND MIN(hx.datum) IS NULL,
               IF(sit.start_date = '0000-00-00', sit.start_date_rezidiv, sit.start_date),
               MIN(hx.datum)
            )                                                   AS bezugsdatum,

            op.datum                                            AS op_datum,
            op.art_primaertumor                                 AS op_art_primaertumor,

            REPLACE(SUBSTRING(s.report_param, 5), ' ', ', ')    AS ops_codes,

            sit.uicc,
            sit.t,
            sit.n,
            sit.m,

            sit.start_date,
            sit.end_date
        FROM ($preQuery) sit
            {$this->_innerStatus()}
            {$this->_statusJoin('eingriff op')} AND op.diagnose_seite IN ('B', sit.diagnose_seite)
                LEFT JOIN histologie hx                 ON hx.erkrankung_id = op.erkrankung_id      AND hx.datum        BETWEEN sit.start_date AND sit.end_date AND hx.diagnose_seite IN ('B', sit.diagnose_seite)
                LEFT JOIN therapie_systemisch th_sys    ON th_sys.erkrankung_id = op.erkrankung_id  AND th_sys.beginn   BETWEEN sit.start_date AND sit.end_date
                LEFT JOIN strahlentherapie th_str       ON th_str.erkrankung_id = op.erkrankung_id  AND th_str.beginn   BETWEEN sit.start_date AND sit.end_date
                LEFT JOIN sonstige_therapie th_son      ON th_son.erkrankung_id = op.erkrankung_id  AND th_son.beginn   BETWEEN sit.start_date AND sit.end_date
                LEFT JOIN therapieplan tp               ON tp.erkrankung_id = op.erkrankung_id  AND tp.datum            BETWEEN sit.start_date AND sit.end_date
        WHERE
            {$this->_getNcState()}
        GROUP BY
             op.eingriff_id
        HAVING
            {$dateF} AND ({$this->_eingriffCase(explode('|', $opHaving), 'ops_codes')})
            {$additionalCondition}
    ) op
        LEFT JOIN komplikation k ON k.erkrankung_id = op.erkrankung_id AND k.eingriff_id = op.eingriff_id
        LEFT JOIN komplikation ak ON ak.erkrankung_id = op.erkrankung_id AND ak.datum BETWEEN op.start_date AND op.end_date
        LEFT JOIN abschluss x ON x.patient_id = op.patient_id
    GROUP BY
        op.eingriff_id
    HAVING
        primaerfall = 1
    ORDER BY
        nachname,
        vorname,
        bezugsdatum,
        op_datum
 ";

$data = sql_query_array($this->_db, $query);

$primaryCases = array();

foreach ($data as $i => $dataset) {
   $data[$i]['uicc'] = $dataset['uicc'] = $stageCalc->calc($dataset['uicc']);
   $diseaseId = $dataset['erkrankung_id'];
   $site      = $dataset['seite'];

   if (array_key_exists($diseaseId, $primaryCases) == false) {
      $primaryCases[$diseaseId] = array();
   }

   if (array_key_exists($site, $primaryCases[$diseaseId]) === false) {
      $primaryCases[$diseaseId][$site] = array(
          'index' => array($i),
          'content' => array(
              't' => $dataset['t'],
              'n' => $dataset['n'],
              'm' => $dataset['m'],
              'uicc' => $dataset['uicc']
          )
      );
   } else {
      $primaryCases[$diseaseId][$site]['index'][] = $i;
   }
}

foreach ($primaryCases as $diseaseId => $content) {
   if (count($content) > 1) {
      $indexFirst  = null;
      $indexSecond = null;
      $indexThird  = null;

      foreach (array_keys($content) as $site) {
         if ($indexFirst === null) {
            $indexFirst = $site;
         } else if($indexSecond === null) {
            $indexSecond = $site;
         } else {
            $indexThird = $site;
         }
      }

      $sPrimaryCaseIndex = $this->_removeLowerSeverityPrimaryCase(
          array(
              'dataIndex' => $indexFirst,
              'data' => $content[$indexFirst]['content']
          ),
          array(
                'dataIndex' => $indexSecond,
                'data' => $content[$indexSecond]['content']
          ), ($indexThird !== null
             ? array(
              'dataIndex' => $indexThird,
              'data' => $content[$indexThird]['content']
              )
             : null
          )
       );

       foreach ($sPrimaryCaseIndex as $site) {
          foreach ($content[$site]['index'] as $index) {
             $data[$index]['primaerfall'] = 2;
          }
       }
   }
}
?>