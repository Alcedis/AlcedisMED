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

class reportExtensionB extends reportMath
{
   //valid ngt (...) values [(sn) dropped due 2 incompatibility]
   protected $_valid_tnm = array(
        'pn'   => array('pN0','pN0(i+)','pN0(i-)','pN1mi','pN1','pN1a','pN1b','pN1c','pN2','pN2a','pN2b','pN3','pN3a','pN3b','pN3c'),
        'g'    => array('X','1','L','2','M','3','H','4'),
        'pt'   => array('pTis','pT1','pT1mic','pT1a','pT1b','pT1c','pT2','pT3','pT4a','pT4b','pT4c','pT4d','pT4')
   );

   //special function 2 check which side is 2 b used
   protected function _checkSide($data)
   {
        if (count($data) === 1) {
            return $data;
        }

        $firstSide  = reset($data);
        $secondSide = end($data);

        foreach ($this->_valid_tnm as $check_value => $check_params) {
            foreach (array_reverse($check_params) as $check_param) {

                $firstVal  = trim(str_replace('(sn)', '', $firstSide[$check_value] ));
                $secondVal = trim(str_replace('(sn)', '', $secondSide[$check_value]));

                if ($firstVal == $check_param && $secondVal != $check_param) {
                    return array($firstSide['seite'] => $firstSide);
                }
                if ($firstVal != $check_param && $secondVal == $check_param) {
                    return array($secondSide['seite'] => $secondSide);
                }
                if ($firstVal == $check_param && $secondVal == $check_param) {
                    continue 2;
                }
            }
        }

        return array($firstSide['seite'] => $firstSide);
   }


    /**
     *
     *
     * @access
     * @param $datasets
     * @return mixed
     */
    protected function _b04Select($datasets)
    {
        $output = array();

        if (count($datasets) > 1) {
            $tmp = array();

            foreach ($datasets as $i => $dataset) {
                $hbeginn    = $dataset['h_beginn'];

                if (strlen($hbeginn)) {
                    $ptHirarchy = strlen($dataset['pt_hirarchy']) ? (int) $dataset['pt_hirarchy'] : -1;
                    $tmp[$ptHirarchy][$hbeginn] = $i;
                }
            }

            if (count($tmp) > 0) {
                krsort($tmp);

                // earliest date sort
                foreach ($tmp as $grade => &$time) {
                    ksort($time);
                }

                $output = $datasets[reset(reset($tmp))];
            } else {
                $output = null;
            }
        } else {
            $output = reset($datasets);
        }

        return $output;
    }


    /**
     *
     *
     * @access
     * @param $datasets
     * @return array
     */
    protected function _convertB041ReportData($datasets)
    {
       $data = array();

       foreach ($datasets as $i => $dataset) {
           $addon = $dataset['addon'];

           unset(
                   $dataset['primaerop_id'],
                   $dataset['patient_id'],
                   $dataset['pt_hirarchy'],
                   $dataset['pt_section'],
                   $dataset['erkrankung_id'],
                   $dataset['max_uicc'],
                   $dataset['anlass'],
                   $dataset['start_date'],
                   $dataset['end_date'],
                   $dataset['h_beginn'],
                   $dataset['041_ereignis'],
                   $dataset['041_ende'],
                   $dataset['042_ereignis'],
                   $dataset['042_ende'],
                   $dataset['043_ereignis'],
                   $dataset['043_ende'],
                   $dataset['044_ereignis'],
                   $dataset['044_ende'],
                   $dataset['045_ereignis'],
                   $dataset['045_beginn'],
                   $dataset['045_ende'],
                   $dataset['addon'],
                   $dataset['erkrankungId'],
                   $addon['section']
           );

           $data[$i] = array_merge($dataset, $addon);
       }

       return $data;
    }
}

?>
