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

<?xml version="1.0" encoding="ISO-8859-15" standalone="yes"?>
<levelone   xmlns="urn::hl7-org/cda"
            xmlns:sciphox="urn::sciphox-org/sciphox"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <clinical_document_header>
      <id EX="{$patient.0.dmp_dokument_id}" RT="{if strlen($arzt.0.bsnr)}{$arzt.0.bsnr}{else}{$patient.0.iknr}{/if}"/>
      <set_id EX="{$patient.0.set_id}" RT="{if strlen($arzt.0.bsnr)}{$arzt.0.bsnr}{else}{$patient.0.iknr}{/if}"/>
      <version_nbr V="{$patient.0.version_nbr}"/>
      <document_type_cd V="DMP_022A" S="1.2.276.0.76.5.100" SN="KBV" DN="Folge-Dokumentation Brustkrebs"/>
      {if strlen($patient.0.unterschrift_datum)}<service_tmr V="{$patient.0.unterschrift_datum}"/>{/if}
      <origination_dttm V="{$patient.0.doku_datum}"/>
      {if $patient.0.version_nbr != 1}
         <document_relationship>
            <document_relationship.type_cd V="RPLC"/>
            <related_document>
               <id EX="{$patient.0.related_document_id}" RT="{if strlen($arzt.0.bsnr)}{$arzt.0.bsnr}{else}{$patient.0.iknr}{/if}"/>
            </related_document>
         </document_relationship>
      {/if}
      <provider>
         <provider.type_cd V="PRF"/>
         {if $patient.0.arztwechsel == '1'}<function_cd V="ARZTW" S="1.2.276.0.76.5.105" SN="KBV" DN="Arztwechsel"/>{/if}
         <person>
            {if strlen($arzt.0.bsnr)}
               <id EX="{$arzt.0.lanr}" RT="LANR"/>
               <id EX="{$arzt.0.bsnr}" RT="BSNR"/>
            {else}
               <id EX="{$patient.0.iknr}" RT="Krankenhaus-IK"/>
            {/if}
            <person_name>
               <nm>
                  {if strlen($arzt.0.vorname)}
                     <GIV V="{$arzt.0.vorname}"/>
                  {/if}
                  <FAM V="{$arzt.0.nachname}"/>
                  {if strlen($arzt.0.titel)}
                     <PFX V="{$arzt.0.titel}" QUAL="AC"/>
                  {/if}
               </nm>
            </person_name>
            <addr>
               {if strlen($patient.0.org_name)}
                  <ADL V="{$patient.0.org_name}"/>
               {/if}
               {if strlen($patient.0.org_zusatz)}
                  <ADL V="{$patient.0.org_zusatz}"/>
               {/if}
               {if strlen($patient.0.org_strasse)}
                  <STR V="{$patient.0.org_strasse}"/>
               {/if}
               {if strlen($patient.0.org_hausnr)}
                  <HNR V="{$patient.0.org_hausnr}"/>
               {/if}
               <ZIP V="{$patient.0.org_plz}"/>
               <CTY V="{$patient.0.org_ort}"/>
            </addr>
            {if strlen($arzt.0.telefon)}
               <telecom V="tel:{$arzt.0.telefon}" USE="WP"/>
            {/if}
            {if strlen($arzt.0.telefax)}
               <telecom V="fax:{$arzt.0.telefax}" USE="WP"/>
            {/if}
            {if strlen($arzt.0.email)}
               <telecom V="mailto:{$arzt.0.email}" USE="WP"/>
            {/if}
            {if strlen($patient.0.org_homepage)}
               <telecom V="http:{$patient.0.org_homepage}" USE="WP"/>
            {/if}
         </person>
      </provider>
      <patient>
         <patient.type_cd V="PATSBJ"/>
         <person>
            <id EX="{$patient.0.fall_nr}" RT="{if strlen($arzt.0.bsnr)}{$arzt.0.bsnr}{else}{$patient.0.iknr}{/if}"/>
            <person_name>
               <nm>
                  <GIV V="{$patient.0.vorname}"/>
                  <FAM V="{$patient.0.nachname}"/>
                  {if strlen($patient.0.titel)}
                     <PFX V="{$patient.0.titel}" QUAL="AC"/>
                  {/if}
               </nm>
            </person_name>
            <addr>
               {if strlen($patient.0.strasse)}
                  <STR V="{$patient.0.strasse}"/>
               {/if}
               {if strlen($patient.0.hausnr)}
                  <HNR V="{$patient.0.hausnr}"/>
               {/if}
               <ZIP V="{$patient.0.plz}"/>
               <CTY V="{$patient.0.ort}"/>
            </addr>
         </person>
         <birth_dttm V="{$patient.0.geburtsdatum}"/>
         <administrative_gender_cd V="{$patient.0.geschlecht}" S="2.16.840.1.113883.5.1"/>
         <local_header ignore="all" descriptor="sciphox">
            <sciphox:sciphox-ssu type="insurance" country="de" version="v2">
               <sciphox:GesetzlicheKrankenversicherung>
                  {if strlen($patient.0.kk_name)}<sciphox:Kostentraegerbezeichnung V="{$patient.0.kk_name}"/>{/if}
                  {if strlen($patient.0.kk_kassen_nr)}<sciphox:KrankenkassennummerIK V="{$patient.0.kk_kassen_nr}"/>{/if}
                  {if strlen($patient.0.kk_kassen_nr)}<sciphox:KostentraegerAbrechnungsbereich V="00" S="2.16.840.1.113883.3.7.1.16"/>{/if}
                  {if strlen($patient.0.kv_bereich)}<sciphox:KVBereich V="{$patient.0.kv_bereich}" S="2.16.840.1.113883.3.7.1.17"/>{/if}
                  {if strlen($patient.0.kk_vknr)}<sciphox:AbrechnungsVKNR V="{$patient.0.kk_vknr}" S="AbrechnungsVKNR"/>{/if}
                  {if $patient.0.gkv != '1' && strlen($patient.0.kk_name)}<sciphox:SKTZusatzangabe V="{$patient.0.kk_name}"/>{/if}
                  {if strlen($patient.0.versich_nr)}<sciphox:Versichertennummer V="{$patient.0.versich_nr}"/>{/if}
                  {if strlen($patient.0.versich_status)}<sciphox:VersichertenstatusKVK V="{$patient.0.versich_status}" S="2.16.840.1.113883.3.7.1.2"/>{/if}
                  {if strlen($patient.0.statusergaenzung)}<sciphox:Statusergaenzung V="{$patient.0.statusergaenzung}" S="2.16.840.1.113883.3.7.1.3"/>{/if}
                  {if strlen($patient.0.vk_gueltig_bis)}<sciphox:BisDatumderGueltigkeit V="{$patient.0.vk_gueltig_bis}"/>{/if}
                  {if strlen($patient.0.kvk_einlesedatum)}<sciphox:KVKEinlesedatum V="{$patient.0.kvk_einlesedatum}"/>{/if}
               </sciphox:GesetzlicheKrankenversicherung>
            </sciphox:sciphox-ssu>
         </local_header>
      </patient>
      <local_header ignore="all" descriptor="sciphox">
         <sciphox:sciphox-ssu type="software" country="de" version="v1">
            <sciphox:Software>
               <sciphox:id EX="{$kbv_pruefnummer}" RT="KBV-Prüfnummer"/>
               <sciphox:SoftwareName V="{$produkt.software_title}"/>
               <sciphox:SoftwareVersion V="{$produkt.software_version}"/>
               <sciphox:SoftwareTyp V="PVS"/>
               <sciphox:Kontakt>
                  <sciphox:Kontakttyp V="SOFTV" S="1.2.276.0.76.3.1.1.5.2.3" DN="Softwareverantwortlicher"/>
                  <organization.nm V="{$organisation.org_name}"/>
                  <person_name>
                     <nm>
                        <GIV V="{$produkt.produkt_verantwortlicher_vorname}"/>
                        <FAM V="{$produkt.produkt_verantwortlicher_nachname}"/>
                     </nm>
                  </person_name>
                  <addr>
                     <STR V="{$organisation.org_strasse}"/>
                     <HNR V="{$organisation.org_hausnr}"/>
                     <ZIP V="{$organisation.org_plz}"/>
                     <CTY V="{$organisation.org_ort}"/>
                  </addr>
                  <telecom V="tel:{$organisation.org_tel}" USE="WP"/>
                  <telecom V="fax:{$organisation.org_fax}" USE="WP"/>
                  <telecom V="mailto:{$organisation.org_email_support}" USE="WP"/>
                  <telecom V="{$organisation.org_web}" USE="WP"/>
               </sciphox:Kontakt>
               <sciphox:Software>
                  <sciphox:SoftwareName V="XSD_BK"/>
                  <sciphox:SoftwareVersion V="{$xsd_software_version}"/>
                  <sciphox:SoftwareTyp V="XSD"/>
                  <sciphox:Software>
                     <sciphox:SoftwareName V="XPM_BK"/>
                     <sciphox:SoftwareVersion V="{$xpm_software_version}"/>
                     <sciphox:SoftwareTyp V="XPM" DN="XML-Pruefmodul"/>
                  </sciphox:Software>
               </sciphox:Software>
            </sciphox:Software>
         </sciphox:sciphox-ssu>
      </local_header>
   </clinical_document_header>
   <body>
      <section>
         {if count($einschreibung) > 0}
            <paragraph>
               <caption>
                  <caption_cd DN="Einschreibung erfolgte wegen"/>
               </caption>
               <content>
                  <local_markup descriptor="sciphox" ignore="all">
                     <sciphox:sciphox-ssu country="de" version="v1">
                        <sciphox:Beobachtungen>
                           <sciphox:Beobachtung>
                              <sciphox:Parameter DN="Einschreibung erfolgte wegen"/>
                              <sciphox:Ergebnistext V="{$einschreibung.0.einschreibung_grund}"/>
                           </sciphox:Beobachtung>
                        </sciphox:Beobachtungen>
                     </sciphox:sciphox-ssu>
                  </local_markup>
               </content>
            </paragraph>
         {/if}
         {if count($behandlung_primaer) > 0}
            <paragraph>
               <caption>
                  <caption_cd DN="Behandlungsstatus nach operativer Therapie Primärtumor/kontralateraler Brustkrebs"/>
               </caption>
               <content>
                  <local_markup descriptor="sciphox" ignore="all">
                     <sciphox:sciphox-ssu country="de" version="v1">
                        <sciphox:Beobachtungen>
                              {foreach from=$behandlung_primaer item=beobachtung}
                                 <sciphox:Beobachtung>
                                    <sciphox:Parameter DN="{$beobachtung.parameter}"/>
                                    {if is_array($beobachtung.ergebnistext) }
                                       {foreach from=$beobachtung.ergebnistext item=ergebnistext}
                                          <sciphox:Ergebnistext V="{$ergebnistext}"/>
                                       {/foreach}
                                    {else}
                                       <sciphox:Ergebnistext V="{$beobachtung.ergebnistext}"/>
                                    {/if}
                                 </sciphox:Beobachtung>
                              {/foreach}
                        </sciphox:Beobachtungen>
                     </sciphox:sciphox-ssu>
                  </local_markup>
               </content>
            </paragraph>
         {/if}
         {if count($neue_ereignisse) > 0}
            <paragraph>
               <caption>
                  <caption_cd DN="Seit der letzten Dokumentation neu aufgetretene Ereignisse"/>
               </caption>
               <content>
                  <local_markup descriptor="sciphox" ignore="all">
                     <sciphox:sciphox-ssu country="de" version="v1">
                        <sciphox:Beobachtungen>
                           {foreach from=$neue_ereignisse item=beobachtung}
                             <sciphox:Beobachtung>
                                 <sciphox:Parameter DN="{$beobachtung.parameter}"/>
                                 {if is_array($beobachtung.ergebnistext) }
                                    {foreach from=$beobachtung.ergebnistext item=ergebnistext}
                                       <sciphox:Ergebnistext V="{$ergebnistext}"/>
                                    {/foreach}
                                 {elseif strlen($beobachtung.ergebnistext)}
                                    <sciphox:Ergebnistext V="{$beobachtung.ergebnistext}"/>
                                 {/if}
                                 {if strlen($beobachtung.zeitpunkt_dttm)}
                                    <sciphox:Zeitpunkt_dttm V="{$beobachtung.zeitpunkt_dttm}"/>
                                 {/if}
                              </sciphox:Beobachtung>
                           {/foreach}
                        </sciphox:Beobachtungen>
                     </sciphox:sciphox-ssu>
                  </local_markup>
               </content>
            </paragraph>
         {/if}
         {if count($behandlung_progress) > 0}
         <paragraph>
            <caption>
               <caption_cd DN="Behandlung bei fortgeschrittener Erkrankung (lokoregionäres Rezidiv/Fernmetastasen)"/>
            </caption>
            <content>
               <local_markup descriptor="sciphox" ignore="all">
                  <sciphox:sciphox-ssu country="de" version="v1">
                     <sciphox:Beobachtungen>
                        {foreach from=$behandlung_progress item=beobachtung}
                          <sciphox:Beobachtung>
                              <sciphox:Parameter DN="{$beobachtung.parameter}"/>
                              {if is_array($beobachtung.ergebnistext) }
                                 {foreach from=$beobachtung.ergebnistext item=ergebnistext}
                                    <sciphox:Ergebnistext V="{$ergebnistext}"/>
                                 {/foreach}
                              {elseif strlen($beobachtung.ergebnistext)}
                                 <sciphox:Ergebnistext V="{$beobachtung.ergebnistext}"/>
                              {/if}
                           </sciphox:Beobachtung>
                        {/foreach}
                     </sciphox:Beobachtungen>
                  </sciphox:sciphox-ssu>
               </local_markup>
            </content>
         </paragraph>
         {/if}
         {if count($sonstige) > 0}
            <paragraph>
               <caption>
                  <caption_cd DN="Sonstige Beratung und Behandlung"/>
               </caption>
               <content>
                  <local_markup descriptor="sciphox" ignore="all">
                     <sciphox:sciphox-ssu country="de" version="v1" type="observation">
                        <sciphox:Beobachtungen>
                           {foreach from=$sonstige item=beobachtung}
                              <sciphox:Beobachtung>
                                 <sciphox:Parameter DN="{$beobachtung.parameter}"/>
                                 {if strlen($beobachtung.ergebnistext)}
                                    {if is_array($beobachtung.ergebnistext) }
                                       {foreach from=$beobachtung.ergebnistext item=ergebnistext}
                                          <sciphox:Ergebnistext V="{$ergebnistext}"/>
                                       {/foreach}
                                    {else}
                                       <sciphox:Ergebnistext V="{$beobachtung.ergebnistext}"/>
                                    {/if}
                                 {/if}
                                 {if strlen($beobachtung.zeitpunkt_dttm)}
                                    {if is_array($beobachtung.zeitpunkt_dttm) }
                                       {foreach from=$beobachtung.zeitpunkt_dttm item=zeitpunkt_dttm}
                                          <sciphox:Zeitpunkt_dttm V="{$zeitpunkt_dttm}"/>
                                       {/foreach}
                                    {else}
                                       <sciphox:Zeitpunkt_dttm V="{$beobachtung.zeitpunkt_dttm}"/>
                                    {/if}
                                 {/if}
                              </sciphox:Beobachtung>
                           {/foreach}
                        </sciphox:Beobachtungen>
                     </sciphox:sciphox-ssu>
                  </local_markup>
               </content>
            </paragraph>
         {/if}
      </section>
   </body>
</levelone>
