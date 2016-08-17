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

/**
 * Delete complete Path
 *
 * @param $f
 */
function deltree($f) {
  if (is_dir($f) == true) {
     foreach(glob($f.'/*') as $sf) {
        if (is_dir($sf) && !is_link($sf)) {
          deltree($sf);
        } else {
          unlink($sf);
        }
     }

     rmdir($f);
  }
}


function moveDir($SourceDirectory, $TargetDirectory)
{
    // add trailing slashes
    if (substr($SourceDirectory,-1) != '/'){
        $SourceDirectory .= '/';
    }
    if (substr($TargetDirectory,-1) != '/'){
        $TargetDirectory .= '/';
    }

    $handle = @opendir($SourceDirectory);
    if (!$handle) {
        die("Das Verzeichnis $SourceDirectory konnte nicht geöffnet werden.");
    }

    if (!is_dir($TargetDirectory)) {
        mkdir($TargetDirectory);
        chmod($TargetDirectory, 0777);
    }

    while ($entry = readdir($handle) ){
        if ($entry[0] == '.'){
            continue;
        }

        if (is_dir($SourceDirectory.$entry)) {
            // Unterverzeichnis
            $success = moveDir($SourceDirectory.$entry, $TargetDirectory.$entry);

        }else{
                $target = $TargetDirectory.$entry;
            copy($SourceDirectory.$entry, $target);
            chmod($target, 0777);
        }
    }
    return true;
}


function getDirContent($dir) {
   $return = array();

   if (is_dir($dir) === true) {
      $return = array_values(array_diff(scandir($dir), array('.', '..', '.svn')));

      foreach ($return as $index => $file) {
         $possibleDir = $dir . $file;

         if (is_dir($possibleDir) === true) {
            unset($return[$index]);

            $return[$file] = getDirContent($possibleDir);
         }
      }
   }

   return $return;
}

?>