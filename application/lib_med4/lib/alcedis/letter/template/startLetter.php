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

require_once(DIR_LIB . '/alcedis/letter/template/processLetter.php');

class FileNotFoundException extends Exception {}

class StartLetter
{
	/**
	 * The uniqueId for the tmp dir
	 *
	 * @access protected
	 * @var string
	 */
    protected $_uniqueId = "";


    /**
     * Array with the images to replace
     *
     * @access protected
     * @var array
     */
    protected $_imageArray = array();


    /**
     * Array with variables to replace
     *
     * @access protected
     * @var array
     */
    protected $_insertArray = array();


    /**
     * Sub Office
     * @var int
     */
    const SUB_OFFICE = 7;


    /**
     * DOM Object
     * @var object
     */
    protected $_dom;


    /**
     * constructor for start.php
     *
     * @access public
     * @param xml file $xml
     * @param string $uniqueId
     * @param string $tmpDir
     */
    public function __construct($xml, $uniqueId = '') {
        $this->_uniqueId      = $uniqueId;

        if (is_string($xml)) {
            $this->_dom = new DOMDocument;
            if (!@$this->_dom->load ($xml)) {
                //Exception
                echo "Unable to load the Document";
            }
        } else {
            //Exception
            echo "Unable to load the XML data into DOM";
        }
        return false;
    }


    /**
     * Saves the xml
     *
     * @access public
     * @param xml file $xml
     */
    public function save($xml)
    {
      $this->_dom->save ($xml);

      // NUR BEI DER CONTENT.XML NICHT BEI DER STYLE.XML
      if (substr($xml, -8) == "tent.xml" && 1==2) {
         $file = implode(" ",file($xml));

         //AUTOMATIC STYLE OHNE INHALT - NICHT GETESTET
         $regExp     = '~(<office:automatic-styles/>)~';
         $replace    = '<office:automatic-styles><style:style style:name="SU" style:family="paragraph" style:parent-style-name="Standard"><style:paragraph-properties fo:break-before="page"/></style:style></office:automatic-styles>';
         $file       = preg_replace($regExp, $replace, $file);

         //AUTOMATIC STYLE MIT INHALT - FUNKTIONIERT
         $regExp       = '~(<office:automatic-styles>.*)(</style:style></office:automatic-styles>)~';
         $replace      = '\1</style:style><style:style style:name="SU" style:family="paragraph" style:parent-style-name="Standard"><style:paragraph-properties fo:break-before="page"/>\2';
         $file       = preg_replace($regExp, $replace, $file);


         $test = '<table:table table:name="Tabelle1" table:style-name="Tabelle1"><table:table-column table:style-name="Tabelle1.A"/><table:table-column table:style-name="Tabelle1.B"/><table:table-column table:style-name="Tabelle1.C"/><table:table-row table:style-name="Tabelle1.1"><table:table-cell table:style-name="Tabelle1.A1" table:number-columns-spanned="3" office:value-type="string"><text:p text:style-name="P10"><text:bookmark-start text:name="__DdeLink__240_2083960716"/>Kennzahlenanhang</text:p><text:p text:style-name="P10"/><text:p text:style-name="P13">Tumorentität/Organ: Lymphome <text:line-break/>Bezugsjahr: 2011 <text:bookmark-end text:name="__DdeLink__240_2083960716"/></text:p><text:p text:style-name="P10"/></table:table-cell><table:covered-table-cell/><table:covered-table-cell/></table:table-row><table:table-row table:style-name="Tabelle1.2"><table:table-cell table:style-name="Tabelle1.A2" office:value-type="string"><text:p text:style-name="P11"/></table:table-cell><table:table-cell table:style-name="Tabelle1.B2" office:value-type="string"><text:p text:style-name="P12">Kennzahlen / Strukturdaten</text:p></table:table-cell><table:table-cell table:style-name="Tabelle1.C2" office:value-type="string"><text:p text:style-name="P12">Istwert</text:p></table:table-cell></table:table-row><table:table-row table:style-name="Tabelle1.2"><table:table-cell table:style-name="Tabelle1.A3" office:value-type="string"><text:p text:style-name="P7">EB_1</text:p></table:table-cell><table:table-cell table:style-name="Tabelle1.A3" office:value-type="string"><text:p text:style-name="P7">Anzahl Primärfälle</text:p><text:p text:style-name="P6"/></table:table-cell><table:table-cell table:style-name="Tabelle1.C3" office:value-type="string"><text:p text:style-name="P8"></text:p></table:table-cell></table:table-row><table:table-row table:style-name="Tabelle1.2"><table:table-cell table:style-name="Tabelle1.A3" office:value-type="string"><text:p text:style-name="P7">EB_1.2</text:p></table:table-cell><table:table-cell table:style-name="Tabelle1.A3" office:value-type="string"><text:p text:style-name="P7">Tumorkonferenz</text:p><text:list xml:id="list27525015" text:style-name="WW8Num52"><text:list-item><text:p text:style-name="P14">Funktionsfähig etabliert seit</text:p></text:list-item><text:list-item><text:p text:style-name="P14">Anteil vorgestellter Primärfälle</text:p></text:list-item><text:list-item><text:p text:style-name="P14">Anzahl vorgestellter sonstiger Patienten</text:p></text:list-item></text:list></table:table-cell><table:table-cell table:style-name="Tabelle1.C3" office:value-type="string"><text:p text:style-name="P7"/><text:p text:style-name="P7">-</text:p><text:p text:style-name="P7"></text:p><text:p text:style-name="P7"></text:p></table:table-cell></table:table-row><table:table-row table:style-name="Tabelle1.2"><table:table-cell table:style-name="Tabelle1.A3" office:value-type="string"><text:p text:style-name="P7">EB_1.4</text:p></table:table-cell><table:table-cell table:style-name="Tabelle1.A3" office:value-type="string"><text:p text:style-name="P7">Anteil psychoonkologische Versorgung</text:p><text:p text:style-name="P6"/></table:table-cell><table:table-cell table:style-name="Tabelle1.C5" office:value-type="string"><text:p text:style-name="P7"></text:p></table:table-cell></table:table-row><table:table-row table:style-name="Tabelle1.2"><table:table-cell table:style-name="Tabelle1.A3" office:value-type="string"><text:p text:style-name="P7">EB_1.5</text:p></table:table-cell><table:table-cell table:style-name="Tabelle1.A3" office:value-type="string"><text:p text:style-name="P7">Anteil Betreuung durch Sozialdienst</text:p><text:p text:style-name="P6"/></table:table-cell><table:table-cell table:style-name="Tabelle1.C5" office:value-type="string"><text:p text:style-name="P7"></text:p></table:table-cell></table:table-row><table:table-row table:style-name="Tabelle1.2"><table:table-cell table:style-name="Tabelle1.A3" office:value-type="string"><text:p text:style-name="P7">EB_1.7</text:p></table:table-cell><table:table-cell table:style-name="Tabelle1.A3" office:value-type="string"><text:p text:style-name="P7">Studienquote</text:p><text:p text:style-name="P6"/></table:table-cell><table:table-cell table:style-name="Tabelle1.C5" office:value-type="string"><text:p text:style-name="P7"></text:p></table:table-cell></table:table-row><table:table-row table:style-name="Tabelle1.2"><table:table-cell table:style-name="Tabelle1.A3" office:value-type="string"><text:p text:style-name="P7">EB_1.8</text:p></table:table-cell><table:table-cell table:style-name="Tabelle1.A3" office:value-type="string"><text:p text:style-name="P7">Name Onkologische Pflegefachkraft</text:p><text:p text:style-name="P6"/></table:table-cell><table:table-cell table:style-name="Tabelle1.C3" office:value-type="string"><text:p text:style-name="P7">-</text:p></table:table-cell></table:table-row><table:table-row table:style-name="Tabelle1.2"><table:table-cell table:style-name="Tabelle1.A3" office:value-type="string"><text:p text:style-name="P7">EB_2.2</text:p></table:table-cell><table:table-cell table:style-name="Tabelle1.A3" office:value-type="string"><text:p text:style-name="P7">Namentliche Nennung der verantwortlichen Ärzte auf Facharztebene aus dem koordinierenden Fachbereich</text:p></table:table-cell><table:table-cell table:style-name="Tabelle1.C3" office:value-type="string"><text:p text:style-name="P9">-</text:p></table:table-cell></table:table-row></table:table>';

         //SEITENUMBRUCH
         $regExp     = '~(</office:text></office:body>|</office:body></office:text text:use-soft-page-breaks="true">)~';
         $insert     = '<text:p text:style-name="SU"/><text:p text:style-name="Standard">test</text:p>';
         $replace    = $test . '\1';
         for ($i = 0; $i < 4; $i++) {
            $file       = preg_replace($regExp, $replace, $file);
         }

         //print_arr($file);

         file_put_contents($xml, $file);
      }
    }


