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

<table id="export_case" style="width: 100%">
    <tr>
        <th class="head" style="width: 5%; text-align: left"> {#lbl_export_btn#}</th>
        <th class="head" style="width: 5%; text-align: left">{#lbl_reason#}</th>
        <th class="head" style="text-align: left">{#lbl_disease#}</th>
        <th class="head" style="width: 9%; text-align: left">{#lbl_side#}</th>
        <th class="head" style="width: 18%; text-align: right">{#lbl_sections#}</th>
        {if 'error' == $rpShow}
        <th class="head" style="width: 12%; text-align: right">{#c_errors#}</th>
        {else}
        <th class="head" style="width: 12%; text-align: right">{#c_warnings#}</th>
        {/if}
    </tr>
    {assign var=counter value=0}
    {foreach from=$cases item=case key=case_id}
        {html_odd_even var="class" key=$counter}
        <tr>
            <td class="{$class}" align="center">
                <a class="export_case" href="index.php?page=register_patient&feature=krebsregister&type={$type}&patient_id={$patient.id}&show={$rpShow}&case_id={$case_id}"></a>
            </td>
            <td class="{$class}">{$case.anlass}</td>
            <td class="{$class}">{$case.erkrankung}</td>
            <td class="{$class}" style="text-align: left">{$case.seite}</td>
            <td class="{$class}" style="text-align: right">{$case.section|@count}</td>
            <td class="{$class}" style="text-align: right">{$case.total}</td>
        </tr>
        {assign var=counter value=$counter+1}
    {/foreach}
</table>
