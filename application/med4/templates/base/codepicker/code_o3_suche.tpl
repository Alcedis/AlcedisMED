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
     <td class="head" colspan="3" align="center">{#caption#}</td>
   </tr>
</table>

{if isset($error_msg)}
   <div class="info"><br><strong>{#lbl_error#}</strong><br>{$error_msg}</div>
{else}
<div class="scroll-content">
    <table class="formtable">
      <tr>
         <td style="width:10%" class="subhead" align="center">{#lbl_auswahl#}</td>
         <td style="width:13%" class="subhead" align="center">{#lbl_code#}</td>
         <td class="subhead">{#lbl_bezeichnung#}</td>
       </tr>
      {section name=i loop=$fields.sub_level.value}
      <tr>
         <td class="edt" align="center">
             {if $fields.sub_level.value[i] != 'd'}
                <span onclick="selectCodepickerCode('{$fields.code.value[i]}','{$fields.description.value[i]}');">
                    <img class="picker-img" src="media/img/base/btn_item.gif" alt="Auswählen"/>
                </span>
             {/if}
         </td>
         <td class="edt" align="center">
               <strong>{$fields.code.value[i]}</strong>
         </td>
         <td class="edt">
               {if $fields.sub_level.value[i] != 'd'}
                   {$fields.description.value[i]}
                {else}
                   <strong>{$fields.description.value[i]}</strong>
                {/if}
         </td>
      </tr>
      {/section}
   </table>
</div>
{/if}