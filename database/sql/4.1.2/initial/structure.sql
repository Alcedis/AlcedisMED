/*
AlcedisMED

Copyright (C) 2010-2016  Alcedis GmbH

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES latin1 */;

CREATE TABLE `abschluss` (
  `abschluss_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `abschluss_grund` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `letzter_kontakt_datum` date DEFAULT NULL,
  `todesdatum` date DEFAULT NULL,
  `tod_ursache` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `tod_ursache_text` text COLLATE latin1_german1_ci,
  `tod_ursache_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `tod_ursache_dauer` int(11) DEFAULT NULL,
  `ursache_quelle` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tod_tumorassoziation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `autopsie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `abschluss_ursache` (
  `abschluss_ursache_id` int(11) NOT NULL,
  `abschluss_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `krankheit` varchar(28) COLLATE latin1_german1_ci NOT NULL,
  `krankheit_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `krankheit_text` text COLLATE latin1_german1_ci,
  `krankheit_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `krankheit_dauer` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `anamnese` (
  `anamnese_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `datum_nb` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `groesse` int(11) DEFAULT NULL,
  `gewicht` double DEFAULT NULL,
  `mehrlingseigenschaften` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `entdeckung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorsorge_regelmaessig` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorsorge_intervall` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorsorge_datum_letzte` date DEFAULT NULL,
  `screening` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_autoimmun` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_autoimmun_sjoergren` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_autoimmun_arthritis` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_autoimmun_lupus_ery` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_autoimmun_zoeliakie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_autoimmun_dermatitis` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_autoimmun_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_raucher` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_raucher_dauer` double DEFAULT NULL,
  `risiko_raucher_menge` int(11) DEFAULT NULL,
  `risiko_exraucher` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_exraucher_dauer` double DEFAULT NULL,
  `risiko_exraucher_menge` int(11) DEFAULT NULL,
  `risiko_alkohol` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_medikamente` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_drogen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_pille` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_pille_dauer` int(11) DEFAULT NULL,
  `hormon_substitution` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hormon_substitution_art` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `testosteron_substitution` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `testosteron_substitution_dauer` int(11) DEFAULT NULL,
  `darmerkrankung_jn` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `darmerkrankung_morbus` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `darmerkrankung_colitis` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `darmerkrankung_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_infekt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_infekt_ebv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_infekt_htlv1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_infekt_hiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_infekt_hcv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_infekt_hp` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_infekt_bb` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_infekt_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `hormon_substitution_dauer` int(11) DEFAULT NULL,
  `hpv` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ01` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ02` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ03` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ04` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ05` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ06` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ07` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ08` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ09` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ10` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_transplantation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_transplantation_detail` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_familie_melanom` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_sonnenbrand_kind` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_sonnenbankbesuch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_sonnenschutzmittel` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_sonnenschutzmittel_detail` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_noxen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_noxen_detail` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_chronische_wunden` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beruf_letzter` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `beruf_letzter_dauer` int(11) DEFAULT NULL,
  `beruf_laengster` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `beruf_laengster_dauer` int(11) DEFAULT NULL,
  `beruf_risiko` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beruf_risiko_detail` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzen_lokalisation` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzen_lokalisation_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzen_lokalisation_text` text COLLATE latin1_german1_ci,
  `sy_schmerzen_lokalisation_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzscore` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_dyspnoe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_haemoptnoe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_husten` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_husten_dauer` int(11) DEFAULT NULL,
  `sy_harndrang` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_nykturie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_pollakisurie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_miktion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_restharn` double DEFAULT NULL,
  `sy_harnverhalt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_harnstau` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_harnstau_lokalisation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_haematurie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_para_syndrom` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_para_syndrom_symptom` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_para_syndrom_detail` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_gewichtsverlust` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_gewichtsverlust_2wo` double DEFAULT NULL,
  `sy_gewichtsverlust_3mo` double DEFAULT NULL,
  `sy_fieber` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_nachtschweiss` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_dauer` int(11) DEFAULT NULL,
  `sy_dauer_einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `euroqol` int(11) DEFAULT NULL,
  `lcss` int(11) DEFAULT NULL,
  `fb_dkg` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `fb_dkg_beurt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `iciq_ui` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ics` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `iief5` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ipss` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lq_dkg` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gz_dkg` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ql` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `familien_karzinom` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_jn` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_fap` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_gardner` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_peutz` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_hnpcc` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_turcot` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_polyposis` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_dcc` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_baxgen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_smad2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_smad4` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_kras` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_apc` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_p53` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_cmyc` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_tgfb2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_wiskott_aldrich` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_cvi` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_louis_bar` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_hpc1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_pcap` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_cabp` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_x27_28` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_brca1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_brca2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bethesda` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beratung_genetik` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pot_pde5hemmer` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pot_pde5hemmer_haeufigkeit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pot_vakuumpumpe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pot_skat` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pot_penisprothese` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ecog` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `schwanger` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `menopausenstatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `alter_menarche` int(11) DEFAULT NULL,
  `alter_menopause` int(11) DEFAULT NULL,
  `menopause_iatrogen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `menopause_iatrogen_ursache` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `geburten_lebend` int(11) DEFAULT NULL,
  `geburten_tot` int(11) DEFAULT NULL,
  `geburten_fehl` int(11) DEFAULT NULL,
  `schwangerschaft_erste_alter` int(11) DEFAULT NULL,
  `schwangerschaft_letzte_alter` int(11) DEFAULT NULL,
  `zn_hysterektomie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_lok1` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_lok2` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_lok3` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_lok4` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorbestrahlung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorbestrahlung_diagnose` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `platinresistenz` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_uterus_zervix` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_uterus_zervix_jahr` int(11) DEFAULT NULL,
  `vorop_uterus_zervix_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_uterus_zervix_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_uterus_corpus` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_uterus_corpus_jahr` int(11) DEFAULT NULL,
  `vorop_uterus_corpus_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_uterus_corpus_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_ovar_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_ovar_r_jahr` int(11) DEFAULT NULL,
  `vorop_ovar_r_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_ovar_r_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_ovar_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_ovar_l_jahr` int(11) DEFAULT NULL,
  `vorop_ovar_l_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_ovar_l_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_adnexe_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_adnexe_r_jahr` int(11) DEFAULT NULL,
  `vorop_adnexe_r_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_adnexe_r_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_adnexe_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_adnexe_l_jahr` int(11) DEFAULT NULL,
  `vorop_adnexe_l_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_adnexe_l_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_vulva` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_vulva_jahr` int(11) DEFAULT NULL,
  `vorop_vulva_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_vulva_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_mamma_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_mamma_r_jahr` int(11) DEFAULT NULL,
  `vorop_mamma_r_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_mamma_r_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_mamma_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_mamma_l_jahr` int(11) DEFAULT NULL,
  `vorop_mamma_l_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_mamma_l_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_sonstige` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_sonstige_jahr` int(11) DEFAULT NULL,
  `vorop_sonstige_bem` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `hormon_sterilitaetsbehandlung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hormon_sterilitaetsbehandlung_dauer` int(11) DEFAULT NULL,
  `sonst` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_dauer` int(11) DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `anamnese_erkrankung` (
  `anamnese_erkrankung_id` int(11) NOT NULL,
  `anamnese_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `erkrankung` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_text` text COLLATE latin1_german1_ci,
  `erkrankung_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_text` text COLLATE latin1_german1_ci,
  `morphologie_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `jahr` int(11) DEFAULT NULL,
  `aktuell` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapie1` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapie2` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapie3` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `anamnese_familie` (
  `anamnese_familie_id` int(11) NOT NULL,
  `anamnese_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `karzinom` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `verwandschaftsgrad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankungsalter` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `aufenthalt` (
  `aufenthalt_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `aufnahmenr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufnahmedatum` date DEFAULT NULL,
  `entlassungsdatum` date DEFAULT NULL,
  `fachabteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `begleitmedikation` (
  `begleitmedikation_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `wirkstoff` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `applikation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dosis_wert` double DEFAULT NULL,
  `dosis_einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beginn` date DEFAULT NULL,
  `beginn_nb` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ende` date DEFAULT NULL,
  `fortsetzung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intermittierend` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `behandler` (
  `behandler_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `funktion` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `beratung` (
  `beratung_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `fragebogen_ausgehaendigt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `psychoonkologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `psychoonkologie_dauer` int(11) DEFAULT NULL,
  `hads` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hads_d_depression` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hads_d_angst` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bc_pass_a` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bc_pass_b` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bc_pass_c` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sozialdienst` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `fam_risikosprechstunde` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `fam_risikosprechstunde_erfolgt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `humangenet_beratung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `interdisziplinaer_angeboten` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `interdisziplinaer_durchgefuehrt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ernaehrungsberatung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `brief` (
  `brief_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_dokument_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `zeichen_sender` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `zeichen_empfaenger` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachricht` date DEFAULT NULL,
  `hauptempfaenger_id` int(11) NOT NULL,
  `abs_oberarzt_id` int(11) DEFAULT NULL,
  `abs_assistent_id` int(11) DEFAULT NULL,
  `fotos` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `datenstand_datum` datetime DEFAULT NULL,
  `document_process` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `document_final` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `document_dirty` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `brief_empfaenger` (
  `brief_empfaenger_id` int(11) NOT NULL,
  `brief_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `empfaenger_id` int(11) NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `diagnose` (
  `diagnose_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `diagnose` varchar(28) COLLATE latin1_german1_ci NOT NULL,
  `diagnose_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `diagnose_text` text COLLATE latin1_german1_ci,
  `diagnose_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_text` text COLLATE latin1_german1_ci,
  `ct` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `schleimhautmelanom` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `untersuchung_id` int(11) DEFAULT NULL,
  `rezidiv_von` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `rezidiv_von_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rezidiv_von_text` text COLLATE latin1_german1_ci,
  `rezidiv_von_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokoregionaer` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_1` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_1_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_1_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_1_text` text COLLATE latin1_german1_ci,
  `metast_visz_2` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_2_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_2_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_2_text` text COLLATE latin1_german1_ci,
  `metast_visz_3` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_3_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_3_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_3_text` text COLLATE latin1_german1_ci,
  `metast_visz_4` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_4_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_4_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_4_text` text COLLATE latin1_german1_ci,
  `metast_haut` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `dmp_brustkrebs_eb` (
  `dmp_brustkrebs_eb_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `fall_nr` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `doku_datum` date DEFAULT NULL,
  `einschreibung_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `melde_user_id` int(11) DEFAULT NULL,
  `unterschrift_datum` date DEFAULT NULL,
  `kv_iknr` varchar(35) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_statusergaenzung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vk_gueltig_bis` date DEFAULT NULL,
  `kvk_einlesedatum` date DEFAULT NULL,
  `mani_primaer` date DEFAULT NULL,
  `mani_kontra` date DEFAULT NULL,
  `mani_rezidiv` date DEFAULT NULL,
  `mani_metast` date DEFAULT NULL,
  `anam_brust_links` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_brust_rechts` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_brust_beidseits` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_unt_stanz` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_unt_vakuum` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_unt_offen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_unt_mammo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_unt_sono` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_unt_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `aktueller_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_bet` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_mast` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_sln` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_axilla` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_anderes` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_tis` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_4` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_x` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_x` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_m` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_g` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_unbekannt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_rezeptorstatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_entf_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_entf_sln` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_entf_09` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_entf_10` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_bef_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_bef_sln_neg` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_bef_13` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_bef_4` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_bef_unbekannt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_strahlen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_chemo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_endo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_lok_intra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_lok_thorax` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_lok_axilla` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_praeop` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_exzision` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_mastektomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_leber` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_lunge` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_knochen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_operativ` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_kontra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphoedem` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_schmerz_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_schmerz_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_schmerz_ne` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_psycho_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_psycho_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_psycho_abgelehnt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `termin_datum` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `xml` text COLLATE latin1_german1_ci,
  `xml_protokoll` text COLLATE latin1_german1_ci,
  `xml_status` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `dmp_brustkrebs_ed_2013` (
  `dmp_brustkrebs_ed_2013_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `fall_nr` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `doku_datum` date DEFAULT NULL,
  `einschreibung_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `melde_user_id` int(11) DEFAULT NULL,
  `unterschrift_datum` date DEFAULT NULL,
  `kv_iknr` varchar(35) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_abrechnungsbereich` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_statusergaenzung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_besondere_personengruppe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_dmp_kennzeichnung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_versicherungsschutz_beginn` date DEFAULT NULL,
  `kv_versicherungsschutz_ende` date DEFAULT NULL,
  `vk_gueltig_bis` date DEFAULT NULL,
  `kvk_einlesedatum` date DEFAULT NULL,
  `mani_primaer` date DEFAULT NULL,
  `mani_kontra` date DEFAULT NULL,
  `mani_rezidiv` date DEFAULT NULL,
  `mani_metast` date DEFAULT NULL,
  `anam_brust_links` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_brust_rechts` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_brust_beidseits` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `aktueller_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_bet` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_mast` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_sln` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_axilla` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_anderes` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_neoadjuvant` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_tis` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_4` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_x` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_x` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_m` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_g` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_unbekannt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_rezeptorstatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_her2` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_strahlen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_chemo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_endo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_ah` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_praeop` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_exzision` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_mastektomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_leber` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_lunge` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_knochen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_operativ` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_kontra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphoedem` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `termin_datum` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `xml` text COLLATE latin1_german1_ci,
  `xml_protokoll` text COLLATE latin1_german1_ci,
  `xml_status` int(11) DEFAULT NULL,
  `dmp_brustkrebs_eb_id` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `dmp_brustkrebs_ed_pnp_2013` (
  `dmp_brustkrebs_ed_pnp_2013_id` int(11) NOT NULL,
  `dmp_brustkrebs_ed_2013_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doku_datum` date DEFAULT NULL,
  `einschreibung_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `melde_user_id` int(11) DEFAULT NULL,
  `unterschrift_datum` date DEFAULT NULL,
  `kv_iknr` varchar(35) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_abrechnungsbereich` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_statusergaenzung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_besondere_personengruppe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_dmp_kennzeichnung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_versicherungsschutz_beginn` date DEFAULT NULL,
  `kv_versicherungsschutz_ende` date DEFAULT NULL,
  `vk_gueltig_bis` date DEFAULT NULL,
  `kvk_einlesedatum` date DEFAULT NULL,
  `anam_brust_links` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_brust_rechts` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_brust_beidseits` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `aktueller_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_bet` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_mast` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_sln` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_axilla` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_anderes` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_neoadjuvant` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_tis` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_4` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_x` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_x` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_m` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_g` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_unbekannt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_rezeptorstatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_her2` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_strahlen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_chemo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_endo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_ah` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_praeop` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_exzision` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_mastektomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_leber` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_lunge` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_knochen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_operativ` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_kontra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphoedem` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `termin_datum` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `xml` text COLLATE latin1_german1_ci,
  `xml_protokoll` text COLLATE latin1_german1_ci,
  `xml_status` int(11) DEFAULT NULL,
  `dmp_brustkrebs_eb_id` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `dmp_brustkrebs_fb` (
  `dmp_brustkrebs_fb_id` int(11) NOT NULL,
  `dmp_brustkrebs_eb_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `melde_user_id` int(11) DEFAULT NULL,
  `arztwechsel` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `doku_datum` date DEFAULT NULL,
  `unterschrift_datum` date DEFAULT NULL,
  `kv_iknr` varchar(35) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_statusergaenzung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vk_gueltig_bis` date DEFAULT NULL,
  `kvk_einlesedatum` date DEFAULT NULL,
  `pth_fertig` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaer_strahlen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaer_chemo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaer_endo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_rezidiv_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_rezidiv_intra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_rezidiv_thorax` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_rezidiv_axilla` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_rezidiv_datum` date DEFAULT NULL,
  `neu_kontra_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_kontra_datum` date DEFAULT NULL,
  `neu_metast_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_leber` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_lunge` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_knochen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_datum` date DEFAULT NULL,
  `lymphoedem` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_status_cr` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_status_pr` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_status_nc` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_status_pd` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_praeop` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_exzision` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_mastektomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_operativ` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_kontra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_schmerz_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_schmerz_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_schmerz_ne` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_mammo_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_mammo_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_mammo_ne` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_psycho_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_psycho_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_psycho_abgelehnt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `termin_datum` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `xml` text COLLATE latin1_german1_ci,
  `xml_protokoll` text COLLATE latin1_german1_ci,
  `xml_status` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `dmp_brustkrebs_fd_2013` (
  `dmp_brustkrebs_fd_2013_id` int(11) NOT NULL,
  `dmp_brustkrebs_ed_2013_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `melde_user_id` int(11) DEFAULT NULL,
  `arztwechsel` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `doku_datum` date DEFAULT NULL,
  `unterschrift_datum` date DEFAULT NULL,
  `kv_iknr` varchar(35) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_abrechnungsbereich` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_statusergaenzung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_besondere_personengruppe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_dmp_kennzeichnung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_versicherungsschutz_beginn` date DEFAULT NULL,
  `kv_versicherungsschutz_ende` date DEFAULT NULL,
  `vk_gueltig_bis` date DEFAULT NULL,
  `kvk_einlesedatum` date DEFAULT NULL,
  `einschreibung_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaer_strahlen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaer_chemo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaer_endo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaer_ah` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_rezidiv_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_rezidiv_datum` date DEFAULT NULL,
  `neu_kontra_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_kontra_datum` date DEFAULT NULL,
  `neu_metast_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_leber` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_lunge` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_knochen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_datum` date DEFAULT NULL,
  `lymphoedem` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_status_cr` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_status_pr` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_status_nc` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_status_pd` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_praeop` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_exzision` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_mastektomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_operativ` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_kontra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `termin_datum` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `xml` text COLLATE latin1_german1_ci,
  `xml_protokoll` text COLLATE latin1_german1_ci,
  `xml_status` int(11) DEFAULT NULL,
  `dmp_brustkrebs_eb_id` int(11) DEFAULT NULL,
  `dmp_brustkrebs_fb_id` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `dmp_counter` (
  `arzt_oder_ik` varchar(9) COLLATE latin1_german1_ci DEFAULT NULL,
  `export_datum` date NOT NULL DEFAULT '0000-00-00',
  `lfd_nr` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `dmp_nummern` (
  `org_id` int(11) NOT NULL,
  `nr` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `patient_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `dmp_nummern_2013` (
  `dmp_nummern_2013_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `dmp_nr_start` int(11) NOT NULL,
  `dmp_nr_end` int(11) NOT NULL,
  `dmp_nr_current` int(11) DEFAULT NULL,
  `pool` longtext COLLATE latin1_german1_ci,
  `nr_count` int(11) NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `dmp_version_nbr` (
  `dmp_brustkrebs_eb_id` int(11) DEFAULT NULL,
  `dmp_brustkrebs_fb_id` int(11) DEFAULT NULL,
  `dmp_dokument_id` varchar(20) COLLATE latin1_german1_ci DEFAULT NULL,
  `zeitpunkt` datetime DEFAULT NULL,
  `version_nbr` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `dmp_version_nbr_2013` (
  `dmp_brustkrebs_ed_2013_id` int(11) DEFAULT NULL,
  `dmp_brustkrebs_ed_pnp_2013_id` int(11) DEFAULT NULL,
  `dmp_brustkrebs_fd_2013_id` int(11) DEFAULT NULL,
  `document_id` varchar(64) COLLATE latin1_german1_ci DEFAULT NULL,
  `bsnr` varchar(32) COLLATE latin1_german1_ci DEFAULT NULL,
  `version_nbr` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `dokument` (
  `dokument_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `keywords` text COLLATE latin1_german1_ci,
  `dokument` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `doc_type` varchar(5) COLLATE latin1_german1_ci NOT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `eingriff` (
  `eingriff_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `notfall` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `operateur1_id` int(11) DEFAULT NULL,
  `operateur2_id` int(11) DEFAULT NULL,
  `therapieplan_id` int(11) DEFAULT NULL,
  `dauer` int(11) DEFAULT NULL,
  `asa` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `wundkontamination_cdc` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `interdisziplinaer` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `urologie_bet` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `chirurgie_bet` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `diagnose_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_diagnostik` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_staging` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_primaertumor` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_lk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_metastasen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_rezidiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_nachresektion` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_transplantation_autolog` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_transplantation_allogen_v` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_transplantation_allogen_nv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_transplantation_syngen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `verwandschaftsgrad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_revision` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_rekonstruktion` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `mark` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mark_mammo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `mark_sono` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `mark_mrt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `mark_abstand` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `stomaposition` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mesorektale_faszie` int(11) DEFAULT NULL,
  `schnellschnitt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `schnellschnitt_dauer` int(11) DEFAULT NULL,
  `intraop_roe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraop_roe_ergebnis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `blasenkatheter` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `thromboseprophylaxe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `antibiotikaprophylaxe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraop_sono` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraop_sono_ergebnis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraop_mrt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraop_mrt_ergebnis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `postop_roentgen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `postop_roentgen_ergebnis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `postop_sono` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `postop_sono_ergebnis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `postop_mrt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `postop_mrt_ergebnis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `stammzellenmobilisierung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `leukapheresen_anzahl` int(11) DEFAULT NULL,
  `stamm_sep_datum` date DEFAULT NULL,
  `stamm_sep_menge` int(11) DEFAULT NULL,
  `stamm_sep_menge_absolut` int(11) DEFAULT NULL,
  `stamm_sep_cd34_konz` double DEFAULT NULL,
  `stamm_sep_cd34_konz_absolut` int(11) DEFAULT NULL,
  `stamm_purging` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `stamm_purging_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `chim1_datum` date DEFAULT NULL,
  `chim1_wert` double DEFAULT NULL,
  `chim2_datum` date DEFAULT NULL,
  `chim2_wert` double DEFAULT NULL,
  `chim3_datum` date DEFAULT NULL,
  `chim3_wert` double DEFAULT NULL,
  `chim4_datum` date DEFAULT NULL,
  `chim4_wert` double DEFAULT NULL,
  `dli1_datum` date DEFAULT NULL,
  `dli1_wert` double DEFAULT NULL,
  `dli2_datum` date DEFAULT NULL,
  `dli2_wert` double DEFAULT NULL,
  `erholung_leukozyten_datum` date DEFAULT NULL,
  `erholung_granulozyten_datum` date DEFAULT NULL,
  `erholung_thrombozyten_datum` date DEFAULT NULL,
  `erholung_gesamt_datum` date DEFAULT NULL,
  `axilla_sampling` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `axilla_nein_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `res_oberlappen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `res_mittellappen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `res_unterlappen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tme` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `pme` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ther_koloskopie_vollstaendig` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_verfahren` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nerverhalt_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphadenektomie_methode_prostata` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraop_bestrahlung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraop_bestrahlung_dosis` double DEFAULT NULL,
  `intraop_zytostatika` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraop_zytostatika_art` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `hypertherme_perfusion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `plastischer_verschluss` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `laparotomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `peritonealzytologie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `peritonealbiopsie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `adnexexstirpation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hysterektomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `omentektomie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphonodektomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sln_nein_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sentinel_nicht_detektierbar` varchar(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sln_markierung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sln_anzahl` int(11) DEFAULT NULL,
  `sln_parasternal` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sln_restaktivitaet` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sln_schnellschnitt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sln_schnellschnitt_befall` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sln_schnellschnitt_dauer_versendung` int(11) DEFAULT NULL,
  `sln_schnellschnitt_dauer_eingang` int(11) DEFAULT NULL,
  `blutverlust` int(11) DEFAULT NULL,
  `polypen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `polypen_anz_gef` int(11) DEFAULT NULL,
  `polypen_anz_entf` int(11) DEFAULT NULL,
  `polypen_op_areal` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aszites_volumen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `peritonealkarzinose_darm` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `peritonealkarzinose_becken` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `peritonealkarzinose_mittelbauch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `peritonealkarzinose_zwerchfell` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_a1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_a2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_a3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_b1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_b2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_b3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_c1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_c2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_c3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_groesse` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_detail` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_a1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_a2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_a3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_b1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_b2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_b3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_c1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_c2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_c3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `r0limit_aetas` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `r0limit_komplikation` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `r0limit_lokalisation` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `r0limit_lokalisation_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `r0limit_wunsch` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `r0limit_wunsch_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `r0limit_sonstige` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `r0limit_sonstige_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_ovar` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_ovar_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_ovar_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_tube` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_tube_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_tube_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_milz` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_milz_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_lk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_lk_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_ureter` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_ureter_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_douglas` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_douglas_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_vaginalstumpf` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_vaginalstumpf_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_duenndarm` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_duenndarm_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_zwerchfell` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_zwerchfell_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_magen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_magen_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_lsm` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_lsm_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_blasenwand` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_blasenwand_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_beckenwand` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_beckenwand_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_mesenterium` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_mesenterium_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_dickdarm` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_dickdarm_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_dickdarmschleimhaut` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_dickdarmschleimhaut_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_omentum_majus` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_omentum_majus_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_bauchwand` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_bauchwand_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_blasenschleimhaut` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_blasenschleimhaut_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_uterus` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_uterus_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_bursa` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_bursa_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_vagina` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_vagina_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_portio` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_portio_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_cervix` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_cervix_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_vulva` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_vulva_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_urethra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_urethra_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_sonst` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_sonst_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `beatmung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `beatmung_dauer` int(11) DEFAULT NULL,
  `intensiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `intensiv_dauer` int(11) DEFAULT NULL,
  `antibiotika` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `thrombose` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `antibiotika_dauer` int(11) DEFAULT NULL,
  `transfusion` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `transfusion_anzahl_ek` int(11) DEFAULT NULL,
  `transfusion_anzahl_tk` int(11) DEFAULT NULL,
  `transfusion_anzahl_ffp` int(11) DEFAULT NULL,
  `datum_drainageentfernung` date DEFAULT NULL,
  `datum_katheterentfernung` date DEFAULT NULL,
  `cystogramm1` int(11) DEFAULT NULL,
  `cystogramm2` int(11) DEFAULT NULL,
  `leckage_primaer` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `leckage_sekundaer` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `cf_entfernung` int(11) DEFAULT NULL,
  `dk_entfernung` int(11) DEFAULT NULL,
  `dk_neuanlage` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dk_entlassung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphocele` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphocele_detail` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `wundabstrich` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `wundabstrich_ergebnis` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `eingriff_ops` (
  `eingriff_ops_id` int(11) NOT NULL,
  `eingriff_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `prozedur` varchar(29) COLLATE latin1_german1_ci NOT NULL,
  `prozedur_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `prozedur_text` text COLLATE latin1_german1_ci,
  `prozedur_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `diagnose_id` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `ekr` (
  `ekr_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `meldetyp` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `datum` date NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `meldebegruendung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `wandlung_diagnose` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `grund` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterrichtet_krankheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `einzugsgebiet` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `datum_einverstaendnis` date DEFAULT NULL,
  `abteilung` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `sh_wohnort` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `weiterleitung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `weiterleitung_datum` date DEFAULT NULL,
  `forschungsvorhaben` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `forschungsvorhaben_datum` date DEFAULT NULL,
  `vermutete_tumorursachen` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `export_for_onkeyline` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachsorgeprogramm` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachsorgepassnr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachsorge_user_id` int(11) DEFAULT NULL,
  `nachsorgetermin` date DEFAULT NULL,
  `mitteilung` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `email` (
  `email_id` int(11) NOT NULL,
  `bez` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `email_from` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `template` text COLLATE latin1_german1_ci
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `erkrankung` (
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `erkrankung` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `erkrankung_detail` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beschreibung` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zweiterkrankung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `fallkennzeichen` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_relevant` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_relevant_haut` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rezidiv_bei_erstvorstellung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `notfall` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `einweiser_id` int(11) DEFAULT NULL,
  `nachsorgepassnummer` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `erkrankung_synchron` (
  `erkrankung_synchron_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `erkrankung_synchron` int(11) NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `export_case_log` (
  `export_case_log_id` int(11) NOT NULL,
  `export_log_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `diagnose_seite` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anlass` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hash` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL,
  `createtime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `export_history` (
  `export_history_id` int(11) NOT NULL,
  `export_log_id` int(11) NOT NULL,
  `export_name` varchar(50) COLLATE latin1_german1_ci NOT NULL,
  `org_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `filter` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `file` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `export_log` (
  `export_log_id` int(11) NOT NULL,
  `export_name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `export_nr` int(11) NOT NULL,
  `next_tan` int(11) DEFAULT NULL,
  `export_unique_id` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `org_id` int(11) NOT NULL,
  `melder_id` int(11) NOT NULL,
  `parameters` longtext COLLATE latin1_german1_ci,
  `finished` tinyint(1) DEFAULT '0',
  `createuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL,
  `createtime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `export_patient_ids_log` (
  `export_patient_ids_log_id` int(11) NOT NULL,
  `export_name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `export_unique_id` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `patient_id` int(11) NOT NULL,
  `export_log_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `export_section_log` (
  `export_section_log_id` int(11) NOT NULL,
  `section_uid` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `export_case_log_id` int(11) NOT NULL,
  `data_changed` tinyint(1) DEFAULT '0',
  `meldungskennzeichen` char(10) COLLATE latin1_german1_ci DEFAULT NULL,
  `melde_uid` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `block` char(255) COLLATE latin1_german1_ci NOT NULL,
  `daten` longtext COLLATE latin1_german1_ci,
  `valid` int(11) DEFAULT '0',
  `errors` longtext COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL,
  `createtime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `exp_ekrrp_log` (
  `exp_ekrrp_log_id` int(11) NOT NULL,
  `export_id` int(11) DEFAULT NULL,
  `ekr_id` int(11) DEFAULT NULL,
  `valid` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `errors` text COLLATE latin1_german1_ci,
  `org_id` int(11) DEFAULT NULL,
  `von` date NOT NULL,
  `bis` date DEFAULT NULL,
  `createuser` varchar(11) COLLATE latin1_german1_ci DEFAULT NULL,
  `createtime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `exp_gekid_log` (
  `exp_gekid_log_id` int(11) NOT NULL,
  `export_id` int(11) DEFAULT NULL,
  `ekr_id` int(11) DEFAULT NULL,
  `valid` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `errors` text COLLATE latin1_german1_ci,
  `org_id` int(11) DEFAULT NULL,
  `von` date NOT NULL,
  `bis` date DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `exp_gkr_log` (
  `exp_gkr_log_id` int(11) NOT NULL,
  `export_id` int(11) DEFAULT NULL,
  `ekr_id` int(11) DEFAULT NULL,
  `valid` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `errors` text COLLATE latin1_german1_ci,
  `org_id` int(11) DEFAULT NULL,
  `von` date NOT NULL,
  `bis` date DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `exp_krbw_ng_log` (
  `exp_krbw_ng_log_id` int(11) NOT NULL,
  `export_id` int(11) DEFAULT NULL,
  `ekr_id` int(11) DEFAULT NULL,
  `valid` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `errors` text COLLATE latin1_german1_ci,
  `org_id` int(11) DEFAULT NULL,
  `von` date NOT NULL,
  `bis` date DEFAULT NULL,
  `createuser` varchar(11) COLLATE latin1_german1_ci DEFAULT NULL,
  `createtime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `exp_krhe_log` (
  `exp_krhe_log_id` int(11) NOT NULL,
  `export_id` int(11) DEFAULT NULL,
  `ekr_id` int(11) DEFAULT NULL,
  `valid` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `errors` text COLLATE latin1_german1_ci,
  `org_id` int(11) DEFAULT NULL,
  `von` date NOT NULL,
  `bis` date DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `foto` (
  `foto_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `keywords` text COLLATE latin1_german1_ci,
  `foto` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `img_type` varchar(5) COLLATE latin1_german1_ci NOT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `fragebogen` (
  `fragebogen_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_fragebogen_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `fragebogen_frage` (
  `fragebogen_frage_id` int(11) NOT NULL,
  `fragebogen_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_fragebogen_id` int(11) NOT NULL,
  `vorlage_fragebogen_frage_id` int(11) NOT NULL,
  `antwort` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `histologie` (
  `histologie_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `art` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `histologie_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `diagnose_seite` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `eingriff_id` int(11) DEFAULT NULL,
  `untersuchung_id` int(11) DEFAULT NULL,
  `referenzpathologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `anzahl_praeparate` int(11) DEFAULT NULL,
  `groesse_x` double DEFAULT NULL,
  `groesse_y` double DEFAULT NULL,
  `groesse_z` double DEFAULT NULL,
  `prostatagewicht` double DEFAULT NULL,
  `multizentrisch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `multifokal` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_text` text COLLATE latin1_german1_ci,
  `morphologie_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_erg1` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_erg1_text` text COLLATE latin1_german1_ci,
  `morphologie_erg1_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_erg2` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_erg2_text` text COLLATE latin1_german1_ci,
  `morphologie_erg2_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_erg3` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_erg3_text` text COLLATE latin1_german1_ci,
  `morphologie_erg3_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `unauffaellig` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ptnm_praefix` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pn` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pm` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `g` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `l` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `v` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `invasionstiefe` double DEFAULT NULL,
  `invasionsbreite` double DEFAULT NULL,
  `r` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ppn` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `konisation_exzision` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `konisation_x` double DEFAULT NULL,
  `konisation_y` double DEFAULT NULL,
  `konisation_z` double DEFAULT NULL,
  `resektionsrand` double DEFAULT NULL,
  `anz_rand_positiv` smallint(6) DEFAULT NULL,
  `status_resektionsrand_organ` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `status_resektionsrand_circumferentiell` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `resektionsrand_circumferentiell` double DEFAULT NULL,
  `resektionsrand_oral` double DEFAULT NULL,
  `resektionsrand_aboral` double DEFAULT NULL,
  `resektionsrand_lateral` double DEFAULT NULL,
  `tumoranteil_turp` smallint(6) DEFAULT NULL,
  `mercury` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `msi` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `msi_mutation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `msi_stabilitaet` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kapselueberschreitung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tubulusbildung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kernpolymorphie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mitoserate` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ki67` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `ki67_index` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gleason1` int(11) DEFAULT NULL,
  `gleason2` int(11) DEFAULT NULL,
  `gleason3` int(11) DEFAULT NULL,
  `gleason4_anteil` smallint(6) DEFAULT NULL,
  `parametrienbefall_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `parametrienbefall_r_infiltration` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `parametrienbefall_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `parametrienbefall_l_infiltration` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `blasteninfiltration` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `blasteninfiltration_prozent` int(11) DEFAULT NULL,
  `sbr_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `sbr_anz_positiv` smallint(6) DEFAULT NULL,
  `sbr_1_laenge` smallint(11) DEFAULT NULL,
  `sbr_1_tumoranteil` smallint(11) DEFAULT NULL,
  `sbr_1_gleason1` smallint(6) DEFAULT NULL,
  `sbr_1_gleason2` smallint(6) DEFAULT NULL,
  `sbr_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbr_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbr_1_gleason4` smallint(6) DEFAULT NULL,
  `sbr_1_diff` smallint(6) DEFAULT NULL,
  `sbr_2_laenge` smallint(11) DEFAULT NULL,
  `sbr_2_tumoranteil` smallint(11) DEFAULT NULL,
  `sbr_2_gleason1` smallint(6) DEFAULT NULL,
  `sbr_2_gleason2` smallint(6) DEFAULT NULL,
  `sbr_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbr_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbr_2_gleason4` smallint(6) DEFAULT NULL,
  `sbr_2_diff` smallint(6) DEFAULT NULL,
  `sbr_3_laenge` smallint(11) DEFAULT NULL,
  `sbr_3_tumoranteil` smallint(11) DEFAULT NULL,
  `sbr_3_gleason1` smallint(6) DEFAULT NULL,
  `sbr_3_gleason2` smallint(6) DEFAULT NULL,
  `sbr_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbr_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbr_3_gleason4` smallint(6) DEFAULT NULL,
  `sbr_3_diff` smallint(6) DEFAULT NULL,
  `sbr_4_laenge` smallint(11) DEFAULT NULL,
  `sbr_4_tumoranteil` smallint(11) DEFAULT NULL,
  `sbr_4_gleason1` smallint(6) DEFAULT NULL,
  `sbr_4_gleason2` smallint(6) DEFAULT NULL,
  `sbr_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbr_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbr_4_gleason4` smallint(6) DEFAULT NULL,
  `sbr_4_diff` smallint(6) DEFAULT NULL,
  `sbr_5_laenge` smallint(11) DEFAULT NULL,
  `sbr_5_tumoranteil` smallint(11) DEFAULT NULL,
  `sbr_5_gleason1` smallint(6) DEFAULT NULL,
  `sbr_5_gleason2` smallint(6) DEFAULT NULL,
  `sbr_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbr_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbr_5_gleason4` smallint(6) DEFAULT NULL,
  `sbr_5_diff` smallint(6) DEFAULT NULL,
  `sbl_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `sbl_anz_positiv` smallint(6) DEFAULT NULL,
  `sbl_1_laenge` smallint(11) DEFAULT NULL,
  `sbl_1_tumoranteil` smallint(11) DEFAULT NULL,
  `sbl_1_gleason1` smallint(6) DEFAULT NULL,
  `sbl_1_gleason2` smallint(6) DEFAULT NULL,
  `sbl_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbl_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbl_1_gleason4` smallint(6) DEFAULT NULL,
  `sbl_1_diff` smallint(6) DEFAULT NULL,
  `sbl_2_laenge` smallint(11) DEFAULT NULL,
  `sbl_2_tumoranteil` smallint(11) DEFAULT NULL,
  `sbl_2_gleason1` smallint(6) DEFAULT NULL,
  `sbl_2_gleason2` smallint(6) DEFAULT NULL,
  `sbl_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbl_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbl_2_gleason4` smallint(6) DEFAULT NULL,
  `sbl_2_diff` smallint(6) DEFAULT NULL,
  `sbl_3_laenge` smallint(11) DEFAULT NULL,
  `sbl_3_tumoranteil` smallint(11) DEFAULT NULL,
  `sbl_3_gleason1` smallint(6) DEFAULT NULL,
  `sbl_3_gleason2` smallint(6) DEFAULT NULL,
  `sbl_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbl_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbl_3_gleason4` smallint(6) DEFAULT NULL,
  `sbl_3_diff` smallint(6) DEFAULT NULL,
  `sbl_4_laenge` smallint(11) DEFAULT NULL,
  `sbl_4_tumoranteil` smallint(11) DEFAULT NULL,
  `sbl_4_gleason1` smallint(6) DEFAULT NULL,
  `sbl_4_gleason2` smallint(6) DEFAULT NULL,
  `sbl_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbl_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbl_4_gleason4` smallint(6) DEFAULT NULL,
  `sbl_4_diff` smallint(6) DEFAULT NULL,
  `sbl_5_laenge` smallint(11) DEFAULT NULL,
  `sbl_5_tumoranteil` smallint(11) DEFAULT NULL,
  `sbl_5_gleason1` smallint(6) DEFAULT NULL,
  `sbl_5_gleason2` smallint(6) DEFAULT NULL,
  `sbl_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbl_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbl_5_gleason4` smallint(6) DEFAULT NULL,
  `sbl_5_diff` smallint(6) DEFAULT NULL,
  `blr_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `blr_anz_positiv` smallint(6) DEFAULT NULL,
  `blr_1_laenge` smallint(11) DEFAULT NULL,
  `blr_1_tumoranteil` smallint(11) DEFAULT NULL,
  `blr_1_gleason1` smallint(6) DEFAULT NULL,
  `blr_1_gleason2` smallint(6) DEFAULT NULL,
  `blr_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `blr_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `blr_1_gleason4` smallint(6) DEFAULT NULL,
  `blr_1_diff` smallint(6) DEFAULT NULL,
  `blr_2_laenge` smallint(11) DEFAULT NULL,
  `blr_2_tumoranteil` smallint(11) DEFAULT NULL,
  `blr_2_gleason1` smallint(6) DEFAULT NULL,
  `blr_2_gleason2` smallint(6) DEFAULT NULL,
  `blr_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `blr_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `blr_2_gleason4` smallint(6) DEFAULT NULL,
  `blr_2_diff` smallint(6) DEFAULT NULL,
  `blr_3_laenge` smallint(11) DEFAULT NULL,
  `blr_3_tumoranteil` smallint(11) DEFAULT NULL,
  `blr_3_gleason1` smallint(6) DEFAULT NULL,
  `blr_3_gleason2` smallint(6) DEFAULT NULL,
  `blr_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `blr_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `blr_3_gleason4` smallint(6) DEFAULT NULL,
  `blr_3_diff` smallint(6) DEFAULT NULL,
  `blr_4_laenge` smallint(11) DEFAULT NULL,
  `blr_4_tumoranteil` smallint(11) DEFAULT NULL,
  `blr_4_gleason1` smallint(6) DEFAULT NULL,
  `blr_4_gleason2` smallint(6) DEFAULT NULL,
  `blr_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `blr_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `blr_4_gleason4` smallint(6) DEFAULT NULL,
  `blr_4_diff` smallint(6) DEFAULT NULL,
  `blr_5_laenge` smallint(11) DEFAULT NULL,
  `blr_5_tumoranteil` smallint(11) DEFAULT NULL,
  `blr_5_gleason1` smallint(6) DEFAULT NULL,
  `blr_5_gleason2` smallint(6) DEFAULT NULL,
  `blr_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `blr_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `blr_5_gleason4` smallint(6) DEFAULT NULL,
  `blr_5_diff` smallint(6) DEFAULT NULL,
  `bll_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `bll_anz_positiv` smallint(6) DEFAULT NULL,
  `bll_1_laenge` smallint(11) DEFAULT NULL,
  `bll_1_tumoranteil` smallint(11) DEFAULT NULL,
  `bll_1_gleason1` smallint(6) DEFAULT NULL,
  `bll_1_gleason2` smallint(6) DEFAULT NULL,
  `bll_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bll_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bll_1_gleason4` smallint(6) DEFAULT NULL,
  `bll_1_diff` smallint(6) DEFAULT NULL,
  `bll_2_laenge` smallint(11) DEFAULT NULL,
  `bll_2_tumoranteil` smallint(11) DEFAULT NULL,
  `bll_2_gleason1` smallint(6) DEFAULT NULL,
  `bll_2_gleason2` smallint(6) DEFAULT NULL,
  `bll_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bll_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bll_2_gleason4` smallint(6) DEFAULT NULL,
  `bll_2_diff` smallint(6) DEFAULT NULL,
  `bll_3_laenge` smallint(11) DEFAULT NULL,
  `bll_3_tumoranteil` smallint(11) DEFAULT NULL,
  `bll_3_gleason1` smallint(6) DEFAULT NULL,
  `bll_3_gleason2` smallint(6) DEFAULT NULL,
  `bll_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bll_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bll_3_gleason4` smallint(6) DEFAULT NULL,
  `bll_3_diff` smallint(6) DEFAULT NULL,
  `bll_4_laenge` smallint(11) DEFAULT NULL,
  `bll_4_tumoranteil` smallint(11) DEFAULT NULL,
  `bll_4_gleason1` smallint(6) DEFAULT NULL,
  `bll_4_gleason2` smallint(6) DEFAULT NULL,
  `bll_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bll_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bll_4_gleason4` smallint(6) DEFAULT NULL,
  `bll_4_diff` smallint(6) DEFAULT NULL,
  `bll_5_laenge` smallint(11) DEFAULT NULL,
  `bll_5_tumoranteil` smallint(11) DEFAULT NULL,
  `bll_5_gleason1` smallint(6) DEFAULT NULL,
  `bll_5_gleason2` smallint(6) DEFAULT NULL,
  `bll_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bll_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bll_5_gleason4` smallint(6) DEFAULT NULL,
  `bll_5_diff` smallint(6) DEFAULT NULL,
  `br_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `br_anz_positiv` smallint(6) DEFAULT NULL,
  `br_1_laenge` smallint(11) DEFAULT NULL,
  `br_1_tumoranteil` smallint(11) DEFAULT NULL,
  `br_1_gleason1` smallint(6) DEFAULT NULL,
  `br_1_gleason2` smallint(6) DEFAULT NULL,
  `br_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `br_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `br_1_gleason4` smallint(6) DEFAULT NULL,
  `br_1_diff` smallint(6) DEFAULT NULL,
  `br_2_laenge` smallint(11) DEFAULT NULL,
  `br_2_tumoranteil` smallint(11) DEFAULT NULL,
  `br_2_gleason1` smallint(6) DEFAULT NULL,
  `br_2_gleason2` smallint(6) DEFAULT NULL,
  `br_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `br_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `br_2_gleason4` smallint(6) DEFAULT NULL,
  `br_2_diff` smallint(6) DEFAULT NULL,
  `br_3_laenge` smallint(11) DEFAULT NULL,
  `br_3_tumoranteil` smallint(11) DEFAULT NULL,
  `br_3_gleason1` smallint(6) DEFAULT NULL,
  `br_3_gleason2` smallint(6) DEFAULT NULL,
  `br_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `br_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `br_3_gleason4` smallint(6) DEFAULT NULL,
  `br_3_diff` smallint(6) DEFAULT NULL,
  `br_4_laenge` smallint(11) DEFAULT NULL,
  `br_4_tumoranteil` smallint(11) DEFAULT NULL,
  `br_4_gleason1` smallint(6) DEFAULT NULL,
  `br_4_gleason2` smallint(6) DEFAULT NULL,
  `br_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `br_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `br_4_gleason4` smallint(6) DEFAULT NULL,
  `br_4_diff` smallint(6) DEFAULT NULL,
  `br_5_laenge` smallint(11) DEFAULT NULL,
  `br_5_tumoranteil` smallint(11) DEFAULT NULL,
  `br_5_gleason1` smallint(6) DEFAULT NULL,
  `br_5_gleason2` smallint(6) DEFAULT NULL,
  `br_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `br_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `br_5_gleason4` smallint(6) DEFAULT NULL,
  `br_5_diff` smallint(6) DEFAULT NULL,
  `bl_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `bl_anz_positiv` smallint(6) DEFAULT NULL,
  `bl_1_laenge` smallint(11) DEFAULT NULL,
  `bl_1_tumoranteil` smallint(11) DEFAULT NULL,
  `bl_1_gleason1` smallint(6) DEFAULT NULL,
  `bl_1_gleason2` smallint(6) DEFAULT NULL,
  `bl_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bl_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bl_1_gleason4` smallint(6) DEFAULT NULL,
  `bl_1_diff` smallint(6) DEFAULT NULL,
  `bl_2_laenge` smallint(11) DEFAULT NULL,
  `bl_2_tumoranteil` smallint(11) DEFAULT NULL,
  `bl_2_gleason1` smallint(6) DEFAULT NULL,
  `bl_2_gleason2` smallint(6) DEFAULT NULL,
  `bl_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bl_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bl_2_gleason4` smallint(6) DEFAULT NULL,
  `bl_2_diff` smallint(6) DEFAULT NULL,
  `bl_3_laenge` smallint(11) DEFAULT NULL,
  `bl_3_tumoranteil` smallint(11) DEFAULT NULL,
  `bl_3_gleason1` smallint(6) DEFAULT NULL,
  `bl_3_gleason2` smallint(6) DEFAULT NULL,
  `bl_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bl_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bl_3_gleason4` smallint(6) DEFAULT NULL,
  `bl_3_diff` smallint(6) DEFAULT NULL,
  `bl_4_laenge` smallint(11) DEFAULT NULL,
  `bl_4_tumoranteil` smallint(11) DEFAULT NULL,
  `bl_4_gleason1` smallint(6) DEFAULT NULL,
  `bl_4_gleason2` smallint(6) DEFAULT NULL,
  `bl_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bl_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bl_4_gleason4` smallint(6) DEFAULT NULL,
  `bl_4_diff` smallint(6) DEFAULT NULL,
  `bl_5_laenge` smallint(11) DEFAULT NULL,
  `bl_5_tumoranteil` smallint(11) DEFAULT NULL,
  `bl_5_gleason1` smallint(6) DEFAULT NULL,
  `bl_5_gleason2` smallint(6) DEFAULT NULL,
  `bl_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bl_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bl_5_gleason4` smallint(6) DEFAULT NULL,
  `bl_5_diff` smallint(6) DEFAULT NULL,
  `tr_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `tr_anz_positiv` smallint(6) DEFAULT NULL,
  `tr_1_laenge` smallint(11) DEFAULT NULL,
  `tr_1_tumoranteil` smallint(11) DEFAULT NULL,
  `tr_1_gleason1` smallint(6) DEFAULT NULL,
  `tr_1_gleason2` smallint(6) DEFAULT NULL,
  `tr_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tr_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tr_1_gleason4` smallint(6) DEFAULT NULL,
  `tr_1_diff` smallint(6) DEFAULT NULL,
  `tr_2_laenge` smallint(11) DEFAULT NULL,
  `tr_2_tumoranteil` smallint(11) DEFAULT NULL,
  `tr_2_gleason1` smallint(6) DEFAULT NULL,
  `tr_2_gleason2` smallint(6) DEFAULT NULL,
  `tr_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tr_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tr_2_gleason4` smallint(6) DEFAULT NULL,
  `tr_2_diff` smallint(6) DEFAULT NULL,
  `tr_3_laenge` smallint(11) DEFAULT NULL,
  `tr_3_tumoranteil` smallint(11) DEFAULT NULL,
  `tr_3_gleason1` smallint(6) DEFAULT NULL,
  `tr_3_gleason2` smallint(6) DEFAULT NULL,
  `tr_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tr_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tr_3_gleason4` smallint(6) DEFAULT NULL,
  `tr_3_diff` smallint(6) DEFAULT NULL,
  `tr_4_laenge` smallint(11) DEFAULT NULL,
  `tr_4_tumoranteil` smallint(11) DEFAULT NULL,
  `tr_4_gleason1` smallint(6) DEFAULT NULL,
  `tr_4_gleason2` smallint(6) DEFAULT NULL,
  `tr_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tr_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tr_4_gleason4` smallint(6) DEFAULT NULL,
  `tr_4_diff` smallint(6) DEFAULT NULL,
  `tr_5_laenge` smallint(11) DEFAULT NULL,
  `tr_5_tumoranteil` smallint(11) DEFAULT NULL,
  `tr_5_gleason1` smallint(6) DEFAULT NULL,
  `tr_5_gleason2` smallint(6) DEFAULT NULL,
  `tr_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tr_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tr_5_gleason4` smallint(6) DEFAULT NULL,
  `tr_5_diff` smallint(6) DEFAULT NULL,
  `tl_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `tl_anz_positiv` smallint(6) DEFAULT NULL,
  `tl_1_laenge` smallint(11) DEFAULT NULL,
  `tl_1_tumoranteil` smallint(11) DEFAULT NULL,
  `tl_1_gleason1` smallint(6) DEFAULT NULL,
  `tl_1_gleason2` smallint(6) DEFAULT NULL,
  `tl_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tl_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tl_1_gleason4` smallint(6) DEFAULT NULL,
  `tl_1_diff` smallint(6) DEFAULT NULL,
  `tl_2_laenge` smallint(11) DEFAULT NULL,
  `tl_2_tumoranteil` smallint(11) DEFAULT NULL,
  `tl_2_gleason1` smallint(6) DEFAULT NULL,
  `tl_2_gleason2` smallint(6) DEFAULT NULL,
  `tl_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tl_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tl_2_gleason4` smallint(6) DEFAULT NULL,
  `tl_2_diff` smallint(6) DEFAULT NULL,
  `tl_3_laenge` smallint(11) DEFAULT NULL,
  `tl_3_tumoranteil` smallint(11) DEFAULT NULL,
  `tl_3_gleason1` smallint(6) DEFAULT NULL,
  `tl_3_gleason2` smallint(6) DEFAULT NULL,
  `tl_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tl_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tl_3_gleason4` smallint(6) DEFAULT NULL,
  `tl_3_diff` smallint(6) DEFAULT NULL,
  `tl_4_laenge` smallint(11) DEFAULT NULL,
  `tl_4_tumoranteil` smallint(11) DEFAULT NULL,
  `tl_4_gleason1` smallint(6) DEFAULT NULL,
  `tl_4_gleason2` smallint(6) DEFAULT NULL,
  `tl_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tl_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tl_4_gleason4` smallint(6) DEFAULT NULL,
  `tl_4_diff` smallint(6) DEFAULT NULL,
  `tl_5_laenge` smallint(11) DEFAULT NULL,
  `tl_5_tumoranteil` smallint(11) DEFAULT NULL,
  `tl_5_gleason1` smallint(6) DEFAULT NULL,
  `tl_5_gleason2` smallint(6) DEFAULT NULL,
  `tl_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tl_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tl_5_gleason4` smallint(6) DEFAULT NULL,
  `tl_5_diff` smallint(6) DEFAULT NULL,
  `mlr_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `mlr_anz_positiv` smallint(6) DEFAULT NULL,
  `mlr_1_laenge` smallint(11) DEFAULT NULL,
  `mlr_1_tumoranteil` smallint(11) DEFAULT NULL,
  `mlr_1_gleason1` smallint(6) DEFAULT NULL,
  `mlr_1_gleason2` smallint(6) DEFAULT NULL,
  `mlr_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mlr_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mlr_1_gleason4` smallint(6) DEFAULT NULL,
  `mlr_1_diff` smallint(6) DEFAULT NULL,
  `mlr_2_laenge` smallint(11) DEFAULT NULL,
  `mlr_2_tumoranteil` smallint(11) DEFAULT NULL,
  `mlr_2_gleason1` smallint(6) DEFAULT NULL,
  `mlr_2_gleason2` smallint(6) DEFAULT NULL,
  `mlr_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mlr_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mlr_2_gleason4` smallint(6) DEFAULT NULL,
  `mlr_2_diff` smallint(6) DEFAULT NULL,
  `mlr_3_laenge` smallint(11) DEFAULT NULL,
  `mlr_3_tumoranteil` smallint(11) DEFAULT NULL,
  `mlr_3_gleason1` smallint(6) DEFAULT NULL,
  `mlr_3_gleason2` smallint(6) DEFAULT NULL,
  `mlr_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mlr_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mlr_3_gleason4` smallint(6) DEFAULT NULL,
  `mlr_3_diff` smallint(6) DEFAULT NULL,
  `mlr_4_laenge` smallint(11) DEFAULT NULL,
  `mlr_4_tumoranteil` smallint(11) DEFAULT NULL,
  `mlr_4_gleason1` smallint(6) DEFAULT NULL,
  `mlr_4_gleason2` smallint(6) DEFAULT NULL,
  `mlr_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mlr_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mlr_4_gleason4` smallint(6) DEFAULT NULL,
  `mlr_4_diff` smallint(6) DEFAULT NULL,
  `mlr_5_laenge` smallint(11) DEFAULT NULL,
  `mlr_5_tumoranteil` smallint(11) DEFAULT NULL,
  `mlr_5_gleason1` smallint(6) DEFAULT NULL,
  `mlr_5_gleason2` smallint(6) DEFAULT NULL,
  `mlr_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mlr_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mlr_5_gleason4` smallint(6) DEFAULT NULL,
  `mlr_5_diff` smallint(6) DEFAULT NULL,
  `mll_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `mll_anz_positiv` smallint(6) DEFAULT NULL,
  `mll_1_laenge` smallint(11) DEFAULT NULL,
  `mll_1_tumoranteil` smallint(11) DEFAULT NULL,
  `mll_1_gleason1` smallint(6) DEFAULT NULL,
  `mll_1_gleason2` smallint(6) DEFAULT NULL,
  `mll_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mll_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mll_1_gleason4` smallint(6) DEFAULT NULL,
  `mll_1_diff` smallint(6) DEFAULT NULL,
  `mll_2_laenge` smallint(11) DEFAULT NULL,
  `mll_2_tumoranteil` smallint(11) DEFAULT NULL,
  `mll_2_gleason1` smallint(6) DEFAULT NULL,
  `mll_2_gleason2` smallint(6) DEFAULT NULL,
  `mll_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mll_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mll_2_gleason4` smallint(6) DEFAULT NULL,
  `mll_2_diff` smallint(6) DEFAULT NULL,
  `mll_3_laenge` smallint(11) DEFAULT NULL,
  `mll_3_tumoranteil` smallint(11) DEFAULT NULL,
  `mll_3_gleason1` smallint(6) DEFAULT NULL,
  `mll_3_gleason2` smallint(6) DEFAULT NULL,
  `mll_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mll_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mll_3_gleason4` smallint(6) DEFAULT NULL,
  `mll_3_diff` smallint(6) DEFAULT NULL,
  `mll_4_laenge` smallint(11) DEFAULT NULL,
  `mll_4_tumoranteil` smallint(11) DEFAULT NULL,
  `mll_4_gleason1` smallint(6) DEFAULT NULL,
  `mll_4_gleason2` smallint(6) DEFAULT NULL,
  `mll_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mll_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mll_4_gleason4` smallint(6) DEFAULT NULL,
  `mll_4_diff` smallint(6) DEFAULT NULL,
  `mll_5_laenge` smallint(11) DEFAULT NULL,
  `mll_5_tumoranteil` smallint(11) DEFAULT NULL,
  `mll_5_gleason1` smallint(6) DEFAULT NULL,
  `mll_5_gleason2` smallint(6) DEFAULT NULL,
  `mll_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mll_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mll_5_gleason4` smallint(6) DEFAULT NULL,
  `mll_5_diff` smallint(6) DEFAULT NULL,
  `mr_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `mr_anz_positiv` smallint(6) DEFAULT NULL,
  `mr_1_laenge` smallint(11) DEFAULT NULL,
  `mr_1_tumoranteil` smallint(11) DEFAULT NULL,
  `mr_1_gleason1` smallint(6) DEFAULT NULL,
  `mr_1_gleason2` smallint(6) DEFAULT NULL,
  `mr_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mr_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mr_1_gleason4` smallint(6) DEFAULT NULL,
  `mr_1_diff` smallint(6) DEFAULT NULL,
  `mr_2_laenge` smallint(11) DEFAULT NULL,
  `mr_2_tumoranteil` smallint(11) DEFAULT NULL,
  `mr_2_gleason1` smallint(6) DEFAULT NULL,
  `mr_2_gleason2` smallint(6) DEFAULT NULL,
  `mr_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mr_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mr_2_gleason4` smallint(6) DEFAULT NULL,
  `mr_2_diff` smallint(6) DEFAULT NULL,
  `mr_3_laenge` smallint(11) DEFAULT NULL,
  `mr_3_tumoranteil` smallint(11) DEFAULT NULL,
  `mr_3_gleason1` smallint(6) DEFAULT NULL,
  `mr_3_gleason2` smallint(6) DEFAULT NULL,
  `mr_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mr_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mr_3_gleason4` smallint(6) DEFAULT NULL,
  `mr_3_diff` smallint(6) DEFAULT NULL,
  `mr_4_laenge` smallint(11) DEFAULT NULL,
  `mr_4_tumoranteil` smallint(11) DEFAULT NULL,
  `mr_4_gleason1` smallint(6) DEFAULT NULL,
  `mr_4_gleason2` smallint(6) DEFAULT NULL,
  `mr_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mr_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mr_4_gleason4` smallint(6) DEFAULT NULL,
  `mr_4_diff` smallint(6) DEFAULT NULL,
  `mr_5_laenge` smallint(11) DEFAULT NULL,
  `mr_5_tumoranteil` smallint(11) DEFAULT NULL,
  `mr_5_gleason1` smallint(6) DEFAULT NULL,
  `mr_5_gleason2` smallint(6) DEFAULT NULL,
  `mr_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mr_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mr_5_gleason4` smallint(6) DEFAULT NULL,
  `mr_5_diff` smallint(6) DEFAULT NULL,
  `ml_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `ml_anz_positiv` smallint(6) DEFAULT NULL,
  `ml_1_laenge` smallint(11) DEFAULT NULL,
  `ml_1_tumoranteil` smallint(11) DEFAULT NULL,
  `ml_1_gleason1` smallint(6) DEFAULT NULL,
  `ml_1_gleason2` smallint(6) DEFAULT NULL,
  `ml_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ml_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ml_1_gleason4` smallint(6) DEFAULT NULL,
  `ml_1_diff` smallint(6) DEFAULT NULL,
  `ml_2_laenge` smallint(11) DEFAULT NULL,
  `ml_2_tumoranteil` smallint(11) DEFAULT NULL,
  `ml_2_gleason1` smallint(6) DEFAULT NULL,
  `ml_2_gleason2` smallint(6) DEFAULT NULL,
  `ml_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ml_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ml_2_gleason4` smallint(6) DEFAULT NULL,
  `ml_2_diff` smallint(6) DEFAULT NULL,
  `ml_3_laenge` smallint(11) DEFAULT NULL,
  `ml_3_tumoranteil` smallint(11) DEFAULT NULL,
  `ml_3_gleason1` smallint(6) DEFAULT NULL,
  `ml_3_gleason2` smallint(6) DEFAULT NULL,
  `ml_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ml_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ml_3_gleason4` smallint(6) DEFAULT NULL,
  `ml_3_diff` smallint(6) DEFAULT NULL,
  `ml_4_laenge` smallint(11) DEFAULT NULL,
  `ml_4_tumoranteil` smallint(11) DEFAULT NULL,
  `ml_4_gleason1` smallint(6) DEFAULT NULL,
  `ml_4_gleason2` smallint(6) DEFAULT NULL,
  `ml_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ml_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ml_4_gleason4` smallint(6) DEFAULT NULL,
  `ml_4_diff` smallint(6) DEFAULT NULL,
  `ml_5_laenge` smallint(11) DEFAULT NULL,
  `ml_5_tumoranteil` smallint(11) DEFAULT NULL,
  `ml_5_gleason1` smallint(6) DEFAULT NULL,
  `ml_5_gleason2` smallint(6) DEFAULT NULL,
  `ml_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ml_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ml_5_gleason4` smallint(6) DEFAULT NULL,
  `ml_5_diff` smallint(6) DEFAULT NULL,
  `ar_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `ar_anz_positiv` smallint(6) DEFAULT NULL,
  `ar_1_laenge` smallint(11) DEFAULT NULL,
  `ar_1_tumoranteil` smallint(11) DEFAULT NULL,
  `ar_1_gleason1` smallint(6) DEFAULT NULL,
  `ar_1_gleason2` smallint(6) DEFAULT NULL,
  `ar_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ar_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ar_1_gleason4` smallint(6) DEFAULT NULL,
  `ar_1_diff` smallint(6) DEFAULT NULL,
  `ar_2_laenge` smallint(11) DEFAULT NULL,
  `ar_2_tumoranteil` smallint(11) DEFAULT NULL,
  `ar_2_gleason1` smallint(6) DEFAULT NULL,
  `ar_2_gleason2` smallint(6) DEFAULT NULL,
  `ar_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ar_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ar_2_gleason4` smallint(6) DEFAULT NULL,
  `ar_2_diff` smallint(6) DEFAULT NULL,
  `ar_3_laenge` smallint(11) DEFAULT NULL,
  `ar_3_tumoranteil` smallint(11) DEFAULT NULL,
  `ar_3_gleason1` smallint(6) DEFAULT NULL,
  `ar_3_gleason2` smallint(6) DEFAULT NULL,
  `ar_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ar_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ar_3_gleason4` smallint(6) DEFAULT NULL,
  `ar_3_diff` smallint(6) DEFAULT NULL,
  `ar_4_laenge` smallint(11) DEFAULT NULL,
  `ar_4_tumoranteil` smallint(11) DEFAULT NULL,
  `ar_4_gleason1` smallint(6) DEFAULT NULL,
  `ar_4_gleason2` smallint(6) DEFAULT NULL,
  `ar_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ar_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ar_4_gleason4` smallint(6) DEFAULT NULL,
  `ar_4_diff` smallint(6) DEFAULT NULL,
  `ar_5_laenge` smallint(11) DEFAULT NULL,
  `ar_5_tumoranteil` smallint(11) DEFAULT NULL,
  `ar_5_gleason1` smallint(6) DEFAULT NULL,
  `ar_5_gleason2` smallint(6) DEFAULT NULL,
  `ar_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ar_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ar_5_gleason4` smallint(6) DEFAULT NULL,
  `ar_5_diff` smallint(6) DEFAULT NULL,
  `al_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `al_anz_positiv` smallint(6) DEFAULT NULL,
  `al_1_laenge` smallint(11) DEFAULT NULL,
  `al_1_tumoranteil` smallint(11) DEFAULT NULL,
  `al_1_gleason1` smallint(6) DEFAULT NULL,
  `al_1_gleason2` smallint(6) DEFAULT NULL,
  `al_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `al_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `al_1_gleason4` smallint(6) DEFAULT NULL,
  `al_1_diff` smallint(6) DEFAULT NULL,
  `al_2_laenge` smallint(11) DEFAULT NULL,
  `al_2_tumoranteil` smallint(11) DEFAULT NULL,
  `al_2_gleason1` smallint(6) DEFAULT NULL,
  `al_2_gleason2` smallint(6) DEFAULT NULL,
  `al_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `al_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `al_2_gleason4` smallint(6) DEFAULT NULL,
  `al_2_diff` smallint(6) DEFAULT NULL,
  `al_3_laenge` smallint(11) DEFAULT NULL,
  `al_3_tumoranteil` smallint(11) DEFAULT NULL,
  `al_3_gleason1` smallint(6) DEFAULT NULL,
  `al_3_gleason2` smallint(6) DEFAULT NULL,
  `al_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `al_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `al_3_gleason4` smallint(6) DEFAULT NULL,
  `al_3_diff` smallint(6) DEFAULT NULL,
  `al_4_laenge` smallint(11) DEFAULT NULL,
  `al_4_tumoranteil` smallint(11) DEFAULT NULL,
  `al_4_gleason1` smallint(6) DEFAULT NULL,
  `al_4_gleason2` smallint(6) DEFAULT NULL,
  `al_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `al_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `al_4_gleason4` smallint(6) DEFAULT NULL,
  `al_4_diff` smallint(6) DEFAULT NULL,
  `al_5_laenge` smallint(11) DEFAULT NULL,
  `al_5_tumoranteil` smallint(11) DEFAULT NULL,
  `al_5_gleason1` smallint(6) DEFAULT NULL,
  `al_5_gleason2` smallint(6) DEFAULT NULL,
  `al_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `al_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `al_5_gleason4` smallint(6) DEFAULT NULL,
  `al_5_diff` smallint(6) DEFAULT NULL,
  `alr_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `alr_anz_positiv` smallint(6) DEFAULT NULL,
  `alr_1_laenge` smallint(11) DEFAULT NULL,
  `alr_1_tumoranteil` smallint(11) DEFAULT NULL,
  `alr_1_gleason1` smallint(6) DEFAULT NULL,
  `alr_1_gleason2` smallint(6) DEFAULT NULL,
  `alr_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `alr_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `alr_1_gleason4` smallint(6) DEFAULT NULL,
  `alr_1_diff` smallint(6) DEFAULT NULL,
  `alr_2_laenge` smallint(11) DEFAULT NULL,
  `alr_2_tumoranteil` smallint(11) DEFAULT NULL,
  `alr_2_gleason1` smallint(6) DEFAULT NULL,
  `alr_2_gleason2` smallint(6) DEFAULT NULL,
  `alr_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `alr_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `alr_2_gleason4` smallint(6) DEFAULT NULL,
  `alr_2_diff` smallint(6) DEFAULT NULL,
  `alr_3_laenge` smallint(11) DEFAULT NULL,
  `alr_3_tumoranteil` smallint(11) DEFAULT NULL,
  `alr_3_gleason1` smallint(6) DEFAULT NULL,
  `alr_3_gleason2` smallint(6) DEFAULT NULL,
  `alr_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `alr_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `alr_3_gleason4` smallint(6) DEFAULT NULL,
  `alr_3_diff` smallint(6) DEFAULT NULL,
  `alr_4_laenge` smallint(11) DEFAULT NULL,
  `alr_4_tumoranteil` smallint(11) DEFAULT NULL,
  `alr_4_gleason1` smallint(6) DEFAULT NULL,
  `alr_4_gleason2` smallint(6) DEFAULT NULL,
  `alr_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `alr_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `alr_4_gleason4` smallint(6) DEFAULT NULL,
  `alr_4_diff` smallint(6) DEFAULT NULL,
  `alr_5_laenge` smallint(11) DEFAULT NULL,
  `alr_5_tumoranteil` smallint(11) DEFAULT NULL,
  `alr_5_gleason1` smallint(6) DEFAULT NULL,
  `alr_5_gleason2` smallint(6) DEFAULT NULL,
  `alr_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `alr_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `alr_5_gleason4` smallint(6) DEFAULT NULL,
  `alr_5_diff` smallint(6) DEFAULT NULL,
  `all_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `all_anz_positiv` smallint(6) DEFAULT NULL,
  `all_1_laenge` smallint(11) DEFAULT NULL,
  `all_1_tumoranteil` smallint(11) DEFAULT NULL,
  `all_1_gleason1` smallint(6) DEFAULT NULL,
  `all_1_gleason2` smallint(6) DEFAULT NULL,
  `all_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `all_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `all_1_gleason4` smallint(6) DEFAULT NULL,
  `all_1_diff` smallint(6) DEFAULT NULL,
  `all_2_laenge` smallint(11) DEFAULT NULL,
  `all_2_tumoranteil` smallint(11) DEFAULT NULL,
  `all_2_gleason1` smallint(6) DEFAULT NULL,
  `all_2_gleason2` smallint(6) DEFAULT NULL,
  `all_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `all_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `all_2_gleason4` smallint(6) DEFAULT NULL,
  `all_2_diff` smallint(6) DEFAULT NULL,
  `all_3_laenge` smallint(11) DEFAULT NULL,
  `all_3_tumoranteil` smallint(11) DEFAULT NULL,
  `all_3_gleason1` smallint(6) DEFAULT NULL,
  `all_3_gleason2` smallint(6) DEFAULT NULL,
  `all_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `all_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `all_3_gleason4` smallint(6) DEFAULT NULL,
  `all_3_diff` smallint(6) DEFAULT NULL,
  `all_4_laenge` smallint(11) DEFAULT NULL,
  `all_4_tumoranteil` smallint(11) DEFAULT NULL,
  `all_4_gleason1` smallint(6) DEFAULT NULL,
  `all_4_gleason2` smallint(6) DEFAULT NULL,
  `all_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `all_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `all_4_gleason4` smallint(6) DEFAULT NULL,
  `all_4_diff` smallint(6) DEFAULT NULL,
  `all_5_laenge` smallint(11) DEFAULT NULL,
  `all_5_tumoranteil` smallint(11) DEFAULT NULL,
  `all_5_gleason1` smallint(6) DEFAULT NULL,
  `all_5_gleason2` smallint(6) DEFAULT NULL,
  `all_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `all_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `all_5_gleason4` smallint(6) DEFAULT NULL,
  `all_5_diff` smallint(6) DEFAULT NULL,
  `l_beurteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `l_anz` int(11) DEFAULT NULL,
  `l_anz_positiv` int(11) DEFAULT NULL,
  `l_laenge` double DEFAULT NULL,
  `l_tumoranteil` double DEFAULT NULL,
  `r_beurteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `r_anz` int(11) DEFAULT NULL,
  `r_anz_positiv` int(11) DEFAULT NULL,
  `r_laenge` double DEFAULT NULL,
  `r_tumoranteil` double DEFAULT NULL,
  `stanzen_ges_anz` int(11) DEFAULT NULL,
  `stanzen_ges_anz_positiv` int(11) DEFAULT NULL,
  `lk_sentinel_entf` int(11) DEFAULT NULL,
  `lk_sentinel_bef` int(11) DEFAULT NULL,
  `lk_12_entf` int(11) DEFAULT NULL,
  `lk_12_bef_makro` int(11) DEFAULT NULL,
  `lk_12_bef_mikro` int(11) DEFAULT NULL,
  `lk_3_entf` int(11) DEFAULT NULL,
  `lk_3_bef_makro` int(11) DEFAULT NULL,
  `lk_3_bef_mikro` int(11) DEFAULT NULL,
  `lk_ip_entf` int(11) DEFAULT NULL,
  `lk_ip_bef_makro` int(11) DEFAULT NULL,
  `lk_ip_bef_mikro` int(11) DEFAULT NULL,
  `lk_bef_makro` int(11) DEFAULT NULL,
  `lk_bef_mikro` int(11) DEFAULT NULL,
  `lk_hilus_entf` int(11) DEFAULT NULL,
  `lk_hilus_bef_mikro` int(11) DEFAULT NULL,
  `lk_hilus_bef_makro` int(11) DEFAULT NULL,
  `lk_interlobaer_entf` int(11) DEFAULT NULL,
  `lk_interlobaer_bef_mikro` int(11) DEFAULT NULL,
  `lk_interlobaer_bef_makro` int(11) DEFAULT NULL,
  `lk_lobaer_entf` int(11) DEFAULT NULL,
  `lk_lobaer_bef_mikro` int(11) DEFAULT NULL,
  `lk_lobaer_bef_makro` int(11) DEFAULT NULL,
  `lk_segmental_entf` int(11) DEFAULT NULL,
  `lk_segmental_bef_mikro` int(11) DEFAULT NULL,
  `lk_segmental_bef_makro` int(11) DEFAULT NULL,
  `lk_lig_pul_entf` int(11) DEFAULT NULL,
  `lk_lig_pul_bef_mikro` int(11) DEFAULT NULL,
  `lk_lig_pul_bef_makro` int(11) DEFAULT NULL,
  `lk_paraoeso_entf` int(11) DEFAULT NULL,
  `lk_paraoeso_bef_mikro` int(11) DEFAULT NULL,
  `lk_paraoeso_bef_makro` int(11) DEFAULT NULL,
  `lk_subcarinal_entf` int(11) DEFAULT NULL,
  `lk_subcarinal_bef_mikro` int(11) DEFAULT NULL,
  `lk_subcarinal_bef_makro` int(11) DEFAULT NULL,
  `lk_paraaortal_entf` int(11) DEFAULT NULL,
  `lk_paraaortal_bef_mikro` int(11) DEFAULT NULL,
  `lk_paraaortal_bef_makro` int(11) DEFAULT NULL,
  `lk_subaortal_entf` int(11) DEFAULT NULL,
  `lk_subaortal_bef_mikro` int(11) DEFAULT NULL,
  `lk_subaortal_bef_makro` int(11) DEFAULT NULL,
  `lk_unt_paratrach_entf` int(11) DEFAULT NULL,
  `lk_unt_paratrach_bef_mikro` int(11) DEFAULT NULL,
  `lk_unt_paratrach_bef_makro` int(11) DEFAULT NULL,
  `lk_prae_retro_trach_entf` int(11) DEFAULT NULL,
  `lk_prae_retro_trach_bef_mikro` int(11) DEFAULT NULL,
  `lk_prae_retro_trach_bef_makro` int(11) DEFAULT NULL,
  `lk_ob_paratrach_entf` int(11) DEFAULT NULL,
  `lk_ob_paratrach_bef_mikro` int(11) DEFAULT NULL,
  `lk_ob_paratrach_bef_makro` int(11) DEFAULT NULL,
  `lk_mediastinum_entf` int(11) DEFAULT NULL,
  `lk_mediastinum_bef_mikro` int(11) DEFAULT NULL,
  `lk_mediastinum_bef_makro` int(11) DEFAULT NULL,
  `lk_l_entf` int(11) DEFAULT NULL,
  `lk_l_bef_mikro` int(11) DEFAULT NULL,
  `lk_l_bef_makro` int(11) DEFAULT NULL,
  `lk_r_entf` int(11) DEFAULT NULL,
  `lk_r_bef_mikro` int(11) DEFAULT NULL,
  `lk_r_bef_makro` int(11) DEFAULT NULL,
  `lk_pelvin_entf` int(11) DEFAULT NULL,
  `lk_pelvin_bef` int(11) DEFAULT NULL,
  `lk_para_entf` int(11) DEFAULT NULL,
  `lk_para_bef` int(11) DEFAULT NULL,
  `lk_inguinal_entf` int(11) DEFAULT NULL,
  `lk_inguinal_bef` int(11) DEFAULT NULL,
  `lk_inguinal_makro` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_inguinal_mikro` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_iliakal_entf` int(11) DEFAULT NULL,
  `lk_iliakal_bef` int(11) DEFAULT NULL,
  `lk_iliakal_makro` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_iliakal_mikro` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_axillaer_entf` int(11) DEFAULT NULL,
  `lk_axillaer_bef` int(11) DEFAULT NULL,
  `lk_axillaer_makro` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_axillaer_mikro` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_zervikal_entf` int(11) DEFAULT NULL,
  `lk_zervikal_bef` int(11) DEFAULT NULL,
  `lk_zervikal_makro` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_zervikal_mikro` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_inguinal_l_entf` int(11) DEFAULT NULL,
  `lk_inguinal_l_bef` int(11) DEFAULT NULL,
  `lk_inguinal_r_entf` int(11) DEFAULT NULL,
  `lk_inguinal_r_bef` int(11) DEFAULT NULL,
  `lk_andere1` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_andere1_entf` int(11) DEFAULT NULL,
  `lk_andere1_bef` int(11) DEFAULT NULL,
  `lk_andere2` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_andere2_entf` int(11) DEFAULT NULL,
  `lk_andere2_bef` int(11) DEFAULT NULL,
  `lk_pelvin_externa_l_entf` int(11) DEFAULT NULL,
  `lk_pelvin_externa_l_bef` int(11) DEFAULT NULL,
  `lk_pelvin_interna_l_entf` int(11) DEFAULT NULL,
  `lk_pelvin_interna_l_bef` int(11) DEFAULT NULL,
  `lk_pelvin_fossa_l_entf` int(11) DEFAULT NULL,
  `lk_pelvin_fossa_l_bef` int(11) DEFAULT NULL,
  `lk_pelvin_communis_l_entf` int(11) DEFAULT NULL,
  `lk_pelvin_communis_l_bef` int(11) DEFAULT NULL,
  `lk_pelvin_externa_r_entf` int(11) DEFAULT NULL,
  `lk_pelvin_externa_r_bef` int(11) DEFAULT NULL,
  `lk_pelvin_interna_r_entf` int(11) DEFAULT NULL,
  `lk_pelvin_interna_r_bef` int(11) DEFAULT NULL,
  `lk_pelvin_fossa_r_entf` int(11) DEFAULT NULL,
  `lk_pelvin_fossa_r_bef` int(11) DEFAULT NULL,
  `lk_pelvin_communis_r_entf` int(11) DEFAULT NULL,
  `lk_pelvin_communis_r_bef` int(11) DEFAULT NULL,
  `lk_para_paracaval_entf` int(11) DEFAULT NULL,
  `lk_para_paracaval_bef` int(11) DEFAULT NULL,
  `lk_para_interaortocaval_entf` int(11) DEFAULT NULL,
  `lk_para_interaortocaval_bef` int(11) DEFAULT NULL,
  `lk_para_cranial_ami_entf` int(11) DEFAULT NULL,
  `lk_para_cranial_ami_bef` int(11) DEFAULT NULL,
  `lk_para_caudal_ami_entf` int(11) DEFAULT NULL,
  `lk_para_caudal_ami_bef` int(11) DEFAULT NULL,
  `lk_para_cranial_vr_entf` int(11) DEFAULT NULL,
  `lk_para_cranial_vr_bef` int(11) DEFAULT NULL,
  `hpv` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ01` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ02` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ03` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ04` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ05` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ06` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ07` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ08` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ09` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis01` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis02` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis03` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis04` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis05` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis06` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis07` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis08` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis09` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_entf` int(11) DEFAULT NULL,
  `lk_bef` int(11) DEFAULT NULL,
  `lk_mikrometastasen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `groesste_ausdehnung` double DEFAULT NULL,
  `kapseldurchbruch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `estro` double DEFAULT NULL,
  `estro_irs` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `estro_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `prog` double DEFAULT NULL,
  `prog_irs` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `prog_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2_fish` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2_fish_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pai1` double DEFAULT NULL,
  `upa` double DEFAULT NULL,
  `egf` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vegf` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `chromogranin` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kras` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `braf` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `egfr` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `egfr_mutation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nse` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ercc1` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ttf1` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `alk` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ros` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `psa` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pcna` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `epca2` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `p53` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ps2` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kathepsin_d` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hmb45` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `melan_a` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `s100` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_grading` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_groesse` double DEFAULT NULL,
  `dcis_resektionsrand` double DEFAULT NULL,
  `dcis_van_nuys` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_vnpi` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_morphologie` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_morphologie_text` text COLLATE latin1_german1_ci,
  `dcis_morphologie_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_kerngrading` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_nekrosen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `histologie_einzel` (
  `histologie_einzel_id` int(11) NOT NULL,
  `histologie_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `materialgewinnung_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `materialgewinnung_anzahl` int(11) DEFAULT NULL,
  `diagnose_id` int(11) DEFAULT NULL,
  `schnittechnik` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `clark` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mikroskopisch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `groesse_x` double DEFAULT NULL,
  `groesse_y` double DEFAULT NULL,
  `groesse_z` double DEFAULT NULL,
  `morphologie` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_text` text COLLATE latin1_german1_ci,
  `morphologie_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `unauffaellig` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ptnm_praefix` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `g` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `l` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `v` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `r` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ulzeration` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ppn` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `uicc` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `regression` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `perineurale_invasion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `wachstumsphase` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `melanom_muttermal` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `randkontrolle` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `resektionsrand` double DEFAULT NULL,
  `tumordicke` double DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `history` (
  `history_id` int(11) NOT NULL,
  `loginname` varchar(20) COLLATE latin1_german1_ci DEFAULT NULL,
  `login_ip` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `login_acc` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `session_id` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `hl7_cache` (
  `hl7_cache_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `hash` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `createtime` datetime NOT NULL,
  `updatetime` datetime DEFAULT NULL,
  `vorname` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachname` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `geburtsdatum` date DEFAULT NULL,
  `patient_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufnahme_nr` text COLLATE latin1_german1_ci,
  `erkrankung` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `hl7_diagnose` (
  `hl7_diagnose_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `diagnose` varchar(28) COLLATE latin1_german1_ci NOT NULL,
  `diagnose_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `diagnose_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `diagnose_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `hl7_log_cache` (
  `hl7_log_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `logtime` datetime NOT NULL,
  `createtime` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `filter` text COLLATE latin1_german1_ci,
  `vorname` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachname` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `geburtsdatum` date DEFAULT NULL,
  `patient_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufnahme_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `msg` text COLLATE latin1_german1_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `hl7_message` (
  `hl7_message_id` int(11) NOT NULL,
  `hl7_cache_id` int(11) NOT NULL,
  `message_control_id` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `createtime` datetime NOT NULL,
  `message` text COLLATE latin1_german1_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `komplikation` (
  `komplikation_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `komplikation` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `eingriff_id` int(11) DEFAULT NULL,
  `untersuchung_id` int(11) DEFAULT NULL,
  `zeitpunkt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `clavien_dindo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ctcae` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `reintervention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `antibiotikum` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `antibiotikum_dauer` double DEFAULT NULL,
  `drainage_intervent` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `drainage_transanal` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sekundaerheilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `revisionsoperation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `wund_spuelung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `wund_spreizung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `wund_vac` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `transfusion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `transfusion_anzahl_ek` int(11) DEFAULT NULL,
  `transfusion_anzahl_tk` int(11) DEFAULT NULL,
  `transfusion_anzahl_ffp` int(11) DEFAULT NULL,
  `gerinnungshemmer` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beatmung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beatmung_dauer` double DEFAULT NULL,
  `intensivstation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intensivstation_dauer` double DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `konferenz` (
  `konferenz_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `uhrzeit_beginn` varchar(5) COLLATE latin1_german1_ci NOT NULL,
  `uhrzeit_ende` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `moderator_id` int(11) DEFAULT NULL,
  `bem_einladung` text COLLATE latin1_german1_ci,
  `bem_abschluss` text COLLATE latin1_german1_ci,
  `teilnehmer` int(10) NOT NULL DEFAULT '0',
  `teilnehmer_bes` int(11) NOT NULL DEFAULT '0',
  `final` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `konferenz_abschluss` (
  `konferenz_abschluss_id` int(11) NOT NULL,
  `konferenz_teilnehmer_id` int(11) NOT NULL,
  `dokument_status` text COLLATE latin1_german1_ci,
  `epikrise_status` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `konferenz_dokument` (
  `konferenz_dokument_id` int(11) NOT NULL,
  `konferenz_id` int(11) NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `datei` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `konferenz_patient_id` int(11) DEFAULT NULL,
  `dokument_id` int(11) DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `konferenz_patient` (
  `konferenz_patient_id` int(11) NOT NULL,
  `konferenz_id` int(11) DEFAULT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `art` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `vorlage_dokument_id` int(11) NOT NULL,
  `fragestellung` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `patientenwunsch` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `patientenwunsch_beo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `patientenwunsch_nerverhalt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaervorstellung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaervorstellung_sonst` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `biopsie_durch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `biopsie_durch_sonst` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `mskcc` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mskcc_ic` float DEFAULT NULL,
  `mskcc_svi` float DEFAULT NULL,
  `mskcc_ocd` float DEFAULT NULL,
  `mskcc_lni` float DEFAULT NULL,
  `mskcc_ee` float DEFAULT NULL,
  `fotos` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `datenstand_datum` datetime DEFAULT NULL,
  `document_process` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `document_final` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `document_dirty` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `konferenz_teilnehmer` (
  `konferenz_teilnehmer_id` int(11) NOT NULL,
  `konferenz_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email_status` text COLLATE latin1_german1_ci,
  `teilgenommen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `konferenz_teilnehmer_profil` (
  `konferenz_teilnehmer_profil_id` int(11) NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `user_list` text COLLATE latin1_german1_ci,
  `user_count` smallint(6) NOT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `labor` (
  `labor_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `vorlage_labor_id` int(11) NOT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `labor_wert` (
  `labor_wert_id` int(11) NOT NULL,
  `labor_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_labor_wert_id` int(11) NOT NULL,
  `parameter` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `wert` double DEFAULT NULL,
  `einheit` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `beurteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_basic` (
  `klasse` varchar(50) COLLATE latin1_german1_ci NOT NULL,
  `code` varchar(30) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `pos` int(11) DEFAULT NULL,
  `kennung` varchar(30) COLLATE latin1_german1_ci DEFAULT '',
  `code_adt` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `code_dmp` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `code_dmp_2014` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `code_eusoma` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `code_gekid` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `code_gekid_plus` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `code_gkr` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `code_gtds` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `code_krbw` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `code_kr_he` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `code_kr_hh` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `code_kr_sh` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `code_onkeyline` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `code_onkonet` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `code_qsmed` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `code_wbc` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `code_wdc` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_dmp` (
  `klasse` varchar(50) COLLATE latin1_german1_ci NOT NULL,
  `code` varchar(30) COLLATE latin1_german1_ci NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `pos` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_dmp_2013` (
  `klasse` varchar(50) COLLATE latin1_german1_ci NOT NULL,
  `code` varchar(30) COLLATE latin1_german1_ci NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `pos` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_exp_diagnose_to_lokalisation` (
  `diagnose_code` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `lokalisation_code` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `lokalisation_text` varchar(255) COLLATE latin1_german1_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_exp_dmp` (
  `klasse` varchar(50) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `code_med` varchar(50) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `code_dmp` varchar(100) COLLATE latin1_german1_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_exp_dmp_2013` (
  `klasse` varchar(50) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `code_med` varchar(50) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `code_dmp` varchar(100) COLLATE latin1_german1_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_exp_ekrrp` (
  `klasse` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `code_med` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `code_ekrrp` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_exp_gekid` (
  `klasse` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `code_med` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `code_gekid` varchar(255) COLLATE latin1_german1_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_exp_gkr` (
  `klasse` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `code_med` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `code_gkr` varchar(255) COLLATE latin1_german1_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_exp_gkr_addresses` (
  `gkr_address_id` int(11) NOT NULL,
  `plz` varchar(5) COLLATE latin1_german1_ci NOT NULL,
  `stadt` varchar(50) COLLATE latin1_german1_ci NOT NULL,
  `zusatz` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_exp_krbw` (
  `klasse` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `code_med` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `code_krbw` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_exp_krbw_ng` (
  `klasse` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `code_med` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `code_krbw` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_exp_krbw_ng_fields` (
  `field_name` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `key_oid` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `key_code` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_exp_krbw_ng_oids` (
  `key_oid` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `key_code` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `class` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `code_med` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `value_oid` varchar(255) COLLATE latin1_german1_ci DEFAULT '',
  `value_code` varchar(255) COLLATE latin1_german1_ci DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_exp_krhe` (
  `klasse` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `code_med` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `code_krhe` varchar(255) COLLATE latin1_german1_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_exp_qsmed` (
  `klasse` varchar(30) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `code_med` varchar(30) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `code_qsmed` varchar(30) COLLATE latin1_german1_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_exp_wbc` (
  `klasse` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `code_med` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `code_wbc` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_exp_wdc` (
  `klasse` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `code_med` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `code_wdc` varchar(255) COLLATE latin1_german1_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_icd10` (
  `code` varchar(12) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `description` text COLLATE latin1_german1_ci NOT NULL,
  `sub_level` smallint(3) NOT NULL DEFAULT '0',
  `selectable` smallint(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_icdo3` (
  `id` int(11) NOT NULL DEFAULT '0',
  `code` varchar(10) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `description` varchar(150) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `sub_level` char(3) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `alc` char(1) COLLATE latin1_german1_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_imp_hl7` (
  `klasse` varchar(50) COLLATE latin1_german1_ci NOT NULL,
  `code` varchar(50) COLLATE latin1_german1_ci NOT NULL,
  `bez` varchar(50) COLLATE latin1_german1_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_ktst` (
  `iknr` varchar(9) COLLATE latin1_german1_ci NOT NULL,
  `name` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `vknr` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `strasse` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `plz` varchar(10) COLLATE latin1_german1_ci DEFAULT NULL,
  `ort` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `gueltig_von` date DEFAULT NULL,
  `gueltig_bis` date DEFAULT NULL,
  `vknr_ref` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `iknr_old` varchar(7) COLLATE latin1_german1_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_ktst_abr` (
  `iknr` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `abrechnungsbereich` varchar(2) COLLATE latin1_german1_ci NOT NULL,
  `name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `iknr_old` varchar(7) COLLATE latin1_german1_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_matrix` (
  `tabelle` varchar(100) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `standard` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `admin` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `supervisor` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `reg` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `dokumentar` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `datenmanager` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `dateneingabe` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `kooperationspartner` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `konferenzteilnehmer` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `moderator` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlentherapeut` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `systemtherapeut` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `pathologe` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `radiologe` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `facharzt` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL,
  `lesen` varchar(100) COLLATE latin1_german1_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_nci` (
  `grp` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `code` varchar(30) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `bez` varchar(250) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `pos` smallint(6) DEFAULT NULL,
  `grad1` text COLLATE latin1_german1_ci,
  `grad2` text COLLATE latin1_german1_ci,
  `grad3` text COLLATE latin1_german1_ci,
  `grad4` text COLLATE latin1_german1_ci,
  `grad5` text COLLATE latin1_german1_ci
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_ops` (
  `code` varchar(12) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `description` text COLLATE latin1_german1_ci NOT NULL,
  `sub_level` tinyint(4) NOT NULL DEFAULT '0',
  `selectable` smallint(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_qs` (
  `klasse` varchar(30) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `jahr` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `code` varchar(30) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `pos` smallint(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;


CREATE TABLE `l_qs_valid` (
  `jahr` year(4) NOT NULL,
  `nr` int(11) NOT NULL DEFAULT '0',
  `layer` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `fields` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bedingung` mediumtext COLLATE latin1_german1_ci,
  `meldung` mediumtext COLLATE latin1_german1_ci,
  `typ` varchar(1) COLLATE latin1_german1_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `l_uicc` (
  `t` varchar(30) COLLATE latin1_german1_ci NOT NULL,
  `n` varchar(30) COLLATE latin1_german1_ci NOT NULL,
  `m` varchar(30) COLLATE latin1_german1_ci NOT NULL,
  `uicc` varchar(30) COLLATE latin1_german1_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `nachsorge` (
  `nachsorge_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ecog` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gewicht` double DEFAULT NULL,
  `malignom` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachsorge_biopsie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `empfehlung_befolgt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumormarkerverlauf` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `psa_bestimmt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `labor_id` int(11) DEFAULT NULL,
  `response_klinisch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `response_klinisch_bestaetigt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `euroqol` int(11) DEFAULT NULL,
  `lcss` int(11) DEFAULT NULL,
  `fb_dkg` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `fb_dkg_beurt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `iciq_ui` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ics` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `iief5` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ipss` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lq_dkg` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gz_dkg` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ql` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `armbeweglichkeit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `umfang_oberarm` double DEFAULT NULL,
  `umfang_unterarm` double DEFAULT NULL,
  `hads_d_depression` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hads_d_angst` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pde5hemmer` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `pde5hemmer_haeufigkeit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vakuumpumpe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `skat` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `penisprothese` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzen_lokalisation` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzen_lokalisation_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzen_lokalisation_text` text COLLATE latin1_german1_ci,
  `sy_schmerzen_lokalisation_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzscore` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_dyspnoe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_haemoptnoe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_husten` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_husten_dauer` int(11) DEFAULT NULL,
  `sy_harndrang` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_nykturie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_pollakisurie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_miktion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_restharn` double DEFAULT NULL,
  `sy_harnverhalt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_harnstau` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_harnstau_lokalisation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_haematurie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_para_syndrom` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_para_syndrom_symptom` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_para_syndrom_detail` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_gewichtsverlust` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_gewichtsverlust_2wo` double DEFAULT NULL,
  `sy_gewichtsverlust_3mo` double DEFAULT NULL,
  `sy_fieber` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_nachtschweiss` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_dauer` int(11) DEFAULT NULL,
  `sy_dauer_einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `analgetika` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `schmerzmedikation_stufe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `response_schmerztherapie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `scapula_alata` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphoedem` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphoedem_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphdrainage` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sensibilitaet` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kontinenz` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorlagenverbrauch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `spaetschaden_blase` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `spaetschaden_blase_grad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `spaetschaden_rektum` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `spaetschaden_rektum_grad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `nachsorge_erkrankung` (
  `nachsorge_erkrankung_id` int(11) NOT NULL,
  `nachsorge_id` int(11) NOT NULL,
  `erkrankung_weitere_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `nebenwirkung` (
  `nebenwirkung_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `nci_code` varchar(27) COLLATE latin1_german1_ci NOT NULL,
  `nci_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `nci_code_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `grad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ausgang` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beginn` date DEFAULT NULL,
  `beginn_unbekannt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ende` date DEFAULT NULL,
  `ende_unbekannt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `zusammenhang` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapie_systemisch_id` int(11) DEFAULT NULL,
  `strahlentherapie_id` int(11) DEFAULT NULL,
  `sonstige_therapie_id` int(11) DEFAULT NULL,
  `therapie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapie_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `sae` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `org` (
  `org_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `namenszusatz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefon` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefax` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `email` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `website` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `strasse` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `hausnr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `plz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `ort` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `staat` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bundesland` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ik_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `kr_kennung` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `mandant` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `img_type` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `patho_item` (
  `patho_item_id` int(11) NOT NULL,
  `data` text COLLATE latin1_german1_ci,
  `date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `patient` (
  `patient_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `patient_nr` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `titel` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `adelstitel` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachname` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `vorname` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `geschlecht` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `geburtsdatum` date NOT NULL,
  `geburtsname` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `datenaustausch` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `datenspeicherung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `datenversand` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `krebsregister` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `geburtsort` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `strasse` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `hausnr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `adrzusatz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `plz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `ort` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `land` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefon` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefax` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `email` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `staat` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_iknr` varchar(35) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_abrechnungsbereich` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_fa` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_statusergaenzung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_wop` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_besondere_personengruppe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_dmp_kennzeichnung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_versicherungsschutz_beginn` date DEFAULT NULL,
  `kv_versicherungsschutz_ende` date DEFAULT NULL,
  `kv_gueltig_bis` date DEFAULT NULL,
  `kv_einlesedatum` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `erkrankungen` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `qs_18_1_b` (
  `qs_18_1_b_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `aufenthalt_id` int(11) NOT NULL,
  `idnrpat` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndatum` date DEFAULT NULL,
  `aufndiag_1` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_1_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_1_text` text COLLATE latin1_german1_ci,
  `aufndiag_1_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_2` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_2_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_2_text` text COLLATE latin1_german1_ci,
  `aufndiag_2_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_3` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_3_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_3_text` text COLLATE latin1_german1_ci,
  `aufndiag_3_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_4` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_4_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_4_text` text COLLATE latin1_german1_ci,
  `aufndiag_4_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_5` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_5_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_5_text` text COLLATE latin1_german1_ci,
  `aufndiag_5_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `asa` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `adjutherapieplanung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `planbesprochen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `planbesprochendatum` date DEFAULT NULL,
  `meldungkrebsregister` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldatum` date DEFAULT NULL,
  `entldiag_1` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldiag_1_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldiag_1_text` text COLLATE latin1_german1_ci,
  `entldiag_1_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldiag_2` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldiag_2_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldiag_2_text` text COLLATE latin1_german1_ci,
  `entldiag_2_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldiag_3` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldiag_3_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldiag_3_text` text COLLATE latin1_german1_ci,
  `entldiag_3_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `entlgrund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sektion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `qs_18_1_brust` (
  `qs_18_1_brust_id` int(11) NOT NULL,
  `qs_18_1_b_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `zuopseite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `arterkrank` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `erstoffeingriff` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tastbarmammabefund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaertumor` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `regiolymphknoten` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `anlasstumordiag` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `anlasstumordiageigen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anlasstumordiagfrueh` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `mammographiescreening` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `anlasstumordiagsympt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anlasstumordiagnachsorge` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anlasstumordiagsonst` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `praehistdiagsicherung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `praehistbefund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `praeicdo3` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ausganghistbefund` date DEFAULT NULL,
  `praethinterdisztherapieplan` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `datumtherapieplan` date DEFAULT NULL,
  `praeoptumorth` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `systchemoth` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `endokrinth` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `spezifantiktherapie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlenth` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonstth` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `pokomplikatspez` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pokowundinfektion` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachblutung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `serom` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `pokosonst` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `posthistbefund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `posticdo3` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `optherapieende` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumortherapieempf` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tnmptmamma` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tnmpnmamma` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `anzahllypmphknoten` int(11) DEFAULT NULL,
  `anzahllypmphknotenunb` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `graddcis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gesamttumorgroesse` int(11) DEFAULT NULL,
  `tnmgmamma` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rezeptorstatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2neustatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `multizentrizitaet` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `angabensicherabstand` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sicherabstand` int(11) DEFAULT NULL,
  `mnachstaging` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bet` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `axilladissektion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `axlkentfomark` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `slkbiopsie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `radionuklidmarkierung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `farbmarkierung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `qs_18_1_o` (
  `qs_18_1_o_id` int(11) NOT NULL,
  `qs_18_1_brust_id` int(11) NOT NULL,
  `qs_18_1_b_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `lfdnreingriff` int(11) DEFAULT NULL,
  `diagoffbiopsie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `praeopmarkierung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `praeopmammographiejl` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraoppraeparatroentgen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `praeopsonographiejl` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraoppraeparatsono` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `praeopmrtjl` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `opdatum` date DEFAULT NULL,
  `opschluessel_1` varchar(29) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_1_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_1_text` text COLLATE latin1_german1_ci,
  `opschluessel_1_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_2` varchar(29) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_2_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_2_text` text COLLATE latin1_german1_ci,
  `opschluessel_2_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_3` varchar(29) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_3_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_3_text` text COLLATE latin1_german1_ci,
  `opschluessel_3_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_4` varchar(29) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_4_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_4_text` text COLLATE latin1_german1_ci,
  `opschluessel_4_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_5` varchar(29) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_5_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_5_text` text COLLATE latin1_german1_ci,
  `opschluessel_5_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_6` varchar(29) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_6_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_6_text` text COLLATE latin1_german1_ci,
  `opschluessel_6_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `sentinellkeingriff` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `antibioprph` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `recht` (
  `recht_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rolle` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `behandler` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `recht_global` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `recht_erkrankung` (
  `recht_erkrankung_id` int(11) NOT NULL,
  `recht_id` int(11) NOT NULL,
  `erkrankung` varchar(30) COLLATE latin1_german1_ci NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `report_time` (
  `report_time_id` int(11) NOT NULL,
  `report` varchar(10) COLLATE latin1_german1_ci NOT NULL,
  `datum_von` char(1) COLLATE latin1_german1_ci DEFAULT '0',
  `datum_bis` char(1) COLLATE latin1_german1_ci DEFAULT '0',
  `jahr` varchar(10) COLLATE latin1_german1_ci DEFAULT NULL,
  `datumsbezug` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kennzahlenjahr` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `roh_daten` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rohdatenx` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachsorgeJahr` varchar(10) COLLATE latin1_german1_ci DEFAULT NULL,
  `time` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `settings` (
  `settings_id` int(11) NOT NULL,
  `software_version` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `software_title` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `software_custom_title` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `fastreg` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `fastreg_role` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `auto_patient_id` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `patient_initials_only` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `show_last_login` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `allow_registration` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `allow_password_reset` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `user_max_login` int(11) DEFAULT NULL,
  `user_max_login_deactivated` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `pat_list_first` varchar(26) COLLATE latin1_german1_ci NOT NULL,
  `pat_list_second` varchar(26) COLLATE latin1_german1_ci NOT NULL,
  `extended_swage` int(1) DEFAULT NULL,
  `show_pictures` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `report_debug` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `deactivate_range_check` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `fake_system_date` date DEFAULT NULL,
  `logo` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `img_type` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `check_ie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_b` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_d` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_gt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_h` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_kh` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_leu` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_lg` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_lu` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_ly` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_m` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_nt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_oes` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_p` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_pa` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_snst` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_sst` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `feature_dkg_oz` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `feature_dkg_b` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `feature_dkg_d` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `feature_dkg_gt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `feature_dkg_h` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `feature_dkg_lu` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `feature_dkg_p` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_gekid` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_gekid_plus` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_ekr_h` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_ekr_rp` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_ekr_sh` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_krbw` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_hl7_e` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_gkr` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_adt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_gtds` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_onkeyline` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_dmp_2014` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_qs181` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_eusoma` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_wbc` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_wdc` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_onkonet` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_patho_e` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_oncobox_darm` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_oncobox_prostata` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_patho_i` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_kr_he` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `konferenz` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `email_attachment` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `zweitmeinung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rolle_konferenzteilnehmer` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rolle_dateneingabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tools` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `dokument` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `pacs` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `max_pacs_savetime` int(11) DEFAULT NULL,
  `codepicker_top_limit` int(11) DEFAULT NULL,
  `status_lasttime` datetime DEFAULT NULL,
  `historys_path` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `settings_export` (
  `settings_export_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `settings` text COLLATE latin1_german1_ci,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `settings_forms` (
  `settings_forms_id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `forms` text COLLATE latin1_german1_ci NOT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `settings_hl7` (
  `settings_hl7_id` int(11) NOT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `active` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `import_mode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `valid_event_types` text COLLATE latin1_german1_ci,
  `patient_ident` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `user_ident` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_dir` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `max_log_time` smallint(6) NOT NULL,
  `max_usability_time` smallint(6) DEFAULT NULL,
  `update_patient_due_caching` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_diagnose_active` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_diagnose_hl7` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_diagnose_filter` text COLLATE latin1_german1_ci,
  `cache_diagnosetyp_active` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_diagnosetyp_hl7` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_diagnosetyp_filter` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_abteilung_active` varchar(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_abteilung_hl7` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_abteilung_filter` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `import_diagnose_active` varchar(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `import_diagnose_hl7` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `import_diagnose_filter` text COLLATE latin1_german1_ci,
  `import_diagnosetyp_active` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `import_diagnosetyp_hl7` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `import_diagnosetyp_filter` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `settings_hl7field` (
  `settings_hl7field_id` int(11) NOT NULL,
  `settings_hl7_id` int(11) NOT NULL,
  `med_feld` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `import` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `hl7` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `hl7_bereich` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `hl7_back` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `feld_typ` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `feld_trim_null` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `multiple` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `multiple_segment` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `multiple_filter` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `ext` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `settings_import` (
  `settings_import_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `settings` text COLLATE latin1_german1_ci,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `settings_pacs` (
  `settings_pacs_id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `ae_title` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `hostname` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `cipher` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `settings_report` (
  `settings_report_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `erkrankung` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `settings` text COLLATE latin1_german1_ci,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `sonstige_therapie` (
  `sonstige_therapie_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `sonstige_art` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `therapieplan_id` int(11) DEFAULT NULL,
  `intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `studie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `studie_id` int(11) DEFAULT NULL,
  `beginn` date NOT NULL,
  `ende` date DEFAULT NULL,
  `endstatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `endstatus_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `endstatus_grund_sonst` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `best_response` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `best_response_datum` date DEFAULT NULL,
  `unterbrechung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterbrechung_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterbrechung_grund_sonst` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `status` (
  `status_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `erkrankung_id` int(11) DEFAULT NULL,
  `parent_status_id` int(11) DEFAULT NULL,
  `form` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `form_id` int(11) NOT NULL,
  `form_date` date DEFAULT NULL,
  `form_status` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `form_data` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `form_param` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `status_lock` char(1) COLLATE latin1_german1_ci NOT NULL DEFAULT '0',
  `report_param` text COLLATE latin1_german1_ci
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `status_lock` (
  `status_lock_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `lock` char(1) COLLATE latin1_german1_ci NOT NULL,
  `status_lock_bem_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `status_lock_bem` (
  `status_lock_bem_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bem` text COLLATE latin1_german1_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `status_log` (
  `form` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `state` char(1) COLLATE latin1_german1_ci NOT NULL DEFAULT '0',
  `status_count` int(11) NOT NULL,
  `status_relation` int(11) NOT NULL,
  `form_count` int(11) NOT NULL,
  `form_relation` int(11) NOT NULL,
  `locked` int(11) NOT NULL,
  `validated` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `strahlentherapie` (
  `strahlentherapie_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_therapie_id` int(11) DEFAULT NULL,
  `hyperthermie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorlage_therapie_art` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `therapieplan_id` int(11) DEFAULT NULL,
  `intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapieform` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `studie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `studie_id` int(11) DEFAULT NULL,
  `beginn` date NOT NULL,
  `ende` date DEFAULT NULL,
  `andauernd` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `zahnarzt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_ganzkoerper` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_primaertumor` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_mamma_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_mamma_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_brustwand_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_brustwand_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_mammaria_interna` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_mediastinum` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_prostata` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_becken` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_abdomen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_vulva` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_vulva_pelvin` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_vulva_inguinal` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_inguinal_einseitig` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_ingu_beidseitig` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_ingu_pelvin` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_vagina` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lymph` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_paraaortal` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_axilla_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_axilla_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_supra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_para` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_iliakal` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_zervikal_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_zervikal_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_hilaer` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_axillaer_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_axillaer_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_abdominell_o` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_abdominell_u` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_iliakal_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_iliakal_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_inguinal_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_inguinal_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_knochen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_gehirn` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_sonst` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_sonst_detail` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_sonst_detail_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_sonst_detail_text` text COLLATE latin1_german1_ci,
  `ziel_sonst_detail_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `art` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `fraktionierungstyp` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `einzeldosis` double DEFAULT NULL,
  `gesamtdosis` double DEFAULT NULL,
  `boost` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `boostdosis` double DEFAULT NULL,
  `dosierung_icru` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `imrt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `igrt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `beschleunigerenergie` double DEFAULT NULL,
  `seed_strahlung_90d` double DEFAULT NULL,
  `seed_strahlung_90d_datum` date DEFAULT NULL,
  `endstatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `endstatus_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `best_response` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `best_response_datum` date DEFAULT NULL,
  `dosisreduktion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dosisreduktion_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dosisreduktion_grund_sonst` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterbrechung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterbrechung_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterbrechung_grund_sonst` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `studie` (
  `studie_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_studie_id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `beginn` date DEFAULT NULL,
  `ende` date DEFAULT NULL,
  `patient_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `arm` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `termin` (
  `termin_id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `art` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `datum` date NOT NULL,
  `uhrzeit` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `dauer` int(11) DEFAULT NULL,
  `brief_gesendet` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erledigt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erinnerung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erinnerung_datum` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `therapieplan` (
  `therapieplan_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `grundlage` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `leistungserbringer` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zeitpunkt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `konferenz_patient_id` int(11) DEFAULT NULL,
  `zweitmeinung_id` int(11) DEFAULT NULL,
  `vorgestellt` int(11) DEFAULT NULL,
  `vorgestellt2` int(11) DEFAULT NULL,
  `grund_keine_konferenz` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `grund_keine_konferenz_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `op` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_brusterhaltend` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_mastektomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_nachresektion` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_sln` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_axilla` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `keine_axilla_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_prostata` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_nerverhaltend` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_lymphadenektomie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_transplantation_autolog` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_transplantation_allogen_v` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_transplantation_allogen_nv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_transplantation_syngen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_extern` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_indiziert` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_mamma` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_axilla` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_lk_supra` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_lk_para` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_thoraxwand` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_art` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_zielvolumen` int(11) DEFAULT NULL,
  `strahlen_gesamtdosis` double DEFAULT NULL,
  `strahlen_einzeldosis` double DEFAULT NULL,
  `strahlen_zeitpunkt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_extern` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_lokalisation` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `chemo_indiziert` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `chemo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `chemo_intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `chemo_id` int(11) DEFAULT NULL,
  `chemo_extern` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `immun_indiziert` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `immun` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `immun_intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `immun_id` int(11) DEFAULT NULL,
  `immun_extern` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ah_indiziert` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ah` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ah_intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ah_id` int(11) DEFAULT NULL,
  `ah_therapiedauer_prostata` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ah_therapiedauer_monate` int(11) DEFAULT NULL,
  `ah_extern` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `andere_indiziert` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `andere` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `andere_intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `andere_id` int(11) DEFAULT NULL,
  `andere_extern` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonstige_indiziert` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonstige` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonstige_schema` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonstige_intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonstige_extern` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `watchful_waiting` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `active_surveillance` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `abweichung_leitlinie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachsorge` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `abweichung_leitlinie_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `studie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorlage_studie_id` int(11) DEFAULT NULL,
  `studie_abweichung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachbehandler_id` int(11) DEFAULT NULL,
  `palliative_versorgung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `datum_palliative_versorgung` date DEFAULT NULL,
  `bem_palliative_versorgung` text COLLATE latin1_german1_ci,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `therapieplan_abweichung` (
  `therapieplan_abweichung_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `therapieplan_id` int(11) NOT NULL,
  `bezug_eingriff` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bezug_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bezug_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bezug_immun` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bezug_ah` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bezug_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bezug_sonstige` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `grund` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `therapie_systemisch` (
  `therapie_systemisch_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_therapie_id` int(11) NOT NULL,
  `vorlage_therapie_art` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `therapieplan_id` int(11) DEFAULT NULL,
  `intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapieform` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapielinie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `metastasentherapie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `studie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `studie_id` int(11) DEFAULT NULL,
  `beginn` date NOT NULL,
  `ende` date DEFAULT NULL,
  `andauernd` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ort_therapiegabe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorverhalten_platin` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zahnarzt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `endstatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `endstatus_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `best_response` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `best_response_datum` date DEFAULT NULL,
  `best_response_bestimmung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dosisaenderung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dosisaenderung_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dosisaenderung_grund_sonst` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterbrechung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterbrechung_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterbrechung_grund_sonst` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `paravasat` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `regelmaessig` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `regelmaessig_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `therapie_systemisch_zyklus` (
  `therapie_systemisch_zyklus_id` int(11) NOT NULL,
  `therapie_systemisch_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `zyklus_nr` int(11) NOT NULL,
  `beginn` date NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `gewicht` double DEFAULT NULL,
  `groesse` int(11) DEFAULT NULL,
  `ecog` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `verschoben` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `verschoben_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `response` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `response_datum` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `therapie_systemisch_zyklustag` (
  `therapie_systemisch_zyklustag_id` int(11) NOT NULL,
  `therapie_systemisch_zyklus_id` int(11) NOT NULL,
  `therapie_systemisch_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `zyklustag` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `therapie_systemisch_zyklustag_wirkstoff` (
  `therapie_systemisch_zyklustag_wirkstoff_id` int(11) NOT NULL,
  `therapie_systemisch_zyklustag_id` int(11) NOT NULL,
  `therapie_systemisch_zyklus_id` int(11) NOT NULL,
  `therapie_systemisch_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_therapie_wirkstoff_id` int(11) NOT NULL,
  `dosis` double DEFAULT NULL,
  `einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aenderung_dosis` double DEFAULT NULL,
  `aenderung_einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `verabreicht_dosis` double DEFAULT NULL,
  `verabreicht_einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kreatinin` double DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `tumorstatus` (
  `tumorstatus_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum_beurteilung` date NOT NULL,
  `anlass` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `datum_sicherung` date DEFAULT NULL,
  `diagnosesicherung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sicherungsgrad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorausbreitung_lokal` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorausbreitung_lk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorausbreitung_konausdehnung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorausbreitung_fernmetastasen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rezidiv_lokal` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rezidiv_lk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rezidiv_metastasen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `quelle_metastasen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rezidiv_psa` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `mhrpc` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zweittumor` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `fall_vollstaendig` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nur_zweitmeinung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `nur_diagnosesicherung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `kein_fall` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `zufall` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `diagnose` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `diagnose_seite` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `diagnose_text` text COLLATE latin1_german1_ci,
  `diagnose_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `diagnose_c19_zuordnung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_text` text COLLATE latin1_german1_ci,
  `lokalisation_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_detail` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hoehe` int(11) DEFAULT NULL,
  `morphologie` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_text` text COLLATE latin1_german1_ci,
  `morphologie_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `groesse_x` double DEFAULT NULL,
  `groesse_y` double DEFAULT NULL,
  `groesse_z` double DEFAULT NULL,
  `multizentrisch` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `multifokal` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `mikrokalk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_morphologie` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_morphologie_text` text COLLATE latin1_german1_ci,
  `dcis_morphologie_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `stadium_mason` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gleason1` int(11) DEFAULT NULL,
  `gleason2` int(11) DEFAULT NULL,
  `gleason3` int(11) DEFAULT NULL,
  `gleason4_anteil` int(11) DEFAULT NULL,
  `eignung_nerverhalt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `eignung_nerverhalt_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_entf` int(11) DEFAULT NULL,
  `lk_bef` int(11) DEFAULT NULL,
  `lk_staging` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_sentinel_bef` int(11) DEFAULT NULL,
  `lk_sentinel_entf` int(11) DEFAULT NULL,
  `regressionsgrad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tnm_praefix` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `t` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `n` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `m` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `g` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `l` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `v` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `r` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `r_lokal` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ppn` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `s` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `infiltration` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `befallen_n` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `befallen_m` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `invasionstiefe` double DEFAULT NULL,
  `resektionsrand` double DEFAULT NULL,
  `uicc` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `figo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ajcc` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lugano` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nhl_who_b` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nhl_who_t` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hl_who` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ann_arbor_stadium` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ann_arbor_aktivitaetsgrad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ann_arbor_extralymphatisch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nhl_ipi` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `flipi` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `durie_salmon` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `iss` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `immun_phaenotyp` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `cll_rai` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `cll_binet` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aml_fab` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aml_who` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `all_egil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mds_fab` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mds_who` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `stadium_sclc` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_mediastinaltumor` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_extranodalbefall` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_bks` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_lk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `estro` double DEFAULT NULL,
  `estro_irs` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `estro_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `prog` double DEFAULT NULL,
  `prog_irs` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `prog_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2_fish` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2_fish_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `psa` double DEFAULT NULL,
  `datum_psa` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `tumorstatus_metastasen` (
  `tumorstatus_metastasen_id` int(11) NOT NULL,
  `tumorstatus_id` int(11) DEFAULT NULL,
  `erkrankung_id` int(11) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `lokalisation` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_text` text COLLATE latin1_german1_ci,
  `lokalisation_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `resektabel` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `untersuchung` (
  `untersuchung_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `art` varchar(29) COLLATE latin1_german1_ci NOT NULL,
  `art_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_text` text COLLATE latin1_german1_ci,
  `art_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `koloskopie_vollstaendig` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ct_becken` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kontrastmittel_iv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `kontrastmittel_po` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `kontrastmittel_rektal` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `pe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `datum` date NOT NULL,
  `anlass` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `arzt_id` int(11) DEFAULT NULL,
  `beurteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hno_untersuchung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lunge` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `be` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `birads` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ut` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `un` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `cn` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mesorektale_faszie` int(11) DEFAULT NULL,
  `lavage_menge` int(11) DEFAULT NULL,
  `bulky` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bulky_groesse` double DEFAULT NULL,
  `lk_a` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_b` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_c` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_d` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_e` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_f` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_g` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_h` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_i` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_k` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `konsistenz` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rsh_verschieblich` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `abgrenzbarkeit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gesamtvolumen` float DEFAULT NULL,
  `kapselueberschreitung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `invasion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `invasion_detail` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `untersuchung_lokalisation` (
  `untersuchung_lokalisation_id` int(11) NOT NULL,
  `untersuchung_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `lokalisation` varchar(30) COLLATE latin1_german1_ci NOT NULL,
  `lokalisation_text` text COLLATE latin1_german1_ci,
  `beurteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `hoehe` double DEFAULT NULL,
  `groesse_x` double DEFAULT NULL,
  `groesse_y` double DEFAULT NULL,
  `groesse_z` double DEFAULT NULL,
  `groesse_nicht_messbar` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `multipel` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `organuebergreifend` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `wachstumsform` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `naessen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `krusten` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `blutung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zellzahl` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `anrede` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `titel` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `adelstitel` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachname` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `vorname` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `fachabteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `teilnahme_dmp` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `teilnahme_netzwerk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `kr_kennung` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `kr_kuerzel` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `vertragsarztnummer` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `lanr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bsnr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `efn` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `efn_nz` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `org` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `strasse` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `hausnr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `plz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `ort` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefon` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `handy` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefax` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `email` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `staat` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `loginname` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `candidate` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `pwd` varchar(40) COLLATE latin1_german1_ci NOT NULL,
  `pwd_change` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bank_kontoinhaber` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bank_blz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bank_kontonummer` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bank_verwendungszweck` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `reset_cookie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `user_lock` (
  `user_lock_id` int(11) NOT NULL,
  `loginname` varchar(20) COLLATE latin1_german1_ci DEFAULT NULL,
  `last_login_acc` datetime DEFAULT NULL,
  `last_login_fail` datetime DEFAULT NULL,
  `login_ip` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `user_log` (
  `user_log_id` int(11) NOT NULL,
  `ip` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `loginname` varchar(20) COLLATE latin1_german1_ci DEFAULT NULL,
  `session_id` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `time_login` datetime DEFAULT NULL,
  `time_logout` datetime DEFAULT NULL,
  `dauer` varchar(20) COLLATE latin1_german1_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `user_reg` (
  `user_reg_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `org_name` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_namenszusatz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_ort` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_strasse` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_hausnr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_plz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_staat` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_bundesland` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_telefon` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_telefax` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_email` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_website` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `registered` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `vorlage_dokument` (
  `vorlage_dokument_id` int(11) NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `typ` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `package` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `doc_konferenz_immer` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ausgabeformat` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `vorlage_fallkennzeichen` (
  `vorlage_fallkennzeichen_id` int(11) NOT NULL,
  `code` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `pos` int(11) NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `vorlage_fragebogen` (
  `vorlage_fragebogen_id` int(11) NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `art` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `vorlage_fragebogen_frage` (
  `vorlage_fragebogen_frage_id` int(11) NOT NULL,
  `vorlage_fragebogen_id` int(11) NOT NULL,
  `frage` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `val_min` int(11) NOT NULL,
  `val_max` int(11) NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `vorlage_icd10` (
  `vorlage_icd10_id` int(11) NOT NULL,
  `code` varchar(28) COLLATE latin1_german1_ci NOT NULL,
  `code_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `createuser` mediumint(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `vorlage_icdo` (
  `vorlage_icdo_id` int(11) NOT NULL,
  `code` varchar(30) COLLATE latin1_german1_ci NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `vorlage_konferenztitel` (
  `vorlage_konferenztitel_id` int(11) NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `vorlage_krankenversicherung` (
  `vorlage_krankenversicherung_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `iknr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `vknr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `strasse` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `plz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `ort` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `land` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bundesland` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefon` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefax` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `email` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `gkv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `vorlage_labor` (
  `vorlage_labor_id` int(11) NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gueltig_von` date DEFAULT NULL,
  `gueltig_bis` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `vorlage_labor_wert` (
  `vorlage_labor_wert_id` int(11) NOT NULL,
  `vorlage_labor_id` int(11) NOT NULL,
  `parameter` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `einheit` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `normal_m_min` double DEFAULT NULL,
  `normal_m_max` double DEFAULT NULL,
  `normal_w_min` double DEFAULT NULL,
  `normal_w_max` double DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `vorlage_ops` (
  `vorlage_ops_id` int(11) NOT NULL,
  `code` varchar(29) COLLATE latin1_german1_ci NOT NULL,
  `code_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `vorlage_query` (
  `vorlage_query_id` int(11) NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `erkrankung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sqlstring` text COLLATE latin1_german1_ci,
  `package` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `typ` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `ident` varchar(10) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `vorlage_query_org` (
  `vorlage_query_org_id` int(11) NOT NULL,
  `vorlage_query_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `vorlage_studie` (
  `vorlage_studie_id` int(11) NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `art` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `studientyp` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `indikation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ethikvotum` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `beginn` date DEFAULT NULL,
  `ende` date DEFAULT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `leiter` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefon` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefax` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `email` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `krz_protokoll` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `krz_protokoll_version` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `protokoll` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `protokoll_version` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `vorlage_therapie` (
  `vorlage_therapie_id` int(11) NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `art` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `erkrankung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `datei` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `vorlage_therapie_wirkstoff` (
  `vorlage_therapie_wirkstoff_id` int(11) NOT NULL,
  `vorlage_therapie_id` int(11) NOT NULL,
  `art` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `wirkstoff` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `radionukleid` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dosis` double DEFAULT NULL,
  `einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `applikation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zyklus_beginn` int(11) DEFAULT NULL,
  `zyklus_anzahl` int(11) DEFAULT NULL,
  `zyklustag` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `zyklustag02` int(11) DEFAULT NULL,
  `zyklustag03` int(11) DEFAULT NULL,
  `zyklustag04` int(11) DEFAULT NULL,
  `zyklustag05` int(11) DEFAULT NULL,
  `zyklustag06` int(11) DEFAULT NULL,
  `zyklustag07` int(11) DEFAULT NULL,
  `zyklustag08` int(11) DEFAULT NULL,
  `zyklustag09` int(11) DEFAULT NULL,
  `zyklustag10` int(11) DEFAULT NULL,
  `zyklusdauer` int(11) DEFAULT NULL,
  `loesungsmittel` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `loesungsmittel_menge` int(11) DEFAULT NULL,
  `infusionsdauer` int(11) DEFAULT NULL,
  `infusionsdauer_einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `applikationsfrequenz` int(11) DEFAULT NULL,
  `applikationsfrequenz_einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapiedauer` int(11) DEFAULT NULL,
  `therapiedauer_einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `zweitmeinung` (
  `zweitmeinung_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_dokument_id` int(11) NOT NULL,
  `fotos` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `datenstand_datum` datetime DEFAULT NULL,
  `document_process` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `document_final` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `document_dirty` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `zytologie` (
  `zytologie_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `histologie_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `eingriff_id` int(11) DEFAULT NULL,
  `untersuchungsmaterial` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nhl_who_b` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nhl_who_t` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hl_who` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ann_arbor_stadium` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ann_arbor_aktivitaetsgrad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ann_arbor_extralymphatisch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nhl_ipi` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `flipi` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `durie_salmon` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `cll_rai` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `cll_binet` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aml_fab` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aml_who` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `all_egil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mds_fab` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mds_who` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zytologie_normal` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zelldichte` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `erythropoese` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `granulopoese` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `megakaryopoese` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `km_infiltration` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `km_infiltration_anteil` double DEFAULT NULL,
  `zyto_sonstiges_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `zyto_sonstiges` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zellveraenderung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `erythrozyten` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erythrozyten_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `granulozyten` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `granulozyten_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `megakaryozyten` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `megakaryozyten_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphozyten_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `plasmazellen_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `zellen_sonstiges` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `zellen_sonstiges_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `liquordiag_1_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `liquordiag_1_zellzahl` int(11) DEFAULT NULL,
  `liquordiag_1_beurteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `liquordiag_2_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `liquordiag_2_zellzahl` int(11) DEFAULT NULL,
  `liquordiag_2_beurteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `liquordiag_3_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `liquordiag_3_zellzahl` int(11) DEFAULT NULL,
  `liquordiag_3_beurteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `myeloperoxidase_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `myeloperoxidase_anteil` int(11) DEFAULT NULL,
  `monozytenesterase_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `monozytenesterase_anteil` int(11) DEFAULT NULL,
  `pas_reaktion_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pas_reaktion_anteil` int(11) DEFAULT NULL,
  `immunzytologie_pathologisch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `immunzytologie_diagnose` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `zytogenetik_normal` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mrd1_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mrd1_ergebnis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mrd2_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mrd2_ergebnis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `zytologie_aberration` (
  `zytologie_aberration_id` int(11) NOT NULL,
  `zytologie_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `aberration` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `karyotyp` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_abschluss` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_abschluss_id` int(11) NOT NULL,
  `abschluss_id` int(11) NOT NULL DEFAULT '0',
  `patient_id` int(11) NOT NULL,
  `abschluss_grund` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `letzter_kontakt_datum` date DEFAULT NULL,
  `todesdatum` date DEFAULT NULL,
  `tod_ursache` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `tod_ursache_text` text COLLATE latin1_german1_ci,
  `tod_ursache_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `tod_ursache_dauer` int(11) DEFAULT NULL,
  `ursache_quelle` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tod_tumorassoziation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `autopsie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_abschluss_ursache` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_abschluss_ursache_id` int(11) NOT NULL,
  `abschluss_ursache_id` int(11) NOT NULL,
  `abschluss_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `krankheit` varchar(28) COLLATE latin1_german1_ci NOT NULL,
  `krankheit_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `krankheit_text` text COLLATE latin1_german1_ci,
  `krankheit_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `krankheit_dauer` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_anamnese` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_anamnese_id` int(11) NOT NULL,
  `anamnese_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `datum_nb` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `groesse` int(11) DEFAULT NULL,
  `gewicht` double DEFAULT NULL,
  `mehrlingseigenschaften` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `entdeckung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorsorge_regelmaessig` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorsorge_intervall` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorsorge_datum_letzte` date DEFAULT NULL,
  `screening` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_autoimmun` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_autoimmun_sjoergren` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_autoimmun_arthritis` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_autoimmun_lupus_ery` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_autoimmun_zoeliakie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_autoimmun_dermatitis` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_autoimmun_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_raucher` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_raucher_dauer` double DEFAULT NULL,
  `risiko_raucher_menge` int(11) DEFAULT NULL,
  `risiko_exraucher` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_exraucher_dauer` double DEFAULT NULL,
  `risiko_exraucher_menge` int(11) DEFAULT NULL,
  `risiko_alkohol` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_medikamente` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_drogen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_pille` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_pille_dauer` int(11) DEFAULT NULL,
  `hormon_substitution` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hormon_substitution_art` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `testosteron_substitution` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `testosteron_substitution_dauer` int(11) DEFAULT NULL,
  `darmerkrankung_jn` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `darmerkrankung_morbus` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `darmerkrankung_colitis` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `darmerkrankung_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_infekt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_infekt_ebv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_infekt_htlv1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_infekt_hiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_infekt_hcv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_infekt_hp` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_infekt_bb` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_infekt_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `hormon_substitution_dauer` int(11) DEFAULT NULL,
  `hpv` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ01` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ02` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ03` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ04` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ05` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ06` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ07` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ08` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ09` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ10` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_transplantation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_transplantation_detail` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_familie_melanom` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_sonnenbrand_kind` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_sonnenbankbesuch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_sonnenschutzmittel` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_sonnenschutzmittel_detail` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_noxen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_noxen_detail` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_chronische_wunden` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beruf_letzter` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `beruf_letzter_dauer` int(11) DEFAULT NULL,
  `beruf_laengster` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `beruf_laengster_dauer` int(11) DEFAULT NULL,
  `beruf_risiko` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beruf_risiko_detail` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzen_lokalisation` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzen_lokalisation_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzen_lokalisation_text` text COLLATE latin1_german1_ci,
  `sy_schmerzen_lokalisation_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzscore` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_dyspnoe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_haemoptnoe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_husten` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_husten_dauer` int(11) DEFAULT NULL,
  `sy_harndrang` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_nykturie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_pollakisurie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_miktion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_restharn` double DEFAULT NULL,
  `sy_harnverhalt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_harnstau` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_harnstau_lokalisation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_haematurie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_para_syndrom` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_para_syndrom_symptom` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_para_syndrom_detail` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_gewichtsverlust` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_gewichtsverlust_2wo` double DEFAULT NULL,
  `sy_gewichtsverlust_3mo` double DEFAULT NULL,
  `sy_fieber` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_nachtschweiss` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_dauer` int(11) DEFAULT NULL,
  `sy_dauer_einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `euroqol` int(11) DEFAULT NULL,
  `lcss` int(11) DEFAULT NULL,
  `fb_dkg` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `fb_dkg_beurt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `iciq_ui` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ics` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `iief5` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ipss` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lq_dkg` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gz_dkg` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ql` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `familien_karzinom` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_jn` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_fap` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_gardner` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_peutz` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_hnpcc` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_turcot` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_polyposis` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_dcc` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_baxgen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_smad2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_smad4` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_kras` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_apc` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_p53` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_cmyc` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_tgfb2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_wiskott_aldrich` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_cvi` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_louis_bar` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_hpc1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_pcap` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_cabp` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_x27_28` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_brca1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_brca2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gen_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bethesda` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beratung_genetik` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pot_pde5hemmer` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pot_pde5hemmer_haeufigkeit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pot_vakuumpumpe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pot_skat` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pot_penisprothese` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ecog` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `schwanger` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `menopausenstatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `alter_menarche` int(11) DEFAULT NULL,
  `alter_menopause` int(11) DEFAULT NULL,
  `menopause_iatrogen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `menopause_iatrogen_ursache` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `geburten_lebend` int(11) DEFAULT NULL,
  `geburten_tot` int(11) DEFAULT NULL,
  `geburten_fehl` int(11) DEFAULT NULL,
  `schwangerschaft_erste_alter` int(11) DEFAULT NULL,
  `schwangerschaft_letzte_alter` int(11) DEFAULT NULL,
  `zn_hysterektomie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_lok1` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_lok2` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_lok3` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_lok4` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorbestrahlung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorbestrahlung_diagnose` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `platinresistenz` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_uterus_zervix` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_uterus_zervix_jahr` int(11) DEFAULT NULL,
  `vorop_uterus_zervix_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_uterus_zervix_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_uterus_corpus` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_uterus_corpus_jahr` int(11) DEFAULT NULL,
  `vorop_uterus_corpus_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_uterus_corpus_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_ovar_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_ovar_r_jahr` int(11) DEFAULT NULL,
  `vorop_ovar_r_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_ovar_r_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_ovar_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_ovar_l_jahr` int(11) DEFAULT NULL,
  `vorop_ovar_l_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_ovar_l_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_adnexe_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_adnexe_r_jahr` int(11) DEFAULT NULL,
  `vorop_adnexe_r_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_adnexe_r_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_adnexe_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_adnexe_l_jahr` int(11) DEFAULT NULL,
  `vorop_adnexe_l_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_adnexe_l_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_vulva` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_vulva_jahr` int(11) DEFAULT NULL,
  `vorop_vulva_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_vulva_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_mamma_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_mamma_r_jahr` int(11) DEFAULT NULL,
  `vorop_mamma_r_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_mamma_r_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_mamma_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_mamma_l_jahr` int(11) DEFAULT NULL,
  `vorop_mamma_l_erhaltung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_mamma_l_histologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_sonstige` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorop_sonstige_jahr` int(11) DEFAULT NULL,
  `vorop_sonstige_bem` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `hormon_sterilitaetsbehandlung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hormon_sterilitaetsbehandlung_dauer` int(11) DEFAULT NULL,
  `sonst` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_dauer` int(11) DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_anamnese_erkrankung` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_anamnese_erkrankung_id` int(11) NOT NULL,
  `anamnese_erkrankung_id` int(11) NOT NULL DEFAULT '0',
  `anamnese_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `erkrankung` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_text` text COLLATE latin1_german1_ci,
  `erkrankung_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_text` text COLLATE latin1_german1_ci,
  `morphologie_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `jahr` int(11) DEFAULT NULL,
  `aktuell` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapie1` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapie2` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapie3` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_anamnese_familie` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_anamnese_familie_id` int(11) NOT NULL,
  `anamnese_familie_id` int(11) NOT NULL DEFAULT '0',
  `anamnese_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `karzinom` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `verwandschaftsgrad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankungsalter` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_aufenthalt` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_aufenthalt_id` int(11) NOT NULL,
  `aufenthalt_id` int(11) NOT NULL DEFAULT '0',
  `patient_id` int(11) NOT NULL,
  `aufnahmenr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufnahmedatum` date DEFAULT NULL,
  `entlassungsdatum` date DEFAULT NULL,
  `fachabteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_begleitmedikation` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_begleitmedikation_id` int(11) NOT NULL,
  `begleitmedikation_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `wirkstoff` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `applikation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dosis_wert` double DEFAULT NULL,
  `dosis_einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beginn` date DEFAULT NULL,
  `beginn_nb` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ende` date DEFAULT NULL,
  `fortsetzung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intermittierend` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_behandler` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_behandler_id` int(11) NOT NULL,
  `behandler_id` int(11) NOT NULL DEFAULT '0',
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `funktion` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_beratung` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_beratung_id` int(11) NOT NULL,
  `beratung_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `fragebogen_ausgehaendigt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `psychoonkologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `psychoonkologie_dauer` int(11) DEFAULT NULL,
  `hads` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hads_d_depression` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hads_d_angst` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bc_pass_a` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bc_pass_b` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bc_pass_c` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sozialdienst` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `fam_risikosprechstunde` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `fam_risikosprechstunde_erfolgt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `humangenet_beratung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `interdisziplinaer_angeboten` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `interdisziplinaer_durchgefuehrt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ernaehrungsberatung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_brief` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_brief_id` int(11) NOT NULL,
  `brief_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_dokument_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `zeichen_sender` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `zeichen_empfaenger` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachricht` date DEFAULT NULL,
  `hauptempfaenger_id` int(11) NOT NULL,
  `abs_oberarzt_id` int(11) DEFAULT NULL,
  `abs_assistent_id` int(11) DEFAULT NULL,
  `fotos` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `datenstand_datum` datetime DEFAULT NULL,
  `document_process` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `document_final` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `document_dirty` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_brief_empfaenger` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_brief_empfaenger_id` int(11) NOT NULL,
  `brief_empfaenger_id` int(11) NOT NULL DEFAULT '0',
  `brief_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `empfaenger_id` int(11) NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_diagnose` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_diagnose_id` int(11) NOT NULL,
  `diagnose_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `diagnose` varchar(28) COLLATE latin1_german1_ci NOT NULL,
  `diagnose_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `diagnose_text` text COLLATE latin1_german1_ci,
  `diagnose_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_text` text COLLATE latin1_german1_ci,
  `ct` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `schleimhautmelanom` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `untersuchung_id` int(11) DEFAULT NULL,
  `rezidiv_von` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `rezidiv_von_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rezidiv_von_text` text COLLATE latin1_german1_ci,
  `rezidiv_von_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokoregionaer` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_1` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_1_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_1_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_1_text` text COLLATE latin1_german1_ci,
  `metast_visz_2` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_2_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_2_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_2_text` text COLLATE latin1_german1_ci,
  `metast_visz_3` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_3_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_3_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_3_text` text COLLATE latin1_german1_ci,
  `metast_visz_4` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_4_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_4_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_visz_4_text` text COLLATE latin1_german1_ci,
  `metast_haut` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_dmp_brustkrebs_eb` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_dmp_brustkrebs_eb_id` int(11) NOT NULL,
  `dmp_brustkrebs_eb_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `fall_nr` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `doku_datum` date DEFAULT NULL,
  `einschreibung_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `melde_user_id` int(11) DEFAULT NULL,
  `unterschrift_datum` date DEFAULT NULL,
  `kv_iknr` varchar(35) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_statusergaenzung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vk_gueltig_bis` date DEFAULT NULL,
  `kvk_einlesedatum` date DEFAULT NULL,
  `mani_primaer` date DEFAULT NULL,
  `mani_kontra` date DEFAULT NULL,
  `mani_rezidiv` date DEFAULT NULL,
  `mani_metast` date DEFAULT NULL,
  `anam_brust_links` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_brust_rechts` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_brust_beidseits` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_unt_stanz` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_unt_vakuum` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_unt_offen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_unt_mammo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_unt_sono` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_unt_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `aktueller_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_bet` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_mast` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_sln` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_axilla` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_anderes` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_tis` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_4` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_x` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_x` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_m` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_g` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_unbekannt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_rezeptorstatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_entf_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_entf_sln` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_entf_09` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_entf_10` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_bef_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_bef_sln_neg` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_bef_13` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_bef_4` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_lk_bef_unbekannt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_strahlen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_chemo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_endo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_lok_intra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_lok_thorax` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_lok_axilla` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_praeop` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_exzision` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_mastektomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_leber` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_lunge` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_knochen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_operativ` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_kontra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphoedem` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_schmerz_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_schmerz_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_schmerz_ne` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_psycho_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_psycho_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_psycho_abgelehnt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `termin_datum` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `xml` text COLLATE latin1_german1_ci,
  `xml_protokoll` text COLLATE latin1_german1_ci,
  `xml_status` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_dmp_brustkrebs_ed_2013` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_dmp_brustkrebs_ed_2013_id` int(11) NOT NULL,
  `dmp_brustkrebs_ed_2013_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `fall_nr` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `doku_datum` date DEFAULT NULL,
  `einschreibung_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `melde_user_id` int(11) DEFAULT NULL,
  `unterschrift_datum` date DEFAULT NULL,
  `kv_iknr` varchar(35) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_abrechnungsbereich` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_statusergaenzung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_besondere_personengruppe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_dmp_kennzeichnung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_versicherungsschutz_beginn` date DEFAULT NULL,
  `kv_versicherungsschutz_ende` date DEFAULT NULL,
  `vk_gueltig_bis` date DEFAULT NULL,
  `kvk_einlesedatum` date DEFAULT NULL,
  `mani_primaer` date DEFAULT NULL,
  `mani_kontra` date DEFAULT NULL,
  `mani_rezidiv` date DEFAULT NULL,
  `mani_metast` date DEFAULT NULL,
  `anam_brust_links` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_brust_rechts` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_brust_beidseits` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `aktueller_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_bet` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_mast` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_sln` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_axilla` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_anderes` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_neoadjuvant` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_tis` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_4` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_x` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_x` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_m` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_g` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_unbekannt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_rezeptorstatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_her2` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_strahlen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_chemo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_endo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_ah` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_praeop` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_exzision` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_mastektomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_leber` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_lunge` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_knochen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_operativ` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_kontra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphoedem` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `termin_datum` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `xml` text COLLATE latin1_german1_ci,
  `xml_protokoll` text COLLATE latin1_german1_ci,
  `xml_status` int(11) DEFAULT NULL,
  `dmp_brustkrebs_eb_id` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_dmp_brustkrebs_ed_pnp_2013` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_dmp_brustkrebs_ed_pnp_2013_id` int(11) NOT NULL,
  `dmp_brustkrebs_ed_pnp_2013_id` int(11) NOT NULL,
  `dmp_brustkrebs_ed_2013_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doku_datum` date DEFAULT NULL,
  `einschreibung_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `melde_user_id` int(11) DEFAULT NULL,
  `unterschrift_datum` date DEFAULT NULL,
  `kv_iknr` varchar(35) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_abrechnungsbereich` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_statusergaenzung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_besondere_personengruppe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_dmp_kennzeichnung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_versicherungsschutz_beginn` date DEFAULT NULL,
  `kv_versicherungsschutz_ende` date DEFAULT NULL,
  `vk_gueltig_bis` date DEFAULT NULL,
  `kvk_einlesedatum` date DEFAULT NULL,
  `anam_brust_links` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_brust_rechts` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_brust_beidseits` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `aktueller_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_bet` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_mast` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_sln` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_axilla` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_anderes` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anam_op_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_neoadjuvant` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_tis` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_4` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_x` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pt_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_x` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_pn_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_m` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_g` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_0` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_unbekannt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_r_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_rezeptorstatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bef_her2` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_strahlen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_chemo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_endo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beh_ah` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_praeop` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_exzision` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_mastektomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_leber` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_lunge` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_knochen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_lok_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_operativ` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_kontra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphoedem` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `termin_datum` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `xml` text COLLATE latin1_german1_ci,
  `xml_protokoll` text COLLATE latin1_german1_ci,
  `xml_status` int(11) DEFAULT NULL,
  `dmp_brustkrebs_eb_id` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_dmp_brustkrebs_fb` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_dmp_brustkrebs_fb_id` int(11) NOT NULL,
  `dmp_brustkrebs_fb_id` int(11) NOT NULL DEFAULT '0',
  `dmp_brustkrebs_eb_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `melde_user_id` int(11) DEFAULT NULL,
  `arztwechsel` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `doku_datum` date DEFAULT NULL,
  `unterschrift_datum` date DEFAULT NULL,
  `kv_iknr` varchar(35) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_statusergaenzung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vk_gueltig_bis` date DEFAULT NULL,
  `kvk_einlesedatum` date DEFAULT NULL,
  `pth_fertig` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaer_strahlen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaer_chemo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaer_endo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_rezidiv_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_rezidiv_intra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_rezidiv_thorax` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_rezidiv_axilla` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_rezidiv_datum` date DEFAULT NULL,
  `neu_kontra_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_kontra_datum` date DEFAULT NULL,
  `neu_metast_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_leber` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_lunge` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_knochen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_datum` date DEFAULT NULL,
  `lymphoedem` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_status_cr` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_status_pr` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_status_nc` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_status_pd` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_praeop` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_exzision` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_mastektomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_operativ` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_kontra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_schmerz_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_schmerz_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_schmerz_ne` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_mammo_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_mammo_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_mammo_ne` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_psycho_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_psycho_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonst_psycho_abgelehnt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `termin_datum` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `xml` text COLLATE latin1_german1_ci,
  `xml_protokoll` text COLLATE latin1_german1_ci,
  `xml_status` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_dmp_brustkrebs_fd_2013` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_dmp_brustkrebs_fd_2013_id` int(11) NOT NULL,
  `dmp_brustkrebs_fd_2013_id` int(11) NOT NULL,
  `dmp_brustkrebs_ed_2013_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `melde_user_id` int(11) DEFAULT NULL,
  `arztwechsel` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `doku_datum` date DEFAULT NULL,
  `unterschrift_datum` date DEFAULT NULL,
  `kv_iknr` varchar(35) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_abrechnungsbereich` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `versich_statusergaenzung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_besondere_personengruppe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_dmp_kennzeichnung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_versicherungsschutz_beginn` date DEFAULT NULL,
  `kv_versicherungsschutz_ende` date DEFAULT NULL,
  `vk_gueltig_bis` date DEFAULT NULL,
  `kvk_einlesedatum` date DEFAULT NULL,
  `einschreibung_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaer_strahlen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaer_chemo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaer_endo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaer_ah` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_rezidiv_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_rezidiv_datum` date DEFAULT NULL,
  `neu_kontra_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_kontra_datum` date DEFAULT NULL,
  `neu_metast_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_leber` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_lunge` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_knochen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `neu_metast_datum` date DEFAULT NULL,
  `lymphoedem` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_status_cr` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_status_pr` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_status_nc` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_status_pd` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_praeop` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_exzision` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_mastektomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rez_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_operativ` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_endo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_th_keine` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_ja` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_nein` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `metast_bip_kontra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `termin_datum` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `xml` text COLLATE latin1_german1_ci,
  `xml_protokoll` text COLLATE latin1_german1_ci,
  `xml_status` int(11) DEFAULT NULL,
  `dmp_brustkrebs_eb_id` int(11) DEFAULT NULL,
  `dmp_brustkrebs_fb_id` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_dmp_nummern_2013` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_dmp_nummern_2013_id` int(11) NOT NULL,
  `dmp_nummern_2013_id` int(11) NOT NULL DEFAULT '0',
  `org_id` int(11) NOT NULL,
  `dmp_nr_start` int(11) NOT NULL,
  `dmp_nr_end` int(11) NOT NULL,
  `dmp_nr_current` int(11) DEFAULT NULL,
  `pool` longtext COLLATE latin1_german1_ci,
  `nr_count` int(11) NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_dokument` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_dokument_id` int(11) NOT NULL,
  `dokument_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `keywords` text COLLATE latin1_german1_ci,
  `dokument` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `doc_type` varchar(5) COLLATE latin1_german1_ci NOT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_eingriff` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_eingriff_id` int(11) NOT NULL,
  `eingriff_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `notfall` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `operateur1_id` int(11) DEFAULT NULL,
  `operateur2_id` int(11) DEFAULT NULL,
  `therapieplan_id` int(11) DEFAULT NULL,
  `dauer` int(11) DEFAULT NULL,
  `asa` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `wundkontamination_cdc` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `interdisziplinaer` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `urologie_bet` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `chirurgie_bet` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `diagnose_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_diagnostik` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_staging` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_primaertumor` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_lk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_metastasen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_rezidiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_nachresektion` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_transplantation_autolog` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_transplantation_allogen_v` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_transplantation_allogen_nv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_transplantation_syngen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `verwandschaftsgrad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_revision` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_rekonstruktion` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `mark` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mark_mammo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `mark_sono` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `mark_mrt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `mark_abstand` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `stomaposition` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mesorektale_faszie` int(11) DEFAULT NULL,
  `schnellschnitt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `schnellschnitt_dauer` int(11) DEFAULT NULL,
  `intraop_roe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraop_roe_ergebnis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `blasenkatheter` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `thromboseprophylaxe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `antibiotikaprophylaxe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraop_sono` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraop_sono_ergebnis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraop_mrt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraop_mrt_ergebnis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `postop_roentgen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `postop_roentgen_ergebnis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `postop_sono` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `postop_sono_ergebnis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `postop_mrt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `postop_mrt_ergebnis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `stammzellenmobilisierung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `leukapheresen_anzahl` int(11) DEFAULT NULL,
  `stamm_sep_datum` date DEFAULT NULL,
  `stamm_sep_menge` int(11) DEFAULT NULL,
  `stamm_sep_menge_absolut` int(11) DEFAULT NULL,
  `stamm_sep_cd34_konz` double DEFAULT NULL,
  `stamm_sep_cd34_konz_absolut` int(11) DEFAULT NULL,
  `stamm_purging` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `stamm_purging_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `chim1_datum` date DEFAULT NULL,
  `chim1_wert` double DEFAULT NULL,
  `chim2_datum` date DEFAULT NULL,
  `chim2_wert` double DEFAULT NULL,
  `chim3_datum` date DEFAULT NULL,
  `chim3_wert` double DEFAULT NULL,
  `chim4_datum` date DEFAULT NULL,
  `chim4_wert` double DEFAULT NULL,
  `dli1_datum` date DEFAULT NULL,
  `dli1_wert` double DEFAULT NULL,
  `dli2_datum` date DEFAULT NULL,
  `dli2_wert` double DEFAULT NULL,
  `erholung_leukozyten_datum` date DEFAULT NULL,
  `erholung_granulozyten_datum` date DEFAULT NULL,
  `erholung_thrombozyten_datum` date DEFAULT NULL,
  `erholung_gesamt_datum` date DEFAULT NULL,
  `axilla_sampling` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `axilla_nein_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `res_oberlappen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `res_mittellappen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `res_unterlappen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tme` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `pme` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ther_koloskopie_vollstaendig` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_verfahren` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nerverhalt_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphadenektomie_methode_prostata` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraop_bestrahlung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraop_bestrahlung_dosis` double DEFAULT NULL,
  `intraop_zytostatika` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraop_zytostatika_art` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `hypertherme_perfusion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `plastischer_verschluss` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `laparotomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `peritonealzytologie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `peritonealbiopsie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `adnexexstirpation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hysterektomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `omentektomie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphonodektomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sln_nein_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sentinel_nicht_detektierbar` varchar(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sln_markierung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sln_anzahl` int(11) DEFAULT NULL,
  `sln_parasternal` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sln_restaktivitaet` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sln_schnellschnitt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sln_schnellschnitt_befall` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sln_schnellschnitt_dauer_versendung` int(11) DEFAULT NULL,
  `sln_schnellschnitt_dauer_eingang` int(11) DEFAULT NULL,
  `blutverlust` int(11) DEFAULT NULL,
  `polypen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `polypen_anz_gef` int(11) DEFAULT NULL,
  `polypen_anz_entf` int(11) DEFAULT NULL,
  `polypen_op_areal` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aszites_volumen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `peritonealkarzinose_darm` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `peritonealkarzinose_becken` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `peritonealkarzinose_mittelbauch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `peritonealkarzinose_zwerchfell` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_a1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_a2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_a3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_b1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_b2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_b3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_c1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_c2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorlast_c3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_groesse` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_detail` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_a1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_a2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_a3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_b1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_b2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_b3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_c1` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_c2` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorrest_c3` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `r0limit_aetas` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `r0limit_komplikation` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `r0limit_lokalisation` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `r0limit_lokalisation_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `r0limit_wunsch` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `r0limit_wunsch_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `r0limit_sonstige` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `r0limit_sonstige_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_ovar` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_ovar_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_ovar_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_tube` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_tube_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_tube_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_milz` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_milz_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_lk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_lk_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_ureter` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_ureter_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_douglas` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_douglas_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_vaginalstumpf` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_vaginalstumpf_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_duenndarm` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_duenndarm_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_zwerchfell` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_zwerchfell_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_magen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_magen_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_lsm` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_lsm_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_blasenwand` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_blasenwand_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_beckenwand` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_beckenwand_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_mesenterium` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_mesenterium_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_dickdarm` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_dickdarm_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_dickdarmschleimhaut` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_dickdarmschleimhaut_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_omentum_majus` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_omentum_majus_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_bauchwand` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_bauchwand_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_blasenschleimhaut` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_blasenschleimhaut_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_uterus` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_uterus_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_bursa` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_bursa_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_vagina` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_vagina_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_portio` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_portio_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_cervix` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_cervix_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_vulva` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_vulva_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_urethra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_urethra_rest` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_sonst` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `befall_sonst_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `beatmung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `beatmung_dauer` int(11) DEFAULT NULL,
  `intensiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `intensiv_dauer` int(11) DEFAULT NULL,
  `antibiotika` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `thrombose` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `antibiotika_dauer` int(11) DEFAULT NULL,
  `transfusion` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `transfusion_anzahl_ek` int(11) DEFAULT NULL,
  `transfusion_anzahl_tk` int(11) DEFAULT NULL,
  `transfusion_anzahl_ffp` int(11) DEFAULT NULL,
  `datum_drainageentfernung` date DEFAULT NULL,
  `datum_katheterentfernung` date DEFAULT NULL,
  `cystogramm1` int(11) DEFAULT NULL,
  `cystogramm2` int(11) DEFAULT NULL,
  `leckage_primaer` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `leckage_sekundaer` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `cf_entfernung` int(11) DEFAULT NULL,
  `dk_entfernung` int(11) DEFAULT NULL,
  `dk_neuanlage` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dk_entlassung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphocele` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphocele_detail` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `wundabstrich` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `wundabstrich_ergebnis` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_eingriff_ops` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_eingriff_ops_id` int(11) NOT NULL,
  `eingriff_ops_id` int(11) NOT NULL DEFAULT '0',
  `eingriff_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `prozedur` varchar(29) COLLATE latin1_german1_ci NOT NULL,
  `prozedur_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `prozedur_text` text COLLATE latin1_german1_ci,
  `prozedur_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `diagnose_id` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_ekr` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_ekr_id` int(11) NOT NULL,
  `ekr_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `meldetyp` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `datum` date NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `meldebegruendung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `wandlung_diagnose` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `grund` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterrichtet_krankheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `einzugsgebiet` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `datum_einverstaendnis` date DEFAULT NULL,
  `abteilung` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `sh_wohnort` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `weiterleitung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `weiterleitung_datum` date DEFAULT NULL,
  `forschungsvorhaben` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `forschungsvorhaben_datum` date DEFAULT NULL,
  `vermutete_tumorursachen` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `export_for_onkeyline` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachsorgeprogramm` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachsorgepassnr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachsorge_user_id` int(11) DEFAULT NULL,
  `nachsorgetermin` date DEFAULT NULL,
  `mitteilung` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_erkrankung` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_erkrankung_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL DEFAULT '0',
  `patient_id` int(11) NOT NULL,
  `erkrankung` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `erkrankung_detail` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beschreibung` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zweiterkrankung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `fallkennzeichen` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_relevant` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_relevant_haut` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rezidiv_bei_erstvorstellung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `notfall` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `einweiser_id` int(11) DEFAULT NULL,
  `nachsorgepassnummer` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_erkrankung_synchron` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_erkrankung_synchron_id` int(11) NOT NULL,
  `erkrankung_synchron_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `erkrankung_synchron` int(11) NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_export_history` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_export_history_id` int(11) NOT NULL,
  `export_history_id` int(11) NOT NULL,
  `export_log_id` int(11) NOT NULL,
  `export_name` varchar(50) COLLATE latin1_german1_ci NOT NULL,
  `org_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `filter` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `file` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_foto` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_foto_id` int(11) NOT NULL,
  `foto_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `keywords` text COLLATE latin1_german1_ci,
  `foto` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `img_type` varchar(5) COLLATE latin1_german1_ci NOT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_fragebogen` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_fragebogen_id` int(11) NOT NULL,
  `fragebogen_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_fragebogen_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_fragebogen_frage` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_fragebogen_frage_id` int(11) NOT NULL,
  `fragebogen_frage_id` int(11) NOT NULL DEFAULT '0',
  `fragebogen_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_fragebogen_id` int(11) NOT NULL,
  `vorlage_fragebogen_frage_id` int(11) NOT NULL,
  `antwort` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_histologie` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_histologie_id` int(11) NOT NULL,
  `histologie_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `art` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `histologie_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `diagnose_seite` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `eingriff_id` int(11) DEFAULT NULL,
  `untersuchung_id` int(11) DEFAULT NULL,
  `referenzpathologie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `anzahl_praeparate` int(11) DEFAULT NULL,
  `groesse_x` double DEFAULT NULL,
  `groesse_y` double DEFAULT NULL,
  `groesse_z` double DEFAULT NULL,
  `prostatagewicht` double DEFAULT NULL,
  `multizentrisch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `multifokal` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_text` text COLLATE latin1_german1_ci,
  `morphologie_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_erg1` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_erg1_text` text COLLATE latin1_german1_ci,
  `morphologie_erg1_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_erg2` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_erg2_text` text COLLATE latin1_german1_ci,
  `morphologie_erg2_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_erg3` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_erg3_text` text COLLATE latin1_german1_ci,
  `morphologie_erg3_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `unauffaellig` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ptnm_praefix` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pn` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pm` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `g` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `l` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `v` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `invasionstiefe` double DEFAULT NULL,
  `invasionsbreite` double DEFAULT NULL,
  `r` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ppn` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `konisation_exzision` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `konisation_x` double DEFAULT NULL,
  `konisation_y` double DEFAULT NULL,
  `konisation_z` double DEFAULT NULL,
  `resektionsrand` double DEFAULT NULL,
  `anz_rand_positiv` smallint(6) DEFAULT NULL,
  `status_resektionsrand_organ` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `status_resektionsrand_circumferentiell` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `resektionsrand_circumferentiell` double DEFAULT NULL,
  `resektionsrand_oral` double DEFAULT NULL,
  `resektionsrand_aboral` double DEFAULT NULL,
  `resektionsrand_lateral` double DEFAULT NULL,
  `tumoranteil_turp` smallint(6) DEFAULT NULL,
  `mercury` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `msi` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `msi_mutation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `msi_stabilitaet` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kapselueberschreitung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tubulusbildung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kernpolymorphie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mitoserate` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ki67` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `ki67_index` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gleason1` int(11) DEFAULT NULL,
  `gleason2` int(11) DEFAULT NULL,
  `gleason3` int(11) DEFAULT NULL,
  `gleason4_anteil` smallint(6) DEFAULT NULL,
  `parametrienbefall_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `parametrienbefall_r_infiltration` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `parametrienbefall_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `parametrienbefall_l_infiltration` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `blasteninfiltration` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `blasteninfiltration_prozent` int(11) DEFAULT NULL,
  `sbr_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `sbr_anz_positiv` smallint(6) DEFAULT NULL,
  `sbr_1_laenge` smallint(11) DEFAULT NULL,
  `sbr_1_tumoranteil` smallint(11) DEFAULT NULL,
  `sbr_1_gleason1` smallint(6) DEFAULT NULL,
  `sbr_1_gleason2` smallint(6) DEFAULT NULL,
  `sbr_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbr_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbr_1_gleason4` smallint(6) DEFAULT NULL,
  `sbr_1_diff` smallint(6) DEFAULT NULL,
  `sbr_2_laenge` smallint(11) DEFAULT NULL,
  `sbr_2_tumoranteil` smallint(11) DEFAULT NULL,
  `sbr_2_gleason1` smallint(6) DEFAULT NULL,
  `sbr_2_gleason2` smallint(6) DEFAULT NULL,
  `sbr_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbr_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbr_2_gleason4` smallint(6) DEFAULT NULL,
  `sbr_2_diff` smallint(6) DEFAULT NULL,
  `sbr_3_laenge` smallint(11) DEFAULT NULL,
  `sbr_3_tumoranteil` smallint(11) DEFAULT NULL,
  `sbr_3_gleason1` smallint(6) DEFAULT NULL,
  `sbr_3_gleason2` smallint(6) DEFAULT NULL,
  `sbr_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbr_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbr_3_gleason4` smallint(6) DEFAULT NULL,
  `sbr_3_diff` smallint(6) DEFAULT NULL,
  `sbr_4_laenge` smallint(11) DEFAULT NULL,
  `sbr_4_tumoranteil` smallint(11) DEFAULT NULL,
  `sbr_4_gleason1` smallint(6) DEFAULT NULL,
  `sbr_4_gleason2` smallint(6) DEFAULT NULL,
  `sbr_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbr_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbr_4_gleason4` smallint(6) DEFAULT NULL,
  `sbr_4_diff` smallint(6) DEFAULT NULL,
  `sbr_5_laenge` smallint(11) DEFAULT NULL,
  `sbr_5_tumoranteil` smallint(11) DEFAULT NULL,
  `sbr_5_gleason1` smallint(6) DEFAULT NULL,
  `sbr_5_gleason2` smallint(6) DEFAULT NULL,
  `sbr_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbr_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbr_5_gleason4` smallint(6) DEFAULT NULL,
  `sbr_5_diff` smallint(6) DEFAULT NULL,
  `sbl_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `sbl_anz_positiv` smallint(6) DEFAULT NULL,
  `sbl_1_laenge` smallint(11) DEFAULT NULL,
  `sbl_1_tumoranteil` smallint(11) DEFAULT NULL,
  `sbl_1_gleason1` smallint(6) DEFAULT NULL,
  `sbl_1_gleason2` smallint(6) DEFAULT NULL,
  `sbl_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbl_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbl_1_gleason4` smallint(6) DEFAULT NULL,
  `sbl_1_diff` smallint(6) DEFAULT NULL,
  `sbl_2_laenge` smallint(11) DEFAULT NULL,
  `sbl_2_tumoranteil` smallint(11) DEFAULT NULL,
  `sbl_2_gleason1` smallint(6) DEFAULT NULL,
  `sbl_2_gleason2` smallint(6) DEFAULT NULL,
  `sbl_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbl_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbl_2_gleason4` smallint(6) DEFAULT NULL,
  `sbl_2_diff` smallint(6) DEFAULT NULL,
  `sbl_3_laenge` smallint(11) DEFAULT NULL,
  `sbl_3_tumoranteil` smallint(11) DEFAULT NULL,
  `sbl_3_gleason1` smallint(6) DEFAULT NULL,
  `sbl_3_gleason2` smallint(6) DEFAULT NULL,
  `sbl_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbl_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbl_3_gleason4` smallint(6) DEFAULT NULL,
  `sbl_3_diff` smallint(6) DEFAULT NULL,
  `sbl_4_laenge` smallint(11) DEFAULT NULL,
  `sbl_4_tumoranteil` smallint(11) DEFAULT NULL,
  `sbl_4_gleason1` smallint(6) DEFAULT NULL,
  `sbl_4_gleason2` smallint(6) DEFAULT NULL,
  `sbl_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbl_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbl_4_gleason4` smallint(6) DEFAULT NULL,
  `sbl_4_diff` smallint(6) DEFAULT NULL,
  `sbl_5_laenge` smallint(11) DEFAULT NULL,
  `sbl_5_tumoranteil` smallint(11) DEFAULT NULL,
  `sbl_5_gleason1` smallint(6) DEFAULT NULL,
  `sbl_5_gleason2` smallint(6) DEFAULT NULL,
  `sbl_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `sbl_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `sbl_5_gleason4` smallint(6) DEFAULT NULL,
  `sbl_5_diff` smallint(6) DEFAULT NULL,
  `blr_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `blr_anz_positiv` smallint(6) DEFAULT NULL,
  `blr_1_laenge` smallint(11) DEFAULT NULL,
  `blr_1_tumoranteil` smallint(11) DEFAULT NULL,
  `blr_1_gleason1` smallint(6) DEFAULT NULL,
  `blr_1_gleason2` smallint(6) DEFAULT NULL,
  `blr_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `blr_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `blr_1_gleason4` smallint(6) DEFAULT NULL,
  `blr_1_diff` smallint(6) DEFAULT NULL,
  `blr_2_laenge` smallint(11) DEFAULT NULL,
  `blr_2_tumoranteil` smallint(11) DEFAULT NULL,
  `blr_2_gleason1` smallint(6) DEFAULT NULL,
  `blr_2_gleason2` smallint(6) DEFAULT NULL,
  `blr_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `blr_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `blr_2_gleason4` smallint(6) DEFAULT NULL,
  `blr_2_diff` smallint(6) DEFAULT NULL,
  `blr_3_laenge` smallint(11) DEFAULT NULL,
  `blr_3_tumoranteil` smallint(11) DEFAULT NULL,
  `blr_3_gleason1` smallint(6) DEFAULT NULL,
  `blr_3_gleason2` smallint(6) DEFAULT NULL,
  `blr_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `blr_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `blr_3_gleason4` smallint(6) DEFAULT NULL,
  `blr_3_diff` smallint(6) DEFAULT NULL,
  `blr_4_laenge` smallint(11) DEFAULT NULL,
  `blr_4_tumoranteil` smallint(11) DEFAULT NULL,
  `blr_4_gleason1` smallint(6) DEFAULT NULL,
  `blr_4_gleason2` smallint(6) DEFAULT NULL,
  `blr_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `blr_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `blr_4_gleason4` smallint(6) DEFAULT NULL,
  `blr_4_diff` smallint(6) DEFAULT NULL,
  `blr_5_laenge` smallint(11) DEFAULT NULL,
  `blr_5_tumoranteil` smallint(11) DEFAULT NULL,
  `blr_5_gleason1` smallint(6) DEFAULT NULL,
  `blr_5_gleason2` smallint(6) DEFAULT NULL,
  `blr_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `blr_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `blr_5_gleason4` smallint(6) DEFAULT NULL,
  `blr_5_diff` smallint(6) DEFAULT NULL,
  `bll_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `bll_anz_positiv` smallint(6) DEFAULT NULL,
  `bll_1_laenge` smallint(11) DEFAULT NULL,
  `bll_1_tumoranteil` smallint(11) DEFAULT NULL,
  `bll_1_gleason1` smallint(6) DEFAULT NULL,
  `bll_1_gleason2` smallint(6) DEFAULT NULL,
  `bll_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bll_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bll_1_gleason4` smallint(6) DEFAULT NULL,
  `bll_1_diff` smallint(6) DEFAULT NULL,
  `bll_2_laenge` smallint(11) DEFAULT NULL,
  `bll_2_tumoranteil` smallint(11) DEFAULT NULL,
  `bll_2_gleason1` smallint(6) DEFAULT NULL,
  `bll_2_gleason2` smallint(6) DEFAULT NULL,
  `bll_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bll_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bll_2_gleason4` smallint(6) DEFAULT NULL,
  `bll_2_diff` smallint(6) DEFAULT NULL,
  `bll_3_laenge` smallint(11) DEFAULT NULL,
  `bll_3_tumoranteil` smallint(11) DEFAULT NULL,
  `bll_3_gleason1` smallint(6) DEFAULT NULL,
  `bll_3_gleason2` smallint(6) DEFAULT NULL,
  `bll_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bll_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bll_3_gleason4` smallint(6) DEFAULT NULL,
  `bll_3_diff` smallint(6) DEFAULT NULL,
  `bll_4_laenge` smallint(11) DEFAULT NULL,
  `bll_4_tumoranteil` smallint(11) DEFAULT NULL,
  `bll_4_gleason1` smallint(6) DEFAULT NULL,
  `bll_4_gleason2` smallint(6) DEFAULT NULL,
  `bll_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bll_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bll_4_gleason4` smallint(6) DEFAULT NULL,
  `bll_4_diff` smallint(6) DEFAULT NULL,
  `bll_5_laenge` smallint(11) DEFAULT NULL,
  `bll_5_tumoranteil` smallint(11) DEFAULT NULL,
  `bll_5_gleason1` smallint(6) DEFAULT NULL,
  `bll_5_gleason2` smallint(6) DEFAULT NULL,
  `bll_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bll_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bll_5_gleason4` smallint(6) DEFAULT NULL,
  `bll_5_diff` smallint(6) DEFAULT NULL,
  `br_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `br_anz_positiv` smallint(6) DEFAULT NULL,
  `br_1_laenge` smallint(11) DEFAULT NULL,
  `br_1_tumoranteil` smallint(11) DEFAULT NULL,
  `br_1_gleason1` smallint(6) DEFAULT NULL,
  `br_1_gleason2` smallint(6) DEFAULT NULL,
  `br_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `br_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `br_1_gleason4` smallint(6) DEFAULT NULL,
  `br_1_diff` smallint(6) DEFAULT NULL,
  `br_2_laenge` smallint(11) DEFAULT NULL,
  `br_2_tumoranteil` smallint(11) DEFAULT NULL,
  `br_2_gleason1` smallint(6) DEFAULT NULL,
  `br_2_gleason2` smallint(6) DEFAULT NULL,
  `br_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `br_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `br_2_gleason4` smallint(6) DEFAULT NULL,
  `br_2_diff` smallint(6) DEFAULT NULL,
  `br_3_laenge` smallint(11) DEFAULT NULL,
  `br_3_tumoranteil` smallint(11) DEFAULT NULL,
  `br_3_gleason1` smallint(6) DEFAULT NULL,
  `br_3_gleason2` smallint(6) DEFAULT NULL,
  `br_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `br_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `br_3_gleason4` smallint(6) DEFAULT NULL,
  `br_3_diff` smallint(6) DEFAULT NULL,
  `br_4_laenge` smallint(11) DEFAULT NULL,
  `br_4_tumoranteil` smallint(11) DEFAULT NULL,
  `br_4_gleason1` smallint(6) DEFAULT NULL,
  `br_4_gleason2` smallint(6) DEFAULT NULL,
  `br_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `br_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `br_4_gleason4` smallint(6) DEFAULT NULL,
  `br_4_diff` smallint(6) DEFAULT NULL,
  `br_5_laenge` smallint(11) DEFAULT NULL,
  `br_5_tumoranteil` smallint(11) DEFAULT NULL,
  `br_5_gleason1` smallint(6) DEFAULT NULL,
  `br_5_gleason2` smallint(6) DEFAULT NULL,
  `br_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `br_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `br_5_gleason4` smallint(6) DEFAULT NULL,
  `br_5_diff` smallint(6) DEFAULT NULL,
  `bl_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `bl_anz_positiv` smallint(6) DEFAULT NULL,
  `bl_1_laenge` smallint(11) DEFAULT NULL,
  `bl_1_tumoranteil` smallint(11) DEFAULT NULL,
  `bl_1_gleason1` smallint(6) DEFAULT NULL,
  `bl_1_gleason2` smallint(6) DEFAULT NULL,
  `bl_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bl_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bl_1_gleason4` smallint(6) DEFAULT NULL,
  `bl_1_diff` smallint(6) DEFAULT NULL,
  `bl_2_laenge` smallint(11) DEFAULT NULL,
  `bl_2_tumoranteil` smallint(11) DEFAULT NULL,
  `bl_2_gleason1` smallint(6) DEFAULT NULL,
  `bl_2_gleason2` smallint(6) DEFAULT NULL,
  `bl_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bl_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bl_2_gleason4` smallint(6) DEFAULT NULL,
  `bl_2_diff` smallint(6) DEFAULT NULL,
  `bl_3_laenge` smallint(11) DEFAULT NULL,
  `bl_3_tumoranteil` smallint(11) DEFAULT NULL,
  `bl_3_gleason1` smallint(6) DEFAULT NULL,
  `bl_3_gleason2` smallint(6) DEFAULT NULL,
  `bl_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bl_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bl_3_gleason4` smallint(6) DEFAULT NULL,
  `bl_3_diff` smallint(6) DEFAULT NULL,
  `bl_4_laenge` smallint(11) DEFAULT NULL,
  `bl_4_tumoranteil` smallint(11) DEFAULT NULL,
  `bl_4_gleason1` smallint(6) DEFAULT NULL,
  `bl_4_gleason2` smallint(6) DEFAULT NULL,
  `bl_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bl_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bl_4_gleason4` smallint(6) DEFAULT NULL,
  `bl_4_diff` smallint(6) DEFAULT NULL,
  `bl_5_laenge` smallint(11) DEFAULT NULL,
  `bl_5_tumoranteil` smallint(11) DEFAULT NULL,
  `bl_5_gleason1` smallint(6) DEFAULT NULL,
  `bl_5_gleason2` smallint(6) DEFAULT NULL,
  `bl_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `bl_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `bl_5_gleason4` smallint(6) DEFAULT NULL,
  `bl_5_diff` smallint(6) DEFAULT NULL,
  `tr_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `tr_anz_positiv` smallint(6) DEFAULT NULL,
  `tr_1_laenge` smallint(11) DEFAULT NULL,
  `tr_1_tumoranteil` smallint(11) DEFAULT NULL,
  `tr_1_gleason1` smallint(6) DEFAULT NULL,
  `tr_1_gleason2` smallint(6) DEFAULT NULL,
  `tr_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tr_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tr_1_gleason4` smallint(6) DEFAULT NULL,
  `tr_1_diff` smallint(6) DEFAULT NULL,
  `tr_2_laenge` smallint(11) DEFAULT NULL,
  `tr_2_tumoranteil` smallint(11) DEFAULT NULL,
  `tr_2_gleason1` smallint(6) DEFAULT NULL,
  `tr_2_gleason2` smallint(6) DEFAULT NULL,
  `tr_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tr_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tr_2_gleason4` smallint(6) DEFAULT NULL,
  `tr_2_diff` smallint(6) DEFAULT NULL,
  `tr_3_laenge` smallint(11) DEFAULT NULL,
  `tr_3_tumoranteil` smallint(11) DEFAULT NULL,
  `tr_3_gleason1` smallint(6) DEFAULT NULL,
  `tr_3_gleason2` smallint(6) DEFAULT NULL,
  `tr_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tr_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tr_3_gleason4` smallint(6) DEFAULT NULL,
  `tr_3_diff` smallint(6) DEFAULT NULL,
  `tr_4_laenge` smallint(11) DEFAULT NULL,
  `tr_4_tumoranteil` smallint(11) DEFAULT NULL,
  `tr_4_gleason1` smallint(6) DEFAULT NULL,
  `tr_4_gleason2` smallint(6) DEFAULT NULL,
  `tr_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tr_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tr_4_gleason4` smallint(6) DEFAULT NULL,
  `tr_4_diff` smallint(6) DEFAULT NULL,
  `tr_5_laenge` smallint(11) DEFAULT NULL,
  `tr_5_tumoranteil` smallint(11) DEFAULT NULL,
  `tr_5_gleason1` smallint(6) DEFAULT NULL,
  `tr_5_gleason2` smallint(6) DEFAULT NULL,
  `tr_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tr_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tr_5_gleason4` smallint(6) DEFAULT NULL,
  `tr_5_diff` smallint(6) DEFAULT NULL,
  `tl_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `tl_anz_positiv` smallint(6) DEFAULT NULL,
  `tl_1_laenge` smallint(11) DEFAULT NULL,
  `tl_1_tumoranteil` smallint(11) DEFAULT NULL,
  `tl_1_gleason1` smallint(6) DEFAULT NULL,
  `tl_1_gleason2` smallint(6) DEFAULT NULL,
  `tl_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tl_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tl_1_gleason4` smallint(6) DEFAULT NULL,
  `tl_1_diff` smallint(6) DEFAULT NULL,
  `tl_2_laenge` smallint(11) DEFAULT NULL,
  `tl_2_tumoranteil` smallint(11) DEFAULT NULL,
  `tl_2_gleason1` smallint(6) DEFAULT NULL,
  `tl_2_gleason2` smallint(6) DEFAULT NULL,
  `tl_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tl_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tl_2_gleason4` smallint(6) DEFAULT NULL,
  `tl_2_diff` smallint(6) DEFAULT NULL,
  `tl_3_laenge` smallint(11) DEFAULT NULL,
  `tl_3_tumoranteil` smallint(11) DEFAULT NULL,
  `tl_3_gleason1` smallint(6) DEFAULT NULL,
  `tl_3_gleason2` smallint(6) DEFAULT NULL,
  `tl_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tl_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tl_3_gleason4` smallint(6) DEFAULT NULL,
  `tl_3_diff` smallint(6) DEFAULT NULL,
  `tl_4_laenge` smallint(11) DEFAULT NULL,
  `tl_4_tumoranteil` smallint(11) DEFAULT NULL,
  `tl_4_gleason1` smallint(6) DEFAULT NULL,
  `tl_4_gleason2` smallint(6) DEFAULT NULL,
  `tl_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tl_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tl_4_gleason4` smallint(6) DEFAULT NULL,
  `tl_4_diff` smallint(6) DEFAULT NULL,
  `tl_5_laenge` smallint(11) DEFAULT NULL,
  `tl_5_tumoranteil` smallint(11) DEFAULT NULL,
  `tl_5_gleason1` smallint(6) DEFAULT NULL,
  `tl_5_gleason2` smallint(6) DEFAULT NULL,
  `tl_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `tl_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `tl_5_gleason4` smallint(6) DEFAULT NULL,
  `tl_5_diff` smallint(6) DEFAULT NULL,
  `mlr_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `mlr_anz_positiv` smallint(6) DEFAULT NULL,
  `mlr_1_laenge` smallint(11) DEFAULT NULL,
  `mlr_1_tumoranteil` smallint(11) DEFAULT NULL,
  `mlr_1_gleason1` smallint(6) DEFAULT NULL,
  `mlr_1_gleason2` smallint(6) DEFAULT NULL,
  `mlr_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mlr_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mlr_1_gleason4` smallint(6) DEFAULT NULL,
  `mlr_1_diff` smallint(6) DEFAULT NULL,
  `mlr_2_laenge` smallint(11) DEFAULT NULL,
  `mlr_2_tumoranteil` smallint(11) DEFAULT NULL,
  `mlr_2_gleason1` smallint(6) DEFAULT NULL,
  `mlr_2_gleason2` smallint(6) DEFAULT NULL,
  `mlr_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mlr_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mlr_2_gleason4` smallint(6) DEFAULT NULL,
  `mlr_2_diff` smallint(6) DEFAULT NULL,
  `mlr_3_laenge` smallint(11) DEFAULT NULL,
  `mlr_3_tumoranteil` smallint(11) DEFAULT NULL,
  `mlr_3_gleason1` smallint(6) DEFAULT NULL,
  `mlr_3_gleason2` smallint(6) DEFAULT NULL,
  `mlr_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mlr_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mlr_3_gleason4` smallint(6) DEFAULT NULL,
  `mlr_3_diff` smallint(6) DEFAULT NULL,
  `mlr_4_laenge` smallint(11) DEFAULT NULL,
  `mlr_4_tumoranteil` smallint(11) DEFAULT NULL,
  `mlr_4_gleason1` smallint(6) DEFAULT NULL,
  `mlr_4_gleason2` smallint(6) DEFAULT NULL,
  `mlr_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mlr_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mlr_4_gleason4` smallint(6) DEFAULT NULL,
  `mlr_4_diff` smallint(6) DEFAULT NULL,
  `mlr_5_laenge` smallint(11) DEFAULT NULL,
  `mlr_5_tumoranteil` smallint(11) DEFAULT NULL,
  `mlr_5_gleason1` smallint(6) DEFAULT NULL,
  `mlr_5_gleason2` smallint(6) DEFAULT NULL,
  `mlr_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mlr_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mlr_5_gleason4` smallint(6) DEFAULT NULL,
  `mlr_5_diff` smallint(6) DEFAULT NULL,
  `mll_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `mll_anz_positiv` smallint(6) DEFAULT NULL,
  `mll_1_laenge` smallint(11) DEFAULT NULL,
  `mll_1_tumoranteil` smallint(11) DEFAULT NULL,
  `mll_1_gleason1` smallint(6) DEFAULT NULL,
  `mll_1_gleason2` smallint(6) DEFAULT NULL,
  `mll_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mll_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mll_1_gleason4` smallint(6) DEFAULT NULL,
  `mll_1_diff` smallint(6) DEFAULT NULL,
  `mll_2_laenge` smallint(11) DEFAULT NULL,
  `mll_2_tumoranteil` smallint(11) DEFAULT NULL,
  `mll_2_gleason1` smallint(6) DEFAULT NULL,
  `mll_2_gleason2` smallint(6) DEFAULT NULL,
  `mll_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mll_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mll_2_gleason4` smallint(6) DEFAULT NULL,
  `mll_2_diff` smallint(6) DEFAULT NULL,
  `mll_3_laenge` smallint(11) DEFAULT NULL,
  `mll_3_tumoranteil` smallint(11) DEFAULT NULL,
  `mll_3_gleason1` smallint(6) DEFAULT NULL,
  `mll_3_gleason2` smallint(6) DEFAULT NULL,
  `mll_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mll_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mll_3_gleason4` smallint(6) DEFAULT NULL,
  `mll_3_diff` smallint(6) DEFAULT NULL,
  `mll_4_laenge` smallint(11) DEFAULT NULL,
  `mll_4_tumoranteil` smallint(11) DEFAULT NULL,
  `mll_4_gleason1` smallint(6) DEFAULT NULL,
  `mll_4_gleason2` smallint(6) DEFAULT NULL,
  `mll_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mll_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mll_4_gleason4` smallint(6) DEFAULT NULL,
  `mll_4_diff` smallint(6) DEFAULT NULL,
  `mll_5_laenge` smallint(11) DEFAULT NULL,
  `mll_5_tumoranteil` smallint(11) DEFAULT NULL,
  `mll_5_gleason1` smallint(6) DEFAULT NULL,
  `mll_5_gleason2` smallint(6) DEFAULT NULL,
  `mll_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mll_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mll_5_gleason4` smallint(6) DEFAULT NULL,
  `mll_5_diff` smallint(6) DEFAULT NULL,
  `mr_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `mr_anz_positiv` smallint(6) DEFAULT NULL,
  `mr_1_laenge` smallint(11) DEFAULT NULL,
  `mr_1_tumoranteil` smallint(11) DEFAULT NULL,
  `mr_1_gleason1` smallint(6) DEFAULT NULL,
  `mr_1_gleason2` smallint(6) DEFAULT NULL,
  `mr_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mr_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mr_1_gleason4` smallint(6) DEFAULT NULL,
  `mr_1_diff` smallint(6) DEFAULT NULL,
  `mr_2_laenge` smallint(11) DEFAULT NULL,
  `mr_2_tumoranteil` smallint(11) DEFAULT NULL,
  `mr_2_gleason1` smallint(6) DEFAULT NULL,
  `mr_2_gleason2` smallint(6) DEFAULT NULL,
  `mr_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mr_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mr_2_gleason4` smallint(6) DEFAULT NULL,
  `mr_2_diff` smallint(6) DEFAULT NULL,
  `mr_3_laenge` smallint(11) DEFAULT NULL,
  `mr_3_tumoranteil` smallint(11) DEFAULT NULL,
  `mr_3_gleason1` smallint(6) DEFAULT NULL,
  `mr_3_gleason2` smallint(6) DEFAULT NULL,
  `mr_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mr_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mr_3_gleason4` smallint(6) DEFAULT NULL,
  `mr_3_diff` smallint(6) DEFAULT NULL,
  `mr_4_laenge` smallint(11) DEFAULT NULL,
  `mr_4_tumoranteil` smallint(11) DEFAULT NULL,
  `mr_4_gleason1` smallint(6) DEFAULT NULL,
  `mr_4_gleason2` smallint(6) DEFAULT NULL,
  `mr_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mr_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mr_4_gleason4` smallint(6) DEFAULT NULL,
  `mr_4_diff` smallint(6) DEFAULT NULL,
  `mr_5_laenge` smallint(11) DEFAULT NULL,
  `mr_5_tumoranteil` smallint(11) DEFAULT NULL,
  `mr_5_gleason1` smallint(6) DEFAULT NULL,
  `mr_5_gleason2` smallint(6) DEFAULT NULL,
  `mr_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `mr_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `mr_5_gleason4` smallint(6) DEFAULT NULL,
  `mr_5_diff` smallint(6) DEFAULT NULL,
  `ml_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `ml_anz_positiv` smallint(6) DEFAULT NULL,
  `ml_1_laenge` smallint(11) DEFAULT NULL,
  `ml_1_tumoranteil` smallint(11) DEFAULT NULL,
  `ml_1_gleason1` smallint(6) DEFAULT NULL,
  `ml_1_gleason2` smallint(6) DEFAULT NULL,
  `ml_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ml_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ml_1_gleason4` smallint(6) DEFAULT NULL,
  `ml_1_diff` smallint(6) DEFAULT NULL,
  `ml_2_laenge` smallint(11) DEFAULT NULL,
  `ml_2_tumoranteil` smallint(11) DEFAULT NULL,
  `ml_2_gleason1` smallint(6) DEFAULT NULL,
  `ml_2_gleason2` smallint(6) DEFAULT NULL,
  `ml_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ml_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ml_2_gleason4` smallint(6) DEFAULT NULL,
  `ml_2_diff` smallint(6) DEFAULT NULL,
  `ml_3_laenge` smallint(11) DEFAULT NULL,
  `ml_3_tumoranteil` smallint(11) DEFAULT NULL,
  `ml_3_gleason1` smallint(6) DEFAULT NULL,
  `ml_3_gleason2` smallint(6) DEFAULT NULL,
  `ml_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ml_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ml_3_gleason4` smallint(6) DEFAULT NULL,
  `ml_3_diff` smallint(6) DEFAULT NULL,
  `ml_4_laenge` smallint(11) DEFAULT NULL,
  `ml_4_tumoranteil` smallint(11) DEFAULT NULL,
  `ml_4_gleason1` smallint(6) DEFAULT NULL,
  `ml_4_gleason2` smallint(6) DEFAULT NULL,
  `ml_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ml_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ml_4_gleason4` smallint(6) DEFAULT NULL,
  `ml_4_diff` smallint(6) DEFAULT NULL,
  `ml_5_laenge` smallint(11) DEFAULT NULL,
  `ml_5_tumoranteil` smallint(11) DEFAULT NULL,
  `ml_5_gleason1` smallint(6) DEFAULT NULL,
  `ml_5_gleason2` smallint(6) DEFAULT NULL,
  `ml_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ml_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ml_5_gleason4` smallint(6) DEFAULT NULL,
  `ml_5_diff` smallint(6) DEFAULT NULL,
  `ar_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `ar_anz_positiv` smallint(6) DEFAULT NULL,
  `ar_1_laenge` smallint(11) DEFAULT NULL,
  `ar_1_tumoranteil` smallint(11) DEFAULT NULL,
  `ar_1_gleason1` smallint(6) DEFAULT NULL,
  `ar_1_gleason2` smallint(6) DEFAULT NULL,
  `ar_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ar_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ar_1_gleason4` smallint(6) DEFAULT NULL,
  `ar_1_diff` smallint(6) DEFAULT NULL,
  `ar_2_laenge` smallint(11) DEFAULT NULL,
  `ar_2_tumoranteil` smallint(11) DEFAULT NULL,
  `ar_2_gleason1` smallint(6) DEFAULT NULL,
  `ar_2_gleason2` smallint(6) DEFAULT NULL,
  `ar_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ar_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ar_2_gleason4` smallint(6) DEFAULT NULL,
  `ar_2_diff` smallint(6) DEFAULT NULL,
  `ar_3_laenge` smallint(11) DEFAULT NULL,
  `ar_3_tumoranteil` smallint(11) DEFAULT NULL,
  `ar_3_gleason1` smallint(6) DEFAULT NULL,
  `ar_3_gleason2` smallint(6) DEFAULT NULL,
  `ar_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ar_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ar_3_gleason4` smallint(6) DEFAULT NULL,
  `ar_3_diff` smallint(6) DEFAULT NULL,
  `ar_4_laenge` smallint(11) DEFAULT NULL,
  `ar_4_tumoranteil` smallint(11) DEFAULT NULL,
  `ar_4_gleason1` smallint(6) DEFAULT NULL,
  `ar_4_gleason2` smallint(6) DEFAULT NULL,
  `ar_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ar_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ar_4_gleason4` smallint(6) DEFAULT NULL,
  `ar_4_diff` smallint(6) DEFAULT NULL,
  `ar_5_laenge` smallint(11) DEFAULT NULL,
  `ar_5_tumoranteil` smallint(11) DEFAULT NULL,
  `ar_5_gleason1` smallint(6) DEFAULT NULL,
  `ar_5_gleason2` smallint(6) DEFAULT NULL,
  `ar_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `ar_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `ar_5_gleason4` smallint(6) DEFAULT NULL,
  `ar_5_diff` smallint(6) DEFAULT NULL,
  `al_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `al_anz_positiv` smallint(6) DEFAULT NULL,
  `al_1_laenge` smallint(11) DEFAULT NULL,
  `al_1_tumoranteil` smallint(11) DEFAULT NULL,
  `al_1_gleason1` smallint(6) DEFAULT NULL,
  `al_1_gleason2` smallint(6) DEFAULT NULL,
  `al_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `al_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `al_1_gleason4` smallint(6) DEFAULT NULL,
  `al_1_diff` smallint(6) DEFAULT NULL,
  `al_2_laenge` smallint(11) DEFAULT NULL,
  `al_2_tumoranteil` smallint(11) DEFAULT NULL,
  `al_2_gleason1` smallint(6) DEFAULT NULL,
  `al_2_gleason2` smallint(6) DEFAULT NULL,
  `al_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `al_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `al_2_gleason4` smallint(6) DEFAULT NULL,
  `al_2_diff` smallint(6) DEFAULT NULL,
  `al_3_laenge` smallint(11) DEFAULT NULL,
  `al_3_tumoranteil` smallint(11) DEFAULT NULL,
  `al_3_gleason1` smallint(6) DEFAULT NULL,
  `al_3_gleason2` smallint(6) DEFAULT NULL,
  `al_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `al_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `al_3_gleason4` smallint(6) DEFAULT NULL,
  `al_3_diff` smallint(6) DEFAULT NULL,
  `al_4_laenge` smallint(11) DEFAULT NULL,
  `al_4_tumoranteil` smallint(11) DEFAULT NULL,
  `al_4_gleason1` smallint(6) DEFAULT NULL,
  `al_4_gleason2` smallint(6) DEFAULT NULL,
  `al_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `al_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `al_4_gleason4` smallint(6) DEFAULT NULL,
  `al_4_diff` smallint(6) DEFAULT NULL,
  `al_5_laenge` smallint(11) DEFAULT NULL,
  `al_5_tumoranteil` smallint(11) DEFAULT NULL,
  `al_5_gleason1` smallint(6) DEFAULT NULL,
  `al_5_gleason2` smallint(6) DEFAULT NULL,
  `al_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `al_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `al_5_gleason4` smallint(6) DEFAULT NULL,
  `al_5_diff` smallint(6) DEFAULT NULL,
  `alr_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `alr_anz_positiv` smallint(6) DEFAULT NULL,
  `alr_1_laenge` smallint(11) DEFAULT NULL,
  `alr_1_tumoranteil` smallint(11) DEFAULT NULL,
  `alr_1_gleason1` smallint(6) DEFAULT NULL,
  `alr_1_gleason2` smallint(6) DEFAULT NULL,
  `alr_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `alr_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `alr_1_gleason4` smallint(6) DEFAULT NULL,
  `alr_1_diff` smallint(6) DEFAULT NULL,
  `alr_2_laenge` smallint(11) DEFAULT NULL,
  `alr_2_tumoranteil` smallint(11) DEFAULT NULL,
  `alr_2_gleason1` smallint(6) DEFAULT NULL,
  `alr_2_gleason2` smallint(6) DEFAULT NULL,
  `alr_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `alr_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `alr_2_gleason4` smallint(6) DEFAULT NULL,
  `alr_2_diff` smallint(6) DEFAULT NULL,
  `alr_3_laenge` smallint(11) DEFAULT NULL,
  `alr_3_tumoranteil` smallint(11) DEFAULT NULL,
  `alr_3_gleason1` smallint(6) DEFAULT NULL,
  `alr_3_gleason2` smallint(6) DEFAULT NULL,
  `alr_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `alr_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `alr_3_gleason4` smallint(6) DEFAULT NULL,
  `alr_3_diff` smallint(6) DEFAULT NULL,
  `alr_4_laenge` smallint(11) DEFAULT NULL,
  `alr_4_tumoranteil` smallint(11) DEFAULT NULL,
  `alr_4_gleason1` smallint(6) DEFAULT NULL,
  `alr_4_gleason2` smallint(6) DEFAULT NULL,
  `alr_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `alr_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `alr_4_gleason4` smallint(6) DEFAULT NULL,
  `alr_4_diff` smallint(6) DEFAULT NULL,
  `alr_5_laenge` smallint(11) DEFAULT NULL,
  `alr_5_tumoranteil` smallint(11) DEFAULT NULL,
  `alr_5_gleason1` smallint(6) DEFAULT NULL,
  `alr_5_gleason2` smallint(6) DEFAULT NULL,
  `alr_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `alr_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `alr_5_gleason4` smallint(6) DEFAULT NULL,
  `alr_5_diff` smallint(6) DEFAULT NULL,
  `all_beurteilung` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `all_anz_positiv` smallint(6) DEFAULT NULL,
  `all_1_laenge` smallint(11) DEFAULT NULL,
  `all_1_tumoranteil` smallint(11) DEFAULT NULL,
  `all_1_gleason1` smallint(6) DEFAULT NULL,
  `all_1_gleason2` smallint(6) DEFAULT NULL,
  `all_1_gleason1_anteil` smallint(6) DEFAULT NULL,
  `all_1_gleason2_anteil` smallint(6) DEFAULT NULL,
  `all_1_gleason4` smallint(6) DEFAULT NULL,
  `all_1_diff` smallint(6) DEFAULT NULL,
  `all_2_laenge` smallint(11) DEFAULT NULL,
  `all_2_tumoranteil` smallint(11) DEFAULT NULL,
  `all_2_gleason1` smallint(6) DEFAULT NULL,
  `all_2_gleason2` smallint(6) DEFAULT NULL,
  `all_2_gleason1_anteil` smallint(6) DEFAULT NULL,
  `all_2_gleason2_anteil` smallint(6) DEFAULT NULL,
  `all_2_gleason4` smallint(6) DEFAULT NULL,
  `all_2_diff` smallint(6) DEFAULT NULL,
  `all_3_laenge` smallint(11) DEFAULT NULL,
  `all_3_tumoranteil` smallint(11) DEFAULT NULL,
  `all_3_gleason1` smallint(6) DEFAULT NULL,
  `all_3_gleason2` smallint(6) DEFAULT NULL,
  `all_3_gleason1_anteil` smallint(6) DEFAULT NULL,
  `all_3_gleason2_anteil` smallint(6) DEFAULT NULL,
  `all_3_gleason4` smallint(6) DEFAULT NULL,
  `all_3_diff` smallint(6) DEFAULT NULL,
  `all_4_laenge` smallint(11) DEFAULT NULL,
  `all_4_tumoranteil` smallint(11) DEFAULT NULL,
  `all_4_gleason1` smallint(6) DEFAULT NULL,
  `all_4_gleason2` smallint(6) DEFAULT NULL,
  `all_4_gleason1_anteil` smallint(6) DEFAULT NULL,
  `all_4_gleason2_anteil` smallint(6) DEFAULT NULL,
  `all_4_gleason4` smallint(6) DEFAULT NULL,
  `all_4_diff` smallint(6) DEFAULT NULL,
  `all_5_laenge` smallint(11) DEFAULT NULL,
  `all_5_tumoranteil` smallint(11) DEFAULT NULL,
  `all_5_gleason1` smallint(6) DEFAULT NULL,
  `all_5_gleason2` smallint(6) DEFAULT NULL,
  `all_5_gleason1_anteil` smallint(6) DEFAULT NULL,
  `all_5_gleason2_anteil` smallint(6) DEFAULT NULL,
  `all_5_gleason4` smallint(6) DEFAULT NULL,
  `all_5_diff` smallint(6) DEFAULT NULL,
  `l_beurteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `l_anz` int(11) DEFAULT NULL,
  `l_anz_positiv` int(11) DEFAULT NULL,
  `l_laenge` double DEFAULT NULL,
  `l_tumoranteil` double DEFAULT NULL,
  `r_beurteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `r_anz` int(11) DEFAULT NULL,
  `r_anz_positiv` int(11) DEFAULT NULL,
  `r_laenge` double DEFAULT NULL,
  `r_tumoranteil` double DEFAULT NULL,
  `stanzen_ges_anz` int(11) DEFAULT NULL,
  `stanzen_ges_anz_positiv` int(11) DEFAULT NULL,
  `lk_sentinel_entf` int(11) DEFAULT NULL,
  `lk_sentinel_bef` int(11) DEFAULT NULL,
  `lk_12_entf` int(11) DEFAULT NULL,
  `lk_12_bef_makro` int(11) DEFAULT NULL,
  `lk_12_bef_mikro` int(11) DEFAULT NULL,
  `lk_3_entf` int(11) DEFAULT NULL,
  `lk_3_bef_makro` int(11) DEFAULT NULL,
  `lk_3_bef_mikro` int(11) DEFAULT NULL,
  `lk_ip_entf` int(11) DEFAULT NULL,
  `lk_ip_bef_makro` int(11) DEFAULT NULL,
  `lk_ip_bef_mikro` int(11) DEFAULT NULL,
  `lk_bef_makro` int(11) DEFAULT NULL,
  `lk_bef_mikro` int(11) DEFAULT NULL,
  `lk_hilus_entf` int(11) DEFAULT NULL,
  `lk_hilus_bef_mikro` int(11) DEFAULT NULL,
  `lk_hilus_bef_makro` int(11) DEFAULT NULL,
  `lk_interlobaer_entf` int(11) DEFAULT NULL,
  `lk_interlobaer_bef_mikro` int(11) DEFAULT NULL,
  `lk_interlobaer_bef_makro` int(11) DEFAULT NULL,
  `lk_lobaer_entf` int(11) DEFAULT NULL,
  `lk_lobaer_bef_mikro` int(11) DEFAULT NULL,
  `lk_lobaer_bef_makro` int(11) DEFAULT NULL,
  `lk_segmental_entf` int(11) DEFAULT NULL,
  `lk_segmental_bef_mikro` int(11) DEFAULT NULL,
  `lk_segmental_bef_makro` int(11) DEFAULT NULL,
  `lk_lig_pul_entf` int(11) DEFAULT NULL,
  `lk_lig_pul_bef_mikro` int(11) DEFAULT NULL,
  `lk_lig_pul_bef_makro` int(11) DEFAULT NULL,
  `lk_paraoeso_entf` int(11) DEFAULT NULL,
  `lk_paraoeso_bef_mikro` int(11) DEFAULT NULL,
  `lk_paraoeso_bef_makro` int(11) DEFAULT NULL,
  `lk_subcarinal_entf` int(11) DEFAULT NULL,
  `lk_subcarinal_bef_mikro` int(11) DEFAULT NULL,
  `lk_subcarinal_bef_makro` int(11) DEFAULT NULL,
  `lk_paraaortal_entf` int(11) DEFAULT NULL,
  `lk_paraaortal_bef_mikro` int(11) DEFAULT NULL,
  `lk_paraaortal_bef_makro` int(11) DEFAULT NULL,
  `lk_subaortal_entf` int(11) DEFAULT NULL,
  `lk_subaortal_bef_mikro` int(11) DEFAULT NULL,
  `lk_subaortal_bef_makro` int(11) DEFAULT NULL,
  `lk_unt_paratrach_entf` int(11) DEFAULT NULL,
  `lk_unt_paratrach_bef_mikro` int(11) DEFAULT NULL,
  `lk_unt_paratrach_bef_makro` int(11) DEFAULT NULL,
  `lk_prae_retro_trach_entf` int(11) DEFAULT NULL,
  `lk_prae_retro_trach_bef_mikro` int(11) DEFAULT NULL,
  `lk_prae_retro_trach_bef_makro` int(11) DEFAULT NULL,
  `lk_ob_paratrach_entf` int(11) DEFAULT NULL,
  `lk_ob_paratrach_bef_mikro` int(11) DEFAULT NULL,
  `lk_ob_paratrach_bef_makro` int(11) DEFAULT NULL,
  `lk_mediastinum_entf` int(11) DEFAULT NULL,
  `lk_mediastinum_bef_mikro` int(11) DEFAULT NULL,
  `lk_mediastinum_bef_makro` int(11) DEFAULT NULL,
  `lk_l_entf` int(11) DEFAULT NULL,
  `lk_l_bef_mikro` int(11) DEFAULT NULL,
  `lk_l_bef_makro` int(11) DEFAULT NULL,
  `lk_r_entf` int(11) DEFAULT NULL,
  `lk_r_bef_mikro` int(11) DEFAULT NULL,
  `lk_r_bef_makro` int(11) DEFAULT NULL,
  `lk_pelvin_entf` int(11) DEFAULT NULL,
  `lk_pelvin_bef` int(11) DEFAULT NULL,
  `lk_para_entf` int(11) DEFAULT NULL,
  `lk_para_bef` int(11) DEFAULT NULL,
  `lk_inguinal_entf` int(11) DEFAULT NULL,
  `lk_inguinal_bef` int(11) DEFAULT NULL,
  `lk_inguinal_makro` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_inguinal_mikro` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_iliakal_entf` int(11) DEFAULT NULL,
  `lk_iliakal_bef` int(11) DEFAULT NULL,
  `lk_iliakal_makro` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_iliakal_mikro` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_axillaer_entf` int(11) DEFAULT NULL,
  `lk_axillaer_bef` int(11) DEFAULT NULL,
  `lk_axillaer_makro` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_axillaer_mikro` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_zervikal_entf` int(11) DEFAULT NULL,
  `lk_zervikal_bef` int(11) DEFAULT NULL,
  `lk_zervikal_makro` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_zervikal_mikro` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_inguinal_l_entf` int(11) DEFAULT NULL,
  `lk_inguinal_l_bef` int(11) DEFAULT NULL,
  `lk_inguinal_r_entf` int(11) DEFAULT NULL,
  `lk_inguinal_r_bef` int(11) DEFAULT NULL,
  `lk_andere1` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_andere1_entf` int(11) DEFAULT NULL,
  `lk_andere1_bef` int(11) DEFAULT NULL,
  `lk_andere2` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_andere2_entf` int(11) DEFAULT NULL,
  `lk_andere2_bef` int(11) DEFAULT NULL,
  `lk_pelvin_externa_l_entf` int(11) DEFAULT NULL,
  `lk_pelvin_externa_l_bef` int(11) DEFAULT NULL,
  `lk_pelvin_interna_l_entf` int(11) DEFAULT NULL,
  `lk_pelvin_interna_l_bef` int(11) DEFAULT NULL,
  `lk_pelvin_fossa_l_entf` int(11) DEFAULT NULL,
  `lk_pelvin_fossa_l_bef` int(11) DEFAULT NULL,
  `lk_pelvin_communis_l_entf` int(11) DEFAULT NULL,
  `lk_pelvin_communis_l_bef` int(11) DEFAULT NULL,
  `lk_pelvin_externa_r_entf` int(11) DEFAULT NULL,
  `lk_pelvin_externa_r_bef` int(11) DEFAULT NULL,
  `lk_pelvin_interna_r_entf` int(11) DEFAULT NULL,
  `lk_pelvin_interna_r_bef` int(11) DEFAULT NULL,
  `lk_pelvin_fossa_r_entf` int(11) DEFAULT NULL,
  `lk_pelvin_fossa_r_bef` int(11) DEFAULT NULL,
  `lk_pelvin_communis_r_entf` int(11) DEFAULT NULL,
  `lk_pelvin_communis_r_bef` int(11) DEFAULT NULL,
  `lk_para_paracaval_entf` int(11) DEFAULT NULL,
  `lk_para_paracaval_bef` int(11) DEFAULT NULL,
  `lk_para_interaortocaval_entf` int(11) DEFAULT NULL,
  `lk_para_interaortocaval_bef` int(11) DEFAULT NULL,
  `lk_para_cranial_ami_entf` int(11) DEFAULT NULL,
  `lk_para_cranial_ami_bef` int(11) DEFAULT NULL,
  `lk_para_caudal_ami_entf` int(11) DEFAULT NULL,
  `lk_para_caudal_ami_bef` int(11) DEFAULT NULL,
  `lk_para_cranial_vr_entf` int(11) DEFAULT NULL,
  `lk_para_cranial_vr_bef` int(11) DEFAULT NULL,
  `hpv` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ01` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ02` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ03` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ04` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ05` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ06` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ07` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ08` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_typ09` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis01` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis02` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis03` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis04` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis05` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis06` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis07` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis08` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hpv_ergebnis09` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_entf` int(11) DEFAULT NULL,
  `lk_bef` int(11) DEFAULT NULL,
  `lk_mikrometastasen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `groesste_ausdehnung` double DEFAULT NULL,
  `kapseldurchbruch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `estro` double DEFAULT NULL,
  `estro_irs` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `estro_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `prog` double DEFAULT NULL,
  `prog_irs` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `prog_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2_fish` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2_fish_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pai1` double DEFAULT NULL,
  `upa` double DEFAULT NULL,
  `egf` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vegf` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `chromogranin` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kras` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `braf` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `egfr` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `egfr_mutation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nse` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ercc1` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ttf1` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `alk` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ros` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `psa` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pcna` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `epca2` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `p53` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ps2` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kathepsin_d` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hmb45` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `melan_a` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `s100` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_grading` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_groesse` double DEFAULT NULL,
  `dcis_resektionsrand` double DEFAULT NULL,
  `dcis_van_nuys` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_vnpi` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_morphologie` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_morphologie_text` text COLLATE latin1_german1_ci,
  `dcis_morphologie_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_kerngrading` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_nekrosen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_histologie_einzel` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_histologie_einzel_id` int(11) NOT NULL,
  `histologie_einzel_id` int(11) NOT NULL DEFAULT '0',
  `histologie_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `materialgewinnung_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `materialgewinnung_anzahl` int(11) DEFAULT NULL,
  `diagnose_id` int(11) DEFAULT NULL,
  `schnittechnik` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `clark` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mikroskopisch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `groesse_x` double DEFAULT NULL,
  `groesse_y` double DEFAULT NULL,
  `groesse_z` double DEFAULT NULL,
  `morphologie` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_text` text COLLATE latin1_german1_ci,
  `morphologie_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `unauffaellig` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ptnm_praefix` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `g` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `l` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `v` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `r` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ulzeration` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ppn` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `uicc` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `regression` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `perineurale_invasion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `wachstumsphase` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `melanom_muttermal` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `randkontrolle` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `resektionsrand` double DEFAULT NULL,
  `tumordicke` double DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_komplikation` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_komplikation_id` int(11) NOT NULL,
  `komplikation_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `komplikation` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `eingriff_id` int(11) DEFAULT NULL,
  `untersuchung_id` int(11) DEFAULT NULL,
  `zeitpunkt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `clavien_dindo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ctcae` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `reintervention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `antibiotikum` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `antibiotikum_dauer` double DEFAULT NULL,
  `drainage_intervent` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `drainage_transanal` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sekundaerheilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `revisionsoperation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `wund_spuelung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `wund_spreizung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `wund_vac` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `transfusion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `transfusion_anzahl_ek` int(11) DEFAULT NULL,
  `transfusion_anzahl_tk` int(11) DEFAULT NULL,
  `transfusion_anzahl_ffp` int(11) DEFAULT NULL,
  `gerinnungshemmer` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beatmung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beatmung_dauer` double DEFAULT NULL,
  `intensivstation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `intensivstation_dauer` double DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_konferenz` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_konferenz_id` int(11) NOT NULL,
  `konferenz_id` int(11) NOT NULL DEFAULT '0',
  `datum` date NOT NULL,
  `uhrzeit_beginn` varchar(5) COLLATE latin1_german1_ci NOT NULL,
  `uhrzeit_ende` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `moderator_id` int(11) DEFAULT NULL,
  `bem_einladung` text COLLATE latin1_german1_ci,
  `bem_abschluss` text COLLATE latin1_german1_ci,
  `teilnehmer` int(10) NOT NULL DEFAULT '0',
  `teilnehmer_bes` int(11) NOT NULL DEFAULT '0',
  `final` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_konferenz_abschluss` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_konferenz_abschluss_id` int(11) NOT NULL,
  `konferenz_abschluss_id` int(11) NOT NULL DEFAULT '0',
  `konferenz_teilnehmer_id` int(11) NOT NULL,
  `dokument_status` text COLLATE latin1_german1_ci,
  `epikrise_status` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_konferenz_dokument` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_konferenz_dokument_id` int(11) NOT NULL,
  `konferenz_dokument_id` int(11) NOT NULL DEFAULT '0',
  `konferenz_id` int(11) NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `datei` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `konferenz_patient_id` int(11) DEFAULT NULL,
  `dokument_id` int(11) DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_konferenz_patient` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_konferenz_patient_id` int(11) NOT NULL,
  `konferenz_patient_id` int(11) NOT NULL DEFAULT '0',
  `konferenz_id` int(11) DEFAULT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `art` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `vorlage_dokument_id` int(11) NOT NULL,
  `fragestellung` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `patientenwunsch` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `patientenwunsch_beo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `patientenwunsch_nerverhalt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaervorstellung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaervorstellung_sonst` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `biopsie_durch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `biopsie_durch_sonst` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `mskcc` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mskcc_ic` float DEFAULT NULL,
  `mskcc_svi` float DEFAULT NULL,
  `mskcc_ocd` float DEFAULT NULL,
  `mskcc_lni` float DEFAULT NULL,
  `mskcc_ee` float DEFAULT NULL,
  `fotos` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `datenstand_datum` datetime DEFAULT NULL,
  `document_process` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `document_final` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `document_dirty` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_konferenz_teilnehmer` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_konferenz_teilnehmer_id` int(11) NOT NULL,
  `konferenz_teilnehmer_id` int(11) NOT NULL DEFAULT '0',
  `konferenz_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email_status` text COLLATE latin1_german1_ci,
  `teilgenommen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_konferenz_teilnehmer_profil` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_konferenz_teilnehmer_profil_id` int(11) NOT NULL,
  `konferenz_teilnehmer_profil_id` int(11) NOT NULL DEFAULT '0',
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `user_list` text COLLATE latin1_german1_ci,
  `user_count` smallint(6) NOT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_labor` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_labor_id` int(11) NOT NULL,
  `labor_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `vorlage_labor_id` int(11) NOT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_labor_wert` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_labor_wert_id` int(11) NOT NULL,
  `labor_wert_id` int(11) NOT NULL DEFAULT '0',
  `labor_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_labor_wert_id` int(11) NOT NULL,
  `parameter` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `wert` double DEFAULT NULL,
  `einheit` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `beurteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_nachsorge` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_nachsorge_id` int(11) NOT NULL,
  `nachsorge_id` int(11) NOT NULL DEFAULT '0',
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ecog` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gewicht` double DEFAULT NULL,
  `malignom` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachsorge_biopsie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `empfehlung_befolgt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumormarkerverlauf` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `psa_bestimmt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `labor_id` int(11) DEFAULT NULL,
  `response_klinisch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `response_klinisch_bestaetigt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `euroqol` int(11) DEFAULT NULL,
  `lcss` int(11) DEFAULT NULL,
  `fb_dkg` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `fb_dkg_beurt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `iciq_ui` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ics` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `iief5` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ipss` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lq_dkg` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gz_dkg` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ql` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `armbeweglichkeit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `umfang_oberarm` double DEFAULT NULL,
  `umfang_unterarm` double DEFAULT NULL,
  `hads_d_depression` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hads_d_angst` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pde5hemmer` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `pde5hemmer_haeufigkeit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vakuumpumpe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `skat` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `penisprothese` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzen_lokalisation` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzen_lokalisation_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzen_lokalisation_text` text COLLATE latin1_german1_ci,
  `sy_schmerzen_lokalisation_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_schmerzscore` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_dyspnoe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_haemoptnoe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_husten` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_husten_dauer` int(11) DEFAULT NULL,
  `sy_harndrang` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_nykturie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_pollakisurie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_miktion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_restharn` double DEFAULT NULL,
  `sy_harnverhalt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_harnstau` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_harnstau_lokalisation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_haematurie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_para_syndrom` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_para_syndrom_symptom` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_para_syndrom_detail` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_gewichtsverlust` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_gewichtsverlust_2wo` double DEFAULT NULL,
  `sy_gewichtsverlust_3mo` double DEFAULT NULL,
  `sy_fieber` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_nachtschweiss` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `sy_dauer` int(11) DEFAULT NULL,
  `sy_dauer_einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `analgetika` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `schmerzmedikation_stufe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `response_schmerztherapie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `scapula_alata` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphoedem` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphoedem_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphdrainage` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sensibilitaet` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kontinenz` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorlagenverbrauch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `spaetschaden_blase` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `spaetschaden_blase_grad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `spaetschaden_rektum` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `spaetschaden_rektum_grad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_nachsorge_erkrankung` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_nachsorge_erkrankung_id` int(11) NOT NULL,
  `nachsorge_erkrankung_id` int(11) NOT NULL DEFAULT '0',
  `nachsorge_id` int(11) NOT NULL,
  `erkrankung_weitere_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_nebenwirkung` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_nebenwirkung_id` int(11) NOT NULL,
  `nebenwirkung_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `nci_code` varchar(27) COLLATE latin1_german1_ci NOT NULL,
  `nci_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `nci_code_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `grad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ausgang` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `beginn` date DEFAULT NULL,
  `beginn_unbekannt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ende` date DEFAULT NULL,
  `ende_unbekannt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `zusammenhang` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapie_systemisch_id` int(11) DEFAULT NULL,
  `strahlentherapie_id` int(11) DEFAULT NULL,
  `sonstige_therapie_id` int(11) DEFAULT NULL,
  `therapie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapie_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `sae` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_org` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_org_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `namenszusatz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefon` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefax` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `email` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `website` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `strasse` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `hausnr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `plz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `ort` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `staat` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bundesland` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ik_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `kr_kennung` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `mandant` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `img_type` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_patient` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_patient_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL DEFAULT '0',
  `org_id` int(11) NOT NULL,
  `patient_nr` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `titel` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `adelstitel` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachname` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `vorname` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `geschlecht` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `geburtsdatum` date NOT NULL,
  `geburtsname` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `datenaustausch` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `datenspeicherung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `datenversand` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `krebsregister` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `geburtsort` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `strasse` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `hausnr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `adrzusatz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `plz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `ort` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `land` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefon` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefax` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `email` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `staat` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_iknr` varchar(35) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_abrechnungsbereich` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_fa` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_status` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_statusergaenzung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_wop` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_besondere_personengruppe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_dmp_kennzeichnung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kv_versicherungsschutz_beginn` date DEFAULT NULL,
  `kv_versicherungsschutz_ende` date DEFAULT NULL,
  `kv_gueltig_bis` date DEFAULT NULL,
  `kv_einlesedatum` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `erkrankungen` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_qs_18_1_b` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_qs_18_1_b_id` int(11) NOT NULL,
  `qs_18_1_b_id` int(11) NOT NULL DEFAULT '0',
  `patient_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `aufenthalt_id` int(11) NOT NULL,
  `idnrpat` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndatum` date DEFAULT NULL,
  `aufndiag_1` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_1_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_1_text` text COLLATE latin1_german1_ci,
  `aufndiag_1_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_2` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_2_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_2_text` text COLLATE latin1_german1_ci,
  `aufndiag_2_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_3` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_3_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_3_text` text COLLATE latin1_german1_ci,
  `aufndiag_3_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_4` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_4_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_4_text` text COLLATE latin1_german1_ci,
  `aufndiag_4_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_5` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_5_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aufndiag_5_text` text COLLATE latin1_german1_ci,
  `aufndiag_5_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `asa` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `adjutherapieplanung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `planbesprochen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `planbesprochendatum` date DEFAULT NULL,
  `meldungkrebsregister` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldatum` date DEFAULT NULL,
  `entldiag_1` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldiag_1_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldiag_1_text` text COLLATE latin1_german1_ci,
  `entldiag_1_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldiag_2` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldiag_2_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldiag_2_text` text COLLATE latin1_german1_ci,
  `entldiag_2_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldiag_3` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldiag_3_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `entldiag_3_text` text COLLATE latin1_german1_ci,
  `entldiag_3_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `entlgrund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sektion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_qs_18_1_brust` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_qs_18_1_brust_id` int(11) NOT NULL,
  `qs_18_1_brust_id` int(11) NOT NULL DEFAULT '0',
  `qs_18_1_b_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `zuopseite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `arterkrank` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `erstoffeingriff` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tastbarmammabefund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `primaertumor` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `regiolymphknoten` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `anlasstumordiag` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `anlasstumordiageigen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anlasstumordiagfrueh` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `mammographiescreening` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `anlasstumordiagsympt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anlasstumordiagnachsorge` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `anlasstumordiagsonst` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `praehistdiagsicherung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `praehistbefund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `praeicdo3` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ausganghistbefund` date DEFAULT NULL,
  `praethinterdisztherapieplan` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `datumtherapieplan` date DEFAULT NULL,
  `praeoptumorth` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `systchemoth` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `endokrinth` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `spezifantiktherapie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlenth` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonstth` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `pokomplikatspez` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pokowundinfektion` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachblutung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `serom` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `pokosonst` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `posthistbefund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `posticdo3` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `optherapieende` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumortherapieempf` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tnmptmamma` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tnmpnmamma` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `anzahllypmphknoten` int(11) DEFAULT NULL,
  `anzahllypmphknotenunb` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `graddcis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gesamttumorgroesse` int(11) DEFAULT NULL,
  `tnmgmamma` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rezeptorstatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2neustatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `multizentrizitaet` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `angabensicherabstand` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sicherabstand` int(11) DEFAULT NULL,
  `mnachstaging` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bet` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `axilladissektion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `axlkentfomark` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `slkbiopsie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `radionuklidmarkierung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `farbmarkierung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_qs_18_1_o` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_qs_18_1_o_id` int(11) NOT NULL,
  `qs_18_1_o_id` int(11) NOT NULL DEFAULT '0',
  `qs_18_1_brust_id` int(11) NOT NULL,
  `qs_18_1_b_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `lfdnreingriff` int(11) DEFAULT NULL,
  `diagoffbiopsie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `praeopmarkierung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `praeopmammographiejl` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraoppraeparatroentgen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `praeopsonographiejl` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `intraoppraeparatsono` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `praeopmrtjl` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `opdatum` date DEFAULT NULL,
  `opschluessel_1` varchar(29) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_1_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_1_text` text COLLATE latin1_german1_ci,
  `opschluessel_1_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_2` varchar(29) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_2_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_2_text` text COLLATE latin1_german1_ci,
  `opschluessel_2_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_3` varchar(29) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_3_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_3_text` text COLLATE latin1_german1_ci,
  `opschluessel_3_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_4` varchar(29) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_4_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_4_text` text COLLATE latin1_german1_ci,
  `opschluessel_4_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_5` varchar(29) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_5_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_5_text` text COLLATE latin1_german1_ci,
  `opschluessel_5_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_6` varchar(29) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_6_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `opschluessel_6_text` text COLLATE latin1_german1_ci,
  `opschluessel_6_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `sentinellkeingriff` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `antibioprph` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_recht` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_recht_id` int(11) NOT NULL,
  `recht_id` int(11) NOT NULL DEFAULT '0',
  `org_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rolle` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `behandler` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `recht_global` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_recht_erkrankung` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_recht_erkrankung_id` int(11) NOT NULL,
  `recht_erkrankung_id` int(11) NOT NULL DEFAULT '0',
  `recht_id` int(11) NOT NULL,
  `erkrankung` varchar(30) COLLATE latin1_german1_ci NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_settings` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_settings_id` int(11) NOT NULL,
  `settings_id` int(11) NOT NULL DEFAULT '0',
  `software_version` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `software_title` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `software_custom_title` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `fastreg` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `fastreg_role` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `auto_patient_id` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `patient_initials_only` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `show_last_login` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `allow_registration` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `allow_password_reset` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `user_max_login` int(11) DEFAULT NULL,
  `user_max_login_deactivated` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `pat_list_first` varchar(26) COLLATE latin1_german1_ci NOT NULL,
  `pat_list_second` varchar(26) COLLATE latin1_german1_ci NOT NULL,
  `extended_swage` int(1) DEFAULT NULL,
  `show_pictures` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `report_debug` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `deactivate_range_check` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `fake_system_date` date DEFAULT NULL,
  `logo` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `img_type` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `check_ie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_b` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_d` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_gt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_h` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_kh` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_leu` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_lg` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_lu` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_ly` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_m` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_nt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_oes` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_p` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_pa` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_snst` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung_sst` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `feature_dkg_oz` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `feature_dkg_b` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `feature_dkg_d` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `feature_dkg_gt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `feature_dkg_h` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `feature_dkg_lu` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `feature_dkg_p` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_gekid` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_gekid_plus` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_ekr_h` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_ekr_rp` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_ekr_sh` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_krbw` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_hl7_e` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_gkr` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_adt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_gtds` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_onkeyline` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_dmp_2014` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_qs181` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_eusoma` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_wbc` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_wdc` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_onkonet` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_patho_e` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_oncobox_darm` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_oncobox_prostata` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_patho_i` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `interface_kr_he` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `konferenz` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `email_attachment` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `zweitmeinung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rolle_konferenzteilnehmer` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rolle_dateneingabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tools` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `dokument` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `pacs` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `max_pacs_savetime` int(11) DEFAULT NULL,
  `codepicker_top_limit` int(11) DEFAULT NULL,
  `status_lasttime` datetime DEFAULT NULL,
  `historys_path` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_settings_export` (
  `a_action` varchar(14) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_settings_export_id` int(11) NOT NULL,
  `settings_export_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `settings` text COLLATE latin1_german1_ci,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_settings_forms` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_settings_forms_id` int(11) NOT NULL,
  `settings_forms_id` int(11) NOT NULL DEFAULT '0',
  `org_id` int(11) DEFAULT NULL,
  `forms` text COLLATE latin1_german1_ci,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_settings_hl7` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_settings_hl7_id` int(11) NOT NULL,
  `settings_hl7_id` int(11) NOT NULL DEFAULT '0',
  `bem` text COLLATE latin1_german1_ci,
  `active` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `import_mode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `valid_event_types` text COLLATE latin1_german1_ci,
  `patient_ident` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `user_ident` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_dir` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `max_log_time` smallint(6) NOT NULL,
  `max_usability_time` smallint(6) DEFAULT NULL,
  `update_patient_due_caching` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_diagnose_active` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_diagnose_hl7` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_diagnose_filter` text COLLATE latin1_german1_ci,
  `cache_diagnosetyp_active` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_diagnosetyp_hl7` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_diagnosetyp_filter` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_abteilung_active` varchar(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_abteilung_hl7` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `cache_abteilung_filter` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `import_diagnose_active` varchar(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `import_diagnose_hl7` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `import_diagnose_filter` text COLLATE latin1_german1_ci,
  `import_diagnosetyp_active` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `import_diagnosetyp_hl7` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `import_diagnosetyp_filter` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_settings_hl7field` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_settings_hl7field_id` int(11) NOT NULL,
  `settings_hl7field_id` int(11) NOT NULL DEFAULT '0',
  `settings_hl7_id` int(11) NOT NULL,
  `med_feld` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `import` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `hl7` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `hl7_bereich` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `hl7_back` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `feld_typ` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `feld_trim_null` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `multiple` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `multiple_segment` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `multiple_filter` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `ext` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_settings_import` (
  `a_action` varchar(14) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_settings_import_id` int(11) NOT NULL,
  `settings_import_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `settings` text COLLATE latin1_german1_ci,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_settings_pacs` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_settings_pacs_id` int(11) NOT NULL,
  `settings_pacs_id` int(11) NOT NULL DEFAULT '0',
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `ae_title` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `hostname` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `cipher` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_settings_report` (
  `a_action` varchar(14) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_settings_report_id` int(11) NOT NULL,
  `settings_report_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `erkrankung` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `settings` text COLLATE latin1_german1_ci,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_sonstige_therapie` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_sonstige_therapie_id` int(11) NOT NULL,
  `sonstige_therapie_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `sonstige_art` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `therapieplan_id` int(11) DEFAULT NULL,
  `intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `studie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `studie_id` int(11) DEFAULT NULL,
  `beginn` date NOT NULL,
  `ende` date DEFAULT NULL,
  `endstatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `endstatus_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `endstatus_grund_sonst` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `best_response` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `best_response_datum` date DEFAULT NULL,
  `unterbrechung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterbrechung_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterbrechung_grund_sonst` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_strahlentherapie` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_strahlentherapie_id` int(11) NOT NULL,
  `strahlentherapie_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_therapie_id` int(11) DEFAULT NULL,
  `hyperthermie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorlage_therapie_art` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `therapieplan_id` int(11) DEFAULT NULL,
  `intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapieform` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `studie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `studie_id` int(11) DEFAULT NULL,
  `beginn` date NOT NULL,
  `ende` date DEFAULT NULL,
  `andauernd` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `zahnarzt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_ganzkoerper` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_primaertumor` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_mamma_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_mamma_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_brustwand_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_brustwand_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_mammaria_interna` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_mediastinum` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_prostata` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_becken` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_abdomen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_vulva` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_vulva_pelvin` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_vulva_inguinal` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_inguinal_einseitig` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_ingu_beidseitig` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_ingu_pelvin` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_vagina` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lymph` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_paraaortal` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_axilla_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_axilla_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_supra` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_para` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_iliakal` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_zervikal_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_zervikal_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_hilaer` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_axillaer_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_axillaer_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_abdominell_o` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_abdominell_u` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_iliakal_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_iliakal_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_inguinal_r` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_lk_inguinal_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_knochen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_gehirn` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_sonst` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_sonst_detail` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_sonst_detail_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ziel_sonst_detail_text` text COLLATE latin1_german1_ci,
  `ziel_sonst_detail_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `art` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `fraktionierungstyp` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `einzeldosis` double DEFAULT NULL,
  `gesamtdosis` double DEFAULT NULL,
  `boost` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `boostdosis` double DEFAULT NULL,
  `dosierung_icru` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `imrt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `igrt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `beschleunigerenergie` double DEFAULT NULL,
  `seed_strahlung_90d` double DEFAULT NULL,
  `seed_strahlung_90d_datum` date DEFAULT NULL,
  `endstatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `endstatus_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `best_response` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `best_response_datum` date DEFAULT NULL,
  `dosisreduktion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dosisreduktion_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dosisreduktion_grund_sonst` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterbrechung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterbrechung_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterbrechung_grund_sonst` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_studie` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_studie_id` int(11) NOT NULL,
  `studie_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_studie_id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `beginn` date DEFAULT NULL,
  `ende` date DEFAULT NULL,
  `patient_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `arm` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_termin` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_termin_id` int(11) NOT NULL,
  `termin_id` int(11) NOT NULL DEFAULT '0',
  `org_id` int(11) DEFAULT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `art` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `datum` date NOT NULL,
  `uhrzeit` varchar(5) COLLATE latin1_german1_ci DEFAULT NULL,
  `dauer` int(11) DEFAULT NULL,
  `brief_gesendet` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erledigt` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erinnerung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erinnerung_datum` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_therapieplan` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_therapieplan_id` int(11) NOT NULL,
  `therapieplan_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `grundlage` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `leistungserbringer` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zeitpunkt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `konferenz_patient_id` int(11) DEFAULT NULL,
  `zweitmeinung_id` int(11) DEFAULT NULL,
  `vorgestellt` int(11) DEFAULT NULL,
  `vorgestellt2` int(11) DEFAULT NULL,
  `grund_keine_konferenz` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `grund_keine_konferenz_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `op` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_brusterhaltend` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_mastektomie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_nachresektion` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_sln` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_axilla` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `keine_axilla_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_prostata` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_nerverhaltend` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_lymphadenektomie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_transplantation_autolog` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_transplantation_allogen_v` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_transplantation_allogen_nv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art_transplantation_syngen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_extern` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `op_art` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_indiziert` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_mamma` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_axilla` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_lk_supra` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_lk_para` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_thoraxwand` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_sonstige` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_art` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_zielvolumen` int(11) DEFAULT NULL,
  `strahlen_gesamtdosis` double DEFAULT NULL,
  `strahlen_einzeldosis` double DEFAULT NULL,
  `strahlen_zeitpunkt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_extern` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `strahlen_lokalisation` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `chemo_indiziert` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `chemo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `chemo_intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `chemo_id` int(11) DEFAULT NULL,
  `chemo_extern` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `immun_indiziert` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `immun` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `immun_intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `immun_id` int(11) DEFAULT NULL,
  `immun_extern` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ah_indiziert` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ah` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ah_intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ah_id` int(11) DEFAULT NULL,
  `ah_therapiedauer_prostata` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ah_therapiedauer_monate` int(11) DEFAULT NULL,
  `ah_extern` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `andere_indiziert` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `andere` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `andere_intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `andere_id` int(11) DEFAULT NULL,
  `andere_extern` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonstige_indiziert` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonstige` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonstige_schema` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonstige_intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sonstige_extern` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `watchful_waiting` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `active_surveillance` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `abweichung_leitlinie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachsorge` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `abweichung_leitlinie_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `studie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `vorlage_studie_id` int(11) DEFAULT NULL,
  `studie_abweichung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachbehandler_id` int(11) DEFAULT NULL,
  `palliative_versorgung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `datum_palliative_versorgung` date DEFAULT NULL,
  `bem_palliative_versorgung` text COLLATE latin1_german1_ci,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_therapieplan_abweichung` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_therapieplan_abweichung_id` int(11) NOT NULL,
  `therapieplan_abweichung_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `therapieplan_id` int(11) NOT NULL,
  `bezug_eingriff` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bezug_strahlen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bezug_chemo` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bezug_immun` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bezug_ah` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bezug_andere` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bezug_sonstige` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `grund` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_therapie_systemisch` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_therapie_systemisch_id` int(11) NOT NULL,
  `therapie_systemisch_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_therapie_id` int(11) NOT NULL,
  `vorlage_therapie_art` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `therapieplan_id` int(11) DEFAULT NULL,
  `intention` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapieform` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapielinie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `metastasentherapie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `studie` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `studie_id` int(11) DEFAULT NULL,
  `beginn` date NOT NULL,
  `ende` date DEFAULT NULL,
  `andauernd` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ort_therapiegabe` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorverhalten_platin` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zahnarzt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `endstatus` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `endstatus_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `best_response` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `best_response_datum` date DEFAULT NULL,
  `best_response_bestimmung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dosisaenderung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dosisaenderung_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dosisaenderung_grund_sonst` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterbrechung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterbrechung_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `unterbrechung_grund_sonst` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `paravasat` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `regelmaessig` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `regelmaessig_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_therapie_systemisch_zyklus` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_therapie_systemisch_zyklus_id` int(11) NOT NULL,
  `therapie_systemisch_zyklus_id` int(11) NOT NULL DEFAULT '0',
  `therapie_systemisch_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `zyklus_nr` int(11) NOT NULL,
  `beginn` date NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `gewicht` double DEFAULT NULL,
  `groesse` int(11) DEFAULT NULL,
  `ecog` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `verschoben` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `verschoben_grund` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `response` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `response_datum` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_therapie_systemisch_zyklustag` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_therapie_systemisch_zyklustag_id` int(11) NOT NULL,
  `therapie_systemisch_zyklustag_id` int(11) NOT NULL DEFAULT '0',
  `therapie_systemisch_zyklus_id` int(11) NOT NULL,
  `therapie_systemisch_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `zyklustag` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_therapie_systemisch_zyklustag_wirkstoff` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_therapie_systemisch_zyklustag_wirkstoff_id` int(11) NOT NULL,
  `therapie_systemisch_zyklustag_wirkstoff_id` int(11) NOT NULL DEFAULT '0',
  `therapie_systemisch_zyklustag_id` int(11) NOT NULL,
  `therapie_systemisch_zyklus_id` int(11) NOT NULL,
  `therapie_systemisch_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_therapie_wirkstoff_id` int(11) NOT NULL,
  `dosis` double DEFAULT NULL,
  `einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aenderung_dosis` double DEFAULT NULL,
  `aenderung_einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `verabreicht_dosis` double DEFAULT NULL,
  `verabreicht_einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kreatinin` double DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_tumorstatus` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_tumorstatus_id` int(11) NOT NULL,
  `tumorstatus_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum_beurteilung` date NOT NULL,
  `anlass` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `datum_sicherung` date DEFAULT NULL,
  `diagnosesicherung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sicherungsgrad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorausbreitung_lokal` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorausbreitung_lk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorausbreitung_konausdehnung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `tumorausbreitung_fernmetastasen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rezidiv_lokal` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rezidiv_lk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `rezidiv_metastasen` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `quelle_metastasen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rezidiv_psa` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `mhrpc` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zweittumor` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `fall_vollstaendig` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nur_zweitmeinung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `nur_diagnosesicherung` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `kein_fall` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `zufall` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `diagnose` varchar(28) COLLATE latin1_german1_ci DEFAULT NULL,
  `diagnose_seite` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `diagnose_text` text COLLATE latin1_german1_ci,
  `diagnose_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `diagnose_c19_zuordnung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_text` text COLLATE latin1_german1_ci,
  `lokalisation_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_detail` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hoehe` int(11) DEFAULT NULL,
  `morphologie` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `morphologie_text` text COLLATE latin1_german1_ci,
  `morphologie_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `groesse_x` double DEFAULT NULL,
  `groesse_y` double DEFAULT NULL,
  `groesse_z` double DEFAULT NULL,
  `multizentrisch` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `multifokal` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `mikrokalk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_morphologie` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `dcis_morphologie_text` text COLLATE latin1_german1_ci,
  `dcis_morphologie_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `stadium_mason` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gleason1` int(11) DEFAULT NULL,
  `gleason2` int(11) DEFAULT NULL,
  `gleason3` int(11) DEFAULT NULL,
  `gleason4_anteil` int(11) DEFAULT NULL,
  `eignung_nerverhalt` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `eignung_nerverhalt_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_entf` int(11) DEFAULT NULL,
  `lk_bef` int(11) DEFAULT NULL,
  `lk_staging` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_sentinel_bef` int(11) DEFAULT NULL,
  `lk_sentinel_entf` int(11) DEFAULT NULL,
  `regressionsgrad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `tnm_praefix` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `t` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `n` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `m` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `g` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `l` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `v` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `r` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `r_lokal` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ppn` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `s` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `infiltration` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `befallen_n` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `befallen_m` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `invasionstiefe` double DEFAULT NULL,
  `resektionsrand` double DEFAULT NULL,
  `uicc` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `figo` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ajcc` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lugano` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nhl_who_b` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nhl_who_t` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hl_who` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ann_arbor_stadium` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ann_arbor_aktivitaetsgrad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ann_arbor_extralymphatisch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nhl_ipi` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `flipi` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `durie_salmon` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `iss` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `immun_phaenotyp` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `cll_rai` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `cll_binet` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aml_fab` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aml_who` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `all_egil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mds_fab` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mds_who` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `stadium_sclc` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_mediastinaltumor` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_extranodalbefall` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_bks` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `risiko_lk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `estro` double DEFAULT NULL,
  `estro_irs` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `estro_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `prog` double DEFAULT NULL,
  `prog_irs` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `prog_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2_fish` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2_fish_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `her2_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `psa` double DEFAULT NULL,
  `datum_psa` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_tumorstatus_metastasen` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_tumorstatus_metastasen_id` int(11) NOT NULL,
  `tumorstatus_metastasen_id` int(11) NOT NULL DEFAULT '0',
  `tumorstatus_id` int(11) DEFAULT NULL,
  `erkrankung_id` int(11) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `lokalisation` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_text` text COLLATE latin1_german1_ci,
  `lokalisation_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `resektabel` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_untersuchung` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_untersuchung_id` int(11) NOT NULL,
  `untersuchung_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `art` varchar(29) COLLATE latin1_german1_ci NOT NULL,
  `art_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `art_text` text COLLATE latin1_german1_ci,
  `art_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `koloskopie_vollstaendig` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ct_becken` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `kontrastmittel_iv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `kontrastmittel_po` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `kontrastmittel_rektal` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `pe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `datum` date NOT NULL,
  `anlass` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `arzt_id` int(11) DEFAULT NULL,
  `beurteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hno_untersuchung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lunge` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `be` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `birads` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ut` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `un` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `cn` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mesorektale_faszie` int(11) DEFAULT NULL,
  `lavage_menge` int(11) DEFAULT NULL,
  `bulky` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bulky_groesse` double DEFAULT NULL,
  `lk_a` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_b` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_c` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_d` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_e` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_f` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_g` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_h` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_i` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_k` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `lk_l` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `konsistenz` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `rsh_verschieblich` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `abgrenzbarkeit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `gesamtvolumen` float DEFAULT NULL,
  `kapselueberschreitung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `invasion` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `invasion_detail` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_untersuchung_lokalisation` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_untersuchung_lokalisation_id` int(11) NOT NULL,
  `untersuchung_lokalisation_id` int(11) NOT NULL DEFAULT '0',
  `untersuchung_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `lokalisation` varchar(30) COLLATE latin1_german1_ci NOT NULL,
  `lokalisation_text` text COLLATE latin1_german1_ci,
  `beurteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_seite` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `lokalisation_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `hoehe` double DEFAULT NULL,
  `groesse_x` double DEFAULT NULL,
  `groesse_y` double DEFAULT NULL,
  `groesse_z` double DEFAULT NULL,
  `groesse_nicht_messbar` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `multipel` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `organuebergreifend` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `wachstumsform` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `naessen` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `krusten` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `blutung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zellzahl` int(11) DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_user` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_user_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `anrede` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `titel` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `adelstitel` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `nachname` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `vorname` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `fachabteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `teilnahme_dmp` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `teilnahme_netzwerk` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `kr_kennung` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `kr_kuerzel` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `vertragsarztnummer` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `lanr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bsnr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `efn` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `efn_nz` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `org` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `strasse` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `hausnr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `plz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `ort` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefon` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `handy` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefax` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `email` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `staat` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `loginname` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `candidate` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `pwd` varchar(40) COLLATE latin1_german1_ci NOT NULL,
  `pwd_change` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bank_kontoinhaber` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bank_blz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bank_kontonummer` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bank_verwendungszweck` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `reset_cookie` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_user_lock` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_user_lock_id` int(11) NOT NULL,
  `user_lock_id` int(11) NOT NULL DEFAULT '0',
  `loginname` varchar(20) COLLATE latin1_german1_ci DEFAULT NULL,
  `last_login_acc` datetime DEFAULT NULL,
  `last_login_fail` datetime DEFAULT NULL,
  `login_ip` varchar(30) COLLATE latin1_german1_ci DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_user_reg` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_user_id` int(11) NOT NULL,
  `user_reg_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `org_name` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_namenszusatz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_ort` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_strasse` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_hausnr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_plz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_staat` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_bundesland` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_telefon` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_telefax` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_email` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_website` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `registered` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_vorlage_dokument` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_vorlage_dokument_id` int(11) NOT NULL,
  `vorlage_dokument_id` int(11) NOT NULL DEFAULT '0',
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `typ` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `package` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `doc_konferenz_immer` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `ausgabeformat` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_vorlage_fallkennzeichen` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_vorlage_fallkennzeichen_id` int(11) NOT NULL,
  `vorlage_fallkennzeichen_id` int(11) DEFAULT NULL,
  `code` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `pos` int(11) NOT NULL,
  `createuser` mediumint(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_vorlage_fragebogen` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_vorlage_fragebogen_id` int(11) NOT NULL,
  `vorlage_fragebogen_id` int(11) NOT NULL DEFAULT '0',
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `art` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_vorlage_fragebogen_frage` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_vorlage_fragebogen_frage_id` int(11) NOT NULL,
  `vorlage_fragebogen_frage_id` int(11) NOT NULL DEFAULT '0',
  `vorlage_fragebogen_id` int(11) NOT NULL,
  `frage` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `val_min` int(11) NOT NULL,
  `val_max` int(11) NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_vorlage_icd10` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_vorlage_icd10_id` int(11) NOT NULL,
  `vorlage_icd10_id` int(11) NOT NULL DEFAULT '0',
  `code` varchar(28) COLLATE latin1_german1_ci NOT NULL,
  `code_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `createuser` mediumint(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_vorlage_icdo` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_vorlage_icdo_id` int(11) NOT NULL,
  `vorlage_icdo_id` int(11) NOT NULL DEFAULT '0',
  `code` varchar(30) COLLATE latin1_german1_ci NOT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_vorlage_konferenztitel` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_vorlage_konferenztitel_id` int(11) NOT NULL,
  `vorlage_konferenztitel_id` int(11) DEFAULT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `createuser` mediumint(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_vorlage_krankenversicherung` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_vorlage_krankenversicherung_id` int(11) NOT NULL,
  `vorlage_krankenversicherung_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `iknr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `vknr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `strasse` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `plz` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `ort` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `land` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bundesland` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefon` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefax` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `email` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `gkv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_vorlage_labor` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_vorlage_labor_id` int(11) NOT NULL,
  `vorlage_labor_id` int(11) NOT NULL DEFAULT '0',
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `gueltig_von` date DEFAULT NULL,
  `gueltig_bis` date DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_vorlage_labor_wert` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_vorlage_labor_wert_id` int(11) NOT NULL,
  `vorlage_labor_wert_id` int(11) NOT NULL DEFAULT '0',
  `vorlage_labor_id` int(11) NOT NULL,
  `parameter` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `einheit` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `normal_m_min` double DEFAULT NULL,
  `normal_m_max` double DEFAULT NULL,
  `normal_w_min` double DEFAULT NULL,
  `normal_w_max` double DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_vorlage_ops` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_vorlage_ops_id` int(11) NOT NULL,
  `vorlage_ops_id` int(11) NOT NULL DEFAULT '0',
  `code` varchar(29) COLLATE latin1_german1_ci NOT NULL,
  `code_version` varchar(50) COLLATE latin1_german1_ci DEFAULT NULL,
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_vorlage_query` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_vorlage_query_id` int(11) NOT NULL,
  `vorlage_query_id` int(11) NOT NULL DEFAULT '0',
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `erkrankung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `sqlstring` text COLLATE latin1_german1_ci,
  `package` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `typ` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `ident` varchar(10) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_vorlage_query_org` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_vorlage_query_org_id` int(11) NOT NULL,
  `vorlage_query_org_id` int(11) NOT NULL DEFAULT '0',
  `vorlage_query_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_vorlage_studie` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_vorlage_studie_id` int(11) NOT NULL,
  `vorlage_studie_id` int(11) NOT NULL DEFAULT '0',
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `art` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `studientyp` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `erkrankung` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `indikation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ethikvotum` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `beginn` date DEFAULT NULL,
  `ende` date DEFAULT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `leiter` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefon` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `telefax` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `email` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `krz_protokoll` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `krz_protokoll_version` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `protokoll` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `protokoll_version` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_vorlage_therapie` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_vorlage_therapie_id` int(11) NOT NULL,
  `vorlage_therapie_id` int(11) NOT NULL DEFAULT '0',
  `bez` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `art` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `erkrankung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `datei` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `freigabe` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `inaktiv` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_vorlage_therapie_wirkstoff` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_vorlage_therapie_wirkstoff_id` int(11) NOT NULL,
  `vorlage_therapie_wirkstoff_id` int(11) NOT NULL DEFAULT '0',
  `vorlage_therapie_id` int(11) NOT NULL,
  `art` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `wirkstoff` varchar(25) COLLATE latin1_german1_ci NOT NULL,
  `radionukleid` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `dosis` double DEFAULT NULL,
  `einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `applikation` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zyklus_beginn` int(11) DEFAULT NULL,
  `zyklus_anzahl` int(11) DEFAULT NULL,
  `zyklustag` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `zyklustag02` int(11) DEFAULT NULL,
  `zyklustag03` int(11) DEFAULT NULL,
  `zyklustag04` int(11) DEFAULT NULL,
  `zyklustag05` int(11) DEFAULT NULL,
  `zyklustag06` int(11) DEFAULT NULL,
  `zyklustag07` int(11) DEFAULT NULL,
  `zyklustag08` int(11) DEFAULT NULL,
  `zyklustag09` int(11) DEFAULT NULL,
  `zyklustag10` int(11) DEFAULT NULL,
  `zyklusdauer` int(11) DEFAULT NULL,
  `loesungsmittel` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `loesungsmittel_menge` int(11) DEFAULT NULL,
  `infusionsdauer` int(11) DEFAULT NULL,
  `infusionsdauer_einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `applikationsfrequenz` int(11) DEFAULT NULL,
  `applikationsfrequenz_einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `therapiedauer` int(11) DEFAULT NULL,
  `therapiedauer_einheit` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_zweitmeinung` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_zweitmeinung_id` int(11) NOT NULL,
  `zweitmeinung_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `vorlage_dokument_id` int(11) NOT NULL,
  `fotos` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `datenstand_datum` datetime DEFAULT NULL,
  `document_process` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `document_final` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `document_dirty` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_zytologie` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_zytologie_id` int(11) NOT NULL,
  `zytologie_id` int(11) NOT NULL DEFAULT '0',
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `histologie_nr` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `eingriff_id` int(11) DEFAULT NULL,
  `untersuchungsmaterial` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nhl_who_b` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nhl_who_t` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `hl_who` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ann_arbor_stadium` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ann_arbor_aktivitaetsgrad` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `ann_arbor_extralymphatisch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `nhl_ipi` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `flipi` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `durie_salmon` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `cll_rai` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `cll_binet` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aml_fab` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `aml_who` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `all_egil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mds_fab` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mds_who` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zytologie_normal` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zelldichte` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `erythropoese` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `granulopoese` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `megakaryopoese` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `km_infiltration` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `km_infiltration_anteil` double DEFAULT NULL,
  `zyto_sonstiges_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `zyto_sonstiges` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `zellveraenderung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `erythrozyten` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `erythrozyten_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `granulozyten` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `granulozyten_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `megakaryozyten` char(1) COLLATE latin1_german1_ci DEFAULT NULL,
  `megakaryozyten_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `lymphozyten_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `plasmazellen_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `zellen_sonstiges` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `zellen_sonstiges_text` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `liquordiag_1_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `liquordiag_1_zellzahl` int(11) DEFAULT NULL,
  `liquordiag_1_beurteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `liquordiag_2_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `liquordiag_2_zellzahl` int(11) DEFAULT NULL,
  `liquordiag_2_beurteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `liquordiag_3_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `liquordiag_3_zellzahl` int(11) DEFAULT NULL,
  `liquordiag_3_beurteilung` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `myeloperoxidase_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `myeloperoxidase_anteil` int(11) DEFAULT NULL,
  `monozytenesterase_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `monozytenesterase_anteil` int(11) DEFAULT NULL,
  `pas_reaktion_urteil` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `pas_reaktion_anteil` int(11) DEFAULT NULL,
  `immunzytologie_pathologisch` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `immunzytologie_diagnose` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `zytogenetik_normal` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mrd1_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mrd1_ergebnis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mrd2_methode` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `mrd2_ergebnis` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `bem` text COLLATE latin1_german1_ci,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `_zytologie_aberration` (
  `a_action` varchar(13) COLLATE latin1_german1_ci DEFAULT NULL,
  `a_zytologie_aberration_id` int(11) NOT NULL,
  `zytologie_aberration_id` int(11) NOT NULL DEFAULT '0',
  `zytologie_id` int(11) NOT NULL,
  `erkrankung_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `aberration` varchar(25) COLLATE latin1_german1_ci DEFAULT NULL,
  `karyotyp` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `createuser` int(11) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `updateuser` int(11) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

ALTER TABLE `abschluss`
  ADD PRIMARY KEY (`abschluss_id`),
  ADD UNIQUE KEY `patient_id` (`patient_id`),
  ADD KEY `patient_id_2` (`patient_id`,`todesdatum`);

ALTER TABLE `abschluss_ursache`
  ADD PRIMARY KEY (`abschluss_ursache_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `idx1` (`abschluss_id`);

ALTER TABLE `anamnese`
  ADD PRIMARY KEY (`anamnese_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`datum`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`);

ALTER TABLE `anamnese_erkrankung`
  ADD PRIMARY KEY (`anamnese_erkrankung_id`),
  ADD UNIQUE KEY `UKEY` (`anamnese_id`,`erkrankung`,`jahr`),
  ADD KEY `anamnese_id` (`anamnese_id`),
  ADD KEY `anamnese_id_2` (`anamnese_id`,`erkrankung`);

ALTER TABLE `anamnese_familie`
  ADD PRIMARY KEY (`anamnese_familie_id`),
  ADD KEY `anamnese_id` (`anamnese_id`);

ALTER TABLE `aufenthalt`
  ADD PRIMARY KEY (`aufenthalt_id`),
  ADD UNIQUE KEY `UKEY1` (`patient_id`,`aufnahmenr`),
  ADD KEY `patient_id` (`patient_id`);

ALTER TABLE `begleitmedikation`
  ADD PRIMARY KEY (`begleitmedikation_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`wirkstoff`,`beginn`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`);

ALTER TABLE `behandler`
  ADD PRIMARY KEY (`behandler_id`),
  ADD UNIQUE KEY `UKEY` (`patient_id`,`user_id`,`funktion`),
  ADD KEY `patient_id` (`patient_id`);

ALTER TABLE `beratung`
  ADD PRIMARY KEY (`beratung_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`datum`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`),
  ADD KEY `report` (`beratung_id`,`psychoonkologie`,`sozialdienst`,`ernaehrungsberatung`,`psychoonkologie_dauer`);

ALTER TABLE `brief`
  ADD PRIMARY KEY (`brief_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`datum`,`vorlage_dokument_id`,`hauptempfaenger_id`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`);

ALTER TABLE `brief_empfaenger`
  ADD PRIMARY KEY (`brief_empfaenger_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`patient_id`,`brief_id`,`empfaenger_id`);

ALTER TABLE `diagnose`
  ADD PRIMARY KEY (`diagnose_id`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`);

ALTER TABLE `dmp_brustkrebs_eb`
  ADD PRIMARY KEY (`dmp_brustkrebs_eb_id`),
  ADD UNIQUE KEY `UKEY` (`patient_id`,`doku_datum`,`einschreibung_grund`),
  ADD KEY `patient_id` (`patient_id`,`erkrankung_id`);

ALTER TABLE `dmp_brustkrebs_ed_2013`
  ADD PRIMARY KEY (`dmp_brustkrebs_ed_2013_id`),
  ADD UNIQUE KEY `UKEY1` (`org_id`,`fall_nr`),
  ADD UNIQUE KEY `UKEY2` (`erkrankung_id`),
  ADD KEY `patient_id` (`patient_id`,`erkrankung_id`);

ALTER TABLE `dmp_brustkrebs_ed_pnp_2013`
  ADD PRIMARY KEY (`dmp_brustkrebs_ed_pnp_2013_id`),
  ADD UNIQUE KEY `UKEY1` (`dmp_brustkrebs_ed_2013_id`),
  ADD KEY `patient_id` (`patient_id`,`erkrankung_id`);

ALTER TABLE `dmp_brustkrebs_fb`
  ADD PRIMARY KEY (`dmp_brustkrebs_fb_id`),
  ADD UNIQUE KEY `UKEY` (`dmp_brustkrebs_eb_id`,`doku_datum`),
  ADD KEY `dmp_brustkrebs_eb_id` (`dmp_brustkrebs_eb_id`,`patient_id`,`erkrankung_id`);

ALTER TABLE `dmp_brustkrebs_fd_2013`
  ADD PRIMARY KEY (`dmp_brustkrebs_fd_2013_id`),
  ADD UNIQUE KEY `UKEY` (`dmp_brustkrebs_ed_2013_id`,`doku_datum`),
  ADD KEY `dmp_brustkrebs_ed_2013_id` (`dmp_brustkrebs_ed_2013_id`,`patient_id`,`erkrankung_id`);

ALTER TABLE `dmp_nummern`
  ADD PRIMARY KEY (`org_id`,`nr`);

ALTER TABLE `dmp_nummern_2013`
  ADD PRIMARY KEY (`dmp_nummern_2013_id`);

ALTER TABLE `dokument`
  ADD PRIMARY KEY (`dokument_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`datum`,`bez`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`);

ALTER TABLE `eingriff`
  ADD PRIMARY KEY (`eingriff_id`),
  ADD UNIQUE KEY `UKEY1` (`erkrankung_id`,`datum`,`diagnose_seite`,`art_diagnostik`),
  ADD UNIQUE KEY `UKEY2` (`erkrankung_id`,`datum`,`diagnose_seite`,`art_primaertumor`),
  ADD UNIQUE KEY `UKEY3` (`erkrankung_id`,`datum`,`diagnose_seite`,`art_lk`),
  ADD UNIQUE KEY `UKEY4` (`erkrankung_id`,`datum`,`diagnose_seite`,`art_metastasen`),
  ADD UNIQUE KEY `UKEY5` (`erkrankung_id`,`datum`,`diagnose_seite`,`art_rezidiv`),
  ADD UNIQUE KEY `UKEY6` (`erkrankung_id`,`datum`,`diagnose_seite`,`art_nachresektion`),
  ADD UNIQUE KEY `UKEY7` (`erkrankung_id`,`datum`,`diagnose_seite`,`art_transplantation_autolog`),
  ADD UNIQUE KEY `UKEY8` (`erkrankung_id`,`datum`,`diagnose_seite`,`art_transplantation_allogen_v`),
  ADD UNIQUE KEY `UKEY9` (`erkrankung_id`,`datum`,`diagnose_seite`,`art_transplantation_allogen_nv`),
  ADD UNIQUE KEY `UKEY10` (`erkrankung_id`,`datum`,`diagnose_seite`,`art_transplantation_syngen`),
  ADD UNIQUE KEY `UKEY11` (`erkrankung_id`,`datum`,`diagnose_seite`,`art_revision`),
  ADD UNIQUE KEY `UKEY12` (`erkrankung_id`,`datum`,`diagnose_seite`,`art_rekonstruktion`),
  ADD UNIQUE KEY `UKEY13` (`erkrankung_id`,`datum`,`diagnose_seite`,`art_sonstige`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`),
  ADD KEY `erkrankung_id_2` (`erkrankung_id`,`datum`),
  ADD KEY `erkrankung_id_4` (`erkrankung_id`,`datum`,`diagnose_seite`,`art_primaertumor`,`art_lk`,`art_metastasen`,`art_rezidiv`,`art_nachresektion`,`art_revision`),
  ADD KEY `erkrankung_id_5` (`erkrankung_id`,`diagnose_seite`),
  ADD KEY `idx1` (`eingriff_id`,`diagnose_seite`);

ALTER TABLE `eingriff_ops`
  ADD PRIMARY KEY (`eingriff_ops_id`),
  ADD KEY `idx1` (`eingriff_id`,`prozedur_seite`),
  ADD KEY `idx2` (`erkrankung_id`,`prozedur`) USING BTREE;

ALTER TABLE `ekr`
  ADD PRIMARY KEY (`ekr_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`datum`),
  ADD KEY `erkrankung_id` (`patient_id`,`erkrankung_id`);

ALTER TABLE `email`
  ADD PRIMARY KEY (`email_id`);

ALTER TABLE `erkrankung`
  ADD PRIMARY KEY (`erkrankung_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `erkrankung` (`patient_id`,`erkrankung`),
  ADD KEY `idx1` (`erkrankung`);

ALTER TABLE `erkrankung_synchron`
  ADD PRIMARY KEY (`erkrankung_synchron_id`),
  ADD UNIQUE KEY `UKEY1` (`erkrankung_id`,`erkrankung_synchron`) USING BTREE,
  ADD KEY `patient_id` (`patient_id`);

ALTER TABLE `export_case_log`
  ADD PRIMARY KEY (`export_case_log_id`),
  ADD KEY `idx1` (`export_log_id`,`patient_id`,`erkrankung_id`),
  ADD KEY `idx2` (`erkrankung_id`,`diagnose_seite`,`anlass`) USING BTREE;

ALTER TABLE `export_history`
  ADD PRIMARY KEY (`export_history_id`),
  ADD UNIQUE KEY `ukey1` (`export_log_id`) USING BTREE;

ALTER TABLE `export_log`
  ADD PRIMARY KEY (`export_log_id`),
  ADD KEY `idx1` (`export_name`,`org_id`,`export_unique_id`,`export_nr`,`finished`),
  ADD KEY `idx2` (`export_name`,`org_id`,`export_unique_id`,`finished`);

ALTER TABLE `export_patient_ids_log`
  ADD PRIMARY KEY (`export_patient_ids_log_id`);

ALTER TABLE `export_section_log`
  ADD PRIMARY KEY (`export_section_log_id`),
  ADD KEY `export_log_id` (`export_case_log_id`,`valid`) USING BTREE;

ALTER TABLE `exp_ekrrp_log`
  ADD PRIMARY KEY (`exp_ekrrp_log_id`);

ALTER TABLE `exp_gekid_log`
  ADD PRIMARY KEY (`exp_gekid_log_id`);

ALTER TABLE `exp_gkr_log`
  ADD PRIMARY KEY (`exp_gkr_log_id`);

ALTER TABLE `exp_krbw_ng_log`
  ADD PRIMARY KEY (`exp_krbw_ng_log_id`);

ALTER TABLE `exp_krhe_log`
  ADD PRIMARY KEY (`exp_krhe_log_id`);

ALTER TABLE `foto`
  ADD PRIMARY KEY (`foto_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`datum`,`bez`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`);

ALTER TABLE `fragebogen`
  ADD PRIMARY KEY (`fragebogen_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`vorlage_fragebogen_id`,`datum`);

ALTER TABLE `fragebogen_frage`
  ADD PRIMARY KEY (`fragebogen_frage_id`),
  ADD UNIQUE KEY `UKEY` (`fragebogen_id`,`vorlage_fragebogen_id`,`vorlage_fragebogen_frage_id`),
  ADD KEY `fragebogen_frage_id` (`fragebogen_id`);

ALTER TABLE `histologie`
  ADD PRIMARY KEY (`histologie_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`datum`,`diagnose_seite`,`art`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`),
  ADD KEY `biopsen` (`histologie_id`,`art`,`eingriff_id`,`untersuchung_id`);

ALTER TABLE `histologie_einzel`
  ADD PRIMARY KEY (`histologie_einzel_id`),
  ADD UNIQUE KEY `UKEY` (`histologie_id`,`diagnose_id`),
  ADD KEY `histologie_id` (`histologie_id`);

ALTER TABLE `history`
  ADD PRIMARY KEY (`history_id`);

ALTER TABLE `hl7_cache`
  ADD PRIMARY KEY (`hl7_cache_id`),
  ADD KEY `org_id` (`org_id`);

ALTER TABLE `hl7_diagnose`
  ADD PRIMARY KEY (`hl7_diagnose_id`),
  ADD KEY `patient_id` (`patient_id`,`diagnose`,`org_id`);

ALTER TABLE `hl7_log_cache`
  ADD PRIMARY KEY (`hl7_log_id`),
  ADD KEY `org_id` (`org_id`);

ALTER TABLE `hl7_message`
  ADD PRIMARY KEY (`hl7_message_id`),
  ADD KEY `hl7_cache_id` (`hl7_cache_id`);

ALTER TABLE `komplikation`
  ADD PRIMARY KEY (`komplikation_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`datum`,`komplikation`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`),
  ADD KEY `eingriffkomplikation` (`erkrankung_id`,`eingriff_id`);

ALTER TABLE `konferenz`
  ADD PRIMARY KEY (`konferenz_id`),
  ADD UNIQUE KEY `UKEY` (`datum`,`uhrzeit_beginn`),
  ADD KEY `konferenz_id` (`konferenz_id`,`datum`);

ALTER TABLE `konferenz_abschluss`
  ADD PRIMARY KEY (`konferenz_abschluss_id`),
  ADD UNIQUE KEY `UKEY1` (`konferenz_teilnehmer_id`);

ALTER TABLE `konferenz_dokument`
  ADD PRIMARY KEY (`konferenz_dokument_id`),
  ADD UNIQUE KEY `UKEY` (`konferenz_id`,`bez`),
  ADD KEY `konferenz_id` (`konferenz_id`);

ALTER TABLE `konferenz_patient`
  ADD PRIMARY KEY (`konferenz_patient_id`),
  ADD KEY `konferenz_id` (`konferenz_id`),
  ADD KEY `erkrankung_id_2` (`erkrankung_id`,`art`,`konferenz_id`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`document_dirty`);

ALTER TABLE `konferenz_teilnehmer`
  ADD PRIMARY KEY (`konferenz_teilnehmer_id`),
  ADD UNIQUE KEY `UKEY` (`konferenz_id`,`user_id`),
  ADD KEY `konferenz_id` (`konferenz_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `konferenz_teilnehmer_profil`
  ADD PRIMARY KEY (`konferenz_teilnehmer_profil_id`),
  ADD UNIQUE KEY `ukey` (`bez`);

ALTER TABLE `labor`
  ADD PRIMARY KEY (`labor_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`datum`,`vorlage_labor_id`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`);

ALTER TABLE `labor_wert`
  ADD PRIMARY KEY (`labor_wert_id`),
  ADD UNIQUE KEY `UKEY` (`vorlage_labor_wert_id`,`labor_id`),
  ADD KEY `labor_id` (`labor_id`,`parameter`,`wert`);

ALTER TABLE `l_basic`
  ADD PRIMARY KEY (`klasse`,`code`) USING BTREE;

ALTER TABLE `l_exp_diagnose_to_lokalisation`
  ADD PRIMARY KEY (`diagnose_code`);

ALTER TABLE `l_exp_gkr_addresses`
  ADD PRIMARY KEY (`gkr_address_id`);

ALTER TABLE `l_exp_qsmed`
  ADD PRIMARY KEY (`klasse`,`code_med`,`code_qsmed`);

ALTER TABLE `l_icd10`
  ADD PRIMARY KEY (`code`);

ALTER TABLE `l_icdo3`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`);
ALTER TABLE `l_icdo3` ADD FULLTEXT KEY `description` (`description`);

ALTER TABLE `l_imp_hl7`
  ADD PRIMARY KEY (`klasse`,`code`);

ALTER TABLE `l_ktst`
  ADD PRIMARY KEY (`iknr`) USING BTREE;

ALTER TABLE `l_ktst_abr`
  ADD KEY `index` (`iknr`) USING BTREE;

ALTER TABLE `l_matrix`
  ADD PRIMARY KEY (`tabelle`);

ALTER TABLE `l_nci`
  ADD PRIMARY KEY (`grp`,`code`),
  ADD KEY `code` (`code`);

ALTER TABLE `l_ops`
  ADD PRIMARY KEY (`code`);

ALTER TABLE `l_qs`
  ADD PRIMARY KEY (`klasse`,`code`,`jahr`);

ALTER TABLE `l_qs_valid`
  ADD PRIMARY KEY (`jahr`,`nr`);

ALTER TABLE `l_uicc`
  ADD UNIQUE KEY `tnm` (`t`,`n`,`m`),
  ADD KEY `t_2` (`t`),
  ADD KEY `n_2` (`n`),
  ADD KEY `m_2` (`m`);

ALTER TABLE `nachsorge`
  ADD PRIMARY KEY (`nachsorge_id`),
  ADD UNIQUE KEY `UKEY` (`patient_id`,`datum`),
  ADD KEY `patient_id` (`patient_id`);

ALTER TABLE `nachsorge_erkrankung`
  ADD PRIMARY KEY (`nachsorge_erkrankung_id`),
  ADD UNIQUE KEY `UKEY` (`nachsorge_id`,`erkrankung_weitere_id`),
  ADD KEY `erkrankung_weitere_id` (`erkrankung_weitere_id`),
  ADD KEY `nachsorge_id` (`nachsorge_id`);

ALTER TABLE `nebenwirkung`
  ADD PRIMARY KEY (`nebenwirkung_id`),
  ADD UNIQUE KEY `UKEY1` (`erkrankung_id`,`nci_code`,`beginn`),
  ADD UNIQUE KEY `UKEY2` (`erkrankung_id`,`nci_code`,`beginn_unbekannt`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`);

ALTER TABLE `org`
  ADD PRIMARY KEY (`org_id`),
  ADD UNIQUE KEY `UKEY` (`name`,`ort`);

ALTER TABLE `patho_item`
  ADD PRIMARY KEY (`patho_item_id`);

ALTER TABLE `patient`
  ADD PRIMARY KEY (`patient_id`),
  ADD UNIQUE KEY `UKEY2` (`patient_nr`,`org_id`);

ALTER TABLE `qs_18_1_b`
  ADD PRIMARY KEY (`qs_18_1_b_id`),
  ADD UNIQUE KEY `UKEY1` (`erkrankung_id`,`aufenthalt_id`);

ALTER TABLE `qs_18_1_brust`
  ADD PRIMARY KEY (`qs_18_1_brust_id`),
  ADD UNIQUE KEY `UKEY` (`qs_18_1_b_id`,`zuopseite`),
  ADD KEY `qs_18_1_b_id` (`qs_18_1_b_id`,`patient_id`,`erkrankung_id`),
  ADD KEY `b_id` (`qs_18_1_b_id`);

ALTER TABLE `qs_18_1_o`
  ADD PRIMARY KEY (`qs_18_1_o_id`),
  ADD UNIQUE KEY `UKEY` (`qs_18_1_brust_id`,`lfdnreingriff`),
  ADD KEY `qs_18_1_brust_id` (`qs_18_1_brust_id`,`qs_18_1_b_id`,`patient_id`,`erkrankung_id`),
  ADD KEY `brust_id` (`qs_18_1_brust_id`);

ALTER TABLE `recht`
  ADD PRIMARY KEY (`recht_id`),
  ADD UNIQUE KEY `UKEY` (`org_id`,`user_id`,`rolle`);

ALTER TABLE `recht_erkrankung`
  ADD PRIMARY KEY (`recht_erkrankung_id`),
  ADD UNIQUE KEY `UKEY` (`recht_id`,`erkrankung`);

ALTER TABLE `report_time`
  ADD PRIMARY KEY (`report_time_id`);

ALTER TABLE `settings`
  ADD PRIMARY KEY (`settings_id`);

ALTER TABLE `settings_export`
  ADD PRIMARY KEY (`settings_export_id`);

ALTER TABLE `settings_forms`
  ADD PRIMARY KEY (`settings_forms_id`),
  ADD UNIQUE KEY `UKEY1` (`org_id`);

ALTER TABLE `settings_hl7`
  ADD PRIMARY KEY (`settings_hl7_id`);

ALTER TABLE `settings_hl7field`
  ADD PRIMARY KEY (`settings_hl7field_id`),
  ADD UNIQUE KEY `ukey` (`med_feld`);

ALTER TABLE `settings_import`
  ADD PRIMARY KEY (`settings_import_id`);

ALTER TABLE `settings_pacs`
  ADD PRIMARY KEY (`settings_pacs_id`);

ALTER TABLE `settings_report`
  ADD PRIMARY KEY (`settings_report_id`);

ALTER TABLE `sonstige_therapie`
  ADD PRIMARY KEY (`sonstige_therapie_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`beginn`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`),
  ADD KEY `erkrankung_id_2` (`erkrankung_id`,`beginn`,`intention`);

ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`form_id`),
  ADD KEY `patient_status` (`patient_id`,`erkrankung_id`,`form`,`form_id`,`form_date`),
  ADD KEY `form` (`form`),
  ADD KEY `patient_status_2` (`form_id`,`form`(100),`form_param`(100),`report_param`(100)) USING BTREE;

ALTER TABLE `status_lock`
  ADD PRIMARY KEY (`status_lock_id`);

ALTER TABLE `status_lock_bem`
  ADD PRIMARY KEY (`status_lock_bem_id`);

ALTER TABLE `status_log`
  ADD PRIMARY KEY (`form`);

ALTER TABLE `strahlentherapie`
  ADD PRIMARY KEY (`strahlentherapie_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`beginn`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`),
  ADD KEY `erkrankung_id_2` (`erkrankung_id`,`beginn`,`intention`);

ALTER TABLE `studie`
  ADD PRIMARY KEY (`studie_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`vorlage_studie_id`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`),
  ADD KEY `erkrankung_id_2` (`erkrankung_id`,`beginn`);

ALTER TABLE `termin`
  ADD PRIMARY KEY (`termin_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`datum`,`art`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`);

ALTER TABLE `therapieplan`
  ADD PRIMARY KEY (`therapieplan_id`),
  ADD UNIQUE KEY `UKEY1` (`erkrankung_id`,`datum`),
  ADD UNIQUE KEY `UKEY2` (`konferenz_patient_id`);

ALTER TABLE `therapieplan_abweichung`
  ADD PRIMARY KEY (`therapieplan_abweichung_id`),
  ADD UNIQUE KEY `UKEY` (`therapieplan_id`,`grund`);

ALTER TABLE `therapie_systemisch`
  ADD PRIMARY KEY (`therapie_systemisch_id`,`erkrankung_id`,`patient_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`vorlage_therapie_id`,`beginn`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`),
  ADD KEY `erkrankung_id_2` (`erkrankung_id`,`beginn`,`intention`,`vorlage_therapie_art`);

ALTER TABLE `therapie_systemisch_zyklus`
  ADD PRIMARY KEY (`therapie_systemisch_zyklus_id`),
  ADD UNIQUE KEY `UKEY` (`therapie_systemisch_id`,`zyklus_nr`),
  ADD KEY `therapie_systemisch_id` (`therapie_systemisch_id`);

ALTER TABLE `therapie_systemisch_zyklustag`
  ADD PRIMARY KEY (`therapie_systemisch_zyklustag_id`),
  ADD UNIQUE KEY `UKEY` (`therapie_systemisch_zyklus_id`,`zyklustag`),
  ADD KEY `therapie_systemisch_zyklus_id` (`therapie_systemisch_zyklus_id`,`therapie_systemisch_id`);

ALTER TABLE `therapie_systemisch_zyklustag_wirkstoff`
  ADD PRIMARY KEY (`therapie_systemisch_zyklustag_wirkstoff_id`),
  ADD UNIQUE KEY `UKEY` (`therapie_systemisch_zyklus_id`,`therapie_systemisch_zyklustag_id`,`vorlage_therapie_wirkstoff_id`),
  ADD KEY `therapie_systemisch_zyklustag_id` (`therapie_systemisch_zyklustag_id`);

ALTER TABLE `tumorstatus`
  ADD PRIMARY KEY (`tumorstatus_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`datum_beurteilung`,`diagnose_seite`),
  ADD KEY `erkrankung_id_2` (`erkrankung_id`,`anlass`,`datum_sicherung`,`diagnose_seite`,`nur_zweitmeinung`,`nur_diagnosesicherung`,`kein_fall`,`tnm_praefix`,`patient_id`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`);

ALTER TABLE `tumorstatus_metastasen`
  ADD PRIMARY KEY (`tumorstatus_metastasen_id`),
  ADD UNIQUE KEY `UKEY` (`tumorstatus_id`,`lokalisation`,`lokalisation_seite`),
  ADD KEY `tumorstatus_id` (`tumorstatus_id`),
  ADD KEY `idx1` (`erkrankung_id`,`lokalisation_seite`);

ALTER TABLE `untersuchung`
  ADD PRIMARY KEY (`untersuchung_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`art`,`datum`,`art_seite`),
  ADD KEY `erkrankung_id` (`erkrankung_id`);

ALTER TABLE `untersuchung_lokalisation`
  ADD PRIMARY KEY (`untersuchung_lokalisation_id`),
  ADD KEY `untersuchung_id` (`untersuchung_id`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `ukey` (`loginname`),
  ADD KEY `fachabteilung` (`user_id`,`fachabteilung`);

ALTER TABLE `user_lock`
  ADD PRIMARY KEY (`user_lock_id`),
  ADD UNIQUE KEY `login_name` (`loginname`);

ALTER TABLE `user_log`
  ADD PRIMARY KEY (`user_log_id`);

ALTER TABLE `user_reg`
  ADD PRIMARY KEY (`user_reg_id`),
  ADD UNIQUE KEY `UKEY` (`user_id`);

ALTER TABLE `vorlage_dokument`
  ADD PRIMARY KEY (`vorlage_dokument_id`),
  ADD UNIQUE KEY `UKEY` (`bez`);

ALTER TABLE `vorlage_fallkennzeichen`
  ADD PRIMARY KEY (`vorlage_fallkennzeichen_id`),
  ADD UNIQUE KEY `UKEY1` (`code`),
  ADD UNIQUE KEY `UKEY2` (`bez`),
  ADD UNIQUE KEY `UKEY3` (`pos`);

ALTER TABLE `vorlage_fragebogen`
  ADD PRIMARY KEY (`vorlage_fragebogen_id`),
  ADD UNIQUE KEY `UKEY` (`bez`),
  ADD KEY `vorlage_fragebogen_id` (`vorlage_fragebogen_id`,`art`);

ALTER TABLE `vorlage_fragebogen_frage`
  ADD PRIMARY KEY (`vorlage_fragebogen_frage_id`),
  ADD UNIQUE KEY `UKEY` (`vorlage_fragebogen_id`,`frage`),
  ADD KEY `vorlage_fragebogen_id` (`vorlage_fragebogen_id`);

ALTER TABLE `vorlage_icd10`
  ADD PRIMARY KEY (`vorlage_icd10_id`),
  ADD UNIQUE KEY `UKEY` (`code`);

ALTER TABLE `vorlage_icdo`
  ADD PRIMARY KEY (`vorlage_icdo_id`),
  ADD UNIQUE KEY `UKEY` (`code`);

ALTER TABLE `vorlage_konferenztitel`
  ADD PRIMARY KEY (`vorlage_konferenztitel_id`),
  ADD UNIQUE KEY `UKEY` (`bez`);

ALTER TABLE `vorlage_krankenversicherung`
  ADD PRIMARY KEY (`vorlage_krankenversicherung_id`),
  ADD UNIQUE KEY `UKEY2` (`iknr`);

ALTER TABLE `vorlage_labor`
  ADD PRIMARY KEY (`vorlage_labor_id`),
  ADD UNIQUE KEY `UKEY` (`bez`);

ALTER TABLE `vorlage_labor_wert`
  ADD PRIMARY KEY (`vorlage_labor_wert_id`),
  ADD UNIQUE KEY `UKEY` (`vorlage_labor_id`,`parameter`),
  ADD KEY `vorlage_labor_id` (`vorlage_labor_id`);

ALTER TABLE `vorlage_ops`
  ADD PRIMARY KEY (`vorlage_ops_id`),
  ADD UNIQUE KEY `UKEY` (`code`);

ALTER TABLE `vorlage_query`
  ADD PRIMARY KEY (`vorlage_query_id`),
  ADD UNIQUE KEY `UKEY` (`bez`);

ALTER TABLE `vorlage_query_org`
  ADD PRIMARY KEY (`vorlage_query_org_id`),
  ADD UNIQUE KEY `UKEY` (`vorlage_query_id`,`org_id`);

ALTER TABLE `vorlage_studie`
  ADD PRIMARY KEY (`vorlage_studie_id`),
  ADD UNIQUE KEY `UKEY` (`bez`),
  ADD KEY `vorlage_studie_id` (`vorlage_studie_id`,`ethikvotum`);

ALTER TABLE `vorlage_therapie`
  ADD PRIMARY KEY (`vorlage_therapie_id`),
  ADD UNIQUE KEY `UKEY` (`bez`);

ALTER TABLE `vorlage_therapie_wirkstoff`
  ADD PRIMARY KEY (`vorlage_therapie_wirkstoff_id`),
  ADD KEY `vorlage_therapie_id` (`vorlage_therapie_id`),
  ADD KEY `vorlage_therapie_id_2` (`vorlage_therapie_id`,`art`,`wirkstoff`);

ALTER TABLE `zweitmeinung`
  ADD PRIMARY KEY (`zweitmeinung_id`),
  ADD KEY `erkrankung_id_2` (`erkrankung_id`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`document_dirty`);

ALTER TABLE `zytologie`
  ADD PRIMARY KEY (`zytologie_id`),
  ADD UNIQUE KEY `UKEY` (`erkrankung_id`,`datum`),
  ADD KEY `erkrankung_id` (`erkrankung_id`,`patient_id`);

ALTER TABLE `zytologie_aberration`
  ADD PRIMARY KEY (`zytologie_aberration_id`),
  ADD UNIQUE KEY `UKEY` (`zytologie_id`,`aberration`),
  ADD KEY `zytologie_id` (`zytologie_id`);

ALTER TABLE `_abschluss`
  ADD PRIMARY KEY (`a_abschluss_id`);

ALTER TABLE `_abschluss_ursache`
  ADD PRIMARY KEY (`a_abschluss_ursache_id`);

ALTER TABLE `_anamnese`
  ADD PRIMARY KEY (`a_anamnese_id`);

ALTER TABLE `_anamnese_erkrankung`
  ADD PRIMARY KEY (`a_anamnese_erkrankung_id`);

ALTER TABLE `_anamnese_familie`
  ADD PRIMARY KEY (`a_anamnese_familie_id`);

ALTER TABLE `_aufenthalt`
  ADD PRIMARY KEY (`a_aufenthalt_id`);

ALTER TABLE `_begleitmedikation`
  ADD PRIMARY KEY (`a_begleitmedikation_id`);

ALTER TABLE `_behandler`
  ADD PRIMARY KEY (`a_behandler_id`);

ALTER TABLE `_beratung`
  ADD PRIMARY KEY (`a_beratung_id`);

ALTER TABLE `_brief`
  ADD PRIMARY KEY (`a_brief_id`);

ALTER TABLE `_brief_empfaenger`
  ADD PRIMARY KEY (`a_brief_empfaenger_id`);

ALTER TABLE `_diagnose`
  ADD PRIMARY KEY (`a_diagnose_id`);

ALTER TABLE `_dmp_brustkrebs_eb`
  ADD PRIMARY KEY (`a_dmp_brustkrebs_eb_id`);

ALTER TABLE `_dmp_brustkrebs_ed_2013`
  ADD PRIMARY KEY (`a_dmp_brustkrebs_ed_2013_id`);

ALTER TABLE `_dmp_brustkrebs_ed_pnp_2013`
  ADD PRIMARY KEY (`a_dmp_brustkrebs_ed_pnp_2013_id`);

ALTER TABLE `_dmp_brustkrebs_fb`
  ADD PRIMARY KEY (`a_dmp_brustkrebs_fb_id`);

ALTER TABLE `_dmp_brustkrebs_fd_2013`
  ADD PRIMARY KEY (`a_dmp_brustkrebs_fd_2013_id`);

ALTER TABLE `_dmp_nummern_2013`
  ADD PRIMARY KEY (`a_dmp_nummern_2013_id`);

ALTER TABLE `_dokument`
  ADD PRIMARY KEY (`a_dokument_id`);

ALTER TABLE `_eingriff`
  ADD PRIMARY KEY (`a_eingriff_id`);

ALTER TABLE `_eingriff_ops`
  ADD PRIMARY KEY (`a_eingriff_ops_id`);

ALTER TABLE `_ekr`
  ADD PRIMARY KEY (`a_ekr_id`);

ALTER TABLE `_erkrankung`
  ADD PRIMARY KEY (`a_erkrankung_id`);

ALTER TABLE `_erkrankung_synchron`
  ADD PRIMARY KEY (`a_erkrankung_synchron_id`);

ALTER TABLE `_export_history`
  ADD PRIMARY KEY (`a_export_history_id`);

ALTER TABLE `_foto`
  ADD PRIMARY KEY (`a_foto_id`);

ALTER TABLE `_fragebogen`
  ADD PRIMARY KEY (`a_fragebogen_id`);

ALTER TABLE `_fragebogen_frage`
  ADD PRIMARY KEY (`a_fragebogen_frage_id`);

ALTER TABLE `_histologie`
  ADD PRIMARY KEY (`a_histologie_id`);

ALTER TABLE `_histologie_einzel`
  ADD PRIMARY KEY (`a_histologie_einzel_id`);

ALTER TABLE `_komplikation`
  ADD PRIMARY KEY (`a_komplikation_id`);

ALTER TABLE `_konferenz`
  ADD PRIMARY KEY (`a_konferenz_id`);

ALTER TABLE `_konferenz_abschluss`
  ADD PRIMARY KEY (`a_konferenz_abschluss_id`);

ALTER TABLE `_konferenz_dokument`
  ADD PRIMARY KEY (`a_konferenz_dokument_id`);

ALTER TABLE `_konferenz_patient`
  ADD PRIMARY KEY (`a_konferenz_patient_id`);

ALTER TABLE `_konferenz_teilnehmer`
  ADD PRIMARY KEY (`a_konferenz_teilnehmer_id`);

ALTER TABLE `_konferenz_teilnehmer_profil`
  ADD PRIMARY KEY (`a_konferenz_teilnehmer_profil_id`);

ALTER TABLE `_labor`
  ADD PRIMARY KEY (`a_labor_id`);

ALTER TABLE `_labor_wert`
  ADD PRIMARY KEY (`a_labor_wert_id`);

ALTER TABLE `_nachsorge`
  ADD PRIMARY KEY (`a_nachsorge_id`);

ALTER TABLE `_nachsorge_erkrankung`
  ADD PRIMARY KEY (`a_nachsorge_erkrankung_id`);

ALTER TABLE `_nebenwirkung`
  ADD PRIMARY KEY (`a_nebenwirkung_id`);

ALTER TABLE `_org`
  ADD PRIMARY KEY (`a_org_id`);

ALTER TABLE `_patient`
  ADD PRIMARY KEY (`a_patient_id`);

ALTER TABLE `_qs_18_1_b`
  ADD PRIMARY KEY (`a_qs_18_1_b_id`);

ALTER TABLE `_qs_18_1_brust`
  ADD PRIMARY KEY (`a_qs_18_1_brust_id`);

ALTER TABLE `_qs_18_1_o`
  ADD PRIMARY KEY (`a_qs_18_1_o_id`);

ALTER TABLE `_recht`
  ADD PRIMARY KEY (`a_recht_id`);

ALTER TABLE `_recht_erkrankung`
  ADD PRIMARY KEY (`a_recht_erkrankung_id`);

ALTER TABLE `_settings`
  ADD PRIMARY KEY (`a_settings_id`);

ALTER TABLE `_settings_export`
  ADD PRIMARY KEY (`a_settings_export_id`);

ALTER TABLE `_settings_forms`
  ADD PRIMARY KEY (`a_settings_forms_id`);

ALTER TABLE `_settings_hl7`
  ADD PRIMARY KEY (`a_settings_hl7_id`);

ALTER TABLE `_settings_hl7field`
  ADD PRIMARY KEY (`a_settings_hl7field_id`);

ALTER TABLE `_settings_import`
  ADD PRIMARY KEY (`a_settings_import_id`);

ALTER TABLE `_settings_pacs`
  ADD PRIMARY KEY (`a_settings_pacs_id`);

ALTER TABLE `_settings_report`
  ADD PRIMARY KEY (`a_settings_report_id`);

ALTER TABLE `_sonstige_therapie`
  ADD PRIMARY KEY (`a_sonstige_therapie_id`);

ALTER TABLE `_strahlentherapie`
  ADD PRIMARY KEY (`a_strahlentherapie_id`);

ALTER TABLE `_studie`
  ADD PRIMARY KEY (`a_studie_id`);

ALTER TABLE `_termin`
  ADD PRIMARY KEY (`a_termin_id`);

ALTER TABLE `_therapieplan`
  ADD PRIMARY KEY (`a_therapieplan_id`);

ALTER TABLE `_therapieplan_abweichung`
  ADD PRIMARY KEY (`a_therapieplan_abweichung_id`);

ALTER TABLE `_therapie_systemisch`
  ADD PRIMARY KEY (`a_therapie_systemisch_id`,`erkrankung_id`,`patient_id`);

ALTER TABLE `_therapie_systemisch_zyklus`
  ADD PRIMARY KEY (`a_therapie_systemisch_zyklus_id`);

ALTER TABLE `_therapie_systemisch_zyklustag`
  ADD PRIMARY KEY (`a_therapie_systemisch_zyklustag_id`);

ALTER TABLE `_therapie_systemisch_zyklustag_wirkstoff`
  ADD PRIMARY KEY (`a_therapie_systemisch_zyklustag_wirkstoff_id`);

ALTER TABLE `_tumorstatus`
  ADD PRIMARY KEY (`a_tumorstatus_id`);

ALTER TABLE `_tumorstatus_metastasen`
  ADD PRIMARY KEY (`a_tumorstatus_metastasen_id`);

ALTER TABLE `_untersuchung`
  ADD PRIMARY KEY (`a_untersuchung_id`);

ALTER TABLE `_untersuchung_lokalisation`
  ADD PRIMARY KEY (`a_untersuchung_lokalisation_id`);

ALTER TABLE `_user`
  ADD PRIMARY KEY (`a_user_id`);

ALTER TABLE `_user_lock`
  ADD PRIMARY KEY (`a_user_lock_id`);

ALTER TABLE `_user_reg`
  ADD PRIMARY KEY (`a_user_id`);

ALTER TABLE `_vorlage_dokument`
  ADD PRIMARY KEY (`a_vorlage_dokument_id`);

ALTER TABLE `_vorlage_fallkennzeichen`
  ADD PRIMARY KEY (`a_vorlage_fallkennzeichen_id`);

ALTER TABLE `_vorlage_fragebogen`
  ADD PRIMARY KEY (`a_vorlage_fragebogen_id`);

ALTER TABLE `_vorlage_fragebogen_frage`
  ADD PRIMARY KEY (`a_vorlage_fragebogen_frage_id`);

ALTER TABLE `_vorlage_icd10`
  ADD PRIMARY KEY (`a_vorlage_icd10_id`);

ALTER TABLE `_vorlage_icdo`
  ADD PRIMARY KEY (`a_vorlage_icdo_id`);

ALTER TABLE `_vorlage_konferenztitel`
  ADD PRIMARY KEY (`a_vorlage_konferenztitel_id`);

ALTER TABLE `_vorlage_krankenversicherung`
  ADD PRIMARY KEY (`a_vorlage_krankenversicherung_id`);

ALTER TABLE `_vorlage_labor`
  ADD PRIMARY KEY (`a_vorlage_labor_id`);

ALTER TABLE `_vorlage_labor_wert`
  ADD PRIMARY KEY (`a_vorlage_labor_wert_id`);

ALTER TABLE `_vorlage_ops`
  ADD PRIMARY KEY (`a_vorlage_ops_id`);

ALTER TABLE `_vorlage_query`
  ADD PRIMARY KEY (`a_vorlage_query_id`);

ALTER TABLE `_vorlage_query_org`
  ADD PRIMARY KEY (`a_vorlage_query_org_id`);

ALTER TABLE `_vorlage_studie`
  ADD PRIMARY KEY (`a_vorlage_studie_id`);

ALTER TABLE `_vorlage_therapie`
  ADD PRIMARY KEY (`a_vorlage_therapie_id`);

ALTER TABLE `_vorlage_therapie_wirkstoff`
  ADD PRIMARY KEY (`a_vorlage_therapie_wirkstoff_id`);

ALTER TABLE `_zweitmeinung`
  ADD PRIMARY KEY (`a_zweitmeinung_id`);

ALTER TABLE `_zytologie`
  ADD PRIMARY KEY (`a_zytologie_id`);

ALTER TABLE `_zytologie_aberration`
  ADD PRIMARY KEY (`a_zytologie_aberration_id`);


ALTER TABLE `abschluss`
  MODIFY `abschluss_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `abschluss_ursache`
  MODIFY `abschluss_ursache_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `anamnese`
  MODIFY `anamnese_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `anamnese_erkrankung`
  MODIFY `anamnese_erkrankung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `anamnese_familie`
  MODIFY `anamnese_familie_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `aufenthalt`
  MODIFY `aufenthalt_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `begleitmedikation`
  MODIFY `begleitmedikation_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `behandler`
  MODIFY `behandler_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `beratung`
  MODIFY `beratung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `brief`
  MODIFY `brief_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `brief_empfaenger`
  MODIFY `brief_empfaenger_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `diagnose`
  MODIFY `diagnose_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `dmp_brustkrebs_eb`
  MODIFY `dmp_brustkrebs_eb_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `dmp_brustkrebs_ed_2013`
  MODIFY `dmp_brustkrebs_ed_2013_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `dmp_brustkrebs_ed_pnp_2013`
  MODIFY `dmp_brustkrebs_ed_pnp_2013_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `dmp_brustkrebs_fb`
  MODIFY `dmp_brustkrebs_fb_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `dmp_brustkrebs_fd_2013`
  MODIFY `dmp_brustkrebs_fd_2013_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `dmp_nummern_2013`
  MODIFY `dmp_nummern_2013_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `dokument`
  MODIFY `dokument_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `eingriff`
  MODIFY `eingriff_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `eingriff_ops`
  MODIFY `eingriff_ops_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `ekr`
  MODIFY `ekr_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `email`
  MODIFY `email_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `erkrankung`
  MODIFY `erkrankung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `erkrankung_synchron`
  MODIFY `erkrankung_synchron_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `export_case_log`
  MODIFY `export_case_log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `export_history`
  MODIFY `export_history_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `export_log`
  MODIFY `export_log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `export_patient_ids_log`
  MODIFY `export_patient_ids_log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `export_section_log`
  MODIFY `export_section_log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `exp_ekrrp_log`
  MODIFY `exp_ekrrp_log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `exp_gekid_log`
  MODIFY `exp_gekid_log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `exp_gkr_log`
  MODIFY `exp_gkr_log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `exp_krbw_ng_log`
  MODIFY `exp_krbw_ng_log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `exp_krhe_log`
  MODIFY `exp_krhe_log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `foto`
  MODIFY `foto_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `fragebogen`
  MODIFY `fragebogen_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `fragebogen_frage`
  MODIFY `fragebogen_frage_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `histologie`
  MODIFY `histologie_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `histologie_einzel`
  MODIFY `histologie_einzel_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `hl7_cache`
  MODIFY `hl7_cache_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `hl7_diagnose`
  MODIFY `hl7_diagnose_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `hl7_log_cache`
  MODIFY `hl7_log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `hl7_message`
  MODIFY `hl7_message_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `komplikation`
  MODIFY `komplikation_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `konferenz`
  MODIFY `konferenz_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `konferenz_abschluss`
  MODIFY `konferenz_abschluss_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `konferenz_dokument`
  MODIFY `konferenz_dokument_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `konferenz_patient`
  MODIFY `konferenz_patient_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `konferenz_teilnehmer`
  MODIFY `konferenz_teilnehmer_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `konferenz_teilnehmer_profil`
  MODIFY `konferenz_teilnehmer_profil_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `labor`
  MODIFY `labor_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `labor_wert`
  MODIFY `labor_wert_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `l_exp_gkr_addresses`
  MODIFY `gkr_address_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `nachsorge`
  MODIFY `nachsorge_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `nachsorge_erkrankung`
  MODIFY `nachsorge_erkrankung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `nebenwirkung`
  MODIFY `nebenwirkung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `org`
  MODIFY `org_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `patho_item`
  MODIFY `patho_item_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `patient`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `qs_18_1_b`
  MODIFY `qs_18_1_b_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `qs_18_1_brust`
  MODIFY `qs_18_1_brust_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `qs_18_1_o`
  MODIFY `qs_18_1_o_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `recht`
  MODIFY `recht_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `recht_erkrankung`
  MODIFY `recht_erkrankung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `report_time`
  MODIFY `report_time_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `settings`
  MODIFY `settings_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `settings_export`
  MODIFY `settings_export_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `settings_forms`
  MODIFY `settings_forms_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `settings_hl7`
  MODIFY `settings_hl7_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `settings_hl7field`
  MODIFY `settings_hl7field_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `settings_import`
  MODIFY `settings_import_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `settings_pacs`
  MODIFY `settings_pacs_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `settings_report`
  MODIFY `settings_report_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `sonstige_therapie`
  MODIFY `sonstige_therapie_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `status_lock`
  MODIFY `status_lock_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `status_lock_bem`
  MODIFY `status_lock_bem_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `strahlentherapie`
  MODIFY `strahlentherapie_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `studie`
  MODIFY `studie_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `termin`
  MODIFY `termin_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `therapieplan`
  MODIFY `therapieplan_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `therapieplan_abweichung`
  MODIFY `therapieplan_abweichung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `therapie_systemisch`
  MODIFY `therapie_systemisch_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `therapie_systemisch_zyklus`
  MODIFY `therapie_systemisch_zyklus_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `therapie_systemisch_zyklustag`
  MODIFY `therapie_systemisch_zyklustag_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `therapie_systemisch_zyklustag_wirkstoff`
  MODIFY `therapie_systemisch_zyklustag_wirkstoff_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tumorstatus`
  MODIFY `tumorstatus_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tumorstatus_metastasen`
  MODIFY `tumorstatus_metastasen_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `untersuchung`
  MODIFY `untersuchung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `untersuchung_lokalisation`
  MODIFY `untersuchung_lokalisation_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_lock`
  MODIFY `user_lock_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_log`
  MODIFY `user_log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_reg`
  MODIFY `user_reg_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vorlage_dokument`
  MODIFY `vorlage_dokument_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vorlage_fallkennzeichen`
  MODIFY `vorlage_fallkennzeichen_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vorlage_fragebogen`
  MODIFY `vorlage_fragebogen_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vorlage_fragebogen_frage`
  MODIFY `vorlage_fragebogen_frage_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vorlage_icd10`
  MODIFY `vorlage_icd10_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vorlage_icdo`
  MODIFY `vorlage_icdo_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vorlage_konferenztitel`
  MODIFY `vorlage_konferenztitel_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vorlage_krankenversicherung`
  MODIFY `vorlage_krankenversicherung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vorlage_labor`
  MODIFY `vorlage_labor_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vorlage_labor_wert`
  MODIFY `vorlage_labor_wert_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vorlage_ops`
  MODIFY `vorlage_ops_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vorlage_query`
  MODIFY `vorlage_query_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vorlage_query_org`
  MODIFY `vorlage_query_org_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vorlage_studie`
  MODIFY `vorlage_studie_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vorlage_therapie`
  MODIFY `vorlage_therapie_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vorlage_therapie_wirkstoff`
  MODIFY `vorlage_therapie_wirkstoff_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `zweitmeinung`
  MODIFY `zweitmeinung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `zytologie`
  MODIFY `zytologie_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `zytologie_aberration`
  MODIFY `zytologie_aberration_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_abschluss`
  MODIFY `a_abschluss_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_abschluss_ursache`
  MODIFY `a_abschluss_ursache_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_anamnese`
  MODIFY `a_anamnese_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_anamnese_erkrankung`
  MODIFY `a_anamnese_erkrankung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_anamnese_familie`
  MODIFY `a_anamnese_familie_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_aufenthalt`
  MODIFY `a_aufenthalt_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_begleitmedikation`
  MODIFY `a_begleitmedikation_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_behandler`
  MODIFY `a_behandler_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_beratung`
  MODIFY `a_beratung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_brief`
  MODIFY `a_brief_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_brief_empfaenger`
  MODIFY `a_brief_empfaenger_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_diagnose`
  MODIFY `a_diagnose_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_dmp_brustkrebs_eb`
  MODIFY `a_dmp_brustkrebs_eb_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_dmp_brustkrebs_ed_2013`
  MODIFY `a_dmp_brustkrebs_ed_2013_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_dmp_brustkrebs_ed_pnp_2013`
  MODIFY `a_dmp_brustkrebs_ed_pnp_2013_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_dmp_brustkrebs_fb`
  MODIFY `a_dmp_brustkrebs_fb_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_dmp_brustkrebs_fd_2013`
  MODIFY `a_dmp_brustkrebs_fd_2013_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_dmp_nummern_2013`
  MODIFY `a_dmp_nummern_2013_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_dokument`
  MODIFY `a_dokument_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_eingriff`
  MODIFY `a_eingriff_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_eingriff_ops`
  MODIFY `a_eingriff_ops_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_ekr`
  MODIFY `a_ekr_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_erkrankung`
  MODIFY `a_erkrankung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_erkrankung_synchron`
  MODIFY `a_erkrankung_synchron_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_export_history`
  MODIFY `a_export_history_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_foto`
  MODIFY `a_foto_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_fragebogen`
  MODIFY `a_fragebogen_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_fragebogen_frage`
  MODIFY `a_fragebogen_frage_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_histologie`
  MODIFY `a_histologie_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_histologie_einzel`
  MODIFY `a_histologie_einzel_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_komplikation`
  MODIFY `a_komplikation_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_konferenz`
  MODIFY `a_konferenz_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_konferenz_abschluss`
  MODIFY `a_konferenz_abschluss_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_konferenz_dokument`
  MODIFY `a_konferenz_dokument_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_konferenz_patient`
  MODIFY `a_konferenz_patient_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_konferenz_teilnehmer`
  MODIFY `a_konferenz_teilnehmer_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_konferenz_teilnehmer_profil`
  MODIFY `a_konferenz_teilnehmer_profil_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_labor`
  MODIFY `a_labor_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_labor_wert`
  MODIFY `a_labor_wert_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_nachsorge`
  MODIFY `a_nachsorge_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_nachsorge_erkrankung`
  MODIFY `a_nachsorge_erkrankung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_nebenwirkung`
  MODIFY `a_nebenwirkung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_org`
  MODIFY `a_org_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_patient`
  MODIFY `a_patient_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_qs_18_1_b`
  MODIFY `a_qs_18_1_b_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_qs_18_1_brust`
  MODIFY `a_qs_18_1_brust_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_qs_18_1_o`
  MODIFY `a_qs_18_1_o_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_recht`
  MODIFY `a_recht_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_recht_erkrankung`
  MODIFY `a_recht_erkrankung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_settings`
  MODIFY `a_settings_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_settings_export`
  MODIFY `a_settings_export_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_settings_forms`
  MODIFY `a_settings_forms_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_settings_hl7`
  MODIFY `a_settings_hl7_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_settings_hl7field`
  MODIFY `a_settings_hl7field_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_settings_import`
  MODIFY `a_settings_import_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_settings_pacs`
  MODIFY `a_settings_pacs_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_settings_report`
  MODIFY `a_settings_report_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_sonstige_therapie`
  MODIFY `a_sonstige_therapie_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_strahlentherapie`
  MODIFY `a_strahlentherapie_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_studie`
  MODIFY `a_studie_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_termin`
  MODIFY `a_termin_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_therapieplan`
  MODIFY `a_therapieplan_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_therapieplan_abweichung`
  MODIFY `a_therapieplan_abweichung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_therapie_systemisch`
  MODIFY `a_therapie_systemisch_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_therapie_systemisch_zyklus`
  MODIFY `a_therapie_systemisch_zyklus_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_therapie_systemisch_zyklustag`
  MODIFY `a_therapie_systemisch_zyklustag_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_therapie_systemisch_zyklustag_wirkstoff`
  MODIFY `a_therapie_systemisch_zyklustag_wirkstoff_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_tumorstatus`
  MODIFY `a_tumorstatus_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_tumorstatus_metastasen`
  MODIFY `a_tumorstatus_metastasen_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_untersuchung`
  MODIFY `a_untersuchung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_untersuchung_lokalisation`
  MODIFY `a_untersuchung_lokalisation_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_user`
  MODIFY `a_user_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_user_lock`
  MODIFY `a_user_lock_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_user_reg`
  MODIFY `a_user_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_vorlage_dokument`
  MODIFY `a_vorlage_dokument_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_vorlage_fallkennzeichen`
  MODIFY `a_vorlage_fallkennzeichen_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_vorlage_fragebogen`
  MODIFY `a_vorlage_fragebogen_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_vorlage_fragebogen_frage`
  MODIFY `a_vorlage_fragebogen_frage_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_vorlage_icd10`
  MODIFY `a_vorlage_icd10_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_vorlage_icdo`
  MODIFY `a_vorlage_icdo_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_vorlage_konferenztitel`
  MODIFY `a_vorlage_konferenztitel_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_vorlage_krankenversicherung`
  MODIFY `a_vorlage_krankenversicherung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_vorlage_labor`
  MODIFY `a_vorlage_labor_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_vorlage_labor_wert`
  MODIFY `a_vorlage_labor_wert_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_vorlage_ops`
  MODIFY `a_vorlage_ops_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_vorlage_query`
  MODIFY `a_vorlage_query_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_vorlage_query_org`
  MODIFY `a_vorlage_query_org_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_vorlage_studie`
  MODIFY `a_vorlage_studie_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_vorlage_therapie`
  MODIFY `a_vorlage_therapie_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_vorlage_therapie_wirkstoff`
  MODIFY `a_vorlage_therapie_wirkstoff_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_zweitmeinung`
  MODIFY `a_zweitmeinung_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_zytologie`
  MODIFY `a_zytologie_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_zytologie_aberration`
  MODIFY `a_zytologie_aberration_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
