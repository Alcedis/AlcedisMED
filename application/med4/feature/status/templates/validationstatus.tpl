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

<div id="validfinished" style="display: none">{$finished}</div>
<table width="100%">
    <tr>
        <td class="head">{#lbl_name#}</td>
        <td class="head">{#lbl_status_count#}</td>
        <td class="head">{#lbl_status_relation#}</td>
        <td class="head">{#lbl_form_count#}</td>
        <td class="head">{#lbl_form_relation#}</td>
        <td class="head">{#lbl_locked#}</td>
        <td class="head">{#lbl_validated#}</td>
        <td class="head"></td>
    </tr>
    {section name=i loop=$validationStatus}
        {html_odd_even var="class" key=%i.index%}
        <tr>
            <td class="{$validationStatus[i].formc}">{$validationStatus[i].lbl}</td>
            <td class="{$class}">{$validationStatus[i].status_count}</td>
            <td class="{$validationStatus[i].rels}">{$validationStatus[i].status_relation}</td>
            <td class="{$validationStatus[i].formc}">{$validationStatus[i].form_count}</td>
            <td class="{$validationStatus[i].relf}">{$validationStatus[i].form_relation}</td>
            <td class="{$class}">{$validationStatus[i].locked}</td>
            <td class="{$validationStatus[i].validc}">{$validationStatus[i].validated}</td>
            <td class="{$class}">
                <input type="image" alt="validate" name="action[validatestatus][{$validationStatus[i].form}]" src="media/img/base/repeat.png">
            </td>
        </tr>
    {sectionelse}
        <tr>
           <td class="even no-data" colspan="6"><div id="loadagain" />{#no_validation_done#}</td>
        </tr>
    {/section}
</table>
