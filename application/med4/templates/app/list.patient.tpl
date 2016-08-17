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

{if $viewAlternative == 1}
    {assign var=organization value='<td class="head ext-search cookie-organisation">Organisation</td>'}
{else}
    {assign var=style value=' style="width:20%"'}
{/if}

<table class="listtable bfl">
    <tr>
        <td class="head edit unsortable"></td>
        {if $viewAlternative != 1}
            <td class="head ext-search cookie-krebsregister cookietype-lookup" align="center"><span style="display:none"> {#krebsregister#}</span>
                <span class="bfl-lookup-content" style="display:none">
                    {$queryMod.lookups.krebsregister}
                </span>
            </td>
        {/if}
        <td class="head ext-search cookie-nachname"{$style}>{#name#}</td>
        <td class="head ext-search cookie-vorname">{#forename#}</td>
        <td class="head ext-search cookie-geburtsdatum">{#birthdate#}</td>
        {$organization}

        {foreach from=$patListKonfiguration item=listitem}
            <td class="head ext-search cookie-{$listitem}">{$smarty.config.$listitem}</td>
        {/foreach}

        <td class="head ext-search cookie-erkrankungen" style="width: 180px">{#lbl_erkrankungen#}</td>
        <td class="head ext-search cookie-status cookietype-lookup" style="width:16px" align="center">
            <span class="bfl-lookup-content" style="display:none">
                {$queryMod.lookups.status}
            </span>
        </td>
    </tr>

    {include file=app/list/list.patient.tpl}
</table>

