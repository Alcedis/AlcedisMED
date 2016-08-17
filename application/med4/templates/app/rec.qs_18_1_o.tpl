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
    {html_set_header  field="lfdnreingriff"         caption=#head_operation#            class="head"}
    {html_set_row     field="lfdnreingriff"         caption=$_lfdnreingriff_lbl         input=$_lfdnreingriff}
    {html_set_row     field="diagoffbiopsie"        caption=$_diagoffbiopsie_lbl        input=$_diagoffbiopsie}
    {html_set_row     field="praeopmarkierung"      caption=$_praeopmarkierung_lbl      input=$_praeopmarkierung}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_1#}
            </div>
        </td>
    </tr>
    {html_set_row     field="praeopmammographiejl"  caption=$_praeopmammographiejl_lbl  input=$_praeopmammographiejl}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_2#}
            </div>
        </td>
    </tr>
    {html_set_row     field="intraoppraeparatroentgen"  caption=$_intraoppraeparatroentgen_lbl  input=$_intraoppraeparatroentgen}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_3#}
            </div>
        </td>
    </tr>
    {html_set_row     field="praeopsonographiejl"   caption=$_praeopsonographiejl_lbl   input=$_praeopsonographiejl}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_4#}
            </div>
        </td>
    </tr>
    {html_set_row     field="intraoppraeparatsono"  caption=$_intraoppraeparatsono_lbl  input=$_intraoppraeparatsono}
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_5#}
            </div>
        </td>
    </tr>
    {html_set_row     field="praeopmrtjl"           caption=$_praeopmrtjl_lbl           input=$_praeopmrtjl}
    {html_set_row     field="opdatum"               caption=$_opdatum_lbl               input=$_opdatum}

    {html_set_header  caption=#subhead_operation#         class="subhead"}

    <tr>
        <td class="lbl">{$_opschluessel_1_lbl}{#info_opschluessel#}</td>
        <td class="edt">{$_opschluessel_1}</td>
    </tr>
    <tr>
        <td class="lbl">{$_opschluessel_2_lbl}{#info_opschluessel#}</td>
        <td class="edt">{$_opschluessel_2}</td>
    </tr>
    <tr>
        <td class="lbl">{$_opschluessel_3_lbl}{#info_opschluessel#}</td>
        <td class="edt">{$_opschluessel_3}</td>
    </tr>
    <tr>
        <td class="lbl">{$_opschluessel_4_lbl}{#info_opschluessel#}</td>
        <td class="edt">{$_opschluessel_4}</td>
    </tr>
    <tr>
        <td class="lbl">{$_opschluessel_5_lbl}{#info_opschluessel#}</td>
        <td class="edt">{$_opschluessel_5}</td>
    </tr>
    <tr>
        <td class="lbl">{$_opschluessel_6_lbl}{#info_opschluessel#}</td>
        <td class="edt">{$_opschluessel_6}</td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 0">
            <div class="info-msg-grey" style="margin-bottom:0 !important">
                {#info_6#}
            </div>
        </td>
    </tr>
    {html_set_row  field="sentinellkeingriff" caption=$_sentinellkeingriff_lbl input=$_sentinellkeingriff}
    {html_set_row  field="antibioprph"        caption=$_antibioprph_lbl        input=$_antibioprph}

    {html_set_header  field="freigabe"         caption=#head_freigabe#          class="head"}
{html_set_row     field="freigabe"      caption=$_freigabe_lbl      input=$_freigabe}

</table>
{html_set_buttons modus=$button}

<div>
{$_qs_18_1_o_id}
{$_qs_18_1_brust_id}
{$_qs_18_1_b_id}
{$_patient_id}
{$_erkrankung_id}
{$_aufenthalt_id}
</div>
