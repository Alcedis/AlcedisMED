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

class hl7Writer extends hl7ImportErkrankung
{
   protected $_aufnNrDelimiter = ", ";

   /**
    * info messages
    * @var type
    */
   protected $_infoMessages = array();


   /**
    * Writes massage data to cache
    *
    * @param type $dataset
    * @return int
    */
   public function writeToCache($dataset, $wasInserted = false)
   {
       $cacheId = $this->_identPatient($this->getSettings('patient_ident'), $dataset['patient']);

       //add process hash
       $dataset['patient']['hash'] = $this->getProcessHash();

       //New patient cache entry
       if (strlen($cacheId) == 0) {
           mysql_query($this->_buildSql('insert', 'hl7_cache', $dataset['patient']), $this->_db);

           return $this->writeToCache($dataset, true);
       } else {
           //write message
           $message = $dataset['message'];
           $message['hl7_cache_id'] = $cacheId;

           mysql_query($this->_buildSql('insert', 'hl7_message', $message), $this->_db);

           if ($wasInserted === false) {
               $patient = $dataset['patient'];
               $numbers = array();

               // update aufnahme_nr
               $currentNrs = dlookup($this->_db, 'hl7_cache', 'aufnahme_nr', "hl7_cache_id = '{$cacheId}'");

               if (strlen($currentNrs) > 0) {
                   foreach (explode($this->_aufnNrDelimiter, $currentNrs) as $i => $number) {
                       if (strlen(trim($number)) == 0) {
                           unset($numbers[$i]);
                       } else {
                           $numbers[] = trim($number);
                       }
                   }
               }

               if (strlen(trim($patient['aufnahme_nr'])) > 0) {
                   $numbers[] = trim($patient['aufnahme_nr']);
               }

               sort($numbers);

               $patient['aufnahme_nr']  = implode($this->_aufnNrDelimiter, array_unique($numbers));
               $patient['updatetime']   = $patient['createtime'];
               unset($patient['createtime']);
               $patient['hl7_cache_id'] = $cacheId;

               mysql_query($this->_buildSql('update', 'hl7_cache', $patient), $this->_db);
           }
       }

       return $cacheId;
   }


    /**
     * writeData
     *
     * @access
     * @param $cacheId
     * @return $this
     */
    public function writeData($cacheId)
    {
        $this->_imported = false;
        $this->_resetDiseaseId();

        $patient  = $this->_getCachePatient($cacheId);
        $messages = $this->_getCacheMessages($cacheId);

        $messagesLeft = count($messages);

        foreach ($messages as $message) {
            $this->_imported = false;

            $this
                ->loadMsg($message['message'], true)
                ->_import($patient)
            ;

            // if message was imported, delete it
            if ($this->_imported === true) {
                $this->_deleteCacheEntry($message['hl7_message_id'], 'message');
                $messagesLeft--;
            }
        }

        return $this->_inspectPatientCache($cacheId, $messagesLeft);
    }


    /**
     *
     *
     * @access
     * @param $cacheId
     * @param $messagesLeft
     * @return $this
     */
    private function _inspectPatientCache($cacheId, $messagesLeft)
    {
        //delete cache entry if no messages left
        if ($messagesLeft == 0) {
            $this->_deleteCacheEntry($cacheId);
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $patient
     * @return $this
     */
    protected function _import($patient)
    {
        foreach (self::$sections as $section) {
            $call = "_{$section}";
            $this->{$call}($patient);
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $cacheId
     * @return mixed
     */
    protected function _getCacheMessages($cacheId)
    {
        return sql_query_array($this->_db, "
            SELECT
                *
            FROM hl7_message
            WHERE
                hl7_cache_id = '{$cacheId}'
           ORDER BY
                message_control_id ASC
        ");
    }


    /**
     *
     *
     * @access
     * @param $cacheId
     * @return mixed
     */
    protected function _getCachePatient($cacheId)
    {
       return reset(sql_query_array($this->_db, "SELECT * FROM hl7_cache WHERE hl7_cache_id = '{$cacheId}'"));
    }


    /**
     *
     *
     * @access
     * @param        $id
     * @param string $table
     * @return $this
     */
    protected function _deleteCacheEntry($id, $table = 'cache')
    {
        mysql_query("DELETE FROM hl7_{$table} WHERE hl7_{$table}_id = '{$id}'", $this->_db);

        return $this;
    }
}

?>
