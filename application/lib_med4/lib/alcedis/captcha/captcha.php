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

class captcha
{
    protected $_captchaPath = null;

    protected $_type = 'png';

    protected $_ttf  = 'XFILES';

    protected $_prefix = 'captcha';

    protected $_length = 5;

    protected $_img = null;

    protected $_text = null;

    public function __construct()
    {
        $this->_captchaPath = DIR_LIB . '/alcedis/captcha/media/';

        return $this;
    }


    public static function create()
    {
        return new self();
    }

    public function generate()
    {
        $this->_text = self::randomString($this->_length);

        return $this;
    }

    /**
     * returns captcha text
     *
     * @return string
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * renders img and returns base64 encoded string
     */
    public function render()
    {
        $path   = $this->_captchaPath . concat(array($this->_prefix . rand(1,2), $this->_type), '.');
        $img    = ImageCreateFromPNG($path);

        $color  = ImageColorAllocate($img, 0, 0, 0);
        $ttf    = $this->_captchaPath . $this->_ttf . '.TTF';

        imagettftext($img, 25, rand(0,5), rand(5,30), 35, $color, $ttf, $this->getText());

        ob_start ();

        imagepng ($img);
        $image_data = ob_get_contents();

        ob_end_clean ();

        imagedestroy($img);

        return base64_encode ($image_data);
    }


    public static function randomString($len) {
        function make_seed() {
            list($usec , $sec) = explode (' ', microtime());
            return (float) $sec + ((float) $usec * 100000);
        }

        srand(make_seed());

        $possible="ABCDEFGHJKLMNPRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789";

        $str="";

        while (strlen($str) < $len) {
            $str .= substr($possible,(rand()%(strlen($possible))),1);
        }

        return $str;
    }


    /**
     * sets length of the captcha
     * @param int $length
     */
    public function setLength($length)
    {
        if (is_int($length) === true) {
            $this->_length = $length;
        }

        return $this;
    }

    /**
     * returns captcha length
     * @return number
     */
    public function getLength()
    {
        return $this->_length;
    }
}

?>