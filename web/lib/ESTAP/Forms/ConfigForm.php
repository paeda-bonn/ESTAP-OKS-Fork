<?php
/*
 * Copyright 2014 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 */

namespace ESTAP\Forms;

use PhoolKit\Form;
use PhoolKit\RequireValidator;
use ESTAP\Config;

/**
 * The configuration form.
 *
 * @author Klaus Reimer <k@ailis.de>
 */
class ConfigForm extends Form
{
    /**
     * If parent login should be enabled.
     *
     * @var boolean
     */
    public $parentLoginEnabled;

    /**
     * If teacher login should be enabled.
     *
     * @var boolean
     */
    public $teacherLoginEnabled;

    /**
     * If appointment reservations are enabled.
     *
     * @var boolean
     */
    public $reservationEnabled;

    /**
     * If duplicate pupil appointment reservations is allowed.
     *
     * @var boolean
     */
    public $duplicatesEnabled;

    /**
     * Comma-separated list of available locales.
     *
     * @var string
     */
    public $locales;

    /**
     * The default locale.
     *
     * @var string
     */
    public $defaultLocale;

    /**
     * The available time slot durations as a comma-separated string.
     *
     * @var string
     */
    public $timeSlotDurations;

    /**
     * The default time slot duration.
     *
     * @var string
     */
    public $defaultTimeSlotDuration;

    /**
     * The minimum password length.
     *
     * @var string
     */
    public $minPasswordLength;

    /**
     * The minimum time slot start hour.
     *
     * @var string
     */
    public $minTimeSlotStartHour;

    /**
     * The maximum time slot end hour.
     *
     * @var string
     */
    public $maxTimeSlotEndHour;

    /**
     * Beginning day of enabled reservation.
     *
     * @var integer
     */
    public $reservationStartDay;

    /**
     * Beginning month of enabled reservation.
     *
     * @var integer
     */
    public $reservationStartMonth;

    /**
     * Beginning year of enabled reservation.
     *
     * @var integer
     */
    public $reservationStartYear;

    /**
     * Beginning hour of enabled reservation.
     *
     * @var integer
     */
    public $reservationStartHour;

    /**
     * Beginning minute of enabled reservation.
     *
     * @var integer
     */
    public $reservationStartMinute;

    /**
     * End day of enabled reservation.
     *
     * @var integer
     */
    public $reservationEndDay;

    /**
     * End month of enabled reservation.
     *
     * @var integer
     */
    public $reservationEndMonth;

    /**
     * End year of enabled reservation.
     *
     * @var integer
     */
    public $reservationEndYear;

    /**
     * End hour of enabled reservation.
     *
     * @var integer
     */
    public $reservationEndHour;

    /**
     * End minute of enabled reservation.
     *
     * @var integer
     */
    public $reservationEndMinute;

    /**
     * If logo should be deleted.
     *
     * @var boolean
     */
    public $deleteLogo;

    /**
     * If background should be deleted.
     *
     * @var boolean
     */
    public $deleteBackground;

    /**
     * The application title (Map with locale as key and title as value).
     *
     * @var Array
     */
    public $title;

    /**
     * The greeting text (Map with locale as key and title as value).
     *
     * @var Array
     */
    public $greeting;

    /**
     * @see PhoolKit.Form::getValidators()
     */
    public function getValidators()
    {
        return array(
            new RequireValidator("locales", "defaultLocale",
                "timeSlotDurations", "defaultTimeSlotDuration",
                "minPasswordLength", "minTimeSlotStartHour",
                "maxTimeSlotEndHour")
        );
    }

    /**
     * @see PhoolKit.Form::init()
     */
    public function init()
    {
        $config = Config::get();
        $this->teacherLoginEnabled = $config->isTeacherLoginEnabled();
        $this->parentLoginEnabled = $config->isParentLoginEnabled();
        $this->reservationEnabled = $config->isReservationEnabled();
        $this->duplicatesEnabled = $config->isDuplicatesEnabled();
        $this->locales = join(",", $config->getLocales());
        $this->defaultLocale = $config->getDefaultLocale();
        $this->timeSlotDurations = join(",", $config->getTimeSlotDurations());
        $this->defaultTimeSlotDuration = $config->getDefaultTimeSlotDuration();
        $this->minPasswordLength = $config->getMinPasswordLength();
        $this->minTimeSlotStartHour = $config->getMinTimeSlotStartHour();
        $this->maxTimeSlotEndHour = $config->getMaxTimeSlotEndHour();
        $this->reservationStartDay = $config->getReservationStartDay();
        $this->reservationStartMonth = $config->getReservationStartMonth();
        $this->reservationStartYear = $config->getReservationStartYear();
        $this->reservationStartHour = $config->getReservationStartHour();
        $this->reservationStartMinute = $config->getReservationStartMinute();
        $this->reservationEndDay = $config->getReservationEndDay();
        $this->reservationEndMonth = $config->getReservationEndMonth();
        $this->reservationEndYear = $config->getReservationEndYear();
        $this->reservationEndHour = $config->getReservationEndHour();
        $this->reservationEndMinute = $config->getReservationEndMinute();
        $this->title = array();
        $this->greeting = array();
        foreach ($config->getLocales() as $locale)
        {
            $this->title[$locale] = $config->getTitle($locale);
            $this->greeting[$locale] = $config->getGreeting($locale);
        }
    }
}
