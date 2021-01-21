<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 */

namespace ESTAP;

use ESTAP\Exceptions\ConfigException;
use Phoolkit\I18N;
use Phoolkit\Request;
use RuntimeException;

/**
 * The ESTAP settings.
 *
 * @author Klaus Reimer <k@ailis.de>
 */
final class Config
{
    /** The configuration values. */
    private $values;

    /** The current locale. */
    private $locale;

    /** The singleton instance of the configuration. */
    private static $instance = null;

    private function __construct()
    {
        $file = Request::getBaseDir() . DIRECTORY_SEPARATOR . "data" .
            DIRECTORY_SEPARATOR . "config.php";
        if (file_exists($file))
            $this->values = include($file);
        else
            $this->values = array();
        $this->locale = Request::getLocale($this->getLocales(),
            $this->getDefaultLocale());
        date_default_timezone_set('Europe/Berlin');
    }

    /**
     * Saves the configuration.
     */
    public function save()
    {
        $dataDir = Request::getBaseDir() . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR;
        $newFile = $dataDir . "config.php.new";
        $file = $dataDir . "config.php";

        ob_start();
        var_export($this->values);
        $data = "<?php return " . ob_get_contents() . "; ?>";
        ob_end_clean();
        if (@file_put_contents($newFile, $data) === false)
            throw new ConfigException();
        if (!@rename($newFile, $file))
            throw new ConfigException();
    }

