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
      <lieferant_id>{$surgical.lieferung.lieferant_id}</lieferant_id>
      <lieferung_id>{$surgical.lieferung.lieferung_id}</lieferung_id>
      <liefer_id>{$surgical.lieferung.liefer_id}</liefer_id>
      <liefer_datum>{$surgical.lieferung.liefer_datum}</liefer_datum>
   </lieferung>
   <patient>
      <patient_id>{$surgical.patient_id}</patient_id>
   </patient>
   <dokumentation>
      <chirurgisch id="{$surgical.lieferung.liefer_id}" refid="{$surgical.lieferung.ref_id}">
         {if strlen( $surgical.diagnose_icd ) > 0}
         <diagnose_icd>{$surgical.diagnose_icd}</diagnose_icd>
         {/if}
         {if strlen( $surgical.diagnose_txt ) > 0}
         <diagnose_txt>{$surgical.diagnose_txt}</diagnose_txt>
         {/if}
         {if strlen( $surgical.diagnose_datum ) > 0}
         <diagnose_datum>{$surgical.diagnose_datum}</diagnose_datum>
         {/if}
         <operateur>{$surgical.operateur}</operateur>
         <operation_datum>{$surgical.operation_datum}</operation_datum>
         <ops_code>
            <ops_1>
               <ops_code>{$surgical.ops_code1}</ops_code>
               <ops_txt>{$surgical.ops_txt1}</ops_txt>
            </ops_1>
            {if strlen( $surgical.ops_code2 ) > 0}
            <ops_2>
               <ops_code>{$surgical.ops_code2}</ops_code>
               <ops_txt>{$surgical.ops_txt2}</ops_txt>
            </ops_2>
            {/if}
            {if strlen( $surgical.ops_code3 ) > 0}
            <ops_3>
               <ops_code>{$surgical.ops_code3}</ops_code>
               <ops_txt>{$surgical.ops_txt3}</ops_txt>
            </ops_3>
            {/if}
            {if strlen( $surgical.ops_code4 ) > 0}
            <ops_4>
               <ops_code>{$surgical.ops_code4}</ops_code>
               <ops_txt>{$surgical.ops_txt4}</ops_txt>
            </ops_4>
            {/if}
            {if strlen( $surgical.ops_code5 ) > 0}
            <ops_5>
               <ops_code>{$surgical.ops_code5}</ops_code>
               <ops_txt>{$surgical.ops_txt5}</ops_txt>
            </ops_5>
            {/if}
            {if strlen( $surgical.ops_code6 ) > 0}
            <ops_6>
               <ops_code>{$surgical.ops_code6}</ops_code>
               <ops_txt>{$surgical.ops_txt6}</ops_txt>
            </ops_6>
            {/if}
            {if strlen( $surgical.ops_code7 ) > 0}
            <ops_7>
               <ops_code>{$surgical.ops_code7}</ops_code>
               <ops_txt>{$surgical.ops_txt7}</ops_txt>
            </ops_7>
            {/if}
            {if strlen( $surgical.ops_code8 ) > 0}
            <ops_8>
               <ops_code>{$surgical.ops_code8}</ops_code>
               <ops_txt>{$surgical.ops_txt8}</ops_txt>
            </ops_8>
            {/if}
            {if strlen( $surgical.ops_code9 ) > 0}
            <ops_9>
               <ops_code>{$surgical.ops_code9}</ops_code>
               <ops_txt>{$surgical.ops_txt9}</ops_txt>
            </ops_9>
            {/if}
            {if strlen( $surgical.ops_code10 ) > 0}
            <ops_10>
               <ops_code>{$surgical.ops_code10}</ops_code>
               <ops_txt>{$surgical.ops_txt10}</ops_txt>
            </ops_10>
            {/if}
         </ops_code>
         <op_ergebnis>{$surgical.op_ergebnis}</op_ergebnis>
         <therapieindikation>
            {if strlen( $surgical.organ_primaersitz ) > 0}
            <organ_primaersitz>{$surgical.organ_primaersitz}</organ_primaersitz>
            {/if}
            {if strlen( $surgical.regionaere_lk ) > 0}
            <regionaere_lk>{$surgical.regionaere_lk}</regionaere_lk>
            {/if}
            {if strlen( $surgical.lokalrezidiv ) > 0}
            <lokalrezidiv>{$surgical.lokalrezidiv}</lokalrezidiv>
            {/if}
            {if strlen( $surgical.fernmetastasen ) > 0}
            <fernmetastasen>{$surgical.fernmetastasen}</fernmetastasen>
            {/if}
            {if strlen( $surgical.systemerkrankung ) > 0}
            <systemerkrankung>{$surgical.systemerkrankung}</systemerkrankung>
            {/if}
            {if strlen( $surgical.rezidiv_systemerkrankung ) > 0}
            <rezidiv_systemerkrankung>{$surgical.rezidiv_systemerkrankung}</rezidiv_systemerkrankung>
            {/if}
         </therapieindikation>
         {if strlen( $surgical.therapieziel ) > 0}
         <therapieziel>{$surgical.therapieziel}</therapieziel>
         {/if}
         <neue_manifestationen>
            {if strlen( $surgical.nein ) > 0}
            <nein>{$surgical.nein}</nein>
            {/if}
         </neue_manifestationen>
         <tumorspezifisches_vorgehen>
            <patient_nachsorge>{$surgical.patient_nachsorge}</patient_nachsorge>
         </tumorspezifisches_vorgehen>
         <weiterbehandlung>{$surgical.weiterbehandlung}</weiterbehandlung>
         {if strlen( $surgical.bemerkungen_lst ) > 0}
         <bemerkungen_lst>{$surgical.bemerkungen_lst}</bemerkungen_lst>
         {/if}
      </chirurgisch>
   </dokumentation>
</onkeyline>