    /**
     * Sets the insertArray
     *
     * @access public
     * @param array $newInsertArray
     * qreturn $this
     */
    public function setInsertArray ($newInsertArray)
    {
        $this->_insertArray = $newInsertArray;
        return $this;
    }


    /**
     * Gets the insertArray
     *
     * @access public
     * @return $this->_insertArray
     */
    public function getInsertArray ()
    {
        return $this->_insertArray;
    }


    /**
     * Gets the imagearray
     *
     * @access public
     * @return $this->_imageArray
     */
    public function getImageArray ()
    {
        return $this->_imageArray;
    }


    /**
     * Converts the letter and replace the variables and pictures
     *
     * @access public
     * @return $this
     */
    public function getResult() {
        $processLetter = new ProcessLetter($this->_uniqueId);
        $root_element  = substr($this->_dom->documentElement->nodeName, self::SUB_OFFICE);
        $processLetter->setFirstNode($this->getFirstNode($root_element))
                      ->setInsertArray ($this->_insertArray)
                      ->convertIt();
        $this->_imageArray = $processLetter->getImageArray();

        return $this;
    }


    /**
     * Gets the first node
     *
     * @access public
     * @param string $tagName
     * @return node $firstNode
     */
    public function getFirstNode ($tagName)
    {
        $firstNode = $this->_dom->getElementsByTagName ($tagName)->item (0)->childNodes;

        return $firstNode;
    }
}
?>
