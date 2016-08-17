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

function kaplanMeierAlgorithm($dataArray, $preSort = false)
{
    $data = array('range' => array(), 'event' => array());

    if ($preSort === true) {
        asort($dataArray['range']);

        foreach ($dataArray['range'] as $i => $range) {
            //Range darf nicht im Minusbereich liegen
            if ($range >= 0) {
                $data['range'][] = $range;
                $data['event'][] = $dataArray['event'][$i];
            }
        }
    } else {
        $data = $dataArray;
    }

    // Kaplan-Meier-Algorithmus
    $kp         = array();
    $s          = array(0 => 1);
    $previous_s = 1;
    $cur_n      = count($data['range']);

    //Vorsortieren der Datensätze
    foreach ($data['range'] as $i => $dauer) {
        $kp["{$dauer}"][] = $data['event'][$i];
    }

    foreach ($kp as $t => $events)
    {
        $eventCount = array_sum($events);
        $caseCount  = count($events);

        $s[$t] = ($eventCount > 0)
            ? ($previous_s * ( $cur_n - $eventCount ) / $cur_n)
            : $previous_s
        ;

        $previous_s = $s[$t];
        $cur_n -= $caseCount;
    }

    return $s;
}


function creatinine_clearance_cockroft($age, $weight, $sex, $creatinin) {
   $value = '';

   if (strlen($age) == 0 || strlen($weight) == 0 || strlen($sex) == 0 || strlen($creatinin) == 0) {
      return $value;
   }

   $factor = $sex == 'm' ? 1 : 0.85;

   $value = (140-$age) * $weight * $factor / (72 * $creatinin);

   $suffix = ' ml/min';

   return round($value, 2) . $suffix;
}

function creatinine_clearance_jelliffe($age, $sex, $creatinin) {
   $value = '';

   if (strlen($age) == 0 || strlen($sex) == 0 || strlen($creatinin) == 0) {
      return $value;
   }

   $factor = $sex == 'm' ? 1 : 0.9;

   $value = $factor * (98 - (0.8 * ($age - 20))) / $creatinin;

   $suffix = ' ml/min';

   return round($value, 2) . $suffix;
}

function true_addition(array $numbers=array()) {
  $cnt = count($numbers);
  $i   = 0;
  $sum = 0;

  foreach ($numbers as $n) {
     if (is_null($n) OR !strlen($n)) {
        ++$i;
     } else {
        $sum += $n;
     }
  }

  return ($cnt == $i) ? '' : $sum;
}

?>