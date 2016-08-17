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

{section name=i loop=$fields.user_id.value}
	{html_odd_even var="class" key=%i.index%}
	<tr>
		<td class="{$class}" align="center"><a href="{$form_rec}&amp;user_id={$fields.user_id.value[i]}" class="edit"></a></td>
		<td class="{$class}">{$fields.anrede.bez[i]}</td>
		<td class="{$class}">{$fields.nachname.value[i]}</td>
		<td class="{$class}">{$fields.vorname.value[i]}</td>
		<td class="{$class}"><b><!-- -->{$fields.loginname.value[i]}</b></td>
		<td class="{$class}">{$fields.created_by.value[i]}</td>
		<td class="{$class}">{$fields.inaktiv.bez[i]}</td>
	</tr>
{sectionelse}
	<tr>
		<td class="edt" colspan="8">{#no_dataset#}</td>
	</tr>
{/section}