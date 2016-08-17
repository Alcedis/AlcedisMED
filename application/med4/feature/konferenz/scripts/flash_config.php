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

require_once( 'ie_bugfix.php' );

$secure_flag   = isset( $_GET['secure_flag'] ) === true   ? $_GET['secure_flag']         : '';
$konferenzId   = isset( $_GET['konferenz_id'] ) === true  ? $_GET['konferenz_id']        : '';

if( $secure_flag != 'm7Ugf4vBdflnG8ugfVbd0flngA' )
   exit;

$smarty->config_load(FILE_CONFIG_SERVER, 'aevolver');
$smarty->config_load('base/flash_config.conf');
$smarty->config_load('settings/konferenz.conf');

$activatePacs = dlookup($db, 'settings', 'pacs', "1");

$config  = $smarty->get_config_vars();

//zugeordnete Patienten und Dokumente holen
$query = "
   SELECT
      DATE_FORMAT(patient.geburtsdatum, '%d.%m.%Y')    AS datum,
      konferenz_patient.konferenz_patient_id                         AS konferenz_patient_id,
      kp_art.bez                                                     AS art,
      CONCAT_WS(', ', patient.nachname, patient.vorname)             AS name,
      l_erkrankung.bez                                               AS erkrankung,
      DATE_FORMAT(patient.geburtsdatum, '%d.%m.%Y')                  AS geburtstag
   FROM konferenz_patient konferenz_patient
      LEFT JOIN l_basic kp_art                  ON kp_art.klasse = 'tumorkonferenz_art' AND kp_art.code     = konferenz_patient.art
      LEFT JOIN patient patient                 ON patient.patient_id                                       = konferenz_patient.patient_id
      LEFT JOIN erkrankung erkrankung           ON konferenz_patient.erkrankung_id                          = erkrankung.erkrankung_id
      LEFT JOIN l_basic l_erkrankung            ON l_erkrankung.klasse='erkrankung' AND l_erkrankung.code   = erkrankung.erkrankung
   WHERE
      konferenz_patient.konferenz_id='$konferenzId'
   ORDER BY
      patient.nachname,
      patient.vorname,
      art DESC
";

$material = array();
$material[ 'patients' ] = array();
$material[ 'documents' ] = array();

foreach (sql_query_array($db, $query) AS $key => $value)
{
   $query = "
      SELECT
         konferenz_dokument_id,
         bez,
         datei
      FROM konferenz_dokument
      WHERE
         konferenz_patient_id = '$value[konferenz_patient_id]'
   ";
   $material[ 'patients' ][ $value['konferenz_patient_id'] ] = array( 'name' => $value['name'] . ' (' . $value['datum'] . '), ' . $value[ 'erkrankung' ],
                                                                      'documents' => sql_query_array($db, $query) );
}

//Dokumente die keinem Patienten zugeordnet sind
$query = "
   SELECT
      konferenz_dokument_id,
      bez,
      datei
   FROM konferenz_dokument
   WHERE
      konferenz_id='$konferenzId'
      AND konferenz_patient_id IS NULL
";
$material[ 'documents' ] = sql_query_array($db, $query);

$materiallist = base64_encode( json_encode( to_utf8( $material ) ) );

$use_pacs = isset($activatePacs)?$activatePacs:"0";

$saddr = ( isset( $config['aevolver_host'] ) ? $config['aevolver_host'] : "127.0.0.1" );

$ccoc = isset($arr_setup[0]['ccoc'])?$arr_setup[0]['ccoc']:'t';
$ccoc = ($ccoc == 't')? 'off' : 'on' ;
$ussl = isset($arr_setup[0]['ussl'])?$arr_setup[0]['ussl']:'t';
$ussl = ($ussl == 't')? '1' : '0';
$aevolver_policy_port = isset($arr_setup[0]['aevolver_policy_port'])?$arr_setup[0]['aevolver_policy_port']:'843';
$proxy_server_address = "";
if ($ussl == '1')
{
   $spn = '10000';
}
else
{
   $spn = '10001';
}

$role = $_SESSION['sess_rolle_code'];
$cct = 2;
if ($role == 'moderator')
{
	$cct = 1;
}

