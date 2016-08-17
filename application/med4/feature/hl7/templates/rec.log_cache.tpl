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
    {html_set_header caption=#head_patient# class="head"}
    <tr>
        <td class="lbl">{$_status_lbl}</td>
        <td class="edt">
            <span style="color:{if $_status_value == 'valid'}green{else}red{/if}"><b>{$_status_bez}</b></span>
        </td>
    </tr>
    {html_set_row field="nachname" caption=$_nachname_lbl  input=<b>$_nachname_value</b>}
    {html_set_row field="vorname" caption=$_vorname_lbl  input=<b>$_vorname_value</b>}
    {html_set_row field="geburtsdatum" caption=$_geburtsdatum_lbl  input=<b>$_geburtsdatum_value</b>}
    {html_set_row field="patient_nr" caption=$_patient_nr_lbl  input=<b>$_patient_nr_value</b>}
    {html_set_row field="aufnahme_nr" caption=$_aufnahme_nr_lbl  input=<b>$_aufnahme_nr_value</b>}

    <!--
    {html_set_header caption=#head_status# class="head"}
    {html_set_header caption=#head_nachricht# class="head"}
    -->
</table>