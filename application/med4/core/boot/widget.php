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

require_once( DIR_LIB . '/alcedis/class.widget.php' );

$widgetExtLoadParams = array(
   'smarty'          => $smarty,
   'patient_id'      => $patient_id,
   'user_id'         => $user_id,
   'org_id'          => $org_id,
   'querys'          => $querys,
   'erkrankung_id'   => $erkrankung_id
);

$widget = new widget($db, $fields, $page, $widgetSelector, $widgetExtLoadParams);

featureService::getInstance()
    ->setParam('form', $page)
    ->setParam('disease', $widgetSelector)
    ->callService($widget, 'getFields')
;

$fields = $widget->getFields();

//Widget selector to smarty
$smarty->widget = $widget;

?>