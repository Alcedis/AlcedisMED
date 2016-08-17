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

abstract class stageTemplates
{
    /**
     * _templates
     *
     * @access  protected
     * @var     array
     */
    protected $_templates = array(
        'kh' => "
             CASE
                ## Lippen und Mundhöhle ##
                WHEN [localisation] LIKE 'C00%' OR [localisation] LIKE 'C02%' OR [localisation] LIKE 'C03%' OR [localisation] LIKE 'C04%' OR [localisation] LIKE 'C05%' OR [localisation] LIKE 'C06%' THEN
                    CASE
                        WHEN [t] = 'is'          AND [n] = '0'           AND [m] = '0' THEN '0'
                        WHEN [t] = '1'           AND [n] = '0'           AND [m] = '0' THEN 'I'
                        WHEN [t] = '2'           AND [n] = '0'           AND [m] = '0' THEN 'II'
                        WHEN [t] IN('1','2')     AND [n] = '1'           AND [m] = '0' THEN 'III'
                        WHEN [t] = '3'           AND [n] IN('0','1')     AND [m] = '0' THEN 'III'
                        WHEN [t] IN('1','2','3') AND [n] = '2'           AND [m] = '0' THEN 'IVA'
                        WHEN [t] = '4a'          AND [n] IN('0','1','2') AND [m] = '0' THEN 'IVA'
                        WHEN                         [n] = '3'           AND [m] = '0' THEN 'IVB'
                        WHEN [t] = '4b'                                  AND [m] = '0' THEN 'IVB'
                        WHEN                                                 [m] = '1' THEN 'IVC'
                        ELSE NULL
                    END

                ## Pharynx: Oro- und Hypopharynx ##
                WHEN [localisation] LIKE 'C01%' OR [localisation] LIKE 'C09%' OR [localisation] LIKE 'C12%' OR [localisation] LIKE 'C13%' OR [localisation] IN ('C05.1','C05.2','C10.0','C10.2','C10.3') THEN
                    CASE
                        WHEN [t] = 'is'          AND [n] = '0'           AND [m] = '0' THEN '0'
                        WHEN [t] = '1'           AND [n] = '0'           AND [m] = '0' THEN 'I'
                        WHEN [t] = '2'           AND [n] = '0'           AND [m] = '0' THEN 'II'
                        WHEN [t] IN('1','2')     AND [n] = '1'           AND [m] = '0' THEN 'III'
                        WHEN [t] = '3'           AND [n] IN('0','1')     AND [m] = '0' THEN 'III'
                        WHEN [t] IN('1','2','3') AND [n] = '2'           AND [m] = '0' THEN 'IVA'
                        WHEN [t] = '4a'          AND [n] IN('0','1','2') AND [m] = '0' THEN 'IVA'
                        WHEN [t] = '4b'                                  AND [m] = '0' THEN 'IVB'
                        WHEN                         [n] = '3'           AND [m] = '0' THEN 'IVB'
                        WHEN                                                 [m] = '1' THEN 'IVC'
                        ELSE NULL
                    END

                ## Pharynx: Nasopharynx ##
                WHEN [localisation] LIKE 'C11%' THEN
                    CASE

                        WHEN [t] = 'is'      AND [n] = '0'           AND [m] = '0' THEN '0'
                        WHEN [t] = '1'       AND [n] = '0'           AND [m] = '0' THEN 'I'
                        WHEN [t] = '1'       AND [n] = '1'           AND [m] = '0' THEN 'II'
                        WHEN [t] = '2'       AND [n] IN('0','1')     AND [m] = '0' THEN 'II'
                        WHEN [t] IN('1','2') AND [n] = '2'           AND [m] = '0' THEN 'III'
                        WHEN [t] = '3'       AND [n] IN('0','1','2') AND [m] = '0' THEN 'III'
                        WHEN [t] = '4'       AND [n] IN('0','1','2') AND [m] = '0' THEN 'IVA'
                        WHEN                     [n] = '3'           AND [m] = '0' THEN 'IVB'
                        WHEN                                             [m] = '1' THEN 'IVC'
                        ELSE NULL
                    END

                ## Larynx ##
                WHEN [localisation] LIKE 'C32%' OR [localisation] = 'C10.1' THEN
                    CASE
                        WHEN [t] = 'is'          AND [n] = '0'            AND [m] = '0' THEN '0'
                        WHEN [t] = '1'           AND [n] = '0'            AND [m] = '0' THEN 'I'
                        WHEN [t] = '2'           AND [n] = '0'            AND [m] = '0' THEN 'II'
                        WHEN [t] IN('1','2')     AND [n] = '1'            AND [m] = '0' THEN 'III'
                        WHEN [t] = '3'           AND [n] IN('0','1')      AND [m] = '0' THEN 'III'
                        WHEN [t] IN('1','2','3') AND [n] = '2'            AND [m] = '0' THEN 'IVA'
                        WHEN [t] = '4a'          AND [n] IN('0','1', '2') AND [m] = '0' THEN 'IVA'
                        WHEN [t] = '4b'                                   AND [m] = '0' THEN 'IVB'
                        WHEN                         [n] = '3'            AND [m] = '0' THEN 'IVB'
                        WHEN                                                  [m] = '1' THEN 'IVC'
                        ELSE NULL
                    END
                ELSE NULL
            END
       ",
        'gt' => "
             CASE
                ## Vulva ##
                WHEN [localisation] LIKE 'C51%' THEN
                    CASE
                        WHEN [t] = 'is'      AND [n] = '0'         AND [m] = '0' THEN '0'
                        WHEN [t] = '1'       AND [n] = '0'         AND [m] = '0' THEN 'I'
                        WHEN [t] = '1a'      AND [n] = '0'         AND [m] = '0' THEN 'IA'
                        WHEN [t] = '1b'      AND [n] = '0'         AND [m] = '0' THEN 'IB'
                        WHEN [t] = '2'       AND [n] = '0'         AND [m] = '0' THEN 'II'
                        WHEN [t] IN('1','2') AND [n] IN('1a','1b') AND [m] = '0' THEN 'IIIA'
                        WHEN [t] IN('1','2') AND [n] IN('2a','2b') AND [m] = '0' THEN 'IIIB'
                        WHEN [t] IN('1','2') AND [n] = '2c'        AND [m] = '0' THEN 'IIIC'
                        WHEN [t] IN('1','2') AND [n] = '3'         AND [m] = '0' THEN 'IVA'
                        WHEN [t] = '3'                             AND [m] = '0' THEN 'IVA'
                        WHEN                                           [m] = '1' THEN 'IVB'
                        ELSE NULL
                    END
                ## Vagina ##
                WHEN [localisation] = 'C52.9' THEN
                    CASE
                        WHEN [t] = 'is'          AND [n] = '0' AND [m] = '0' THEN '0'
                        WHEN [t] = '1'           AND [n] = '0' AND [m] = '0' THEN 'I'
                        WHEN [t] = '2'           AND [n] = '0' AND [m] = '0' THEN 'II'
                        WHEN [t] = '3'           AND [n] = '0' AND [m] = '0' THEN 'III'
                        WHEN [t] IN('1','2','3') AND [n] = '1' AND [m] = '0' THEN 'III'
                        WHEN [t] = '4'                         AND [m] = '0' THEN 'IVA'
                        WHEN                                       [m] = '1' THEN 'IVB'
                        ELSE NULL
                    END
                ## Cervix uteri ##
                WHEN [localisation] IN ('C53.0', 'C53.1') THEN
                    CASE
                        WHEN [t] = 'is'           AND [n] = '0' AND [m] = '0' THEN '0'
                        WHEN [t] = '1a'           AND [n] = '0' AND [m] = '0' THEN 'IA'
                        WHEN [t] = '1a1'          AND [n] = '0' AND [m] = '0' THEN 'IA1'
                        WHEN [t] = '1a2'          AND [n] = '0' AND [m] = '0' THEN 'IA2'
                        WHEN [t] = '1b'           AND [n] = '0' AND [m] = '0' THEN 'IB'
                        WHEN [t] = '1b1'          AND [n] = '0' AND [m] = '0' THEN 'IB1'
                        WHEN [t] = '1b2'          AND [n] = '0' AND [m] = '0' THEN 'IB2'
                        WHEN [t] = '2'            AND [n] = '0' AND [m] = '0' THEN 'II'
                        WHEN [t] = '2a'           AND [n] = '0' AND [m] = '0' THEN 'IIA'
                        WHEN [t] = '2b'           AND [n] = '0' AND [m] = '0' THEN 'IIB'
                        WHEN [t] = '2a1'          AND [n] = '0' AND [m] = '0' THEN 'IIA1'
                        WHEN [t] = '2a2'          AND [n] = '0' AND [m] = '0' THEN 'IIA2'
                        WHEN [t] = '3'            AND [n] = '0' AND [m] = '0' THEN 'III'
                        WHEN [t] = '3a'           AND [n] = '0' AND [m] = '0' THEN 'IIIA'
                        WHEN [t] IN('1','2','3a') AND [n] = '1' AND [m] = '0' THEN 'IIIB'
                        WHEN [t] = '3b'                         AND [m] = '0' THEN 'IIIB'
                        WHEN [t] = '4'                          AND [m] = '0' THEN 'IVA'
                        WHEN                                        [m] = '1' THEN 'IVB'
                        ELSE NULL
                    END

                ## Uterrussarkome ##
                WHEN [localisation] IN ('C53.8', 'C53.9', 'C54.3') OR
                     ([localisation] = 'C54.0' AND [morphologie] IN ('8890/3','8930/3','8933/3')) THEN
                    CASE
                        WHEN [t] = '1'            AND [n] = '0' AND [m] = '0' THEN 'I'
                        WHEN [t] = '1a'           AND [n] = '0' AND [m] = '0' THEN 'IA'
                        WHEN [t] = '1b'           AND [n] = '0' AND [m] = '0' THEN 'IB'
                        WHEN [t] = '1c'           AND [n] = '0' AND [m] = '0' AND [morphologie] NOT IN ('8890/3', '8930/3') THEN 'IC'
                        WHEN [t] = '2'            AND [n] = '0' AND [m] = '0' THEN 'II'
                        WHEN [t] = '2a'           AND [n] = '0' AND [m] = '0' THEN 'IIA'
                        WHEN [t] = '2b'           AND [n] = '0' AND [m] = '0' THEN 'IIB'
                        WHEN [t] = '3a'           AND [n] = '0' AND [m] = '0' THEN 'IIIA'
                        WHEN [t] = '3b'           AND [n] = '0' AND [m] = '0' THEN 'IIIB'
                        WHEN [t] IN('1','2','3')  AND [n] = '1' AND [m] = '0' THEN 'IIIC'
                        WHEN [t] = '4'                          AND [m] = '0' THEN 'IVA'
                        WHEN                                        [m] = '1' THEN 'IVB'
                        ELSE NULL
                    END

                ## Uterus Endometrium##
                WHEN [localisation] IN('C54.3', 'C54.1') OR
                     ([localisation] = 'C54.0' AND [morphologie] NOT IN ('8890/3','8930/3','8933/3')) THEN
                    CASE
                        WHEN [t] = '1a'          AND [n] = '0' AND [m] = '0' THEN 'IA'
                        WHEN [t] = '1b'          AND [n] = '0' AND [m] = '0' THEN 'IB'
                        WHEN [t] = '2'           AND [n] = '0' AND [m] = '0' THEN 'II'
                        WHEN [t] = '3a'          AND [n] = '0' AND [m] = '0' THEN 'IIIA'
                        WHEN [t] = '3b'          AND [n] = '0' AND [m] = '0' THEN 'IIIB'
                        WHEN [t] IN('1','2','3') AND [n] = '1' AND [m] = '0' THEN 'IIIC'
                        WHEN [t] = '4'                         AND [m] = '0' THEN 'IVA'
                        WHEN                                       [m] = '1' THEN 'IVB'
                        ELSE NULL
                    END

                ## Ovar ##
                WHEN [localisation] = 'C56.9' THEN
                    CASE
                        WHEN [t] = '1a' AND [n] = '0' AND [m] = '0' THEN 'IA'
                        WHEN [t] = '1b' AND [n] = '0' AND [m] = '0' THEN 'IB'
                        WHEN [t] = '1c' AND [n] = '0' AND [m] = '0' THEN 'IC'
                        WHEN [t] = '2a' AND [n] = '0' AND [m] = '0' THEN 'IIA'
                        WHEN [t] = '2b' AND [n] = '0' AND [m] = '0' THEN 'IIB'
                        WHEN [t] = '2c' AND [n] = '0' AND [m] = '0' THEN 'IIC'
                        WHEN [t] = '3a' AND [n] = '0' AND [m] = '0' THEN 'IIIA'
                        WHEN [t] = '3b' AND [n] = '0' AND [m] = '0' THEN 'IIIB'
                        WHEN [t] = '3c' AND [n] = '0' AND [m] = '0' THEN 'IIIC'
                        WHEN                [n] = '1' AND [m] = '0' THEN 'IIIC'
                        WHEN                              [m] = '1' THEN 'IV'
                        ELSE NULL
                    END

                ## Tuba uterina ##
                WHEN [localisation] = 'C57.0' THEN
                    CASE
                        WHEN [t] = 'is' AND [n] = '0' AND [m] = '0' THEN '0'
                        WHEN [t] = '1a' AND [n] = '0' AND [m] = '0' THEN 'IA'
                        WHEN [t] = '1b' AND [n] = '0' AND [m] = '0' THEN 'IB'
                        WHEN [t] = '1c' AND [n] = '0' AND [m] = '0' THEN 'IC'
                        WHEN [t] = '2a' AND [n] = '0' AND [m] = '0' THEN 'IIA'
                        WHEN [t] = '2b' AND [n] = '0' AND [m] = '0' THEN 'IIB'
                        WHEN [t] = '2c' AND [n] = '0' AND [m] = '0' THEN 'IIC'
                        WHEN [t] = '3a' AND [n] = '0' AND [m] = '0' THEN 'IIIA'
                        WHEN [t] = '3b' AND [n] = '0' AND [m] = '0' THEN 'IIIB'
                        WHEN [t] = '3c' AND [n] = '0' AND [m] = '0' THEN 'IIIC'
                        WHEN                [n] = '1' AND [m] = '0' THEN 'IIIC'
                        WHEN                              [m] = '1' THEN 'IV'
                        ELSE NULL
                    END
                ## Retroperitoneum und Peritoneum ##
                WHEN [localisation] LIKE 'C48%' THEN
                    CASE
                        WHEN [t] = '1a' AND [n] = '0' AND [m] = '0' THEN 'IA'
                        WHEN [t] = '1b' AND [n] = '0' AND [m] = '0' THEN 'IB'
                        WHEN [t] = '1c' AND [n] = '0' AND [m] = '0' THEN 'IC'
                        WHEN [t] = '2a' AND [n] = '0' AND [m] = '0' THEN 'IIA'
                        WHEN [t] = '2b' AND [n] = '0' AND [m] = '0' THEN 'IIB'
                        WHEN [t] = '2c' AND [n] = '0' AND [m] = '0' THEN 'IIC'
                        WHEN [t] = '3a' AND [n] = '0' AND [m] = '0' THEN 'IIIA'
                        WHEN [t] = '3b' AND [n] = '0' AND [m] = '0' THEN 'IIIB'
                        WHEN [t] = '3c' AND [n] = '0' AND [m] = '0' THEN 'IIIC'
                        WHEN                [n] = '1' AND [m] = '0' THEN 'IIIC'
                        WHEN                              [m] = '1' THEN 'IV'
                        ELSE NULL
                    END
                ELSE NULL
            END
       ",
       'pa' => "
            CASE
                ## Pankreas ##
                WHEN [localisation] LIKE 'C25%' THEN
                    CASE
                        WHEN [t] = 'is'            AND [n] = '0'         AND [m] = '0' THEN '0'
                        WHEN [t] = '1'             AND [n] = '0'         AND [m] = '0' THEN 'IA'
                        WHEN [t] = '2'             AND [n] = '0'         AND [m] = '0' THEN 'IB'
                        WHEN [t] = '3'             AND [n] = '0'         AND [m] = '0' THEN 'IIA'
                        WHEN [t] IN('1','2', '3')  AND [n] = '1'         AND [m] = '0' THEN 'IIB'
                        WHEN [t] = '4'                                   AND [m] = '0' THEN 'III'
                        WHEN                                                 [m] = '0' THEN 'IV'
                        ELSE NULL
                    END
            END
        ",
        'd' => "
            CASE
                ## Kolon und Rektum ##
                WHEN LEFT([localisation], 3) IN ('C18', 'C19', 'C20') THEN
                    CASE
                        WHEN [t] = 'is'            AND [n] = '0'         AND [m] = '0'  THEN '0'
                        WHEN [t] IN('1','2')       AND [n] = '0'         AND [m] = '0'  THEN 'I'
                        WHEN [t] = '3'             AND [n] = '0'         AND [m] = '0'  THEN 'IIA'
                        WHEN [t] = '4a'            AND [n] = '0'         AND [m] = '0'  THEN 'IIB'
                        WHEN [t] = '4b'            AND [n] = '0'         AND [m] = '0'  THEN 'IIC'
                        WHEN [t] IN('1','2')       AND [n] = '1a'        AND [m] = '0'  THEN 'IIIA'
                        WHEN [t] = '1'             AND [n] = '2a'        AND [m] = '0'  THEN 'IIIA'
                        WHEN [t] IN('3','4a')      AND [n] = '1'         AND [m] = '0'  THEN 'IIIB'
                        WHEN [t] IN('2', '3')      AND [n] = '2a'        AND [m] = '0'  THEN 'IIIB'
                        WHEN [t] IN('1','2')       AND [n] = '2b'        AND [m] = '0'  THEN 'IIIB'
                        WHEN [t] = '4a'            AND [n] = '2a'        AND [m] = '0'  THEN 'IIIC'
                        WHEN [t] IN('3','4b')      AND [n] = '2b'        AND [m] = '0'  THEN 'IIIC'
                        WHEN [t] = '4b'            AND [n] IN('1','2')   AND [m] = '0'  THEN 'IIIC'
                        WHEN                           [n] IN('1','2')   AND [m] = '0'  THEN 'III'
                        WHEN                                                 [m] = '1a' THEN 'IVA'
                        WHEN                                                 [m] = '1b' THEN 'IVB'
                        ELSE NULL
                    END
            END
        ",
        'p' => "
            CASE
                ## Prostata ##
                WHEN [localisation] LIKE 'C61%' THEN
                    CASE
                        WHEN [t] IN ('1a', '2a')   AND [n] = '0'         AND [m] = '0' THEN 'I'
                        WHEN [t] IN ('2b', '2c')   AND [n] = '0'         AND [m] = '0' THEN 'II'
                        WHEN [t] = '3'             AND [n] = '0'         AND [m] = '0' THEN 'III'
                        WHEN [t] = '4'             AND [n] = '0'         AND [m] = '0' THEN 'IV'
                        WHEN                           [n] = '1'         AND [m] = '0' THEN 'IV'
                        WHEN                                                 [m] = '1' THEN 'IV'
                        ELSE NULL
                    END
            END
        ",
        'lu' => "
            CASE
                ## Lunge ##
                WHEN [localisation] LIKE 'C34%' THEN
                    CASE
                        WHEN [t] = 'X'                      AND [n] = '0'         AND [m] = '0' THEN 'Okkultes Karzinom'
                        WHEN [t] = 'is'                     AND [n] = '0'         AND [m] = '0' THEN '0'
                        WHEN [t] IN ('1a', '1b')            AND [n] = '0'         AND [m] = '0' THEN 'IA'
                        WHEN [t] = '2a'                     AND [n] = '0'         AND [m] = '0' THEN 'IB'
                        WHEN [t] = '2b'                     AND [n] = '0'         AND [m] = '0' THEN 'IIA'
                        WHEN [t] IN ('1a', '1b', '2a')      AND [n] = '1'         AND [m] = '0' THEN 'IIA'
                        WHEN [t] = '2b'                     AND [n] = '1'         AND [m] = '0' THEN 'IIB'
                        WHEN [t] = '3'                      AND [n] = '0'         AND [m] = '0' THEN 'IIB'
                        WHEN [t] IN ('1a', '1b', '2a', '2b') AND [n] = '2'         AND [m] = '0' THEN 'IIIA'
                        WHEN [t] = '3'                      AND [n] IN ('1', '2') AND [m] = '0' THEN 'IIIA'
                        WHEN [t] = '4'                      AND [n] IN ('0', '1') AND [m] = '0' THEN 'IIIA'
                        WHEN [t] = '4'                      AND [n] = '2'         AND [m] = '0' THEN 'IIIB'
                        WHEN                                    [n] = '3'         AND [m] = '0' THEN 'IIIB'
                        WHEN                                                          [m] = '1' THEN 'IV'
                        ELSE NULL
                    END
            END
        ",
        'h' => "
              CASE
                WHEN [diagnosis] LIKE 'C43%' THEN
                CASE
                    WHEN [t] = '1a'                  AND  [n] LIKE '0%'                                       AND [m] = '0'     THEN 'IA'
                    WHEN [t] = '1b'                  AND  [n] LIKE '0%'                                       AND [m] = '0'     THEN 'IB'
                    WHEN [t] = '2a'                  AND  [n] LIKE '0%'                                       AND [m] = '0'     THEN 'IB'
                    WHEN [t] = '2b'                  AND  [n] LIKE '0%'                                       AND [m] = '0'     THEN 'IIA'
                    WHEN [t] = '3a'                  AND  [n] LIKE '0%'                                       AND [m] = '0'     THEN 'IIA'
                    WHEN [t] = '3b'                  AND  [n] LIKE '0%'                                       AND [m] = '0'     THEN 'IIB'
                    WHEN [t] = '4a'                  AND  [n] LIKE '0%'                                       AND [m] = '0'     THEN 'IIB'
                    WHEN [t] = '4b'                  AND  [n] LIKE '0%'                                       AND [m] = '0'     THEN 'IIC'
                    WHEN [t] IN('1a','2a','3a','4a') AND ([n] LIKE '1a%' OR [n] LIKE '2a%')                   AND [m] = '0'     THEN 'IIIA'
                    WHEN [t] IN('1a','2a','3a','4a') AND ([n] LIKE '1b%' OR [n] LIKE '2b%' OR [n] LIKE '2c%') AND [m] = '0'     THEN 'IIIB'
                    WHEN [t] IN('1b','2b','3b','4b') AND ([n] LIKE '1a%' OR [n] LIKE '2a%')                   AND [m] = '0'     THEN 'IIIB'
                    WHEN [t] IN('1b','2b','3b','4b') AND ([n] LIKE '1b%' OR [n] LIKE '2b%' OR [n] LIKE '2c%') AND [m] = '0'     THEN 'IIIC'
                    WHEN [t] != 'X'                  AND  ([n] = '3' || [n] = '3(sn)')                        AND [m] = '0'     THEN 'IIIC'
                    WHEN [t] != 'X'                  AND  [n] != 'X'                                          AND [m] LIKE '1%' THEN 'IV'
                    ELSE NULL
                END
            END
        "
    );
}

?>
