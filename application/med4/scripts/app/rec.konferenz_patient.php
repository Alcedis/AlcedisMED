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

$table         = $tbl_konferenz_patient;
$form_id       = isset( $_REQUEST['konferenz_patient_id'] ) ? $_REQUEST['konferenz_patient_id'] : '';
$konferenz_id  = isset($_REQUEST['konferenz_id']) === true
   ? $_REQUEST['konferenz_id']
   : (strlen($form_id) > 0
      ? dlookup($db, 'konferenz_patient', 'konferenz_id', "konferenz_patient_id = '{$form_id}'")
      : '');

$final         = dlookup($db, 'konferenz', 'final', "konferenz_id = '{$konferenz_id}'") == 1 ? true : false;
$statusLock    = $final == true || $statusLocked == true ? true : false;

$location      = get_url('page=view.erkrankung');
$this_location = get_url("page=rec.konferenz_patient&konferenz_patient_id={$form_id}");

//Sonderfall Report View
if ($action == "report" && $statusLock == true) {
  $permission->setForbidden(false);
}

if ($permission->action($action) === true) {
    $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
    require($permission->getActionFilePath());
}

if ($statusLock == true) {
    $smarty->assign('status_locked', true);
}

$button = get_buttons($table, $form_id, $statusLock);

show_record($smarty, $db, $fields, $table, $form_id);

$xhtml = xhtmlManager::create($smarty, $table, $form_id, 'protokoll')
  ->setConvertXhtml()
  ->getXhtml()
;

$documentType = strlen($form_id) ? dlookup($db, 'vorlage_dokument', 'ausgabeformat', "vorlage_dokument_id = '" . reset($fields['vorlage_dokument_id']['value']) . "'") : null;

if (strlen($form_id) > 0) {
    $smarty->assign('convertdoc', 'kp' . $form_id);
}

$smarty
    ->assign('button', $button)
    ->assign('statusLocked', $statusLock)
    ->assign('dokumentTyp', $documentType)
    ->assign('protocolRights', $permission->action('U') === true)
    ->assign('xhtml', $xhtml)
    ->assign('ckEditorJs', DIR_LIB . "/ckeditor/ckeditor.js" )
    ->assign('caption', $config['caption_doku'])
;

//Fotos
if (appSettings::get('show_pictures') === true) {
   $queryFotos = "SELECT * FROM foto WHERE erkrankung_id = '{$erkrankung_id}'";
   $checkFotos = explode(';', $fields['fotos']['value'][0]);

   $smarty
      ->assign('fotoAktiv',  true)
      ->assign('images',     sql_query_array($db, $queryFotos))
      ->assign('checkFotos', $checkFotos)
   ;
}


function ext_err(validator $valid)
{
   $fields        = $valid->_fields;
   $db            = $valid->_db;
   $erkrankungId  = reset($fields['erkrankung_id']['value']);
   $konferenzId   = reset($fields['konferenz_id']['value']);
   $konferenzPatientId = reset($fields['konferenz_patient_id']['value']);
   $config        = $valid->_smarty->get_config_vars();

   //eCheck 6
   if (strlen(reset($fields['konferenz_id']['value'])) > 0) {

      $selfExclude = strlen($konferenzPatientId) > 0 ? " AND kp.konferenz_patient_id != '{$konferenzPatientId}'" : null;

      $result = reset(sql_query_array($db, "
         SELECT
            k.konferenz_id,
            IF(MAX(kp.konferenz_patient_id) IS NOT NULL, 1, NULL) AS 'alreadyActive',

            MAX(IF(allk.konferenz_id = kp.konferenz_id, allk.bez, NULL)) AS 'bez',
            MAX(IF(allk.konferenz_id = kp.konferenz_id, DATE_FORMAT(allk.datum, '%d.%m.%Y'), NULL)) AS 'date'

         FROM konferenz k
            LEFT JOIN konferenz allk ON allk.datum = k.datum
               LEFT JOIN konferenz_patient kp ON kp.erkrankung_id = '{$erkrankungId}' AND kp.konferenz_id = allk.konferenz_id {$selfExclude}
         WHERE
            k.konferenz_id = '{$konferenzId}'
         GROUP BY
            k.konferenz_id
      "));

      if (strlen($result['alreadyActive']) > 0) {
         $valid->set_msg('err', 10, 'konferenz_id', sprintf($config['msg_konferenz'], $result['date'], $result['bez']));
      }
   }

   //eCheck 3
   $valid->condition_and('$primaervorstellung !== "son"' , array('!primaervorstellung_sonst'));

   //eCheck 4
   $valid->condition_and('$biopsie_durch !== "son"' , array('!biopsie_durch_sonst'));

   //eCheck 5
   $valid->condition_and('in_array($mskcc, array("", 0)) == true' , array('!mskcc_ic && !mskcc_svi && !mskcc_ocd && !mskcc_lni && !mskcc_ee'));
}

?>