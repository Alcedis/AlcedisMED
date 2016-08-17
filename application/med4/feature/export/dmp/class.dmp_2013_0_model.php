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

require_once(DIR_LIB.'/libphonenumber/PhoneNumberUtil.php');
require_once('feature/export/base/class.exportdefaultmodel.php');
require_once('feature/export/base/helper.common.php');
require_once('class.dmp_2013_0_serialiser.php');
require_once('class.dmp_2013_0_section.php');
require_once('class.dmp_2013_0_observation.php');
require_once('helper.dmp2013.php');

class Cdmp_2013_0_Model extends CExportDefaultModel
{


    /**
     * @access protected
     * @var array
     */
    protected $m_l_exp_dmp = array();


    /**
     * @access public
     * @return void
     */
    public function __construct()
    {
    }

    // *****************************************************************************************************************
    // Overrides from class CExportDefaultModel

    /**
     * @see CExportDefaultModel::ExtractData
     */
    public function ExtractData($parameters, $wrapper, &$exportRecord)
    {
        $this->ExtractDataExt($parameters, $wrapper, $exportRecord); //, 10, 'fd');
    }


    /**
     *
     *
     * @access public
     * @param        $parameters
     * @param        $wrapper
     * @param        $exportRecord
     * @param        $id
     * @param string $type
     * @return void
     */
    public function ExtractDataExt($parameters, $wrapper, &$exportRecord, $id = -1, $type = '' ) {
        $this->m_l_exp_dmp = $this->_ReadDmpExportCodeTable();
        if ($type != '') {
            $this->m_l_basic = HDatabase::ReadExportCodeTable($this->m_db, $this->GetExportName());
        }
        $functionNames = array(
            '_CreateEdSection',
            '_CreateEdPnpSection',
            '_CreateFdSection'
        );
        $extractData = array();
        // Alternativer Empfänger (alias empfaenger2)?
        $empfaenger2Where = '';
        if (strlen($parameters['empfaenger2_bedingung']) > 0) {
            if (true === $parameters['empfaenger_aok']) {
                $empfaenger2Where = "AND IFNULL(ktst.name, k.name) LIKE '{$parameters['empfaenger2_bedingung']}'";
            } else {
                $empfaenger2Where = "AND IFNULL(ktst.name, k.name) NOT LIKE '{$parameters['empfaenger2_bedingung']}'";
            }
        }
        if (($type == '') || ($type == 'ed')) {
            $where = '';
            if ($id > -1) {
                $where = "ed.dmp_brustkrebs_ed_2013_id='{$id}'";
            }
            else {
                $where =
                    "p.org_id='{$parameters['org_id']}' " .
                    "AND ed.melde_user_id='{$parameters['melde_user_id']}' " .
                    "AND ed.doku_datum BETWEEN '{$parameters['von_datum']}' AND '{$parameters['bis_datum']}' " .
                    "{$empfaenger2Where}";
            }
            // Alle Erstdokumentationen holen
            $query = "
                SELECT
                    ed.dmp_brustkrebs_ed_2013_id           AS dmp_dbid,
                    'ed'                                   AS type,
                    CONCAT('',
                            'e',
                            ed.dmp_brustkrebs_ed_2013_id,
                            '-',
                            DATE_FORMAT(IFNULL(ed.updatetime, ed.createtime), '%Y%m%d%H%i%s')
                    )                                      AS dmp_dokument_id,
                    CONCAT('e',
                            ed.dmp_brustkrebs_ed_2013_id
                    )                                      AS set_id,
                    IFNULL(MAX(dvn.version_nbr), 0) + 1    AS version_nbr,
                    IFNULL(dvn_p.document_id, '')          AS parent_document_id,
                    IFNULL(dvn_p.bsnr, '')                 AS parent_bsnr,
                    '-'                                    AS diagnose_seite,
                    p.vorname                              AS patient_vorname,
                    p.nachname                             AS patient_nachname,
                    p.titel                                AS patient_title,
                    p.strasse                              AS patient_strasse,
                    p.hausnr                               AS patient_hausnr,
                    p.plz                                  AS patient_plz,
                    p.ort                                  AS patient_ort,
                    p.geburtsdatum                         AS patient_geburtsdatum,
                    p.geschlecht                           AS patient_geschlecht,
                    p.kv_nr                                AS patient_versich_nr,
                    p.kv_status                            AS patient_versich_status,
                    p.kv_statusergaenzung                  AS patient_statusergaenzung,
                    p.kv_gueltig_bis                       AS patient_vk_gueltig_bis,
                    p.kv_einlesedatum                      AS patient_kvk_einlesedatum,
                    IFNULL(p.kv_abrechnungsbereich, '00')  AS patient_kv_abrechnungsbereich,
                    IFNULL(ktst.vknr, k.vknr)              AS krankenkasse_vknr,
                    p.kv_iknr                              AS krankenkasse_iknr,

                    IF(ktst.iknr IS NOT NULL,
                        '1',
                        IF(k.iknr IS NOT NULL,
                            k.gkv,
                            '0'
                       )
                    )                                      AS krankenkasse_gkv,
                    u.vorname                              AS arzt_vorname,
                    u.nachname                             AS arzt_nachname,
                    u.titel                                AS arzt_title,
                    u.fachabteilung                        AS arzt_fachabteilung,
                    lb.bez                                 AS fachabteilung,
                    u.telefon                              AS arzt_telefon,
                    u.telefax                              AS arzt_telefax,
                    u.email                                AS arzt_email,
                    u.lanr                                 AS arzt_lanr,
                    u.bsnr                                 AS arzt_bsnr,
                    o.name                                 AS org_name,
                    o.namenszusatz                         AS org_namenszusatz,
                    o.strasse                              AS org_strasse,
                    o.hausnr                               AS org_hausnr,
                    o.plz                                  AS org_plz,
                    o.ort                                  AS org_ort,
                    o.ik_nr                                AS org_iknr,
                    o.website                              AS org_website,
                    ed.*

                FROM
                    patient p
                    INNER JOIN dmp_brustkrebs_ed_2013 ed    ON p.patient_id=ed.patient_id
                    INNER JOIN erkrankung e                 ON ed.erkrankung_id=e.erkrankung_id
                                                               AND e.erkrankung='b'
                                                               AND p.patient_id=e.patient_id
                    LEFT JOIN user u                        ON ed.melde_user_id=u.user_id
                    LEFT JOIN l_basic lb                    ON u.fachabteilung=lb.code
                                                               AND klasse='fachabteilung'
                    LEFT JOIN org o                         ON p.org_id=o.org_id
                    LEFT JOIN vorlage_krankenversicherung k ON p.kv_iknr=k.iknr
                    LEFT JOIN l_ktst ktst                   ON p.kv_iknr=ktst.iknr
                    LEFT JOIN dmp_version_nbr_2013 dvn
                        ON ed.dmp_brustkrebs_ed_2013_id=dvn.dmp_brustkrebs_ed_2013_id
                    LEFT JOIN dmp_version_nbr_2013 dvn_p
                        ON ed.dmp_brustkrebs_ed_2013_id=dvn_p.dmp_brustkrebs_ed_2013_id
                           AND dvn_p.version_nbr=1
                WHERE
                    {$where}

                GROUP BY
                    ed.dmp_brustkrebs_ed_2013_id

                ORDER BY
                    p.nachname,
                    p.vorname,
                    ed.doku_datum
            ";
            $extractData[0] = sql_query_array($this->m_db, $query);
        }
        else {
            $extractData[0] = array();
        }
        if (($type == '') || ($type == 'ed_pnp')) {
            // Alle Erstdokumentationen pnp holen
            $where = '';
            if ($id > -1) {
                $where = "ed_pnp.dmp_brustkrebs_ed_pnp_2013_id='{$id}'";
            }
            else {
                $where =
                    "p.org_id='{$parameters['org_id']}' " .
                    "AND ed_pnp.melde_user_id='{$parameters['melde_user_id']}' " .
                    "AND ed_pnp.doku_datum BETWEEN '{$parameters['von_datum']}' AND '{$parameters['bis_datum']}' " .
                    "{$empfaenger2Where}";
            }
            $query = "
            SELECT
                ed_pnp.dmp_brustkrebs_ed_pnp_2013_id       AS dmp_dbid,
                'ed_pnp'                                   AS type,
                CONCAT( '',
                        'ep',
                        ed_pnp.dmp_brustkrebs_ed_pnp_2013_id,
                        '-',
                        DATE_FORMAT(IFNULL(ed_pnp.updatetime, ed_pnp.createtime), '%Y%m%d%H%i%s')
                )                                          AS dmp_dokument_id,
                CONCAT( 'ep',
                        ed_pnp.dmp_brustkrebs_ed_pnp_2013_id
                )                                          AS set_id,
                IFNULL(MAX(dvn.version_nbr), 0) + 1        AS version_nbr,
                IFNULL(dvn_p.document_id, '')              AS parent_document_id,
                IFNULL(dvn_p.bsnr, '')                     AS parent_bsnr,
                '-'                                        AS diagnose_seite,
                p.vorname                                  AS patient_vorname,
                p.nachname                                 AS patient_nachname,
                p.titel                                    AS patient_title,
                p.strasse                                  AS patient_strasse,
                p.hausnr                                   AS patient_hausnr,
                p.plz                                      AS patient_plz,
                p.ort                                      AS patient_ort,
                p.geburtsdatum                             AS patient_geburtsdatum,
                p.geschlecht                               AS patient_geschlecht,
                p.kv_nr                                    AS patient_versich_nr,
                p.kv_status                                AS patient_versich_status,
                p.kv_statusergaenzung                      AS patient_statusergaenzung,
                p.kv_gueltig_bis                           AS patient_vk_gueltig_bis,
                p.kv_einlesedatum                          AS patient_kvk_einlesedatum,
                IFNULL(p.kv_abrechnungsbereich, '00')      AS patient_kv_abrechnungsbereich,
                IFNULL(ktst.vknr, k.vknr)                  AS krankenkasse_vknr,
                p.kv_iknr                                  AS krankenkasse_iknr,
                IF( ktst.iknr IS NOT NULL,
                    '1',
                    IF(k.iknr IS NOT NULL,
                        k.gkv,
                        '0'
                    )
                )                                          AS krankenkasse_gkv,
                u.vorname                                  AS arzt_vorname,
                u.nachname                                 AS arzt_nachname,
                u.titel                                    AS arzt_title,
                u.fachabteilung                            AS arzt_fachabteilung,
                lb.bez                                     AS fachabteilung,
                u.telefon                                  AS arzt_telefon,
                u.telefax                                  AS arzt_telefax,
                u.email                                    AS arzt_email,
                u.lanr                                     AS arzt_lanr,
                u.bsnr                                     AS arzt_bsnr,
                o.name                                     AS org_name,
                o.namenszusatz                             AS org_namenszusatz,
                o.strasse                                  AS org_strasse,
                o.hausnr                                   AS org_hausnr,
                o.plz                                      AS org_plz,
                o.ort                                      AS org_ort,
                o.ik_nr                                    AS org_iknr,
                o.website                                  AS org_website,
                ed.fall_nr                                 AS fall_nr,
                ed_pnp.*

            FROM
                patient p
                INNER JOIN dmp_brustkrebs_ed_pnp_2013 ed_pnp ON p.patient_id=ed_pnp.patient_id
                INNER JOIN erkrankung e                      ON ed_pnp.erkrankung_id=e.erkrankung_id
                                                                AND e.erkrankung='b'
                                                                AND p.patient_id=e.patient_id
                LEFT JOIN dmp_brustkrebs_ed_2013 ed
                    ON ed_pnp.dmp_brustkrebs_ed_2013_id=ed.dmp_brustkrebs_ed_2013_id
                LEFT JOIN user u                             ON ed_pnp.melde_user_id=u.user_id
                LEFT JOIN l_basic lb                         ON u.fachabteilung=lb.code
                                                               AND klasse='fachabteilung'
                LEFT JOIN org o                              ON p.org_id=o.org_id
                LEFT JOIN vorlage_krankenversicherung k      ON p.kv_iknr=k.iknr
                LEFT JOIN l_ktst ktst                        ON p.kv_iknr=ktst.iknr
                LEFT JOIN dmp_version_nbr_2013 dvn
                    ON ed_pnp.dmp_brustkrebs_ed_pnp_2013_id=dvn.dmp_brustkrebs_ed_pnp_2013_id
                LEFT JOIN dmp_version_nbr_2013 dvn_p
                    ON ed_pnp.dmp_brustkrebs_ed_pnp_2013_id=dvn_p.dmp_brustkrebs_ed_pnp_2013_id
                       AND dvn_p.version_nbr=1

            WHERE
                {$where}

            GROUP BY
                ed_pnp.dmp_brustkrebs_ed_pnp_2013_id

            ORDER BY
                p.nachname,
                p.vorname,
                ed_pnp.doku_datum
            ";
            $extractData[1] = sql_query_array($this->m_db, $query);
        }
        else {
            $extractData[1] = array();
        }
        if (($type == '') || ($type == 'fd')) {
            // alle Folgedokumentationen holen
            $where = '';
            if ($id > -1) {
                $where = "fd.dmp_brustkrebs_fd_2013_id='{$id}'";
            }
            else {
                $where =
                    "p.org_id='{$parameters['org_id']}' " .
                    "AND fd.melde_user_id='{$parameters['melde_user_id']}' " .
                    "AND fd.doku_datum BETWEEN '{$parameters['von_datum']}' AND '{$parameters['bis_datum']}' " .
                    "{$empfaenger2Where}";
            }
            $query = "
                SELECT
                    fd.dmp_brustkrebs_fd_2013_id           AS dmp_dbid,
                    'fd'                                   AS type,
                    CONCAT( '',
                            'f',
                            fd.dmp_brustkrebs_fd_2013_id,
                            '-',
                            DATE_FORMAT(IFNULL(fd.updatetime, fd.createtime), '%Y%m%d%H%i%s')
                    )                                      AS dmp_dokument_id,
                    CONCAT( 'f',
                            fd.dmp_brustkrebs_fd_2013_id
                    )                                      AS set_id,
                    IFNULL(MAX(dvn.version_nbr), 0) + 1    AS version_nbr,
                    IFNULL(dvn_p.document_id, '')          AS parent_document_id,
                    IFNULL(dvn_p.bsnr, '')                 AS parent_bsnr,
                    '-'                                    AS diagnose_seite,
                    p.vorname                              AS patient_vorname,
                    p.nachname                             AS patient_nachname,
                    p.titel                                AS patient_title,
                    p.strasse                              AS patient_strasse,
                    p.hausnr                               AS patient_hausnr,
                    p.plz                                  AS patient_plz,
                    p.ort                                  AS patient_ort,
                    p.geburtsdatum                         AS patient_geburtsdatum,
                    p.geschlecht                           AS patient_geschlecht,
                    p.kv_nr                                AS patient_versich_nr,
                    p.kv_status                            AS patient_versich_status,
                    p.kv_statusergaenzung                  AS patient_statusergaenzung,
                    p.kv_gueltig_bis                       AS patient_vk_gueltig_bis,
                    p.kv_einlesedatum                      AS patient_kvk_einlesedatum,
                    IFNULL(p.kv_abrechnungsbereich, '00')  AS patient_kv_abrechnungsbereich,
                    IFNULL(ktst.vknr, k.vknr)              AS krankenkasse_vknr,
                    p.kv_iknr                              AS krankenkasse_iknr,
                    IF( ktst.iknr IS NOT NULL,
                        '1',
                        IF( k.iknr IS NOT NULL,
                            k.gkv,
                            '0'
                        )
                    )                                      AS krankenkasse_gkv,
                    u.vorname                              AS arzt_vorname,
                    u.nachname                             AS arzt_nachname,
                    u.titel                                AS arzt_title,
                    u.fachabteilung                        AS arzt_fachabteilung,
                    lb.bez                                 AS fachabteilung,
                    u.telefon                              AS arzt_telefon,
                    u.telefax                              AS arzt_telefax,
                    u.email                                AS arzt_email,
                    u.lanr                                 AS arzt_lanr,
                    u.bsnr                                 AS arzt_bsnr,
                    o.name                                 AS org_name,
                    o.namenszusatz                         AS org_namenszusatz,
                    o.strasse                              AS org_strasse,
                    o.hausnr                               AS org_hausnr,
                    o.plz                                  AS org_plz,
                    o.ort                                  AS org_ort,
                    o.ik_nr                                AS org_iknr,
                    o.website                              AS org_website,
                    ed.fall_nr                             AS fall_nr,
                    fd.*

                FROM
                    patient p
                    INNER JOIN dmp_brustkrebs_fd_2013 fd    ON p.patient_id=fd.patient_id
                    INNER JOIN erkrankung e                 ON fd.erkrankung_id=e.erkrankung_id
                                                               AND e.erkrankung='b'
                                                               AND p.patient_id=e.patient_id
                    LEFT JOIN dmp_brustkrebs_ed_2013 ed
                        ON fd.dmp_brustkrebs_ed_2013_id=ed.dmp_brustkrebs_ed_2013_id
                    LEFT JOIN user u                        ON fd.melde_user_id=u.user_id
                    LEFT JOIN l_basic lb                    ON u.fachabteilung=lb.code
                                                                AND klasse='fachabteilung'
                    LEFT JOIN org o                         ON p.org_id=o.org_id
                    LEFT JOIN vorlage_krankenversicherung k ON p.kv_iknr=k.iknr
                    LEFT JOIN l_ktst ktst                   ON p.kv_iknr=ktst.iknr
                    LEFT JOIN dmp_version_nbr_2013 dvn
                        ON fd.dmp_brustkrebs_fd_2013_id=dvn.dmp_brustkrebs_fd_2013_id
                    LEFT JOIN dmp_version_nbr_2013 dvn_p
                        ON fd.dmp_brustkrebs_fd_2013_id=dvn_p.dmp_brustkrebs_fd_2013_id
                           AND dvn_p.version_nbr=1

                WHERE
                    {$where}

                GROUP BY
                    fd.dmp_brustkrebs_fd_2013_id

                ORDER BY
                    p.nachname,
                    p.vorname,
                    fd.doku_datum
            ";
            $extractData[2] = sql_query_array($this->m_db, $query);
        }
        else {
            $extractData[2] = array();
        }

        // Cases erstellen
        for ($i = 0; $i < 3; $i++) {
            if (false !== $extractData[$i]) {
                foreach ($extractData[$i] as $row) {
                    // Create main case
                    $case = $this->CreateCase($exportRecord->GetDbid(), $parameters, $row);
                    // Header
                    $section = $this->_CreateHeaderSection($parameters, $row, $sectionUid);
                    $header = $this->CreateBlock($case->GetDbid(), $parameters, 'header', $sectionUid, $section);
                    $case->AddSection($header);
                    // Block
                    $fn = $functionNames[$i];
                    $section = $this->$fn($parameters, $row, $sectionUid);
                    $block = $this->CreateBlock($case->GetDbid(), $parameters, $row['type'], $sectionUid, $section);
                    $case->AddSection($block);
                    $exportRecord->AddCase($case);
                }
            }
        }
    }


