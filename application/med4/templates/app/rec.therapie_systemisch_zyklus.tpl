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

{html_set_header caption=#head_zyklus#    class="head"}
{html_set_row field="zyklus_nr"  caption=$_zyklus_nr_lbl    input=$_zyklus_nr}
{html_set_row field="beginn"     caption=$_beginn_lbl       input=$_beginn}
{html_set_row field="org_id"     caption=$_org_id_lbl       input=$_org_id}
{html_set_row field="user_id"    caption=$_user_id_lbl      input=$_user_id}
{html_set_row field="gewicht"    caption=$_gewicht_lbl      input=$_gewicht add=#lbl_kg#}
{html_set_row field="groesse"    caption=$_groesse_lbl      input=$_groesse add=#lbl_cm#}
{html_set_row field="ecog"       caption=$_ecog_lbl         input=$_ecog}

{html_set_html field="verschoben" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_verschoben_lbl</td>
            <td class='edt' style='width:10%;'>$_verschoben</td>
            <td class='edt'>$_verschoben_grund_lbl $_verschoben_grund</td>
         </tr>
         </table>
      </td>
   </tr>
"}

{html_set_html field="response" html="
   <tr style='border-top:1px solid #fff;'>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_response_lbl</td>
            <td class='edt' style='width:10%;'>$_response</td>
            <td class='edt'>$_response_datum_lbl $_response_datum</td>
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
{$_therapie_systemisch_zyklus_id}
{$_therapie_systemisch_id}
{$_patient_id}
{$_erkrankung_id}
</div>