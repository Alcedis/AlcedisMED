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

{if $_erkrankung_id_value}
    <tr>
        <td class="lbl">{$_erkrankung_lbl}</td>
        <td class="edt"><b>{$_erkrankung_bez}</b><input type="hidden" name="erkrankung" value="{$_erkrankung_value}"/>
        {if $_erkrankung_value == 'sst'}
            <div style="margin-top:5px">{$_erkrankung_detail_lbl} {$_erkrankung_detail}</div>
        {/if}
        </td>
    </tr>
{else}
    <tr>
        <td class="lbl">{$_erkrankung_lbl}</td>
        <td class="edt">{$_erkrankung}
        <div style="margin-top:5px">{$_erkrankung_detail_lbl} {$_erkrankung_detail}</div>
        </td>

    </tr>
{/if}

{html_set_row caption=$_beschreibung_lbl                input=$_beschreibung field="beschreibung"}
{html_set_row caption=$_seite_lbl                       input=$_seite field="seite"}
{html_set_row caption=$_zweiterkrankung_lbl             input=$_zweiterkrankung field="zweiterkrankung"}
{html_set_row caption=$_fallkennzeichen_lbl             input=$_fallkennzeichen field="fallkennzeichen"}

{html_set_header caption=#head_besonders# class="head"}
{if $SESSION.sess_erkrankung_data.code === 'h'}
    <tr>
        <td colspan="2" style="padding:0px">
            <div class="info-msg">
                {#msg_erkrankung_relevant_haut#}
            </div>
        </td>
    </tr>
{/if}
{html_set_row caption=$_erkrankung_relevant_haut_lbl    input=$_erkrankung_relevant_haut field="erkrankung_relevant_haut"}
{html_set_row caption=$_erkrankung_relevant_lbl         input=$_erkrankung_relevant field="erkrankung_relevant"}
{html_set_row caption=$_rezidiv_bei_erstvorstellung_lbl input=$_rezidiv_bei_erstvorstellung field="rezidiv_bei_erstvorstellung"}
{html_set_row caption=$_notfall_lbl                     input=$_notfall field="notfall"}
{html_set_row caption=$_einweiser_id_lbl                input=$_einweiser_id field="einweiser_id"}
{html_set_row caption=$_nachsorgepassnummer_lbl         input=$_nachsorgepassnummer field="nachsorgepassnummer"}

{if $SESSION.sess_erkrankung_data.code === 'd' || $SESSION.sess_erkrankung_data.code === 'lu'}
{html_set_header caption=#head_erkrankung_synchron# class="head"}
    <tr>
        <td colspan="2" style="padding:0px">
            <div class="info-msg">
                {#msg_erkrankung_relevant#}
            </div>
        </td>
    </tr>
    <tr style='border-bottom:1px solid #fff;'>
        <td class="msg" colspan="2">
            <div class="dlist" id="dlist_synchron">
                <div class="add">
                    <input class="button" type="button" name="erkrankung_synchron" value="Hinzuf&uuml;gen" onclick="execute_request(this,'rec.erkrankung_synchron', null, ['patient_id', 'erkrankung_id'])"/>
                </div>
            </div>
        </td>
    </tr>
{/if}

{html_set_header caption=#head_bem# class="head" field="bem"}
{html_set_header caption=$_bem class="edt" field="bem"}

</table>
{html_set_buttons modus=$button classes=$button_params.css prependings=$button_params.pre}

<div>
{$_erkrankung_id}
{$_patient_id}
</div>
