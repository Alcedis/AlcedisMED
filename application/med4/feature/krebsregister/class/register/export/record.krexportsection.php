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

class RKrExportSection
{
    /**
     * section query stack for better performance on db writing
     *
     * @access  private
     * @var     array
     */
    private static $queryStack = array();


    /**
     * queryStackFlushSize
     *
     * @access  private
     * @var     int
     */
    private static $queryStackFlushSize = 600;


    /**
     * queryStackFlushIndex
     *
     * @access  private
     * @var     int
     */
    private static $queryStackFlushIndex = 0;


    /**
     * m_data_changed
     *
     * @access  protected
     * @var     int
     */
    protected $m_data_changed = 0;


    /**
     * m_export_section_id
     *
     * @access  protected
     * @var     int
     */
    protected $m_export_section_id = 0;


    /**
     * m_section_uid
     *
     * @access  protected
     * @var     string
     */
    protected $m_section_uid = '';


    /**
     * m_export_case_id
     *
     * @access  protected
     * @var     int
     */
    protected $m_export_case_id = 0;


    /**
     * m_meldungskennzeichen
     *
     * @access  protected
     * @var     string
     */
    protected $m_meldungskennzeichen;


    /**
     * m_melde_uid
     *
     * @access  protected
     * @var     string
     */
    protected $m_melde_uid;


    /**
     * m_block
     *
     * @access  protected
     * @var     string
     */
    protected $m_block = '';


    /**
     * m_daten
     *
     * @access  protected
     * @var     array
     */
    protected $m_daten = array();


    /**
     * m_valid
     *
     * @access  protected
     * @var     int
     */
    protected $m_valid = 0;


    /**
     * m_errors
     *
     * @access  protected
     * @var     array
     */
    protected $m_errors = array();


    /**
     * m_create_user_id
     *
     * @access  protected
     * @var     int
     */
    protected $m_create_user_id = 0;


    /**
     * m_create_time
     *
     * @access  protected
     * @var     string
     */
    protected $m_create_time;


    /**
     * m_update_time
     *
     * @access  protected
     * @var     string
     */
    protected $m_update_time;


    /**
     * Create
     *
     * @access  public
     * @param   $db_row
     * @return  void
     */
    public function create(array $db_row)
    {
        $this->m_export_section_id = $db_row['export_section_log_id'];
        $this->m_section_uid = $db_row['section_uid'];
        $this->m_export_case_id = $db_row['export_case_log_id'];
        $this->m_data_changed = $db_row['data_changed'];
        $this->m_meldungskennzeichen = $db_row['meldungskennzeichen'];
        $this->m_melde_uid = $db_row['melde_uid'];
        $this->m_block = $db_row['block'];
        $this->m_daten = unserialize(base64_decode($db_row['daten']));
        $this->m_valid = $db_row['valid'];
        $this->m_errors = unserialize(base64_decode($db_row['errors']));
        $this->m_create_user_id = $db_row['createuser'];
        $this->m_create_time = $db_row['createtime'];
        $this->m_update_time = $db_row['updatetime'];
    }


    /**
     * Clear
     *
     * @access  public
     * @return  RKrExportSection
     */
    public function clear()
    {
        $this->m_data_changed = 0;
        $this->m_export_section_id = 0;
        $this->m_section_uid = '';
        $this->m_export_case_id = 0;
        $this->m_meldungskennzeichen = null;
        $this->m_melde_uid = null;
        $this->m_block = '';
        $this->m_daten = null;
        $this->m_valid = 0;
        $this->m_errors = array();
        $this->m_create_user_id = 0;
        $this->m_create_time = null;
        $this->m_update_time = null;

        return $this;
    }


    /**
     * SetDataChanged
     *
     * @access  public
     * @param   int $data_changed
     * @return  RKrExportSection
     */
    public function setDataChanged($data_changed)
    {
        $this->m_data_changed = $data_changed;

        return $this;
    }


    /**
     * HasDataChanged
     *
     * @access  public
     * @return  int
     */
    public function hasDataChanged()
    {
        return $this->m_data_changed;
    }


