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
	<tr>
		<td class="head edit unsortable"></td>
		<td class="head unsortable" style="width:70px">{#lbl_anrede#}</td>
		<td class="head ext-search cookie-nachname">{#lbl_nachname#}</td>
		<td class="head ext-search cookie-vorname">{#lbl_vorname#}</td>
		<td class="head ext-search cookie-loginname">{#lbl_loginname#}</td>
		<td class="head ext-search cookie-angelegt">{#lbl_angelegt_von#}</td>
		<td class="head ext-search cookie-inaktiv cookietype-check">{#lbl_inaktiv#}</td>
	</tr>
	
	{include file=base/list/list.user.tpl}
	
</table>