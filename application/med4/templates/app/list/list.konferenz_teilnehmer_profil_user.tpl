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

{section name=i loop=$fieldsProfil.user_id.value}
{html_odd_even var="class" key=%i.index%}
<tr>
   <td class="{$class}" align="center">
      <input type="checkbox" class="bfl-buffer-input" name="id-{$fieldsProfil.user_id.value[i]}" {if $fieldsProfil.checked.value[i] == 1} checked="checked" {/if} value="1" />
   </td>
   <td class="{$class}">
      <div><strong>{$fieldsProfil.user.value[i]}</strong></div>
      <div style="font-size:9pt">{$fieldsProfil.ort.value[i]}</div>
      <div style="font-size:9pt">{$fieldsProfil.fachabteilung.value[i]}</div>
   </td>
   <td class="{$class}">{$fieldsProfil.telefon.value[i]}</td>
   <td class="{$class}">{$fieldsProfil.email.value[i]}</td>
</tr>
{sectionelse}
<tr>
    <td class="even no-data" colspan="4">{#no_dataset#}</td>
</tr>
{/section}