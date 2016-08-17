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

class HFileSystem
{
    /**
     *
     *
     * @static
     * @access public
     * @param     $path
     * @param int $umask
     * @return void
     * @throws EExportException
     */
    static public function CreatePath($path, $umask = 0002)
    {
        umask($umask);
        if (!file_exists($path)) {
            if (!mkdir($path, 0777, true)) {
                throw new EExportException("ERROR: Could not create path [$path].");
            }
        }
    }


    /**
     *
     *
     * @static
     * @access public
     * @param $dir
     * @return void
     */
    static public function DeleteDirectory($dir)
    {
        if (!$dh = @opendir($dir)) {
            return;
        }
        while (false !== ($obj = readdir($dh))) {
            if ($obj == '.' || $obj=='..') {
                continue;
            }
            if (!@unlink($dir . '/' . $obj)) {
                HFileSystem::DeleteDirectory($dir . '/' . $obj);
            }
        }
        closedir($dh);
        @rmdir($dir);
    }


    /**
     *
     *
     * @static
     * @access public
     * @param $file
     * @param $receiverUid
     * @param $workingDir
     * @param $publicKey
     * @param $pgpBinary
     * @return void
     */
    static public function FileEncryption($file, $receiverUid, $workingDir, $publicKey, $pgpBinary) {
        HFileSystem::CreatePath($workingDir);
        $absolutPublicKey = getcwd() . $publicKey;
        $cmd = "$pgpBinary --homedir '$workingDir' --import '$absolutPublicKey'";
        exec( $cmd );
        $cmd = "$pgpBinary --homedir '$workingDir' --trust-model always -r \"$receiverUid\" -e '$file'";
        exec( $cmd );
    }

}

?>
