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
{html_set_header caption=#head_massnahmen#       class="head"}
{if in_array($SESSION.sess_erkrankung_data.code, array('b', 'lu')) === true}
<tr>
    <td style="padding:0 !important" colspan="2">
        <div class="info-msg" style="margin-bottom:0 !important">
            {#info_seitenangabe#}
        </div>
    </td>
</tr>
{/if}
{html_set_row field="prozedur"        caption=$_prozedur_lbl         input=$_prozedur}
{html_set_row field="diagnose_id"     caption=$_diagnose_id_lbl      input=$_diagnose_id}
</table>
{html_set_ajax_buttons modus=$button}

<div>
<input type="hidden" name="sess_pos" value="{$sess_pos}" />
{$_eingriff_ops_id}
{$_eingriff_id}
{$_patient_id}
{$_erkrankung_id}
</div>