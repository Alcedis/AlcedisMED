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

switch($action)
{
    case 'anlage':
        $sections     = array('dokument', 'epikrise');
        $recipients   = array();

        //Sections aufbauen
        foreach ($sections as $section) {
            if (bflBuffer::notEmpty("anlage_{$section}") === true) {
                foreach (bflBuffer::get("anlage_{$section}", 'add') as $idCompilation) {
                    $ids    = explode('|', $idCompilation);
                    $ktId   = array_key_exists(0, $ids) === true ? $ids[0] : null;
                    $uId    = array_key_exists(1, $ids) === true ? $ids[1] : null;
                    $kaId   = array_key_exists(2, $ids) === true ? $ids[2] : null;

                    if ($ktId !== null && $uId !== null) {
                        if (array_key_exists($uId, $recipients) === false) {
                            $recipients[$uId] = array(
                                'ktId' => $ktId,
                                'kaId' => $kaId,
                                'send' => array()
                            );
                        }

                        $recipients[$uId]['send'][$section] = 1;
                    }
                }
            }
        }

        if (count($recipients) > 0 && strlen($konferenz_id) > 0) {

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

            require_once 'feature/konferenz/class/attachment.php';

            //Initialize email class
            $alcMail = conferenceAttachment::create($db, $smarty, $konferenz_id)
                ->setTemplate('post_conference')
                ->selectRecipients(array_keys($recipients))
            ;

            if (appSettings::get('email_attachment') === true) {
                $alcMail->registerAttachmentToRecipients($recipients);
            }

            $alcMail
                ->setFrom($moderator['email'])
                ->send()
            ;

            $dateDe = date('d.m.Y H:i');

            //Logging
            foreach ($alcMail->getRecipients() as $stat) {
                $uId          = $stat['data']['user_id'];
                $ktId         = $recipients[$uId]['ktId'];
                $kaId         = $recipients[$uId]['kaId'];
                $sections     = $recipients[$uId]['send'];

                $tmpFields = $fields;

                //Update
                if (strlen($kaId) > 0) {
                    $ka = reset(sql_query_array($db, "SELECT * FROM konferenz_abschluss WHERE konferenz_abschluss_id = '{$kaId}'"));

                    foreach ($sections as $section => $active) {
                        $ka["{$section}_status"] .= "<div class='email_{$stat['email']['status']}'>{$dateDe}</div>";
                    }

                    array2fields($ka, $tmpFields);
                    execute_update($smarty, $db, $tmpFields, 'konferenz_abschluss', "konferenz_abschluss_id = '{$kaId}'", 'update', "", true);
                } else {
                    //Insert
                    $ka = array('konferenz_teilnehmer_id' => $ktId);

                    foreach ($sections as $section => $active) {
                        $ka["{$section}_status"] = "<div class='email_{$stat['email']['status']}'>{$dateDe}</div>";
                    }

                    array2fields($ka, $tmpFields);

                    execute_insert($smarty, $db, $tmpFields, 'konferenz_abschluss', 'insert', true);
                }
            }

            $smarty
                ->assign('message', $config['msg_sent'])
            ;
        }

    break;
}

?>