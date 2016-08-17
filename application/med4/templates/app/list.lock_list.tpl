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

<table class="listtable" border="0">
<tr>
   <td class="head">{#head_form#}</td>
   <td class="head">{#head_inhalt#}</td>
   <td class="head">{#head_bem#}</td>
   <td class="head">{#head_zeit#}</td>
   <td class="head">{#head_status#}</td>
</tr>
   {section name=i loop=$fields.status_lock_id.value}
      {html_odd_even var="class" key=%i.index%}
      <tr>
         <td class="{$class}">
            <b>{$fields.form_name.value[i]}</b>
         </td>
         <td class="{$class}" style="width: 30%;">
            {$fields.form_data.value[i]}
         </td>
         <td class="{$class}">
            {$fields.bem.value[i]}
         </td>
         <td class="{$class}">
            {$fields.time.value[i]}
         </td>
         <td class="{$class}">
            {$fields.lock_status.value[i]}
         </td>
      </tr>
   {/section}
</table>