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
 * Class registerStateMessageTherapyAbstract
 */
abstract class registerStateMessageTherapyAbstract extends registerStateMessageAbstract
{
    /**
     * _buildIntention
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _buildIntention(array $record)
    {
        $intention  = null;
        $rIntention = $record['intention'];

        if (strlen($rIntention) > 0) {
            if (str_contains($rIntention, 'kur') === true) {
                $intention = 'K';
            } else if (str_contains($rIntention, 'pal') === true) {
                $intention = 'P';
            } else {
                $intention = 'S';
            }
        }

        return registerHelper::ifNull($intention, 'X');
    }


    /**
     * _buildOpRelation
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _buildOpRelation(array $record)
    {
        $relation = null;
        $intention = $record['intention'];

        if (strlen($intention) > 0) {
            if (in_array($intention, array('kurna', 'palna')) === true) {
                $relation = 'N';
            } else if (in_array($intention, array('kura', 'pala')) === true) {
                $relation = 'A';
            } else if (in_array($intention, array('kur', 'pal')) === true) {
                $relation = 'O';
            } else {
                $relation = 'S';
            }
        }

        return $relation;
    }


    /**
     * _buildEndReason
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _buildEndReason(array $record)
    {
        $reason       = null;
        $status       = $record['endstatus'];
        $statusReason = $record['endstatus_grund'];

        if (in_array($statusReason, array('hn', 'nhn')) === true) {
            $reason = 'A';
        } else if ($status === 'plan') {
            $reason = 'E';
        } else if ($statusReason === 'patw') {
            $reason = 'V';
        } else if ($statusReason === 'tup') {
            $reason = 'P';
        } else if (strlen($statusReason) > 0) {
            $reason = 'S';
        } else if ($status === 'abbr') {
            $reason = 'U';
        }

        return $reason;
    }


    /**
     * _buildByEffect
     *
     * @access  protected
     * @param   array $record
     * @return  array
     */
    protected function _buildByEffect(array $record)
    {
        $byEffects   = array();
        $therapyDate = $record['beginn'];

        // only if begin date exists
        if (strlen($therapyDate) > 0) {
            foreach ($record['nebenwirkung'] as $byEffect) {
                $diff = date_diff_days($therapyDate, $byEffect['beginn']);

                if ($diff >= 0 && $diff <= 90) {
                    $grade = $byEffect['grad'];

                    if (in_array($grade, array(1, 2)) === true) {
                        $grade = 'K';
                    }

                    $byEffects[] = array(
                        'nebenwirkung_grad' => registerHelper::ifNull($grade, 'U'),
                        'nebenwirkung_art'  => registerMap::create('nci', $byEffect['nci_code'])
                            ->setAppending($byEffect['nci_text'], '; ')
                            ->getLabel()
                        ,
                        'nebenwirkung_version' => null
                    );
                }
            }
        }

        return $byEffects;
    }
}
