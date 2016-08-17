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

   $query_lokalisation = "
        SELECT
           d.diagnose_id AS 'code',
           d.lokalisation,
           s.bez,
           d.lokalisation_text
        FROM diagnose d
           LEFT JOIN l_basic s ON s.klasse = 'seite' AND s.code = d.lokalisation_seite
        WHERE
             d.erkrankung_id = '{$erkrankung_id}'
        GROUP BY
             d.diagnose_id
        ORDER BY
             d.lokalisation
     ";


   $fields = array(
      'eingriff_ops_id' => array('req' => 0,'size' =>'' ,'maxlen' =>'11' ,'type' =>'hidden'   ,'ext' => '' ),
      'eingriff_id'     => array('req' => 0,'size' =>'' ,'maxlen' =>'11' ,'type' =>'hidden'   ,'ext' => '' ),
      'erkrankung_id'   => array('req' => 1,'size' =>'' ,'maxlen' =>'11' ,'type' =>'hidden'   ,'ext' => '' ),
      'patient_id'      => array('req' => 1,'size' =>'' ,'maxlen' =>'11' ,'type' =>'hidden'   ,'ext' => '' ),
      'prozedur'        => array('req' => 1,'size' =>'7','maxlen' =>'11' ,'type' =>'code_ops' ,'ext' => array('showSide' => true) ),
      'prozedur_seite'  => array('req' => 0,'size' =>'' ,'maxlen' =>''   ,'type' =>'lookup'   ,'ext' => array('l_basic'=>'seite'), 'default' => '-', 'null' => '-'),
      'prozedur_text'   => array('req' => 0,'size' =>'' ,'maxlen' =>''   ,'type' =>'textarea' ,'ext' => '' ),
      'prozedur_version'=> array('req' => 0,'size' =>'' ,'maxlen' =>'255','type' =>'string'   ,'ext' => '' ),
      'diagnose_id'     => array('req' => 0,'size' =>'' ,'maxlen' =>''   ,'type' =>'query'    ,'ext' => $query_lokalisation ),
      'createuser'      => array('req' => 0,'size' =>'' ,'maxlen' =>'11' ,'type' =>'hidden'   ,'ext' => '' ),
      'createtime'      => array('req' => 0,'size' =>'' ,'maxlen' =>''   ,'type' =>'hidden'   ,'ext' => '' ),
      'updateuser'      => array('req' => 0,'size' =>'' ,'maxlen' =>'11' ,'type' =>'hidden'   ,'ext' => '' ),
      'updatetime'      => array('req' => 0,'size' =>'' ,'maxlen' =>''   ,'type' =>'hidden'   ,'ext' => '' )
   );

?>