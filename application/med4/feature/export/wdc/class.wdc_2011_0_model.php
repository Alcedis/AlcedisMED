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
require_once( 'class.wdc_2011_0_serialiser.php' );
require_once( 'core/class/report/helper.reports.php' );
require_once( 'feature/export/base/helper.common.php' );

/**
 * Class Cwdc_2011_0_Model
 */
class Cwdc_2011_0_Model extends CExportDefaultModel
{
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
     * @access
     * @var array
     */
    protected $m_complication_codings = array(
        "az"        =>    "5",
        "azi"       =>    "5",
        "ane"       =>    "5",
        "ani"       =>    "4",
        "aninr"     =>    "4",
        "andorg"    =>    "5",
        "ate"       =>    "5",
        "atn"       =>    "5",
        "bsr"       =>    "5",
        "bs"        =>    "5",
        "bf"        =>    "5",
        "bik"       =>    "5",
        "blase"     =>    "5",
        "blut"      =>    "3",
        "blutv"     =>    "3",
        "bsi"       =>    "5",
        "ci"        =>    "5",
        "darm"      =>    "5",
        "deku"      =>    "5",
        "epp"       =>    "5",
        "emo"       =>    "5",
        "f38"       =>    "5",
        "fis"       =>    "5",
        "gns"       =>    "5",
        "gefa"      =>    "5",
        "gerinnung" =>    "5",
        "hes"       =>    "5",
        "Harnver"   =>    "5",
        "hed"       =>    "5",
        "ilu"       =>    "5",
        "idah"      =>    "5",
        "kk"        =>    "5",
        "le"        =>    "5",
        "lzb"       =>    "5",
        "milz"      =>    "5",
        "myo"       =>    "5",
        "nbl"       =>    "3",
        "ndo"       =>    "5",
        "ndt"       =>    "5",
        "nsw"       =>    "5",
        "narkosz"   =>    "5",
        "nerv"      =>    "5",
        "nerv_erh"  =>    "5",
        "opn"       =>    "3",
        "orthost"   =>    "5",
        "pank"      =>    "5",
        "per"       =>    "5",
        "pla"       =>    "5",
        "ple"       =>    "5",
        "pneu"      =>    "5",
        "pif"       =>    "5",
        "rf"        =>    "5",
        "rektv"     =>    "5",
        "ri"        =>    "5",
        "sa"        =>    "5",
        "sep"       =>    "5",
        "sal"       =>    "5",
        "sth"       =>    "5",
        "sha"       =>    "5",
        "sif"       =>    "5",
        "son"       =>    "5",
        "sdys"      =>    "5",
        "sten"      =>    "5",
        "skr"       =>    "5",
        "thro"      =>    "5",
        "tbt"       =>    "5",
        "tod"       =>    "5",
        "Tueinris"  =>    "5",
        "uf"        =>    "5",
        "Urethra"   =>    "5",
        "uterus"    =>    "5",
        "vddhp"     =>    "5",
        "vdno"      =>    "5",
        "wta"       =>    "5",
        "wd"        =>    "5",
        "wuheilst"  =>    "2",
        "wi"        =>    "1",
        "wa1"       =>    "1",
        "wa2"       =>    "1",
        "wa3"       =>    "1",
        "wctc2"     =>    "1",
        "wn"        =>    "5",
        "ls"        =>    "5",
        "lv"        =>    "5",
        "mn"        =>    "5",
        "mv"        =>    "5",
    );


    /**
     *
     */
    public function __construct()
    {
    }


    /**
     * Overrides from class CExportDefaultModel
     *
     * @access
     * @param $parameters
     * @param $wrapper
     * @param $export_record
     * @return void
     */
    public function ExtractData( $parameters, $wrapper, &$export_record )
    {
        $relevantSelectWhere = "ts.erkrankung_id = t.erkrankung_id " .
                               "AND ts.diagnose_seite IN ('B', t.diagnose_seite) " .
                               "AND ts.anlass = t.anlass";
        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;
        $wrapper->SetRangeDate( $parameters[ 'datum_von' ], $parameters[ 'datum_bis' ] );
        $wrapper->SetErkrankungen( 'd' );
        $wrapper->UsePrimaryCasesOnly();
        $wrapper->DoNotUseEkrMeldungsCheck();
        $wrapper->SetDiagnosen( "diagnose LIKE 'C18%' " .
                                "OR diagnose LIKE 'C19%' " .
                                "OR diagnose LIKE 'C20%' " .
                                "OR diagnose LIKE 'D01.0%' " .
                                "OR diagnose LIKE 'D01.1%' " .
                                "OR diagnose LIKE 'D01.2%' " );


        $wrapper->SetAdditionalJoins( array(
            "LEFT JOIN untersuchung exam           ON s.form = 'untersuchung'            AND exam.untersuchung_id           = s.form_id",
            "LEFT JOIN komplikation comp           ON s.form = 'komplikation'            AND comp.komplikation_id           = s.form_id",
            "LEFT JOIN therapieplan tplan          ON s.form = 'therapieplan'            AND tplan.therapieplan_id          = s.form_id",
            "LEFT JOIN therapieplan_abweichung tPA ON s.form = 'therapieplan_abweichung' AND tPA.therapieplan_abweichung_id = s.form_id"
        ) );

        $wrapper->SetAdditionalSelects( array(
            "p.kv_nr                                                        AS 'versicherungsnummer'",
            "p.titel                                                        AS 'titel'",
            "p.geburtsname                                                  AS 'geburtsname'",
            "p.strasse                                                      AS 'strasse'",
            "p.hausnr                                                       AS 'hausnummer'",
            "p.staat                                                        AS 'land'",
            "p.plz                                                          AS 'plz'",
            "p.ort                                                          AS 'wohnort'",
            "p.kv_iknr                                                      AS 'ikassenschluessel'",
            "(SELECT name FROM l_ktst WHERE iknr = p.kv_iknr LIMIT 1)       AS 'kassenname'",
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

                   LIMIT 1 )    AS 'tnm_praefix'",
        ) );

