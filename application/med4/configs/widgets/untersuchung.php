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
            'hno_untersuchung',
            'lunge'
        ),
        'kh'
    )
    ->showFieldIn(
        array(
            'un'
        ),
        array('d', 'kh', 'sst', 'm')
    )
    ->showFieldIn(
        array(
            'ut'
        ),
        array('d', 'sst', 'm')
    )
   ->showFieldIn(
      array(
         'birads'
      ),
      'b'
   )
   ->showFieldIn(
      array(
         'koloskopie_vollstaendig',
         'ct_becken',
         'mesorektale_faszie'
      ),
      'd'
   )
    ->showFieldIn(
        array(
            'cn'
        ),
        array('d', 'm')
    )
   ->showFieldIn(
      array(
         'lavage_menge'
      ),
      'lu'
   )
   ->showFieldIn(
      array(
         'konsistenz',
         'rsh_verschieblich',
         'abgrenzbarkeit',
         'gesamtvolumen',
         'kapselueberschreitung',
         'invasion',
         'invasion_detail'
      ),
      'p'
   )
   ->showFieldIn(
      array(
         'bulky',
         'bulky_groesse',
         'lk_a',
         'lk_b',
         'lk_c',
         'lk_d',
         'lk_e',
         'lk_f',
         'lk_g',
         'lk_h',
         'lk_i',
         'lk_k',
         'lk_l'
      ),
      array('leu', 'ly', 'snst')
   );

?>
