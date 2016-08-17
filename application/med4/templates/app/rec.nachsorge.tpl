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
{html_set_header caption=#head_nachsorge#    class="head"}

<tr>
	<td class="lbl">{$smarty.config.lbl_erkrankung}</td>
	<td class="edt"><strong>{$erkrankungData.bez}</strong></td>
</tr>

{html_set_row field="datum"              caption=$_datum_lbl              input=$_datum}

{html_set_row field="org_id"             caption=$_org_id_lbl             input=$_org_id}
{html_set_row field="user_id"            caption=$_user_id_lbl            input=$_user_id}
{html_set_row field="ecog"               caption=$_ecog_lbl               input=$_ecog}
{html_set_row field="gewicht"            caption=$_gewicht_lbl            input=$_gewicht add=#lbl_kg#}
{html_set_row field="malignom"           caption=$_malignom_lbl           input=$_malignom}
{html_set_row field="nachsorge_biopsie"  caption=$_nachsorge_biopsie_lbl  input=$_nachsorge_biopsie}
{html_set_row field="empfehlung_befolgt" caption=$_empfehlung_befolgt_lbl input=$_empfehlung_befolgt}

{getView field='dlist_erkrankung'}
{html_set_header caption="Nachsorge für weitere Erkrankungen"    class="head"}
   <tr>
      <td class="msg" colspan="2">
         <div class="dlist" id="dlist_erkrankung">
            <div class="add">
               <input class="button" type="button" name="nachsorge_erkrankung" value="Hinzuf&uuml;gen" onclick="execute_request(this,'rec.nachsorge_erkrankung', null, ['patient_id', 'nachsorge_id'])"/>
            </div>
         </div>
      </td>
   </tr>
{/getView}
{html_set_header caption=#head_response#    class="head" field="tumormarkerverlauf,response_klinisch"}
{html_set_header class="msgbox"             caption=#info_labor#}
{html_set_row field="tumormarkerverlauf"    caption=$_tumormarkerverlauf_lbl     input=$_tumormarkerverlauf}
{html_set_row field="psa_bestimmt"          caption=$_psa_bestimmt_lbl           input=$_psa_bestimmt}
{html_set_row field="labor_id"              caption=$_labor_id_lbl               input=$_labor_id}
{html_set_html field="response_klinisch" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl'>$_response_klinisch_lbl</td>
            <td class='edt' style='width:11%;'>$_response_klinisch</td>
            <td class='edt'>
               $_response_klinisch_bestaetigt_lbl
               $_response_klinisch_bestaetigt
            </td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_header caption=#head_qualitaet#    class="head" field="hads_d_depression,hads_d_angst,umfang_unterarm,umfang_oberarm,armbeweglichkeit,ql,gz_dkg,lq_dkg,ipss,iief5,ics,iciq_ui,lcss,euroqol"}

{html_set_row field="euroqol"             caption=$_euroqol_lbl            input=$_euroqol add=#lbl_proz#}
{html_set_row field="lcss"                caption=$_lcss_lbl               input=$_lcss}
{html_set_row field="fb_dkg"              caption=$_fb_dkg_lbl             input=$_fb_dkg}
{html_set_row field="iciq_ui"             caption=$_iciq_ui_lbl            input=$_iciq_ui}
{html_set_row field="ics"                 caption=$_ics_lbl                input=$_ics}
{html_set_row field="iief5"               caption=$_iief5_lbl              input=$_iief5}
{html_set_row field="ipss"                caption=$_ipss_lbl               input=$_ipss}
{html_set_row field="lq_dkg"              caption=$_lq_dkg_lbl             input=$_lq_dkg}
{html_set_row field="gz_dkg"              caption=$_gz_dkg_lbl             input=$_gz_dkg}
{html_set_row field="ql"                  caption=$_ql_lbl                 input=$_ql}
{html_set_row field="armbeweglichkeit"    caption=$_armbeweglichkeit_lbl   input=$_armbeweglichkeit}
{html_set_row field="umfang_oberarm"      caption=$_umfang_oberarm_lbl     input=$_umfang_oberarm add=#lbl_cm#}
{html_set_row field="umfang_unterarm"     caption=$_umfang_unterarm_lbl    input=$_umfang_unterarm add=#lbl_cm#}
{html_set_row field="hads_d_depression"   caption=$_hads_d_depression_lbl  input=$_hads_d_depression}
{html_set_row field="hads_d_angst"        caption=$_hads_d_angst_lbl       input=$_hads_d_angst}

{html_set_header field="pde5hemmer" caption=#head_potenz_ma#    class="head"}
{html_set_row field="pde5hemmer"              caption=$_pde5hemmer_lbl              input=$_pde5hemmer}
{html_set_row field="pde5hemmer_haeufigkeit"  caption=$_pde5hemmer_haeufigkeit_lbl  input=$_pde5hemmer_haeufigkeit}
{html_set_row field="vakuumpumpe"             caption=$_vakuumpumpe_lbl             input=$_vakuumpumpe}
{html_set_row field="skat"                    caption=$_skat_lbl                    input=$_skat}
{html_set_row field="penisprothese"           caption=$_penisprothese_lbl           input=$_penisprothese}

{html_set_header caption=#head_sympt_tmb# field="sy_schmerzen,sy_schmerzen_lokalisation,sy_schmerzscore,sy_dyspnoe,sy_haemoptnoe,sy_husten,sy_harndrang,sy_nykturie,sy_pollakisurie,sy_miktion,sy_harnverhalt,sy_harnstau,sy_haematurie,sy_para_syndrom,sy_gewichtsverlust,sy_fieber,sy_nachtschweiss,sy_sonstige,sy_dauer,analgetika,schmerzmedikation_stufe,response_schmerztherapie,scapula_alata,lymphoedem,lymphdrainage,sensibilitaet,kontinenz,vorlagenverbrauch"    class="head"}
{html_set_row field="sy_schmerzen"                 caption=$_sy_schmerzen_lbl       input=$_sy_schmerzen}
{html_set_row field="sy_schmerzen_lokalisation"    caption=$_sy_schmerzen_lokalisation_lbl    input=$_sy_schmerzen_lokalisation}
{html_set_row field="sy_schmerzscore"              caption=$_sy_schmerzscore_lbl    input=$_sy_schmerzscore}
{html_set_row field="sy_dyspnoe"                   caption=$_sy_dyspnoe_lbl         input=$_sy_dyspnoe}
{html_set_row field="sy_haemoptnoe"                caption=$_sy_haemoptnoe_lbl      input=$_sy_haemoptnoe}
{html_set_html field="sy_husten" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_sy_husten_lbl</td>
            <td class='edt' style='width:15%;'>$_sy_husten</td>
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
            <td class='edt' style='width:15%;'>$_sy_miktion</td>
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
            <td class='edt' style='width:15%;'>$_sy_harnstau</td>
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
            <td class='edt' style='width:15%;'>$_sy_para_syndrom</td>
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
            <td class='edt'>
               $_sy_gewichtsverlust<br/>
               <div style='padding-top:3px'>$_sy_gewichtsverlust_2wo $_sy_gewichtsverlust_2wo_lbl</div>
               <div style='padding-top:3px'>$_sy_gewichtsverlust_3mo $_sy_gewichtsverlust_3mo_lbl</div>
            </td>
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
            <td class='lbl'>$_sy_dauer_lbl</td>
            <td class='edt'>$_sy_dauer $_sy_dauer_einheit</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_row field="analgetika"                caption=$_analgetika_lbl               input=$_analgetika}
{html_set_row field="schmerzmedikation_stufe"   caption=$_schmerzmedikation_stufe_lbl  input=$_schmerzmedikation_stufe}
{html_set_row field="response_schmerztherapie"  caption=$_response_schmerztherapie_lbl input=$_response_schmerztherapie}

{html_set_row field="scapula_alata"             caption=$_scapula_alata_lbl               input=$_scapula_alata}
{html_set_row field="lymphoedem"                caption=$_lymphoedem_lbl               input=$_lymphoedem add="<span style='padding-left:15px'>$_lymphoedem_seite_lbl $_lymphoedem_seite</span>"}
{html_set_row field="lymphdrainage"             caption=$_lymphdrainage_lbl               input=$_lymphdrainage}
{html_set_row field="sensibilitaet"             caption=$_sensibilitaet_lbl               input=$_sensibilitaet}

{html_set_row field="kontinenz"                 caption=$_kontinenz_lbl                input=$_kontinenz}
{html_set_row field="vorlagenverbrauch"         caption=$_vorlagenverbrauch_lbl        input=$_vorlagenverbrauch}

{html_set_header field="spaetschaden_blase" caption=#head_spaetschaeden#    class="head"}
{html_set_html field="spaetschaden_blase" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl'>$_spaetschaden_blase_lbl</td>
            <td class='edt' style='width:10%;'>$_spaetschaden_blase</td>
            <td class='edt' style='width:5%;' align='right'>$_spaetschaden_blase_grad_lbl</td>
            <td class='edt'>$_spaetschaden_blase_grad</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_html field="spaetschaden_rektum" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table' style='margin-top:1px;'>
         <tr>
            <td class='lbl'>$_spaetschaden_rektum_lbl</td>
            <td class='edt' style='width:10%;'>$_spaetschaden_rektum</td>
            <td class='edt' style='width:5%;' align='right'>$_spaetschaden_rektum_grad_lbl</td>
            <td class='edt'>$_spaetschaden_rektum_grad</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_header caption=#head_bem#    class="head"}
{html_set_header caption=$_bem         class="edt"}

</table>
{html_set_buttons modus=$button}

<div>
<input type="hidden" value="{$_nachsorge_id_value}" name="form_id" />
{$_nachsorge_id}
{$_patient_id}
</div>
