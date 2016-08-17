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

require_once 'feature/dkg/class/conditions.php';

class dkgManager extends dkgConditions
{
    private static $instance = null;

    protected $_services = array(
       'widget' => array(
           'getFields' => '_convertFields',
           'assign' => 'assign'
       )
    );

    /**
     *
     * @param type $smarty
     * @return dkgManager
     */
    static public function getInstance($smarty = null)
    {
        if (self::$instance === null) {
            self::$instance = new self($smarty);
        }

        return self::$instance->resetParams();
    }

    /**
     * assign hub list to template
     *
     */
    public function assign()
    {
        if ($this->getParam('pageType') === 'rec') {
            $hubs = $this->getHubs($this->getParam('disease'), $this->getParam('form'));

            if (count($hubs) > 0) {
                $assign = array('' => null);

                foreach ($hubs as $hub) {
                    $assign[$hub->getName()] = $this->getLabel("hub_{$hub->getName()}");
                }

                $this->_smarty->assign('interface', $assign);
            }
        }

        return $this;
    }


    /**
     *
     * @param widget $widget
     */
    protected function _convertFields($widget)
    {
        $fields = $widget->getAllFields();

        $hubs = $this->getHubs($this->getParam('disease'), $this->getParam('form'));

        foreach ($hubs as $hub) {
            $fields = $hub->applyToFields($fields, $this->getParam('form'), $this->getParam('disease'));
        }

        $widget->setAllFields($fields);

        return $this;
    }
}

?>
