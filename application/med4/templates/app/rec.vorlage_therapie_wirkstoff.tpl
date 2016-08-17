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
{html_set_header class="head" caption=#head_wirkstoff#}

{html_set_row caption=$_wirkstoff_lbl                    input=$_wirkstoff}

{if $art === 'str'}
   {html_set_row caption=$_radionukleid_lbl                input=$_radionukleid}
{/if}

{html_set_row caption=$_dosis_lbl                           input="$_dosis $_einheit_lbl $_einheit"}

{if $art != 'str'}
{html_set_row caption=$_applikation_lbl                     input=$_applikation}
{/if}
{if $art == 'dauer'}

{html_set_row caption=$_applikationsfrequenz_lbl            input="$_applikationsfrequenz $_applikationsfrequenz_einheit"}
{html_set_row caption=$_therapiedauer_lbl                   input="$_therapiedauer $_therapiedauer_einheit"}

{else}

{html_set_row caption=$_zyklus_beginn_lbl                   input=$_zyklus_beginn}
{html_set_row caption=$_zyklus_anzahl_lbl                   input=$_zyklus_anzahl}
<tr>
	<td class="lbl">{$_zyklustag_lbl}</td>
	<td class="edt">
		<div>
			{$_zyklustag}
			{$_zyklustag02}
			{$_zyklustag03}
			{$_zyklustag04}
			{$_zyklustag05}
		</div>
		<div style="margin-top:5px">
			{$_zyklustag06}
			{$_zyklustag07}
			{$_zyklustag08}
			{$_zyklustag09}
			{$_zyklustag10}
		</div>
	</td>
</tr>
{html_set_row caption=$_zyklusdauer_lbl                     input=$_zyklusdauer}

{if $art == 'zyk'}
   {html_set_row caption=$_loesungsmittel_lbl                  input="$_loesungsmittel $_loesungsmittel_menge `$smarty.config.lbl_ml`"}
   {html_set_row caption=$_infusionsdauer_lbl                  input="$_infusionsdauer $_infusionsdauer_einheit"}
{/if}

{/if}

</table>

{html_set_ajax_buttons modus=$button}
<div>
{$_vorlage_therapie_wirkstoff_id}
{$_vorlage_therapie_id}
{$_art}
<input type="hidden" name="sess_pos" value="{$sess_pos}" />
</div>