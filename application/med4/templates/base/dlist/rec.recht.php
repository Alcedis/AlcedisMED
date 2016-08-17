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

$list[] = array(
    'name'        => 'erkrankung',
    'table'       => 'recht_erkrankung',
    'target_page' => '?page=rec.recht_erkrankung',
    'head_content'=> array(
        array(
            'label' => ''
        ),
        array(
            'label' => $config['lbl_erkrankung']
        ),
        array(
            'label' => $config['lbl_delete']
        )
    ),
    'body_content'=> array(
        array(
            'field' => 'BTN_EDT',
            'tag' => "align='center'"
        ),
        array(
            'field' => 'erkrankung'
        ),
        array(
            'field' => 'BTN_DELETE',
            'tag' => "align='center'"
        ),
    )
);

$dontShowDeleteButton = false;

$rechtId    = isset($_REQUEST['recht_id']) === true && strlen($_REQUEST['recht_id']) > 0 ? $_REQUEST['recht_id'] : null;
$userId     = isset($_SESSION['sess_user_id']) === true && strlen($_SESSION['sess_user_id']) > 0 ? $_SESSION['sess_user_id'] : null;
$rolle_code = isset($_SESSION['sess_rolle_code']) === true && strlen($_SESSION['sess_rolle_code']) > 0 ? $_SESSION['sess_rolle_code'] : null;

if ($rechtId !== null && $rolle_code != 'admin') {
   //Prüfen ob user auf die Org id des ausgefwählten Datensatzes ein Recht hat

   $result = reset(sql_query_array($db, "
      SELECT
         r.recht_id
      FROM recht r
         INNER JOIN recht rorg ON rorg.org_id = r.org_id AND rorg.user_id = '{$userId}' AND rorg.rolle = 'supervisor'
      WHERE
         r.recht_id = '{$rechtId}'
   "));

   if ($result === false) {
      $dontShowDeleteButton = true;
   }
}

$tmpl_array = get_delete_right($list, $dontShowDeleteButton);

?>
