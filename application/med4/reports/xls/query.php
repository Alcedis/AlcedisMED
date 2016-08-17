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

class reportContentQuery extends customReport
{
   public function generate()
   {
      $vorlageQueryId = isset($this->_params['id']) === true ? $this->_params['id'] : null;

      if ($vorlageQueryId !== null)
      {
         $query = "
            SELECT
               bez,
               sqlstring
            FROM vorlage_query
            WHERE vorlage_query_id = '$vorlageQueryId'
         ";

         $result       = reset(sql_query_array($this->_db, $query));
         $this->_title = $result['bez'];
         $sql          = self::parseSql($result['sqlstring']);
         $this->_data  = sql_query_array($this->_db, $sql);

         $this->writeXLS();
      }
   }

   //static function, called in ext_err of rec.vorlage_query
   public static function parseSql($sql)
   {
      return stripslashes( trim( $sql ) );
   }
}

?>