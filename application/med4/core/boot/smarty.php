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

require_once( DIR_LIB . '/smarty/libs/Smarty.class.php' );

$smarty = new Smarty;

$smarty->plugins_dir = array(DIR_LIB  .'/smarty/libs/plugins', DIR_EXT . '/plugins');
$smarty->compile_dir = 'templates/cache';

$smarty->force_compile = true;

/***
 * Neue Smarty Ressource für E-Mail
**/

function email_get_template($tpl_name, &$tpl_source, $smarty_obj)
{
    $tpl = dlookup( $smarty_obj->_tpl_vars['db'], 'email', 'template', "bez='$tpl_name'" );

    $tpl_source = $tpl;

    return true;
}


function email_get_subject($tpl_name, &$emailSubject, $smarty_obj)
{
    $emailSubject = dlookup($smarty_obj->_tpl_vars['db'], 'email', 'subject', "bez='$tpl_name'" );

    return true;
}

function email_get_from($tpl_name, &$emailFrom, $smarty_obj)
{
    $emailFrom = dlookup( $smarty_obj->_tpl_vars['db'], 'email', "email_from", "bez='$tpl_name'" );

    return true;
}

function email_get_timestamp($tpl_name, &$tpl_timestamp, $smarty_obj)
{
    $tpl_timestamp = time();
    return true;
}

function email_db_get_secure($tpl_name, $smarty_obj)
{
    return true;
}

function email_db_get_trusted($tpl_name, $smarty_obj)
{
    // wird für Templates nicht verwendet, muss aber implementiert werden
    return true;
}

// Ressource 'email' registrieren
$smarty->register_resource( 'email', array('email_get_template' ,
        'email_get_timestamp',
        'email_get_secure'   ,
        'email_get_trusted'   ) );

$smarty->register_resource('email_subject', array('email_get_subject' ,
        'email_get_timestamp',
        'email_get_secure'   ,
        'email_get_trusted'   ));

$smarty->register_resource('email_from', array('email_get_from' ,
        'email_get_timestamp',
        'email_get_secure'   ,
        'email_get_trusted'   ));

?>