    /**
     * getDbid
     *
     * @access  public
     * @return  int
     */
    public function getDbid()
    {
        return $this->m_export_section_id;
    }


    /**
     * getSectionUid
     *
     * @access  public
     * @return  string
     */
    public function getSectionUid()
    {
        return $this->m_section_uid;
    }


    /**
     * setSectionUid
     *
     * @access  public
     * @param   string  $section_uid
     * @return  RKrExportSection
     */
    public function setSectionUid($section_uid)
    {
        $this->m_section_uid = $section_uid;

        return $this;
    }


    /**
     * getExportCaseId
     *
     * @access  public
     * @return  int
     */
    public function getExportCaseId()
    {
        return $this->m_export_case_id;
    }


    /**
     * setExportCaseId
     *
     * @access  public
     * @param   int $export_case_id
     * @return  RKrExportSection
     */
    public function setExportCaseId($export_case_id)
    {
        $this->m_export_case_id = $export_case_id;

        return $this;
    }


    /**
     * getMeldungskennzeichen
     *
     * @access  public
     * @return  string
     */
    public function getMeldungskennzeichen()
    {
        return $this->m_meldungskennzeichen;
    }


    /**
     * setMeldungskennzeichen
     *
     * @access  public
     * @param   string  $meldungskennzeichen
     * @return  RKrExportSection
     */
    public function setMeldungskennzeichen($meldungskennzeichen)
    {
        $this->m_meldungskennzeichen = $meldungskennzeichen;

        return $this;
    }


    /**
     * getMeldeUid
     *
     * @access  public
     * @return  string
     */
    public function getMeldeUid()
    {
        return $this->m_melde_uid;
    }


    /**
     * setMeldeUid
     *
     * @access  public
     * @param   int $melde_uid
     * @return  RKrExportSection
     */
    public function setMeldeUid($melde_uid)
    {
        $this->m_melde_uid = $melde_uid;

        return $this;
    }


    /**
     * getBlock
     *
     * @access  public
     * @return  string
     */
    public function getBlock()
    {
        return $this->m_block;
    }


    /**
     * setBlock
     *
     * @access  public
     * @param $block
     * @return     void
     */
    public function setBlock($block)
    {
        $this->m_block = $block;

        return $this;
    }


    /**
     * getDaten
     *
     * @access  public
     * @return  array
     */
    public function getDaten()
    {
        return $this->m_daten;
    }


    /**
     * getData
     *
     * @access  public
     * @return  array
     */
    public function getData()
    {
        return $this->m_daten;
    }


    /**
     * setDaten
     *
     * @access  public
     * @param   array   $daten
     * @return  RKrExportSection
     */
    public function setDaten(array $daten)
    {
        $this->m_daten = $daten;

        return $this;
    }


    /**
     * getValid
     *
     * @access  public
     * @return  int
     */
    public function getValid()
    {
        return $this->m_valid;
    }


    /**
     * setValid
     *
     * @access  public
     * @param   int $valid
     * @return  RKrExportSection
     */
    public function setValid($valid)
    {
        $this->m_valid = $valid;

        return $this;
    }


    /**
     * getErrors
     *
     * @access  public
     * @return  array
     */
    public function getErrors()
    {
        return $this->m_errors;
    }


    /**
     * setErrors
     *
     * @access  public
     * @param   array   $errors
     * @return  RKrExportSection
     */
    public function setErrors(array $errors)
    {
        $this->m_errors = $errors;

        return $this;
    }


    /**
     * addError
     *
     * @access  public
     * @param   array $error
     * @return  RKrExportSection
     */
    public function addError(array $error)
    {
        $this->m_errors[] = $error;

        return $this;
    }


    /**
     * hasErrors
     *
     * @access  public
     * @return  bool
     */
    public function hasErrors()
    {
        return (count($this->m_errors) > 0);
    }


    /**
     * getCreateUserId
     *
     * @access  public
     * @return  int
     */
    public function getCreateUserId()
    {
        return $this->m_create_user_id;
    }


    /**
     * setCreateUserId
     *
     * @access  public
     * @param   int $create_user_id
     * @return  RKrExportSection
     */
    public function setCreateUserId($create_user_id)
    {
        $this->m_create_user_id = $create_user_id;

        return $this;
    }


