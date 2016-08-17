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
      <lieferant_id>{$closure.lieferung.lieferant_id}</lieferant_id>
      <lieferung_id>{$closure.lieferung.lieferung_id}</lieferung_id>
      <liefer_id>{$closure.lieferung.liefer_id}</liefer_id>
      <liefer_datum>{$closure.lieferung.liefer_datum}</liefer_datum>
   </lieferung>
   <patient>
      <patient_id>{$closure.patient_id}</patient_id>
   </patient>
   <dokumentation>
      <abschluss id="{$closure.lieferung.liefer_id}" refid="{$closure.lieferung.ref_id}">
         <grund_abschluss>
            <verstorben>
               {if strlen( $closure.todesdatum ) > 0}
               <todesdatum>{$closure.todesdatum}</todesdatum>
               {/if}
            </verstorben>
         </grund_abschluss>
         {if strlen( $closure.letzter_kontakt ) > 0}
         <letzter_kontakt>{$closure.letzter_kontakt}</letzter_kontakt>
         {/if}
         <todesursache>
            {if strlen( $closure.nicht_tumorbedingt ) > 0}
            <nicht_tumorbedingt>{$closure.nicht_tumorbedingt}</nicht_tumorbedingt>
            {/if}
            {if strlen( $closure.tumorbedingt ) > 0}
            <tumorbedingt>{$closure.tumorbedingt}</tumorbedingt>
            {/if}
            {if strlen( $closure.therapiebedingt ) > 0}
            <therapiebedingt>{$closure.therapiebedingt}</therapiebedingt>
            {/if}
            {if strlen( $closure.nicht_entscheidbar ) > 0}
            <nicht_entscheidbar>{$closure.nicht_entscheidbar}</nicht_entscheidbar>
            {/if}
         </todesursache>
      </abschluss>
   </dokumentation>
</onkeyline>
