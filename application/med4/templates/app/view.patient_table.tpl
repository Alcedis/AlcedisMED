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

<table class="listtable sidebar">
   <tr>
      <td class="head unsortable" style="width:25px"></td>
      <td class="head">{#typ#}</td>
      <td class="head">{#info#}</td>

      <td class="head unsortable" style="width:100px"></td>
      <td class="head unsortable" style="width:20px"></td>
      <td class="head" style="width:16px" align="center"></td>
   </tr>

{section name=i loop=$fields.form_id.value}

{html_odd_even var="class" key=%i.index%}

<tr class="{$fields.class.value[i]}">
   <td class="{$class}" style="width: 25px" ><a href="index.php?page={$fields.form.value[i]}&amp;{$fields.class.value[i]}_id={$fields.form_id.value[i]}{if $fields.kpdirty.value[i] == 1}&amp;convertdoc=kd{$fields.form_id.value[i]}{/if}" class="edit"></a></td>
   <td class="{$class}" style="width: 90px" ><strong>{$fields.form_name.value[i]}</strong></td>
   <td class="{$class}">{$fields.info.value[i]}</td>
   <td class="{$class}" align="right">
      {if strlen($fields.locked.value[i])}
         <span style="font-size:9pt">{$fields.locked.value[i]}/{$fields.total.value[i]}</span>
      {/if}
   </td>
   <td class="{$class}" align="center" >
      <a href="index.php?page=lock&amp;form={$fields.class.value[i]}&amp;location=view.patient&amp;selected={$fields.status_id.value[i]}">
         <img src="media/img/app/lock/{$fields.status_lock.value[i]}.png" alt="{$fields.status_lock.value[i]}" title=""/>
      </a>
   </td>
   <td class="{$class}" align="center">
        <span style="display:none">{$fields.status.value[i]}</span>
      {if in_array($fields.class.value[i], array('behandler', 'abschluss', 'aufenthalt'))}
         <a href="index.php?page={$fields.form.value[i]}&amp;{$fields.class.value[i]}_id={$fields.form_id.value[i]}">
      {else}
         <a href="index.php?page=status&amp;patient_id={$fields.patient_id.value[i]}&amp;selected={$fields.status_id.value[i]}&amp;location=patient">
      {/if}
         <img class="popup-trigger" src="media/img/app/ampel/{$fields.status.value[i]}.png" title="" alt="{$fields.status.bez[i]}" />
         <div class="info-popup above before" style="display:none;">{#lbl_status#}{#lbl_ddot#} {$fields.status.bez[i]}</div>
      </a>
   </td>
</tr>
{sectionelse}
<tr>
   <td class="even no-data" colspan="7">{#no_dataset#}</td>
</tr>
{/section}
</table>