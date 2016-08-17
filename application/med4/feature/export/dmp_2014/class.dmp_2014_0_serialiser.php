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

require_once(DIR_LIB.'/zip/pclzip.lib.php');
require_once('feature/export/base/class.exportxmlserialiser.php');
require_once('feature/export/history/class.historymanager.php');
require_once('feature/export/history/class.history.php');
require_once('reports/pdf/b/dmp_begleitzettel_2014.php');
require_once('core/class/report/alcReportPdf.php');
require_once('feature/export/helper.dmp.php');

class Cdmp_2014_0_Serialiser extends CExportXmlSerialiser
{

    /**
     *
     *
     * @access public
     * @return
     */
    public function __construct()
    {
    }

    /**
     * @see CExportXmlSerialiser::Validate
     */
    public function Validate($parameters)
    {
        $checkCount = 0;
        $filePostfix = '';
        $type = '';
        $exportPath =
            $this->GetExportPath($parameters['main_dir'], $parameters['login_name']) . $parameters["check_dir"];
        $result = array();
        // Alle neuen Cases im alten suchen...
        $cases = $this->m_export_record->GetCases();
        foreach ($cases as $caseKey => $case) {
            $sections = $case->GetSections();
            $data = array();
            $data['header'] = $this->_GetSectionData($case, 'header');
            // Check
            foreach ($sections as $sectionKey => $section) {
                if ($section->GetBlock() !== 'header') {
                    switch ($section->GetBlock()) {
                        case 'ed' :
                            $schemaFileName = "DMP_Brustkrebs_ED.xsd";
                            $data['header']['document']['version'] = 'DMP_BRK_EE';
                            $data['header']['document']['description'] = 'Erst-Dokumentation Brustkrebs';
                            $data['body'] = $section->GetDaten();
                            $filePostfix = 'EBK';
                            $template = 'dmp_2014_0_ed.tpl';
                            $type = 'ed';
                            break;
                        case 'ed_pnp' :
                            $schemaFileName = "DMP_Brustkrebs_ED.xsd";
                            $data['header']['document']['version'] = 'DMP_BRK_EE';
                            $data['header']['document']['description'] = 'Erst-Dokumentation Brustkrebs';
                            $data['body'] = $section->GetDaten();
                            $filePostfix = 'EBK';
                            $template = 'dmp_2014_0_ed_pnp.tpl';
                            $type = 'ed';
                            break;
                        case 'fd' :
                            $schemaFileName = "DMP_Brustkrebs_FD.xsd";
                            $data['header']['document']['version'] = 'DMP_BRK_EF';
                            $data['header']['document']['description'] = 'Folge-Dokumentation Brustkrebs';
                            $data['body'] = $section->GetDaten();
                            $filePostfix = 'FBK';
                            $template = 'dmp_2014_0_fd.tpl';
                            $type = 'fd';
                            break;
                    }
                    $data = $this->ReplaceAllXmlEntities($data);
                    $this->m_internal_smarty->assign('schemaFileName', $schemaFileName);
                    $this->m_internal_smarty->assign('data', $data);
                    $xml = $this->m_internal_smarty->fetch($template);
                    // XML-Datei prüfen mit Prüfmodul
                    $filename =
                        $data['header']['bsnr'] . "_" .
                        $data['header']['provider']['patient']['fall_nr'] . "_" .
                        date("Ymd", strtotime($data['header']['dokumentations_datum'])) . "." .
                        $filePostfix;
                    $fileFilter = "*.{$filePostfix}";
                    $result = HelperDmp::checkXml(
                        $xml,
                        $filename,
                        $exportPath,
                        $type,
                        $fileFilter,
                        false,
                        '2015-01-01'
                    );
                    $this->_updateDmpXmlData($type, $data['header']['dmp_dbid'], $result);
                    $checkCount++;
                    $bodyData = $section->GetDaten();
                    if ($result['xml_status'] > 0) {
                        $bodyData['xml_status'] = HelperDmp::getStatusText($result['xml_status']);
                        $errors = HelperDmp::parseErrorsFromProtocol($result['xml_protocol']);
                        // Ticket #12416
                        $errors = $this->removeJavaVersionWarning($errors);
                        $case->SetSectionErrorsByUid($section->GetSectionUid(), $errors);
                        // Ticket #12416
                        if (count($errors) == 0) {
                            $bodyData['xml_status'] = 'Ok';
                        }
                    }
                    else {
                        $bodyData['xml_status'] = HelperDmp::getStatusText($result['xml_status']);
                        $case->SetSectionErrorsByUid($section->GetSectionUid(), array());
                    }
                    $section->SetDaten($bodyData);
                    $sections[$sectionKey] = $section;
                    $case->SetSections($sections);
                }
            }
            $cases[$caseKey] = $case;
        }
        $this->m_export_record->SetCases($cases);
        //HFileSystem::DeleteDirectory($exportPath);
        return $result;
    }


