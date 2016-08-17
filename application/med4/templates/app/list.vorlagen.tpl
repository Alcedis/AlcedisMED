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
<tr>
	<td class="head edit unsortable" ></td>
	<td class="head">{#lbl_formular#}</td>
	<td class="head unsortable" align="center" style="width:20%">{#lbl_anzahl#}</td>
   <!-- <td class="head unsortable" align="center" style="width:10%">{#lbl_hilfe#}</td>-->
</tr>

{section name=i loop=$abschnitte}
{html_odd_even var="class" key=%i.index%}
{if $abschnitte[i].show == 1}
<tr>
	<td class="{$class}" align="center"><a href="index.php?page={$abschnitte[i].location}" class="edit"></a></td>
	<td class="{$class}"><a href="index.php?page={$abschnitte[i].location}">{$abschnitte[i].caption}</a></td>
	<td align="center" class="{$class}"><a class="link" href="index.php?page={$abschnitte[i].location}"><strong>{$abschnitte[i].box}</strong></a></td>
	<!--
	<td align="center" class="{$class}">
	   <a class="link" href="{$abschnitte[i].help_file}" target="_blank">
	     <img src="media/img/base/btn_pdf_small.gif" alt="Anzeigen" />
	   </a>
	</td>-->
</tr>
{/if}
{/section}
</table>
