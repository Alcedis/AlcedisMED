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
	<td class="head">{#lbl_ort#}</td>
	<td class="head">{#lbl_telefon#}</td>
	<td class="head">{#lbl_telefax#}</td>
	<td class="head">{#lbl_inaktiv#}</td>
</tr>

{section name=i loop=$fields.org_id.value}
{html_odd_even var="class" key=%i.index%}
<tr>
   <td class="{$class}" align="center"><a href="{$form_rec}&amp;org_id={$fields.org_id.value[i]}" class="edit"></a></td>
	<td class="{$class}"><b>{$fields.name.value[i]}</b></td>
   <td class="{$class}">{$fields.ort.value[i]}</td>
	<td class="{$class}">{$fields.telefon.value[i]}</td>
	<td class="{$class}">{$fields.telefax.value[i]}</td>
	<td class="{$class}">{$fields.inaktiv.bez[i]}</td>
</tr>
{sectionelse}
<tr>
	<td class="edt" colspan="6">{#no_dataset#}</td>
</tr>
{/section}
</table>