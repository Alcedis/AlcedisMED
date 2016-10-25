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
<div style="margin-bottom: 15px; margin-top: 10px">
    <table class="listtable no-filter no-hover">
        <tr>
            <td class="head" colspan="2" align="center">
                <span>{#lbl_patient#}</span>
            </td>
        </tr>
        <tr>
            <td class="sdinfo-{$patient.geschlecht}" style="width: 3%">
                {if strlen($patient.geschlecht) > 0}
                <img style="float:left" class="patient-info" title="" src="media/img/base/patient-{$patient.geschlecht}.png" alt="patient"/>
                {else}
                <img style="float:left" class="patient-info" title="" src="media/img/base/patient.png" alt="patient"/>
                {/if}
            </td>
            <div>
                <td class="sdinfo-{$patient.geschlecht}">
                    <span><b>{#lbl_name#} </b>{$patient.vorname} {$patient.nachname}</span><br/>
                    <span><b>{#lbl_geb#} </b>{$patient.geburtsdatum}</span><br/>
                    <span><b>{#lbl_patno#} </b>{$patient.patient_nr}</span>
                </td>
            </div>
        </tr>
    </table>

    {if $rpShow === 'history'}
        {include file="../feature/krebsregister/templates/list/patient.history.tpl"}
    {else}
        {if isset($case)}
            {include file="../feature/krebsregister/templates/list/patient.errormessages.tpl"}
        {else}
            {include file="../feature/krebsregister/templates/list/patient.cases.tpl"}
        {/if}
    {/if}
</div>
