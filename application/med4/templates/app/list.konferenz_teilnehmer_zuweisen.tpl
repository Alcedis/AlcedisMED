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

<table width="100%" >
   <tr>
      <td><!--  -->{$conference}</td>
   </tr>
</table>

<table class="listtable bfl" summary='{$bflparam}'>
<tr>
   <td class="head unsortable" style="width:70px">
    {#lbl_select#}
    <input type="text" class="bfl-buffer" name="buffer-id" value='{literal}{"add":{},"remove":{}}{/literal}' />
   </td>
   <td class="head ext-search cookie-teilnehmer">{#lbl_teilnehmer#}</td>
   <td class="head ext-search cookie-telefon">{#lbl_telefon#}</td>
   <td class="head ext-search cookie-email">{#lbl_email#}</td>
</tr>

{include file=app/list/list.konferenz_teilnehmer_zuweisen.tpl}

</table>

<table class="inline-table">
    <tr style="border-bottom: 0px none;">
        <td align="center" style="margin: 0px; padding: 0px;">
        <input class=" button" type="submit" alt="{$smarty.config.btn_lbl_insert}" value="{$smarty.config.btn_lbl_insert}" name="action[update]">
        <input class="button" type="submit" alt="{$smarty.config.btn_lbl_cancel}" value="{$smarty.config.btn_lbl_cancel}" name="action[cancel]">
        </td>
    </tr>
</table>