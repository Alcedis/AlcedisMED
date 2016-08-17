<?php/*
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

//funktionen um temporäre inhalte im materialordner zu verwaltenfunction check_user_dir($username){    return is_dir("material/$username");}function create_user_dir($username){    mkdir("material/$username");}function empty_user_dir($username){    if (check_user_dir($username)) {        foreach (array_diff(scandir("material/$username"), array('.', '..')) as $file) {           $path = "material/$username/$file";           if (file_exists($path) && !is_dir($path))                unlink($path);        }    } else {       create_user_dir($username);    }}function get_user_dir($username){   return "material/{$username}/";}?>