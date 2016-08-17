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

require_once('feature/exports/scripts/class.medexportfactory.php');
require_once('feature/exports/scripts/csv/class.csv2_0_0.php');
require_once(DIR_LIB . '/zip/pclzip.lib.php');

if ( $permission->action( $action ) === true ) {
    require( $permission->getActionFilePath() );
}

$view_state = '';

// Config laden
$smarty->config_load( 'settings/interfaces.conf' );
$config  = $smarty->get_config_vars();
$smarty->config_load( 'app/export_csv.conf', 'export_csv' );
$config = $smarty->get_config_vars();

$tables = array_merge(
    array(
        'all' => 'alle'
    ),
    CCsv2_0_0::getRequiredTables()
);

foreach ($tables as $i => &$table) {
    if (is_numeric($i) === true) {
        $table = "(SELECT '{$table}' AS value, '{$table}' AS label)";
    } else {
        $table = "(SELECT '{$i}' AS value, '{$table}' AS label)";
    }
}

$fields = array(
    'sel_tabelle'    => array('req' => 1, 'size' => '', 'maxlen' => '', 'type' => 'query',  'ext' => implode(' UNION ', $tables), 'emptyField' => false),
    'sel_erkrankung' => array('req' => 1, 'size' => '', 'maxlen' => '', 'type' => 'lookup', 'ext' => array('l_basic' => 'erkrankung'), 'emptyField' => false)
);

// Werte aus Formular in Fields legen
form2fields( $fields );

$fields[ 'sel_tabelle' ][ 'value' ][ 0 ] = 'all';
$fields[ 'sel_erkrankung' ][ 'value' ][ 0 ] = 'all';

// Formularfelder generieren
$item = new itemgenerator( $smarty, $db, $fields, $config );
$item->preselected = false;
$item->generate_elements();

$smarty->assign( "back_btn", "page=extras" );

if ( ( $permission->action( "export" ) === true ) &&
     ( $permission->action( "download" ) === true ) ) {
    // Wenn keine Aktion, dann Script nicht weiter ausführen
    if ( $action != 'export' ) {
        return;
    }

    // Validierung starten
    $validate = validate_dataset( $smarty, $db, $fields, 'ext_err', '' );
    if ( !$validate ) {
        $item->generate_elements();
        return;
    }

    $smarty->config_load( 'settings/interfaces.conf' );
    $smarty->config_load( 'app/export_csv.conf', 'export_csv' );
    $smarty->config_load( FILE_CONFIG_APP );
    $config = $smarty->get_config_vars();
    $login_name = isset( $_SESSION[ 'sess_loginname' ] ) ? $_SESSION[ 'sess_loginname' ] : '';
    $ext_dir = isset( $config[ 'exp_csv_dir' ] ) ? $config[ 'exp_csv_dir' ] : 'csv/';
    $export_obj = CMedExportFactory::CreateObject( $smarty, $db, "Csv", "2.0" );
    $export_path = $export_obj->GetExportPath( $ext_dir, $login_name );
    $result = $export_obj->Export( $_SESSION, $_REQUEST );

    $view_state = 'result';
    $csv_files = array();
    $csv_urls = array();
    $rowcount = array();
    $xml_paths = array();

    if ($result !== false) {
        foreach ($result['valid'] as $item) {
            $csv_files[] = $item[ 'file' ];
            $csv_urls[]  = "index.php?page=export_csv&action=download&type=csv&file=" . $item[ 'url' ];
            $rowcount[]  = $item[ 'count' ];
            $xml_paths[] = $item[ 'url' ];
        }
    }

    $zip_filename = "csv_export_all_" . date( "Ymd_His" ) . ".zip";
    $zip_path = $export_path . $zip_filename;
    $zip = new PclZip( $zip_path );
    $zip->create( $xml_paths, '', $export_path );
    $zip_url = "index.php?page=export_csv&action=download&type=zip&file=" . $zip_path;

    // Ausgabe im HTML
    $smarty->assign( 'csv_files', $csv_files );
    $smarty->assign( 'csv_urls', $csv_urls	);
    $smarty->assign( 'rowcount', $rowcount );
    $smarty->assign( 'zip_url', $zip_url );
    $smarty->assign( 'zip_filename', $zip_filename );

    $smarty->assign( 'back_btn', 'page=extras' );
}

$smarty->assign( "view", $view_state );

function ext_err( $valid )
{
}

?>
