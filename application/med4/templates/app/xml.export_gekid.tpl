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

<?xml version="1.0" encoding="utf-8"?>
<GEKID xmlns="http://www.gekid.de/namespace" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <Melder>
      {xml_tag name='Meldende_Stelle'        value=$data.Melder.Meldende_Stelle}
      {xml_tag name='KH_Abt_Station_Praxis'  value=$data.Melder.KH_Abt_Station_Praxis}
      {xml_tag name='Arztname'               value=$data.Melder.Arztname}
      {xml_tag name='Anschrift'              value=$data.Melder.Anschrift}
      {xml_tag name='Postleitzahl'           value=$data.Melder.Postleitzahl}
      {xml_tag name='Ort'                    value=$data.Melder.Ort}
      {xml_tag name='Meldedatum'             value=$data.Melder.Meldedatum}
   </Melder>
   <Daten>
      {foreach from=$data.Daten item=cur_pat}
         <Patient>
            <Person>
               {xml_tag name='Titel'            value=$cur_pat.Titel}
               {xml_tag name='Vornamen'         value=$cur_pat.Vornamen}
               {xml_tag name='Nachname'         value=$cur_pat.Nachname}
               {xml_tag name='Namenszusatz'     value=$cur_pat.Namenszusatz}
               {xml_tag name='Fruehere_Namen'   value=$cur_pat.Fruehere_Namen}
               {xml_tag name='Geburtsname'      value=$cur_pat.Geburtsname}
               {xml_tag name='Geschlecht'       value=$cur_pat.Geschlecht}
               {xml_tag name='Geburtsdatum'     value=$cur_pat.Geburtsdatum}
               {xml_tag name='Strasse'          value=$cur_pat.Strasse}
               {xml_tag name='Hausnummer'       value=$cur_pat.Hausnummer}
               {xml_tag name='Postfix'          value=$cur_pat.Postfix}
               {xml_tag name='Postleitzahl'     value=$cur_pat.Postleitzahl}
               {xml_tag name='Ort'              value=$cur_pat.Ort}
               {xml_tag name='Todesdatum'       value=$cur_pat.Todesdatum}
               {xml_tag name='Meldebegruendung' value=$cur_pat.Meldebegruendung}
            </Person>
            <Tumor>
               {xml_tag name='Referenznummer'               value=$cur_pat.Referenznummer}
               {xml_tag name='Diagnosetag'                  value=$cur_pat.Diagnosetag}
               {xml_tag name='Diagnosemonat'                value=$cur_pat.Diagnosemonat}
               {xml_tag name='Diagnosejahr'                 value=$cur_pat.Diagnosejahr}
               {xml_tag name='ICD'                          value=$cur_pat.ICD}
               {xml_tag name='Diagnose_Freitext'            value=$cur_pat.Diagnose_Freitext}
               {xml_tag name='Morphologie_Code'             value=$cur_pat.Morphologie_Code}
               {xml_tag name='Morphologie_Freitext'         value=$cur_pat.Morphologie_Freitext}
               {xml_tag name='Dignitaet'                    value=$cur_pat.Dignitaet}
               {xml_tag name='ICD_Auflage'                  value=$cur_pat.ICD_Auflage}
               {xml_tag name='Topographie_Code'             value=$cur_pat.Topographie_Code}
               {xml_tag name='ICDO_Auflage'                 value=$cur_pat.ICDO_Auflage}
               {xml_tag name='Grading'                      value=$cur_pat.Grading}
               {xml_tag name='Zelltyp'                      value=$cur_pat.Zelltyp}
               {xml_tag name='Diagnosesicherung'            value=$cur_pat.Diagnosesicherung}
               {xml_tag name='Diagnoseanlass'               value=$cur_pat.Diagnoseanlass}
               {xml_tag name='Seitenlokalisation'           value=$cur_pat.Seitenlokalisation}
               {xml_tag name='Grobstadium'                  value=$cur_pat.Grobstadium}
               {xml_tag name='Praefix_TNM'                  value=$cur_pat.Praefix_TNM}
               {xml_tag name='T'                            value=$cur_pat.T}
               {xml_tag name='N'                            value=$cur_pat.N}
               {xml_tag name='M'                            value=$cur_pat.M}
               {xml_tag name='TNM_Auflage'                  value=$cur_pat.TNM_Auflage}
               {xml_tag name='Operation'                    value=$cur_pat.Operation}
               {xml_tag name='Strahlentherapie'             value=$cur_pat.Strahlentherapie}
               {xml_tag name='Chemotherapie'                value=$cur_pat.Chemotherapie}
               {xml_tag name='Hormontherapie'               value=$cur_pat.Hormontherapie}
               {xml_tag name='Immuntherapie'                value=$cur_pat.Immuntherapie}
               {xml_tag name='Knochenmarktransplantation'   value=$cur_pat.Knochenmarktransplantation}
               {xml_tag name='Sonstige_Therapie'            value=$cur_pat.Sonstige_Therapie}
            </Tumor>
         </Patient>
      {/foreach}
   </Daten>
</GEKID>