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
<Patienten>
    <InfoXML>
        <DatumXML>{$data.infoXml.DatumXML}</DatumXML>
        <NameTudokusys>{$data.infoXml.NameTudokusys}</NameTudokusys>
        <VersionTudokusys>{$data.infoXml.VersionTudokusys}</VersionTudokusys>
    </InfoXML>
    {foreach from=$data.patients item=patient}
    <Patient>
        <Stammdaten>
            <PatientID>{$patient.stammdaten.PatientID}</PatientID>
            <GeburtsJahr>{$patient.stammdaten.GeburtsJahr}</GeburtsJahr>
            <GeburtsMonat>{$patient.stammdaten.GeburtsMonat}</GeburtsMonat>
            <GeburtsTag>{$patient.stammdaten.GeburtsTag}</GeburtsTag>
            <Geschlecht>{$patient.stammdaten.Geschlecht}</Geschlecht>
            <EinwilligungTumordoku>{$patient.stammdaten.EinwilligungTumordoku}</EinwilligungTumordoku>
            <EinwilligungExterneStelle>{$patient.stammdaten.EinwilligungExterneStelle}</EinwilligungExterneStelle>
        </Stammdaten>
        {foreach from=$patient.cases item=case}
        <Fall>
            <Anamnese>
                <RelevanteKrebsvorerkrankungen>{$case.anamnese.RelevanteKrebsvorerkrankungen}</RelevanteKrebsvorerkrankungen>
                <JahrRelevanteKrebsvorerkrankungen>{$case.anamnese.JahrRelevanteKrebsvorerkrankungen}</JahrRelevanteKrebsvorerkrankungen>
                <NichtRelevanteKrebsvorerkrankungen>{$case.anamnese.NichtRelevanteKrebsvorerkrankungen}</NichtRelevanteKrebsvorerkrankungen>
                <JahrNichtRelevanteKrebsvorerkrankungen>{$case.anamnese.JahrNichtRelevanteKrebsvorerkrankungen}</JahrNichtRelevanteKrebsvorerkrankungen>
                <DKGPatientenfragebogen>{$case.anamnese.DKGPatientenfragebogen}</DKGPatientenfragebogen>
                <PositiveFamilienanamnese>{$case.anamnese.PositiveFamilienanamnese}</PositiveFamilienanamnese>
            </Anamnese>
            <Grundgesamtheiten>{$case.grundgesamtheiten.Grundgesamtheiten}</Grundgesamtheiten>
            <Fallinfos>
                <Zentrumsfall>{$case.fallinfos.Zentrumsfall}</Zentrumsfall>
                <Organ>{$case.fallinfos.Organ}</Organ>
                <RegNr>{$case.fallinfos.RegNr}</RegNr>
                <HauptNebenStandort>{$case.fallinfos.HauptNebenStandort}</HauptNebenStandort>
                <FallNummer>{$case.fallinfos.FallNummer}</FallNummer>
                <EingabeFalldaten>{$case.fallinfos.EingabeFalldaten}</EingabeFalldaten>
            </Fallinfos>
            <Diagnose>
                <DatumErstdiagnosePrimaertumor>{$case.diagnose.DatumErstdiagnosePrimaertumor}</DatumErstdiagnosePrimaertumor>
                <DatumHistologischeSicherung>{$case.diagnose.DatumHistologischeSicherung}</DatumHistologischeSicherung>
                <ICDOHistologieDiagnose>{$case.diagnose.ICDOHistologieDiagnose}</ICDOHistologieDiagnose>
                <Tumorauspraegung>{$case.diagnose.Tumorauspraegung}</Tumorauspraegung>
                <ICDOLokalisation>{$case.diagnose.ICDOLokalisation}</ICDOLokalisation>
                <KolonRektum>{$case.diagnose.KolonRektum}</KolonRektum>
                <TumorlokalisationRektum>{$case.diagnose.TumorlokalisationRektum}</TumorlokalisationRektum>
                <praeT>{$case.diagnose.praeT}</praeT>
                <praeN>{$case.diagnose.praeN}</praeN>
                <praeM>{$case.diagnose.praeM}</praeM>
                <UICCStadium>{$case.diagnose.UICCStadium}</UICCStadium>
                <SynchroneBehandlungKolorektalerPrimaertumoren>{$case.diagnose.SynchroneBehandlungKolorektalerPrimaertumoren}</SynchroneBehandlungKolorektalerPrimaertumoren>
                <MRTBecken>{$case.diagnose.MRTBecken}</MRTBecken>
                <CTBecken>{$case.diagnose.CTBecken}</CTBecken>
                <AbstandFaszie>{$case.diagnose.AbstandFaszie}</AbstandFaszie>
            </Diagnose>
            <PraetherapeutischeTumorkonferenz>
                <VorstellungPraetherapeutischeTumorkonferenz>{$case.praetherapeutischeTumorkonferenz.VorstellungPraetherapeutischeTumorkonferenz}</VorstellungPraetherapeutischeTumorkonferenz>
                <EmpfehlungPraetherapeutischeTumorkonferenz>{$case.praetherapeutischeTumorkonferenz.EmpfehlungPraetherapeutischeTumorkonferenz}</EmpfehlungPraetherapeutischeTumorkonferenz>
            </PraetherapeutischeTumorkonferenz>
            <EndoskopischePrimaertherapie>
                <DatumTherapeutischeKoloskopie>{$case.endoskopischePrimaertherapie.DatumTherapeutischeKoloskopie}</DatumTherapeutischeKoloskopie>
                <OPSCodeEndoskopischePrimaertherapie>{$case.endoskopischePrimaertherapie.OPSCodeEndoskopischePrimaertherapie}</OPSCodeEndoskopischePrimaertherapie>
            </EndoskopischePrimaertherapie>
            <ChirurgischePrimaertherapie>
                <ASAKlassifikation>{$case.chirurgischePrimaertherapie.ASAKlassifikation}</ASAKlassifikation>
                <DatumOperativeTumorentfernung>{$case.chirurgischePrimaertherapie.DatumOperativeTumorentfernung}</DatumOperativeTumorentfernung>
                <OPSCodesChirurgischePrimaertherapie>{$case.chirurgischePrimaertherapie.OPSCodesChirurgischePrimaertherapie}</OPSCodesChirurgischePrimaertherapie>
                <NotfallOderElektiveingriff>{$case.chirurgischePrimaertherapie.NotfallOderElektiveingriff}</NotfallOderElektiveingriff>
                <Erstoperateur>{$case.chirurgischePrimaertherapie.Erstoperateur}</Erstoperateur>
                <Zweitoperateur>{$case.chirurgischePrimaertherapie.Zweitoperateur}</Zweitoperateur>
                <AnastomoseDurchgefuehrt>{$case.chirurgischePrimaertherapie.AnastomoseDurchgefuehrt}</AnastomoseDurchgefuehrt>
                <TMEDurchgefuehrt>{$case.chirurgischePrimaertherapie.TMEDurchgefuehrt}</TMEDurchgefuehrt>
                <PostoperativeWundinfektion>{$case.chirurgischePrimaertherapie.PostoperativeWundinfektion}</PostoperativeWundinfektion>
                <DatumPostoperativeWundinfektion>{$case.chirurgischePrimaertherapie.DatumPostoperativeWundinfektion}</DatumPostoperativeWundinfektion>
                <AufgetretenAnastomoseninsuffizienz>{$case.chirurgischePrimaertherapie.AufgetretenAnastomoseninsuffizienz}</AufgetretenAnastomoseninsuffizienz>
                <AnastomoseninsuffizienzInterventionspflichtig>{$case.chirurgischePrimaertherapie.AnastomoseninsuffizienzInterventionspflichtig}</AnastomoseninsuffizienzInterventionspflichtig>
                <DatumInterventionspflichtigeAnastomoseninsuffizienz>{$case.chirurgischePrimaertherapie.DatumInterventionspflichtigeAnastomoseninsuffizienz}</DatumInterventionspflichtigeAnastomoseninsuffizienz>
                <Revisionseingriff>{$case.chirurgischePrimaertherapie.Revisionseingriff}</Revisionseingriff>
                <DatumRevisionseingriff>{$case.chirurgischePrimaertherapie.DatumRevisionseingriff}</DatumRevisionseingriff>
                <OPmitStoma>{$case.chirurgischePrimaertherapie.OPmitStoma}</OPmitStoma>
                <Stomaangezeichnet>{$case.chirurgischePrimaertherapie.Stomaangezeichnet}</Stomaangezeichnet>
            </ChirurgischePrimaertherapie>
            <PostoperativeHistologieStaging>
                <pT>{$case.postoperativeHistologieStaging.pT}</pT>
                <pN>{$case.postoperativeHistologieStaging.pN}</pN>
                <postM>{$case.postoperativeHistologieStaging.postM}</postM>
                <Grading>{$case.postoperativeHistologieStaging.Grading}</Grading>
                <ICDOHistologiePostoperative>{$case.postoperativeHistologieStaging.ICDOHistologiePostoperative}</ICDOHistologiePostoperative>
                <PSRLokalNachAllenOPs>{$case.postoperativeHistologieStaging.PSRLokalNachAllenOPs}</PSRLokalNachAllenOPs>
                <PSRGesamtNachPrimaertherapie>{$case.postoperativeHistologieStaging.PSRGesamtNachPrimaertherapie}</PSRGesamtNachPrimaertherapie>
                <GueteDerMesorektumresektion>{$case.postoperativeHistologieStaging.GueteDerMesorektumresektion}</GueteDerMesorektumresektion>
                <AnzahlDerUntersuchtenLymphknoten>{$case.postoperativeHistologieStaging.AnzahlDerUntersuchtenLymphknoten}</AnzahlDerUntersuchtenLymphknoten>
                <AbstandAboralerTumorrand>{$case.postoperativeHistologieStaging.AbstandAboralerTumorrand}</AbstandAboralerTumorrand>
                <AbstandZirkumferentiellerTumorrand>{$case.postoperativeHistologieStaging.AbstandZirkumferentiellerTumorrand}</AbstandZirkumferentiellerTumorrand>
            </PostoperativeHistologieStaging>
            <PostoperativeTumorkonferenz>
                <VorstellungPostoperativeTumorkonferenz>{$case.postoperativeTumorkonferenz.VorstellungPostoperativeTumorkonferenz}</VorstellungPostoperativeTumorkonferenz>
                <EmpfehlungPostoperativeTumorkonferenz>{$case.postoperativeTumorkonferenz.EmpfehlungPostoperativeTumorkonferenz}</EmpfehlungPostoperativeTumorkonferenz>
            </PostoperativeTumorkonferenz>
            <Lebermetastasen>
                <LebermetastasenVorhanden>{$case.lebermetastasen.LebermetastasenVorhanden}</LebermetastasenVorhanden>
                <LebermetastasenAusschliesslich>{$case.lebermetastasen.LebermetastasenAusschliesslich}</LebermetastasenAusschliesslich>
                <PrimaereLebermetastasenresektion>{$case.lebermetastasen.PrimaereLebermetastasenresektion}</PrimaereLebermetastasenresektion>
                <BedingungenSenkundaereLebermetastasenresektion>{$case.lebermetastasen.BedingungenSenkundaereLebermetastasenresektion}</BedingungenSenkundaereLebermetastasenresektion>
                <SekundaereLebermetastasenresektion>{$case.lebermetastasen.SekundaereLebermetastasenresektion}</SekundaereLebermetastasenresektion>
            </Lebermetastasen>
            <PraeoperativeStrahlentherapie>
                <EmpfehlungPraeoperativeStrahlentherapie>{$case.praeoperativeStrahlentherapie.EmpfehlungPraeoperativeStrahlentherapie}</EmpfehlungPraeoperativeStrahlentherapie>
                <DatumEmpfehlungPraeoperativeStrahlentherapie>{$case.praeoperativeStrahlentherapie.DatumEmpfehlungPraeoperativeStrahlentherapie}</DatumEmpfehlungPraeoperativeStrahlentherapie>
                <TherapiezeitpunktPraeoperativeStrahlentherapie>{$case.praeoperativeStrahlentherapie.TherapiezeitpunktPraeoperativeStrahlentherapie}</TherapiezeitpunktPraeoperativeStrahlentherapie>
                <TherapieintentionPraeoperativeStrahlentherapie>{$case.praeoperativeStrahlentherapie.TherapieintentionPraeoperativeStrahlentherapie}</TherapieintentionPraeoperativeStrahlentherapie>
                <GruendeFuerNichtdurchfuehrungPraeoperativeStrahlentherapie>{$case.praeoperativeStrahlentherapie.GruendeFuerNichtdurchfuehrungPraeoperativeStrahlentherapie}</GruendeFuerNichtdurchfuehrungPraeoperativeStrahlentherapie>
                <DatumBeginnPraeoperativeStrahlentherapie>{$case.praeoperativeStrahlentherapie.DatumBeginnPraeoperativeStrahlentherapie}</DatumBeginnPraeoperativeStrahlentherapie>
                <DatumEndePraeoperativeStrahlentherapie>{$case.praeoperativeStrahlentherapie.DatumEndePraeoperativeStrahlentherapie}</DatumEndePraeoperativeStrahlentherapie>
                <GrundDerBeendigungDerPraeoperativeStrahlentherapie>{$case.praeoperativeStrahlentherapie.GrundDerBeendigungDerPraeoperativeStrahlentherapie}</GrundDerBeendigungDerPraeoperativeStrahlentherapie>
            </PraeoperativeStrahlentherapie>
            <PostoperativeStrahlentherapie>
                <EmpfehlungPostoperativeStrahlentherapie>{$case.postoperativeStrahlentherapie.EmpfehlungPostoperativeStrahlentherapie}</EmpfehlungPostoperativeStrahlentherapie>
                <DatumEmpfehlungPostoperativeStrahlentherapie>{$case.postoperativeStrahlentherapie.DatumEmpfehlungPostoperativeStrahlentherapie}</DatumEmpfehlungPostoperativeStrahlentherapie>
                <TherapiezeitpunktPostoperativeStrahlentherapie>{$case.postoperativeStrahlentherapie.TherapiezeitpunktPostoperativeStrahlentherapie}</TherapiezeitpunktPostoperativeStrahlentherapie>
                <TherapieintentionPostoperativeStrahlentherapie>{$case.postoperativeStrahlentherapie.TherapieintentionPostoperativeStrahlentherapie}</TherapieintentionPostoperativeStrahlentherapie>
                <GruendeFuerNichtdurchfuehrungPostoperativeStrahlentherapie>{$case.postoperativeStrahlentherapie.GruendeFuerNichtdurchfuehrungPostoperativeStrahlentherapie}</GruendeFuerNichtdurchfuehrungPostoperativeStrahlentherapie>
                <DatumBeginnPostoperativeStrahlentherapie>{$case.postoperativeStrahlentherapie.DatumBeginnPostoperativeStrahlentherapie}</DatumBeginnPostoperativeStrahlentherapie>
                <DatumEndePostoperativeStrahlentherapie>{$case.postoperativeStrahlentherapie.DatumEndePostoperativeStrahlentherapie}</DatumEndePostoperativeStrahlentherapie>
                <GrundDerBeendigungDerPostoperativeStrahlentherapie>{$case.postoperativeStrahlentherapie.GrundDerBeendigungDerPostoperativeStrahlentherapie}</GrundDerBeendigungDerPostoperativeStrahlentherapie>
            </PostoperativeStrahlentherapie>
            <PraeoperativeChemotherapie>
                <EmpfehlungPraeoperativeChemotherapie>{$case.praeoperativeChemotherapie.EmpfehlungPraeoperativeChemotherapie}</EmpfehlungPraeoperativeChemotherapie>
                <DatumEmpfehlungPraeoperativeChemotherapie>{$case.praeoperativeChemotherapie.DatumEmpfehlungPraeoperativeChemotherapie}</DatumEmpfehlungPraeoperativeChemotherapie>
                <TherapiezeitpunktPraeoperativeChemotherapie>{$case.praeoperativeChemotherapie.TherapiezeitpunktPraeoperativeChemotherapie}</TherapiezeitpunktPraeoperativeChemotherapie>
                <TherapieintentionPraeoperativeChemotherapie>{$case.praeoperativeChemotherapie.TherapieintentionPraeoperativeChemotherapie}</TherapieintentionPraeoperativeChemotherapie>
                <GruendeFuerNichtdurchfuehrungPraeoperativeChemotherapie>{$case.praeoperativeChemotherapie.GruendeFuerNichtdurchfuehrungPraeoperativeChemotherapie}</GruendeFuerNichtdurchfuehrungPraeoperativeChemotherapie>
                <DatumBeginnPraeoperativeChemotherapie>{$case.praeoperativeChemotherapie.DatumBeginnPraeoperativeChemotherapie}</DatumBeginnPraeoperativeChemotherapie>
                <DatumEndePraeoperativeChemotherapie>{$case.praeoperativeChemotherapie.DatumEndePraeoperativeChemotherapie}</DatumEndePraeoperativeChemotherapie>
                <GrundDerBeendigungDerPraeoperativeChemotherapie>{$case.praeoperativeChemotherapie.GrundDerBeendigungDerPraeoperativeChemotherapie}</GrundDerBeendigungDerPraeoperativeChemotherapie>
            </PraeoperativeChemotherapie>
            <PostoperativeChemotherapie>
                <EmpfehlungPostoperativeChemotherapie>{$case.postoperativeChemotherapie.EmpfehlungPostoperativeChemotherapie}</EmpfehlungPostoperativeChemotherapie>
                <DatumEmpfehlungPostoperativeChemotherapie>{$case.postoperativeChemotherapie.DatumEmpfehlungPostoperativeChemotherapie}</DatumEmpfehlungPostoperativeChemotherapie>
                <TherapiezeitpunktPostoperativeChemotherapie>{$case.postoperativeChemotherapie.TherapiezeitpunktPostoperativeChemotherapie}</TherapiezeitpunktPostoperativeChemotherapie>
                <TherapieintentionPostoperativeChemotherapie>{$case.postoperativeChemotherapie.TherapieintentionPostoperativeChemotherapie}</TherapieintentionPostoperativeChemotherapie>
                <GruendeFuerNichtdurchfuehrungPostoperativeChemotherapie>{$case.postoperativeChemotherapie.GruendeFuerNichtdurchfuehrungPostoperativeChemotherapie}</GruendeFuerNichtdurchfuehrungPostoperativeChemotherapie>
                <DatumBeginnPostoperativeChemotherapie>{$case.postoperativeChemotherapie.DatumBeginnPostoperativeChemotherapie}</DatumBeginnPostoperativeChemotherapie>
                <DatumEndePostoperativeChemotherapie>{$case.postoperativeChemotherapie.DatumEndePostoperativeChemotherapie}</DatumEndePostoperativeChemotherapie>
                <GrundDerBeendigungDerPostoperativeChemotherapie>{$case.postoperativeChemotherapie.GrundDerBeendigungDerPostoperativeChemotherapie}</GrundDerBeendigungDerPostoperativeChemotherapie>
            </PostoperativeChemotherapie>
            <BestSupportiveCare>{$case.bestSupportiveCare.BestSupportiveCare}</BestSupportiveCare>
            <Prozess>
                <DatumStudie>{$case.prozess.DatumStudie}</DatumStudie>
                <Studientyp>{$case.prozess.Studientyp}</Studientyp>
                <PsychoonkologischeBetreuung>{$case.prozess.PsychoonkologischeBetreuung}</PsychoonkologischeBetreuung>
                <BeratungSozialdienst>{$case.prozess.BeratungSozialdienst}</BeratungSozialdienst>
                <GenetischeBeratungEmpfohlen>{$case.prozess.GenetischeBeratungEmpfohlen}</GenetischeBeratungEmpfohlen>
                <GenetischeBeratungErhalten>{$case.prozess.GenetischeBeratungErhalten}</GenetischeBeratungErhalten>
                <ImmunhistochemischeUntersuchungAufMSI>{$case.prozess.ImmunhistochemischeUntersuchungAufMSI}</ImmunhistochemischeUntersuchungAufMSI>
            </Prozess>
            {foreach from=$case.followUps item=followUp}
            <FollowUp>
                <DatumFollowUp>{$followUp.DatumFollowUp}</DatumFollowUp>
                <LokoregionaeresRezidiv>{$followUp.LokoregionaeresRezidiv}</LokoregionaeresRezidiv>
                <LymphknotenRezidiv>{$followUp.LymphknotenRezidiv}</LymphknotenRezidiv>
                <Fernmetastasen>{$followUp.Fernmetastasen}</Fernmetastasen>
                <Zweittumor>{$followUp.Zweittumor}</Zweittumor>
                <Verstorben>{$followUp.Verstorben}</Verstorben>
                <QuelleFollowUp>{$followUp.QuelleFollowUp}</QuelleFollowUp>
            </FollowUp>
            {/foreach}
        </Fall>
        {/foreach}
    </Patient>
    {/foreach}
</Patienten>
