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

{html_set_header caption=#head_abschluss#    class="head"}
{html_set_row caption=$_abschluss_grund_lbl         input=$_abschluss_grund}
{html_set_row caption=$_letzter_kontakt_datum_lbl   input=$_letzter_kontakt_datum field="letzter_kontakt_datum"}

{html_set_header caption=#head_angaben_tod#  class="head" field="todesdatum,tod_ursache,tod_tumorassoziation,autopsie"}
{html_set_row caption=$_todesdatum_lbl            input=$_todesdatum field="todesdatum"}
{html_set_row caption=$_tod_ursache_lbl           input=$_tod_ursache field="tod_ursache"}
{html_set_row caption=$_tod_ursache_dauer_lbl     input=$_tod_ursache_dauer field="tod_ursache_dauer" add=#lbl_monate#}
<tr>
    <td colspan='9' class='head'>{#head_abschluss_ursache#}</td>
</tr>
<tr>
    <td class='msg' colspan='2'>
        <div class='dlist' id='dlist_ursache'>
            <div class='add'>
                <input class='button' type='button' name='abschluss_ursache' value="Hinzuf&uuml;gen" onclick="execute_request(this,'rec.abschluss_ursache', null, ['patient_id', 'abschluss_id'])"/>
            </div>
        </div>
    </td>
</tr>
{html_set_row caption=$_ursache_quelle_lbl  input=$_ursache_quelle field="ursache_quelle"}
{html_set_row caption=$_tod_tumorassoziation_lbl  input=$_tod_tumorassoziation field="tod_tumorassoziation"}
{html_set_row caption=$_autopsie_lbl              input=$_autopsie field="autopsie"}

{html_set_header caption=#head_bem# class="head" field="bem"}
{html_set_header caption=$_bem      class="edt" field="bem"}

</table>
{html_set_buttons modus=$button}

<div>
{$_abschluss_id}
{$_patient_id}
{$_erkrankung_id}
</div>
