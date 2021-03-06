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

$fields = array(
    'dmp_nummern_2013_id' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '11',
        'type' => 'hidden',
        'ext' => ''
    ),
    'org_id' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '11',
        'type' => 'hidden',
        'ext' => ''
    ),
    'dmp_nr_start' => array(
        'req' => 1,
        'size' => '',
        'maxlen' => '7',
        'type' => 'int',
        'ext' => ''
    ),
    'dmp_nr_end' => array(
        'req' => 1,
        'size' => '',
        'maxlen' => '7',
        'type' => 'int',
        'ext' => ''
    ),
    'dmp_nr_current' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '7',
        'type' => 'hidden',
        'ext' => ''
    ),
    'pool' => array(
        'req' => 0,
        'size' => '',
        'type' => 'text',
        'ext' => ''
    ),
    'nr_count' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '7',
        'type' => 'int',
        'ext' => ''
    ),
    'createuser' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '11',
        'type' => 'hidden',
        'ext' => ''
    ),
    'createtime' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '11',
        'type' => 'hidden',
        'ext' => ''
    ),
    'updateuser' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '11',
        'type' => 'hidden',
        'ext' => ''
    ),
    'updatetime' => array(
        'req' => 0,
        'size' => '',
        'maxlen' => '11',
        'type' => 'hidden',
        'ext' => ''
    )
);
