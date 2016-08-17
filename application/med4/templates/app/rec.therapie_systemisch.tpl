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

{html_set_header caption=#head_sys_therapie#    class="head"}

{if $_therapie_systemisch_id_value}
   <tr>
      <td class="lbl">{$_vorlage_therapie_id_lbl}</td>
      <td class="edt"><strong>{$_vorlage_therapie_id_bez}</strong>
         <input type="hidden" name="vorlage_therapie_id" value="{$_vorlage_therapie_id_value}" />
      </td>
   </tr>
{else}
   {html_set_row field="vorlage_therapie_id"      caption=$_vorlage_therapie_id_lbl     input=$_vorlage_therapie_id}
{/if}


{html_set_row field="org_id"                caption=$_org_id_lbl                input=$_org_id}
{html_set_row field="user_id"               caption=$_user_id_lbl               input=$_user_id}
{html_set_row field="therapieplan_id"       caption=$_therapieplan_id_lbl       input=$_therapieplan_id}
{html_set_row field="intention"             caption=$_intention_lbl             input=$_intention}
{html_set_row field="therapieform"          caption=$_therapieform_lbl          input=$_therapieform}
{html_set_row field="therapielinie"         caption=$_therapielinie_lbl         input=$_therapielinie}
{html_set_row field="metastasentherapie"    caption=$_metastasentherapie_lbl    input=$_metastasentherapie}

{html_set_row field="studie"                caption=$_studie_lbl                input=$_studie}
{html_set_row field="studie_id"             caption=$_studie_id_lbl             input=$_studie_id}
{if $erkrankungData.code == 'b'}
    {html_set_header  class="msgbox"        caption=#info_dauer#}
{/if}
{html_set_row field="beginn"                caption=$_beginn_lbl                input=$_beginn}
{html_set_row field="ende"                  caption=$_ende_lbl                  input=$_ende}
{html_set_row field="andauernd"             caption=$_andauernd_lbl             input=$_andauernd}
{html_set_row field="ort_therapiegabe"      caption=$_ort_therapiegabe_lbl      input=$_ort_therapiegabe}
{html_set_row field="tumorverhalten_platin" caption=$_tumorverhalten_platin_lbl input=$_tumorverhalten_platin}
{html_set_row field="zahnarzt" caption=$_zahnarzt_lbl input=$_zahnarzt}
{html_set_header caption=#head_erfolg#    class="head"}

{html_set_html field="endstatus,endstatus_grund,best_response,best_response_bestimmung,dosisaenderung,dosisaenderung_grund,unterbrechung,unterbrechung_grund,unterbrechung_grund_sonst,paravasat,regelmaessig,regelmaessig_grund" html="
<tr>
   <td class='msg' colspan='2'>
      <table class='inline-table'>
"}

{html_set_html field="endstatus,endstatus_grund" html="
         <tr>
            <td class='lbl' style='width:35%;'>$_endstatus_lbl</td>
            <td class='edt' colspan='2'>$_endstatus<br/><br/>$_endstatus_grund_lbl $_endstatus_grund</td>
         </tr>
"}

{html_set_html field="best_response,best_response_datum" html="
         <tr>
            <td class='lbl' style='width:35%;'>$_best_response_lbl</td>
            <td class='edt' style='width:9%;'>$_best_response</td>
            <td class='edt'>$_best_response_datum_lbl $_best_response_datum</td>
         </tr>
"}

{html_set_html field="best_response_bestimmung" html="
         <tr>
            <td class='lbl' style='width:35%;'>$_best_response_bestimmung_lbl</td>
            <td class='edt' colspan='2'>$_best_response_bestimmung</td>
         </tr>
"}

{html_set_html field="dosisaenderung,dosisaenderung_grund" html="
         <tr>
            <td class='lbl' style='width:35%;'>$_dosisaenderung_lbl</td>
            <td class='edt' style='width:10%;'>$_dosisaenderung</td>
            <td class='edt'>
               $_dosisaenderung_grund_lbl
               $_dosisaenderung_grund<br/>
               $_dosisaenderung_grund_sonst_lbl<br/>
               $_dosisaenderung_grund_sonst
            </td>
         </tr>
"}

{html_set_html field="unterbrechung,unterbrechung_grund,unterbrechung_grund_sonst" html="
         <tr>
            <td class='lbl' style='width:35%;'>$_unterbrechung_lbl</td>
            <td class='edt' style='width:10%;'>$_unterbrechung</td>
            <td class='edt'>
               $_unterbrechung_grund_lbl
               $_unterbrechung_grund <br/>
               $_unterbrechung_grund_sonst_lbl<br/>
               $_unterbrechung_grund_sonst
            </td>
         </tr>
"}

{html_set_row field="paravasat"  caption=$_paravasat_lbl   input=$_paravasat colspan="2"}

{html_set_html field="regelmaessig,regelmaessig_grund" html="
         <tr>
            <td class='lbl' style='width:35%;'>$_regelmaessig_lbl</td>
            <td class='edt' style='width:10%;'>$_regelmaessig</td>
            <td class='edt'>
               $_regelmaessig_grund_lbl
               $_regelmaessig_grund
            </td>
         </tr>
"}

{html_set_html field="endstatus,endstatus_grund,best_response,best_response_bestimmung,dosisaenderung,dosisaenderung_grund,unterbrechung,unterbrechung_grund,unterbrechung_grund_sonst,paravasat,regelmaessig,regelmaessig_grund" html="
      </table>
   </td>
</tr>
"}


{html_set_header caption=#head_bem#    class="head"}
{html_set_header caption=$_bem         class="edt"}

</table>
{html_set_buttons modus=$button}

<div>
{$_vorlage_therapie_art}
{$_therapie_systemisch_id}
{$_patient_id}
{$_erkrankung_id}
</div>
