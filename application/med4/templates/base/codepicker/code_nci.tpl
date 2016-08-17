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

<div id="codepicker-form"{if count($top10) > 0 && !$fromHistory} style="display:none;"{/if}>
   <table class="formtable">
      <tr>
         <td class="head" colspan="2" align="center">{#caption#}</td>
      </tr>
   </table>
   <div class="scroll-content">
      <table class="formtable">
         <tr>
            <td class="subhead" style="width:10%" align="center">{#selection#}</td>
            <td class="subhead">{#chapter#}</td>
         </tr>

         {section name=i loop=$fields.grp.value}
         <tr>
             <td class="edt" align="center" style="width:100px;">
                <span class="link" onclick='changeCodepicker({literal}{{/literal}code:"{$fields.grp.value[i]}", page : "code_nci_gruppe"{literal}}{/literal}, null, 1);'>
                   <img class="picker-img" src="media/img/base/picker-folder.png" alt="Auswählen"/>
                </span>
             </td>
             <td class="edt">
               {$fields.grp.value[i]}
             </td>
         </tr>
         {/section}
      </table>
   </div>
</div>