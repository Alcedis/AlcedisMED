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

{section name=i loop=$fields.zweitmeinung_id.value}

{html_odd_even var="class" key=%i.index%}
<tr>
    <td class="{$class}" align="center">
        <a href="index.php?page=view.erkrankung&amp;patient_id={$fields.patient_id.value[i]}&amp;erkrankung_id={$fields.erkrankung_id.value[i]}" class="gotodisease"></a>
    </td>
	<td class="{$class}">{$fields.nachname.value[i]}</td>
	<td class="{$class}">{$fields.vorname.value[i]}</td>
	<td class="{$class}">{$fields.geburtsdatum.value[i]}</td>
	<td class="{$class}">{$fields.patient_nr.value[i]}</td>
	<td class="{$class}">{$fields.erkrankung.value[i]}</td>
	<td class="{$class}">{$fields.datum.value[i]}</td>
	<td class="{$class}" style="text-align:center">
        <input type="submit" class="report dont_prevent_double_save button_show_file" name="action[report]" value="" alt="{$fields.zweitmeinung_id.value[i]}"/>
	</td>
</tr>
{sectionelse}
<tr>
	<td class="even no-data" colspan="9">{#no_dataset#}</td>
</tr>
{/section}