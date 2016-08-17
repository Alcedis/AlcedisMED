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

{html_set_header caption=#head_sonstige_therapie#    class="head"}
{html_set_row field="bez"              caption=$_bez_lbl                input=$_bez}
{html_set_row field="sonstige_art"     caption=$_sonstige_art_lbl       input=$_sonstige_art}
{html_set_row field="org_id"           caption=$_org_id_lbl             input=$_org_id}
{html_set_row field="user_id"          caption=$_user_id_lbl            input=$_user_id}
{html_set_row field="therapieplan_id"  caption=$_therapieplan_id_lbl    input=$_therapieplan_id}
{html_set_row field="intention"        caption=$_intention_lbl          input=$_intention}
{html_set_row field="studie"           caption=$_studie_lbl             input=$_studie}
{html_set_row field="studie_id"        caption=$_studie_id_lbl          input=$_studie_id}
{html_set_row field="beginn"           caption=$_beginn_lbl             input=$_beginn}
{html_set_row field="ende"             caption=$_ende_lbl               input=$_ende}

{html_set_header caption=#head_erfolg#    class="head" field="endstatus,endstatus_grund,endstatus_grund_sonst,best_response,best_response_datum,unterbrechung,unterbrechung_grund,unterbrechung_grund_sonst"}
{html_set_html field="endstatus" html="
   <tr style='border-bottom:1px solid #fff;'>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' rowspan='3' style='width:35%;'>$_endstatus_lbl</td>
            <td class='edt' colspan='2'>$_endstatus</td>
         </tr>
         <tr>
            <td class='edt' style='text-align:right;width:28%;'>$_endstatus_grund_lbl</td>
            <td class='edt'>$_endstatus_grund</td>
         </tr>
         <tr>
            <td class='edt' style='text-align:right;width:28%;'>$_endstatus_grund_sonst_lbl</td>
            <td class='edt'>$_endstatus_grund_sonst</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_html field="best_response" html="
   <tr style='border-bottom:1px solid #fff;'>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_best_response_lbl</td>
            <td class='edt' style='width:10%;'>$_best_response</td>
            <td class='edt' style='text-align:right;width:18%;'>
               $_best_response_datum_lbl
               </td>
               <td class='edt'>
               $_best_response_datum
            </td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_html field="unterbrechung" html="
   <tr style='border-bottom:1px solid #fff;'>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' rowspan='2' style='width:35%;'>$_unterbrechung_lbl</td>
            <td class='edt' rowspan='2' style='width:10%;'>$_unterbrechung</td>
            <td class='edt' style='text-align:right;width:18%'>$_unterbrechung_grund_lbl</td>
            <td class='edt'>$_unterbrechung_grund</td>
        </tr>
        <tr>
            <td class='edt' style='text-align:right;width:18%'>$_unterbrechung_grund_sonst_lbl</td>
            <td class='edt'>$_unterbrechung_grund_sonst</td>
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
{$_sonstige_therapie_id}
{$_patient_id}
{$_erkrankung_id}
</div>
