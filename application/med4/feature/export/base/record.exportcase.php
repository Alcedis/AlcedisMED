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

require_once( 'record.exportsection.php' );
require_once( 'class.exportexception.php' );

class RExportCase
{
    /**
     * m_export_case_id
     *
     * @access  protected
     * @var     int
     */
    protected $m_export_case_id = 0;


    /**
     * m_export_id
     *
     * @access  protected
     * @var     int
     */
    protected $m_export_id = 0;


    /**
     * m_patient_id
     *
     * @access  protected
     * @var     int
     */
    protected $m_patient_id = 0;


    /**
     * m_erkrankung_id
     *
     * @access  protected
     * @var     int
     */
    protected $m_erkrankung_id = 0;


    /**
     * m_diagnose_seite
     *
     * @access  protected
     * @var     string
     */
    protected $m_diagnose_seite = '';


    /**
     * m_anlass
     *
     * @access  protected
     * @var     string
     */
    protected $m_anlass = '';


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
     * _m_hash
     *
     * @access  protected
     * @var     string
     */
    protected $m_hash;


    /**
     * m_update_time
     *
     * @access  protected
     * @var     string
     */
    protected $m_update_time;


    /**
     * m_sections
     *
     * @access  protected
     * @var     RExportSection[]
     */
    protected $m_sections = array();


    /**
     * Create
     *
     * @access  public
     * @param   resource    $db
     * @param   array       $db_row
     * @param   bool $get_sections
     * @return  void
     */
    public function Create($db, array $db_row, $get_sections = false)
    {
        $this->m_export_case_id = $db_row[ 'export_case_log_id' ];
        $this->m_export_id      = $db_row[ 'export_log_id' ];
        $this->m_patient_id     = $db_row[ 'patient_id' ];
        $this->m_erkrankung_id  = $db_row[ 'erkrankung_id' ];
        $this->m_diagnose_seite = $db_row[ 'diagnose_seite' ];
        $this->m_anlass         = $db_row[ 'anlass' ];
        $this->m_create_time    = $db_row[ 'createtime' ];
        $this->m_update_time    = $db_row[ 'updatetime' ];
        $this->m_hash           = $db_row['hash'];

        if ( $get_sections ) {
            $this->ReadSections( $db );
        } else {
            $this->m_sections = array();
        }
    }


    /**
     * Clear
     *
     * @access  public
     * @return  void
     */
    public function Clear()
    {
        $this->m_export_case_id = 0;
        $this->m_export_id      = 0;
        $this->m_patient_id     = 0;
        $this->m_erkrankung_id  = 0;
        $this->m_diagnose_seite = '';
        $this->m_anlass         = '';
        $this->m_create_time    = null;
        $this->hash             = '';
        $this->m_update_time    = null;
        $this->m_sections       = array();
    }


    /**
     * getter for m_export_case_id
     *
     * @access  public
     * @return  int
     */
    public function getDbid()
    {
        return $this->m_export_case_id;
    }


    /**
     * getter for m_export_id
     *
     * @access  public
     * @return  int
     */
    public function getExportId()
    {
        return $this->m_export_id;
    }


    /**
     * setter for m_export_id
     *
     * @access  public
     * @param   $export_id
     * @return  void
     */
    public function setExportId( $export_id )
    {
        $this->m_export_id = $export_id;
    }


    /**
     * getter for m_patient_id
     *
     * @access  public
     * @return  int
     */
    public function getPatientId()
    {
        return $this->m_patient_id;
    }


    /**
     * getHash
     *
     * @access  public
     * @return  mixed
     */
    public function getHash()
    {
        return $this->m_hash;
    }


    /**
     * setHash
     *
     * @access  public
     * @param   string $hash
     * @return  RExportCase
     */
    public function setHash($hash)
    {
        $this->m_hash = $hash;

        return $this;
    }


    /**
     * setter for m_patient_id
     *
     * @access  public
     * @param   $patient_id
     * @return  void
     */
    public function setPatientId( $patient_id )
    {
        $this->m_patient_id = $patient_id;
    }


