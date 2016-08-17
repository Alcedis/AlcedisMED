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


class Cgkr_6_2_ModelHelper
{

    /**
     *
     *
     * @static
     * @access
     * @param $record
     * @return mixed
     */
    public static function GEDA($record)
    {
        return $record['geburtsdatum'];
    }


    /**
     *
     *
     * @static
     * @access
     * @param $record
     * @return mixed
     */
    public static function SEXG($record)
    {
        return $record['geschlecht'];
    }


    /**
     *
     *
     * @static
     * @access
     * @param $record
     * @return mixed
     */
    public static function TITEL($record)
    {
        return $record['titel'];
    }


    /**
     * MEHRL
     *
     * @access  public
     * @param   array $records
     * @return  int
     */
    public static function MEHRL($records)
    {
        return self::getFirstFilled('mehrlingseigenschaften', $records);
    }


    /**
     *
     *
     * @access  public
     * @param   $records
     * @return  void
     */
    public static function BF1N($records)
    {
        return self::getFirstFilled('beruf_laengster', $records);
    }


    /**
     *
     *
     * @static
     * @access
     * @param $record
     * @return mixed
     */
    public static function ORTG($record)
    {
        return $record['ort'];
    }


    /**
     *
     *
     * @static
     * @access
     * @param $record
     * @return mixed
     */
    public static function PLZN($record)
    {
        return $record['plz'];
    }


    /**
     *
     *
     * @static
     * @access
     * @param $record
     * @return mixed
     */
    public static function SAN($record)
    {
        return (strlen($record['staat']) > 0) ? $record['staat'] : 'x';
    }


    /**
     *
     *
     * @static
     * @access
     * @param $record
     * @return mixed
     */
    public static function STR($record)
    {
        return concat(array($record['strasse'], $record['hausnr']), ' ');
    }


    /**
     *
     *
     * @access  public
     * @param   $records
     * @return  void
     */
    public static function JALAEN($records)
    {
        return self::getFirstFilled('beruf_laengster_dauer', $records);
    }


    /**
     *
     *
     * @access  public
     * @param   $records
     * @return  void
     */
    public static function BF2($records)
    {
        return self::getFirstFilled('beruf_letzter', $records);
    }


    /**
     *
     *
     * @access  public
     * @param   $records
     * @return  void
     */
    public static function JALET($records)
    {
        return self::getFirstFilled('beruf_letzter_dauer', $records);
    }


    /**
     *
     *
     * @access  public
     * @param   $records
     * @return  string
     */
    public static function KINL($records)
    {
        $val = self::getFirstFilled('geburten_lebend', $records);

        return ($val > 9 ? 9 : $val);
    }


    /**
     * KINT
     *
     * @access  public
     * @param   array   $records
     * @return  string
     */
    public static function KINT($records)
    {
        $val = self::getFirstFilled('geburten_tot', $records);

        return ($val > 9 ? 9 : $val);
    }


    /**
     * KINF
     *
     * @access  public
     * @param   array   $records
     * @return  string
     */
    public static function KINF($records)
    {
        $val = self::getFirstFilled('geburten_fehl', $records);

        return ($val > 9 ? 9 : $val);
    }


    /**
     *
     *
     * @static
     * @access
     * @param $records
     * @return mixed
     */
    public static function ANLDIAG($records)
    {
        $map = array(
            'gf' => 'u',
            'ns' => 'a',
            'nv' => 'u',
            'sc' => 'c',
            'su' => 's',
            'ze' => 'a',
            'ts' => 't'
        );

        return self::map(self::getFirstFilled('entdeckung', $records, 'x'), $map);
    }


    /**
     *
     *
     * @static
     * @access  public
     * @param   $records
     * @param   $map
     * @return  string
     */
    public static function VORT($records, $map)
    {
        $val = array();

        foreach ($records as $record) {
            $year = $record['jahr'];
            $text = $record['erkrankung_text'];
            $therapie1 = self::map($record['therapie1'], $map);
            $therapie2 = self::map($record['therapie2'], $map);
            $therapie3 = self::map($record['therapie3'], $map);
            $id = "{$year}{$text}{$therapie1}{$therapie2}{$therapie3}";
            $val[$id] = concat(array($year, $text, $therapie1, $therapie2, $therapie3), ', ');
        }

        return implode(', ', $val);
    }


