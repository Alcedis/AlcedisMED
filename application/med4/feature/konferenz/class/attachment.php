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

class conferenceAttachment extends alcEmail
{
    protected $_conferenceId = null;

    protected $_cAttachments = array('dokument' => array(), 'epikrise' => array());

    /**
     *
     * @var upload
     */
    protected $_upload = null;


    /**
     * @param ressource $db
     * @param smarty $smarty
     * @param int $conferenceId
     * @return conferenceAttachment
     */
    public static function create($db, $smarty, $conferenceId) {
        return new self($db, $smarty, $conferenceId);
    }

    public function __construct($db, $smarty, $conferenceId) {
        parent::__construct($db, $smarty);

        $this->_init($conferenceId);

        return $this;
    }


    protected function _init($conferenceId)
    {
        $this->_conferenceId = $conferenceId;

        $this->_upload = upload::create($this->_smarty)
            ->setDestinations(array('conference' => array('document', 'konferenz', $conferenceId)))
            ->setDestinations(array('patient' => array('doc', 'doc')))
        ;

        $this
            ->_getDocuments()
            ->_getProtocolls()
            ->_getConferenceData()
        ;
    }

    /**
     *
     * @see alcEmail::setTemplate()
     * @return conferenceAttachment
     */
    public function setTemplate($templateName) {
        parent::setTemplate($templateName);

        return $this;
    }

    /**
     * register the attachments to a user for sending it via mail
     * if noCheck == true, the "send" array in recipient don´t have to be set
     * there will be assigned alle "dokument" and "epikrise" Files :D
     * to the user
     *
     * @param array $recipients
     * @param boolean $noCheck
     * @return conferenceAttachment
     */
    public function registerAttachmentToRecipients($recipients, $noCheck = false)
    {
        foreach ($recipients as $userId => $recipient) {
            foreach ($this->_cAttachments AS $type => $attachments) {
                if ($noCheck === true) {
                    foreach ($attachments as $attachment) {
                        $this->addAttachmentToRecipient($userId, $attachment);
                    }
                } else {
                    if (is_array($recipient) === true && array_key_exists('send', $recipient) === true && array_key_exists($type, $recipient['send']) === true) {
                        foreach ($attachments as $attachment) {
                            $this->addAttachmentToRecipient($userId, $attachment);
                        }
                    }
                }
            }
        }



        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see alcEmail::selectRecipients()
     */
    public function selectRecipients($userIds) {
        parent::selectRecipients($userIds);

        return $this;
    }


    /**
     * get conference information
     */
    protected function _getConferenceData()
    {
        $query = "
            SELECT
                k.*,
                DATE_FORMAT(k.datum, '%d.%m.%Y') AS 'datum',
                CONCAT_WS(' ', u.titel, u.vorname, u.nachname) AS 'moderator_fullname',
                u.titel       AS 'moderator_titel',
                u.nachname    AS 'moderator_nachname',
                u.vorname     AS 'moderator_vorname',
                u.telefon     AS 'moderator_telefon',
                u.telefax     AS 'moderator_fax',
                u.handy       AS 'moderator_handy',
                u.email       AS 'moderator_email'
            FROM konferenz k
                LEFT JOIN user u ON k.moderator_id = u.user_id
            WHERE
                k.konferenz_id = '{$this->_conferenceId}'
            GROUP BY
                k.konferenz_id
        ";

        $data = sql_query_array($this->_db, $query);

        if (count($data) == 1) {
            foreach (reset($data) AS $var => $value) {
                if (str_starts_with($var, 'moderator') === false) {
                    $this->assignToTemplate('konferenz_' . $var, $value);
                } else {
                    $this->assignToTemplate($var, $value);
                }
            }
        }

        return $this;
    }


    /**
     * get all documents from conference
     * @return conferenceAttachment
     */
    protected function _getDocuments()
    {
        $query = "
            SELECT
                kd.konferenz_dokument_id,
                IF(kd.dokument_id IS NULL, 'conference', 'patient') AS 'destination',
                IFNULL(d.dokument, kd.datei) AS 'name'
            FROM konferenz_dokument kd
                LEFT JOIN dokument d ON kd.dokument_id = d.dokument_id
            WHERE
                kd.konferenz_id = '{$this->_conferenceId}'
        ";

        $this->_attach('dokument', sql_query_array($this->_db, $query));

        return $this;
    }


    /**
     * get all conference protocolls
     * @return conferenceAttachment
     */
    protected function _getProtocolls()
    {
        $query = "
            SELECT
                kp.konferenz_patient_id,
                CONCAT_WS('',
                    'konferenz_patient_',
                    kp.konferenz_patient_id,
                    '/',
                    'protokoll.pdf'
                ) AS 'name',

                CONCAT_WS('.',
                    CONCAT_WS('_',
                        p.nachname,
                        p.vorname,
                        DATE_FORMAT(p.geburtsdatum, '%d.%m.%Y')
                    ),
                    'pdf'
                ) AS 'label',
                'patient' AS destination
            FROM konferenz_patient kp
                LEFT JOIN patient p ON kp.patient_id = p.patient_id
            WHERE
                kp.konferenz_id = '{$this->_conferenceId}'
            GROUP BY
                kp.konferenz_patient_id
        ";

        $this->_attach('epikrise', sql_query_array($this->_db, $query));

        return $this;
    }

    /**
     *
     * @param unknown_type $type
     * @param unknown_type $datasets
     * @return conferenceAttachment
     */
    private function _attach($type, $datasets)
    {
        foreach ($datasets as $dataset) {
            $filePath = $this->_upload->getDestination($dataset['destination']);
            $fileName = $dataset['name'];
            $fullPath = concat(array($filePath, $fileName), '');

            $label = array_key_exists('label', $dataset) === true ? $dataset['label'] : null;

            if (is_file($fullPath) === true) {
                $this->_cAttachments[$type][] = md5($fullPath);

                $this->registerAttachment($fullPath, $fullPath, $label);
            }
        }

        return $this;
    }

}

?>