    /**
     * getter for erkrankung_id
     *
     * @access  public
     * @return  int
     */
    public function getErkrankungId()
    {
        return $this->m_erkrankung_id;
    }


    /**
     * setter for m_erkrankungs_id
     *
     * @access  public
     * @param   $erkrankung_id
     * @return  void
     */
    public function setErkrankungId( $erkrankung_id )
    {
        $this->m_erkrankung_id = $erkrankung_id;
    }


    /**
     * getter for m_diagnose_seite
     *
     * @access  public
     * @return  string
     */
    public function getDiagnoseSeite()
    {
        return $this->m_diagnose_seite;
    }


    /**
     * setter for m_diagnose_seite
     *
     * @access  public
     * @param   $diagnose_seite
     * @return  void
     */
    public function setDiagnoseSeite( $diagnose_seite )
    {
        $this->m_diagnose_seite = $diagnose_seite;
    }


    /**
     * getter for m_anlass
     *
     * @access  public
     * @return  string
     */
    public function getAnlass()
    {
        return $this->m_anlass;
    }


    /**
     * setter for m_anlass
     *
     * @access  public
     * @param   $anlass
     * @return  void
     */
    public function setAnlass( $anlass )
    {
        $this->m_anlass = $anlass;
    }


    /**
     * getter for m_create_user_id
     *
     * @access  public
     * @return  int
     */
    public function getCreateUserId()
    {
        return $this->m_create_user_id;
    }


    /**
     * setter for m_create_user_id
     *
     * @access  public
     * @param   $create_user_id
     * @return  void
     */
    public function setCreateUserId( $create_user_id )
    {
        $this->m_create_user_id = $create_user_id;
    }


    /**
     * getter for m_create_time
     *
     * @access  public
     * @return  null
     */
    public function getCreateTime()
    {
        return $this->m_create_time;
    }


    /**
     * setter for m_create_time
     *
     * @access  public
     * @param   $create_time
     * @param   bool $update_childern
     * @return  void
     */
    public function setCreateTime($create_time, $update_childern = false )
    {
        $this->m_create_time = $create_time;
        if ($update_childern ) {

            foreach($this->m_sections as $section ) {
                if ($section instanceof RExportSection ) {
                    $section->setCreatetime($create_time );
                }
            }
        }
    }


    /**
     * getter for m_update_time
     *
     * @access  public
     * @return  null
     */
    public function getUpdateTime()
    {
        return $this->m_update_time;
    }


    /**
     * setter for m_update_time
     *
     * @access  public
     * @param   $update_time
     * @return  void
     */
    public function setUpdateTime($update_time )
    {
        $this->m_update_time = $update_time;
    }


    /**
     * getter for m_sections
     *
     * @access  public
     * @return  RExportSection[]
     */
    public function getSections()
    {
        return $this->m_sections;
    }


    /**
     * getFirstSection
     *
     * @access  public
     * @param   string $name
     * @return  RExportSection
     */
    public function getFirstSection($name)
    {
        $section = null;

        foreach ($this->m_sections as $pSection) {
            if ($pSection->getBlock() === $name) {
                $section = $pSection;

                break;
            }
        }

        return $section;
    }


    /**
     * setter for m_sections
     *
     * @access  public
     * @param   RExportSection[] $sections
     * @return  RExportCase
     */
    public function setSections(array $sections)
    {
        $this->m_sections = array();

        foreach ($sections as $section) {
            $this->addSection($section);
        }

        return $this;
    }


    /**
     * add new section to m_section
     *
     * @access  public
     * @param   RExportSection $section
     * @return  RExportCase
     * @throws  EExportException
     */
    public function addSection(RExportSection $section)
    {
        $this->m_sections[] = $section;

        return $this;
    }

    /*
    public function getValidSections()
    {
        $result = array();
        foreach( $this->m_sections as $section) {
            if ( ( $section instanceof RExportSection) &&
                 ( $section->getValid() == 1)) {
                $result[] = &$section;
            }
        }
        return $result;
    }

    public function getInvalidSections()
    {
        $result = array();
        foreach( $this->m_sections as $section) {
            if ( ( $section instanceof RExportSection) &&
                 ( $section->getValid() == 0)) {
                $result[] = &$section;
            }
        }
        return $result;
    }
	  */

