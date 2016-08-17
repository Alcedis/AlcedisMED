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

{if $form_id}
	<table class="formtable" style="margin-bottom:6px">
	{html_set_header class="head" caption=#head_aktivitaet#}
		<tr>
		   <td class='lbl'>{$_inaktiv_lbl}</td>
		   <td class='edt'>
		   	{$_inaktiv} {if $stateButton !== false}<input style="margin:0 5px" type="submit" class="button" name="action[{$stateButton}]" value="{#btn_lbl_update#}" />{/if}
		   </td>
		</tr>
	</table>
	
	{if $inaktiv == true}
		<div class="warn">
			{#info_inaktiv#}
		</div>
	{else}
		<div class="green-info">
			{#info_aktiv#}
		</div>
	{/if}
{/if}

<table class="formtable">

{html_set_header class="head" caption=#head_kv#}

{html_set_row caption=$_name_lbl       input=$_name}
{html_set_row caption=$_gkv_lbl        input=$_gkv}
{html_set_row caption=$_iknr_lbl       input=$_iknr}
{html_set_row caption=$_vknr_lbl       input=$_vknr}
{html_set_row caption=$_strasse_lbl    input=$_strasse}

<tr>
   <td class='lbl'>{$_plz_lbl} {#lbl_slash#} {$_ort_lbl}</td>
   <td class='edt'>{$_plz} {#lbl_slash#} {$_ort}</td>
</tr>

{html_set_row caption=$_land_lbl       input=$_land}
{html_set_row caption=$_bundesland_lbl input=$_bundesland}
{html_set_row caption=$_telefon_lbl    input=$_telefon}
{html_set_row caption=$_telefax_lbl    input=$_telefax}
{html_set_row caption=$_email_lbl      input=$_email}



{html_set_header class="head" caption=#head_bem#}
{html_set_header class="edt"  caption=$_bem}

</table>
{html_set_buttons modus=$button}

{$_vorlage_krankenversicherung_id}