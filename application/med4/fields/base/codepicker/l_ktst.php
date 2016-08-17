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
	'iknr'        => array( 'req' => 0, 'size' => '15', 'maxlen' => '255', 'type' => 'string', 'ext' => '' ),
	'name'        => array( 'req' => 0, 'size' => '30', 'maxlen' => '255', 'type' => 'string', 'ext' => '' ),
	'vknr'        => array( 'req' => 0, 'size' => '15', 'maxlen' => '255', 'type' => 'string', 'ext' => '' ),
	'strasse'     => array( 'req' => 0, 'size' => ''  , 'maxlen' => '255', 'type' => 'string', 'ext' => '' ),
	'plz'         => array( 'req' => 0, 'size' => '5' , 'maxlen' => '5'  , 'type' => 'string', 'ext' => '' ),
	'ort'         => array( 'req' => 0, 'size' => '19', 'maxlen' => '255', 'type' => 'string', 'ext' => '' ),
	'gueltig_von' => array( 'req' => 0, 'size' => ''  , 'maxlen' => '255', 'type' => 'date'  , 'ext' => '' ),
	'gueltig_bis' => array( 'req' => 0, 'size' => ''  , 'maxlen' => '255', 'type' => 'date'  , 'ext' => '' )
);

?>