    /**
     * @see CExportDefaultModel::PreparingData
     */
    public function PreparingData($parameters, &$exportRecord)
    {
    }


    /**
     * @see CExportDefaultModel::HandleDiff
     */
    public function HandleDiff($parameters, $case, &$section, $oldSection)
    {
        $section->SetMeldungskennzeichen("N");
        $section->SetDataChanged(1);
    }


    /**
     * @see CExportDefaultModel::CheckData
     */
    public function CheckData($parameters, &$exportRecord)
    {
        // Hier jeden Abschnitt gegen XSD Prüfen und Fehler in DB schreiben...
        $serialiser = new Cdmp_2013_0_Serialiser;
        $serialiser->Create(
            $this->m_absolute_path,
            $this->GetExportName(),
            $this->m_smarty,
            $this->m_db,
            $this->m_error_function
        );
        $serialiser->SetData($exportRecord);
        return $serialiser->Validate($this->m_parameters);
    }


    /**
     * @see CExportDefaultModel::WriteData
     */
    public function WriteData()
    {
        $this->m_export_record->SetFinished(true);
        // Hier gesammtes XML schreiben und nicht mehr gegen XSD prüfen..
        $serialiser = new Cdmp_2013_0_Serialiser;
        $serialiser->Create(
            $this->m_absolute_path,
            $this->GetExportName(),
            $this->m_smarty,
            $this->m_db,
            $this->m_error_function
        );
        $serialiser->SetData($this->m_export_record);
        $this->m_export_filename = $serialiser->Write($this->m_parameters);
        $this->m_export_record->Write($this->m_db);
    }

