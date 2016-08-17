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

{if isset($error_msg)}
   <div class="info"><br><strong>{#lbl_error#}</strong><br>{$error_msg}</div>
{else}
   <table class="formtable" style="margin-top:-1px;">
      <tr>
         <td class="head" colspan="7" align="center">{#caption#}</td>
      </tr>
      <tr>
         <td class="subhead" align="center"  style="width:70px;">{#lbl_auswahl#}</td>
         <td class="subhead" style="width:80px;" align="center">{#lbl_iknr#}</td>
         <td class="subhead" style="width:150px;">{#lbl_name#}</td>
         <td class="subhead" style="width:80px;" align="center">{#lbl_vknr#}</td>
         <td class="subhead" style="width:165px;">{#lbl_ort#}</td>
         <td class="subhead" style="width:165px;">{#lbl_strasse#}</td>
         <td class="subhead" align="center">{#lbl_plz#}</td>
      </tr>
</table>
<div class="scroll-content">
<table class="formtable">
   {section loop=$fields.iknr.value name=i}
      <tr>
         <td class="edt" align="center" style="width:70px;">
            <span onclick="selectCodepickerCode('{$fields.iknr.value[i]}','{$fields.name.value[i]}');">
               <img class="picker-img" src="media/img/base/btn_item.gif" alt="Auswählen"/>
            </span>
         </td>
         <td class="edt" style="width:80px;" align="center">{$fields.iknr.value[i]}</td>
         <td class="edt" style="width:150px;">{$fields.name.value[i]}</td>
         <td class="edt" style="width:80px;" align="center">{$fields.vknr.value[i]}</td>
         <td class="edt" style="width:165px;">{$fields.ort.value[i]}</td>
         <td class="edt" style="width:165px;">{$fields.strasse.value[i]}</td>
         <td class="edt" align="center">{$fields.plz.value[i]}</td>
      </tr>
   {/section}
   </table>
{/if}
</div>