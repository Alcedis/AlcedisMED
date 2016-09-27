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
         'lokalisation',
         'lokalisation_seite',
         'lokalisation_version',
         'lokalisation_text',
         'ct',
         'schleimhautmelanom',
         'rezidiv_von',
         'rezidiv_von_seite',
         'lokoregionaer',
         'metast_visz',
         'metast_visz_1',
         'metast_visz_1_seite',
         'metast_visz_2',
         'metast_visz_2_seite',
         'metast_visz_3',
         'metast_visz_3_seite',
         'metast_visz_4',
         'metast_visz_4_seite',
         'metast_haut',
         'metast_lk'
      ),
      'h'
   )

   ->hideFieldIn(
      array(
         'diagnose_seite',
      ),
      array('leu', 'ly', 'snst')
   )

?>