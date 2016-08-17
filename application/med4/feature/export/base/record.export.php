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

require_once('record.exportcase.php');
require_once('class.exportexception.php');

class RExport
{
    /**
     * m_export_id
     *
     * @access  protected
     * @var     int
     */
    protected $m_export_id = 0;


    /**
     * m_export_name
     *
     * @access  protected
     * @var     string
     */
    protected $m_export_name = '';


    /**
     * m_export_nr
     *
     * @access  protected
     * @var     int
     */
    protected $m_export_nr = 0;


    /**
     * m_next_tan
     *
     * @access  protected
     * @var     int
     */
    protected $m_next_tan = 1;


    /**
     * _exportUniqueId
     *
     * @access  protected
     * @var     string
     */
    protected $_exportUniqueId = "";


    /**
     * m_org_id
     *
     * @access  protected
     * @var     int
     */
    protected $m_org_id = 0;


    /**
     * m_melder_id
     *
     * @access  protected
     * @var     int
     */
    protected $m_melder_id = 0;


    /**
     * m_parameters
     *
     * @access  protected
     * @var     string
     */
    protected $m_parameters;


    /**
     * m_finished
     *
     * @access  protected
     * @var     int
     */
    protected $m_finished = 0;


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
     * m_cases
     *
     * @access  protected
     * @var     RExportCase[]
     */
    protected $m_cases = array();


    /**
     *
     */
    public function __construct()
    {
        $this->Clear();
    }


    /**
     * Create
     *
     * @access  p
     * @param       $export_id
     * @param       $export_name
     * @param       $export_nr
     * @param       $next_tan
     * @param       $exportUniqueId
     * @param       $org_id
     * @param       $melder_id
     * @param       $parameters
     * @param       $finished
     * @param       $create_user_id
     * @param       $create_time
     * @param       $update_time
     * @param array $cases
     * @return     void
     */
    public function Create(
        $export_id, $export_name, $export_nr, $next_tan, $exportUniqueId, $org_id, $melder_id, $parameters, $finished,
        $create_user_id, $create_time, $update_time, $cases = array())
    {
        $this->m_export_id = $export_id;
        $this->m_export_name = $export_name;
        $this->m_export_nr = $export_nr;
        $this->m_next_tan = $next_tan;
        $this->_exportUniqueId = $exportUniqueId;
        $this->m_org_id = $org_id;
        $this->m_melder_id = $melder_id;
        $this->m_parameters = unserialize($parameters);
        $this->m_finished = $finished;
        $this->m_create_user_id = $create_user_id;
        $this->m_create_time = $create_time;
        $this->m_update_time = $update_time;
        $this->m_cases = $cases;
    }


    /**
     * Clear
     *
     * @access  public
     * @return  void
     */
    public function Clear()
    {
        $this->m_export_id = 0;
        $this->m_export_name = '';
        $this->m_export_nr = 0;
        $this->m_next_tan = 1;
        $this->_exportUniqueId = "";
        $this->m_org_id = 0;
        $this->m_melder_id = 0;
        $this->m_parameters = null;
        $this->m_finished = 0;
        $this->m_create_user_id = 0;
        $this->m_create_time = null;
        $this->m_update_time = null;
        $this->m_cases = array();
    }


    /**
     * getDbId
     *
     * @access  public
     * @return  int
     */
    public function getDbId()
    {
        return $this->m_export_id;
    }


    /**
     * setDbId
     *
     * @access  public
     * @param   int $export_id
     * @return  RExport
     */
    public function setDbId($export_id)
    {
        $this->m_export_id = $export_id;

        return $this;
    }


    /**
     * getExportName
     *
     * @access  public
     * @return  string
     */
    public function getExportName()
    {
        return $this->m_export_name;
    }


    /**
     * setExportName
     *
     * @access  public
     * @param   string  $export_name
     * @return  RExport
     */
    public function setExportName($export_name)
    {
        $this->m_export_name = $export_name;

        return $this;
    }


    /**
     * getExportNr
     *
     * @access  public
     * @return  int
     */
    public function getExportNr()
    {
        return $this->m_export_nr;
    }


    /**
     * setExportNr
     *
     * @access  public
     * @param   string  $export_nr
     * @return  RExport
     */
    public function setExportNr($export_nr)
    {
        $this->m_export_nr = $export_nr;

        return $this;
    }

