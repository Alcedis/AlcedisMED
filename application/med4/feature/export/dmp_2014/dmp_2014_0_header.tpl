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
        {if (strlen($data.header.version_nbr) > 0 ) }
        <version_nbr V="{$data.header.version_nbr}"/>
        {/if}
        {if (strlen($data.header.document.version) > 0) }
        <document_type_cd V="{$data.header.document.version}" S="1.2.276.0.76.5.100" SN="KBV" DN="{$data.header.document.description}"/>
        {/if}
        {if strlen($data.header.datum_unterschrift) > 0}
        <service_tmr V="{$data.header.datum_unterschrift}"/>
        {/if}
        {if (strlen($data.header.dokumentations_datum) > 0 ) }
        <origination_dttm V="{$data.header.dokumentations_datum}"/>
        {/if}
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
                {elseif strlen( $data.header.provider.org.iknr ) > 0}
                <id EX="{$data.header.provider.org.iknr}" RT="Krankenhaus-IK"/>
                {/if}
                <person_name>
                    <nm>
                        {if strlen( $data.header.provider.arzt.vorname ) > 0}
                        <GIV V="{$data.header.provider.arzt.vorname}"/>
                        {/if}
                        {if (strlen($data.header.provider.arzt.nachname) > 0 ) }
                        <FAM V="{$data.header.provider.arzt.nachname}"/>
                        {/if}
                        {if strlen($data.header.provider.arzt.titel_ac) > 0}
                        <PFX V="{$data.header.provider.arzt.titel_ac}" QUAL="AC"/>
                        {/if}
                        {if strlen($data.header.provider.arzt.titel_nb) > 0}
                        <PFX V="{$data.header.provider.arzt.titel_nb}" QUAL="NB"/>
                        {/if}
                    </nm>
                </person_name>
                <addr USE="PHYS">
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
                    {if strlen( $data.header.provider.org.plz ) > 0}
                    <ZIP V="{$data.header.provider.org.plz}"/>
                    {/if}
                    {if (strlen($data.header.provider.org.ort) > 0 ) }
                    <CTY V="{$data.header.provider.org.ort}"/>
                    {/if}
                    {if strlen($data.header.provider.org.postfach) > 0}
                    <POB V="{$data.header.provider.org.postfach}"/>
                    {/if}
                    {if strlen($data.header.provider.org.cnt) > 0}
                    <CNT V="{$data.header.provider.org.cnt}"/>
                    {/if}
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
                        {if strlen($data.header.provider.patient.vorname) > 0}
                        <GIV V="{$data.header.provider.patient.vorname}"/>
                        {/if}
                        {if (strlen($data.header.provider.patient.nachname) > 0 )}
                        <FAM V="{$data.header.provider.patient.nachname}"/>
                        {/if}
                        {if strlen( $data.header.provider.patient.titel_ac ) > 0}
                        <PFX V="{$data.header.provider.patient.titel_ac}" QUAL="AC"/>
                        {/if}
                        {if strlen( $data.header.provider.patient.titel_nb ) > 0}
                        <PFX V="{$data.header.provider.patient.titel_nb}" QUAL="NB"/>
                        {/if}
                    </nm>
                </person_name>
                <addr USE="PHYS">
                    {if strlen( $data.header.provider.patient.adrzusatz ) > 0}
                    <ADL V="{$data.header.provider.patient.adrzusatz}"/>
                    {/if}
                    {if strlen( $data.header.provider.patient.strasse ) > 0}
                    <STR V="{$data.header.provider.patient.strasse}"/>
                    {/if}
                    {if strlen( $data.header.provider.patient.hausnr ) > 0}
                    <HNR V="{$data.header.provider.patient.hausnr}"/>
                    {/if}
                    {if strlen($data.header.provider.patient.plz) > 0}
                    <ZIP V="{$data.header.provider.patient.plz}"/>
                    {/if}
                    {if (strlen($data.header.provider.patient.ort) > 0 ) }
                    <CTY V="{$data.header.provider.patient.ort}"/>
                    {/if}
                    {if strlen($data.header.provider.patient.postfach) > 0}
                    <POB V="{$data.header.provider.patient.postfach}"/>
                    {/if}
                    {if strlen($data.header.provider.patient.cnt) > 0}
                    <CNT V="{$data.header.provider.patient.cnt}"/>
                    {/if}
                </addr>
            </person>
            {if (strlen($data.header.provider.patient.geburtsdatum) > 0 ) }
            <birth_dttm V="{$data.header.provider.patient.geburtsdatum}"/>
            {/if}
            {if (strlen($data.header.provider.patient.geschlecht) > 0 ) }
            <administrative_gender_cd V="{$data.header.provider.patient.geschlecht}" S="2.16.840.1.113883.5.1"/>
            {/if}
            <local_header ignore="all" descriptor="sciphox">
                <sciphox:sciphox-ssu type="insurance" country="de" version="v3">
                    <sciphox:GesetzlicheKrankenversicherung>
                        {if (strlen($data.header.provider.krankenkasse.name) > 0 ) }
                        <sciphox:Kostentraegerbezeichnung V="{$data.header.provider.krankenkasse.name}"/>
                        {/if}
                        {if (strlen($data.header.provider.krankenkasse.iknr) > 0 ) }
                        <sciphox:Kostentraegerkennung V="{$data.header.provider.krankenkasse.iknr}"/>
                        {/if}
                        {if (strlen($data.header.provider.krankenkasse.abrechnungsbereich) > 0 ) }
                        <sciphox:KostentraegerAbrechnungsbereich V="{$data.header.provider.krankenkasse.abrechnungsbereich}" S="2.16.840.1.113883.3.7.1.16"/>
                        {/if}
                        {if strlen($data.header.provider.patient.kv_wop) > 0}
                        <sciphox:WOP V="{$data.header.provider.patient.kv_wop}" S="2.16.840.1.113883.3.7.1.17"/>
                        {/if}
                        {if strlen($data.header.provider.krankenkasse.vknr) > 0}
                        <sciphox:AbrechnungsVKNR V="{$data.header.provider.krankenkasse.vknr}" S="AbrechnungsVKNR"/>
                        {/if}
                        {if (strlen($data.header.provider.krankenkasse.name) > 0) && ($data.header.provider.krankenkasse.gkv == 0)}
                        <sciphox:SKTZusatzangabe V="{$data.header.provider.krankenkasse.name}"/>
                        {/if}
                        {if (strlen($data.header.provider.patient.versich_nr) > 0 ) }
                        <sciphox:Versichertennummer V="{$data.header.provider.patient.versich_nr}"/>
                        {/if}
                        {if (strlen($data.header.provider.patient.kv_versichertenstatus) > 0 ) }
                        <sciphox:Versichertenart V="{$data.header.provider.patient.kv_versichertenstatus}" S="2.16.840.1.113883.3.7.1.1"/>
                        {/if}
                        {if strlen($data.header.provider.patient.besonderePersonengruppe) > 0}
                        <sciphox:BesonderePersonengruppe V="{$data.header.provider.patient.besonderePersonengruppe}" S="1.2.276.0.76.5.222"/>
                        {/if}
                        {if strlen($data.header.provider.patient.dmp_kennzeichnung) > 0}
                        <sciphox:DMP_Kennzeichnung V="{$data.header.provider.patient.dmp_kennzeichnung}" S="1.2.276.0.76.5.223"/>
                        {/if}
                        {if strlen($data.header.provider.patient.kv_versicherungsschutz_beginn) > 0}
                        <sciphox:VersicherungsschutzBeginn V="{$data.header.provider.patient.kv_versicherungsschutz_beginn}"/>
                        {/if}
                        {if strlen($data.header.provider.patient.kv_versicherungsschutz_ende) > 0}
                        <sciphox:VersicherungsschutzEnde V="{$data.header.provider.patient.kv_versicherungsschutz_ende}"/>
                        {/if}
                        {if strlen($data.header.provider.patient.kv_einlesedatum) > 0}
                        <sciphox:Einlesedatum V="{$data.header.provider.patient.kv_einlesedatum}"/>
                        {/if}
                    </sciphox:GesetzlicheKrankenversicherung>
                </sciphox:sciphox-ssu>
            </local_header>
        </patient>
        <local_header ignore="all" descriptor="sciphox">
            <sciphox:sciphox-ssu type="software" country="de" version="v1">
                <sciphox:Software>
                    <sciphox:id EX="{$data.header.kbv_pruefnummer}" RT="KBV-Prüfnummer"/>
                    {if (strlen($data.header.software.name) > 0 ) }
                    <sciphox:SoftwareName V="{$data.header.software.name}"/>
                    {/if}
                    {if (strlen($data.header.software.version) > 0 ) }
                    <sciphox:SoftwareVersion V="{$data.header.software.version}"/>
                    {/if}
                    <sciphox:SoftwareTyp V="PVS"/>
                    <sciphox:Kontakt>
                        <sciphox:Kontakttyp V="SOFTV" S="1.2.276.0.76.3.1.1.5.2.3" DN="Softwareverantwortlicher"/>
                        {if (strlen($data.header.software.org_name) > 0 ) }
                        <organization.nm V="{$data.header.software.org_name}"/>
                        {/if}
                        {if (strlen($data.header.software.produkt_verantwortlicher_nachname) > 0 ) }
                        <person_name>
                            <nm>
                                {if (strlen($data.header.software.produkt_verantwortlicher_vorname) > 0 ) }
                                <GIV V="{$data.header.software.produkt_verantwortlicher_vorname}"/>
                                {/if}
                                <FAM V="{$data.header.software.produkt_verantwortlicher_nachname}"/>
                            </nm>
                        </person_name>
                        {/if}
                        <addr>
                            {if strlen( $data.header.software.org_strasse ) > 0}
                            <STR V="{$data.header.software.org_strasse}"/>
                            {/if}
                            {if strlen( $data.header.software.org_hausnr ) > 0}
                            <HNR V="{$data.header.software.org_hausnr}"/>
                            {/if}
                            {if (strlen($data.header.software.org_plz) > 0 ) }
                            <ZIP V="{$data.header.software.org_plz}"/>
                            {/if}
                            {if (strlen($data.header.software.org_ort) > 0 ) }
                            <CTY V="{$data.header.software.org_ort}"/>
                            {/if}
                        </addr>
                        {if (strlen($data.header.software.org_tel) > 0 ) }
                        <telecom V="{$data.header.software.org_tel}" USE="WP"/>
                        {/if}
                        {if (strlen($data.header.software.org_fax) > 0 ) }
                        <telecom V="{$data.header.software.org_fax}" USE="WP"/>
                        {/if}
                        {if (strlen($data.header.software.org_email_support) > 0 ) }
                        <telecom V="{$data.header.software.org_email_support}" USE="WP"/>
                        {/if}
                        {if (strlen($data.header.software.org_web) > 0 ) }
                        <telecom V="{$data.header.software.org_web}" USE="WP"/>
                        {/if}
                    </sciphox:Kontakt>
                    <sciphox:Software>
                        <sciphox:SoftwareName V="XSD_BK"/>
                        {if (strlen($data.header.xsd_software_version) > 0 ) }
                        <sciphox:SoftwareVersion V="{$data.header.xsd_software_version}"/>
                        {/if}
                        <sciphox:SoftwareTyp V="XSD" DN="XML-Schema"/>
                        <sciphox:Software>
                            <sciphox:SoftwareName V="XPM_BK"/>
                            {if (strlen($data.header.xpm_software_version) > 0 ) }
                            <sciphox:SoftwareVersion V="{$data.header.xpm_software_version}"/>
                            {/if}
                            <sciphox:SoftwareTyp V="XPM" DN="XML-Pruefmodul"/>
                        </sciphox:Software>
                    </sciphox:Software>
                </sciphox:Software>
            </sciphox:sciphox-ssu>
        </local_header>
    </clinical_document_header>
