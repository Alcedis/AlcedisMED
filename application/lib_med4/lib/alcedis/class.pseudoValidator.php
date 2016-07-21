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

class pseudoValidator
{
    /**
     * _smarty
     *
     * @access  protected
     * @var     Smarty
     */
    protected $_smarty;


    /**
     * _db
     *
     * @access  protected
     * @var     ressource
     */
    protected $_db;


    /**
     * _validator
     *
     * @access  protected
     * @var     validator
     */
    protected $_validator;


    /**
     * _validations
     *
     * @access  protected
     * @var     array
     */
    protected $_validations = array();


    /**
     * _config
     *
     * @access  protected
     * @var     array
     */
    protected $_config = array();


    /**
     * @param Smarty    $smarty
     * @param ressource $db
     */
    public function __construct($smarty, $db)
    {
        $this->_smarty = $smarty;

        $this->_config['base'] = $smarty->get_config_vars();
        $this->_db = $db;
    }


    /**
     * create
     *
     * @static
     * @access  public
     * @param   Smarty      $smarty
     * @param   ressource   $db
     * @return  pseudoValidator
     */
    public static function create($smarty, $db)
    {
        return new self($smarty, $db);
    }


    /**
     * _loadConfig
     *
     * @access  protected
     * @param   string  $form
     * @return  pseudoValidator
     */
    protected function _loadConfig($form)
    {
        $this->_smarty->clear_config();

        if (array_key_exists($form, $this->_config) === false) {
            $this->_smarty->config_load("app/{$form}.conf", 'rec');

            $this->_config[$form] = $this->_smarty->get_config_vars();
            $this->_smarty->clear_config();
        }

        $this->_smarty->set_config(
            array_merge(
                 $this->_config['base'],
                 $this->_config[$form]
            )
        );

        return $this;
    }


    /**
     * _loadFunction
     *
     * @access  protected
     * @param   string  $form
     * @param   string  $type
     * @return  void
     */
    protected function _loadFunction($form, $type)
    {
        $fileName = "scripts/app/rec.{$form}.php";
        $script   = file_get_contents($fileName);

        $this->_validations[$form][$type] = $this->_get_function($script, "ext_{$type}");
    }


    /**
     * validate
     *
     * @access  public
     * @param   string  $form
     * @param   array   $fields
     * @param   string  $type
     * @return  pseudoValidator
     */
    public function validate($form, $fields, $type = 'err')
    {
        $this->_loadConfig($form);

        $valid = new validator($this->_smarty, $this->_db, $fields);

        $valid->getType($type)->enable_js = false;

        if (isset($this->_validations[$form][$type]) === false) {
            $this->_loadFunction($form, $type);
        }

        $validFunction = $this->_validations[$form][$type];

        if ($validFunction !== null) {
            $new   = '__' . md5(microtime());
            $validFunction = str_replace("function ext_{$type}", "function {$new}", $validFunction);

            eval ($validFunction);
            $new($valid);
        }

        $this->_validator = $valid;

        return $this;
    }


    /**
     * getFields
     *
     * @access  public
     * @param   string  $type
     * @return  array
     */
    public function getFields($type)
    {
        $return = null;

        if ($this->_validator !== null) {
            $return = $this->_validator->getValidationFields($type);
        }

        return $return;
    }


    /**
     * getValidator
     *
     * @access  public
     * @return  validator
     */
    public function getValidator()
    {
        return $this->_validator;
    }


    /**
     * _get_function
     *
     * @access  protected
     * @param   string  $script
     * @param   string  $function_name
     * @return  string
     */
    protected function _get_function($script, $function_name)
    {
        $cnt_signs  = strlen($script);
        $start      = strpos($script, "function $function_name");

        if ($start !== false) {
            $stop  = strpos($script, '{', $start) + 1;
            $level = 1;

            while ($level != 0 AND ++$stop < $cnt_signs) {
                switch ($script{$stop}) {
                   case '{': ++$level; break;
                   case '}': --$level; break;
                }
            }

            $return = substr($script, $start, $stop - $start + 1);
        } else {
            $return = null;
        }

        return $return;
    }
}

?>
