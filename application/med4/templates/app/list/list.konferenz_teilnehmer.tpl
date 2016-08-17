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

{section name=i loop=$fields.konferenz_teilnehmer_id.value}
	{html_odd_even var="class" key=%i.index%}

	<tr>
	   <td class="{$class}">
	      <div><strong>{$fields.user.value[i]}</strong> {if strlen($fields.email.value[i])}<span style="font-size:8pt"> / {$fields.email.value[i]}<span>{/if}</div>
	      <div style="font-size:9pt">{$fields.ort.value[i]}</div>
	      <div style="font-size:9pt">{$fields.fachabteilung.value[i]}</div>
	      <div style="font-size:9pt">{$fields.telefon.value[i]}</div>
	   </td>
	   <td class="{$class}" align="center">
          <input type="checkbox" class="bfl-buffer-input email" name="email-{$fields.konferenz_teilnehmer_id.value[i]}|{$fields.user_id.value[i]}" value="1" />
       </td>
	   <td class="{$class}" align="center">
	   	  <div style="font-size:9pt">{$fields.email_status.value[i]}</div>
	   </td>
	   <td class="{$class}" align="center">
	      <input type="checkbox" class="bfl-buffer-input teilgenommen" name="teilgenommen-{$fields.konferenz_teilnehmer_id.value[i]}" value="1" {if $fields.teilgenommen.value[i] == 1}checked="checked"{/if} />
	   </td>
	   <td class="{$class}" align="center">
	      <input type="checkbox" class="bfl-buffer-input entfernen" name="entfernen-{$fields.konferenz_teilnehmer_id.value[i]}" value="1"/>
	   </td>
	</tr>
{sectionelse}
	<tr>
		<td class="even no-data" colspan="7">{#no_dataset#}</td>
	</tr>
{/section}