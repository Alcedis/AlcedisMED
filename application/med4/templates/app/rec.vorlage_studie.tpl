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

{if $freigabe == true || $inaktiv == true}
	<table class="formtable" style="margin-bottom:6px">
	{html_set_header class="head" caption=#head_aktivitaet#}
		<tr>
		   <td class='lbl'>{$_inaktiv_lbl}</td>
		   <td class='edt'>
		   	{$_inaktiv} {if $stateButton !== false}<input style="margin:0 5px" type="submit" class="button btnconfirm" name="action[{$stateButton}]" value="{#btn_lbl_update#}" />{/if}
		   </td>
		</tr>
	</table>

	{if $inaktiv == true}
		<div class="warn">
			{#info_inaktiv#}
		</div>
	{else}
		<div class="green-info">
			{#info_freigabe#}
		</div>
	{/if}
{/if}

<table class="formtable">

{html_set_header class="head" caption=#head_studie#}
{html_set_row caption=$_bez_lbl          input=$_bez}
{html_set_row caption=$_art_lbl          input=$_art}
{html_set_row caption=$_studientyp_lbl   input=$_studientyp}
{html_set_row caption=$_erkrankung_lbl   input=$_erkrankung}
{html_set_row caption=$_indikation_lbl   input=$_indikation}
{html_set_row caption=$_ethikvotum_lbl   input=$_ethikvotum}
{html_set_row caption=$_beginn_lbl       input=$_beginn}
{html_set_row caption=$_ende_lbl         input=$_ende}
{html_set_row caption=$_leiter_lbl       input=$_leiter}
{html_set_row caption=$_telefon_lbl      input=$_telefon}
{html_set_row caption=$_telefax_lbl      input=$_telefax}
{html_set_row caption=$_email_lbl        input=$_email}
{if $krz_protokoll}
	<tr>
	   <td class='lbl'>{$_krz_protokoll_lbl}</td>
	   <td class='edt'>
	   	<div style="float:left">{$krz_protokoll_text} {$err_krz_protokoll}</div>
	      <div style="float:right">{$btn_unset_file}</div>
	   	<input type="hidden" name="krz_protokoll" value="{$krz_protokoll}" />
	   </td>
	</tr>
{else}
	<tr>
	   <td class='lbl'>{$_krz_protokoll_lbl}</td>
	   <td class='edt'><input type='file' size='50' name='krz_protokoll' />{$err_krz_protokoll}</td>
	</tr>
{/if}

{html_set_row caption=$_krz_protokoll_version_lbl        input=$_krz_protokoll_version}

{if $protokoll}
	<tr>
	   <td class='lbl'>{$_protokoll_lbl}</td>
	   <td class='edt'>
	   	<div style="float:left">{$protokoll_text} {$err_protokoll}</div>
	      <div style="float:right">{$btn_unset_file}</div>
	   	<input type="hidden" name="protokoll" value="{$protokoll}" />
	   </td>
	</tr>
{else}
	<tr>
	   <td class='lbl'>{$_protokoll_lbl}</td>
	   <td class='edt'><input type='file' size='50' name='protokoll' />{$err_protokoll}</td>
	</tr>
{/if}

{html_set_row caption=$_protokoll_version_lbl        input=$_protokoll_version}

{html_set_row caption=$_freigabe_lbl        input=$_freigabe}
{html_set_header class="head" caption=#head_bem#}
{html_set_header class="edt" caption=$_bem}


</table>
{html_set_buttons modus=$button}
<div>
{$_vorlage_studie_id}
</div>
