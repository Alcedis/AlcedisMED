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
      <lieferant_id>{$internistical.lieferung.lieferant_id}</lieferant_id>
      <lieferung_id>{$internistical.lieferung.lieferung_id}</lieferung_id>
      <liefer_id>{$internistical.lieferung.liefer_id}</liefer_id>
      <liefer_datum>{$internistical.lieferung.liefer_datum}</liefer_datum>
   </lieferung>
   <patient>
      <patient_id>{$internistical.patient_id}</patient_id>
   </patient>
   <dokumentation>
      <internistisch id="{$internistical.lieferung.liefer_id}" refid="{$internistical.lieferung.ref_id}">
         {if strlen( $internistical.diagnose_icd ) > 0}
         <diagnose_icd>{$internistical.diagnose_icd}</diagnose_icd>
         {/if}
         {if strlen( $internistical.diagnose_txt ) > 0}
         <diagnose_txt>{$internistical.diagnose_txt}</diagnose_txt>
         {/if}
         {if strlen( $internistical.diagnose_datum ) > 0}
         <diagnose_datum>{$internistical.diagnose_datum}</diagnose_datum>
         {/if}
         <behandelnder>{$internistical.behandelnder}</behandelnder>
         <beginn_therapie>{$internistical.beginn_therapie}</beginn_therapie>
         {if strlen( $internistical.ende_therapie ) > 0}
         <ende_therapie>{$internistical.ende_therapie}</ende_therapie>
         {/if}
         <therapieschema_medikamente>{$internistical.therapieschema_medikamente}</therapieschema_medikamente>
         {if strlen( $internistical.anzahl_kurse ) > 0}
         <anzahl_kurse>{$internistical.anzahl_kurse}</anzahl_kurse>
         {/if}
         {if strlen( $internistical.angaben_zur_therapie ) > 0}
         <angaben_zur_therapie>{$internistical.angaben_zur_therapie}</angaben_zur_therapie>
         {/if}
         <therapieergebnis>{$internistical.therapieergebnis}</therapieergebnis>
         <nebenwirkungen>
            {if strlen( $internistical.nein ) > 0}
            <nein>{$internistical.nein}</nein>
            {/if}
            {if strlen( $internistical.nein ) === 0}
            <ja>
               {if strlen( $internistical.haematologisch ) > 0}
               <haematologisch>{$internistical.haematologisch}</haematologisch>
               {/if}
               {if strlen( $internistical.renal ) > 0}
               <renal>{$internistical.renal}</renal>
               {/if}
               {if strlen( $internistical.allergie ) > 0}
               <allergie>{$internistical.allergie}</allergie>
               {/if}
               {if strlen( $internistical.haarverlust ) > 0}
               <haarverlust>{$internistical.haarverlust}</haarverlust>
               {/if}
               {if strlen( $internistical.haut ) > 0}
               <haut>{$internistical.haut}</haut>
               {/if}
               {if strlen( $internistical.gehoer ) > 0}
               <gehoer>{$internistical.gehoer}</gehoer>
               {/if}
               {if strlen( $internistical.fieber ) > 0}
               <fieber>{$internistical.fieber}</fieber>
               {/if}
               {if strlen( $internistical.gastrointestinal ) > 0}
               <gastrointestinal>{$internistical.gastrointestinal}</gastrointestinal>
               {/if}
               {if strlen( $internistical.pulmonal ) > 0}
               <pulmonal>{$internistical.pulmonal}</pulmonal>
               {/if}
               {if strlen( $internistical.kardial ) > 0}
               <kardial>{$internistical.kardial}</kardial>
               {/if}
               {if strlen( $internistical.infektion ) > 0}
               <infektion>{$internistical.infektion}</infektion>
               {/if}
               {if strlen( $internistical.nerven ) > 0}
               <nerven>{$internistical.nerven}</nerven>
               {/if}
               {if strlen( $internistical.schmerzen ) > 0}
               <schmerzen>{$internistical.schmerzen}</schmerzen>
               {/if}
            </ja>
            {/if}
         </nebenwirkungen>
         <therapieindikation>
            {if strlen( $internistical.organ_primaersitz ) > 0}
            <organ_primaersitz>{$internistical.organ_primaersitz}</organ_primaersitz>
            {/if}
            {if strlen( $internistical.lokalrezidiv ) > 0}
            <lokalrezidiv>{$internistical.lokalrezidiv}</lokalrezidiv>
            {/if}
            {if strlen( $internistical.fernmetastasen ) > 0}
            <fernmetastasen>{$internistical.fernmetastasen}</fernmetastasen>
            {/if}
            {if strlen( $internistical.systemerkrankung ) > 0}
            <systemerkrankung>{$internistical.systemerkrankung}</systemerkrankung>
            {/if}
            {if strlen( $internistical.rezidiv_systemerkrankung ) > 0}
            <rezidiv_systemerkrankung>{$internistical.rezidiv_systemerkrankung}</rezidiv_systemerkrankung>
            {/if}
         </therapieindikation>
         <therapieziel>{$internistical.therapieziel}</therapieziel>
         <therapieart>
            {if strlen( $internistical.chemotherapie ) > 0}
            <chemotherapie>{$internistical.chemotherapie}</chemotherapie>
            {/if}
            {if strlen( $internistical.stammzelltransplantation ) > 0 && strlen( $internistical.chemotherapie ) === 0}
            <stammzelltransplantation>{$internistical.stammzelltransplantation}</stammzelltransplantation>
            {/if}
            {if strlen( $internistical.hormontherapie ) > 0 && strlen( $internistical.stammzelltransplantation ) === 0 && strlen( $internistical.chemotherapie ) === 0}
            <hormontherapie>{$internistical.hormontherapie}</hormontherapie>
            {/if}
            {if strlen( $internistical.immuntherapie ) > 0 && strlen( $internistical.hormontherapie ) === 0 && strlen( $internistical.stammzelltransplantation ) === 0 && strlen( $internistical.chemotherapie ) === 0}
            <immuntherapie>{$internistical.immuntherapie}</immuntherapie>
            {/if}
            {if strlen( $internistical.chemotherapie ) === 0 &&
            strlen( $internistical.stammzelltransplantation ) === 0 &&
            strlen( $internistical.hormontherapie ) === 0 &&
            strlen( $internistical.immuntherapie ) === 0 }
            <andere_tumortherapie>
               <andere_text>{$internistical.andere_text}</andere_text>
            </andere_tumortherapie>
            {/if}
         </therapieart>
         <neue_manifestationen>
            {if strlen( $internistical.neue_manifestationen ) > 0}
            <nein>{$internistical.neue_manifestationen}</nein>
            {/if}
         </neue_manifestationen>
         <tumorspezifisches_vorgehen>
            {if strlen( $internistical.patient_nachsorge ) > 0}
            <patient_nachsorge>{$internistical.patient_nachsorge}</patient_nachsorge>
            {/if}
         </tumorspezifisches_vorgehen>
         <weiterbehandlung>{$internistical.weiterbehandlung}</weiterbehandlung>
      </internistisch>
   </dokumentation>
</onkeyline>