$domain = "med4.de";
$application = "imageviewer";
$room = "";
$web_session_id = session_id();
$web_session_name = "test";
$web_application_uid = $_SESSION['sess_user_id'];
$web_application_path = $config['auth_dir'];
$view_mode = "1";
$rdir = "";
$conference_name = "" . $konferenzId;
$conference_room = "" . $konferenzId;
$conference_password = "";
$ccoc = "0";
$vds = "1";
$vis = "1";
$hdv = "0";
$alis = "on";
$fps = "24";

// XML für schreiben
echo utf8_encode( '
       <?xml version="1.0" encoding="UTF-8"?>
       <config>
          <var name="app_unique_id"             content="' . $config['app_unique_id']                                                                      . '" />
          <var name="flashcomm_url"             content="' . $config['flashcomm_url']                                                                      . '" />
          <var name="arrow_color"               content="' . ( isset( $config['tuk_arrow_color']           ) ? $config['tuk_arrow_color'] : "" )           . '" />
          <var name="dir_material"              content="' . $config['dir_material']                                                                       . '" />
          <var name="default_swf"               content="' . $config['default_swf']                                                                        . '" />
          <var name="title_main"                content="' . $config['title_main']                                                                         . '" />
          <var name="title_video"               content="' . ( isset( $config['title_video']               ) ? $config['title_video'] : "" )               . '" />
          <var name="title_peoplelist"          content="' . ( isset( $config['title_peoplelist']          ) ? $config['title_peoplelist'] : "" )          . '" />
          <var name="title_chat"                content="' . ( isset( $config['title_chat']                ) ? $config['title_chat'] : "" )                . '" />
          <var name="title_settings"            content="' . ( isset( $config['title_settings']            ) ? $config['title_settings'] : "" )            . '" />
          <var name="title_presentation"        content="' . ( isset( $config['title_presentation']        ) ? $config['title_presentation'] : "" )        . '" />
          <var name="title_patientlist"         content="' . ( isset( $config['title_patientlist']         ) ? $config['title_patientlist'] : "" )         . '" />
          <var name="title_logout"              content="' . ( isset( $config['title_logout']              ) ? $config['title_logout'] : "" )              . '" />
          <var name="lbl_select"                content="' . ( isset( $config['lbl_select']                ) ? $config['lbl_select'] : "" )                . '" />
          <var name="lbl_btnSyncPage"           content="' . $config['lbl_btnSyncPage']                                                                    . '" />
          <var name="text_infoPage"             content="' . ( isset( $config['text_infoPage']             ) ? $config['text_infoPage'] : "" )             . '" />
          <var name="lbl_desktop_sharing"       content="' . ( isset( $config['lbl_desktop_sharing']       ) ? $config['lbl_desktop_sharing'] : "" )       . '" />
          <var name="lbl_data"                  content="' . ( isset( $config['lbl_data']                  ) ? $config['lbl_data'] : "" )                  . '" />
          <var name="lbl_result"        	      content="' . ( isset( $config['lbl_result']                ) ? $config['lbl_result'] : "" )                . '" />
          <var name="lbl_document"              content="' . ( isset( $config['lbl_document']              ) ? $config['lbl_document'] : "" )              . '" />
          <var name="lbl_pacs_selection"        content="' . ( isset( $config['lbl_pacs_selection']        ) ? $config['lbl_pacs_selection'] : "" )        . '" />
          <var name="lbl_firstFrame"            content="' . ( isset( $config['lbl_firstFrame']            ) ? $config['lbl_firstFrame'] : "" )            . '" />
          <var name="lbl_prevFrame"             content="' . ( isset( $config['lbl_prevFrame']             ) ? $config['lbl_prevFrame'] : "" )             . '" />
          <var name="lbl_nextFrame"             content="' . ( isset( $config['lbl_nextFrame']             ) ? $config['lbl_nextFrame'] : "" )             . '" />
          <var name="lbl_lastFrame"             content="' . ( isset( $config['lbl_lastFrame']             ) ? $config['lbl_lastFrame'] : "" )             . '" />
          <var name="data_view_mode"            content="' . ( isset( $config['data_view_mode']            ) ? $config['data_view_mode'] : "" )            . '" />
          <var name="lbl_webcam_start"          content="' . ( isset( $config['lbl_webcam_start']          ) ? $config['lbl_webcam_start'] : "" )          . '" />
          <var name="lbl_webcam_pause"          content="' . ( isset( $config['lbl_webcam_pause']          ) ? $config['lbl_webcam_pause'] : "" )          . '" />
          <var name="lbl_webcam_stop"           content="' . ( isset( $config['lbl_webcam_stop']           ) ? $config['lbl_webcam_stop'] : "" )           . '" />
          <var name="lbl_desktop_sharing_start" content="' . ( isset( $config['lbl_desktop_sharing_start'] ) ? $config['lbl_desktop_sharing_start'] : "" ) . '" />
          <var name="lbl_desktop_sharing_pause" content="' . ( isset( $config['lbl_desktop_sharing_pause'] ) ? $config['lbl_desktop_sharing_pause'] : "" ) . '" />
          <var name="lbl_desktop_sharing_stop"  content="' . ( isset( $config['lbl_desktop_sharing_stop']  ) ? $config['lbl_desktop_sharing_stop'] : "" )  . '" />
          <var name="lbl_connected"             content="' . ( isset( $config['lbl_connected']             ) ? $config['lbl_connected'] : "" )             . '" />
          <var name="lbl_not_connected"         content="' . ( isset( $config['lbl_not_connected']         ) ? $config['lbl_not_connected'] : "" )         . '" />
          <var name="lbl_draw_color"            content="' . ( isset( $config['lbl_draw_color']            ) ? $config['lbl_draw_color'] : "" )            . '" />
          <var name="lbl_selection_color"       content="' . ( isset( $config['lbl_selection_color']       ) ? $config['lbl_selection_color'] : "" )       . '" />
          <var name="lbl_wb_button"             content="' . ( isset( $config['lbl_wb_button']             ) ? $config['lbl_wb_button'] : "" )             . '" />
          <var name="lbl_wb_edit"               content="' . ( isset( $config['lbl_wb_edit']               ) ? $config['lbl_wb_edit'] : "" )               . '" />
          <var name="lbl_wb_line"               content="' . ( isset( $config['lbl_wb_line']               ) ? $config['lbl_wb_line'] : "" )               . '" />
          <var name="lbl_wb_polyline"           content="' . ( isset( $config['lbl_wb_polyline']           ) ? $config['lbl_wb_polyline'] : "" )           . '" />
          <var name="lbl_wb_polygon"            content="' . ( isset( $config['lbl_wb_polygon']            ) ? $config['lbl_wb_polygon'] : "" )            . '" />
          <var name="lbl_wb_circle"             content="' . ( isset( $config['lbl_wb_circle']             ) ? $config['lbl_wb_circle'] : "" )             . '" />
          <var name="lbl_wb_annotation"         content="' . ( isset( $config['lbl_wb_annotation']         ) ? $config['lbl_wb_annotation'] : "" )         . '" />
          <var name="audio_active"              content="' . ( isset( $config['audio_active']              ) ? $config['audio_active'] : false )           . '" />
          <var name="audio_codec"               content="' . ( isset( $config['audio_codec']               ) ? $config['audio_codec'] : "NellyMoser" )     . '" />
          <var name="webcam_panel"              content="' . ( isset( $config['webcam_panel']              ) ? $config['webcam_panel'] : false )           . '" />
          <var name="second_webcam_view"        content="' . ( isset( $config['second_webcam_view']        ) ? $config['second_webcam_view'] : false )     . '" />
          <var name="whiteboard"                content="' . ( isset( $config['whiteboard']                ) ? $config['whiteboard'] : false )             . '" />
          <var name="fullscreen"                content="' . ( isset( $config['fullscreen']                ) ? $config['fullscreen'] : false )             . '" />
          <var name="sticky_cursor"             content="' . ( isset( $config['sticky_cursor']             ) ? $config['sticky_cursor'] : true )           . '" />
          <var name="webcam_panel"              content="' . ( isset( $config['webcam_panel']              ) ? $config['webcam_panel'] : false )           . '" />
          <var name="third_webcam_view"         content="' . ( isset( $config['third_webcam_view']         ) ? $config['third_webcam_view'] : false )      . '" />
          <var name="user_list_width"           content="' . ( isset( $config['user_list_width']           ) ? $config['user_list_width'] : "300" )        . '" />
          <var name="user_list_sorted"          content="' . ( isset( $config['user_list_sorted']          ) ? $config['user_list_sorted'] : false )       . '" />
          <var name="user_list_show_firstname"  content="' . ( isset( $config['user_list_show_firstname']  ) ? $config['user_list_show_firstname'] : false ) . '" />
          <var name="user_list_show_cityname"   content="' . ( isset( $config['user_list_show_cityname']   ) ? $config['user_list_show_cityname'] : false ) . '" />
          <var name="user_chat"                 content="' . ( isset( $config['user_chat']                 ) ? $config['user_chat'] : false )              . '" />
          <var name="desktop_sharing_active"    content="' . ( isset( $config['desktop_sharing_active']    ) ? $config['desktop_sharing_active'] : false ) . '" />
          <var name="image_server"              content="' . ( isset( $config['image_server']              ) ? $config['image_server'] : "127.0.0.1" )     . '" />
          <var name="image_server_port"         content="' . ( isset( $config['image_server_port']         ) ? $config['image_server_port'] : 10000 )      . '" />
          <var name="image_zoom_view_active"    content="' . ( isset( $config['image_zoom_view_active']    ) ? $config['image_zoom_view_active'] : false ) . '" />
          <var name="app_path"                  content="' . dirname(dirname( $_SERVER['SCRIPT_NAME'] ))                                                   . '" />
          <var name="activate_pacs"             content="' . $activatePacs                                                                                 . '" />
          <var name="material"                  content="' . $materiallist                                                                                 . '" />
          <var name="fullscreen"                content="' . ( isset( $config['fullscreen']                ) ? $config['fullscreen'] : false )             . '" />
          <var name="use_pacs"                  content="' . $use_pacs                                                                                     . '" />
          <var name="server_address"            content="' . $saddr                                                                                        . '" />
          <var name="server_port"               content="' . $spn                                                                                          . '" />
          <var name="policy_server_port"        content="' . $aevolver_policy_port                                                                         . '" />
          <var name="proxy_server_address"      content="' . $proxy_server_address                                                                         . '" />
          <var name="use_ssl"                   content="' . $ussl                                                                                         . '" />
          <var name="domain"                    content="' . $domain                                                                                       . '" />
          <var name="application"               content="' . $application                                                                                  . '" />
          <var name="room"                      content="' . $room                                                                                         . '" />
          <var name="web_session_id"            content="' . $web_session_id                                                                               . '" />
          <var name="web_session_name"          content="' . $web_session_name                                                                             . '" />
          <var name="web_application_uid"       content="' . $web_application_uid                                                                          . '" />
          <var name="web_application_path"      content="' . $web_application_path                                                                         . '" />
          <var name="view_mode"                 content="' . $view_mode                                                                                    . '" />
          <var name="conference_creation_typ"   content="' . $cct                                                                                          . '" />
          <var name="conference_name"           content="' . $conference_name                                                                              . '" />
          <var name="conference_room"           content="' . $conference_room                                                                              . '" />
          <var name="conference_password"       content="' . $conference_password                                                                          . '" />
          <var name="create_cube_on_client"     content="' . $ccoc                                                                                         . '" />
          <var name="view_dicom_selection"      content="' . $vds                                                                                          . '" />
          <var name="view_image_selection"      content="' . $vis                                                                                          . '" />
          <var name="root_dir"                  content="' . $rdir                                                                                         . '" />
          <var name="hide_dir_view"             content="' . $hdv                                                                                          . '" />
          <var name="allow_image_switching"     content="' . $alis                                                                                         . '" />
          <var name="fps"                       content="' . (isset($config['fps']                         ) ? $config['fps'] : '24')                      . '" />
          <var name="redraw_time"               content="' . (isset($config['redraw_time']                 ) ? $config['redraw_time'] : '500')             . '" />
       </config>
');

exit;

   function to_utf8($in)
   {
       $out = array();
       if (is_array($in)) {
            foreach ($in as $key => $value) {
                $out[to_utf8($key)] = to_utf8($value);
            }
        } elseif(is_string($in)) {
                return utf8_encode($in);
        } else {
            return $in;
        }
        return isset($out)?$out:'';
   }
?>
