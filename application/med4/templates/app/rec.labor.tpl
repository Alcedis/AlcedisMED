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

<table class="formtable" >

{html_set_header caption=#head_labor#    class="head"}
{html_set_row field="datum" caption=$_datum_lbl  input=$_datum}

<tr>
   <td class="lbl">
      {$_vorlage_labor_id_lbl}
   </td>
   <td class="edt">
      {if strlen($_labor_id_value) > 0}
         <input type="hidden" name="vorlage_labor_id" value="{$_vorlage_labor_id_value}" />
         <strong>{$labor_name}</strong>
      {else}
         {$_vorlage_labor_id}
      {/if}
   </td>
</tr>
{html_set_header caption=#head_messwerte# class="head dyn"}
<tr>
   <td class="msg" colspan="2">
      <table class="inline-table append">
      {if strlen($extForm)}
         {include file=$extForm}
      {else}
        <tr style="display:none;"><td style="display:none;"><!-- --></td></tr>
      {/if}
      </table>
      <input type="hidden" name="allocated_data" value="{$labor_wert_data}"/>
      <input type="hidden" name="pos_errors" value="{$pos_errors}"/>
   </td>
</tr>
<tr{if strlen($extForm)} style="display:none;"{/if}>
   <td colspan="2" class="msg">
      <div class="msgbox">{#msg_no_vorlage#}</div>
   </td>
</tr>
{html_set_header caption=#head_bem# class="head"}
{html_set_header field="bem" caption=$_bem      class="edt"}

</table>
{html_set_buttons modus=$button}

<div>
<input type="hidden" value="{$_labor_id_value}" name="form_id" />
<input type="hidden" value="{$_vorlage_labor_id_value}" name="dlist_param" />
{$_labor_id}
{$_patient_id}
{$_erkrankung_id}
</div>