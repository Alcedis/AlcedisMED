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

	<tr >
	   <td class="head ext-search cookie-status cookietype-check" align="left" style="width:25%">{#lbl_status#}</td>
	   <td class="head ext-search cookie-id" >{#lbl_id#}</td>
		<td class="head ext-search cookie-datum" >{#lbl_datum#}</td>
		<td class="head ext-search cookie-uhrzeit" >{#lbl_uhrzeit#}</td>
		<td class="head ext-search cookie-ip" align="right">{#lbl_ip#}</td>
	</tr>

	{include file=base/list/list.user_log_detail.tpl}

</table>