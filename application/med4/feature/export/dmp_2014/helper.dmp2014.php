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

require_once('class.dmp_2014_0_model.php');
require_once('feature/export/helper.dmp.php');

class HDmp2014
{

    /**
     *
     *
     * @static
     * @access public
     * @param $parameters
     * @param $formId
     * @param $type
     * @param $smarty
     * @param $db
     * @return void
     */
    static public function checkForm($parameters, $formId, $type, $smarty, $db)
    {
        $absolutePath = "feature/export/dmp_2014/";
        HDatabase::LoadExportSettings($db, $parameters, 'dmp_2014');
        $model = new Cdmp_2014_0_Model;
        $model->Create($absolutePath, 'dmp_2014', $smarty, $db);
        $model->SetParameters($parameters);
        $exportRecord = new RExport;
        $exportRecord->SetExportName('dmp_2014');
        $exportRecord->SetExportNr(1);
        $exportRecord->SetOrgId($parameters['org_id']);
        $exportRecord->SetCreateUserId($parameters['user_id']);
        $exportRecord->SetFinished(0);
        $exportRecord->SetParameters($parameters);
        $model->ExtractDataExt($parameters, null, $exportRecord, $formId, $type);
        $result = $model->CheckData($parameters, $exportRecord);
        return $result;
    }


