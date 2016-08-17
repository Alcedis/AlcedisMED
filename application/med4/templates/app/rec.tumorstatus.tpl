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

{if $preload_data == true}
	<table class="formtable">
   {html_set_header caption="Daten vorbelegen"  class="head"}
   <tr>
      <td class='lbl' colspan='2'>

         {if in_array($erkrankungData.code, array('b', 'lu', 'sst'))}
            Seite:
            <select name="preload_seite" class='input {if $preload_seite_err == true} imp-pat-error{/if}'>
               {html_options options=$side selected=$selectedSide}
            </select>
            {if in_array($erkrankungData.code, array('b', 'lu'))}
                <span style="font-family:Verdana, Arial;color:red">*</span>
            {/if}
         {/if}
         <input style="margin:0 5px; margin-left:20px" type="submit" class="button_large btnconfirm" name="action[preload_data]" value="{#lbl_preload#}" alt="{#lbl_preload#}"/>
      </td>
   </tr>
   </table>
{/if}

<table class="formtable">
{html_set_header caption=#head_tumor#  class="head"}
{html_set_row field="datum_beurteilung"   caption=$_datum_beurteilung_lbl  input=$_datum_beurteilung}
{html_set_row field="anlass"              caption=$_anlass_lbl             input=$_anlass}
{html_set_row field="datum_sicherung"     caption=$_datum_sicherung_lbl    input=$_datum_sicherung}
{html_set_row field="diagnosesicherung"   caption=$_diagnosesicherung_lbl  input=$_diagnosesicherung}

{html_set_html field="tumorausbreitung_lokal" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' rowspan='2'>`$smarty.config.lbl_bei_primaer`</td>
            <td class='edt'>$_tumorausbreitung_lokal $_tumorausbreitung_lokal_lbl</td>
            <td class='edt'>$_tumorausbreitung_lk $_tumorausbreitung_lk_lbl</td>
         </tr>
         <tr>
            <td class='edt'>$_tumorausbreitung_konausdehnung $_tumorausbreitung_konausdehnung_lbl</td>
            <td class='edt'>$_tumorausbreitung_fernmetastasen $_tumorausbreitung_fernmetastasen_lbl</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_html field="rezidiv_lokal" html="
    <tr>
        <td class='msg' colspan='2'>
            <table class='inline-table'>
                <tr>
                    <td style='border-top:1px solid #fff' class='lbl' rowspan='3'>`$smarty.config.lbl_bei_rezidiv`</td>
                    <td style='border-top:1px solid #fff' class='edt'>$_rezidiv_lokal $_rezidiv_lokal_lbl</td>
                    <td style='border-top:1px solid #fff' class='edt'>$_rezidiv_lk $_rezidiv_lk_lbl</td>
                    <td style='border-top:1px solid #fff' class='edt'>$_rezidiv_psa $_rezidiv_psa_lbl</td>
                </tr>
                <tr>
                    <td class='edt'>$_rezidiv_metastasen $_rezidiv_metastasen_lbl</td>
                    <td class='edt' colspan='2'>$_quelle_metastasen_lbl $_quelle_metastasen</td>
                </tr>
            </table>
        </td>
    </tr>
"}

{html_set_row field="mhrpc"   caption=$_mhrpc_lbl     input=$_mhrpc}

{html_set_row field="fall_vollstaendig"   caption=$_fall_vollstaendig_lbl     input=$_fall_vollstaendig}
{html_set_row field="zweittumor"          caption=$_zweittumor_lbl            input=$_zweittumor}
{html_set_row field="sicherungsgrad"      caption=$_sicherungsgrad_lbl        input=$_sicherungsgrad}

{html_set_row field="nur_zweitmeinung"          caption=$_nur_zweitmeinung_lbl      input=$_nur_zweitmeinung}
{html_set_row field="nur_diagnosesicherung"     caption=$_nur_diagnosesicherung_lbl input=$_nur_diagnosesicherung}
{html_set_row field="kein_fall"                 caption=$_kein_fall_lbl             input=$_kein_fall}

{html_set_header caption=#head_primaer#  class="head"}
{html_set_row field="zufall"                    caption=$_zufall_lbl                 input=$_zufall}
{html_set_row field="diagnose"                  caption=$_diagnose_lbl                 input=$_diagnose}
{html_set_row field="diagnose_c19_zuordnung"    caption=$_diagnose_c19_zuordnung_lbl   input=$_diagnose_c19_zuordnung}


{getView field='lokalisation'}
<tr>
   <td colspan="2" style="padding:0 !important">
      <div class="info-msg" style="margin-bottom:0 !important">
         {#info_metast#}
      </div>
   </td>
</tr>
{/getView}

{html_set_row field="lokalisation"           caption=$_lokalisation_lbl          input=$_lokalisation}
{html_set_row field="lokalisation_detail"    caption=$_lokalisation_detail_lbl   input=$_lokalisation_detail}
{html_set_row field="hoehe"                  caption=$_hoehe_lbl                 input=$_hoehe add=#lbl_cm#}
{html_set_row field="morphologie"            caption=$_morphologie_lbl           input=$_morphologie}
{html_set_html field="groesse_x" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
            <tr>
               <td class='lbl' style='width:35%;' >$_groesse_x_lbl</td>
               <td class='edt'>
                  $_groesse_x`$smarty.config.lbl_mm` &nbsp;&nbsp;
                  $_groesse_y_lbl &nbsp;&nbsp; $_groesse_y`$smarty.config.lbl_mm` &nbsp;&nbsp;
                  $_groesse_z_lbl &nbsp;&nbsp; $_groesse_z`$smarty.config.lbl_mm`<br/>
               </td>
            </tr>
         </table>
      </td>
   </tr>
"}
{html_set_row field="multizentrisch"      caption=$_multizentrisch_lbl     input=$_multizentrisch}
{html_set_row field="multifokal"          caption=$_multifokal_lbl         input=$_multifokal}
{html_set_row field="mikrokalk"           caption=$_mikrokalk_lbl          input=$_mikrokalk}
{html_set_row field="dcis_morphologie"    caption=$_dcis_morphologie_lbl   input=$_dcis_morphologie}

{html_set_row field="stadium_mason" caption=$_stadium_mason_lbl   input=$_stadium_mason}

{html_set_row field="gleason1" caption=$_gleason1_lbl   input="$_gleason1`$smarty.config.lbl_plus` $_gleason2"}

{html_set_row field="gleason3"          caption=$_gleason3_lbl          input=$_gleason3}
{html_set_row field="gleason4_anteil"   caption=$_gleason4_anteil_lbl   input=$_gleason4_anteil add=#lbl_prozent#}

{html_set_row field="eignung_nerverhalt" caption=$_eignung_nerverhalt_lbl   input=$_eignung_nerverhalt add="$_eignung_nerverhalt_seite_lbl $_eignung_nerverhalt_seite"}

{html_set_header caption=#head_lymph#  class="head" field="lk_entf,lk_bef,lk_staging,lk_sentinel_entf,lk_sentinel_bef"}
{html_set_row field="lk_entf"    caption=$_lk_entf_lbl    input=$_lk_entf}
{html_set_row field="lk_bef"     caption=$_lk_bef_lbl     input=$_lk_bef}
{html_set_row field="lk_staging" caption=$_lk_staging_lbl input=$_lk_staging}

</table>

{getView field='dlist_metastasen'}
<table class="formtable msg">
    {html_set_header caption=#head_metastasen#  class="head"}
    <tr style='border-bottom:1px solid #fff;'>
        <td class="msg" colspan="2">
            <div class="dlist" id="dlist_metastasen">
               <div class="add">
                  <input class="button" type="button" name="tumorstatus_metastasen" value="Hinzuf&uuml;gen" onclick="execute_request(this,'rec.tumorstatus_metastasen', null, ['patient_id', 'tumorstatus_id', 'erkrankung_id'])"/>
               </div>
            </div>
        </td>
    </tr>
</table>
{/getView}


<table class="formtable msg">
{html_set_header caption=#head_formel#  class="head"}
{html_set_row field="regressionsgrad"  caption=$_regressionsgrad_lbl input=$_regressionsgrad}
{html_set_html field="tnm_praefix,t,n,m,g,l,v,r,r_lokal,ppn" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table' style='margin-top:1px;'>
         <tr>
            <td class='lbl' style='width:35%;'>`$smarty.config.lbl_tnm`</td>
            <td class='edt'>
               $_tnm_praefix
               $_t_lbl $_t $_n_lbl $_n $_m_lbl $_m <br/><br/>
               $_g_lbl $_g $_l_lbl $_l $_v_lbl $_v $_r_lbl $_r $_r_lokal_lbl $_r_lokal $_ppn_lbl $_ppn $_s_lbl $_s
            </td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_row field="infiltration"  caption=$_infiltration_lbl  input=$_infiltration}
{html_set_row field="befallen_n"  caption=$_befallen_n_lbl  input=$_befallen_n}
{html_set_row field="befallen_m"  caption=$_befallen_m_lbl  input=$_befallen_m}

{html_set_row field="resektionsrand"    caption=$_resektionsrand_lbl   input=$_resektionsrand add=#lbl_mm#}
{html_set_row field="invasionstiefe"    caption=$_invasionstiefe_lbl   input=$_invasionstiefe add=#lbl_mm#}

{html_set_row field="ajcc"  caption=$_ajcc_lbl  input=$_ajcc}
{html_set_row field="figo"  caption=$_figo_lbl  input=$_figo}
{html_set_row field="uicc"  caption=$_uicc_lbl  input=$_uicc}
{html_set_row field="lugano"  caption=$_lugano_lbl  input=$_lugano}

{html_set_header field="nhl_who_b,nhl_who_t,hl_who,ann_arbor_stadium,ann_arbor_aktivitaetsgrad,ann_arbor_extralymphatisch,nhl_ipi,flipi,durie_salmon,iss,immun_phaenotyp" caption=#subhead_lymph#  class="subhead"}
{html_set_row field="nhl_who_b" caption=$_nhl_who_b_lbl input=$_nhl_who_b}
{html_set_row field="nhl_who_t" caption=$_nhl_who_t_lbl input=$_nhl_who_t}
{html_set_row field="hl_who"    caption=$_hl_who_lbl    input=$_hl_who}

{html_set_row field="ann_arbor_stadium"            caption=$_ann_arbor_stadium_lbl           input=$_ann_arbor_stadium}
{html_set_row field="ann_arbor_aktivitaetsgrad"    caption=$_ann_arbor_aktivitaetsgrad_lbl   input=$_ann_arbor_aktivitaetsgrad}
{html_set_row field="ann_arbor_extralymphatisch"   caption=$_ann_arbor_extralymphatisch_lbl  input=$_ann_arbor_extralymphatisch}

{html_set_row field="nhl_ipi"         caption=$_nhl_ipi_lbl         input=$_nhl_ipi}
{html_set_row field="flipi"           caption=$_flipi_lbl           input=$_flipi}
{html_set_row field="durie_salmon"    caption=$_durie_salmon_lbl    input=$_durie_salmon}
{html_set_row field="iss"             caption=$_iss_lbl             input=$_iss}
{html_set_row field="immun_phaenotyp" caption=$_immun_phaenotyp_lbl input=$_immun_phaenotyp}

{html_set_header field="cll_rai,cll_binet" caption=#subhead_cll#  class="subhead"}
{html_set_row field="cll_rai"       caption=$_cll_rai_lbl   input=$_cll_rai}
{html_set_row field="cll_binet"     caption=$_cll_binet_lbl input=$_cll_binet}

{html_set_header field="aml_fab,aml_who,all_egil" caption=#subhead_aml_all#  class="subhead"}
{html_set_row field="aml_fab"   caption=$_aml_fab_lbl  input=$_aml_fab}
{html_set_row field="aml_who"   caption=$_aml_who_lbl  input=$_aml_who}
{html_set_row field="all_egil"  caption=$_all_egil_lbl input=$_all_egil}

{html_set_header field="mds_fab,mds_who" caption=#subhead_mds#  class="subhead"}
{html_set_row field="mds_fab"   caption=$_mds_fab_lbl  input=$_mds_fab}
{html_set_row field="mds_who"   caption=$_mds_who_lbl  input=$_mds_who}

{html_set_row field="stadium_sclc"  caption=$_stadium_sclc_lbl   input=$_stadium_sclc}

{html_set_row field="risiko"   caption=$_risiko_lbl  input=$_risiko}

</table>
<table class="formtable msg">
{html_set_html field="risiko_mediastinaltumor,risiko_extranodalbefall,risiko_bks,risiko_lk" html="
   <tr style='border-top: 1px solid #fff;'>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:29.5% !important' rowspan='2'>`$smarty.config.lbl_risiko`</td>
            <td class='edt' >$_risiko_mediastinaltumor $_risiko_mediastinaltumor_lbl</td>
            <td class='edt'>$_risiko_extranodalbefall $_risiko_extranodalbefall_lbl</td>
         </tr>
         <tr>
            <td class='edt'>$_risiko_bks $_risiko_bks_lbl</td>
            <td class='edt'>$_risiko_lk $_risiko_lk_lbl</td>
         </tr>
         </table>
      </td>
   </tr>
"}
</table>

<table class="formtable msg">
{html_set_header field="estro,psa" class="head" colspan="7" caption=#head_parameter#}

{html_set_html field="estro" html="
<tr>
   <td class='subhead' align='center' colspan='2'>`$smarty.config.lbl_parameter`</td>
   <td class='subhead' align='center'>`$smarty.config.lbl_wert`</td>
   <td class='subhead' align='center'>$_estro_irs_lbl</td>
   <td class='subhead' align='center'>`$smarty.config.lbl_beurteilung`</td>
</tr>
"}

{html_set_html field="estro" html="
<tr>
   <td class='lbl' colspan='2'>$_estro_urteil_lbl</td>
   <td class='edt'>$_estro `$smarty.config.lbl_prozent`</td>
   <td class='edt'>$_estro_irs</td>
   <td class='edt'>$_estro_urteil</td>
</tr>
<tr>
   <td class='lbl' colspan='2'>$_prog_urteil_lbl</td>
   <td class='edt'>$_prog `$smarty.config.lbl_prozent`</td>
   <td class='edt'>$_prog_irs</td>
   <td class='edt'>$_prog_urteil</td>
</tr>
"}

{html_set_html field="psa,datum_psa" html="
<tr>
   <td class='subhead' align='center' colspan='3'>`$smarty.config.lbl_parameter`</td>
   <td class='subhead' align='center' colspan='3'>`$smarty.config.subhead_wert`</td>
   <td class='subhead' align='center' colspan='3'>`$smarty.config.subhead_datum`</td>
</tr>
<tr>
   <td class='lbl' colspan='3'>$_psa_lbl</td>
   <td class='edt' colspan='3'>$_psa `$smarty.config.psa_suffix`</td>
   <td class='edt' colspan='3'>$_datum_psa</td>
</tr>
"}
</table>

{html_set_html field="estro" html="
<table class='formtable msg'>
<tr>
   <td class='subhead' align='center' style='width:35%'>`$smarty.config.lbl_parameter`</td>
   <td class='subhead' align='center'>`$smarty.config.lbl_methode`</td>
   <td class='subhead' align='center'>`$smarty.config.lbl_wert`</td>
   <td class='subhead' align='center'>`$smarty.config.lbl_beurteilung`</td>
</tr>
<tr>
   <td class='lbl'>$_her2_methode_lbl</td>
   <td class='edt'>$_her2_methode</td>
   <td class='edt'>$_her2</td>
   <td class='edt' rowspan='2'>$_her2_urteil</td>
</tr>
<tr>
   <td class='lbl'>$_her2_fish_methode_lbl</td>
   <td class='edt'>$_her2_fish_methode</td>
   <td class='edt'>$_her2_fish</td>
</tr>
</table>
"}

<table class="formtable msg">
{html_set_header caption=#head_bem#  class="head"}
{html_set_header caption=$_bem  class="edt"}

</table>
{html_set_buttons modus=$button}

<div>
<input type="hidden" value="{$_tumorstatus_id_value}" name="form_id" />
<input type="hidden" value="{$preloaded}" name="preloaded" />
{$_tumorstatus_id}
{$_patient_id}
{$_erkrankung_id}
</div>
