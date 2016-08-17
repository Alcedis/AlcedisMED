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

{html_set_header caption=#head_abweichung#    class="head"}
{html_set_row field="datum"                  caption=$_datum_lbl                 input=$_datum}


{html_set_row field="therapieplan_id"                  caption=$_therapieplan_id_lbl                 input=$_therapieplan_id}


{html_set_row field="therapieplan_id"                  caption=#lbl_therapieart#                 input="
               $_bezug_eingriff $_bezug_eingriff_lbl<br/>
               $_bezug_strahlen $_bezug_strahlen_lbl<br/>
               $_bezug_chemo $_bezug_chemo_lbl<br/>
               $_bezug_immun $_bezug_immun_lbl<br/>
               $_bezug_ah $_bezug_ah_lbl <br/>
               $_bezug_andere $_bezug_andere_lbl<br/>
               $_bezug_sonstige $_bezug_sonstige_lbl
"}

{html_set_row field="grund"                  caption=$_grund_lbl                 input=$_grund}

{html_set_header caption=#head_bem#    class="head"}
{html_set_header caption=$_bem         class="edt"}

</table>
{html_set_buttons modus=$button}

<div>
{$_therapieplan_abweichung_id}
{$_patient_id}
{$_erkrankung_id}
</div>