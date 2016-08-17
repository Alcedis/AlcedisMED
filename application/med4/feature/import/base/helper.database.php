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

require_once( 'class.importexception.php' );

class HDatabase
{

	/**
     * loads import settings and throw error if not defined
     * @param unknown_type $base_settings
     * @param unknown_type $export_name
     */
    static public function LoadSettings( $db, &$base_settings, $import_name )
    {
        $settings_found = false;
        $import_settings = json_decode(
            dlookup( $db, 'settings_import', 'settings', "name = '{$import_name}'" ),
                     true );
        if ( null != $import_settings ) {
            foreach( $import_settings as $settings ) {
                if ( $settings[ 'org_id' ] == $base_settings[ 'org_id' ] ) {
                    $base_settings = array_merge( $base_settings, $settings );
                    $settings_found = true;
                    break;
                }
            }
        }
        if ( false === $settings_found ) {
            throw new EImportException( "No settings defined for org_id ({$base_settings['org_id']})" );
        }
    }

}

?>
