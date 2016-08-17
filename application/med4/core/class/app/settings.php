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

class appSettings
{
    /**
     * _instance
     *
     * @access  private
     * @var     appSettings
     */
    private static $_instance;

    private $_db;


    /**
     * _settings
     *
     * @access  private
     * @var     array
     */
    private $_settings = array();


    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * set db ressource
     *
     * @param ressource $db
     * @return appSettings
     */
    public static function setDB($db)
    {
        $s = self::getInstance();

        $s->_db = $db;

        return $s;
    }


    /**
     * set
     * if var is null then overwrite complete settings, else only one $settingsvar
     *
     * @static
     * @access  public
     * @param   array   $settings
     * @param   string  $var
     * @return  appSettings
     */
    public static function set($settings, $var = null)
    {
        $s = self::getInstance();

        if ($var === null) {
            $s->_settings = $settings;
        } else {
            $s->_settings[$var] = $settings;
        }

        return $s;
    }


    /**
     * get settings variable
     *
     * @static
     * @param   string  $type
     * @param   string  $feature
     * @param   string  $contains
     * @return  string
     */
    public static function get($type = null, $feature = null, $contains = null)
    {
        $s = self::getInstance();

        $settingsVar = null;

        if ($type === null && $feature === null) {
            $settingsVar = $s->_settings;
        } else if ($feature === null) {
            if (array_key_exists($type, $s->_settings) === true) {
                if (is_array($s->_settings[$type]) === true) {
                    $settingsVar = $s->_settings[$type];
                } elseif (strlen($s->_settings[$type]) > 0) {
                    switch ($s->_settings[$type]) {
                        case '1': $settingsVar = true; break;
                        default: $settingsVar = $s->_settings[$type]; break;
                    }
                }
            }
        } else {
            if (array_key_exists('feature', $s->_settings) === true && array_key_exists($feature, $s->_settings['feature']) === true) {

                if ($type === null) {
                    $settingsVar = $s->_settings['feature'][$feature];
                } else {
                    if (array_key_exists($type, $s->_settings['feature'][$feature]) === true && strlen($s->_settings['feature'][$feature][$type]) > 0) {
                        switch ($s->_settings['feature'][$feature][$type]) {
                            case '1': $settingsVar = true; break;
                            default: $settingsVar = $s->_settings['feature'][$feature][$type]; break;
                        }
                    }
                }
            }
        }

        if ($settingsVar !== null && $settingsVar !== true && $contains !== null) {
            $settingsVar = str_contains($settingsVar, $contains);
        }

        return $settingsVar;
    }


    /**
     * refresh
     *
     * @static
     * @access  public
     * @return  appSettings
     */
    public static function refresh()
    {
        $s = self::getInstance();

        $result = sql_query_array($s->_db, "SELECT * FROM settings WHERE settings_id = '1'");

        $settings = reset($result);

        $diseases = array();
        $features = array();

        foreach ($settings as $key => $value) {
            switch (true) {
                case (strpos($key, 'erkrankung_') !== false) :
                    if ($value != null) {
                        $diseases[] = substr($key, 11);
                    }

                    unset($settings[$key]);

                    break;

                case (strpos($key, 'interface_') !== false) :
                    if ($value != null) {
                        $features[] = substr($key, 10);
                    }

                    unset($settings[$key]);

                    break;
            }
        }

        $settings['erkrankungen']    = implode(',', $diseases);
        $settings['interfaces']      = implode(',', $features);

        //HL7
        $hl7Settings = sql_query_array($s->_db, "SELECT * FROM settings_hl7");

        if (count($hl7Settings) == 1) {
            $settings['feature']['hl7'] = reset($hl7Settings);
        }

        //Tools
        $settingsForms = sql_query_array($s->_db, "SELECT * FROM settings_forms");

        foreach ($settingsForms as $settingForm) {
            $forms = json_decode($settingForm['forms'], true);
            formManager::setFormProperties($settingForm['org_id'], $forms);
        }

        $s->_settings = $settings;

        return $s;
    }
}

?>
