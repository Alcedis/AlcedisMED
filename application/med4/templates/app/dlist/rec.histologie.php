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
    'name'        => 'einzelhistologie',
    'table'       => 'histologie_einzel',
    'target_page' => '?page=rec.histologie_einzel',
    'head_content'=> array(
        array(
            'label' => ''
        ),
        array(
            'label' => $config['lbl_lokalisation']
        ),
        array(
            'label' => $config['lbl_morphologie']
        ),
        array(
            'label' => $config['lbl_morphologie_text']
        ),
        array(
            'label' => $config['lbl_unauffaellig']
        ),
        array(
            'label' => $config['lbl_ptnm_praefix']
        ),
        array(
            'label' => $config['lbl_pt']
        ),
        array(
            'label' => $config['lbl_g']
        ),
        array(
            'label' => $config['lbl_l']
        ),
        array(
            'label' => $config['lbl_v']
        ),
        array(
            'label' => $config['lbl_r']
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
            'field' => 'diagnose_id'
        ),
        array(
            'field' => 'morphologie'
        ),
        array(
            'field' => 'morphologie_text'
        ),
        array(
            'field' => 'unauffaellig'
        ),
        array(
            'field' => 'ptnm_praefix'
        ),
        array(
            'field' => 'pt'
        ),
        array(
            'field' => 'g'
        ),
        array(
            'field' => 'l'
        ),
        array(
            'field' => 'v'
        ),
        array(
            'field' => 'r'
        ),
        array(
            'field' => 'BTN_DELETE',
            'tag' => "align='center'"
        ),
    )
);

$tmpl_array = get_delete_right($list, $statusLock);

?>