    // *****************************************************************************************************************
    // Helper functions

    /**
     *
     *
     * @access protected
     * @param $extractData
     * @return string
     */
    protected function _GetUidFromData($extractData) {
        return $extractData['dmp_dokument_id'] . "_" . $extractData['patient_id'] . "_" .$extractData['erkrankung_id'];
    }


    /**
     *
     *
     * @access protected
     * @param $datum
     * @return string
     */
    protected function _CheckDatum($datum) {
        $minDatum = date('1900-01-01');
        $maxDatum = date('2050-12-31');
        if (date($datum) < $minDatum) {
            return '1900-01-01';
        }
        elseif (date($datum) > $maxDatum) {
            return '2050-12-31';
        }
        return date('Y-m-d', strtotime($datum));
    }


    /**
     *
     *
     * @access protected
     * @param $parameters
     * @param $extractData
     * @param $sectionUid
     * @return array
     */
    protected function _CreateHeaderSection($parameters, $extractData, &$sectionUid)
    {
        $bsnr = '';
        if (strlen($extractData['arzt_bsnr']) > 0) {
            $bsnr = $extractData['arzt_bsnr'];
        }
        else {
            $bsnr = $extractData['org_iknr'];
        }
        $header = array();
        // Eindeutige ID im MED4-System
        $header['dmp_dbid'] = $extractData['dmp_dbid'];
        $header['dmp_dokument_id'] = $extractData['dmp_dokument_id'];
        $header['set_id'] = $extractData['set_id'];
        // Betriebsstättennummer oder Krankenhaus-IK
        $header['bsnr'] = $bsnr;

        $header['parent_bsnr'] = $extractData['parent_bsnr'];
        $header['parent_dokument_id'] = $extractData['parent_document_id'];

        // Dokumentennummer, wird für neu exportieren immer um eins erhöht
        $header['version_nbr'] = $extractData['version_nbr'];
        $header['xsd_software_version'] = $parameters['xsd_software_version'];
        $header['xpm_software_version'] = $parameters['xpm_software_version'];
        $header['kbv_pruefnummer'] = $parameters['kbv_pruefnummer'];
        $header['document'] = array();
        $header['document']['version'] = ''; // Wird später gesetzt!
        $header['document']['description'] = ''; // Wird später gesetzt!
        $header['datum_unterschrift'] = $this->_CheckDatum($extractData['unterschrift_datum']);
        $header['dokumentations_datum'] = $this->_CheckDatum($extractData['doku_datum']);
        // Provider: Arzt
        $header['provider'] = array();
        $header['provider']['arzt'] = array();
        // Arztnummer
        $header['provider']['arzt']['lanr'] = $extractData['arzt_lanr'];
        // Betriebsstättennummer
        $header['provider']['arzt']['rolle'] = 'BEHA';
        $header['provider']['arzt']['rollenbeschreibung'] = 'Behandelnde Arzt';
        if (isset($extractData['arztwechsel']) && $extractData['arztwechsel'] == '1') {
            $header['provider']['arzt']['rolle'] = 'ARZTW';
            $header['provider']['arzt']['rollenbeschreibung'] = 'Arztwechsel';
        }
        $header['provider']['arzt']['bsnr'] = $extractData['arzt_bsnr'];
        $header['provider']['arzt']['vorname'] = HCommon::TrimString($extractData['arzt_vorname'], 60, true);
        $header['provider']['arzt']['nachname'] = HCommon::TrimString($extractData['arzt_nachname'], 60, true);
        $split = $this->_SplitTitle($extractData['arzt_title']);
        $header['provider']['arzt']['titel_ac'] = HCommon::TrimString($split['ac'], 15, true);
        $header['provider']['arzt']['titel_nb'] = HCommon::TrimString($split['nb'], 15, true);
        $header['provider']['arzt']['telefon'] = HCommon::TrimString(
            $this->_CheckUrl('tel', $this->_CheckFonNumber($extractData['arzt_telefon'])),
            150,
            true
        );
        $header['provider']['arzt']['telefax'] = HCommon::TrimString(
            $this->_CheckUrl('fax', $this->_CheckFonNumber($extractData['arzt_telefax'])),
            150,
            true
        );
        $header['provider']['arzt']['email'] = HCommon::TrimString($this->_CheckUrl(
            'mailto',
            $extractData['arzt_email']),
            150,
            true
        );
        $header['provider']['org'] = array();
        $header['provider']['org']['name'] =  HCommon::TrimString($extractData['org_name'], 60, true);
        $header['provider']['org']['fachabteilung'] =  HCommon::TrimString($extractData['fachabteilung'], 60, true);
        $header['provider']['org']['strasse'] =  HCommon::TrimString($extractData['org_strasse'], 60, true);
        $header['provider']['org']['hausnr'] =  HCommon::TrimString($extractData['org_hausnr'], 15, true);
        $header['provider']['org']['plz'] =  HCommon::TrimString($extractData['org_plz'], 7, true);
        $header['provider']['org']['ort'] =  HCommon::TrimString($extractData['org_ort'], 60, true);
        // Krankenhaus-IK
        $header['provider']['org']['iknr'] = $extractData['org_iknr'];
        $header['provider']['org']['website'] = HCommon::TrimString(
            $this->_CheckUrl('http',
            $extractData['org_website']),
            150,
            true
        );
        $header['provider']['patient'] = array();
        $header['provider']['patient']['fall_nr'] = $extractData['fall_nr'];

        $header['provider']['patient']['vorname'] = HCommon::TrimString($extractData['patient_vorname'], 28, true);
        $header['provider']['patient']['nachname'] = HCommon::TrimString($extractData['patient_nachname'], 28, true);
        $split = $this->_SplitTitle($extractData['patient_title']);
        $header['provider']['patient']['titel_ac'] = HCommon::TrimString($split['ac'], 15, true);
        $header['provider']['patient']['titel_nb'] = HCommon::TrimString($split['nb'], 15, true);
        $header['provider']['patient']['strasse'] =  HCommon::TrimString($extractData['patient_strasse'], 60, true);
        $header['provider']['patient']['hausnr'] =  HCommon::TrimString($extractData['patient_hausnr'], 15, true);
        $header['provider']['patient']['plz'] =  HCommon::TrimString($extractData['patient_plz'], 7, true);
        $header['provider']['patient']['ort'] =  HCommon::TrimString($extractData['patient_ort'], 60, true);
        $header['provider']['patient']['geburtsdatum'] = $this->_CheckDatum($extractData['patient_geburtsdatum']);
        $header['provider']['patient']['geschlecht'] = $this->GetExportCode(
            'geschlecht',
            $extractData['patient_geschlecht'],
            'UN'
        );
        $header['provider']['patient']['versich_nr'] = $extractData['patient_versich_nr'];

        $versichertenStatusKvk =
            $this->_GetDmpExportCode('versichertenstatus', $extractData['patient_versich_status']) . "000";

        $header['provider']['patient']['versich_status'] = $versichertenStatusKvk;

        $header['provider']['patient']['statusergaenzung'] = $extractData['patient_statusergaenzung'];
        $yearMonth = HCommon::GetYearAndMonth($extractData['patient_vk_gueltig_bis']);
        if (false === $yearMonth) {
            $yearMonth = '';
        }
        $header['provider']['patient']['vk_gueltig_bis'] = $yearMonth;
        $header['provider']['patient']['kvk_einlesedatum'] = $extractData['patient_kvk_einlesedatum'];

        $iknr       = $extractData['krankenkasse_iknr'];
        $hcSection  = $extractData['patient_kv_abrechnungsbereich'];

        $validatedHealthCare = $this->_validateHealthCare($iknr, $hcSection);

        $header['provider']['krankenkasse'] = array(
            'iknr'                  => $iknr,
            'name'                  => $validatedHealthCare['name'],
            'vknr'                  => $extractData['krankenkasse_vknr'],
            'abrechnungsbereich'    => $validatedHealthCare['abrechnungsbereich'],
            'gkv'                   => $extractData['krankenkasse_gkv'],
            'kv_bereich'            => '' // TODO
        );

        $header['software'] = array();
        $header['software']['name'] = $parameters['sw_name'];
        $header['software']['version'] = $parameters['sw_version'];
        $header['software']['org_name'] = $parameters['sw_hersteller'];
        $header['software']['produkt_verantwortlicher_vorname'] = $parameters['produkt_verantwortlicher_vorname'];
        $header['software']['produkt_verantwortlicher_nachname'] = $parameters['produkt_verantwortlicher_nachname'];
        $header['software']['org_strasse'] = $parameters['sw_hersteller_strasse'];
        $header['software']['org_hausnr'] = $parameters['sw_hersteller_hausnr'];
        $header['software']['org_plz'] = $parameters['sw_hersteller_plz'];
        $header['software']['org_ort'] = $parameters['sw_hersteller_ort'];
        $header['software']['org_tel'] =
            $this->_CheckUrl('tel', $this->_CheckFonNumber($parameters['sw_hersteller_telefon']));
        $header['software']['org_fax'] =
            $this->_CheckUrl('fax', $this->_CheckFonNumber($parameters['sw_hersteller_telefax']));
        $header['software']['org_email_support'] =
            $this->_CheckUrl('mailto', $parameters['sw_hersteller_email_support']);
        $header['software']['org_web'] =
            $this->_CheckUrl('http', $parameters['sw_hersteller_website']);
        $sectionUid = 'HEADER_' . $this->_GetUidFromData($extractData);
        return $header;
    }

