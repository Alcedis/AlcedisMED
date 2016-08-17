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

{section name=i loop=$fields.form_id.value}

{html_odd_even var="class" key=%i.index%}

<tr class="{$fields.form.value[i]}{if strlen($fields.parent_status_id.value[i])} parent-{$fields.parent_status_id.value[i]}{/if}" valign="top" id="status-{$fields.status_id.value[i]}">
   <td class="{$class}">
    {if $fields.form.value[i] == 'qs_18_1_b'}
        <a href="index.php?page=view.qs_18_1&amp;{$fields.form.value[i]}_id={$fields.form_id.value[i]}" class="edit"></a></td>
    {else}
        <a href="index.php?page=rec.{$fields.form.value[i]}&amp;{$fields.form.value[i]}_id={$fields.form_id.value[i]}" class="edit"></a></td>
    {/if}
   <td class="{$class}" align="center">{$fields.form_date.value[i]}</td>
   <td class="{$class}" ><strong>{$fields.form_name.value[i]}</strong></td>
   <td class="{$class}" >
       {if $fields.form.value[i] == 'diagnose' && $fields.form_param.value[i] == 1}
           <span style="color: #9413c4; font-size: 8pt; font-weight: bold">HL7</span>
       {/if}

      {if $fields.form.value[i] == 'dokument'}
            <a href="index.php?page=rec.dokument&amp;action[file][dokument]=1&amp;dokument_id={$fields.form_id.value[i]}">
                <img class="popup-trigger" src="media/img/base/package.png" alt="" title=""/>
                <span class="info-popup above before" style="display:none;">{#download#}</span>
            </a>
      {/if}
      {if $fields.form.value[i] == 'foto'}
            <a href="index.php?page=rec.foto&amp;action[file][foto]=1&amp;foto_id={$fields.form_id.value[i]}">
                <img class='popup-trigger' src='media/img/base/images.png' alt='' title=''/>
                <span class='info-popup above before' style='display:none;'>
                    <img style="margin:5px 11px;" class='thumb-img' alt='Lade...' src='index.php?page=foto&amp;type=thumbnail&amp;thumb=75&amp;foto_id={$fields.form_id.value[i]}'/>
                </span>
            </a>
       {/if}
       {if $fields.form.value[i]=="dmp_brustkrebs_eb"}
         <a href="index.php?page=dmp_popups&type=dmp_eb_bogen&id={$fields.form_id.value[i]}" target="_blank" >
            <img class="popup-trigger" src="media/img/app/dmp/btn_edmp_very_small.png" alt="{#lbl_dmp_bogen#}" title=""/>
                <span class="info-popup above before" style="display:none;">{$smarty.config.form_dmp_brustkrebs_eb}</span>
         </a>
        {/if}
        {if $fields.form.value[i]=="dmp_brustkrebs_fb"}
         <a href="index.php?page=dmp_popups&type=dmp_fb_bogen&id={$fields.form_id.value[i]}" target="_blank" >
            <img class="popup-trigger" src="media/img/app/dmp/btn_edmp_very_small.png" alt="{#lbl_dmp_bogen#}" title=""/>
            <span class="info-popup above before" style="display:none;">{$smarty.config.form_dmp_brustkrebs_fb}</span>
         </a>
      {/if}
        {if $fields.form.value[i]=="dmp_brustkrebs_ed_2013"}
            <a href="index.php?page=dmp_2013_popups&type=dmp_ed_2013_bogen&id={$fields.form_id.value[i]}" target="_blank" >
                {if strlen($fields.report_param.value[i])}
                    <img class="popup-trigger" src="media/img/app/dmp/btn_edmp_export_very_small.png" alt="{#lbl_dmp_bogen#}" title=""/>
                {else}
                    <img class="popup-trigger" src="media/img/app/dmp/btn_edmp_very_small.png" alt="{#lbl_dmp_bogen#}" title=""/>
                {/if}
                <span class="info-popup above before" style="display:none;">
                    {$smarty.config.form_dmp_brustkrebs_ed_2013}
                    {if strlen($fields.report_param.value[i])}
                        {#lbl_dmp_exported#} {$fields.report_param.value[i]}
                    {/if}
                </span>
            </a>
        {/if}
       {if $fields.form.value[i]=="dmp_brustkrebs_ed_pnp_2013"}
           <a href="index.php?page=dmp_2013_popups&type=dmp_ed_pnp_2013_bogen&id={$fields.form_id.value[i]}" target="_blank" >
               {if strlen($fields.report_param.value[i])}
                   <img class="popup-trigger" src="media/img/app/dmp/btn_edmp_export_very_small.png" alt="{#lbl_dmp_bogen#}" title=""/>
               {else}
                   <img class="popup-trigger" src="media/img/app/dmp/btn_edmp_very_small.png" alt="{#lbl_dmp_bogen#}" title=""/>
               {/if}

               <span class="info-popup above before" style="display:none;">
                   {$smarty.config.form_dmp_brustkrebs_ed_pnp_2013}
                   {if strlen($fields.report_param.value[i])}
                       {#lbl_dmp_exported#} {$fields.report_param.value[i]}
                   {/if}
               </span>
           </a>
       {/if}
       {if $fields.form.value[i]=="dmp_brustkrebs_fd_2013"}
           <a href="index.php?page=dmp_2013_popups&type=dmp_fd_2013_bogen&id={$fields.form_id.value[i]}" target="_blank" >
               {if strlen($fields.report_param.value[i])}
                   <img class="popup-trigger" src="media/img/app/dmp/btn_edmp_export_very_small.png" alt="{#lbl_dmp_bogen#}" title=""/>
               {else}
                   <img class="popup-trigger" src="media/img/app/dmp/btn_edmp_very_small.png" alt="{#lbl_dmp_bogen#}" title=""/>
               {/if}

               <span class="info-popup above before" style="display:none;">
                   {$smarty.config.form_dmp_brustkrebs_fd_2013}
                   {if strlen($fields.report_param.value[i])}
                       {#lbl_dmp_exported#} {$fields.report_param.value[i]}
                   {/if}
               </span>
           </a>
       {/if}
   </td>
   <td class="{$class} disease-information">
        <div style="width:325px; word-wrap: break-word">
            {$fields.form_data.value[i]}
        </div>
   </td>
   <td class="{$class} no-search">{$fields.reference.value[i]}</td>
   <td class="{$class}" align="center" >
      {if $fields.form.value[i] != 'qs_18_1_b'}
          <a href="index.php?page=lock&amp;location=view.erkrankung&amp;selected={$fields.status_id.value[i]}">
             <img src="media/img/app/lock/{$fields.status_lock.value[i]}.png" alt="{$fields.status_lock.value[i]}" title=""/>
          </a>
      {/if}
   </td>
   <td class="{$class}" align="center" >
      <span style="display:none">{$fields.status.value[i]}</span>

      {if $fields.form.value[i] == 'qs_18_1_b'}
          <a href="index.php?page=view.qs_18_1&amp;{$fields.form.value[i]}_id={$fields.form_id.value[i]}">
             <img class="popup-trigger" src="media/img/app/ampel/{$fields.form_status.value[i]}.png" alt="{$fields.form_status.bez[i]}" title=""/>
             <span class="info-popup above before" style="display:none;">{#lbl_status#}{#lbl_ddot#} {$fields.form_status.bez[i]}</span>
          </a>
      {else}
          <a href="index.php?page=rec.{$fields.form.value[i]}&amp;{$fields.form.value[i]}_id={$fields.form_id.value[i]}">
             <img class="popup-trigger" src="media/img/app/ampel/{$fields.form_status.value[i]}.png" alt="{$fields.form_status.bez[i]}" title=""/>
             <span class="info-popup above before" style="display:none;">{#lbl_status#}{#lbl_ddot#} {$fields.form_status.bez[i]}</span>
          </a>
      {/if}
   </td>
</tr>
{sectionelse}
<tr>
	<td class="even no-data" colspan="8">{#no_dataset#}</td>
</tr>
{/section}
