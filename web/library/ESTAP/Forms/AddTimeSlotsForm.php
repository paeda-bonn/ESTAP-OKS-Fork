<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */

namespace ESTAP\Forms;

use ESTAP\Config;
use PhoolKit\Form;
use PhoolKit\RequireValidator;

/**
 * Form to add time slots.
 *
 * @author Klaus Reimer <k@ailis.de>
 */
class AddTimeSlotsForm extends Form
{
    /**
     * The time slot duration.
     *
     * @var string
     */
    public $duration;

    /**
     * The starting hour.
     *
     * @var string
     */
    public $startHour;

    /**
     * The starting minute.
     *
     * @var string
     */
    public $startMinute;

    /**
     * The ending hour.
     *
     * @var string.
     */
    public $endHour;

    /**
     * The ending minute.
     *
     * @var string.
     */
    public $endMinute;

    /**
     * The day of the month
     *
     * @var int
     */
    public $day;

    /**
     * The month of the year
     *
     * @var int
     */
    public $month;

    /**
     * The year
     *
     * @var int
     */
    public $year;


    /**
     * @see PhoolKit.Form::getValidators()
     */
    public function getValidators()
    {
        return array(
            new RequireValidator("duration", "startHour", "startMinute",
                "endHour", "endMinute", "day", "month", "year")
        );
    }

    /**
     * @see PhoolKit.Form::init()
     */
    public function init()
    {
        $config = Config::get();
        $this->duration = $config->getDefaultTimeSlotDuration();
        $this->startHour = $config->getMinTimeSlotStartHour();
        $this->startMinute = 0;
        $this->endHour = $config->getMaxTimeSlotEndHour();
        $this->endMinute = 0;
        $this->day = date("d");
        $this->month = date("m");
        $this->year = date("Y");
    }
}
