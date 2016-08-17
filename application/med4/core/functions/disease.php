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

//Baumansicht HTML-Generator Funktion
function buildDiseaseTree($config, $referenceList, $restrictions, $tree, $param, $level = 0)
{
   $html = '';

   foreach ($tree as $element) {
      $name       = $element['form_name'];
      $date       = $element['form_date_de'];
      $data       = $element['form_data'];
      $form_id    = $element['form_id'];
      $form       = $element['form'];
      $status     = $element['form_status'];
      $statusBez  = $element['form_status_bez'];
      $statusId   = $element['status_id'];
      $lock       = $element['status_lock'];
      $formParam  = $element['form_param'];
      $reportParam = $element['report_param'];
      $firstLine  = concat(array($date, $name), ' - ');

      if ($form == 'qs_18_1_b') {
          $formLink  = "<a href='index.php?page=view.qs_18_1&amp;{$form}_id={$form_id}{$param}'>
              <img src='media/img/base/edit.png' alt=''/>
          </a>";
      } else {
          $formLink  = "<a href='index.php?page=rec.{$form}&amp;{$form}_id={$form_id}{$param}'>
                <img src='media/img/base/edit.png' alt=''/>
          </a>";
      }

      $treeIcon = "<div style='float:left; margin-right:10px'>
        <img style='padding-left:6px' src='media/img/base/tree-line.png' alt='' />
      </div>
      ";

      if ($form == 'qs_18_1_b') {

          $statusLink = "
             <span style='display:none'>{$status}</span>
             <a class='ampel' href='index.php?page=view.qs_18_1&amp;{$form}_id={$form_id}{$param}'>
                <img class='popup-trigger' src='media/img/app/ampel/{$status}.png' alt='{$status}' title=''/>
                <span class='info-popup above before' style='display:none;'>Status: {$statusBez}</span>
             </a>
          ";
      } else {
          $statusLink = "
              <span style='display:none'>{$status}</span>
              <a class='ampel' href='index.php?page=rec.{$form}&amp;{$form}_id={$form_id}{$param}'>
                  <img class='popup-trigger' src='media/img/app/ampel/{$status}.png' alt='{$status}' title=''/>
                  <span class='info-popup above before' style='display:none;'>Status: {$statusBez}</span>
              </a>
          ";
      }

      $add = null;

      if (in_array($form, array('dmp_brustkrebs_eb', 'dmp_brustkrebs_fb')) == true) {
         $dmpType = substr($form, -2);
         $add = "<a href='index.php?page=dmp_popups&type=dmp_{$dmpType}_bogen&id={$form_id}' target='_blank' >
               <img class='popup-trigger' src='media/img/app/dmp/btn_edmp_very_small.png' alt='{#lbl_dmp_bogen#}' title=''/>
               <span class='info-popup above before' style='display:none;'>{$config["form_{$form}"]}</span>
            </a>
         ";
      }

       if (in_array($form, array('dmp_brustkrebs_ed_2013', 'dmp_brustkrebs_ed_pnp_2013', 'dmp_brustkrebs_fd_2013')) == true) {
           $dmpType = substr($form, -7);
           $add = "<a href='index.php?page=dmp_2013_popups&type=dmp_{$dmpType}_bogen&id={$form_id}' target='_blank' >";

           if (strlen($reportParam) > 0) {
               $add .= "<img class='popup-trigger' src='media/img/app/dmp/btn_edmp_export_very_small.png' alt='{#lbl_dmp_bogen#}' title=''/>";
           } else {
               $add .= "<img class='popup-trigger' src='media/img/app/dmp/btn_edmp_very_small.png' alt='{#lbl_dmp_bogen#}' title=''/>";
           }

           $add .= "<span class='info-popup above before' style='display:none;'>{$config["form_{$form}"]} ";

           if (strlen($reportParam) > 0) {
               $add .= "{$config['lbl_dmp_exported']} {$reportParam}";
           }

           $add .= "</span>
            </a>
         ";
       }


      if ($form == 'foto') {
          $add = "<a href='index.php?page=rec.foto&amp;action[file][foto]=1&amp;foto_id={$form_id}'>
                <img class='popup-trigger' src='media/img/base/images.png' alt='' title=''/>
                <span class='info-popup above before' style='display:none;'>
                    <img style='margin:5px 11px;' class='thumb-img' alt='Lade...' src='index.php?page=foto&amp;type=thumbnail&amp;thumb=75&amp;foto_id={$form_id}'/>
                </span>
            </a>
          ";
      }

      if ($form == 'dokument') {
          $add = "<a href='index.php?page=rec.dokument&amp;action[file][dokument]=1&amp;dokument_id={$form_id}'>
              <img class='popup-trigger' src='media/img/base/package.png' alt='' title=''>
              <span class='info-popup above before' style='display:none;'>{$config['download']}</span>
          </a>
          ";
      }

      $lockLink = null;

      if ($form != 'qs_18_1_b') {
          $lockLink = "
             <a class='ampel' href='index.php?page=lock&amp;location=view.erkrankung&amp;selected={$statusId}'>
                <img src='media/img/app/lock/{$lock}.png' alt='{$lock}' title='' />
             </a>
          ";
      }

      $refLink = "<span class='no-search'>" .
        buildReferenceList($config, $statusId, $form, $referenceList, $form_id, $param, $formParam, $restrictions) .
        '</span>'
      ;

      switch (true) {
          case ($level == 2):
              $paddingLeft      = 38;
              $treeIconActive   = $treeIcon;
              $width            = 512;
              break;
          case ($level == 1):
              $paddingLeft      = 5;
              $treeIconActive   = $treeIcon;
              $width            = 545;
              break;
          default:
              $paddingLeft      = 0;
              $treeIconActive   = null;
              $width            = 580;
              break;
      }

      $html .= "
        <tr valign='top'>
          <td colspan='3' style='padding-left:{$paddingLeft}px;padding-top:6px'>
            <div style='padding-left:6px'>
                $treeIconActive
                <div style='float:left'>$formLink</div>
                <div style='float:left;padding-left:6px;width:{$width}px'>
                    <div style='height:23px'>
                        <div style='float:left'>
                            <a href='index.php?page=rec.{$form}&amp;{$form}_id={$form_id}{$param}'>
                                <strong>$firstLine</strong>
                            </a>
                        </div>
                        <div style='float:left; padding-left:5px; padding-top:2px'>
                           {$add}
                        </div>
                    </div>
                    <div style='margin-bottom:6px; font-size: 8.5pt!important; word-wrap: break-word'>
                    {$data}
                    </div>
                  </div>
              </div>
          </td>
          <td style='padding-top:6px'>$refLink</td>
          <td style='padding-top:6px'>$lockLink</td>
          <td style='padding-top:6px'>$statusLink</td>
        </tr>
      ";

      if (isset($element['branches'])) {
         $html .= buildDiseaseTree($config, $referenceList, $restrictions, $element['branches'], $param, $level + 1);
      }
   }

   return $html;
}


