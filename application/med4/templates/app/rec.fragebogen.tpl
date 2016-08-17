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

<table class="formtable msg">
   {html_set_header class="head" caption=#head_fragebogen#}
   {if strlen($_fragebogen_id_value)}
      {html_set_row caption=$_vorlage_fragebogen_id_lbl input="<b>$_vorlage_fragebogen_id_bez</b><input type='hidden' name='vorlage_fragebogen_id' value='$_vorlage_fragebogen_id_value'/>"}
   {else}
      {html_set_row caption=$_vorlage_fragebogen_id_lbl input=$_vorlage_fragebogen_id}
   {/if}
   {html_set_row caption=$_datum_lbl input=$_datum}
</table>
<table class="formtable no-align">
   {html_set_header class="head append" caption=#head_fragen#}
   {html_set_header class="msgbox" caption=#info_vorlage#}
   {foreach from=$fragen item="frage" key="i"}
      <tr class="dyn_question">
         <td class="lbl" style="width:50%;">
            {$frage.frage}
         </td>
         <td class="edt">
            {html_options class="input" name="antwort[$i]" options=$frage.range selected=$antwort.$i}
            <input type="hidden" name="vorlage_fragebogen_frage_id[{$i}]" value="{$frage.vorlage_fragebogen_frage_id}"/>
         </td>
      </tr>
   {/foreach}
   {html_set_header class="head" caption=#head_bem#}
   {html_set_header class="edt" caption=$_bem}
</table>
{html_set_buttons modus=$button}
<div>
{$_fragebogen_id}
{$_patient_id}
{$_erkrankung_id}
</div>