    /**
     *
     *
     * @static
     * @access public
     * @param      $xml
     * @param      $filename
     * @param      $exportPath
     * @param      $fileFilter
     * @param bool $deleteDirectory
     * @return array
     * @throws Exception
     */
    static public function checkXml($xml, $filename, $exportPath, $type, $fileFilter, $deleteDirectory = true)
    {
        $lower_type = strtolower($type);
        $upper_type = strtoupper($type);
        $result = array(
            'xml' => '',
            'xml_protocol' => '',
            'xml_status_text' => '',
            'xml_status' => 0
        );
        // Init
        $xpmTmpPath = $exportPath;
        $kbvTabelle = 'kbv_tabelle_ebk.bin';
        if ($lower_type == 'fd') {
            $kbvTabelle = 'kbv_tabelle_fbk.bin';
        }
        $xpmBasePath = "./XPM/";
        // Verzeichnisse anlegen
        HFileSystem::CreatePath($xpmTmpPath);
        // Config-Datei erstellen und schreiben
        $xpmConfigXml =
            "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n" .
            "<konfiguration>\n" .
            "    <grundeinstellung>\n" .
            "        <pruefpfad>{$xpmBasePath}</pruefpfad>\n" .
            "        <pruefdaten>{$xpmTmpPath}</pruefdaten>\n" .
            "        <okdaten>{$xpmTmpPath}</okdaten>\n" .
            "        <fehlerdaten>{$xpmTmpPath}</fehlerdaten>\n" .
            "        <datei_filter>{$fileFilter}</datei_filter>\n" .
            "        <pruefschema>{$xpmBasePath}Schema/DMP_Brustkrebs_{$upper_type}.xsd</pruefschema>\n" .
            "        <warnungen>ja</warnungen>\n" .
            "    </grundeinstellung>\n" .
            "    <eingabedateien>\n" .
            "        <kbv_tabelle>{$xpmBasePath}Kbvtab/{$kbvTabelle}</kbv_tabelle>\n" .
            "        <KTStamm>{$xpmBasePath}Kbvtab/KTStamm.bin</KTStamm>\n" .
            "    </eingabedateien>\n" .
            "    <ausgabedateien>\n" .
            "        <FehlerListe Format=\"XML\">{$xpmTmpPath}{$filename}.Protocol.xml</FehlerListe>\n" .
            "        <StatistikListe Format=\"XML\">{$xpmTmpPath}{$filename}.Statistik.xml</StatistikListe>\n" .
            "    </ausgabedateien>\n" .
            "</konfiguration>";
        file_put_contents("{$xpmTmpPath}xpm_{$lower_type}_config.xml", $xpmConfigXml);
        // XML-Datei schreiben
        $xmlPathname = $xpmTmpPath . $filename;
        file_put_contents($xmlPathname, HelperDmp::replaceXmlSpecialChars($xml));
        // XML-Datei durch Prüfmodul checken
        if (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'win32')) {
            // Windows
            $osChar = ';';
        }
        else {
            // Unix
            $osChar = ':';
        }
        $paramList = array(
            '-Dfile.encoding=8859_15 ',
            '-Duser.language=de ',
            '-classpath '
            . $xpmBasePath . 'Bin/xpm-3.2.4.jar' . $osChar
            . $xpmBasePath . 'Bin/pruefungBKED.jar' . $osChar
            . $xpmBasePath . 'Bin/pruefungBKFD.jar' . $osChar
            . $xpmBasePath . 'Bin/xerces-2.9.1.jar' . $osChar
            . $xpmBasePath . 'Bin/jasperreports-2.0.5.jar' . $osChar
            . $xpmBasePath . 'Bin/log4j-1.2.16.jar' . $osChar
            . $xpmBasePath . 'Bin/pdfviewer-1.16.jar' . $osChar
            . ' ',
            'de.kbv.pruefmodul.Main ',
            '-c ' . $xpmTmpPath . 'xpm_' . $lower_type . '_config.xml ',
            '-f ' . $xmlPathname
        );
        $exec = "java ";
        foreach ($paramList as $param) {
            $exec .= $param;
        }
        $cwd = getcwd();
        chdir("{$cwd}/feature/export/dmp_2014/");
        $e = exec($exec, $result, $code);
        chdir($cwd);
        // Prüfen ob Protokoll erstellt wurde
        if (file_exists("{$xpmTmpPath}{$filename}.Protocol.xml")) {
            $xmlProtocol = file_get_contents("{$xpmTmpPath}{$filename}.Protocol.xml");
            if (!$xmlProtocol) {
                throw new Exception("Konnte [{$xpmTmpPath}{$filename}.Protocol.xml] Datei nicht lesen.");
            }
        }
        else {
            throw new Exception( "Protokoll-Datei [{$xpmTmpPath}{$filename}.Protocol.xml] existiert nicht." );
        }
        // Protokoll-Datei auswerten
        if (!is_null($xmlProtocol) && (strlen($xmlProtocol) > 0)) {
            $result['xml_protocol'] = $xmlProtocol;
            $tmpXml = new SimpleXMLElement($xmlProtocol);
            $abort = ($tmpXml->parameter->ABBRUCH == '0') ? false : true;
            $errors = (intval($tmpXml->parameter->FEHLER) > 0) ? true : false;
            $warnings = (intval($tmpXml->parameter->WARNUNGEN) > 0) ? true : false;
            $infos = (intval($tmpXml->parameter->INFOS) > 0) ? true : false;
            if ($abort) {
                $result['xml_status_text'] = 'Abbruch';
                $result['xml_status'] = 3;
            }
            elseif ($errors) {
                $result['xml_status_text'] = 'Fehler';
                $result['xml_status'] = 2;
            }
            elseif ($warnings || $infos) {
                $result['xml_status_text'] = 'Warnung';
                $result['xml_status'] = 1;
            }
            else {
                $result['xml_status_text'] = 'Ok';
                $result['xml_status'] = 0;
            }
        }
        if ($deleteDirectory) {
            // Verzeichniss mit allem löschen
            HFileSystem::DeleteDirectory($xpmTmpPath);
        }
        $result['xml'] = $xml;
        return $result;
    }


    static public function cryptZip($parameter, $zipDirPath, $zipFilename)
    {
        $xkmTmpPath = $zipDirPath;
        $xkmOkPath = $xkmTmpPath . $parameter['zip_ok_dir'];
        HFileSystem::CreatePath($xkmOkPath);
        //$xkm_url = "index.php?page=export_dmp&action=download&type=bin&file=" . $xkmOkPath . $xkmfile;
        //$xkmTmpPath = $exportPath;
        $xkmBasePath = "./XKM";
        $protokolOutputType = "pdf";
        $xkmProtocolFilename = $xkmTmpPath . "xkm_protocol." . strtolower($protokolOutputType);
        $xkmConfigXml =
            "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>" .
            "<konfiguration>" .
            "   <quelle>$xkmTmpPath</quelle>" .
            "   <ausschuss>$xkmTmpPath</ausschuss>" .
            "   <entschluesselt>$xkmTmpPath</entschluesselt>" .
            "   <verschluesselt>$xkmOkPath</verschluesselt>" .
            "   <arbeitsmodus>Verschluesselung</arbeitsmodus>" .
            "   <protokolldatei>$xkmProtocolFilename</protokolldatei>" .
            "   <system>$xkmBasePath/System/</system>" .
            "   <schluesselpfad>$xkmBasePath/System/keys/</schluesselpfad>" .
            "   <schluesseldatei>$xkmBasePath/Konfig/schluessel.xml</schluesseldatei>" .
            "   <protokollformat>" . strtoupper($protokolOutputType) . "</protokollformat>" .
            "   <diskette>Nein</diskette>" .
            "   <paketgroesse>unbegrenzt</paketgroesse>" .
            "   <konfigdialog>ja</konfigdialog>" .
            "   <abrechnungskomprimierung>ja</abrechnungskomprimierung>" .
            "</konfiguration>";
        file_put_contents("{$xkmTmpPath}/xkm_config.xml", $xkmConfigXml);
        if (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'win32')) {
            // Windows
            $osChar = ';';
        }
        else {
            // Unix
            $osChar = ':';
        }
        $paramList = array(
            '-Xmx300m ',
            '-Dfile.encoding=8859_1 ',
            '-Duser.language=de ',
            '-Djava.awt.headless=true ',
            '-classpath '
            . $xkmBasePath . '/Bin/xkm.jar' . $osChar
            . $xkmBasePath . '/Bin/gnu.jar' . $osChar
            . $xkmBasePath . '/Bin/bcprov-jdk14-133.jar' . $osChar
            . $xkmBasePath . '/Bin/commons-beanutils.jar' . $osChar
            . $xkmBasePath . '/Bin/commons-collections.jar' . $osChar
            . $xkmBasePath . '/Bin/commons-digester.jar' . $osChar
            . $xkmBasePath . '/Bin/commons-logging.jar' . $osChar
            . $xkmBasePath . '/Bin/itext.jar' . $osChar
            . $xkmBasePath . '/Bin/jasperreports.jar' . $osChar
            . $xkmBasePath . '/Bin/jakarta-poi.jar' . $osChar
			. $xkmBasePath . '/Bin/pdfviewer-1.16.jar' . $osChar
            . $xkmBasePath . '/Bin/kbvzip.jar ',
            'de.kbv.xkm.Main ',
            '-c ' . $xkmTmpPath . 'xkm_config.xml ',
            '-f ' . $zipDirPath . $zipFilename . ' ',
            '-m DMP_Verschluesselung'
        );
        $exec = "java ";
        foreach ($paramList as $param) {
            $exec .= $param;
        }
        $cwd = getcwd();
        chdir("{$cwd}/feature/export/dmp_2014/");
        //
        // ACHTUNG: Schlägt fehl wenn im Verzeichnis ../XKM/System/Work andere Verzeichnise wie z.B. .svn sind!!!
        //          Es darf keine Unterverzeichnise geben!!!
        //
        $e = exec($exec, $result, $code);
        if (0 != $code) {
            die("DMP-XKM error: $e");
        }
        chdir($cwd);
        $paths = array(
            'xkmFileName' => "{$zipFilename}.XKM",
            'xkmFilePath' => "{$xkmOkPath}{$zipFilename}.XKM",
            'xkmCryptoProtocolPath' => $xkmProtocolFilename
        );
        return $paths;
    }

}

?>
