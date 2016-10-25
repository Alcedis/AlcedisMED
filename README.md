# AlcedisMED

- Aktuelle Version: 4.1.3

## Offizielle Seite

https://tumordokumentation.alcedis.de

### Spezifikationsdokumente für die Anwendung

https://tumordokumentation.alcedis.de/downloads/


## Übersicht
Alcedis MED bietet Ihnen ein breites Spektrum an Möglichkeiten:

 - Dokumentation aller Erkrankungen
 - Zertifizierungsrelevante Auswertungen nach OnkoZert/DKG für die meisten Entitäten (Brust, gynäkologische Tumoren, Prostata, Pankreas, Haut, Lunge, Kopf-Hals, onkologisches Zentrum)
 - OncoBox Darm, zertifiziert nach OnkoZert
 - ADT-GEKID Datensatz für die Meldung an das klinische Krebsregister
 - DMP Export, zertifiziert von der KBV (bis 30.06.2018)
 - Tumorkonferenz

## Systemvoraussetzungen

**Hardware:**
- 4 GB RAM
- mind. 50 GB HDD
- Dual Core CPU

**Software:**
- Linux basiertes OS
- PHP v5.6.x
- Apache Webserver *(current stable Release)*
- MySQL Datenbank *(current stable Release)*

## Webinar vom 05.10.2016 zum Thema: AlcedisMED Installation

https://youtu.be/3wzhyhhHhts

## Changelog

4.1.3:
    
    - Fix: 
         Krebsregister Export: 
         - Nutzten verschiedene User den Export, aktualisierte sich die Listenansicht der Patienten nicht. Fehlerbehebung/Nachdokumentation konnte nicht angezeigt werden.
         - Die Felder Menge_TNM/TNM/TNM_TMenge_TNM/TNM/TNM_NMenge_TNM/TNM/TNM_M Im Abschnitt "TNM" sind nur noch Pflicht bei soliden Tumoren AUSSER bei C44* (Sarkome).
           Bei Sarkomen gibt es kein TNM.
    
    - Feature: 
         Krebsregister Export: 
         - In der Patientenliste kann nun nach Erkrankung gefiltert werden.
         - Die Validierung des Felds "KrankenkassenNr." wurde enfernt. Es kann nun auch leer bleiben.
         - Der Name der Krankenkassen wird nun auch exportiert wenn die KrankenkassenNr. länger oder kürzer als 9 (Private Krankenvericherungen) Zeichen
           ist. Dadurch wird wenigstens der Name der Kasse mitgesendet, wenn die KassenNr. nicht dem XSD entspricht.

4.1.2: 

    - Feature "Krebsregister Export" hinzugefügt
