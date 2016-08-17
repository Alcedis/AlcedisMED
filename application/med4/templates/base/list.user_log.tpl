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

<table class="listtable bfl">

	<tr align="center">
		<td class="head unsortable" style="width:12%">{#lbl_anzeigen#}</td>
		<td class="head ext-search cookie-loginname" align="left">{#lbl_login_name#}</td>
		<td class="head ext-search cookie-nachname" align="left">{#lbl_nachname#}</td>
		<td class="head ext-search cookie-vorname" align="left">{#lbl_vorname#}</td>
		<td class="head ext-search cookie-logintime" >{#lbl_last_login_acc#}</td>
	</tr>

	{include file=base/list/list.user_log.tpl}

</table>