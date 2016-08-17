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

$content = array(
    'idnrpat'         => $this->getFieldValue('idnrpat'),
    'aufenthaltId'    => $this->getFieldValue('aufenthalt_id'),
    'aufndatum'       => $this->getFieldValue('aufndatum'),
    'entldatum'       => $this->getFieldValue('entldatum')
);

$formDate = $content['aufndatum'];

$data = array(
    concat(array(
        concat(array('Ident.:',$content['idnrpat']),' '),
        concat(array(todate($content['aufndatum'], 'de'), todate($content['entldatum'], 'de')), ' - ')
        ), ' / '
    )
);

if (check_array_content($content) !== true) {
    $data = '-';
}

$this
   ->setStatus('form_date', $formDate)
   ->setStatus('erkrankung_id', $this->getFieldValue('erkrankung_id'))
   ->setStatus('form_data', $data)
;

?>