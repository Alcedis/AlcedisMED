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

{section name=i loop=$fields.user_id.value}
{html_odd_even var="class" key=%i.index%}
<tr>
   <td class="{$class}" align="center">
      {if $fields.editable.value[i] == 0}
         <span style="font-size:8pt;">{#lbl_no_right#}</span>
      {else}
         <a href="index.php?page=rec.vorlage_arzt&amp;arzt_id={$fields.user_id.value[i]}" class="edit"></a>
      {/if}
   </td>
   <td class="{$class}"><strong>{$fields.name.value[i]}</strong></td>
   <td class="{$class}">
      <span style="font-size:9pt"><!--  -->
         {$fields.strasse.value[i]} {$fields.hausnr.value[i]}
         {if (strlen($fields.strasse.value[i]) || strlen($fields.hausnr.value[i])) && (strlen($fields.ort.value[i]) || strlen($fields.plz.value[i]))}
         <br/>
         {/if}
         {$fields.plz.value[i]} {$fields.ort.value[i]}
      </span>
   </td>
   <td class="{$class}"><span style="font-size:9pt"><!--  -->{$fields.fachabteilung.value[i]}</span></td>
   <td class="{$class}" align="center">{$fields.inaktiv.bez[i]}</td>
</tr>
{sectionelse}
	<tr>
	   <td class="edt" colspan="5">{#no_dataset#}</td>
	</tr>
{/section}
