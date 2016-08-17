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
<darm>
    <schema_version>
        {xml_tag name='typ'                                                                     value=$data.schema_version.typ}
        {xml_tag name='jahr'                                                                    value=$data.schema_version.jahr}
    </schema_version>
    {xml_tag name='zentrum_id'                                                                  value=$data.zentrum_id}
    {xml_tag name='datum_datensatzerstellung'                                                   value=$data.datum_datensatzerstellung|date_format:"%Y-%m-%d"}
    {xml_tag name='zeitraum_beginn'                                                             value=$data.zeitraum_beginn}
    {xml_tag name='zeitraum_ende'                                                               value=$data.zeitraum_ende}
    <sw>
        {xml_tag name='sw_hersteller'                                                           value=$data.sw_hersteller}
        {xml_tag name='sw_name'                                                                 value=$data.sw_name}
        {xml_tag name='sw_version'                                                              value=$data.sw_version}
    </sw>
    {if strlen( $data.technische_ansprechpartner.tech_ansprechpartner_name ) > 0 && strlen( $data.technische_ansprechpartner.email ) > 0 }
        <technischer_ansprechpartner>
            {xml_tag name='tech_ansprechpartner_name'                                           value=$data.technische_ansprechpartner.tech_ansprechpartner_name}
            {xml_tag name='email'                                                               value=$data.technische_ansprechpartner.email}
        </technischer_ansprechpartner>
    {/if}
    {foreach from=$data.patients item=patient}
    <patient>
        {xml_tag name='patient_id'                                                              value=$patient.patient_id}
        <pat_daten>
            {xml_tag name='geburtstag'                                                          value=$patient.geburtstag|date_format:"%Y-%m-%d"}
            {xml_tag name='geschlecht'                                                          value=$patient.geschlecht}
            {if $patient.todesdatum != '' && $patient.todesursache != ''}
                <verstorben>
                    {xml_tag name='todesdatum'                                                  value=$patient.todesdatum}
                    {xml_tag name='todesursache'                                                value=$patient.todesursache}
                </verstorben>
            {/if}
        </pat_daten>
        {foreach from=$patient.faelle item=fall}
            <fall>
                {xml_tag name='fall_id'                                                         value=$fall.fall_id}
                {xml_tag name='kostentraeger'                                                   value=$fall.kostentraeger}
                {xml_tag name='fall_beginn'                                                     value=$fall.fall_beginn}
                {xml_tag name='koerpergroesse'                                                  value=$fall.koerpergroesse}
                {xml_tag name='koerpergewicht'                                                  value=$fall.koerpergewicht}
                {if isset( $fall.study ) }
                    {foreach from=$fall.study item=studie}
                        <studie>
                            {xml_tag name='studienteilnehmer'                                   value=$studie.studienteilnehmer}
                            {if isset( $studie.studien_name ) }
                                {xml_tag name='studien_name'                                        value=$studie.studien_name}
                            {/if}
                            {if isset( $studie.studie_beendet ) }
                            {xml_tag name='studie_beendet'                                      value=$studie.studie_beendet}
                            {/if}
                            {if isset( $studie.datum_einschluss ) }
                            {xml_tag name='datum_einschluss'                                    value=$studie.datum_einschluss}
                            {/if}
                            {if isset( $studie.datum_studienende ) }
                            {xml_tag name='datum_studienende'                                   value=$studie.datum_studienende}
                            {/if}
                        </studie>
                    {/foreach}
                {/if}
                {if isset( $fall.conference ) }
                    {foreach from=$fall.conference item=tumorkonferenz}
                        <tumorkonferenz>
                            {xml_tag name='tumorkonferenz_datum'                                value=$tumorkonferenz.tumorkonferenz_datum}
                            {xml_tag name='empfehlung'                                          value=$tumorkonferenz.empfehlung}
                            {xml_tag name='postoperativ'                                        value=$tumorkonferenz.postoperativ}
                            {xml_tag name='praetherapeutisch'                                   value=$tumorkonferenz.praetherapeutisch}
                         </tumorkonferenz>
                    {/foreach}
                {/if}
                {if isset( $fall.anamnesis ) }
                    {foreach from=$fall.anamnesis item=anamnese}
                        <anamnese>
                            {xml_tag name='rezidiv'                                             value=$anamnese.rezediv}
                            {xml_tag name='erstdiag_datum'                                      value=$anamnese.erstdiag_datum}
                            {xml_tag name='familie_pos'                                         value=$anamnese.familie_pos}
                        </anamnese>
                    {/foreach}
                {/if}
                {if isset( $fall.diagnose ) }
                    {foreach from=$fall.diagnose item=diag}
                        <diagnose>
                            {xml_tag name='tumor'                                               value=$diag.tumor}
                            {xml_tag name='anocutanlinie'                                       value=$diag.anocutanlinie}
                            {xml_tag name='rezidiv'                                             value=$diag.rezediv}
                            {xml_tag name='datum_diagnose'                                      value=$diag.datum_diagnose}
                            <icd>
                                {xml_tag name='icd_code'                                        value=$diag.icd_code}
                                {xml_tag name='icd_text'                                        value=$diag.icd_text}
                                {xml_tag name='icd_version'                                     value=$diag.icd_version}
                            </icd>
                            <klinischer_tnm>
                                {xml_tag name='t'                                               value=$diag.t}
                                {xml_tag name='n'                                               value=$diag.n}
                                {xml_tag name='m'                                               value=$diag.m}
                                {xml_tag name='y'                                               value=$diag.y}
                                {xml_tag name='g'                                               value=$diag.g}
                                {xml_tag name='tnm_version'                                     value=$diag.tnm_version}
                            </klinischer_tnm>
                            <koloskopie>
                                {xml_tag name='ges_koloskopie'                                  value=$diag.ges_koloskopie}
                                {xml_tag name='tot_koloskopie'                                  value=$diag.tot_koloskopie}
                                {xml_tag name='ther_koloskopie'                                 value=$diag.ther_koloskopie}
                                {xml_tag name='ther_koloskopie_kompl'                           value=$diag.ther_koloskopie_kompl}
                                {xml_tag name='unv_stenosierende_koloskopie'                    value=$diag.unv_stenosierende_koloskopie}
                                {xml_tag name='polyp_nachweis'                                  value=$diag.polyp_nachweis}
                                {xml_tag name='polypektomie'                                    value=$diag.polypektomie}
                                {xml_tag name='polyp_op_gebiet'                                 value=$diag.polyp_op_gebiet}
                                {xml_tag name='polypektomie_polyp'                              value=$diag.polypektomie_polyp}
                            </koloskopie>
                        </diagnose>
                    {/foreach}
                {/if}
                {if isset( $fall.histology ) }
                    {foreach from=$fall.histology item=histologie}
                        {if isset( $histologie.patho_histo_klassifikation ) }
                            <histologie>
                                {xml_tag name='msi'                                                 value=$histologie.msi}
                                {xml_tag name='k_ras_wildtyp'                                       value=$histologie.k_ras_wildtyp}
                                <patho_histo_klassifikation>
                                   {xml_tag name='morpho_code'                                      value=$histologie.patho_histo_klassifikation.morpho_code}
                                   {xml_tag name='morpho_text'                                      value=$histologie.patho_histo_klassifikation.morpho_text}
                                   {xml_tag name='topologie_code'                                   value=$histologie.patho_histo_klassifikation.topologie_code}
                                   {xml_tag name='topologie_text'                                   value=$histologie.patho_histo_klassifikation.topologie_text}
                                   {xml_tag name='histo_datum'                                      value=$histologie.patho_histo_klassifikation.histo_datum}
                                   {xml_tag name='icdo_version'                                     value=$histologie.patho_histo_klassifikation.icdo_version}
                                   {xml_tag name='t'                                                value=$histologie.patho_histo_klassifikation.t}
                                   {xml_tag name='n'                                                value=$histologie.patho_histo_klassifikation.n}
                                   {xml_tag name='m'                                                value=$histologie.patho_histo_klassifikation.m}
                                   {xml_tag name='y'                                                value=$histologie.patho_histo_klassifikation.y}
                                   {xml_tag name='g'                                                value=$histologie.patho_histo_klassifikation.g}
                                   {xml_tag name='r'                                                value=$histologie.patho_histo_klassifikation.r}
                                   {xml_tag name='l'                                                value=$histologie.patho_histo_klassifikation.l}
                                   {xml_tag name='v'                                                value=$histologie.patho_histo_klassifikation.v}
                                   {xml_tag name='pn'                                               value=$histologie.patho_histo_klassifikation.pn}
                                   {xml_tag name='tnm_version'                                      value=$histologie.patho_histo_klassifikation.tnm_version}
                                   {foreach from=$histologie.patho_histo_klassifikation.metastasen_ort item=ort}
                                      {xml_tag name='metastasen_ort'                                value=$ort.ort}
                                   {/foreach}
                                   {xml_tag name='stadiengruppierung_uicc'                          value=$histologie.patho_histo_klassifikation.stadiengruppierung_uicc}
                                </patho_histo_klassifikation>
                            </histologie>
                        {/if}
                    {/foreach}
                {/if}
                {if isset( $fall.operations ) && count( $fall.operations ) > 0 ||
                    isset( $fall.systemicTherapy ) && count( $fall.systemicTherapy ) > 0 ||
                    isset( $fall.radioTherapy ) && count( $fall.radioTherapy ) > 0 }
                    <therapie>
                        {if isset( $fall.operations )}
                            {foreach from=$fall.operations item=operation}
                                {if isset( $operation.ct_ops )}
                                    {foreach from=$operation.ct_ops item=ops_code}
                                        <ops>
                                            {xml_tag name='ops_code'                            value=$ops_code.ops_code}
                                            {xml_tag name='ops_text'                            value=$ops_code.ops_text}
                                            {xml_tag name='ops_datum'                           value=$ops_code.ops_datum}
                                        </ops>
                                    {/foreach}
                                {/if}
                            {/foreach}
                            {foreach from=$fall.operations item=operation}
                                <operation>
                                    {xml_tag name='ersteingriff'                                value=$operation.ersteingriff}
                                    {xml_tag name='op_datum'                                    value=$operation.op_datum}
                                    {xml_tag name='op_typ'                                      value=$operation.op_typ}
                                    {xml_tag name='op_notfalltyp'                               value=$operation.op_notfalltyp}
                                    {xml_tag name='op_intention'                                value=$operation.op_intention}
                                    {xml_tag name='op_letalitaet'                               value=$operation.op_letalitaet}
                                    {if ( strlen( $operation.distal ) > 0 ) && ( strlen( $operation.lateral ) > 0 ) }
                                        <operativer_sicherheitsabstand>
                                            {xml_tag name='lateral'                             value=$operation.lateral}
                                            {xml_tag name='distal'                              value=$operation.distal}
                                        </operativer_sicherheitsabstand>
                                    {/if}
                                    {if ( strlen( $operation.abstand_resektionsraender_distal ) > 0 ) &&
                                        ( strlen( $operation.abstand_resektionsraender_lateral ) > 0 ) }
                                        <circumferentieller_tumorrand>
                                            {xml_tag name='abstand_resektionsraender_distal'    value=$operation.abstand_resektionsraender_distal}
                                            {xml_tag name='abstand_resektionsraender_lateral'   value=$operation.abstand_resektionsraender_lateral}
                                        </circumferentieller_tumorrand>
                                    {/if}
                                    {xml_tag name='pathohistologisch_lokaler_r_status'          value=$operation.pathohistologisch_lokaler_r_status}
                                    {xml_tag name='asa_score'                                   value=$operation.asa_score}
                                    {if isset($operation.op_komplikation)}
                                        {foreach from=$operation.op_komplikation item=komplikation}
                                            <op_komplikation>
                                                {xml_tag name='op_komplikationsgrad'                value=$komplikation.op_komplikationsgrad}
                                                {xml_tag name='op_komplikationsart'                 value=$komplikation.op_komplikationsart}
                                            </op_komplikation>
                                        {/foreach}
                                    {/if}
                                    {xml_tag name='mesorektumexstirpation'                      value=$operation.mesorektumexstirpation}
                                    <stoma>
                                        {xml_tag name='kuenstl_darmausgang'                     value=$operation.kuenstl_darmausgang}
                                        {xml_tag name='stoma_protektiv'                         value=$operation.stoma_protektiv}
                                    </stoma>
                                    {foreach from=$operation.lymphknoten item=lymphknot}
                                        <lymphknoten>
                                            {xml_tag name='lymphknoten_extirpation'             value=$lymphknot.lymphknoten_extirpation}
                                            {xml_tag name='anz_entf_lymphknoten_histo'          value=$lymphknot.lk_removed }
                                            {xml_tag name='anz_entf_lymphknoten_pos'            value=$lymphknot.lk_infested }
                                        </lymphknoten>
                                    {/foreach}
                                    {xml_tag name='regionale_operationsverfahren'               value=$operation.regionale_operationsverfahren}
                                    {xml_tag name='mercury'                                     value=$operation.mercury}
                                    {xml_tag name='leberresektion'                              value=$operation.leberresektion}
                                    {xml_tag name='ohne_anastomose'                             value=$operation.ohne_anastomose}
                                </operation>
                            {/foreach}
                        {/if}
                        {if isset( $fall.systemicTherapy )}
                            {foreach from=$fall.systemicTherapy item=systemtherapie}
                                <systemtherapie>
                                    {xml_tag name='systemtherapie'                              value=$systemtherapie.systemtherapie}
                                    {xml_tag name='protokoll'                                   value=$systemtherapie.protokoll}
                                    {xml_tag name='systemtherapie_intention'                    value=$systemtherapie.systemtherapie_intention}
                                    {xml_tag name='systemtherapie_beg_datum'                    value=$systemtherapie.systemtherapie_beg_datum}
                                    {xml_tag name='systemtherapie_ende_datum'                   value=$systemtherapie.systemtherapie_ende_datum}
                                    {xml_tag name='systemtherapie_ergebnis'                     value=$systemtherapie.systemtherapie_ergebnis}
                                    {xml_tag name='systemtherapie_erfolg'                       value=$systemtherapie.systemtherapie_erfolg}
                                    {xml_tag name='keine_systemtherapie'                        value=$systemtherapie.keine_systemtherapie}
                                </systemtherapie>
                            {/foreach}
                        {/if}
                        {if isset( $fall.radioTherapy )}
                            {foreach from=$fall.radioTherapy item=strahlentherapie}
                                <strahlentherapie>
                                    {xml_tag name='strahlentherapie'                            value=$strahlentherapie.strahlentherapie}
                                    {xml_tag name='strahlentherapie_intention'                  value=$strahlentherapie.strahlentherapie_intention}
                                    {xml_tag name='strahlentherapie_beg_datum'                  value=$strahlentherapie.strahlentherapie_beg_datum}
                                    {xml_tag name='strahlentherapie_ende_datum'                 value=$strahlentherapie.strahlentherapie_ende_datum}
                                    {xml_tag name='strahlentherapie_ergebnis'                   value=$strahlentherapie.strahlentherapie_ergebnis}
                                    {xml_tag name='keine_strahlentherapie'                      value=$strahlentherapie.keine_strahlentherapie}
                                    {xml_tag name='gesamtdosis'                                 value=$strahlentherapie.gesamtdosis}
                                    {xml_tag name='boost'                                       value=$strahlentherapie.boost}
                                </strahlentherapie>
                            {/foreach}
                        {/if}
                    </therapie>
                    {/if}
                    {if isset( $fall.lab ) &&
                        count( $fall.lab ) > 0 }
                        <labor>
                            {foreach from=$fall.lab item=labor}
                                <cea>
                                    {xml_tag name='cea'                                         value=$labor.cea}
                                    {xml_tag name='cea_datum'                                   value=$labor.cea_datum}
                                </cea>
                            {/foreach}
                        </labor>
                    {/if}
                    {if isset( $fall.afterCare ) }
                        {foreach from=$fall.afterCare item=nachsorge}
                            <nachsorge>
                                {xml_tag name='nachsorge_datum'                                 value=$nachsorge.nachsorge_datum}
                            </nachsorge>
                        {/foreach}
                    {/if}
                    {if isset( $fall.abschluss ) }
                        {foreach from=$fall.abschluss item=followup}
                            <follow_up>
                                {xml_tag name='lost_follow_up'                                  value=$followup.lost_follow_up}
                                {xml_tag name='overall_survival'                                value=$followup.overall_survival}
                                {xml_tag name='disease_free_survival'                           value=$followup.disease_free_survival}
                            </follow_up>
                        {/foreach}
                    {/if}
                </fall>
            {/foreach}
        </patient>
    {/foreach}
</darm>
