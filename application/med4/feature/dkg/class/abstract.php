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

abstract class dkgAbstract extends featureAbstract implements feature
{
    protected $_smarty = null;

    private $_hubs = array();

    private $_configBuffer = array();

    public function __construct($smarty)
    {
        $this->_smarty = $smarty;

        $this
            ->_loadConfig()
            ->_identifyActiveHubs()
            ->_buildHubs()
        ;
    }

    /**
     * loads dkg config
     *
     * @return dkgAbstract
     */
    private function _loadConfig()
    {
        $configBackup = $this->_smarty->get_config_vars();

        $this->_smarty->clear_config();

        $this->_smarty->config_load("../feature/dkg/config/dkg.conf");

        $this->_configBuffer['base'] = $this->_smarty->get_config_vars();

        $this->_smarty->set_config($configBackup);

        return $this;
    }


    public function getLabel($fieldNames = null, $table = null)
    {
        $label = null;
        $table = $table === null ? 'base' : $table;

        if (array_key_exists($table, $this->_configBuffer) === false) {
            $configBackup = $this->_smarty->get_config_vars();

            $this->_smarty->clear_config();
            $this->_smarty->config_load("app/{$table}.conf", 'rec');

            $this->_configBuffer[$table] = $this->_smarty->get_config_vars();
            $this->_smarty->clear_config();

            $this->_smarty->set_config($configBackup);
        }

        $config = $this->_configBuffer[$table];

        if ($fieldNames !== null) {
            $fieldNames  = is_array($fieldNames) === false ? array($fieldNames) : $fieldNames;

            foreach ($fieldNames as $fieldName) {
                if (array_key_exists($fieldName, $this->_configBuffer[$table]) === true) {
                    $label = $this->_configBuffer[$table][$fieldName];
                    break;
                }
            }
        } else {
            $label = $this->_configBuffer[$table];
        }

        return $label;
    }


    /**
     *
     * @param type $table
     * @return array
     */
    public function getLabels($table = null)
    {
        return $this->getLabel(null, $table);
    }


    /**
     * identify active hubs from settings
     *
     * @return dkgAbstract
     */
    private function _identifyActiveHubs()
    {
        foreach (appSettings::get() as $var => $val) {
            if (str_starts_with($var, 'feature_dkg_') === true && $val == '1') {
                $this->_hubs[substr($var, 12)] = null;
            }
        }

        return $this;
    }

    /**
     * checks if hub is active
     *
     * @param type $hubName
     * @return boolean
     */
    protected function _isActiveHub($hubNames)
    {
        $active = false;

        $hubNames = is_array($hubNames) === false ? array($hubNames) : $hubNames;

        foreach ($hubNames as $hubName) {
            if (array_key_exists($hubName, $this->_hubs) === true) {
                $active = true;
                break;
            }
        }

        return $active;
    }


    /**
     *
     * @return \dkgAbstract
     */
    private function _buildHubs()
    {
        foreach ($this->_hubs as $hubName => $dummy) {
            $hubFile = "feature/dkg/validations/hubs/{$hubName}.php";

            if (file_exists($hubFile)) {
                require_once $hubFile;

                $hubClass = 'dkg' . ucfirst($hubName) . 'Validations';

                $this->_hubs[$hubName] = new $hubClass();
            }
        }

        return $this;
    }


    /**
     * refresh loaded hubs
     *
     * @return dkgAbstract
     */
    public function refresh()
    {
        $this
            ->_identifyActiveHubs()
            ->_buildHubs()
        ;

        return $this;
    }


    public function getHubs($disease = false, $form = false)
    {
        $hubs = array();

        $disease = $disease !== false ? $disease : $this->getParam('disease');
        $form    = $form !== false    ? $form    : $this->getParam('form');

        foreach ($this->_getHubsForDisease($disease) as $hub) {
            if ($hub->validationForFormExists($form) === true) {
                $hubs[] = $hub;
            }
        }

        return $hubs;
    }

    public function getHubNames($all = false, $viewLabel = false)
    {
        $names = array();

        $disease = $all === true ? null : $this->getParam('disease');

        foreach ($this->getHubs($disease) as $hub) {
            $names[] = $viewLabel === true ? $this->_configBuffer['base']["hub_{$hub->getName()}"] : $hub->getName();
        }

        return $names;
    }

    private function _getHubsForDisease($disease)
    {
        $hubs = array();

        foreach ($this->_hubs as $hub) {
            if ($hub->checkForDisease($disease) === true) {
                $hubs[] = $hub;
            }
        }

        return $hubs;
    }

    public function getHubForms($merge = true)
    {
        $forms = array();

        if ($merge === true) {
            foreach ($this->_getHubsForDisease($this->getParam('disease')) as $hub) {
                $forms = array_merge($forms, $hub->getFormNames());
            }

            $forms = array_unique($forms);
        } else {
            foreach ($this->_getHubsForDisease($this->getParam('disease')) as $hub) {
                $forms[$hub->getname()] = $hub->getFormNames();
            }
        }

        return $forms;
    }


    public function getDlistNames()
    {
        $dlists = array();

        foreach ($this->getHubs(null, $this->getParam('form')) as $hub) {
            $hubDlists = $hub->getDlists($this->getParam('form'));

            foreach ($hubDlists as $list) {
                $dlists[] = $list->getTable();
            }
        }

        return array_unique($dlists);
    }


    public function getDlists()
    {
        $dlists = array();

        foreach ($this->getHubs() as $hub) {
            if (count($hubDlist = $hub->getDlists($this->getParam('form'))) > 0) {
                $dlists[$hub->getName()] = $hubDlist;
            }
        }

        return $dlists;
    }
}

?>
