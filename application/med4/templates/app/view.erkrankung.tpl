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

<div id="stammdaten-info">
   <table class="listtable no-filter no-hover" border="0">
      <tr>
         <td class="head" colspan="3" align="center">
            <span>{#lbl_info#}</span>
         </td>
      </tr>
      <tr>
         <td class="erkrankunglist" style="width:17%">
            <a href="index.php?page=rec.erkrankung&amp;patient_id={$patient_id}&amp;erkrankung_id={$erkrankung_id}" class="view-head">
               <b>{#lbl_erkrankung#}</b>
            </a>
         </td>
         <td class="erkrankunglist">
            {$kurzbeschreibung_der_erkrankung}
         </td>
         <td class="erkrankunglist" align="right" style="width:13%">
            {if $SESSION.sess_erkrankung_data.code === 'b' && strpos($SESSION.settings.interfaces, 'dmp') !== false}
	           <a class="popup-trigger" style="padding-right:3px" href="index.php?page=report&amp;action=report&amp;sub=b&amp;name=dmp_einverstaendnis&amp;type=pdf&amp;footer=none&amp;patient_id={$patient_id}" target="_blank">
	              <img src="media/img/base/pdf_very_small.png" alt="{#lbl_dmp_einverstaendnis#}" title=""/>
	              <span class="info-popup above before" style="display:none;">{#lbl_dmp_einverstaendnis#}</span>
	           </a>
            {/if}

            <a href="index.php?page=lock&amp;location=view.erkrankung&amp;selected={$status_id}" style="padding-right:5px">
               <img src="media/img/app/lock/{$status_lock}.png" alt="{$status_lock}" title=""/>
            </a>

            <a class="popup-trigger" style="padding-right:3px" href="index.php?page=rec.erkrankung&amp;patient_id={$patient_id}&amp;erkrankung_id={$erkrankung_id}">
               <img src="media/img/app/ampel/{$status}.png" alt="" title="" />
               <span class="info-popup above before" style="display:none;">{#lbl_status#}{#lbl_ddot#} {$status_bez}</span>
            </a>
         </td>
      </tr>
   </table>
</div>

{include file=$erkrankungView}