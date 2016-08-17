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

{if count($top10) > 0}<div id="top10table">   <table class="formtable">      <tr>          <td class="head" colspan="3" align="center">{#head_top#} {$top10|@count}</td>      </tr>   </table>   <div class="scroll-content">      <table class="formtable">         <tr>            <td class="subhead" style="width:10%" align="center">{#lbl_auswahl#}</td>            <td class="subhead" style="width:13%" align="center">{#lbl_code#}</td>            <td class="subhead">{#lbl_bezeichnung#}</td>         </tr>         {foreach from=$top10 item=ttEntry}            <tr>               <td class="edt" style="width:10%" align="center">                 <span onclick="selectCodepickerCode('{$ttEntry.code}','{$ttEntry.description}');">                    <img class="picker-img" src="media/img/base/btn_item.gif" alt="Auswählen"/>                 </span>               </td>               <td class="edt" style="width:13%" align="center"><strong>{$ttEntry.code}</strong></td>               <td class="edt">{$ttEntry.description}</td>            </tr>            {/foreach}        </table>    </div></div>{/if}