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
         'nhl_who_b',
         'nhl_who_t',
         'hl_who',
         'ann_arbor_stadium',
         'ann_arbor_aktivitaetsgrad',
         'ann_arbor_extralymphatisch',
         'nhl_ipi',
         'flipi',
         'durie_salmon'
      ),
      array('ly')
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
      array('leu')
   )
   ->showFieldIn(
      array(
         'liquordiag_1_methode',
         'liquordiag_1_zellzahl',
         'liquordiag_1_beurteilung',
         'liquordiag_2_methode',
         'liquordiag_2_zellzahl',
         'liquordiag_2_beurteilung',
         'liquordiag_3_methode',
         'liquordiag_3_zellzahl',
         'liquordiag_3_beurteilung'
      ),
      array('leu','ly','snst')
   );

?>