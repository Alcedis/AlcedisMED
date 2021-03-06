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

{section name=i loop=$fields.vorlage_krankenversicherung_id.value}
	{html_odd_even var="class" key=%i.index%}
	<tr>
	   <td class="{$class}"  align="center"><a href="index.php?page=rec.vorlage_krankenversicherung&amp;vorlage_krankenversicherung_id={$fields.vorlage_krankenversicherung_id.value[i]}" class="edit"></a></td>
		<td class="{$class}">{$fields.name.value[i]}</td>
		<td class="{$class}">{$fields.iknr.value[i]}</td>
		<td class="{$class}">{$fields.gkv.bez[i]}</td>
		<td class="{$class}">{$fields.telefon.value[i]}</td>
		<td class="{$class}">{$fields.telefax.value[i]}</td>
		<td class="{$class}">{$fields.email.value[i]}</td>
		<td class="{$class}">{$fields.inaktiv.bez[i]}</td>
	</tr>
{sectionelse}
	<tr>
		<td class="even no-data" colspan="8">{#no_dataset#}</td>
	</tr>
{/section}