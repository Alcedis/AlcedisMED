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

$fields = $widget->loadExtFields('fields/base/recht.php');

unset(
   //Recht
   $_SESSION['sess_recht_id'],
   $_SESSION['sess_recht_erkrankung'],
   $_SESSION['sess_recht_behandler'],
   $_SESSION['sess_matrix'],

   //Rolle
   $_SESSION['sess_rolle_code'],
   $_SESSION['sess_rolle_bez'],

   //Organisation
   $_SESSION['sess_org_id'],
   $_SESSION['sess_org_name'],
   $_SESSION['sess_org_ort'],
   $_SESSION['sess_org_logo'],
   $_SESSION['sess_org_logo_img_type'],

   //Konferenz
   $_SESSION['sess_konferenz_name'],

   //Init
   $_SESSION['sess_start_page']
);

$systemDiseases = str_replace(',', "','", appSettings::get('erkrankungen'));
$rolle_selected = isset($_REQUEST['rolle_selected']) ? $_REQUEST['rolle_selected'] : false;

// rolle wurde ausgewählt
if ($rolle_selected !== false) {
   $recht_id = isset($_REQUEST['recht_id']) === true ? $_REQUEST['recht_id'] : null;

   $query = "SELECT
      recht.*,
      org.*,
      rolle.bez                AS rolle_bez,

      recht.recht_global,

      IF( recht.recht_global = '1',
          GROUP_CONCAT(DISTINCT l_erkrankung_alle.code ORDER BY l_erkrankung_alle.code SEPARATOR ','),
          GROUP_CONCAT(DISTINCT l_erkrankung.code ORDER BY l_erkrankung.code SEPARATOR ','))    AS recht_erkrankung,

      IF( recht.recht_global = '1',
          GROUP_CONCAT(DISTINCT l_erkrankung_alle.bez ORDER BY l_erkrankung_alle.code SEPARATOR ','),
          GROUP_CONCAT(DISTINCT l_erkrankung.bez ORDER BY l_erkrankung.code SEPARATOR ','))    AS recht_erkrankung_bez

   FROM recht recht
      LEFT JOIN org org        ON recht.org_id = org.org_id
      LEFT JOIN l_basic rolle  ON rolle.klasse = 'rolle' AND rolle.code = recht.rolle
      LEFT JOIN recht_erkrankung erkrankung ON recht.recht_id = erkrankung.recht_id
         LEFT JOIN l_basic l_erkrankung ON erkrankung.erkrankung=l_erkrankung.code AND l_erkrankung.klasse='erkrankung' AND l_erkrankung.code IN ('{$systemDiseases}')

         LEFT JOIN l_basic l_erkrankung_alle ON  l_erkrankung_alle.klasse='erkrankung' AND l_erkrankung_alle.code IN ('{$systemDiseases}')
   WHERE recht.recht_id = '{$recht_id}' AND recht.user_id = '{$user_id}'
   GROUP BY recht.recht_id
   ";

   $result = reset(sql_query_array($db, $query));

   //Sessionvariablen werden gesetzt
   $_SESSION['sess_recht_id']             = $result['recht_id'];
   $_SESSION['sess_recht_behandler']      = array_key_exists('behandler', $result) == true ? $result['behandler']: null;
   $_SESSION['sess_recht_global']         = $result['recht_global'];
   $_SESSION['sess_recht_erkrankung']     = strlen($result['recht_erkrankung']) ? explode(',', $result['recht_erkrankung']) : array();
   $_SESSION['sess_recht_erkrankung_bez'] = strlen($result['recht_erkrankung_bez']) ? explode(',', $result['recht_erkrankung_bez']) : array();

   $_SESSION['sess_org_id']            = $result['org_id'];
   $_SESSION['sess_org_logo']          = $result['logo'];
   $_SESSION['sess_org_logo_img_type'] = $result['img_type'];
   $_SESSION['sess_org_name']          = $result['name'];
   $_SESSION['sess_org_ort']           = $result['ort'];
   $_SESSION['sess_rolle_code']        = $result['rolle'];
   $_SESSION['sess_rolle_bez']         = $result['rolle_bez'];
   $_SESSION['sess_start_page']        = 'rollenauswahl';

   if (strlen($result['rolle']) > 0) {
      $sess_rolle_code = $_SESSION['sess_rolle_code'];

      $matrix_query = "SELECT
         tabelle,
         standard,
         $sess_rolle_code AS zugriff
         FROM $tbl_l_matrix WHERE 1";

      $result = sql_query_array($db, $matrix_query);

      $_SESSION['sess_matrix'] = $result;

      foreach ($result as $part) {
         $_SESSION['sess_permission_matrix'][$part['tabelle']] = $part['zugriff'];
      }

      $startPage = get_start_page($_SESSION['sess_matrix']);

      if ($startPage != '') {
         $_SESSION['sess_start_page']  = $startPage;
      }
   }

   action_cancel("index.php?page={$_SESSION['sess_start_page']}");
} else {
   $moderatorCheck = appSettings::get('konferenz') !== true ? "AND rolle.code != 'moderator'" : null;

   $query = "SELECT
      recht.*,
      org.*,
      CONCAT_WS( ', ', org.name, org.ort) AS org_id,
      rolle.bez                           AS rolle_bez,
      IFNULL(
         IF( recht.recht_global = '1',
            GROUP_CONCAT(DISTINCT l_erkrankung_alle.bez ORDER BY l_erkrankung_alle.bez SEPARATOR ', '),
            GROUP_CONCAT(DISTINCT l_erkrankung.bez ORDER BY l_erkrankung.bez SEPARATOR ', ')
         ),
      '-')                                AS erkrankung_bez
   FROM recht recht
      LEFT JOIN org org        ON recht.org_id = org.org_id
      LEFT JOIN l_basic rolle  ON rolle.klasse = 'rolle' AND rolle.code = recht.rolle
      LEFT JOIN recht_erkrankung erkrankung ON recht.recht_id = erkrankung.recht_id
         LEFT JOIN l_basic l_erkrankung ON erkrankung.erkrankung=l_erkrankung.code AND l_erkrankung.klasse='erkrankung' AND l_erkrankung.code IN ('{$systemDiseases}')
         LEFT JOIN l_basic l_erkrankung_alle ON  l_erkrankung_alle.klasse='erkrankung' AND l_erkrankung_alle.code IN ('{$systemDiseases}')
   WHERE
      recht.user_id = '{$user_id}' {$moderatorCheck}
   GROUP BY recht.recht_id
   ORDER BY org.name, rolle_bez";

   $fields['rolle_bez']       = array( 'type' => 'string' );
   $fields['erkrankung_bez']  = array( 'type' => 'string' );

   data2list( $db, $fields, $query );

   if (appSettings::get('show_last_login') === true){
      $last_login  = dlookup($db, 'history', "DATE_FORMAT(MAX(login_time), '%d.%m.%Y %H:%i:%s')", "loginname = '{$_SESSION['sess_loginname']}' AND (login_acc = 1) AND (login_time < '{$_SESSION['sess_time_login']}')" );

      if (strlen($last_login) > 0){
         $smarty->assign('last_login', $last_login);
      }
   }
}

?>