    /**
     * DIDA
     *
     * @static
     * @access
     * @param $record
     * @param $investigations
     * @return bool|null|string
     */
    public static function DIDA($record, $investigations)
    {
        $val = null;

        if (count($investigations) > 0) {
            $op = self::didaMapInvestigation($record['dida_eingriff']);
            $in = self::didaMapInvestigation($record['dida_untersuchung']);

            foreach ($investigations as $investigation) {
                $type = $investigation['type'];

                if ($type == 'zyto') {
                    if (array_key_exists($investigation['eingriff_id'], $op) === true) {
                        $val = $op[$investigation['eingriff_id']];
                        break;
                    }
                } else {
                    if (strlen($investigation['eingriff_id']) > 0 && array_key_exists($investigation['eingriff_id'], $op) === true) {
                        $val = $op[$investigation['eingriff_id']];
                        break;
                    } else if (array_key_exists($investigation['untersuchung_id'], $in) === true) {
                        $val = $in[$investigation['untersuchung_id']];
                        break;
                    }
                }
            }
        }

        if ($val === null) {
            $val = $record['min_tumorstate'];
        }

        return $val;
    }


    /**
     * map investigatiosn
     *
     * id|date,id|date,id|date
     *
     * @static
     * @access
     * @param $field
     * @return void
     */
    public static function didaMapInvestigation($field)
    {
        $map = array();

        if (strlen($field) > 0) {
            $records = explode(',', $field);

            foreach ($records as $record) {
                $parts = explode('|', $record);

                $map[$parts[0]] = $parts[1];
            }
        }

        return $map;
    }


    /**
     * DTEXT
     *
     * @static
     * @access  public
     * @param   $record
     * @return  mixed
     */
    public static function DTEXT($record)
    {
        return $record['diagnose_text'];
    }


    /**
     * ICDZ
     *
     * @static
     * @access  public
     * @param   $record
     * @return  mixed
     */
    public static function ICDZ($record)
    {
        return str_replace('.', null, $record['diagnose']);
    }


    /**
     * LOKT
     *
     * @static
     * @access  public
     * @param   $record
     * @param   $map
     * @return  void
     */
    public static function LOKT($record, $map)
    {
        $val = null;

        if (strlen($record['lokalisation']) > 0) {
            $val = $record['lokalisation_text'];
        } else {
            $localisation = self::map($record['diagnose'], $map);

            if (true === is_array($localisation)) {
                $val = $localisation['text'];
            }
        }

        return $val;
    }


    /**
     * ICT
     *
     * @static
     * @access  public
     * @param   array   $record
     * @param   array   $map
     * @return  string
     */
    public static function ICT($record, $map)
    {
        $val = null;

        if (strlen($record['lokalisation']) > 0) {
            $val = $record['lokalisation'];
        } else {
            $localisation = self::map($record['diagnose'], $map);

            if (true === is_array($localisation)) {
                $val = $localisation['code'];
            }
        }

        return str_replace(array('.', 'C'), array(null, null), $val);
    }


    /**
     * LATN
     *
     * @static
     * @access  public
     * @param   array   $record
     * @return  string
     */
    public static function LATN($record)
    {
        $val = $record['diagnose_seite'];

        if ((strlen($val) == 0) ||
            (('-' == $val) && (strlen($record['lokalisation_seite']) > 0))) {
            $val = $record['lokalisation_seite'];
        }

        return ($val !== '-' ? strtolower($val) : null);
    }


    /**
     * HFK
     *
     * @static
     * @access  public
     * @param   $record
     * @return  string
     */
    public static function HFK($record)
    {
        return (self::checkICM($record) === true ?  $record['morphologie_text'] : null);
    }


    /**
     * ICM
     *
     * @static
     * @access  public
     * @param   $record
     * @return  string
     */
    public static function ICM($record)
    {
        return (self::checkICM($record) === true ? str_replace('/', null, $record['morphologie']) : null);
    }


