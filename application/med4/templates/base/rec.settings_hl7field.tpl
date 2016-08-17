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
{html_set_row caption=$_import_lbl 				input=$_import}
{html_set_row caption=$_med_feld_lbl 				input=$_med_feld}

{html_set_row caption=$_hl7_lbl input=$_hl7 add="<br/><br/>$_hl7_bereich_lbl $_hl7_bereich`$smarty.config.lbl_bereich_bsp` $_hl7_back$_hl7_back_lbl"}

{html_set_row caption=$_feld_typ_lbl  input="$_feld_typ $_feld_trim_null$_feld_trim_null_lbl"}
{html_set_header caption=#head_multiple# class=head colspan=2}
{html_set_row caption=$_multiple_lbl 				input=$_multiple}
{html_set_row caption=$_multiple_segment_lbl 	input=$_multiple_segment}
{html_set_row caption=$_multiple_filter_lbl 		input=$_multiple_filter}
{html_set_header caption=#head_ext#  class="head"}
{html_set_header caption=$_ext  class="edt"}
</table>

{html_set_ajax_buttons modus=$button}
<div>
{$_settings_hl7field_id}
{$_settings_hl7_id}
<input type="hidden" name="sess_pos" value="{$sess_pos}" />
</div>