//Only required in view.erkrankung
/**
 * buildReferenceList
 * creates html for reference
 *
 * @param   $config        array
 * @param   $formName      string
 * @param   $referenceList array
 * @return  string
 */
function buildReferenceList($config, $statusId, $formName, $referenceList, $formId, $param='', $formParam, $restrictions)
{
   $html = '';

   if (array_key_exists($formName, $referenceList) === true) {
      $list = $referenceList[$formName];

      $restrictionsList = array_key_exists($formName, $restrictions) === true ? $restrictions[$formName] : null;

      foreach ($list as $i => $entry) {
         if ($restrictionsList !== null && array_key_exists($entry, $restrictionsList) === true) {
            $restriction = $restrictionsList[$entry];

            if ($restriction != $formParam) {
               continue;
            }
         }

         $parentId   = "&amp;{$formName}_id={$formId}";
         $link       = "index.php?page=rec.{$entry}{$parentId}{$param}";

         $html .= "<a style='line-height:20px;' class='hover-link' href='{$link}'>
            <img style='float:left;padding-right:5px;' src='media/img/base/add.png' alt='{$config[$entry]}' title=''/>
               {$config[$entry]}
            </a><br/>"
         ;
      }
   }

   $return = strlen($html) == 0
      ? ''
      : "<span class='add-ref-form'>
            <img style='float:left; padding-right:2px;' src='media/img/base/add-user-small.png' alt='add-ref-form' title='' />
         </span>
         <div class='reference-list'>{$html}</div>"
      ;

   return $return;
}

