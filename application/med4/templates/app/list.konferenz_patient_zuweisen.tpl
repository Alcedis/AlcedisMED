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

<table class="listtable bfl" summary='{$bflparam}'>
<tr>
   <td class="head unsortable" style="width:60px" align="center">
   	  {#lbl_auswahl#}
   	  <input type="text" class="bfl-buffer" name="buffer-id" value='{literal}{"add":{},"remove":{}}{/literal}' />
   </td>
   <td class="head ext-search cookie-patient">{#lbl_patient#}</td>
   <td class="head ext-search cookie-erkrankung">{#lbl_erkrankung#}</td>
   <td class="head ext-search cookie-art">{#lbl_art#}</td>
   <td class="head unsortable" style="width: 95px" align="right">{#lbl_datenstand#}</td>
</tr>

{include file=app/list/list.konferenz_patient_zuweisen.tpl}

</table>

<table class="inline-table">
    <tr style="border-bottom: 0px none;">
        <td align="center" style="margin: 0px; padding: 0px;">
        <input class=" button" type="submit" alt="{$smarty.config.btn_lbl_insert}" value="{$smarty.config.btn_lbl_insert}" name="action[add]">
        <input class="button" type="submit" alt="{$smarty.config.btn_lbl_cancel}" value="{$smarty.config.btn_lbl_cancel}" name="action[cancel]">
        </td>
    </tr>
</table>