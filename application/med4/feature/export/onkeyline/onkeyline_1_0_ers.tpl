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
      <lieferant_id>{$firstIntroduction.lieferung.lieferant_id}</lieferant_id>
      <lieferung_id>{$firstIntroduction.lieferung.lieferung_id}</lieferung_id>
      <liefer_id>{$firstIntroduction.lieferung.liefer_id}</liefer_id>
      <liefer_datum>{$firstIntroduction.lieferung.liefer_datum}</liefer_datum>
   </lieferung>
   <patient>
      <patient_id>{$firstIntroduction.patient_id}</patient_id>
   </patient>
   <dokumentation>
      <erstvorstellung id="{$firstIntroduction.lieferung.liefer_id}">
         {if strlen( $firstIntroduction.diagnose_icd ) > 0}
         <diagnose_icd>{$firstIntroduction.diagnose_icd}</diagnose_icd>
         {/if}
         {if strlen( $firstIntroduction.diagnose_text ) > 0}
         <diagnose_text>{$firstIntroduction.diagnose_text}</diagnose_text>
         {/if}
         <diagnose_datum>{$firstIntroduction.diagnose_datum}</diagnose_datum>
         <diagnosesicherung>
            {if strlen( $firstIntroduction.nicht_gesichert ) > 0}
            <nicht_gesichert>{$firstIntroduction.nicht_gesichert}</nicht_gesichert>
            {else}
               {if strlen( $firstIntroduction.histologisch ) > 0 || strlen( $firstIntroduction.zytologisch ) > 0}
               <gesichert>
                  {if strlen( $firstIntroduction.histologisch ) > 0}
                  <histologisch>{$firstIntroduction.histologisch}</histologisch>
                  {else}
                     <zytologisch>{$firstIntroduction.zytologisch}</zytologisch>
                  {/if}
               </gesichert>
               {/if}
            {/if}
         </diagnosesicherung>
         <lokalisation>{$firstIntroduction.lokalisation}</lokalisation>
         <lokalisation_txt>{$firstIntroduction.lokalisation_txt}</lokalisation_txt>
         <seitenlokalisation>{$firstIntroduction.seitenlokalisation}</seitenlokalisation>
         <histologie_zytologie>{$firstIntroduction.histologie_zytologie}</histologie_zytologie>
         {if strlen( $firstIntroduction.histologie_zytologie_code ) > 0}
         <histologie_zytologie_code>{$firstIntroduction.histologie_zytologie_code}</histologie_zytologie_code>
         {/if}
         {if strlen( $firstIntroduction.pathologe ) > 0}
         <pathologe>{$firstIntroduction.pathologe}</pathologe>
         {/if}
         {if strlen( $firstIntroduction.histologie_nummer ) > 0}
         <histologie_nummer>{$firstIntroduction.histologie_nummer}</histologie_nummer>
         {/if}
         {if strlen( $firstIntroduction.mamma_histologie_code ) > 0 ||
         strlen( $firstIntroduction.mamma_histologie_txt ) > 0 ||
         strlen( $firstIntroduction.m_menopausenstatus ) > 0 ||
         strlen( $firstIntroduction.m_keine_tumgroesse_keineop ) > 0 ||
         strlen( $firstIntroduction.m_groesse_cm ) > 0 ||
         strlen( $firstIntroduction.m_hormonrezeptor_status ) > 0 ||
         strlen( $firstIntroduction.m_her2_neu_status ) > 0 ||
         strlen( $firstIntroduction.m_lkop_nein ) > 0 ||
         strlen( $firstIntroduction.m_sln_biopsie ) > 0 ||
         strlen( $firstIntroduction.m_axilladissektion ) > 0 ||
         strlen( $firstIntroduction.m_gesamt_befallen ) > 0 ||
         strlen( $firstIntroduction.m_gesamt_entnommen ) > 0 }
         <mamma_karzinom>
            <mamma_histologie_code>{$firstIntroduction.mamma_histologie_code}</mamma_histologie_code>
            <mamma_histologie_txt>{$firstIntroduction.mamma_histologie_txt}</mamma_histologie_txt>
            <m_menopausenstatus>{$firstIntroduction.m_menopausenstatus}</m_menopausenstatus>
            <m_tumorgroesse>
               {if strlen( $firstIntroduction.m_keine_tumgroesse_keineop ) > 0}
               <m_keine_tumgroesse_keineop>{$firstIntroduction.m_keine_tumgroesse_keineop}</m_keine_tumgroesse_keineop>
               {/if}
               {if strlen( $firstIntroduction.m_groesse_cm ) > 0}
               <m_groesse_cm>{$firstIntroduction.m_groesse_cm}</m_groesse_cm>
               {/if}
            </m_tumorgroesse>
            <m_hormonrezeptor_status>{$firstIntroduction.m_hormonrezeptor_status}</m_hormonrezeptor_status>
            <m_her2_neu_status>{$firstIntroduction.m_her2_neu_status}</m_her2_neu_status>
            <m_lymphknoten_op>
               {if strlen( $firstIntroduction.m_lkop_nein ) > 0}
               <m_lkop_nein>{$firstIntroduction.m_lkop_nein}</m_lkop_nein>
               {/if}
               {if $firstIntroduction.m_lkop_nein !== 'on'}
               <m_lkop_ja>
                  {if strlen( $firstIntroduction.m_sln_biopsie ) > 0}
                  <m_sln_biopsie>{$firstIntroduction.m_sln_biopsie}</m_sln_biopsie>
                  {/if}
                  {if strlen( $firstIntroduction.m_axilladissektion ) > 0}
                  <m_axilladissektion>{$firstIntroduction.m_axilladissektion}</m_axilladissektion>
                  {/if}
                  {if strlen( $firstIntroduction.m_gesamt_befallen ) > 0}
                  <m_gesamt_befallen>{$firstIntroduction.m_gesamt_befallen}</m_gesamt_befallen>
                  {/if}
                  {if strlen( $firstIntroduction.m_gesamt_entnommen ) > 0}
                  <m_gesamt_entnommen>{$firstIntroduction.m_gesamt_entnommen}</m_gesamt_entnommen>
                  {/if}
               </m_lkop_ja>
               {/if}
            </m_lymphknoten_op>
         </mamma_karzinom>
         {/if}
         <basalzell_karzinom>
            {if strlen( $firstIntroduction.nein ) > 0}
            <nein>{$firstIntroduction.nein}</nein>
            {else}
            <ja>
               {if strlen( $firstIntroduction.basaliom_histologie_code ) > 0}
               <basaliom_histologie_code>{$firstIntroduction.basaliom_histologie_code}</basaliom_histologie_code>
               {/if}
               {if strlen( $firstIntroduction.basaliom_histologie_txt ) > 0}
               <basaliom_histologie_txt>{$firstIntroduction.basaliom_histologie_txt}</basaliom_histologie_txt>
               {/if}
            </ja>

            {/if}
         </basalzell_karzinom>
         <kolon_karzinom>
            {if strlen( $firstIntroduction.kras_faktor ) > 0}
            <kras_faktor>{$firstIntroduction.kras_faktor}</kras_faktor>
            {/if}
         </kolon_karzinom>
         <infiltration>
            {if strlen( $firstIntroduction.infKeine ) > 0}
            <keine>{$firstIntroduction.infKeine}</keine>
            {/if}
         </infiltration>
         <tnm_klassifikation>
            {if strlen($firstIntroduction.tnm_klassifikation_keine) > 0 }
            <keine>{$firstIntroduction.tnm_klassifikation_keine}</keine>
            {elseif strlen($firstIntroduction.pt_wert) > 0 &&
                    strlen($firstIntroduction.pn_wert) > 0 &&
                    strlen($firstIntroduction.pm_wert) > 0 }
            <histopathologisch>
               <pt>
                  <ptnm_wert>{$firstIntroduction.pt_wert}</ptnm_wert>
                  <ptnm_txt>{$firstIntroduction.pt_wert_txt}</ptnm_txt>
                  <c_faktor>
                    <c4_operation_histologie>{$firstIntroduction.pt_c4_operation_histologie}</c4_operation_histologie>
                  </c_faktor>
               </pt>
               <pn>
                  <ptnm_wert>{$firstIntroduction.pn_wert}</ptnm_wert>
                  <ptnm_txt>{$firstIntroduction.pn_wert_txt}</ptnm_txt>
                  <c_faktor>
                    <c4_operation_histologie>{$firstIntroduction.pn_c4_operation_histologie}</c4_operation_histologie>
                  </c_faktor>
               </pn>
               <pm>
                  <ptnm_wert>{$firstIntroduction.pm_wert}</ptnm_wert>
                  <ptnm_txt>{$firstIntroduction.pm_wert_txt}</ptnm_txt>
                  <c_faktor>
                  {if strlen($firstIntroduction.pm_c4_operation_histologie) > 0 }
                    <c4_operation_histologie>{$firstIntroduction.pm_c4_operation_histologie}</c4_operation_histologie>
                  {/if}
                  </c_faktor>
               </pm>
               <grading>{$firstIntroduction.grading}</grading>
               <residualtumor>{$firstIntroduction.residualtumor}</residualtumor>
               <lymph_invasion>{$firstIntroduction.lymph_invasion}</lymph_invasion>
               <venen_invasion>{$firstIntroduction.venen_invasion}</venen_invasion>
            </histopathologisch>
            {elseif strlen($firstIntroduction.ct_wert) > 0 &&
                    strlen($firstIntroduction.cn_wert) > 0 &&
                    strlen($firstIntroduction.cm_wert) }
            <klinisch>
               <ct>
                  <ctnm_wert>{$firstIntroduction.ct_wert}</ctnm_wert>
                  <ctnm_txt>{$firstIntroduction.ct_wert_txt}</ctnm_txt>
                  <c_faktor>
                     {if strlen( $firstIntroduction.ct_c2_diagnostische_verfahren ) > 0}
                     <c2_diagnostische_verfahren>{$firstIntroduction.ct_c2_diagnostische_verfahren}</c2_diagnostische_verfahren>
                     {/if}
                  </c_faktor>
               </ct>
               <cn>
                  <ctnm_wert>{$firstIntroduction.cn_wert}</ctnm_wert>
                  <ctnm_txt>{$firstIntroduction.cn_wert_txt}</ctnm_txt>
                  <c_faktor>
                     {if strlen( $firstIntroduction.cn_c2_diagnostische_verfahren ) > 0}
                     <c2_diagnostische_verfahren>{$firstIntroduction.cn_c2_diagnostische_verfahren}</c2_diagnostische_verfahren>
                     {/if}
                  </c_faktor>
               </cn>
               <cm>
                  <ctnm_wert>{$firstIntroduction.cm_wert}</ctnm_wert>
                  <ctnm_txt>{$firstIntroduction.cm_wert_txt}</ctnm_txt>
                  <c_faktor>
                     {if strlen( $firstIntroduction.cm_c2_diagnostische_verfahren ) > 0}
                     <c2_diagnostische_verfahren>{$firstIntroduction.cm_c2_diagnostische_verfahren}</c2_diagnostische_verfahren>
                     {/if}
                  </c_faktor>
               </cm>
            </klinisch>
            {/if}
         </tnm_klassifikation>
         <primaertumor_angaben>
            {if strlen( $firstIntroduction.klassifikation ) > 0}
            <klassifikation>{$firstIntroduction.klassifikation}</klassifikation>
            {/if}
            <regionaere_lymphknoten>{$firstIntroduction.regionaere_lymphknoten}</regionaere_lymphknoten>
         </primaertumor_angaben>
         <maligne_vorerkrankungen>
            {if strlen( $firstIntroduction.maligne_nein ) > 0}
            <nein>{$firstIntroduction.maligne_nein}</nein>
            {/if}
            {if strlen( $firstIntroduction.bereits_bekannt ) > 0}
            <bereits_bekannt>{$firstIntroduction.bereits_bekannt}</bereits_bekannt>
            {/if}
            {if strlen( $firstIntroduction.bereits_bekannt ) == 0 && strlen( $firstIntroduction.maligne_nein ) == 0}
            <folgende_Angaben>
               <malvorerkrankung_1>
                  <art_erkrankung>{$firstIntroduction.art_erkrankung1}</art_erkrankung>
                  <dia_datum>{$firstIntroduction.dia_datum1}</dia_datum>
                  <therapie>
                     {if strlen( $firstIntroduction.operation1 ) > 0}
                     <operation>{$firstIntroduction.operation1}</operation>
                     {/if}
                     {if strlen( $firstIntroduction.hormontherapie1 ) > 0}
                     <hormontherapie>{$firstIntroduction.hormontherapie1}</hormontherapie>
                     {/if}
                     {if strlen( $firstIntroduction.strahlentherapie1 ) > 0}
                     <strahlentherapie>{$firstIntroduction.strahlentherapie1}</strahlentherapie>
                     {/if}
                     {if strlen( $firstIntroduction.immuntherapie1 ) > 0}
                     <immuntherapie>{$firstIntroduction.immuntherapie1}</immuntherapie>
                     {/if}
                     {if strlen( $firstIntroduction.chemotherapie1 ) > 0}
                     <chemotherapie>{$firstIntroduction.chemotherapie1}</chemotherapie>
                     {/if}
                     {if strlen( $firstIntroduction.sonstige1 ) > 0}
                     <sonstige>{$firstIntroduction.sonstige1}</sonstige>
                     {/if}
                  </therapie>
               </malvorerkrankung_1>
               {if strlen( $firstIntroduction.art_erkrankung2 ) > 0}
               <malvorerkrankung_2>
                  {if strlen( $firstIntroduction.art_erkrankung2 ) > 0}
                  <art_erkrankung>{$firstIntroduction.art_erkrankung2}</art_erkrankung>
                  {/if}
                  {if strlen( $firstIntroduction.dia_datum2 ) > 0}
                  <dia_datum>{$firstIntroduction.dia_datum2}</dia_datum>
                  {/if}
                  <therapie>
                     {if strlen( $firstIntroduction.operation2 ) > 0}
                     <operation>{$firstIntroduction.operation2}</operation>
                     {/if}
                     {if strlen( $firstIntroduction.hormontherapie2 ) > 0}
                     <hormontherapie>{$firstIntroduction.hormontherapie2}</hormontherapie>
                     {/if}
                     {if strlen( $firstIntroduction.strahlentherapie2 ) > 0}
                     <strahlentherapie>{$firstIntroduction.strahlentherapie2}</strahlentherapie>
                     {/if}
                     {if strlen( $firstIntroduction.immuntherapie2 ) > 0}
                     <immuntherapie>{$firstIntroduction.immuntherapie2}</immuntherapie>
                     {/if}
                     {if strlen( $firstIntroduction.chemotherapie2 ) > 0}
                     <chemotherapie>{$firstIntroduction.chemotherapie2}</chemotherapie>
                     {/if}
                     {if strlen( $firstIntroduction.sonstige2 ) > 0}
                     <sonstige>{$firstIntroduction.sonstige2}</sonstige>
                     {/if}
                  </therapie>
               </malvorerkrankung_2>
               {/if}
               {if strlen( $firstIntroduction.art_erkrankung3 ) > 0}
               <malvorerkrankung_3>
                  {if strlen( $firstIntroduction.art_erkrankung3 ) > 0}
                  <art_erkrankung>{$firstIntroduction.art_erkrankung3}</art_erkrankung>
                  {/if}
                  {if strlen( $firstIntroduction.dia_datum3 ) > 0}
                  <dia_datum>{$firstIntroduction.dia_datum3}</dia_datum>
                  {/if}
                  <therapie>
                     {if strlen( $firstIntroduction.operation3 ) > 0}
                     <operation>{$firstIntroduction.operation3}</operation>
                     {/if}
                     {if strlen( $firstIntroduction.hormontherapie3 ) > 0}
                     <hormontherapie>{$firstIntroduction.hormontherapie3}</hormontherapie>
                     {/if}
                     {if strlen( $firstIntroduction.strahlentherapie3 ) > 0}
                     <strahlentherapie>{$firstIntroduction.strahlentherapie3}</strahlentherapie>
                     {/if}
                     {if strlen( $firstIntroduction.immuntherapie3 ) > 0}
                     <immuntherapie>{$firstIntroduction.immuntherapie3}</immuntherapie>
                     {/if}
                     {if strlen( $firstIntroduction.chemotherapie3 ) > 0}
                     <chemotherapie>{$firstIntroduction.chemotherapie3}</chemotherapie>
                     {/if}
                     {if strlen( $firstIntroduction.sonstige3 ) > 0}
                     <sonstige>{$firstIntroduction.sonstige3}</sonstige>
                     {/if}
                  </therapie>
               </malvorerkrankung_3>
               {/if}
            </folgende_Angaben>
            {/if}
         </maligne_vorerkrankungen>
         <fernmetastasen>
            {if strlen($firstIntroduction.meta_lokalisation) > 0 || strlen($firstIntroduction.meta_lokalisation_txt) > 0}
            <ja>
               <lokalisationen>
                  <lokalisation>{$firstIntroduction.meta_lokalisation}</lokalisation>
                  <lokalisation_txt>{$firstIntroduction.meta_lokalisation_txt}</lokalisation_txt>
                  <datum>{$firstIntroduction.datum}</datum>
               </lokalisationen>
            </ja>
            {/if}
            {if strlen( $firstIntroduction.fernmetastasen_nein ) > 0}
            <nein>{$firstIntroduction.fernmetastasen_nein}</nein>
            {/if}
         </fernmetastasen>
         <tumorspezifische_behandlung>
            {if strlen( $firstIntroduction.behandlung_keine ) > 0}
            <keine>{$firstIntroduction.behandlung_keine}</keine>
            {/if}
            {if strlen( $firstIntroduction.weitere_Boegen ) > 0}
            <weitere_Boegen>{$firstIntroduction.weitere_Boegen}</weitere_Boegen>
            {/if}
         </tumorspezifische_behandlung>
         {if strlen( $firstIntroduction.bemerkungen ) > 0}
         <bemerkungen>{$firstIntroduction.bemerkungen}</bemerkungen>
         {/if}
         <tumorspezifisches_vorgehen>
            {if strlen( $firstIntroduction.patient_verstorben ) > 0}
            <patient_verstorben>{$firstIntroduction.patient_verstorben}</patient_verstorben>
            {/if}
            {if strlen( $firstIntroduction.keine_therapie ) > 0}
            <keine_therapie>{$firstIntroduction.keine_therapie}</keine_therapie>
            {/if}
            {if strlen( $firstIntroduction.operative_therapie ) > 0}
            <operative_therapie>{$firstIntroduction.operative_therapie}</operative_therapie>
            {/if}
            {if strlen( $firstIntroduction.strahlen_therapie ) > 0}
            <strahlen_therapie>{$firstIntroduction.strahlen_therapie}</strahlen_therapie>
            {/if}
            {if strlen( $firstIntroduction.zytostatika_therapie ) > 0}
            <zytostatika_therapie>{$firstIntroduction.zytostatika_therapie}</zytostatika_therapie>
            {/if}
            {if strlen( $firstIntroduction.hormon_therapie ) > 0}
            <hormon_therapie>{$firstIntroduction.hormon_therapie}</hormon_therapie>
            {/if}
            {if strlen( $firstIntroduction.immun_therapie ) > 0}
            <immun_therapie>{$firstIntroduction.immun_therapie}</immun_therapie>
            {/if}
            {if strlen( $firstIntroduction.patient_nachsorge ) > 0}
            <patient_nachsorge>{$firstIntroduction.patient_nachsorge}</patient_nachsorge>
            {/if}
         </tumorspezifisches_vorgehen>
         {if strlen( $firstIntroduction.hausarzt ) > 0}
         <hausarzt>{$firstIntroduction.hausarzt}</hausarzt>
         {/if}
         <anlass_erfassung>{$firstIntroduction.anlass_erfassung}</anlass_erfassung>
      </erstvorstellung>
   </dokumentation>
</onkeyline>
