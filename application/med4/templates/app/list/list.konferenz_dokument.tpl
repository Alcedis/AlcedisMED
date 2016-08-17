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

{section name=i loop=$fields.id.value}

{html_odd_even var="class" key=%i.index%}

<tr>
   {if $insertRight === true}
    <td class="{$class} edit" align="center">

       {if $fields.iscodoc.value[i]}
           <a href="index.php?page=rec.konferenz_dokument&amp;konferenz_dokument_id={$fields.id.value[i]|substr:1}{$link_param}" class="edit"></a>
       {else}
          <a href="index.php?page=rec.konferenz_dokument&amp;dokument_id={$fields.id.value[i]|substr:1}{$link_param}">
             <img class="popup-trigger" src="media/img/base/add_plus.png" />
             <span class="info-popup above after" style="display:none;">{#create#}</span>
          </a>
       {/if}
       </td>
   {/if}
   <td class="{$class}">{$fields.bez.value[i]}</td>
   <td class="{$class}">
   <!-- -->
      {if $fields.iscodoc.value[i] != 1 || $fields.dokument_id.value[i]}
        <b>{$fields.name.value[i]}</b> <span style="font-size:8pt">({$fields.geburtsdatum.value[i]})</span><br/>
        <span style="font-size:9pt">Erkrankung: {$fields.erkrankung.value[i]}</span><br/>
        <span style="font-size:9pt">{$fields.org.value[i]}</span><br/>
      {/if}
   </td>
   <td class="{$class}">
    <div>
        <div style="float:left;margin-right:6px">
            {if $fields.type.value[i] == 'konf'}
                <img src="media/img/base/konferenz_small.png" alt=""/>
                </div>
                <div style="float:left">
                    <b>Konferenz</b>
            {else}
                <img src="media/img/base/patient-overview.png" alt=""/>
                </div>
                <div style="float:left">
                    <b>Patient</b>
            {/if}
        </div>
    </div>

    <div>
    </div>

   </td>

   <td class="{$class}" align="center">
       <input type="submit" class="dont_prevent_double_save button_show_{$fields.doc_type.value[i]}" name="action[file][{if $fields.iscodoc.value[i] != 1 || $fields.dokument_id.value[i]}d{/if}{$fields.dokid.value[i]}]" value="" alt=""/>
   </td>
</tr>
{sectionelse}
<tr>
    <td class="even no-data" colspan="5">{#no_dataset#}</td>
</tr>
{/section}