    public function getNextTan()
    {
        return $this->m_next_tan;
    }

    public function setNextTan($next_tan)
    {
        $this->m_next_tan = $next_tan;
    }

    public function setExportUniqueId($exportUniqueId)
    {
        $this->_exportUniqueId = $exportUniqueId;

        return $this;
    }

    public function getExportUniqueId()
    {
        return $this->_exportUniqueId;
    }

    public function getOrgId()
    {
        return $this->m_org_id;
    }

    public function setOrgId($org_id)
    {
        $this->m_org_id = $org_id;

        return $this;
    }

    public function getMelderId()
    {
        return $this->m_melder_id;
    }

    public function setMelderId($melder_id)
    {
        $this->m_melder_id = $melder_id;
    }

    public function getParameters()
    {
        return $this->m_parameters;
    }

    public function setParameters($parameters)
    {
        $this->m_parameters = $parameters;

        return $this;
    }

    public function getFinished()
    {
        return $this->m_finished;
    }

    public function setFinished($finished)
    {
        $this->m_finished = $finished;

        return $this;
    }

    public function getCreateUserId()
    {
        return $this->m_create_user_id;
    }

    public function setCreateUserId($create_user_id)
    {
        $this->m_create_user_id = $create_user_id;

        return $this;
    }

    public function getCreateTime()
    {
        return $this->m_create_time;
    }


    /**
     * setCreateTime
     *
     * @access  public
     * @param   string  $create_time
     * @param   bool $updateChildren
     * @return  void
     */
    public function setCreateTime($create_time, $updateChildren = false)
    {
        $this->m_create_time = $create_time;

        if ($updateChildren) {
            foreach($this->m_cases as $case) {
                if ($case instanceof RExportCase) {
                    $case->setCreatetime($create_time, $updateChildren);
                }
            }
        }
    }

    public function getUpdateTime()
    {
        return $this->m_update_time;
    }

    public function setUpdateTime($update_time)
    {
        $this->m_update_time = $update_time;
    }


    /**
     * getCases
     *
     * @access  public
     * @return  RExportCase[]
     */
    public function getCases()
    {
        return $this->m_cases;
    }


    /**
     * setCases
     *
     * @access  public
     * @param   RExportCase[] $cases
     * @return  RExport
     */
    public function setCases(array $cases)
    {
        $this->m_cases = array();

        foreach ($cases as $case) {
            $this->addCase($case);
        }

        return $this;
    }


    /**
     * addCase
     *
     * @access  public
     * @param   RExportCase $case
     * @return  RExport
     * @throws  EExportException
     */
    public function addCase(RExportCase $case)
    {
        $this->m_cases[] = $case;

        return $this;
    }


    /**
     * read
     *
     * @access  public
     * @param   resource $db
     * @param   string   $export_name
     * @param   string   $exportUniqueId
     * @param   int      $org_id
     * @param   string   $search_type
     * @return  bool
     */
    public function read($db, $export_name, $exportUniqueId, $org_id, $search_type = '')
    {
        $this->Clear();

        $query = "
            SELECT
                *
            FROM
                export_log el
            WHERE
                el.export_name='{$export_name}' AND
                el.org_id={$org_id} AND
                el.export_unique_id='{$exportUniqueId}'
        ";

        if ('finished' === strtolower($search_type)) {
            $query .= "AND el.finished=1 ";
        } else if ('not_finished' === strtolower($search_type)) {
            $query .= "AND el.finished=0 ";
        }

        $query .= "
            ORDER BY
                el.createtime,
                el.export_nr
        ";

        $result = end(sql_query_array($db, $query));

        if (false !== $result) {
            $this->m_export_id = (int)$result['export_log_id'];
            $this->m_export_name = $result['export_name'];
            $this->m_export_nr = (int)$result['export_nr'];
            $this->m_next_tan = (int)$result['next_tan'];
            $this->_exportUniqueId = $result['export_unique_id'];
            $this->m_org_id = (int)$result['org_id'];
            $this->m_melder_id = (int)$result['melder_id'];
            $this->m_parameters = unserialize($result['parameters']);
            $this->m_finished = (int)$result['finished'];
            $this->m_create_user_id = (int)$result['createuser'];
            $this->m_create_time = $result['createtime'];
            $this->m_update_time = $result['updatetime'];

            // get all cases for the selected export
            $query = "
                SELECT
                    *
                FROM
                    export_case_log ecl
                WHERE
                    ecl.export_log_id = '{$result['export_log_id']}'
                ORDER BY
                    ecl.patient_id,
                    ecl.erkrankung_id,
                    ecl.diagnose_seite
            ";

            $result_data = sql_query_array($db, $query);

            if (false !== $result_data) {
                foreach ($result_data as $row) {
                    $case = new RExportCase;
                    $case->Create($db, $row, true);
                    $this->m_cases[] = $case;
                }
            }

            return true;
        }

        return false;
    }