    /**
     * Return the configuration.
     *
     * @return Config
     *            The configuration.
     */
    public static function get()
    {
        if (!self::$instance) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    /**
     * Returns the configaration value for the specified key or the specified
     * default value when key does not exist.
     *
     * @param string $key
     *            The config key.
     * @param mixed $defaultValue
     *            The default value used when configuration key does not exist.
     *            If not specified then null is used.
     * @return mixed
     *            The configuration value.
     */
    private function getValue($key, $defaultValue = null)
    {
        if (array_key_exists($key, $this->values))
            return $this->values[$key];
        else
            return $defaultValue;
    }

    /**
     * Checks if duplicates are allowed.
     *
     * @return boolean
     *            True if duplicates are allowed.
     */
    public function isDuplicatesEnabled()
    {
        return $this->getValue("duplicatesEnabled", false);
    }

    /**
     * Allows or disallows duplicates.
     *
     * @param boolean $duplicatesEnabled
     *            True to allow, false to disallow.
     */
    public function setDuplicatesEnabled($duplicatesEnabled)
    {
        $this->values["duplicatesEnabled"] = $duplicatesEnabled;
    }

    /**
     * Checks if parent login is enabled.
     *
     * @return boolean
     *            True if parent login is enabled.
     */
    public function isParentLoginEnabled()
    {
        return $this->getValue("parentLoginEnabled", false);
    }

    /**
     * Enables or disables parent login.
     *
     * @param boolean $parentLoginEnabled
     *            True to enable parent login, false to disable it.
     */
    public function setParentLoginEnabled($parentLoginEnabled)
    {
        $this->values["parentLoginEnabled"] = $parentLoginEnabled;
    }

    /**
     * Checks if teacher login is enabled.
     *
     * @return boolean
     *            True if parent login is enabled.
     */
    public function isTeacherLoginEnabled()
    {
        return $this->getValue("teacherLoginEnabled", false);
    }

    /**
     * Enables or disables teacher login.
     *
     * @param boolean $teacherLoginEnabled
     *            True to enable teacher login, false to disable it.
     */
    public function setTeacherLoginEnabled($teacherLoginEnabled)
    {
        $this->values["teacherLoginEnabled"] = $teacherLoginEnabled;
    }

    /**
     * Checks if appointment reservation for teachers is enabled.
     *
     * @return boolean
     *            True if appointment reservation is enabled.
     */
    public function isTeacherReservationEnabled()
    {
        $isEnabled = $this->isReservationEnabled();
        $now = date("Y.m.d.H.i");
        $stop = $this->getReservationEndYear() . '.' .
            str_pad($this->getReservationEndMonth(), 2, '0', STR_PAD_LEFT) . '.' .
            str_pad($this->getReservationEndDay(), 2, '0', STR_PAD_LEFT) . '.' .
            str_pad($this->getReservationEndHour(), 2, '0', STR_PAD_LEFT) . '.' .
            str_pad($this->getReservationEndMinute(), 2, '0', STR_PAD_LEFT);
        $beforeEndTime = ($now < $stop);
        return ($beforeEndTime & $isEnabled) or (!$isEnabled & $this->isTeacherLoginEnabled());
    }

    /**
     * Checks if appointment reservation for parents is enabled.
     *
     * @return boolean
     *            True if appointment reservation is enabled.
     */
    public function isParentReservationEnabled()
    {
        $isEnabled = $this->isReservationEnabled();
        $now = date("Y.m.d.H.i");
        $start = $this->getReservationStartYear() . '.' .
            str_pad($this->getReservationStartMonth(), 2, '0', STR_PAD_LEFT) . '.' .
            str_pad($this->getReservationStartDay(), 2, '0', STR_PAD_LEFT) . '.' .
            str_pad($this->getReservationStartHour(), 2, '0', STR_PAD_LEFT) . '.' .
            str_pad($this->getReservationStartMinute(), 2, '0', STR_PAD_LEFT);
        $stop = $this->getReservationEndYear() . '.' .
            str_pad($this->getReservationEndMonth(), 2, '0', STR_PAD_LEFT) . '.' .
            str_pad($this->getReservationEndDay(), 2, '0', STR_PAD_LEFT) . '.' .
            str_pad($this->getReservationEndHour(), 2, '0', STR_PAD_LEFT) . '.' .
            str_pad($this->getReservationEndMinute(), 2, '0', STR_PAD_LEFT);
        $inTimeWindow = ($start <= $now) & ($now < $stop);
        return ($isEnabled & $inTimeWindow) or (!$isEnabled & $this->isParentLoginEnabled());
    }

    /**
     * Checks if appointment reservation is enabled.
     *
     * @return boolean
     *            True if appointment reservation is enabled.
     */
    public function isReservationEnabled()
    {
        return $this->getValue("reservationEnabled", false);
    }

    /**
     * Enables or disables appointment reservation.
     *
     * @param boolean $reservationEnabled
     *            True to enable appointment reservation, false to disable it.
     */
    public function setReservationEnabled($reservationEnabled)
    {
        $this->values["reservationEnabled"] = $reservationEnabled;
    }

    /**
     * Checks if parent login is enabled and throws an exception if this is
     * not the case.
     *
     * @throws RuntimeException
     *            When parent login is not enabled.
     */
    public function requireParentLoginEnabled()
    {
        if (!$this->isParentLoginEnabled())
            throw new RuntimeException(I18N::getMessage("errors.loginDisabled"));
    }

    /**
     * Checks if teacher login is enabled and throws an exception if this is
     * not the case.
     *
     * @throws RuntimeException
     *            When teacher login is not enabled.
     */
    public function requireTeacherLoginEnabled()
    {
        if (!$this->isTeacherLoginEnabled())
            throw new RuntimeException(I18N::getMessage("errors.loginDisabled"));
    }

    /**
     * Checks if appointment reservation is enabled and throws an exception if
     * this is not the case.
     *
     * @throws RuntimeException
     *            When appointment reservation is not enabled.
     */
    public function requireReservationEnabled()
    {
        if (!$this->isReservationEnabled())
            throw new RuntimeException(I18N::getMessage("errors.reservationDisabled"));
    }

    /**
     * Checks if appointment reservation for teachers is enabled and throws an exception if
     * this is not the case.
     *
     * @throws RuntimeException
     *            When appointment reservation is not enabled.
     */
    public function requireTeacherReservationEnabled()
    {
        if (!$this->isTeacherReservationEnabled())
            throw new RuntimeException(I18N::getMessage("errors.reservationDisabled"));
    }

    /**
     * Checks if appointment reservation for parents is enabled and throws an exception if
     * this is not the case.
     *
     * @throws RuntimeException
     *            When appointment reservation is not enabled.
     */
    public function requireParentReservationEnabled()
    {
        if (!$this->isParentReservationEnabled())
            throw new RuntimeException(I18N::getMessage("errors.reservationDisabled"));
    }

    /**
     * Returns the list of available locales.
     *
     * @return string[]
     *            The list of available locales.
     */
    public function getLocales()
    {
        return $this->getValue("locales", array("en", "de"));
    }

    /**
     * Sets the list of available locales.
     *
     * @param string[] $locales
     *            The list of available locates to set.
     */
    public function setLocales($locales)
    {
        $this->values["locales"] = $locales;
    }

    /**
     * Returns the default locale.
     *
     * @return string
     *            The default locale.
     */
    public function getDefaultLocale()
    {
        return $this->getValue("defaultLocale", "en");
    }

    /**
     * Sets the default locale.
     *
     * @param string $defaultLocale
     *            The default locale to set.
     */
    public function setDefaultLocale($defaultLocale)
    {
        $this->values["defaultLocale"] = $defaultLocale;
    }

    /**
     * Returns the available time slot durations as a comma-separated list.
     *
     * @return integer[]
     *            The available time slot durations.
     */
    public function getTimeSlotDurations()
    {
        return $this->getValue("timeSlotDurations", array(10, 15, 20, 30, 45, 60));
    }

    /**
     * Sets the available time slot durations.
     *
     * @param integer[] $timeSlotDurations
     *            The time slot durations to set.
     */
    public function setTimeSlotDurations($timeSlotDurations)
    {
        $this->values["timeSlotDurations"] = $timeSlotDurations;
    }

    /**
     * Returns the default time slot duration.
     *
     * @return integer
     *            The default time slot duration.
     */
    public function getDefaultTimeSlotDuration()
    {
        return $this->getValue("defaultTimeSlotDuration", 10);
    }

    /**
     * Sets the default time slot duration.
     *
     * @param integer $defaultTimeSlotDuration
     *            The default time slot duration to set.
     */
    public function setDefaultTimeSlotDuration($defaultTimeSlotDuration)
    {
        $this->values["defaultTimeSlotDuration"] = $defaultTimeSlotDuration;
    }

    /**
     * Returns the minimum password length.
     *
     * @return integer
     *            The minimum password length.
     */
    public function getMinPasswordLength()
    {
        return $this->getValue("minPasswordLength", 8);
    }

    /**
     * Sets the minimum password length.
     *
     * @param integer $minPasswordLength
     *            The minimum password length to set.
     */
    public function setMinPasswordLength($minPasswordLength)
    {
        $this->values["minPasswordLength"] = $minPasswordLength;
    }

    /**
     * Returns the minimum time slot start hour.
     *
     * @return integer
     *            The minimum time slot start hour.
     */
    public function getMinTimeSlotStartHour()
    {
        return $this->getValue("minTimeSlotStartHour", 7);
    }

    /**
     * Sets the minimum time slot start hour.
     *
     * @param integer $minTimeSlotStartHour
     *            The minimum time slot start hour to set.
     */
    public function setMinTimeSlotStartHour($minTimeSlotStartHour)
    {
        $this->values["minTimeSlotStartHour"] = $minTimeSlotStartHour;
    }

    /**
     * Returns the maximum time slot end hour.
     *
     * @return integer
     *            The maximum time slot end hour.
     */
    public function getMaxTimeSlotEndHour()
    {
        return $this->getValue("maxTimeSlotEndHour", 18);
    }

    /**
     * Sets the maximum time slot end hour.
     *
     * @param integer $maxTimeSlotEndHour
     *            The maximum time slot end hour to set.
     */
    public function setMaxTimeSlotEndHour($maxTimeSlotEndHour)
    {
        $this->values["maxTimeSlotEndHour"] = $maxTimeSlotEndHour;
    }

    /**
     * Returns the URL of the logo or null if no logo is set.
     *
     * @return The URL of the logo or null if none.
     */
    public function getLogo()
    {
        $filename = $this->getValue("logo");
        if (!$filename) return null;
        return Request::getBaseUrl() . "/data/" . $filename;
    }

    /**
     * Sets the logo.
     *
     * @param array $upload
     *           The file upload of the logo or null if logo should be
     *           removed.
     */
    public function setLogo($upload)
    {
        // Do nothing if empty file upload
        if ($upload && !$upload["tmp_name"]) return;

        // Remove existing file
        $file = $this->getLogo();
        if ($file) {
            @unlink(Request::getBaseDir() . DIRECTORY_SEPARATOR . "data" .
                DIRECTORY_SEPARATOR . $file);
            $this->values["logo"] = null;
        }

        // Save new file if present
        if ($upload) {
            $ext = pathinfo($upload["name"], PATHINFO_EXTENSION);
            $file = uniqid() . "." . $ext;
            @move_uploaded_file($upload["tmp_name"], Request::getBaseDir() .
                DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . $file);
            $this->values["logo"] = $file;
        }
    }

    /**
     * Returns the URL of the background image or null if no background is set.
     *
     * @return The URL of the background image or null if none.
     */
    public function getBackground()
    {
        $filename = $this->getValue("background");
        if (!$filename) return null;
        return Request::getBaseUrl() . "/data/" . $filename;
    }

    /**
     * Sets the background image.
     *
     * @param array $upload
     *           The file upload of the background image or null if background should be
     *           removed.
     */
    public function setBackground($upload)
    {
        // Do nothing if empty file upload
        if ($upload && !$upload["tmp_name"]) return;

        // Remove existing file
        $file = $this->getBackground();
        if ($file) {
            @unlink(Request::getBaseDir() . DIRECTORY_SEPARATOR . "data" .
                DIRECTORY_SEPARATOR . $file);
            $this->values["background"] = null;
        }

        // Save new file if present
        if ($upload) {
            $ext = pathinfo($upload["name"], PATHINFO_EXTENSION);
            $file = uniqid() . "." . $ext;
            @move_uploaded_file($upload["tmp_name"], Request::getBaseDir() .
                DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . $file);
            $this->values["background"] = $file;
        }
    }

    /**
     * Returns the URL of the custom CSS or null if not set.
     *
     * @return The URL of the custom CSS or null if none.
     */
    public function getStylesUrl()
    {
        $filename = $this->getValue("styles");
        if (!$filename) return null;
        return Request::getBaseUrl() . "/data/" . $filename;
    }

    /**
     * Returns the filename of the custom CSS or null if not set.
     *
     * @return The filename of the custom CSS or null if none.
     */
    public function getStylesFile()
    {
        $filename = $this->getValue("styles");
        if (!$filename) return null;
        return Request::getBaseDir() . DIRECTORY_SEPARATOR . "data" .
            DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Returns the custom CSS.
     *
     * @return The custom CSS.
     */
    public function getStyles()
    {
        $file = $this->getStylesFile();
        if (!file_exists($file)) return "";
        return file_get_contents($file);
    }

    /**
     * Sets the custom CSS.
     *
     * @param string $styles
     *           The custom CSS to set.
     */
    public function setStyles($styles)
    {
        // Remove existing CSS file
        $file = $this->getStylesFile();
        if ($file) {
            @unlink($file);
            $this->values["styles"] = null;
        }

        // Save new styles if present
        if ($styles) {
            $file = uniqid() . ".css";
            $this->values["styles"] = $file;
            file_put_contents($this->getStylesFile(), $styles);
        }
    }

    /**
     * Returns the application title for the specified locale.
     *
     * @param string $locale
     *            The locale.
     * @return string
     *            The application title.
     */
    public function getTitle($locale = null)
    {

        /*if (!$locale) $locale = $this->locale;
        $values = $this->getValue("title", array());
        if (array_key_exists($locale, $values))
            return $values[$locale];*/
        return "Elternsprechtag";
    }

    /**
     * Sets the application title for the specified locale.
     *
     * @param string $locale
     *            The locale. Defaults to current locale.
     * @param string $title
     *            The title to set.
     */
    public function setTitle($locale, $title)
    {
        if (!array_key_exists("title", $this->values))
            $this->values["title"] = array();
        $this->values["title"][$locale] = $title;
    }

    /**
     * Returns the greeting text for the specified locale.
     *
     * @param string $locale
     *            The locale. Defaults to current locale.
     * @return string
     *            The greeting text.
     */
    public function getGreeting($locale = null)
    {
        if (!$locale) $locale = $this->locale;
        $values = $this->getValue("greeting", array());
        if (array_key_exists($locale, $values))
            return $values[$locale];
        return "";
    }

    /**
     * Sets the greeting text for the specified locale.
     *
     * @param string $locale
     *            The locale.
     * @param string $greeting
     *            The greeting text to set.
     */
    public function setGreeting($locale, $greeting)
    {
        if (!array_key_exists("greeting", $this->values))
            $this->values["greeting"] = array();
        $this->values["greeting"][$locale] = $greeting;
    }

    /**
     * Returns the reservation start day.
     *
     * @return integer
     *            The reservation start day.
     */
    public function getReservationStartDay()
    {
        return $this->getValue("reservationStartDay", 1);
    }

    /**
     * Sets the reservation start day.
     *
     * @param integer $reservationStartDay
     *            The reservation start day to set.
     */
    public function setReservationStartDay($reservationStartDay)
    {
        $this->values["reservationStartDay"] = $reservationStartDay;
    }

    /**
     * Returns the reservation start month.
     *
     * @return integer
     *            The reservation start month.
     */
    public function getReservationStartMonth()
    {
        return $this->getValue("reservationStartMonth", 1);
    }

    /**
     * Sets the reservation start month.
     *
     * @param integer $reservationStartMonth
     *            The reservation start month to set.
     */
    public function setReservationStartMonth($reservationStartMonth)
    {
        $this->values["reservationStartMonth"] = $reservationStartMonth;
    }

    /**
     * Returns the reservation start year.
     *
     * @return integer
     *            The reservation start year.
     */
    public function getReservationStartYear()
    {
        return $this->getValue("reservationStartYear", date("Y"));
    }

    /**
     * Sets the reservation start year.
     *
     * @param integer $reservationStartYear
     *            The reservation start year to set.
     */
    public function setReservationStartYear($reservationStartYear)
    {
        $this->values["reservationStartYear"] = $reservationStartYear;
    }

    /**
     * Returns the reservation start hour.
     *
     * @return integer
     *            The reservation start hour.
     */
    public function getReservationStartHour()
    {
        return $this->getValue("reservationStartHour", 10);
    }

    /**
     * Sets the reservation start Hour.
     *
     * @param integer $reservationStartHour
     *            The reservation start hour to set.
     */
    public function setReservationStartHour($reservationStartHour)
    {
        $this->values["reservationStartHour"] = $reservationStartHour;
    }

    /**
     * Returns the reservation start minute.
     *
     * @return integer
     *            The reservation start minute.
     */
    public function getReservationStartMinute()
    {
        return $this->getValue("reservationStartMinute", 0);
    }

    /**
     * Sets the reservation start minute.
     *
     * @param integer $reservationStartMinute
     *            The reservation start Minute to set.
     */
    public function setReservationStartMinute($reservationStartMinute)
    {
        $this->values["reservationStartMinute"] = $reservationStartMinute;
    }

    /**
     * Returns the reservation end day.
     *
     * @return integer
     *            The reservation end day.
     */
    public function getReservationEndDay()
    {
        return $this->getValue("reservationEndDay", 1);
    }

    /**
     * Sets the reservation end day.
     *
     * @param integer $reservationEndDay
     *            The reservation end day to set.
     */
    public function setReservationEndDay($reservationEndDay)
    {
        $this->values["reservationEndDay"] = $reservationEndDay;
    }

    /**
     * Returns the reservation end month.
     *
     * @return integer
     *            The reservation end month.
     */
    public function getReservationEndMonth()
    {
        return $this->getValue("reservationEndMonth", 1);
    }

    /**
     * Sets the reservation end Month.
     *
     * @param integer $reservationEndMonth
     *            The reservation end month to set.
     */
    public function setReservationEndMonth($reservationEndMonth)
    {
        $this->values["reservationEndMonth"] = $reservationEndMonth;
    }

    /**
     * Returns the reservation end year.
     *
     * @return integer
     *            The reservation end year.
     */
    public function getReservationEndYear()
    {
        return $this->getValue("reservationEndYear", date("Y"));
    }

    /**
     * Sets the reservation end Year.
     *
     * @param integer $reservationEndYear
     *            The reservation end year to set.
     */
    public function setReservationEndYear($reservationEndYear)
    {
        $this->values["reservationEndYear"] = $reservationEndYear;
    }

    /**
     * Returns the reservation end hour.
     *
     * @return integer
     *            The reservation end hour.
     */
    public function getReservationEndHour()
    {
        return $this->getValue("reservationEndHour", 10);
    }

    /**
     * Sets the reservation end Hour.
     *
     * @param integer $reservationEndHour
     *            The reservation end hour to set.
     */
    public function setReservationEndHour($reservationEndHour)
    {
        $this->values["reservationEndHour"] = $reservationEndHour;
    }

    /**
     * Returns the reservation end minute.
     *
     * @return integer
     *            The reservation end minute.
     */
    public function getReservationEndMinute()
    {
        return $this->getValue("reservationEndMinute", 0);
    }

    /**
     * Sets the reservation end minute.
     *
     * @param integer $reservationEndMinute
     *            The reservation end Minute to set.
     */
    public function setReservationEndMinute($reservationEndMinute)
    {
        $this->values["reservationEndMinute"] = $reservationEndMinute;
    }

    /**
     * Returns the reservation start date.
     *
     * @return string
     *            The reservation start date.
     */
    public function getReservationStartDate()
    {
        return $this->getReservationStartDay() . '.' . $this->getReservationStartMonth() . '.' . $this->getReservationStartYear();

    }

    /**
     * Returns the reservation start time.
     *
     * @return string
     *            The reservation start time.
     */
    public function getReservationStartTime()
    {
        return $this->getReservationStartHour() . ':' . str_pad($this->getReservationStartMinute(), 2, '0', STR_PAD_LEFT);

    }

    /**
     * Returns the reservation end date.
     *
     * @return string
     *            The reservation end date.
     */
    public function getReservationEndDate()
    {
        return $this->getReservationEndDay() . '.' . $this->getReservationEndMonth() . '.' . $this->getReservationEndYear();
    }

    /**
     * Returns the reservation end time.
     *
     * @return string
     *            The reservation end time.
     */
    public function getReservationEndTime()
    {
        return $this->getReservationEndHour() . ':' . str_pad($this->getReservationEndMinute(), 2, '0', STR_PAD_LEFT);
    }

    /**
     * Returns the reservation end time.
     *
     * @return boolean
     *            The reservation end time.
     */
    public function isBeforeStartTime()
    {
        $now = date("Y.m.d.H.i");
        $start = $this->getReservationStartYear() . '.' .
            str_pad($this->getReservationStartMonth(), 2, '0', STR_PAD_LEFT) . '.' .
            str_pad($this->getReservationStartDay(), 2, '0', STR_PAD_LEFT) . '.' .
            str_pad($this->getReservationStartHour(), 2, '0', STR_PAD_LEFT) . '.' .
            str_pad($this->getReservationStartMinute(), 2, '0', STR_PAD_LEFT);
        return $now < $start;
    }

    /**
     * Returns the list of days to select as start and end day for the
     * reservation window.
     *
     * @return array
     *            The possible days.
     */
    public static function getDays()
    {
        $days = array();
        for ($i = 1; $i <= 31; $i += 1) {
            $days[$i] = sprintf("%02d", $i);
        }
        return $days;
    }

    /**
     * Returns the list of month to select as start and end month for the
     * reservation window.
     *
     * @return array
     *            The possible months.
     */
    public static function getMonths()
    {
        $months = array();
        for ($i = 1; $i <= 12; $i += 1) {
            $months[$i] = sprintf("%02d", $i);
        }
        return $months;
    }

    /**
     * Returns the list of years to select as start and end year for the
     * reservation window.
     *
     * @return array
     *            The possible years.
     */
    public static function getYears()
    {
        $years = array();
        for ($i = date('Y') - 1; $i <= date('Y') + 1; $i += 1) {
            $years[$i] = (string)$i;
        }
        return $years;
    }

    /**
     * Returns the list of hours to select as start and end hour for the
     * reservation window.
     *
     * @return array
     *            The possible hours.
     */
    public static function getHours()
    {
        $hours = array();
        for ($i = 0; $i <= 23; $i += 1) {
            $hours[$i] = sprintf("%02d", $i);
        }
        return $hours;
    }

    /**
     * Returns the list of minutes to select as start and end minute for the
     * reservation window.
     *
     * @return array
     *            The possible minutes.
     */
    public static function getMinutes()
    {
        $minutes = array();
        for ($i = 0; $i <= 50; $i += 10) {
            $minutes[$i] = sprintf("%02d", $i);
        }
        return $minutes;
    }

}