    /**
     * setter for meldungskennzeichen
     *
     * @access  public
     * @param   string $meldungskennzeichen
     * @return  void
     */
    public function setMeldungskennzeichen($meldungskennzeichen)
    {
        foreach ($this->m_sections as $section) {
            $section->setMeldungskennzeichen($meldungskennzeichen);
        }
    }


    /**
     * checks every section, if there is any meldungskennzeichen equal to ...
     *
     * @access  public
     * @param   string $meldungskennzeichen
     * @return  bool
     */
    public function hasAnySesctionWith($meldungskennzeichen)
    {
        foreach($this->m_sections as $section) {
            if ($meldungskennzeichen == $section->getMeldungskennzeichen()) {
                return true;
            }
        }

        return false;
    }


    /**
     * read export_case_log from db
     *
     * @access      public
     * @param       $db
     * @param       $export_case_id
     * @param       bool $get_sections
     * @return      void
     */
    public function read($db, $export_case_id, $get_sections = false)
    {
        $this->Clear();

        $query = "
            SELECT
                *
            FROM
                export_case_log ecl
            WHERE
              ecl.export_case_log_id = '{$export_case_id}'
        ";

        $result = end( sql_query_array( $db, $query));

        if (false !== $result) {
            $this->m_export_case_id = (int)$result[ 'export_case_log_id' ];
            $this->m_export_id      = (int)$result[ 'export_log_id' ];
            $this->m_patient_id     = (int)$result[ 'patient_id' ];
            $this->m_erkrankung_id  = (int)$result[ 'erkrankung_id' ];
            $this->m_diagnose_seite = $result[ 'diagnose_seite' ];
            $this->m_anlass         = $result[ 'anlass' ];
            $this->m_create_user_id = (int)$result[ 'createuser' ];
            $this->m_create_time    = $result[ 'createtime' ];
            $this->m_update_time    = $result[ 'updatetime' ];
            $this->m_hash           = $result[ 'hash' ];

            if ($get_sections === true) {
                $this->readSections($db);
            }
        }
    }


    /**
     * read export_section_log from db
     *
     * @access  public
     * @param   resource $db
     * @return  void
     */
    public function readSections($db)
    {
        $this->m_sections = array();

        $query = "
            SELECT
                *
            FROM
                export_section_log esl
            WHERE
              esl.export_case_log_id = {$this->m_export_case_id}
        ";

        $result = sql_query_array( $db, $query);

        if (false !== $result) {
            foreach ($result as $row) {
                $section = new RExportSection;

                $section->create($row);

                $this->addSection($section);
            }
        }
    }


    /**
     * Write case identification data into export_case_log
     *
     * @access
     * @param $db
     * @return void
     */
    public function Write( $db)
    {
        $hash = strlen($this->m_hash) > 0 ? "'{$this->m_hash}'" : 'NULL';

        if ( 0 == $this->m_export_case_id) {
            $query = "
                INSERT INTO export_case_log (
                    export_log_id,
                    patient_id,
                    erkrankung_id,
                    diagnose_seite,
                    anlass,
                    hash,
                    createuser,
                    createtime,
                    updatetime)
                VALUES (
                    {$this->m_export_id},
                    {$this->m_patient_id},
                    {$this->m_erkrankung_id},
                    '{$this->m_diagnose_seite}',
                    '{$this->m_anlass}',
                    {$hash},
                    {$this->m_create_user_id},
                    '{$this->m_create_time}',
                    NOW()
               )
            ";

            @mysql_query( $query, $db);
            $this->m_export_case_id = @mysql_insert_id( $db);

        } else {

            // Do update
            $query = "
            	UPDATE export_case_log SET
            		export_log_id   = {$this->m_export_id},
					patient_id      = {$this->m_patient_id},
					erkrankung_id   = {$this->m_erkrankung_id},
					diagnose_seite  = '{$this->m_diagnose_seite}',
					anlass          = '{$this->m_anlass}',
					hash            = {$hash},
					createuser      = {$this->m_create_user_id},
					createtime      = '{$this->m_create_time}',
					updatetime      = NOW()

            	WHERE
            		export_case_log_id = {$this->m_export_case_id}

            ";
            @mysql_query( $query, $db);
        }

        // Write references
        foreach ($this->m_sections as $section) {
            $section->setExportCaseId($this->m_export_case_id);
            $section->Write( $db);
        }
    }


