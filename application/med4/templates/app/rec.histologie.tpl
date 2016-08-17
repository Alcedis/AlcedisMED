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

<table class="formtable msg">

{html_set_header caption=#head_histologie# class="head"}
{html_set_row field="datum"               caption=$_datum_lbl              input=$_datum}
{html_set_row field="art"                 caption=$_art_lbl                input=$_art}
{html_set_row field="org_id"              caption=$_org_id_lbl             input=$_org_id}
{html_set_row field="user_id"             caption=$_user_id_lbl            input=$_user_id}
{html_set_row field="histologie_nr"       caption=$_histologie_nr_lbl      input=$_histologie_nr}
{html_set_row field="diagnose_seite"      caption=$_diagnose_seite_lbl     input=$_diagnose_seite}
{html_set_row field="eingriff_id"         caption=$_eingriff_id_lbl        input=$_eingriff_id}
{html_set_row field="untersuchung_id"     caption=$_untersuchung_id_lbl    input=$_untersuchung_id}
{html_set_row field="referenzpathologie"  caption=$_referenzpathologie_lbl input=$_referenzpathologie}
{html_set_row field="anzahl_praeparate"   caption=$_anzahl_praeparate_lbl  input=$_anzahl_praeparate}

{html_set_header caption=#head_befund_resektats# class="head" field="blasteninfiltration_prozent,blasteninfiltration,parametrienbefall_r,parametrienbefall_l,parametrienbefall_r_infiltration,parametrienbefall_l_infiltration,gleason1,ki67_index,ki67,mitoserate,kernpolymorphie,tubulusbildung,kapselueberschreitung,msi_stabilitaet,msi_mutation,msi,mercury,tumoranteil_turp,resektionsrand_lateral,resektionsrand_aboral,resektionsrand_oral,status_resektionsrand_circumferentiell,status_resektionsrand_organ,anz_rand_positiv,resektionsrand,invasionsbreite,invasionstiefe,ptnm_praefix,pt,pn,pm,g,l,v,r,ppn,konisation_exzision,konisation_x,konisation_y,konisation_z,unauffaellig,morphologie_erg3,morphologie_erg2,morphologie_erg1,morphologie,multifokal,prostatagewicht,multizentrisch,groesse_x,groesse_y,groesse_z"}

{getView field="groesse_x,groesse_y,groesse_z"}
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
            <tr>
               <td class='lbl' style='width:35%;' >
               {if $SESSION.sess_erkrankung_data.code === 'd'}
                  {#groesse_x_darm#}
               {else}
                  {$_groesse_x_lbl}
               {/if}
               </td>
               <td class='edt'>
                  {html_set_html field="groesse_x" html="$_groesse_x `$smarty.config.lbl_mm` &nbsp;"}

                  {html_set_html field="groesse_y" html="`$smarty.config.lbl_mult` &nbsp; $_groesse_y`$smarty.config.lbl_mm` &nbsp;"}

                  {html_set_html field="groesse_z" html="`$smarty.config.lbl_mult` &nbsp; $_groesse_z`$smarty.config.lbl_mm`"}
               </td>
            </tr>
         </table>
      </td>
   </tr>
{/getView}

{html_set_row field="prostatagewicht"  caption=$_prostatagewicht_lbl  input=$_prostatagewicht add=#lbl_g#}
{html_set_row field="multizentrisch"   caption=$_multizentrisch_lbl   input=$_multizentrisch}
{html_set_row field="multifokal"       caption=$_multifokal_lbl       input=$_multifokal}
{html_set_row field="morphologie"      caption=$_morphologie_lbl      input=$_morphologie}
{html_set_row field="morphologie_erg1" caption=$_morphologie_erg1_lbl input=$_morphologie_erg1}
{html_set_row field="morphologie_erg2" caption=$_morphologie_erg2_lbl input=$_morphologie_erg2}
{html_set_row field="morphologie_erg3" caption=$_morphologie_erg3_lbl input=$_morphologie_erg3}
{html_set_row field="unauffaellig"     caption=$_unauffaellig_lbl     input=$_unauffaellig}

{html_set_html field="ptnm_praefix,pt,pn,pm,g,l,v,r,ppn" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table' style='margin-top:1px;'>
         <tr>
            <td class='lbl' style='width:35%;'>`$smarty.config.lbl_tnm`</td>
            <td class='edt'>
               $_ptnm_praefix $_pt_lbl $_pt $_pn_lbl $_pn $_pm_lbl $_pm <br/><br/>
               $_g_lbl $_g $_l_lbl $_l $_v_lbl $_v $_r_lbl $_r $_ppn_lbl $_ppn
            </td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_row field="konisation_exzision" caption=$_konisation_exzision_lbl input=$_konisation_exzision}
{getView field="konisation_x,konisation_y,konisation_z"}
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
            <tr>
               <td class='lbl' style='width:35%;' >
               {$_konisation_x_lbl}
               </td>
               <td class='edt'>
                  {html_set_html field="konisation_x" html="$_konisation_x `$smarty.config.lbl_mm` &nbsp;"}
                  {html_set_html field="konisation_y" html="`$smarty.config.lbl_mult` &nbsp; $_konisation_y`$smarty.config.lbl_mm` &nbsp;"}
                  {html_set_html field="konisation_z" html="`$smarty.config.lbl_mult` &nbsp; $_konisation_z`$smarty.config.lbl_mm`"}
               </td>
            </tr>
         </table>
      </td>
   </tr>
{/getView}

{html_set_row field="invasionstiefe"   caption=$_invasionstiefe_lbl   input=$_invasionstiefe add=#lbl_mm#}
{html_set_row field="invasionsbreite"  caption=$_invasionsbreite_lbl  input=$_invasionsbreite add=#lbl_mm#}

{html_set_row field="resektionsrand"   caption=$_resektionsrand_lbl  input=$_resektionsrand add=#lbl_mm#}

{html_set_row field="anz_rand_positiv"                   caption=$_anz_rand_positiv_lbl                  input=$_anz_rand_positiv                  }
{html_set_row field="status_resektionsrand_organ"        caption=$_status_resektionsrand_organ_lbl       input=$_status_resektionsrand_organ       }

{html_set_row field="status_resektionsrand_circumferentiell"    caption=$_status_resektionsrand_circumferentiell_lbl   input=$_status_resektionsrand_circumferentiell}
{html_set_row field="resektionsrand_circumferentiell"    caption=$_resektionsrand_circumferentiell_lbl   input=$_resektionsrand_circumferentiell   add=#lbl_mm#}
{html_set_row field="resektionsrand_oral"                caption=$_resektionsrand_oral_lbl               input=$_resektionsrand_oral               add=#lbl_mm#}
{html_set_row field="resektionsrand_aboral"              caption=$_resektionsrand_aboral_lbl             input=$_resektionsrand_aboral             add=#lbl_mm#}
{html_set_row field="resektionsrand_lateral"             caption=$_resektionsrand_lateral_lbl            input=$_resektionsrand_lateral            add=#lbl_mm#}
{html_set_row field="tumoranteil_turp"                   caption=$_tumoranteil_turp_lbl                  input=$_tumoranteil_turp                  add=#lbl_prozent#}

{html_set_row field="mercury"   caption=$_mercury_lbl  input=$_mercury}
{html_set_html field="msi" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table' style='margin-top:1px;'>
         <tr>
            <td class='lbl' style='width:35%;'>$_msi_lbl</td>
            <td class='edt'>$_msi</td>
            <td class='edt'>$_msi_mutation_lbl $_msi_mutation</td>
            <td class='edt'>$_msi_stabilitaet_lbl $_msi_stabilitaet</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_row field="kapselueberschreitung"  caption=$_kapselueberschreitung_lbl input=$_kapselueberschreitung}
{html_set_row field="tubulusbildung"         caption=$_tubulusbildung_lbl        input=$_tubulusbildung}
{html_set_row field="kernpolymorphie"        caption=$_kernpolymorphie_lbl       input=$_kernpolymorphie}
{html_set_row field="mitoserate"             caption=$_mitoserate_lbl            input=$_mitoserate}

{html_set_html field="hpv" html="
<table class='formtable msg'>
"}

{html_set_row field="hpv" colspan="3" caption=$_hpv_lbl input=$_hpv}
{html_set_html field="hpv" html="
    <tr>
        <td class='lbl' rowspan='4'>`$smarty.config.hpv_typ01`</td>
    </tr>
    <tr>
        <td class='edt'>$_hpv_typ01 $_hpv_ergebnis01</td>
        <td class='edt' style='text-align:center'>$_hpv_typ02 $_hpv_ergebnis02</td>
        <td class='edt' style='text-align:center'>$_hpv_typ03 $_hpv_ergebnis03</td>
    </tr>
    <tr>
        <td class='edt'>$_hpv_typ04 $_hpv_ergebnis04</td>
        <td class='edt' style='text-align:center'>$_hpv_typ05 $_hpv_ergebnis05</td>
        <td class='edt' style='text-align:center'>$_hpv_typ06 $_hpv_ergebnis06</td>
    </tr>
    <tr>
        <td class='edt'>$_hpv_typ07 $_hpv_ergebnis07</td>
        <td class='edt' style='text-align:center'>$_hpv_typ08 $_hpv_ergebnis08</td>
        <td class='edt' style='text-align:center'>$_hpv_typ09 $_hpv_ergebnis09</td>
    </tr>
"}

{html_set_html field="hpv" html="
</table>
"}

{html_set_row field="ki67"                   caption=$_ki67_lbl                  input=$_ki67 add=#lbl_prozent#}
{html_set_row field="ki67_index"             caption=$_ki67_index_lbl            input=$_ki67_index}
{html_set_row field="gleason1"               caption=$_gleason1_lbl              input="$_gleason1$_gleason2_lbl $_gleason2"}
{html_set_row field="gleason3"               caption=$_gleason3_lbl              input=$_gleason3}
{html_set_row field="gleason4_anteil"        caption=$_gleason4_anteil_lbl       input=$_gleason4_anteil add=#lbl_prozent#}

{html_set_html field="parametrienbefall_r,parametrienbefall_l,parametrienbefall_r_infiltration,parametrienbefall_l_infiltration" html="
<tr>
   <td class='msg' colspan='2'>
      <table class='inline-table' style='margin-top:1px;'>
         <tr>
            <td class='lbl' rowspan='2'>`$smarty.config.lbl_parametrienbefall`</td>
            <td class='edt'>$_parametrienbefall_r $_parametrienbefall_r_lbl</td>
            <td class='edt'>$_parametrienbefall_r_infiltration_lbl</td>
            <td class='edt'>$_parametrienbefall_r_infiltration</td>
         </tr>
         <tr>
            <td class='edt'>$_parametrienbefall_l $_parametrienbefall_l_lbl</td>
            <td class='edt'>$_parametrienbefall_l_infiltration_lbl</td>
            <td class='edt'>$_parametrienbefall_l_infiltration</td>
         </tr>
      </table>
   </td>
</tr>
"}

{html_set_row field="blasteninfiltration" caption=$_blasteninfiltration_lbl input="$_blasteninfiltration&nbsp;&nbsp;&nbsp;$_blasteninfiltration_prozent_lbl $_blasteninfiltration_prozent" add=#lbl_prozent#}

{if $SESSION.sess_erkrankung_data.code === 'h'}
<tr>
   <td colspan='9' class='head'>{#head_histologie_einzel#}</td>
</tr>
<tr>
   <td class='msg' colspan='2'>
      <div class='dlist' id='dlist_einzelhistologie'>
         <div class='add'>
            <input class='button' type='button' name='histologie_einzel' value="Hinzuf&uuml;gen" onclick="execute_request(this,'rec.histologie_einzel', null, ['patient_id', 'histologie_id', 'erkrankung_id'])"/>
         </div>
      </div>
   </td>
</tr>
{/if}
</table>

{if $SESSION.sess_erkrankung_data.code === 'p'}
<table class="inline-table">
   {html_set_header caption=#head_histologie_stanze# class="head"}
</table>

{include file="app/histologie_stanze.tpl"}
{/if}

<table class="formtable msg">
{html_set_header caption=#head_loko_lymph# class="head" field="lk_sentinel_entf,lk_sentinel_bef"}

{html_set_row field="lk_sentinel_entf"             caption=$_lk_sentinel_entf_lbl             input=$_lk_sentinel_entf}
{html_set_row field="lk_sentinel_bef"              caption=$_lk_sentinel_bef_lbl             input=$_lk_sentinel_bef}
</table>

{html_set_html field="lk_12_entf,lk_12_bef_makro,lk_12_bef_mikro,lk_3_entf,lk_3_bef_makro,lk_3_bef_mikro,lk_ip_entf,lk_ip_bef_makro,lk_ip_bef_mikro,lk_bef_makro,lk_bef_mikro,lk_hilus_entf,lk_hilus_bef_mikro,lk_hilus_bef_makro,lk_interlobaer_entf,lk_interlobaer_bef_mikro,lk_interlobaer_bef_makro,lk_lobaer_entf,lk_lobaer_bef_mikro,lk_lobaer_bef_makro,lk_segmental_entf,lk_segmental_bef_mikro,lk_segmental_bef_makro,lk_lig_pul_entf,lk_lig_pul_bef_mikro,lk_lig_pul_bef_makro,lk_paraoeso_entf,lk_paraoeso_bef_mikro,lk_paraoeso_bef_makro,lk_subcarinal_entf,lk_subcarinal_bef_mikro,lk_subcarinal_bef_makro,lk_paraaortal_entf,lk_paraaortal_bef_mikro,lk_paraaortal_bef_makro,lk_subaortal_entf,lk_subaortal_bef_mikro,lk_subaortal_bef_makro,lk_unt_paratrach_entf,lk_unt_paratrach_bef_mikro,lk_unt_paratrach_bef_makro,lk_prae_retro_trach_entf,lk_prae_retro_trach_bef_mikro,lk_prae_retro_trach_bef_makro,lk_ob_paratrach_entf,lk_ob_paratrach_bef_mikro,lk_ob_paratrach_bef_makro,lk_mediastinum_entf,lk_mediastinum_bef_mikro,lk_mediastinum_bef_makro" html="
<table class='formtable msg'>
   <tr>
      <td class='subhead' style='width:35%'></td>
      <td class='subhead histologie-width-f'>`$smarty.config.lbl_entfernt`</td>
      <td class='subhead histologie-width-s'>`$smarty.config.lbl_makro_befall`</td>
      <td class='subhead'>`$smarty.config.lbl_mikro_befall`</td>
   </tr>
</table>
"}

{if $erkrankungData.code != 'p'}

{html_set_html field="lk_12_entf,lk_12_bef_makro,lk_12_bef_mikro,lk_3_entf,lk_3_bef_makro,lk_3_bef_mikro,lk_ip_entf,lk_ip_bef_makro,lk_ip_bef_mikro,lk_bef_makro,lk_bef_mikro" html="
	<table class='formtable msg'>
"}
   {html_set_html field="lk_12_entf,lk_12_bef_makro,lk_12_bef_mikro" html="
   <tr>
      <td class='lbl'>$_lk_12_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_12_entf</td>
      <td class='edt histologie-width-s'>$_lk_12_bef_makro</td>
      <td class='edt'>$_lk_12_bef_mikro</td>
   </tr>
   "}

   {html_set_html field="lk_3_entf,lk_3_bef_makro,lk_3_bef_mikr" html="
   <tr>
      <td class='lbl'>$_lk_3_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_3_entf</td>
      <td class='edt histologie-width-s'>$_lk_3_bef_makro</td>
      <td class='edt'>$_lk_3_bef_mikro</td>
   </tr>
   "}

   {html_set_html field="lk_ip_entf,lk_ip_bef_makro,lk_ip_bef_mikro" html="
   <tr>
      <td class='lbl'>$_lk_ip_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_ip_entf</td>
      <td class='edt histologie-width-s'>$_lk_ip_bef_makro</td>
      <td class='edt'>$_lk_ip_bef_mikro</td>
   </tr>
   "}


   {html_set_html field="lk_bef_makro,lk_bef_mikro" html="
   <tr>
      <td class='lbl'>`$smarty.config.lbl_gesamt`</td>
      <td class='edt histologie-width-f'>$_lk_entf</td>
      <td class='edt histologie-width-s'>$_lk_bef_makro</td>
      <td class='edt' >$_lk_bef_mikro</td>
   </tr>
   "}

{html_set_html field="lk_12_entf,lk_12_bef_makro,lk_12_bef_mikro,lk_3_entf,lk_3_bef_makro,lk_3_bef_mikro,lk_ip_entf,lk_ip_bef_makro,lk_ip_bef_mikro,lk_bef_makro,lk_bef_mikro" html="
	</table>
"}
{/if}

{html_set_html field="lk_hilus_entf,lk_hilus_bef_mikro,lk_hilus_bef_makro,lk_interlobaer_entf,lk_interlobaer_bef_mikro,lk_interlobaer_bef_makro,lk_lobaer_entf,lk_lobaer_bef_mikro,lk_lobaer_bef_makro,lk_segmental_entf,lk_segmental_bef_mikro,lk_segmental_bef_makro,lk_lig_pul_entf,lk_lig_pul_bef_mikro,lk_lig_pul_bef_makro,lk_paraoeso_entf,lk_paraoeso_bef_mikro,lk_paraoeso_bef_makro,lk_subcarinal_entf,lk_subcarinal_bef_mikro,lk_subcarinal_bef_makro,lk_paraaortal_entf,lk_paraaortal_bef_mikro,lk_paraaortal_bef_makro,lk_subaortal_entf,lk_subaortal_bef_mikro,lk_subaortal_bef_makro,lk_unt_paratrach_entf,lk_unt_paratrach_bef_mikro,lk_unt_paratrach_bef_makro,lk_prae_retro_trach_entf,lk_prae_retro_trach_bef_mikro,lk_prae_retro_trach_bef_makro,lk_ob_paratrach_entf,lk_ob_paratrach_bef_mikro,lk_ob_paratrach_bef_makro,lk_mediastinum_entf,lk_mediastinum_bef_mikro,lk_mediastinum_bef_makro" html="
	<table class='formtable msg'>
"}
   {html_set_html field="lk_hilus_entf,lk_hilus_bef_makro,lk_hilus_bef_mikro" html="
   <tr>
      <td class='lbl'>$_lk_hilus_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_hilus_entf</td>
      <td class='edt histologie-width-s'>$_lk_hilus_bef_makro</td>
      <td class='edt'>$_lk_hilus_bef_mikro</td>
   </tr>
   "}

   {html_set_html field="lk_interlobaer_entf,lk_interlobaer_bef_makro,lk_interlobaer_bef_mikro" html="
   <tr>
      <td class='lbl'>$_lk_interlobaer_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_interlobaer_entf</td>
      <td class='edt histologie-width-s'>$_lk_interlobaer_bef_makro</td>
      <td class='edt'>$_lk_interlobaer_bef_mikro</td>
   </tr>
   "}

   {html_set_html field="lk_lobaer_entf,lk_lobaer_bef_makro,lk_lobaer_bef_mikro" html="
   <tr>
      <td class='lbl'>$_lk_lobaer_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_lobaer_entf</td>
      <td class='edt histologie-width-s'>$_lk_lobaer_bef_makro</td>
      <td class='edt'>$_lk_lobaer_bef_mikro</td>
   </tr>
   "}

   {html_set_html field="lk_segmental_entf,lk_segmental_bef_makro,lk_segmental_bef_mikro" html="
   <tr>
      <td class='lbl'>$_lk_segmental_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_segmental_entf</td>
      <td class='edt histologie-width-s'>$_lk_segmental_bef_makro</td>
      <td class='edt'>$_lk_segmental_bef_mikro</td>
   </tr>
   "}

   {html_set_html field="lk_lig_pul_entf,lk_lig_pul_bef_makro,lk_lig_pul_bef_mikro" html="
   <tr>
      <td class='lbl'>$_lk_lig_pul_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_lig_pul_entf</td>
      <td class='edt histologie-width-s'>$_lk_lig_pul_bef_makro</td>
      <td class='edt'>$_lk_lig_pul_bef_mikro</td>
   </tr>
   "}

   {html_set_html field="lk_paraoeso_entf,lk_paraoeso_bef_makro,lk_paraoeso_bef_mikro" html="
   <tr>
      <td class='lbl'>$_lk_paraoeso_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_paraoeso_entf</td>
      <td class='edt histologie-width-s'>$_lk_paraoeso_bef_makro</td>
      <td class='edt'>$_lk_paraoeso_bef_mikro</td>
   </tr>
   "}

   {html_set_html field="lk_subcarinal_entf,lk_subcarinal_bef_makro,lk_subcarinal_bef_mikro" html="
   <tr>
      <td class='lbl'>$_lk_subcarinal_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_subcarinal_entf</td>
      <td class='edt histologie-width-s'>$_lk_subcarinal_bef_makro</td>
      <td class='edt'>$_lk_subcarinal_bef_mikro</td>
   </tr>
   "}

   {html_set_html field="lk_paraaortal_entf,lk_paraaortal_bef_makro,lk_paraaortal_bef_mikro" html="
   <tr>
      <td class='lbl'>$_lk_paraaortal_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_paraaortal_entf</td>
      <td class='edt histologie-width-s'>$_lk_paraaortal_bef_makro</td>
      <td class='edt'>$_lk_paraaortal_bef_mikro</td>
   </tr>
   "}

   {html_set_html field="lk_subaortal_entf,lk_subaortal_bef_makro,lk_subaortal_bef_mikro" html="
   <tr>
      <td class='lbl'>$_lk_subaortal_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_subaortal_entf</td>
      <td class='edt histologie-width-s'>$_lk_subaortal_bef_makro</td>
      <td class='edt'>$_lk_subaortal_bef_mikro</td>
   </tr>
   "}

   {html_set_html field="lk_unt_paratrach_entf,lk_unt_paratrach_bef_makro,lk_unt_paratrach_bef_mikro" html="
   <tr>
      <td class='lbl'>$_lk_unt_paratrach_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_unt_paratrach_entf</td>
      <td class='edt histologie-width-s'>$_lk_unt_paratrach_bef_makro</td>
      <td class='edt'>$_lk_unt_paratrach_bef_mikro</td>
   </tr>
   "}

   {html_set_html field="lk_prae_retro_trach_entf,lk_prae_retro_trach_bef_makro,lk_prae_retro_trach_bef_mikro" html="
   <tr>
      <td class='lbl'>$_lk_prae_retro_trach_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_prae_retro_trach_entf</td>
      <td class='edt histologie-width-s'>$_lk_prae_retro_trach_bef_makro</td>
      <td class='edt'>$_lk_prae_retro_trach_bef_mikro</td>
   </tr>
   "}

   {html_set_html field="lk_ob_paratrach_entf,lk_ob_paratrach_bef_makro,lk_ob_paratrach_bef_mikro" html="
   <tr>
      <td class='lbl'>$_lk_ob_paratrach_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_ob_paratrach_entf</td>
      <td class='edt histologie-width-s'>$_lk_ob_paratrach_bef_makro</td>
      <td class='edt'>$_lk_ob_paratrach_bef_mikro</td>
   </tr>
   "}

   {html_set_html field="lk_mediastinum_entf,lk_mediastinum_bef_makro,lk_mediastinum_bef_mikro" html="
   <tr>
      <td class='lbl'>$_lk_mediastinum_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_mediastinum_entf</td>
      <td class='edt histologie-width-s'>$_lk_mediastinum_bef_makro</td>
      <td class='edt'>$_lk_mediastinum_bef_mikro</td>
   </tr>
   "}

{html_set_html field="lk_hilus_entf,lk_hilus_bef_mikro,lk_hilus_bef_makro,lk_interlobaer_entf,lk_interlobaer_bef_mikro,lk_interlobaer_bef_makro,lk_lobaer_entf,lk_lobaer_bef_mikro,lk_lobaer_bef_makro,lk_segmental_entf,lk_segmental_bef_mikro,lk_segmental_bef_makro,lk_lig_pul_entf,lk_lig_pul_bef_mikro,lk_lig_pul_bef_makro,lk_paraoeso_entf,lk_paraoeso_bef_mikro,lk_paraoeso_bef_makro,lk_subcarinal_entf,lk_subcarinal_bef_mikro,lk_subcarinal_bef_makro,lk_paraaortal_entf,lk_paraaortal_bef_mikro,lk_paraaortal_bef_makro,lk_subaortal_entf,lk_subaortal_bef_mikro,lk_subaortal_bef_makro,lk_unt_paratrach_entf,lk_unt_paratrach_bef_mikro,lk_unt_paratrach_bef_makro,lk_prae_retro_trach_entf,lk_prae_retro_trach_bef_mikro,lk_prae_retro_trach_bef_makro,lk_ob_paratrach_entf,lk_ob_paratrach_bef_mikro,lk_ob_paratrach_bef_makro,lk_mediastinum_entf,lk_mediastinum_bef_mikro,lk_mediastinum_bef_makro" html="
</table>
"}

{html_set_html field="lk_l_entf,lk_l_bef_makro,lk_l_bef_mikro,lk_r_entf,lk_r_entf,lk_r_bef_makro,lk_r_bef_mikro" html="
<table class='formtable msg'>
"}
   {html_set_html field="lk_l_entf,lk_l_bef_makro,lk_l_bef_mikro" html="
   <tr>
      <td class='lbl'>$_lk_l_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_l_entf</td>
      <td class='edt histologie-width-s'>$_lk_l_bef_makro</td>
      <td class='edt'>$_lk_l_bef_mikro</td>
   </tr>
   "}

   {html_set_html field="lk_r_entf,lk_r_bef_makro,lk_r_bef_mikro" html="
   <tr>
      <td class='lbl'>$_lk_r_entf_lbl</td>
      <td class='edt histologie-width-f'>$_lk_r_entf</td>
      <td class='edt histologie-width-s'>$_lk_r_bef_makro</td>
      <td class='edt'>$_lk_r_bef_mikro</td>
   </tr>
   "}

{html_set_html field="lk_l_entf,lk_l_bef_makro,lk_l_bef_mikro,lk_r_entf,lk_r_entf,lk_r_bef_makro,lk_r_bef_mikro" html="
</table>
"}


{if $erkrankungData.code == 'p'}
	<table class='formtable msg'>
	   {html_set_html field="lk_bef_makro,lk_bef_mikro" html="
	   <tr>
	      <td class='lbl'>`$smarty.config.lbl_gesamt`</td>
	      <td class='edt histologie-width-f'>$_lk_entf</td>
	      <td class='edt histologie-width-s'>$_lk_bef_makro</td>
	      <td class='edt' >$_lk_bef_mikro</td>
	   </tr>
	   "}
	</table>
{/if}



{html_set_html field="lk_pelvin_entf,lk_pelvin_bef,lk_para_entf,lk_para_bef,lk_inguinal_l_entf,lk_inguinal_l_bef,lk_inguinal_r_entf,lk_inguinal_r_bef,lk_andere1,lk_andere1_entf,lk_andere1_bef,lk_andere2,lk_andere2_entf,lk_andere2_bef,lk_pelvin_externa_l_entf,lk_pelvin_externa_l_bef,lk_pelvin_interna_l_entf,lk_pelvin_interna_l_bef,lk_pelvin_fossa_l_entf,lk_pelvin_fossa_l_bef,lk_pelvin_communis_l_entf,lk_pelvin_communis_l_bef,lk_pelvin_externa_r_entf,lk_pelvin_externa_r_bef,lk_pelvin_interna_r_entf,lk_pelvin_interna_r_bef,lk_pelvin_fossa_r_entf,lk_pelvin_fossa_r_bef,lk_pelvin_communis_r_entf,lk_pelvin_communis_r_bef,lk_para_paracaval_entf,lk_para_paracaval_bef,lk_para_interaortocaval_entf,lk_para_interaortocaval_bef,lk_para_cranial_ami_entf,lk_para_cranial_ami_bef,lk_para_caudal_ami_entf,lk_para_caudal_ami_bef,lk_para_cranial_vr_entf,lk_para_cranial_vr_bef" html="
<table class='formtable msg'>
   <tr>
      <td class='subhead'></td>
      <td class='subhead'>`$smarty.config.lbl_entfernt`</td>
      <td class='subhead'>`$smarty.config.lbl_befallen`</td>
   </tr>
"}

   {html_set_html field="lk_pelvin_entf,lk_pelvin_bef" html="
   <tr>
      <td class='lbl'>$_lk_pelvin_entf_lbl</td>
      <td class='edt'>$_lk_pelvin_entf</td>
      <td class='edt'>$_lk_pelvin_bef</td>
   </tr>
   "}

   {html_set_html field="lk_para_entf,lk_para_bef" html="
   <tr>
      <td class='lbl'>$_lk_para_entf_lbl</td>
      <td class='edt'>$_lk_para_entf</td>
      <td class='edt'>$_lk_para_bef</td>
   </tr>
   "}

   {html_set_html field="lk_inguinal_l_entf,lk_inguinal_l_bef" html="
   <tr>
      <td class='lbl'>$_lk_inguinal_l_entf_lbl</td>
      <td class='edt'>$_lk_inguinal_l_entf</td>
      <td class='edt'>$_lk_inguinal_l_bef</td>
   </tr>
   "}

   {html_set_html field="lk_inguinal_r_entf,lk_inguinal_r_bef" html="
   <tr>
      <td class='lbl'>$_lk_inguinal_r_entf_lbl</td>
      <td class='edt'>$_lk_inguinal_r_entf</td>
      <td class='edt'>$_lk_inguinal_r_bef</td>
   </tr>
   "}

   {html_set_html field="lk_pelvin_externa_l_entf,lk_pelvin_externa_l_bef" html="
   <tr>
      <td class='lbl'>$_lk_pelvin_externa_l_entf_lbl</td>
      <td class='edt'>$_lk_pelvin_externa_l_entf</td>
      <td class='edt'>$_lk_pelvin_externa_l_bef</td>
   </tr>
   "}

   {html_set_html field="lk_pelvin_interna_l_entf,lk_pelvin_interna_l_bef" html="
   <tr>
      <td class='lbl'>$_lk_pelvin_interna_l_entf_lbl</td>
      <td class='edt'>$_lk_pelvin_interna_l_entf</td>
      <td class='edt'>$_lk_pelvin_interna_l_bef</td>
   </tr>
   "}

   {html_set_html field="lk_pelvin_fossa_l_entf,lk_pelvin_fossa_l_bef" html="
   <tr>
      <td class='lbl'>$_lk_pelvin_fossa_l_entf_lbl</td>
      <td class='edt'>$_lk_pelvin_fossa_l_entf</td>
      <td class='edt'>$_lk_pelvin_fossa_l_bef</td>
   </tr>
   "}

   {html_set_html field="lk_pelvin_communis_l_entf,lk_pelvin_communis_l_bef" html="
   <tr>
      <td class='lbl'>$_lk_pelvin_communis_l_entf_lbl</td>
      <td class='edt'>$_lk_pelvin_communis_l_entf</td>
      <td class='edt'>$_lk_pelvin_communis_l_bef</td>
   </tr>
   "}

   {html_set_html field="lk_pelvin_externa_r_entf,lk_pelvin_externa_r_bef" html="
   <tr>
      <td class='lbl'>$_lk_pelvin_externa_r_entf_lbl</td>
      <td class='edt'>$_lk_pelvin_externa_r_entf</td>
      <td class='edt'>$_lk_pelvin_externa_r_bef</td>
   </tr>
   "}

   {html_set_html field="lk_pelvin_interna_r_entf,lk_pelvin_interna_r_bef" html="
   <tr>
      <td class='lbl'>$_lk_pelvin_interna_r_entf_lbl</td>
      <td class='edt'>$_lk_pelvin_interna_r_entf</td>
      <td class='edt'>$_lk_pelvin_interna_r_bef</td>
   </tr>
   "}

   {html_set_html field="lk_pelvin_fossa_r_entf,lk_pelvin_fossa_r_bef" html="
   <tr>
      <td class='lbl'>$_lk_pelvin_fossa_r_entf_lbl</td>
      <td class='edt'>$_lk_pelvin_fossa_r_entf</td>
      <td class='edt'>$_lk_pelvin_fossa_r_bef</td>
   </tr>
   "}

   {html_set_html field="lk_pelvin_communis_r_entf,lk_pelvin_communis_r_bef" html="
   <tr>
      <td class='lbl'>$_lk_pelvin_communis_r_entf_lbl</td>
      <td class='edt'>$_lk_pelvin_communis_r_entf</td>
      <td class='edt'>$_lk_pelvin_communis_r_bef</td>
   </tr>
   "}

   {html_set_html field="lk_para_paracaval_entf,lk_para_paracaval_bef" html="
   <tr>
      <td class='lbl'>$_lk_para_paracaval_entf_lbl</td>
      <td class='edt'>$_lk_para_paracaval_entf</td>
      <td class='edt'>$_lk_para_paracaval_bef</td>
   </tr>
   "}

   {html_set_html field="lk_para_interaortocaval_entf,lk_para_interaortocaval_bef" html="
   <tr>
      <td class='lbl'>$_lk_para_interaortocaval_entf_lbl</td>
      <td class='edt'>$_lk_para_interaortocaval_entf</td>
      <td class='edt'>$_lk_para_interaortocaval_bef</td>
   </tr>
   "}

   {html_set_html field="lk_para_cranial_ami_entf,lk_para_cranial_ami_bef" html="
   <tr>
      <td class='lbl'>$_lk_para_cranial_ami_entf_lbl</td>
      <td class='edt'>$_lk_para_cranial_ami_entf</td>
      <td class='edt'>$_lk_para_cranial_ami_bef</td>
   </tr>
   "}

   {html_set_html field="lk_para_caudal_ami_entf,lk_para_caudal_ami_bef" html="
   <tr>
      <td class='lbl'>$_lk_para_caudal_ami_entf_lbl</td>
      <td class='edt'>$_lk_para_caudal_ami_entf</td>
      <td class='edt'>$_lk_para_caudal_ami_bef</td>
   </tr>
   "}

   {html_set_html field="lk_para_cranial_vr_entf,lk_para_cranial_vr_bef" html="
   <tr>
      <td class='lbl'>$_lk_para_cranial_vr_entf_lbl</td>
      <td class='edt'>$_lk_para_cranial_vr_entf</td>
      <td class='edt'>$_lk_para_cranial_vr_bef</td>
   </tr>
   "}

   {html_set_html field="lk_andere1,lk_andere1_entf,lk_andere1_bef" html="
   <tr>
      <td class='lbl'>$_lk_andere1</td>
      <td class='edt'>$_lk_andere1_entf</td>
      <td class='edt'>$_lk_andere1_bef</td>
   </tr>
   "}

   {html_set_html field="lk_andere2,lk_andere2_entf,lk_andere2_bef" html="
   <tr>
      <td class='lbl'>$_lk_andere2</td>
      <td class='edt'>$_lk_andere2_entf</td>
      <td class='edt'>$_lk_andere2_bef</td>
   </tr>
   "}

{html_set_html field="lk_pelvin_entf,lk_pelvin_bef,lk_para_entf,lk_para_bef,lk_inguinal_l_entf,lk_inguinal_l_bef,lk_inguinal_r_entf,lk_inguinal_r_bef,lk_andere1,lk_andere1_entf,lk_andere1_bef,lk_andere2,lk_andere2_entf,lk_andere2_bef,lk_pelvin_externa_l_entf,lk_pelvin_externa_l_bef,lk_pelvin_interna_l_entf,lk_pelvin_interna_l_bef,lk_pelvin_fossa_l_entf,lk_pelvin_fossa_l_bef,lk_pelvin_communis_l_entf,lk_pelvin_communis_l_bef,lk_pelvin_externa_r_entf,lk_pelvin_externa_r_bef,lk_pelvin_interna_r_entf,lk_pelvin_interna_r_bef,lk_pelvin_fossa_r_entf,lk_pelvin_fossa_r_bef,lk_pelvin_communis_r_entf,lk_pelvin_communis_r_bef,lk_para_paracaval_entf,lk_para_paracaval_bef,lk_para_interaortocaval_entf,lk_para_interaortocaval_bef,lk_para_cranial_ami_entf,lk_para_cranial_ami_bef,lk_para_caudal_ami_entf,lk_para_caudal_ami_bef,lk_para_cranial_vr_entf,lk_para_cranial_vr_bef" html="
</table>
"}

{html_set_html field="lk_inguinal_entf" html="
<table class='formtable msg'>
   <tr>
      <td class='subhead'></td>
      <td class='subhead' style='text-align:center;'>`$smarty.config.lbl_entfernt`</td>
      <td class='subhead' style='text-align:center;'>`$smarty.config.lbl_befallen`</td>
      <td class='subhead' style='text-align:center;'>`$smarty.config.lbl_makro`</td>
      <td class='subhead' style='text-align:center;'>`$smarty.config.lbl_mikro`</td>
   </tr>
   <tr>
      <td class='lbl'>$_lk_inguinal_entf_lbl</td>
      <td class='edt' style='text-align:center;'>$_lk_inguinal_entf</td>
      <td class='edt' style='text-align:center;'>$_lk_inguinal_bef</td>
      <td class='edt' style='text-align:center;'>$_lk_inguinal_makro</td>
      <td class='edt' style='text-align:center;'>$_lk_inguinal_mikro</td>
   </tr>
   <tr>
      <td class='lbl'>$_lk_iliakal_entf_lbl</td>
      <td class='edt' style='text-align:center;'>$_lk_iliakal_entf</td>
      <td class='edt' style='text-align:center;'>$_lk_iliakal_bef</td>
      <td class='edt' style='text-align:center;'>$_lk_iliakal_makro</td>
      <td class='edt' style='text-align:center;'>$_lk_iliakal_mikro</td>
   </tr>
   <tr>
      <td class='lbl'>$_lk_axillaer_entf_lbl</td>
      <td class='edt' style='text-align:center;'>$_lk_axillaer_entf</td>
      <td class='edt' style='text-align:center;'>$_lk_axillaer_bef</td>
      <td class='edt' style='text-align:center;'>$_lk_axillaer_makro</td>
      <td class='edt' style='text-align:center;'>$_lk_axillaer_mikro</td>
   </tr>
   <tr>
      <td class='lbl'>$_lk_zervikal_entf_lbl</td>
      <td class='edt' style='text-align:center;'>$_lk_zervikal_entf</td>
      <td class='edt' style='text-align:center;'>$_lk_zervikal_bef</td>
      <td class='edt' style='text-align:center;'>$_lk_zervikal_makro</td>
      <td class='edt' style='text-align:center;'>$_lk_zervikal_mikro</td>
   </tr>
</table>
"}

{html_set_html field="lk_entf,lk_bef,lk_mikrometastasen,groesste_ausdehnung,kapseldurchbruch" html="
	<table class='formtable msg'>
"}

	{if in_array($erkrankungData.code, array('p', 'b')) == false}
		{html_set_html field="lk_entf,lk_bef,lk_mikrometastasen,groesste_ausdehnung,kapseldurchbruch" html="
			<tr>
				<td colspan='3' class='subhead'>`$smarty.config.subhead_zusammenfassung`</td>
			</tr>
		"}
	{/if}

	{if strlen($_lk_bef_makro) == 0}
		{if in_array($erkrankungData.code, array('gt')) == false}
			{html_set_row field="lk_entf"  caption=$_lk_entf_lbl   input=$_lk_entf}
			{html_set_row field="lk_bef"  caption=$_lk_bef_lbl   input=$_lk_bef}
	   {else}
	   	{html_set_html field="lk_entf,lk_bef" html="
		   <tr>
		      <td class='lbl'><span class='highlight-feature'>`$smarty.config.lbl_lk_gesamt`</span></td>
		      <td class='edt'>$_lk_entf</td>
		      <td class='edt'>$_lk_bef</td>
		   </tr>
		   "}
	   {/if}
	{else}
		{html_set_row field="lk_bef"  caption=$_lk_bef_lbl   input=$_lk_bef}
	{/if}

{html_set_row field="lk_mikrometastasen" colspan="2"  caption=$_lk_mikrometastasen_lbl input=$_lk_mikrometastasen}
{html_set_row field="groesste_ausdehnung" colspan="2"  caption=$_groesste_ausdehnung_lbl input=$_groesste_ausdehnung add=#lbl_mm#}
{html_set_row field="kapseldurchbruch" colspan="2"  caption=$_kapseldurchbruch_lbl input=$_kapseldurchbruch}

{html_set_html field="lk_entf,lk_bef,lk_mikrometastasen,groesste_ausdehnung,kapseldurchbruch" html="
	</table>
"}

<table class='formtable msg'>
{html_set_header field="estro,estro_irs,estro_urteil,prog,prog_irs,prog_urteil,her2,her2_methode,her2_fish,her2_fish_methode,her2_urteil,pai1,psa,upa,egf,vegf,chromogranin,kras,braf,egfr,egfr_mutation,nse,ercc1,ttf1,alk,ros,pcna,epca2,p53,ps2,kathepsin_d,hmb45,melan_a,s100" caption=#head_tmmrk_und_rzpt# class="head" colspan="5"}

{html_set_html field="estro,estro_irs,estro_urteil,prog,prog_irs,prog_urteil" html="
<tr>
   <td class='subhead' align='center' colspan='2'>`$smarty.config.lbl_parameter`</td>
   <td class='subhead' align='center'>`$smarty.config.lbl_wert`</td>
   <td class='subhead' align='center'>$_estro_irs_lbl</td>
   <td class='subhead' align='center'>`$smarty.config.lbl_beurteilung`</td>
</tr>
"}

{html_set_html field="estro,estro_urteil,estro_irs" html="
<tr>
   <td class='lbl' colspan='2'>$_estro_lbl</td>
   <td class='edt'>$_estro `$smarty.config.lbl_prozent`</td>
   <td class='edt'>$_estro_irs</td>
   <td class='edt'>$_estro_urteil</td>
</tr>
"}
{html_set_html field="prog,prog_urteil,prog_irs" html="
<tr>
   <td class='lbl' colspan='2'>$_prog_lbl</td>
   <td class='edt'>$_prog `$smarty.config.lbl_prozent`</td>
   <td class='edt'>$_prog_irs</td>
   <td class='edt'>$_prog_urteil</td>
</tr>
"}

{html_set_html field="estro,estro_irs,estro_urteil,prog,prog_irs,prog_urteil,her2,her2_methode,her2_fish,her2_fish_methode,her2_urteil" html="
</table>
"}

{html_set_html field="her2_methode,her2,her2_fish_methode,her2_fish" html="
<table class='formtable msg'>
<tr>
   <td class='subhead' align='center' style='width:35%'>`$smarty.config.lbl_parameter`</td>
   <td class='subhead' align='center'>`$smarty.config.lbl_methode`</td>
   <td class='subhead' align='center'>`$smarty.config.lbl_wert`</td>
   <td class='subhead' align='center'>`$smarty.config.lbl_beurteilung`</td>
</tr>
"}

{html_set_html field="her2_methode,her2,her2_urteil" html="
<tr>
   <td class='lbl'>$_her2_methode_lbl</td>
   <td class='edt'>$_her2_methode</td>
   <td class='edt'>$_her2</td>
   <td class='edt' rowspan='2'>$_her2_urteil</td>
</tr>
"}

{html_set_html field="her2_fish_methode,her2_fish" html="
<tr>
   <td class='lbl'>$_her2_fish_methode_lbl</td>
   <td class='edt'>$_her2_fish_methode</td>
   <td class='edt'>$_her2_fish</td>
</tr>
"}
</table>

<table class='formtable msg'>
{html_set_row field="pai1"                caption=$_pai1_lbl               input=$_pai1}
{html_set_row field="upa"                 caption=$_upa_lbl                input=$_upa}
{html_set_row field="egf"                 caption=$_egf_lbl                input=$_egf}
{html_set_row field="vegf"                caption=$_vegf_lbl               input=$_vegf}
{html_set_row field="chromogranin"        caption=$_chromogranin_lbl       input=$_chromogranin}
{html_set_row field="kras"                caption=$_kras_lbl               input=$_kras}
{html_set_row field="braf"                caption=$_braf_lbl               input=$_braf}
{html_set_row field="egfr"                caption=$_egfr_lbl               input=$_egfr}
{html_set_row field="egfr_mutation"       caption=$_egfr_mutation_lbl      input=$_egfr_mutation}
{html_set_row field="nse"                 caption=$_nse_lbl                input=$_nse}
{html_set_row field="ercc1"               caption=$_ercc1_lbl              input=$_ercc1}
{html_set_row field="ttf1"                caption=$_ttf1_lbl               input=$_ttf1}
{html_set_row field="alk"                 caption=$_alk_lbl                input=$_alk}
{html_set_row field="ros"                 caption=$_ros_lbl                input=$_ros}
{html_set_row field="psa"                 caption=$_psa_lbl                input=$_psa}
{html_set_row field="pcna"                caption=$_pcna_lbl               input=$_pcna}
{html_set_row field="epca2"               caption=$_epca2_lbl              input=$_epca2}
{html_set_row field="p53"                 caption=$_p53_lbl                input=$_p53}
{html_set_row field="ps2"                 caption=$_ps2_lbl                input=$_ps2}
{html_set_row field="kathepsin_d"         caption=$_kathepsin_d_lbl        input=$_kathepsin_d}
{html_set_row field="hmb45"               caption=$_hmb45_lbl              input=$_hmb45}
{html_set_row field="melan_a"             caption=$_melan_a_lbl            input=$_melan_a}
{html_set_row field="s100"                caption=$_s100_lbl               input=$_s100}

{html_set_header field="dcis_grading,dcis_groesse,dcis_resektionsrand,dcis_van_nuys,dcis_vnpi,dcis_morphologie,dcis_kerngrading,dcis_nekrosen"     caption=#head_dcis_angaben#         class="head"}
{html_set_row field="dcis_grading"        caption=$_dcis_grading_lbl          input=$_dcis_grading}
{html_set_row field="dcis_groesse"        caption=$_dcis_groesse_lbl          input=$_dcis_groesse add=#lbl_mm#}
{html_set_row field="dcis_resektionsrand" caption=$_dcis_resektionsrand_lbl   input=$_dcis_resektionsrand add=#lbl_mm#}
{html_set_row field="dcis_van_nuys"       caption=$_dcis_van_nuys_lbl         input=$_dcis_van_nuys}
{html_set_row field="dcis_vnpi"           caption=$_dcis_vnpi_lbl             input=$_dcis_vnpi}
{html_set_row field="dcis_morphologie"           caption=$_dcis_morphologie_lbl             input=$_dcis_morphologie}
{html_set_row field="dcis_kerngrading" caption=$_dcis_kerngrading_lbl   input=$_dcis_kerngrading}
{html_set_row field="dcis_nekrosen"    caption=$_dcis_nekrosen_lbl      input=$_dcis_nekrosen}

{html_set_header caption=#head_bem# class="head"}
{html_set_header caption=$_bem class="edt"}
</table>
{html_set_buttons modus=$button}

<div>
<input type="hidden" value="{$_histologie_id_value}" name="form_id" />
{$_histologie_id}
{$_patient_id}
{$_erkrankung_id}
</div>