    /**
     *
     *
     * @access
     * @param $errors
     * @return array
     */
    private function removeJavaVersionWarning($errors) {
        // Ticket #12416
        $result = array();
        foreach ($errors as $error) {
            if (false === strpos($error, "System ist noch eine ältere Version")) {
                $result[] = $error;
            }
        }
        return $result;
    }


    /**
     *
     *
     * @access
     * @param $type
     * @param $dmp_dbid
     * @param $xmlProtocol
     * @return void
     */
    protected function _updateDmpXmlData($type, $dmp_dbid, $xmlData)
    {
        $query = "
            UPDATE
                dmp_brustkrebs_{$type}_2013

            SET
                xml = '{$this->_cleanupForDatabase($xmlData['xml'])}',
                xml_protokoll = '{$this->_cleanupForDatabase($xmlData['xml_protocol'])}',
                xml_status = {$xmlData['xml_status']}

            WHERE
                dmp_brustkrebs_{$type}_2013_id ='{$dmp_dbid}'
        ";
        mysql_query($query, $this->m_db);
        if (mysql_errno($this->m_db) > 0) {
            die(mysql_error($this->m_db));
        }
    }


    /**
     *
     *
     * @access
     * @param $text
     * @return string
     */
    protected function _cleanupForDatabase($text)
    {
        return mysql_real_escape_string($text);
    }


    /**
     * @see CExportXmlSerialiser::Encrypt
     */
    public function Encrypt($parameters)
    {
    }


