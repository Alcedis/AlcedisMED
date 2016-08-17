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

class menuManager
{
    /**
     * _instance
     *
     * @access  protected
     * @var     menuManager
     */
    protected static $_instance;


    /**
     * _menuItems
     *
     * @access  protected
     * @var     array
     */
    protected $_menuItems = array();


    /**
     * _menuItemGroups
     *
     * @access  protected
     * @var     array
     */
    protected $_menuItemGroups = array();

    /**
     * _menuPages
     *
     * @access  protected
     * @var     array
     */
    protected $_menuPages = array();


    /**
     * _roleMappings
     *
     * @access  protected
     * @var     array
     */
    protected $_roleMappings = array();


    /**
     * _pageMappings
     *
     * @access  protected
     * @var     array
     */
    protected $_pageMappings = array();


    /**
     * _page
     *
     * @access  protected
     * @var     string
     */
    protected $_page;


    /**
     * _role
     *
     * @access  protected
     * @var     string
     */
    protected $_role;


    /**
     * _menuPath
     *
     * @access  protected
     * @var     string
     */
    protected $_menuPath = 'base/menu/';


    /**
     * _config
     *
     * @access  protected
     * @var     array
     */
    protected $_config = array();


    /**
     * _hideMenu
     *
     * @access  protected
     * @var     bool
     */
    protected $_hideMenu = false;


    /**
     * list
     *
     * @static
     * @access  public
     * @var     string
     */
    public static $list = 'list';


    /**
     * rec
     *
     * @static
     * @access  public
     * @var     string
     */
    public static $rec  = 'rec';


    /**
     * getInstance
     *
     * @static
     * @access  public
     * @return  menuManager
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }


    /**
     * registerMenuItem
     *
     * @static
     * @access  public
     * @param   string  $itemName
     * @param   string  $page
     * @param   string  $type
     * @return  menuManager
     */
    public static function registerMenuItem($itemName, $page = null, $type = 'list')
    {
        $m = self::getInstance();

        $page   = $page !== null ? $page : $itemName;
        $config = explode('|', $m->_config[$itemName]);

        $m->_menuItems[$itemName] = array(
            'href'  => concat(array($type, $page), '.'),
            'color' => $config[0],
            'lbl'   => $config[1],
            'hide'  => false
        );

        return $m;
    }


    /**
     * addMenuItemGroup
     *
     * @static
     * @access  public
     * @param   string  $groupName
     * @param   array   $itemNames
     * @return  menuManager
     */
    public static function addMenuItemGroup($groupName, array $itemNames)
    {
        $m = self::getInstance();

        $m->_menuItemGroups[$groupName] = array(
            'lbl' => 'Datengewinnung',
            'items' => $itemNames
        );

        return $m;
    }


    /**
     * addMenuItemGroup
     *
     * @static
     * @access  public
     * @param   string  $itemName
     * @param   string  $groupName
     * @return  menuManager
     */
    public static function addToMenuItemGroup($itemName, $groupName)
    {
        $m = self::getInstance();

        if (array_key_exists($groupName, $m->_menuItemGroups) === true) {
            // offset todo
            $m->_menuItemGroups[$groupName]['items'][] = $itemName;
        }

        return $m;
    }


    /**
     * mapPageItems
     *
     * @static
     * @access  public
     * @param   string $page
     * @param   array $items
     * @return  menuManager
     */
    public static function mapPageItems($page, array $items = array())
    {
        $m = self::getInstance();

        $m->_pageMappings[$page] = is_array($items) === false ? array($items) : $items;

        return $m;
    }


    /**
     * getMenuItems
     *
     * @static
     * @access  public
     * @return  array
     */
    public static function getMenuItems()
    {
        $m = self::getInstance();

        $items = array();

        if ($m->_hideMenu === true) {
            return $items;
        }

        //First check for defined page Menu Mappings
        if (array_key_exists($m->_page, $m->_pageMappings) === true) {

            foreach ($m->_pageMappings[$m->_page] as $itemName) {
                if (array_key_exists($itemName, $m->getMenuItemGroups()) === true) {
                    $groupMenuItem = $m->buildGroupMenuItem($itemName);

                    if ($groupMenuItem !== null) {
                        $items[] = $groupMenuItem;
                    }
                } else {
                    $menuItem = $m->buildMenuItem($itemName);

                    if ($menuItem !== null) {
                        $items[] = $menuItem;
                    }
                }
            }
        } elseif ($m->_role !== null) { //Check for role mapping
            $tmpItems = array();

            if (array_key_exists($m->_role, $m->_roleMappings) === true) {
                $tmpItems = $m->_roleMappings[$m->_role];
            } elseif (array_key_exists('default', $m->_roleMappings) === true)  {
                $tmpItems = $m->_roleMappings['default'];
            }

            foreach ($tmpItems as $itemName) {

                // check if menu item group exists
                if (array_key_exists($itemName, $m->getMenuItemGroups()) === true) {
                    $groupMenuItem = $m->buildGroupMenuItem($itemName);

                    if ($groupMenuItem !== null) {
                        $items[] = $groupMenuItem;
                    }
                } else {
                    $menuItem = $m->buildMenuItem($itemName);

                    if ($menuItem !== null) {
                        $items[] = $menuItem;
                    }
                }
            }

            //Check for default page mapping
        } elseif (array_key_exists($m->_page, $m->_menuPages) === true) {
            $items[] = array(
                'type'   => 'menu',
                'source' => "{$m->_menuPath}{$m->_page}.tpl",
            );
        }

        return $items;
    }


