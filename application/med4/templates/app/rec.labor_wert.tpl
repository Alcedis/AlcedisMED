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

<table class="formtable" style="margin-bottom:0">
<tr>
   <td class="subhead">
      {#head_messwert#}
   </td>
   <td class="subhead">
      {$_wert_lbl}
   </td>
   <td class="subhead">
      {$_einheit_lbl}
   </td>
</tr>
<tr>
   <td class="lbl">
      <strong>{$_parameter_bez}</strong>
   </td>
   <td class="edt">
      {$_wert}
   </td>
   <td class="lbl">
      <strong>{$_einheit_bez}</strong>
   </td>
</tr>
</table>

<table class="formtable" style="margin-top:0">
{html_set_row field="beurteilung"    caption=$_beurteilung_lbl   input=$_beurteilung}

</table>
{html_set_ajax_buttons modus=$button}

<div>
<input type="hidden" name="sess_pos" value="{$sess_pos}" />
{$_labor_wert_id}
{$_labor_id}
{$_erkrankung_id}
{$_patient_id}
<input type="hidden" name="vorlage_labor_wert_id" value="{$_vorlage_labor_wert_id_value}" />
<input type="hidden" name="einheit" value="{$_einheit_value}" />
</div>