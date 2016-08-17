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
    <table style="width:100%;">
        <tr>
            <td class="head" align="center">{#head_export_download#}</td>
        </tr>
        <tr>
            <td><a class="visible-anchor" href="index.php?page=onkeyline&amp;feature=export&amp;action=download&amp;type=zip&amp;file={$zip_url}" style="font-size:14px; font-weight:bold; margin:5px">{$zip_filename}</a></td>
        </tr>
    </table>
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
            </td>
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
                    <a href="#" onclick="execute_request( this, 'onkeyline&amp;feature=export&amp;action=view_errors&amp;export_id={$errorlist_data[i].export_section_log_id}', null, [] )" title="{#tooltip_view_errors#}" >
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
                    <a href="#" onclick="execute_request( this, 'onkyline&amp;feature=export&amp;action=view_warnings&amp;export_id={$warninglist_data[i].export_section_log_id}', null, [] )" title="{#tooltip_view_warnings#}" >
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
                {if ( $valid_cases > 0 ) }
                    {html_set_buttons modus='export_create' class='button_large' table=false}
                {/if}
            </td>
        </tr>
    </table>
    <input type="hidden" name="melder_id" value="{$melder_id}" />
{else}
    <table class="formtable">
        {html_set_header caption=#head_export# class="head"}
        {html_set_row caption=$_melder_id_lbl           input=$_melder_id}
        <tr>
            <td class="edt" colspan="2" align="center">
                <table>
                    <tr>
                        <td style="border: 0px;">
                            {html_set_buttons modus='export_start' class='button_large' table=false}
                        </td>
                        <td style="border: 0px;">
                            <div id="buttonbar">
                                <a class="button_large" style="margin-top: 3px;" href="index.php?page=history&feature=export&exportname=onkeyline">{#start_history#}</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
{/if}
