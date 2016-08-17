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

$query_org = '';
$where = '';

if ($rolle_code == 'admin') {
    $query_org .= "
        SELECT
           '0' AS org_id,
           '- MED4 Basis Konfiguration -' AS 'oname'
        UNION
    ";
} else {
    $orgRights = dlookup($db, 'recht', 'GROUP_CONCAT(DISTINCT org_id)', "user_id = '{$user_id}' AND rolle = 'supervisor' GROUP BY user_id");

    $orgRights = strlen($orgRights) ? $orgRights : '-999';

    $where .= "WHERE o.org_id IN ({$orgRights})";
}

$query_org .= "
   SELECT
      o.org_id,
      CONCAT_WS(', ', o.ort, o.name) as 'oname'
   FROM org o
       {$where}
   WHERE o.org_id >= 0
   ORDER BY
      oname
";


$fields = array(
    'settings_forms_id'   => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden',   'ext' => ''),
    'org_id'              => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'query',    'ext' => $query_org),
    'forms'               => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'textarea', 'ext' => ''),
    'bem'                 => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'textarea', 'ext' => ''),
    'createuser'          => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
    'createtime'          => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
    'updateuser'          => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
    'updatetime'          => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',   'ext' => '')
);

?>
