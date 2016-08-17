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

$konferenzId = $this->getParam('konferenzId');

$data = array();

$datasets = sql_query_array($this->_db, "
   SELECT
      p.nachname,
      p.vorname,
      DATE_FORMAT(p.geburtsdatum, '%d.%m.%Y') AS 'geburtsdatum',
      kp.art,
      e_bez.bez AS 'erkrankung',
      tp.*
   FROM konferenz_patient kp
      INNER JOIN patient p ON p.patient_id = kp.patient_id
      LEFT JOIN erkrankung e ON e.erkrankung_id = kp.erkrankung_id
         LEFT JOIN l_basic       e_bez          ON e_bez.klasse = IF(e.erkrankung_detail IS NOT NULL, 'erkrankung_sst_detail', 'erkrankung') AND
                                                   e_bez.code = IF(e.erkrankung_detail IS NOT NULL, e.erkrankung_detail, e.erkrankung)

      LEFT JOIN therapieplan tp ON tp.konferenz_patient_id = kp.konferenz_patient_id
   WHERE
      kp.konferenz_id = '{$konferenzId}'
   ORDER BY
      p.nachname ASC,
      p.vorname ASC
");

$this->_smarty->config_load('app/therapieplan.conf', 'rec');
$tpConfig = $this->_smarty->get_config_vars();

foreach ($datasets as $i => $dataset) {
    $i += 4;

    $d = $dataset;

    $data["A$i"] = utf8_encode($dataset['nachname']);
    $data["B$i"] = utf8_encode($dataset['vorname']);
    $data["C$i"] = $dataset['geburtsdatum'];
    $data["D$i"] = utf8_encode($dataset['erkrankung']);
    $data["E$i"] = utf8_encode($this->_translateLookup($dataset['art'], 'tumorkonferenz_art'));
    $data["F$i"] = utf8_encode(concat(array(
        isFilled($dataset['intention'], concat(array(
            $tpConfig['intention'],
            $this->_translateLookup($dataset['intention'], 'intention_gesamt')
        ),': ')),
        isFilled($dataset['op'], concat(array(
            $tpConfig['lbl_eingriff'],
            concat(array(
                $this->_translateLookup($dataset['op'], 'jn'),
                isFilled($dataset['op_intention'], concat(array($tpConfig['lbl_intention'], $this->_translateLookup($dataset['op_intention'], 'intention_eingriff')),': ')),
                isFilled($dataset['op_extern'], $tpConfig['op_extern']),
                isFilled($dataset['op_art_prostata'], concat(array($tpConfig['op_art_prostata'], $this->_translateLookup($dataset['op_art_prostata'], 'op_art_prostata')),': ')),
                isFilled($dataset['op_art_nerverhaltend'], concat(array($tpConfig['op_art_nerverhaltend'], $this->_translateLookup($dataset['op_art_nerverhaltend'], 'op_art_nerverhaltend')),': ')),
                isFilled($dataset['op_art_transplantation_autolog'], $tpConfig['op_art_transplantation_autolog']),
                isFilled($dataset['op_art_transplantation_allogen_v'], $tpConfig['op_art_transplantation_allogen_v']),
                isFilled($dataset['op_art_transplantation_allogen_nv'], $tpConfig['op_art_transplantation_allogen_nv']),
                isFilled($dataset['op_art_transplantation_syngen'], $tpConfig['op_art_transplantation_syngen']),
                isFilled($d['op_art_brusterhaltend'], $tpConfig['op_art_brusterhaltend']),
                isFilled($d['op_art_mastektomie'], $tpConfig['op_art_mastektomie']),
                isFilled($d['op_art_nachresektion'], $tpConfig['op_art_nachresektion']),
                isFilled($d['op_art_sln'], $tpConfig['op_art_sln']),
                isFilled($d['op_art_axilla'], $tpConfig['op_art_axilla']),
                isFilled($d['keine_axilla_grund'], concat(array($tpConfig['keine_axilla_grund'], $this->_translateLookup($d['keine_axilla_grund'], 'abweichung_axilla')),': ')),
            ), ', ')
        ),': ')),

        //Strahlentherapie
        isFilled(
            concat(array($d['strahlen_indiziert'], $d['strahlen'], $d['strahlen_intention'], $d['strahlen_mamma'], $d['strahlen_axilla'], $d['strahlen_lk_supra'], $d['strahlen_lk_para'], $d['strahlen_thoraxwand'], $d['strahlen_art'], $d['strahlen_zielvolumen'], $d['strahlen_gesamtdosis'], $d['strahlen_einzeldosis'], $d['strahlen_extern']),''),
            concat(array(
                $tpConfig['strahlen'],
                concat(array(
                    isFilled($d['strahlen_indiziert'],  concat(array($tpConfig['lbl_indiziert'], $this->_translateLookup($d['strahlen_indiziert'], 'jn')),': ')),
                    isFilled($d['strahlen'],            concat(array($tpConfig['lbl_geplant'], $this->_translateLookup($d['strahlen'], 'jn')),': ')),
                    isFilled($d['strahlen_mamma'],      concat(array(concat(array($tpConfig['lbl_lokalisation'], $tpConfig['strahlen_mamma']),' '), $this->_translateLookup($d['strahlen_mamma'], 'strahlen_mamma')),': ')),
                    isFilled($d['strahlen_axilla'],     concat(array(concat(array($tpConfig['lbl_lokalisation'], $tpConfig['strahlen_axilla']),' '), $this->_translateLookup($d['strahlen_axilla'], 'lrb')),': ')),
                    isFilled($d['strahlen_lk_supra'],   concat(array(concat(array($tpConfig['lbl_lokalisation'], $tpConfig['strahlen_lk_supra']),' '), $this->_translateLookup($d['strahlen_lk_supra'], 'lrb')),': ')),
                    isFilled($d['strahlen_lk_para'],    concat(array(concat(array($tpConfig['lbl_lokalisation'], $tpConfig['strahlen_lk_para']),' '), $this->_translateLookup($d['strahlen_lk_para'], 'lrb')),': ')),
                    isFilled($d['strahlen_thoraxwand'], concat(array(concat(array($tpConfig['lbl_lokalisation'], $tpConfig['strahlen_thoraxwand']),' '), $this->_translateLookup($d['strahlen_thoraxwand'], 'lrb')),': ')),
                    isFilled($d['strahlen_intention'],  concat(array($tpConfig['lbl_intention'], $this->_translateLookup($d['strahlen_intention'], 'intention')),': ')),
                    isFilled($d['strahlen_extern'],     $tpConfig['strahlen_extern']),
                    isFilled($d['strahlen_art'],        concat(array($tpConfig['strahlen_art'], $this->_translateLookup($d['strahlen_art'], 'strahlen_art_prostata')),': ')),
                    isFilled($d['strahlen_zielvolumen'], concat(array($tpConfig['strahlen_zielvolumen'], concat(array($d['strahlen_zielvolumen'], $tpConfig['lbl_ml']), ' ')),': ')),
                    isFilled($d['strahlen_gesamtdosis'], concat(array($tpConfig['strahlen_gesamtdosis'], concat(array($d['strahlen_gesamtdosis'], $tpConfig['lbl_gyghd']), ' ')),': ')),
                    isFilled($d['strahlen_einzeldosis'], concat(array($tpConfig['strahlen_einzeldosis'], concat(array($d['strahlen_einzeldosis'], $tpConfig['lbl_gy']), ' ')),': ')),
                ),', ')
            ),': ')
        ),

        //Chemotherapie
        isFilled(
            concat(array($d['chemo_indiziert'], $d['chemo'], $d['chemo_intention'], $d['chemo_extern'], $d['chemo_id']),''),
            concat(array(
                $tpConfig['chemo'],
                concat(array(
                    isFilled($d['chemo_indiziert'], concat(array($tpConfig['lbl_indiziert'], $this->_translateLookup($d['chemo_indiziert'], 'jn')),': ')),
                    isFilled($d['chemo'],           concat(array($tpConfig['lbl_geplant'], $this->_translateLookup($d['chemo'], 'jn')),': ')),
                    isFilled($d['chemo_id'],        concat(array($tpConfig['chemo_id'], dlookup($this->_db, 'vorlage_therapie', 'bez', "vorlage_therapie_id = '{$d['chemo_id']}'")),': ')),
                    isFilled($d['chemo_intention'],  concat(array($tpConfig['lbl_intention'], $this->_translateLookup($d['chemo_intention'], 'intention')),': ')),
                    isFilled($d['chemo_extern'],    $tpConfig['chemo_extern'])
                ),', ')
            ),': ')
        ),

        //Immuntherapie
        isFilled(
            concat(array($d['immun_indiziert'], $d['immun'], $d['immun_intention'], $d['immun_extern'], $d['immun_id']),''),
            concat(array(
                $tpConfig['immun'],
                concat(array(
                    isFilled($d['immun_indiziert'], concat(array($tpConfig['lbl_indiziert'], $this->_translateLookup($d['immun_indiziert'], 'jn')),': ')),
                    isFilled($d['immun'],           concat(array($tpConfig['lbl_geplant'], $this->_translateLookup($d['immun'], 'jn')),': ')),
                    isFilled($d['immun_id'],        concat(array($tpConfig['immun_id'], dlookup($this->_db, 'vorlage_therapie', 'bez', "vorlage_therapie_id = '{$d['immun_id']}'")),': ')),
                    isFilled($d['immun_intention'], concat(array($tpConfig['lbl_intention'], $this->_translateLookup($d['immun_intention'], 'intention')),': ')),
                    isFilled($d['immun_extern'],    $tpConfig['immun_extern'])
                ),', ')
            ),': ')
        ),

        //Antihormonelle Therapie
        isFilled(
            concat(array($d['ah_indiziert'], $d['ah'], $d['ah_intention'], $d['ah_extern'], $d['ah_id'], $d['ah_therapiedauer_prostata'], $d['ah_therapiedauer_monate']),''),
            concat(array(
                $tpConfig['ah'],
                concat(array(
                    isFilled($d['ah_indiziert'], concat(array($tpConfig['lbl_indiziert'], $this->_translateLookup($d['ah_indiziert'], 'jn')),': ')),
                    isFilled($d['ah'],           concat(array($tpConfig['lbl_geplant'], $this->_translateLookup($d['ah'], 'jn')),': ')),
                    isFilled($d['ah_id'],        concat(array($tpConfig['ah_id'], dlookup($this->_db, 'vorlage_therapie', 'bez', "vorlage_therapie_id = '{$d['ah_id']}'")),': ')),
                    isFilled($d['ah_intention'], concat(array($tpConfig['lbl_intention'], $this->_translateLookup($d['ah_intention'], 'intention')),': ')),
                    isFilled($d['ah_extern'],    $tpConfig['ah_extern']),
                    isFilled(
                        concat(array($d['ah_therapiedauer_prostata'], $d['ah_therapiedauer_monate']),''),
                        concat(array($tpConfig['ah_therapiedauer_prostata'],
                            concat(array(
                                $this->_translateLookup($d['ah_therapiedauer_prostata'], 'ah_therapiedauer_prostata'),
                                attach_label($d['ah_therapiedauer_monate'], $tpConfig['lbl_monate'])
                            ),', ')
                        ),': ')),
                ),', ')
            ),': ')
        ),

        //Andere Systemische Therapie
        isFilled(
            concat(array($d['andere_indiziert'], $d['andere'], $d['andere_intention'], $d['andere_extern'], $d['andere_id']),''),
            concat(array(
                $tpConfig['andere'],
                concat(array(
                    isFilled($d['andere_indiziert'], concat(array($tpConfig['lbl_indiziert'], $this->_translateLookup($d['andere_indiziert'], 'jn')),': ')),
                    isFilled($d['andere'],           concat(array($tpConfig['lbl_geplant'], $this->_translateLookup($d['andere'], 'jn')),': ')),
                    isFilled($d['andere_id'],        concat(array($tpConfig['andere_id'], dlookup($this->_db, 'vorlage_therapie', 'bez', "vorlage_therapie_id = '{$d['andere_id']}'")),': ')),
                    isFilled($d['andere_intention'], concat(array($tpConfig['lbl_intention'], $this->_translateLookup($d['andere_intention'], 'intention')),': ')),
                    isFilled($d['andere_extern'],    $tpConfig['andere_extern'])
                ),', ')
            ),': ')
        ),

        //sonstige Therapie
        isFilled(
            concat(array($d['sonstige_indiziert'], $d['sonstige'], $d['sonstige_intention'], $d['sonstige_extern'], $d['sonstige_schema']),''),
            concat(array(
                $tpConfig['sonstige'],
                concat(array(
                    isFilled($d['sonstige_indiziert'], concat(array($tpConfig['lbl_indiziert'], $this->_translateLookup($d['sonstige_indiziert'], 'jn')),': ')),
                    isFilled($d['sonstige'],           concat(array($tpConfig['lbl_geplant'], $this->_translateLookup($d['sonstige'], 'jn')),': ')),
                    isFilled($d['sonstige_schema'],    concat(array($tpConfig['sonstige_schema'], $d['sonstige_schema']),': ')),
                    isFilled($d['sonstige_intention'], concat(array($tpConfig['lbl_intention'], $this->_translateLookup($d['sonstige_intention'], 'intention')),': ')),
                    isFilled($d['sonstige_extern'],    $tpConfig['sonstige_extern'])
                ),', ')
            ),': ')
        ),

        isFilled($dataset['watchful_waiting'], concat(array($tpConfig['watchful_waiting'], $this->_translateLookup($dataset['watchful_waiting'], 'jn')),': ')),
        isFilled($dataset['active_surveillance'], concat(array($tpConfig['active_surveillance'], $this->_translateLookup($dataset['active_surveillance'], 'jn')),': ')),
        isFilled($dataset['abweichung_leitlinie'], concat(array($tpConfig['abweichung_leitlinie'], $this->_translateLookup($dataset['abweichung_leitlinie'], 'jn')),': ')),
        isFilled($dataset['nachsorge'], concat(array($tpConfig['nachsorge'], $this->_translateLookup($dataset['nachsorge'], 'jn')),': ')),
        isFilled($dataset['abweichung_leitlinie_grund'], concat(array($tpConfig['abweichung_leitlinie_grund'], $this->_translateLookup($dataset['abweichung_leitlinie_grund'], 'abweichung_leitlinie')),': ')),

        //Studie
        isFilled(
            concat(array($d['studie'], $d['vorlage_studie_id'], $d['studie_abweichung']),''),
            concat(array(
                $tpConfig['studie'],
                concat(array(
                    isFilled($d['studie'], $this->_translateLookup($d['studie'], 'jn')),
                    isFilled($d['vorlage_studie_id'], concat(array($tpConfig['vorlage_studie_id'], dlookup($this->_db, 'vorlage_studie', 'bez', "vorlage_studie_id = '{$d['vorlage_studie_id']}'")),': ')),
                    isFilled($d['studie_abweichung'], concat(array($tpConfig['studie_abweichung'], $this->_translateLookup($d['studie_abweichung'], 'studie_abweichung')),': ')),
                ),', ')
            ),': ')
        ),

        isFilled($d['nachbehandler_id'], concat(array($tpConfig['nachbehandler_id'], dlookup($this->_db, 'user', "CONCAT_WS(', ', nachname, vorname)", "user_id = '{$d['nachbehandler_id']}'")),': ')),
        isFilled($d['palliative_versorgung'], concat(array($tpConfig['palliative_versorgung'], $this->_translateLookup($d['palliative_versorgung'], 'jn')),': ')),
        isFilled($d['datum_palliative_versorgung'], concat(array($tpConfig['datum_palliative_versorgung'], todate($d['datum_palliative_versorgung'], 'de')),': ')),
        isFilled($d['bem_palliative_versorgung'], concat(array($tpConfig['bem_palliative_versorgung'],$d['bem_palliative_versorgung']),': ')),
        isFilled($d['bem'], concat(array($tpConfig['bem'],$d['bem']),': '))
    ), "\n"));
}

function isFilled($field, $val)
{
    return (strlen($field) > 0 ? $val : '');
}

?>