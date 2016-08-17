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

{html_set_header caption=#head_strahlentherapie#    class="head"}
{html_set_row field="vorlage_therapie_id"      caption=$_vorlage_therapie_id_lbl        input=$_vorlage_therapie_id add="$_hyperthermie $_hyperthermie_lbl"}
{html_set_row field="org_id"           caption=$_org_id_lbl             input=$_org_id}
{html_set_row field="user_id"          caption=$_user_id_lbl            input=$_user_id}
{html_set_row field="therapieplan_id"  caption=$_therapieplan_id_lbl    input=$_therapieplan_id}
{html_set_row field="intention"        caption=$_intention_lbl          input=$_intention}
{html_set_row field="therapieform"  caption=$_therapieform_lbl    input=$_therapieform}
{html_set_row field="studie"           caption=$_studie_lbl             input=$_studie}
{html_set_row field="studie_id"        caption=$_studie_id_lbl          input=$_studie_id}
{if $erkrankungData.code == 'b'}
    {html_set_header  class="msgbox"                caption=#info_dauer#}
{/if}
{html_set_row field="beginn"           caption=$_beginn_lbl             input=$_beginn}
{html_set_row field="ende"             caption=$_ende_lbl               input=$_ende}
{html_set_row field="andauernd"        caption=$_andauernd_lbl          input=$_andauernd}
{html_set_row field="zahnarzt" caption=$_zahnarzt_lbl input=$_zahnarzt}

{html_set_header caption=#head_bestrahlungsziel#    class="head"}
{html_set_html field="ziel_sonst" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl'>
               `$smarty.config.lbl_lokalisation`
            </td><td class='msg' colspan='2'>
               <table class='inline-table'>
"}
{html_set_html field="ziel_ganzkoerper" html="
   <tr>
      <td class='edt' colspan='2'>$_ziel_ganzkoerper $_ziel_ganzkoerper_lbl</td>
   </tr>
"}
{html_set_html field="ziel_primaertumor" html="
   <tr>
      <td class='edt' colspan='2'>$_ziel_primaertumor $_ziel_primaertumor_lbl</td>
   </tr>
"}
{html_set_html field="ziel_mamma_r" html="
   <tr>
      <td class='edt'> $_ziel_mamma_r$_ziel_mamma_r_lbl</td>
      <td class='edt'>$_ziel_mamma_l $_ziel_mamma_l_lbl</td>
   </tr>
"}
{html_set_html field="ziel_brustwand_r" html="
   <tr>
      <td class='edt'>$_ziel_brustwand_r $_ziel_brustwand_r_lbl</td>
      <td class='edt'>$_ziel_brustwand_l $_ziel_brustwand_l_lbl</td>
   </tr>
"}
{html_set_html field="ziel_mammaria_interna" html="
   <tr>
      <td class='edt' colspan='3'>$_ziel_mammaria_interna $_ziel_mammaria_interna_lbl</td>
   </tr>
"}
{html_set_html field="ziel_mediastinum" html="
   <tr>
      <td class='edt' colspan='2'>$_ziel_mediastinum $_ziel_mediastinum_lbl</td>
   </tr>
"}
{html_set_html field="ziel_axilla_r" html="
   <tr>
      <td class='edt'>$_ziel_axilla_r $_ziel_axilla_r_lbl</td>
      <td class='edt'>$_ziel_axilla_l $_ziel_axilla_l_lbl</td>
   </tr>
"}
{html_set_html field="ziel_lk_supra" html="
   <tr>
      <td class='edt'>$_ziel_lk_supra $_ziel_lk_supra_lbl</td>
      <td class='edt'>$_ziel_lk_para $_ziel_lk_para_lbl</td>
   </tr>
"}
{html_set_html field="ziel_prostata" html="
   <tr>
      <td class='edt' colspan='2'>$_ziel_prostata $_ziel_prostata_lbl</td>
   </tr>
"}
{html_set_html field="ziel_becken" html="
   <tr>
      <td class='edt'>$_ziel_becken $_ziel_becken_lbl</td>
      <td class='edt'>$_ziel_abdomen $_ziel_abdomen_lbl</td>
   </tr>
   <tr>
      <td class='edt'>$_ziel_vulva $_ziel_vulva_lbl</td>
      <td class='edt'>$_ziel_vulva_pelvin $_ziel_vulva_pelvin_lbl</td>
   </tr><tr>
      <td class='edt'>$_ziel_vulva_inguinal $_ziel_vulva_inguinal_lbl</td>
      <td class='edt'>$_ziel_inguinal_einseitig $_ziel_inguinal_einseitig_lbl</td>
   </tr>
   <tr>
      <td class='edt'>$_ziel_ingu_beidseitig $_ziel_ingu_beidseitig_lbl</td>
      <td class='edt'>$_ziel_ingu_pelvin $_ziel_ingu_pelvin_lbl</td>
   </tr>
   <tr>
      <td class='edt'>$_ziel_vagina $_ziel_vagina_lbl</td>
      <td class='edt' colspan='5'>$_ziel_paraaortal $_ziel_paraaortal_lbl</td>
   </tr>
"}
{html_set_html field="ziel_lymph" html="
   <tr>
      <td class='edt' colspan='2'>$_ziel_lymph $_ziel_lymph_lbl</td>
   </tr>
"}
{html_set_html field="ziel_lk" html="
   <tr>
      <td class='edt' colspan='2'>$_ziel_lk $_ziel_lk_lbl</td>
   </tr>
"}
{html_set_html field="ziel_lk_iliakal" html="
   <tr>
      <td class='edt' colspan='2'>$_ziel_lk_iliakal $_ziel_lk_iliakal_lbl</td>
   </tr>
"}
{html_set_html field="ziel_lk_zervikal_r" html="
   <tr>
      <td class='edt' colspan='2'>$_ziel_lk_zervikal_r $_ziel_lk_zervikal_r_lbl</td>
    </tr>
    <tr>
      <td class='edt' colspan='2'>$_ziel_lk_zervikal_l $_ziel_lk_zervikal_l_lbl</td>
   </tr>
"}
{html_set_html field="ziel_lk_hilaer" html="
   <tr>
      <td class='edt' colspan='2'>$_ziel_lk_hilaer $_ziel_lk_hilaer_lbl</td>
   </tr>
"}
{html_set_html field="ziel_lk_axillaer_r" html="
   <tr>
      <td class='edt'>$_ziel_lk_axillaer_r $_ziel_lk_axillaer_r_lbl</td>
      <td class='edt'>$_ziel_lk_axillaer_l $_ziel_lk_axillaer_l_lbl</td>
   </tr>
"}
{html_set_html field="ziel_lk_abdominell_o" html="
   <tr>
      <td class='edt' colspan='2'>$_ziel_lk_abdominell_o $_ziel_lk_abdominell_o_lbl</td>
    </tr>
    <tr>
      <td class='edt' colspan='2'>$_ziel_lk_abdominell_u $_ziel_lk_abdominell_u_lbl</td>
   </tr>
"}
{html_set_html field="ziel_lk_iliakal_r" html="
   <tr>
      <td class='edt'>$_ziel_lk_iliakal_r $_ziel_lk_iliakal_r_lbl</td>
      <td class='edt'>$_ziel_lk_iliakal_l $_ziel_lk_iliakal_l_lbl</td>
   </tr>
"}
{html_set_html field="ziel_lk_inguinal_r" html="
   <tr>
      <td class='edt'>$_ziel_lk_inguinal_r $_ziel_lk_inguinal_r_lbl</td>
      <td class='edt'>$_ziel_lk_inguinal_l $_ziel_lk_inguinal_l_lbl</td>
   </tr>
"}
{html_set_html field="ziel_knochen" html="
   <tr>
      <td class='edt' colspan='2'>$_ziel_knochen $_ziel_knochen_lbl</td>
   </tr><tr>
      <td class='edt' colspan='2'>$_ziel_gehirn $_ziel_gehirn_lbl</td>
   </tr><tr>
      <td class='edt' colspan='2'>$_ziel_sonst $_ziel_sonst_lbl</td>
   </tr>
"}
{html_set_html field="ziel_sonst_detail" html="
   <tr>
      <td class='edt' colspan='2'>$_ziel_sonst_detail</td>
   </tr>
"}
{html_set_html field="ziel_sonst" html="
         </table>
      </td>
   </tr>
   </table>
   </td>
   </tr>
"}

{html_set_header caption=#head_bestrahlungsart#    class="head" field="art,fraktionierungstyp,einzeldosis,gesamtdosis,boost,boostdosis,dosierung_icru,imrt,igrt,beschleunigerenergie,seed_strahlung_90d,seed_strahlung_90d_datum"}
{html_set_row field="art"   caption=$_art_lbl      input=$_art}
{html_set_row field="fraktionierungstyp"   caption=$_fraktionierungstyp_lbl      input=$_fraktionierungstyp}
{html_set_row field="einzeldosis"   caption=$_einzeldosis_lbl      input=$_einzeldosis add=#lbl_gy#}
{html_set_row field="gesamtdosis"   caption=$_gesamtdosis_lbl      input=$_gesamtdosis add=#lbl_gy#}
{html_set_html field="boost" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_boost_lbl</td>
            <td class='edt' style='width:10%;'>$_boost</td>
            <td class='edt'>
               $_boostdosis_lbl $_boostdosis `$smarty.config.lbl_gy`
            </td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_row field="dosierung_icru"         caption=$_dosierung_icru_lbl           input=$_dosierung_icru}
{html_set_row field="imrt"                   caption=$_imrt_lbl                     input=$_imrt}
{html_set_row field="igrt"                   caption=$_igrt_lbl                     input=$_igrt}
{html_set_row field="beschleunigerenergie"   caption=$_beschleunigerenergie_lbl     input=$_beschleunigerenergie add=#lbl_mv#}

{html_set_html field="seed_strahlung_90d" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_seed_strahlung_90d_lbl</td>
            <td class='edt' style='width:10%;'>$_seed_strahlung_90d</td>
            <td class='edt'>
               $_seed_strahlung_90d_datum_lbl
               $_seed_strahlung_90d_datum
            </td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_header caption=#head_therapieerfolg#    class="head" field="unterbrechung,unterbrechung_grund,endstatus,endstatus_grund,best_response,best_response_datum,dosisreduktion,dosisreduktion_grund,dosisreduktion_grund_sonst"}
{html_set_html field="endstatus" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_endstatus_lbl</td>
            <td class='edt'>$_endstatus</td>
            <td class='edt'>
               $_endstatus_grund_lbl<br/>
               $_endstatus_grund
            </td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_html field="best_response" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table' style='margin-top:1px;'>
         <tr>
            <td class='lbl' style='width:35%;'>$_best_response_lbl</td>
            <td class='edt' style='width:15%;'>$_best_response</td>
            <td class='edt'>
               $_best_response_datum_lbl<br/>
               $_best_response_datum
            </td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_html field="dosisreduktion" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table' style='margin-top:1px;'>
         <tr>
            <td class='lbl' style='width:35%;'>$_dosisreduktion_lbl</td>
            <td class='edt' style='width:15%;'>$_dosisreduktion</td>
            <td class='edt'>
               $_dosisreduktion_grund_lbl $_dosisreduktion_grund
               <div style='padding-top:7px'>$_dosisreduktion_grund_sonst_lbl $_dosisreduktion_grund_sonst</div>
            </td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_html field="unterbrechung" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table' style='margin-top:1px;'>
         <tr>
            <td class='lbl' style='width:35%;'>$_unterbrechung_lbl</td>
            <td class='edt' style='width:15%;'>$_unterbrechung</td>
            <td class='edt'>
               $_unterbrechung_grund_lbl $_unterbrechung_grund
               <div style='padding-top:7px'>$_unterbrechung_grund_sonst_lbl $_unterbrechung_grund_sonst</div>
            </td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_header caption=#head_bem#    class="head" field="bem"}
{html_set_header caption=$_bem         class="edt" field="bem"}

</table>
{html_set_buttons modus=$button}

<div>
{$_vorlage_therapie_art}
{$_strahlentherapie_id}
{$_patient_id}
{$_erkrankung_id}
</div>
