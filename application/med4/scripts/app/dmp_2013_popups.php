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

if ($permission->action($action) === true) {
    $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
    require($permission->getActionFilePath());
}

if (!isset($_REQUEST['type'])) {
    throw new Exception("Popup Typ muss gestzt sein.");
}
switch($_REQUEST['type']) {
    case 'export_statistik_ed_2013' :
    case 'export_statistik_fd_2013' :
    case 'verschluesselungsprotokoll' :
    case 'dmp_log_file' :
        if (!isset($_REQUEST['file'])) {
            throw new Exception("DMP: Datei muss angegeben werden.");
        }
        $file = $_REQUEST['file'];
        download::create($file, "pdf")->output();

        break;

    case 'dmp_ed_2013_bogen' :
        if (!isset($_REQUEST['id'])) {
            throw new Exception("DMP-Bogen: Id muss angegeben werden.");
        }
        $dmp_ed_2013_id = $_REQUEST['id'];
        show_dmp_ed_2013_bogen($dmp_ed_2013_id, $db);

        break;

    case 'dmp_ed_pnp_2013_bogen' :
        if (!isset($_REQUEST['id'])) {
            throw new Exception("DMP-Bogen: Id muss angegeben werden.");
        }
        $dmp_ed_pnp_2013_id = $_REQUEST['id'];
        show_dmp_ed_pnp_2013_bogen($dmp_ed_pnp_2013_id, $db);

        break;

    case 'dmp_fd_2013_bogen' :
        if (!isset($_REQUEST['id'])) {
            throw new Exception("DMP-Bogen: Id muss angegeben werden.");
        }
        $dmp_fd_2013_id = $_REQUEST['id'];
        show_dmp_fd_2013_bogen($dmp_fd_2013_id, $db);

        break;

    default :
        throw new Exception("Popup Typ ist unbekannt.");

        break;
}
exit();

/**
 *
 *
 * @access
 * @param $dmp_ed_2013_id
 * @param $db
 * @return void
 * @throws Exception
 */
function show_dmp_ed_2013_bogen($dmp_ed_2013_id, $db)
{
    $query = "
        SELECT
            xml
        FROM
            dmp_brustkrebs_ed_2013
        WHERE
            dmp_brustkrebs_ed_2013_id=$dmp_ed_2013_id
    ";
    $result = sql_query_array($db, $query);
    if (($result != false) && isset($result[0]['xml']) && !is_null($result[0]['xml'])) {
        show_dmp_bogen($result[0]['xml']);
    } else {
        throw new Exception("Keine Daten f�r Anzeige vorhanden.");
    }
}


/**
 *
 *
 * @access
 * @param $dmp_ed_pnp_2013_id
 * @param $db
 * @return void
 * @throws Exception
 */
function show_dmp_ed_pnp_2013_bogen($dmp_ed_pnp_2013_id, $db)
{
    $query = "
        SELECT
            xml
        FROM
            dmp_brustkrebs_ed_pnp_2013
        WHERE
            dmp_brustkrebs_ed_pnp_2013_id=$dmp_ed_pnp_2013_id
    ";
    $result = sql_query_array($db, $query);
    if (($result != false) && isset($result[0]['xml']) && !is_null($result[0]['xml'])) {
        show_dmp_bogen($result[0]['xml']);
    } else {
        throw new Exception("Keine Daten f�r Anzeige vorhanden.");
    }
}


/**
 *
 *
 * @access
 * @param $dmp_fd_2013_id
 * @param $db
 * @return void
 * @throws Exception
 */
function show_dmp_fd_2013_bogen($dmp_fd_2013_id, $db)
{
    $query = "
        SELECT
            xml
        FROM
            dmp_brustkrebs_fd_2013
        WHERE
            dmp_brustkrebs_fd_2013_id=$dmp_fd_2013_id
    ";
    $result = sql_query_array($db, $query);
    if (($result != false) && isset($result[0]['xml']) && !is_null($result[0]['xml'])) {
        show_dmp_bogen($result[0]['xml']);
    } else {
        throw new Exception("Keine Daten f�r Anzeige vorhanden.");
    }
}


/**
 *
 *
 * @access
 * @param $xml
 * @return void
 */
function show_dmp_bogen($xml)
{
    // Referenz auf Stylesheet einbauen
    $xml_arr = explode("\n", $xml);

    $xsl_line = '<?xml-stylesheet href="feature/export/dmp/eDokumentation.xsl" type="text/xsl"?>' . "\n";
    $xml_arr = array_merge(array($xml_arr[0], $xsl_line), array_slice($xml_arr, 1));

    // Stringl�ngen-Begrenzung f�r bestimmte Felder
    $line_vorname  = -1;
    $mode_vorname  = false;
    $sub_vorname   = 0;
    foreach ($xml_arr as $i => $line) {
        // P3-15: Titel/Vorname max. 19 Zeichen
        if (strpos($line, 'PATSBJ')) {
            $mode_vorname = true;
        }

        if ($mode_vorname && strpos($line, '<GIV')) {
            $line_vorname = $i;
        }

        if ($mode_vorname && strpos($line, '<PFX')) {
            $parts = explode('"', $line);
            if(isset($parts[1])) {
                $sub_vorname = strlen($parts[1]) + 2;
            }
        }

        if ($mode_vorname && strpos($line, '</nm>')) {
            $mode_vorname = false;
            $parts = explode('"', $xml_arr[$line_vorname]);
            if(isset($parts[1])) {
                $parts[1] = substr($parts[1], 0, max(0, 28 - $sub_vorname));
                $xml_arr[$line_vorname] = implode('"', $parts);
            }
        }
    }


    // Ausgabe
    header('Content-type: text/xml; charset=ISO-8859-1');
    echo implode('', $xml_arr);
}
?>
