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

class mimeType
{
    private static $_mimeTypes = array(
        'ai'    => 'application/postscript',
        'aif'   => 'audio/x-aiff',
        'aifc'  => 'audio/x-aiff',
        'aiff'  => 'audio/x-aiff',
        'avi'   => 'video/x-msvideo',
        'bin'   => 'application/macbinary',
        'bmp'   => 'image/bmp',
        'class' => 'application/octet-stream',
        'cpt'   => 'application/mac-compactpro',
        'css'   => 'text/css',
        'csv'   => 'text/csv',
        'dcr'   => 'application/x-director',
        'dir'   => 'application/x-director',
        'dll'   => 'application/octet-stream',
        'dms'   => 'application/octet-stream',
        'doc'   => 'application/msword',
        'doc'   => 'application/msword',
        'dvi'   => 'application/x-dvi',
        'dxr'   => 'application/x-director',
        'eml'   => 'message/rfc822',
        'eps'   => 'application/postscript',
        'exe'   => 'application/octet-stream',
        'gif'   => 'image/gif',
        'gtar'  => 'application/x-gtar',
        'hqx'   => 'application/mac-binhex40',
        'htm'   => 'text/html',
        'html'  => 'text/html',
        'jpe'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'jpg'   => 'image/jpeg',
        'js'    => 'application/x-javascript',
        'json'  => 'application/json',
        'lha'   => 'application/octet-stream',
        'log'   => 'text/plain',
        'lzh'   => 'application/octet-stream',
        'mid'   => 'audio/midi',
        'midi'  => 'audio/midi',
        'mif'   => 'application/vnd.mif',
        'mov'   => 'video/quicktime',
        'movie' => 'video/x-sgi-movie',
        'mp2'   => 'audio/mpeg',
        'mp3'   => 'audio/mpeg',
        'mpe'   => 'video/mpeg',
        'mpeg'  => 'video/mpeg',
        'mpg'   => 'video/mpeg',
        'mpga'  => 'audio/mpeg',
        'oda'   => 'application/oda',
        'odt'   => 'application/vnd.oasis.opendocument.text',
        'pdf'   => 'application/pdf',
        'php'   => 'application/x-httpd-php',
        'php3'  => 'application/x-httpd-php',
        'php4'  => 'application/x-httpd-php',
        'phps'  => 'application/x-httpd-php-source',
        'phtml' => 'application/x-httpd-php',
        'png'   => 'image/png',
        'ppt'   => 'application/vnd.ms-powerpoint',
        'ps'    => 'application/postscript',
        'psd'   => 'application/octet-stream',
        'qt'    => 'video/quicktime',
        'ra'    => 'audio/x-realaudio',
        'ram'   => 'audio/x-pn-realaudio',
        'rm'    => 'audio/x-pn-realaudio',
        'rpm'   => 'audio/x-pn-realaudio-plugin',
        'rtf'   => 'text/rtf',
        'rtx'   => 'text/richtext',
        'rv'    => 'video/vnd.rn-realvideo',
        'sea'   => 'application/octet-stream',
        'shtml' => 'text/html',
        'sit'   => 'application/x-stuffit',
        'smi'   => 'application/smil',
        'smil'  => 'application/smil',
        'so'    => 'application/octet-stream',
        'swf'   => 'application/x-shockwave-flash',
        'tar'   => 'application/x-tar',
        'text'  => 'text/plain',
        'tgz'   => 'application/x-tar',
        'tif'   => 'image/tiff',
        'tiff'  => 'image/tiff',
        'txt'   => 'text/plain',
        'wav'   => 'audio/x-wav',
        'wbxml' => 'application/vnd.wap.wbxml',
        'wmlc'  => 'application/vnd.wap.wmlc',
        'word'  => 'application/msword',
        'xht'   => 'application/xhtml+xml',
        'xhtml' => 'application/xhtml+xml',
        'xl'    => 'application/excel',
        'xls'   => 'application/vnd.ms-excel',
        'xml'   => 'text/xml',
        'xsl'   => 'text/xml',
        'zip'   => 'application/zip'
    );

    public static function create() {
        return new self();
    }

    public function get($type) {
        $type = strtolower($type);

        if (isset(self::$_mimeTypes[$type]) === true) {
            $mimeType = self::$_mimeTypes[$type];
        } else {
            $mimeType = 'application/x-unknown-content-type';
        }

        return $mimeType;
    }


    /**
     * Returns the mime type of a file
     *
     * @access  public
     * @param   string  $file
     * @return  string
     */
    public static function getMimeTypeFromFile($file)
    {
        $extension = self::getExtension($file);

        return self::get($extension);
    }

    public static function getExtension($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }
}

?>