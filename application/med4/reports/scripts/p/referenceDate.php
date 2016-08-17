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

require_once 'reports/scripts/reportHelper.php';
require_once 'core/class/customReport.php';


class referenceDate extends customReport
{
    /**
     * row separator
     *
     * @var string
     */
    const SEPARATOR_ROWS = "\x01";


    /**
     * col separator
     *
     * @var string
     */
    const SEPARATOR_COLS = "\x02";


    /**
     * _db
     *
     * @access  protected
     * @var     resource
     */
    protected $_db;


    /**
     * _diseaseIds
     *
     * @access  protected
     * @var     string
     */
    protected $_diseaseIds;


    /**
     * _cache
     *
     * @access  protected
     * @var     array
     */
    protected $_cache = array();


    /**
     * referenceDateFields
     * (attention: order of this fields are very important because they reflect the priority order of reference date)
     *
     * @access  protected
     * @var     array
     */
    protected $_referenceDateFields = array(
        'bezug_rpe',
        'bezug_rze',
        'bezug_perk',
        'bezug_seed',
        'bezug_hdr',
        'bezug_as',
        'bezug_ww',
        'bezugsdatum_cpz_andere_lokale_therapie',
        'bezugsdatum_cpz_ausschliesslich_systemisch',
        'bezugsdatum_cpz_andere_behandlung'
    );


    /**
     * referenceDate constructor.
     *
     * @access  public
     * @param   resource $db
     * @param   string $diseaseIds
     */
    public function __construct($db, $diseaseIds)
    {
        $this->_db = $db;
        $this->_diseaseIds = $diseaseIds;
    }


    /**
     * create referenceDate
     *
     * @static
     * @access  public
     * @param   resource $db
     * @param   string $diseaseIds
     * @return  referenceDate
     */
    public static function create($db, $diseaseIds)
    {
        return new self($db, $diseaseIds);
    }


    /**
     * _buildReferenceData
     *
     * @access  public
     * @param   array $record
     * @return  array
     */
    public function buildReferenceDates(array $record)
    {
        // create empty data array
        $data = array_fill_keys($this->_referenceDateFields, null);

        // process dates only if primary case
        if ($this->_isPrimary($record) === true) {
            // known method calls for better parameter handling
            $data['bezug_rpe']  = $this->_getReferenceDateRpe($record);
            $data['bezug_rze']  = $this->_getReferenceDateRze($record);
            $data['bezug_seed'] = $this->_getReferenceDateSeed($record);
            $data['bezug_hdr']  = $this->_getReferenceDateHdr($record);
            $data['bezug_perk'] = $this->_getReferenceDatePerk($record);

            $asWw = $this->_buildActiveSurveillanceAndWatchfulWaiting($record);

            $data['bezug_as'] = $asWw['active_surveillance'];
            $data['bezug_ww'] = $asWw['watchful_waiting'];

            $data['bezugsdatum_cpz_andere_lokale_therapie']     = $this->_getReferenceDateCpzOtherLocalTherapy($record);
            $data['bezugsdatum_cpz_ausschliesslich_systemisch'] = $this->_getReferenceDateCpzSystemicTherapy($record);
            $data['bezugsdatum_cpz_andere_behandlung']          = $this->_getReferenceDateCpzOtherTreatment($record);

            $finalReferenceDates = array_filter($data);

            // process only if final reference
            if (count($finalReferenceDates) > 0) {
                $finalReferenceData = $this->_calculateFinalReferenceData($record, $finalReferenceDates);

                // append final reference data
                $data = array_merge($data, $finalReferenceData);
            }
        }

        return $data;
    }


    /**
     * _calculateFinalReferenceData
     * (attention: $dates must not contain null values and must not empty!)
     *
     * @access  protected
     * @param   array $record
     * @param   array $dates
     * @return  array
     */
    protected function _calculateFinalReferenceData(array $record, array $dates)
    {
        $exclude = array(
            'bezug_as' => null,
            'bezug_ww' => null
        );

        $data = array(
            'therapie_bezugsdatum' => null,
            'bezugsdatum' => null
        );

        // sort dates and with storing key names
        asort($dates);

        // remove as and ww dates from date array so we could check prio 1 or prio 2
        $prio1Dates = array_diff_key($dates, $exclude);

        // check prio 1 dates (without as and ww!)
        if (count($prio1Dates) > 0) {
            $firstDate = reset($prio1Dates);
            $firstYear = substr($firstDate, 0, 4);

            // find first matching year
            foreach ($this->_referenceDateFields as $fieldName) {
                if (array_key_exists($fieldName, $prio1Dates) === true) {

                    $fieldDate = $prio1Dates[$fieldName];

                    // if fieldDate year same as lowest year
                    if (substr($fieldDate, 0, 4) === $firstYear) {
                        $data['bezugsdatum'] = $fieldDate;
                        $data['therapie_bezugsdatum'] = $fieldName;

                        break;
                    }
                }
            }
        } else { // prio 2 this can only be one (as or ww)
            $data['bezugsdatum']          = reset($dates);
            $data['therapie_bezugsdatum'] = reset(array_keys($dates));
        }

        return $data;
    }


