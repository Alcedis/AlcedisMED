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
    'name'        => 'wert',
    'table'       => 'vorlage_labor_wert',
    'target_page' => '?page=rec.vorlage_labor_wert',
    'head_content'=> array(
        array(
            'label' => ''
        ),
        array(
            'label' => $config['lbl_parameter']
        ),
        array(
            'label' => $config['lbl_einheit']
        ),
        array(
            'label' => $config['lbl_normal_m_min'],
            'tag' => 'class="subhead" align="center"'
        ),
        array(
            'label' => $config['lbl_normal_w_min'],
            'tag' => 'class="subhead" align="center"'
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
            'field' => 'parameter'
        ),
        array(
            'field' => 'einheit'
        ),
        array(
            'field' => array(
                'normal_m_min',
                'normal_m_max'
            ),
            'separator' => $config['lbl_normal_m_max'],
            'tag' => "align='center'"
        ),
        array(
            'field' => array(
                'normal_w_min',
                'normal_w_max'
            ),
            'separator' => $config['lbl_normal_w_max'],
            'tag' => "align='center'"
        ),
        array(
            'field' => 'BTN_DELETE',
            'tag' => "align='center'"
        ),
    )
);

$hard = true;

if (isset($_REQUEST['vorlage_labor_id']) === true) {
   $vorlage_labor_id = $_REQUEST['vorlage_labor_id'];
   $hard = dlookup($db, 'vorlage_labor', 'freigabe', "vorlage_labor_id = '$vorlage_labor_id'") == 1 ? true : false;
}

$tmpl_array = get_delete_right($list, $hard);

?>
