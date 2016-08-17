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

function updateKonferenzTeilnehmer($db, $konferenzId)
{
   mysql_query("
      UPDATE
        konferenz
      SET
        teilnehmer     = (SELECT COUNT(t.konferenz_teilnehmer_id) FROM konferenz_teilnehmer t WHERE t.konferenz_id = '{$konferenzId}'),
        teilnehmer_bes = (SELECT COUNT(t.konferenz_teilnehmer_id) FROM konferenz_teilnehmer t WHERE t.konferenz_id = '{$konferenzId}' AND t.teilgenommen IS NOT NULL)
      WHERE
        konferenz_id = '{$konferenzId}'
   ", $db);

   //Aus Gründen der Übersichtlichkeit
   $protocol = protocol::create($db, null, false);

   foreach (sql_query_array($db, "SELECT * FROM konferenz_patient WHERE konferenz_id = '{$konferenzId}'") AS $kp) {
      $protocol->makeDirty(protocol::$kp, $kp['konferenz_patient_id']);
   }
}

switch($action) {

   case 'profil':

      $teilnehmerProfilId  = isset($_REQUEST['teilnehmer_profil_id'])   ? $_REQUEST['teilnehmer_profil_id'] : '';

      if (strlen($teilnehmerProfilId) > 0 && strlen($konferenz_id) > 0) {

         $profil = reset(sql_query_array($db, "
             SELECT
                bez,
                IFNULL(user_list, \"''\") as user_list

             FROM konferenz_teilnehmer_profil
             WHERE konferenz_teilnehmer_profil_id = '{$teilnehmerProfilId}'

         "));

         $userList       = $profil['user_list'];
         $avaiable       = array();
         $updatedKt      = false;

         foreach (sql_query_array($db, "SELECT user_id FROM konferenz_teilnehmer WHERE konferenz_id = '{$konferenz_id}' AND user_id IN ($userList)") as $kt) {
            $avaiable[] = $kt['user_id'];
         }

         foreach (sql_query_array($db, "SELECT * FROM konferenz_teilnehmer WHERE konferenz_id = '{$konferenz_id}' AND user_id NOT IN ($userList)") AS $kt) {
            $tmpFields = $fields;
            array2fields($kt, $tmpFields);

            execute_delete($smarty, $db, $tmpFields, 'konferenz_teilnehmer', "konferenz_teilnehmer_id = '{$kt['konferenz_teilnehmer_id']}'", 'delete', true);

            $updatedKt = true;
         }

         $userListArr = $userList !== "''" ? explode(',', $userList) : array();

         //insert rest
         foreach ($userListArr as $userId) {
            if (in_array($userId, $avaiable) == false) {
               $updatedKt = true;
               $tmpFields = $fields;

               $data = array(
                   'konferenz_id'   => $konferenz_id,
                   'user_id'        => $userId
                );

                array2fields($data, $tmpFields);

                execute_insert($smarty, $db, $tmpFields, 'konferenz_teilnehmer', 'insert', true);
            }
         }

         if ($updatedKt === true) {
             updateKonferenzTeilnehmer($db, $konferenz_id);

             $smarty
                ->assign('message', sprintf($config['msg_profile_loaded'], $profil['bez']))
             ;
         }
      } else {
         $smarty
            ->assign('error', $config['msg_select_profile'])
            ->assign('error_on_dropdown', true)
         ;
      }

      break;

    case 'email':
        if (bflBuffer::notEmpty('email') === true && strlen($konferenz_id) > 0) {
            $moderator = reset(sql_query_array($db, "
                SELECT
                    u.user_id,
                    u.email
                FROM konferenz k
                    LEFT JOIN user u ON k.moderator_id = u.user_id
                WHERE
                    k.konferenz_id = '{$konferenz_id}'
                GROUP BY
                    k.konferenz_id
            "));

            if (strlen($moderator['user_id']) == 0) {
                $smarty->assign('warn', $config['msg_no_moderator']);
                break;
            } elseif (strlen($moderator['email']) == 0) {
                $smarty->assign('warn', $config['msg_no_moderator_email']);
                break;
            }

            $recipients   = array();

            foreach (bflBuffer::get('email', 'add') as $idCompilation) {
                $ids = explode('|', $idCompilation);
                $ktId   = array_key_exists(0, $ids) === true ? $ids[0] : null;
                $uId    = array_key_exists(1, $ids) === true ? $ids[1] : null;

                if ($ktId !== null && $uId !== null) {
                    if (array_key_exists($uId, $recipients) === false) {
                        $recipients[$uId] = array(
                            'ktId' => $ktId
                        );
                    }
                }
            }

            if (count($recipients) > 0) {
                $dateDe = date('d.m.Y H:i');
                require_once 'feature/konferenz/class/attachment.php';

                //Send Mail class
                $alcMail = conferenceAttachment::create($db, $smarty, $konferenz_id)
                    ->setTemplate('invitation_conference')
                    ->selectRecipients(array_keys($recipients))
                ;

                if (appSettings::get('email_attachment') === true) {
                    $alcMail->registerAttachmentToRecipients($recipients, true);
                }

                $alcMail
                    ->setFrom($moderator['email'])
                    ->send()
                ;

                 //Logging
                foreach ($alcMail->getRecipients() as $stat) {
                    $uId          = $stat['data']['user_id'];
                    $ktId         = $recipients[$uId]['ktId'];

                    $ktFields = $fields;

                    //Update
                    $kt = reset(sql_query_array($db, "SELECT * FROM konferenz_teilnehmer WHERE konferenz_teilnehmer_id = '{$ktId}'"));

                    $kt["email_status"] .= "<div class='email_{$stat['email']['status']}'>{$dateDe}</div>";

                    array2fields($kt, $ktFields);
                    execute_update($smarty, $db, $ktFields, 'konferenz_teilnehmer', "konferenz_teilnehmer_id = '{$ktId}'", 'update', "", true);
                }

                $smarty
                    ->assign('message', $config['msg_sent'])
                ;
            }
        }

        break;

   case 'confirm':
       if (bflBuffer::NotEmpty('teilgenommen') === true) {
           $add    = implode(',', bflBuffer::get('teilgenommen', 'add'));
           $remove = implode(',', bflBuffer::get('teilgenommen', 'remove'));

           $updatedKt   = false;

           //Add Kt
           if (strlen($add) > 0) {
              foreach (sql_query_array($db, "SELECT * FROM konferenz_teilnehmer WHERE konferenz_teilnehmer_id IN ({$add})") AS $kt) {
                 $tmpFields = $fields;

                 $kt['teilgenommen'] = 1;

                 array2fields($kt, $tmpFields);

                 execute_update($smarty, $db, $tmpFields, 'konferenz_teilnehmer', "konferenz_teilnehmer_id = '{$kt['konferenz_teilnehmer_id']}'", 'update', "", true);

                 $updatedKt = true;
              }
           }

           //remove Kt
           if (strlen($remove) > 0) {
              foreach (sql_query_array($db, "SELECT * FROM konferenz_teilnehmer WHERE konferenz_teilnehmer_id IN ({$remove})") AS $kt) {
                 $tmpFields = $fields;

                 $kt['teilgenommen'] = NULL;

                 array2fields($kt, $tmpFields);

                 execute_update($smarty, $db, $tmpFields, 'konferenz_teilnehmer', "konferenz_teilnehmer_id = '{$kt['konferenz_teilnehmer_id']}'", 'update', "", true);

                 $updatedKt = true;
              }
           }

           if ($updatedKt === true) {
              updateKonferenzTeilnehmer($db, $konferenz_id);

              $smarty
                 ->assign('message', $config['lbl_teilnehmer_bestaetigt'])
              ;
         }
      }
      break;

   case 'remove':
        if (bflBuffer::NotEmpty('entfernen') === true) {
            $add = implode(',', bflBuffer::get('entfernen', 'add'));

            if (strlen($add) > 0) {

             $removed = 0;

             $kaFields = $widget->loadExtFieldsOnce('fields/app/konferenz_abschluss.php');

             foreach (sql_query_array($db, "SELECT * FROM konferenz_teilnehmer WHERE konferenz_teilnehmer_id IN ($add)") AS $kt) {
                $tmpFields = $fields;
                array2fields($kt, $tmpFields);
                $ktId = $kt['konferenz_teilnehmer_id'];

                //Konferenz Abschluss ebenfalls entfernen falls vorhanden
                $kaDataset = sql_query_array($db, "SELECT * FROM konferenz_abschluss WHERE konferenz_teilnehmer_id = '{$ktId}'");

                if (is_array($kaDataset) === true && count($kaDataset) > 0) {
                    $tmpKaFields = $kaFields;
                    $kaDataset = reset($kaDataset);
                    $kaId = $kaDataset['konferenz_abschluss_id'];

                    array2fields($kaDataset, $tmpKaFields);
                    execute_delete($smarty, $db, $tmpKaFields, 'konferenz_abschluss', "konferenz_abschluss_id = '{$kaId}'", 'delete', true);
                }

                execute_delete($smarty, $db, $tmpFields, 'konferenz_teilnehmer', "konferenz_teilnehmer_id = '{$ktId}'", 'delete', true);

                $removed++;
             }

             if ($removed > 0) {
                updateKonferenzTeilnehmer($db, $konferenz_id);

                $smarty
                    ->assign('message', sprintf($config['msg_removed'], $removed))
                ;
             }
         }
      }

      break;
}

?>
