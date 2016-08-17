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

require_once( 'feature/export/base/class.exportdefaultmodel.php' );
require_once( 'feature/export/base/helper.common.php' );
require_once( 'class.wbc_2012_0_serialiser.php' );

class Cwbc_2012_0_Model extends CExportDefaultModel
{

    /**
     * @access
     * @var array
     */
    protected $m_metastasis_codings = array(
        "C00"      =>    "OTH",
        "C00.0"    =>    "OTH",
        "C00.1"    =>    "OTH",
        "C00.2"    =>    "OTH",
        "C00.3"    =>    "OTH",
        "C00.4"    =>    "OTH",
        "C00.5"    =>    "OTH",
        "C00.6"    =>    "OTH",
        "C00.8"    =>    "OTH",
        "C00.9"    =>    "OTH",
        "C01"      =>    "OTH",
        "C01.9"    =>    "OTH",
        "C02"      =>    "OTH",
        "C02.0"    =>    "OTH",
        "C02.1"    =>    "OTH",
        "C02.2"    =>    "OTH",
        "C02.3"    =>    "OTH",
        "C02.4"    =>    "OTH",
        "C02.8"    =>    "OTH",
        "C02.9"    =>    "OTH",
        "C03"      =>    "OTH",
        "C03.0"    =>    "OTH",
        "C03.1"    =>    "OTH",
        "C03.9"    =>    "OTH",
        "C04"      =>    "OTH",
        "C04.0"    =>    "OTH",
        "C04.1"    =>    "OTH",
        "C04.8"    =>    "OTH",
        "C04.9"    =>    "OTH",
        "C05"      =>    "OTH",
        "C05.0"    =>    "OTH",
        "C05.1"    =>    "OTH",
        "C05.2"    =>    "OTH",
        "C05.8"    =>    "OTH",
        "C05.9"    =>    "OTH",
        "C06"      =>    "OTH",
        "C06.0"    =>    "OTH",
        "C06.1"    =>    "OTH",
        "C06.2"    =>    "OTH",
        "C06.8"    =>    "OTH",
        "C06.9"    =>    "OTH",
        "C07"      =>    "OTH",
        "C07.9"    =>    "OTH",
        "C08"      =>    "OTH",
        "C08.0"    =>    "OTH",
        "C08.1"    =>    "OTH",
        "C08.8"    =>    "OTH",
        "C08.9"    =>    "OTH",
        "C09"      =>    "OTH",
        "C09.0"    =>    "OTH",
        "C09.1"    =>    "OTH",
        "C09.8"    =>    "OTH",
        "C09.9"    =>    "OTH",
        "C10"      =>    "OTH",
        "C10.0"    =>    "OTH",
        "C10.1"    =>    "OTH",
        "C10.2"    =>    "OTH",
        "C10.3"    =>    "OTH",
        "C10.4"    =>    "OTH",
        "C10.8"    =>    "OTH",
        "C10.9"    =>    "OTH",
        "C11"      =>    "OTH",
        "C11.0"    =>    "OTH",
        "C11.1"    =>    "OTH",
        "C11.2"    =>    "OTH",
        "C11.3"    =>    "OTH",
        "C11.8"    =>    "OTH",
        "C11.9"    =>    "OTH",
        "C12"      =>    "OTH",
        "C12.9"    =>    "OTH",
        "C13"      =>    "OTH",
        "C13.0"    =>    "OTH",
        "C13.1"    =>    "OTH",
        "C13.2"    =>    "OTH",
        "C13.8"    =>    "OTH",
        "C13.9"    =>    "OTH",
        "C14"      =>    "OTH",
        "C14.0"    =>    "OTH",
        "C14.2"    =>    "OTH",
        "C14.8"    =>    "OTH",
        "C15"      =>    "OTH",
        "C15.0"    =>    "OTH",
        "C15.1"    =>    "OTH",
        "C15.2"    =>    "OTH",
        "C15.3"    =>    "OTH",
        "C15.4"    =>    "OTH",
        "C15.5"    =>    "OTH",
        "C15.8"    =>    "OTH",
        "C15.9"    =>    "OTH",
        "C16"      =>    "OTH",
        "C16.0"    =>    "OTH",
        "C16.1"    =>    "OTH",
        "C16.2"    =>    "OTH",
        "C16.3"    =>    "OTH",
        "C16.4"    =>    "OTH",
        "C16.5"    =>    "OTH",
        "C16.6"    =>    "OTH",
        "C16.8"    =>    "OTH",
        "C16.9"    =>    "OTH",
        "C17"      =>    "OTH",
        "C17.0"    =>    "OTH",
        "C17.1"    =>    "OTH",
        "C17.2"    =>    "OTH",
        "C17.3"    =>    "OTH",
        "C17.8"    =>    "OTH",
        "C17.9"    =>    "OTH",
        "C18"      =>    "OTH",
        "C18.0"    =>    "OTH",
        "C18.1"    =>    "OTH",
        "C18.2"    =>    "OTH",
        "C18.3"    =>    "OTH",
        "C18.4"    =>    "OTH",
        "C18.5"    =>    "OTH",
        "C18.6"    =>    "OTH",
        "C18.7"    =>    "OTH",
        "C18.8"    =>    "OTH",
        "C18.9"    =>    "OTH",
        "C19"      =>    "OTH",
        "C19.9"    =>    "OTH",
        "C20"      =>    "OTH",
        "C20.9"    =>    "OTH",
        "C21"      =>    "OTH",
        "C21.0"    =>    "OTH",
        "C21.1"    =>    "OTH",
        "C21.2"    =>    "OTH",
        "C21.8"    =>    "OTH",
        "C22"      =>    "HEP",
        "C22.0"    =>    "HEP",
        "C22.1"    =>    "HEP",
        "C23"      =>    "OTH",
        "C23.9"    =>    "OTH",
        "C24"      =>    "OTH",
        "C24.0"    =>    "OTH",
        "C24.1"    =>    "OTH",
        "C24.8"    =>    "OTH",
        "C24.9"    =>    "OTH",
        "C25"      =>    "OTH",
        "C25.0"    =>    "OTH",
        "C25.1"    =>    "OTH",
        "C25.2"    =>    "OTH",
        "C25.3"    =>    "OTH",
        "C25.4"    =>    "OTH",
        "C25.7"    =>    "OTH",
        "C25.8"    =>    "OTH",
        "C25.9"    =>    "OTH",
        "C26"      =>    "OTH",
        "C26.0"    =>    "OTH",
        "C26.8"    =>    "OTH",
        "C26.9"    =>    "OTH",
        "C30"      =>    "OTH",
        "C30.0"    =>    "OTH",
        "C30.1"    =>    "OTH",
        "C31"      =>    "OTH",
        "C31.0"    =>    "OTH",
        "C31.1"    =>    "OTH",
        "C31.2"    =>    "OTH",
        "C31.3"    =>    "OTH",
        "C31.8"    =>    "OTH",
        "C31.9"    =>    "OTH",
        "C32"      =>    "OTH",
        "C32.0"    =>    "OTH",
        "C32.1"    =>    "OTH",
        "C32.2"    =>    "OTH",
        "C32.3"    =>    "OTH",
        "C32.8"    =>    "OTH",
        "C32.9"    =>    "OTH",
        "C33"      =>    "OTH",
        "C33.9"    =>    "OTH",
        "C34"      =>    "PUL",
        "C34.0"    =>    "PUL",
        "C34.1"    =>    "PUL",
        "C34.2"    =>    "PUL",
        "C34.3"    =>    "PUL",
        "C34.8"    =>    "PUL",
        "C34.9"    =>    "PUL",
        "C37"      =>    "OTH",
        "C37.9"    =>    "OTH",
        "C38"      =>    "OTH",
        "C38.0"    =>    "OTH",
        "C38.1"    =>    "OTH",
        "C38.2"    =>    "OTH",
        "C38.3"    =>    "OTH",
        "C38.4"    =>    "PLE",
        "C38.8"    =>    "PLE",
        "C39"      =>    "OTH",
        "C39.0"    =>    "OTH",
        "C39.8"    =>    "OTH",
        "C39.9"    =>    "OTH",
        "C40"      =>    "OSS",
        "C40.0"    =>    "OSS",
        "C40.1"    =>    "OSS",
        "C40.2"    =>    "OSS",
        "C40.3"    =>    "OSS",
        "C40.8"    =>    "OSS",
        "C40.9"    =>    "OSS",
        "C41"      =>    "OSS",
        "C41.0"    =>    "OSS",
        "C41.1"    =>    "OSS",
        "C41.2"    =>    "OSS",
        "C41.3"    =>    "OSS",
        "C41.4"    =>    "OSS",
        "C41.8"    =>    "OSS",
        "C41.9"    =>    "OSS",
        "C42"      =>    "OSS",
        "C42.0"    =>    "OTH",
        "C42.1"    =>    "MAR",
        "C42.2"    =>    "OTH",
        "C42.3"    =>    "OTH",
        "C42.4"    =>    "OTH",
        "C44"      =>    "SKI",
        "C44.0"    =>    "SKI",
        "C44.1"    =>    "SKI",
        "C44.2"    =>    "SKI",
        "C44.3"    =>    "SKI",
        "C44.4"    =>    "SKI",
        "C44.5"    =>    "SKI",
        "C44.6"    =>    "SKI",
        "C44.7"    =>    "SKI",
        "C44.8"    =>    "SKI",
        "C44.9"    =>    "SKI",
        "C47"      =>    "OTH",
        "C47.0"    =>    "OTH",
        "C47.1"    =>    "OTH",
        "C47.2"    =>    "OTH",
        "C47.3"    =>    "OTH",
        "C47.4"    =>    "OTH",
        "C47.5"    =>    "OTH",
        "C47.6"    =>    "OTH",
        "C47.8"    =>    "OTH",
        "C47.9"    =>    "OTH",
        "C48"      =>    "PER",
        "C48.0"    =>    "PER",
        "C48.1"    =>    "PER",
        "C48.2"    =>    "PER",
        "C48.8"    =>    "PER",
        "C49"      =>    "OTH",
        "C49.0"    =>    "OTH",
        "C49.1"    =>    "OTH",
        "C49.2"    =>    "OTH",
        "C49.3"    =>    "OTH",
        "C49.4"    =>    "OTH",
        "C49.5"    =>    "OTH",
        "C49.6"    =>    "OTH",
        "C49.8"    =>    "OTH",
        "C49.9"    =>    "OTH",
        "C50"      =>    "OTH",
        "C50.0"    =>    "OTH",
        "C50.1"    =>    "OTH",
        "C50.2"    =>    "OTH",
        "C50.3"    =>    "OTH",
        "C50.4"    =>    "OTH",
        "C50.5"    =>    "OTH",
        "C50.6"    =>    "OTH",
        "C50.8"    =>    "OTH",
        "C50.9"    =>    "OTH",
        "C51"      =>    "OTH",
        "C51.0"    =>    "OTH",
        "C51.1"    =>    "OTH",
        "C51.2"    =>    "OTH",
        "C51.8"    =>    "OTH",
        "C51.9"    =>    "OTH",
        "C52"      =>    "OTH",
        "C52.9"    =>    "OTH",
        "C53"      =>    "OTH",
        "C53.0"    =>    "OTH",
        "C53.1"    =>    "OTH",
        "C53.8"    =>    "OTH",
        "C53.9"    =>    "OTH",
        "C54"      =>    "OTH",
        "C54.0"    =>    "OTH",
        "C54.1"    =>    "OTH",
        "C54.2"    =>    "OTH",
        "C54.3"    =>    "OTH",
        "C54.8"    =>    "OTH",
        "C54.9"    =>    "OTH",
        "C55"      =>    "OTH",
        "C55.9"    =>    "OTH",
        "C56"      =>    "OTH",
        "C56.9"    =>    "OTH",
        "C57"      =>    "OTH",
        "C57.0"    =>    "OTH",
        "C57.1"    =>    "OTH",
        "C57.2"    =>    "OTH",
        "C57.3"    =>    "OTH",
        "C57.4"    =>    "OTH",
        "C57.7"    =>    "OTH",
        "C57.8"    =>    "OTH",
        "C57.9"    =>    "OTH",
        "C58"      =>    "OTH",
        "C58.9"    =>    "OTH",
        "C60"      =>    "OTH",
        "C60.0"    =>    "OTH",
        "C60.1"    =>    "OTH",
        "C60.2"    =>    "OTH",
        "C60.8"    =>    "OTH",
        "C60.9"    =>    "OTH",
        "C61"      =>    "OTH",
        "C61.9"    =>    "OTH",
        "C62"      =>    "OTH",
        "C62.0"    =>    "OTH",
        "C62.1"    =>    "OTH",
        "C62.9"    =>    "OTH",
        "C63"      =>    "OTH",
        "C63.0"    =>    "OTH",
        "C63.1"    =>    "OTH",
        "C63.2"    =>    "OTH",
        "C63.7"    =>    "OTH",
        "C63.8"    =>    "OTH",
        "C63.9"    =>    "OTH",
        "C64"      =>    "ADR",
        "C64.9"    =>    "ADR",
        "C65"      =>    "OTH",
        "C65.9"    =>    "OTH",
        "C66"      =>    "OTH",
        "C66.9"    =>    "OTH",
        "C67"      =>    "OTH",
        "C67.0"    =>    "OTH",
        "C67.1"    =>    "OTH",
        "C67.2"    =>    "OTH",
        "C67.3"    =>    "OTH",
        "C67.4"    =>    "OTH",
        "C67.5"    =>    "OTH",
        "C67.6"    =>    "OTH",
        "C67.7"    =>    "OTH",
        "C67.8"    =>    "OTH",
        "C67.9"    =>    "OTH",
        "C68"      =>    "OTH",
        "C68.0"    =>    "OTH",
        "C68.1"    =>    "OTH",
        "C68.8"    =>    "OTH",
        "C68.9"    =>    "OTH",
        "C69"      =>    "OTH",
        "C69.0"    =>    "OTH",
        "C69.1"    =>    "OTH",
        "C69.2"    =>    "OTH",
        "C69.3"    =>    "OTH",
        "C69.4"    =>    "OTH",
        "C69.5"    =>    "OTH",
        "C69.6"    =>    "OTH",
        "C69.8"    =>    "OTH",
        "C69.9"    =>    "OTH",
        "C70"      =>    "OTH",
        "C70.0"    =>    "OTH",
        "C70.1"    =>    "OTH",
        "C70.9"    =>    "OTH",
        "C71"      =>    "BRA",
        "C71.0"    =>    "BRA",
        "C71.1"    =>    "BRA",
        "C71.2"    =>    "BRA",
        "C71.3"    =>    "BRA",
        "C71.4"    =>    "BRA",
        "C71.5"    =>    "BRA",
        "C71.6"    =>    "BRA",
        "C71.7"    =>    "BRA",
        "C71.8"    =>    "BRA",
        "C71.9"    =>    "BRA",
        "C72"      =>    "OTH",
        "C72.0"    =>    "OTH",
        "C72.1"    =>    "OTH",
        "C72.2"    =>    "OTH",
        "C72.3"    =>    "OTH",
        "C72.4"    =>    "OTH",
        "C72.5"    =>    "OTH",
        "C72.8"    =>    "OTH",
        "C72.9"    =>    "OTH",
        "C73"      =>    "OTH",
        "C73.9"    =>    "OTH",
        "C74"      =>    "ADR",
        "C74.0"    =>    "ADR",
        "C74.1"    =>    "ADR",
        "C74.9"    =>    "ADR",
        "C75"      =>    "OTH",
        "C75.0"    =>    "OTH",
        "C75.1"    =>    "OTH",
        "C75.2"    =>    "OTH",
        "C75.3"    =>    "OTH",
        "C75.4"    =>    "OTH",
        "C75.5"    =>    "OTH",
        "C75.8"    =>    "OTH",
        "C75.9"    =>    "OTH",
        "C76"      =>    "OTH",
        "C76.0"    =>    "OTH",
        "C76.1"    =>    "OTH",
        "C76.2"    =>    "OTH",
        "C76.3"    =>    "OTH",
        "C76.4"    =>    "OTH",
        "C76.5"    =>    "OTH",
        "C76.7"    =>    "OTH",
        "C76.8"    =>    "OTH",
        "C77"      =>    "LYM",
        "C77.0"    =>    "LYM",
        "C77.1"    =>    "LYM",
        "C77.2"    =>    "LYM",
        "C77.3"    =>    "LYM",
        "C77.4"    =>    "LYM",
        "C77.5"    =>    "LYM",
        "C77.8"    =>    "LYM",
        "C77.9"    =>    "LYM",
        "C80"      =>    "OTH",
        "C80.9"    =>    "OTH",
        "C98.0"    =>    "OTH",
    );


