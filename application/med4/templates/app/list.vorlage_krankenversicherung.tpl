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
		<td class="head ext-search cookie-name">{#lbl_name#}</td>
		<td class="head ext-search cookie-iknr">{#lbl_kassen_nr#}</td>
		<td style="width:60px" class="head ext-search cookie-gkv cookietype-check">{#lbl_gkv#}</td>
		<td class="head unsortable">{#lbl_telefon#}</td>
		<td class="head unsortable">{#lbl_telefax#}</td>
		<td class="head unsortable">{#lbl_email#}</td>
		<td style="width:70px" class="head ext-search cookie-inaktiv cookietype-check">{#lbl_inaktiv#}</td>
	</tr>

	{include file=app/list/list.vorlage_krankenversicherung.tpl}

</table>