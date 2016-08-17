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

$patList = array('patient_nr' => $config['patient_nr'], 'aufnahme_nr' => $config['aufnahme_nr'], 'createtime' => $config['createtime']);


$fields = array(
   'settings_id'                    => array( 'req' => 0, 'size' => '', 'maxlen' => '11', 'type' => 'hidden', 'ext' => '' ),
   'software_version'               => array( 'req' => 0, 'size' => '50', 'maxlen' => '255', 'type' => 'string' , 'ext' => '' ),
   'software_title'                 => array( 'req' => 0, 'size' => '50', 'maxlen' => '255', 'type' => 'string' , 'ext' => '' ),
   'software_custom_title'          => array( 'req' => 0, 'size' => '50', 'maxlen' => '255', 'type' => 'string' , 'ext' => '' ),
   'fastreg'                        => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'fastreg_role'                   => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'lookup' , 'ext' => array('l_basic' => 'rolle') ),
   'auto_patient_id'                => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'patient_initials_only'          => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'show_last_login'                => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'allow_registration'             => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'allow_password_reset'           => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'user_max_login'                 => array( 'req' => 0, 'size' => '2','maxlen' => ''  , 'type' => 'int'   , 'ext' => '' ),
   'user_max_login_deactivated'     => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'pat_list_first'                 => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'radio' , 'ext' => $patList ),
   'pat_list_second'                => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'radio' , 'ext' => $patList ),
   'extended_swage'                 => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'show_pictures'                  => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'report_debug'                   => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'deactivate_range_check'         => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'fake_system_date'               => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'date'  , 'ext' => '', 'range' => false ),
   'logo'                           => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'file'  , 'ext' => '' ),
   'img_type'                       => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'hidden', 'ext' => '' ),
   'check_ie'                       => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'erkrankung_b'                   => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'erkrankung_d'                   => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'erkrankung_gt'                  => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'erkrankung_h'                   => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'erkrankung_kh'                  => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'erkrankung_leu'                 => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'erkrankung_lg'                  => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'erkrankung_lu'                  => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'erkrankung_ly'                  => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'erkrankung_m'                   => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'erkrankung_nt'                  => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'erkrankung_oes'                 => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'erkrankung_p'                   => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'erkrankung_pa'                  => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'erkrankung_snst'                => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'erkrankung_sst'                 => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'feature_dkg_oz'                 => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'feature_dkg_b'                  => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'feature_dkg_d'                  => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'feature_dkg_gt'                 => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'feature_dkg_h'                  => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'feature_dkg_lu'                 => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'feature_dkg_p'                  => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_gekid'                => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_gekid_plus'           => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_ekr_h'                => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_ekr_rp'               => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_ekr_sh'               => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_krbw'                 => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_hl7_e'                => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_gkr'                  => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_adt'                  => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_gtds'                 => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_onkeyline'            => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_dmp_2014'             => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_qs181'                => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_eusoma'               => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_wbc'                  => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_wdc'                  => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_onkonet'              => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_patho_i'              => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_patho_e'              => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_oncobox_darm'         => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_oncobox_prostata'     => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'interface_kr_he'                => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'rolle_konferenzteilnehmer'      => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'rolle_dateneingabe'             => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'konferenz'                      => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'email_attachment'               => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'zweitmeinung'                   => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'tools'                          => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'dokument'                       => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'pacs'                           => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
   'max_pacs_savetime'              => array( 'req' => 0, 'size' => '4','maxlen' => '4' , 'type' => 'int'   , 'ext' => '' ),
   'codepicker_top_limit'           => array( 'req' => 0, 'size' => '1','maxlen' => '3' , 'type' => 'int'   , 'ext' => '' ),
   'historys_path'                  => array( 'req' => 1, 'size' => '50', 'maxlen' => '255', 'type' => 'string' , 'ext' => '' ),
   'createuser'                     => array( 'req' => 0, 'size' => '', 'maxlen' => '20', 'type' => 'hidden', 'ext' => '' ),
   'createtime'                     => array( 'req' => 0, 'size' => '', 'maxlen' => '19', 'type' => 'hidden', 'ext' => '' ),
   'updateuser'                     => array( 'req' => 0, 'size' => '', 'maxlen' => '20', 'type' => 'hidden', 'ext' => '' ),
   'updatetime'                     => array( 'req' => 0, 'size' => '', 'maxlen' => '19', 'type' => 'hidden', 'ext' => '' ),
);

?>