    /**
     * write
     *
     * @access  public
     * @param   resource    $db
     * @return  void
     */
    public function write($db)
    {
        $parameters = serialize($this->m_parameters);

        if (0 === $this->m_export_id) {
            // Do insert
            $createtime = date("c", time());
            $this->setCreatetime($createtime, true);

            $query = "
                INSERT INTO export_log (
                    export_name,
                    export_nr,
                    next_tan,
                    export_unique_id,
                    org_id,
                    melder_id,
                    parameters,
                    finished,
                    createuser,
                    createtime,
                    updatetime)
                VALUES (
                    '{$this->m_export_name}',
                    {$this->m_export_nr},
                    {$this->m_next_tan},
                    '{$this->_exportUniqueId}',
                    {$this->m_org_id},
                    {$this->m_melder_id},
                    '{$parameters}',
                    {$this->m_finished},
                    {$this->m_create_user_id},
                    '{$this->m_create_time}',
                    NOW()
               )
            ";
            @mysql_query($query, $db);
            $this->m_export_id = @mysql_insert_id($db);
        }
        else {
            // Do update
            $query = "
                UPDATE export_log SET
                    export_name='{$this->m_export_name}',
                    export_nr={$this->m_export_nr},
                    next_tan={$this->m_next_tan},
                    export_unique_id='{$this->_exportUniqueId}',
                    org_id={$this->m_org_id},
                    melder_id={$this->m_melder_id},
                    parameters='{$parameters}',
                    finished={$this->m_finished},
                    createuser={$this->m_create_user_id},
                    createtime='{$this->m_create_time}',
                    updatetime=NOW()

                WHERE
                    export_log_id={$this->m_export_id}
            ";
            @mysql_query($query, $db);
        }

        // Write references
        foreach ($this->m_cases as $case) {
            $case->setExportId($this->m_export_id);
            $case->Write($db);
        }
    }


    /**
     * removeCasesWithoutPatientId
     *
     * @access  public
     * @param   resource $db
     * @param   array $patientIds
     * @return  RExport
     */
    public function removeCasesWithoutPatientId($db, array $patientIds)
    {
        foreach ($this->getCases() as $i => $case) {
            if (in_array($case->getPatientId(), $patientIds) === false) {
                $case->Delete($db);
                unset($this->m_cases[$i]);
            }
        }

        return $this;
    }


    /**
     * set finish flag to db and model
     *
     * @access  public
     * @param   resource $db
     * @param   int $exportId
     * @return  RExport
     */
    public function markAsFinished($db, $exportId = null)
    {
        // take model export id if not given
        if ($exportId === null) {
            $exportId = $this->m_export_id;
        }

        if ($exportId !== 0) {
            $this->setFinished(1);

            $query = "
                UPDATE export_log SET
                    finished = 1,
                    updatetime = NOW()
                WHERE
                    export_log_id = '{$exportId}'
            ";

            @mysql_query($query, $db);
        }

        return $this;
    }


    /**
     * delete
     *
     * @access  public
     * @param   resource    $db
     * @param   int $export_id
     * @return  void
     * @throws  EExportException
     */
    public function delete($db, $export_id = 0)
    {
        $dbid = $export_id;
        if (0 === $dbid) {
            $dbid = $this->m_export_id;
        }
        if (0 !== $dbid) {
            // Delete refernces
            foreach($this->m_cases as $case) {
                if ($case instanceof RExportCase) {
                    $case->Delete($db);
                }
            }
            // Delete object
            $query = "DELETE FROM export_log WHERE export_log_id = '{$dbid}'";

            @mysql_query($query, $db);

            $this->Clear();
        } else {
            throw new EExportException("ERROR: Export dbid is null, delete failed.");
        }
    }


