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

{html_set_header caption=#caption#    class="head"}

{html_set_row field=datum                 caption=$_datum_lbl                 input=$_datum              }
{html_set_row field=diagnose              caption=$_diagnose_lbl              input=$_diagnose           }
{html_set_row field=lokalisation          caption=$_lokalisation_lbl          input=$_lokalisation       }
{html_set_row field=ct                    caption=$_ct_lbl                    input=$_ct                 }
{html_set_row field=schleimhautmelanom    caption=$_schleimhautmelanom_lbl    input=$_schleimhautmelanom }
{html_set_row caption=$_untersuchung_id_lbl input=$_untersuchung_id}


{html_set_header caption=#head_rezidiv#    class="head" field="rezidiv_von"}
{html_set_row field=rezidiv_von    caption=$_rezidiv_von_lbl    input=$_rezidiv_von }
{html_set_row field=lokoregionaer    caption=$_lokoregionaer_lbl    input=$_lokoregionaer }

{html_set_html field="metast_visz" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' rowspan='5'>$_metast_visz_lbl</td>
            <td class='edt'>
               $_metast_visz `$smarty.config.lbl_ja`
            </td>
         </tr>
         <tr>
            <td class='edt'>$_metast_visz_1 </td>
         </tr>
         <tr>
            <td class='edt'>$_metast_visz_2 </td>
         </tr>
         <tr>
            <td class='edt'>$_metast_visz_3 </td>
         </tr>
         <tr>
            <td class='edt'>$_metast_visz_4 </td>
         </tr>
         <tr>
            <td class='lbl'>$_metast_haut_lbl</td>
            <td class='edt'>
               $_metast_haut `$smarty.config.lbl_ja`
            </td>
         </tr>
         <tr>
            <td class='lbl'>$_metast_lk_lbl</td>
            <td class='edt'>
               $_metast_lk `$smarty.config.lbl_ja`
            </td>
         </tr>
         </table>
      </td>
   </tr>

"}

{html_set_header caption=#head_bem# class="head"}
{html_set_header caption=$_bem      class="edt"}

</table>
{html_set_buttons modus=$button}

<div>
{$_diagnose_id}
{$_patient_id}
{$_erkrankung_id}
</div>