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
    'name'        => 'hl7field',
    'table'       => 'settings_hl7field',
    'target_page' => '?page=rec.settings_hl7field',
    'head_content'=> array(
        array(
            'label' => ''
        ),
        array(
            'label' => $config['lbl_med_feld']
        ),
        array(
            'label' => $config['lbl_hl7']
        ),
        array(
            'label' => $config['lbl_zeichen']
        ),
        array(
            'label' => $config['lbl_import']
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
            'field' => 'med_feld'
        ),
        array(
            'field' => 'hl7'
        ),
        array(
            'field' => 'hl7_bereich'
        ),
        array(
            'field' => 'import'
        ),
        array(
            'field' => 'BTN_DELETE',
            'tag' => "align='center'"
        ),
    )
);

$tmpl_array = get_delete_right($list);

?>
