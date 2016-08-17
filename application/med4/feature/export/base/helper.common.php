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

require_once('class.exportexception.php');

class HCommon
{

    /**
     *
     *
     * @static
     * @access public
     * @param $tnm
     * @return string
     */
    static public function TrimTNM($tnm)
    {
        $pos = strpos($tnm, "(");
        $tmp = $tnm;
        if ($pos !== false) {
            $tmp = substr($tnm, 0, $pos);
        }
        return $tmp;
    }


    /**
     *
     *
     * @static
     * @access public
     * @param      $string
     * @param      $length
     * @param bool $pointsAtEnd
     * @return string
     */
    static public function TrimString($string, $length, $pointsAtEnd = true)
    {
        $tmp = $string;
        if (strlen($string) > $length) {
            if ($pointsAtEnd) {
                $tmp = substr($string, 0, ($length - 3));
                $tmp .= "...";
            }
            else {
                $tmp = substr($string, 0, $length);
            }
        }
        return $tmp;
    }


    /**
     *
     *
     * @static
     * @access public
     * @param $date
     * @return bool|string
     */
    static public function GetYearAndMonth($date)
    {
        return date("Y-m", strtotime($date));
    }

}

?>