    /**
     * check if morphologie ends with relevant endings
     *
     * @static
     * @access
     * @param $record
     * @return bool
     */
    public static function checkICM($record)
    {
        $checkEnding = array('1', '2', '3', '6', '9');

        if (str_starts_with($record['diagnose'], array('D32', 'D33', 'D35.2', 'D35.4')) === true) {
            $checkEnding[] = '0';
        }

        return str_ends_with($record['morphologie'], $checkEnding);
    }


    /**
     * GRADING
     *
     * @static
     * @access  public
     * @param   $record
     * @return  mixed
     */
    public static function GRADING($record)
    {
        $map = array(
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            'H' => 'h',
            'L' => 'l',
            'M' => 'i',
            'B' => 'x',
            'X' => 'x'
        );

        return self::map($record['g'], $map, 'x');
    }


    /**
     * TAUSB
     *
     * where order is
     * tumorausbreitung_lokal
     * tumorausbreitung_konausdehnung
     * tumorausbreitung_lk
     * tumorausbreitung_fernmetastasen
     *
     * @static
     * @access  public
     * @param   $record
     * @return  mixed
     */
    public static function TAUSB($record)
    {
        $val   = null;
        $where = $record['tumorausbreitung'];

        if (strlen($where) > 0) {
            $map   = array('l', 'k', 'r', 'f');
            $where = explode('|', $where);

            foreach ($where as $i => $section) {
                if ($section == 1) {
                    $val = $map[$i];
                    break;
                }
            }
        }

        if ($val === null) {
            $m = $record['m'];
            $n = $record['n'];
            $t = $record['t'];

            switch (true) {
                case (strlen($m) > 0 && str_contains($m, array('0', 'X')) === false):
                    $val = 'f';
                    break;

                case (strlen($n) > 0 && str_contains($n, array('0', 'X')) === false):
                    $val = 'r';
                    break;

                case (str_contains($t, array('3', '4')) === true && str_contains($n, '0') === true && str_contains($m, '0') === true):
                    $val = 'k';
                    break;

                default:
                    $val = 'l';
                    break;
            }
        }

        return $val;
    }


    /**
     * STA_VER1
     *
     * @static
     * @access  public
     * @param   $record
     * @return  mixed
     */
    public static function STA_VER1($record)
    {
        $field = self::checkSTA($record);

        $map = array(
            'ann_arbor_stadium' => 'a',
            'rai'               => 'r',
            'binet'             => 'b',
            'fab'               => 'f',
            'durie_salmon'      => 'm',
            'gleason1'          => 'g',
            'clark'             => 'l',
            'tumordicke'        => 'w'
        );

        return self::map($field, $map);
    }


    /**
     *
     *
     * @static
     * @access
     * @param      $record
     * @param bool $skipFirst
     * @return null
     */
    public static function STADIUM1($record, $skipFirst = false)
    {
        $val = null;

        $field = self::checkSTA($record, $skipFirst);

        if ($field !== null) {
            switch ($field) {
                case 'ann_arbor_stadium':
                    $fieldVal = $record[$field];

                    $stadium = substr($fieldVal, 0, strpos($fieldVal, '/'));

                    $val = concat(array(
                        $stadium,
                        $record['ann_arbor_aktivitaetsgrad'],
                        $record['ann_arbor_extralymphatisch']
                    ), '');

                    break;

                case 'clark':
                    $values = explode(',', $record[$field]);

                    rsort($values);

                    $val = reset($values);

                    break;

                case 'gleason1':
                    $g1     = $record[$field];
                    $g2     = $record['gleason2'];

                    $val = "{$g1}+{$g2}";

                    break;

                default:
                    $val = $record[$field];
                    break;
            }
        }

        return $val;
    }


    /**
     *
     *
     * @static
     * @access
     * @param $record
     * @return null
     */
    public static function STADIUM2($record)
    {
        return self::STADIUM1($record, true);
    }


