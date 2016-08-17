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
	<td class="head"align="center">{$headline} {$code}</td>
</tr>

</table>
<div class="scroll-content">
   <table class="formtable">
      <tr>
         <td class="subhead" style="width:10%" align="center">{#lbl_auswahl#}</td>
         <td class="subhead"  style="width:13%" align="center">{#lbl_gliederung#}</td>
         <td class="subhead">{#lbl_titel#}</td>
      </tr>
      {section name=i loop=$fields.sub_class.value}
    	<tr>
         <td class="edt" align="center" style="width:100px;">
            <span class="link" style="font-weight:bold;" onclick='changeCodepicker({literal}{{/literal}code:"{$fields.code.value[i]}", page: "code_icd_untergruppe"{literal}}{/literal}, null, 2);'>
                <img alt="Auswählen" class="picker-img" src="media/img/base/picker-folder.png"/>
            </span>
         </td>
         <td class="edt" align="center" style="width:100px;">
            <strong>{$fields.code.value[i]}</strong>
         </td>
    		<td class="edt">
            {$fields.description.value[i]}
         </td>
    	</tr>
    {/section}
    </table>
</div>