    /**
     * _getReferenceDateRpe
     * (returns the reference date for field "bezug_rpe")
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _getReferenceDateRpe(array $record)
    {
        $date = null;

        // only process if field rpe is 1
        if ($record['rpe'] == '1') {
            // get first rpe form (it's an op form)
            $firstRecord = $this->_getFirstRecord($record['bezug_rpe'], array('id', 'date'), 'date');

            // if found (it should because rpe is 1) get reference date
            if ($firstRecord !== null) {
                // op contains "therapieplan_id" which is required for finding the reference date
                $op = $this->_getOp($firstRecord['id']);

                $date = $this->_findReferenceDate($record['erkrankung_id'], $op['therapieplan_id'], $firstRecord['date']);
            }
        }

        return $date;
    }


    /**
     * _getReferenceDateRze
     * (returns the reference date for field "bezug_rze")
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _getReferenceDateRze(array $record)
    {
        $date = null;

        if ($record['rze'] === '1') {
            // get first rze form (it's an op form)
            $firstRecord = $this->_getFirstRecord($record['bezug_rze'], array('id', 'date'), 'date');

            // if found (it should because rze is 1) get reference date
            if ($firstRecord !== null) {
                // op contains "therapieplan_id" which is required for finding the reference date
                $op = $this->_getOp($firstRecord['id']);

                $incidentialFinding = strlen($record['zufallsbefund']) > 0;

                if ($incidentialFinding === true) {
                    $date = $firstRecord['date'];
                } else {
                    $date = $this->_findReferenceDate($record['erkrankung_id'], $op['therapieplan_id'], $firstRecord['date']);
                }
            }
        }

        return $date;
    }


    /**
     * _getReferenceDateSeed
     * (returns the reference date for field "bezug_seed")
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _getReferenceDateSeed(array $record)
    {
        $date = null;

        if (strlen($record['str_permanent_seed']) > 0) {
            // get first seed form (it's an radio form)
            $firstRecord = $this->_getFirstRecord($record['bezug_seed'], array('id', 'date', 'therapyPlanId'), 'date');

            // if found (it should because str_permanent_seed is 1) get reference date
            if ($firstRecord !== null) {
                $date = $this->_findReferenceDate($record['erkrankung_id'], $firstRecord['therapyPlanId'], $firstRecord['date']);
            }
        }

        return $date;
    }


    /**
     * _getReferenceDateHdr
     * (returns the reference date for field "bezug_hdr")
     *
     * @access  protected
     * @param   array   $record
     * @return  string
     */
    protected function _getReferenceDateHdr(array $record)
    {
        $date = null;

        if (strlen($record['hdr_brachytherapie']) > 0) {
            // get first hdr form (it's an radio form)
            $firstRecord = $this->_getFirstRecord($record['bezug_hdr'], array('id', 'date', 'therapyPlanId'), 'date');

            // if found (it should because hdr_brachytherapie is 1) get reference date
            if ($firstRecord !== null) {
                $date = $this->_findReferenceDate($record['erkrankung_id'], $firstRecord['therapyPlanId'], $firstRecord['date']);
            }
        }

        return $date;
    }


    /**
     * _getReferenceDatePerk
     * (returns the reference date for field "bezug_perk")
     *
     * @access  protected
     * @param   array   $record
     * @return  string
     */
    protected function _getReferenceDatePerk(array $record)
    {
        $date = null;

        if (strlen($record['def_perkutane_strahlentherapie']) > 0) {
            // get first perk radio form (it's an radio form)
            $firstRecord = $this->_getFirstRecord($record['bezug_perk'], array('id', 'date', 'therapyPlanId'), 'date');

            // if found (it should because "def_perkutane_strahlentherapie" is 1) get reference date
            if ($firstRecord !== null) {
                $date = $this->_findReferenceDate($record['erkrankung_id'], $firstRecord['therapyPlanId'], $firstRecord['date']);
            }
        }

        return $date;
    }


