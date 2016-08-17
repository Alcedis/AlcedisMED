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
<mamma>
    <schema_version>
        {xml_tag name='typ'                                         value=$data.schema_version.typ}
        {xml_tag name='jahr'                                        value=$data.schema_version.jahr}
    </schema_version>
    {xml_tag name='zentrum_id'                                      value=$data.zentrum_id}
    {xml_tag name='datum_datensatzerstellung'                       value=$data.datum_datensatzerstellung}
    {xml_tag name='zeitraum_beginn'                                 value=$data.zeitraum_beginn}
    {xml_tag name='zeitraum_ende'                                   value=$data.zeitraum_ende}
    <sw>
        {xml_tag name='sw_hersteller'                               value=$data.sw.sw_hersteller}
        {xml_tag name='sw_name'                                     value=$data.sw.sw_name}
        {xml_tag name='sw_version'                                  value=$data.sw.sw_version}
    </sw>
    {if strlen( $data.technische_ansprechpartner.tech_ansprechpartner_name ) > 0 &&
        strlen( $data.technische_ansprechpartner.email ) > 0 }
    <technischer_ansprechpartner>
        {xml_tag name='tech_ansprechpartner_name'                   value=$data.technische_ansprechpartner.tech_ansprechpartner_name}
        {xml_tag name='email'                                       value=$data.technische_ansprechpartner.email}
    </technischer_ansprechpartner>
    {/if}
    {foreach from=$data.patients item=patient}
    <patient>
        {xml_tag name='patient_id'                                  value=$patient.patient_id}
        <pat_daten>
            {xml_tag name='geburtstag'                              value=$patient.pat_daten.geburtstag}
            {xml_tag name='geschlecht'                              value=$patient.pat_daten.geschlecht}
            {if strlen( $patient.pat_daten.verstorben.todesdatum ) > 0 &&
                strlen( $patient.pat_daten.verstorben.todesursache ) > 0 }
            <verstorben>
                {xml_tag name='todesdatum'                          value=$patient.pat_daten.verstorben.todesdatum}
                {xml_tag name='todesursache'                        value=$patient.pat_daten.verstorben.todesursache}
            </verstorben>
            {/if}
        </pat_daten>
        {foreach from=$patient.faelle item=fall}
        <fall>
            {xml_tag name='fall_id'                                 value=$fall.fall_id}
            {xml_tag name='kostentraeger'                           value=$fall.kostentraeger}
            {if isset( $fall.menopause ) &&
                strlen( $fall.menopause.menopausenstatus ) > 0 &&
                strlen( $fall.menopause.menopausenstatus_datum ) > 0 }
            <menopause>
                {xml_tag name='menopausenstatus'                    value=$fall.menopause.menopausenstatus}
                {xml_tag name='menopausenstatus_datum'              value=$fall.menopause.menopausenstatus_datum}
            </menopause>
            {/if}
            {xml_tag name='koerpergroesse'                          value=$fall.koerpergroesse}
            {xml_tag name='koerpergewicht'                          value=$fall.koerpergewicht}
            {xml_tag name='seite'                                   value=$fall.seite}
            {xml_tag name='fall_beginn'                             value=$fall.fall_beginn}
            {if isset( $fall.aufenthalte_stationaer ) }
            {foreach from=$fall.aufenthalte_stationaer item=aufenthalt}
            <aufenthalt_stationaer>
                {xml_tag name='aufenthalt_beginn'                   value=$aufenthalt.aufenthalt_beginn}
                {xml_tag name='aufenthalt_dauer'                    value=$aufenthalt.aufenthalt_dauer}
            </aufenthalt_stationaer>
            {/foreach}
            {/if}
            {if isset( $fall.studien ) }
            {foreach from=$fall.studien item=studie}
            <studie>
                {xml_tag name='studienteilnehmer'                   value=$studie.studienteilnehmer}
                {xml_tag name='studien_name'                        value=$studie.studien_name}
                {xml_tag name='datum_einschluss'                    value=$studie.datum_einschluss}
                {xml_tag name='datum_ende'                          value=$studie.datum_ende}
                {xml_tag name='studie_beendet'                      value=$studie.studie_beendet}
                {xml_tag name='studien_bemerkungen'                 value=$studie.studien_bemerkungen}
            </studie>
            {/foreach}
            {/if}
            {if isset( $fall.tumorkonferenzen ) }
            {foreach from=$fall.tumorkonferenzen item=tumorkonferenz}
            <tumorkonferenz>
                {xml_tag name='tumorkonferenz_datum'                value=$tumorkonferenz.tumorkonferenz_datum}
                {xml_tag name='empfehlung'                          value=$tumorkonferenz.empfehlung}
                {xml_tag name='zeitpunkt'                           value=$tumorkonferenz.zeitpunkt}
            </tumorkonferenz>
            {/foreach}
            {/if}
            {if isset( $fall.diagnosen ) }
            {foreach from=$fall.diagnosen item=diagnose}
            <diagnose>
                {xml_tag name='rezidiv'                             value=$diagnose.rezidiv}
                {xml_tag name='diag_datum'                          value=$diagnose.diag_datum}
                {xml_tag name='biopsie_methode'                     value=$diagnose.biopsie_methode}
                {xml_tag name='biopsie_steuerung'                   value=$diagnose.biopsie_steuerung}
                {xml_tag name='biopsie_extern_durchgefuehrt'        value=$diagnose.biopsie_extern_durchgefuehrt}
                {xml_tag name='biopsie_datum'                       value=$diagnose.biopsie_datum}
                {xml_tag name='mikrokalk'                           value=$diagnose.mikrokalk}
                {if strlen( $diagnose.klassifikation.icd_code ) > 0 ||
                    strlen( $diagnose.klassifikation.icd_text ) > 0 ||
                    strlen( $klassifikation.definitive_morphologie ) > 0 ||
                    strlen( $diagnose.klassifikation.definitive_topologie ) > 0 }
                <klassifikation>
                    {xml_tag name='icd_code'                        value=$diagnose.klassifikation.icd_code}
                    {xml_tag name='icd_text'                        value=$diagnose.klassifikation.icd_text}
                    {xml_tag name='definitive_morphologie'          value=$diagnose.klassifikation.definitive_morphologie}
                    {xml_tag name='definitive_topologie'            value=$diagnose.klassifikation.definitive_topologie}
                </klassifikation>
                {/if}
                {if strlen( $diagnose.tumor.multizentritaet ) > 0 &&
                    strlen( $diagnose.tumor.multifokalitaet ) > 0 }
                <tumor>
                    {xml_tag name='multizentritaet'                 value=$diagnose.tumor.multizentritaet}
                    {xml_tag name='multifokalitaet'                 value=$diagnose.tumor.multifokalitaet}
                    {xml_tag name='dignitaet'                       value=$diagnose.tumor.dignitaet}
                    {if strlen( $diagnose.tumor.klinische_einteilung.t ) > 0 ||
                        strlen( $diagnose.tumor.klinische_einteilung.n ) > 0 ||
                        strlen( $diagnose.tumor.klinische_einteilung.m ) > 0||
                        strlen( $diagnose.tumor.klinische_einteilung.tnm_version ) > 0 ||
                        strlen( $diagnose.tumor.klinische_einteilung.stadiengruppierung_uicc ) > 0 }
                    <klinische_einteilung>
                        {xml_tag name='t'                           value=$diagnose.tumor.klinische_einteilung.t}
                        {xml_tag name='n'                           value=$diagnose.tumor.klinische_einteilung.n}
                        {xml_tag name='m'                           value=$diagnose.tumor.klinische_einteilung.m}
                        {xml_tag name='tnm_version'                 value=$diagnose.tumor.klinische_einteilung.tnm_version}
                        {xml_tag name='stadiengruppierung_uicc'     value=$diagnose.tumor.klinische_einteilung.stadiengruppierung_uicc}
                    </klinische_einteilung>
                    {/if}
                </tumor>
                {/if}
            </diagnose>
            {/foreach}
            {/if}
            {if isset( $fall.therapien ) }
            {foreach from=$fall.therapien item=therapie}
            {if count( $therapie.operationen ) > 0 ||
                count( $therapie.systemtherapien ) > 0 ||
                count( $therapie.strahlentherapien ) > 0 }
            <therapie>
                {foreach from=$therapie.operationen item=op}
                <operation>
                    {xml_tag name='tumorrelevante_op'               value=$op.tumorrelevante_op}
                    {foreach from=$op.ops item=opsc}
                    <ops>
                        {xml_tag name='ops_code'                    value=$opsc.ops_code}
                        {xml_tag name='ops_text'                    value=$opsc.ops_text}
                        {xml_tag name='ops_version'                 value=$opsc.ops_version}
                    </ops>
                    {/foreach}
                    {xml_tag name='op_datum'                        value=$op.op_datum}
                    {xml_tag name='op_typ'                          value=$op.op_typ}
                    {xml_tag name='op_intention'                    value=$op.op_intention}
                    {xml_tag name='op_letalitaet'                   value=$op.op_letalitaet}
                    {xml_tag name='operativer_sicherheitsabstand'   value=$op.operativer_sicherheitsabstand}
                    {xml_tag name='ablatio_wunsch'                  value=$op.ablatio_wunsch}
                    {xml_tag name='bet_wunsch'                      value=$op.bet_wunsch}
                    {xml_tag name='bet_nicht_durchgefuehrt'         value=$op.bet_nicht_durchgefuehrt}
                    {xml_tag name='mastektomie_nicht_durchgefuehrt' value=$op.mastektomie_nicht_durchgefuehrt}
                    {xml_tag name='sentinel_nicht_durchgefuehrt'    value=$op.sentinel_nicht_durchgefuehrt}
                    {xml_tag name='axilla_nicht_durchgefuehrt'      value=$op.axilla_nicht_durchgefuehrt}
                    {xml_tag name='sentinel_nicht_detektierbar'     value=$op.sentinel_nicht_detektierbar}
                    {xml_tag name='lokale_pathohisto_radikalitaet'  value=$op.lokale_pathohisto_radikalitaet}
                    {xml_tag name='asa_score'                       value=$op.asa_score}
                    {foreach from=$op.op_komplikationen item=komp}
                    <op_komplikation>
                        {xml_tag name='op_komplikation'             value=$komp.op_komplikation}
                        {xml_tag name='op_komplikationsgrad'        value=$komp.op_komplikationsgrad}
                        {xml_tag name='op_komplikationsart'         value=$komp.op_komplikationsart}
                    </op_komplikation>
                    {/foreach}
                    {xml_tag name='praeoperative_markierung'        value=$op.praeoperative_markierung}
                    {xml_tag name='markierung_abstand'              value=$op.markierung_abstand}
                    {xml_tag name='bildgebende_kontrolle'           value=$op.bildgebende_kontrolle}
                    {xml_tag name='anz_entf_lymphknoten'            value=$op.anz_entf_lymphknoten}
                    {xml_tag name='anz_entf_lymphknoten_pos'        value=$op.anz_entf_lymphknoten_pos}
                    {xml_tag name='anz_entf_sentinel'               value=$op.anz_entf_sentinel}
                    {xml_tag name='anz_entf_sentinel_pos'           value=$op.anz_entf_sentinel_pos}
                    {xml_tag name='op_extern_durchgefuehrt'         value=$op.op_extern_durchgefuehrt}
                    {xml_tag name='brustrekonstruktion'             value=$op.brustrekonstruktion}
                </operation>
                {/foreach}
                {foreach from=$therapie.systemtherapien item=thera}
                <systemtherapie>
                    {xml_tag name='systemtherapie_typ'              value=$thera.systemtherapie_typ}
                    {xml_tag name='systemtherapie_ausfuehrung'      value=$thera.systemtherapie_ausfuehrung}
                    {xml_tag name='systemtherapie_intention'        value=$thera.systemtherapie_intention}
                    {xml_tag name='systemtherapie_beg_datum'        value=$thera.systemtherapie_beg_datum}
                    {xml_tag name='systemtherapie_ende_datum'       value=$thera.systemtherapie_ende_datum}
                    {xml_tag name='protokoll'                       value=$thera.protokoll}
                    {xml_tag name='protokoll_art'                   value=$thera.protokoll_art}
                    {xml_tag name='anthrazyklin_gabe'               value=$thera.anthrazyklin_gabe}
                    {xml_tag name='taxan_gabe'                      value=$thera.taxan_gabe}
                    {xml_tag name='systemtherapie_ergebnis'         value=$thera.systemtherapie_ergebnis}
                    {xml_tag name='systemtherapie_erfolg'           value=$thera.systemtherapie_erfolg}
                    {xml_tag name='keine_systemtherapie'            value=$thera.keine_systemtherapie}
                </systemtherapie>
                {/foreach}
                {foreach from=$therapie.strahlentherapien item=strahlen}
                <strahlentherapie>
                    {xml_tag name='strahlentherapie'                value=$strahlen.strahlentherapie}
                    {xml_tag name='strahlentherapie_intention'      value=$strahlen.strahlentherapie_intention}
                    {xml_tag name='strahlentherapie_beg_datum'      value=$strahlen.strahlentherapie_beg_datum}
                    {xml_tag name='strahlentherapie_ende_datum'     value=$strahlen.strahlentherapie_ende_datum}
                    {xml_tag name='strahlentherapie_ergebnis'       value=$strahlen.strahlentherapie_ergebnis}
                    {xml_tag name='keine_strahlentherapie'          value=$strahlen.keine_strahlentherapie }
                    {xml_tag name='gesamtdosis'                     value=$strahlen.gesamtdosis }
                    {xml_tag name='boost'                           value=$strahlen.boost }
                    {foreach from=$strahlen.regionen item=region}
                    {if strlen( $region ) > 0 }
                    <region>{$region}</region>
                    {/if}
                    {/foreach}
                </strahlentherapie>
                {/foreach}
            </therapie>
            {/if}
            {/foreach}
            {/if}
            {if isset( $fall.patho_histologien ) }
            {foreach from=$fall.patho_histologien item=histo}
            <patho_histologie>
                {xml_tag name='morpho_code'                         value=$histo.morpho_code}
                {xml_tag name='morpho_text'                         value=$histo.morpho_text}
                {xml_tag name='topologie_code'                      value=$histo.topologie_code}
                {xml_tag name='topologie_text'                      value=$histo.topologie_text}
                {xml_tag name='histo_datum'                         value=$histo.histo_datum}
                {xml_tag name='t'                                   value=$histo.t}
                {xml_tag name='n'                                   value=$histo.n}
                {xml_tag name='m'                                   value=$histo.m}
                {xml_tag name='praefix'                             value=$histo.praefix}
                {xml_tag name='g'                                   value=$histo.g}
                {xml_tag name='r'                                   value=$histo.r}
                {xml_tag name='l'                                   value=$histo.l}
                {xml_tag name='v'                                   value=$histo.v}
                {xml_tag name='metastasen_ort'                      value=$histo.metastasen_ort}
                {xml_tag name='tnm_version'                         value=$histo.tnm_version}
            </patho_histologie>
            {/foreach}
            {/if}
            {if isset( $fall.labore ) }
            {foreach from=$fall.labore item=labor}
            <labor>
                {if strlen( $labor.her2 ) > 0 }
                <her2>
                    <her2>{$labor.her2}</her2>
                    <her2_datum>{$labor.her2_datum}</her2_datum>
                </her2>
                {/if}
                {if strlen( $labor.fish ) > 0 }
                <fish>
                    <fish>{$labor.fish}</fish>
                    <fish_datum>{$labor.fish_datum}</fish_datum>
                </fish>
                {/if}
                {if strlen( $labor.cish ) > 0 }
                <cish>
                    <cish>{$labor.cish}</cish>
                    <cish_datum>{$labor.cish_datum}</cish_datum>
                </cish>
                {/if}
                {if ( strlen( $labor.er ) > 0 ) &&
                    ( strlen( $labor.er_score ) > 0 ) }
                <er>
                    <er>{$labor.er}</er>
                    <er_datum>{$labor.er_datum}</er_datum>
                    <er_score>{$labor.er_score}</er_score>
                </er>
                {/if}
                {if ( strlen( $labor.pr ) > 0 ) &&
                    ( strlen( $labor.pr_score ) > 0 ) }
                <pr>
                    <pr>{$labor.pr}</pr>
                    <pr_datum>{$labor.pr_datum}</pr_datum>
                    <pr_score>{$labor.pr_score}</pr_score>
                </pr>
                {/if}
                {if strlen( $labor.upa ) > 0 }
                <upa>
                    <upa>{$labor.upa}</upa>
                    <upa_datum>{$labor.upa_datum}</upa_datum>
                </upa>
                {/if}
                {if strlen( $labor.pai1 ) > 0 }
                <pai1>
                    <pai1>{$labor.pai1}</pai1>
                    <pai1_datum>{$labor.pai1_datum}</pai1_datum>
                </pai1>
                {/if}
            </labor>
            {/foreach}
            {/if}
        </fall>
        {/foreach}
        {if isset( $patient.nachsorgen ) }
        {foreach from=$patient.nachsorgen item=nachsorge}
        <nachsorge>
            {xml_tag name='nachsorge_datum'                         value=$nachsorge.nachsorge_datum}
            {xml_tag name='nachsorge_beurteilung'                   value=$nachsorge.nachsorge_beurteilung}
            {xml_tag name='rezidiv_status'                          value=$nachsorge.rezidiv_status}
            {xml_tag name='lost_follow_up'                          value=$nachsorge.lost_follow_up}
        </nachsorge>
        {/foreach}
        {/if}
    </patient>
    {/foreach}
</mamma>
