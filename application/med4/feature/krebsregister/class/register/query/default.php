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

require_once 'abstract.php';
require_once 'feature/export/base/helper.database.php';

/**
 * Class registerQueryDefault
 */
abstract class registerQueryDefault extends registerQueryAbstract
{
    const ST_WHERE = "ts.erkrankung_id = t.erkrankung_id AND ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.anlass = t.anlass";
    const ST_ORDER = "ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1";

    /**
     * default initialize
     *
     * @access  protected
     * @return     void
     */
    protected function _initialize()
    {
        $where = self::ST_WHERE;
        $order = self::ST_ORDER;

        $this
            ->addSelect("(SELECT ts.diagnose_seite FROM tumorstatus ts WHERE {$where} AND ts.diagnose IS NOT NULL {$order}) AS diagnose_seite", true)
            ->addSelect("(SELECT ekr.meldebegruendung FROM ekr ekr WHERE ekr.erkrankung_id = t.erkrankung_id ORDER BY datum DESC LIMIT 1) as ekr_mbg", true)
            ->addSelect("(SELECT ekr.datum FROM ekr ekr WHERE ekr.erkrankung_id = t.erkrankung_id ORDER BY datum DESC LIMIT 1) as ekr_datum", true)
        ;
        // ekr form must exist
        $this->addHaving("(ekr_mbg IS NOT NULL)", true);
    }


    /**
     * _buildQuery
     *
     * @access  protected
     * @return  string
     */
    protected function _buildQuery()
    {
        $this->_initializeSubmittals();

        $where = self::ST_WHERE;
        $order = self::ST_ORDER;

        $tsFields = array('tumorstatus_id', 'anlass', 'datum_sicherung', 'r_lokal', 'rezidiv_lokal', 'rezidiv_lk',
            'rezidiv_metastasen', 'tnm_praefix', 't', 'multizentrisch', 'multifokal', 'n', 'm', 'l', 'v', 'ppn', 's',
            'nhl_who_b', 'nhl_who_t', 'hl_who', 'ann_arbor_stadium', 'durie_salmon',
            'iss', 'cll_rai', 'cll_binet', 'aml_fab', 'aml_who', 'all_egil', 'mds_fab', 'mds_who', 'bem', 'zufall'
        );

        $tsmFields = array('tumorstatus_metastasen_id', 'tumorstatus_id', 'lokalisation');

        $tsGroup  = registerQueryElementGroup::create('tumorstatus ts', $tsFields);
        $tsmGroup = registerQueryElementGroup::create('tumorstatus_metastasen tsm', $tsmFields);

        $this
            ->addSelect('p.kv_nr as krankenversichertennr', true)
            ->addSelect('p.kv_fa as familienangehoerigennr', true)
            ->addSelect('p.kv_iknr as krankenkassennr', true)
            ->addSelect('p.titel', true)
            ->addSelect('p.adelstitel as namenszusatz', true)
            ->addSelect('p.geburtsname', true)
            ->addSelect('p.strasse', true)
            ->addSelect('p.hausnr as hausnummer', true)
            ->addSelect('p.plz', true)
            ->addSelect('p.ort', true)
            ->addSelect('p.land as land', true)
            ->addSelect('(SELECT e.datum FROM ekr e WHERE e.erkrankung_id = sit.erkrankung_id ORDER BY datum DESC LIMIT 1) as ekr_datum')
            ->addSelect("(SELECT ekr.user_id FROM ekr ekr WHERE ekr.erkrankung_id = t.erkrankung_id ORDER BY datum DESC LIMIT 1) as meldender_arzt", true)
            ->addSelect("(SELECT ts.diagnose_version FROM tumorstatus ts WHERE {$where} AND ts.diagnose IS NOT NULL {$order}) AS diagnose_version", true)
            ->addSelect("(SELECT ts.diagnose_text FROM tumorstatus ts WHERE {$where} AND ts.diagnose IS NOT NULL {$order}) AS diagnose_text", true)
            ->addSelect("(SELECT ts.n FROM tumorstatus ts WHERE {$where} AND ts.n IS NOT NULL {$order}) AS n", true)
            ->addSelect("(SELECT ts.m FROM tumorstatus ts WHERE {$where} AND ts.m IS NOT NULL {$order}) AS m", true)
            ->addSelect("(SELECT ts.lokalisation FROM tumorstatus ts WHERE {$where} AND ts.lokalisation IS NOT NULL {$order}) AS lokalisation", true)
            ->addSelect("(SELECT ts.lokalisation_version FROM tumorstatus ts WHERE {$where} AND ts.lokalisation IS NOT NULL {$order}) AS lokalisation_version", true)
            ->addSelect("(SELECT ts.lokalisation_seite FROM tumorstatus ts WHERE {$where} AND ts.lokalisation IS NOT NULL {$order}) AS lokalisation_seite", true)
            ->addSelect("(SELECT ts.lokalisation_text FROM tumorstatus ts WHERE {$where} AND ts.lokalisation IS NOT NULL {$order}) AS lokalisation_text", true)
            ->addSelect("(SELECT ts.diagnosesicherung FROM tumorstatus ts WHERE {$where} AND ts.diagnosesicherung IS NOT NULL {$order}) AS diagnosesicherung", true)
            ->addSelect("(SELECT ts.morphologie_version FROM tumorstatus ts WHERE {$where} AND ts.morphologie IS NOT NULL {$order}) AS morphologie_version", true)
            ->addSelect("(SELECT ts.morphologie_text FROM tumorstatus ts WHERE {$where} AND ts.morphologie IS NOT NULL {$order}) AS morphologie_text", true)
            ->addSelect("(SELECT ts.g FROM tumorstatus ts WHERE {$where} AND ts.g IS NOT NULL {$order}) AS grading", true)
            ->addSelect("(SELECT ts.lk_entf FROM tumorstatus ts WHERE {$where} AND ts.lk_entf IS NOT NULL {$order}) AS lk_entf", true)
            ->addSelect("(SELECT ts.lk_bef FROM tumorstatus ts WHERE {$where} AND ts.lk_bef IS NOT NULL {$order}) AS lk_bef", true)
            ->addSelect("(SELECT ts.r_lokal FROM tumorstatus ts WHERE {$where} AND ts.r_lokal IS NOT NULL {$order}) AS r_lokal", true)
            ->addSelect("(SELECT ts.bem FROM tumorstatus ts WHERE {$where} AND ts.bem IS NOT NULL {$order}) AS bem", true)
            ->addSelect("(SELECT {$tsGroup->getStatement()} FROM tumorstatus ts WHERE {$where}) AS tumorstatus", true)
            ->addSelect("(SELECT {$tsmGroup->getStatement()} FROM tumorstatus_metastasen tsm WHERE tsm.erkrankung_id = t.erkrankung_id) AS tumorstatus_metastasen", true)
        ;

        $this
            ->addGroupSelectPostProcess($tsGroup, 'datum_sicherung')
            ->addGroupSelectPostProcess($tsmGroup)
        ;

        $this->addRelation('tumorstatus_metastasen', 'tumorstatus');

        $this->_addDefault();
        $this->_addAnamnesis();
        $this->_addHistology();
        $this->_addIntervention();
        $this->_addComplication();
        $this->_addTherapies();
        $this->_addByEffect();
        $this->_addTherapyPlan();
        $this->_addAfterTreatment();
        $this->_addConclusion();
    }


