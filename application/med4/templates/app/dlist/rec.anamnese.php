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
    'name'        => 'familie',
    'table'       => 'anamnese_familie',
    'target_page' => '?page=rec.anamnese_familie',
    'head_content'=> array(
        array(
            'label' => ''
        ),
        array(
            'label' => $config['lbl_karzinom']
        ),
        array(
            'label' => $config['lbl_verwandschaftsgrad']
        ),
        array(
            'label' => $config['lbl_erkrankungsalter']
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
            'field' => 'karzinom'
        ),
        array(
            'field' => 'verwandschaftsgrad'
        ),
        array(
            'field' => 'erkrankungsalter'
        ),
        array(
            'field' => 'BTN_DELETE',
            'tag' => "align='center'"
        ),
    )
);

$list[] = array(
    'name'        => 'erkrankung',
    'table'       => 'anamnese_erkrankung',
    'target_page' => '?page=rec.anamnese_erkrankung',
    'head_content'=> array(
        array(
            'label' => ''
        ),
        array(
            'label' => $config['lbl_erkrankung']
        ),
        array(
            'label' => $config['lbl_erkrankung_text']
        ),
        array(
            'label' => $config['lbl_jahr']
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
            'field' => 'erkrankung_text'
        ),
        array(
            'field' => 'jahr'
        ),
        array(
            'field' => 'BTN_DELETE',
            'tag' => "align='center'"
        ),
    )
);

$tmpl_array = get_delete_right($list, $statusLock);

?>
