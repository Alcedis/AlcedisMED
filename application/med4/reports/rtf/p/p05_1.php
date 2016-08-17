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

class reportContentP05_1 extends reportExtensionP
{
    /**
     *
     *
     * @access
     * @param   $auditjahr
     * @param   $bezugsjahr
     * @return  array
     */
    protected function _initalizeMatrix($auditjahr, $bezugsjahr)
    {
        $data = array(
            'auditjahr'  => $auditjahr,
            'bezugsjahr' => $bezugsjahr
        );

        $lbls = array('vorjahr','p','f','fq','dfs_a','dfs_p','oas_a','oas_p');

        foreach (range(1,5) as $n) {
            $n = strlen($n) == 1 ? '0' . $n : $n;
            foreach ($lbls as $k => $lbl) {
                $data[$lbl . '_' . $n] = $lbl == 'vorjahr' ? $bezugsjahr-$n : 0;
            }
        }

        return $data;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    public function generate()
    {
        $this->setTemplate('p05_1');

        $bezugsjahr = isset($this->_params['jahr']) && strlen($this->_params['jahr']) ? $this->_params['jahr'] : date('Y');
        $auditjahr  = $bezugsjahr+1;

        $end_jahr    = $bezugsjahr - 1;
        $beginn_jahr = $bezugsjahr - 5;

        $additionalContent['selects'] = array(
            "(SELECT ts.datum_sicherung FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND YEAR(ts.datum_sicherung) = {$bezugsjahr}    ORDER BY ts.datum_sicherung ASC LIMIT 1)  AS followupbezugsjahr",
            "(SELECT ts.datum_sicherung FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.datum_sicherung <= '{$bezugsjahr}-12-31' ORDER BY ts.datum_sicherung DESC LIMIT 1) AS lastRez",
            "(SELECT th_str.beginn FROM strahlentherapie th_str WHERE th_str.erkrankung_id = t.erkrankung_id AND th_str.intention = 'kur' ORDER BY th_str.beginn DESC LIMIT 1) AS def_strahlentherapie_beginn"
        );

        $additionalContent['fields'] = array(
            "sit.patient_id",
            "sit.erkrankung_id",
            "sit.tumorstatus_id",
            "IF(MAX(x.todesdatum) <= '{$bezugsjahr}-12-31', MAX(x.todesdatum), NULL) AS 'todesdatum_less_nachsorgejahr'",
            "sit.lastRez",
            "IF(MIN(IF(YEAR(n.datum) = {$bezugsjahr}, n.datum, NULL)) IS NOT NULL OR sit.followupbezugsjahr IS NOT NULL, 1, 0) AS 'followup'",
            "sit.def_strahlentherapie_beginn"
        );

        $datasets = $this->loadRessource('p01', $additionalContent);

        $matrix = $this->_initalizeMatrix($auditjahr, $bezugsjahr);

        foreach ($datasets as $dataset) {

            $bezuzgsjahr = substr($dataset['bezugsdatum'], 0, 4);

            if ($bezuzgsjahr <= $end_jahr && $bezuzgsjahr >= $beginn_jahr) {

                if ($this->_cpz_pz05_universe($dataset) !== 1) {
                    continue;
                }

                $n = $bezugsjahr - date('Y', strtotime($dataset['bezugsdatum']));
                $n = "0$n";

                $matrix["p_$n"]++;

                //- Nachsorge im gewählten Bezugsjahr dokumentiert (Bezug: Erhebungsdatum, Formular Nachsorge)
                //- Rezidiv im gewählten Bezugsjahr diagnostiziert (Bezug: Anlass "Beurteilung des ... Rezidives/Progresses"
                //- Todesdatum vor dem Ende des Betrachtungszeitraums (Formular Abschluss)
                if ($dataset['followup'] == '0' && strlen($dataset['todesdatum_less_nachsorgejahr']) == 0) {
                    continue;
                }

                $matrix["f_$n"] ++;

                $matrix["dfs_a_$n"] += (int) (strlen($dataset['lastRez']) == 0);

                $matrix["oas_a_$n"] += (int) (strlen($dataset['todesdatum_less_nachsorgejahr']) == 0);
            }
        }

        foreach (range(1,5) as $n) {
            $n = "0$n";
            $matrix["fq_$n"]    = $matrix["p_$n"] > 0 ? round($matrix["f_$n"] / $matrix["p_$n"] * 100, 2) . '%' : '-';
            $matrix["dfs_p_$n"] = $matrix["f_$n"] > 0 ? round($matrix["dfs_a_$n"] / $matrix["f_$n"] * 100, 2) . '%' : '-';
            $matrix["oas_p_$n"] = $matrix["f_$n"] > 0 ? round($matrix["oas_a_$n"] / $matrix["f_$n"] * 100, 2) . '%' : '-';
        }

        $this->_data = $matrix;

        $this->writePDF(true);
    }


    /**
     * _cpz_pz05_universe
     *
     * das Grundkollektiv ist:
     * 'Primärfall' = 1 UND
     * 'Therapie des Bezugsdatums' ? Active Surveillance UND 'Therapie des Bezugsdatums' ? Watchful Waiting
     *
     * @access  protected
     * @param   array $data
     * @return  int
     */
    protected function _cpz_pz05_universe(array $data)
    {
        $result = 0;

        if ($this->_isPrimary($data) == 1 &&
            $this->_is_bezug_as($data) === false &&
            $this->_is_bezug_ww($data) === false &&
            $this->_cpz_pz05_filter($data) == 1
        ) {
            $result = 1;
        }

        return $result;
    }


    /**
     * _cpz_pz05_filter
     *
     * 1.) ausgeschlossen werden Patienten mit 'R (lokal)' = R1 ODER 'R (lokal)' =  R2 ODER 'R' = R1 ODER 'R' = R2
     * 2.) eingeschlossen werden Patienten mit ['R' = R0 ODER ['R (lokal)' = R0 UND 'M' = c/pM0] ]
     * 3.) eingeschlossen werden Patienten mit ['definitive perkutane Strahlentherapie' = 1 || 'Str. permanent seed' = 1
     *     || 'HDR-Brachytherapie' = 1] UND 'M prätherapeutisch' = c/pM0 UND es ist eine Nachsorge innerhalb von 365
     *     Tagen nach Beginn DIESER Therapie dokumentiert UND es ist kein 'Datum Rezidiv' innerhalb von 365 Tagen nach
     *     Beginn DIESER Therapie dokumentiert
     * 4.) alle noch verbliebenen Patienten werden ausgeschlossen
     *
     * @access  protected
     * @param   array   $data
     * @return  int
     */
    protected function _cpz_pz05_filter(array $data)
    {
        // Prio 1
        if ($data['r_lokal'] == '1' || $data['r_lokal'] == '2' || $data['r'] == '1' || $data['r'] == '2') {
            return 0;
        }

        // Prio 2
        if ($data['r'] == '0' || ($data['r_lokal'] == '0' && $this->_checkM0($data) == true)) {
            return 1;
        }

        // Prio 3
        if ($this->_isOneYearTumorFree($data) === true) {
            return 1;
        }

        // Prio 4
        return 0;
    }
}

?>
