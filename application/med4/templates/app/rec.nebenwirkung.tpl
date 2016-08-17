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

{html_set_header caption=#head_nebenwirkung#    class="head"}
{html_set_html field="nci_code" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='vertical-align:top;'>$_nci_code_lbl</td>
            <td class='edt'>
               $_nci_code
               $_nci_text
            </td>
         </tr>
         </table>
      </td>
   </tr>
   <tr class='grad-info' style='display:none;'>
      <td colspan='2' class='msg'>
         <div class='message' style='margin:0px 1px 0px 0px;'>
            <table class='info-table'>
               <tr>
                  <td><strong>`$smarty.config.grad1`</strong></td>
                  <td><strong>`$smarty.config.grad2`</strong></td>
                  <td><strong>`$smarty.config.grad3`</strong></td>
                  <td><strong>`$smarty.config.grad4`</strong></td>
                  <td><strong>`$smarty.config.grad5`</strong></td>
               </tr>
               <tr>
                  <td class='small'>$grad1</td>
                  <td class='small'>$grad2</td>
                  <td class='small'>$grad3</td>
                  <td class='small'>$grad4</td>
                  <td class='small'>$grad5</td>
               </tr>
            </table>
         </div>
      </td>
   </tr>
"}
{html_set_row field="grad"     						caption=$_grad_lbl     						input=$_grad}
{html_set_row field="ausgang"  						caption=$_ausgang_lbl 						input=$_ausgang}
{html_set_row field="beginn"              		caption=$_beginn_lbl             		input="$_beginn <span style='padding-left: 17px'>$_beginn_unbekannt_lbl $_beginn_unbekannt</span>"}
{html_set_row field="ende"              			caption=$_ende_lbl             			input="$_ende <span style='padding-left: 17px'>$_ende_unbekannt_lbl $_ende_unbekannt</span>"}
{html_set_row field="zusammenhang"              caption=$_zusammenhang_lbl             input=$_zusammenhang}
{html_set_row field="therapie_systemisch_id"    caption=$_therapie_systemisch_id_lbl   input=$_therapie_systemisch_id}
{html_set_row field="strahlentherapie_id"       caption=$_strahlentherapie_id_lbl      input=$_strahlentherapie_id}
{html_set_row field="sonstige_therapie_id"      caption=$_sonstige_therapie_id_lbl     input=$_sonstige_therapie_id}
{html_set_html field="therapie" html="
   <tr>
      <td class='msg' colspan='2'>
         <table class='inline-table'>
         <tr>
            <td class='lbl' style='width:35%;'>$_therapie_lbl</td>
            <td class='edt'>$_therapie</td>
            <td class='edt'>$_therapie_text_lbl $_therapie_text</td>
         </tr>
         </table>
      </td>
   </tr>
"}
{html_set_row field="sae"     caption=$_sae_lbl     input=$_sae}

{html_set_header caption=#head_bem#    class="head"}
{html_set_header caption=$_bem         class="edt"}

</table>
{html_set_buttons modus=$button}

<div>
{$_nebenwirkung_id}
{$_patient_id}
{$_erkrankung_id}
</div>