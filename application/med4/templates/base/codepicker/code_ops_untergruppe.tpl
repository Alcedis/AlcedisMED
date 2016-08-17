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
     <td class="head" align="center">{$headline} ({$code})</td>
   </tr>
</table>
<div class="scroll-content">
    <table class="formtable">
      <tr>
         <td class="subhead" align="center" style="width:10%">{#lbl_auswahl#}</td>
         <td class="subhead" align="center" style="width:13%">{#lbl_code#}</td>
         <td class="subhead">{#lbl_schluessel#}</td>
      </tr>
    {section name=i loop=$fields.sub_level.value}
       {if $fields.sub_level.value[i] == "3" OR $fields.sub_level.value[i] == "4"}
    		{assign var="cur_headline" value=$fields.description.value[i]}
    		<tr>
    		   <td class="edt" align="center" style="width:10%">
    		      {if $fields.selectable.value[i] == 1}
    		         <span onclick="selectCodepickerCode('{$fields.code.value[i]}','{$fields.description.value[i]}');">
    		             <img class="picker-img" src="media/img/base/btn_item.gif" alt="Auswählen"/>
    		         </span>
    		      {/if}
    		   </td>
    			<td class="edt" align="center" style="width:13%"><strong>{$fields.code.value[i]}</strong></td>
    			<td class="edt"><strong>{$fields.description.value[i]}</strong></td>
    		</tr>
    	{/if}
    	{if $fields.sub_level.value[i] == "5" OR $fields.sub_level.value[i] == "6"}
    		<tr>
    		   <td class="edt" align="center" style="width:10%">
    		      {if $fields.selectable.value[i] == 1}
    		         <span onclick="selectCodepickerCode('{$fields.code.value[i]}','{$fields.description.value[i]}');">
    		            <img class="picker-img" src="media/img/base/btn_item.gif" alt="Auswählen"/>
    		         </span>
    		      {/if}
    		   </td>
    			<td class="edt" align="center" style="width:13%"><strong>{$fields.code.value[i]}</strong></td>
    			<td class="edt">{$fields.description.value[i]}</td>
    		</tr>
    	{/if}
    {/section}
    </table>
</div>