    /**
     * STA_VER2
     *
     * @static
     * @access  public
     * @param   $record
     * @return  mixed
     */
    public static function STA_VER2($record)
    {
        $field = self::checkSTA($record, true);

        $map = array(
            'ann_arbor_stadium' => 'a',
            'rai'               => 'r',
            'binet'             => 'b',
            'fab'               => 'f',
            'durie_salmon'      => 'm',
            'gleason1'          => 'g',
            'clark'             => 'l',
            'tumordicke'        => 'w'
        );

        return self::map($field, $map);
    }


    /**
     * find STA field
     *
     * @static
     * @access  public
     * @param        $record
     * @param   bool $skipFirst
     * @return  null
     */
    public static function checkSTA($record, $skipFirst = false)
    {
        $select = null;
        $fields = array('ann_arbor_stadium', 'rai', 'binet', 'fab', 'durie_salmon', 'gleason1', 'clark', 'tumordicke');

        foreach ($fields as $field) {
            if (strlen($record[$field]) > 0) {
                if ($skipFirst === true) {
                    $skipFirst = false;
                    continue;
                }

                $select = $field;
                break;
            }
        }

        return $select;
    }


    /**
     * TNMprae
     *
     * @static
     * @access          public
     * @param           $record
     * @param           $map
     * @param  string   $praefix
     * @return string
     */
    public static function TNMprae($record, $map, $datumDida, $n = 'cn', $praefix = 'ct')
    {
        $tnm = concat(self::filter(array(
             $record["{$praefix}_praefix"],
             $record[$praefix],
             $record[$n],
             $record["{$praefix}_cm"],
             self::map($record["{$praefix}_l"], $map['l']),
             self::map($record["{$praefix}_v"], $map['v']),
             self::map($record["{$praefix}_ppn"], $map['ppn'])
        )), ' ');

        $uicc = self::prepend($record["{$praefix}_uicc"], 'UICC:');

        $result = (strlen($tnm) > 0) ? $tnm : '';
        if (strlen($uicc) > 0) {
            if (strlen($result) > 0) {
                $result .= ', ';
            }
            $result .= $uicc;
        }
        $result .= (strlen($result) > 0) ? ', ' . self::prepend((($datumDida >= '2010-01-01') ? 7 : 6), 'Aufl.:') : '';

        return $result;
    }


    /**
     * TNMpost
     *
     * @static
     * @access
     * @param $record
     * @param $map
     * @return string
     */
    public static function TNMpost($record, $map, $datumDida)
    {
        return self::TNMprae($record, $map, $datumDida, 'pn', 'pt');
    }


    /**
     * HDSICH
     *
     * @static
     * @access
     * @param $record
     * @return null|string
     */
    public static function HDSICH($record)
    {
        $diagnosesicherung = $record['diagnosesicherung'];

        $val = strlen($diagnosesicherung) > 0 ? $diagnosesicherung : null;

        if ($val === null) {
            $morph = $record['morphologie'];
            $loc   = $record['lokalisation'];

            if (strlen($morph) > 0) {
                if ($loc != 'C80.9' && str_ends_with($morph, array('/0', '/1', '/2', '/3')) === true) {
                    $val = 'h';
                } else if ($loc == 'C80.9' && str_ends_with($morph, array('/3')) === true) {
                    $val = 'm';
                } else if (str_ends_with($morph, array('/6', '/9')) === true) {
                    $val = 'm';
                } else if ($record['autopsie'] == 1 && $record['todesdatum'] == $record['datum_sicherung']) {
                    $val = 'a';
                }
            } else if ($record['zyto_count'] != '0') {
                $val = 'z';
            }
        }

        return ($val === null ? 'x' : $val);
    }


    /**
     * BEF
     *
     * @static
     * @access  public
     * @param   $record
     * @return  mixed
     */
    public static function BEF($record)
    {
        $map = array(
            'tot'  => 'v',
            'abge' => 'x',
            'lost' => 'x'
        );

        return self::map($record['abschlussgrund'], $map, 'l');
    }


    /**
     * STDA
     *
     * @static
     * @access
     * @param $record
     * @return mixed
     */
    public static function STDA($record)
    {
        return $record['todesdatum'];
    }