    /**
     * _initializeSubmittals
     *
     * @access  protected
     * @return  void
     */
    protected function _initializeSubmittals()
    {
        $vtwFields = array('vorlage_therapie_wirkstoff_id', 'vorlage_therapie_id', 'wirkstoff');

        $agents = registerQueryElementGroup::create()
            ->setTableName('vorlage_therapie_wirkstoff')
            ->setAlias('vtw')
            ->setFields($vtwFields)
            ->getStatement()
        ;

        $subTherapies = HDatabase::createMap(sql_query_array($this->getDB(), "
            SELECT
                vt.vorlage_therapie_id,
                vt.bez,
                vt.bem,
                {$agents} AS wirkstoffe
            FROM vorlage_therapie vt
                LEFT JOIN vorlage_therapie_wirkstoff vtw ON vtw.vorlage_therapie_id = vt.vorlage_therapie_id
            GROUP BY
                vt.vorlage_therapie_id
        "));

        foreach ($subTherapies as &$subTherapy) {
            $c = $subTherapy['wirkstoffe'];

            $subTherapy['wirkstoffe'] = HDatabase::recordStringToArray($c, $vtwFields);
        }

        $this->addCacheEntry('therapyTemplate', $subTherapies);
    }


    /**
     * _addDefault
     *
     * @access  protected
     * @return  void
     */
    protected function _addDefault()
    {
        $this
            ->addJoin($this->createStatusJoin('histologie h', true))
            ->addJoin($this->createStatusJoin('zytologie z'))
        ;

        // diagnosis date
        $this
            ->addSelect("MIN(IF(h.unauffaellig IS NULL, h.datum, NULL)) AS date_histology")
            ->addSelect("MIN(z.datum) AS date_zytology")
            ->addSelect("(
                SELECT
                    MIN(ts.datum_sicherung)
                FROM
                    tumorstatus ts
                WHERE
                    ts.erkrankung_id = t.erkrankung_id AND
                    ts.diagnose_seite IN ('B', t.diagnose_seite)
                ORDER BY
                    ts.datum_sicherung DESC,
                    ts.sicherungsgrad ASC,
                    ts.datum_beurteilung DESC
                LIMIT 1
                ) AS date_tumorstate",
                true
            )
        ;
    }


    /**
     * _addAnamnesis
     *
     * @access  protected
     * @return  void
     */
    protected function _addAnamnesis()
    {
        $this
            ->addJoin($this->createStatusJoin('anamnese a'))
            ->addJoin('LEFT JOIN anamnese_erkrankung ae ON ae.anamnese_id = a.anamnese_id')
        ;

        $this->addGroupSelect(registerQueryElementGroup::create('anamnese a', array(
            'anamnese_id',
            'datum',
            'ecog'
        )), 'datum');

        $this->addGroupSelect(registerQueryElementGroup::create('anamnese_erkrankung ae', array(
            'anamnese_id',
            'erkrankung',
            'jahr'
        )));

        $this->addRelation('anamnese_erkrankung', 'anamnese');
    }


    /**
     * _addHistology
     *
     * @access  protected
     * @return  void
     */
    protected function _addHistology()
    {
        $this->addGroupSelect(registerQueryElementGroup::create('histologie h', array(
            'histologie_id',
            'datum',
            'histologie_nr',
            'eingriff_id',
            'morphologie',
            'morphologie_version',
            'morphologie_text',
            'multizentrisch',
            'multifokal',
            'g',
            'lk_entf',
            'lk_bef',
            'lk_bef_makro',
            'lk_bef_mikro',
            'lk_sentinel_entf',
            'lk_sentinel_bef',
            'ptnm_praefix',
            'pt',
            'pn',
            'pm',
            'l',
            'v',
            'ppn'
        )), 'datum');

        $this->addRelation('histologie', 'eingriff', 'eingriff_id', true, false);
    }


    /**
     * _addIntervention
     *
     * @access  protected
     * @return  void
     */
    protected function _addIntervention()
    {
        $this
            ->addJoin($this->createStatusJoin('eingriff e', true))
            ->addJoin('LEFT JOIN eingriff_ops ops ON e.eingriff_id = ops.eingriff_id')
        ;

        $this->addGroupSelect(registerQueryElementGroup::create('eingriff e', array(
            'eingriff_id',
            'datum',
            'intention',
            'art_primaertumor',
            'art_diagnostik',
            'art_revision',
            'art_sonstige',
            'art_metastasen',
            'art_nachresektion',
            'art_rekonstruktion',
            'operateur1_id',
            'operateur2_id',
            'intraop_bestrahlung',
            'intraop_bestrahlung_dosis',
            'intraop_zytostatika',
        )), 'datum');

        $this->addGroupSelect(registerQueryElementGroup::create('eingriff_ops ops', array(
            'eingriff_ops_id',
            'eingriff_id',
            'prozedur',
            'prozedur_seite',
            'prozedur_version',
            'prozedur_text'
        )));

        $this->addRelation('eingriff_ops', 'eingriff', 'eingriff_id');
    }


    /**
     * _addComplication
     *
     * @access  protected
     * @return  void
     */
    protected function _addComplication()
    {
        $this->addJoin('LEFT JOIN komplikation k ON k.erkrankung_id = sit.erkrankung_id');

        $this->addGroupSelect(registerQueryElementGroup::create('komplikation k', array(
            'komplikation_id',
            'eingriff_id',
            'komplikation'
        )));

        $this->addRelation('komplikation', 'eingriff', 'eingriff_id');
    }


    /**
     * _addTherapies
     *
     * @access  protected
     * @return  void
     */
    protected function _addTherapies()
    {
        $this
            ->addJoin($this->createStatusJoin('strahlentherapie str'))
            ->addJoin($this->createStatusJoin('therapie_systemisch sys'))
        ;

        $this->addGroupSelect(registerQueryElementGroup::create('therapie_systemisch sys', array(
            'therapie_systemisch_id',
            'vorlage_therapie_id',
            'vorlage_therapie_art',
            'intention',
            'beginn',
            'ende',
            'endstatus',
            'endstatus_grund',
            'bem'
        )));

        $this->addMapping('therapie_systemisch', 'therapyTemplate', 'vorlage_therapie_id');

        $this->addGroupSelect(registerQueryElementGroup::create('strahlentherapie str', array(
            'strahlentherapie_id',
            'beginn',
            'ende',
            'art',
            'intention',
            'gesamtdosis',
            'einzeldosis',
            'endstatus',
            'endstatus_grund',
            'ziel_ganzkoerper',
            'ziel_primaertumor',
            'ziel_mamma_r',
            'ziel_mamma_l',
            'ziel_brustwand_r',
            'ziel_brustwand_l',
            'ziel_mammaria_interna',
            'ziel_mediastinum',
            'ziel_prostata',
            'ziel_becken',
            'ziel_abdomen',
            'ziel_vulva',
            'ziel_vulva_pelvin',
            'ziel_vulva_inguinal',
            'ziel_inguinal_einseitig',
            'ziel_ingu_beidseitig',
            'ziel_ingu_pelvin',
            'ziel_vagina',
            'ziel_lymph',
            'ziel_paraaortal',
            'ziel_axilla_r',
            'ziel_axilla_l',
            'ziel_lk',
            'ziel_lk_supra',
            'ziel_lk_para',
            'ziel_lk_iliakal',
            'ziel_lk_zervikal_r',
            'ziel_lk_zervikal_l',
            'ziel_lk_hilaer',
            'ziel_lk_axillaer_r',
            'ziel_lk_axillaer_l',
            'ziel_lk_abdominell_o',
            'ziel_lk_abdominell_u',
            'ziel_lk_iliakal_r',
            'ziel_lk_iliakal_l',
            'ziel_lk_inguinal_r',
            'ziel_lk_inguinal_l',
            'ziel_knochen',
            'ziel_gehirn',
            'ziel_sonst_detail',
            'ziel_sonst_detail_seite',
            'ziel_sonst_detail_text'
        )));
    }


    /**
     * _addByEffect
     *
     * @access  protected
     * @return  void
     */
    protected function _addByEffect()
    {
        $this->addJoin('LEFT JOIN nebenwirkung nw ON nw.erkrankung_id = sit.erkrankung_id');

        $this->addGroupSelect(registerQueryElementGroup::create('nebenwirkung nw', array(
            'nebenwirkung_id',
            'strahlentherapie_id',
            'therapie_systemisch_id',
            'beginn',
            'grad',
            'nci_code',
            'nci_text'
        )));

        $this
            ->addRelation('nebenwirkung', 'strahlentherapie', null, true, true)
            ->addRelation('nebenwirkung', 'therapie_systemisch', 'therapie_systemisch_id', true, true)
        ;
    }


    /**
     * _addTherapyPlan
     *
     * @access  protected
     * @return  void
     */
    protected function _addTherapyPlan()
    {
        $this->addJoin($this->createStatusJoin('therapieplan tp'));

        $this->addGroupSelect(registerQueryElementGroup::create('therapieplan tp', array(
            'therapieplan_id',
            'datum',
            'intention',
            'grundlage',
            'zeitpunkt',
            'active_surveillance',
            'watchful_waiting',
            'bem'
        )));
    }


    /**
     * _addAfterTreatment
     *
     * @access  protected
     * @return  void
     */
    protected function _addAfterTreatment()
    {
        $this->addJoin("LEFT JOIN nachsorge n ON s.form = 'nachsorge' AND LOCATE(CONCAT_WS('','-',sit.erkrankung_id,'-'), s.report_param) > 0 AND n.nachsorge_id = s.form_id");

        $this->addGroupSelect(registerQueryElementGroup::create('nachsorge n', array(
            'nachsorge_id',
            'datum',
            'response_klinisch',
            'bem'
        )));
    }


    /**
     * _addConclusion
     *
     * @access  protected
     * @return  void
     */
    protected function _addConclusion()
    {
        $this
            ->addJoin($this->createStatusJoin('abschluss ab'))
        ;

        $this->addGroupSelect(registerQueryElementGroup::create('abschluss ab', array(
            'abschluss_id',
            'todesdatum',
            'tod_tumorassoziation',
            'bem'
        )));
    }


    /**
     * _postProcess
     *
     * @access  protected
     * @param   array $record
     * @return  void
     */
    protected function _postProcess(array &$record)
    {
        parent::_postProcess($record);

        if (isset($record['abschluss']) === true) {
            // map "abschluss" entry to single relation
            if (count($record['abschluss']) > 0) {
                $record['abschluss'] = reset($record['abschluss']);
            } else {
                $record['abschluss'] = null;
            }
        }
    }
}
