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

//funktionen um temporäre inhalte im materialordner zu verwaltenfunction get_upload_dir($smarty){	$smarty->config_load(FILE_CONFIG_SERVER,  'upload');	$config              = $smarty->get_config_vars();	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')	{	   $upload_dir          = $config['upload_dir_win'];	}	else	{	   $upload_dir          = $config['upload_dir'];	}	return $upload_dir;}function get_tmp_dir($smarty){	$smarty->config_load(FILE_CONFIG_SERVER,  'upload');	$config              = $smarty->get_config_vars();	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')	{	   $upload_dir          = $config['tmp_dir_win'];	}	else	{	   $upload_dir          = $config['tmp_dir'];	}	return $upload_dir;}function getUploadDir($smarty, $type, $straight = true){   $backup = $smarty->get_config_vars();   $smarty->clear_config();   $smarty->config_load(FILE_CONFIG_SERVER,  'upload');   $config = $smarty->get_config_vars();   $typeDir = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'      ? (isset($config["{$type}_dir_win"]) === false ? null : $config["{$type}_dir_win"])      : (isset($config["{$type}_dir"]) === false ? null : $config["{$type}_dir"]);   $smarty->set_config($backup);   if ($typeDir === null) {      echo 'No directory for this type declared';      exit;   }   if ($straight !== true) {      $typeDir = array(         $type => $typeDir,         'config' => $config      );   }   return $typeDir;}?>