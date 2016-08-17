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
         <td class="subhead" style="width:10%" align="center">{#head_selection#}</td>
         <td class="subhead" style="width:13%" align="center">{#head_group#}</td>
         <td class="subhead">{#head_bez#}</td>
      </tr>

      {section name=i loop=$fields.code.value}
      <tr>
         <td class="edt" align="center">
            <span onclick="selectCodepickerCode('{$fields.code.value[i]}','{$fields.bez.value[i]}');">
               <img class="picker-img" src="media/img/base/btn_item.gif" alt="Auswählen"/>
            </span>
         </td>
         <td class="edt">
               {$fields.grp.value[i]}</td>
         </td>

         <td class="edt">
            {$fields.bez.value[i]}
            <div style="display:none;" class="grad-info-{$fields.code.value[i]}">
               <span class="grad-1">{$fields.grad1.value[i]}</span>
               <span class="grad-2">{$fields.grad2.value[i]}</span>
               <span class="grad-3">{$fields.grad3.value[i]}</span>
               <span class="grad-4">{$fields.grad4.value[i]}</span>
               <span class="grad-5">{$fields.grad5.value[i]}</span>
            </div>
         </td>
      </tr>
      {/section}
   </table>
</div>
{/if}