    protected function _FillErstmeldungsSections($extractData, &$sections)
    {
        // *************************************************************************************************************
        // Section: Einschreibung
        if (isset($extractData['mani_primaer']) ||
            isset($extractData['mani_kontra']) ||
            isset($extractData['mani_rezidiv']) ||
            isset($extractData['mani_metast'])) {
            $section = new Cdmp_2013_0_Section;
            $section->SetCaption('Einschreibung');
            //          Erstmanifestation des Primärtumors (Datum des histologischen Nachweises)
            if (strlen($extractData['mani_primaer']) > 0) {
                $observation = new Cdmp_2013_0_Observation;
                $observation->SetParameter(
                    'Erstmanifestation des Primärtumors (Datum des histologischen Nachweises)');
                $observation->AddZeitpunktDttm(HelperDmp::CheckDate($extractData['mani_primaer']));
                $section->AddObservation($observation);
            }
            //          Manifestation eines kontralateralen Brustkrebses (Datum des histologischen Nachweises)
            if (strlen($extractData['mani_kontra']) > 0) {
                $observation = new Cdmp_2013_0_Observation;
                $observation->SetParameter(
                    'Manifestation eines kontralateralen Brustkrebses (Datum des histologischen Nachweises)');
                $observation->AddZeitpunktDttm(HelperDmp::CheckDate($extractData['mani_kontra']));
                $section->AddObservation($observation);
            }
            //          Lokoregionäres Rezidiv (Datum des histologischen Nachweises)
            if (strlen($extractData['mani_rezidiv']) > 0) {
                $observation = new Cdmp_2013_0_Observation;
                $observation->SetParameter('Lokoregionäres Rezidiv (Datum des histologischen Nachweises)');
                $observation->AddZeitpunktDttm(HelperDmp::CheckDate($extractData['mani_rezidiv']));
                $section->AddObservation($observation);
            }
            //          Fernmetastasen erstmals gesichert
            if (strlen($extractData['mani_metast']) > 0) {
                $observation = new Cdmp_2013_0_Observation;
                $observation->SetParameter('Fernmetastasen erstmals gesichert');
                $observation->AddZeitpunktDttm(HelperDmp::CheckDate($extractData['mani_metast']));
                $section->AddObservation($observation);
            }
            if ($section->HasObservations()) {
                $sections[] = $section->ToArray();
            }
        }

        // *************************************************************************************************************
        // Section: Anamnese und Behandlungsstatus Primärtumors/kontralateralen Brustkrebses
        $section = new Cdmp_2013_0_Section;
        $section->SetCaption('Anamnese und Behandlungsstatus des Primärtumors/kontralateralen Brustkrebses');
        //          Betroffene Brust
        if ((strlen($extractData['anam_brust_links']) > 0) ||
            (strlen($extractData['anam_brust_rechts']) > 0) ||
            (strlen($extractData['anam_brust_beidseits']) > 0)) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Betroffene Brust');
            if ($extractData['anam_brust_beidseits'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('seite', 'b'));
            }
            if ($extractData['anam_brust_links'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('seite', 'l'));
            }
            if ($extractData['anam_brust_rechts'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('seite', 'r'));
            }
            $section->AddObservation($observation);
        }
        //          Aktueller Behandlungsstatus bezogen auf das operative Vorgehen
        if (strlen($extractData['aktueller_status']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Aktueller Behandlungsstatus bezogen auf das operative Vorgehen');
            $observation->AddErgebnisText(
                $this->_GetDmpExportCode('behandlungsstatus', $extractData['aktueller_status'])
            );
            $section->AddObservation($observation);
        }
        //          Art der erfolgten operativen Therapie
        if ((strlen($extractData['anam_op_bet']) > 0) ||
            (strlen($extractData['anam_op_mast']) > 0) ||
            (strlen($extractData['anam_op_sln']) > 0) ||
            (strlen($extractData['anam_op_axilla']) > 0) ||
            (strlen($extractData['anam_op_anderes']) > 0) ||
            (strlen($extractData['anam_op_keine']) > 0)) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Art der erfolgten operativen Therapie');
            if ($extractData['anam_op_bet'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('art_operative_therapie', 'bet'));
            }
            if ($extractData['anam_op_mast'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('art_operative_therapie', 'mastektomie'));
            }
            if ($extractData['anam_op_sln'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('art_operative_therapie', 'sen_lymph_biop'));
            }
            if ($extractData['anam_op_axilla'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('art_operative_therapie', 'axil_lymph_dek'));
            }
            if ($extractData['anam_op_anderes'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('art_operative_therapie', 'anderes_vor'));
            }
            if ($extractData['anam_op_keine'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('art_operative_therapie', 'keine_op'));
            }
            $section->AddObservation($observation);
        }
        if ($section->HasObservations()) {
            $sections[] = $section->ToArray();
        }

