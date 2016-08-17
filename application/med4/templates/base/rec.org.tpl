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

   {html_set_header caption=#head_org# class=head}
   {html_set_row caption=$_name_lbl                      input=$_name}
   {html_set_row caption=$_namenszusatz_lbl              input=$_namenszusatz}
   {html_set_header caption=#head_kontakt# class=head}
   {html_set_row caption=$_strasse_lbl                   input=$_strasse add=$_hausnr}
   {html_set_row caption=$_plz_lbl                       input=$_plz add=$_ort}
   {html_set_row caption=$_telefon_lbl                   input=$_telefon}
   {html_set_row caption=$_telefax_lbl                   input=$_telefax}
   {html_set_row caption=$_email_lbl                     input=$_email}
   {html_set_row caption=$_website_lbl                   input=$_website}
   {html_set_row caption=$_staat_lbl                      input=$_staat}
   {html_set_row caption=$_bundesland_lbl                input=$_bundesland}
   {html_set_header caption=#head_ident# class=head}
   {html_set_row caption=$_ik_nr_lbl                     input=$_ik_nr}
   {html_set_row caption=$_kr_kennung_lbl                input=$_kr_kennung}

   {if $SESSION.sess_rolle_code == 'admin'}
      {html_set_row caption=$_mandant_lbl                   input=$_mandant}
   {/if}


	{if $SESSION.sess_rolle_code == 'admin'}
	{html_set_header class="head"    caption=#head_logo# }
		{if strlen($logo)}
	   <tr>
	      <td class="lbl">
	         {$_logo_lbl}
	      </td><td class="edt">
	         <img class='thumb-img' alt='thumb' src='index.php?page=rec.org&amp;type=thumbnail&amp;org_id={$_org_id_value}'/>
	         <input type='hidden' name='logo' value="{$logo}"/> <input type="hidden" name="ext" value="{$ext}"/>
	         <div style="float:right">{$btn_unset_file}</div>
	      </td>
	   </tr>
		{else}
	   	{html_set_row     field="logo"      caption=$_logo_lbl      input="<input type='file' name='logo'/>" }
		{/if}
	{/if}

   {html_set_header class="head"    caption=#head_bem# }
   {html_set_header class="edt"     caption=$_bem}

</table>

{html_set_buttons modus=$button}
<div>
{$_org_id}
{$_parent_id}
{if $SESSION.sess_rolle_code != 'admin'}
<input type="hidden" name="mandant" value="{$_mandant_value}" />
{/if}
</div>