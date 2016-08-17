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

$fields = array(
    'dmp_brustkrebs_ed_2013_id' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '11',
        'type' => 'hidden',
        'ext' => '',
        'reference' => 'dmp_brustkrebs_fd_2013'
    ),
    'erkrankung_id' => array(
        'req' => 1,
        'size' => '',
        'maxlen' => '11',
        'type' => 'hidden',
        'ext' => ''
    ),
    'patient_id' => array(
        'req' => 1,
        'size' => '',
        'maxlen' => '11',
        'type' => 'hidden',
        'ext' => ''
    ),
    'org_id' => array(
        'req' => 1,
        'size' => '',
        'maxlen' => '11',
        'type' => 'hidden',
        'ext' => ''
    ),
    'fall_nr' => array(
        'req' => 1,
        'size' => '',
        'maxlen' => '255',
        'type' => 'string',
        'ext' => ''
    ),
    'doku_datum' => array(
        'req' => 1,
        'size' => '',
        'maxlen' => '',
        'type' => 'date',
        'ext' => '',
        'range' => false
    ),
    'einschreibung_grund' => array(
        'req' => 1,
        'size' => '',
        'maxlen' => '',
        'type' => 'lookup',
        'ext' => array('l_dmp_2013' => 'einschreibung')
    ),
    'melde_user_id' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '11',
        'type' => 'picker',
        'ext' => array('query' => $querys['query_user'], 'type' => 'arzt'),
        'preselect' => 'user.user_id'
    ),
    'unterschrift_datum' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'date',
        'ext' => '',
        'range' => false
    ),
    'kv_iknr' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '11',
        'type' => 'code_ktst',
        'ext' => ''
    ),
    'kv_abrechnungsbereich' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '11',
        'type' => 'lookup',
        'ext' => array('l_basic' => 'kv_abrechnungsbereich')
    ),
    'versich_nr' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '255',
        'type' => 'string',
        'ext' => ''
    ),
    'versich_status' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'lookup',
        'ext' => array('l_basic' => 'kv_status')
    ),
    'versich_statusergaenzung' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'lookup',
        'ext' => array('l_basic' => 'kv_statusergaenzung')
    ),
    'kv_besondere_personengruppe' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'lookup',
        'ext' => array('l_basic' => 'kv_besondere_personengruppe')
    ),
    'kv_dmp_kennzeichnung' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'lookup',
        'ext' => array('l_basic' => 'kv_dmp_kennzeichnung')
    ),
    'kv_versicherungsschutz_beginn' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'date',
        'ext' => '',
        'range' => false
    ),
    'kv_versicherungsschutz_ende' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'date',
        'ext' => '',
        'range' => false
    ),
    'vk_gueltig_bis' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'date',
        'ext' => '', 'range' => false
    ),
    'kvk_einlesedatum' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'date',
        'ext' => '',
        'range' => false
    ),
    'mani_primaer' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'date',
        'ext' => ''
    ),
    'mani_kontra' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'date',
        'ext' => ''
    ),
    'mani_rezidiv' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'date',
        'ext' => ''
    ),
    'mani_metast' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'date',
        'ext' => ''
    ),
    'anam_brust_links' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'anam_brust_rechts' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'anam_brust_beidseits' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'aktueller_status' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'lookup',
        'ext' => array('l_dmp_2013' => 'aktueller_status_eb')
    ),
    'anam_op_bet' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'anam_op_mast' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'anam_op_sln' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'anam_op_axilla' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'anam_op_anderes' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'anam_op_keine' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_neoadjuvant' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'lookup',
        'ext' => array('l_basic' => 'jn')
    ),
    'bef_pt_tis' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_pt_0' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_pt_1' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_pt_2' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_pt_3' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_pt_4' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_pt_x' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_pt_keine' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_pn_0' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_pn_1' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_pn_2' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_pn_3' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_pn_x' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_pn_keine' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_m' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'lookup',
        'ext' => array('l_dmp_2013' => 'm')
    ),
    'bef_g' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'lookup',
        'ext' => array('l_dmp_2013' => 'g')
    ),
    'bef_r_0' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_r_1' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_r_2' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_r_unbekannt' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_r_keine' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'bef_rezeptorstatus' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'lookup',
        'ext' => array('l_dmp_2013' => 'bef_rezeptorstatus')
    ),
    'bef_her2' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'lookup',
        'ext' => array('l_dmp_2013' => 'bef_her2')
    ),
    'beh_strahlen' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'lookup',
        'ext' => array('l_dmp_2013' => 'th_status')
    ),
    'beh_chemo' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'lookup',
        'ext' => array('l_dmp_2013' => 'th_status')
    ),
    'beh_endo' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'lookup',
        'ext' => array('l_dmp_2013' => 'th_status')
    ),
    'beh_ah' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'lookup',
        'ext' => array('l_dmp_2013' => 'th_status')
    ),
    'rez_th_praeop' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'rez_th_exzision' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'rez_th_mastektomie' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'rez_th_strahlen' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'rez_th_chemo' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'rez_th_endo' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'rez_th_andere' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'rez_th_keine' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'metast_lok_leber' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'metast_lok_lunge' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'metast_lok_knochen' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'metast_lok_andere' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'metast_th_operativ' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'metast_th_strahlen' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'metast_th_chemo' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'metast_th_endo' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'metast_th_andere' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'metast_th_keine' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'metast_bip_ja' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'metast_bip_nein' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'metast_bip_kontra' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'check',
        'ext' => ''
    ),
    'lymphoedem' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'lookup',
        'ext' => array('l_dmp_2013' => 'nj')
    ),
    'termin_datum' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'date',
        'ext' => '',
        'range' => false
    ),
    'bem' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '',
        'type' => 'textarea',
        'ext' => ''
    ),
    'createuser' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '11',
        'type' => 'hidden',
        'ext' => ''
    ),
    'createtime' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '11',
        'type' => 'hidden',
        'ext' => ''
    ),
    'updateuser' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '11',
        'type' => 'hidden',
        'ext' => ''
    ),
    'updatetime' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '11',
        'type' => 'hidden',
        'ext' => ''
    )
);

?>