    /**
     * SEK
     *
     * @static
     * @access  public
     * @param   $record
     * @return  mixed
     */
    public static function SEK($record)
    {
        $map = array(
            '0' => 'n',
            '1' => 'j'
        );

        return self::map($record['autopsie'], $map);
    }


    /**
     * T1A
     *
     * @static
     * @access  public
     * @param   $record
     * @return  mixed
     */
    public static function T1A($record)
    {
        return str_replace('.', null, $record['tod_ursache']);
    }


    /**
     * X1A
     *
     * @static
     * @access  public
     * @param   $record
     * @return  mixed
     */
    public static function X1A($record)
    {
        return $record['tod_ursache_text'];
    }


    /**
     * X1A
     *
     * @static
     * @access  public
     * @param   $record
     * @return  mixed
     */
    public static function Z1A($record)
    {
        return self::append($record['tod_ursache_dauer'], 'Monate');
    }


    /**
     * T1B
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function T1B($records)
    {
        $val = null;

        if (array_key_exists(0, $records) === true) {
            $val = str_replace('.', null, $records[0]['krankheit']);
        }

        return $val;
    }


    /**
     * X1B
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function X1B($records)
    {
        $val = null;

        if (array_key_exists(0, $records) === true) {
            $val = $records[0]['krankheit_text'];
        }

        return $val;
    }


    /**
     * Z1B
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function Z1B($records)
    {
        $val = null;

        if (array_key_exists(0, $records) === true) {
            $val = self::append($records[0]['krankheit_dauer'], 'Monate');
        }

        return $val;
    }


    /**
     * T1C
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function T1C($records)
    {
        $val = null;

        if (array_key_exists(1, $records) === true) {
            $val = str_replace('.', null, $records[1]['krankheit']);
        }

        return $val;
    }


    /**
     * X1C
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function X1C($records)
    {
        $val = null;

        if (array_key_exists(1, $records) === true) {
            $val = $records[1]['krankheit_text'];
        }

        return $val;
    }


    /**
     * Z1C
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function Z1C($records)
    {
        $val = null;

        if (array_key_exists(1, $records) === true) {
            $val = self::append($records[1]['krankheit_dauer'], 'Monate');
        }

        return $val;
    }


    /**
     * T2A
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function T2A($records)
    {
        $val = null;

        if (array_key_exists(2, $records) === true) {
            $val = str_replace('.', null, $records[2]['krankheit']);
        }

        return $val;
    }


    /**
     * X2A
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function X2A($records)
    {
        $val = null;

        if (array_key_exists(2, $records) === true) {
            $val = $records[2]['krankheit_text'];
        }

        return $val;
    }


    /**
     * Z2A
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function Z2A($records)
    {
        $val = null;

        if (array_key_exists(2, $records) === true) {
            $val = self::append($records[2]['krankheit_dauer'], 'Monate');
        }

        return $val;
    }


    /**
     * T2B
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function T2B($records)
    {
        $val = null;



        if (array_key_exists(3, $records) === true) {
            $val = str_replace('.', null, $records[3]['krankheit']);
        }

        return $val;
    }


    /**
     * X2B
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function X2B($records)
    {
        $val = null;

        if (array_key_exists(3, $records) === true) {
            $val = $records[3]['krankheit_text'];
        }

        return $val;
    }


    /**
     * Z2B
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function Z2B($records)
    {
        $val = null;

        if (array_key_exists(3, $records) === true) {
            $val = self::append($records[3]['krankheit_dauer'], 'Monate');
        }

        return $val;
    }


    /**
     * CHAT
     *
     * @static
     * @access  public
     * @param   $record
     * @return  mixed
     */
    public static function CHAT($record)
    {
        $val = self::getFirstFilled('intention', $record, 'x');

        $map = array(
            'kur' => 'k',
            'pal' => 'p'
        );

        return self::map($val, $map);
    }