    /**
     * getCreateTime
     *
     * @access  public
     * @return  string
     */
    public function getCreateTime()
    {
        return $this->m_create_time;
    }


    /**
     * setCreateTime
     *
     * @access  public
     * @param   string  $create_time
     * @return  RKrExportSection
     */
    public function setCreateTime($create_time)
    {
        $this->m_create_time = $create_time;

        return $this;
    }


    /**
     * getUpdateTime
     *
     * @access  public
     * @return  string
     */
    public function getUpdateTime()
    {
        return $this->m_update_time;
    }


    /**
     * setUpdateTime
     *
     * @access  public
     * @param   string  $update_time
     * @return  RKrExportSection
     */
    public function setUpdateTime($update_time)
    {
        $this->m_update_time = $update_time;

        return $this;
    }


    /**
     * ClearTan
     *
     * @access  public
     * @return  RKrExportSection
     */
    public function clearTan()
    {
        if (is_array($this->m_daten)) {
            $this->m_daten['tan'] = "--";
        }

        return $this;
    }


    /**
     * read
     *
     * @access  public
     * @param   resource $db
     * @param   int      $export_section_id
     * @return  RKrExportSection
     */
    public function read($db, $export_section_id)
    {
        $query = "SELECT * FROM export_section_log esl WHERE esl.export_section_log_id = '{$export_section_id}'";
        $result = end(sql_query_array($db, $query));

        if (false !== $result) {
            $this->m_export_section_id = (int)$result['export_section_log_id'];
            $this->m_section_uid = $result['section_uid'];
            $this->m_export_case_id = (int)$result['export_case_log_id'];
            $this->m_data_changed = (int)$result['data_changed'];

            if (strlen($result['meldungskennzeichen']) > 0) {
                $this->m_meldungskennzeichen = $result['meldungskennzeichen'];
            }

            if (strlen($result['melde_uid']) > 0) {
                $this->m_melde_uid = (int)$result['melde_uid'];
            }

            $this->m_block = $result['block'];
            $this->m_daten = unserialize(base64_decode($result['daten']));
            $this->m_valid = (int)$result['valid'];
            $this->m_errors = unserialize(base64_decode($result['errors']));
            $this->m_create_user_id = (int)$result['createuser'];
            $this->m_create_time = $result['createtime'];
            $this->m_update_time = $result['updatetime'];
        }

        return $this;
    }


    /**
     * write
     *
     * @access  public
     * @param   resource $db
     * @param   bool $toStack
     * @return  RKrExportSection
     */
    public function write($db, $toStack = false)
    {
        $daten = base64_encode(serialize($this->m_daten));
        $errors = base64_encode(serialize($this->m_errors));

        if (0 === $this->m_export_section_id) {

            $time = date("c", time());

            $values = "(
                '{$this->m_section_uid}',
                {$this->m_export_case_id},
                {$this->m_data_changed}," .
                (strlen($this->m_meldungskennzeichen) > 0 ? "'{$this->m_meldungskennzeichen}'" : 'NULL') . "," .
                (strlen($this->m_melde_uid) > 0 ? "'{$this->m_melde_uid}'" : 'NULL') . "," .
                "'{$this->m_block}',
                '{$daten}',
                {$this->m_valid},
                '{$errors}',
                {$this->m_create_user_id},
                '{$this->m_create_time}',
                '$time'
            )";

            if ($toStack === false) {
                $query = "
                INSERT INTO export_section_log (
                    section_uid,
                    export_case_log_id,
                    data_changed,
                    meldungskennzeichen,
                    melde_uid,
                    block,
                    daten,
                    valid,
                    errors,
                    createuser,
                    createtime,
                    updatetime)
                VALUES {$values}
            ";
                @mysql_query($query, $db);

                $this->m_export_section_id = @mysql_insert_id($db);
            } else {
                self::addToQueryStack($db, $values);
            }
        } else {
            // Do update
            $query = "
                UPDATE export_section_log SET
                    section_uid='{$this->m_section_uid}',
                    export_case_log_id={$this->m_export_case_id},
                    data_changed={$this->m_data_changed},
                    meldungskennzeichen=" . (strlen($this->m_meldungskennzeichen) > 0 ? "'{$this->m_meldungskennzeichen}'" : 'NULL') . ",
                    melde_uid=" . (strlen($this->m_melde_uid) > 0 ? "'{$this->m_melde_uid}'" : 'NULL') . ",
                    block='{$this->m_block}',
                    daten='{$daten}',
                    valid={$this->m_valid},
                    errors='{$errors}',
                    createuser={$this->m_create_user_id},
                    createtime='{$this->m_create_time}',
                    updatetime=NOW()
                WHERE
                    export_section_log_id={$this->m_export_section_id}
            ";
            @mysql_query($query, $db);
        }

