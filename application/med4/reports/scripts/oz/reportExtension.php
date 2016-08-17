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

class reportExtensionOz extends reportMath
{
    /**
     * _diagnosen
     *
     * @access  protected
     * @var     array
     */
    protected $_diagnosen = array(
        'darm' => array(
            'C18',
            'C19',
            'C20',
            'D01.0',
            'D01.1',
            'D01.2'
        ),
        'pankreas' => array(
            'C25'
        ),
        'magen' => array(
            'C16',
            'D00.2'
        ),
        'speiseroehre' => array(
            'C15'
        ),
        'lebertumore' => array(
            'C22',
            'C24',
            'D01.5'
        ),
        'endokrine' => array(
            'C73',
            'D09.3',
            'C74'
        ),
        'lymphome' => array(
            'C81',
            'C82',
            'C83',
            'C84',
            'C85',
            'C86',
        ),
        'laeukemie' => array(
            'C91',
            'C92',
            'C93',
            'C94',
            'C95'
        ),
        'plasmozyten' => array(
            'C88.0',
            'C88.4',
            'C90',
            'C96'
        ),
        'mamma' => array(
            'C50',
            'D05'
        ),
        'gyn' => array(
            'C53',
            'D06',
            'C54',
            'C55',
            'C56',
            'D39.1',
            'C51',
            'C52',
            'D07.1',
            'D07.2'
        ),
        'haut' => array(
            'C43',
            'D03',
            'C44',
            'D04'
        ),
        'lunge' => array(
            'C34',
            'D02.2'
        ),
        'prostata' => array(
            'C61',
            'D07.5'
        ),
        'hoden' => array(
            'C62',
            'C60',
            'D07.4'
        ),
        'niere' => array(
            'C64'
        ),
        'harnblase' => array(
            'C67',
            'D09.0'
        ),
        'muskuloskel' => array(
            'C49'
        ),
        'kopf' => array(
            'C00',
            'C01',
            'C02',
            'C03',
            'C04',
            'C05',
            'C06',
            'C07',
            'C08',
            'C09',
            'C10',
            'C11',
            'C12',
            'C13',
            'C14',
            'C32',
            'D00.0',
            'D02.0'
        ),
        'not' => array(
            'C70',
            'C71',
            'C72',
            'D32',
            'D33.3',
            'D42',
            'D43',
            'D44.3',
            'D44.4',
            'D44.5'
        ),
        'sonstige' => array(
            'C17',
            'C21',
            'C23',
            'C26',
            'C30',
            'C31',
            'C33',
            'C37',
            'C38',
            'C39',
            'C40',
            'C41',
            'C45.0',
            'C45.1',
            'C45.2',
            'C45.7',
            'C45.9',
            'C46.0',
            'C46.1',
            'C46.2',
            'C46.3',
            'C46.7',
            'C46.8',
            'C46.9',
            'C47',
            'C48',
            'C57',
            'C58',
            'C63',
            'C65',
            'C66',
            'C68',
            'C69',
            'C75',
            'C76',
            'C77',
            'C78',
            'C79',
            'C80',
            'C88.1',
            'C88.2',
            'C88.3',
            'C88.5',
            'C88.6',
            'C88.7',
            'C88.8',
            'C88.9',
            'C97',
            'D01.3',
            'D01.4',
            'D01.7',
            'D01.9',
            'D02.1',
            'D02.3',
            'D02.4',
            'D07.0',
            'D07.3',
            'D07.6',
            'D09.1',
            'D09.2',
            'D09.7',
            'D09.9'
        )
    );


    /**
     * _buildDiagCase
     *
     * @access  protected
     * @return  string
     */
    protected function _buildDiagCase()
    {
        $return = 'CASE';

        foreach ($this->_diagnosen as $diagnoseType => $diagnosen) {
            $when = array();

            foreach ($diagnosen as $diagnose) {
                $length = strlen($diagnose);
                $when[] = "sit.diagnose = '{$diagnose}' OR LEFT(sit.diagnose, {$length}) = '{$diagnose}'";
            }

            $return .= ' WHEN ' . implode(' OR ', $when) . " THEN '{$diagnoseType}'";
        }

        $return .= "ELSE NULL END AS 'diagnosetyp'";

        return $return;
    }


