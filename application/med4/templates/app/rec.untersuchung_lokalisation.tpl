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

{html_set_header caption=#head_untersuchung_lokalisation# class="head"}

{html_set_row field="lokalisation"        caption=$_lokalisation_lbl       input=$_lokalisation}
{html_set_row field="beurteilung"   caption=$_beurteilung_lbl     input=$_beurteilung}

{html_set_html field="groesse_x" html="
<tr>
   <td class='lbl'>$_groesse_x_lbl</td>
   <td class='edt'>
      $_groesse_x`$smarty.config.lbl_mm` &nbsp;&nbsp;
      $_groesse_y_lbl &nbsp;&nbsp; $_groesse_y`$smarty.config.lbl_mm` &nbsp;&nbsp;
      $_groesse_z_lbl &nbsp;&nbsp; $_groesse_z`$smarty.config.lbl_mm`<br/>
      <div style='margin-top:6px'>
         $_groesse_nicht_messbar $_groesse_nicht_messbar_lbl <br/>
         $_multipel $_multipel_lbl <br/>
         $_organuebergreifend $_organuebergreifend_lbl
      </div>
   </td>
</tr>
"}

{html_set_row field="hoehe"         caption=$_hoehe_lbl           input=$_hoehe add=#lbl_cm#}
{html_set_row field="wachstumsform" caption=$_wachstumsform_lbl   input=$_wachstumsform}

{html_set_html field="naessen,krusten,blutung" html="
<tr>
   <td class='msg' colspan='2'>
      <table class='inline-table'>
      <tr>
         <td class='lbl'>`$smarty.config.lbl_verhalten`</td>
         <td class='edt'>$_naessen_lbl</td>
         <td class='edt'>$_naessen</td>
         <td class='edt'>$_krusten_lbl</td>
         <td class='edt'>$_krusten</td>
         <td class='edt'>$_blutung_lbl</td>
         <td class='edt'>$_blutung</td>
      </tr>
      </table>
   </td>
</tr>
"}

{html_set_row field="zellzahl" caption=$_zellzahl_lbl   input=$_zellzahl}

</table>

{html_set_ajax_buttons modus=$button}

<div>
<input type="hidden" name="sess_pos" value="{$sess_pos}" />
{$_untersuchung_lokalisation_id}
{$_untersuchung_id}
{$_patient_id}
{$_erkrankung_id}
</div>