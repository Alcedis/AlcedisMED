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

<div class='current-conference'>
    <b>{#current_conference#}</b> {$conference}
</div>

{if $appSettings.dokument && $insertRight === true && $dokumentExists === true}
    <div class='info-msg'>{#info_create#}</div>
{/if}

<table class="listtable bfl" summary='{$bflparam}'>
<tr>
   {if $insertRight === true}
       <td class="head edit unsortable"></td>
   {/if}
   <td class="head ext-search cookie-bez" style="width:400px;">{#lbl_bez#}</td>
   <td class="head unsortable">{#info#}</td>
   <td class="head ext-search cookie-zuordnung cookietype-lookup">{#lbl_zuordnung#}
     <span class="bfl-lookup-content" style="display:none">
            {$queryMod.lookups.zuordnung}
     </span>
   </td>
   <td class="head unsortable" style="width:100px;" align="center">{#lbl_datei#}</td>
</tr>

{include file=app/list/list.konferenz_dokument.tpl}

</table>