    /**
     * _getPrimaerfalLCases
     *
     * @access  protected
     * @return  string
     */
    protected function _getPrimaerfalLCases()
    {
       return "
         CASE sit.diagnosetyp
             WHEN 'prostata' THEN IF(
                  sit.anlass = 'p' AND (
                  COUNT(DISTINCT IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = 1, s.form_id, NULL)) > 0 OR
                  COUNT(DISTINCT th_sys.therapie_systemisch_id) > 0 OR
                  COUNT(DISTINCT th_str.strahlentherapie_id) > 0 OR
                  COUNT(DISTINCT IF('1' IN (tp.watchful_waiting, tp.active_surveillance), tp.therapieplan_id, NULL)) > 0
                 ), 1, 0)
             WHEN 'lunge' THEN IF(
                 sit.anlass = 'p' AND (
                     COUNT(DISTINCT IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = 1, s.form_id, NULL)) > 0 OR
                     COUNT(DISTINCT th_sys.therapie_systemisch_id) > 0 OR
                     COUNT(DISTINCT th_str.strahlentherapie_id) > 0 OR
                     COUNT(DISTINCT th_son.sonstige_therapie_id) > 0 OR
                     COUNT(IF(tp.palliative_versorgung = '1', 1, NULL)) > 0
                 ), 1, 0
             )
             WHEN 'haut' THEN IF(
                 sit.anlass = 'p' AND sit.diagnose LIKE 'C%',
                 1,
                 0
             )
             ELSE IF(sit.anlass = 'p', 1, 0)
         END
       ";
    }


    /**
     * _unsetPrimaryHauete
     *
     * @access  protected
     * @param   array   $data
     * @param   array   $haut
     * @return  array
     */
    protected function _unsetPrimaryHauete($data, $haut)
    {
       //trying a new style
         foreach ($haut as $patientId => $records):
            if (count($records) === 1): continue; endif;
            $keyInDataArray = null;
            $bzgDate = null;

            foreach ($records as $record):
               if ($record['bzg'] === null): continue; endif;
               if ($bzgDate === null || $record['bzg'] < $bzgDate):
                  $bzgDate = $record['bzg'];
                  $keyInDataArray = $record['key'];
               endif;
            endforeach;

            if ($keyInDataArray !== null):
               foreach ($records as $record):
                  if ($record['key'] !== $keyInDataArray):
                     $data[$record['key']]['primaerfall'] = 0;
                  endif;
               endforeach;
            endif;
         endforeach;

         return $data;
    }


    /**
     * _buildBezugsdatumCases
     *
     * @access  protected
     * @return  string
     */
    protected function _buildBezugsdatumCases()
    {
       //zusammenkopierte bezugsdatumsbedingungen anhand des diagnosetyps (aka. erkrankung) aus *Z01-Auswertungen
       //für p01_d1 wird immer das datum der erstvorstellung genommen
      return "
         CASE
             WHEN sit.diagnosetyp = 'darm' THEN IFNULL(
                 IFNULL(
                    MIN(IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1', s.form_date, NULL)),
                    MIN(h.datum)
                 ),
                 IF(
                    sit.anlass LIKE 'r%',
                    IF(sit.start_date = '0000-00-00', sit.start_date_rezidiv, sit.start_date),
                    NULL
                 )
              )
             WHEN  sit.diagnosetyp = 'prostata' THEN
             IF(
             sit.anlass LIKE 'r%' AND (
                IFNULL(
                   MIN(
                      DISTINCT IF(
                         s.form = 'konferenz_patient' AND
                         LEFT(s.report_param, 4) = 'prae' AND
                         SUBSTRING(s.report_param, 6) != '' AND
                         SUBSTRING(s.report_param, 6) BETWEEN sit.start_date AND sit.end_date,
                         SUBSTRING(s.report_param, 6),
                         NULL
                      )
                   ),
                   MIN(tp.datum)
                )
             ) IS NULL,
             IF(sit.start_date = '0000-00-00', sit.start_date_rezidiv, sit.start_date),
             IF(
                sit.anlass LIKE 'r%',
                IFNULL(
                   MIN(
                      DISTINCT IF(
                         s.form = 'konferenz_patient' AND
                         LEFT(s.report_param, 4) = 'prae' AND
                         SUBSTRING(s.report_param, 6) != '' AND
                         SUBSTRING(s.report_param, 6) BETWEEN sit.start_date AND sit.end_date,
                         SUBSTRING(s.report_param, 6),
                         NULL
                      )
                   ),
                   MIN(tp.datum)
                ),
                IFNULL(
                   MIN(auf.aufnahmedatum),
                   IFNULL(
                      MIN(
                         DISTINCT IF(
                            s.form = 'konferenz_patient' AND
                            LEFT(s.report_param, 4) = 'prae' AND
                            SUBSTRING(s.report_param, 6) != '' AND
                            SUBSTRING(s.report_param, 6) BETWEEN sit.start_date AND sit.end_date,
                            SUBSTRING(s.report_param, 6),
                            NULL
                         )
                      ),
                      MIN(tp.datum)
                   )
                )
             )
          )
         WHEN sit.diagnosetyp IN ('laeukemie', 'lymphome', 'sonstige', 'plasmozyten') THEN
             IF(sit.anlass LIKE 'r%' AND MIN(z.datum) IS NULL AND MIN(h.datum) IS NULL,
                 IF(sit.start_date = '0000-00-00', sit.start_date_rezidiv, sit.start_date),
                 IF(
                    MIN(z.datum) IS NOT NULL AND MIN(h.datum) IS NOT NULL,
                    IF(MIN(z.datum) < MIN(h.datum), MIN(z.datum), MIN(h.datum))
                    , IF(
                       MIN(z.datum) IS NOT NULL,
                       MIN(z.datum),
                       MIN(h.datum)
                    )
                 )
             )
         ELSE
             IF(sit.anlass LIKE 'r%' AND MIN(h.datum) IS NULL,
                 IF(sit.start_date = '0000-00-00', sit.start_date_rezidiv, sit.start_date),
                 MIN(h.datum)
             )
         END
      ";
    }

     /**
       * Convert data for oz04.1 report
       *
       * @param $data
       */
     protected function _convertOz041ReportData($data)
     {
         $config = $this->loadConfigs('oz04');

         foreach ($data as &$dataset) {
             $addon = $dataset['addon'];

             $erkrankung = $dataset['erkrankung'];

             $dataset['erkrankung'] = $config["erkrankung_{$erkrankung}"];

             unset(
                 $dataset['patient_id'],
                 $dataset['addon'],
                 $dataset['h_beginn'],
                 $dataset['041_ereignis'],
                 $dataset['041_ende'],
                 $dataset['042_ereignis'],
                 $dataset['042_ende'],
                 $dataset['043_ereignis'],
                 $dataset['043_ende'],
                 $dataset['044_ereignis'],
                 $dataset['044_ende'],
                 $dataset['045_ereignis'],
                 $dataset['045_beginn'],
                 $dataset['045_ende'],
                 $dataset['pt_section'],
                 $dataset['anlass'],
                 $dataset['start_date'],
                 $dataset['end_date'],
                 $dataset['erkrankung_id'],
                 $dataset['zugeordnet_zu'],
                 $dataset['prostata_nz'],
                 $dataset['diagnosetyp'],
                 $addon['section']
             );

             $dataset = array_merge($dataset, $addon);
         }

         return $data;
     }
}

?>
