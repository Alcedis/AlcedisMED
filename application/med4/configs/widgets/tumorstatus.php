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
            'infiltration',
            'befallen_n',
            'befallen_m',
            'invasionstiefe'
        ),
        'kh'
    )
   ->showFieldIn(
        array(
            'lugano',
            's'
        ),
        'sst'
   )
   ->showFieldIn(
        array(
            'risiko'
        ),
        array('sst', 'p')
   )
   ->showFieldIn(
      array(
         'ajcc'
      ),
      'h'
   )
   ->showFieldIn(
      array(
         'figo',
         'lk_staging'
      ),
      'gt'
   )
   ->showFieldIn(
      array(
         'rezidiv_psa',
         'mhrpc',
         'psa',
         'datum_psa',
         'eignung_nerverhalt',
         'eignung_nerverhalt_seite',
         'gleason1',
         'gleason2',
         'gleason3',
         'gleason4_anteil',
         'zufall'
      ),
      'p'
   )
    ->showFieldIn(
        'zweittumor',
        array('d', 'p')
    )

   ->showFieldIn(
      array(
         'hoehe',
         'stadium_mason',
         'diagnose_c19_zuordnung',
         'lokalisation_detail'
      ),
      'd'
   )
   ->showFieldIn(
      'diagnose_seite',
       array('b', 'lu', 'sst')
   )
   ->showFieldIn(
      array(
         'mikrokalk',
         'dcis_morphologie',
         'dcis_morphologie_text',
         'estro',
         'estro_irs',
         'estro_urteil',
         'prog',
         'prog_irs',
         'prog_urteil',
         'her2',
         'her2_methode',
         'her2_fish',
         'her2_fish_methode',
         'her2_urteil'
      ),
      'b'
   )
   ->showFieldIn('stadium_sclc', 'lu')
   ->showFieldIn(
      array(
         'nhl_who_b',
         'nhl_who_t',
         'hl_who',
         'ann_arbor_stadium',
         'ann_arbor_aktivitaetsgrad',
         'ann_arbor_extralymphatisch',
         'nhl_ipi',
         'flipi',
         'durie_salmon',
         'iss',
         'immun_phaenotyp'
      ),
      array('ly', 'snst')
   )
   ->showFieldIn(
      array(
         'cll_rai',
         'cll_binet',
         'aml_fab',
         'aml_who',
         'all_egil',
         'mds_fab',
         'mds_who'
      ),
      array('leu','snst')
   )
   ->showFieldIn(
      array(
         'risiko_mediastinaltumor',
         'risiko_extranodalbefall',
         'risiko_bks',
         'risiko_lk'
      ),
      array('leu', 'ly', 'snst')
   )
   ->hideFieldIn(
      array(
         'uicc'
      ),
      array('leu', 'ly', 'snst', 'gt')
   )
   ->hideFieldIn(
      array(
         'quelle_metastasen',
         'groesse_x',
         'groesse_y',
         'groesse_z',
         'multizentrisch',
         'multifokal',
         'regressionsgrad',
         'tnm_praefix',
         't',
         'n',
         'm',
         'g',
         'l',
         'v',
         'r',
         'r_lokal',
         'ppn',
         'resektionsrand'
      ),
      array('leu', 'ly', 'snst')
   )
    ->hideFieldIn(
        array(
            'quelle_metastasen'
        ),
        'lu'
    )
    ->hideFieldIn(
        array(
             'tumorausbreitung_lokal'
        ),
        array('leu', 'ly')
    );

?>
