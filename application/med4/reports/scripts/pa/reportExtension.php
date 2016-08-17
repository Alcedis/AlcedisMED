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

class reportExtensionPa extends reportMath
{
    protected function _eingriffCase($codes, $field = 'op.ops_codes')
    {
        $cases = array();
        foreach ($codes as $case) {
            $cases[] = "LOCATE('{$case}', $field) != 0";
        }
        $cases = implode(' OR ', $cases);

        return "IF({$cases}, 1, NULL)";
    }

    /**
      * Convert data for d04.1 report
      *
      * @param $data
      */
    protected function _convertPa041ReportData($data)
    {
        foreach ($data as &$dataset) {
            $addon = $dataset['addon'];

            unset(
                $dataset['patient_id'],
                $dataset['erkrankung_id'],
                $dataset['addon'],
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
                $dataset['max_uicc'],
                $dataset['anlass'],
                $dataset['start_date'],
                $dataset['end_date'],
                $addon['section']
            );

            $dataset = array_merge($dataset, $addon);
        }

        return $data;

    }
}

?>