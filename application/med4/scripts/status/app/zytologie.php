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
   'histologie_nr'               => $this->getFieldValue('histologie_nr'),
   'zytologie_normal'            => $this->getFieldDescription('zytologie_normal'),
   'zellveraenderung'            => $this->getFieldDescription('zellveraenderung'),
   'immunzytologie_pathologisch' => $this->getFieldDescription('immunzytologie_pathologisch'),
   'zytogenetik_normal'          => $this->getFieldDescription('zytogenetik_normal')
);

$data = array(
   array('lbl' => $this->getFieldLabel('histologie_nr'),                'value' => $content['histologie_nr'],                 'connector' => ' '),
   array('lbl' => $this->getFieldLabel('zytologie_normal'),             'value' => $content['zytologie_normal'],              'connector' => ': '),
   array('lbl' => $this->getFieldLabel('zellveraenderung'),             'value' => $content['zellveraenderung'],              'connector' => ': '),
   array('lbl' => $this->getFieldLabel('immunzytologie_pathologisch'),  'value' => $content['immunzytologie_pathologisch'],   'connector' => ': '),
   array('lbl' => $this->getFieldLabel('zytogenetik_normal'),           'value' => $content['zytogenetik_normal'],            'connector' => ': ')
);

if (check_array_content($content) !== true) {
   $data = '-';
}

$this
   ->setStatus('form_date', $this->getFieldValue('datum'))
   ->setStatus('erkrankung_id', $this->getFieldValue('erkrankung_id'))
   ->setStatus('form_data', $data)
;

?>