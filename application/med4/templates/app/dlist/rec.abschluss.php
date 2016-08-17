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
    'name'        => 'ursache',
    'table'       => 'abschluss_ursache',
    'target_page' => '?page=rec.abschluss_ursache',
    'head_content'=> array(
        array(
            'label' => ''
        ),
        array(
            'label' => $config['lbl_krankheit']
        ),
        array(
            'label' => $config['lbl_krankheit_seite']
        ),
        array(
            'label' => $config['lbl_krankheit_text']
        ),
        array(
            'label' => $config['lbl_krankheit_dauer']
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
            'field' => 'krankheit'
        ),
        array(
            'field' => 'krankheit_seite'
        ),
        array(
            'field' => 'krankheit_text'
        ),
        array(
            'field' => 'krankheit_dauer',
            'add' => $config['lbl_monate']
        ),
        array(
            'field' => 'BTN_DELETE',
            'tag' => "align='center'"
        ),
    )
);

$tmpl_array = get_delete_right($list, $statusLock);

?>
