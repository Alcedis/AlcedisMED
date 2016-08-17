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
{html_set_header class="head" caption=#head_therapie#}
{html_set_row caption=$_bez_lbl          input=$_bez}
{html_set_row caption=$_art_lbl          input=$_art}
{html_set_row caption=$_erkrankung_lbl   input=$_erkrankung}
{if $datei}
	<tr>
	   <td class='lbl'>{$_datei_lbl}</td>
	   <td class='edt'>
	      <div style="float:left">{$datei_text} {$err_datei}</div>
	      <div style="float:right">{$btn_unset_file}</div>
	       <input type="hidden" name="datei" value="{$datei}" />
	   </td>
	</tr>
{else}
	<tr>
	   <td class='lbl'>{$_datei_lbl}</td>
	   <td class='edt'><input type='file' size='50' name='datei' />{$err_datei}</td>
	</tr>
{/if}
{html_set_header class="head" caption=#head_wirkstoffgabe#}
<tr>
   <td colspan="2" class="msg">
      <div class="dlist" id="dlist_wirkstoff">

         {if $freigabe == false}
            <div class="add">
               <select name="wirkstoff_art">{html_options options=$dd_wirkstoff_art}</select>
               <input class="button" type="button" name="wirkstoff" value="Hinzuf&uuml;gen" onclick="execute_request(this,'rec.vorlage_therapie_wirkstoff', 'wirkstoff_art', ['vorlage_therapie_id'])"/>
            </div>
         {/if}
      </div>
   </td>
</tr>


{html_set_header class="head" caption=#head_bem#}
{html_set_header class="edt" caption=$_bem}

{html_set_header class="head" caption=#head_freigabe#}
{html_set_row caption=$_freigabe_lbl     input=$_freigabe}

</table>
{html_set_buttons modus=$button}

<div>
{$_vorlage_therapie_id}
</div>