        $wrapper->SetAdditionalFields( array(
            "sit.tnm_praefix                                                AS 'tnm_praefix'",
            "sit.versicherungsnummer                                        AS 'versicherungsnummer'",
            "sit.patient_nr                                                 AS 'referenznr'",
            "sit.titel                                                      AS 'titel'",
            "sit.geburtsname                                                AS 'geburtsname'",
            "sit.strasse                                                    AS 'strasse'",
            "sit.hausnummer                                                 AS 'hausnummer'",
            "sit.land                                                       AS 'land'",
            "sit.plz                                                        AS 'plz'",
            "sit.wohnort                                                    AS 'wohnort'",
            "sit.kassenname                                                 AS 'kassenname'",
            "sit.tnm_praefix                                                AS 'tnm_praefix'",
            "IFNULL(
                IFNULL(
                    MIN(IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1', s.form_date, NULL)),
                    MIN(h.datum)
                ),
                IF(
                    sit.anlass LIKE 'r%',
                        IF(sit.start_date = '0000-00-00', sit.start_date_rezidiv, sit.start_date),
                        NULL
                )
            )                                                                      AS 'bezugsdatum'",
            "IF( MIN( exam.untersuchung_id ) IS NOT NULL,
                GROUP_CONCAT( DISTINCT
                    IF( exam.untersuchung_id IS NOT NULL,
                        CONCAT_WS( '{$separator_col}',
                            IFNULL(exam.untersuchung_id, ''),
                            IFNULL(exam.datum, ''),
                            IFNULL(exam.art, ''),
                            IFNULL(exam.koloskopie_vollstaendig, '')
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
            )                                                               AS 'examination'",

            "IF( MIN( comp.komplikation_id ) IS NOT NULL,
                GROUP_CONCAT( DISTINCT
                    IF( comp.komplikation_id IS NOT NULL,
                        CONCAT_WS( '{$separator_col}',
                            IFNULL(comp.komplikation_id, ''),
                            IFNULL(comp.datum, ''),
                            IFNULL(comp.eingriff_id, ''),
                            IFNULL(comp.komplikation, ''),
                            IFNULL(comp.revisionsoperation, '')
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
            )                                                               AS 'complications'",

            "IF( MIN( tplan.therapieplan_id ) IS NOT NULL,
                GROUP_CONCAT( DISTINCT
                    IF( tplan.therapieplan_id IS NOT NULL,
                        CONCAT_WS( '{$separator_col}',
                            IFNULL(tplan.therapieplan_id, ''),
                            IFNULL(tplan.datum, ''),
                            IFNULL(tplan.konferenz_patient_id, ''),
                            IFNULL(tplan.grundlage, ''),
                            IFNULL(tplan.zeitpunkt, ''),
                            IFNULL(tplan.ah_intention, ''),
                            IFNULL(tplan.andere_intention, ''),
                            IFNULL(tplan.chemo_intention, ''),
                            IFNULL(tplan.immun_intention, ''),
                            IFNULL(tplan.strahlen_intention, ''),
                            IFNULL(tplan.abweichung_leitlinie_grund, '')
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
            )                                                               AS 'therapyplan'",

            "IF( MIN( tPA.therapieplan_abweichung_id ) IS NOT NULL,
                GROUP_CONCAT( DISTINCT
                    IF( tPA.therapieplan_abweichung_id IS NOT NULL,
                        CONCAT_WS( '{$separator_col}',
                            IFNULL(tPA.therapieplan_abweichung_id, ''),
                            IFNULL(tPA.datum, ''),
                            IFNULL(tPA.therapieplan_id, ''),
                            IFNULL(tPA.bezug_strahlen, ''),
                            IFNULL(tPA.bezug_chemo, ''),
                            IFNULL(tPA.bezug_immun, ''),
                            IFNULL(tPA.bezug_ah, ''),
                            IFNULL(tPA.bezug_andere, ''),
                            IFNULL(tPA.grund, '')
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
            )                                                               AS 'therapyplan_deviance'",
        ) );

        $result = $wrapper->GetExportData( $parameters );

        foreach( $result as $extract_data ) {
            $extract_data[ 'examination' ] = HReports::RecordStringToArray($extract_data[ 'examination' ],
            $names=array(
                'untersuchung_id',
                'datum',
                'art',
                'koloskopie_vollstaendig'
            ));

            $extract_data[ 'complications' ] = HReports::RecordStringToArray($extract_data[ 'complications' ],
            $names=array(
                'komplikation_id',
                'datum',
                'eingriff_id',
                'komplikation',
                'revisionsoperation'
            ));

            $extract_data[ 'therapyplan' ] = HReports::RecordStringToArray($extract_data[ 'therapyplan' ],
            $names=array(
                'therapieplan_id',
                'datum',
                'konferenz_patient_id',
                'grundlage',
                'zeitpunkt',
                'ah_intention',
                'andere_intention',
                'chemo_intention',
                'immun_intention',
                'strahlen_intention',
                'abweichung_leitlinie_grund'
            ));

            $extract_data[ 'therapyplan_deviance' ] = HReports::RecordStringToArray($extract_data[ 'therapyplan_deviance' ],
            $names=array(
                'therapieplan_abweichung_id',
                'datum',
                'therapieplan_id',
                'bezug_strahlen',
                'bezug_chemo',
                'bezug_immun',
                'bezug_ah',
                'bezug_andere',
                'grund'
            ));

            $extract_data[ 'startDate' ] = '';
            $oldestHistology = HReports::GetMinElementByDate($extract_data['alle_histologien']);
            $oldestOperation = HReports::GetMinElementByDate($extract_data['operationen'], $index = 2, $value = array('1'));
            $extract_data[ 'startDate' ] = $oldestOperation !== false ? $oldestOperation[ 'beginn' ] : $oldestHistology[ 'datum' ];

            $patient_id    = $extract_data[ 'patient_id' ];
            $erkrankung_id = $extract_data['erkrankung_id'];
            $query         = "SELECT MIN(datum_sicherung) AS datum FROM tumorstatus WHERE patient_id=$patient_id AND erkrankung_id=$erkrankung_id AND anlass LIKE '%r%'";
            $rezedivQuery  = sql_query_array( $this->m_db, $query );

            $extract_data[ 'start_date_rezidiv' ] = '';
            if (strlen($rezedivQuery[ '0' ][ 'datum' ] > 0)) {
                $extract_data[ 'start_date_rezidiv' ]  = $rezedivQuery[ '0' ][ 'datum' ];
            }

            $extract_data['study'] = HReports::GetAllStudies($this->m_db, $extract_data[ 'erkrankung_id' ], $extract_data[ 'start_date' ], $extract_data[ 'end_date' ]);

            //Ausschusskriteria
            if ($this->getReject($extract_data[ 'erkrankung_id' ]) !== '1') {
                // Create main case
                $case = $this->CreateCase( $export_record->GetDbid(), $parameters, $extract_data );

                // Melder
                $section = $this->CreateMelderSection( $parameters, $section_uid );
                $melder = $this->CreateBlock( $case->GetDbid(), $parameters, 'melder', $section_uid, $section );
                $case->AddSection( $melder );

                // Fall
                $section = $this->CreateFallSection( $parameters, $extract_data, $section_uid );
                $fall = $this->CreateBlock( $case->GetDbid(), $parameters, 'fall', $section_uid, $section );
                $case->AddSection( $fall );

                // Patient
                $section = $this->CreatePatientSection( $parameters, $extract_data, $section_uid );
                $patient = $this->CreateBlock( $case->GetDbid(), $parameters, 'patient', $section_uid, $section );
                $case->AddSection( $patient );

                // Anamnesis
                $section = $this->CreateAnamnesisSection( $parameters, $extract_data, $section_uid );
                $anamnesis = $this->CreateBlock( $case->GetDbid(), $parameters, 'anamnesis', $section_uid, $section );
                $case->AddSection( $anamnesis );

                //Study
                $study = $this->CreateStudySection( $parameters, $extract_data);
                if (isset($study) && count($study) > 0) {
                    foreach( $study as $row ) {
                        $section_uid = 'STUDY_' . $row[ 'studie_id' ];
                        $study = $this->CreateBlock( $case->GetDbid(), $parameters, 'study', $section_uid, $row );
                        $case->AddSection( $study );
                    }
                }

                //Conference
                if (isset($extract_data[ 'therapyplan' ]) && count($extract_data[ 'therapyplan' ]) > 0) {
                    $conference = $this->CreateConferenceSection( $parameters, $extract_data, $section_uid );
                    if (isset($conference) && count($conference) > 0) {
                        foreach( $conference as $row ) {
                            $section_uid = 'CONFERENCE_' . $row[ 'therapieplan_id' ];
                            $conference = $this->CreateBlock( $case->GetDbid(), $parameters, 'conference', $section_uid, $row );
                            $case->AddSection( $conference );
                        }
                    }
                }

                // Diagnose
                $section = $this->CreateDiagnoseSection( $parameters, $extract_data, $section_uid );
                $section_uid = 'DIAGNOSE_' . $section[ 'diagnose_id' ];
                $diagnose = $this->CreateBlock( $case->GetDbid(), $parameters, 'diagnose', $section_uid, $section );
                $case->AddSection( $diagnose );

                // Histology
                $section = $this->CreateHistologySection( $parameters, $extract_data, $section_uid );
                $section_uid = 'HISTOLOGY_' . $section[ 'histologie_id' ];
                $histology = $this->CreateBlock( $case->GetDbid(), $parameters, 'histology', $section_uid, $section );
                $case->AddSection( $histology );

                // Operations
                $operations = $this->GetOperationSections( $parameters, $extract_data );
                if (isset($operations) && count($operations) > 0) {
                    foreach( $operations as $row ) {
                        $section_uid = 'OPERATIONS_' . $row[ 'eingriff_id' ];
                        $operations = $this->CreateBlock( $case->GetDbid(), $parameters, 'operations', $section_uid, $row );
                        $case->AddSection( $operations );
                    }
                }

                //SystemicTherapys
                $systemicTherapy = $this->GetSystemicTherapySections( $parameters, $extract_data );
                if (isset($systemicTherapy) && count($systemicTherapy) > 0) {
                    foreach( $systemicTherapy as $row ) {
                        $section_uid = 'SYSTEM_' . $row[ 'systemische_therapie_id' ];
                        $systemicTherapy = $this->CreateBlock( $case->GetDbid(), $parameters, 'systemicTherapy', $section_uid, $row );
                        $case->AddSection( $systemicTherapy );
                    }
                }

                //RadioTherapys
                $radioTherapy = $this->GetRadioTherapySections( $parameters, $extract_data );
                if (isset($radioTherapy) && count($radioTherapy) > 0) {
                    foreach( $radioTherapy as $row ) {
                        $section_uid = 'RADIO_' . $row[ 'strahlen_therapie_id' ];
                        $radioTherapy = $this->CreateBlock( $case->GetDbid(), $parameters, 'radioTherapy', $section_uid, $row );
                        $case->AddSection( $radioTherapy );
                    }
                }

                //Labs
                $lab = $this->GetLaborSections($extract_data);
                if (isset($lab) && count($lab) > 0) {
                    foreach ($lab as $row) {
                        $section_uid = 'LAB_' . $row[ 'labor_wert_id' ];
                        $lab = $this->CreateBlock($case->GetDbid(), $parameters, 'lab', $section_uid, $row );
                        $case->AddSection( $lab );
                    }
                }


                //afterCare
                $afterCare = $this->GetAfterCareSection( $parameters, $extract_data);
                if (isset($afterCare) && count($afterCare) > 0) {
                    foreach ($afterCare as $row) {
                        $section_uid = 'AFTER_' . $row[ 'nachsorge_id' ];
                        $afterCare = $this->CreateBlock( $case->GetDbid(), $parameters, 'afterCare', $section_uid, $row);
                        $case->AddSection( $afterCare );
                    }
                }

                // Abschluss
                $section = $this->CreateAbschlussSection( $parameters, $extract_data );
                if ( count( $section ) > 0 ) {
                    $section_uid = 'ABS_' . $extract_data[ 'abschluss_id' ];
                    $abschluss = $this->CreateBlock( $case->GetDbid(), $parameters, 'abschluss', $section_uid, $section );
                    $case->AddSection( $abschluss );
                }

                // Add main case
                $export_record->AddCase( $case );
            }
        }
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $export_record
     * @return void
     */
    public function PreparingData( $parameters, &$export_record )
    {
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $section
     * @param $old_section
     * @return void
     */
    public function HandleDiff($parameters, $case, &$section, $old_section)
    {
        $section->SetMeldungskennzeichen( "N" );
        $section->SetDataChanged( 1 );
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $export_record
     * @return void
     */
    public function CheckData( $parameters, &$export_record )
    {
        // Hier jeden Abschnitt gegen XSD Pr?fen und Fehler in DB schreiben...
        $serialiser = new Cwdc_2011_0_Serialiser();
        $serialiser->Create( $this->m_absolute_path,
                             $this->GetExportName(),
                             $this->m_smarty, $this->m_db, $this->m_error_function );
        $serialiser->SetData( $export_record );
        $serialiser->Validate( $this->m_parameters );
    }


    /**
     *
     *
     * @access
     * @return void
     */
    public function WriteData()
    {
        $this->m_export_record->SetFinished( true );
        // Hier gesammtes XML schreiben und nicht mehr gegen XSD prüfen..
        $serialiser = new Cwdc_2011_0_Serialiser();
        $serialiser->Create( $this->m_absolute_path, $this->GetExportName(),
                             $this->m_smarty, $this->m_db, $this->m_error_function );
        $serialiser->SetData( $this->m_export_record );
        $this->m_export_filename = $serialiser->Write( $this->m_parameters );
        $this->m_export_record->Write( $this->m_db );
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $section_uid
     * @return array
     */
    protected function CreateMelderSection( $parameters, &$section_uid )
    {
        $ansprechpartner_name = isset( $parameters[ 'ansprechpartner_name' ] )
            ? $parameters[ 'ansprechpartner_name' ]
            : ''
        ;
        $ansprechpartner_email = isset( $parameters[ 'ansprechpartner_email' ] )
            ? $parameters[ 'ansprechpartner_email' ]
            : ''
        ;
        $melder = array();
        $melder[ 'schema_version' ] = array(
            'typ'  => $parameters[ 'schema_version_typ' ],
            'jahr' => $parameters[ 'schema_version_jahr' ]
        );
        $melder[ 'zentrum_id' ]                = $parameters[ 'zentrum_id' ];
        $melder[ 'datum_datensatzerstellung' ] = date( "Y-m-d" );
        $melder[ 'zeitraum_beginn' ]           = $this->CheckDatum($parameters[ 'datum_von' ]);
        $melder[ 'zeitraum_ende' ]             = $this->CheckDatum($parameters[ 'datum_bis' ]);
        $melder[ 'sw_hersteller' ]             = HCommon::TrimString( $parameters[ 'sw_hersteller' ] ,200, true );
        $melder[ 'sw_name' ]                   = HCommon::TrimString( $parameters[ 'sw_name' ] ,200, true );
        $melder[ 'sw_version' ]                = HCommon::TrimString( $parameters[ 'sw_version' ] ,200, true );
        $melder[ 'technische_ansprechpartner' ] = array(
            "tech_ansprechpartner_name" => HCommon::TrimString( $ansprechpartner_name, 200, true ),
            "email"                     => HCommon::TrimString( $ansprechpartner_email, 200, true )
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
    protected function CreateFallSection( $parameters, $extract_data, &$section_uid )
    {
        $fall = array();
        $fall[ 'fall_id' ]        = HCommon::TrimString( $extract_data[ 'erkrankung_id' ], 200, true );
        $fall[ 'kostentraeger' ]  = HCommon::TrimString( $extract_data[ 'kassenname' ],200, true );
        $fall[ 'koerpergroesse' ] = $this->GetKoerpergroesse( $extract_data[ 'anamnesen' ] );
        $fall[ 'koerpergewicht' ] = $this->GetKoerpergewicht( $extract_data[ 'anamnesen' ] );
        $fall[ 'fall_beginn' ]    = $this->CheckDatum( $extract_data[ 'startDate' ] );

        $section_uid              = 'FALL_' . $this->GetUidFromData( $extract_data );
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
    protected function CreatePatientSection( $parameters, $extract_data, &$section_uid )
    {

        $patient = array();
        $patient[ 'patient_id' ]   = HCommon::TrimString( $extract_data[ 'referenznr' ] ,200, true );
        $patient[ 'geburtstag' ]   = $this->CheckDatum( $extract_data[ 'geburtsdatum' ] );
        $patient[ 'geschlecht' ]   = strlen($extract_data[ 'geschlecht' ]) > 0 ? $extract_data[ 'geschlecht' ] : 'x';
        $patient[ 'todesdatum' ]   = '';
        $patient[ 'todesursache' ] = '';
        if ( $extract_data[ 'abschlussgrund' ] === 'tot' ) {
            $patient[ 'todesdatum' ]   = $this->CheckDatum( $extract_data[ 'todesdatum' ]);
            $patient[ 'todesursache' ] = $this->GetExportCode('tod_tumorassoziation', $extract_data[ 'tod_tumorbedingt' ], '');
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
    protected function CreateStudySection( $parameters, $extract_data )
    {
        $studys = array();

        if (isset($extract_data['study']) && count($extract_data['study']) > 0) {
            foreach ($extract_data['study'] as $stud) {
                $study = array();
                $study[ 'studie_id' ]         = $stud[ 'studie_id' ];
                $study[ 'studienteilnehmer' ] = '1';
                if ( $study[ 'studienteilnehmer' ] === '1' ) {
                    $study_name = $this->GetStudienVorlage( $stud[ 'vorlage_studie_id' ] );
                    $study[ 'studien_name' ]     = HCommon::TrimString( $study_name[ 'bez' ], 200, true );
                    $study[ 'studie_beendet' ]   = strlen($stud[ 'ende' ] > 0) ? '1' : '2';
                    if (strlen($stud[ 'beginn' ]) > 0) {
                        $study[ 'datum_einschluss' ] = $this->CheckDatum( $stud[ 'beginn' ] );
                    } elseif (strlen($stud[ 'date' ]) > 0 ) {
                        $study[ 'datum_einschluss' ] = $stud[ 'date' ];
                    } else {
                        $study[ 'datum_einschluss' ] = '1900-01-01';
                    }
                    $study[ 'datum_studienende' ] = $this->CheckDatum( $stud[ 'ende' ] );;

                }
                $studys[] = $study;
            }
        } else {
            $study[ 'studie_id' ] = '';
            $study[ 'studienteilnehmer' ] = '0';
            $studys[] = $study;
        }
        $section_uid = 'STUDY_' . $this->GetUidFromData( $extract_data );
        return $studys;
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
    protected function CreateConferenceSection( $parameters, $extract_data, &$section_uid )
    {
        $conference = array();

        foreach( $extract_data[ 'therapyplan' ] as $tplan ) {
            $tmp = array();
            if ($tplan[ 'grundlage' ] == 'tk') {
                $tmp = array();
                $tmp[ 'therapieplan_id' ]      = $tplan[ 'therapieplan_id' ];
                $tmp[ 'tumorkonferenz_datum' ] = $this->CheckDatum( $tplan[ 'datum' ]);
                $tmp[ 'empfehlung' ]           = '1';
                $tmp[ 'postoperativ' ]         = $tplan[ 'zeitpunkt' ] === 'post' ? '1' : '0';
                $tmp[ 'praetherapeutisch' ]    = $tplan[ 'zeitpunkt' ] === 'prae' ? '1' : '0';
                $section_uid = 'CONFERENCE_' . $tplan[ 'therapieplan_id' ] . $this->GetUidFromData( $extract_data );
                $conference[] = $tmp;
            }
        }

        return $conference;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @return array
     */
    protected function CreateAnamnesisSection( $parameters, $extract_data, &$section_uid )
    {
        $anamnesis = array();
        $oldestHistology = HReports::GetMinElementByDate($extract_data['alle_histologien']);

        $anamnesis[ 'rezediv' ]        = '0';
        $anamnesis[ 'erstdiag_datum' ] = $this->CheckDatum( $oldestHistology[ 'datum' ] );
        $anamnesis[ 'familie_pos' ]    = $this->getFamilyAnamesis($extract_data[ 'erkrankung_id' ]);
        $section_uid = 'ANAMNESE_' . $this->GetUidFromData( $extract_data );
        return $anamnesis;
    }

    protected function CreateDiagnoseSection( $parameters, $extract_data, &$section_uid )
    {
        $diagnose = array();
        $diagnose[ 'diagnose_id' ] = $extract_data[ 'tumoridentifikator' ];
        $diagnose[ 'tumor' ] = '';
        switch ( substr ( $extract_data[ 'diagnose' ], 0, 3 ) ) {
            case 'C18':
                $diagnose[ 'tumor' ] = '1';
                break;
            case 'C19':
                $diagnose[ 'tumor' ] = '2';
                break;
            case 'C20':
                $diagnose[ 'tumor' ] = '3';
                break;
        }
        switch ( $extract_data[ 'diagnose' ] ) {
            case 'D01.0':
                $diagnose[ 'tumor' ] = '1';
                break;
            case 'D01.1':
                $diagnose[ 'tumor' ] = '2';
                break;
            case 'D01.2':
                $diagnose[ 'tumor' ] = '3';
                break;
        }


        $diagnose[ 'anocutanlinie' ] = $extract_data[ 'hoehe' ];
        $diagnose[ 'rezediv' ] = strlen($extract_data[ 'start_date_rezidiv' ] > 0 ) ? '1' : '0';

        $diagnose[ 'datum_diagnose' ] = $this->CheckDatum( $extract_data[ 'erstdiagnose_datum' ]);
        $diagnosis = HReports::GetDiagnoseWithText(
            $this->m_db,
            $extract_data[ 'erkrankung_id' ],
            $extract_data[ 'diagnose_seite' ],
            $extract_data[ 'start_date' ],
            $extract_data[ 'end_date' ],
            true
        );

        $diagnose[ 'icd_code' ]                     = HCommon::TrimString( $diagnosis[ 'diagnose' ] ,200, true );
        $diagnose[ 'icd_text' ]                     = HCommon::TrimString( $diagnosis[ 'text' ] ,200, true );
        $diagnose[ 'icd_version' ]                  = '405';
        $diagnose[ 't' ]                            = '';
        $diagnose[ 't' ]                            = $this->GetExportCode($class = 'ct', $code = $extract_data[ 'ct' ], '');
        $diagnose[ 'n' ]                            = '';
        $diagnose[ 'n' ]                            = $this->GetExportCode($class = 'cn', $code = $extract_data[ 'cn' ], '');
        $diagnose[ 'm' ]                            = '';
        $diagnose[ 'm' ]                            = $this->GetExportCode($class = 'cm', $code = $extract_data[ 'cm' ], '');
        $diagnose[ 'y' ]                            = '';
        $diagnose[ 'y' ]                            = $this->GetExportCode( 'tnm_praefix',
                                                                            $this->GetClinicalPraefix( $extract_data ),
                                                                            '0' );
        $diagnose[ 'g' ]                            = '';
        $diagnose[ 'g' ]                            = $this->GetExportCode( 'g',
                                                                            $this->GetClinicalG( $extract_data ),
                                                                            '' );
        $diagnose[ 'tnm_version' ]                  = '503';
        $diagnose[ 'ges_koloskopie' ]               = '';
        $diagnose[ 'tot_koloskopie' ]               = '';
        $diagnose[ 'ther_koloskopie' ]              = '0';
        $diagnose[ 'ther_koloskopie_kompl' ]        = '0';
        $diagnose[ 'unv_stenosierende_koloskopie' ] = '';
        $diagnose[ 'polyp_nachweis' ]               = '';
        $diagnose[ 'polypektomie' ]                 = '';
        $diagnose[ 'polyp_op_gebiet' ]              = '';
        $diagnose[ 'polypektomie_polyp' ]           = '';

        $tmp_ges_koloskopie               = 0;
        $tmp_tot_koloskopie               = 0;
        $tmp_unv_stenosierende_koloskopie = 0;
        $tmp_polyp_nachweis               = 0;
        $tmp_polypektomie                 = 0;
        $tmp_polyp_op_gebiet              = 0;
        $tmp_polypektomie_polyp           = 0;
        $ges_koloskopie                   = 0;
        $tot_koloskopie                   = 0;
        $unv_stenosierende_koloskopie     = 0;
        $polyp_nachweis                   = 0;
        $polypektomie                     = 0;
        $polyp_op_gebiet                  = 0;
        $polypektomie_polyp               = 0;
        $reset_polyp_op_gebiet            = 0;

        foreach ($extract_data[ 'operationen' ] as $ops) {
            foreach ($ops[ 'ops_codes' ] as $opCode ) {
                if (substr($opCode[ 'prozedur' ], 0, 7) === '5-452.2' || substr($opCode[ 'prozedur' ], 0, 7) === '5-452.5' || (substr($opCode[ 'prozedur' ], 0, 6) === '5-482.' && substr($opCode[ 'prozedur' ], -1) === '1')) {
                    $diagnose[ 'ther_koloskopie' ]       = '1';
                    foreach ($extract_data[ 'complications' ] as $comp) {
                        if ($ops[ 'eingriff_id' ] === $comp[ 'eingriff_id' ]) {
                            $diagnose[ 'ther_koloskopie_kompl' ] = '1';
                        }
                    }
                }
                if (substr($opCode[ 'prozedur' ], 0, 7) === '5-452.2' ||
                    substr($opCode[ 'prozedur' ], 0, 7) === '5-452.5' ||
                    substr($opCode[ 'prozedur' ], 0, 6) === '1-650.' ||
                    $opCode[ 'prozedur' ] === '1-652.1' ||
                    (substr($opCode[ 'prozedur' ], 0, 6) === '5-482.' && substr($opCode[ 'prozedur' ], -1) === '2') ||
                    (substr($opCode[ 'prozedur' ], 0, 6) === '5-482.' && substr($opCode[ 'prozedur' ], -1) === '1')) {
                    $tmp_ges_koloskopie++;
                    if ($ops[ 'ther_koloskopie_vollstaendig' ] === '1') {
                        $tmp_tot_koloskopie++;
                    }
                    if ($ops[ 'ther_koloskopie_vollstaendig' ] === '3') {
                        $tmp_unv_stenosierende_koloskopie++;
                    }
                    if ($ops[ 'polypen' ] === '1') {
                        $tmp_polyp_nachweis++;
                    }
                    if ($ops[ 'polypen_op_areal' ] === '1' && $reset_polyp_op_gebiet === 0) {
                        $tmp_polyp_op_gebiet++;
                    }
                    if ($ops[ 'polypen' ] === '1' && $ops[ 'polypen_op_areal' ] === '0') {
                        $tmp_polypektomie_polyp++;
                    }
                    if ($opCode[ 'prozedur' ] === '5-452.21' || $opCode[ 'prozedur' ] === '5-452.22') {
                        $tmp_polypektomie++;
                        $reset_polyp_op_gebiet = 1;
                    }
                }
            }

            if ($tmp_ges_koloskopie > 0 ) {
                $ges_koloskopie++;
            }
            if ($tmp_tot_koloskopie > 0) {
                $tot_koloskopie++;
            }
            if ($tmp_unv_stenosierende_koloskopie > 0) {
                $unv_stenosierende_koloskopie++;
            }
            if ($tmp_polyp_nachweis > 0) {
                $polyp_nachweis++;
            }
            if ($tmp_polypektomie > 0) {
                $polypektomie++;
            }
            if ($tmp_polyp_op_gebiet > 0) {
                $polyp_op_gebiet++;
            }
            if ($tmp_polypektomie_polyp > 0) {
                $polypektomie_polyp++;
            }
            if ($reset_polyp_op_gebiet === 1) {
                $polyp_op_gebiet = 0;
            }
        }

        foreach ($extract_data[ 'examination'] as $exam) {
            if (substr($exam[ 'art' ], 0, 7) === '5-452.2' ||
                substr($exam[ 'art' ], 0, 7) === '5-452.5' ||
                substr($exam[ 'art' ], 0, 6) === '1-650.'  ||
                       $exam[ 'art' ]        === '1-652.1' ||
               (substr($exam[ 'art' ], 0, 6) === '5-482.' && substr($exam[ 'art' ], -1) === '2') ||
               (substr($exam[ 'art' ], 0, 6) === '5-482.' && substr($exam[ 'art' ], -1) === '1')) {
                    if ($exam[ 'koloskopie_vollstaendig' ] === '1') {
                        $tot_koloskopie++;
                    }
                    if ($exam[ 'koloskopie_vollstaendig' ] === '3') {
                        $unv_stenosierende_koloskopie++;
                    }
                    $ges_koloskopie++;
            }
        }
        $diagnose[ 'ges_koloskopie' ]               = $ges_koloskopie;
        $diagnose[ 'tot_koloskopie' ]               = $tot_koloskopie;
        $diagnose[ 'unv_stenosierende_koloskopie' ] = $unv_stenosierende_koloskopie;
        $diagnose[ 'polyp_nachweis' ]               = $polyp_nachweis;
        $diagnose[ 'polypektomie' ]                 = $polypektomie;
        $diagnose[ 'polyp_op_gebiet' ]              = $polyp_op_gebiet;
        $diagnose[ 'polypektomie_polyp' ]           = $polypektomie_polyp;

        return $diagnose;
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
    protected function CreateHistologySection( $parameters, $extract_data, &$section_uid )
    {
        $histology = array();

        $newestHisto = HReports::GetMaxElementByDate($extract_data[ 'alle_histologien' ]);
        $histology[ 'histologie_id' ] = $newestHisto[ 'histologie_id' ];
        $histology[ 'msi' ]                     = '';

        foreach ($extract_data[ 'alle_histologien' ] as $hist) {
            if (isset($hist['msi']) && $hist['msi'] === '1') {
                $histology[ 'msi' ] = '1';
            } elseif ($histology[ 'msi' ] !== '1' && isset($hist['msi']) && $hist['msi'] === '0') {
                $histology[ 'msi' ] = '0';
            } elseif ($histology[ 'msi' ] !== '1' && $hist['msi'] === '') {
                $histology[ 'msi' ] = '2';
            }
        }

        $kras = HReports::GetMaxElementByDate($extract_data['alle_histologien'], 4);
        $histology[ 'k_ras_wildtyp' ] = '2';
        if (isset($kras[ 'kras' ]) && $kras[ 'kras' ] === 'wild') {
            $histology[ 'k_ras_wildtyp' ] = '1';
        } elseif (isset($kras[ 'kras' ]) && $kras[ 'kras' ] === 'mut') {
            $histology[ 'k_ras_wildtyp' ] = '0';
        }

        $morph = HReports::GetMorphologieWithText(
            $this->m_db,
            $extract_data[ 'erkrankung_id' ],
            $extract_data[ 'diagnose_seite' ],
            $extract_data[ 'start_date' ],
            $extract_data[ 'end_date' ]
        );

        $local = HReports::GetLokalisationWithText(
            $this->m_db,
            $extract_data[ 'erkrankung_id' ],
            $extract_data[ 'diagnose_seite' ],
            $extract_data[ 'start_date' ],
            $extract_data[ 'end_date' ],
            true
        );

        if ($local === array(
            'lokalisation' => '',
            'text'         => ''
        )) {
            $local = HReports::GetDiagnoseToLokalisation($this->m_db, $extract_data['diagnose']);
            if ($local === false) {
                $local = array(
                    'lokalisation' => '',
                    'text'         => ''
                );
            }
        }

        $metastasen_ort = array();
        foreach($extract_data['metastasen_lokalisationen'] as $metastasis) {
            $metastasen_ort[] = array('ort' => (isset($this->m_metastasis_codings[$metastasis['lokalisation']]) ? $this->m_metastasis_codings[$metastasis['lokalisation']] : ''));
        }
        $ptnm_praefix = '0';
        if ( $extract_data[ 'tnm_praefix' ] === 'y' || $extract_data[ 'tnm_praefix' ] === 'yr') {
            $ptnm_praefix = '1';
        }

        $patho_histo_klassifikation = array(
            'morpho_code'             => HCommon::TrimString( $morph[ 'morphologie' ] ,200, true ),
            'morpho_text'             => HCommon::TrimString( $morph[ 'text' ] ,200, true ),
            'topologie_code'          => HCommon::TrimString( $local[ 'lokalisation' ] ,200, true ),
            'topologie_text'          => HCommon::TrimString( $local[ 'text' ] ,200, true ),
            'histo_datum'             => $this->CheckDatum( $newestHisto[ 'datum' ]),
            'icdo_version'            => '301',
            't'  => $this->GetExportCode($class = 'pt', $code = $extract_data[ 't_postop' ], ''),
            'n'  => $this->GetExportCode($class = 'pn', $code = $extract_data[ 'n_postop' ], ''),
            'm'  => $this->GetExportCode($class = 'pm', $code = $extract_data[ 'm_postop' ], ''),
            'y'  => $ptnm_praefix,
            'g'  => $this->GetExportCode($class = 'g', $code = $extract_data[ 'g' ], ''),
            'r'  => $this->GetExportCode($class = 'r', $code = $extract_data[ 'r' ], ''),
            'l'  => $extract_data[ 'l' ],
            'v'  => $extract_data[ 'v' ],
            'pn' => $this->GetExportCode($class = 'ppn', $code = $extract_data[ 'ppn' ], ''),
            'tnm_version'              => '503',
            'metastasen_ort'           => $metastasen_ort,
            'stadiengruppierung_uicc'  => $extract_data[ 'uicc' ] !== 'ok' ? $this->GetExportCode('uicc', $extract_data[ 'uicc' ], '') : '',
        );

        $histology[ 'patho_histo_klassifikation' ] =         $patho_histo_klassifikation;

        return $histology;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @return array
     */
    protected function GetOperationSections( $parameters,  $extract_data )
    {
        $operations = array();
        foreach( $extract_data[ 'operationen' ] as $ops ) {
            $tmp = array();

            //Datum der chemotherapie (für leberresektion)
            foreach ($extract_data[ 'systemische_therapien' ] as $sysTherapy) {
                if ($sysTherapy[ 'art' ] === 'c' || $sysTherapy[ 'art' ] === 'cst' || $sysTherapy[ 'art' ] === 'ci') {
                    $chemoDate = $sysTherapy[ 'beginn' ];
                }
            }

            $lymphknoten_extirpation = '0';
            foreach ($ops[ 'ops_codes' ] as $opCodes) {
                if (substr($opCodes[ 'prozedur' ], 2, 1) !== 'e' ) {
                    $tmp[ 'ct_ops' ][] = array(
                        'eingriff_id' => $ops[ 'eingriff_id' ],
                        'ops_code'    => HCommon::TrimString( $opCodes[ 'prozedur' ] ,200, true ),
                        'ops_text'    => HCommon::TrimString( $opCodes[ 'prozedur_text' ] ,200, true ),
                        'ops_datum'   => $this->CheckDatum( $ops[ 'beginn' ] ),
                    );
                }
                if (substr($opCodes[ 'prozedur' ], 0, 5) === '5-400' ||
                    substr($opCodes[ 'prozedur' ], 0, 5) === '5-401' ||
                    substr($opCodes[ 'prozedur' ], 0, 5) === '5-402' ||
                    substr($opCodes[ 'prozedur' ], 0, 5) === '5-406' ||
                    substr($opCodes[ 'prozedur' ], 0, 7) === '5-407.2' ||
                    substr($opCodes[ 'prozedur' ], 0, 7) === '5-407.3' ||
                    substr($opCodes[ 'prozedur' ], 0, 7) === '5-407.4' ||
                    substr($opCodes[ 'prozedur' ], 0, 7) === '5-407.x' ||
                    substr($opCodes[ 'prozedur' ], 0, 7) === '5-407.y' ||
                    substr($opCodes[ 'prozedur' ], 0, 7) === '5-408.5' ||
                    substr($opCodes[ 'prozedur' ], 0, 7) === '5-455.4' ||
                    substr($opCodes[ 'prozedur' ], 0, 7) === '5-455.6' ||
                    substr($opCodes[ 'prozedur' ], 0, 7) === '5-458.0' ||
                    substr($opCodes[ 'prozedur' ], 0, 7) === '5-458.1' ||
                    substr($opCodes[ 'prozedur' ], 0, 7) === '5-458.5' ) {
                    $lymphknoten_extirpation = '1';
                }
            }
            $tmp[ 'eingriff_id' ]                        = $ops[ 'eingriff_id' ];
            $tmp[ 'ersteingriff' ]                       = $ops[ 'art_primaertumor' ];
            $tmp[ 'op_datum' ]                           = $this->CheckDatum( $ops[ 'beginn' ] );
            $tmp[ 'op_typ' ]                             = '4';
            if ($ops[ 'art_revision' ] === '1') {
                $tmp[ 'op_typ' ]                             = '2';
            }
            if ($ops[ 'art_primaertumor' ] === '1' || $ops[ 'art_nachresektion' ] === '1') {
                $tmp[ 'op_typ' ]                             = '1';
            }
            $tmp[ 'op_notfalltyp' ]                      = $ops[ 'notfall' ] === '1' ? '2' : '1';
            $tmp[ 'op_intention' ]                       = '';
            $tmp[ 'op_intention' ]                       = $ops[ 'intention' ] === 'kur' ? '1' :  $tmp[ 'op_intention' ];
            $tmp[ 'op_intention' ]                       = $ops[ 'intention' ] === 'pal' ? '2' :  $tmp[ 'op_intention' ];
            if ($ops[ 'art_diagnostik' ] === '1' && $ops[ 'intention' ] === '') {
                $tmp[ 'op_intention' ]                       = '3';
            }
            $tmp[ 'op_letalitaet' ]                      = '';
            $tmp[ 'op_letalitaet' ]                      = $extract_data[ 'abschlussgrund' ] !== 'tot' ? '1' : '';
            if ($extract_data[ 'abschlussgrund' ] === 'tot') {
                $datediff = date_diff_raw($ops[ 'beginn' ], $extract_data[ 'todesdatum' ]);
                $datediff = ($datediff['h'] / 24);
                $tmp[ 'op_letalitaet' ] = $datediff <= 30 ? '2' : $tmp[ 'op_letalitaet' ];
                $tmp[ 'op_letalitaet' ] = $datediff > 30 ? '3' : $tmp[ 'op_letalitaet' ];
                $tmp[ 'op_letalitaet' ] = strlen($extract_data[ 'todesdatum' ] > 0) ? $tmp[ 'op_letalitaet' ] : '4';

            }

            $tmp[ 'lateral' ]                            = '';
            $tmp[ 'distal' ]                             = '';
            $tmp[ 'abstand_resektionsraender_distal' ]   = '';
            $tmp[ 'abstand_resektionsraender_lateral' ]  = '';
            $tmp[ 'pathohistologisch_lokaler_r_status' ] = '0';
            $lymphknoten  = array();
            $mercury     = '0';
            foreach ($extract_data[ 'alle_histologien' ] as $aHisto) {
                if ($ops[ 'eingriff_id' ] = $aHisto[ 'eingriff_id' ]) {
                    $tmp[ 'lateral' ]                            = $aHisto[ 'resektionsrand_lateral' ] === '' ? '-1' : $aHisto[ 'resektionsrand_lateral' ];
                    $tmp[ 'distal' ]                             = $aHisto[ 'resektionsrand_aboral' ]  === '' ? '-1' : $aHisto[ 'resektionsrand_aboral' ];
                    $tmp[ 'abstand_resektionsraender_distal' ]   = $aHisto[ 'resektionsrand_aboral' ]  === '' ? '-1' : $aHisto[ 'resektionsrand_aboral' ];
                    $tmp[ 'abstand_resektionsraender_lateral' ]  = $aHisto[ 'resektionsrand_lateral' ] === '' ? '-1' : $aHisto[ 'resektionsrand_lateral' ];
                    $tmp[ 'pathohistologisch_lokaler_r_status' ] = $this->GetExportCode($class = 'r', $code = $aHisto[ 'r' ], '0');
                    $mercury     = strlen ($aHisto[ 'mercury' ] > 0) ? $aHisto[ 'mercury' ] : '0';
                    $lk_removed = $aHisto[ 'lk_entf' ] > 0 ? $aHisto[ 'lk_entf' ] : '-1';

                    $lymphknoten[] = array(
                        'lymphknoten_extirpation' => $lymphknoten_extirpation,
                        'lk_removed'              => $lk_removed,
                        'lk_infested'             => $aHisto[ 'lk_bef' ],
                    );
                }
            }
            $tmp[ 'asa_score' ]                          = $ops[ 'asa' ];
            $tmp[ 'op_komplikation' ] = '';

            if (isset($extract_data[ 'complications' ]) && count($extract_data[ 'complications' ]) > 0) {
                foreach ($extract_data[ 'complications' ] as $complication) {
                    if ($tmp[ 'eingriff_id' ] === $complication[ 'eingriff_id' ]) {
                        $grad = '4';
                        $art  = '0';
                        $grad = $complication[ 'revisionsoperation' ] === '1'   ? '2' : $grad;
                        $grad = $complication[ 'revisionsoperation' ] === '0'   ? '1' : $grad;
                        $grad = $complication[ 'komplikation' ]       === 'tod' ? '3' : $grad;
                        $art = $this->m_complication_codings[$complication['komplikation']];
                        $tmp[ 'op_komplikation' ][] = array(
                            'op_komplikationsgrad' => $grad,
                            'op_komplikationsart'  => $art
                        );
                    }
                }
            }

            if( $tmp['op_komplikation'] === '') {
                $tmp[ 'op_komplikation' ][] = array(
                    'op_komplikationsgrad' => '4',
                    'op_komplikationsart'  => '0'
                );
            }

            $tmp[ 'mesorektumexstirpation' ] = $ops[ 'tme' ];
            $tmp[ 'mesorektumexstirpation' ] = $ops[ 'pme' ] === '1' ? '2' : $tmp[ 'mesorektumexstirpation' ];
            $tmp[ 'mesorektumexstirpation' ] = $tmp[ 'mesorektumexstirpation' ] === '' ? '0' : $tmp[ 'mesorektumexstirpation' ];

            $tmp[ 'kuenstl_darmausgang' ]           = '0';
            $tmp[ 'regionale_operationsverfahren' ] = '4';
            $tmp[ 'leberresektion' ]                = '3';
            $tmp[ 'stoma_protektiv' ]               = '0';
            foreach ($ops[ 'ops_codes' ] as $opCodes) {
                if ((substr($opCodes[ 'prozedur' ], 0, 6) === '5-455.' && substr($opCodes[ 'prozedur' ], -1) === '2') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-455.' && substr($opCodes[ 'prozedur' ], -1) === '3') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-455.' && substr($opCodes[ 'prozedur' ], -1) === '4') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-455.' && substr($opCodes[ 'prozedur' ], -1) === '6') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-456.' && substr($opCodes[ 'prozedur' ], -1) === '0') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-456.' && substr($opCodes[ 'prozedur' ], -1) === '7') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-458.' && substr($opCodes[ 'prozedur' ], -1) === '2') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-458.' && substr($opCodes[ 'prozedur' ], -1) === '3') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-458.' && substr($opCodes[ 'prozedur' ], -1) === '4') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-484.' && substr($opCodes[ 'prozedur' ], -1) === '2') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-484.' && substr($opCodes[ 'prozedur' ], -1) === '6') ||
                    substr($opCodes[ 'prozedur' ], 0, 5) === '5-460' ||
                    substr($opCodes[ 'prozedur' ], 0, 5) === '5-461' ||
                    substr($opCodes[ 'prozedur' ], 0, 5) === '5-462' ||
                    substr($opCodes[ 'prozedur' ], 0, 5) === '5-463' ||
                    $opCodes[ 'prozedur' ] === '5-e03.y' ||
                    $opCodes[ 'prozedur' ] === '5-e04.y' ||
                    $opCodes[ 'prozedur' ] === '5-e05.y' ||
                    $opCodes[ 'prozedur' ] === '5-e06.y' ||
                    $opCodes[ 'prozedur' ] === '5-e07.y' ) {
                        $tmp[ 'kuenstl_darmausgang' ]                = '1';
                }

                if (substr($opCodes[ 'prozedur' ], 0, 6) === '5-462.') {
                    $tmp[ 'stoma_protektiv' ]                    = '1';
                }

                if (substr($opCodes[ 'prozedur' ], 0, 7) === '5-456.2') {
                    $tmp[ 'regionale_operationsverfahren' ]      = '2';
                } elseif (substr($opCodes[ 'prozedur' ], 0, 7) === '5-452.2' || substr($opCodes[ 'prozedur' ], 0, 7) === '5-452.5' ) {
                    $tmp[ 'regionale_operationsverfahren' ]      = '1';
                }

                if (substr($opCodes[ 'prozedur' ], 0, 5) === '5-501' ||
                    substr($opCodes[ 'prozedur' ], 0, 5) === '5-502' ||
                    substr($opCodes[ 'prozedur' ], 0, 5) === '5-509' ) {
                    $tmp[ 'leberresektion' ]                     = '1';
                    if (isset($chemoDate) && $chemoDate < $ops[ 'beginn' ]) {
                        $tmp[ 'leberresektion' ]                     = '2';
                    }
                }
            }

            $tmp[ 'lymphknoten' ] = $lymphknoten;
            $tmp[ 'mercury' ]     = $mercury;

            $tmp[ 'ohne_anastomose' ]                    = '1';

            foreach ($ops[ 'ops_codes' ] as $opCodes) {
                if ((substr($opCodes[ 'prozedur' ], 0, 6) === '5-455.' && substr($opCodes[ 'prozedur' ], -1) === '1') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-455.' && substr($opCodes[ 'prozedur' ], -1) === '4') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-455.' && substr($opCodes[ 'prozedur' ], -1) === '5') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-456.' && substr($opCodes[ 'prozedur' ], -1) === '1') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-456.' && substr($opCodes[ 'prozedur' ], -1) === '2') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-456.' && substr($opCodes[ 'prozedur' ], -1) === '3') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-456.' && substr($opCodes[ 'prozedur' ], -1) === '4') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-456.' && substr($opCodes[ 'prozedur' ], -1) === '5') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-458.' && substr($opCodes[ 'prozedur' ], -1) === '1') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-458.' && substr($opCodes[ 'prozedur' ], -1) === '4') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-458.' && substr($opCodes[ 'prozedur' ], -1) === '5') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-486.' && substr($opCodes[ 'prozedur' ], -1) === '6') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-484.' && substr($opCodes[ 'prozedur' ], -1) === '1') ||
                    (substr($opCodes[ 'prozedur' ], 0, 6) === '5-484.' && substr($opCodes[ 'prozedur' ], -1) === '5') ||
                    $opCodes[ 'prozedur' ] === '5-459.2' ||
                    $opCodes[ 'prozedur' ] === '5-459.3' ||
                    $opCodes[ 'prozedur' ] === '5-e00.y' ||
                    $opCodes[ 'prozedur' ] === '5-e01.y' ||
                    $opCodes[ 'prozedur' ] === '5-e02.y' ) {
                    $tmp[ 'ohne_anastomose' ]                    = '0';
                 }
            }
            $operations[] = $tmp;
        }
        return $operations;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @return array
     */
    protected function GetSystemicTherapySections( $parameters,  $extract_data )
    {
        $systemicTherapy = array();
        if (isset($extract_data[ 'systemische_therapien' ]) && count($extract_data[ 'systemische_therapien' ] > 0)) {
            foreach ($extract_data[ 'systemische_therapien' ] as $sysTherapy) {
                $tmp = array();
                $tmp[ 'systemische_therapie_id' ]   = isset($sysTherapy[ 'systemische_therapie_id' ]);
                $tmp[ 'systemtherapie' ]            = $this->GetExportCode('therapieart', $sysTherapy[ 'art' ], '');
                $protokoll = $this->GetTherapieVorlagenBezeichnung( $sysTherapy[ 'vorlage_therapie_id' ] );
                $tmp[ 'protokoll' ]                 = HCommon::TrimString( $protokoll, 200, true );
                $tmp[ 'systemtherapie_intention' ]  = $this->GetExportCode('intention', $sysTherapy[ 'intention' ], '');
                $tmp[ 'systemtherapie_beg_datum' ]  = $this->CheckDatum( $sysTherapy[ 'beginn' ] );
                $tmp[ 'systemtherapie_ende_datum' ] = $this->CheckDatum( $sysTherapy[ 'ende' ] );
                $tmp[ 'systemtherapie_ergebnis' ]   = '5';
                if ($sysTherapy[ 'endstatus' ] === 'plan' || $sysTherapy[ 'endstatus' ] === 'abw') {
                    $tmp[ 'systemtherapie_ergebnis' ]   = '1';
                }
                if ($sysTherapy[ 'endstatus' ] === 'abbr') {
                    switch ($sysTherapy[ 'endstatus_grund' ]) {
                        case 'hn':
                        case 'nhn':
                            $tmp[ 'systemtherapie_ergebnis' ]   = '2';
                            break;
                        case 'tod':
                            $tmp[ 'systemtherapie_ergebnis' ]   = '3';
                            break;
                        case 'patw':
                            $tmp[ 'systemtherapie_ergebnis' ]   = '4';
                            break;
                    }
                }
                $tmp[ 'systemtherapie_erfolg' ]     = '6';
                switch ($sysTherapy[ 'best_response' ]) {
                    case 'CR':
                        $tmp[ 'systemtherapie_erfolg' ]     = '1';
                        break;
                    case 'PR':
                        $tmp[ 'systemtherapie_erfolg' ]     = '2';
                        break;
                    case 'SD':
                        $tmp[ 'systemtherapie_erfolg' ]     = '3';
                        break;
                    case 'PD':
                        $tmp[ 'systemtherapie_erfolg' ]     = '4';
                        break;
                }
                $tmp[ 'keine_systemtherapie' ]      = '';
                $systemicTherapy[] = $tmp;
            }
        }

        if (isset($extract_data[ 'therapyplan_deviance' ]) && count($extract_data[ 'therapyplan_deviance' ]) > 0) {
            foreach ($extract_data[ 'therapyplan_deviance' ] as $deviance) {
                foreach (array('chemo', 'immun', 'ah', 'andere') as $type) {
                    if ($deviance[ 'bezug_' . $type ] === '1') {
                        $tmp = array();
                        $tmp['systemische_therapie_id']   = $type . '_' . $deviance[ 'therapieplan_abweichung_id' ];
                        $tmp['systemtherapie']            = '0';
                        $tmp['protokoll']                 = '';
                        $tmp['systemtherapie_intention']  = '';
                        foreach ($extract_data[ 'therapyplan'] as $plan) {
                            if ($plan[ 'therapieplan_id' ] === $deviance[ 'therapieplan_id' ]) {
                                $tmp['systemtherapie_intention']  = $this->GetExportCode('intention', $plan[ $type . '_intention'], '');
                            }
                        }
                        $tmp['systemtherapie_beg_datum']  = '';
                        $tmp['systemtherapie_ende_datum'] = '';
                        $tmp['systemtherapie_ergebnis']   = '6';
                        $tmp['systemtherapie_erfolg']     = '5';
                        $tmp['keine_systemtherapie']      = $this->GetExportCode('therapieplan_abweichung_grund', $deviance[ 'grund' ], '');;
                        $systemicTherapy[] = $tmp;
                    }
                }
            }
        }
        return $systemicTherapy;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @return array
     */
    protected function GetRadioTherapySections( $parameters,  $extract_data )
    {
        $radioTherapy = array();
        if (isset($extract_data[ 'strahlen_therapien' ]) && count($extract_data[ 'strahlen_therapien' ]) > 0) {
            foreach ($extract_data[ 'strahlen_therapien' ] as $rTherapy) {
                $tmp = array();
                $tmp[ 'strahlen_therapie_id' ]        = $rTherapy[ 'strahlentherapie_id' ];
                $tmp[ 'strahlentherapie' ]            = '1';
                $tmp[ 'strahlentherapie_intention' ]  = $this->GetExportCode('intention', $rTherapy[ 'intention' ], '');
                $tmp[ 'strahlentherapie_beg_datum' ]  = $this->CheckDatum( $rTherapy[ 'beginn' ] );
                $tmp[ 'strahlentherapie_ende_datum' ] = $this->CheckDatum( $rTherapy[ 'ende' ] );
                $tmp[ 'strahlentherapie_ergebnis' ]   = '5';
                if ($rTherapy[ 'endstatus' ] === 'plan' || $rTherapy[ 'endstatus' ] === 'abw') {
                    $tmp[ 'strahlentherapie_ergebnis' ]   = '1';
                }
                if ($rTherapy[ 'endstatus' ] === 'abbr' && isset($rTherapy[ 'endstatus_grund' ]) && strlen($rTherapy[ 'endstatus_grund' ]) > 0) {
                    switch ($rTherapy[ 'endstatus_grund' ]) {
                        case 'hn':
                        case 'nhn':
                            $tmp[ 'strahlentherapie_ergebnis' ]   = '2';
                            break;
                        case 'tod':
                            $tmp[ 'strahlentherapie_ergebnis' ]   = '3';
                            break;
                        case 'patw':
                            $tmp[ 'strahlentherapie_ergebnis' ]   = '4';
                            break;
                    }
                }
                $tmp[ 'keine_strahlentherapie' ]      = '';

                $tmp[ 'gesamtdosis' ]                 = $rTherapy[ 'gesamtdosis' ];
                $tmp[ 'boost' ]                       = $rTherapy[ 'boostdosis' ];

                $radioTherapy[] = $tmp;
            }
        }

         foreach ($extract_data[ 'therapyplan_deviance' ] as $deviance) {
             if ($deviance[ 'bezug_strahlen' ] === '1') {
                $tmp = array();
                $tmp['strahlen_therapie_id']   = 'radio_' . $deviance[ 'therapieplan_abweichung_id' ];
                $tmp['strahlentherapie']            = '0';
                $tmp['strahlentherapie_intention']  = '';
                foreach ($extract_data[ 'therapyplan'] as $plan) {
                    if ($plan[ 'therapieplan_id' ] === $deviance[ 'therapieplan_id' ]) {
                        $tmp['strahlentherapie_intention']  = $this->GetExportCode('intention', $plan[ 'strahlen_intention'], '');
                    }
                }
                $tmp['strahlentherapie_beg_datum']  = '';
                $tmp['strahlentherapie_ende_datum'] = '';
                $tmp['strahlentherapie_ergebnis']   = '6';
                $tmp['keine_strahlentherapie']      = $this->GetExportCode('therapieplan_abweichung_grund', $deviance[ 'grund' ], '');;
                $tmp['gesamtdosis']  = '';
                $tmp['boost'] = '';
                $radioTherapy[] = $tmp;
             }
        }
        return $radioTherapy;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @return array
     */
    protected function GetLaborSections($extract_data )
    {
        $lab = array();
        $patient_id    = $extract_data[ 'patient_id' ];
        $erkrankung_id = $extract_data[ 'erkrankung_id' ];
        $query = "
                SELECT
                    lab.labor_id,
                    lv.labor_wert_id,
                    lab.datum,
                    lv.parameter,
                    lv.wert
                FROM
                    labor lab
                LEFT JOIN
                    labor_wert lv
                ON
                    lab.labor_id = lv.labor_id
                WHERE
                    lab.patient_id = '{$patient_id}'
                AND
                    lv.parameter = 'cea'
                AND
                    lv.erkrankung_id= '{$erkrankung_id}'
                AND
                    lv.wert IS NOT NULL
                AND
                    lab.datum IS NOT NULL
            ";
        $labQuery = sql_query_array( $this->m_db, $query );

        foreach ($labQuery as $labor) {
            $tmp = array();
            $tmp[ 'labor_wert_id' ] = $labor[ 'labor_wert_id' ];
            $tmp[ 'cea' ]           = $labor[ 'wert' ];
            $tmp[ 'cea_datum' ]     = $this->CheckDatum( $labor[ 'datum' ] );
            $lab[] = $tmp;
        }

        return $lab;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @return array
     */
    protected function GetAfterCareSection( $parameters, $extract_data )
    {
        $afterCare = array();
        foreach ($extract_data[ 'nachsorgen' ] as $nachsorge) {
            $tmp = array();
            $tmp[ 'nachsorge_id' ]    = $nachsorge[ 'nachsorge_id' ];
            $tmp[ 'nachsorge_datum' ] = $this->CheckDatum($nachsorge[ 'datum' ]);
            $afterCare[] = $tmp;
        }

        return $afterCare;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @return array
     */
    protected function CreateAbschlussSection( $parameters, $extract_data )
    {
        $followup = array();

        $followup[ 'lost_follow_up' ]        = $extract_data[ 'abschlussgrund' ] === 'lost' ? '1' : '0';
        $followup[ 'overall_survival' ]      = '';
        if ($extract_data[ 'abschlussgrund' ] === 'tot') {


            switch ($extract_data[ 'tod_tumorbedingt' ]) {
                case 'tott':
                    $followup[ 'overall_survival' ] = '1';
                    break;
                case 'totn':
                case 'totnt':
                    $followup[ 'overall_survival' ] = '0';
                    break;
                default:
                    $followup[ 'overall_survival' ] = '2';
            }
        }

        $followup[ 'disease_free_survival' ] = '';
        if ( strlen( $extract_data[ 'start_date_rezidiv' ] ) > 0 ) {

            $disease_free_survival = HReports::CalcMonths( $extract_data[ 'startDate' ], $extract_data[ 'start_date_rezidiv' ] );
            $followup[ 'disease_free_survival' ] = $disease_free_survival < 0 ? '' : $disease_free_survival;
        }
        return $followup;
    }


    /**
     *
     *
     * @access
     * @param $extract_data
     * @return string
     */
    protected function GetUidFromData( $extract_data )
    {
        return $extract_data[ 'patient_id' ] . "_" .
               $extract_data[ 'erkrankung_id' ] . "_" .
               $extract_data[ 'tumoridentifikator' ];
    }


    /**
     *
     *
     * @access
     * @param $anamnesen
     * @return string
     */
    protected function GetKoerpergroesse( $anamnesen ) {
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
    protected function GetKoerpergewicht( $anamnesen ) {
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
     * @param $value
     * @return mixed
     */
    protected function KillSn( $value ) {
        return str_replace( '(sn)', '', $value );
    }


    /**
     *
     *
     * @access
     * @param $erkrankung_id
     * @return string
     */
    protected function getReject($erkrankung_id)
    {
        $query = "
            SELECT
                ts.nur_zweitmeinung,
                ts.nur_diagnosesicherung,
                ts.kein_fall

            FROM
                tumorstatus ts

            WHERE
                ts.erkrankung_id = {$erkrankung_id}
        ";
        $result = sql_query_array( $this->m_db, $query );
        $reject = '0';
        if ( false !== $result ) {
            if (isset($result) && count($result) >0) {
                foreach($result as $row) {
                    if ($row['nur_zweitmeinung'] === '1' || $row['nur_diagnosesicherung'] === '1' || $row['kein_fall'] === '1') {
                        $reject = '1';
                    }
                }
            }
        }

        return $reject;
    }


    /**
     *
     *
     * @access
     * @param $erkrankung_id
     * @return string
     */
    protected function getFamilyAnamesis($erkrankung_id)
    {
        $query = "
            SELECT
                fam.karzinom
            FROM
                anamnese_familie fam
            WHERE
                fam.erkrankung_id = {$erkrankung_id}
            AND
                (fam.karzinom='d'
            OR
                fam.karzinom='kore')
        ";

        $result = sql_query_array( $this->m_db, $query );
        if (isset($result) && count($result) >0) {
            return '1';
        }

        return '2';
    }

    /**
     *
     *
     * @access
     * @param $extract_data
     * @return string
     */
    protected function GetClinicalPraefix( $extract_data )
    {
        $query = "
            SELECT
                ts.tnm_praefix

            FROM
                tumorstatus ts

            WHERE
                ts.erkrankung_id = {$extract_data[ 'erkrankung_id' ]}
                AND ts.datum_sicherung BETWEEN '{$extract_data[ 'start_date' ]}' AND '{$extract_data[ 'end_date' ]}'
                AND ts.anlass='p'
                AND IF(ts.t IS NULL, 1, LEFT( ts.t, 1 ) != 'p')
                AND IF(ts.n IS NULL, 1, LEFT( ts.n, 1 ) != 'p')
                AND IF(ts.m IS NULL, 1, LEFT( ts.m, 1 ) != 'p')
                AND ( ts.t IS NOT NULL
                      OR ts.n IS NOT NULL
                      OR ts.m IS NOT NULL )

            ORDER BY
                ts.datum_sicherung DESC,
                ts.sicherungsgrad ASC,
                ts.datum_beurteilung DESC

            LIMIT 0, 1
        ";
        $result = reset( sql_query_array( $this->m_db, $query ) );
        if ( false !== $result ) {
            return $result[ 'tnm_praefix' ];
        }
        return '';
    }




    /**
     *
     *
     * @access
     * @param $extract_data
     * @return string
     */
    protected function GetClinicalG( $extract_data )
    {
        $query = "
            SELECT
                ts.g

            FROM
                tumorstatus ts

            WHERE
                ts.erkrankung_id = {$extract_data[ 'erkrankung_id' ]}
                AND ts.datum_sicherung BETWEEN '{$extract_data[ 'start_date' ]}' AND '{$extract_data[ 'end_date' ]}'
                AND ts.anlass='p'
                AND IF(ts.t IS NULL, 1, LEFT( ts.t, 1 ) != 'p')
                AND IF(ts.n IS NULL, 1, LEFT( ts.n, 1 ) != 'p')
                AND IF(ts.m IS NULL, 1, LEFT( ts.m, 1 ) != 'p')
                AND ( ts.t IS NOT NULL
                      OR ts.n IS NOT NULL
                      OR ts.m IS NOT NULL )

            ORDER BY
                ts.datum_sicherung DESC,
                ts.sicherungsgrad ASC,
                ts.datum_beurteilung DESC

            LIMIT 0, 1
        ";
        $result = reset( sql_query_array( $this->m_db, $query ) );
        if ( false !== $result ) {
            return $result[ 'g' ];
        }
        return '';
    }

}

?>
