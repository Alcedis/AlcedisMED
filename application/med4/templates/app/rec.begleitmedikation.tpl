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

{html_set_header caption=#head_bm# class="head"}
{html_set_row field="wirkstoff"        caption=$_wirkstoff_lbl          input=$_wirkstoff}
{html_set_row field="applikation"      caption=$_applikation_lbl        input=$_applikation}

{html_set_html field="dosis_wert" html="
<tr>
   <td class='lbl'>$_dosis_wert_lbl</td>
   <td class='edt'>$_dosis_wert $_dosis_einheit</td>
</tr>
"}

{html_set_row field="beginn"           caption=$_beginn_lbl             input="$_beginn <span style='padding-left: 17px'>$_beginn_nb_lbl $_beginn_nb</span>"}
{html_set_row field="ende"             caption=$_ende_lbl               input=$_ende}
{html_set_row field="fortsetzung"      caption=$_fortsetzung_lbl        input=$_fortsetzung}
{html_set_row field="intermittierend"  caption=$_intermittierend_lbl    input=$_intermittierend}

{html_set_header caption=#head_bem# class="head"}
{html_set_header caption=$_bem      class="edt"}

</table>
{html_set_buttons modus=$button}

<div>
{$_begleitmedikation_id}
{$_patient_id}
{$_erkrankung_id}
</div>