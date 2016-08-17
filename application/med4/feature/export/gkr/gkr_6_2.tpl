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
<KRBW xmlns="krbw">
  <KEY_VS></KEY_VS>
  <KEY_KLR></KEY_KLR>
  <ABSENDER>    
    <ABSENDER_MELDER_ID>{$data.melder.id}</ABSENDER_MELDER_ID>
    <ABSENDER_PRUEFCODE>{$data.melder.pruefcode}</ABSENDER_PRUEFCODE>
    <MELDER>
      <MELDER_ID>{$data.melder.id}</MELDER_ID>
      <MELDER_PRUEFCODE>{$data.melder.pruefcode}</MELDER_PRUEFCODE>
      {if strlen( $data.melder.ansprechpartner ) > 0}
      <ANSPRECHPARTNER>{$data.melder.ansprechpartner}</ANSPRECHPARTNER>
      {/if}
      {if strlen( $data.melder.quellsystem ) > 0}
      <QUELLSYSTEM>{$data.melder.quellsystem}</QUELLSYSTEM>
      {/if}
      {foreach from=$data.patients item=patient }
      <PATIENT>
      	{if strlen( $patient.versicherungsnummer ) > 0 }
        <VERSICHERTENNR>{$patient.versicherungsnummer}</VERSICHERTENNR>
        {/if}
        <REFERENZNR>{$patient.referenznr}</REFERENZNR>
        <UNTERRICHTUNG>{$patient.unterrichtung}</UNTERRICHTUNG>
        {if strlen( $patient.titel ) > 0}
        <TITEL>{$patient.titel}</TITEL>
        {/if}
        <NACHNAME>{$patient.nachname}</NACHNAME>
        <VORNAMEN>{$patient.vorname}</VORNAMEN>
        {if strlen( $patient.geburtsname ) > 0}
        <GEBURTSNAME>{$patient.geburtsname}</GEBURTSNAME>
        {/if}
        <GEBURTSDATUM>{$patient.geburtsdatum}</GEBURTSDATUM>
        <GESCHLECHT>{$patient.geschlecht}</GESCHLECHT>
        <LAND>{$patient.land}</LAND>
        {if strlen( $patient.plz ) > 0}
        <PLZ>{$patient.plz}</PLZ>
        {/if}
        {if strlen( $patient.wohnort ) > 0}
        <WOHNORT>{$patient.wohnort}</WOHNORT>
        {/if}
        {if strlen( $patient.strasse ) > 0}
        <STRASSE>{$patient.strasse}</STRASSE>
        {/if}
        {if strlen( $patient.hausnummer ) > 0}
        <HAUSNR>{$patient.hausnummer}</HAUSNR>
        {/if}
        <KLR>
          {if strlen( $patient.staatsangehoerigkeit ) > 0}
          <STAATSANGEHOERIGKEIT>{$patient.staatsangehoerigkeit}</STAATSANGEHOERIGKEIT>
          {/if}
        </KLR>
        {foreach from=$patient.diagnosen item=diagnose }
        <MELDUNG>
          <DIAGNOSE>
            <TAN>{$diagnose.tan}</TAN>
            <MELDUNGSKENNZEICHEN>{$diagnose.meldungskennzeichen}</MELDUNGSKENNZEICHEN>
            <ERSTDIAGNOSEDATUM>{$diagnose.erstdiagnosedatum}</ERSTDIAGNOSEDATUM>
            <KLR>
              <TUMORIDENTIFIKATOR>{$diagnose.tumoridentifikator}</TUMORIDENTIFIKATOR>
              {if strlen( $diagnose.diagnose_icd ) > 0}
              <DIAGNOSE_ICD>{$diagnose.diagnose_icd}</DIAGNOSE_ICD>
              {/if}
              <ICD-REVISION>{$diagnose.icd_revision}</ICD-REVISION>
              {if strlen( $diagnose.lokalisation_icd_o ) > 0}
              <LOKALISATION-ICD-O>{$diagnose.lokalisation_icd_o}</LOKALISATION-ICD-O>
              {/if}
              <SEITENLOKALISATION>{$diagnose.seitenlokalisation}</SEITENLOKALISATION>
              <ICD-O-VERSION>{$diagnose.icd_o_version}</ICD-O-VERSION>
              <HISTOLOGIE-ICD-O>{$diagnose.histologie_icd_o}</HISTOLOGIE-ICD-O>
              {if ( ( strlen( $diagnose.ct_stadium ) > 0 ) ||
                    ( strlen( $diagnose.cn_stadium ) > 0 ) ||
                    ( strlen( $diagnose.cm_stadium ) > 0 ) ||
                    ( strlen( $diagnose.t_stadium_postop ) > 0 ) ||
                    ( strlen( $diagnose.n_stadium_postop ) > 0 ) ||
                    ( strlen( $diagnose.m_stadium_postop ) > 0 ) ) }
              <TNM_VERSION>{$diagnose.tnm_version}</TNM_VERSION>
              {/if}
              {if strlen( $diagnose.ct_stadium ) > 0}
              <CT_STADIUM>{$diagnose.ct_stadium}</CT_STADIUM>
              {/if}
              {if strlen( $diagnose.cn_stadium ) > 0}
              <CN_STADIUM>{$diagnose.cn_stadium}</CN_STADIUM>
              {/if}
              {if strlen( $diagnose.cm_stadium ) > 0}
              <CM_STADIUM>{$diagnose.cm_stadium}</CM_STADIUM>
              {/if}
              {if strlen( $diagnose.t_stadium_postop ) > 0}
              <T_STADIUM_POSTOP>{$diagnose.t_stadium_postop}</T_STADIUM_POSTOP>
              {/if}
              {if strlen( $diagnose.n_stadium_postop ) > 0}
              <N_STADIUM_POSTOP>{$diagnose.n_stadium_postop}</N_STADIUM_POSTOP>
              {/if}
              {if strlen( $diagnose.m_stadium_postop ) > 0}
              <M_STADIUM_POSTOP>{$diagnose.m_stadium_postop}</M_STADIUM_POSTOP>
              {/if}
              {if strlen( $diagnose.grading ) > 0}
              <GRADING>{$diagnose.grading}</GRADING>
              {/if}
              {if strlen( $diagnose.l_kategorie ) > 0}
              <L_KATEGORIE>{$diagnose.l_kategorie}</L_KATEGORIE>
              {/if}
              {if strlen( $diagnose.v_kategorie ) > 0}
              <V_KATEGORIE>{$diagnose.v_kategorie}</V_KATEGORIE>
              {/if}
              {if strlen( $diagnose.pn_kategorie ) > 0}
              <PN_KATEGORIE>{$diagnose.pn_kategorie}</PN_KATEGORIE>
              {/if}
              {if strlen( $diagnose.figo ) > 0}
              <FIGO>{$diagnose.figo}</FIGO>
              {/if}
							{if strlen( $diagnose.gleason_grading ) > 0}
              <GLEASON_GRADING>{$diagnose.gleason_grading}</GLEASON_GRADING>
              {/if}
              {if strlen( $diagnose.gleason_grading2 ) > 0}
              <GLEASON_GRADING2>{$diagnose.gleason_grading2}</GLEASON_GRADING2>
              {/if}
              {if strlen( $diagnose.gleason_score ) > 0}
              <GLEASON_SCORE>{$diagnose.gleason_score}</GLEASON_SCORE>
              {/if}
							{if strlen( $diagnose.ann_arbor ) > 0}
              <ANN_ARBOR>{$diagnose.ann_arbor}</ANN_ARBOR>
              {/if}
              {if strlen( $diagnose.binet ) > 0}
              <BINET>{$diagnose.binet}</BINET>
              {/if}
              {if strlen( $diagnose.durie_salmon ) > 0}
              <DURIE_SALMON>{$diagnose.durie_salmon}</DURIE_SALMON>
              {/if}
              {if strlen( $diagnose.fab ) > 0}
              <FAB>{$diagnose.fab}</FAB>
              {/if}
              {if strlen( $diagnose.rai ) > 0}
              <RAI>{$diagnose.rai}</RAI>
              {/if}
              {if strlen( $diagnose.gesamt_psa ) > 0}
              <GESAMT_PSA>{$diagnose.gesamt_psa}</GESAMT_PSA>
              {/if}
							{if strlen( $diagnose.lymphknoten_untersucht ) > 0}
              <LYMPHKNOTEN_UNTERSUCHT>{$diagnose.lymphknoten_untersucht}</LYMPHKNOTEN_UNTERSUCHT>
              {/if}
              {if strlen( $diagnose.lymphknoten_befallen ) > 0}
              <LYMPHKNOTEN_BEFALLEN>{$diagnose.lymphknoten_befallen}</LYMPHKNOTEN_BEFALLEN>
              {/if}
							{if strlen( $diagnose.rezeptor_oestrogen ) > 0}
              <REZEPTOR_OESTROGEN>{$diagnose.rezeptor_oestrogen}</REZEPTOR_OESTROGEN>
              {/if}
              {if strlen( $diagnose.rezeptor_progesteron ) > 0}
              <REZEPTOR_PROGESTERON>{$diagnose.rezeptor_progesteron}</REZEPTOR_PROGESTERON>
              {/if}
              {if strlen( $diagnose.rezeptor_her2 ) > 0}
              <REZEPTOR_HER2>{$diagnose.rezeptor_her2}</REZEPTOR_HER2>
              {/if}
              {if strlen( $diagnose.menopausenstatus ) > 0}
              <MENOPAUSENSTATUS>{$diagnose.menopausenstatus}</MENOPAUSENSTATUS>
              {/if}
              {if strlen( $diagnose.diagnoseanlass ) > 0}
              <DIAGNOSEANLASS>{$diagnose.diagnoseanlass}</DIAGNOSEANLASS>
              {/if}
              {if strlen( $diagnose.fruehere_tumordiagnosen ) > 0}
              <FRUEHERE_TUMORDIAGNOSEN>{$diagnose.fruehere_tumordiagnosen}</FRUEHERE_TUMORDIAGNOSEN>
              {/if}
              {if isset( $diagnose.metastasen ) && count( $diagnose.metastasen ) > 0 }
              <METASTASEN>
	              {foreach from=$diagnose.metastasen item=metastase }
	              <METASTASE>
	             		<METASTASENLOKALISATION>{$metastase.lokalisation}</METASTASENLOKALISATION>
	             	</METASTASE>
	              {/foreach}
              </METASTASEN>
              {/if}
            </KLR>
          </DIAGNOSE>
        </MELDUNG>
        {/foreach}
        {foreach from=$patient.therapien item=therapie }
        <MELDUNG>
          <THERAPIE>
            <TAN>{$therapie.tan}</TAN>
            <MELDUNGSKENNZEICHEN>{$therapie.meldungskennzeichen}</MELDUNGSKENNZEICHEN>
            <KLR>
              <TUMORIDENTIFIKATOR>{$therapie.tumoridentifikator}</TUMORIDENTIFIKATOR>
              <THERAPIEART>{$therapie.therapieart}</THERAPIEART>
              {if ( ( 'ME' == $therapie.therapieart ) &&
                    ( isset( $therapie.medikamentoese_therapie ) ) &&
                    ( strlen( $therapie.medikamentoese_therapie ) > 0 ) ) }
              <MEDIKAMENTOESE_THERAPIE>{$therapie.medikamentoese_therapie}</MEDIKAMENTOESE_THERAPIE>
              {/if}
              {if ( ( 'ST' == $therapie.therapieart ) && 
                    ( isset( $therapie.strahlentherapie ) ) &&
                    ( strlen( $therapie.strahlentherapie ) > 0 ) ) }
              <STRAHLENTHERAPIE>{$therapie.strahlentherapie}</STRAHLENTHERAPIE>
              {/if}
              {if ( 'SO' == $therapie.therapieart ) }
	              {if ( ( isset( $therapie.sonstige_therapie ) ) &&
	                    ( strlen( $therapie.sonstige_therapie ) > 0 ) ) }
	              <SONSTIGE_THERAPIE>{$therapie.sonstige_therapie}</SONSTIGE_THERAPIE>
	              {/if}
	              {if ( ( isset( $therapie.therapie_detail ) ) &&
	                    ( strlen( $therapie.therapie_detail ) > 0 ) ) }
	              <THERAPIE_DETAIL>{$therapie.therapie_detail}</THERAPIE_DETAIL>
	              {/if}
              {/if}
              {if isset( $therapie.ops_codes ) && count( $therapie.ops_codes ) > 0}
              <OPS_SCHLUESSEL>
              	{foreach from=$therapie.ops_codes item=ops_code }
                <SCHLUESSEL>{$ops_code.prozedur}</SCHLUESSEL>
              	{/foreach}
              </OPS_SCHLUESSEL>
              {/if}
              <THERAPIE_START>{$therapie.beginn}</THERAPIE_START>
              {if ( ( isset( $therapie.ende ) ) &&
	                  ( strlen( $therapie.ende ) > 0 ) ) }
              <THERAPIE_ENDE>{$therapie.ende}</THERAPIE_ENDE>
              {/if}
              {if ( ( isset( $therapie.abbruch ) ) &&
                    ( $therapie.abbruch == 'A' ) ) }
							<THERAPIEABBRUCH>{$therapie.abbruch}</THERAPIEABBRUCH>
              {/if} 
            </KLR>
          </THERAPIE>
        </MELDUNG>
        {/foreach}
        {foreach from=$patient.verlaeufe item=verlauf }
        <MELDUNG>
          <VERLAUF>
            <TAN>{$verlauf.tan}</TAN>
            <MELDUNGSKENNZEICHEN>{$verlauf.meldungskennzeichen}</MELDUNGSKENNZEICHEN>
            <KLR>
              <TUMORIDENTIFIKATOR>{$verlauf.tumoridentifikator}</TUMORIDENTIFIKATOR>
              <UNTERSUCHUNGSDATUM>{$verlauf.datum}</UNTERSUCHUNGSDATUM>
              <TUMORGESCHEHEN>{$verlauf.tumorgeschehen}</TUMORGESCHEHEN>
            </KLR>
          </VERLAUF>
        </MELDUNG>
        {/foreach}
        {foreach from=$patient.abschluesse item=abschluss }
        <MELDUNG>
          <ABSCHLUSS>
            <TAN>{$abschluss.tan}</TAN>
            <MELDUNGSKENNZEICHEN>{$abschluss.meldungskennzeichen}</MELDUNGSKENNZEICHEN>
            <KLR>
            	{if strlen( $abschluss.tumoridentifikator ) > 0}
              <TUMORIDENTIFIKATOR>{$abschluss.tumoridentifikator}</TUMORIDENTIFIKATOR>
              {/if}
              <ABSCHLUSSGRUND>{$abschluss.abschlussgrund}</ABSCHLUSSGRUND>
              {if ( ( 'T' == $abschluss.abschlussgrund ) && 
                    ( isset( $abschluss.sterbedatum ) ) &&
                    ( strlen( $abschluss.sterbedatum ) > 0 ) ) }
              <STERBEDATUM>{$abschluss.sterbedatum}</STERBEDATUM>
              {/if}
              {if ( ( 'T' == $abschluss.abschlussgrund ) && 
                    ( isset( $abschluss.tod_tumorbedingt ) ) &&
                    ( strlen( $abschluss.tod_tumorbedingt ) > 0 ) ) }
              <TOD_TUMORBEDINGT>{$abschluss.tod_tumorbedingt}</TOD_TUMORBEDINGT>
              {/if}
              {if ( ( 'L' == $abschluss.abschlussgrund ) && 
                    ( isset( $abschluss.letzte_patienteninformation ) ) &&
                    ( strlen( $abschluss.letzte_patienteninformation ) > 0 ) ) }
              <LETZTE_PATIENTENINFORMATION>{$abschluss.letzte_patienteninformation}</LETZTE_PATIENTENINFORMATION>
              {/if}
            </KLR>
          </ABSCHLUSS>
        </MELDUNG>
        {/foreach}
      </PATIENT>
      {/foreach}
    </MELDER>
  </ABSENDER>
</KRBW>