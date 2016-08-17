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

require_once( 'class.exportexception.php' );

class HCommon
{

    /**
     *
     * @param $tnm
     * @return unknown_type
     */
    static public function TrimTNM( $tnm )
    {
        $pos = strpos( $tnm, "(" );
        $tmp = $tnm;
        if ( $pos !== false ) {
            $tmp = substr( $tnm, 0, $pos );
        }
        return $tmp;
    }

    /**
     *
     * @param $string
     * @param $length
     * @param $points_at_end
     * @return unknown_type
     */
    static public function TrimString( $string,
                                       $length,
                                       $points_at_end = true )
    {
        $tmp = $string;
        if ( strlen( $string ) > $length ) {
            if ( $points_at_end ) {
                $tmp = substr( $string, 0, $length - 3 );
                $tmp .= "...";
            }
            else {
                $tmp = substr( $string, 0, $length );
            }
        }
        return $tmp;
    }

}

?>