//Prüft ob ein Formular innerhalb einer Erkrankung anhand der Rechte der Erkrankung des Nutzers, gespeichert werden darf
function getDiseaseSaveRight()
{
   $forbidden = false;

   if (isset($_SESSION['sess_erkrankung_data']) === true) {
      $recht_erkrankung = isset($_SESSION['sess_recht_erkrankung']) === true ? $_SESSION['sess_recht_erkrankung'] : array();
      $erkrankung_code  = isset($_SESSION['sess_erkrankung_data']['code']) ? $_SESSION['sess_erkrankung_data']['code'] : null;

      if ($erkrankung_code === null || in_array($erkrankung_code, $recht_erkrankung) === false){
         $forbidden = true;
      }
   }

   return $forbidden;
}


function deleteForm($db, $smarty, $table, $reference, $form_id, $fieldLoaction = 'app')
{
   $fields     = $smarty->widget->loadExtFields("fields/{$fieldLoaction}/{$table}.php");
   $primaryKey = get_primaer_key($table);

   $result     = sql_query_array($db, "SELECT * FROM `{$table}` WHERE {$reference}_id = {$form_id}");

   foreach ($result AS $dataset) {
      $value      = $dataset[$primaryKey];
      $tmpFields  = $fields;

      array2fields($dataset, $tmpFields);

      action_delete($smarty, $db, $tmpFields, $table, $value, 'delete', null, '', '', true);

      mysql_query("DELETE FROM `status` WHERE form = '{$table}' AND form_id = {$value}");
   }
}


function deleteReference($db, $table, $reference, $formId)
{
   mysql_query("UPDATE `{$table}` SET {$reference}_id = NULL WHERE {$reference}_id = {$formId}");
}



