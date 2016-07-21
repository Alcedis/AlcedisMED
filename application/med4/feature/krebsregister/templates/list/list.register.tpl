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

{section name=i loop=$fields.patient_id.value}
    {html_odd_even var="class" key=%i.index%}

    <tr>
        <td class="{$class}" style="text-align: center">
            <input type="checkbox" class="bfl-buffer-input export" name="export-{$fields.patient_id.value[i]}" value="1" />
        </td>

        <td class="{$class}" align="center">
            <img src="media/img/app/ampel/{$fields.status.value[i]}.png">
        </td>

        <td class="{$class}" align="center">
            <a href="index.php?page=view.patient&patient_id={$fields.patient_id.value[i]}">
                <img class="popup-trigger back-img" src="media/img/base/patient-overview.png" style="padding-top:1px" alt="">
            </a>
            <div class="info-popup below center" style="display: none;">{#pat_overview#}</div>
        </td>

        <td class="{$class}">{$fields.nachname.value[i]}</td>
        <td class="{$class}">{$fields.vorname.value[i]}</td>
        <td class="{$class}">{$fields.patient_nr.value[i]}</td>
        <td class="{$class}">{$fields.geburtsdatum.value[i]}</td>

        <td class="{$class}" align="center">
            {if $fields.errors.value[i] == 1}
            <a href="index.php?page=register_patient&feature=krebsregister&type={$type}&show=error&patient_id={$fields.patient_id.value[i]}">
                <img alt="" src="media/img/app/krebsregister/error.png">
            </a>
            {else}
                <img alt="" src="media/img/app/krebsregister/error_g.png">
            {/if}
        </td>
        <td class="{$class}" align="center">
            {if $fields.warnings.value[i] == 1}
            <a href="index.php?page=register_patient&feature=krebsregister&type={$type}&show=warning&patient_id={$fields.patient_id.value[i]}">
                <img alt="" src="media/img/app/krebsregister/warning.png">
            </a>
            {else}
                <img alt="" src="media/img/app/krebsregister/warning_g.png">
            {/if}
        </td>

        <td class="{$class}"><span style="color: grey">{$fields.lexport.value[i]}</span></td>

        <td class="{$class}" style="text-align: center">
            {if strlen($fields.lexport.value[i]) > 0}
            <a href="index.php?page=register_patient&feature=krebsregister&type={$type}&show=history&patient_id={$fields.patient_id.value[i]}">
                <img alt="export" src="media/img/base/picker-folder.png">
            </a>
            {else}
                <img alt="" src="media/img/base/picker-folder_g.png">
            {/if}

        </td>
    </tr>
{sectionelse}
    <tr>
        <td class="even no-data" colspan="11">{#no_dataset#}</td>
    </tr>
{/section}
