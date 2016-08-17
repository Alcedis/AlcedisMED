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

$query_konferenz_patient = "
   SELECT
      kp.konferenz_patient_id,
      CONCAT_WS(', ',
         DATE_FORMAT(k.datum, '%d.%m.%Y'),
         CONCAT_WS(' ',
            art.bez,
            CONCAT_WS('',
               '(Datenstand: ',
               DATE_FORMAT(kp.datenstand_datum, '%d.%m.%Y'),
               ')'
            )
         )
      )
   FROM konferenz_patient kp
      LEFT JOIN konferenz k ON k.konferenz_id = kp.konferenz_id
      LEFT JOIN l_basic art ON art.klasse = 'tumorkonferenz_art' AND art.code = kp.art

      LEFT JOIN therapieplan tp ON tp.konferenz_patient_id = kp.konferenz_patient_id
   WHERE
      kp.patient_id = '$patient_id' AND kp.erkrankung_id = '$erkrankung_id'
";

$query_zweitmeinung = "
    SELECT
        zw.zweitmeinung_id,
        CONCAT_WS(' - ','Zweitmeinung',
        CONCAT_WS('',
            '(Datenstand: ',
                DATE_FORMAT(zw.datenstand_datum, '%d.%m.%Y'),
            ')'
        ))
    FROM zweitmeinung zw
        LEFT JOIN therapieplan tp ON tp.zweitmeinung_id = zw.zweitmeinung_id
    WHERE
        zw.patient_id = '$patient_id' AND zw.erkrankung_id = '$erkrankung_id'
";


$query_org = "
    SELECT org_id, name FROM org
";

$fields = array(
   'therapieplan_id'                   => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '', 'reference' => array('therapie_systemisch', 'strahlentherapie', 'eingriff', 'sonstige_therapie', 'therapieplan_abweichung')),
   'erkrankung_id'                     => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
   'patient_id'                        => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
   'datum'                             => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '', 'range' => false),
   'grundlage'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'therapieplanung_grundlage') ),
   'org_id'                            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'query'      , 'ext' => $query_org ),
   'leistungserbringer'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'leistungserbringer') ),
   'zeitpunkt'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'therapieplan_zeitpunkt') ),
   'zweitmeinung_id'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'query'      , 'ext' => $query_zweitmeinung, 'parent_status' => 'zweitmeinung' ),
   'konferenz_patient_id'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'query'      , 'ext' => $query_konferenz_patient, 'parent_status' => 'konferenz_patient' ),
   'vorgestellt'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'picker'     , 'ext' => array( 'query' => $querys['query_user'], 'type' => 'arzt'),'preselect' => 'user.user_id' ),
   'vorgestellt2'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'picker'     , 'ext' => array( 'query' => $querys['query_user'], 'type' => 'arzt'),'preselect' => 'user.user_id' ),
   'grund_keine_konferenz'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'abweichung_konferenz') ),
   'grund_keine_konferenz_sonstige'    => array('req' => 0, 'size' => '30' , 'maxlen' =>'255' , 'type' => 'string'   , 'ext' => ''),
   'intention'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'intention_gesamt') ),
   'op'                                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'op_intention'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'intention_eingriff') ),
   'op_art_brusterhaltend'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'op_art_mastektomie'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'op_art_nachresektion'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'op_art_sln'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'op_art_axilla'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'keine_axilla_grund'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'abweichung_axilla') ),
   'op_art_prostata'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'op_art_prostata') ),
   'op_art_nerverhaltend'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'op_art_nerverhaltend') ),
   'op_art_lymphadenektomie'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'op_art_transplantation_autolog'    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'op_art_transplantation_allogen_v'  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'op_art_transplantation_allogen_nv' => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'op_art_transplantation_syngen'     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'op_extern'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'op_sonstige'                       => array('req' => 0, 'size' =>'46', 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
   'op_art'                            => array('req' => 0, 'size' =>'46', 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
   'strahlen_indiziert'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'strahlen'                          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'strahlen_intention'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'intention') ),
   'strahlen_mamma'                    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'strahlen_mamma') ),
   'strahlen_axilla'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'lrb') ),
   'strahlen_lk_supra'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'lrb') ),
   'strahlen_lk_para'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'lrb') ),
   'strahlen_thoraxwand'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'lrb') ),
   'strahlen_sonstige'                 => array('req' => 0, 'size' =>'46', 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
   'strahlen_art'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'strahlen_art_prostata') ),
   'strahlen_zielvolumen'              => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
   'strahlen_gesamtdosis'              => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'float'        , 'ext' => '' ),
   'strahlen_einzeldosis'              => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'float'        , 'ext' => '' ),
   'strahlen_extern'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'strahlen_lokalisation'             => array('req' => 0, 'size' =>'46', 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
   'chemo_indiziert'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'chemo'                             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'chemo_intention'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'intention') ),
   'chemo_extern'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'immun_indiziert'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'immun'                             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'immun_intention'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'intention') ),
   'ah_id'                             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'query'      , 'ext' => $querys['query_vorlage_therapie'] . "AND LEFT(art, 2) = 'ah' ORDER BY bez", 'preselect' => 'vorlage_therapie_id' ),
   'chemo_id'                          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'query'      , 'ext' => $querys['query_vorlage_therapie'] . "AND LEFT(art, 1) = 'c' ORDER BY bez", 'preselect' => 'vorlage_therapie_id'  ),
   'immun_id'                          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'query'      , 'ext' => $querys['query_vorlage_therapie'] . "AND LOCATE('i', art) > 0 ORDER BY bez", 'preselect' => 'vorlage_therapie_id'  ),
   'immun_extern'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ah_indiziert'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'ah'                                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'ah_intention'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'intention') ),
   'ah_therapiedauer_prostata'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'ah_therapiedauer_prostata') ),
   'ah_therapiedauer_monate'           => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
   'ah_extern'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'andere_indiziert'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'andere'                            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'andere_intention'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'intention') ),
   'andere_id'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'query'      , 'ext' => $querys['query_vorlage_therapie'] . " ORDER BY bez", 'preselect' => 'vorlage_therapie_id'  ),
   'andere_extern'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'sonstige_indiziert'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'sonstige'                          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'sonstige_schema'                   => array('req' => 0, 'size' =>'46', 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
   'sonstige_intention'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'intention') ),
   'sonstige_extern'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'watchful_waiting'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'active_surveillance'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'abweichung_leitlinie'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'nachsorge'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'abweichung_leitlinie_grund'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'abweichung_leitlinie') ),
   'studie'                            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'vorlage_studie_id'                 => array('req' => 0, 'size' => '450',  'maxlen' => '' , 'type' => 'picker'    ,
            'ext' => array(
                    'query' => $querys['query_vorlage_studie'],
                    'type' => 'query',
                    'table' => 'vorlage_studie'
            ),
            'preselect' => 'vorlage_studie_id'
   ),
   'studie_abweichung'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'studie_abweichung') ),
   'nachbehandler_id'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'picker'     , 'ext' => array( 'query' => $querys['query_user'], 'type' => 'arzt'),'preselect' => 'user.user_id' ),
   'palliative_versorgung'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'datum_palliative_versorgung'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => ''),
   'bem_palliative_versorgung'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => ''),
   'bem'                               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => ''),
   'createuser'                        => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'createtime'                        => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updateuser'                        => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updatetime'                        => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => '')
);

?>
