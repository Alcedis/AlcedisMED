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

{section name=i loop=$fields.history_id.value}
{html_odd_even var="class" key=%i.index%}
   <tr>
   	<td class="{$class}" align="left">
		   {if $fields.login_acc.value[i] == 1}
         	<span style="color:green">
         		{#lbl_login_passed#}
         	</span>
            <img src="media/img/base/btn_ok_small.gif" alt=""/>
	      {else}
         	<span style="color:red">
         		{#lbl_login_error#}
         	</span>
	      {/if}
      </td>
      <td class="{$class}">{$fields.history_id.value[i]}</td>
      <td class="{$class}">{$fields.login_date_de.value[i]}</td>
      <td class="{$class}">{$fields.login_time_de.value[i]}</td>
      <td class="{$class}" align="right">{$fields.login_ip.value[i]}</td>
   </tr>
{sectionelse}
   <tr>
	   <td class="edt" colspan="5"><b>{#lbl_no_user#}</b></td>
   </tr>
{/section}