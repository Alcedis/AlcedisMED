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

<div class='help-msg'>{#lbl_create_patient#}</div>
<table class="listtable bfl">
<tr >
   <td class="head edit unsortable"></td>
	<td class="head ext-search cookie-nachname" style="width:20%">{#name#}</td>
	<td class="head ext-search cookie-vorname" style="min-width:10%">{#forename#}</td>
	<td class="head ext-search cookie-geburtsdatum" style="font-size:7pt !important; width:11%">{#birthdate#}</td>
	<td class="head ext-search cookie-patient_nr" style="font-size:7pt !important; min-width:8%">{#pat_nr#}</td>
	<td class="head ext-search cookie-aufnahme_nr" style="font-size:7pt !important; min-width:9%">{#reg_nr#}</td>
	<td class="head ext-search cookie-createtime" style="font-size:7pt !important; width:11%">{#createtime#}</td>
	<td class="head ext-search cookie-erkrankung" align="right" style="width:22%"><span style="padding-right:10px">{#lbl_erkrankung#}</span></td>
    <td class="head unsortable"></td>
</tr>

{include file=app/list/list.patient_import.tpl}

</table>