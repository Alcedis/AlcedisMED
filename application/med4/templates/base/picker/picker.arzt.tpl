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

{include file="base/picker/navigation.tpl"}

<table class="listtable">
   <tr class="lhead">
      <td class="head unsortable edit">{#head_add#}</td>
      <td class="head">{#head_prefix#}</td>
      <td class="head">{#head_nachname#}</td>
      <td class="head">{#head_vorname#}</td>
      <td class="head">{#head_fachabteilung#}</td>
   </tr>
   {foreach from=$data item="record" key="i"}
   {html_odd_even var="class" key=%i.index%}
   <tr>
      <td class="{$class}" align="center">
         <input type="hidden" name="add_value" value="{$record.user_id}"/>
         {if $multi == 'true'}
            <input type="checkbox" class="input" {if in_array($record.user_id, $preSelection)}checked="checked"{/if}name="add_entry"/>
         {else}
            <img src="media/img/base/add-user-small.png" class="picker-add-user"/>
         {/if}
      </td>
      <td class="{$class}">{$record.prefix}</td>
      <td class="{$class}">{$record.nachname}</td>
      <td class="{$class}">{$record.vorname}</td>
      <td class="{$class}">{$record.fachabteilung}</td>
   </tr>
   {foreachelse}
   <tr class="no-data">
      <td class="{$class}" colspan="5">{#no_data#}</td>
   </tr>
   {/foreach}
</table>
<table class="formtable">
   <tr>
      <td class="msg" colspan="5" align="center">
         {if $multi == 'true'}
            <input type="button" class="button" name="btn_add" value="{#btn_add#}"/>
         {/if}
         <input type="button" class="button" name="btn_cancel" value="{#btn_cancel#}"/>
      </td>
   </tr>
</table>