        return $this;
    }


    /**
     * Delete
     *
     * @access  public
     * @param   resource  $db
     * @param   int $export_section_id
     * @return  RKrExportSection
     * @throws  EKrExportException
     */
    public function delete($db, $export_section_id = 0)
    {
        $dbId = $export_section_id;

        if (0 === $dbId) {
            $dbId = $this->m_export_section_id;
        }

        if (0 !== $dbId) {
            $query = "DELETE FROM export_section_log WHERE export_section_log_id = '{$dbId}'";

            @mysql_query($query, $db);

            $this->clear();
        } else {
            throw new EKrExportException("ERROR: Export dbid is null, delete failed.");
        }

        return $this;
    }


    /**
     * toArray
     *
     * @access  public
     * @return  array
     */
    public function toArray()
    {
        $result = array();
        $result['export_section_log_id'] = $this->m_export_section_id;
        $result['section_uid'] = $this->m_section_uid;
        $result['export_case_log_id'] = $this->m_export_case_id;
        $result['data_changed'] = $this->m_data_changed;
        $result['meldungskennzeichen'] = $this->m_meldungskennzeichen;
        $result['melde_uid'] = $this->m_melde_uid;
        $result['block'] = $this->m_block;
        $result['daten'] = $this->m_daten;
        $result['valid'] = $this->m_valid;
        $result['errors'] = $this->m_errors;
        $result['createuser'] = $this->m_create_user_id;
        $result['createtime'] = $this->m_create_time;
        $result['updatetime'] = $this->m_update_time;

        return $result;
    }


    /**
     * _hasOnlyWarnings
     *
     * @access  public
     * @return  bool
     */
    public function _hasOnlyWarnings()
    {
        if (0 == count($this->m_errors)) {
            return false;
        }

        foreach ($this->m_errors as $error) {
            if (substr($error, 0, 10) != '[warning] ') {
                return false;
            }
        }

        return true;
    }


    /**
     * clear query stack
     *
     * @static
     * @access  public
     * @return  void
     */
    public static function clearQueryStack()
    {
        self::$queryStack = array();
        self::$queryStackFlushIndex = 0;
    }


    /**
     * flushQueryStack
     *
     * @static
     * @access  public
     * @param   resource $db
     * @return  void
     */
    public static function flushQueryStack($db)
    {
        if (count(self::$queryStack) > 0) {
            $query = "
                INSERT INTO export_section_log (
                    section_uid,
                    export_case_log_id,
                    data_changed,
                    meldungskennzeichen,
                    melde_uid,
                    block,
                    daten,
                    valid,
                    errors,
                    createuser,
                    createtime,
                    updatetime
                ) VALUES " . implode(',', self::$queryStack)
            ;

            @mysql_query($query, $db);

            //if (mysql_errno($db)) {
            //    file_put_contents('error.txt',  mysql_errno($db) . ": " . mysql_error($db), FILE_APPEND);
            //}

            self::clearQueryStack();
        }
    }


    /**
     * addToQueryStack
     *
     * @static
     * @access  public
     * @param   resource $db
     * @param   string $values
     * @return  void
     */
    public static function addToQueryStack($db, $values)
    {
        self::$queryStack[] = $values;

        self::$queryStackFlushIndex++;

        // automatically flush query stack
        if (self::$queryStackFlushIndex >= self::$queryStackFlushSize) {
            self::flushQueryStack($db);
        }
    }
}

?>
