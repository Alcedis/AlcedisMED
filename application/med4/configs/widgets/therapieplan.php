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
        'op_sonstige', array(
            'b', 'leu', 'ly', 'p', 'snst'
        )
    )
    ->hideFieldIn(
        'op_art', array(
            'b', 'leu', 'ly', 'p', 'snst'
        )
    )
    ->showFieldIn(
      array(
         'leistungserbringer',
         'watchful_waiting',
         'active_surveillance',
         'strahlen_art',
         'strahlen_zielvolumen',
         'strahlen_gesamtdosis',
         'strahlen_einzeldosis',
         'strahlen_zeitpunkt',
         'ah_therapiedauer_prostata',
         'ah_therapiedauer_monate',
         'op_art_prostata',
         'op_art_nerverhaltend',
         'op_art_lymphadenektomie'
      ),
      'p'
   )
   ->hideFieldIn(
        'strahlen_lokalisation', 'b'
   )
   ->showFieldIn(
      array(
         'op_art_brusterhaltend',
         'op_art_mastektomie',
         'op_art_nachresektion',
         'op_art_sln',
         'op_art_axilla',
         'keine_axilla_grund',
         'strahlen_mamma',
         'strahlen_axilla',
         'strahlen_lk_supra',
         'strahlen_lk_para',
         'strahlen_thoraxwand',
         'strahlen_sonstige'
      ),
      'b'
   )
   ->showFieldIn(
      array(
         'op_art_transplantation_autolog',
         'op_art_transplantation_allogen_v',
         'op_art_transplantation_allogen_nv',
         'op_art_transplantation_syngen'
      ),
      array('leu','ly','snst')
   );

?>
