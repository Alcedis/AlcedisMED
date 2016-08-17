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
			{#info_freigabe#}
		</div>
	{/if}
{/if}

<table class="formtable">

{html_set_header class="head" caption=#head_labor#}
{html_set_row caption=$_bez_lbl       input=$_bez}


{html_set_header class="head" caption=#head_parameter#}
</table>

<div class="dlist" id="dlist_wert">
   {if $freigabe == false}
   <div class="add">
      <input class="button" type="button" name="vorlage_labor_wert" value="Hinzuf&uuml;gen" onclick="execute_request(this,'rec.vorlage_labor_wert', null, ['vorlage_labor_id'])"/>
   </div>
   {/if}

</div>


<table class="formtable msg">
{html_set_header class="head" caption=#head_freigabe#}
{html_set_row caption=$_freigabe_lbl       input=$_freigabe}
{html_set_row caption=$_gueltig_von_lbl    input=$_gueltig_von}
</table>

<table class="formtable msg">
<tr>
   <td class="lbl">{$_gueltig_bis_lbl} </td>
   
   {if strlen($_vorlage_labor_id_value) && $SESSION.sess_rolle_code == 'supervisor' && false}
		<td class="edt" style="width:120px">
	      {$_gueltig_bis}
	   </td>
	   <td class="edt">
	      <input style="margin-left: 10px" type="submit" alt="Verlängern" value="Verlängern" name="action[verlaengern]" class="button" />
		</td>
	{else}
	   <td class="edt">
	      {$_gueltig_bis}
	   </td>
	{/if}
</tr>
</table>

<table class="formtable msg">
{html_set_header class="head" caption=#head_bem#}
{html_set_header class="edt"  caption=$_bem}

</table>
{html_set_buttons modus=$button}

<div>
{$_vorlage_labor_id}
</div>