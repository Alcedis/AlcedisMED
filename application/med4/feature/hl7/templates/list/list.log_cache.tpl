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

{section name=i loop=$fields.hl7_log_id.value}

{html_odd_even var="class" key=%i.index%}

<tr>
   <td class="{$class}" align="center">
       <a href="index.php?page=rec.log_cache&amp;feature=hl7&amp;hl7_log_id={$fields.hl7_log_id.value[i]}" >
           <img src="media/img/base/hl7_message.png" alt=""/>
       </a>
   </td>
   <td class="{$class}">
       {if $fields.status.value[i] == 'error'}
           <div>
               <div style="float:left;padding-top:2px"><img src="media/img/base/editdelete.png" alt=""/></div>
               <div style="float:left;padding-left:5px"><span style="color:red">{$fields.status.bez[i]}</span></div>
           </div>
       {else}
           <div>
               <div style="float:left;padding-top:2px"><img src="media/img/base/ok_small.png" alt=""/></div>
               <div style="float:left;padding-left:5px"><span style="color:green">{$fields.status.bez[i]}</span></div>
           </div>
       {/if}
   </td>
   <td class="{$class}">{$fields.nachname.value[i]}</td>
   <td class="{$class}">{$fields.vorname.value[i]}</td>
   <td class="{$class}">{$fields.geburtsdatum.value[i]}</td>
   <td class="{$class}">{$fields.patient_nr.value[i]}</td>
   <td class="{$class}">{$fields.aufnahme_nr.value[i]}</td>
   <td class="{$class}" align="right"><span style="font-size:8pt">{$fields.logtime.value[i]}</span></td>
</tr>
{sectionelse}
<tr>
   <td class="even no-data" colspan="8">{#no_dataset#}</td>
</tr>
{/section}