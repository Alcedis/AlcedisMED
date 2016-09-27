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

class dkgLuValidations extends dkgBaseValidations
{
    public function __construct()
    {
        $this
            ->_setName('lu')
            ->_activateForDisease('lu')
            ->registerValidations()
        ;
    }

    public function registerValidations()
    {
        $this
            ->addValidation(dkgValidation::create('vorlage_studie')
                ->addField(dkgValidationField::create('ethikvotum')
                     ->setCheck(false)
                )
            )
            ->addValidation(dkgValidation::create('vorlage_therapie')
                ->addField(dkgValidationField::create('art'))
            )
            ->addValidation(dkgValidation::create('untersuchung')
                ->addField(dkgValidationField::create('art'))
                ->addField(dkgValidationField::create('art_seite')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('org_id')
                    ->setCheck(false)
                )
            )
            ->addValidation(dkgValidation::create('eingriff')
                ->addField(dkgValidationField::create('datum'))
                ->addField(dkgValidationField::create('operateur1_id')
                    ->setCondition('$org_id == "$patientOrgId" || strlen($org_id) == 0')
                )
                ->addField(dkgValidationField::create('diagnose_seite')
                    ->setCheck(false)
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
                ->addDlist(dkgValidation::create('eingriff_ops')
                    ->addField(dkgValidationField::create('prozedur'))
                )
                ->addField(dkgValidationField::create('org_id')
                    ->setCheck(false)
                )
            )
            ->addValidation(dkgValidation::create('komplikation')
                ->addField(dkgValidationField::create('komplikation'))
                ->addField(dkgValidationField::create('revisionsoperation'))
            )
            ->addValidation(dkgValidation::create('histologie')
                ->addField(dkgValidationField::create('datum'))
                ->addField(dkgValidationField::create('art'))
                ->addField(dkgValidationField::create('diagnose_seite')
                    ->setCheck(false)
                )
            )
            ->addValidation(dkgValidation::create('therapieplan')
                ->addField(dkgValidationField::create('datum'))
                ->addField(dkgValidationField::create('grundlage'))
                ->addField(dkgValidationField::create('palliative_versorgung'))
                ->addField(dkgValidationField::create('zeitpunkt'))
                ->addField(dkgValidationField::create('konferenz_patient_id')
                    ->setCondition('$grundlage == "tk" && appSettings::get("konferenz") === true')
                )
                ->addField(dkgValidationField::create('intention'))
                ->addField(dkgValidationField::create('org_id')
                    ->setCheck(false)
                )
            )
            ->addValidation(dkgValidation::create('therapie_systemisch')
                ->addField(dkgValidationField::create('beginn'))
                ->addField(dkgValidationField::create('best_response'))
                ->addField(dkgValidationField::create('org_id')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('intention')
                    ->setCheck(false)
                )
            )
            ->addValidation(dkgValidation::create('strahlentherapie')
                ->addField(dkgValidationField::create('endstatus'))
                ->addField(dkgValidationField::create('ziel_primaertumor')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('ziel_brustwand_r')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('ziel_brustwand_l')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('ziel_mediastinum')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('ziel_lymph')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('ziel_knochen')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('ziel_sonst')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('org_id')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('intention')
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
            ->addValidation(dkgValidation::create('nachsorge')
                ->addField(dkgValidationField::create('datum'))
            )
            ->addValidation(dkgValidation::create('tumorstatus')
                ->addField(dkgValidationField::create('datum_sicherung'))
                ->addField(dkgValidationField::create('sicherungsgrad'))
                ->addField(dkgValidationField::create('datum_beurteilung'))
                ->addField(dkgValidationField::create('anlass'))
                ->addField(dkgValidationField::create('diagnose'))
                ->addField(dkgValidationField::create('diagnose_seite')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('rezidiv_lokal')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('rezidiv_lk')
                    ->setCheck(false)
                )
                ->addField(dkgValidationField::create('rezidiv_metastasen')
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
                ->addField(dkgValidationField::create('morphologie'))
                ->addField(dkgValidationField::create('t'))
                ->addField(dkgValidationField::create('n'))
                ->addField(dkgValidationField::create('m'))
                ->addField(dkgValidationField::create('g')
                    ->setCondition('substr($t, 0, 1) == "p" && in_array($tnm_praefix, array("y", "yr")) === false')
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
                ->addField(dkgValidationField::create('ajcc'))
            )
        ;

        return $this;
    }
}

?>