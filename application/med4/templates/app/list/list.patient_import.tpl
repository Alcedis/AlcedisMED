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
   <td class="{$class}" align="center">
   	<input src="media/img/base/add_normal.png" type="image" name="action[patient][{$fields.id.value[i]}]" />
  	</td>
	<td class="{$class}">{$fields.nachname.value[i]}</td>
	<td class="{$class}">{$fields.vorname.value[i]}</td>
	<td class="{$class}"><span style="font-size:10pt"><!-- -->{$fields.geburtsdatum.value[i]}</span></td>
	<td class="{$class}"><span style="font-size:10pt"><!-- -->{$fields.patient_nr.value[i]}</span></td>
	<td class="{$class}"><span style="font-size:10pt"><!-- -->{$fields.aufnahme_nr.value[i]}</span></td>
	<td class="{$class}" align="right"><span style="font-size:10pt"><!-- -->{$fields.createtime.value[i]}</span></td>
	<td class="{$class}" align="right">
		<select name="erkrankung[{$fields.id.value[i]}]" {if isset($patient_error) && in_array($fields.id.value[i], $patient_error) == true} class="imp-pat-error"{/if}>
			<option><!-- --></option>
         {html_options values=$SESSION.sess_recht_erkrankung output=$SESSION.sess_recht_erkrankung_bez selected=$fields.erkrankung.value[i]}
		</select>
	</td>
    <td class="{$class}">
      <input class="popup-trigger" name="action[import][{$fields.id.value[i]}]" type="image" src="media/img/base/import.png" alt="" />
      <div class="above center info-popup">{#direct_import#}</div>
   </td>
</tr>
{sectionelse}
<tr>
	<td class="even no-data" colspan="9">{#no_dataset#}</td>
</tr>
{/section}