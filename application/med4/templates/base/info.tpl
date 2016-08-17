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

{if isset($patientInfoData) == true || isset($erkrankungData) == true}
	<table class="user-info-table" width="100%" >
		<tr>
			{if isset($patientInfoData) == true}

				<td style="text-align:left;">

					{if $patientoverview}
						<a href="index.php?page=view.patient&amp;patient_id={$patientoverview}">
							<img class="popup-trigger back-img" alt="" style="padding-top:1px" src="media/img/base/patient-overview.png"/>
						</a>
   					<div class="info-popup below center" style="display:none;">{$smarty.config.patientoverview}</div>
					{/if}

					<strong>Name:</strong>
					{$patientInfoData.name|truncate:44:"...":true}
				</td>

				{if isset($erkrankungData) == true}
					<td style="text-align:right; width:280px;">
					<strong>{#lbl_info_erkrankung#}</strong> {$erkrankungData.bez}
                  <span class="erkrankung-info-code" style="display:none">
                     {$erkrankungData.code}
                  </span>
               </td>
            {/if}

				{if strlen($patientInfoData.geschlecht) > 0}
					<td style="text-align:right; width:120px; ">
						<strong>Geschlecht:</strong>
				         {if $patientInfoData.geschlecht == 'm'}
				            <img style="position:relative; top: 0px" src="media/img/base/infobar-m.png" alt="" />
				         {elseif $patientInfoData.geschlecht == 'w'}
				            <img style="position:relative; top: 1px" src="media/img/base/infobar-w.png" alt="" />
				         {/if}
					 </td>
				{/if}

            <td style="width: 190px; text-align:right" >
					<strong>Geburtsdatum:</strong> {$patientInfoData.geburtsdatum|date_format:"%d.%m.%Y"}
				</td>
			{/if}
		</tr>
	</table>
{/if}

{if strpos('konferenz', $page) !== false && isset($konferenzName) == true && strlen($konferenzName) > 0 && isset($patientInfoData) == false && isset($erkrankungData) == false}
   <table class="user-info-table" width="100%" >
      <tr>
         <td>
            <strong>Konferenz:</strong> {$konferenzName|truncate:44:"...":true}
         </td>
      </tr>
   </table>
{/if}