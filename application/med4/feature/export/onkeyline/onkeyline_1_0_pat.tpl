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
      <lieferant_id>{$patient.lieferung.lieferant_id}</lieferant_id>
      <lieferung_id>{$patient.lieferung.lieferung_id}</lieferung_id>
      <liefer_id>{$patient.lieferung.liefer_id}</liefer_id>
      <liefer_datum>{$patient.lieferung.liefer_datum}</liefer_datum>
   </lieferung>
   <patient>
      <patient_id>{$patient.patient_id}</patient_id>
   </patient>
   <dokumentation>
      <patient id="{$patient.lieferung.liefer_id}">
         {if strlen( $patient.titel ) > 0}
         <titel>{$patient.titel}</titel>
         {/if}
         {if strlen( $patient.geburtsname ) > 0}
         <geburtsname>{$patient.geburtsname}</geburtsname>
         {/if}
         <geschlecht>{$patient.geschlecht}</geschlecht>
         <strasse>{$patient.strasse}</strasse>
         <hnr>{$patient.hnr}</hnr>
         <ort>{$patient.ort}</ort>
         <plz>{$patient.plz}</plz>
         {if strlen( $patient.ausland ) > 0}
         <ausland>{$patient.ausland}</ausland>
         {/if}
         <kassenschluessel>{$patient.kassenschluessel}</kassenschluessel>
         <kassenname>{$patient.kassenname}</kassenname>
         <versichertengruppe>{$patient.versichertengruppe}</versichertengruppe>
         {if strlen( $patient.nachsorgepassnummer ) > 0}
         <nachsorgepassnummer>{$patient.nachsorgepassnummer}</nachsorgepassnummer>
         {/if}
         {if strlen( $patient.bemerkungen ) > 0}
         <bemerkungen>{$patient.bemerkungen}</bemerkungen>
         {/if}
      </patient>
   </dokumentation>
</onkeyline>