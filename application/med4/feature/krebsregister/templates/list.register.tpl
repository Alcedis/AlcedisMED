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
    <table  width="100%">
        <tr>
            <td>

                {#lbl_last_export#} {if strlen($lastExportFinished)} {$lastExportFinished} Uhr{else}--{/if}<br/>

                {#lbl_current_export#} {$currentExportState} Uhr<br/>
                {#lbl_patients#} {$patients}
            </td>

            <td style="text-align: right">
                <span class="hover" onclick="send('export', this);">Auswahl exportieren</span>
            </td>
            <td style="text-align: right">
                <span class="hover" onclick="send('exportall', this);">Alle Patienten exportieren</span>
            </td>
            <td style="text-align: right">
                <span class="hover" onclick="send('refreshall', this);">Datenstand aktualisieren</span>
            </td>
        </tr>
</table>
</div>

<table class="listtable bfl feature-krebsregister" {literal}summary='{"type":"{/literal}{$type}{literal}"}'{/literal}>
    <tr>
        <td class="head unsortable" align="center" style="width:3%">
            <input type="text" class="bfl-buffer" name="buffer-export" value='{literal}{"add":{},"remove":{}}{/literal}' />
        </td>

        <td class="head ext-search cookie-status cookietype-lookup" align="center">{#status#}
            <span class="bfl-lookup-content" style="display:none">
                {$queryMod.lookups.status}
            </span>
        </td>
        <td class="head unsortable">
            <!-- -->
        </td>
        <td class="head ext-search cookie-nachname">{#nachname#}</td>
        <td class="head ext-search cookie-vorname">{#vorname#}</td>
        <td class="head unsortable ext-search cookie-patient_r">{#patient_nr#}</td>
        <td class="head ext-search cookie-geburtsdatum">{#geburtsdatum#}</td>
        <td class="head ext-search cookie-erkrankung">{#erkrankung#}</td>

        <td class="head ext-search cookie-errors cookietype-lookup" align="center">{#error#}
            <span class="bfl-lookup-content" style="display:none">
                {$queryMod.lookups.errors}
            </span>
        </td>
        <td class="head ext-search cookie-warnings cookietype-lookup" align="center">{#warning#}
            <span class="bfl-lookup-content" style="display:none">
                {$queryMod.lookups.warnings}
            </span>
        </td>

        <td class="head ext-search cookie-lexport">{#lexport#}</td>

        <td class="head unsortable" style="text-align: center">
            {#archiv#}
        </td>
    </tr>

    {include file="../feature/krebsregister/templates/list/list.register.tpl"}

</table>
