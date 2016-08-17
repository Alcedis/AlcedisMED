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

{section name=i loop=$fields.patient_id.value}

{html_odd_even var="class" key=%i.index%}

<tr>
   <td class="{$class}">{$fields.nachname.value[i]}</td>
   <td class="{$class}">{$fields.vorname.value[i]}</td>
   <td class="{$class}">{$fields.geburtsdatum.value[i]}</td>
   <td class="{$class}">{$fields.patient_nr.value[i]}</td>
   <td class="{$class}">{$fields.diagnosen.value[i]}</td>

   {if strlen($fields.erkrankungen.value[i]) == 0}
      <td class="{$class}" colspan="2" align="right">
         <em>{#no_disease#}</em>
      </td>
   {else}
      <td class="{$class}" align="right">
         <select name="erkrankung[{$fields.hl7_diagnose_id.value[i]}]" {if isset($patient_error) && in_array($fields.hl7_diagnose_id.value[i], $patient_error) == true} class="imp-pat-error"{/if}>
            <option></option>
            {$fields.erkrankungen.value[i]}
         </select>
      </td>
      <td class="{$class}" align="center">
         <input type="image" name="action[allocate][{$fields.hl7_diagnose_id.value[i]}]" src="media/img/base/add_normal.png">
      </td>
   {/if}
</tr>
{sectionelse}
<tr>
   <td class="even no-data" colspan="7">{#no_dataset#}</td>
</tr>
{/section}