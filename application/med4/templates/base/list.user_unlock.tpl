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

<table class="listtable">

<tr align="center">
	<td class="head unsortable" align="center" style="width:10%">{#lbl_entsperren#}</td>
	<td class="head">{#lbl_info#}</td>
	<td class="head" >{#lbl_login_name#}</td>
	<td class="head">{#lbl_ip#}</td>
	<td class="head">{#lbl_last_login_acc#}</td>
	<td class="head">{#lbl_last_login_fail#}</td>
</tr>

{section name=i loop=$user_locked}
{html_odd_even var="class" key=%i.index%}
<tr align="center">
	<td class="{$class}" >
	  <a href="{$form_rec}&amp;user_unlock_id={$user_locked[i].user_lock_id}">
	     <img src="media/img/base/apply-filter.png" alt="Freigeben" />
	  </a>
	</td>
   <td class="{$class}"><span style="font-size:9pt">{$user_locked[i].info}</span></td>
   <td class="{$class}"><strong>{$user_locked[i].loginname}</strong></td>
   <td class="{$class}">{$user_locked[i].login_ip}</td>
   <td class="{$class}">{$user_locked[i].last_login_acc} Uhr</td>
   <td class="{$class}">{$user_locked[i].last_login_fail} Uhr</td>
</tr>
{sectionelse}
<tr>
	<td class="edt" colspan="6">{#lbl_no_user#}</td>
</tr>
{/section}

</table>