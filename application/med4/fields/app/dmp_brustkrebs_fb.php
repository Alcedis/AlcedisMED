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
   'dmp_brustkrebs_fb_id'     => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
   'dmp_brustkrebs_eb_id'     => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '',  'parent_status' => 'dmp_brustkrebs_eb'),
 	'erkrankung_id'            => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
	'patient_id'               => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
	'melde_user_id'            => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'picker'     , 'ext' => array( 'query' => $querys['query_user'], 'type' => 'arzt'), 'preselect' => 'user.user_id' ),
	'arztwechsel'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'doku_datum'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '' ),
	'unterschrift_datum'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '' ),
	'kv_iknr'                  => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'code_ktst'  , 'ext' => ''),
	'versich_nr'               => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => ''),
	'versich_status'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'kv_status') ),
	'versich_statusergaenzung' => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'kv_statusergaenzung') ),
	'vk_gueltig_bis'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '', 'range' => false),
	'kvk_einlesedatum'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '', 'range' => false),
    //'einschreibung_grund'      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_dmp' => 'einschreibung') ),
	'pth_fertig'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_dmp' => 'nj') ),
	'primaer_strahlen'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_dmp' => 'th_status') ),
	'primaer_chemo'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_dmp' => 'th_status') ),
	'primaer_endo'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_dmp' => 'th_status') ),
	'neu_rezidiv_nein'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'neu_rezidiv_intra'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'neu_rezidiv_thorax'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'neu_rezidiv_axilla'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'neu_rezidiv_datum'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '' ),
	'neu_kontra_nein'          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'neu_kontra_datum'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '' ),
	'neu_metast_nein'          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'neu_metast_leber'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'neu_metast_lunge'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'neu_metast_knochen'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'neu_metast_andere'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'neu_metast_datum'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '' ),
	'lymphoedem'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_dmp' => 'nj') ),
	'rez_status_cr'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'rez_status_pr'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'rez_status_nc'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'rez_status_pd'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'rez_th_praeop'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'rez_th_exzision'          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'rez_th_mastektomie'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'rez_th_strahlen'          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'rez_th_chemo'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'rez_th_endo'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'rez_th_andere'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'rez_th_keine'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'metast_th_operativ'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'metast_th_strahlen'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'metast_th_chemo'          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'metast_th_endo'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'metast_th_andere'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'metast_th_keine'          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'metast_bip_ja'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'metast_bip_nein'          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'metast_bip_kontra'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'sonst_schmerz_ja'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'sonst_schmerz_nein'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'sonst_schmerz_ne'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'sonst_mammo_ja'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'sonst_mammo_nein'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'sonst_mammo_ne'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'sonst_psycho_ja'          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'sonst_psycho_nein'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'sonst_psycho_abgelehnt'   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'termin_datum'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '', 'range' => false ),
	'bem'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => ''),
   'createuser'               => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'createtime'               => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updateuser'               => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updatetime'               => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => '')
);

?>