    /**
     * OPE
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function OPE($records)
    {
        $val = self::getFirstFilled('eingriff_id', $records, 'n');

        return ($val !== 'n' ? 'j' : $val);
    }


    /**
     * DMOPE
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function DMOPE($records)
    {
        $val = self::getFirstFilled('beginn', $records);

        return ($val !== null ? self::convertDate($val, 'dmy') : null);
    }


    /**
     * STT
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function STT($records)
    {
        $return = 'n';

        foreach ($records as $record) {
            if (self::checkSTT($record) === true) {
                $return = 'j';

                break;
            }
        }

        return $return;
    }


    /**
     * DMSTT
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function DMSTT($records)
    {
        $return = null;

        foreach ($records as $record) {
            if (self::checkSTT($record) === true) {
                $return = self::convertDate($record['beginn'], 'dmy');

                break;
            }
        }

        return $return;
    }


    /**
     *
     *
     * @static
     * @access
     * @param $record
     * @return bool
     */
    public static function checkSTT($record)
    {
        return str_contains($record['vorlage_therapie_art'], array('st', 'cst', 'ist', 'ahst'));
    }


    /**
     * CHE
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function CHE($records)
    {
        $return = 'n';

        foreach ($records as $record) {
            if (self::checkCHE($record) === true) {
                $return = 'j';

                break;
            }
        }

        return $return;
    }


    /**
     * DMCHE
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function DMCHE($records)
    {
        $return = null;

        foreach ($records as $record) {
            if (self::checkCHE($record) === true) {
                $return = self::convertDate($record['beginn'], 'dmy');

                break;
            }
        }

        return $return;
    }


    /**
     *
     *
     * @static
     * @access
     * @param $record
     * @return bool
     */
    public static function checkCHE($record)
    {
        return str_contains($record['vorlage_therapie_art'], array('ci', 'cst', 'c'));
    }


    /**
     * HOR
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function HOR($records)
    {
        $return = 'n';

        foreach ($records as $record) {
            if (self::checkHOR($record) === true) {
                $return = 'j';

                break;
            }
        }

        return $return;
    }


    /**
     * DMHOR
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function DMHOR($records)
    {
        $return = null;

        foreach ($records as $record) {
            if (self::checkHOR($record) === true) {
                $return = self::convertDate($record['beginn'], 'dmy');

                break;
            }
        }

        return $return;
    }


    /**
     *
     *
     * @static
     * @access
     * @param $record
     * @return bool
     */
    public static function checkHOR($record)
    {
        return str_contains($record['vorlage_therapie_art'], array('ah', 'ahst'));
    }


    /**
     * IMM
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function IMM($records)
    {
        $return = 'n';

        foreach ($records as $record) {
            if (self::checkIMM($record) === true) {
                $return = 'j';

                break;
            }
        }

        return $return;
    }


    /**
     * DMIMM
     *
     * @static
     * @access  public
     * @param   $records
     * @return  mixed
     */
    public static function DMIMM($records)
    {
        $return = null;

        foreach ($records as $record) {
            if (self::checkIMM($record) === true) {
                $return = self::convertDate($record['beginn'], 'dmy');

                break;
            }
        }

        return $return;
    }


    /**
     *
     *
     * @static
     * @access
     * @param $record
     * @return bool
     */
    public static function checkIMM($record)
    {
        return str_contains($record['vorlage_therapie_art'], array('ci', 'ist', 'i'));
    }


    /**
     * AND
     *
     * @static
     * @access  public
     * @param   $record
     * @return  mixed
     */
    public static function AND_ ($record)
    {
        $exist = count($record['sonstige_therapien']) > 0;

        if ($exist === false) {
            foreach ($record['systemische_therapien'] as $therapy) {
                if (str_contains($therapy['vorlage_therapie_art'], array('son', 'sonstr')) === true) {
                    $exist = true;

                    break;
                }
            }
        }

        return ($exist === true ? 'a' : null);
    }


    /**
     * QTU
     *
     * @static
     * @access  public
     * @param   array   $record
     * @return  string
     */
    public static function QTU($record)
    {
        $map = array(
            'o' => 'o',
            's' => 'x',
            't' => 't'
        );
        return self::map($record['ursache_quelle'], $map);
    }


    /**
     * TURS
     *
     * @static
     * @access  public
     * @param   $record
     * @return  mixed
     */
    public static function TURS($record)
    {
        $val = null;

        if ($record['abschlussgrund'] == 'tot') {
            $map = array(
                'tott'  => 'j',
                'totn'  => 'j',
                'totnt' => 'n',
                'totnb' => 'x'
            );

            $val = self::map($record['tumorassoziation'], $map);
        }

        return $val;
    }


