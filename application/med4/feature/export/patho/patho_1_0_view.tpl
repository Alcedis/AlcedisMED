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

{if $view_type == 'errors' }
<div class="err">
   <div class="formcaption" style="font-weight:bold; ">{$erroritem_data.nachname}, {$erroritem_data.vorname} ( {$erroritem_data.geburtsdatum} )</div>
   <div class="formcaption" style="font-weight:bold; ">{$erroritem_data.erkrankung}: {$erroritem_data.block}</div>
   <div style="font-weight:bold; margin-top:10px;">{#errors#}</div>
   {foreach from=$erroritem_data.errors item=error}
   <div style="margin-top:-5px;">{$error}</div></br>
   {/foreach}
</div>
{elseif $view_type == 'log'}
	<table style="width:100%;">
		<tr>
			<td class="head" align="center">{#head_export_download#}</td>
		</tr>
  		<tr>
     		<td><a class="visible-anchor" href="index.php?page=krbw&amp;feature=export&amp;action=download&amp;type=xml&amp;file={$zip_url}" style="font-size:14px; font-weight:bold; margin:5px">{$zip_filename}</a></td>
  		</tr>
	</table>
{elseif $view_type == 'errorlist'}
{else}
	<table class="formtable">
		{html_set_header caption=#head_export# class="head"}
		{html_set_row caption=$_von_datum_lbl           input=$_von_datum}
		{html_set_row caption=$_bis_datum_lbl           input=$_bis_datum}
		<tr>
			<td class="edt" colspan="2" align="center">
				{html_set_buttons modus='export_start' class='button_large' table=false}
			</td>
		</tr>
	</table>
{/if}