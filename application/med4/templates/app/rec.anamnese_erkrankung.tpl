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

{html_set_header caption=#head_erkrankung# class="head"}

{html_set_row field="erkrankung"  caption=$_erkrankung_lbl input=$_erkrankung}
{html_set_row field="morphologie"            caption=$_morphologie_lbl           input=$_morphologie}
{html_set_row field="jahr"    caption=$_jahr_lbl   input=$_jahr}

{html_set_row field="aktuell"    caption=$_aktuell_lbl   input=$_aktuell}
{html_set_row field="therapie1"  caption=$_therapie1_lbl input="$_therapie1<div style='margin-top:10px'>$_therapie2</div><div style='margin-top:10px'>$_therapie3</div>"}

{html_set_header caption=#head_bem# class="head"}
{html_set_header field="bem" caption=$_bem      class="edt"}

</table>
{html_set_ajax_buttons modus=$button}

<div>
<input type="hidden" name="sess_pos" value="{$sess_pos}" />
{$_anamnese_erkrankung_id}
{$_anamnese_id}
{$_patient_id}
{$_erkrankung_id}
</div>
