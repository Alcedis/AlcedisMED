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

<?xml version="1.0" encoding="UTF-8"?>
<onkeyline xmlns="http://kvn.de/ONKEYLINE" version="1.00">
   <lieferung>
      <lieferant_id>{$secondary.lieferung.lieferant_id}</lieferant_id>
      <lieferung_id>{$secondary.lieferung.lieferung_id}</lieferung_id>
      <liefer_id>{$secondary.lieferung.liefer_id}</liefer_id>
      <liefer_datum>{$secondary.lieferung.liefer_datum}</liefer_datum>
   </lieferung>
   <patient>
      <patient_id>{$secondary.patient_id}</patient_id>
   </patient>
   <dokumentation>
      <sekundaer id="{$secondary.lieferung.liefer_id}" refid="{$secondary.lieferung.ref_id}">
         <datum>{$secondary.datum}</datum>
         <infiltration_metastase_rezidiv>{$secondary.infiltration_metastase_rezidiv}</infiltration_metastase_rezidiv>
         <lokalisation>{$secondary.lokalisation}</lokalisation>
         <lokalisation_txt>{$secondary.lokalisation_txt}</lokalisation_txt>
         {if strlen( $secondary.seitenlokalisation ) > 0}
         <seitenlokalisation>{$secondary.seitenlokalisation}</seitenlokalisation>
         {/if}
         {if strlen( $secondary.differenzierung ) > 0}
         <differenzierung>{$secondary.differenzierung}</differenzierung>
         {/if}
         {if strlen( $secondary.mamma_histologie_code ) > 0 ||
         strlen( $secondary.mamma_histologie_txt ) > 0 ||
         strlen( $secondary.m_menopausenstatus ) > 0 ||
         strlen( $secondary.m_keine_tumgroesse_keineop ) > 0 ||
         strlen( $secondary.m_groesse_cm ) > 0 ||
         strlen( $secondary.m_hormonrezeptor_status ) > 0 ||
         strlen( $secondary.m_her2_neu_status ) > 0 ||
         strlen( $secondary.m_lkop_nein ) > 0 ||
         strlen( $secondary.m_sln_biopsie ) > 0 ||
         strlen( $secondary.m_axilladissektion ) > 0 ||
         strlen( $secondary.m_gesamt_befallen ) > 0 ||
         strlen( $secondary.m_gesamt_entnommen ) > 0 }
         <mamma_karzinom>
            <mamma_histologie_code>{$secondary.mamma_histologie_code}</mamma_histologie_code>
            <mamma_histologie_txt>{$secondary.mamma_histologie_txt}</mamma_histologie_txt>
            <m_menopausenstatus>{$secondary.m_menopausenstatus}</m_menopausenstatus>
            <m_tumorgroesse>
               {if strlen( $secondary.m_keine_tumgroesse_keineop ) > 0}
               <m_keine_tumgroesse_keineop>{$secondary.m_keine_tumgroesse_keineop}</m_keine_tumgroesse_keineop>
               {/if}
               {if strlen( $secondary.m_groesse_cm ) > 0}
               <m_groesse_cm>{$secondary.m_groesse_cm}</m_groesse_cm>
               {/if}
            </m_tumorgroesse>
            <m_hormonrezeptor_status>{$secondary.m_hormonrezeptor_status}</m_hormonrezeptor_status>
            <m_her2_neu_status>{$secondary.m_her2_neu_status}</m_her2_neu_status>
            <m_lymphknoten_op>
               {if strlen( $secondary.m_lkop_nein ) > 0}
               <m_lkop_nein>{$secondary.m_lkop_nein}</m_lkop_nein>
               {/if}
               {if $secondary.m_lkop_nein !== 'on'}
               <m_lkop_ja>
                  {if strlen( $secondary.m_sln_biopsie ) > 0}
                  <m_sln_biopsie>{$secondary.m_sln_biopsie}</m_sln_biopsie>
                  {/if}
                  {if strlen( $secondary.m_axilladissektion ) > 0}
                  <m_axilladissektion>{$secondary.m_axilladissektion}</m_axilladissektion>
                  {/if}
                  <m_gesamt_befallen>{$secondary.m_gesamt_befallen}</m_gesamt_befallen>
                  <m_gesamt_entnommen>{$secondary.m_gesamt_entnommen}</m_gesamt_entnommen>
               </m_lkop_ja>
               {/if}
            </m_lymphknoten_op>
         </mamma_karzinom>
         {/if}
         {if strlen($secondary.ct_wert) > 0}
         <c_tnm>
            <ct>
               <ctnm_wert>{$secondary.ct_wert}</ctnm_wert>
               <ctnm_txt>{$secondary.ct_wert_txt}</ctnm_txt>
               <c_faktor>
                  {if strlen( $secondary.ct_c2_diagnostische_verfahren ) > 0}
                  <c2_diagnostische_verfahren>{$secondary.ct_c2_diagnostische_verfahren}</c2_diagnostische_verfahren>
                  {/if}
               </c_faktor>
            </ct>
            <cn>
               <ctnm_wert>{$secondary.cn_wert}</ctnm_wert>
               <ctnm_txt>{$secondary.cn_wert_txt}</ctnm_txt>
               <c_faktor>
                  {if strlen( $secondary.cn_c2_diagnostische_verfahren ) > 0}
                  <c2_diagnostische_verfahren>{$secondary.cn_c2_diagnostische_verfahren}</c2_diagnostische_verfahren>
                  {/if}
               </c_faktor>
            </cn>
            <cm>
               <ctnm_wert>{$secondary.cm_wert}</ctnm_wert>
               <ctnm_txt>{$secondary.cm_wert_txt}</ctnm_txt>
               <c_faktor>
                  {if strlen( $secondary.cm_c2_diagnostische_verfahren ) > 0}
                  <c2_diagnostische_verfahren>{$secondary.cm_c2_diagnostische_verfahren}</c2_diagnostische_verfahren>
                  {/if}
               </c_faktor>
            </cm>
         </c_tnm>
         {/if}
         {if strlen($secondary.pt_wert) > 0}
         <p_tnm>
            <pt>
               <ptnm_wert>{$secondary.pt_wert}</ptnm_wert>
               <ptnm_txt>{$secondary.pt_wert_txt}</ptnm_txt>
            </pt>
            <pn>
               <ptnm_wert>{$secondary.pn_wert}</ptnm_wert>
               <ptnm_txt>{$secondary.pn_wert_txt}</ptnm_txt>
            </pn>
            <pm>
               <ptnm_wert>{$secondary.pm_wert}</ptnm_wert>
               <ptnm_txt>{$secondary.pm_wert_txt}</ptnm_txt>
            </pm>
            <grading>{$secondary.grading}</grading>
            <residualtumor>{$secondary.residualtumor}</residualtumor>
         </p_tnm>
         {/if}
         {if strlen( $secondary.pathologe ) > 0}
         <pathologe>{$secondary.pathologe}</pathologe>
         {/if}
         {if strlen( $secondary.befundnummer ) > 0}
         <befundnummer>{$secondary.befundnummer}</befundnummer>
         {/if}
         {if strlen( $secondary.bemerkung_lst ) > 0}
         <bemerkung_lst>{$secondary.bemerkung_lst}</bemerkung_lst>
         {/if}
      </sekundaer>
   </dokumentation>
</onkeyline>
