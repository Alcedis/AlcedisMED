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

require_once(DIR_LIB . '/zip/pclzip.lib.php' );

class reportPackage
{
    /**
     * filepath + name of zip
     * @var type
     */
    protected $_file = null;

    /**
     * target path
     * @var type
     */
    protected $_target = null;

    protected $_fields = array();

    protected $_smarty = null;


    /**
     *
     * @return reportPackage
     */
    public static function create() {
        return new self();
    }


    /**
     * extracts the zip file to given target path
     *
     * @return reportPackage
     */
    public function extract()
    {
        if ($this->_file !== null && $this->_target !== null) {
            $archive = new PclZip($this->_file);

            $extract = $archive->extract(PCLZIP_OPT_PATH, $this->_target);
        }

        return $this;
    }


    /**
     *
     * @param type $fields
     * @return reportPackage
     */
    public function loadPackage($smarty, $baseFields)
    {
        $this->_smarty = $smarty;

        $paths = array(
            'config' => "{$this->_target}/report.conf",
            'fields' => "{$this->_target}/fields.php",
        );

        // config exists
        if (is_file($paths['config']) === true) {
            $this->_smarty->config_load($paths['config']);
        }

        if (is_file($paths['fields']) === true) {
            require $paths['fields'];

            $fields = $this->_convertFields($fields);
        }

        if (isset($fields) === false) {
            $fields = array();
        }

        $this->_fields = array_merge($baseFields, $fields);

        return $this;
    }

    public function getConfig()
    {
        return $this->_smarty->get_config_vars();
    }

    /**
     *
     * @return array
     */
    public function getFields()
    {
        return $this->_fields;
    }


    private function _convertFields($fields)
    {
        $config = $this->getConfig();

        $filters = explode(',', $config['filter']);

        $packageFilter = array();

        foreach ($fields as $i => $field) {
            $type = $field['type'];

            switch ($type) {
                case 'lookup' :

                    if (reset(array_keys($fields[$i]['ext'])) !== 'l_basic') {
                        $fields[$i]['type'] = 'query';

                        $select = array();

                        foreach ($field['ext'] as $key => $value) {
                            $select[] = concat(array($key, "'{$value}'"));
                        }

                        $fields[$i]['ext'] = 'SELECT ' . implode(' UNION SELECT ', $select);
                    }

                    break;
            }

            if (in_array($i, $filters) === true) {
                $packageFilter[] = array(
                    'config' => isset($config["lbl_filter_{$i}"]) === true ? $config["lbl_filter_{$i}"] : '<span style="color:red">not defined in config</span>',
                    'field' => $i,
                    'add' => array_key_exists('add', $field) === true ? $field['add'] : null
                );
            }
        }

        if (count($packageFilter) > 0) {
            $this->_smarty->assign('packageFilter', $packageFilter);
        }

        return $fields;
    }


    /**
     *
     * @param type $path
     * @return reportPackage
     */
    public function setFile($filePath)
    {
        if (is_file($filePath) === true) {
            $this->_file = $filePath;
        }

        return $this;
    }


    public function deletePackage($path)
    {
        deltree($path);

        return $this;
    }

    /**
     *
     * @param type $path
     * @return reportPackage
     */
    public function setTarget($path, $kill = true)
    {
        $this->_target = $path;

        if ($kill === true) {
            deltree($path);
            mkdir($path, 0777, true);
        }

        return $this;
    }
}

?>