        // *************************************************************************************************************
        // Section: Aktueller Befundstatus Primärtumors/kontralateralen Brustkrebses
        $section = new Cdmp_2013_0_Section;
        $section->SetCaption('Aktueller Befundstatus des Primärtumors/kontralateralen Brustkrebses');
        //          Präoperative/neoadjuvante Therapie
        if (strlen($extractData['bef_neoadjuvant']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Präoperative/neoadjuvante Therapie');
            if ($extractData['bef_neoadjuvant'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('bef_neoadjuvant', 'j'));
            }
            elseif ($extractData['bef_neoadjuvant'] == '0') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('bef_neoadjuvant', 'n'));
            }
            $section->AddObservation($observation);
        }
        //          pT
        if ((strlen($extractData['bef_pt_tis']) > 0) ||
            (strlen($extractData['bef_pt_0']) > 0) ||
            (strlen($extractData['bef_pt_1']) > 0) ||
            (strlen($extractData['bef_pt_2']) > 0) ||
            (strlen($extractData['bef_pt_3']) > 0) ||
            (strlen($extractData['bef_pt_4']) > 0) ||
            (strlen($extractData['bef_pt_x']) > 0) ||
            (strlen($extractData['bef_pt_keine']) > 0)) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('pT');
            if ($extractData['bef_pt_tis'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('pt', 'tis'));
            }
            if ($extractData['bef_pt_0'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('pt', '0'));
            }
            if ($extractData['bef_pt_1'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('pt', '1'));
            }
            if ($extractData['bef_pt_2'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('pt', '2'));
            }
            if ($extractData['bef_pt_3'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('pt', '3'));
            }
            if ($extractData['bef_pt_4'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('pt', '4'));
            }
            if ($extractData['bef_pt_x'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('pt', 'x'));
            }
            if ($extractData['bef_pt_keine'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('pt', 'keine_op'));
            }
            $section->AddObservation($observation);
        }
        //          pN
        if ((strlen($extractData['bef_pn_0']) > 0) ||
            (strlen($extractData['bef_pn_1']) > 0) ||
            (strlen($extractData['bef_pn_2']) > 0) ||
            (strlen($extractData['bef_pn_3']) > 0) ||
            (strlen($extractData['bef_pn_x']) > 0) ||
            (strlen($extractData['bef_pn_keine']) > 0)) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('pN');
            if ($extractData['bef_pn_0'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('pn', '0'));
            }
            if ($extractData['bef_pn_1'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('pn', '1'));
            }
            if ($extractData['bef_pn_2'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('pn', '2'));
            }
            if ($extractData['bef_pn_3'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('pn', '3'));
            }
            if ($extractData['bef_pn_x'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('pn', 'x'));
            }
            if ($extractData['bef_pn_keine'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('pn', 'keine_op'));
            }
            $section->AddObservation($observation);
        }
        //          M
        if (strlen($extractData['bef_m']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('M');
            $observation->AddErgebnisText($extractData['bef_m']);
            $section->AddObservation($observation);
        }
        //          Grading
        if (strlen($extractData['bef_g']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Grading');
            $observation->AddErgebnisText($this->_GetDmpExportCode('grading', $extractData['bef_g']));
            $section->AddObservation($observation);
        }
        //          Resektionsstatus
        if ((strlen($extractData['bef_r_0']) > 0) ||
            (strlen($extractData['bef_r_1']) > 0) ||
            (strlen($extractData['bef_r_2']) > 0) ||
            (strlen($extractData['bef_r_unbekannt']) > 0) ||
            (strlen($extractData['bef_r_keine']) > 0)) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Resektionsstatus');
            if ($extractData['bef_r_0'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('r', '0'));
            }
            if ($extractData['bef_r_1'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('r', '1'));
            }
            if ($extractData['bef_r_2'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('r', '2'));
            }
            if ($extractData['bef_r_unbekannt'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('r', 'unbekannt'));
            }
            if ($extractData['bef_r_keine'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('r', 'keine_op'));
            }
            $section->AddObservation($observation);
        }
        //          Immunhistochemischer Hormonrezeptorstatus (Östrogen und/oder Progesteron)
        if (strlen($extractData['bef_rezeptorstatus']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Immunhistochemischer Hormonrezeptorstatus (Östrogen und/oder Progesteron)');
            $observation->AddErgebnisText(
                $this->_GetDmpExportCode('rezeptorstatus', $extractData['bef_rezeptorstatus'])
            );
            $section->AddObservation($observation);
        }
        //          HER2/neu-Status
        if (strlen($extractData['bef_her2']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('HER2/neu-Status');
            $observation->AddErgebnisText($this->_GetDmpExportCode('her2', $extractData['bef_her2']));
            $section->AddObservation($observation);
        }
        if ($section->HasObservations()) {
            $sections[] = $section->ToArray();
        }

        // *************************************************************************************************************
        // Section: Behandlung Primärtumors/kontralateralen Brustkrebses
        $section = new Cdmp_2013_0_Section;
        $section->SetCaption('Behandlung des Primärtumors/kontralateralen Brustkrebses');
        //          Strahlentherapie
        if (strlen($extractData['beh_strahlen']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Strahlentherapie');
            $observation->AddErgebnisText($this->_GetDmpExportCode('th_behandlung', $extractData['beh_strahlen']));
            $section->AddObservation($observation);
        }
        //          Chemotherapie
        if (strlen($extractData['beh_chemo']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Chemotherapie');
            $observation->AddErgebnisText($this->_GetDmpExportCode('th_behandlung', $extractData['beh_chemo']));
            $section->AddObservation($observation);
        }
        //          Endokrine Therapie
        if (strlen($extractData['beh_endo']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Endokrine Therapie');
            $observation->AddErgebnisText($this->_GetDmpExportCode('th_behandlung', $extractData['beh_endo']));
            $section->AddObservation($observation);
        }
        //          Antikörpertherapie mit Trastuzumab
        if (strlen($extractData['beh_ah']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Antikörpertherapie mit Trastuzumab');
            $observation->AddErgebnisText($this->_GetDmpExportCode('th_behandlung', $extractData['beh_ah']));
            $section->AddObservation($observation);
        }
        if ($section->HasObservations()) {
            $sections[] = $section->ToArray();
        }

        // *************************************************************************************************************
        // Section: Befunde und Therapie lokoregionären Rezidivs
        $section = new Cdmp_2013_0_Section;
        $section->SetCaption('Befunde und Therapie eines lokoregionären Rezidivs');
        //          Andauernde oder abgeschlossene Therapie
        if ((strlen($extractData['rez_th_praeop']) > 0) ||
            (strlen($extractData['rez_th_exzision']) > 0) ||
            (strlen($extractData['rez_th_mastektomie']) > 0) ||
            (strlen($extractData['rez_th_strahlen']) > 0) ||
            (strlen($extractData['rez_th_chemo']) > 0) ||
            (strlen($extractData['rez_th_endo']) > 0) ||
            (strlen($extractData['rez_th_andere']) > 0) ||
            (strlen($extractData['rez_th_keine']) > 0)) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Andauernde oder abgeschlossene Therapie');
            if ($extractData['rez_th_praeop'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('th_status', 'praeop'));
            }
            if ($extractData['rez_th_exzision'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('th_status', 'exzision'));
            }
            if ($extractData['rez_th_mastektomie'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('th_status', 'mastektomie'));
            }
            if ($extractData['rez_th_strahlen'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('th_status', 'strahlen'));
            }
            if ($extractData['rez_th_chemo'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('th_status', 'chemo'));
            }
            if ($extractData['rez_th_endo'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('th_status', 'endo'));
            }
            if ($extractData['rez_th_andere'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('th_status', 'andere'));
            }
            if ($extractData['rez_th_keine'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('th_status', 'keine'));
            }
            $section->AddObservation($observation);
        }
        if ($section->HasObservations()) {
            $sections[] = $section->ToArray();
        }

        // *************************************************************************************************************
        // Section: Befunde und Therapie von Fernmetastasen
        $section = new Cdmp_2013_0_Section;
        $section->SetCaption('Befunde und Therapie von Fernmetastasen');
        //          Lokalisation
        if ((strlen($extractData['metast_lok_leber']) > 0) ||
            (strlen($extractData['metast_lok_lunge']) > 0) ||
            (strlen($extractData['metast_lok_knochen']) > 0) ||
            (strlen($extractData['metast_lok_andere']) > 0)) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Lokalisation');
            if ($extractData['metast_lok_leber'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('lokalisation', 'leber'));
            }
            if ($extractData['metast_lok_lunge'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('lokalisation', 'lunge'));
            }
            if ($extractData['metast_lok_knochen'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('lokalisation', 'knochen'));
            }
            if ($extractData['metast_lok_andere'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('lokalisation', 'andere'));
            }
            $section->AddObservation($observation);
        }
        //          Andauernde oder abgeschlossene Therapie
        if ((strlen($extractData['metast_th_operativ']) > 0) ||
            (strlen($extractData['metast_th_strahlen']) > 0) ||
            (strlen($extractData['metast_th_chemo']) > 0) ||
            (strlen($extractData['metast_th_endo']) > 0) ||
            (strlen($extractData['metast_th_andere']) > 0) ||
            (strlen($extractData['metast_th_keine']) > 0)) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Andauernde oder abgeschlossene Therapie');
            if ($extractData['metast_th_operativ'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('meta_th_status', 'operativ'));
            }
            if ($extractData['metast_th_strahlen'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('meta_th_status', 'strahlen'));
            }
            if ($extractData['metast_th_chemo'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('meta_th_status', 'chemo'));
            }
            if ($extractData['metast_th_endo'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('meta_th_status', 'endo'));
            }
            if ($extractData['metast_th_andere'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('meta_th_status', 'andere'));
            }
            if ($extractData['metast_th_keine'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('meta_th_status', 'keine'));
            }
            $section->AddObservation($observation);
        }
        //          Bisphosphonat-Therapie bei Knochenmetastasen
        if ((strlen($extractData['metast_bip_ja']) > 0) ||
            (strlen($extractData['metast_bip_nein']) > 0) ||
            (strlen($extractData['metast_bip_kontra']) > 0)) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Bisphosphonat-Therapie bei Knochenmetastasen');
            if ($extractData['metast_bip_ja'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('metast_bip', 'j'));
            }
            if ($extractData['metast_bip_nein'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('metast_bip', 'n'));
            }
            if ($extractData['metast_bip_kontra'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('metast_bip', 'kontra'));
            }
            $section->AddObservation($observation);
        }
        if ($section->HasObservations()) {
            $sections[] = $section->ToArray();
        }

        // *************************************************************************************************************
        // Section: Sonstige Befunde
        $section = new Cdmp_2013_0_Section;
        $section->SetCaption('Sonstige Befunde');
        //          Lymphödem vorhanden
        if (strlen($extractData['lymphoedem']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Lymphödem vorhanden');
            $observation->AddErgebnisText($this->_GetDmpExportCode('lymphoedem', $extractData['lymphoedem']));
            $section->AddObservation($observation);
        }
        //          Geplantes Datum der nächsten Dokumentationserstellung
        if (strlen($extractData['termin_datum']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Geplantes Datum der nächsten Dokumentationserstellung');
            $observation->AddZeitpunktDttm(HelperDmp::CheckDate($extractData['termin_datum']));
            $section->AddObservation($observation);
        }
        if ($section->HasObservations()) {
            $sections[] = $section->ToArray();
        }
        // *************************************************************************************************************
    }


    /**
     *
     *
     * @access protected
     * @param $parameters
     * @param $extractData
     * @param $sectionUid
     * @return array
     */
    protected function _CreateEdSection($parameters, $extractData, &$sectionUid) {
        $ed = array();
        $ed['xml_status'] = $this->_GetXmlStatusText($extractData['xml_status']);
        $ed['sections'] = array();
        $this->_FillErstmeldungsSections($extractData, $ed['sections']);
        $sectionUid = 'ED_' . $this->_GetUidFromData($extractData);
        return $ed;
    }


    /**
     *
     *
     * @access protected
     * @param $parameters
     * @param $extractData
     * @param $sectionUid
     * @return array
     */
    protected function _CreateEdPnpSection($parameters, $extractData, &$sectionUid) {
        $ed_pnp = array();
        $ed_pnp['xml_status'] = $this->_GetXmlStatusText($extractData['xml_status']);
        $ed_pnp['sections'] = array();
        $this->_FillErstmeldungsSections($extractData, $ed_pnp['sections']);
        $sectionUid = 'ED_PNP_' . $this->_GetUidFromData($extractData);
        return $ed_pnp;
    }


    /**
     *
     *
     * @access protected
     * @param $parameters
     * @param $extractData
     * @param $sectionUid
     * @return array
     */
    protected function _CreateFdSection($parameters, $extractData, &$sectionUid) {
        $fd = array();
        $fd['xml_status'] = $this->_GetXmlStatusText($extractData['xml_status']);
        $fd['sections'] = array();

        // *************************************************************************************************************
        // Section: Einschreibung erfolgt wegen
        $section = new Cdmp_2013_0_Section;
        $section->SetCaption('Einschreibung erfolgte wegen');
        //          Einschreibung erfolgt wegen
        if (strlen($extractData['einschreibung_grund']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Einschreibung erfolgte wegen');
            $observation->AddErgebnisText($this->_GetDmpExportCode('grund', $extractData['einschreibung_grund']));
            $section->AddObservation($observation);
        }
        if ($section->HasObservations()) {
            $fd['sections'][] = $section->ToArray();
        }

        // *************************************************************************************************************
        // Section: Behandlungsstatus nach operativer Therapie des Primärtumors/kontralateralen Brustkrebses
        //          (adjuvante Therapie)
        $section = new Cdmp_2013_0_Section;
        $section->SetCaption(
            'Behandlungsstatus nach operativer Therapie des Primärtumors/kontralateralen Brustkrebses ' .
            '(adjuvante Therapie)'
        );
        //          Strahlentherapie
        if (strlen($extractData['primaer_strahlen']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Strahlentherapie');
            $observation->AddErgebnisText($this->_GetDmpExportCode('th_behandlung', $extractData['primaer_strahlen']));
            $section->AddObservation($observation);
        }
        //          Chemotherapie
        if (strlen($extractData['primaer_chemo']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Chemotherapie');
            $observation->AddErgebnisText($this->_GetDmpExportCode('th_behandlung', $extractData['primaer_chemo']));
            $section->AddObservation($observation);
        }
        //          Endokrine Therapie
        if (strlen($extractData['primaer_endo']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Endokrine Therapie');
            $observation->AddErgebnisText($this->_GetDmpExportCode('th_behandlung', $extractData['primaer_endo']));
            $section->AddObservation($observation);
        }
        //          Antikörpertherapie mit Trastuzumab
        if (strlen($extractData['primaer_ah']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Antikörpertherapie mit Trastuzumab');
            $observation->AddErgebnisText($this->_GetDmpExportCode('th_behandlung', $extractData['primaer_ah']));
            $section->AddObservation($observation);
        }
        if ($section->HasObservations()) {
            $fd['sections'][] = $section->ToArray();
        }

        // *************************************************************************************************************
        // Section: Seit der letzten Dokumentation neu aufgetretene Ereignisse
        $section = new Cdmp_2013_0_Section;
        $section->SetCaption('Seit der letzten Dokumentation neu aufgetretene Ereignisse');
        //          Manifestation eines lokoregionären Rezidivs (Datum des histologischen Nachweises)
        if ((strlen($extractData['neu_rezidiv_nein']) > 0) ||
            (strlen($extractData['neu_rezidiv_datum']) > 0)) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter(
                'Manifestation eines lokoregionären Rezidivs (Datum des histologischen Nachweises)'
            );
            if ($extractData['neu_rezidiv_nein'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('nein', 'n'));
            }
            if (strlen($extractData['neu_rezidiv_datum']) > 0) {
                $observation->AddZeitpunktDttm(HelperDmp::CheckDate($extractData['neu_rezidiv_datum']));
            }
            $section->AddObservation($observation);
        }
        //          Manifestation eines kontralateralen Brustkrebses (Datum des histologischen Nachweises)
        if ((strlen($extractData['neu_kontra_nein']) > 0) ||
            (strlen($extractData['neu_kontra_datum']) > 0)) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter(
                'Manifestation eines kontralateralen Brustkrebses (Datum des histologischen Nachweises)'
            );
            if ($extractData['neu_kontra_nein'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('nein', 'n'));
            }
            if (strlen($extractData['neu_kontra_datum']) > 0) {
                $observation->AddZeitpunktDttm(HelperDmp::CheckDate($extractData['neu_kontra_datum']));
            }
            $section->AddObservation($observation);
        }
        //          Manifestation von Fernmetastasen (Datum des Diagnosesicherung)
        if ((strlen($extractData['neu_metast_nein']) > 0) ||
            (strlen($extractData['neu_metast_leber']) > 0) ||
            (strlen($extractData['neu_metast_lunge']) > 0) ||
            (strlen($extractData['neu_metast_knochen']) > 0) ||
            (strlen($extractData['neu_metast_andere']) > 0) ||
            (strlen($extractData['neu_metast_datum']) > 0)) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Manifestation von Fernmetastasen (Datum der Diagnosesicherung)');
            if ($extractData['neu_metast_nein'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('neu_metastasen', 'nein'));
            }
            if ($extractData['neu_metast_leber'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('neu_metastasen', 'leber'));
            }
            if ($extractData['neu_metast_lunge'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('neu_metastasen', 'lunge'));
            }
            if ($extractData['neu_metast_knochen'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('neu_metastasen', 'knochen'));
            }
            if ($extractData['neu_metast_andere'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('neu_metastasen', 'andere'));
            }
            if (strlen($extractData['neu_metast_datum']) > 0) {
                $observation->AddZeitpunktDttm(HelperDmp::CheckDate($extractData['neu_metast_datum']));
            }
            $section->AddObservation($observation);
        }
        //          Lymphödem
        if (strlen($extractData['lymphoedem']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Lymphödem');
            $observation->AddErgebnisText($this->_GetDmpExportCode('jn', $extractData['lymphoedem']));
            $section->AddObservation($observation);
        }
        if ($section->HasObservations()) {
            $fd['sections'][] = $section->ToArray();
        }

        // *************************************************************************************************************
        // Section: Behandlung bei fortgeschrittener Erkrankung (lokoregionäres Rezidiv/Fernmetastasen)
        $section = new Cdmp_2013_0_Section;
        $section->SetCaption('Behandlung bei fortgeschrittener Erkrankung (lokoregionäres Rezidiv/Fernmetastasen)');
        //          Aktueller Behandlungsstatus
        if ((strlen($extractData['rez_status_cr']) > 0) ||
            (strlen($extractData['rez_status_pr']) > 0) ||
            (strlen($extractData['rez_status_nc']) > 0) ||
            (strlen($extractData['rez_status_pd']) > 0)) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Aktueller Behandlungsstatus');
            if ($extractData['rez_status_cr'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('rez_status', 'cr'));
            }
            if ($extractData['rez_status_pr'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('rez_status', 'pr'));
            }
            if ($extractData['rez_status_nc'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('rez_status', 'nc'));
            }
            if ($extractData['rez_status_pd'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('rez_status', 'pd'));
            }
            $section->AddObservation($observation);
        }
        //          Seit der letzten Dokumentation andauernde oder abgeschlossene Therapie des lokoregionären Rezidivs
        if ((strlen($extractData['rez_th_praeop']) > 0) ||
            (strlen($extractData['rez_th_exzision']) > 0) ||
            (strlen($extractData['rez_th_mastektomie']) > 0) ||
            (strlen($extractData['rez_th_strahlen']) > 0) ||
            (strlen($extractData['rez_th_chemo']) > 0) ||
            (strlen($extractData['rez_th_endo']) > 0) ||
            (strlen($extractData['rez_th_andere']) > 0) ||
            (strlen($extractData['rez_th_keine']) > 0)) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter(
                'Seit der letzten Dokumentation andauernde oder abgeschlossene Therapie des lokoregionären Rezidivs'
            );
            if ($extractData['rez_th_praeop'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('rez_th', 'praeop'));
            }
            if ($extractData['rez_th_exzision'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('rez_th', 'exzision'));
            }
            if ($extractData['rez_th_mastektomie'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('rez_th', 'mastektomie'));
            }
            if ($extractData['rez_th_strahlen'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('rez_th', 'strahlen'));
            }
            if ($extractData['rez_th_chemo'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('rez_th', 'chemo'));
            }
            if ($extractData['rez_th_endo'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('rez_th', 'endo'));
            }
            if ($extractData['rez_th_andere'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('rez_th', 'andere'));
            }
            if ($extractData['rez_th_keine'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('rez_th', 'keine'));
            }
            $section->AddObservation($observation);
        }
        //          Seit der letzten Dokumentation andauernde oder abgeschlossene Therapie der Fernmetastasen
        if ((strlen($extractData['metast_th_operativ']) > 0) ||
            (strlen($extractData['metast_th_strahlen']) > 0) ||
            (strlen($extractData['metast_th_chemo']) > 0) ||
            (strlen($extractData['metast_th_endo']) > 0) ||
            (strlen($extractData['metast_th_andere']) > 0) ||
            (strlen($extractData['metast_th_keine']) > 0)) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter(
                'Seit der letzten Dokumentation andauernde oder abgeschlossene Therapie der Fernmetastasen'
            );
            if ($extractData['metast_th_operativ'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('meta_th_status', 'operativ'));
            }
            if ($extractData['metast_th_strahlen'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('meta_th_status', 'strahlen'));
            }
            if ($extractData['metast_th_chemo'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('meta_th_status', 'chemo'));
            }
            if ($extractData['metast_th_endo'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('meta_th_status', 'endo'));
            }
            if ($extractData['metast_th_andere'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('meta_th_status', 'andere'));
            }
            if ($extractData['metast_th_keine'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('meta_th_status', 'keine'));
            }
            $section->AddObservation($observation);
        }
        //          Bisphosphonat-Therapie bei Knochenmetastasen
        if ((strlen($extractData['metast_bip_ja']) > 0) ||
            (strlen($extractData['metast_bip_nein']) > 0) ||
            (strlen($extractData['metast_bip_kontra']) > 0)) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Bisphosphonat-Therapie bei Knochenmetastasen');
            if ($extractData['metast_bip_ja'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('metast_bip', 'j'));
            }
            if ($extractData['metast_bip_nein'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('metast_bip', 'n'));
            }
            if ($extractData['metast_bip_kontra'] == '1') {
                $observation->AddErgebnisText($this->_GetDmpExportCode('metast_bip', 'kontra'));
            }
            $section->AddObservation($observation);
        }
        if ($section->HasObservations()) {
            $fd['sections'][] = $section->ToArray();
        }

        // *************************************************************************************************************
        // Section: Sonstiges
        $section = new Cdmp_2013_0_Section;
        $section->SetCaption('Sonstiges');
        //          Geplantes Datum der nächsten Dokumentationserstellung
        if (strlen($extractData['termin_datum']) > 0) {
            $observation = new Cdmp_2013_0_Observation;
            $observation->SetParameter('Geplantes Datum der nächsten Dokumentationserstellung');
            $observation->AddZeitpunktDttm(HelperDmp::CheckDate($extractData['termin_datum']));
            $section->AddObservation($observation);
        }
        if ($section->HasObservations()) {
            $fd['sections'][] = $section->ToArray();
        }

        // *************************************************************************************************************
        $sectionUid = 'FD_' . $this->_GetUidFromData($extractData);
        return $fd;
    }


    /**
     *
     *
     * @access protected
     * @param $title
     * @return array
     */
    protected function _SplitTitle($title)
    {
        $result = array(
            'ac' => '',
            'nb' => ''
        );
        $tmp = explode('.', $title);
        for ($i = 0; $i < (count($tmp) - 1); $i++) {
            $result['ac'] .= trim($tmp[$i]) . ". ";
        }
        if (strlen($tmp[count($tmp) - 1]) > 0) {
            $result['nb'] = trim($tmp[count($tmp) - 1]);
        }
        $result['ac'] = trim($result['ac']);
        return $result;
    }


    /**
     *
     *
     * @access protected
     * @param $prefix
     * @param $url
     * @return string
     */
    protected function _CheckUrl($prefix, $url)
    {
        if (strlen($url) > 0) {
            $pfP = '';
            $pf = strtolower($prefix) . ':';
            if (strtolower(substr($url, 0, strlen($pf))) !== $pf) {
                if ($pf === 'http') {
                    $pfP = '//';
                }
                $url = "{$pf}{$pfP}" . $url;
            }
        }
        return $url;
    }


    /**
     *
     *
     * @access protected
     * @return array
     */
    protected function _ReadDmpExportCodeTable()
    {
        $codes = array();

        $query = "
            SELECT
                klasse,
                code_med,
                code_dmp

            FROM
                l_exp_dmp_2013
        ";
        $result = sql_query_array($this->m_db, $query);
        if ( $result !== false ) {
            foreach ($result as $row) {
                $codes[ $row[ 'klasse' ] . "_" . $row[ 'code_med' ] ] = $row[ 'code_dmp' ];
            }
        }
        return $codes;
    }


    /**
     * validate healthcare
     *
     * @access
     * @param $iknr
     * @param $section
     * @return array
     */
    protected function _validateHealthCare($iknr, $section)
    {
        $lKtstAbrCodes = array();

        $return = array(
            'name' => '',
            'abrechnungsbereich' => ''
        );

        foreach (sql_query_array($this->m_db, "SELECT * FROM l_ktst_abr WHERE iknr = '{$iknr}'") as $entry) {
            $lKtstAbrCodes[$entry['abrechnungsbereich']] = $entry['name'];
        }

        if (array_key_exists($section, $lKtstAbrCodes) === true) {
            $return['name'] = $lKtstAbrCodes[$section];
            $return['abrechnungsbereich'] = $section;
        } elseif (count($lKtstAbrCodes) > 0) {

            // if more then one code possible, then ignore and throw error
            if (count($lKtstAbrCodes) == 1) {
                $return['name'] = reset(array_values($lKtstAbrCodes));
                $return['abrechnungsbereich'] = reset(array_keys($lKtstAbrCodes));
            }
        } else {
            // iknr selected which is not in l_ktst_abr
            $return['name'] = dlookup($this->m_db, 'vorlage_krankenversicherung', 'name', "iknr = '{$iknr}'");
            $return['abrechnungsbereich'] = $section;
        }

        return $return;
    }


    /**
     *
     *
     * @access protected
     * @param $class
     * @param $med_code
     * @param $default_value
     * @return mixed
     */
    protected function _GetDmpExportCode( $class, $med_code )
    {
        if ( false === isset( $this->m_l_exp_dmp[ $class . "_" . $med_code ] ) ) {
            return '';
        }
        return $this->m_l_exp_dmp[ $class . "_" . $med_code ];
    }


    /**
     *
     *
     * @access protected
     * @param $xmlStatus
     * @return string
     */
    protected function _GetXmlStatusText($xmlStatus)
    {
        $result = '';
        switch($xmlStatus) {
        case 3 :
            $result = 'Abbruch';
            break;
        case 2 :
            $result = 'Fehler';
            break;
        case 1 :
            $result = 'Warnung';
            break;
        case 0 :
            $result = 'Ok';
            break;
        }
        return $result;
    }


    /**
     *
     *
     * @access protected
     * @param $phoneNumber
     * @return string
     */
    protected function _CheckFonNumber($phoneNumber)
    {
        if (strlen($phoneNumber) == 0) {
            return '';
        }
        $phoneNumberProto = null;
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $phoneNumberProto = $phoneUtil->parseAndKeepRawInput($phoneNumber, "DE");
        } catch (NumberParseException $e) {
            echo $e;
        }
        if (!$phoneUtil->isValidNumber($phoneNumberProto)) {
            return '';
        }
        return $phoneUtil->format($phoneNumberProto, PhoneNumberFormat::E164);
    }

}

?>
