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
<ADT_GEKID xmlns="http://www.gekid.de/namespace">
    <Absender Absender_ID="Alcedis" Software_ID="Alcedis" Installations_ID="MED">
        {xmlTag name='Absender_Bezeichnung' value=$data.absender}
        {xmlTag name='Absender_Ansprechpartner' value=$data.absender}
        {xmlTag name='Absender_Anschrift' value=$data.absender}
        {xmlTag name='Absender_Telefon' value=$data.absender}
        {xmlTag name='Absender_EMail' value=$data.absender}
    </Absender>
    <Menge_Patient>
        {foreach from=$data.patients item=patient}
        <Patient>
            <Patienten_Stammdaten Patient_ID="{$patient.patient.patient_id}">
                {xmlTag name='KrankenversichertenNr' value=$patient.patient}
                {xmlTag name='FamilienangehoerigenNr' value=$patient.patient}
                {xmlTag name='KrankenkassenNr' value=$patient.patient}
                {xmlTag name='Patienten_Nachname' value=$patient.patient.nachname}
                {xmlTag name='Patienten_Titel' value=$patient.patient.titel}
                {xmlTag name='Patienten_Namenszusatz' value=$patient.patient.namenszusatz}
                {xmlTag name='Patienten_Vornamen' value=$patient.patient.vorname}
                {xmlTag name='Patienten_Geburtsname' value=$patient.patient.geburtsname}
                {xmlTag name='Patienten_Geschlecht' value=$patient.patient.geschlecht}
                {xmlTag name='Patienten_Geburtsdatum' value=$patient.patient.geburtsdatum}
                <Menge_Adresse>
                    <Adresse>
                        {xmlTag name='Patienten_Strasse' value=$patient.patient.strasse}
                        {xmlTag name='Patienten_Hausnummer' value=$patient.patient.hausnummer}
                        {xmlTag name='Patienten_Land' value=$patient.patient.land}
                        {xmlTag name='Patienten_PLZ' value=$patient.patient.plz}
                        {xmlTag name='Patienten_Ort' value=$patient.patient.ort}
                        {xmlTag name='Gueltig_von' value=$patient.patient}
                        {xmlTag name='Gueltig_bis' value=$patient.patient}
                    </Adresse>
                </Menge_Adresse>
            </Patienten_Stammdaten>
            <Menge_Meldung>
                {foreach from=$patient.messages item=message}
                <Meldung Meldung_ID="{$message.message.id}" Melder_ID="{$message.message.meldender_arzt}">
                    {xmlTag name='Meldedatum' value=$message.message}
                    {xmlTag name='Meldebegruendung' value=$message.message}
                    {xmlTag name='Meldeanlass' value=$message.message}

                    {if array_key_exists('tumorzuordnung', $message) === true}
                    <Tumorzuordnung Tumor_ID="{$message.tumorzuordnung.id}">
                        {xmlTag name='Primaertumor_ICD_Code' value=$message.tumorzuordnung}
                        {xmlTag name='Diagnosedatum' value=$message.tumorzuordnung}
                        {xmlTag name='Seitenlokalisation' value=$message.tumorzuordnung}
                    </Tumorzuordnung>
                    {/if}

                    {if array_key_exists('diagnose', $message) === true}
                    <Diagnose Tumor_ID="{$message.diagnose.id}">
                        {xmlTag name='Primaertumor_ICD_Code' value=$message.diagnose}
                        {xmlTag name='Primaertumor_ICD_Version' value=$message.diagnose}
                        {xmlTag name='Primaertumor_Diagnosetext' value=$message.diagnose}
                        {xmlTag name='Primaertumor_Topographie_ICD_O' value=$message.diagnose}
                        {xmlTag name='Primaertumor_Topographie_ICD_O_Version' value=$message.diagnose}
                        {xmlTag name='Primaertumor_Topographie_ICD_O_Freitext' value=$message.diagnose}
                        {xmlTag name='Diagnosedatum' value=$message.diagnose}
                        {xmlTag name='Diagnosesicherung' value=$message.diagnose}
                        {xmlTag name='Seitenlokalisation' value=$message.diagnose}
                        {xmlTag name='Fruehere_Tumorerkrankungen' value=$message.diagnose}

                        {if count($message.diagnose.menge_histologie) > 0}
                        <Menge_Histologie>
                            {foreach from=$message.diagnose.menge_histologie item=histology}
                            <Histologie Histologie_ID="{$histology.id}">
                                {xmlTag name='Tumor_Histologiedatum' value=$histology}
                                {xmlTag name='Histologie_EinsendeNr' value=$histology}
                                {xmlTag name='Morphologie_Code' value=$histology}
                                {xmlTag name='Morphologie_ICD_O_Version' value=$histology}
                                {xmlTag name='Morphologie_Freitext' value=$histology}
                                {xmlTag name='Grading' value=$histology}
                                {xmlTag name='LK_untersucht' value=$histology}
                                {xmlTag name='LK_befallen' value=$histology}
                                {xmlTag name='Sentinel_LK_untersucht' value=$histology}
                                {xmlTag name='Sentinel_LK_befallen' value=$histology}
                            </Histologie>
                            {/foreach}
                        </Menge_Histologie>
                        {/if}

                        {if count($message.diagnose.menge_fm) > 0}
                        <Menge_FM>
                            {foreach from=$message.diagnose.menge_fm item=metastasis}
                            <Fernmetastase>
                                {xmlTag name='FM_Diagnosedatum' value=$metastasis}
                                {xmlTag name='FM_Lokalisation' value=$metastasis}
                            </Fernmetastase>
                            {/foreach}
                        </Menge_FM>
                        {/if}

                        {if count($message.diagnose.menge_tnm) > 0}
                        <Menge_TNM>
                            {foreach from=$message.diagnose.menge_tnm item=tnm}
                            <TNM TNM_ID="{$tnm.id}">
                                {xmlTag name='TNM_Datum' value=$tnm}
                                {xmlTag name='TNM_Version' value=$tnm}
                                {xmlTag name='TNM_y_Symbol' value=$tnm}
                                {xmlTag name='TNM_r_Symbol' value=$tnm}
                                {xmlTag name='TNM_a_Symbol' value=$tnm}
                                {xmlTag name='TNM_c_p_u_Praefix_T' value=$tnm}
                                {xmlTag name='TNM_T' value=$tnm}
                                {xmlTag name='TNM_m_Symbol' value=$tnm}
                                {xmlTag name='TNM_c_p_u_Praefix_N' value=$tnm}
                                {xmlTag name='TNM_N' value=$tnm}
                                {xmlTag name='TNM_c_p_u_Praefix_M' value=$tnm}
                                {xmlTag name='TNM_M' value=$tnm}
                                {xmlTag name='TNM_L' value=$tnm}
                                {xmlTag name='TNM_V' value=$tnm}
                                {xmlTag name='TNM_Pn' value=$tnm}
                                {xmlTag name='TNM_S' value=$tnm}
                            </TNM>
                            {/foreach}
                        </Menge_TNM>
                        {/if}

                        {if count($message.diagnose.menge_weitere_klassifikation) > 0}
                        <Menge_Weitere_Klassifikation>
                            {foreach from=$message.diagnose.menge_weitere_klassifikation item=ac}
                            <Weitere_Klassifikation>
                                {xmlTag name='Datum' value=$ac}
                                {xmlTag name='Name' value=$ac}
                                {xmlTag name='Stadium' value=$ac}
                            </Weitere_Klassifikation>
                            {/foreach}
                        </Menge_Weitere_Klassifikation>
                        {/if}

                        {xmlTag name='Allgemeiner_Leistungszustand' value=$message.diagnose}
                        {xmlTag name='Anmerkung' value=$message.diagnose}
                    </Diagnose>

                    {/if}

                    {if array_key_exists('menge_op', $message) === true}
                    <Menge_OP>
                        {foreach from=$message.menge_op item=intervention}
                        <OP OP_ID="{$intervention.id}">
                            {xmlTag name='OP_Intention' value=$intervention}
                            {xmlTag name='OP_Datum' value=$intervention}

                            {if count($intervention.menge_ops) > 0}
                            <Menge_OPS>
                                {foreach from=$intervention.menge_ops item=op}
                                <OP_OPS>{$op}</OP_OPS>
                                {/foreach}
                            </Menge_OPS>
                            {/if}

                            {xmlTag name='OP_OPS_Version' value=$intervention}

                            {if $intervention.histologie !== null}
                            <Histologie Histologie_ID="{$intervention.histologie.id}">
                                {xmlTag name='Tumor_Histologiedatum' value=$intervention.histologie}
                                {xmlTag name='Histologie_EinsendeNr' value=$intervention.histologie}
                                {xmlTag name='Morphologie_Code' value=$intervention.histologie}
                                {xmlTag name='Morphologie_ICD_O_Version' value=$intervention.histologie}
                                {xmlTag name='Morphologie_Freitext' value=$intervention.histologie}
                                {xmlTag name='Grading' value=$intervention.histologie}
                                {xmlTag name='LK_untersucht' value=$intervention.histologie}
                                {xmlTag name='LK_befallen' value=$intervention.histologie}
                                {xmlTag name='Sentinel_LK_untersucht' value=$intervention.histologie}
                                {xmlTag name='Sentinel_LK_befallen' value=$intervention.histologie}
                            </Histologie>
                            {/if}

                            {if count($intervention.menge_tnm) > 0}
                            <Menge_TNM>
                                {foreach from=$intervention.menge_tnm item=tnm}
                                <TNM TNM_ID="{$tnm.id}">
                                    {xmlTag name='TNM_Datum' value=$tnm}
                                    {xmlTag name='TNM_Version' value=$tnm}
                                    {xmlTag name='TNM_y_Symbol' value=$tnm}
                                    {xmlTag name='TNM_r_Symbol' value=$tnm}
                                    {xmlTag name='TNM_a_Symbol' value=$tnm}
                                    {xmlTag name='TNM_c_p_u_Praefix_T' value=$tnm}
                                    {xmlTag name='TNM_T' value=$tnm}
                                    {xmlTag name='TNM_m_Symbol' value=$tnm}
                                    {xmlTag name='TNM_c_p_u_Praefix_N' value=$tnm}
                                    {xmlTag name='TNM_N' value=$tnm}
                                    {xmlTag name='TNM_c_p_u_Praefix_M' value=$tnm}
                                    {xmlTag name='TNM_M' value=$tnm}
                                    {xmlTag name='TNM_L' value=$tnm}
                                    {xmlTag name='TNM_V' value=$tnm}
                                    {xmlTag name='TNM_Pn' value=$tnm}
                                    {xmlTag name='TNM_S' value=$tnm}
                                </TNM>
                                {/foreach}
                            </Menge_TNM>
                            {/if}

                            {if $intervention.residualstatus !== null}
                            <Residualstatus>
                                {xmlTag name='Lokale_Beurteilung_Residualstatus' value=$intervention.residualstatus}
                                {xmlTag name='Gesamtbeurteilung_Residualstatus' value=$intervention.residualstatus}
                            </Residualstatus>
                            {/if}

                            {if count($intervention.menge_komplikation) > 0}
                            <Menge_Komplikation>
                                {foreach from=$intervention.menge_komplikation item=complication}
                                    {xmlTag name='OP_Komplikation' value=$complication}
                                {/foreach}
                            </Menge_Komplikation>
                            {/if}

                            {if count($intervention.menge_operateur) > 0}
                            <Menge_Operateur>
                                {foreach from=$intervention.menge_operateur item=person}
                                {xmlTag name='Name_Operateur' value=$person}
                                {/foreach}
                            </Menge_Operateur>
                            {/if}

                            {xmlTag name='Anmerkung' value=$intervention}
                        </OP>
                        {/foreach}
                    </Menge_OP>
                    {/if}

                    {if array_key_exists('menge_st', $message) === true}
                    <Menge_ST>
                        {foreach from=$message.menge_st item=therapy}
                        <ST ST_ID="{$therapy.id}">
                            {xmlTag name='ST_Intention' value=$therapy}
                            {xmlTag name='ST_Stellung_OP' value=$therapy}

                            {if count($therapy.menge_bestrahlung) > 0}
                            <Menge_Bestrahlung>
                                {foreach from=$therapy.menge_bestrahlung item=bestrahlung}
                                <Bestrahlung>
                                    {xmlTag name='ST_Zielgebiet' value=$bestrahlung}
                                    {xmlTag name='ST_Seite_Zielgebiet' value=$bestrahlung}
                                    {xmlTag name='ST_Beginn_Datum' value=$bestrahlung}
                                    {xmlTag name='ST_Ende_Datum' value=$bestrahlung}
                                    {xmlTag name='ST_Applikationsart' value=$bestrahlung}
                                    {xmlTag name='ST_Gesamtdosis' value=$bestrahlung}
                                    {xmlTag name='ST_Einzeldosis' value=$bestrahlung}
                                </Bestrahlung>
                                {/foreach}
                            </Menge_Bestrahlung>
                            {/if}

                            {xmlTag name='ST_Ende_Grund' value=$therapy}

                            {if $therapy.residualstatus !== null}
                            <Residualstatus>
                                {xmlTag name='Lokale_Beurteilung_Residualstatus' value=$therapy.residualstatus}
                                {xmlTag name='Gesamtbeurteilung_Residualstatus' value=$therapy.residualstatus}
                            </Residualstatus>
                            {/if}

                            {if count($therapy.menge_nebenwirkung) > 0}
                            <Menge_Nebenwirkung>
                                {foreach from=$therapy.menge_nebenwirkung item=byEffect}
                                <ST_Nebenwirkung>
                                    {xmlTag name='Nebenwirkung_Grad' value=$byEffect}
                                    {xmlTag name='Nebenwirkung_Art' value=$byEffect}
                                    {xmlTag name='Nebenwirkung_Version' value=$byEffect}
                                </ST_Nebenwirkung>
                                {/foreach}
                            </Menge_Nebenwirkung>
                            {/if}

                            {xmlTag name='Anmerkung' value=$therapy}
                        </ST>
                        {/foreach}
                    </Menge_ST>
                    {/if}

                    {if array_key_exists('menge_syst', $message) === true}
                    <Menge_SYST>
                        {foreach from=$message.menge_syst item=therapy}
                        <SYST SYST_ID="{$therapy.id}">
                            {xmlTag name='SYST_Intention' value=$therapy}
                            {xmlTag name='SYST_Stellung_OP' value=$therapy}

                            {if count($therapy.menge_therapieart) > 0}
                            <Menge_Therapieart>
                                {foreach from=$therapy.menge_therapieart item=kind}
                                {xmlTag name='SYST_Therapieart' value=$kind}
                                {/foreach}
                            </Menge_Therapieart>
                            {/if}

                            {xmlTag name='SYST_Therapieart_Anmerkung' value=$therapy}
                            {xmlTag name='SYST_Protokoll' value=$therapy}
                            {xmlTag name='SYST_Beginn_Datum' value=$therapy}

                            {if count($therapy.menge_substanz) > 0}
                            <Menge_Substanz>
                                {foreach from=$therapy.menge_substanz item=substance}
                                {xmlTag name='SYST_Substanz' value=$substance}
                                {/foreach}
                            </Menge_Substanz>
                            {/if}

                            {xmlTag name='SYST_Ende_Grund' value=$therapy}
                            {xmlTag name='SYST_Ende_Datum' value=$therapy}

                            {if $therapy.residualstatus !== null}
                            <Residualstatus>
                                {xmlTag name='Lokale_Beurteilung_Residualstatus' value=$therapy.residualstatus}
                                {xmlTag name='Gesamtbeurteilung_Residualstatus' value=$therapy.residualstatus}
                            </Residualstatus>
                            {/if}

                            {if count($therapy.menge_nebenwirkung) > 0}
                            <Menge_Nebenwirkung>
                                {foreach from=$therapy.menge_nebenwirkung item=byEffect}
                                <SYST_Nebenwirkung>
                                    {xmlTag name='Nebenwirkung_Grad' value=$byEffect}
                                    {xmlTag name='Nebenwirkung_Art' value=$byEffect}
                                    {xmlTag name='Nebenwirkung_Version' value=$byEffect}
                                </SYST_Nebenwirkung>
                                {/foreach}
                            </Menge_Nebenwirkung>
                            {/if}

                            {xmlTag name='Anmerkung' value=$therapy}
                        </SYST>
                        {/foreach}
                    </Menge_SYST>
                    {/if}

                    {if array_key_exists('menge_verlauf', $message) === true}
                    <Menge_Verlauf>
                        {assign var='progress' value=$message.menge_verlauf}

                        <Verlauf Verlauf_ID="{$progress.id}">

                            {if $progress.histologie !== null}
                            <Histologie Histologie_ID="{$progress.histologie.id}">
                                {xmlTag name='Tumor_Histologiedatum' value=$progress.histologie}
                                {xmlTag name='Histologie_EinsendeNr' value=$progress.histologie}
                                {xmlTag name='Morphologie_Code' value=$progress.histologie}
                                {xmlTag name='Morphologie_ICD_O_Version' value=$progress.histologie}
                                {xmlTag name='Morphologie_Freitext' value=$progress.histologie}
                                {xmlTag name='Grading' value=$progress.histologie}
                                {xmlTag name='LK_untersucht' value=$progress.histologie}
                                {xmlTag name='LK_befallen' value=$progress.histologie}
                                {xmlTag name='Sentinel_LK_untersucht' value=$progress.histologie}
                                {xmlTag name='Sentinel_LK_befallen' value=$progress.histologie}
                            </Histologie>
                            {/if}

                            {if count($progress.menge_tnm) > 0}
                            <Menge_TNM>
                                {foreach from=$progress.menge_tnm item=tnm}
                                <TNM TNM_ID="{$tnm.id}">
                                    {xmlTag name='TNM_Datum' value=$tnm}
                                    {xmlTag name='TNM_Version' value=$tnm}
                                    {xmlTag name='TNM_y_Symbol' value=$tnm}
                                    {xmlTag name='TNM_r_Symbol' value=$tnm}
                                    {xmlTag name='TNM_a_Symbol' value=$tnm}
                                    {xmlTag name='TNM_c_p_u_Praefix_T' value=$tnm}
                                    {xmlTag name='TNM_T' value=$tnm}
                                    {xmlTag name='TNM_m_Symbol' value=$tnm}
                                    {xmlTag name='TNM_c_p_u_Praefix_N' value=$tnm}
                                    {xmlTag name='TNM_N' value=$tnm}
                                    {xmlTag name='TNM_c_p_u_Praefix_M' value=$tnm}
                                    {xmlTag name='TNM_M' value=$tnm}
                                    {xmlTag name='TNM_L' value=$tnm}
                                    {xmlTag name='TNM_V' value=$tnm}
                                    {xmlTag name='TNM_Pn' value=$tnm}
                                    {xmlTag name='TNM_S' value=$tnm}
                                </TNM>
                                {/foreach}
                            </Menge_TNM>
                            {/if}

                            {if count($progress.menge_weitere_klassifikation) > 0}
                            <Menge_Weitere_Klassifikation>
                                {foreach from=$progress.menge_weitere_klassifikation item=ac}
                                <Weitere_Klassifikation>
                                    {xmlTag name='Datum' value=$ac}
                                    {xmlTag name='Name' value=$ac}
                                    {xmlTag name='Stadium' value=$ac}
                                </Weitere_Klassifikation>
                                {/foreach}
                            </Menge_Weitere_Klassifikation>
                            {/if}

                            {xmlTag name='Untersuchungsdatum_Verlauf' value=$progress}
                            {xmlTag name='Gesamtbeurteilung_Tumorstatus' value=$progress}
                            {xmlTag name='Verlauf_Lokaler_Tumorstatus' value=$progress}
                            {xmlTag name='Verlauf_Tumorstatus_Lymphknoten' value=$progress}
                            {xmlTag name='Verlauf_Tumorstatus_Fernmetastasen' value=$progress}

                            {if count($progress.menge_fm) > 0}
                            <Menge_FM>
                                {foreach from=$progress.menge_fm item=metastasis}
                                <Fernmetastase>
                                    {xmlTag name='FM_Diagnosedatum' value=$metastasis}
                                    {xmlTag name='FM_Lokalisation' value=$metastasis}
                                </Fernmetastase>
                                {/foreach}
                            </Menge_FM>
                            {/if}

                            {xmlTag name='Allgemeiner_Leistungszustand' value=$progress}

                            {if $progress.tod !== null}
                            <Tod>
                                {xmlTag name='Sterbedatum' value=$progress.tod}
                                {xmlTag name='Tod_tumorbedingt' value=$progress.tod}

                                {if count($progress.tod.menge_todesursache) > 0}
                                <Menge_Todesursache>
                                    {foreach from=$progress.tod.menge_todesursache item=reason}
                                    {xmlTag name='Todesursache_ICD' value=$reason}
                                    {/foreach}
                                </Menge_Todesursache>
                                {/if}
                            </Tod>
                            {/if}

                            {xmlTag name='Anmerkung' value=$progress}
                        </Verlauf>
                    </Menge_Verlauf>
                    {/if}

                    {if array_key_exists('menge_tumorkonferenz', $message) === true}
                    <Menge_Tumorkonferenz>
                        {foreach from=$message.menge_tumorkonferenz item=conference}
                        <Tumorkonferenz Tumorkonferenz_ID="{$conference.id}">
                            {xmlTag name='Tumorkonferenz_Datum' value=$conference}
                            {xmlTag name='Tumorkonferenz_Typ' value=$conference}
                            {xmlTag name='Anmerkung' value=$conference}
                        </Tumorkonferenz>
                        {/foreach}
                    </Menge_Tumorkonferenz>
                    {/if}

                    {if array_key_exists('menge_zusatzitem', $message) === true }
                    <Menge_Zusatzitem>
                        {foreach from=$message.menge_zusatzitem item=item}
                        <Zusatzitem>
                            {xmlTag name='Datum' value=$item}
                            {xmlTag name='Art' value=$item}
                            {xmlTag name='Wert' value=$item}
                            {xmlTag name='Bemerkung' value=$item}
                        </Zusatzitem>
                        {/foreach}
                    </Menge_Zusatzitem>
                    {/if}
                    {xmlTag name='Anmerkung' value=$message}
                </Meldung>
                {/foreach}
            </Menge_Meldung>
            {xmlTag name='Anmerkung' value=$patient.patient.anmerkung}
        </Patient>
        {/foreach}
    </Menge_Patient>
    <Menge_Melder>
        {foreach from=$data.melder item=melder}
        <Melder Melder_ID="{$melder.id}">
            {xmlTag name='Melder_IKNR' value=$melder}
            {xmlTag name='Melder_LANR' value=$melder}
            {xmlTag name='Melder_BSNR' value=$melder}
            {xmlTag name='Meldende_Stelle' value=$melder}
            {xmlTag name='Melder_KH_Abt_Station_Praxis' value=$melder}
            {xmlTag name='Melder_Arztname' value=$melder}
            {xmlTag name='Melder_Anschrift' value=$melder}
            {xmlTag name='Melder_PLZ' value=$melder}
            {xmlTag name='Melder_Ort' value=$melder}
            {xmlTag name='Melder_Bankname' value=$melder}
            {xmlTag name='Melder_Kontoinhaber' value=$melder}
            {xmlTag name='Melder_BIC' value=$melder}
            {xmlTag name='Melder_IBAN' value=$melder}
        </Melder>
        {/foreach}
    </Menge_Melder>
</ADT_GEKID>
