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
   <table class="listtable no-filter no-hover">
      <tr>
         <td class="head" colspan="2" align="center">
            <span>{#lbl_patient#}</span>
         </td>
      </tr>
      {if $viewOrg}
          <tr>
            <td colspan="2" style="background-color:#f2eadc" align="center">
                <table>
                    <tr>
                        <td>
                            <div style="padding-top:3px">
                                <img src="media/img/base/org.png" alt=""/>
                            </div>
                        </td>
                        <td><b>{$SESSION.sess_patient_data.org.name}</b></td>
                    </tr>
                </table>
            </td>
          </tr>
      {/if}
      <tr>
         <td class="sdinfo-{$SESSION.sess_patient_data.geschlecht}">
         	<a href="index.php?page=rec.patient&amp;patient_id={$SESSION.sess_patient_data.patient_id}" class="view-head">
               <b>{#lbl_stamm#}</b>
            </a>
         </td>
         <td class="sdinfo-{$SESSION.sess_patient_data.geschlecht}" align="right">
            <img style="float:right" class="patient-info" title="" src="media/img/base/patient{if strlen($SESSION.sess_patient_data.geschlecht)}-{$SESSION.sess_patient_data.geschlecht}{/if}.png" alt="patient"/>
            <div style="margin-right: 40px; font-size: 9pt">
               <span><b>{#lbl_name#}</b> {$SESSION.sess_patient_data.nachname}, {$SESSION.sess_patient_data.vorname}</span><br/>
               <span><b>{#lbl_geb#}:</b> {$SESSION.sess_patient_data.geburtsdatum|date_format:"%d.%m.%Y"}</span>
            </div>
         </td>
      </tr>
   </table>
</div>

<div class="loader">
   <img class="ajax-loader-img" src="media/img/ui/dlist-loader.gif" alt="loading" title=""/>
   <br/>
   <i>{#lbl_lade#}</i>
</div>

<div class="view-content"></div>