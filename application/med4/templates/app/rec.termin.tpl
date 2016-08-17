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
<tr>
	<td class="head" colspan="2">{#head_termin#}</td>
</tr>
<tr>
	<td class="lbl" style="width:30%">{$_patient_id_lbl}</td>
	<td class="edt"><strong>{$patient_name}</strong></td>
</tr>
{html_set_row field=art 				caption=$_art_lbl   				input=$_art}
{html_set_row field=datum 				caption=$_datum_lbl   			input=$_datum}
{html_set_row field=uhrzeit			caption=$_uhrzeit_lbl   		input=$_uhrzeit add=#lbl_uhr#}
{html_set_row field=dauer				caption=$_dauer_lbl   			input=$_dauer add=#lbl_minuten#}
{html_set_row field=brief_gesendet	caption=$_brief_gesendet_lbl  input=$_brief_gesendet}
{html_set_row field=erledigt			caption=$_erledigt_lbl   		input=$_erledigt}

{html_set_row field=erinnerung 		caption=$_erinnerung_lbl   	input=$_erinnerung add="<span style='padding-left:25px'>$_erinnerung_datum_lbl $_erinnerung_datum</span>"}

{if $form_id}
	{html_set_header caption=#head_print# class="head"}
	<tr>
		<td class="edt" colspan="2" align="center">
			
			<table class="button-container" border="0">
      		<tr>
					<td><input type="submit" class="dont_prevent_double_save button_get_pdf_rpt" name="action[print][single]" value="" alt=""/></td>
					<td><input type="submit" name="action[print][single]" class="dont_prevent_double_save button_gen_rpt_text" value="{#lbl_print#}" alt=""/></td>
      		</tr>
      	</table>
		</td>
</tr>
{/if}
{html_set_header caption=#head_bem# class="head"}
{html_set_header field="bem" caption=$_bem      class="edt"}
</table>
{html_set_buttons modus=$button}

<div>
   {$_termin_id}
   {$_erkrankung_id}
   {$_patient_id}
   {$_org_id}
</div>