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

<table class="listtable" id="main_table">
<tr>
   <td class="head edit unsortable" align="center">{#select#}</td>
   <td class="head">{#lbl_org#}</td>
   <td class="head" style="width:220px">{#lbl_recht#}</td>
   <td class="head" align="right" style="padding-right:10px">{#lbl_erkrankung#}</td>
</tr>

{section name=i loop=$fields.recht_id.value}
{html_odd_even var="class" key=%i.index%}
<tr class="list-item">
   <td class="{$class}" align="center"><a class="folder" href="index.php?page=rollenauswahl&amp;recht_id={$fields.recht_id.value[i]}&amp;rolle_selected=true"></a></td>
   <td class="{$class}">{$fields.org_id.value[i]}</td>
   <td class="{$class}"><b>{$fields.rolle_bez.value[i]}</b></td>
   <td class="{$class}" align="right" style="width:350px;padding-right:10px">
      <span style="font-size:9pt">{$fields.erkrankung_bez.value[i]}</span>
   </td>
</tr>

{sectionelse}
<tr>
	<td class="edt" colspan="4">{#no_dataset#}</td>
</tr>
{/section}

</table>