    /**
     * @see CExportXmlSerialiser::Write
     */
    public function Write($parameters)
    {
        $queryVersionNbr = "";
        $anyFilesExportable = false;
        $data = array();
        $xmlFiles = array();
        $dmp_ed_patdat = array();
        $dmp_ed_pnp_patdat = array();
        $dmp_fd_patdat = array();
        $filename = "";
        $filePostfix = '';
        $exportPath = $this->GetExportPath($parameters['main_dir'], $parameters['login_name']);
        $checkExportPath = $exportPath . $parameters['check_dir'];
        $xmlExportPath = $exportPath . $parameters['xml_dir'];
        HFileSystem::DeleteDirectory($xmlExportPath);
        HFileSystem::CreatePath($xmlExportPath);
        $cases = $this->m_export_record->GetCases();
        foreach ($cases as $caseKey => $case) {
            if ($case->IsCaseValid()) {
                $anyFilesExportable = true;
                $sections = $case->GetSections();
                $data['header'] = $this->_GetSectionData($case, 'header');
                // Check
                foreach ($sections as $sectionKey => $section) {
                    if ($section->GetBlock() !== 'header') {
                        switch ($section->GetBlock()) {
                            case 'ed' :
                                $schemaFileName = "DMP_Brustkrebs_ED.xsd";
                                $data['header']['document']['version'] = 'DMP_BRK_EE';
                                $data['header']['document']['description'] = 'Erst-Dokumentation Brustkrebs';
                                $data['body'] = $section->GetDaten();
                                $filePostfix = 'EBK';
                                $template = 'dmp_2014_0_ed.tpl';
                                $queryVersionNbr = "
                                    INSERT INTO dmp_version_nbr_2013 (
                                        dmp_brustkrebs_ed_2013_id,
                                        dmp_brustkrebs_ed_pnp_2013_id,
                                        dmp_brustkrebs_fd_2013_id,
                                        document_id,
                                        bsnr,
                                        version_nbr,
                                        date
                                    )
                                    VALUES (
                                        {$data['header']['dmp_dbid']},
                                        NULL,
                                        NULL,
                                        '{$data['header']['dmp_dokument_id']}',
                                        '{$data['header']['bsnr']}',
                                        {$data['header']['version_nbr']},
                                        NOW()
                                    )
                                ";
                                break;
                            case 'ed_pnp' :
                                $schemaFileName = "DMP_Brustkrebs_ED.xsd";
                                $data['header']['document']['version'] = 'DMP_BRK_EE';
                                $data['header']['document']['description'] = 'Erst-Dokumentation Brustkrebs';
                                $data['body'] = $section->GetDaten();
                                $filePostfix = 'EBK';
                                $template = 'dmp_2014_0_ed_pnp.tpl';
                                $queryVersionNbr = "
                                    INSERT INTO dmp_version_nbr_2013 (
                                        dmp_brustkrebs_ed_2013_id,
                                        dmp_brustkrebs_ed_pnp_2013_id,
                                        dmp_brustkrebs_fd_2013_id,
                                        document_id,
                                        bsnr,
                                        version_nbr,
                                        date
                                    )
                                    VALUES (
                                        NULL,
                                        {$data['header']['dmp_dbid']},
                                        NULL,
                                        '{$data['header']['dmp_dokument_id']}',
                                        '{$data['header']['bsnr']}',
                                        {$data['header']['version_nbr']},
                                        NOW()
                                    )
                                ";
                                break;
                            case 'fd' :
                                $schemaFileName = "DMP_Brustkrebs_FD.xsd";
                                $data['header']['document']['version'] = 'DMP_BRK_EF';
                                $data['header']['document']['description'] = 'Folge-Dokumentation Brustkrebs';
                                $data['body'] = $section->GetDaten();
                                $filePostfix = 'FBK';
                                $template = 'dmp_2014_0_fd.tpl';
                                $queryVersionNbr = "
                                    INSERT INTO dmp_version_nbr_2013 (
                                        dmp_brustkrebs_ed_2013_id,
                                        dmp_brustkrebs_ed_pnp_2013_id,
                                        dmp_brustkrebs_fd_2013_id,
                                        document_id,
                                        bsnr,
                                        version_nbr,
                                        date
                                    )
                                    VALUES (
                                        NULL,
                                        NULL,
                                        {$data['header']['dmp_dbid']},
                                        '{$data['header']['dmp_dokument_id']}',
                                        '{$data['header']['bsnr']}',
                                        {$data['header']['version_nbr']},
                                        NOW()
                                    )
                                ";
                                break;
                        }
                        $data = $this->ReplaceAllXmlEntities($data);
                        $this->m_internal_smarty->assign('schemaFileName', $schemaFileName);
                        $this->m_internal_smarty->assign('data', $data);
                        $xml = $this->m_internal_smarty->fetch($template);
                        $filename =
                            $data['header']['bsnr'] . "_" .
                            $data['header']['provider']['patient']['fall_nr'] . "_" .
                            date('Ymd', strtotime($data['header']['dokumentations_datum'])) . "." .
                            $filePostfix;
                        file_put_contents($xmlExportPath . $filename, $xml);
                        @mysql_query($queryVersionNbr, $this->m_db);
                        $xmlFiles[] = $xmlExportPath . $filename;
                        $d = array(
                            'kv_nr' => $data['header']['provider']['patient']['versich_nr'],
                            'nachname' => $data['header']['provider']['patient']['nachname'],
                            'vorname' => $data['header']['provider']['patient']['vorname'],
                            'geburtsdatum' => $data['header']['provider']['patient']['geburtsdatum'],
                            'fall_nr' => $data['header']['provider']['patient']['fall_nr'],
                            'dmp_dokument_id' => $data['header']['dmp_dokument_id'],
                            'doku_datum' => $data['header']['dokumentations_datum'],
                            'unterschrift_datum' => $data['header']['datum_unterschrift'],
                            'status' => $data['body']['xml_status'],
                            'protokol_file' => "{$checkExportPath}{$filename}.Protocol.xml",
                            'dmp_dbid' => $data['header']['dmp_dbid']
                        );
                        switch ($section->GetBlock()) {
                            case 'ed' :
                                $dmp_ed_patdat[] = $d;
                                break;
                            case 'ed_pnp' :
                                $dmp_ed_pnp_patdat[] = $d;
                                break;
                            case 'fd' :
                                $dmp_fd_patdat[] = $d;
                                break;
                        }
                    }
                }
            }
        }
        //HFileSystem::DeleteDirectory($exportPath);
        $this->m_export_record->SetCases($cases);
        $this->m_export_record->Write($this->m_db);


        // Set date-flag into status table, so you can identify exported forms
        $cases = $this->m_export_record->GetCases();
        foreach ($cases as $caseKey => $case) {
            if ($case->IsCaseValid()) {
                $formId = $this->_GetSectionData($case, 'header');
                if (str_starts_with($formId['set_id'], 'ep')) {
                    mysql_query("
                        UPDATE
                            status
                        SET
                            report_param = NOW()
                        WHERE
                            form = 'dmp_brustkrebs_ed_pnp_2013'
                        AND
                            form_id ='{$formId['dmp_dbid']}'
                    ", $this->m_db);

                } elseif (str_starts_with($formId['set_id'], 'f')) {
                    mysql_query("
                        UPDATE
                            status
                        SET
                            report_param = NOW()
                        WHERE
                            form = 'dmp_brustkrebs_fd_2013'
                        AND
                            form_id ='{$formId['dmp_dbid']}'
                    ", $this->m_db);

                } else {
                    mysql_query("
                        UPDATE
                            status
                        SET
                            report_param = NOW()
                        WHERE
                            form = 'dmp_brustkrebs_ed_2013'
                        AND
                            form_id ='{$formId['dmp_dbid']}'
                    ", $this->m_db);
                }

            }
        }


        $this->m_smarty->config_load('../feature/export/dmp_2014/dmp_2014_0.conf', 'export_dmp');
        $config = $this->m_smarty->get_config_vars();

        // Sind exportfï¿½hige Daten vorhanden?
        if (!$anyFilesExportable) {
            $this->m_smarty->assign('exportergebnis', $config['info_keine_daten_exportierbar']);
        }
        else {
            $this->m_smarty->assign('exportergebnis', $config[ 'info_daten_exportiert' ]);

            $lfdNr = $this->m_export_record->GetExportNr();

            $zipExportPath = $exportPath . $parameters['zip_dir'];
            HFileSystem::CreatePath($zipExportPath);

            $appendix = date('Y') == 2013 ? '2013' : null;
            $fileName = $data['header']['bsnr'] . '_' . date( 'YmdHis' ) . '_' . $lfdNr . '_BK' . $appendix;
            $zipfile  = $fileName . '.zip';

            // ZIP-Datei aus den XML-Dateien erzeugen
            // XML-Dateien mï¿½ssen erhalten bleiben!
            $zipPath = $zipExportPath . $zipfile;
            $zip = new PclZip($zipPath);
            $zip->create($xmlFiles, 'Brustkrebs/Dokumentation', $xmlExportPath);

            $zipFileUrl = "index.php?page=dmp_2014&feature=export&action=download&type=zip&file={$zipPath}";
            $paths = HelperDmp::cryptZip($parameters, $zipExportPath, $zipfile, '2015-01-01');
            $xkmFileUrl = "index.php?page=dmp_2014&feature=export&action=download&type=bin&file={$paths['xkmFilePath']}";
            $xkmCryptoProtocolUrl =
                "index.php?page=dmp_2014&feature=export&action=download&type=pdf&file={$paths['xkmCryptoProtocolPath']}";

            $empfaengerIk = $parameters['empfaenger_ik'];
            if ($parameters['empfaenger_aok']) {
                $empfaengerIk = $parameters['empfaenger2_ik'];
            }

            $idxFilename = $fileName . '.idx';
            $idxUrl =
                "index.php?page=dmp_2014&feature=export&action=download&type=xml&file={$zipExportPath}{$idxFilename}";
            $this->m_internal_smarty->assign(
                array(
                    'erstellungsdatum'   => date( 'Y-m-d' ),
                    'empfaenger_ik'      => $empfaengerIk,
                    'bsnr'               => $data['header']['provider']['arzt']['bsnr'],
                    'klinik_ik'          => $data['header']['provider']['org']['iknr'],
                    'xkmfile'            => $paths['xkmFileName'],
                    'datum_von'          => $parameters['von_datum'],
                    'datum_bis'          => $parameters['bis_datum'],
                    'xkm_version'        => '1.20'
                )
            );
            $xml = $this->m_internal_smarty->fetch('dmp_2014_0_begleitdatei.tpl');
            file_put_contents($zipExportPath . $idxFilename, $xml);
            $_SESSION['startdate'] = '';
            $_SESSION['enddate']   = '';
            $this->m_smarty->assign('show_xkm', true);
            $this->m_smarty->assign('zip_url', $zipFileUrl);
            $this->m_smarty->assign('zip_filename', 'Export Datei');
            $this->m_smarty->assign('xkm_url', $xkmFileUrl);
            $this->m_smarty->assign('idx_url', $idxUrl);
            $this->m_smarty->assign('xkm_crypto_protocol_url', $xkmCryptoProtocolUrl );
            $this->m_smarty->assign(
                array(
                    'info_beschriftung'  =>
                        sprintf( $config[ 'info_datentraegerbeschriftung' ],
                            $data['header']['bsnr'], $empfaengerIk, $lfdNr, date('d.m.Y'))
                )
            );
            $this->m_smarty->assign('dmp_ed_patdat', $dmp_ed_patdat);
            $this->m_smarty->assign('dmp_ed_pnp_patdat', $dmp_ed_pnp_patdat);
            $this->m_smarty->assign('dmp_fd_patdat', $dmp_fd_patdat);
            $_SESSION['sess_dmp_begleitzettel'] = array(
                'absender' => $data['header']['bsnr'],
                'empfaenger' => $empfaengerIk,
                'dateien' => array(
                    $paths['xkmFileName'],
                    $idxFilename
                )
            );

            // Begleitzettel nur fï¿½r Histry erstellen
            $begleitzettelFilename = $zipExportPath . 'begleitzettel.pdf';
            $params = array(
                'dmpBegleitzettel' => array(
                    'absender' => $data['header']['bsnr'],
                    'empfaenger' => $empfaengerIk,
                    'dateien' => array(
                        $paths['xkmFileName'],
                        $idxFilename
                    )
                )
            );
            $renderer = alcReportPdf::create($this->m_db, $this->m_smarty, $parameters['user_id'], $params);
            $renderer->addPage();
            $begleitzettel = new reportContentDmp_begleitzettel_2014(
                $renderer, $this->m_db, $this->m_smarty, 'b', 'pdf', $params);
            $begleitzettel->generate($renderer);
            $renderer->output($begleitzettelFilename, 'F');

            // History erstellen
            $historyManager = CHistoryManager::getInstance();
            $historyManager->initialise($this->m_db, $this->m_smarty);
            $history = $historyManager->createHistory();
            $history->setExportLogId($this->m_export_record->GetDbId());
            $history->setExportName($this->m_export_record->GetExportName());
            $history->setOrgId($parameters['org_id']);
            $history->setUserId($parameters['user_id']);
            $history->setDate(date('Ymd', time()));
            $history->addFilter('Meldender Arzt', $this->GetUser($parameters['melde_user_id']));
            $history->addFilter('von', $parameters['von_datum']);
            $history->addFilter('bis', $parameters['bis_datum']);
            $history->addFilter(
                'Export an alternativen EmpfÃ¤nger (AOK)', ($parameters['empfaenger_aok'] == true) ? '1' : '0');
            $history->setFiles(
                array(
                    $zipExportPath . $idxFilename,
                    $zipPath,
                    $paths['xkmFilePath'],
                    $paths['xkmCryptoProtocolPath'],
                    $begleitzettelFilename
                )
            );
            $historyManager->insertHistory($history);
        }
        return $filename;
    }


    /**
     *
     *
     * @access
     * @param $user_id
     * @return string
     */
    protected function GetUser($user_id)
    {
        $query = "
            SELECT
                nachname,
                vorname

            FROM
                user

            WHERE
                user_id={$user_id}
        ";
        $result = sql_query_array($this->m_db, $query);
        if (false !== $result) {
            return $result[0]['nachname'] . ", " . $result[0]['vorname'];
        }
        return "";
    }

    /**
     * @see CExportXmlSerialiser::GetFilename
     */
    public function GetFilename()
    {
        return 'dmp_export_' . date('YmdHis') . '.xml';
    }

    // *****************************************************************************************************************
    // Helper functions

    /**
     *
     *
     * @access protected
     * @param $case
     * @param $blockName
     * @return array
     */
    protected function _GetSectionData($case, $blockName)
    {
        foreach ($case->GetSections() as $section) {
            if ($blockName == $section->GetBlock()) {
                return $section->GetDaten();
            }
        }
        return array();
    }

}

?>
