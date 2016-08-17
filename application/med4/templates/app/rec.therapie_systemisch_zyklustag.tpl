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

<table class="formtable">

{html_set_header caption=#head_zyklustag# class="head"}
{html_set_row                       caption=#lbl_vorlage#    input=$vorlage}
<tr>
	<td class="lbl">{#lbl_zyklus_nr#}</td>
	<td class="edt"><strong>{$zyklusData.zyklus_nr}</strong></td>
</tr>
{html_set_row field="datum"         caption=$_datum_lbl      input=$_datum}

<tr>
	<td class='lbl'>{$_zyklustag_lbl}</td>
	<td class='edt'>
		{if strlen($_therapie_systemisch_zyklustag_id_value)}
			<strong>{$_zyklustag_value}</strong>
			<input type="hidden" name="zyklustag" value="{$_zyklustag_value}" />
		{else}
			{$_zyklustag}
		{/if}
	</td>
</tr>

{html_set_row field="org_id"        caption=$_org_id_lbl     input=$_org_id}
{html_set_row field="user_id"       caption=$_user_id_lbl    input=$_user_id}
<tr>
	<td class="lbl">{#lbl_groesse#}</td>
	<td class="edt"><strong><!--  -->{$zyklusData.groesse}</strong> {#lbl_cm#}</td>
</tr>
<tr>
	<td class="lbl">{#lbl_gewicht#}</td>
	<td class="edt"><strong><!--  -->{$zyklusData.gewicht}</strong> {#lbl_kg#}</td>
</tr>

{html_set_header caption=#head_wirkstoffgabe# class="head dyn"}
<tr>
   <td class="msg" colspan="2">
      <table class="inline-table append">
      {if strlen($extForm)}
         {include file=$extForm}
      {else}
        <tr style="display:none;"><td style="display:none;"><!-- --></td></tr>
      {/if}
      </table>
   </td>
</tr>
<tr{if strlen($extForm)} style="display:none;"{/if}>
   <td colspan="2" class="msg">
      <div class="msgbox">{#lbl_zyklustag_dokumentieren#}</div>
   </td>
</tr>
{html_set_header caption=#head_bem#    class="head"}
{html_set_row    field="mitteilung"    caption=$_mitteilung_lbl  input=$_mitteilung}
{html_set_header caption=$_bem         class="edt"}
</table>
{html_set_buttons modus=$button}

<div>
<input type="hidden" name="allocated_data" value="{$wirkstoff_data}"/>
<input type="hidden" name="pos_errors" value="{$pos_errors}"/>
{$_therapie_systemisch_zyklustag_id}
{$_therapie_systemisch_zyklus_id}
{$_therapie_systemisch_id}
{$_erkrankung_id}
{$_patient_id}
</div>