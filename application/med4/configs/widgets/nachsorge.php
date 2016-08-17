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

$this
   ->showFieldIn(
      array(
         'nachsorge_biopsie',
         'psa_bestimmt',
         'vorlage_labor_id',
         'iciq_ui',
         'ics',
         'fb_dkg',
         'iief5',
         'ipss',
         'lq_dkg',
         'gz_dkg',
         'ql',
         'pde5hemmer',
         'pde5hemmer_haeufigkeit',
         'vakuumpumpe',
         'skat',
         'penisprothese',
         'kontinenz',
         'vorlagenverbrauch',
         'spaetschaden_blase',
         'spaetschaden_blase_grad',
         'spaetschaden_rektum',
         'spaetschaden_rektum_grad',
         'sy_harndrang',
         'sy_nykturie',
         'sy_pollakisurie',
         'sy_miktion',
         'sy_restharn',
         'sy_harnverhalt',
         'sy_harnstau',
         'sy_harnstau_lokalisation',
         'sy_haematurie',
         'sy_gewichtsverlust_2wo'
      ),
      'p'
   )
   ->showFieldIn(
      array(
         'armbeweglichkeit',
         'umfang_oberarm',
         'umfang_unterarm',
         'scapula_alata',
         'lymphoedem',
         'lymphoedem_seite',
         'lymphdrainage',
         'sensibilitaet'
      ),
      'b'
   )
   ->showFieldIn(
      array(
         'sy_fieber',
         'sy_nachtschweiss'
      ),
      array('leu', 'ly', 'snst')
   )
   ->showFieldIn(
      array(
         'sy_dyspnoe',
         'sy_haemoptnoe',
         'sy_husten',
         'sy_husten_dauer',
         'sy_para_syndrom',
         'sy_para_syndrom_symptom',
         'sy_para_syndrom_detail',
         'sy_gewichtsverlust_3mo',
         'euroqol',
         'lcss'
      ),
      'lu'
   );

?>
