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

{section name=i loop=$fields.termin_id.value}
	{html_odd_even var="class" key=%i.index%}
	<tr>
		<td class="{$class}" align="center">
		    <a href="index.php?page=rec.termin_patient&amp;termin_id={$fields.termin_id.value[i]}&amp;origin=true">
	           <img src="media/img/base/edit.png"            alt="Bearbeiten"/>
		    </a>
		</td>
		<td class="{$class}">{$fields.datum.value[i]}</td>
		<td class="{$class}">{$fields.patient_name.value[i]}</td>
		<td class="{$class}"><span style="font-size:9pt">{$fields.patient_nr.value[i]}</span></td>
		<td class="{$class}">
			{if strlen($fields.uhrzeit.value[i])}
				<span style="font-size:9pt">{$fields.uhrzeit.value[i]} {#lbl_uhr#}</span>
			{/if}
			{if strlen($fields.uhrzeit.value[i]) && strlen($fields.dauer.value[i])}<br/>{/if}
			{if strlen($fields.dauer.value[i])}
				<span style="font-size:9pt"> {$fields.dauer.value[i]} {#lbl_min#}</span>
			{/if}
		</td>
		<td class="{$class}"><span style="font-size:9pt">{$fields.art.value[i]}</span></td>
		<td class="{$class}"><span style="font-size:9pt">{$fields.brief_gesendet.bez[i]}</span></td>
		<td class="{$class}" align="right">
			<input type="submit" class="dont_prevent_double_save button_show_file" name="action[print][single][{$fields.termin_id.value[i]}]" value="" alt=""/>
		</td>
	</tr>
{sectionelse}
	{html_set_header class="even no-data" colspan="8" caption=#no_dataset#}
{/section}
