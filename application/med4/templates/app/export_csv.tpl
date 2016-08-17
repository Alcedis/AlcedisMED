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

{if $view == 'error'}

{elseif $view == 'result'}
	<table class="table" width="100%">
	{html_set_header caption=#head_export# class="head"}
	<tr>
		<td class="head" align="center">{#anz_ds#}</td>
		<td class="head">{#tabelle#}</td>
	</tr>
	{section name=i loop=$csv_files}
	<tr>
		<td class="edt" align="center">{$rowcount[i]}</td>
		<td class="edt"><a class="visible-anchor" href="{$csv_urls[i]}" target="_blank">{$csv_files[i]}</a></td>
	</tr>
	{/section}
	{if count( $csv_files ) > 1 }
	<tr>
		<td class="edt"colspan="2" align="center" style="font-weight:bold;" >{#lbl_download#}</td>
	</tr>
	<tr>
		<td class="edt"colspan="2" align="center"><a class="visible-anchor" href="{$zip_url}" >{$zip_filename}</a></td>
	</tr>
	{/if}
	</table>
{else}
	<table class="formtable">
		{html_set_header caption=#head_export# class="head"}
		{html_set_row    caption=$_sel_tabelle_lbl      input=$_sel_tabelle}
		{html_set_row    caption=$_sel_erkrankung_lbl   input=$_sel_erkrankung}
		<tr>
			<td class="edt" colspan="2" align="center">
				{html_set_buttons modus='export' class='button_large' table=false}
			</td>
		</tr>
	</table>
{/if}
