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

$fotoId    = isset($_REQUEST['foto_id']) === true ? $_REQUEST['foto_id'] : null;

if ($fotoId !== null) {

   $rechte = isset($_SESSION['sess_recht_erkrankung']) ? implode("','", $_SESSION['sess_recht_erkrankung']) : '';

   $result = sql_query_array($db, "
      SELECT
         f.foto   AS filename,
         IF(f.img_type = 'jpg', 'jpeg', f.img_type) AS ext
      FROM foto f
         LEFT JOIN patient p ON f.patient_id = p.patient_id
         LEFT JOIN erkrankung e ON f.patient_id = e.patient_id
      WHERE f.foto_id = '{$fotoId}' AND p.org_id = {$org_id} AND e.erkrankung IN ('{$rechte}')
      GROUP
         BY f.foto_id
   ");

   $result = $result !== false ? end($result) : null;

   if ($result !== null) {

      $type       = isset($_REQUEST['type']) && strlen($_REQUEST['type']) ? $_REQUEST['type'] : false;
      $max_size   = isset($_REQUEST['thumb']) && strlen($_REQUEST['thumb']) ? $_REQUEST['thumb'] : 300;
      $thumbWidth = $type == 'thumbnail' ? $max_size : false;

      $uploadDir  = getUploadDir($smarty, 'upload', false);
      $path        = $uploadDir['upload'] . $uploadDir['config']['image_dir'] . $result['filename'];

      if (is_file($path) === true) {
         createImage($path, $result['ext'], $thumbWidth);
      }
   }
}

?>