    /**
     *
     */
    public function __construct()
    {
    }

    //*****************************************************************************************************************
    //
    // Overrides from class CExportDefaultModel
    //

    /**
     * @override
     */
    public function ExtractData( $parameters,
                                 $wrapper,
                                 &$export_record )
    {
        $relevantSelectWhere = "ts.erkrankung_id = t.erkrankung_id " .
                               "AND ts.diagnose_seite IN ('B', t.diagnose_seite) " .
                               "AND ts.anlass = t.anlass";
        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;
        $wrapper->SetRangeDate( $parameters[ 'datum_von' ],
                                $parameters[ 'datum_bis' ] );
        $wrapper->SetErkrankungen( 'b' );
        $wrapper->UsePrimaryCasesOnly();
        $wrapper->DoNotUseEkrMeldungsCheck();
        $wrapper->SetDiagnosen( "diagnose LIKE 'C50%' " .
                                "OR diagnose LIKE 'D05.1%' " .
                                "OR diagnose LIKE 'D05.7%' " .
                                "OR diagnose LIKE 'D05.9' " );
        $wrapper->SetMorphologien( "( morphologie NOT LIKE '8520/2' ) " .
                                   "AND RIGHT( morphologie, 2 ) IN ( '/2', '/3' )" );
        $wrapper->SetAdditionalSelects(
            array(
                 "p.kv_iknr                                                                                                                                                                                                        AS 'krankenkassennummer'",
                 "(SELECT ts.mikrokalk          FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.mikrokalk IS NOT NULL          ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS 'mikrokalk'",
                 "(SELECT ts.multizentrisch     FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.multizentrisch IS NOT NULL     ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS 'multizentrisch'",
                 "(SELECT ts.multifokal         FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.multifokal IS NOT NULL         ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS 'multifokal'",
                 "(SELECT ts.rezidiv_lokal      FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.rezidiv_lokal IS NOT NULL      ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS 'rezidiv_lokal'",
                 "(SELECT ts.rezidiv_lk         FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.rezidiv_lk IS NOT NULL         ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS 'rezidiv_lk'",
                 "(SELECT ts.rezidiv_metastasen FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.rezidiv_metastasen IS NOT NULL ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS 'rezidiv_metastasen'",
                 "(SELECT ts.uicc               FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.uicc IS NOT NULL               ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS 'form_uicc'",
                 "(SELECT
                       ts.tnm_praefix

                   FROM
                       tumorstatus ts

                   WHERE
                       {$relevantSelectWhere}
                       AND LEFT( ts.t, 1 )='p'
                       AND LEFT( ts.n, 1 )='p'
                       AND ts.m IS NOT NULL

                   ORDER BY
                       ts.datum_sicherung DESC,
                       ts.sicherungsgrad ASC,
                       ts.datum_beurteilung DESC

                   LIMIT 1 )                                                                                                                                                                                                       AS 'tnm_praefix'",
                 "(SELECT ts.her2_fish          FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.her2_fish IS NOT NULL          ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS 'her2_fish'",
                 "(SELECT IF(MAX(ts.nur_zweitmeinung) IS NOT NULL OR MAX(ts.nur_diagnosesicherung) IS NOT NULL OR MAX(ts.kein_fall) IS NOT NULL, 1, NULL) FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass = t.anlass) AS 'nicht_zaehlen'",
            )
        );
        $wrapper->SetAdditionalFields(
            array(
                "sit.rezidiv_lokal                                   AS rezidiv_lokal",
                "sit.rezidiv_lk                                      AS rezidiv_lk",
                "sit.rezidiv_metastasen                              AS rezidiv_metastasen",
                "sit.erstdiagnose_datum                              AS fruehestes_datum_sicherung",
                "sit.nicht_zaehlen                                   AS nicht_zaehlen",
                "sit.her2_fish                                       AS 'her2_fish'",
                "sit.tnm_praefix                                     AS 'tnm_praefix'",
                "sit.form_uicc                                       AS 'form_uicc'",
                "sit.mikrokalk                                       AS 'mikrokalk'",
                "sit.multizentrisch                                  AS 'multizentrisch'",
                "sit.multifokal                                      AS 'multifokal'",
                "sit.datum_sicherung                                 AS 'datum_sicherung'",
                "sit.krankenkassennummer                             AS 'krankenkassennummer'",
                "sit.patient_nr                                      AS 'referenznr'",
                "IF( sit.anlass LIKE 'r%' AND MIN( h_a.datum ) IS NULL,
                     IF( sit.start_date = '0000-00-00',
                         sit.start_date_rezidiv,
                         sit.start_date ),
                     MIN( h_a.datum )
                 )                                                   AS 'bezugsdatum'",
                "IF( MAX( auf.aufenthalt_id ) IS NOT NULL,
                     GROUP_CONCAT( DISTINCT
                        IF( auf.aufenthalt_id IS NOT NULL,
                            CONCAT_WS( '{$separator_col}',
                                IFNULL( auf.aufenthalt_id, '' ),
                                IFNULL( auf.aufnahmedatum, '' ),
                                IFNULL( auf.entlassungsdatum, '' )
                            ),
                            NULL
                        )
                        SEPARATOR '{$separator_row}'
                     ),
                     NULL
                 )                                                   AS 'aufenthalte'",
                "IF( MAX( stud.studie_id ) IS NOT NULL,
                     GROUP_CONCAT( DISTINCT
                        IF( stud.studie_id IS NOT NULL,
                            CONCAT_WS( '{$separator_col}',
                                IFNULL( stud.studie_id, '' ),
                                IFNULL( stud.date, '' ),
                                IFNULL( stud.beginn, '' ),
                                IFNULL( stud.ende, '' ),
                                IFNULL( stud.vorlage_studie_id, '' )
                            ),
                            NULL
                        )
                        SEPARATOR '{$separator_row}'
                     ),
                     NULL
                 )                                                   AS 'studien'",
                "IF( MAX( tpe.therapieplan_id ) IS NOT NULL,
                     GROUP_CONCAT( DISTINCT
                        IF( tpe.therapieplan_id IS NOT NULL,
                            CONCAT_WS( '{$separator_col}',
                                IFNULL( tpe.therapieplan_id, '' ),
                                IFNULL( tpe.datum, '' ),
                                IFNULL( tpe.zeitpunkt, '' ),
                                IFNULL( tpe.chemo_id, '' ),
                                IFNULL( tpe.chemo_intention, '' ),
                                IFNULL( tpe.immun_id, '' ),
                                IFNULL( tpe.immun_intention, '' ),
                                IFNULL( tpe.ah_id, '' ),
                                IFNULL( tpe.ah_intention, '' ),
                                IFNULL( tpe.andere_id, '' ),
                                IFNULL( tpe.andere_intention, '' ),
                                IFNULL( tpe.strahlen, '' ),
                                IFNULL( tpe.strahlen_intention, '' ),
                                IFNULL( tpe.chemo, '' ),
                                IFNULL( tpe.immun, '' ),
                                IFNULL( tpe.ah, '' ),
                                IFNULL( tpe.andere, '' )
                            ),
                            NULL
                        )
                        SEPARATOR '{$separator_row}'
                     ),
                     NULL
                 )                                                   AS 'therapieplaene'",
                "IF( MAX( untsu.untersuchung_id ) IS NOT NULL,
                     GROUP_CONCAT( DISTINCT
                        IF( untsu.untersuchung_id IS NOT NULL,
                            CONCAT_WS( '{$separator_col}',
                                IFNULL( untsu.untersuchung_id, '' ),
                                IFNULL( untsu.datum, '' ),
                                IFNULL( untsu.art, '' ),
                                IFNULL( untsu.org_id, '' ),
                                IFNULL( untsu.art_seite, '' )
                            ),
                            NULL
                        )
                        SEPARATOR '{$separator_row}'
                     ),
                     NULL
                 )                                                   AS 'untersuchungen'",
            )
        );
        $wrapper->SetAdditionalJoins(
            array(
                "LEFT JOIN studie stud                               ON s.form = 'studie'
                                                                        AND stud.studie_id = s.form_id",
                "LEFT JOIN therapieplan tpe                          ON s.form = 'therapieplan'
                                                                        AND tpe.therapieplan_id = s.form_id
                                                                        AND tpe.grundlage='tk'",
                "LEFT JOIN untersuchung untsu                        ON s.form = 'untersuchung'
                                                                        AND untsu.untersuchung_id = s.form_id
                                                                        AND untsu.art_seite IN ('B', sit.diagnose_seite )"
            )
        );
        $result = $wrapper->GetExportData( $parameters );
        foreach( $result as $extract_data ) {
            if ( '1' !== $extract_data[ 'nicht_zaehlen' ] ) {
                // Aufenthalte
                $aufenthalte = array();
                if ( strlen( $extract_data[ 'aufenthalte' ] ) > 0 ) {
                    $aufenthalte = HReports::RecordStringToArray( $extract_data[ 'aufenthalte' ],
                                                                  array(
                                                                      "aufenthalt_id",
                                                                      "aufnahmedatum",
                                                                      "entlassungsdatum"
                                                                  ) );
                }
                $extract_data[ 'aufenthalte' ] = $aufenthalte;
                // Studien
                $studien = array();
                if ( strlen( $extract_data[ 'studien' ] ) > 0 ) {
                    $studien = HReports::RecordStringToArray( $extract_data[ 'studien' ],
                                                              array(
                                                                  "studie_id",
                                                                  "date",
                                                                  "beginn",
                                                                  "ende",
                                                                  "vorlage_studie_id"
                                                              ) );
                }
                $extract_data[ 'studien' ] = $studien;
                // Terapiepläne
                $therapieplaene = array();
                if ( strlen( $extract_data[ 'therapieplaene' ] ) > 0 ) {
                    $therapieplaene = HReports::RecordStringToArray( $extract_data[ 'therapieplaene' ],
                                                                     array(
                                                                         "therapieplan_id",
                                                                         "datum",
                                                                         "zeitpunkt",
                                                                         "chemo_id",
                                                                         "chemo_intention",
                                                                         "immun_id",
                                                                         "immun_intention",
                                                                         "ah_id",
                                                                         "ah_intention",
                                                                         "andere_id",
                                                                         "andere_intention",
                                                                         "strahlen",
                                                                         "strahlen_intention",
                                                                         "chemo",
                                                                         "immun",
                                                                         "ah",
                                                                         "andere"
                                                                     ) );
                }
                $extract_data[ 'therapieplaene' ] = $this->PreparingTherapyPlans( $therapieplaene,
                                                                                  $extract_data[ 'strahlen_therapien' ],
                                                                                  $extract_data[ 'systemische_therapien' ] );
                // Untersuchungen
                $untersuchungen = array();
                if ( strlen( $extract_data[ 'untersuchungen' ] ) > 0 ) {
                    $untersuchungen = HReports::RecordStringToArray( $extract_data[ 'untersuchungen' ],
                        array(
                             "untersuchung_id",
                             "datum",
                             "ops_code",
                             "org_id",
                             "art_seite"
                        ) );
                }
                $extract_data[ 'untersuchungen' ] = $untersuchungen;
                // Operationen anhand ihrer OPS-Codes überprüfen und gegebenfalls entfernen und/oder aufbereiten
                $extract_data[ 'operationen' ] = HReports::CheckOperationsSide( $this->m_db,
                                                                                $extract_data[ 'operationen' ] );
                // Datengewinnung
                // Create main case
                $case = $this->CreateCase( $export_record->GetDbid(),
                                           $parameters,
                                           $extract_data );
                // Melder
                $section = $this->CreateMelderSection( $parameters,
                                                       $section_uid );
                $melder = $this->CreateBlock( $case->GetDbid(),
                                              $parameters,
                                              'melder',
                                              $section_uid,
                                              $section );
                $case->AddSection( $melder );
                // Fall
                $section = $this->CreateFallSection( $parameters,
                                                     $extract_data,
                                                     $section_uid );
                $fall = $this->CreateBlock( $case->GetDbid(),
                                            $parameters,
                                            'fall',
                                            $section_uid,
                                            $section );
                $case->AddSection( $fall );
                // Patient
                $section = $this->CreatePatientSection( $parameters,
                                                        $extract_data,
                                                        $section_uid );
                $patient = $this->CreateBlock( $case->GetDbid(),
                                               $parameters,
                                               'patient',
                                               $section_uid,
                                               $section );
                $case->AddSection( $patient );
                // Aufenthalte
                $aufenthalte = $this->CreateAufenthaltSections( $parameters,
                                                                $extract_data );
                foreach( $aufenthalte as $row ) {
                    $section_uid = 'AUFENTHALT_' . $row[ 'aufenthalt_id' ];
                    $aufenthalt = $this->CreateBlock( $case->GetDbid(),
                                                      $parameters,
                                                      'aufenthalt',
                                                      $section_uid,
                                                      $row );
                    $case->AddSection( $aufenthalt );
                }
                // Studien
                $studien = $this->CreateStudieSections( $parameters,
                                                        $extract_data );
                foreach( $studien as $row ) {
                    $section_uid = 'STUDIE_' . $row[ 'studie_id' ];
                    $studie = $this->CreateBlock( $case->GetDbid(),
                                                  $parameters,
                                                  'studie',
                                                  $section_uid,
                                                  $row );
                    $case->AddSection( $studie );
                }
                // Tumorkonferenzen
                $tumorkonferenzen = $this->CreateTumorkonferenzSections( $parameters,
                                                                         $extract_data );
                foreach( $tumorkonferenzen as $row ) {
                    $section_uid = 'TUMORKONF_' . $row[ 'therapieplan_id' ];
                    $tumorkonferenz = $this->CreateBlock( $case->GetDbid(),
                                                          $parameters,
                                                          'tumorkonferenz',
                                                          $section_uid,
                                                          $row );
                    $case->AddSection( $tumorkonferenz );
                }
                // Diagnose
                $section = $this->CreateDiagnoseSection( $parameters,
                                                         $extract_data,
                                                         $section_uid );
                $diagnose = $this->CreateBlock( $case->GetDbid(),
                                                $parameters,
                                                'diagnose',
                                                $section_uid,
                                                $section );
                $case->AddSection( $diagnose );
                // Therapien
                $therapien = $this->GetTherapieSections( $parameters,
                                                         $extract_data );
                foreach( $therapien as $row ) {
                    $section_uid = $row[ 'therapieart' ] . '_' . $row[ 'therapie_id' ];
                    $therapie = $this->CreateBlock( $case->GetDbid(),
                                                    $parameters,
                                                    'therapie',
                                                    $section_uid,
                                                    $row );
                    $case->AddSection( $therapie );
                }
                // Histologien
                $histologien = $this->GetPathoHistologieSections( $parameters,
                                                                  $extract_data );
                foreach( $histologien as $row ) { // INFO: Kommt so-wie-so nur eine! :)
                    $section_uid = 'HISTO_' . $row[ 'tumorstatus_id' ];
                    $histologie = $this->CreateBlock( $case->GetDbid(),
                                                      $parameters,
                                                      'histologie',
                                                      $section_uid,
                                                      $row );
                    $case->AddSection( $histologie );
                }
                // Labore
                $section = $this->CreateLaborSection( $parameters,
                                                      $extract_data,
                                                      $section_uid );
                $labor = $this->CreateBlock( $case->GetDbid(),
                                             $parameters,
                                             'labor',
                                             $section_uid,
                                             $section );
                $case->AddSection( $labor );

                // Nachsorgen
                $nachsorgen = $this->GetNachsorgeSections( $parameters,
                                                           $extract_data );
                foreach( $nachsorgen as $row ) {
                    $section_uid = 'NACH_' . $row[ 'nachsorge_id' ];
                    $nachsorge = $this->CreateBlock( $case->GetDbid(),
                                                     $parameters,
                                                     'nachsorge',
                                                     $section_uid,
                                                     $row );
                    $case->AddSection( $nachsorge );
                }
                // Add main case
                $export_record->AddCase( $case );
            }
        }
    }


    /**
     * @override
     */
    public function PreparingData( $parameters,
                                   &$export_record )
    {
    }


    /**
     * @override
     */
    public function HandleDiff($parameters, $case, &$section, $old_section)
    {
        $section->SetMeldungskennzeichen( "N" );
        $section->SetDataChanged( 1 );
    }


    /**
     * @override
     */
    public function CheckData( $parameters,
                               &$export_record )
    {
        // Hier jeden Abschnitt gegen XSD Prüfen und Fehler in DB schreiben...
        $serialiser = new Cwbc_2012_0_Serialiser();
        $serialiser->Create( $this->m_absolute_path,
                             $this->GetExportName(),
                             $this->m_smarty,
                             $this->m_db,
                             $this->m_error_function );
        $serialiser->SetData( $export_record );
        $serialiser->Validate( $this->m_parameters );
    }


    /**
     * @override
     */
    public function WriteData()
    {
        $this->m_export_record->SetFinished( true );
        // Hier gesammtes XML schreiben und nicht mehr gegen XSD prüfen..
        $serialiser = new Cwbc_2012_0_Serialiser();
        $serialiser->Create( $this->m_absolute_path,
                             $this->GetExportName(),
                             $this->m_smarty,
                             $this->m_db,
                             $this->m_error_function );
        $serialiser->SetData( $this->m_export_record );
        $this->m_export_filename = $serialiser->Write( $this->m_parameters );
        $this->m_export_record->Write( $this->m_db );
    }

    //*********************************************************************************************
    //
    // Helper functions
    //

    /**
     *
     *
     * @access
     * @param $extract_data
     * @return string
     */
    protected function GetUidFromData( $extract_data ) {
        return $extract_data[ 'patient_id' ] . "_" .
               $extract_data[ 'erkrankung_id' ] . "_" .
               $extract_data[ 'tumoridentifikator' ] .
               $extract_data[ 'diagnose_seite' ];
    }


    /**
     *
     *
     * @access
     * @param $datum
     * @return string
     */
    protected function CheckDatum( $datum ) {
        $min_datum = date( '1900-01-01' );
        $max_datum = date( '2050-12-31' );
        if ( date( $datum ) < $min_datum ) {
            return '1900-01-01';
        }
        else if ( date( $datum ) > $max_datum ) {
            return '2050-12-31';
        }
        return $datum;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $section_uid
     * @return array
     */
    protected function CreateMelderSection( $parameters,
                                            &$section_uid )
    {
        $ansprechpartner_name = isset( $parameters[ 'ansprechpartner_name' ] ) ?
            $parameters[ 'ansprechpartner_name' ] : '';
        $ansprechpartner_email =  isset( $parameters[ 'ansprechpartner_email' ] ) ?
            $parameters[ 'ansprechpartner_email' ] : '';
        $melder = array();
        $melder[ 'schema_version' ] = array(
            'typ' => $parameters[ 'schema_version_typ' ],
            'jahr' => $parameters[ 'schema_version_jahr' ]
        );
        $melder[ 'zentrum_id' ] = $parameters[ 'zentrum_id' ];
        $melder[ 'datum_datensatzerstellung' ] = $this->CheckDatum( date( "Y-m-d" ) );
        $melder[ 'zeitraum_beginn' ] = $this->CheckDatum( $parameters[ 'datum_von' ] );
        $melder[ 'zeitraum_ende' ] = $this->CheckDatum( $parameters[ 'datum_bis' ] );
        $melder[ 'technische_ansprechpartner' ] = array(
            "tech_ansprechpartner_name" => HCommon::TrimString( $ansprechpartner_name,
                                                                200,
                                                                true ),
            "email"                     => HCommon::TrimString( $ansprechpartner_email,
                                                                200,
                                                                true )
        );
        $melder[ 'sw' ] = array(
            'sw_hersteller' => HCommon::TrimString( $parameters[ 'sw_hersteller' ],
                                                    200,
                                                    true ),
            'sw_name'       => HCommon::TrimString( $parameters[ 'sw_name' ],
                                                    200,
                                                    true ),
            'sw_version'    => HCommon::TrimString( $parameters[ 'sw_version' ],
                                                    200,
                                                    true )
        );
        $section_uid = 'MELDER_' . $parameters[ 'user_id' ] . '_' . $parameters[ 'org_id' ];
        return $melder;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @param $section_uid
     * @return array
     */
    protected function CreateFallSection( $parameters,
                                          $extract_data,
                                          &$section_uid ) {
        $fall = array();
        $fall[ 'fall_id' ] = $extract_data[ 'erkrankung_id' ] . $extract_data[ 'diagnose_seite' ];
        $kkd = $this->GetKrankenkassenDaten( $extract_data[ 'krankenkassennummer' ] );
        $fall[ 'kostentraeger' ] = "";
        if ( false != $kkd ) {
            $fall[ 'kostentraeger' ] = HCommon::TrimString( $kkd[ 'name' ],
                                                            200,
                                                            true );
        }
        $fall[ 'menopause' ][ 'menopausenstatus' ] = "4";
        $fall[ 'menopause' ][ 'menopausenstatus_datum' ] = "1900-01-01";
        $anam = HReports::GetMaxElementByDate( $extract_data[ 'anamnesen' ],
                                               2,
                                               null );
        if ( false !== $anam ) {
            $fall[ 'menopause' ][ 'menopausenstatus' ] = $this->GetExportCode( 'menopause',
                                                                               $anam[ 'menopausenstatus' ],
                                                                               '4' );
            $fall[ 'menopause' ][ 'menopausenstatus_datum' ] = $this->CheckDatum( $anam[ 'datum' ] );
        }
        $fall[ 'koerpergroesse' ] = $this->GetKoerpergroesse( $extract_data[ 'anamnesen' ] );
        $fall[ 'koerpergewicht' ] = $this->GetKoerpergewicht( $extract_data[ 'anamnesen' ] );
        $fall[ 'seite' ] = $this->GetExportCode( 'seite',
                                                 $extract_data[ 'diagnose_seite' ],
                                                 '3' );
        $fall[ 'fall_beginn' ] = $this->CheckDatum( $extract_data[ 'bezugsdatum' ] );
        $section_uid = 'FALL_' . $this->GetUidFromData( $extract_data );
        return $fall;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @param $section_uid
     * @return array
     */
    protected function CreatePatientSection( $parameters,
                                             $extract_data,
                                             &$section_uid )
    {
        $patient = array();
        $patient[ 'nachname' ] = $extract_data[ 'nachname' ];
        $patient[ 'vorname' ] = $extract_data[ 'vorname' ];
        $patient[ 'patient_id' ] = $extract_data[ 'referenznr' ];
        $patient[ 'pat_daten' ][ 'geburtstag' ] = $extract_data[ 'geburtsdatum' ];
        $patient[ 'pat_daten' ][ 'geschlecht' ] = ( strlen( $extract_data[ 'geschlecht' ] ) > 0 ) ? $extract_data[ 'geschlecht' ] : 'x';
        $patient[ 'pat_daten' ][ 'verstorben' ] = array(
            'todesdatum' => '',
            'todesursache' => ''
        );
        if ( $extract_data[ 'abschlussgrund' ] == 'tot' ) {
            $patient[ 'pat_daten' ][ 'verstorben' ][ 'todesdatum' ] = "1900-01-01";
            if ( strlen( $extract_data[ 'todesdatum' ] ) > 0 ) {
                $patient[ 'pat_daten' ][ 'verstorben' ][ 'todesdatum' ] = $this->CheckDatum( $extract_data[ 'todesdatum' ] );
            }
            $patient[ 'pat_daten' ][ 'verstorben' ][ 'todesursache' ] = $this->GetExportCode( 'tod_tumorassoziation',
                                                                                              $extract_data[ 'tod_tumorbedingt' ],
                                                                                              '4' );
        }
        $section_uid = 'PATIENT_' . $extract_data[ 'patient_id' ];
        return $patient;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @return array
     */
    protected function CreateAufenthaltSections( $parameters,
                                                 $extract_data )
    {
        $aufenthalte = array();
        foreach( $extract_data[ 'aufenthalte' ] as $aufenthalt ) {
            if ( ( strlen( $aufenthalt[ 'aufnahmedatum' ] ) > 0 ) &&
                 ( strlen( $aufenthalt[ 'entlassungsdatum' ] ) > 0 ) ) {
                $item = array();
                $item[ 'aufenthalt_id' ] = $aufenthalt[ 'aufenthalt_id' ];
                $item[ 'aufenthalt_beginn' ] = $this->CheckDatum( $aufenthalt[ 'aufnahmedatum' ] );
                $item[ 'aufenthalt_dauer' ] = HReports::CalcDauer( $aufenthalt[ 'aufnahmedatum' ],
                                                                   $aufenthalt[ 'entlassungsdatum' ] );
                $aufenthalte[] = $item;
            }
        }
        return $aufenthalte;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @return array
     */
    protected function CreateStudieSections( $parameters,
                                             $extract_data )
    {
        $studien = array();
        if ( count( $extract_data[ 'studien' ] ) > 0 ) {
            foreach( $extract_data[ 'studien' ] as $studie ) {
                $stud_vor = $this->GetStudienVorlage( $studie[ 'vorlage_studie_id' ] );
                $item = array();
                $item[ 'studie_id' ] = $studie[ 'studie_id' ];
                $item[ 'studienteilnehmer' ] = "1";
                $item[ 'studien_name' ] = "";
                if ( ( false !== $stud_vor ) &&
                     strlen( $stud_vor[ 'bez' ] ) > 0 ) {
                    $item[ 'studien_name' ] = HCommon::TrimString( $stud_vor[ 'bez' ],
                                                                   200,
                                                                   true );
                }
                $item[ 'datum_einschluss' ] = $studie[ 'beginn' ];
                if ( strlen( $item[ 'datum_einschluss' ] ) == 0 ) {
                    $item[ 'datum_einschluss' ] = $studie[ 'date' ];
                }
                $item[ 'datum_einschluss' ] = $this->CheckDatum( $item[ 'datum_einschluss' ] );
                $item[ 'datum_ende' ] = $this->CheckDatum( $studie[ 'ende' ] );
                $item[ 'studie_beendet' ] = "2";
                if ( strlen( $studie[ 'ende' ] ) > 0 ) {
                    $item[ 'studie_beendet' ] = "1";
                }
                $item[ 'studien_bemerkungen' ] = "";
                if ( ( false !== $stud_vor ) &&
                     strlen( $stud_vor[ 'art' ] ) > 0 ) {
                    $item[ 'studien_bemerkungen' ] = HCommon::TrimString( $stud_vor[ 'art' ],
                                                                          200,
                                                                          true );
                }
                $studien[] = $item;
            }
        }
        else {
            $studien[] = array(
                'studie_id'           => -1,
                'studienteilnehmer'   => '0',
                'studien_name'        => '',
                'datum_einschluss'    => '',
                'datum_ende'          => '',
                'studie_beendet'      => '',
                'studien_bemerkungen' => ''
            );
        }
        return $studien;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @return array
     */
    protected function CreateTumorkonferenzSections( $parameters,
                                                     $extract_data )
    {
        $tumorkonferenzen = array();
        foreach( $extract_data[ 'therapieplaene' ] as $therapieplan ) {
            $item = array();
            $item[ 'therapieplan_id' ] = $therapieplan[ 'therapieplan_id' ];
            $item[ 'tumorkonferenz_datum' ] = $this->CheckDatum( $therapieplan[ 'datum' ] );
            $item[ 'empfehlung' ] = "1";
            $item[ 'zeitpunkt' ] = $this->GetExportCode( 'therapieplan_zeitpunkt',
                                                         $therapieplan[ 'zeitpunkt' ],
                                                         '3' );
            $tumorkonferenzen[] = $item;
        }
        return $tumorkonferenzen;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @param $section_uid
     * @return array
     */
    protected function CreateDiagnoseSection( $parameters,
                                              $extract_data,
                                              &$section_uid )
    {
        $diagnose = array();
        $diagnose[ 'rezidiv' ] = "0";
        $diagnose[ 'diag_datum' ] = $this->CheckDatum( $extract_data[ 'fruehestes_datum_sicherung' ] );
        $diagnose[ 'biopsie_methode' ] = "6";
        $diagnose[ 'biopsie_steuerung' ] = "5";
        $diagnose[ 'biopsie_extern_durchgefuehrt' ] = "2";
        $diagnose[ 'biopsie_datum' ] = "1900-01-01";
        $biopsie_record = $this->GetBiopsieData( $extract_data, $biopsie_methode );
        if ( false !== $biopsie_record ) {
            $diagnose[ 'biopsie_methode' ] = $biopsie_methode;
            $diagnose[ 'biopsie_steuerung' ] = $this->GetBiopsieSteuerung( $biopsie_record );
            $diagnose[ 'biopsie_extern_durchgefuehrt' ] = $this->IsBiopsieFromExtern( $parameters,
                                                                                      $biopsie_record );
            $diagnose[ 'biopsie_datum' ] = $this->CheckDatum( $biopsie_record[ 'datum' ] );
        }
        $diagnose[ 'mikrokalk' ] = ( $extract_data[ 'mikrokalk' ] == '1' ) ? '1' : '0';
        $icd_code = HReports::GetDiagnoseWithText( $this->m_db,
                                                   $extract_data[ 'erkrankung_id' ],
                                                   $extract_data[ 'diagnose_seite' ],
                                                   $extract_data[ 'start_date' ],
                                                   $extract_data[ 'end_date' ] );
        $diagnose[ 'klassifikation' ][ 'icd_code' ] = "";
        $diagnose[ 'klassifikation' ][ 'icd_text' ] = "";
        if ( isset( $icd_code[ 'diagnose' ] ) ) {
            $diagnose[ 'klassifikation' ][ 'icd_code' ] = HCommon::TrimString( $icd_code[ 'diagnose' ],
                                                                               200,
                                                                               true );
            $diagnose[ 'klassifikation' ][ 'icd_text' ] = HCommon::TrimString( $icd_code[ 'text' ],
                                                                               200,
                                                                               true );
        }
        $diagnose[ 'klassifikation' ][ 'definitive_morphologie' ] = $extract_data[ 'morphologie' ];
        if ( strlen( $diagnose[ 'klassifikation' ][ 'definitive_morphologie' ] ) == 0 ) {
            $diagnose[ 'klassifikation' ][ 'definitive_morphologie' ] = "9999/9";
        }

        $lokalisation = $extract_data[ 'lokalisation' ];
        if ( strlen( $lokalisation ) === 0 ) {
            $lokalisation = "";
            $arr = HReports::GetDiagnoseToLokalisation( $this->m_db, $extract_data[ 'diagnose' ] );
            if ( false !== $arr ) {
                $lokalisation = $arr[ 'lokalisation' ];
            }
        }
        $diagnose[ 'klassifikation' ][ 'definitive_topologie' ] = HCommon::TrimString( $lokalisation,
                                                                                       200,
                                                                                       true );
        $diagnose[ 'tumor' ][ 'multizentritaet' ] = ( $extract_data[ 'multizentrisch' ] == '1' ) ? '1' : '0';
        $diagnose[ 'tumor' ][ 'multifokalitaet' ] = ( $extract_data[ 'multifokal' ] == '1' ) ? '1' : '0';
        $diagnose[ 'tumor' ][ 'dignitaet' ] = "3";
        if ( strlen( $diagnose[ 'klassifikation' ][ 'definitive_morphologie' ] ) > 0 ) {
            if ( substr( $diagnose[ 'klassifikation' ][ 'definitive_morphologie' ], -2 ) == '/3' ) {
                $diagnose[ 'tumor' ][ 'dignitaet' ] = '2';
            }
            else if ( substr( $diagnose[ 'klassifikation' ][ 'definitive_morphologie' ], -2 ) == '/0' ) {
                $diagnose[ 'tumor' ][ 'dignitaet' ] = '1';
            }
        }
        $diagnose[ 'tumor' ][ 'klinische_einteilung' ][ 't' ] = $this->GetExportCode( 'ct',
                                                                                      $extract_data[ 'ct' ],
                                                                                      '' );
        $diagnose[ 'tumor' ][ 'klinische_einteilung' ][ 'n' ] = $this->GetExportCode( 'cn',
                                                                                      $extract_data[ 'cn' ],
                                                                                      '' );
        $diagnose[ 'tumor' ][ 'klinische_einteilung' ][ 'm' ] = $this->GetExportCode( 'cm',
                                                                                      $extract_data[ 'cm' ],
                                                                                      '' );
        $diagnose[ 'tumor' ][ 'klinische_einteilung' ][ 'tnm_version' ] = "503";
        $diagnose[ 'tumor' ][ 'klinische_einteilung' ][ 'stadiengruppierung_uicc' ] = $this->BuildUicc( $extract_data );
        $section_uid = 'DIAG_' . $this->GetUidFromData( $extract_data );
        return $diagnose;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @return array
     */
    protected function GetTherapieSections( $parameters,
                                            $extract_data )
    {
        $therapien = array();
        $therapien[ 0 ][ 'therapie_id' ] = "1";
        $therapien[ 0 ][ 'therapieart' ] = "therapie";
        $therapien[ 0 ][ 'operationen' ] = array();
        $therapien[ 0 ][ 'systemtherapien' ] = array();
        $therapien[ 0 ][ 'strahlentherapien' ] = array();
        // Operationen
        foreach( $extract_data[ 'operationen' ] as $op ) {
            $item = array();
            $item[ 'tumorrelevante_op' ] = $this->IsTumorrelevanteOp( $op );
            $item[ 'ops' ] = array();
            foreach( $op[ 'ops_codes' ] as $code ) {
                if ( (substr($code[ 'prozedur' ], 2, 1) !== 'e' ) ) {
                    $item[ 'ops' ][] = array(
                        'ops_code' => $code[ 'prozedur' ],
                        'ops_text' => HCommon::TrimString( $code[ 'prozedur_text' ],
                                                           200,
                                                           true )
                    );
                }
            }
            $item[ 'op_datum' ] = $this->CheckDatum( $op[ 'beginn' ] );
            $item[ 'op_typ' ] = '4';
            if ( ( $item[ 'tumorrelevante_op' ] == '1' ) ||
                 ( $op[ 'art_primaertumor' ] == '1' ) ) {
                $item[ 'op_typ' ] = '1';
            }
            else if ( $op[ 'art_revision' ] == '1'  ) {
                $item[ 'op_typ' ] = '2';
            }
            $item[ 'op_intention' ] = '';
            if ( $op[ 'intention' ] == 'kur' ) {
                $item[ 'op_intention' ] = '1';
            }
            else if ( $op[ 'intention' ] == 'pal' ) {
                $item[ 'op_intention' ] = '2';
            }
            $item[ 'op_letalitaet' ] = $this->GetOpLetalitaet( $op, $extract_data );
            $item[ 'operativer_sicherheitsabstand' ] = $this->GetOperativerSicherheitsabstand( $op[ 'eingriff_id' ] );
            $item[ 'ablatio_wunsch' ] = '2';
            $item[ 'bet_wunsch' ] = $this->GetBetWunsch( $op, $extract_data );
            $item[ 'bet_nicht_durchgefuehrt' ] = $this->GetBetNichtDurchgefuehrt( $op );
            $item[ 'mastektomie_nicht_durchgefuehrt' ] = $this->MastektomieNichtDurchgefuehrt( $op );
            $item[ 'sentinel_nicht_durchgefuehrt' ] = $this->GetExportCode( 'sln_nein_grund',
                                                                            $op[ 'sln_nein_grund' ],
                                                                            '' );
            $item[ 'axilla_nicht_durchgefuehrt' ] = $this->GetExportCode( 'axilla_nein_grund',
                                                                          $op[ 'axilla_nein_grund' ],
                                                                          '' );
            $item[ 'sentinel_nicht_detektierbar' ] = '2';
            if ( strlen( $op[ 'sentinel_nicht_detectierbar' ] ) > 0 ) {
                $item[ 'sentinel_nicht_detektierbar' ] = $op[ 'sentinel_nicht_detectierbar' ];
            }
            $item[ 'lokale_pathohisto_radikalitaet' ] = $this->GetLokalePathohistoRadikalitaet( $op[ 'eingriff_id' ] );
            $item[ 'asa_score' ] = $this->GetExportCode( 'asa',
                                                         $op[ 'asa' ],
                                                         '' );
            $item[ 'op_komplikationen' ] = $this->GetKomplikationen( $op[ 'eingriff_id' ] );
            $item[ 'praeoperative_markierung' ] = '3';
            if ( ( $op[ 'art_primaertumor' ] == '1' ) ||
                 ( $op[ 'art_rezidiv' ] == '1' ) ) {
                if ( $op[ 'mark' ] == '1d' ) {
                    $item[ 'praeoperative_markierung' ] = '1';
                }
                else if ( $op[ 'mark' ] == '1om' ) {
                    $item[ 'praeoperative_markierung' ] = '2';
                }
            }
            $item[ 'markierung_abstand' ] = $this->GetExportCode( 'mark_abstand',
                                                                  $op[ 'mark_abstand' ],
                                                                  '2' );

            $item[ 'bildgebende_kontrolle' ] = '2';
            if (($op['postop_roentgen'] == '0') && ($op['intraop_roe'] == '0') &&
                ($op['postop_sono'] == '0') && ($op['intraop_sono'] == '0')) {
                $item[ 'bildgebende_kontrolle' ] = '0';
            } elseif (($op['postop_roentgen'] == '1') || ($op['intraop_roe'] == '1') ||
                ($op['postop_sono'] == '1') || ($op['intraop_sono'] == '1')) {
                $item[ 'bildgebende_kontrolle' ] = '1';
            } else {
                $item[ 'bildgebende_kontrolle' ] = '2';
            }

            $lymph_data = $this->GetLymphData( $op );
            $item[ 'anz_entf_lymphknoten' ] = $lymph_data[ 'anz_entf_lymphknoten' ];
            $item[ 'anz_entf_lymphknoten_pos' ] = $lymph_data[ 'anz_entf_lymphknoten_pos' ];
            $item[ 'anz_entf_sentinel' ] = $lymph_data[ 'anz_entf_sentinel' ];
            $item[ 'anz_entf_sentinel_pos' ] = $lymph_data[ 'anz_entf_sentinel_pos' ];
            $item[ 'op_extern_durchgefuehrt' ] = '2';
            if ( strlen( $op[ 'org_id' ] ) > 0 ) {
                if ( $op[ 'org_id' ] == $parameters[ 'org_id' ] ) {
                    $item[ 'op_extern_durchgefuehrt' ] = '0';
                }
                else {
                    $item[ 'op_extern_durchgefuehrt' ] = '1';
                }
            }
            $item[ 'brustrekonstruktion' ] = $this->GetBrustRekonstruktion( $op );
            $therapien[ 0 ][ 'operationen' ][] = $item;
        }
        // Systemische Therapien
        $sys_th_ids = array();
        foreach( $extract_data[ 'systemische_therapien' ] as $sys_th ) {
            $item = $this->CreateSystemischeTherapieItem( $sys_th[ 'vorlage_therapie_id' ],
                                                          $sys_th[ 'art' ],
                                                          $sys_th[ 'beginn' ],
                                                          $sys_th[ 'ende' ],
                                                          $sys_th[ 'intention' ],
                                                          $sys_th[ 'endstatus' ],
                                                          $sys_th[ 'endstatus_grund' ],
                                                          $sys_th[ 'best_response' ],
                                                          '',
                                                          '' );
            $item[ 'keine_systemtherapie' ] = '';
            $therapien[ 0 ][ 'systemtherapien' ][] = $item;
            $sys_th_ids[] = array(
                'tid' => $sys_th[ 'therapieplan_id' ],
                'art' => $sys_th[ 'art' ]
            );
        }
        // Alle Therapiepläne exportieren
        foreach( $extract_data[ 'therapieplaene' ] as $thp_sys ) {
                if ( ( strlen( $thp_sys[ 'chemo_id' ] ) > 0 ) &&
                     ( $thp_sys[ 'chemo' ] === '1' ) ) {
                    $item = $this->CreateSystemischeTherapieItem( $thp_sys[ 'chemo_id' ],
                                                                  $this->GetTherapieVorlagenArt( $thp_sys[ 'chemo_id' ] ),
                                                                  '',
                                                                  '',
                                                                  $thp_sys[ 'chemo_intention' ],
                                                                  '',
                                                                  '',
                                                                  '',
                                                                  '',
                                                                  $thp_sys[ 'therapieplan_id' ] );
                    if ( count( $item ) > 0 ) {
                        $item[ 'systemtherapie_ausfuehrung' ] = '1';
                        $item[ 'systemtherapie_ergebnis' ] = '6';
                        $item[ 'systemtherapie_erfolg' ] = '5';
                        $therapien[ 0 ][ 'systemtherapien' ][] = $item;
                    }
                }
                if ( ( strlen( $thp_sys[ 'immun_id' ] ) > 0 ) &&
                     ( $thp_sys[ 'immun' ] === '1' ) ) {
                    $item = $this->CreateSystemischeTherapieItem( $thp_sys[ 'immun_id' ],
                                                                  $this->GetTherapieVorlagenArt( $thp_sys[ 'immun_id' ] ),
                                                                  '',
                                                                  '',
                                                                  $thp_sys[ 'immun_intention' ],
                                                                  '',
                                                                  '',
                                                                  '',
                                                                  '',
                                                                  $thp_sys[ 'therapieplan_id' ] );
                    if ( count( $item ) > 0 ) {
                        $item[ 'systemtherapie_ausfuehrung' ] = '1';
                        $item[ 'systemtherapie_ergebnis' ] = '6';
                        $item[ 'systemtherapie_erfolg' ] = '5';
                        $therapien[ 0 ][ 'systemtherapien' ][] = $item;
                    }
                }
                if ( ( strlen( $thp_sys[ 'ah_id' ] ) > 0 ) &&
                     ( $thp_sys[ 'ah' ] === '1' ) ) {
                    $item = $this->CreateSystemischeTherapieItem( $thp_sys[ 'ah_id' ],
                                                                  $this->GetTherapieVorlagenArt( $thp_sys[ 'ah_id' ] ),
                                                                  '',
                                                                  '',
                                                                  $thp_sys[ 'ah_intention' ],
                                                                  '',
                                                                  '',
                                                                  '',
                                                                  '',
                                                                  $thp_sys[ 'therapieplan_id' ] );
                    if ( count( $item ) > 0 ) {
                        $item[ 'systemtherapie_ausfuehrung' ] = '1';
                        $item[ 'systemtherapie_ergebnis' ] = '6';
                        $item[ 'systemtherapie_erfolg' ] = '5';
                        $therapien[ 0 ][ 'systemtherapien' ][] = $item;
                    }
                }
                if ( ( strlen( $thp_sys[ 'andere_id' ] ) > 0 ) &&
                     ( $thp_sys[ 'andere' ] === '1' ) ) {
                    $item = $this->CreateSystemischeTherapieItem( $thp_sys[ 'andere_id' ],
                                                                  $this->GetTherapieVorlagenArt( $thp_sys[ 'andere_id' ] ),
                                                                  '',
                                                                  '',
                                                                  $thp_sys[ 'andere_intention' ],
                                                                  '',
                                                                  '',
                                                                  '',
                                                                  '',
                                                                  $thp_sys[ 'therapieplan_id' ] );
                    if ( count( $item ) > 0 ) {
                        $item[ 'systemtherapie_ausfuehrung' ] = '1';
                        $item[ 'systemtherapie_ergebnis' ] = '6';
                        $item[ 'systemtherapie_erfolg' ] = '5';
                        $therapien[ 0 ][ 'systemtherapien' ][] = $item;
                    }
                }
        }
        // Strahlentherapien
        $th_ids = array();
        foreach( $extract_data[ 'strahlen_therapien' ] as $strahlen_th ) {
            $item = array();
            $item[ 'strahlentherapie' ] = '1';
            $item[ 'strahlentherapie_intention' ] = $this->GetExportCode( 'intention',
                                                                          $strahlen_th[ 'intention' ],
                                                                          '' );
            $item[ 'strahlentherapie_beg_datum' ] = $this->CheckDatum( $strahlen_th[ 'beginn' ] );
            $item[ 'strahlentherapie_ende_datum' ] = $this->CheckDatum( $strahlen_th[ 'ende' ] );
            $item[ 'strahlentherapie_ergebnis' ] = '5';
            if ( ( $strahlen_th[ 'endstatus' ] == 'plan' ) ||
                 ( $strahlen_th[ 'endstatus' ] == 'abw' ) ) {
                $item[ 'strahlentherapie_ergebnis' ] = '1';
            }
            else if ( ( $strahlen_th[ 'endstatus' ] == 'abbr' ) &&
                      ( ( $strahlen_th[ 'endstatus_grund' ] == 'hn' ) ||
                        ( $strahlen_th[ 'endstatus_grund' ] == 'nhn' ) ) ) {
                $item[ 'strahlentherapie_ergebnis' ] = '2';
            }
            else if ( ( $strahlen_th[ 'endstatus' ] == 'abbr' ) &&
                      ( $strahlen_th[ 'endstatus_grund' ] == 'tod' ) ) {
                $item[ 'strahlentherapie_ergebnis' ] = '3';
            }
            else if ( ( $strahlen_th[ 'endstatus' ] == 'abbr' ) &&
                      ( $strahlen_th[ 'endstatus_grund' ] == 'patw' ) ) {
                $item[ 'strahlentherapie_ergebnis' ] = '4';
            }
            $item[ 'keine_strahlentherapie' ] = '';
            $item[ 'gesamtdosis' ] = $strahlen_th[ 'gesamtdosis' ];
            $item[ 'boost' ] = $strahlen_th[ 'boostdosis' ];
            $item[ 'regionen' ] = $this->GetRegionen( $strahlen_th );
            $therapien[ 0 ][ 'strahlentherapien' ][] = $item;
            $th_ids[] = array(
                'tid' => $strahlen_th[ 'therapieplan_id' ],
                'art' => $strahlen_th[ 'art' ]
            );
        }
        // Alle Therapiepläne exportieren
        foreach( $extract_data[ 'therapieplaene' ] as $thp_sys ) {
            $item = array();
            if ( $thp_sys[ 'strahlen' ] == '1' ) {
                $abweicung_grund = '';
                $item[ 'strahlentherapie' ] = $this->IsStrahlenTherapie( $thp_sys,
                                                                         $abweicung_grund );
                $item[ 'strahlentherapie_intention' ] = $this->GetExportCode( 'intention',
                                                                              $thp_sys[ 'strahlen_intention' ],
                                                                              '' );
                $item[ 'strahlentherapie_beg_datum' ] = '';
                $item[ 'strahlentherapie_ende_datum' ] = '';
                $item[ 'strahlentherapie_ergebnis' ] = '';
                $item[ 'keine_strahlentherapie' ] = $this->GetExportCode( 'therapieplan_abweichung_grund',
                                                                          $abweicung_grund,
                                                                          '' );
                $item[ 'gesamtdosis' ] = '';
                $item[ 'boost' ] = '';
                $item[ 'regionen' ] = array(
                    '802'
                );
                $therapien[ 0 ][ 'strahlentherapien' ][] = $item;
            }
        }
        return $therapien;
    }


    /**
     *
     *
     * @access
     * @param $vorlage_id
     * @param $vorlage_art
     * @param $beginn
     * @param $ende
     * @param $intention
     * @param $endstatus
     * @param $endstatus_grund
     * @param $best_response
     * @param $abweichung_grund
     * @param $therapieplan_id
     * @return array
     */
    protected function CreateSystemischeTherapieItem( $vorlage_id,
                                                      $vorlage_art,
                                                      $beginn,
                                                      $ende,
                                                      $intention,
                                                      $endstatus,
                                                      $endstatus_grund,
                                                      $best_response,
                                                      $abweichung_grund,
                                                      $therapieplan_id )
    {
        $item = array();
        $item[ 'systemtherapie_typ' ] = $this->GetExportCode( 'therapieart',
                                                              $vorlage_art,
                                                              '' );
        $item[ 'systemtherapie_ausfuehrung' ] = '4';
        if ( ( strlen( $beginn ) > 0 ) &&
             ( strlen( $ende ) > 0 ) ) {
            $item[ 'systemtherapie_ausfuehrung' ] = '3';
        }
        $item[ 'systemtherapie_intention' ] = $this->GetExportCode( 'intention',
                                                                    $intention,
                                                                    '5' );
        $item[ 'systemtherapie_beg_datum' ] = $this->CheckDatum( $beginn );
        $item[ 'systemtherapie_ende_datum' ] = $this->CheckDatum( $ende );
        $item[ 'protokoll' ] = HCommon::TrimString( $this->GetTherapieVorlagenBezeichnung( $vorlage_id ),
                                                    200,
                                                    true );
        $item[ 'protokoll_art' ] = $this->GetProtokollCode( $vorlage_id,
                                                            $vorlage_art );
        $item[ 'anthrazyklin_gabe' ] = $this->GetAnthrazyklinGabe( $vorlage_id );
        $item[ 'taxan_gabe' ] = $this->GetTaxanGabe( $vorlage_id );
        $item[ 'systemtherapie_ergebnis' ] = '5';
        if ( ( $endstatus == 'plan' ) ||
             ( $endstatus == 'abw' ) ) {
            $item[ 'systemtherapie_ergebnis' ] = '1';
        }
        else if ( ( $endstatus == 'abbr' ) &&
                  ( ( $endstatus_grund == 'hn' ) ||
                    ( $endstatus_grund == 'nhn' ) ) ) {
            $item[ 'systemtherapie_ergebnis' ] = '2';
        }
        else if ( ( $endstatus == 'abbr' ) &&
                  ( $endstatus_grund == 'tod' ) ) {
            $item[ 'systemtherapie_ergebnis' ] = '3';
        }
        else if ( ( $endstatus == 'abbr' ) &&
                  ( $endstatus_grund == 'patw' ) ) {
            $item[ 'systemtherapie_ergebnis' ] = '4';
        }
        $item[ 'systemtherapie_erfolg' ] = '6';
        switch( $best_response ) {
            case 'CR' :
                $item[ 'systemtherapie_erfolg' ] = '1';
                break;
            case 'PR' :
                $item[ 'systemtherapie_erfolg' ] = '2';
                break;
            case 'SD' :
                $item[ 'systemtherapie_erfolg' ] = '3';
                break;
            case 'PD' :
                $item[ 'systemtherapie_erfolg' ] = '4';
                break;
            case 'NED' :
                $item[ 'systemtherapie_erfolg' ] = '6';
                break;
        }



            $item[ 'keine_systemtherapie' ] = '';
        if (strlen( $therapieplan_id ) > 0) {
            $abweichung_grund = $this->HasTherapieAbweichung( $therapieplan_id );
            if (count ($abweichung_grund) > 0) {
                $abweichung_grund = $abweichung_grund[0]['grund'];
            }
            if ( strlen ($abweichung_grund) > 0) {
                $item[ 'keine_systemtherapie' ] = $this->GetExportCode( 'therapieplan_abweichung_grund',
                    $abweichung_grund,
                    '' );
            }
        }
        return $item;
    }


    /**
     *
     *
     * @access
     * @param $therapieplan_id
     * @return array|bool
     */
    protected function HasTherapieAbweichung( $therapieplan_id )
    {
        $query = "
            SELECT
                th_a.grund

            FROM
                therapieplan_abweichung th_a

            WHERE
                th_a.therapieplan_id={$therapieplan_id}
                AND ( th_a.bezug_chemo='1'
                      OR th_a.bezug_immun='1'
                      OR th_a.bezug_ah='1'
                      OR th_a.bezug_andere='1' )

            ORDER BY
                th_a.datum DESC
        ";
        $result = sql_query_array( $this->m_db, $query );
        if ( count( $result ) > 0 ) {
            return $result;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @return array
     */
    protected function GetPathoHistologieSections( $parameters,
                                                   $extract_data )
    {
        $result = array();
        $result[ 0 ][ 'tumorstatus_id' ] = $extract_data[ 'tumoridentifikator' ];
        $result[ 0 ][ 'morpho_code' ] = '';
        $result[ 0 ][ 'morpho_text' ] = '';
        $tmp = HReports::GetMorphologieWithText( $this->m_db,
                                                 $extract_data[ 'erkrankung_id' ],
                                                 $extract_data[ 'diagnose_seite' ],
                                                 $extract_data[ 'start_date' ],
                                                 $extract_data[ 'end_date' ] );
        if ( ( false !== $tmp ) &&
             ( count( $tmp ) > 0 ) ) {
            $result[ 0 ][ 'morpho_code' ] = $tmp[ 'morphologie' ];
            $result[ 0 ][ 'morpho_text' ] = HCommon::TrimString( $tmp[ 'text' ],
                                                                 200,
                                                                 true );
        }
        $result[ 0 ][ 'topologie_code' ] = '';
        $result[ 0 ][ 'topologie_text' ] = '';
        $tmp = HReports::GetLokalisationWithText( $this->m_db,
                                                  $extract_data[ 'erkrankung_id' ],
                                                  $extract_data[ 'diagnose_seite' ],
                                                  $extract_data[ 'start_date' ],
                                                  $extract_data[ 'end_date' ] );
        if ( ( count( $tmp ) > 0 ) &&
             ( strlen( $tmp[ 'lokalisation' ] ) > 0 ) &&
             ( strlen( $tmp[ 'text' ] ) > 0 ) ) {
            $result[ 0 ][ 'topologie_code' ] = HCommon::TrimString( $tmp[ 'lokalisation' ],
                                                                    200,
                                                                    true );
            $result[ 0 ][ 'topologie_text' ] = HCommon::TrimString( $tmp[ 'text' ],
                                                                    200,
                                                                    true );
        }
        else {
            $tmp = HReports::GetDiagnoseToLokalisation( $this->m_db,
                                                        $extract_data[ 'diagnose' ] );
            if ( false !== $tmp ) {
                $result[ 0 ][ 'topologie_code' ] = HCommon::TrimString( $tmp[ 'lokalisation' ],
                                                                        200,
                                                                        true );
                $result[ 0 ][ 'topologie_text' ] = HCommon::TrimString( $tmp[ 'text' ],
                                                                        200,
                                                                        true );
            }
        }
        $result[ 0 ][ 'histo_datum' ] = $this->CheckDatum( $extract_data[ 'datum_sicherung' ] );
        $result[ 0 ][ 't' ] = $this->GetExportCode( 'pt',
                                                    $extract_data[ 't_postop' ],
                                                    '' );
        $result[ 0 ][ 'n' ] = $this->GetExportCode( 'pn',
                                                    $extract_data[ 'n_postop' ],
                                                    '' );
        $result[ 0 ][ 'm' ] = $this->GetExportCode( 'pm',
                                                    $extract_data[ 'm_postop' ],
                                                    '' );
        $result[ 0 ][ 'praefix' ] = 'p';
        if ( $extract_data[ 'tnm_praefix' ] == 'y' ) {
            $result[ 0 ][ 'praefix' ] = 'yp';
        }
        else if ( $extract_data[ 'tnm_praefix' ] == 'yr' ) {
            $result[ 0 ][ 'praefix' ] = 'yrp';
        }
        else if ( $extract_data[ 'tnm_praefix' ] == 'r' ) {
            $result[ 0 ][ 'praefix' ] = 'rp';
        }
        $result[ 0 ][ 'g' ] = $this->GetExportCode( 'g',
                                                    $extract_data[ 'g' ],
                                                    '' );
        $result[ 0 ][ 'r' ] = $this->GetExportCode( 'r',
                                                    $extract_data[ 'r' ],
                                                    '' );
        $result[ 0 ][ 'l' ] = $this->GetExportCode( 'l',
                                                    $extract_data[ 'l' ],
                                                    '' );
        $result[ 0 ][ 'v' ] = $this->GetExportCode( 'v',
                                                    $extract_data[ 'v' ],
                                                    '' );
        $result[ 0 ][ 'metastasen_ort' ] = $this->GetMetastasenOrte( $extract_data );
        $result[ 0 ][ 'tnm_version' ] = '503';
        return $result;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @param $section_uid
     * @return array
     */
    protected function CreateLaborSection( $parameters,
                                           $extract_data,
                                           &$section_uid )
    {
        $result = array();
        $result[ 'her2' ] = $this->GetExportCode( 'her2',
                                                  $extract_data[ 'her2' ],
                                                  '9' );
        $result[ 'her2_datum' ] = $this->CheckDatum( $extract_data[ 'datum_sicherung' ] );
        $result[ 'fish' ] = '';
        if ( $extract_data[ 'her2_fish_methode' ] == 'fish' ) {
            if ( $extract_data[ 'her2_fish' ] == '0' ) {
                $result[ 'fish' ] = '0';
            }
            else if ( ( $extract_data[ 'her2_fish' ] == '1' ) ||
                      ( $extract_data[ 'her2_fish' ] == '2' ) ) {
                $result[ 'fish' ] = '1';
            }
        }
        $result[ 'fish_datum' ] = $this->CheckDatum( $extract_data[ 'datum_sicherung' ] );
        $result[ 'cish' ] = '';
        if ( $extract_data[ 'her2_fish_methode' ] == 'cish' ) {
            if ( $extract_data[ 'her2_fish' ] == '0' ) {
                $result[ 'cish' ] = '0';
            }
            else if ( ( $extract_data[ 'her2_fish' ] == '1' ) ||
                ( $extract_data[ 'her2_fish' ] == '2' ) ) {
                $result[ 'cish' ] = '1';
            }
        }
        $result[ 'cish_datum' ] = $this->CheckDatum( $extract_data[ 'datum_sicherung' ] );
        $result[ 'er' ] = '';
        if ( $extract_data[ 'estro_urteil' ] == 'p' ) {
            $result[ 'er' ] = '21';
        }
        else if ( $extract_data[ 'estro_urteil' ] == 'n' ) {
            $result[ 'er' ] = '22';
        }
        $result[ 'er_datum' ] = $this->CheckDatum( $extract_data[ 'datum_sicherung' ] );
        $result[ 'er_score' ] = '';
        if ( strlen( $result[ 'er' ] ) > 0 ) {
            $result[ 'er_score' ] = '2';
        }
        $result[ 'pr' ] = '';
        if ( $extract_data[ 'prog_urteil' ] == 'p' ) {
            $result[ 'pr' ] = '21';
        }
        else if ( $extract_data[ 'prog_urteil' ] == 'n' ) {
            $result[ 'pr' ] = '22';
        }
        $result[ 'pr_datum' ] = $this->CheckDatum( $extract_data[ 'datum_sicherung' ] );
        $result[ 'pr_score' ] = '';
        if ( strlen( $result[ 'pr' ] ) > 0 ) {
            $result[ 'pr_score' ] = '2';
        }
        $result[ 'upa' ] = '';
        $result[ 'upa_datum' ] = '';
        $histo = $this->GetUpaHistologie( $extract_data );
        if ( false !== $histo ) {
            $result[ 'upa' ] = $histo[ 'upa' ];
            $result[ 'upa_datum' ] = $this->CheckDatum( $histo[ 'datum' ] );
        }
        $result[ 'pai1' ] = '';
        $result[ 'pai1_datum' ] = '';
        $histo = $this->GetPai1Histologie( $extract_data );
        if ( false !== $histo ) {
            $result[ 'pai1' ] = $histo[ 'pai1' ];
            $result[ 'pai1_datum' ] = $this->CheckDatum( $histo[ 'datum' ] );
        }
        $section_uid = 'LABOR_' . $extract_data[ 'tumoridentifikator' ] . $extract_data[ 'diagnose_seite' ];
        return $result;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @return array
     */
    protected function GetNachsorgeSections( $parameters,
                                             $extract_data )
    {
        $result = array();
        foreach( $extract_data[ 'nachsorgen' ] as $nachsorge ) {
            $item = array();
            $item[ 'nachsorge_id' ] = $nachsorge[ 'nachsorge_id' ];
            $item[ 'nachsorge_datum' ] = $this->CheckDatum( $nachsorge[ 'datum' ] );
            $item[ 'nachsorge_beurteilung' ] = '0';
            $rezidiv_status = '';
            if ( ( strlen( $extract_data[ 'abschluss_id' ] ) > 0 ) &&
                 ( $extract_data[ 'abschlussgrund' ] == 'tot' ) ) {
                $item[ 'nachsorge_beurteilung' ] = '6';
            }
            else if ( $this->HasTumorstatusWithRezidive( $extract_data, $rezidiv_status ) ) {
                $item[ 'nachsorge_beurteilung' ] = '3';
            }
            $item[ 'rezidiv_status' ] = '';
            if ( $item[ 'nachsorge_beurteilung' ] == '3' ) {
                if ( ( $extract_data[ 'rezidiv_lokal' ] == '1' ) &&
                     ( $extract_data[ 'rezidiv_metastasen' ] == '1' ) ) {
                    $item[ 'rezidiv_status' ] = '108';
                }
                else if ( $extract_data[ 'rezidiv_lokal' ] == '1' ) {
                    $item[ 'rezidiv_status' ] = '102';
                }
                else if ( $extract_data[ 'rezidiv_lk' ] == '1' ) {
                    $item[ 'rezidiv_status' ] = '104';
                }
                else if ( $extract_data[ 'rezidiv_metastasen' ] == '1' ) {
                    $item[ 'rezidiv_status' ] = '106';
                }
            }
            $item[ 'lost_follow_up' ] = '0';
            if ( ( strlen( $extract_data[ 'abschluss_id' ] ) > 0 ) &&
                 ( $extract_data[ 'abschlussgrund' ] == 'lost' ) ) {
                $item[ 'lost_follow_up' ] = '1';
            }
            $result[] = $item;
        }
        return $result;
    }


    /**
     *
     *
     * @access
     * @param $anamnesen
     * @return string
     */
    protected function GetKoerpergroesse( $anamnesen )
    {
        if ( count( $anamnesen ) > 0 ) {
            $anam = HReports::GetMaxElementByDate( $anamnesen, 4, null );
            if ( false !== $anam ) {
                return $anam[ 'groesse' ];
            }
        }
        return "";
    }


    /**
     *
     *
     * @access
     * @param $anamnesen
     * @return string
     */
    protected function GetKoerpergewicht( $anamnesen )
    {
        if ( count( $anamnesen ) > 0 ) {
            $anam = HReports::GetMaxElementByDate( $anamnesen, 5, null );
            if ( false !== $anam ) {
                return $anam[ 'gewicht' ];
            }
        }
        return "";
    }


    /**
     *
     *
     * @access
     * @param $extract_data
     * @return array
     */
    protected function GetBiopsieData( $extract_data, &$biopsie_methode )
    {
        $biopsien = array();
        $biopsie_methode = '6';
        $ops = HReports::GetRecordsWithValueAt( $extract_data[ 'operationen' ],
                                                8,
                                                '1' );
        foreach( $ops as $operation ) {
            $tmp = array(
                'datum'  => $operation[ 'beginn' ],
                'org_id' => $operation[ 'org_id' ],
                'ops_codes' => array()
            );
            foreach( $operation[ 'ops_codes' ] as $code ) {
                if ( in_array( $code[ 'prozedur_seite' ],
                               array( 'B', $extract_data[ 'diagnose_seite' ] ) ) ) {
                    $tmp[ 'ops_codes' ][] = $code[ 'prozedur' ];
                }
            }
            $biopsien[] = $tmp;
        }

        foreach( $extract_data[ 'untersuchungen' ] as $untersuchung ) {
            $ops_code = array();
            if ( in_array( $untersuchung[ 'art_seite' ],
                           array( 'B', $extract_data[ 'diagnose_seite' ] ) ) ) {
                $ops_code = array( $untersuchung[ 'ops_code' ] );
            }
            $tmp = array(
                'datum'  => $untersuchung[ 'datum' ],
                'org_id' => $untersuchung[ 'org_id' ],
                'ops_codes' => $ops_code
            );
            $biopsien[] = $tmp;
        }
        for( $i = 0; $i < 4; $i++ ) {
            foreach( $biopsien as $biopsie ) {
                foreach( $biopsie[ 'ops_codes' ] as $ops_code ) {
                    switch( $i ) {
                        case 0 :
                            if ( ( substr( $ops_code, 0, 5 ) == '1-e03' ) ||
                                 ( substr( $ops_code, 0, 7 ) == '5-870.7' ) ) {
                                $biopsie_methode = '1';
                                return $biopsie;
                            }
                            break;
                        case 1 :
                            if ( substr( $ops_code, 0, 5 ) == '1-e02' ) {
                                $biopsie_methode = '3';
                                return $biopsie;
                            }
                            break;
                        case 2 :
                            if ( ( $ops_code == '1-493.31' ) ||
                                 ( $ops_code == '1-493.32' ) ) {
                                $biopsie_methode = '4';
                                return $biopsie;
                            }
                            break;
                        case 3 :
                            if ( ( $ops_code == '1-494.31' ) ||
                                 ( $ops_code == '1-494.32' ) ) {
                                $biopsie_methode = '5';
                                return $biopsie;
                            }
                            break;
                    }
                }
            }
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $biopsie_record
     * @return string
     */
    protected function GetBiopsieSteuerung( $biopsie_record )
    {
        $codes_a = array(
            '1-e03.9',
            '1-e03.10',
            '1-e03.11',
            '1-e03.12',
            '1-e03.13'
        );
        $codes_b = array(
            '1-e03.1',
            '1-e03.2',
            '1-e03.x'
        );
        $codes_c = array(
            '1-e03.4',
            '1-e03.5',
            '1-e03.6',
            '1-e03.7',
            '1-e03.8'
        );
        $codes_d = array(
            '1-e03.14',
            '1-e03.15',
            '1-e03.16',
            '1-e03.17',
            '1-e03.18'
        );
        foreach( $biopsie_record[ 'ops_codes' ] as $ops_code ) {
            if ( in_array( $ops_code, $codes_a ) ) {
                return '1';
            }
            else if ( in_array( $ops_code, $codes_b ) ) {
                return '2';
            }
            else if ( in_array( $ops_code, $codes_c ) ) {
                return '3';
            }
            else if ( in_array( $ops_code, $codes_d ) ) {
                return '4';
            }
        }
        return '5';
    }


    /**
     *
     *
     * @access
     * @param $parameter
     * @param $biopsie_record
     * @return string
     */
    protected function IsBiopsieFromExtern( $parameter,
                                            $biopsie_record )
    {
        if ( strlen( $biopsie_record[ 'org_id' ] ) > 0 ) {
            if ( $biopsie_record[ 'org_id' ] == $parameter[ 'org_id' ] ) {
                return '0';
            }
            else {
                return '1';
            }
        }
        return '2';
    }


    /**
     *
     *
     * @access
     * @param $op
     * @return string
     */
    protected function IsTumorrelevanteOp( $op )
    {
        foreach( $op[ 'ops_codes' ] as $code ) {
            if ( ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-870' ) ||
                 ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-871' ) ||
                 ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-872' ) ||
                 ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-873' ) ||
                 ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-874' ) ||
                 ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-875' ) ||
                 ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-876' ) ||
                 ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-877' ) ) {
                return '1';
            }
        }
        if ( $op[ 'art_nachresektion' ] == '1' ) {
            return '1';
        }
        return '0';
    }


    /**
     *
     *
     * @access
     * @param $op
     * @param $extract_data
     * @return string
     */
    protected function GetOpLetalitaet( $op, $extract_data )
    {
        $result = '1';
        if ( $extract_data[ 'abschlussgrund' ] === 'tot' ) {
            if ( strlen( $extract_data[ 'todesdatum' ] ) > 0 ) {
                $diff = HReports::CalcDauer( $op[ 'beginn' ],
                                             $extract_data[ 'todesdatum' ] );
                if ( $diff <= 30 ) {
                    return '2';
                }
                else {
                    return '3';
                }
            }
            else {
                $result = '4';
            }
        }
        return $result;
    }


    /**
     *
     *
     * @access
     * @param $eingriff_id
     * @return string
     */
    protected function GetOperativerSicherheitsabstand( $eingriff_id )
    {
        $query = "
            SELECT
                h.histologie_id,
                h.datum,
                h.resektionsrand

            FROM
                histologie h

            WHERE
                h.eingriff_id={$eingriff_id}
                AND h.resektionsrand IS NOT NULL

            ORDER BY
                h.datum DESC
        ";
        $result = reset( sql_query_array( $this->m_db, $query ) );
        if ( false !== $result ) {
            if ( $result[ 'resektionsrand' ] < 1.0 ) {
                return '-1';
            }
            else if ( $result[ 'resektionsrand' ] >= 1.0 ) {
                return $result[ 'resektionsrand' ];
            }
        }
        return '-2';
    }


    /**
     *
     *
     * @access
     * @param $konferenz_id
     * @return string
     */
    protected function GetPatientenwunschBeo( $konferenz_id )
    {
        $query = "
            SELECT
                konf_p.patientenwunsch_beo

            FROM
               konferenz_patient konf_p

            WHERE
                konf_p.konferenz_id={$konferenz_id}
                AND konf_p.patientenwunsch_beo IS NOT NULL

            ORDER BY
                konf_p.datum DESC
        ";
        $result = reset( sql_query_array( $this->m_db, $query ) );
        if ( false !== $result ) {
            return $result[ 'patientenwunsch_beo' ];
        }
        return '';
    }


    /**
     *
     *
     * @access
     * @param $op
     * @param $extract_data
     * @return string
     */
    protected function GetBetWunsch( $op,
                                     $extract_data )
    {
        $query = "
            SELECT
                konf_pat.patientenwunsch_beo,
                konf_pat.datenstand_datum

            FROM
                konferenz_patient konf_pat
                INNER JOIN konferenz konf  ON konf_pat.konferenz_id = konf.konferenz_id
                                              AND konf.datum <= '{$op[ 'beginn' ]}'

            WHERE
                konf_pat.erkrankung_id = {$extract_data['erkrankung_id']}

            ORDER BY
                konf_pat.datenstand_datum DESC

            LIMIT 0, 1
        ";
        $result = reset( sql_query_array( $this->m_db, $query ) );
        if ( false !== $result ) {
            $wunsch = '2';
            if ( $result[ 'patientenwunsch_beo' ] == '0' ) {
                $wunsch = '0';
            }
            else if ( $result[ 'patientenwunsch_beo' ] == '1' ) {
                $wunsch = '1';
            }
            return $wunsch;
        }
        return '2';
    }


    /**
     *
     *
     * @access
     * @param $eingriff_id
     * @return string|unknown_type
     */
    protected function GetLokalePathohistoRadikalitaet( $eingriff_id )
    {
        $query = "
            SELECT
                h.histologie_id,
                h.datum,
                h.r

            FROM
                histologie h

            WHERE
                h.eingriff_id={$eingriff_id}
                AND h.r IS NOT NULL

            ORDER BY
                h.datum DESC
        ";
        $result = reset( sql_query_array( $this->m_db, $query ) );
        if ( false !== $result ) {
            return $this->GetExportCode( 'r',
                                         $result[ 'r' ],
                                         '' );
        }
        return '';
    }


    /**
     *
     *
     * @access
     * @param $eingriff_id
     * @return array
     */
    protected function GetKomplikationen( $eingriff_id )
    {
        $komplikationen = array();

        $query = "
            SELECT
                kompl.komplikation_id,
                kompl.datum,
                kompl.komplikation,
                kompl.revisionsoperation

            FROM
                komplikation kompl

            WHERE
                kompl.eingriff_id={$eingriff_id}

            ORDER BY
                kompl.datum DESC
        ";
        $result = sql_query_array( $this->m_db, $query );
        if ( ( false !== $result ) &&
             ( count( $result ) > 0 ) ) {
            foreach( $result as $row ) {
                $komplikationsgrad = '4';
                if ( ( strlen( $row[ 'komplikation' ] ) > 0 ) &&
                     ( $row[ 'komplikation' ] == 'tod' ) ) {
                    $komplikationsgrad = '3';
                }
                else if ( strlen( $row[ 'revisionsoperation' ] ) > 0 ) {
                    if ( $row[ 'revisionsoperation' ] == '0' ) {
                        $komplikationsgrad = '1';
                    }
                    else if ( $row[ 'revisionsoperation' ] == '1' ) {
                        $komplikationsgrad = '2';
                    }
                }
                $komplikationen[] = array(
                    'op_komplikation' => HCommon::TrimString( $this->GetLBasicBez( 'komplikation',
                                                                                   $row[ 'komplikation' ] ),
                                                              200,
                                                              true ),
                    'op_komplikationsgrad' => $komplikationsgrad,
                    'op_komplikationsart' => $this->GetExportCode( 'komplikation',
                                                                   $row[ 'komplikation' ],
                                                                   '5' )
                );
            }
        }
        else {
            $komplikationen[] = array(
                'op_komplikation' => '',
                'op_komplikationsgrad' => '5',
                'op_komplikationsart' => '0'
            );
        }
        return $komplikationen;
    }


    /**
     *
     *
     * @access
     * @param $op
     * @return array
     */
    protected function GetLymphData( $op )
    {
        $data = array();

        $data[ 'anz_entf_lymphknoten' ] = '';
        $data[ 'anz_entf_lymphknoten_pos' ] = '';
        $data[ 'anz_entf_sentinel' ] = '';
        $data[ 'anz_entf_sentinel_pos' ] = '';
        $query = "
            SELECT
                h.histologie_id,
                h.datum,
                h.lk_entf

            FROM
                histologie h

            WHERE
                h.eingriff_id={$op[ 'eingriff_id' ]}
                AND h.lk_entf IS NOT NULL

            ORDER BY
                h.datum DESC
        ";
        $result = reset( sql_query_array( $this->m_db, $query ) );
        if ( false !== $result ) {
            $data[ 'anz_entf_lymphknoten' ] = $result[ 'lk_entf' ];
        }
        $query = "
            SELECT
                h.histologie_id,
                h.datum,
                h.lk_bef_makro,
                h.lk_bef_mikro

            FROM
                histologie h

            WHERE
                h.eingriff_id={$op[ 'eingriff_id' ]}
                AND h.lk_bef_makro IS NOT NULL
                AND h.lk_bef_mikro IS NOT NULL

            ORDER BY
                h.datum DESC
        ";
        $result = reset( sql_query_array( $this->m_db, $query ) );
        if ( false !== $result ) {
            $data[ 'anz_entf_lymphknoten_pos' ] = intval( $result[ 'lk_bef_makro' ] ) +
                                                  intval( $result[ 'lk_bef_mikro' ] );
        }
        $query = "
            SELECT
                h.histologie_id,
                h.datum,
                h.lk_sentinel_entf

            FROM
                histologie h

            WHERE
                h.eingriff_id={$op[ 'eingriff_id' ]}
                AND h.lk_sentinel_entf IS NOT NULL

            ORDER BY
                h.datum DESC
        ";
        $result = reset( sql_query_array( $this->m_db, $query ) );
        if ( false !== $result ) {
            $data[ 'anz_entf_sentinel' ] = $result[ 'lk_sentinel_entf' ];
        }
        $query = "
            SELECT
                h.histologie_id,
                h.datum,
                h.lk_sentinel_bef

            FROM
                histologie h

            WHERE
                h.eingriff_id={$op[ 'eingriff_id' ]}
                AND h.lk_sentinel_bef IS NOT NULL

            ORDER BY
                h.datum DESC
        ";
        $result = reset( sql_query_array( $this->m_db, $query ) );
        if ( false !== $result ) {
            $data[ 'anz_entf_sentinel_pos' ] = $result[ 'lk_sentinel_bef' ];
        }
        return $data;
    }


    /**
     *
     *
     * @access
     * @param $op
     * @return string
     */
    protected function GetBrustRekonstruktion( $op )
    {
        foreach( $op[ 'ops_codes' ] as $code ) {
            if ( ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-883' ) ||
                 ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-885' ) ||
                 ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-886' ) ||
                 ( substr( $code[ 'prozedur' ], 0, 7 ) == '5-876.1' ) ||
                 ( substr( $code[ 'prozedur' ], 0, 7 ) == '5-876.2' ) ||
                 ( substr( $code[ 'prozedur' ], 0, 7 ) == '5-876.3' ) ||
                 ( substr( $code[ 'prozedur' ], 0, 7 ) == '5-889.2' ) ||
                 ( substr( $code[ 'prozedur' ], 0, 7 ) == '5-889.3' ) ||
                 ( substr( $code[ 'prozedur' ], 0, 7 ) == '5-889.4' ) ||
                 ( substr( $code[ 'prozedur' ], 0, 7 ) == '5-889.5' ) ||
                 ( $code[ 'prozedur' ] == '5-905.0a' ) ) {
                return '1';
            }
        }
        return '0';
    }


    /**
     *
     *
     * @access
     * @param $op
     * @return string
     */
    protected function GetBetNichtDurchgefuehrt( $op )
    {
        if ( strlen( $op[ 'therapieplan_id' ] ) != 0 ) {
            // Darf kein OPS-Code aus den zwei Bereichen sein!
            foreach( $op[ 'ops_codes' ] as $code ) {
                if ( ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-870' ) ||
                     ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-871' ) ) {
                    return '';
                }
            }
            $query = "
                SELECT
                    tpa.grund

                FROM
                    therapieplan tp
                    LEFT JOIN therapieplan_abweichung tpa ON tpa.therapieplan_id=tp.therapieplan_id
					                                         AND tpa.bezug_eingriff='1'

                WHERE
                    tp.therapieplan_id={$op[ 'therapieplan_id' ]}
                    AND tp.op_art_brusterhaltend='1'

                LIMIT 0, 1
            ";
            $result = reset( sql_query_array( $this->m_db, $query ) );
            if ( false !== $result ) {
                return $this->GetExportCode( 'therapieplan_abweichung_grund',
                                             $result[ 'grund' ],
                                             '' );
            }
        }
        return '';
    }


    /**
     *
     *
     * @access
     * @param $op
     * @return string
     */
    protected function MastektomieNichtDurchgefuehrt( $op )
    {
        if ( strlen( $op[ 'therapieplan_id' ] ) != 0 ) {
            // Darf kein OPS-Code aus den zwei Bereichen sein!
            foreach( $op[ 'ops_codes' ] as $code ) {
                if ( ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-872' ) ||
                     ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-873' ) ||
                     ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-874' ) ||
                     ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-875' ) ||
                     ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-876' ) ||
                     ( substr( $code[ 'prozedur' ], 0, 5 ) == '5-877' ) ) {
                    return '';
                }
            }
            $query = "
                SELECT
                    tpa.grund

                FROM
                    therapieplan tp
                    LEFT JOIN therapieplan_abweichung tpa ON tpa.therapieplan_id=tp.therapieplan_id
                                                             AND tpa.bezug_eingriff='1'

                WHERE
                    tp.therapieplan_id={$op[ 'therapieplan_id' ]}
                    AND tp.op_art_mastektomie='1'

                LIMIT 0, 1
            ";
            $result = reset( sql_query_array( $this->m_db, $query ) );
            if ( false !== $result ) {
                return $this->GetExportCode( 'therapieplan_abweichung_grund',
                    $result[ 'grund' ],
                    '' );
            }
        }
        return '';
    }


    /**
     *
     *
     * @access
     * @param $vorlage_id
     * @param $vorlage_art
     * @return string
     */
    protected function GetProtokollCode( $vorlage_id,
                                         $vorlage_art)
    {
        $wirkstoffe = $this->GetWirkstoffe( $vorlage_id );
        if ( $vorlage_art == 'cst' ) {
            return '106';
        }
        else if ( ( ( $vorlage_art == 'c' ) ||
                    ( $vorlage_art == 'ci' ) ) &&
                  ( count( $wirkstoffe ) > 1 ) ) {
            return '102';
        }
        else if ( ( ( $vorlage_art == 'c' ) ||
                    ( $vorlage_art == 'ci' ) ) &&
                  ( count( $wirkstoffe ) < 2 ) ) {
            return '101';
        }
        else if ( ( $vorlage_art == 'i' ) ||
                  ( $vorlage_art == 'ist' ) ) {
            return '112';
        }
        else if ( ( $vorlage_art == 'ah' ) &&
                    $this->WirkstoffeInArray( $wirkstoffe,
                                              array(
                                                  'tamoxifen',
                                                  'toremifen',
                                                  'fulvestrant',
                                                  'raloxifen'
                                              ) ) ) {
            return '107';
        }
        else if ( ( $vorlage_art == 'ah' ) &&
                    $this->WirkstoffeInArray( $wirkstoffe,
                                              array(
                                                  'aminoglutethimid',
                                                  'anastrozol',
                                                  'exemestan',
                                                  'gemcitabin',
                                                  'letrozol',
                                                  'metenolon'
                                              ) ) ) {
            return '108';
        }
        else if ( ( $vorlage_art == 'ah' ) &&
                    $this->WirkstoffeInArray( $wirkstoffe,
                                              array(
                                                  'mifepriston'
                                              ) ) ) {
            return '109';
        }
        else if ( ( $vorlage_art == 'ah' ) &&
                    $this->WirkstoffeInArray( $wirkstoffe,
                        array(
                             'buserelin',
                             'goserelin',
                             'leuprorelin',
                             'triptorelin',
                             'lhrhanaloga'
                        ) ) ) {
            return '110';
        }
        else if ( ( $vorlage_art == 'son' ) ||
                  ( $vorlage_art == 'sonstr' ) ) {
            return '113';
        }
        return '';
    }


    /**
     *
     *
     * @access
     * @param $wirkstoffe
     * @param $wirkstoffe_search
     * @return bool
     */
    protected function WirkstoffeInArray( $wirkstoffe,
                                          $wirkstoffe_search )
    {
        if ( is_array( $wirkstoffe ) &&
             is_array( $wirkstoffe_search ) ) {
            foreach( $wirkstoffe as $wirkstoff ) {
                if ( in_array( $wirkstoff, $wirkstoffe_search ) ) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $vorlage_id
     * @return string
     */
    protected function GetAnthrazyklinGabe( $vorlage_id )
    {
        $wirkstoffe = $this->GetWirkstoffe( $vorlage_id );
        if ( count( $wirkstoffe ) == 0 ) {
            return '2';
        }
        if ( $this->WirkstoffeInArray( $wirkstoffe,
             array(
                 'adriamycin',
                 'doxorubicin',
                 'epirubicin',
                 'doxorubicinpeg',
                 'doxorubicinnpeg'
             ) ) ) {
            return '1';
        }
        return '0';
    }


    /**
     *
     *
     * @access
     * @param $vorlage_id
     * @return string
     */
    protected function GetTaxanGabe( $vorlage_id )
    {
        $wirkstoffe = $this->GetWirkstoffe( $vorlage_id );
        if ( count( $wirkstoffe ) == 0 ) {
            return '2';
        }
        if ( $this->WirkstoffeInArray( $wirkstoffe,
             array(
                 'docetaxel',
                 'paclitaxel'
             ) ) ) {
            return '1';
        }
        return '0';
    }


    /**
     *
     *
     * @access
     * @param $extract_data
     * @return bool
     */
    protected function HasTumorstatusWithRezidive( $extract_data )
    {
        $query = "
            SELECT
                *

            FROM
                tumorstatus ts

            WHERE
                ts.erkrankung_id = {$extract_data[ 'erkrankung_id' ]}
                AND ts.anlass LIKE 'r%'
        ";
        $result = sql_query_array( $this->m_db, $query );
        if ( ( false !== $result ) &&
             ( count( $result ) > 0 ) ) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $extract_data
     * @return string
     */
    protected function BuildUicc( $extract_data )
    {
        if ( strlen( $extract_data[ 'form_uicc' ] ) > 0 ) {
            return $this->GetExportCode( 'uicc',
                                         $extract_data[ 'form_uicc' ],
                                         '' );
        }
        $query = "
            SELECT
                ts.t,
                ts.n,
                ts.m

            FROM
                tumorstatus ts

            WHERE
                ts.erkrankung_id = {$extract_data[ 'erkrankung_id' ]}
                AND ts.datum_sicherung BETWEEN '{$extract_data[ 'start_date' ]}' AND '{$extract_data[ 'end_date' ]}'
                AND ts.diagnose_seite IN ( 'B', '{$extract_data[ 'diagnose_seite' ]}' )
                AND ts.anlass='p'
                AND ts.t IS NOT NULL
                AND ts.n IS NOT NULL
                AND ts.m IS NOT NULL

            ORDER BY
                ts.datum_sicherung DESC,
                ts.sicherungsgrad ASC,
                ts.datum_beurteilung DESC

            LIMIT 0, 1
        ";
        $result = reset( sql_query_array( $this->m_db, $query ) );
        if ( false !== $result ) {
            $t = substr( $result[ 't' ], 1 );
            $n = substr( $result[ 'n' ], 1 );
            $m = substr( $result[ 'm' ], 1 );
            if ( ( $t == 'Tis' ) &&
                 ( $n == 'N0' ) &&
                 ( $m == 'M0' ) ) {
                return '1';
            }
            else if ( ( ( $t == 'T1' ) ||
                        ( $t == 'T1mic' ) ) &&
                      ( $n == 'N0' ) &&
                      ( $m == 'M0' ) ) {
                return '2';
            }
            else if ( ( ( $t == 'T0' ) ||
                        ( $t == 'T1' ) ||
                        ( $t == 'T1mic' ) ) &&
                      ( $n == 'N1mi' ) &&
                      ( $m == 'M0' ) ) {
                return '3';
            }
            else if ( ( ( $t == 'T0' ) ||
                        ( $t == 'T1' ) ||
                        ( $t == 'T1mic' ) ) &&
                      ( $n == 'N1' ) &&
                      ( $m == 'M0' ) ) {
                return '4';
            }
            else if ( ( $t == 'T2' ) &&
                      ( $n == 'N0' ) &&
                      ( $m == 'M0' ) ) {
                return '4';
            }
            else if ( ( $t == 'T2' ) &&
                      ( $n == 'N1' ) &&
                      ( $m == 'M0' ) ) {
                return '5';
            }
            else if ( ( $t == 'T3' ) &&
                      ( $n == 'N0' ) &&
                      ( $m == 'M0' ) ) {
                return '5';
            }
            else if ( ( ( $t == 'T0' ) ||
                        ( $t == 'T1' ) ||
                        ( $t == 'T1mic' ) ||
                        ( $t == 'T2' ) ) &&
                      ( $n == 'N2' ) &&
                      ( $m == 'M0' ) ) {
                return '6';
            }
            else if ( ( $t == 'T3' ) &&
                      ( ( $n == 'N1' ) ||
                        ( $n == 'N2' ) ) &&
                      ( $m == 'M0' ) ) {
                return '6';
            }
            else if ( ( $t == 'T4' ) &&
                      ( ( $n == 'N0' ) ||
                        ( $n == 'N1' ) ||
                        ( $n == 'N2' ) ) &&
                      ( $m == 'M0' ) ) {
                return '7';
            }
            else if ( ( $n == 'N3' ) &&
                      ( $m == 'M0' ) ) {
                return '8';
            }
            else if ( $m == 'M1' ) {
                return '9';
            }
        }
        return '';
    }


    /**
     *
     *
     * @access
     * @param $strahlen_th
     * @return array
     */
    protected function GetRegionen( $strahlen_th )
    {
        $regionen = array();

        if ( ( ( $strahlen_th[ 'ziel_mamma_r' ] == '1' ) ||
               ( $strahlen_th[ 'ziel_mamma_l' ] == '1' ) ) &&
             ( ( $strahlen_th[ 'ziel_axilla_r' ] == '1' ) ||
               ( $strahlen_th[ 'ziel_axilla_l' ] == '1' ) ) ) {
            $regionen[] = '203';
        }
        if ( ( ( $strahlen_th[ 'ziel_mamma_r' ] == '1' ) ||
               ( $strahlen_th[ 'ziel_mamma_l' ] == '1' ) ) &&
             ( $strahlen_th[ 'ziel_lk_supra' ] == '1' ) ) {
            $regionen[] = '202';
        }
        if ( ( $strahlen_th[ 'ziel_mamma_r' ] == '1' ) ||
             ( $strahlen_th[ 'ziel_mamma_l' ] == '1' ) ) {
            $regionen[] = '201';
        }
        if ( ( $strahlen_th[ 'ziel_brustwand_r' ] == '1' ) ||
             ( $strahlen_th[ 'ziel_brustwand_l' ] == '1' ) ) {
            $regionen[] = '301';
        }
        if ( ( substr( $strahlen_th[ 'ziel_sonst_detail' ], 0, 3 ) == 'C34' ) ||
             ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C77.1' ) ) {
            $regionen[] = '302';
        }
        if ( ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C71.9' ) ||
             ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C76.0' ) ) {
            $regionen[] = '100';
        }
        if ( ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C41.4' ) ||
             ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C47.5' ) ||
             ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C49.5' ) ||
             ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C76.3' ) ||
             ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C77.5' ) ) {
            $regionen[] = '501';
        }
        if ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C76.2' ) {
            $regionen[] = '505';
        }
        if ( ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C60.1' ) ||
             ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C40.1' ) ||
             ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C47.1' ) ||
             ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C49.1' ) ||
             ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C77.3' ) ) {
            $regionen[] = '601';
        }
        if ( ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C40.2' ) ||
             ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C40.3' ) ||
             ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C47.2' ) ||
             ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C49.2' ) ||
             ( $strahlen_th[ 'ziel_sonst_detail' ] == 'C77.4' ) ) {
            $regionen[] = '602';
        }
        if ( count( $regionen ) == 0 ) {
            $regionen[] = '701';
        }
        return $regionen;
    }


    /**
     *
     *
     * @access
     * @param $thp_sys
     * @param $abweicung_grund
     * @return string
     */
    protected function IsStrahlenTherapie( $thp_sys,
                                           &$abweicung_grund )
    {
        $abweicung_grund = '';
        $query = "
            SELECT
                th_abw.bezug_strahlen,
                th_abw.grund

            FROM
                therapieplan_abweichung th_abw

            WHERE
                th_abw.therapieplan_id = {$thp_sys[ 'therapieplan_id' ]}
                AND th_abw.bezug_strahlen='1'

            ORDER BY
                th_abw.datum DESC
        ";
        $result = sql_query_array( $this->m_db, $query );
        if ( ( false !== $result ) &&
             ( count( $result ) > 0 ) ) {
            $abweicung_grund = $result[ 0 ][ 'grund' ];
            return '0';
        }
        return '1';
    }


    /**
     *
     *
     * @access
     * @param $extract_data
     * @return string
     */
    protected function GetMetastasenOrte( $extract_data )
    {
        $lokalisationen_str = '';

        $query = "
            SELECT
                ts_m.lokalisation

            FROM
                tumorstatus ts
                INNER JOIN tumorstatus_metastasen ts_m ON ts.tumorstatus_id=ts_m.tumorstatus_id
                                                          AND ts_m.lokalisation IS NOT NULL

            WHERE
                ts.erkrankung_id = {$extract_data[ 'erkrankung_id' ]}
                AND ts.datum_sicherung BETWEEN '{$extract_data[ 'start_date' ]}' AND '{$extract_data[ 'end_date' ]}'
                AND ts.diagnose_seite IN ( 'B', '{$extract_data[ 'diagnose_seite' ]}' )
                AND ts.anlass='p'

            ORDER BY
                ts.datum_sicherung DESC,
                ts.sicherungsgrad ASC,
                ts.datum_beurteilung DESC

            LIMIT 0, 1
        ";
        $result = end( sql_query_array( $this->m_db, $query ) );
        if ( false !== $result ) {
            $tmp = $this->GetMetastasenOrt( $result[ 'lokalisation' ] );
            if ( strlen( $tmp ) > 0 ) {
                $lokalisationen_str = $tmp;
            }
        }
        return $lokalisationen_str;
    }


    /**
     *
     *
     * @access
     * @param $code
     * @return string
     */
    protected function GetMetastasenOrt( $code )
    {
        if ( isset( $this->m_metastasis_codings[ $code ] ) ) {
            return $this->m_metastasis_codings[ $code ];
        }
        return '';
    }


    /**
     *
     *
     * @access
     * @param $extract_data
     * @return array|bool
     */
    protected function GetUpaHistologie( $extract_data )
    {
        $histologie = HReports::GetMaxElementByDate( $extract_data[ 'alle_histologien' ],
                                                     13,
                                                     null );
        if ( false !== $histologie ) {
            return array(
                'upa' => $histologie[ 'upa' ],
                'datum' => $histologie[ 'datum' ]
            );
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $extract_data
     * @return array|bool
     */
    protected function GetPai1Histologie( $extract_data )
    {
        $histologie = HReports::GetMaxElementByDate( $extract_data[ 'alle_histologien' ],
                                                     14,
                                                     null );
        if ( false !== $histologie ) {
            return array(
                'pai1' => $histologie[ 'pai1' ],
                'datum' => $histologie[ 'datum' ]
            );
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $therapieplan_id
     * @return array
     */
    protected function GetOpsWithTherapyPlan( $therapieplan_id )
    {
        $query = "
            SELECT
                *

            FROM
                eingriff e

            WHERE
                e.erkrankung

            ORDER BY
                ts.datum_sicherung DESC,
                ts.sicherungsgrad ASC,
                ts.datum_beurteilung DESC

            LIMIT 0, 1
        ";
        $result = end( sql_query_array( $this->m_db, $query ) );
        return array();
    }


    /**
     *
     *
     * @access
     * @param $therapy_plans
     * @return array
     */
    protected function PreparingTherapyPlans( $therapy_plans,
                                              $strahlen_therapien,
                                              $systemische_therapien )
    {
        $result = array();
        foreach( $therapy_plans as $tp ) {
            // Alle Strahlentherapien durchgehen...
            foreach( $strahlen_therapien as $sth_th ) {
                if ( ( $sth_th[ 'therapieplan_id' ] == $tp[ 'therapieplan_id' ] ) &&
                     ( $tp[ 'strahlen' ] == '1' ) ) {
                    $tp[ 'strahlen' ] = '';
                    $tp[ 'strahlen_intention' ] = '';
                }
            }
            // Alle anderen Therapien durchgehen...
            foreach( $systemische_therapien as $sys_th ) {
                if ( $sys_th[ 'therapieplan_id' ] == $tp[ 'therapieplan_id' ] ) {
                    if ( $sys_th[ 'vorlage_therapie_id' ] == $tp[ 'chemo_id' ] ) {
                        $tp[ 'chemo' ] = '';
                        $tp[ 'chemo_id' ] = '';
                        $tp[ 'chemo_intention' ] = '';
                    }
                    else if ( $sys_th[ 'vorlage_therapie_id' ] == $tp[ 'immun_id' ] ) {
                        $tp[ 'immun' ] = '';
                        $tp[ 'immun_id' ] = '';
                        $tp[ 'immun_intention' ] = '';
                    }
                    else if ( $sys_th[ 'vorlage_therapie_id' ] == $tp[ 'ah_id' ] ) {
                        $tp[ 'ah' ] = '';
                        $tp[ 'ah_id' ] = '';
                        $tp[ 'ah_intention' ] = '';
                    }
                    else if ( $sys_th[ 'vorlage_therapie_id' ] == $tp[ 'andere_id' ] ) {
                        $tp[ 'andere' ] = '';
                        $tp[ 'andere_id' ] = '';
                        $tp[ 'andere_intention' ] = '';
                    }
                }
            }
            $result[] = $tp;
        }
        return $result;
    }

}

?>