    /**
     * delete from export_case_log
     *
     * @access
     * @param $db
     * @param int $export_case_id
     * @return void
     * @throws EExportException
     */
    public function Delete( $db, $export_case_id = 0)
    {
        $dbid = $export_case_id;

        if (0 == $dbid) {
            $dbid = $this->m_export_case_id;
        }

        if (0 != $dbid) {
            // Delete references
            foreach ($this->m_sections as $section) {
                $section->Delete($db);
            }

            // Delete object
            $query = "DELETE FROM export_case_log WHERE export_case_log_id = '{$dbid}'";

            @mysql_query( $query, $db);
            $this->Clear();
        } else {
            throw new EExportException( "ERROR: Export dbid is null, delete failed.");
        }
    }


    /**
     * identification fields to array
     *
     * @access  public
     * @return  array
     */
    public function toArray()
    {
        $result = array();
        $result[ 'export_case_log_id' ] = $this->m_export_case_id;
        $result[ 'export_log_id' ]      = $this->m_export_id;
        $result[ 'patient_id' ]         = $this->m_patient_id;
        $result[ 'erkrankung_id' ]      = $this->m_erkrankung_id;
        $result[ 'diagnose_seite' ]     = $this->m_diagnose_seite;
        $result[ 'anlass' ]             = $this->m_anlass;
        $result[ 'hash' ]               = $this->m_hash;
        $result[ 'createuser' ]         = $this->m_create_user_id;
        $result[ 'createtime' ]         = $this->m_create_time;
        $result[ 'updatetime' ]         = $this->m_update_time;
        $result[ 'sections' ]           = array();

        foreach( $this->m_sections as $section) {
            if ( $section instanceof RExportSection) {
                $result[ 'sections' ][] = $section->ToArray();
            }
        }

        return $result;
    }


    /**
     *
     *
     * @access  public
     * @return  bool
     */
    public function IsCaseValid()
    {
        return $this->IsValid();
    }


    /**
     * checks if data has changed
     *
     * @access  public
     * @return  bool
     */
    public function HasDataChanged()
    {
        foreach( $this->m_sections as $section) {
            if ( ( $section instanceof RExportSection) &&
                 ( 1 == $section->HasDataChanged())) {
                return true;
            }
        }
        return false;
    }


    /**
     * returns invalid sections
     *
     * @access  public
     * @return  array
     */
    public function getAllInvalidSections()
    {
        $result = array();

        foreach( $this->m_sections as $section) {
            if (( $section instanceof RExportSection) && (0 == $section->getValid())) {
                $arr = $section->ToArray();
                $arr[ 'patient_id' ]     = $this->m_patient_id;
                $arr[ 'erkrankung_id' ]  = $this->m_erkrankung_id;
                $arr[ 'diagnose_seite' ] = $this->m_diagnose_seite;
                $arr[ 'anlass' ]         = $this->m_anlass;

                $result[$arr['export_case_log_id'] . '-' . $arr['export_section_log_id']] = $arr;
            }
        }

        return $result;
    }


    /**
     * returns all sections with warning
     *
     * @access  public
     * @return  array
     */
    public function getAllSectionsWithOnlyWarnings()
    {
        $result = array();

        foreach( $this->m_sections as $section) {
            if (($section instanceof RExportSection) && (1 == $this->HasDataChanged()) && $section->_hasOnlyWarnings()) {
                $arr = $section->ToArray();
                $arr[ 'patient_id' ]     = $this->m_patient_id;
                $arr[ 'erkrankung_id' ]  = $this->m_erkrankung_id;
                $arr[ 'diagnose_seite' ] = $this->m_diagnose_seite;
                $arr[ 'anlass' ]         = $this->m_anlass;

                $result[$arr['export_case_log_id'] . '-' . $arr['export_section_log_id']] = $arr;
            }
        }

        return $result;
    }


