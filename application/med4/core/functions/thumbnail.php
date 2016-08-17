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

function createImage ($path, $type, $thumbWidth = false) {

   $type = $type == 'jpg' ? 'jpeg' : $type;

   $create = 'imagecreatefrom' . $type;
   $output = $create($path);

   if ($thumbWidth !== false) {

       $width  = imagesx($output);
       $height = imagesy($output);

       if ($width > $height) {
            $new_width  = $width > $thumbWidth ? $thumbWidth : $width;
            $new_height = floor( $height * ( $thumbWidth / $width ) );
            $new_height = $height > $new_height ? $new_height : $height;
       } else {
            $new_height = $height > $thumbWidth ? $thumbWidth : $height;
            $new_width  = floor( $width  * ( $thumbWidth / $height ) );
            $new_width  = $width  > $new_width  ? $new_width  : $width;
       }

       $tmp_img = imagecreatetruecolor( $new_width, $new_height );

       imagecopyresized($tmp_img, $output, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

       imagedestroy($output);
       $output = $tmp_img;
   }

   $black = imagecolorallocate($output, 0, 0, 0);
   imagecolortransparent($output, $black);
   header("Content-type: image/$type");

   $render = 'image' . $type;
   $render($output);

   imagedestroy($output);
}

?>