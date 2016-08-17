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
      <lieferant_id>{$melanom.lieferung.lieferant_id}</lieferant_id>
      <lieferung_id>{$melanom.lieferung.lieferung_id}</lieferung_id>
      <liefer_id>{$melanom.lieferung.liefer_id}</liefer_id>
      <liefer_datum>{$melanom.lieferung.liefer_datum}</liefer_datum>
   </lieferung>
   <patient>
      <patient_id>{$melanom.patient_id}</patient_id>
   </patient>
   <dokumentation>
      <melanom id="{$melanom.lieferung.liefer_id}" refid="{$melanom.lieferung.ref_id}">
         <diagnose_datum>{$melanom.diagnose_datum}</diagnose_datum>
         <lokalisation>{$melanom.lokalisation}</lokalisation>
         <lokalisation_txt>{$melanom.lokalisation_txt}</lokalisation_txt>
         <seitenlokalisation>{$melanom.seitenlokalisation}</seitenlokalisation>
         <melanom_histologie_code>{$melanom.melanom_histologie_code}</melanom_histologie_code>
         <melanom_histologie_txt>{$melanom.melanom_histologie_txt}</melanom_histologie_txt>
         <regressionszeichen>{$melanom.regressionszeichen}</regressionszeichen>
         <ulzeration_erosion>{$melanom.ulzeration_erosion}</ulzeration_erosion>
         <pathologe>{$melanom.pathologe}</pathologe>
         <histologie_nummer>{$melanom.histologie_nummer}</histologie_nummer>
         <primarius_gefunden>{$melanom.primarius_gefunden}</primarius_gefunden>
         <fernemtastasen>
             {if strlen($melanom.nein) == 0}
                <ja>
                   <lokalisationen>
                      <lokalisation>{$melanom.lokalisation2}</lokalisation>
                      <lokalisation_txt>{$melanom.lokalisation_txt2}</lokalisation_txt>
                      <datum>{$melanom.datum}</datum>
                   </lokalisationen>
                </ja>
            {else}
                <nein>{$melanom.nein}</nein>
            {/if}
         </fernemtastasen>
         {if isset($melanom.pt_wert)}
         <ptnm>
            <pt>
               <ptnm_wert>{$melanom.pt_wert}</ptnm_wert>
               <ptnm_txt>{$melanom.pt_txt}</ptnm_txt>
            </pt>
            <pn>
               <ptnm_wert>{$melanom.pn_wert}</ptnm_wert>
               <ptnm_txt>{$melanom.pn_txt}</ptnm_txt>
            </pn>
            <pm>
               <ptnm_wert>{$melanom.pm_wert}</ptnm_wert>
               <ptnm_txt>{$melanom.pm_txt}</ptnm_txt>
            </pm>
         </ptnm>
         {/if}
         <tumordicke_breslow_mm>{$melanom.tumordicke_breslow_mm}</tumordicke_breslow_mm>
         <clark_level>
            {if strlen( $melanom.clark_level_I ) > 0}
            <clark_level_I>{$melanom.clark_level_I}</clark_level_I>
            {/if}
            {if strlen( $melanom.clark_level_II ) > 0}
            <clark_level_II>{$melanom.clark_level_II}</clark_level_II>
            {/if}
            {if strlen( $melanom.clark_level_III ) > 0}
            <clark_level_III>{$melanom.clark_level_III}</clark_level_III>
            {/if}
            {if strlen( $melanom.clark_level_IV ) > 0}
            <clark_level_IV>{$melanom.clark_level_IV}</clark_level_IV>
            {/if}
            {if strlen( $melanom.clark_level_V ) > 0}
            <clark_level_V>{$melanom.clark_level_V}</clark_level_V>
            {/if}
         </clark_level>
         <maligne_vorerkrankungen>
            {if strlen( $melanom.vorerkrankungen_nein ) > 0}
            <nein>{$melanom.vorerkrankungen_nein}</nein>
            {/if}
            {if strlen( $melanom.bereits_bekannt ) > 0 && strlen( $melanom.vorerkrankungen_nein ) == 0 }
            <bereits_bekannt>{$melanom.bereits_bekannt}</bereits_bekannt>
            {/if}
            {if strlen( $melanom.bereits_bekannt ) == 0 && strlen( $melanom.vorerkrankungen_nein ) == 0 }
            <folgende_Angaben>
	            <malvorerkrankung_1>
	               <art_erkrankung>{$melanom.art_erkrankung1}</art_erkrankung>
	               <dia_datum>{$melanom.dia_datum1}</dia_datum>
	               <therapie>
	                  {if strlen( $melanom.operation1 ) > 0}
	                  <operation>{$melanom.operation1}</operation>
	                  {/if}
	                  {if strlen( $melanom.hormontherapie1 ) > 0}
	                  <hormontherapie>{$melanom.hormontherapie1}</hormontherapie>
	                  {/if}
	                  {if strlen( $melanom.strahlentherapie1 ) > 0}
	                  <strahlentherapie>{$melanom.strahlentherapie1}</strahlentherapie>
	                  {/if}
	                  {if strlen( $melanom.immuntherapie1 ) > 0}
	                  <immuntherapie>{$melanom.immuntherapie1}</immuntherapie>
	                  {/if}
	                  {if strlen( $melanom.chemotherapie1 ) > 0}
	                  <chemotherapie>{$melanom.chemotherapie1}</chemotherapie>
	                  {/if}
	                  {if strlen( $melanom.sonstige1 ) > 0}
	                  <sonstige>{$melanom.sonstige1}</sonstige>
	                  {/if}
	               </therapie>
	            </malvorerkrankung_1>
	            {if strlen( $melanom.art_erkrankung2 ) > 0}
	            <malvorerkrankung_2>
	               <art_erkrankung>{$melanom.art_erkrankung2}</art_erkrankung>
	               <dia_datum>{$melanom.dia_datum2}</dia_datum>
	               <therapie>
	                  {if strlen( $melanom.operation2 ) > 0}
	                  <operation>{$melanom.operation2}</operation>
	                  {/if}
	                  {if strlen( $melanom.hormontherapie2 ) > 0}
	                  <hormontherapie>{$melanom.hormontherapie2}</hormontherapie>
	                  {/if}
	                  {if strlen( $melanom.strahlentherapie2 ) > 0}
	                  <strahlentherapie>{$melanom.strahlentherapie2}</strahlentherapie>
	                  {/if}
	                  {if strlen( $melanom.immuntherapie2 ) > 0}
	                  <immuntherapie>{$melanom.immuntherapie2}</immuntherapie>
	                  {/if}
	                  {if strlen( $melanom.chemotherapie2 ) > 0}
	                  <chemotherapie>{$melanom.chemotherapie2}</chemotherapie>
	                  {/if}
	                  {if strlen( $melanom.sonstige2 ) > 0}
	                  <sonstige>{$melanom.sonstige2}</sonstige>
	                  {/if}
	               </therapie>
	            </malvorerkrankung_2>
	            {/if}
	            {if strlen( $melanom.art_erkrankung3 ) > 0}
	            <malvorerkrankung_3>
	               <art_erkrankung>{$melanom.art_erkrankung3}</art_erkrankung>
	               <dia_datum>{$melanom.dia_datum3}</dia_datum>
	               <therapie>
	                  {if strlen( $melanom.operation3 ) > 0}
	                  <operation>{$melanom.operation3}</operation>
	                  {/if}
	                  {if strlen( $melanom.hormontherapie3 ) > 0}
	                  <hormontherapie>{$melanom.hormontherapie3}</hormontherapie>
	                  {/if}
	                  {if strlen( $melanom.strahlentherapie3 ) > 0}
	                  <strahlentherapie>{$melanom.strahlentherapie3}</strahlentherapie>
	                  {/if}
	                  {if strlen( $melanom.immuntherapie3 ) > 0}
	                  <immuntherapie>{$melanom.immuntherapie3}</immuntherapie>
	                  {/if}
	                  {if strlen( $melanom.chemotherapie3 ) > 0}
	                  <chemotherapie>{$melanom.chemotherapie3}</chemotherapie>
	                  {/if}
	                  {if strlen( $melanom.sonstige3 ) > 0}
	                  <sonstige>{$melanom.sonstige3}</sonstige>
	                  {/if}
	               </therapie>
	            </malvorerkrankung_3>
	            {/if}
	          </folgende_Angaben>
	          {/if}
         </maligne_vorerkrankungen>
         <erst_op>
            {if strlen( $melanom.op_nein1 ) > 0}
            <op_nein>{$melanom.op_nein1}</op_nein>
            {/if}
            {if $melanom.op_nein1 !== 'on'}
            <op_ja>
               <op_datum>{$melanom.op_datum1}</op_datum>
               <operateur>{$melanom.operateur1}</operateur>
               <op_ergebnis>
                  {if strlen( $melanom.nicht_beurteilbar ) > 0}
                  <nicht_beurteilbar>{$melanom.nicht_beurteilbar}</nicht_beurteilbar>
                  {/if}
               </op_ergebnis>
               <sicherheitsabstand_cm>{$melanom.sicherheitsabstand_cm}</sicherheitsabstand_cm>
            </op_ja>
            {/if}
         </erst_op>
         <zweit_op>
            {if strlen( $melanom.op_nein2 ) > 0}
            <op_nein>{$melanom.op_nein2}</op_nein>
            {/if}
            {if $melanom.op_nein2 !== 'on'}
            <op_ja>
               <op_datum>{$melanom.op_datum2}</op_datum>
               <operateur>{$melanom.operateur2}</operateur>
               <op_grund>
                  {if strlen( $melanom.resttumor ) > 0}
                  <resttumor>{$melanom.resttumor}</resttumor>
                  {/if}
               </op_grund>
               <sicherheitsabstand_cm>{$melanom.sicherheitsabstand_cm2}</sicherheitsabstand_cm>
            </op_ja>
            {/if}
         </zweit_op>
         <lymphknoten_op>
            {if strlen( $melanom.op_nein3 ) > 0}
            <op_nein>{$melanom.op_nein3}</op_nein>
            {/if}
            {if $melanom.op_nein3 !== 'on'}
            <op_ja>
               <op_datum>{$melanom.op_datum3}</op_datum>
               <operateur>{$melanom.operateur3}</operateur>
               <op_art>
                  {if strlen( $melanom.therapeutische_lkop ) > 0}
                  <therapeutische_lkop>{$melanom.therapeutische_lkop}</therapeutische_lkop>
                  {/if}
                  {if strlen( $melanom.sentinel_lkop ) > 0}
                  <sentinel_lkop>{$melanom.sentinel_lkop}</sentinel_lkop>
                  {/if}
               </op_art>
               <op_ergebnis>{$melanom.op_ergebnis}</op_ergebnis>
            </op_ja>
            {/if}
         </lymphknoten_op>
         {if strlen( $melanom.bemerkungen ) > 0}
         <bemerkungen>{$melanom.bemerkungen}</bemerkungen>
         {/if}
         <weiteres_vorgehen>
            {if strlen( $melanom.patient_nachsorge ) > 0}
            <patient_nachsorge>{$melanom.patient_nachsorge}</patient_nachsorge>
            {/if}
         </weiteres_vorgehen>
         <weiterbehandlung>{$melanom.weiterbehandlung}</weiterbehandlung>
         {if strlen( $melanom.hausarzt ) > 0}
         <hausarzt>{$melanom.hausarzt}</hausarzt>
         {/if}
         <anlass_erfassung>{$melanom.anlass_erfassung}</anlass_erfassung>
      </melanom>
   </dokumentation>
</onkeyline>
