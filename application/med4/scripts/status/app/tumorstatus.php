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

$content = array(
   'anlass'          => $this->getFieldDescription('anlass'),
   'sicherungsgrad'  => $this->getFieldDescription('sicherungsgrad'),
   'diagnose'        => $this->getFieldValue('diagnose'),
   'tnm_praefix'     => $this->getFieldDescription('tnm_praefix'),
   't'               => $this->getFieldDescription('t'),
   'n'               => $this->getFieldDescription('n'),
   'm'               => $this->getFieldDescription('m'),
   'g'               => $this->getFieldDescription('g'),
   'l'               => $this->getFieldDescription('l'),
   'v'               => $this->getFieldDescription('v'),
   'r'               => $this->getFieldDescription('r'),
   'uicc'            => $this->getFieldDescription('uicc'),
   'diagnose_seite'  => $this->getFieldDescription('diagnose_seite'),
   'ann_arbor'       => $this->getFieldDescription('ann_arbor_stadium'),
);

$data = array(
    $content['anlass'],
    $content['sicherungsgrad'],
    $content['diagnose'],
    trim(implode(' ', array(
       $content['tnm_praefix'],
       $content['t'],
       $content['n'],
       $content['m'],
       $content['g'],
       $content['l'],
       $content['v'],
       $content['r']))),
   $content['ann_arbor'],
   $content['uicc'],
   $content['diagnose_seite']
);

if (check_array_content($content) !== true) {
   $data = '-';
} else {
   foreach ($data as $k => $tmp)
    if (!strlen($tmp))
        unset($data[$k]);

   $data = implode(' - ', $data);
}

$this
   ->setStatus('form_date', $this->getFieldValue('datum_sicherung'))
   ->setStatus('erkrankung_id', $this->getFieldValue('erkrankung_id'))
   ->setStatus('form_data', $data)
;

?>