    /**
     * _getReferenceDateOtherLocalTherapy
     *
     * @access  protected
     * @param   array   $record
     * @return  string
     */
    protected function _getReferenceDateCpzOtherLocalTherapy(array $record)
    {
        $date = null;

        if (strlen($record['bezugsdatum_cpz_andere_lokale_therapie'] > 0)) {
            // get cpz other local therapy form (it's an th_son form)
            $firstRecord = $this->_getFirstRecord($record['bezugsdatum_cpz_andere_lokale_therapie'], array('id', 'date', 'therapyPlanId'), 'date');

            // if found (it should because "_cpz_andere_lokale_therapie" is true) get reference date
            if ($firstRecord !== null) {
                $date = $this->_findReferenceDate($record['erkrankung_id'], $firstRecord['therapyPlanId'], $firstRecord['date']);
            }
        }

        return $date;
    }


    /**
     * returns the reference date for field "bezugsdatum_cpz_ausschliesslich_systemisch"
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _getReferenceDateCpzSystemicTherapy(array $record)
    {
        $date = null;

        if (strlen($record['bezugsdatum_cpz_ausschliesslich_systemisch']) > 0) {
            // get cpz systemic therapy form (it's an th_sys form)
            $firstRecord = $this->_getFirstRecord($record['bezugsdatum_cpz_ausschliesslich_systemisch'], array('id', 'date', 'therapyPlanId'), 'date');

            // if found (it should because "_cpz_ausschliesslich_systemisch" is true) get reference date
            if ($firstRecord !== null) {
                $date = $this->_findReferenceDate($record['erkrankung_id'], $firstRecord['therapyPlanId'], $firstRecord['date']);
            }
        }

        return $date;
    }


    /**
     * _getReferenceDateCpzOtherTreatment
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _getReferenceDateCpzOtherTreatment(array $record)
    {
        $date = null;

        if (strlen($record['bezugsdatum_cpz_andere_behandlung_andere_behandlung']) > 0 ||
            strlen($record['bezugsdatum_cpz_andere_behandlung_pall_strahlentherapie']) > 0 ||
            strlen($record['bezugsdatum_cpz_andere_behandlung_therapie_systemisch']) > 0 ||
            strlen($record['bezugsdatum_cpz_andere_behandlung_strahlentherapie']) > 0 ||
            strlen($record['bezugsdatum_cpz_andere_behandlung_palliative_versorgung']) > 0) {

            $stdFields = array('id', 'date', 'therapyPlanId');

            // build array of first therapy records
            $firstRecords = array(
                $this->_getFirstRecord($record['bezugsdatum_cpz_andere_behandlung_andere_behandlung'], $stdFields, 'date'),
                $this->_getFirstRecord($record['bezugsdatum_cpz_andere_behandlung_pall_strahlentherapie'], $stdFields, 'date'),
                $this->_getFirstRecord($record['bezugsdatum_cpz_andere_behandlung_therapie_systemisch'], $stdFields, 'date'),
                $this->_getFirstRecord($record['bezugsdatum_cpz_andere_behandlung_strahlentherapie'], $stdFields, 'date'),
                $this->_getFirstRecord($record['bezugsdatum_cpz_andere_behandlung_palliative_versorgung'], array('date', 'palli'), 'date')
            );

            // remove all null values (no therapy in this block)
            $firstRecords = array_filter($firstRecords);

            // process first records if min therapy or palli supply exists
            if (count($firstRecords) > 0) {
                $veryFirstRecords = array();

                // order very first records
                foreach ($firstRecords as $firstRecord) {
                    $veryFirstRecords[$firstRecord['date']][] = $firstRecord;
                }

                // order asc
                ksort($veryFirstRecords);

                // very first could also be more then 1 record (two therapies on same date)
                $veryFirstRecords = reset($veryFirstRecords);

                // only one very first therapy exists
                if (count($veryFirstRecords) === 1) {
                    $veryFirstRecord = reset($veryFirstRecords);

                    // if very first is palli supply
                    if (array_key_exists('palli', $veryFirstRecord) === true) {
                        $date = $veryFirstRecord['date'];
                    } else {
                        $date = $this->_findReferenceDate($record['erkrankung_id'], $veryFirstRecord['therapyPlanId'], $veryFirstRecord['date']);
                    }
                } else { // if two therapies on same date exists, get earliest therapyPlan date
                    $earliestTherapyPlanDates = array();

                    foreach ($veryFirstRecords as $firstRecord) {
                        // if very first is palli supply
                        if (array_key_exists('palli', $firstRecord) === true) {
                            $earliestTherapyPlanDates[] = $firstRecord['date'];
                        } else {
                            $earliestTherapyPlanDates[] = $this->_findReferenceDate($record['erkrankung_id'], $firstRecord['therapyPlanId'], $firstRecord['date']);
                        }
                    }

                    // remove null values
                    $earliestTherapyPlanDates = array_filter($earliestTherapyPlanDates);

                    // only take date if min one exists
                    if (count($earliestTherapyPlanDates) > 0) {
                        sort($earliestTherapyPlanDates);

                        $date = reset($earliestTherapyPlanDates);
                    }
                }
            }
        }

        return $date;
    }


    /**
     * _findReferenceDate
     *
     * @access  protected
     * @param   int    $diseaseId
     * @param   int    $therapyPlanId
     * @param   string $therapyDate
     * @return  string
     */
    protected function _findReferenceDate($diseaseId, $therapyPlanId, $therapyDate)
    {
        $date     = null;
        $timeline = array();

        // first get all therapyPlans for disease
        $therapyPlans = $this->_getTherapyPlans($diseaseId);

        // if therapyPlan was selected as relation in therapy, get tp date
        if ($therapyPlanId !== null) {
            foreach ($therapyPlans as $therapyPlan) {
                $tpDate = $therapyPlan['datum'];

                // find related therapyPlan
                if ($therapyPlan['id'] == $therapyPlanId) {
                    $date = $tpDate;
                    break;
                }
            }
        }

        // if date is null (cause $therapyPlanId is not set or no related therapyPlan found)
        if ($date === null) {
            $praeopConferences = $this->_getPraeopConferences($diseaseId);

            // find all praeop conference dates earlier then $therapyDate
            foreach ($praeopConferences as $conference) {
                $cDate = $conference['datum'];

                if ($cDate <= $therapyDate) {
                    $timeline[] = $cDate;
                }
            }

            // find all therapyPlan dates earlier then $therapyDate
            foreach ($therapyPlans as $therapyPlan) {
                $tpDate = $therapyPlan['datum'];

                if ($tpDate <= $therapyDate) {
                    $timeline[] = $tpDate;
                }
            }

            // only process if min one timeline entry exists
            if (count($timeline) > 0) {
                $timeline = array_unique($timeline);

                // sort timeline DESC
                rsort($timeline);

                // get nearest date to therapy
                $date = reset($timeline);
            }
        }

        return $date;
    }


