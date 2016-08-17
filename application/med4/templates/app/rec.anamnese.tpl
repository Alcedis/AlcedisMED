{*
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
*} 

<table class="formtable">

{html_set_header caption=#head_basis#    class="head"}
<tr>
    <td colspan="2" style="padding:0px">
        <div class="info-msg">
            {#msg_info#}
        </div>
    </td>
</tr>
{html_set_row field="datum"                  caption=$_datum_lbl                  input=$_datum add="$_datum_nb$_datum_nb_lbl"}
{html_set_row field="groesse"                caption=$_groesse_lbl                input=$_groesse  add=#lbl_cm#}
{html_set_row field="gewicht"                caption=$_gewicht_lbl                input=$_gewicht  add=#lbl_kg#}
{html_set_row field="mehrlingseigenschaften" caption=$_mehrlingseigenschaften_lbl input=$_mehrlingseigenschaften}
{html_set_row field="entdeckung"             caption=$_entdeckung_lbl             input=$_entdeckung}

{html_set_html field="vorsorge_regelmaessig" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_vorsorge_regelmaessig_lbl</td>
            <td class='edt' style='width:8%;'>$_vorsorge_regelmaessig</td>
            <td class='edt' style='width:30%;' align='right'>
               $_vorsorge_intervall_lbl <br/>
               <div style='margin-top:4px'>
                  $_vorsorge_datum_letzte_lbl
               </div>
            </td>
            <td class='edt' >
               $_vorsorge_intervall<br/>
               <div style='margin-top:4px'>
                  $_vorsorge_datum_letzte
               </div>
            </td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_row field="screening"             caption=$_screening_lbl             input=$_screening}

{html_set_header caption=#head_risiko#    class="head" field="risiko_raucher,risiko_autoimmun_sonstige,risiko_autoimmun_dermatitis,risiko_autoimmun_zoeliakie,risiko_autoimmun_lupus_ery,risiko_autoimmun_arthritis,risiko_autoimmun,risiko_autoimmun_sjoergren,risiko_exraucher,risiko_alkohol,risiko_medikamente,risiko_drogen,risiko_pille,hormon_substitution,testosteron_substitution,darmerkrankung_jn,risiko_infekt,risiko_infekt_ebv,risiko_infekt_htlv1,risiko_infekt_hiv,risiko_infekt_hcv,risiko_infekt_hp,risiko_infekt_bb,risiko_infekt_sonstige,hpv,hpv,risiko_transplantation,risiko_familie_melanom,risiko_sonnenbrand_kind,risiko_sonnenbankbesuch,risiko_sonnenschutzmittel,risiko_noxen,risiko_chronische_wunden,beruf_letzter,beruf_laengster,beruf_risiko,risiko_sonstige"}

{html_set_row field="risiko_autoimmun"     				caption=$_risiko_autoimmun_lbl     				input=$_risiko_autoimmun}
{html_set_row field="risiko_autoimmun_sjoergren"     	caption=$_risiko_autoimmun_sjoergren_lbl     input=$_risiko_autoimmun_sjoergren}
{html_set_row field="risiko_autoimmun_arthritis"     	caption=$_risiko_autoimmun_arthritis_lbl     input=$_risiko_autoimmun_arthritis}
{html_set_row field="risiko_autoimmun_lupus_ery"     	caption=$_risiko_autoimmun_lupus_ery_lbl     input=$_risiko_autoimmun_lupus_ery}
{html_set_row field="risiko_autoimmun_zoeliakie"     	caption=$_risiko_autoimmun_zoeliakie_lbl     input=$_risiko_autoimmun_zoeliakie}
{html_set_row field="risiko_autoimmun_dermatitis"     caption=$_risiko_autoimmun_dermatitis_lbl    input=$_risiko_autoimmun_dermatitis}
{html_set_row field="risiko_autoimmun_sonstige"     	caption=$_risiko_autoimmun_sonstige_lbl     	input=$_risiko_autoimmun_sonstige}

{html_set_html field="risiko_raucher" html="
	<tr>
		<td class='lbl'>
			$_risiko_raucher_lbl
		</td>
		<td class='edt'>
			$_risiko_raucher
"}
			{html_set_html field="risiko_raucher_dauer" html="<span style='padding-left: 20px'>$_risiko_raucher_dauer_lbl $_risiko_raucher_dauer `$smarty.config.lbl_jahre`</span>"}
			{html_set_html field="risiko_raucher_menge" html="<span style='padding-left: 20px'>$_risiko_raucher_menge_lbl $_risiko_raucher_menge `$smarty.config.lbl_stueck`</span>"}
		{html_set_html field="risiko_raucher" html="
		</td>
	</tr>
"}

{html_set_html field="risiko_exraucher" html="
	<tr>
		<td class='lbl'>
			$_risiko_exraucher_lbl
		</td>
		<td class='edt'>
			$_risiko_exraucher
"}
			{html_set_html field="risiko_exraucher_dauer" html="<span style='padding-left: 20px'>$_risiko_exraucher_dauer_lbl $_risiko_exraucher_dauer `$smarty.config.lbl_jahre`</span>"}
			{html_set_html field="risiko_exraucher_dauer" html="<span style='padding-left: 20px'>$_risiko_exraucher_menge_lbl $_risiko_exraucher_menge `$smarty.config.lbl_stueck`</span>"}
{html_set_html field="risiko_exraucher" html="
		</td>
	</tr>
"}

{html_set_row field="risiko_alkohol"     caption=$_risiko_alkohol_lbl     input=$_risiko_alkohol}
{html_set_row field="risiko_medikamente" caption=$_risiko_medikamente_lbl input=$_risiko_medikamente}
{html_set_row field="risiko_drogen"      caption=$_risiko_drogen_lbl      input=$_risiko_drogen}

{html_set_html field="risiko_pille" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_risiko_pille_lbl</td>
            <td class='edt' style='width:10%;'>$_risiko_pille</td>
            <td class='edt'>$_risiko_pille_dauer_lbl $_risiko_pille_dauer `$smarty.config.lbl_jahre`</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_html field="hormon_substitution" html="
   <tr>
   <td class='msg' colspan='2'>
      <table class='inline-table'>
      <tr>
         <td class='lbl' style='width:35%;'>$_hormon_substitution_lbl</td>
         <td class='edt' style='width:10%;'>$_hormon_substitution</td>
         <td class='edt'>$_hormon_substitution_dauer_lbl $_hormon_substitution_dauer `$smarty.config.lbl_jahre`</td>
         <td class='edt'>$_hormon_substitution_art_lbl  $_hormon_substitution_art </td>

      </tr>
      </table>
   </td>
   </tr>
"}

{html_set_row field="testosteron_substitution"       caption=$_testosteron_substitution_lbl   input="$_testosteron_substitution $_testosteron_substitution_dauer_lbl $_testosteron_substitution_dauer" add=#lbl_jahre#}

{html_set_html field="darmerkrankung_jn" html="
   <tr>
   <td class='msg' colspan='2'>
      <table class='inline-table'>
      <tr>
         <td class='lbl' style='width:35%;border-bottom:1px solid #fff;' rowspan='3'>$_darmerkrankung_jn_lbl</td>
         <td class='edt' style='width:10%;border-bottom:1px solid #fff;' rowspan='3'>$_darmerkrankung_jn</td>
         <td class='edt' style='width:11%;'>$_darmerkrankung_morbus_lbl</td>
         <td class='edt'>$_darmerkrankung_morbus</td>
      </tr>
      <tr>
         <td class='edt' style='width:11%;'>$_darmerkrankung_colitis_lbl</td>
         <td class='edt'>$_darmerkrankung_colitis</td>
      </tr>
      <tr>
         <td class='edt' style='width:11%;border-bottom:1px solid #fff;'>$_darmerkrankung_sonstige_lbl</td>
         <td class='edt' style='border-bottom:1px solid #fff;'>$_darmerkrankung_sonstige</td>
      </tr>
      </table>
   </td>
   </tr>
"}

{html_set_row field="risiko_infekt" caption=$_risiko_infekt_lbl  input=$_risiko_infekt}
{html_set_row field="risiko_infekt_ebv" caption=$_risiko_infekt_ebv_lbl  input=$_risiko_infekt_ebv}
{html_set_row field="risiko_infekt_htlv1" caption=$_risiko_infekt_htlv1_lbl  input=$_risiko_infekt_htlv1}
{html_set_row field="risiko_infekt_hiv" caption=$_risiko_infekt_hiv_lbl  input=$_risiko_infekt_hiv}
{html_set_row field="risiko_infekt_hcv" caption=$_risiko_infekt_hcv_lbl  input=$_risiko_infekt_hcv}
{html_set_row field="risiko_infekt_hp" caption=$_risiko_infekt_hp_lbl  input=$_risiko_infekt_hp}
{html_set_row field="risiko_infekt_bb" caption=$_risiko_infekt_bb_lbl  input=$_risiko_infekt_bb}
{html_set_row field="risiko_infekt_sonstige" caption=$_risiko_infekt_sonstige_lbl  input=$_risiko_infekt_sonstige}


{html_set_row field="hpv" caption=$_hpv_lbl input=$_hpv}
{html_set_html field="hpv" html="
   <tr>
   	<td class='lbl'>$_hpv_typ01_lbl</td>
   	<td class='edt'>
   		$_hpv_typ01
   		<span style='padding-left:10px'>$_hpv_typ02</span>
   		<span style='padding-left:10px'>$_hpv_typ03</span>
   		<span style='padding-left:10px'>$_hpv_typ04</span>
   		<span style='padding-left:10px'>$_hpv_typ05</span>
   		<br/><br/>
   	   $_hpv_typ06
   		<span style='padding-left:10px'>$_hpv_typ07</span>
   		<span style='padding-left:10px'>$_hpv_typ08</span>
   		<span style='padding-left:10px'>$_hpv_typ09</span>
   		<span style='padding-left:10px'>$_hpv_typ10</span>
   	</td>
   </tr>
"}

{html_set_row field="risiko_transplantation" caption=$_risiko_transplantation_lbl  input="$_risiko_transplantation $_risiko_transplantation_detail_lbl $_risiko_transplantation_detail"}
{html_set_row field="risiko_familie_melanom" caption=$_risiko_familie_melanom_lbl  input=$_risiko_familie_melanom}

{html_set_row field="risiko_sonnenbrand_kind" caption=$_risiko_sonnenbrand_kind_lbl  input=$_risiko_sonnenbrand_kind}
{html_set_row field="risiko_sonnenbankbesuch" caption=$_risiko_sonnenbankbesuch_lbl  input=$_risiko_sonnenbankbesuch}

{html_set_html field="risiko_sonnenschutzmittel" html="
   <tr>
   <td class='msg' colspan='2'>
      <table class='inline-table'>
      <tr>
         <td class='lbl' style='width:35%;'>$_risiko_sonnenschutzmittel_lbl</td>
         <td class='edt' style='width:10%;'>$_risiko_sonnenschutzmittel</td>
         <td class='edt'>$_risiko_sonnenschutzmittel_detail_lbl $_risiko_sonnenschutzmittel_detail</td>
      </tr>
      </table>
   </td>
   </tr>
"}

{html_set_html field="risiko_noxen" html="
   <tr>
   <td class='msg' colspan='2'>
      <table class='inline-table'>
      <tr>
         <td class='lbl' style='width:35%;'>$_risiko_noxen_lbl</td>
         <td class='edt' style='width:10%;'>$_risiko_noxen</td>
         <td class='edt'>$_risiko_noxen_detail_lbl $_risiko_noxen_detail</td>
      </tr>
      </table>
   </td>
   </tr>
"}

{html_set_row field="risiko_chronische_wunden" caption=$_risiko_chronische_wunden_lbl  input=$_risiko_chronische_wunden}

{html_set_html field="beruf_letzter" html="
   <tr>
   <td class='msg' colspan='2'>
      <table class='inline-table'>
      <tr>
         <td class='lbl' style='width:35%;border-bottom:1px solid #fff;'>$_beruf_letzter_lbl</td>
         <td class='edt' style='border-bottom:1px solid #fff;'>$_beruf_letzter</td>
         <td class='edt' style='border-bottom:1px solid #fff;'>$_beruf_letzter_dauer_lbl $_beruf_letzter_dauer`$smarty.config.lbl_jahre`</td>
      </tr>
      </table>
   </td>
   </tr>
"}

{html_set_html field="beruf_laengster" html="
   <tr>
   <td class='msg' colspan='2'>
      <table class='inline-table'>
      <tr>
         <td class='lbl' style='width:35%;'>$_beruf_laengster_lbl</td>
         <td class='edt'>$_beruf_laengster</td>
         <td class='edt'>$_beruf_laengster_dauer_lbl $_beruf_laengster_dauer`$smarty.config.lbl_jahre`</td>
      </tr>
      </table>
   </td>
   </tr>
"}

{html_set_row field="beruf_risiko"     caption=$_beruf_risiko_lbl    input=$_beruf_risiko add=$_beruf_risiko_detail}
{html_set_row field="risiko_sonstige"     caption=$_risiko_sonstige_lbl    input=$_risiko_sonstige}
{html_set_header caption=#head_symptome# class="head" field="sy_schmerzen,sy_schmerzen_lokalisation,sy_schmerzscore,sy_dyspnoe,sy_haemoptnoe,sy_husten,sy_harndrang,sy_nykturie,sy_pollakisurie,sy_miktion,sy_harnverhalt,sy_harnstau,sy_haematurie,sy_para_syndrom,sy_gewichtsverlust,sy_fieber,sy_nachtschweiss,sy_sonstige,sy_dauer"}
{html_set_row field="sy_schmerzen"        caption=$_sy_schmerzen_lbl       input=$_sy_schmerzen}
{html_set_html field="sy_schmerzen_lokalisation" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_sy_schmerzen_lokalisation_lbl</td>
            <td class='edt'>
               $_sy_schmerzen_lokalisation
            </td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_row field="sy_schmerzscore"      caption=$_sy_schmerzscore_lbl    input=$_sy_schmerzscore}
{html_set_row field="sy_dyspnoe"           caption=$_sy_dyspnoe_lbl         input=$_sy_dyspnoe}
{html_set_row field="sy_haemoptnoe"        caption=$_sy_haemoptnoe_lbl      input=$_sy_haemoptnoe}
{html_set_html field="sy_husten" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_sy_husten_lbl</td>
            <td class='edt' style='width:12%;'>$_sy_husten</td>
            <td class='edt'>$_sy_husten_dauer_lbl $_sy_husten_dauer</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_row field="sy_harndrang"         caption=$_sy_harndrang_lbl       input=$_sy_harndrang}
{html_set_row field="sy_nykturie"          caption=$_sy_nykturie_lbl        input=$_sy_nykturie}
{html_set_row field="sy_pollakisurie"      caption=$_sy_pollakisurie_lbl    input=$_sy_pollakisurie}
{html_set_html field="sy_miktion" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_sy_miktion_lbl</td>
            <td class='edt' style='width:12%;'>$_sy_miktion</td>
            <td class='edt'>$_sy_restharn_lbl $_sy_restharn `$smarty.config.lbl_ml`</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_row field="sy_harnverhalt"       caption=$_sy_harnverhalt_lbl      input=$_sy_harnverhalt}
{html_set_html field="sy_harnstau" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_sy_harnstau_lbl</td>
            <td class='edt' style='width:12%;'>$_sy_harnstau</td>
            <td class='edt'>$_sy_harnstau_lokalisation_lbl $_sy_harnstau_lokalisation</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_row field="sy_haematurie"    caption=$_sy_haematurie_lbl   input=$_sy_haematurie}
{html_set_html field="sy_para_syndrom" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'  style='margin-top:1px;'>
         <tr>
            <td class='lbl' style='width:35%;' rowspan='2'>$_sy_para_syndrom_lbl</td>
            <td class='edt' style='width:12%;'>$_sy_para_syndrom</td>
            <td class='edt'>$_sy_para_syndrom_symptom_lbl $_sy_para_syndrom_symptom</td>
         </tr>
         <tr>
            <td class='edt' colspan='2'>$_sy_para_syndrom_detail_lbl $_sy_para_syndrom_detail</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_html field="sy_gewichtsverlust" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table' style='margin-top:1px;'>
         <tr>
            <td class='lbl' style='width:35%;'>$_sy_gewichtsverlust_lbl</td>
            <td class='edt' style='width:12%;'>$_sy_gewichtsverlust</td>
            <td class='edt'>$_sy_gewichtsverlust_2wo $_sy_gewichtsverlust_2wo_lbl $_sy_gewichtsverlust_3mo $_sy_gewichtsverlust_3mo_lbl</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_row field="sy_fieber"   caption=$_sy_fieber_lbl  input=$_sy_fieber}
{html_set_row field="sy_nachtschweiss"   caption=$_sy_nachtschweiss_lbl  input=$_sy_nachtschweiss}

{html_set_row field="sy_sonstige"   caption=$_sy_sonstige_lbl  input=$_sy_sonstige}
{html_set_html field="sy_dauer" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_sy_dauer_lbl</td>
            <td class='edt' style='width:9%;'>$_sy_dauer</td>
            <td class='edt'>$_sy_dauer_einheit</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_header field="euroqol,lcss,fb_dkg,iciq_ui,ics,iief5,ipss,lq_dkg,gz_dkg,ql" caption=#head_qualitaet# class="head"}
{html_set_row field="euroqol"  caption=$_euroqol_lbl  input=$_euroqol add=#lbl_proz#}
{html_set_row field="lcss"     caption=$_lcss_lbl     input=$_lcss}
{html_set_row field="fb_dkg"   caption=$_fb_dkg_lbl   input=$_fb_dkg add="<br/><br/>$_fb_dkg_beurt_lbl $_fb_dkg_beurt"}
{html_set_row field="iciq_ui"  caption=$_iciq_ui_lbl  input=$_iciq_ui}
{html_set_row field="ics"      caption=$_ics_lbl      input=$_ics}
{html_set_row field="iief5"    caption=$_iief5_lbl    input=$_iief5}
{html_set_row field="ipss"     caption=$_ipss_lbl     input=$_ipss}
{html_set_row field="lq_dkg"   caption=$_lq_dkg_lbl   input=$_lq_dkg}
{html_set_row field="gz_dkg"   caption=$_gz_dkg_lbl   input=$_gz_dkg}
{html_set_row field="ql"       caption=$_ql_lbl       input=$_ql}

{getView field='dlist_familie,familien_karzinom,gen_jn,gen_sonstige,gen_fap,gen_gardner,gen_peutz,gen_hnpcc,gen_turcot,gen_polyposis,gen_dcc,gen_baxgen,gen_smad2,gen_smad4,gen_kras,gen_apc,gen_p53,gen_cmyc,gen_tgfb2,gen_wiskott_aldrich,gen_cvi,gen_louis_bar,gen_hpc1,gen_pcap,gen_cabp,gen_brca1,gen_brca2,gen_sonstige,gen_x27_28,gen_jn,gen_sonstige,beratung_genetik,bethesda,pot_pde5hemmer,pot_pde5hemmer_haeufigkeit,pot_vakuumpumpe,pot_skat,pot_penisprothese'}

   {html_set_header caption=#head_familienanam# class="head"}
   {html_set_row field="familien_karzinom"  caption=$_familien_karzinom_lbl  input=$_familien_karzinom}

   {getView field='dlist_familie'}

      <tr style='border-bottom:1px solid #fff;'>
         <td class="msg" colspan="2">
            <div class="dlist" id="dlist_familie">
               <div class="add">
                  <input class="button" type="button" name="anamnese_familie" value="Hinzuf&uuml;gen" onclick="execute_request(this,'rec.anamnese_familie', null, ['patient_id', 'anamnese_id', 'erkrankung_id'])"/>
               </div>
            </div>
         </td>
      </tr>
   {/getView}

{html_set_html field="gen_jn,gen_sonstige" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width: 35%;' rowspan='8'>$_gen_jn_lbl</td>
            <td class='edt' colspan='3'>$_gen_jn</td>
         </tr>
"}
{html_set_html field="gen_fap,gen_gardner,gen_peutz" html="
         <tr>
            <td class='edt'>$_gen_fap $_gen_fap_lbl</td>
            <td class='edt'>$_gen_gardner $_gen_gardner_lbl</td>
            <td class='edt'>$_gen_peutz $_gen_peutz_lbl</td>
         </tr>
"}
{html_set_html field="gen_hnpcc,gen_turcot,gen_polyposis" html="
         <tr>
            <td class='edt'>$_gen_hnpcc $_gen_hnpcc_lbl</td>
            <td class='edt'>$_gen_turcot $_gen_turcot_lbl</td>
            <td class='edt'>$_gen_polyposis $_gen_polyposis_lbl</td>
         </tr>
"}
{html_set_html field="gen_dcc,gen_baxgen,gen_smad2" html="
         <tr>
            <td class='edt'>$_gen_dcc $_gen_dcc_lbl</td>
            <td class='edt'>$_gen_baxgen $_gen_baxgen_lbl</td>
            <td class='edt'>$_gen_smad2 $_gen_smad2_lbl</td>
         </tr>
"}
{html_set_html field="gen_smad4,gen_kras,gen_apc" html="
         <tr>
            <td class='edt'>$_gen_smad4 $_gen_smad4_lbl</td>
            <td class='edt'>$_gen_kras $_gen_kras_lbl</td>
            <td class='edt'>$_gen_apc $_gen_apc_lbl</td>
         </tr>
"}

{html_set_html field="gen_p53,gen_cmyc,gen_tgfb2" html="
         <tr>
            <td class='edt'>$_gen_p53 $_gen_p53_lbl</td>
            <td class='edt'>$_gen_cmyc $_gen_cmyc_lbl</td>
            <td class='edt'>$_gen_tgfb2 $_gen_tgfb2_lbl</td>
         </tr>
"}

{html_set_html field="gen_wiskott_aldrich,gen_cvi,gen_louis_bar" html="
         <tr>
            <td class='edt'>$_gen_wiskott_aldrich $_gen_wiskott_aldrich_lbl</td>
            <td class='edt'>$_gen_cvi $_gen_cvi_lbl</td>
            <td class='edt'>$_gen_louis_bar $_gen_louis_bar_lbl</td>
         </tr>
"}

{html_set_html field="gen_hpc1,gen_pcap,gen_cabp" html="
         <tr>
            <td class='edt'>$_gen_hpc1 $_gen_hpc1_lbl</td>
            <td class='edt'>$_gen_pcap $_gen_pcap_lbl</td>
            <td class='edt'>$_gen_cabp $_gen_cabp_lbl</td>
         </tr>
"}

{html_set_html field="gen_brca1,gen_brca2" html="
         <tr>
            <td class='edt'>$_gen_brca1 $_gen_brca1_lbl</td>
            <td class='edt'>$_gen_brca2 $_gen_brca2_lbl</td>
            <td class='edt'></td>
         </tr>
"}

{html_set_html field="gen_sonstige,gen_x27_28" html="
         <tr>
            <td class='edt' colspan='2'>$_gen_sonstige_lbl $_gen_sonstige</td>
            <td class='edt'>$_gen_x27_28 $_gen_x27_28_lbl</td>
         </tr>
"}

{html_set_html field="gen_jn,gen_sonstige" html="
         </table>
      </td>
   </tr>
"}


{html_set_row field="bethesda"          caption=$_bethesda_lbl          input=$_bethesda}
{html_set_row field="beratung_genetik"  caption=$_beratung_genetik_lbl  input=$_beratung_genetik}
{html_set_html field='pot_pde5hemmer,pot_pde5hemmer_haeufigkeit,pot_vakuumpumpe,pot_skat,pot_penisprothese' html="
    <tr>
        <td class='head' colspan='2'>`$smarty.config.head_potenz`</td>
    </tr>
    <tr>
       <td class='lbl'>$_pot_pde5hemmer_lbl</td>
       <td class='edt'>$_pot_pde5hemmer</td>
    </tr>
    <tr>
       <td class='lbl'>$_pot_pde5hemmer_haeufigkeit_lbl</td>
       <td class='edt'>$_pot_pde5hemmer_haeufigkeit</td>
    </tr>
    <tr>
       <td class='lbl'>$_pot_vakuumpumpe_lbl</td>
       <td class='edt'>$_pot_vakuumpumpe</td>
    </tr>
    <tr>
       <td class='lbl'>$_pot_skat_lbl</td>
       <td class='edt'>$_pot_skat</td>
    </tr>
    <tr>
       <td class='lbl'>$_pot_penisprothese_lbl</td>
       <td class='edt'>$_pot_penisprothese</td>
    </tr>
"}
{/getView}

{html_set_header caption=#head_therapierelevant# class="head" field="ecog,schwanger,menopausenstatus,alter_menarche,alter_menopause,menopause_iatrogen,geburten_lebend,geburten_fehl,geburten_tot,schwangerschaft_erste_alter,schwangerschaft_letzte_alter,zn_hysterektomie,vorop,vorbestrahlung,platinresistenz,vorop_uterus_zervix"}
{html_set_row field="ecog"             caption=$_ecog_lbl               input=$_ecog}
{html_set_row field="schwanger"        caption=$_schwanger_lbl          input=$_schwanger}
{html_set_row field="menopausenstatus" caption=$_menopausenstatus_lbl   input=$_menopausenstatus}
{html_set_row field="alter_menarche"   caption=$_alter_menarche_lbl     input=$_alter_menarche add=#lbl_jahre#}
{html_set_row field="alter_menopause"  caption=$_alter_menopause_lbl    input=$_alter_menopause add=#lbl_jahre#}
{html_set_html field="menopause_iatrogen" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_menopause_iatrogen_lbl</td>
            <td class='edt' style='width:10%;'>$_menopause_iatrogen</td>
            <td class='edt'>$_menopause_iatrogen_ursache_lbl $_menopause_iatrogen_ursache</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_row field="geburten_lebend"              caption=$_geburten_lebend_lbl                input=$_geburten_lebend}
{html_set_row field="geburten_tot"                 caption=$_geburten_tot_lbl                   input=$_geburten_tot}
{html_set_row field="geburten_fehl"                caption=$_geburten_fehl_lbl                  input=$_geburten_fehl}
{html_set_row field="schwangerschaft_erste_alter"  caption=$_schwangerschaft_erste_alter_lbl    input=$_schwangerschaft_erste_alter add=#lbl_jahre#}
{html_set_row field="schwangerschaft_letzte_alter" caption=$_schwangerschaft_letzte_alter_lbl   input=$_schwangerschaft_letzte_alter add=#lbl_jahre#}
{html_set_row field="zn_hysterektomie" caption=$_zn_hysterektomie_lbl   input=$_zn_hysterektomie}
{html_set_html field="vorop" html="
    <tr>
        <td class='msg' colspan='2'>
            <table class='inline-table' style='border-bottom:1px solid #fff;'>
                <tr>
                    <td class='lbl' rowspan='3'>$_vorop_lbl</td>
                    <td class='edt' rowspan='3' style='width:10%;'>$_vorop</td>
                    <td class='edt' colspan='2'>$_vorop_lok1_lbl</td>
                </tr>
                <tr>
                    <td class='edt'>$_vorop_lok1</td>
                    <td class='edt'>$_vorop_lok2</td>
                </tr>
                <tr>
                    <td class='edt'>$_vorop_lok3</td>
                    <td class='edt'>$_vorop_lok4</td>
                </tr>
            </table>
        </td>
    </tr>
"}
{html_set_html field="vorbestrahlung" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_vorbestrahlung_lbl</td>
            <td class='edt' style='width:10%;'>$_vorbestrahlung</td>
            <td class='edt'>$_vorbestrahlung_diagnose_lbl $_vorbestrahlung_diagnose</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_row field="platinresistenz" caption=$_platinresistenz_lbl input=$_platinresistenz}

{html_set_html field="vorop_uterus_zervix" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='subhead'>`$smarty.config.vorop`</td>
            <td class='subhead'></td>
            <td class='subhead' style='width:95px;text-align:center'>$_vorop_uterus_zervix_jahr_lbl</td>
            <td class='subhead' style='width:140px;'>$_vorop_uterus_zervix_erhaltung_lbl</td>
            <td class='subhead'>$_vorop_uterus_zervix_histologie_lbl</td>
         </tr>
         <tr>
            <td class='lbl'>$_vorop_uterus_zervix_lbl</td>
            <td class='edt' style='text-align:center;'>$_vorop_uterus_zervix</td>
            <td class='edt' style='text-align:center;'>$_vorop_uterus_zervix_jahr</td>
            <td class='edt'>$_vorop_uterus_zervix_erhaltung</td>
            <td class='edt'>$_vorop_uterus_zervix_histologie</td>
         </tr>
         <tr>
            <td class='lbl'>$_vorop_uterus_corpus_lbl</td>
            <td class='edt' style='text-align:center;'>$_vorop_uterus_corpus</td>
            <td class='edt' style='text-align:center;'>$_vorop_uterus_corpus_jahr</td>
            <td class='edt'>$_vorop_uterus_corpus_erhaltung</td>
            <td class='edt'>$_vorop_uterus_corpus_histologie</td>
         </tr>
         <tr>
            <td class='lbl'>$_vorop_ovar_r_lbl</td>
            <td class='edt' style='text-align:center;'>$_vorop_ovar_r</td>
            <td class='edt' style='text-align:center;'>$_vorop_ovar_r_jahr</td>
            <td class='edt'>$_vorop_ovar_r_erhaltung</td>
            <td class='edt'>$_vorop_ovar_r_histologie</td>
         </tr>
         <tr>
            <td class='lbl'>$_vorop_ovar_l_lbl</td>
            <td class='edt' style='text-align:center;'>$_vorop_ovar_l</td>
            <td class='edt' style='text-align:center;'>$_vorop_ovar_l_jahr</td>
            <td class='edt'>$_vorop_ovar_l_erhaltung</td>
            <td class='edt'>$_vorop_ovar_l_histologie</td>
         </tr>
         <tr>
            <td class='lbl'>$_vorop_adnexe_r_lbl</td>
            <td class='edt' style='text-align:center;'>$_vorop_adnexe_r</td>
            <td class='edt' style='text-align:center;'>$_vorop_adnexe_r_jahr</td>
            <td class='edt'>$_vorop_adnexe_r_erhaltung</td>
            <td class='edt'>$_vorop_adnexe_r_histologie</td>
         </tr>
         <tr>
            <td class='lbl'>$_vorop_adnexe_l_lbl</td>
            <td class='edt' style='text-align:center;'>$_vorop_adnexe_l</td>
            <td class='edt' style='text-align:center;'>$_vorop_adnexe_l_jahr</td>
            <td class='edt'>$_vorop_adnexe_l_erhaltung</td>
            <td class='edt'>$_vorop_adnexe_l_histologie</td>
         </tr>
         <tr>
            <td class='lbl'>$_vorop_vulva_lbl</td>
            <td class='edt' style='text-align:center;'>$_vorop_vulva</td>
            <td class='edt' style='text-align:center;'>$_vorop_vulva_jahr</td>
            <td class='edt'>$_vorop_vulva_erhaltung</td>
            <td class='edt'>$_vorop_vulva_histologie</td>
         </tr>
         <tr>
            <td class='lbl'>$_vorop_mamma_r_lbl</td>
            <td class='edt' style='text-align:center;'>$_vorop_mamma_r</td>
            <td class='edt' style='text-align:center;'>$_vorop_mamma_r_jahr</td>
            <td class='edt'>$_vorop_mamma_r_erhaltung</td>
            <td class='edt'>$_vorop_mamma_r_histologie</td>
         </tr>
         <tr>
            <td class='lbl'>$_vorop_mamma_l_lbl</td>
            <td class='edt' style='text-align:center;'>$_vorop_mamma_l</td>
            <td class='edt' style='text-align:center;'>$_vorop_mamma_l_jahr</td>
            <td class='edt'>$_vorop_mamma_l_erhaltung</td>
            <td class='edt'>$_vorop_mamma_l_histologie</td>
         </tr>
         <tr>
            <td class='lbl'>$_vorop_sonstige_lbl</td>
            <td class='edt' style='text-align:center;'>$_vorop_sonstige</td>
            <td class='edt' style='text-align:center;'>$_vorop_sonstige_jahr</td>
            <td class='edt' colspan='2'>$_vorop_sonstige_bem</td>
         </tr>
         </table>
      </td>
   </tr>
"}



{html_set_header field="hormon_sterilitaetsbehandlung" caption=#head_dauertherapien# class="head"}
{html_set_html field="hormon_sterilitaetsbehandlung" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_hormon_sterilitaetsbehandlung_lbl</td>
            <td class='edt' style='width:10%;'>$_hormon_sterilitaetsbehandlung</td>
            <td class='edt'> $_hormon_sterilitaetsbehandlung_dauer_lbl $_hormon_sterilitaetsbehandlung_dauer `$smarty.config.lbl_jahre`</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_html field="sonst" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table' style='border-top:1px solid #fff'>
         <tr>
            <td class='lbl' style='width:35%;'>$_sonst_lbl</td>
            <td class='edt' style='width:10%;'>$_sonst</td>
            <td class='edt'>$_sonst_dauer_lbl $_sonst_dauer `$smarty.config.lbl_jahre`</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{getView field='dlist_erkrankung'}
   {html_set_header caption=#head_best_erkrankung# class="head"}

   <tr>
      <td class="msg" colspan="2">
         <div class="dlist" id="dlist_erkrankung">
            <div class="add">
               <input class="button" type="button" name="anamnese_erkrankung" value="Hinzuf&uuml;gen" onclick="execute_request(this,'rec.anamnese_erkrankung', null, ['patient_id', 'anamnese_id', 'erkrankung_id'])"/>
            </div>
         </div>
      </td>
   </tr>
{/getView}

{html_set_header caption=#head_bem# class="head"}
{html_set_header field="bem" caption=$_bem      class="edt"}

</table>
{html_set_buttons modus=$button}

<div>
<input type="hidden" value="{$_anamnese_id_value}" name="form_id" />
{$_anamnese_id}
{$_patient_id}
{$_erkrankung_id}
</div>
