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

<div id="erroneous_case">
    <b>{#lbl_reason#}:</b> <span style="margin-right: 10px">{$case.anlass}</span> <b>{#lbl_disease#}:</b> <span style="margin-right: 10px">{$case.erkrankung}</span> {if isset($case.seite)}<b>{#lbl_side#}:</b> {$case.seite}{/if}
</div>
</table>
<div id="accordion">
    {assign var=counter value=0}
    {foreach from=$case.section item=section}
        <h3>
            <a href="#">
                Sektion: '{$section.uid}'
                {*<span style="font-size: 7.6pt;">{$section.errorMessages|@count}</span>*}
            </a>
        </h3>
        <div>
            <table width="100%">
                <tr>
                    <td>
                        <span style="font-size: 8pt; padding-left: 8px; color: grey;">{#c_message#}</span>
                        <hr/>
                    </td>
                </tr>
                {foreach from=$section.errorMessages item=message key=errorMessageNo}
                {html_odd_even var="class" key=$counter}
                    <tr>
                        <td class="{$class}" style="text-align: left">{$message}</td>
                    </tr>
                {assign var=counter value=$counter+1}
                {/foreach}
            </table>
            <div class="register-message">
                {$section.daten}
            </div>
        </div>
    {/foreach}
</div>
