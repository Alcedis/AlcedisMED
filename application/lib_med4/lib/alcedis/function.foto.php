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

/*
 * Bestimmt den Typ eines Bildes (Jpg, PNG oder GIF)
 *
 * @param     string    $path
 * @return    string / false
 */
function foto_get_type($path)
{
   // getimagesize gibt false zurück, wenn File kein gültiges Jpg, PNG oder GIF ist.
   // Kleiner, aber hier feiner, workaround, da es in der PHP stdlib zur Zeit keine
   // Möglichkeit gibt den korrekten MIME-Type eines Files auszulessen (*grml* ...)
   $ret_getimagesize = @getimagesize($path);
   $img_mimetype = isset($ret_getimagesize['mime']) ? $ret_getimagesize['mime'] : '';
   switch($img_mimetype)
   {
      case 'image/jpeg':
         $img_type = 'jpg';
         break;
      case 'image/png':
         $img_type = 'png';
         break;
      case 'image/gif':
         $img_type = 'gif';
         break;

      default:
         unlink($path);
         return false;
         break;
   }
   return $img_type;
}

/*
 * Gibt binärdaten so aus, das man sie ohne Bedenken in eine DB speichern kann
 *
 * @param   string   $path
 * @return  string   $bytes
 *
 */
function foto_make_bytes($path)
{
   $bytes = addslashes(fread(fopen($path,'r'),filesize($path)));
   return $bytes;
}

/*
 * Erstellt ein Thumbnail
 *
 * @param   string   $picture
 * @param   string   $thumbnail
 * @param   string   $img_type
 * @param   int      $new_w
 * @param   int      $new_h
 *
 * Erklärung:
 * create_thumbnail( 'Pfad der Quell-Datei',
 *                   'Pfad der Ziel-Datei',
 *                   'Typ des Bildes',
 *                   'Max. Breite, die das Thumbnail haben soll',
 *                   'Max. Höhe, die das Thumbnail haben soll' )
 *
 */
function foto_create_thumbnail($picture, $thumbnail, $img_type, $new_w, $new_h)
{
   switch($img_type)
   {
      case 'jpg':
      case 'jpeg':
         $src_img = imagecreatefromjpeg($picture);
         break;
      case 'png':
         $src_img = imagecreatefrompng($picture);
         break;
      case 'gif':
         $src_img = imagecreatefromgif($picture);
         break;
   }

   $old_w = imageSX($src_img);
   $old_h = imageSY($src_img);

   // Größe berechnen
   if($old_w > $new_w)
   {
      $thumb_w = $new_w;
      $thumb_h = $old_h * ($new_w / $old_w);
      if($thumb_h > $new_h)
      {
         $thumb_w = $thumb_w * ($new_h / $thumb_h);
         $thumb_h = $new_h;
      }
   }
   elseif($old_w <= $new_w && $old_h <= $new_h)
   {
      $thumb_w = $old_w;
      $thumb_h = $old_h;
   }
   elseif($old_w <= $new_w && $old_h > $new_h)
   {
      $thumb_w = $old_w * ($new_h / $old_h);
      $thumb_h = $new_h;
   }

  $dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);
  imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_w, $old_h);

   switch($img_type)
   {
      case 'jpg':
      case 'jpeg':
         imagejpeg($dst_img, $thumbnail);
         break;
      case 'png':
         imagepng($dst_img, $thumbnail);
         break;
      case 'gif':
         imagegif($dst_img, $thumbnail);
         break;
   }

  imagedestroy($dst_img);
  imagedestroy($src_img);
}