    /**
     * _getPraeopConferences
     *
     * @access  protected
     * @param   int $diseaseId
     * @return  array
     */
    protected function _getPraeopConferences($diseaseId)
    {
        $cache = $this->getCache('praeopConferences');

        if ($cache === null) {
            $cache = $this->_mapArray(sql_query_array($this->_db, "
                SELECT
                    kp.konferenz_patient_id,
                    kp.erkrankung_id,
                    k.datum
                FROM konferenz_patient kp
                    INNER JOIN konferenz k ON k.konferenz_id = kp.konferenz_id
                WHERE
                    kp.art = 'prae' AND
                    kp.erkrankung_id IN ({$this->_diseaseIds})
                GROUP BY
                    kp.konferenz_patient_id
            "), 'erkrankung_id', true);

            $this->setCache('praeopConferences', $cache);
        }

        $conferences = array();

        // find therapyPlans for diseaseId
        if (array_key_exists($diseaseId, $cache) === true) {
            $conferences = $cache[$diseaseId];
        }

        return $conferences;
    }


    /**
     * _getTherapyPlans
     *
     * @access  protected
     * @param   int $diseaseId
     * @return  array
     */
    protected function _getTherapyPlans($diseaseId)
    {
        $cache = $this->getCache('therapyPlans');

        if ($cache === null) {
            $orgId      = $this->getParam('org_id');

            $cache = $this->_mapArray(sql_query_array($this->_db, "
                SELECT
                    tp.therapieplan_id as id,
                    tp.erkrankung_id,
                    tp.datum
                FROM therapieplan tp
                WHERE
                    tp.erkrankung_id IN ({$this->_diseaseIds}) AND
                    (tp.org_id IS NULL OR tp.org_id = '{$orgId}')
            "), 'erkrankung_id', true);

            $this->setCache('therapyPlans', $cache);
        }

        $therapyPlans = array();

        // find therapyPlans for diseaseId
        if (array_key_exists($diseaseId, $cache) === true) {
            $therapyPlans = $cache[$diseaseId];
        }

        return $therapyPlans;
    }


    /**
     * getOp
     * (get op record for id. caching cause several fields in query want more data but joining only on status table)
     *
     * @access  protected
     * @param   int $opId
     * @return  array
     */
    protected function _getOp($opId)
    {
        $record = null;
        $cache  = $this->getCache('op');

        // build cache if not already done
        if ($cache === null) {

            $cache = $this->_mapArray(sql_query_array($this->_db, "
                SELECT
                    eingriff_id,
                    therapieplan_id
                FROM eingriff
                WHERE
                    erkrankung_id IN ({$this->_diseaseIds})
            "), 'eingriff_id');

            $this->setCache('op', $cache);
        }

        // get record from cache with id
        if (array_key_exists($opId, $cache) === true) {
            $record = $cache[$opId];
        }

        return $record;
    }


    /**
     * _buildActiveSurveillanceAndWatchfulWaiting
     *
     * @access  protected
     * @param   $record
     * @return  array
     */
    protected function _buildActiveSurveillanceAndWatchfulWaiting(array $record)
    {
        $rowSeparator = HReports::SEPARATOR_ROWS;

        $result = array(
            'active_surveillance' => null,
            'watchful_waiting' => null
        );

        $dates = array();

        // iterate over record fields and explode therapyPlan dates for sorting
        foreach ($result as $fieldName => $dummy) {
            if (strlen($record[$fieldName]) > 0) {
                $therapyDates = explode($rowSeparator, $record[$fieldName]);

                foreach ($therapyDates as $date) {
                    $dates[$date] = $fieldName;
                }
            }
        }

        // if min one therapyPlan exist, fill result
        if (count($dates) > 0) {
            // sort asc
            ksort($dates);

            $earliestDate = reset(array_keys($dates));

            $choose = reset($dates);

            // set value to 1
            $result[$choose] = $earliestDate;
        }

        return $result;
    }


    /**
     * _mapArray
     *
     * @access  protected
     * @param   array   $array
     * @param   string  $key
     * @param   bool $multidimensional
     * @return  array
     */
    protected function _mapArray(array $array, $key, $multidimensional = false)
    {
        $result = array();

        if ($multidimensional === false) {
            foreach ($array as $arr) {
                $result[$arr[$key]] = $arr;
            }
        } else {
            foreach ($array as $arr) {
                $result[$arr[$key]][] = $arr;
            }
        }

        return $result;
    }


    /**
     * Sorts an array, by given field
     *
     * @access protected
     * @param  array  $array
     * @param  string $sortField
     * @param  bool   $asc
     * @return array
     */
    protected function _sortArray(array $array, $sortField, $asc = true)
    {
        $tmp = array();
        $sortedArray = array();

        foreach ($array as $dataset) {
            $tmp[$dataset[$sortField]][] = $dataset;
        }

        $asc === true ? ksort($tmp) : krsort($tmp);

        foreach ($tmp as $new) {
            if (count($new) > 1) {
                foreach ($new as $n) {
                    $sortedArray[] = $n;
                }
            } else {
                $sortedArray[] = $new[0];
            }
        }

        return $sortedArray;
    }


    /**
     * _getFirstRecord
     *
     * @access  protected
     * @param   string  $value
     * @param   array   $fields
     * @param   string  $orderField
     * @return  array
     */
    protected function _getFirstRecord($value, array $fields, $orderField)
    {
        $record = null;

        if (is_array($value) === true) {
            $record = reset($this->_sortArray($value, $orderField));
        } elseif (strlen($value) > 0) {
            $records = HReports::RecordStringToArray($value, $fields);

            $record = reset($records);
        }

        return $record;
    }


    /**
     * 'sonstige Therapie' = andere lokale Therapie || HIFU-Therapie (Hochintensiver fokussierter Ultraschall)
     *
     * @access      protected
     * @param       array $data
     * @return      string
     */
    protected function _cpz_andere_lokale_therapie(array $data)
    {
        return $data['cpz_andere_lokale_therapie'] !== null ? '1' : '0';
    }


    /**
     * _isPrimary
     *
     * @access protected
     * @param  array $data
     * @return bool
     */
    protected function _isPrimary(array $data)
    {
        return ($data['primaerfall'] == '1');
    }


    /**
     * getCache
     *
     * @access  public
     * @param   string  $entry
     * @param   mixed   $default
     * @return  mixed
     */
    public function getCache($entry, $default = null)
    {
        $cache = $default;

        if (array_key_exists($entry, $this->_cache) === true) {
            $cache = $this->_cache[$entry];
        }

        return $cache;
    }


    /**
     * setCache
     *
     * @access  public
     * @param   string  $entry
     * @param   mixed   $value
     * @return  $this
     */
    public function setCache($entry, $value)
    {
        $this->_cache[$entry] = $value;

        return $this;
    }
}
