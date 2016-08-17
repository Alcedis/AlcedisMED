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

class alcEmail
{
    protected $_db;

    /**
     *
     * @var smarty
     */
    protected $_smarty;

    protected $_recipients = array();

    protected $_template = '';

    protected $_templateName = null;

    protected $_subject = null;

    protected $_from = null;

    protected $_attachments = array();

    protected $_currentRecipient = null;

    protected $_boundary = null;

    protected $_templateAssigns = array();

    protected $_debug = false;

    public static $WAITING  = 'waiting';
    public static $ACC      = 'sent';
    public static $ERR      = 'error';
    public static $NOMAIL   = 'nomail';

    public function __construct($db, $smarty)
    {
        $this->_db = $db;
        $this->_smarty = $smarty;

        $this->_smarty->assign('db', $db);

        return $this;
    }

    public static function create($db, $smarty) {
        return new self($db, $smarty);
    }

    /**
     * Get boundary
     *
     * @access  public
     * @param   boolean $withPrefix
     * @return  string
     */
    protected function _getBoundary($withPrefix = true)
    {
        if ($this->_boundary === null) {
            $this->_boundary = '--' . strtoupper(md5(uniqid(time())));
        }

        if ($withPrefix === false) {
            return substr($this->_boundary, 2);
        }

        return $this->_boundary;
    }


    /**
     * assign one single var or an array full of vars to email template
     *
     * @param mixed $vars
     * @param mixed $value
     * @return alcEmail
     */
    public function assignToTemplate($vars, $value = null)
    {
        if (is_array($vars) === true) {
            foreach ($vars as $var => $value) {
                $this->_templateAssigns[$var] = $value;
            }
        } else {
            $this->_templateAssigns[$vars] = $value;
        }

        return $this;
    }


    /**
     *
     * @param unknown_type $templateName
     * @return alcEmail
     */
    public function setTemplate($templateName)
    {
        $this->_templateName = $templateName;

        return $this;
    }

    /**
     * select recipients via given userIds
     *
     * @param unknown_type $userIds
     * @return alcEmail
     */
    public function selectRecipients($userIds)
    {
        if ($userIds !== null) {
            if (is_array($userIds) === true) {
                $userIds = implode(',', $userIds);
            }

            $query = "
                SELECT
                    *,
                    CONCAT_WS(' ', titel, nachname) AS 'fullname'
                FROM user
                WHERE
                    user_id IN ({$userIds})
            ";

            foreach (sql_query_array($this->_db, $query) as $recipient) {
                $dataset = array(
                    'data'  => $recipient,
                    'email' => array(
                        'status' => self::$WAITING,
                        'attachments' => array(),
                        'header' => array(),
                        'subject' => null,
                        'content' => null,
                    )
                );

                $this->_recipients[] = $dataset;
            }
        }

        return $this;
    }


    /**
     * Set email recipient manually
     * must be a dataset containing user_id
     *
     * @param      string $recipient
     * @return     alcEmail
     */
    public function setRecipient($recipient)
    {
        if (is_array($recipient) === true && (array_key_exists('email', $recipient) === true && strlen($recipient['email']) > 0)) {
            $dataset = array(
                'data'  => $recipient,
                'email' => array(
                    'status' => self::$WAITING,
                    'attachments' => array(),
                    'header' => array(),
                    'subject' => null,
                    'content' => null
                )
            );

            $this->_recipients[] = $dataset;
        }

        return $this;
    }

    /**
     * parse
     */
    public function send()
    {
        if ($this->_templateName === null) {
            throw new Exception('no template assigned');
        }

        if (count($this->_recipients) > 0) {

            $this
                ->_parse()
                ->_sendMail()
            ;
        }

        return $this;
    }


    /**
     * registers an email attachment if file exists
     *
     * @param string $name
     * @param string $filePath
     *
     * return alcEmail
     */
    public function registerAttachment($name, $path, $label = null)
    {
        if (is_file($path) === true) {
            $content = chunk_split(
                base64_encode(
                    file_get_contents($path)
                ), 68, ""
            );

            $label = ($label === null ? substr(basename($path), 14) : $label);

            $this->_attachments[md5($name)] = array(
                'label'    => (strlen($label) == 0 ? basename($path) : $label),
                'content'  => $content,
                'mimetype' => mimeType::getMimeTypeFromFile($path),
                'path'     => $path
            );
        }

        return $this;
    }


    /**
     *
     *
     */
    public function addAttachment($attachment)
    {
        if (array_key_exists($attachment, $this->_attachments) === true) {
            foreach ($this->_recipients as $i => $recipient) {
                $this->_recipients[$i]['email']['attachments'][] = $attachment;
            }
        }

        return $this;
    }


    /**
     * adds registered attachment to recipient
     * there must be a user_id in
     *
     * @param int $recipient
     * @param string $attachment
     */
    public function addAttachmentToRecipient($userId, $attachment)
    {
        if (array_key_exists($attachment, $this->_attachments) === true) {
            foreach ($this->_recipients as $i => $recipient) {
                if (array_key_exists('user_id', $recipient['data']) === true && $recipient['data']['user_id'] == $userId) {
                     $this->_recipients[$i]['email']['attachments'][] = $attachment;
                }
            }
        }

        return $this;
    }


    /**
     * Get headers as string
     *
     * @return string
     */
    protected function _getHeaderAsString()
    {
        $headInformation = array();

        foreach ($this->_recipients[$this->_currentRecipient]['email']['header'] as $name => $value) {
            $headInformation[] = "$name: $value";
        }

        return implode("\r\n", $headInformation);
    }

