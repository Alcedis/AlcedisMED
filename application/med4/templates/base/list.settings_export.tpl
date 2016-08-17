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

<table class="listtable">
<tr>
	<td class="head edit unsortable"></td>
	<td class="head">{#lbl_name#}</td>
</tr>

{section name=i loop=$fields.settings_export_id.value}
{html_odd_even var="class" key=%i.index%}
<tr>
   <td class="{$class}" align="center"><a href="{$form_rec}&amp;settings_export_id={$fields.settings_export_id.value[i]}" class="edit"></a></td>
   <td class="{$class}"><b>{$fields.name.value[i]}</b></td>
</tr>
{sectionelse}
<tr>
	<td class="edt" colspan=2>{#no_dataset#}</td>
</tr>
{/section}

</table>