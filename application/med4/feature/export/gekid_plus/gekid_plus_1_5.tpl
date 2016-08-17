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
        <Meldende_Stelle>{$data.melder.Meldende_Stelle}</Meldende_Stelle>
        {xml_tag name='KH_Abt_Station_Praxis' value=$data.melder.KH_Abt_Station_Praxis}
        <Arztname>{$data.melder.Arztname}</Arztname>
        {xml_tag name='Anschrift' value=$data.melder.Anschrift}
        {xml_tag name='Postleitzahl' value=$data.melder.Postleitzahl}
        {xml_tag name='Ort' value=$data.melder.Ort}
        <Meldedatum>{$data.melder.Meldedatum}</Meldedatum>
    </Melder>
    <Daten>
        {foreach from=$data.patienten item=patient}
        <Patient>
            <Person>
                {xml_tag name='Titel' value=$patient.person.Titel}
                <Vornamen>{$patient.person.Vornamen}</Vornamen>
                <Nachname>{$patient.person.Nachname}</Nachname>
                {xml_tag name='Geburtsname' value=$patient.person.Geburtsname}
                <Geschlecht>{$patient.person.Geschlecht}</Geschlecht>
                <Geburtsdatum>{$patient.person.Geburtsdatum}</Geburtsdatum>
                <Strasse>{$patient.person.Strasse}</Strasse>
                {xml_tag name='Hausnummer' value=$patient.person.Hausnummer}
                {xml_tag name='Postfix' value=$patient.person.Postfix}
                <Postleitzahl>{$patient.person.Postleitzahl}</Postleitzahl>
                <Ort>{$patient.person.Ort}</Ort>
                {xml_tag name='Todesdatum' value=$patient.person.Todesdatum}
                {xml_tag name='Todesursache' value=$patient.person.Todesursache}
                <Meldebegruendung>{$patient.person.Meldebegruendung}</Meldebegruendung>
            </Person>
            <Tumor>
                {xml_tag name='Referenznummer' value=$patient.tumor.Referenznummer}
                {xml_tag name='Diagnosetag' value=$patient.tumor.Diagnosetag}
                {xml_tag name='Diagnosemonat' value=$patient.tumor.Diagnosemonat}
                <Diagnosejahr>{$patient.tumor.Diagnosejahr}</Diagnosejahr>
                {xml_tag name='ICD' value=$patient.tumor.ICD}
                {xml_tag name='Diagnose_Freitext' value=$patient.tumor.Diagnose_Freitext}
                {xml_tag name='Morphologie_Code' value=$patient.tumor.Morphologie_Code}
                {xml_tag name='Morphologie_Freitext' value=$patient.tumor.Morphologie_Freitext}
                {xml_tag name='Dignitaet' value=$patient.tumor.Dignitaet}
                {xml_tag name='ICD_Auflage' value=$patient.tumor.ICD_Auflage}
                {xml_tag name='Topographie_Code' value=$patient.tumor.Topographie_Code}
                {xml_tag name='ICDO_Auflage' value=$patient.tumor.ICDO_Auflage}
                <Grading>{$patient.tumor.Grading}</Grading>
                {xml_tag name='Zelltyp' value=$patient.tumor.Zelltyp}
                <Diagnosesicherung>{$patient.tumor.Diagnosesicherung}</Diagnosesicherung>
                {xml_tag name='Diagnoseanlass' value=$patient.tumor.Diagnoseanlass}
                {xml_tag name='Seitenlokalisation' value=$patient.tumor.Seitenlokalisation}
                {xml_tag name='Grobstadium' value=$patient.tumor.Grobstadium}
                {xml_tag name='y' value=$patient.tumor.y}
                {xml_tag name='r' value=$patient.tumor.r}
                {xml_tag name='a' value=$patient.tumor.a}
                {xml_tag name='Praefix_TNM' value=$patient.tumor.Praefix_TNM}
                <T>{$patient.tumor.T}</T>
                {xml_tag name='Multi' value=$patient.tumor.Multi}
                <N>{$patient.tumor.N}</N>
                <M>{$patient.tumor.M}</M>
                {xml_tag name='R' value=$patient.tumor.R}
                {xml_tag name='UICC_Stadium' value=$patient.tumor.UICC_Stadium}
                {xml_tag name='TNM_Auflage' value=$patient.tumor.TNM_Auflage}
                {xml_tag name='Tumorgroesse' value=$patient.tumor.Tumorgroesse}
                {xml_tag name='Breslow' value=$patient.tumor.Breslow}
                {xml_tag name='Gleason_Score' value=$patient.tumor.Gleason_Score}
                {xml_tag name='Andere_Klassifikation' value=$patient.tumor.Andere_Klassifikation}
                {xml_tag name='Oestrogen_Status' value=$patient.tumor.Oestrogen_Status}
                {xml_tag name='Oestrogen_pos_TZ' value=$patient.tumor.Oestrogen_pos_TZ}
                {xml_tag name='Progesteron_Status' value=$patient.tumor.Progesteron_Status}
                {xml_tag name='Progesteron_pos_TZ' value=$patient.tumor.Progesteron_pos_TZ}
                {xml_tag name='HER2_Status' value=$patient.tumor.HER2_Status}
                {xml_tag name='Operation' value=$patient.tumor.Operation}
                {xml_tag name='Strahlentherapie' value=$patient.tumor.Strahlentherapie}
                {xml_tag name='Chemotherapie' value=$patient.tumor.Chemotherapie}
                {xml_tag name='Hormontherapie' value=$patient.tumor.Hormontherapie}
                {xml_tag name='Immuntherapie' value=$patient.tumor.Immuntherapie}
                {xml_tag name='Knochenmarktransplantation' value=$patient.tumor.Knochenmarktransplantation}
                {xml_tag name='Sonstige_Therapie' value=$patient.tumor.Sonstige_Therapie}
                {xml_tag name='Bemerkungen' value=$patient.tumor.Bemerkungen}
            </Tumor>
            {if ((strlen($patient.pathologe.KH_Abt_Station_Praxis) > 0) ||
                 (strlen($patient.pathologe.Name_Pathologe) > 0) ||
                 (strlen($patient.pathologe.Anschrift) > 0) ||
                 (strlen($patient.pathologe.Postleitzahl) > 0) ||
                 (strlen($patient.pathologe.Ort) > 0))}
            <Pathologe>
                {xml_tag name='KH_Abt_Station_Praxis' value=$patient.pathologe.KH_Abt_Station_Praxis}
                {xml_tag name='Name_Pathologe' value=$patient.pathologe.Name_Pathologe}
                {xml_tag name='Anschrift' value=$patient.pathologe.Anschrift}
                {xml_tag name='Postleitzahl' value=$patient.pathologe.Postleitzahl}
                {xml_tag name='Ort' value=$patient.pathologe.Ort}
            </Pathologe>
            {/if}
        </Patient>
        {/foreach}
    </Daten>
</GEKID>
