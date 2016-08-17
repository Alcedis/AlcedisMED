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

{html_set_header caption=#head_familie# class="head"}

{html_set_row field="karzinom"            caption=$_karzinom_lbl           input=$_karzinom}
{html_set_row field="verwandschaftsgrad"  caption=$_verwandschaftsgrad_lbl input=$_verwandschaftsgrad}
{html_set_row field="erkrankungsalter"    caption=$_erkrankungsalter_lbl   input=$_erkrankungsalter add=#lbl_jahre#}

</table>
{html_set_ajax_buttons modus=$button}

<div>
<input type="hidden" name="sess_pos" value="{$sess_pos}" />
{$_anamnese_familie_id}
{$_anamnese_id}
{$_patient_id}
{$_erkrankung_id}
</div>