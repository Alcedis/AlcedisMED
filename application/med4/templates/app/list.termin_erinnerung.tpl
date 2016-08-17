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

<table class="formtable" style="margin-bottom:5px">
{html_set_header class="head" colspan=3 caption=#head_druck#}
   <tr>
      <td class="even" style="width: 320px">
         {#lbl_von#}
         <input type="text" class="datepicker input" name="sel_datum_von" value="" size="10" maxlength="10" />
         <span style="margin-left: 10px">
            {#lbl_bis#}
            <input  type="text" class="datepicker input" name="sel_datum_bis" value="" size="10" maxlength="10" />
         </span>
      </td>
      <td class="even" style="vertical-align:middle">
      	<table class="button-container" border="0">
      		<tr>
					<td><input type="submit" class="dont_prevent_double_save button_get_pdf_rpt" name="action[print][multiple][erinnerung]" value="" alt=""/></td>
					<td><input type="submit" name="action[print][multiple][erinnerung]" class="dont_prevent_double_save button_gen_rpt_text" value="{#lbl_export#}" alt=""/></td>
      		</tr>
      	</table>
      </td>
      <td class="even" style="width: 190px; text-align:center">
   		<a class="termin-erinnerung" href="index.php?page=list.termin">
				<b>{#lbl_alle_termine#}</b>
			</a>
      </td>
   </tr>
</table>

<table class="listtable bfl">
	<tr>
		<td class="subhead edit unsortable" style="width:40px"></td>
		<td class="subhead ext-search cookie-datum" style="width:70px;">{#lbl_datum#}</td>
		<td class="subhead ext-search cookie-name">{#lbl_name#}</td>
		<td class="subhead unsortable" style="width:70px;">{#lbl_patient_nr#}</td>
		<td class="subhead unsortable">{#lbl_uhrzeit#}/{#lbl_dauer#}</td>
		<td class="subhead ext-search cookie-art">{#lbl_art#}</td>
		<td class="subhead ext-search cookie-gesendet cookietype-check" style="width:80px;">{#lbl_gesendet#}</td>
		<td class="subhead unsortable"></td>
	</tr>

	{include file=app/list/list.termin_erinnerung.tpl}

</table>