    /**
     * getPatienCount
     *
     * @access  public
     * @return  int
     */
    public function getPatienCount()
    {
        $patients = array();

        foreach ($this->m_cases as $case) {
            $patients[$case->getPatientId()] = 1;
        }

        return count($patients);
    }


    /**
     * getValidCasesCount
     * ()
     *
     * @access  p
     * @return  int
     */
    public function getValidCasesCount()
    {
        $valid_cases = 0;
        foreach($this->m_cases as $case) {
            if (($case instanceof RExportCase) &&
                $case->IsCaseValid() &&
                (1 == $case->HasDataChanged())) {
                $valid_cases++;
            }
        }

        return $valid_cases;
    }

    public function getInvalidCasesCount()
    {
        $invalid_cases = 0;
        foreach($this->m_cases as $case) {
            if (($case instanceof RExportCase) &&
                !$case->IsCaseValid()) {
                $invalid_cases++;
            }
        }
        return $invalid_cases;
    }

    public function getAllInvalidSections()
    {
        $result = array();
        foreach($this->m_cases as $case) {
            if ($case instanceof RExportCase) {
                $result = array_merge($result, $case->getAllSectionsWithOnlyWarnings());
                $result = array_merge($result, $case->getAllInvalidSections());
            }
        }
        return array_values($result);
    }


    /**
     * getAllValidPatientIds
     *
     * @access  public
     * @param   int $invaild_patients_count
     * @return  array
     */
    public function getAllValidPatientIds(&$invaild_patients_count)
    {
        $result = array();
        $patient_ids = array();
        $invalid_patient_ids = array();

        foreach ($this->m_cases as $case) {
            if ($case instanceof RExportCase) {
                $patient_ids[$case->getPatientId()] = $case->getPatientId();

                if (!$case->IsValid()) {
                    $invalid_patient_ids[$case->getPatientId()] = $case->getPatientId();
                }
            }
        }

        $invaild_patients_count = count($invalid_patient_ids);

        foreach ($invalid_patient_ids as $invalid_id) {
            if (isset($patient_ids[$invalid_id])) {
                unset($patient_ids[$invalid_id]);
            }
        }

        foreach ($patient_ids as $id) {
            $result[] = $id;
        }

        return $result;
    }


    /**
     * WriteAllValidPatientIds
     *
     * @access  public
     * @param   resource $db
     * @return  void
     */
    public function WriteAllValidPatientIds($db)
    {
        $invaild_patients_count = 0;
        $vaild_partient_ids = $this->getAllValidPatientIds($invaild_patients_count);

        foreach ($vaild_partient_ids as $patient_id) {
            $query = "
                INSERT INTO export_patient_ids_log (
                    export_name,
                    export_unique_id,
                    patient_id,
                    export_log_id
               )
                VALUES (
                    '{$this->m_export_name}',
                    '{$this->_exportUniqueId}',
                    {$patient_id},
                    {$this->m_export_id}
               )
            ";
            @mysql_query($query, $db);
        }
    }


    /**
     * getNextExportNr
     *
     * @static
     * @access  public
     * @param   resource    $db
     * @param   string      $exportName
     * @param   string      $exportUniqueId
     * @return  int
     */
    public static function getNextExportNr($db, $exportName, $exportUniqueId)
    {
        $query = "
            SELECT
                IFNULL(MAX(export_nr), 0) AS max_export_nr
            FROM
                export_log el
            WHERE
                el.export_name = '{$exportName}' AND
                el.export_unique_id = '{$exportUniqueId}'
        ";

        $result = end(sql_query_array($db, $query));

        if (false !== $result) {
            return($result['max_export_nr'] + 1);
        }

        return 1;
    }


    /**
     * ReadLastFinished
     *
     * @static
     * @access  public
     * @param   resource    $db
     * @param   string      $export_name
     * @param   string      $exportUniqueId
     * @param   int         $org_id
     * @return  bool|RExport
     */
    static public function ReadLastFinished($db, $export_name, $exportUniqueId, $org_id)
    {
        $data = new RExport;

        if (false === $data->Read($db, $export_name, $exportUniqueId, $org_id, 'finished')) {
            return false;
        }

        return $data;
    }

}
