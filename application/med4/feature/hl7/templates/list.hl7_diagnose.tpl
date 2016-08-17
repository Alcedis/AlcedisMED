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

<table class="listtable bfl feature-hl7">
<tr>
   <td class="head ext-search cookie-nachname">{#name#}</td>
   <td class="head ext-search cookie-vorname">{#forename#}</td>
   <td class="head ext-search cookie-geburtsdatum">{#birthdate#}</td>
   <td class="head ext-search cookie-patient_nr">{#pat_nr#}</td>
   <td class="head ext-search cookie-diagnosen">{#diagnostic#}</td>
   <td class="head unsortable" style="width: 180px" align="right">{#erkrankung#}</td>
   <td class="head unsortable" style="width: 8%" align="right">{#allocate#}</td>
</tr>

{include file=../feature/hl7/templates/list/list.hl7_diagnose.tpl}

</table>

<table width="100%" >
   <tr>
      <td align="center">
         <input type="submit" class="button" name="action[cancel]" value="{#btn_lbl_cancel#}" />
      </td>
   </tr>
</table>