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

/**
 * Class registerStateHe
 */
class registerStateHe extends registerStateDefault
{
    /**
     * _check
     * c diagnosis codes are excluding
     * d diagnosis codes are including
     *
     * @access  protected
     * @var     array
     */
    protected $_check = array(
        'morphologie' => array(
            'D0', 'D32.0', 'D32.1', 'D32.9', 'D33.0', 'D33.1', 'D33.2', 'D33.3', 'D33.4', 'D33.7', 'D33.9', 'D35.2',
            'D35.3', 'D35.4', 'D37', 'D38', 'D39', 'D40', 'D41', 'D42', 'D43', 'D44', 'D45', 'D46', 'D47', 'D48'
        ),
        'diagnose' => array(
            'C' => array('C77', 'C78', 'C79', 'C97'),
            'D' => array('D00', 'D01', 'D02', 'D03', 'D04', 'D05', 'D06', 'D07', 'D08', 'D09', 'D32', 'D33', 'D35.2', 'D35.3',
                'D35.4', 'D37', 'D38', 'D39', 'D40', 'D41', 'D42','D43', 'D44','D45', 'D46', 'D47', 'D48')
        )
    );


    /**
     * _filterPatients
     * (Es gibt mindestens einen tumorstatus mit 'anlass' = 'p'  oder "b")
     *
     * @access  protected
     * @param   registerPatientCollection $collection
     * @return  void
     */
    protected function _filterPatients(registerPatientCollection $collection)
    {
        /* @var registerPatient $patient */
        foreach ($collection as $patient) {
            foreach ($patient->getCases() as $case) {
                if (in_array($case->getData('anlass'), array('p', 'b')) === true &&
                    $this->_checkDiagnose($case->getData('diagnose')) === true
                ) {
                    $patient->setValid();
                    $case->setValid();
                }
            }
        }
    }


    /**
     * _checkDiagnose
     *
     * Die Diagnose ist:  'diagnose' = C00.*-C76.* / C80.* / C81.*-C96.* /
     * D00.*-D09.* / D32.*-D33.* / D35.2 - D35.4 / D37.*-D48
     * Ausnahme : C44 und D04.* werden nicht exportiert!
     *
     * @access  protected
     * @param   string  $diagnose
     * @return  bool
     */
    protected function _checkDiagnose($diagnose)
    {
        $valid = false;

        if ((str_starts_with($diagnose, 'C') && str_starts_with($diagnose, $this->_check['diagnose']['C']) === false) ||
            (str_starts_with($diagnose, 'D') && str_starts_with($diagnose, $this->_check['diagnose']['D']) === true)) {
            $valid = true;
        }

        return $valid;
    }


    /**
     * addAdditionalItems for he state
     *
     * @access  protected
     * @param   registerPatientCase $case
     * @return  array
     */
    public function addAdditionalItems(registerPatientCase $case)
    {
        $items = array();
        $primaryCase = $case->getPrimaryCase();

        $items[] = $this->_buildDiagnoseItem($primaryCase);
        $items[] = $this->_buildAutopsyItem($primaryCase);
        $items[] = $this->_buildDeathReasonItem($primaryCase);
        $items = array_merge($items, $this->_buildOpsCodeItems($case->getData('eingriff')));
        $items = array_merge($items, $this->_buildRadioByEffectItems($case->getData('strahlentherapie')));
        $items = array_merge($items, $this->_buildSysTherapyByEffectItems($case->getData('therapie_systemisch')));

        return array_filter($items);
    }


    /**
     * _buildDiagnoseItem
     *
     * @access  protected
     * @param   registerPatientCase $primaryCase
     * @return  array
     */
    protected function _buildDiagnoseItem(registerPatientCase $primaryCase)
    {
        $item = array(
            'art'  => 'Tumorzuordnung_Primaertumor_Text',
            'wert' => $primaryCase->getData('diagnose_text')
        );

        return $item;
    }


    /**
     * _buildAutopsyItem
     *
     * @access  protected
     * @param   registerPatientCase $primaryCase
     * @return  array
     */
    protected function _buildAutopsyItem(registerPatientCase $primaryCase)
    {
        $item = null;
        $closure = $primaryCase->getData('abschluss');

        if ($closure !== null && strlen($closure['autopsie']) > 0) {
            $item = array(
                'art'  => 'Tod_Autopsie',
                'wert' => ($closure['autopsie'] == '0' ? 'N' : 'J')
            );
        }

        return $item;
    }


    /**
     * _buildDeathReasonItem
     *
     * @access  protected
     * @param   registerPatientCase $primaryCase
     * @return  array
     */
    protected function _buildDeathReasonItem(registerPatientCase $primaryCase)
    {
        $item = null;
        $closure = $primaryCase->getData('abschluss');

        if ($closure !== null && strlen($closure['tod_ursache_text']) > 0) {
            $item = array(
                'art' => 'Tod_Todesursache_Text',
                'wert' => $closure['tod_ursache_text']
            );
        }

        return $item;
    }


    /**
     * _buildOpsCodeItems
     *
     * @access  protected
     * @param   array   $interventions
     * @return  array
     */
    protected function _buildOpsCodeItems(array $interventions)
    {
        $items = array();

        foreach ($interventions as $intervention) {
            $date = todate($intervention['datum'], 'de');

            foreach ($intervention['eingriff_ops'] as $ops) {
                $items[] = array(
                    'datum' => $date,
                    'art'   => 'OP_OPS_Text',
                    'wert'  => $ops['prozedur_text']
                );
            }
        }

        return $items;
    }


    /**
     * _buildRadioByEffectItems
     *
     * @access  protected
     * @param   array $therapies
     * @return  array
     */
    protected function _buildRadioByEffectItems(array $therapies)
    {
        $items = array();

        foreach ($therapies as $therapy) {
            $date = todate($therapy['beginn'], 'de');

            foreach ($therapy['nebenwirkung'] as $byEffect) {
                $items[] = array(
                    'datum' => $date,
                    'art'   => 'ST_Nebenwirkung_Text',
                    'wert'  => registerMap::create('nci', $byEffect['nci_code'])
                        ->setAppending($byEffect['nci_text'], '; ')
                        ->getLabel()
                );
            }
        }

        return $items;
    }


    /**
     * _buildSysTherapyByEffectItems
     *
     * @access  protected
     * @param   array $therapies
     * @return  array
     */
    protected function _buildSysTherapyByEffectItems(array $therapies)
    {
        $items = array();

        foreach ($therapies as $therapy) {
            $date = todate($therapy['beginn'], 'de');

            foreach ($therapy['nebenwirkung'] as $byEffect) {
                $items[] = array(
                    'datum' => $date,
                    'art'   => 'SYST_Nebenwirkung_Text',
                    'wert'  => registerMap::create('nci', $byEffect['nci_code'])
                        ->setAppending($byEffect['nci_text'], '; ')
                        ->getLabel()
                );
            }
        }

        return $items;
    }
}
