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

    <clinical_document_header>
        <id EX="{$data.header.dmp_dokument_id}" RT="{$data.header.bsnr}"/>
        <set_id EX="{$data.header.set_id}" RT="{$data.header.bsnr}"/>
        <version_nbr V="{$data.header.version_nbr}"/>
        <document_type_cd V="{$data.header.document.version}" S="1.2.276.0.76.5.100" SN="KBV" DN="{$data.header.document.description}"/>
        {if strlen($data.header.datum_unterschrift) > 0}
        <service_tmr V="{$data.header.datum_unterschrift}"/>
        {/if}
        <origination_dttm V="{$data.header.dokumentations_datum}"/>
        {if strlen($data.header.parent_dokument_id) > 0}
        <document_relationship>
            <document_relationship.type_cd V="RPLC"/>
            <related_document>
                <id EX="{$data.header.parent_dokument_id}" RT="{$data.header.parent_bsnr}"/>
            </related_document>
        </document_relationship>
        {/if}
        <provider>
            <provider.type_cd V="PRF"/>
            <function_cd V="{$data.header.provider.arzt.rolle}" S="1.2.276.0.76.5.105" SN="KBV" DN="{$data.header.provider.arzt.rollenbeschreibung}"/>
            <person>
                {if (strlen($data.header.provider.arzt.lanr) > 0) &&
                    (strlen($data.header.provider.arzt.bsnr) > 0)}
                <id EX="{$data.header.provider.arzt.lanr}" RT="LANR"/>
                <id EX="{$data.header.provider.arzt.bsnr}" RT="BSNR"/>
                {/if}
                {if strlen( $data.header.provider.org.iknr ) > 0}
                <id EX="{$data.header.provider.org.iknr}" RT="Krankenhaus-IK"/>
                {/if}
                <person_name>
                    <nm>
                        {if strlen( $data.header.provider.arzt.vorname ) > 0}
                        <GIV V="{$data.header.provider.arzt.vorname}"/>
                        {/if}
                        <FAM V="{$data.header.provider.arzt.nachname}"/>
                        {if strlen($data.header.provider.arzt.titel_ac)}
                        <PFX V="{$data.header.provider.arzt.titel_ac}" QUAL="AC"/>
                        {/if}
                        {if strlen($data.header.provider.arzt.titel_nb)}
                        <PFX V="{$data.header.provider.arzt.titel_nb}" QUAL="NB"/>
                        {/if}
                    </nm>
                </person_name>
                <addr>
                    {if strlen( $data.header.provider.org.name ) > 0}
                    <ADL V="{$data.header.provider.org.name}"/>
                    {/if}
                    {if strlen( $data.header.provider.org.fachabteilung ) > 0}
                    <ADL V="{$data.header.provider.org.fachabteilung}"/>
                    {/if}
                    {if strlen( $data.header.provider.org.strasse ) > 0}
                    <STR V="{$data.header.provider.org.strasse}"/>
                    {/if}
                    {if strlen( $data.header.provider.org.hausnr ) > 0}
                    <HNR V="{$data.header.provider.org.hausnr}"/>
                    {/if}
                    <ZIP V="{$data.header.provider.org.plz}"/>
                    <CTY V="{$data.header.provider.org.ort}"/>
                </addr>
                {if strlen( $data.header.provider.arzt.telefon ) > 0}
                <telecom V="{$data.header.provider.arzt.telefon}" USE="WP"/>
                {/if}
                {if strlen( $data.header.provider.arzt.telefax ) > 0}
                <telecom V="{$data.header.provider.arzt.telefax}" USE="WP"/>
                {/if}
                {if strlen( $data.header.provider.arzt.email ) > 0}
                <telecom V="{$data.header.provider.arzt.email}" USE="WP"/>
                {/if}
                {if strlen( $data.header.provider.org.website ) > 0}
                <telecom V="{$data.header.provider.org.website}" USE="WP"/>
                {/if}
            </person>
        </provider>
        <patient>
            <patient.type_cd V="PATSBJ"/>
            <person>
                <id EX="{$data.header.provider.patient.fall_nr}" RT="{$data.header.bsnr}"/>
                <person_name>
                    <nm>
                        <GIV V="{$data.header.provider.patient.vorname}"/>
                        <FAM V="{$data.header.provider.patient.nachname}"/>
                        {if strlen( $data.header.provider.patient.titel_ac ) > 0}
                        <PFX V="{$data.header.provider.patient.titel_ac}" QUAL="AC"/>
                        {/if}
                        {if strlen( $data.header.provider.patient.titel_nb ) > 0}
                        <PFX V="{$data.header.provider.patient.titel_nb}" QUAL="NB"/>
                        {/if}
                    </nm>
                </person_name>
                <addr>
                    {if strlen( $data.header.provider.patient.strasse ) > 0}
                    <STR V="{$data.header.provider.patient.strasse}"/>
                    {/if}
                    {if strlen( $data.header.provider.patient.hausnr ) > 0}
                    <HNR V="{$data.header.provider.patient.hausnr}"/>
                    {/if}
                    <ZIP V="{$data.header.provider.patient.plz}"/>
                    <CTY V="{$data.header.provider.patient.ort}"/>
                </addr>
            </person>
            <birth_dttm V="{$data.header.provider.patient.geburtsdatum}"/>
            <administrative_gender_cd V="{$data.header.provider.patient.geschlecht}" S="2.16.840.1.113883.5.1"/>
            <local_header ignore="all" descriptor="sciphox">
                <sciphox:sciphox-ssu type="insurance" country="de" version="v2">
                    <sciphox:GesetzlicheKrankenversicherung>
                        <sciphox:Kostentraegerbezeichnung V="{$data.header.provider.krankenkasse.name}"/>
                        <sciphox:KrankenkassennummerIK V="{$data.header.provider.krankenkasse.iknr}"/>
                        <sciphox:KostentraegerAbrechnungsbereich V="{$data.header.provider.krankenkasse.abrechnungsbereich}" S="2.16.840.1.113883.3.7.1.16"/>
                        {if strlen( $data.header.provider.krankenkasse.kv_bereich ) > 0}
                        <sciphox:KVBereich V="{$data.header.provider.krankenkasse.kv_bereich}" S="2.16.840.1.113883.3.7.1.17"/>
                        {/if}
                        <sciphox:AbrechnungsVKNR V="{$data.header.provider.krankenkasse.vknr}" S="AbrechnungsVKNR"/>
                        {if (strlen($data.header.provider.krankenkasse.name) > 0) &&
                            ($data.header.provider.krankenkasse.gkv == 0)}
                        <sciphox:SKTZusatzangabe V="{$data.header.provider.krankenkasse.name}"/>
                        {/if}
                        <sciphox:Versichertennummer V="{$data.header.provider.patient.versich_nr}"/>
                        {if strlen( $data.header.provider.patient.versich_status ) > 0}
                        <sciphox:VersichertenstatusKVK V="{$data.header.provider.patient.versich_status}" S="2.16.840.1.113883.3.7.1.2"/>
                        {/if}
                        {if strlen( $data.header.provider.patient.statusergaenzung ) > 0}
                        <sciphox:Statusergaenzung V="{$data.header.provider.patient.statusergaenzung}" S="2.16.840.1.113883.3.7.1.3"/>
                        {/if}
                        {if strlen( $data.header.provider.patient.vk_gueltig_bis ) > 0}
                        <sciphox:BisDatumderGueltigkeit V="{$data.header.provider.patient.vk_gueltig_bis}"/>
                        {/if}
                        {if strlen( $data.header.provider.patient.kvk_einlesedatum ) > 0}
                        <sciphox:KVKEinlesedatum V="{$data.header.provider.patient.kvk_einlesedatum}"/>
                        {/if}
                    </sciphox:GesetzlicheKrankenversicherung>
                </sciphox:sciphox-ssu>
            </local_header>
        </patient>
        <local_header ignore="all" descriptor="sciphox">
            <sciphox:sciphox-ssu type="software" country="de" version="v1">
                <sciphox:Software>
                    <sciphox:id EX="{$data.header.kbv_pruefnummer}" RT="KBV-Prüfnummer"/>
                    <sciphox:SoftwareName V="{$data.header.software.name}"/>
                    <sciphox:SoftwareVersion V="{$data.header.software.version}"/>
                    <sciphox:SoftwareTyp V="PVS"/>
                    <sciphox:Kontakt>
                        <sciphox:Kontakttyp V="SOFTV" S="1.2.276.0.76.3.1.1.5.2.3" DN="Softwareverantwortlicher"/>
                        <organization.nm V="{$data.header.software.org_name}"/>
                        <person_name>
                            <nm>
                                <GIV V="{$data.header.software.produkt_verantwortlicher_vorname}"/>
                                <FAM V="{$data.header.software.produkt_verantwortlicher_nachname}"/>
                            </nm>
                        </person_name>
                        <addr>
                            {if strlen( $data.header.software.org_strasse ) > 0}
                            <STR V="{$data.header.software.org_strasse}"/>
                            {/if}
                            {if strlen( $data.header.software.org_hausnr ) > 0}
                            <HNR V="{$data.header.software.org_hausnr}"/>
                            {/if}
                            <ZIP V="{$data.header.software.org_plz}"/>
                            <CTY V="{$data.header.software.org_ort}"/>
                        </addr>
                        <telecom V="{$data.header.software.org_tel}" USE="WP"/>
                        <telecom V="{$data.header.software.org_fax}" USE="WP"/>
                        <telecom V="{$data.header.software.org_email_support}" USE="WP"/>
                        <telecom V="{$data.header.software.org_web}" USE="WP"/>
                    </sciphox:Kontakt>
                    <sciphox:Software>
                        <sciphox:SoftwareName V="XSD_BK"/>
                        <sciphox:SoftwareVersion V="{$data.header.xsd_software_version}"/>
                        <sciphox:SoftwareTyp V="XSD" DN="XML-Schema"/>
                        <sciphox:Software>
                            <sciphox:SoftwareName V="XPM_BK"/>
                            <sciphox:SoftwareVersion V="{$data.header.xpm_software_version}"/>
                            <sciphox:SoftwareTyp V="XPM" DN="XML-Pruefmodul"/>
                        </sciphox:Software>
                    </sciphox:Software>
                </sciphox:Software>
            </sciphox:sciphox-ssu>
        </local_header>
    </clinical_document_header>