function deleteDisease($db, $smarty, $tables, $erkrankungId)
{
   $affectedRows  = 0;
   $dbTables      = relationManager::get();
   $tmpDbTables   = sql_query_array($db, 'SHOW TABLES');
   $config        = $smarty->get_config_vars();

   $except = array('status', 'nachsorge', 'nachsorge_erkrankung', 'erkrankung', 'lock', 'abschluss_ursache');

   foreach ($tables as $table) {
      if (in_array($table, $dbTables) == true && in_array($table, $except) == false) {
         $fields     = $smarty->widget->loadExtFields("fields/app/{$table}.php");

         $results    = sql_query_array($db, "SELECT * FROM `{$table}` WHERE erkrankung_id = '{$erkrankungId}'");

         $primaryKey = get_primaer_key($table);

         foreach ($results as $result) {
            $value      = $result[$primaryKey];
            $tmpFields  = $fields;

            array2fields($result, $tmpFields);

            action_delete($smarty, $db, $tmpFields, $table, $value, 'delete');

            $affectedRows++;
         }
      }
   }

   //Nachsorgen löschen
   $result = sql_query_array($db, "SELECT * FROM nachsorge_erkrankung WHERE erkrankung_weitere_id = '{$erkrankungId}'");

   $nachsorgeErkrankungFields = $smarty->widget->loadExtFields("fields/app/nachsorge_erkrankung.php");
   $nachsorgeFields           = $smarty->widget->loadExtFields("fields/app/nachsorge.php");

   $primaryKeyNachsorgeErkrankung = get_primaer_key('nachsorge_erkrankung');
   $primaryKeyNachsorge           = get_primaer_key('nachsorge');

   foreach ($result as $nachsorge_erkrankung) {
      $value   = $nachsorge_erkrankung[$primaryKeyNachsorgeErkrankung];
      $fields  = $nachsorgeErkrankungFields;

      $nachsorge_erkrankung_id   = $nachsorge_erkrankung['nachsorge_erkrankung_id'];
      $nachsorge_id              = $nachsorge_erkrankung['nachsorge_id'];

      array2fields($nachsorge_erkrankung, $fields);

      action_delete($smarty, $db, $fields, 'nachsorge_erkrankung', $value, 'delete', null, '', '', true);
      $affectedRows++;

      //Sobald gelöschter Eintrag dem letzten Entspricht, komplette Nachsorge löschen
      $remain = strlen(dlookup($db, 'nachsorge_erkrankung', 'nachsorge_erkrankung_id', "nachsorge_id = '{$nachsorge_id}'")) ? true : false;

      if ($remain == false) {
         $fields        = $nachsorgeFields;
         $result        = reset(sql_query_array($db, "SELECT * FROM nachsorge WHERE nachsorge_id = '{$nachsorge_id}'"));
         $value         = $result[$primaryKeyNachsorge];

         array2fields($result, $fields);

         action_delete($smarty, $db, $fields, 'nachsorge', $value, 'delete');
         $affectedRows++;
      } else {
         statusReportParam::fire('nachsorge', $nachsorge_id);
      }
   }

   //Erkrankung
   $primaryKey    = get_primaer_key('erkrankung');
   $fields        = $smarty->widget->loadExtFields("fields/app/erkrankung.php");
   $result        = reset(sql_query_array($db, "SELECT * FROM erkrankung WHERE erkrankung_id = '$erkrankungId'"));
   $value         = $result[$primaryKey];

   array2fields($result, $fields);

   action_delete($smarty, $db, $fields, 'erkrankung', $value, 'delete');
   $affectedRows++;

    //Erkrankung Synchron
    $fields = $smarty->widget->loadExtFields("fields/app/erkrankung_synchron.php");

    $query = "SELECT * FROM erkrankung_synchron WHERE erkrankung_id = '{$erkrankungId}' OR erkrankung_synchron = '{$erkrankungId}'";

    foreach (sql_query_array($db, $query) as $es) {
        $tmpFields = $fields;

        array2fields($es, $tmpFields);

        action_delete($smarty, $db, $tmpFields, 'erkrankung_synchron', $es['erkrankung_synchron_id'], 'delete');

        $affectedRows++;
    }


   $sessWarn   = sprintf($config['lbl_erkrankung_deleted'], $affectedRows);
   $sessWarn   .= $affectedRows == 1 ? $config['lbl_datensatz_single'] : $config['lbl_datensatz_multi'];

   $_SESSION['sess_warn'][] = $sessWarn;

   return true;
}


/**
 * returns the situations (e.g anlass of the desease)
 *
 * @param $db
 * @param $erkrankungId
 * @param $date
 */
function getSituations($db, $erkrankungId, $date = null)
{
   $having = $date !== null ? "'$date' BETWEEN start_date AND end_date" : '1';

   $query = "
      SELECT
         t.anlass,

         IF(x.first_date=MIN(IF(t2.anlass = t.anlass, t2.datum_sicherung, null)), '0000-00-00', MIN(t.datum_sicherung))                                       AS 'start_date',
         DATE_SUB(IFNULL(MIN(IF(t2.anlass != t.anlass AND t2.datum_sicherung > t.datum_sicherung, t2.datum_sicherung,null) ), '9999-12-31'), INTERVAL 1 DAY)  AS 'end_date'
      FROM erkrankung e
         INNER JOIN tumorstatus t    ON t.erkrankung_id = e.erkrankung_id
              LEFT JOIN tumorstatus t2    ON t2.erkrankung_id = e.erkrankung_id

              INNER JOIN (
                  SELECT
                      erkrankung_id,
                      MIN(datum_sicherung) AS first_date
                  FROM tumorstatus
                  WHERE
                     erkrankung_id = '{$erkrankungId}'
                  GROUP BY
                    erkrankung_id
              ) x                                           ON x.erkrankung_id = e.erkrankung_id
      WHERE
          e.erkrankung_id = '{$erkrankungId}'
      GROUP BY
          t.anlass
      HAVING
         {$having}
   ";

   return sql_query_array($db, $query);
}


?>