    /**
     * buildMenuItem
     *
     * @access  public
     * @param   string $itemName
     * @return  array
     */
    public function buildMenuItem($itemName)
    {
        $item = null;

        if ($this->_menuItems[$itemName]['hide'] !== true) {
            $item = array(
                'type'  => 'item',
                'href'  => $this->_menuItems[$itemName]['href'],
                'page'  => $this->_page,
                'lbl'   => $this->_menuItems[$itemName]['lbl'],
                'color' => $this->_menuItems[$itemName]['color']
            );
        }

        return $item;
    }


    /**
     * buildGroupMenuItem
     *
     * @access  public
     * @param $itemName
     * @return  array|null
     * @throws Exception
     */
    public function buildGroupMenuItem($itemName)
    {
        $group = $this->getMenuItemGroup($itemName);
        $item  = null;

        // if only one group item exists, render it like default item
        if (count($group['items']) === 1) {
            $item = $this->buildMenuItem(reset($group['items']));
        } else {
            $menuItems = array();

            foreach ($group['items'] as $groupItemName) {
                if ($this->_menuItems[$groupItemName]['hide'] !== true) {
                    $menuItems[] = $this->buildMenuItem($groupItemName);
                }
            }

            $item = array(
                'type'  => 'group',
                'lbl'   => $group['lbl'],
                'color' => $this->_menuItems[$itemName]['color'],
                'items' => $menuItems
            );
        }

        return $item;
    }


    /**
     * getMenuItemGroups
     *
     * @access  public
     * @return  array
     */
    public function getMenuItemGroups()
    {
        return $this->_menuItemGroups;
    }


    /**
     * getMenuItemGroup
     *
     * @access  public
     * @param   string  $groupName
     * @return  array
     * @throws  Exception
     */
    public function getMenuItemGroup($groupName)
    {
        if (array_key_exists($groupName, $this->_menuItemGroups) === false) {
            throw new Exception("item group with name '{$groupName}' doesn't exists");
        }

        return $this->_menuItemGroups[$groupName];
    }


    /**
     * registerMenuPage
     *
     * @static
     * @access  public
     * @param   string  $page
     * @return  menuManager
     */
    public static function registerMenuPage($page)
    {
        $m = self::getInstance();

        if (is_file("templates/{$m->_menuPath}{$page}.tpl") === true) {
            $m->_menuPages[$page] = true;
        }

        return $m;
    }


    /**
     * mapRoleItems
     *
     * @static
     * @access  public
     * @param   string  $role
     * @param   array   $items
     * @param   bool $extras
     * @return  menuManager
     */
    public static function mapRoleItems($role, array $items, $extras = true)
    {
        $m = self::getInstance();

        if ($extras === true) {
            $items = array_merge(
                $items,
                array('extras')
            );
        }

        $m->_roleMappings[$role] = $items;

        return $m;
    }


    /**
     * setPage
     *
     * @static
     * @access  public
     * @param   string  $page
     * @return  menuManager
     */
    public static function setPage($page)
    {
        $m = self::getInstance();

        $m->_page = $page;

        return $m;
    }


    /**
     * setRole
     *
     * @static
     * @access  public
     * @param   string  $role
     * @return  menuManager
     */
    public static function setRole($role)
    {
        $m = self::getInstance();

        $m->_role = $role;

        return $m;
    }


    /**
     * setConfig
     *
     * @static
     * @access  public
     * @param   Smarty $smarty
     * @return  menuManager
     */
    public static function setConfig(Smarty $smarty)
    {
        $m = self::getInstance();

        $backupConfig = $smarty->get_config_vars();
        $smarty->clear_config();

        $smarty->config_load('base/menu.conf');
        $m->_config  = $smarty->get_config_vars();

        $smarty->set_config($backupConfig);

        return $m;
    }


    /**
     * hideMenuItem
     *
     * @static
     * @access  public
     * @param   string  $itemName
     * @return  menuManager
     */
    public static function hideMenuItem($itemName)
    {
        $m = self::getInstance();

        if (array_key_exists($itemName, $m->_menuItems) === true) {
            $m->_menuItems[$itemName]['hide'] = true;
        }

        return $m;
    }


    /**
     * hideMenu
     *
     * @static
     * @access  public
     * @return  menuManager
     */
    public static function hideMenu()
    {
        $m = self::getInstance();

        $m->_hideMenu = true;

        return $m;
    }
}

?>
