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

<table class="listtable sidebar bfl bflsub-table" border="0">
   <tr>
      <td class="head unsortable" style="width:30px"></td>
      <td class="head ext-search cookie-date" align="center" style="width:70px">{#lbl_datum#}</td>
      <td class="head ext-search cookie-form" style="width:140px">{#lbl_formular#}</td>
      <td class="head unsortable" style="width:16px"></td>
      <td class="head ext-search cookie-content">{#lbl_inhalt#}</td>
      <td class="head unsortable" style="width:20px"></td>
      <td class="head unsortable" style="width:25px"></td>
      <td class="head ext-search cookie-status cookietype-lookup" align="center" style="width:16px">
            <span class="bfl-lookup-content">
                {$queryMod.lookups.status}
            </span>
      </td>
   </tr>

   {include file=app/list/view.erkrankung_table.tpl}

</table>
