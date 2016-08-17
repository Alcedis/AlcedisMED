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

require_once( 'class.importexception.php' );

class HFileSystem
{

	  /**
	   *
	   * @param $path
	   * @param $umask
	   */
    static public function CreatePath( $path, $umask = 0002 )
    {
        umask( $umask );
        if ( !file_exists( $path ) ) {
            if ( !mkdir( $path, 0777, true ) ) {
                throw new EImportException( "ERROR: Could not create path [$path]." );
            }
        }
    }

    /**
     *
     * @param $path
     * @return unknown_type
     */
    static public function CeckPath( $path )
    {
        if ( ( $path[ strlen( $path ) - 1 ] != "\\" ) ||
             ( $path[ strlen( $path ) - 1 ] != "/" ) ) {
            $path .= "/";
        }
        return $path;
    }

    /**
     *
     * @param $dir
     */
    static public function DeleteDirectory( $dir )
    {
        if ( !$dh = @opendir( $dir ) ) {
            return;
        }
        while( false !== ( $obj = readdir( $dh ) ) ) {
            if ( $obj == '.' || $obj=='..' ) {
                continue;
            }
            if ( !@unlink( $dir . '/' . $obj ) ) {
                HFileSystem::DeleteDirectory( $dir . '/' . $obj );
            }
        }
        closedir( $dh );
        @rmdir( $dir );
    }

    /**
     *
     * @param $file
     * @param $receiver_uid
     * @param $working_dir
     * @param $public_key
     * @param $pgp_binary
     */
    static public function FileEncryption( $file,
                                           $receiver_uid,
                                           $working_dir,
                                           $public_key,
                                           $pgp_binary ) {
        HFileSystem::CreatePath( $working_dir );
        $absolut_public_key = getcwd() . $public_key;
        $cmd = "$pgp_binary --homedir '$working_dir' --import '$absolut_public_key'";
        exec( $cmd );
        $cmd = "$pgp_binary --homedir '$working_dir' --trust-model always -r \"$receiver_uid\" -e '$file'";
        exec( $cmd );
    }

    /**
     *
     * @param $dir
     * @param $do_sort
     * @return unknown_type
     */
    static public function GetFilenames( $dir, $do_sort = false ) {
        $file_names = array();
        if ( !is_dir( $dir ) ||
             !is_writable( $dir ) ) {
            return array();
        }
        $dir = HFileSystem::CeckPath( $dir );
        if ( $dir_h = opendir( $dir ) ) {
            while( $cur_file_name = readdir( $dir_h ) ) {
        		    if( is_file( $dir . $cur_file_name ) ) {
                    $file_names[] = $cur_file_name;
        		    }
            }
            closedir( $dir_h );
            if ( $do_sort ) {
                sort( $file_names );
            }
            return $file_names;
        }
        return array();
    }

}

?>
