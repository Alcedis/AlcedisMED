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

<table class="formtable">
   <tr>
     <td class="head" align="center">{#head_suche#}</td>
   </tr>
</table>

{if isset($error_msg)}
   <div class="info"><br><strong>{#lbl_error#}</strong><br>{$error_msg}</div>
{else}

<div class="scroll-content">
   <table class="formtable">
      <tr>
         <td class="subhead" style="width:10%" align="center">{#lbl_auswahl#}</td>
         <td class="subhead" style="width:13%" align="center">{#lbl_code#}</td>
         <td class="subhead">{#lbl_krankheit#}</td>
      </tr>

      {section name=i loop=$fields.sub_class.value}
      	{if $fields.sub_level.value[i] == "3"}
      		<tr>
      			<td class="edt" align="center">
      			   {if $fields.selectable.value[i] == 1}
      			   <span onclick="selectCodepickerCode('{$fields.code.value[i]}','{$fields.description.value[i]}');">
      			      <img class="picker-img" src="media/img/base/btn_item.gif" alt="Auswählen"/>
      			   </span>
      			   {/if}
      			</td>
      			<td class="edt" align="center"><strong>{$fields.code.value[i]}</strong></td>
      			<td class="edt"><strong>{$fields.description.value[i]}</strong></td>
      		</tr>
      	{/if}
      	{if $fields.sub_level.value[i] == "4" OR $fields.sub_level.value[i] == "5"}
      		<tr>
      			<td class="edt" align="center">
      			   {if $fields.selectable.value[i] == 1}
      			      <span onclick="selectCodepickerCode('{$fields.code.value[i]}','{$fields.description.value[i]}');">
      			         <img class="picker-img" src="media/img/base/btn_item.gif" alt="Auswählen"/>
      			      </span>
      			   {/if}
      			</td>
      			<td class="edt" align="center"><strong>{$fields.code.value[i]}</strong></td>
      			<td class="edt">{$fields.description.value[i]}</td>
      		</tr>
      	{/if}
      {/section}
   </table>
</div>
{/if}