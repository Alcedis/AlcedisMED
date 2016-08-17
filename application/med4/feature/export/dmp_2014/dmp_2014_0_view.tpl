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

{if $view_type == 'errors' }
    <div class="err">
        <div class="formcaption" style="font-weight:bold; ">{$erroritem_data.nachname}, {$erroritem_data.vorname} ( {$erroritem_data.geburtsdatum} )</div>
        <div class="formcaption" style="font-weight:bold; ">{$erroritem_data.erkrankung}: {$erroritem_data.block}</div>
        <div>&nbsp;</div>
        {foreach from=$erroritem_data.errors item=error}
            <div style="margin-top:-5px;">{$error}</div></br>
        {/foreach}
    </div>
{elseif $view_type == 'warnings' }
    <div class="warn">
        <div class="formcaption" style="font-weight:bold; ">{$warningitem_data.nachname}, {$warningitem_data.vorname} ( {$warningitem_data.geburtsdatum} )</div>
        <div class="formcaption" style="font-weight:bold; ">{$warningitem_data.erkrankung}: {$warningitem_data.block}</div>
        <div>&nbsp;</div>
        {foreach from=$warningitem_data.errors item=warning}
            <div style="margin-top:-5px;">{$warning}</div></br>
        {/foreach}
    </div>
{elseif $view_type == 'log'}
    <table class="formtable">
        {html_set_header class="head" colspan="3" caption=#head_export_file#}
        <tr>
            <td class="msgbox" colspan="3">{$exportergebnis}</td>
        </tr>
        {if $show_xkm}
        <tr>
            <td class="edt" width="33%">
                {if $show_xkm}
                    <strong>{#versanddokumente#}</strong><br><br>
                    <a class="visible-anchor" href="{$xkm_url}" target="_blank">{#exportdatei#}</a><br><br>
                    <a class="visible-anchor" href="{$idx_url}" target="_blank">{#begleitdatei#}</a><br><br>
                    <a class="visible-anchor" href="index.php?page=report&amp;sub=b&amp;name=dmp_begleitzettel_2014&amp;type=pdf&amp;footer=none&amp;action=report" target="_blank" >{#begleitzettel#}</a><br><br>
                    </br></br>
                {/if}
            </td>
            <td class="edt" width="33%">
                <strong>{#interne_dokumente#}</strong><br><br>
                {if $show_xkm}<a class="visible-anchor" href="{$zip_url}" target="_blank">{#zipdatei#}</a>{/if}<br><br>
                {if $show_xkm}<a class="visible-anchor" href="{$xkm_crypto_protocol_url}" target="_blank" >{#verschluesselungsprotokoll#}</a>{/if}<br><br>
                </br></br>
                </br></br>
            </td>
            <td class="edt" width="34%">
                {$info_beschriftung}
                <br><br>
            </td>
        </tr>
        {/if}
    </table>
    {if $show_xkm}
    <table class="formtable">
        {html_set_header class="head" colspan="9" caption=#head_liste_ed#}
        <tr>
            <td class="sub_head">{#versich_nr#}</td>
            <td class="sub_head">{#patient#}</td>
            <td class="sub_head">{#geburtsdatum#}</td>
            <td class="sub_head">{#fall_nr#}</td>
            <td class="sub_head">{#dmp_dokument_id#}</td>
            <td class="sub_head">{#doku_datum#}</td>
            <td class="sub_head">{#unterschrift_datum#}</td>
            <td class="sub_head">{#exportstatus#}</td>
            <td class="sub_head">{#dokubogen#}</td>
        </tr>
        {foreach from=$dmp_ed_patdat item=patient_ed}
            <tr>
                <td class="edt">{$patient_ed.kv_nr}</td>
                <td class="edt">{$patient_ed.nachname}, {$patient_ed.vorname}</td>
                <td class="edt">{$patient_ed.geburtsdatum}</td>
                <td class="edt">{$patient_ed.fall_nr}</td>
                <td class="edt">{$patient_ed.dmp_dokument_id}</td>
                <td class="edt">{$patient_ed.doku_datum}</td>
                <td class="edt">{$patient_ed.unterschrift_datum}</td>
                {if $patient_ed.status=="Ok"}
                    <td class="edt"><span>{$patient_ed.status}</span></td>
                {else}
                    <td class="edt"><a class="visible-anchor" href="#" onclick="execute_request( this, 'dmp_2014&amp;feature=export&amp;action=view_all_errors&amp;export_type=ed&amp;export_id={$patient_ed.dmp_dbid}', null, [] )" >{$patient_ed.status}</a></td>
                {/if}
                <td class="edt"><a href="index.php?page=dmp_2013_popups&type=dmp_ed_2013_bogen&id={$patient_ed.dmp_dbid}" target="_blank" ><img border="0" src="media/img/app/dmp/btn_edmp_small.gif" alt="DMP-Bogen"></a></td>
            </tr>
            {foreachelse}
            <tr>
                <td class="edt no_data" colspan="9">{#msg_no_data#}</td>
            </tr>
        {/foreach}
        {html_set_header class="head" colspan="9" caption=#head_liste_ed_pnp#}
        <tr>
            <td class="sub_head">{#versich_nr#}</td>
            <td class="sub_head">{#patient#}</td>
            <td class="sub_head">{#geburtsdatum#}</td>
            <td class="sub_head">{#fall_nr#}</td>
            <td class="sub_head">{#dmp_dokument_id#}</td>
            <td class="sub_head">{#doku_datum#}</td>
            <td class="sub_head">{#unterschrift_datum#}</td>
            <td class="sub_head">{#exportstatus#}</td>
            <td class="sub_head">{#dokubogen#}</td>
        </tr>
        {foreach from=$dmp_ed_pnp_patdat item=patient_ed_pnp}
            <tr>
                <td class="edt">{$patient_ed_pnp.kv_nr}</td>
                <td class="edt">{$patient_ed_pnp.nachname}, {$patient_ed_pnp.vorname}</td>
                <td class="edt">{$patient_ed_pnp.geburtsdatum}</td>
                <td class="edt">{$patient_ed_pnp.fall_nr}</td>
                <td class="edt">{$patient_ed_pnp.dmp_dokument_id}</td>
                <td class="edt">{$patient_ed_pnp.doku_datum}</td>
                <td class="edt">{$patient_ed_pnp.unterschrift_datum}</td>
                {if $patient_ed_pnp.status=="Ok"}
                    <td class="edt"><span>{$patient_ed_pnp.status}</span></td>
                {else}
                    <td class="edt"><a class="visible-anchor" href="#" onclick="execute_request( this, 'dmp_2014&amp;feature=export&amp;action=view_all_errors&amp;export_type=ed_pnp&amp;export_id={$patient_ed_pnp.dmp_dbid}', null, [] )" >{$patient_ed_pnp.status}</a></td>
                {/if}
                <td class="edt"><a href="index.php?page=dmp_2013_popups&type=dmp_ed_pnp_2013_bogen&id={$patient_ed_pnp.dmp_dbid}" target="_blank" ><img border="0" src="media/img/app/dmp/btn_edmp_small.gif" alt="DMP-Bogen"></a></td>
            </tr>
            {foreachelse}
            <tr>
                <td class="edt no_data" colspan="9">{#msg_no_data#}</td>
            </tr>
        {/foreach}
        {html_set_header class="head" colspan="9" caption=#head_liste_fd#}
        <tr>
            <td class="sub_head">{#versich_nr#}</td>
            <td class="sub_head">{#patient#}</td>
            <td class="sub_head">{#geburtsdatum#}</td>
            <td class="sub_head">{#fall_nr#}</td>
            <td class="sub_head">{#dmp_dokument_id#}</td>
            <td class="sub_head">{#doku_datum#}</td>
            <td class="sub_head">{#unterschrift_datum#}</td>
            <td class="sub_head">{#exportstatus#}</td>
            <td class="sub_head">{#dokubogen#}</td>
        </tr>
        {foreach from=$dmp_fd_patdat item=patient_fd}
            <tr>
                <td class="edt">{$patient_fd.kv_nr}</td>
                <td class="edt">{$patient_fd.nachname}, {$patient_fd.vorname}</td>
                <td class="edt">{$patient_fd.geburtsdatum}</td>
                <td class="edt">{$patient_fd.fall_nr}</td>
                <td class="edt">{$patient_fd.dmp_dokument_id}</td>
                <td class="edt">{$patient_fd.doku_datum}</td>
                <td class="edt">{$patient_fd.unterschrift_datum}</td>
                {if $patient_fd.status=="Ok"}
                    <td class="edt"><span>{$patient_fd.status}</span></td>
                {else}
                    <td class="edt"><a class="visible-anchor" href="#" onclick="execute_request( this, 'dmp_2014&amp;feature=export&amp;action=view_all_errors&amp;export_type=fd&amp;export_id={$patient_fd.dmp_dbid}', null, [] )" >{$patient_fd.status}</a></td>
                {/if}
                <td class="edt"><a href="index.php?page=dmp_2013_popups&type=dmp_fd_2013_bogen&id={$patient_fd.dmp_dbid}" target="_blank" ><img border="0" src="media/img/app/dmp/btn_edmp_small.gif" alt="DMP-Bogen"></a></td>
            </tr>
            {foreachelse}
            <tr>
                <td class="edt no_data" colspan="9">{#msg_no_data#}</td>
            </tr>
        {/foreach}
    </table>
    {/if}
{elseif $view_type == 'errorlist'}
    <table width="100%" {#table_form#}>
        <tr>
            <td class="head" colspan="9" align="center">{#head_export_errorlist#}</td>
        </tr>
        <tr>
            <td colspan="4">
                <div style="font-size:14px; font-weight:bold; margin:10px;" ><img border="0" src="media/img/base/ok.png" alt="okay"><span>{$info_patienten_valid}</span></div>
            </td>
            <td colspan="4">
                <div style="font-size:14px; font-weight:bold; margin:10px;" ><img border="0" src="media/img/base/err.png" alt="error"><span>{$info_patienten_invalid}</span></div>
            </td>
        </tr>
    </table>
    <table class="listtable bfl">
        <tr>
            <td class="head unsortable" colspan="9" style="text-align: center;">{#errors#}</td>
        </tr>
        <tr>
            <td class="head unsortable"></td>
            <td class="head edit unsortable">{#fehler#}</td>
            <td class="head unsortable" >{#export_nr#}</td>
            <td class="head unsortable" >{#name#}</td>
            <td class="head unsortable" >{#geburtsdatum#}</td>
            <td class="head unsortable" >{#erkrankung#}</td>
            <td class="head unsortable" >{#seite#}</td>
            <td class="head unsortable" >{#abschnitt#}</td>
            <td class="head unsortable" >{#doku_datum#}</td>
        </tr>
        {section name=i loop=$errorlist_data}
        {html_odd_even var="class" key=%i.index%}
        <tr>
        <td class="{$class}" align="center">
            <a href="index.php?page=view.erkrankung&amp;patient_id={$errorlist_data[i].patient_id}&amp;erkrankung_id={$errorlist_data[i].erkrankung_id}" title="{#tooltip_edit#}" >
                {if $errorlist_data[i].geschlecht == 'w' }
                <img border="0" src="media/img/base/patient-w.png" alt="edit">
                {else}
                <img border="0" src="media/img/base/patient-m.png" alt="edit">
                {/if}
            </a>
        </td>
        <td class="{$class}" align="center">
            <a href="#" onclick="execute_request( this, 'dmp_2014&amp;feature=export&amp;action=view_errors&amp;export_id={$errorlist_data[i].export_section_log_id}', null, [] )" title="{#tooltip_view_errors#}" >
                <img border="0" src="media/img/base/err.png" alt="view_errors">
            </a>
        </td>
            <td class="{$class}" align="center">{$errorlist_data[i].export_nr}</td>
            <td class="{$class}">{$errorlist_data[i].nachname}, {$errorlist_data[i].vorname}</td>
            <td class="{$class}">{$errorlist_data[i].geburtsdatum}</td>
            <td class="{$class}">{$errorlist_data[i].erkrankung}</td>
            <td class="{$class}">{$errorlist_data[i].diagnose_seite}</td>
            <td class="{$class}">{$errorlist_data[i].block}</td>
            <td class="{$class}">{$errorlist_data[i].createtime}</td>
        </tr>
        {sectionelse}
        <tr>
            <td class="even no-data" colspan="9">{#info_keine_fehler_vorhanden#}</td>
        </tr>
        {/section}
        <tr>
            <td class="head unsortable" colspan="9" style="text-align: center;">{#warnings#}</td>
        </tr>
        <tr>
            <td class="head unsortable"></td>
            <td class="head edit unsortable">{#fehler#}</td>
            <td class="head unsortable" >{#export_nr#}</td>
            <td class="head unsortable" >{#name#}</td>
            <td class="head unsortable" >{#geburtsdatum#}</td>
            <td class="head unsortable" >{#erkrankung#}</td>
            <td class="head unsortable" >{#seite#}</td>
            <td class="head unsortable" >{#abschnitt#}</td>
            <td class="head unsortable" >{#doku_datum#}</td>
        </tr>
        {section name=i loop=$warninglist_data}
            {html_odd_even var="class" key=%i.index%}
            <tr>
                <td class="{$class}" align="center">
                    <a href="index.php?page=view.erkrankung&amp;patient_id={$warninglist_data[i].patient_id}&amp;erkrankung_id={$warninglist_data[i].erkrankung_id}" title="{#tooltip_edit#}" >
                        {if $warninglist_data[i].geschlecht == 'w' }
                            <img border="0" src="media/img/base/patient-w.png" alt="edit">
                        {else}
                            <img border="0" src="media/img/base/patient-m.png" alt="edit">
                        {/if}
                    </a>
                </td>
                <td class="{$class}" align="center">
                    <a href="#" onclick="execute_request( this, 'dmp_2014&amp;feature=export&amp;action=view_warnings&amp;export_id={$warninglist_data[i].export_section_log_id}', null, [] )" title="{#tooltip_view_warnings#}" >
                        <img border="0" src="media/img/base/warn.png" alt="view_warnings">
                    </a>
                </td>
                <td class="{$class}" align="center">{$warninglist_data[i].export_nr}</td>
                <td class="{$class}">{$warninglist_data[i].nachname}, {$warninglist_data[i].vorname}</td>
                <td class="{$class}">{$warninglist_data[i].geburtsdatum}</td>
                <td class="{$class}">{$warninglist_data[i].erkrankung}</td>
                <td class="{$class}">{$warninglist_data[i].diagnose_seite}</td>
                <td class="{$class}">{$warninglist_data[i].block}</td>
                <td class="{$class}">{$warninglist_data[i].createtime}</td>
            </tr>
            {sectionelse}
            <tr>
                <td class="even no-data" colspan="9">{#info_keine_warnungen_vorhanden#}</td>
            </tr>
        {/section}
        <tr>
            <td class="edt" colspan="9" align="center">
                {if ( ( $invalid_cases > 0 ) || ( $valid_cases > 0 ) ) }
                {html_set_buttons modus='export_delete' class='button_large' table=false}
                {/if}
                {if $valid_cases > 0 }
                {html_set_buttons modus='export_create' class='button_large' table=false}
                {/if}
            </td>
        </tr>
    </table>
{else}
    <table class="formtable">
        {html_set_header caption=#head_export# class="head"}
        {html_set_row caption=$_sel_melde_user_id_lbl input=$_sel_melde_user_id}
        {html_set_row caption=#sel_bez#               input="$_sel_von_date_lbl $_sel_von_date" add="$_sel_bis_date_lbl $_sel_bis_date " }
        {html_set_row caption=$_sel_empfaenger2_lbl   input=$_sel_empfaenger2}
        <tr>
            <td class="edt" colspan="2" align="center">
                <table>
                    <tr>
                        <td style="border: 0px;">
                            {html_set_buttons modus='export_start' class='button_large' table=false}
                        </td>
                        <td style="border: 0px;">
                            <div id="buttonbar">
                                <a class="button_large" style="margin-top: 3px;" href="index.php?page=history&feature=export&exportname=dmp_2014">{#start_history#}</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
{/if}
