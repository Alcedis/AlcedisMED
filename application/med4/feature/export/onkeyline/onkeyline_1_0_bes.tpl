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
      <lieferant_id>{$radioTherapy.lieferung.lieferant_id}</lieferant_id>
      <lieferung_id>{$radioTherapy.lieferung.lieferung_id}</lieferung_id>
      <liefer_id>{$radioTherapy.lieferung.liefer_id}</liefer_id>
      <liefer_datum>{$radioTherapy.lieferung.liefer_datum}</liefer_datum>
   </lieferung>
   <patient>
      <patient_id>{$radioTherapy.patient_id}</patient_id>
   </patient>
   <dokumentation>
      <radiologisch id="{$radioTherapy.lieferung.liefer_id}" refid="{$radioTherapy.lieferung.ref_id}">
         {if strlen( $radioTherapy.diagnose_icd ) > 0}
         <diagnose_icd>{$radioTherapy.diagnose_icd}</diagnose_icd>
         {/if}
         {if strlen( $radioTherapy.diagnose_txt ) > 0}
         <diagnose_txt>{$radioTherapy.diagnose_txt}</diagnose_txt>
         {/if}
         {if strlen( $radioTherapy.diagnose_datum ) > 0}
         <diagnose_datum>{$radioTherapy.diagnose_datum}</diagnose_datum>
         {/if}
         <behandelnder>{$radioTherapy.behandelnder}</behandelnder>
         <beginn_therapie>{$radioTherapy.beginn_therapie}</beginn_therapie>
         <ende_therapie>{$radioTherapy.ende_therapie}</ende_therapie>
         {if strlen( $radioTherapy.strahlenart ) > 0}
         <strahlenart>{$radioTherapy.strahlenart}</strahlenart>
         {/if}
         <strahlendosis>
            {if strlen( $radioTherapy.gesamtdosis_gy ) > 0}
            <gesamtdosis_gy>{$radioTherapy.gesamtdosis_gy}</gesamtdosis_gy>
            {/if}
         </strahlendosis>
         <bestrahlungsregion>{$radioTherapy.bestrahlungsregion}</bestrahlungsregion>
         <therapieergebnis>{$radioTherapy.therapieergebnis}</therapieergebnis>
         <nebenwirkungen>
            {if strlen( $radioTherapy.nein ) > 0}
            <nein>{$radioTherapy.nein}</nein>
            {/if}
            {if $radioTherapy.nein !== 'on'}
            <ja>
               {if strlen( $radioTherapy.haematologisch ) > 0}
               <haematologisch>{$radioTherapy.haematologisch}</haematologisch>
               {/if}
               {if strlen( $radioTherapy.renal ) > 0}
               <renal>{$radioTherapy.renal}</renal>
               {/if}
               {if strlen( $radioTherapy.allergie ) > 0}
               <allergie>{$radioTherapy.allergie}</allergie>
               {/if}
               {if strlen( $radioTherapy.haarverlust ) > 0}
               <haarverlust>{$radioTherapy.haarverlust}</haarverlust>
               {/if}
               {if strlen( $radioTherapy.haut ) > 0}
               <haut>{$radioTherapy.haut}</haut>
               {/if}
               {if strlen( $radioTherapy.gehoer ) > 0}
               <gehoer>{$radioTherapy.gehoer}</gehoer>
               {/if}
               {if strlen( $radioTherapy.fieber ) > 0}
               <fieber>{$radioTherapy.fieber}</fieber>
               {/if}
               {if strlen( $radioTherapy.gastrointestinal ) > 0}
               <gastrointestinal>{$radioTherapy.gastrointestinal}</gastrointestinal>
               {/if}
               {if strlen( $radioTherapy.pulmonal ) > 0}
               <pulmonal>{$radioTherapy.pulmonal}</pulmonal>
               {/if}
               {if strlen( $radioTherapy.kardial ) > 0}
               <kardial>{$radioTherapy.kardial}</kardial>
               {/if}
               {if strlen( $radioTherapy.infektion ) > 0}
               <infektion>{$radioTherapy.infektion}</infektion>
               {/if}
               {if strlen( $radioTherapy.nerven ) > 0}
               <nerven>{$radioTherapy.nerven}</nerven>
               {/if}
               {if strlen( $radioTherapy.schmerzen ) > 0}
               <schmerzen>{$radioTherapy.schmerzen}</schmerzen>
               {/if}
            </ja>
            {/if}
         </nebenwirkungen>
         <therapieindikation>
            {if strlen( $radioTherapy.organ_primaersitz ) > 0}
            <organ_primaersitz>{$radioTherapy.organ_primaersitz}</organ_primaersitz>
            {/if}
            {if strlen( $radioTherapy.lokalrezidiv ) > 0}
            <lokalrezidiv>{$radioTherapy.lokalrezidiv}</lokalrezidiv>
            {/if}
            {if strlen( $radioTherapy.fernmetastasen ) > 0}
            <fernmetastasen>{$radioTherapy.fernmetastasen}</fernmetastasen>
            {/if}
            {if strlen( $radioTherapy.systemerkrankung ) > 0}
            <systemerkrankung>{$radioTherapy.systemerkrankung}</systemerkrankung>
            {/if}
            {if strlen( $radioTherapy.rezidiv_systemerkrankung ) > 0}
            <rezidiv_systemerkrankung>{$radioTherapy.rezidiv_systemerkrankung}</rezidiv_systemerkrankung>
            {/if}
         </therapieindikation>
         <therapieziel>{$radioTherapy.therapieziel}</therapieziel>
         <neue_manifestationen>
            {if strlen( $radioTherapy.mani_nein ) > 0}
            <nein>{$radioTherapy.mani_nein}</nein>
            {/if}
         </neue_manifestationen>
         <tumorspezifisches_vorgehen>
            <patient_nachsorge>{$radioTherapy.patient_nachsorge}</patient_nachsorge>
         </tumorspezifisches_vorgehen>
         <weiterbehandlung>{$radioTherapy.weiterbehandlung}</weiterbehandlung>
      </radiologisch>
   </dokumentation>
</onkeyline>