    public function debug()
    {
        $this->_debug = true;
        return $this;
    }


    /**
     * sends mail
     */
    protected function _sendMail()
    {
        foreach ($this->_recipients as $i => $recipient) {
            $status = self::$ERR;

            if (array_key_exists('email', $recipient['data']) === true && strlen($recipient['data']['email'])) {
                $mail = $this->_debug === true
                    ? true
                    : @mail($recipient['data']['email'], $recipient['email']['subject'], $recipient['email']['content'], $recipient['email']['header']);
                ;

                $status = $mail === true ? self::$ACC : self::$ERR;
            } else {
                $status = self::$NOMAIL;
            }

            $this->_recipients[$i]['email']['status'] = $status;
        }
    }

    /**
     * returns all recipients
     *
     * @return arry
     */
    public function getRecipients()
    {
        return $this->_recipients;
    }



    /**
     * set header from recipient
     *
     * @param unknown_type $i
     * @param unknown_type $var
     * @param unknown_type $val
     * @return alcEmail
     */
    protected function _setHeader($var, $val)
    {
        $this->_recipients[$this->_currentRecipient]['email']['header'][$var] = $val;

        return $this;
    }


    protected function _setCurrentRecipient($id)
    {
        $this->_currentRecipient = $id;

        return $this;
    }


    /**
     * build base email header for recipient
     * @return alcEmail
     */
    protected function _buildHeader()
    {
        $this
            ->_setHeader('From', $this->getFrom())
            ->_setHeader('MIME-Version', '1.0')
            ->_setHeader('Content-Type', 'multipart/mixed; boundary="' . $this->_getBoundary(false) . '"')
        ;

        return $this;
    }


    /**
     * _parse
     *
     * @access  protected
     * @return  alcEmail
     */
    protected function _parse()
    {
        //Assign template vars to smarty
        foreach ($this->_templateAssigns as $var => $value) {
            $this->_smarty->assign($var, $value);
        }

        foreach ($this->_recipients as $i => $recipient)
        {
            //set current recipient for header set
            $this->_setCurrentRecipient($i);

            //Assign variables
            foreach ($recipient['data'] as $field => $value) {
                $this->_smarty->assign($field, $value);
            }

            //Email Subject
            $subject = array(
                "=?UTF-8?B?",
                base64_encode(utf8_encode($this->_smarty->fetch("email_subject:{$this->_templateName}"))),
                "?="
            );

            $this->_recipients[$i]['email']['subject'] = concat($subject, '');

            //Base Email head and content
            $this
                ->_buildHeader()
            ;

            $content = array(
                $this->_getBoundary(),
            );

            $content[] = "Content-Type: text/html; charset=UTF-8";
            $content[] = "Content-Transfer-Encoding: 8bit";
            $content[] = $this->_wrapHTMLMail(utf8_encode($this->_smarty->fetch("email:{$this->_templateName}")));

            //Attachments
            foreach ($recipient['email']['attachments'] as $attachment) {
                $content[] = $this->_getBoundary();
                $content[] = $this->_getAttachment($attachment);
            }

            $content[] = $this->_getBoundary() . "--";

            $this->_recipients[$i]['email']['header'] = $this->_getHeaderAsString();

            //Assign content to email
            $this->_recipients[$i]['email']['content'] = implode("\r\n", $content);

            //Remove assigned variables
            foreach ($recipient['data'] as $field => $value) {
                $this->_smarty->clear_assign($field);
            }
        }

        return $this;
    }


    protected function _wrapHTMLMail($html)
    {
        $html = "
            <html>
                <head>
                    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
                </head>
                <body>
                    {$html}
                </body>
            </html>
        ";

        return $html;
    }


    protected function _getAttachment($name)
    {
        $attachment = $this->_attachments[$name];

        $content = array(
            "Content-Type: {$attachment['mimetype']}; name=\"{$attachment['label']}\"",
            "Content-Transfer-Encoding: base64",
            "Content-Disposition: attachment; filename=\"{$attachment['label']}\"\r\n",
             $attachment['content']
        );

        return implode("\r\n", $content);
    }

    /**
     * TODO email check
     *
     */
    public function setFrom($from)
    {
        if (strlen($from) > 0) {
            $this->_from = $from;
        }

       return $this;
    }

    public function getFrom()
    {
        $from = $this->_from;

        if ($from === null) {
            $from = $this->_smarty->fetch("email_from:{$this->_templateName}");
        }

        return $from;
    }

}

/*
 //error handling bei fehlschlag
/*
if( $email )
    return array('status' => 0, 'teilnehmer' => $result);

// Problem beim Mailversand: Mail an Moderator
$subject      = $config['emailerror_subject1'] . ' ' . $konferenz['datum'] . ' ' . $config['emailerror_subject2'];
$message_body = $smarty->fetch( 'base/email_konferenz_teilnehmer_fehler.tpl' );
$message_body = str_replace( "\r\n", "\n", $message_body );
$email        = @mail( $konferenz['email'], $subject, $message_body, 'From: ' . $config['error_mail'] );

// Es hat ein Problem gegeben, aber der Moderator wurde benachrichtigt
if( $email )
    return array('status' => 2, 'teilnehmer' => $result);

// Der E-Mail-Versand über die Anwendung ist gestört
return array('status' => 3, 'teilnehmer' => $result);
*/


?>
