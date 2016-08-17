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

{html_set_header class="head" caption=#head_fragebogen#}
{html_set_row caption=$_bez_lbl   input=$_bez}
{html_set_row caption=$_art_lbl   input=$_art}
{html_set_header class="head" caption=#head_fragen#}


   {if $freigabe != 1 && $inaktiv != 1}
      <tr>
         <td colspan="2" class="lbl" align="right">
            <input id="add_question" class="button_large" type="button" value="{#add_frage#}"/>
         </td>
      </tr>
   {/if}

   <tr><td colspan="2" class="msg">
      <table id="append" class="inline-table">
         <tr>
           <td class="subhead" style="width:15%;">{#lbl_frage#}</td>
           <td class="subhead"></td>
           <td class="subhead" align="center" style="width:20%;">{#lbl_antwort#}</td>
           {if $freigabe != 1 && $inaktiv != 1}
           		<td class="subhead" align="center" style="width:10%;">{#lbl_delete#}</td>
           {/if}
         </tr>

   <!-- Bereich der dynamisch hinzugefügt wird -->

   {if $fragen}
      {foreach from=$fragen item=frage key=i}
         <tr>
           <td class="lbl" style="width:15%;">{#lbl_fragestellung#}<span style="color: red;">*</span></td>
           <td class="edt" align="center"><input style="width:460px;" class="input" name="question[frage][]" type="text" value="{$frage.frage}"/></td>
           <td class="edt" align="center" style="width:20%;"><input class="input" name="question[min][]" type="text" size="1" maxlength="3" value="{$frage.val_min}"/> {#lbl_min#}
               <input id="q{$i+1}_max" class="input" name="question[max][]" type="text" size="1" maxlength="3" value="{$frage.val_max}"/> {#lbl_max#}</td>

           {if $freigabe != 1 && $inaktiv != 1}
           		<td class="edt" align="center" style="width:10%;">
           			<img class="img_del_btn" src="media/img/base/btn_code_reset.png" style="cursor:pointer;" alt="{#lbl_delete#}"/>
           		</td>
           {/if}
         </tr>
      {/foreach}
   {/if}
         </table>
      </td>
   </tr>

{html_set_header class="head" caption=#head_freigabe#}
{html_set_row caption=$_freigabe_lbl input=$_freigabe}
{html_set_header class="head" caption=#head_bem#}
{html_set_header class="edt"  caption=$_bem}

</table>
{html_set_buttons modus=$button}

{$_vorlage_dokument_id}