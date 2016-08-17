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

<div id="sidebar" class="bfl">
   <table class="sidebartable">
   <tr>
      <td class="subhead" colspan="3"><a id="remove-filter" href="#"><img class="remove-filter-img" src="media/img/base/remove-filter.png" alt="remove-filter"/> {#lbl_filter_entf#}</a></td>
   </tr>
   {html_sidebar_element lbl=#anamnese#                           href='rec.anamnese'                      param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='anamnese'                       permission=$alcPermission}
   {html_sidebar_element lbl=#untersuchung#                       href='rec.untersuchung'                  param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='untersuchung'                   permission=$alcPermission}
   {html_sidebar_element lbl=#labor#                              href='rec.labor'                         param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='labor'                          permission=$alcPermission}
   {html_sidebar_element lbl=#diagnose#                           href='rec.diagnose'                      param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='diagnose'                       permission=$alcPermission}
   {html_sidebar_element lbl=#eingriff#                           href='rec.eingriff'                      param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='eingriff'                       permission=$alcPermission}
   {html_sidebar_element lbl=#komplikation#                       href='rec.komplikation'                  param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='komplikation'                   permission=$alcPermission}
   {html_sidebar_element lbl=#histologie#                         href='rec.histologie'                    param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='histologie'                     permission=$alcPermission}
   {html_sidebar_element lbl=#zytologie#                          href='rec.zytologie'                     param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='zytologie'                      permission=$alcPermission}
   {html_sidebar_element lbl=#tumorstatus#                        href='rec.tumorstatus'                   param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='tumorstatus'                    permission=$alcPermission}

   {if $SESSION.settings.zweitmeinung == 1}
      {html_sidebar_element lbl=#zweitmeinung#                   href='rec.zweitmeinung'                  param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='zweitmeinung'                    permission=$alcPermission}
   {/if}

   {if $SESSION.settings.konferenz == 1}
      {html_sidebar_element lbl=#konferenz_patient#                  href='rec.konferenz_patient'             param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='konferenz_patient'          permission=$alcPermission}
   {else}
      {html_sidebar_element lbl=#konferenz_patient#                  href='rec.konferenz_patient'             param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='konferenz_patient'          noNew=true}
   {/if}

   {html_sidebar_element lbl=#therapieplan#                       href='rec.therapieplan'                  param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='therapieplan'                  permission=$alcPermission}
   {html_sidebar_element lbl=#therapieplan_abweichung#            href='rec.therapieplan_abweichung'       param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='therapieplan_abweichung'       permission=$alcPermission}
   {html_sidebar_element lbl=#therapie_systemisch#                href='rec.therapie_systemisch'           param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='therapie_systemisch'           permission=$alcPermission}
   {html_sidebar_element lbl=#therapie_systemisch_zyklus#         noNew=true href='rec.therapie_systemisch_zyklus'    param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='therapie_systemisch_zyklus'    permission=$alcPermission}
   {html_sidebar_element lbl=#therapie_systemisch_zyklustag#      noNew=true href='rec.therapie_systemisch_zyklustag' param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='therapie_systemisch_zyklustag' permission=$alcPermission}
   {html_sidebar_element lbl=#strahlentherapie#                   href='rec.strahlentherapie'              param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='strahlentherapie'              permission=$alcPermission}
   {html_sidebar_element lbl=#sonstige_therapie#                  href='rec.sonstige_therapie'             param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='sonstige_therapie'             permission=$alcPermission}
   {html_sidebar_element lbl=#nebenwirkung#                       href='rec.nebenwirkung'                  param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='nebenwirkung'                  permission=$alcPermission}
   {html_sidebar_element lbl=#begleitmedikation#                  href='rec.begleitmedikation'             param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='begleitmedikation'             permission=$alcPermission}
   {html_sidebar_element lbl=#studie#                             href='rec.studie'                        param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='studie'                        permission=$alcPermission}
   {html_sidebar_element lbl=#beratung#                           href='rec.beratung'                      param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='beratung'                      permission=$alcPermission}
   {html_sidebar_element lbl=#foto#                               href='rec.foto'                          param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='foto'                          permission=$alcPermission}

   {if $SESSION.settings.dokument == 1}
       {html_sidebar_element lbl=#dokument#                          href='rec.dokument'                     param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='dokument'                       permission=$alcPermission}
   {/if}

   {html_sidebar_element lbl=#brief#                              href='rec.brief'                         param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='brief'                         permission=$alcPermission}
   {html_sidebar_element lbl=#termin#                             href='rec.termin'                        param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='termin'                        permission=$alcPermission}
   {html_sidebar_element lbl=#ekr#                                href='rec.ekr'                           param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='ekr'                           permission=$alcPermission}
   {html_sidebar_element lbl=#fragebogen#                         href='rec.fragebogen'                    param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='fragebogen'                    permission=$alcPermission}
   {if $SESSION.sess_erkrankung_data.code === 'b'}
       {if strpos($SESSION.settings.interfaces, 'dmp_2014') !== false}
           {html_sidebar_element lbl=#dmp_brustkrebs_ed_2013#          href='rec.dmp_brustkrebs_ed_2013'        param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='dmp_brustkrebs_ed_2013'   permission=$alcPermission}
           {html_sidebar_element lbl=#dmp_brustkrebs_ed_pnp_2013#      noNew=true href='rec.dmp_brustkrebs_ed_pnp_2013'    param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='dmp_brustkrebs_ed_pnp_2013'        permission=$alcPermission}
           {html_sidebar_element lbl=#dmp_brustkrebs_fd_2013#          noNew=true href='rec.dmp_brustkrebs_fd_2013'        param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='dmp_brustkrebs_fd_2013'        permission=$alcPermission}
       {/if}
       {if strpos($SESSION.settings.interfaces, 'qs181') !== false}
           {html_sidebar_element lbl=#qs_18_1_b#                 		href='rec.qs_18_1_b'                     param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='qs_18_1_b'                     permission=$alcPermission}
       {/if}
   {/if}
   {html_sidebar_element lbl=#nachsorge#                 href='rec.nachsorge'                     param="patient_id=$patient_id&amp;erkrankung_id=$erkrankung_id" ref='nachsorge'                     permission=$alcPermission}
   </table>
</div>
