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

/**
 * Class registerPatientCase
 */
class registerPatientCase
{
    /**
     * _data
     *
     * @access  protected
     * @var     array
     */
    protected $_data = array();


    /**
     * _valid
     *
     * @access  protected
     * @var     bool
     */
    protected $_valid = false;


    /**
     * _ident
     *
     * @access  protected
     * @var     string
     */
    protected $_ident;


    /**
     * _primaryCase
     *
     * @access  protected
     * @var     registerPatientCase
     */
    protected $_primaryCase = false;


    /**
     * _primaryCaseIdent
     *
     * @access  protected
     * @var     string
     */
    protected $_primaryCaseIdent;


    /**
     * create registerPatientCase
     *
     * @static
     * @access  public
     * @param   array $data
     * @return  registerPatientCase
     */
    public static function create(array $data)
    {
        $case = new self;

        $case->setData($data);

        return $case;
    }


    /**
     * get identification for this case
     *
     * @access  public
     * @return  string
     */
    public function getIdent()
    {
        $ident = $this->_ident;

        if ($ident === null) {
            $data  = $this->getData();
            $parts = array(
                $data['erkrankung_id'],
                $data['anlass']
            );

            $side = $data['diagnose_seite'];

            if (strlen($side) > 0) {
                $parts[] = $side;
            }

            $ident = $this->_ident = strtolower(implode('_', $parts));
        }

        return $ident;
    }


    /**
     * getPrimaryCaseIdent
     *
     * @access  public
     * @return  string
     */
    public function getPrimaryCaseIdent()
    {
        $ident = $this->_primaryCaseIdent;

        if ($ident === null) {
            $data  = $this->getData();
            $parts = array(
                $data['erkrankung_id'],
                'p'
            );

            $side = $data['diagnose_seite'];

            if (strlen($side) > 0) {
                $parts[] = $side;
            }

            $ident = $this->_primaryCaseIdent = strtolower(implode('_', $parts));
        }

        return $ident;
    }


    /**
     * get primary case for this case
     * if nothing is set, this case is already the primary
     *
     * @access  public
     * @return  registerPatientCase
     */
    public function getPrimaryCase()
    {
        $primaryCase = $this->_primaryCase;

        // if false, no primary for this case exists
        if ($primaryCase === false) {
            $primaryCase = null;
        } else if ($primaryCase === true) { // if true, this is primary case, so return it
            $primaryCase = $this;
        }

        return $primaryCase;
    }


    /**
     * hasPrimaryCase
     * (returns true if this case is primary or has primary relation and is valid
     *
     * @access  public
     * @param   bool $validOnly
     * @return  bool
     */
    public function hasPrimaryCase($validOnly = true)
    {
        $bool = $this->_primaryCase !== false;

        // if only valid primary cases are allowed and this case has a primary case but it's not valid
        if ($validOnly === true && $bool === true && $this->getPrimaryCase()->isValid() === false) {
            $bool = false;
        }

        return $bool;
    }


    /**
     * define this case as primary
     *
     * @access  public
     * @return  registerPatientCase
     */
    public function setAsPrimaryCase()
    {
        $this->_primaryCase = true;

        return $this;
    }


    /**
     * setPrimaryCase
     *
     * @access  public
     * @param   registerPatientCase $case
     * @return  registerPatientCase
     */
    public function setPrimaryCase(registerPatientCase $case)
    {
        $this->_primaryCase = $case;

        return $this;
    }


    /**
     * isPrimaryCase
     *
     * @access  public
     * @return  bool
     */
    public function isPrimaryCase()
    {
        return ($this->_primaryCase === true);
    }


    /**
     * getData
     *
     * @access  public
     * @param   string $field
     * @return  mixed
     */
    public function getData($field = null)
    {
        $data = null;

        if ($field !== null) {
            if (array_key_exists($field, $this->_data) === true) {
                $data = $this->_data[$field];
            }
        } else {
            $data = $this->_data;
        }

        return $data;
    }


    /**
     * setData
     *
     * @access  public
     * @param   array $data
     * @return  registerPatientCase
     */
    public function setData(array $data)
    {
        $this->_data = $data;

        return $this;
    }


    /**
     * setValid
     *
     * @access  public
     * @param   bool $valid
     * @return  registerPatientCase
     */
    public function setValid($valid = true)
    {
        $this->_valid = $valid;

        return $this;
    }


    /**
     * isValid
     *
     * @access  public
     * @return  bool
     */
    public function isValid()
    {
        return $this->_valid;
    }
}
