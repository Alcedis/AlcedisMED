<?php

/*
 * AlcedisMED
 * Copyright (C) 2010-2016  Alcedis GmbH
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */ 

class dkgPValidations extends dkgBaseValidations
{
    public function __construct()
    {
        $this
            ->_setName('p')
            ->_activateForDisease('p')
            ->registerValidations()
        ;
    }

    public function registerValidations()
    {
        $this
            ->addValidation(dkgValidation::create('patient')
                ->addField(dkgValidationField::create('datenaustausch')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('datenspeicherung')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('datenversand')
                    ->setCheck(false)
                )
            )
            ->addValidation(dkgValidation::create('vorlage_studie')
                ->addField(dkgValidationField::create('studientyp')
                        ->setCheck(false)
                )
                ->addField(dkgValidationField::create('ethikvotum')
                     ->setCheck(false)
                )
            )
            ->addValidation(dkgValidation::create('vorlage_therapie')
                ->addField(dkgValidationField::create('vorlage_therapie_id'))
                ->addDlist(dkgValidation::create('vorlage_therapie_wirkstoff')
                    ->addField(dkgValidationField::create('wirkstoff'))
                    ->addField(dkgValidationField::create('therapiedauer'))
                    ->addField(dkgValidationField::create('therapiedauer_einheit'))
                )
            )
            ->addValidation(dkgValidation::create('anamnese')
                ->addField(dkgValidationField::create('fb_dkg'))
                ->addField(dkgValidationField::create('iciq_ui'))
                ->addField(dkgValidationField::create('iief5'))
                ->addField(dkgValidationField::create('lq_dkg'))
                ->addField(dkgValidationField::create('gz_dkg'))
                ->addDlist(dkgValidation::create('anamnese_erkrankung')
                    ->addField(dkgValidationField::create('morphologie')
                            ->setCheck(false)
                    )
                )
            )
            ->addValidation(dkgValidation::create('labor')
                ->addField(dkgValidationField::create('datum'))
            )
            ->addValidation(dkgValidation::create('eingriff')
                ->addField(dkgValidationField::create('datum'))
                ->addField(dkgValidationField::create('org_id')
                    ->setCondition('$org_id == "$patientOrgId" || strlen($org_id) == 0')
                )
                ->addField(dkgValidationField::create('operateur1_id')
                    ->setCondition('$org_id == "$patientOrgId" || strlen($org_id) == 0')
                )
                ->addField(dkgValidationField::create('operateur2_id')
                    ->setCheck(false)
                    ->setCondition('($org_id == "$patientOrgId" || strlen($org_id) == 0) && in_array("1", array($art_primaertumor, $art_lk, $art_metastasen, $art_rezidiv, $art_nachresektion, $art_revision)) === true')
                )
                ->addField(dkgValidationField::create('art_primaertumor')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('art_rezidiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('art_revision')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('schnellschnitt')
                    ->setCheck(false)
                    ->setCondition('$org_id == "$patientOrgId" && in_array("1", array($art_primaertumor, $art_lk, $art_metastasen, $art_rezidiv, $art_nachresektion, $art_revision)) === true')
                )
                ->addField(dkgValidationField::create('schnellschnitt_dauer')
                    ->setCheck(false)
                    ->setCondition('$schnellschnitt == "1"')
                )
                ->addField(dkgValidationField::create('op_verfahren')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('nerverhalt_seite')
                    ->setCheck(false)
                )
                ->addDlist(dkgValidation::create('eingriff_ops')
                    ->addField(dkgValidationField::create('prozedur'))
                )
            )
            ->addValidation(dkgValidation::create('komplikation')
                ->addField(dkgValidationField::create('komplikation'))

                ->addField(dkgValidationField::create('clavien_dindo')
                        ->setCheck(false)
                )
                ->addField(dkgValidationField::create('ctcae')
                        ->setCheck(false)
                )
                ->addField(dkgValidationField::create('revisionsoperation'))
            )
            ->addValidation(dkgValidation::create('histologie')
                ->addField(dkgValidationField::create('datum'))
                ->addField(dkgValidationField::create('art'))
                ->addField(dkgValidationField::create('stanzen_ges_anz')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('eingriff_id')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('untersuchung_id')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('r_anz')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('l_anz')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('sbl_anz_positiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('sbr_anz_positiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('bll_anz_positiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('blr_anz_positiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('bl_anz_positiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('br_anz_positiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('tl_anz_positiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('tr_anz_positiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('mll_anz_positiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('mlr_anz_positiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('ml_anz_positiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('mr_anz_positiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('al_anz_positiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('ar_anz_positiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('all_anz_positiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('alr_anz_positiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('stanzen_ges_anz_positiv')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('gleason1')
                    ->setCheck(false)
                    ->setCondition('in_array($ptnm_praefix, array("y","yr","r")) === false')
                )
            )
            ->addValidation(dkgValidation::create('konferenz_patient')
                ->addField(dkgValidationField::create('art'))
                ->addField(dkgValidationField::create('patientenwunsch_nerverhalt')
                    ->setCheck(false)
                )
            )
            ->addValidation(dkgValidation::create('therapieplan')
                ->addField(dkgValidationField::create('datum'))
                ->addField(dkgValidationField::create('org_id'))
                ->addField(dkgValidationField::create('leistungserbringer'))
                ->addField(dkgValidationField::create('grundlage'))
                ->addField(dkgValidationField::create('zeitpunkt'))
                ->addField(dkgValidationField::create('konferenz_patient_id')
                    ->setCondition('$grundlage == "tk" && appSettings::get("konferenz") === true')
                )
                ->addField(dkgValidationField::create('watchful_waiting'))
                ->addField(dkgValidationField::create('active_surveillance'))
                ->addField(dkgValidationField::create('palliative_versorgung')
                    ->setCheck(false)
                )
            )
            ->addValidation(dkgValidation::create('strahlentherapie')
                ->addField(dkgValidationField::create('vorlage_therapie_id'))
                ->addField(dkgValidationField::create('intention'))
                ->addField(dkgValidationField::create('beginn'))
                ->addField(dkgValidationField::create('ende')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('art'))
                ->addField(dkgValidationField::create('gesamtdosis'))
                ->addField(dkgValidationField::create('seed_strahlung_90d')
                    ->setCheck(false)
                    ->setCondition('$art == "str_seeds"')
                )
                ->addField(dkgValidationField::create('endstatus'))
                ->addField(dkgValidationField::create('best_response'))
            )
            ->addValidation(dkgValidation::create('therapie_systemisch')
                ->addField(dkgValidationField::create('endstatus'))
                ->addField(dkgValidationField::create('zahnarzt')
                    ->setCheck(false)
                )
            )
            ->addValidation(dkgValidation::create('sonstige_therapie')
                ->addField(dkgValidationField::create('sonstige_art')
                    ->setCheck(false)
                )
            )
            ->addValidation(dkgValidation::create('studie')
                ->addField(dkgValidationField::create('vorlage_studie_id'))
                ->addField(dkgValidationField::create('date')
                    ->setCondition('strlen($beginn) == 0')
                )
                ->addField(dkgValidationField::create('beginn')
                    ->setCondition('strlen($date) == 0')
                )
            )
             ->addValidation(dkgValidation::create('beratung')
                ->addField(dkgValidationField::create('datum'))
                ->addField(dkgValidationField::create('psychoonkologie'))
                ->addField(dkgValidationField::create('psychoonkologie_dauer')
                    ->setCondition('$psychoonkologie == 1')
                )
                ->addField(dkgValidationField::create('sozialdienst'))
            )
            ->addValidation(dkgValidation::create('fragebogen')
                ->addField(dkgValidationField::create('datum'))
            )
            ->addValidation(dkgValidation::create('abschluss')
                ->addField(dkgValidationField::create('abschluss_grund'))
                ->addField(dkgValidationField::create('todesdatum')
                    ->setCheck(false)
                    ->setCondition('$abschluss_grund == "tot"')
                )
            )
            ->addValidation(dkgValidation::create('tumorstatus')
                ->addField(dkgValidationField::create('datum_sicherung'))
                ->addField(dkgValidationField::create('diagnosesicherung')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('sicherungsgrad'))
                ->addField(dkgValidationField::create('datum_beurteilung'))
                ->addField(dkgValidationField::create('anlass'))
                ->addField(dkgValidationField::create('zweittumor')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('zufall')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('diagnose'))
                ->addField(dkgValidationField::create('eignung_nerverhalt'))
                ->addField(dkgValidationField::create('rezidiv_lokal')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('rezidiv_lk')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('rezidiv_metastasen')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('quelle_metastasen')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('rezidiv_psa')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('fall_vollstaendig')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('nur_zweitmeinung')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('nur_diagnosesicherung')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('kein_fall')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('morphologie')
                    ->setCondition('$rezidiv_psa != 1')
                )
                ->addField(dkgValidationField::create('gleason1')
                    ->setCondition('$rezidiv_psa != 1')
                )
                ->addField(dkgValidationField::create('gleason2')
                    ->setCondition('$rezidiv_psa != 1')
                )
                ->addField(dkgValidationField::create('t')
                    ->setCondition('$rezidiv_psa != 1')
                )
                ->addField(dkgValidationField::create('n')
                    ->setCondition('$rezidiv_psa != 1')
                )
                ->addField(dkgValidationField::create('m')
                    ->setCondition('$rezidiv_psa != 1')
                )
                ->addField(dkgValidationField::create('g')
                    ->setCondition('substr($t, 0, 1) == "p" && strlen($rezidiv_psa) == 0 && in_array($tnm_praefix, array("y", "yr")) === false')
                )
                ->addField(dkgValidationField::create('r')
                    ->setCheck(false)
                    ->setCondition('substr($t, 0, 1) == "p"')
                )
                ->addField(dkgValidationField::create('r_lokal')
                    ->setCheck(false)
                    ->setCondition('substr($t, 0, 1) == "p"')
                )
                ->addField(dkgValidationField::create('resektionsrand')
                    ->setCheck(false)
                    ->setCondition('substr($t, 0, 1) == "p"')
                )
                ->addField(dkgValidationField::create('psa'))
                ->addField(dkgValidationField::create('datum_psa'))
            )
            ->addValidation(dkgValidation::create('nachsorge')
                ->addField(dkgValidationField::create('datum'))
                ->addField(dkgValidationField::create('nachsorge_biopsie')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('fb_dkg'))
                ->addField(dkgValidationField::create('psa_bestimmt')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('vorlage_labor_id')
                    ->setCondition('$psa_bestimmt == 1')
                )
                ->addField(dkgValidationField::create('iciq_ui'))
                ->addField(dkgValidationField::create('iief5'))
                ->addField(dkgValidationField::create('lq_dkg'))
                ->addField(dkgValidationField::create('gz_dkg'))
            )
        ;

        return $this;
    }
}

?>