    /**
     * UTR
     *
     * @static
     * @access  public
     * @param   $record
     * @return  mixed
     */
    public static function UTR($record)
    {
        return $record['kr_meldung']['meldebegruendung'];
    }


    /**
     * EINRICHT
     *
     * @static
     * @access  public
     * @param   $record
     * @return  null|string
     */
    public static function EINRICHT($record)
    {
        return ($record !== null ? $record['name'] : null);
    }


    /**
     * ABT
     *
     * @static
     * @access  public
     * @param   $record
     * @return  null|string
     */
    public static function ABT($record)
    {
        return ($record !== null ? $record['fachabteilung_description'] : null);
    }


    /**
     * PLZ_E
     *
     * @static
     * @access  public
     * @param   $record
     * @return  null|string
     */
    public static function PLZ_E($record)
    {
        return ($record !== null ? $record['plz'] : null);
    }


    /**
     * ORT_E
     *
     * @static
     * @access  public
     * @param   $record
     * @return  null|string
     */
    public static function ORT_E($record)
    {
        return ($record !== null ? $record['ort'] : null);
    }


    /**
     * STR_E
     *
     * @static
     * @access  public
     * @param   $record
     * @return  null|string
     */
    public static function STR_E($record)
    {
        return ($record !== null ? $record['strasse'] : null);
    }


    /**
     * NAMN
     *
     * @static
     * @access  public
     * @param   $record
     * @return  null|string
     */
    public static function NAMN($record)
    {
        return ($record !== null ? $record['fullname'] : null);
    }


    /**
     * TELN
     *
     * @static
     * @access  public
     * @param   $record
     * @return  null|string
     */
    public static function TELN($record)
    {
        return ($record !== null ? $record['telefon'] : null);
    }


    /**
     * DMN
     *
     * @static
     * @access  public
     * @param   $record
     * @return  null|string
     */
    public static function DMN($record)
    {
        return $record['kr_meldung']['datum'];
    }


    /**
     * REFNR
     *
     * @static
     * @access  public
     * @param   $record
     * @return  string
     */
    public static function REFNR($record)
    {
        return $record['patient_nr'];
    }


    /**
     *
     *
     * @static
     * @access
     * @param      $value
     * @param      $map
     * @param null $default
     * @return mixed
     */
    public static function map($value, $map, $default = null)
    {
        return (array_key_exists($value, $map) === true ? $map[$value] : ($default !== null ? $default : $value));
    }


    /**
     *
     *
     * @static
     * @access
     * @param      $field
     * @param      $records
     * @param null $default
     * @return null
     */
    public static function getFirstFilled($field, $records, $default = null)
    {
        $return = $default;

        foreach ($records as $record) {
            $val = $record[$field];

            if (strlen($val) > 0) {
                $return = $val;

                break;
            }
        }

        return $return;

    }


    /**
     *
     *
     * @static
     * @access
     * @param $val
     * @return null
     */
    public static function filter($val)
    {
        return array_filter($val);
    }


    /**
     *
     *
     * @static
     * @access
     * @param        $val
     * @param        $appending
     * @param string $separator
     * @return null|string
     */
    public static function append($val, $appending, $separator = ' ')
    {
        return (strlen($val) > 0 ? concat(array($val, $appending), $separator) : null);
    }


    /**
     *
     *
     * @static
     * @access
     * @param        $val
     * @param        $prepending
     * @param string $separator
     * @return null|string
     */
    public static function prepend($val, $prepending, $separator = ' ')
    {
        return (strlen($val) > 0 ? concat(array($prepending, $val), $separator) : null);
    }


    /**
     * convert iso date
     *
     * @static
     * @access
     * @param        $isoDate
     * @param string $format
     * @return bool|null|string
     */
    public static function convertDate($isoDate, $format = "dmY")
    {
        return (strlen($isoDate) > 0 ? date($format, strtotime($isoDate)) : null);
    }

}

?>
