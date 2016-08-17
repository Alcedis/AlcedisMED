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

    {if $viewAlternative}
        <tr>
            <td class="{$class}" align="center"><a href="{$form_rec}&amp;patient_id={$fields.patient_id.value[i]}" class="edit"></a></td>
            <td class="{$class}" colspan="7">
                <div style="width:100%">
                    <div style="float:left; width:300px; padding-right:20px">
                       <div><b>{$fields.nachname.value[i]}, {$fields.vorname.value[i]}</b></div>
                       <div><span style="font-size:9pt">{$fields.geburtsdatum.value[i]}</span></div>
                       <div><span style="font-size:9pt">Pat.-Nr.: <b>{$fields.patient_nr.value[i]}</b></span></div>
                   </div>
                   <div style="float:left; width:300px">
                       <div><span style="font-size:9pt">Org.: <b>{$fields.organisation.value[i]}</b></span></div>
                       <div><span style="font-size:9pt">Erstellt: <b>{$fields.createtime.value[i]}</b></span></div>
                       <div><span style="font-size:9pt">Aufn.-Nr.: <b>{$fields.aufnahme_nr.value[i]}</b></span></div>
                   </div>
                   <div style="float:left; width:200px">
                       <div><span style="font-size:9pt"><b>{$fields.erkrankungen.value[i]}</b></span></div>
                   </div>
               </div>
            </td>
            <td class="{$class}">
                <span style="display:none">{$fields.status.value[i]}</span>
                    <a href="index.php?page=status&amp;patient_id={$fields.patient_id.value[i]}&amp;location=overview">
                        <img class="popup-trigger" src="media/img/app/ampel/{$fields.status.value[i]}.png" alt="{$fields.status.bez[i]}" title=""/>
                        <span class="info-popup above before" style="display:none;">{#lbl_status#}{#lbl_ddot#} {$fields.status.bez[i]}</span>
                    </a>
            </td>
        </tr>
    {else}
        <tr>
            <td class="{$class}" align="center"><a href="{$form_rec}&amp;patient_id={$fields.patient_id.value[i]}" class="edit"></a></td>
            <td class="{$class}" align="center">
                {if $fields.krebsregister.value[i] == 1}
                    <div class="krebsregister popup-trigger">
                        <span class="info-popup above after" style="display:none;">{#kr_only#}</span>
                    </div>
                {/if}
            </td>
            <td class="{$class}">{$fields.nachname.value[i]}</td>
            <td class="{$class}">{$fields.vorname.value[i]}</td>
            <td class="{$class}" style="width:110px;">{$fields.geburtsdatum.value[i]}</td>

            {foreach from=$patListKonfiguration item=listitem}
                <td class="{$class}" style="width:100px;">{$fields.$listitem.value[i]}</td>
            {/foreach}

            <td class="{$class}"><span style="font-size:9pt">{$fields.erkrankungen.value[i]}</span></td>

            <td class="{$class}" align="center">
              <span style="display:none">{$fields.status.value[i]}</span>
              <a href="index.php?page=status&amp;patient_id={$fields.patient_id.value[i]}&amp;location=overview">
                 <img class="popup-trigger" src="media/img/app/ampel/{$fields.status.value[i]}.png" alt="{$fields.status.bez[i]}" title=""/>
                 <span class="info-popup above before" style="display:none;">{#lbl_status#}{#lbl_ddot#} {$fields.status.bez[i]}</span>
             </a>
            </td>
        </tr>
    {/if}
{sectionelse}
<tr>
    <td class="even no-data" colspan="9">{#no_dataset#}</td>
</tr>
{/section}
