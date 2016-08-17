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

switch ($action)
{
   case 'file':
         $konferenzDokumentId = is_array($_REQUEST['action'][$action]) === true ? array_keys($_REQUEST['action'][$action]) : array();

         if (count($konferenzDokumentId) > 0) {
            $id = end($konferenzDokumentId);

            $upload   = new upload($smarty);

            switch (substr($id,0,1)) {
                // Erkrankung -> Dokument
                case 'd':

                    $id = substr($id, 1);

                    $fileName = dlookup($db, 'dokument', 'dokument', "dokument_id = '{$id}'");

                    $destination = $upload
                        ->setDestinations(array('dokument' => array('doc', 'doc')))
                        ->getDestination('dokument');
                    ;

                    break;

                default:
                    $fileName      = dlookup($db, 'konferenz_dokument', 'datei', "konferenz_dokument_id = '{$id}'");
                    $konferenzId   = dlookup($db, 'konferenz_dokument', 'konferenz_id', "konferenz_dokument_id = '{$id}'");

                    $destination = $upload
                        ->setDestinations(array('datei' => array('document', 'konferenz', $konferenzId)))
                        ->getDestination('datei');
                    ;

                    break;
            }

            download::create($destination . $fileName, substr($fileName, -3))
                ->output(substr($fileName, 14))
            ;
         }

      break;
}

?>