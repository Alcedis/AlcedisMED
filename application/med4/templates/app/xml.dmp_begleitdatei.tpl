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

<?xml version="1.0" encoding="ISO-8859-15"?>
<bgl:begleitdatei xmlns:bgl="http://www.kbv.de/ns/meta/2003-05-15" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.kbv.de/ns/meta/2003-05-15 ..\Schema\begleitdatei.xsd" Version="1.08">
	<bgl:erstellungsdatum-datei V="{$erstellungsdatum}"/>
	<bgl:empfaenger>
		<bgl:datenstelle EX="{$empfaenger_ik}" RT="Institutskennzeichen"/>
	</bgl:empfaenger>
	<bgl:absender>
		{if strlen( $bsnr )}
		<bgl:arzt EX="{$bsnr}" RT="BSNR"/>
		{else}
		<bgl:krankenhaus EX="{$klinik_ik}" RT="Krankenhaus-IK"/>
		{/if}
	</bgl:absender>
	<bgl:komprimierungssoftware>
		<bgl:software-name V="PhpConcept Library - Zip Module"/>
		<bgl:software-version V="2.1"/>
		<bgl:software-hersteller V="PhpConcept - Vincent Blavet"/>
		<bgl:software-link V="http://www.phpconcept.net"/>
	</bgl:komprimierungssoftware>
	<bgl:verschluesselungssoftware>
		<bgl:software-name V="XKM"/>
		<bgl:software-version V="{$xkm_version}"/>
		<bgl:software-hersteller V="KBV"/>
	</bgl:verschluesselungssoftware>
	<bgl:archive>
		<bgl:archiv>
			<bgl:name V="{$xkmfile}"/>
            <bgl:verzeichnis>
                <bgl:pfad V="Brustkrebs/Dokumentation"/>
                <bgl:zeitraum>
                    <bgl:von V="{$datum_von}"/>
                    <bgl:bis V="{$datum_bis}"/>
                </bgl:zeitraum>
            </bgl:verzeichnis>
		</bgl:archiv>
	</bgl:archive>
</bgl:begleitdatei>