    /**
     * check if case is valid
     *
     * @access
     * @return bool
     */
    public function IsValid()
    {
        foreach( $this->m_sections as $section) {
            if ( ( $section instanceof RExportSection) &&
                 ( 0 == $section->getValid())) {
                return false;
            }
        }

        return true;
    }


    /**
     *
     *
     * @access  public
     * @param   $blockName
     * @param   $errors
     * @return  void
     * @throws  EExportException
     */
    public function setSectionErrorsByName($blockName, $errors)
    {
        foreach ($this->m_sections as $key => $section) {
            if ($blockName == $section->getBlock()) {
                if (is_array($errors) && count($errors) > 0) {
                    $this->m_sections[$key]->setErrors($errors);
                    if ($this->_HasOnlyWarnings($errors)) {
                        $this->m_sections[$key]->setValid(1);
                    } else {
                        $this->m_sections[$key]->setValid(0);
                    }
                } else {
                    $this->m_sections[$key]->setErrors(array());
                    $this->m_sections[$key]->setValid(1);
                }

                return;
            }
        }

        throw new EExportException("ERROR: Section [$blockName] not found.");
    }


    /**
     * getSectionsByName
     *
     * @access  public
     * @param   string  $name
     * @return  RExportSection[]
     */
    public function getSectionsByName($name)
    {
        $sections = array();

        foreach ($this->m_sections as $section) {
            if ($section->getBlock() === $name) {
                $sections[] = $section;
            }
        }

        return $sections;
    }


    /**
     * getFirstSectionByName
     *
     * @access  public
     * @param   string  $name
     * @return  RExportSection
     */
    public function getFirstSectionByName($name)
    {
        $section = null;

        $sections = $this->getSectionsByName($name);

        if (count($sections) > 0) {
            $section = reset($sections);
        }

        return $section;
    }


    /**
     *
     *
     * @access  public
     * @param   $sectionUid
     * @param   $errors
     * @return  void
     * @throws  EExportException
     */
    public function setSectionErrorsByUid( $sectionUid, $errors)
    {
        foreach ($this->m_sections as $key => $section) {
            if ($sectionUid == $section->getSectionUid()) {
                if (is_array($errors) && count($errors) > 0) {
                    $this->m_sections[$key]->setErrors($errors);
                    if ($this->_HasOnlyWarnings($errors)) {
                        $this->m_sections[$key]->setValid(1);
                    } else {
                        $this->m_sections[$key]->setValid(0);
                    }
                } else {
                    $this->m_sections[$key]->setErrors(array());
                    $this->m_sections[$key]->setValid(1);
                }

                return;
            }
        }

        throw new EExportException("ERROR: Section UID [$sectionUid] not found.");
    }


    /**
     *
     *
     * @access  public
     * @param   $section_uid
     * @param   $tan
     * @return  void
     * @throws  EExportException
     */
    public function setSectionTanByUid( $section_uid, $tan)
    {
        foreach( $this->m_sections as $key => $section) {
            if ( $section_uid == $section->getSectionUid()) {
                $data = $this->m_sections[ $key ]->getDaten();
                $data[ 'tan' ] = $tan;
                $this->m_sections[ $key ]->setDaten( $data);
                return;
            }
        }
        throw new EExportException( "ERROR: Section UID [$section_uid] not found.");
    }


    /**
     *
     *
     * @access  protected
     * @param   $errors
     * @return  bool
     */
    protected function _HasOnlyWarnings($errors)
    {
        if (0 == count($errors)) {
            return false;
        }

        foreach ($errors as $error) {
            if (substr($error, 0, 10) != '[warning] ') {
                return false;
            }
        }

        return true;
    }
}
?>
