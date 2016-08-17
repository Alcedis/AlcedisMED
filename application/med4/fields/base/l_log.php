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
	'l_log_id'    => array( 'req' => 0, 'size' => '', 'maxlen' => '', 'type' => 'int'    , 'ext' => '' ),
	'ip'          => array( 'req' => 0, 'size' => '', 'maxlen' => '', 'type' => 'string' , 'ext' => '' ),
	'loginname'   => array( 'req' => 0, 'size' => '', 'maxlen' => '', 'type' => 'string' , 'ext' => '' ),
	'session_id'  => array( 'req' => 0, 'size' => '', 'maxlen' => '', 'type' => 'string' , 'ext' => '' ),
	'time_login'  => array( 'req' => 0, 'size' => '', 'maxlen' => '', 'type' => 'date'   , 'ext' => '' ),
	'time_logout' => array( 'req' => 0, 'size' => '', 'maxlen' => '', 'type' => 'date'   , 'ext' => '' ),
	'dauer'       => array( 'req' => 0, 'size' => '', 'maxlen' => '', 'type' => 'string' , 'ext' => '' ),
);

?>
