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


{html_set_header caption=#head_teilnahme#  class="head"}
{html_set_row field="vorlage_studie_id"   caption=$_vorlage_studie_id_lbl  input=$_vorlage_studie_id}
{html_set_row field="org_id"              caption=$_org_id_lbl             input=$_org_id}
{html_set_row field="user_id"             caption=$_user_id_lbl            input=$_user_id}
{html_set_row field="beginn"              caption=$_beginn_lbl             input=$_beginn}
{html_set_row field="ende"                caption=$_ende_lbl               input=$_ende}
{html_set_row field="patient_nr"          caption=$_patient_nr_lbl         input=$_patient_nr}
{html_set_row field="arm"                 caption=$_arm_lbl                input=$_arm}

{html_set_header caption=#head_bem#  class="head"}
{html_set_header caption=$_bem  class="edt"}

</table>
{html_set_buttons modus=$button}

<div>
{$_studie_id}
{$_patient_id}
{$_erkrankung_id}
</div>