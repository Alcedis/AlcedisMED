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
    'name'        => 'wirkstoff',
    'table'       => 'vorlage_therapie_wirkstoff',
    'target_page' => '?page=rec.vorlage_therapie_wirkstoff',
    'head_content'=> array(
        array(
            'label' => ''
        ),
        array(
            'label' => $config['lbl_wirkstoff']
        ),
        array(
            'label' => $config['lbl_zyklus_beginn']
        ),
        array(
            'label' => $config['lbl_zyklus_anzahl']
        ),
        array(
            'label' => $config['lbl_zyklustag']
        ),
        array(
            'label' => $config['lbl_applikationsfrequenz']
        ),
        array(
            'label' => $config['lbl_therapiedauer']
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
            'field' => 'wirkstoff'
        ),
        array(
            'field' => 'zyklus_beginn'
        ),
        array(
            'field' => 'zyklus_anzahl'
        ),
        array(
            'field' => 'zyklustag'
        ),
        array(
            'field' => 'applikationsfrequenz'
        ),
        array(
            'field' => 'therapiedauer'
        ),
        array(
            'field' => 'BTN_DELETE',
            'tag' => "align='center'"
        ),
    )
);

$hard = true;

if (isset($_REQUEST['vorlage_therapie_id']) === true) {

   $vorlage_therapie_id = $_REQUEST['vorlage_therapie_id'];
   $hard = dlookup($db, 'vorlage_therapie', 'freigabe', "vorlage_therapie_id = '$vorlage_therapie_id'") == 1 ? true : false;
}

$tmpl_array = get_delete_right($list, $hard);

?>
