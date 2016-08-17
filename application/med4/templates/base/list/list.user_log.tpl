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

{section name=i loop=$fields.loginname.value}
	{html_odd_even var="class" key=%i.index%}
	<tr align="center">
	   <td class="{$class}">
			<a class="folder" href="index.php?page=list.user_log_detail&amp;loginname={$fields.loginname.value[i]}"></a>
	   </td>
	   <td class="{$class}" align="left">{$fields.loginname.value[i]}</td>
	   <td class="{$class}" align="left">{$fields.nachname.value[i]}</td>
	   <td class="{$class}" align="left">{$fields.vorname.value[i]}</td>
	   <td class="{$class}">{$fields.login_time_de.value[i]}</td>
	</tr>
	{sectionelse}
	<tr>
		<td class="edt" colspan="6"><b>{#lbl_no_user#}</b></td>
	</tr>
{/section}