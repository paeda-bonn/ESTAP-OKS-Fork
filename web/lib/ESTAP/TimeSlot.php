<?php 
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */

namespace ESTAP;

use InvalidArgumentException;
use Phoolkit\I18N;
use ESTAP\Utils\DB;
use ESTAP\Config;

/**
 * A time slot which can be reserved for a pupil.
 * 
 * @author Klaus Reimer <k@ailis.de>
 */
final class TimeSlot
{
    /**
     * Cached complete list of all time slots.
     * 
     * @var array
     */
    private static $timeSlots;
 
    /**
     * Index from ID to cached time slot.
     * 
     * @var object
     */
    private static $timeSlotIndex = array();
        
    /**
     * The time slot ID.
     * 
     * @var integer;
     */
    private $id;
    
    /** 
     * The start time in minutes after 00:00.
     *  
     * @var integer
     */ 
    private $startTime;
    
    /** 
     * The end time in minutes after 00:00.
     * 
     * @var integer
     */ 
    private $endTime;
    
    /**
     * Creates a new time slot.
     * 
     * @param integer $id
     *           The ID of the time slot.
     * @param integer $startTime
     *           The start time in minutes after 00:00.
     * @param integer $endTime
     *           The end time in minutes after 00:00.
     */
    public function __construct($id, $startTime, $endTime)
    {
        $this->id = $id;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }
    
    /**
     * Returns the time slot ID.
     * 
     * @return integer
     *            The time slot ID
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Returns the start time in minutes after 00:00.
     * 
     * @return integer
     *            The start time.
     */
    public function getStartTime()
    {
        return $this->startTime;
    }
    
    /**
     * Returns the start time as a string.
     * 
     * @return string
     *            The start time as a string.
     */
    public function getStartTimeString()
    {
        return sprintf("%02d:%02d", $this->startTime / 60, $this->startTime % 60);
    }
    
    /**
     * Returns the end time in minutes after 00:00.
     * 
     * @return integer
     *            The end time.
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Returns the end time as a string.
     * 
     * @return string
     *            The end time as a string.
     */
    public function getEndTimeString()
    {
        return sprintf("%02d:%02d", $this->endTime / 60, $this->endTime % 60);
    }
    
    /**
     * Returns the time slot as a string.
     * 
     * @return string
     *            The time slot as a string.
     */
    public function getTimeString()
    {
        return I18N::getMessage("timeSlot", $this->getStartTimeString(), 
            $this->getEndTimeString()); 
    }

    /**
     * Returns the possible durations.
     * 
     * @return array
     *            The possible durations.
     */
    public static function getDurations()
    {
        $durations = array();
        foreach (Config::get()->getTimeSlotDurations() as $duration)
        {
            $durations[$duration] = I18N::getMessage("duration", $duration);
        }
        return $durations;
    }

    /**
     * Returns the list of hours to select as start and end hour for new
     * time slots.
     * 
     * @return array
     *            The possible hours.
     */
    public static function getHours()
    {
        $config = Config::get();
        $hours = array();
        for ($i = $config->getMinTimeSlotStartHour(); 
            $i <= $config->getMaxTimeSlotEndHour(); $i += 1)
        {
            $hours[$i] = sprintf("%02d", $i);
        }
        return $hours;
    }

    /**
     * Returns the list of minutes to select from for the start and end time
     * in time slot generation
     * 
     * @return array
     *            The possible minutes.
     */
    public static function getMinutes()
    {
        $minutes = array();
        for ($i = 0; $i < 60; $i += 15)
        {
            $minutes[$i] = sprintf("%02d", $i);
        }
        return $minutes;
    }    
    
    /**
     * Creates a new time slot in the database and returns it.
     * 
     * @param string $startTime
     *            The start time in minutes after 00:00.
     * @param string $endTime
     *            The end time in minutes after 00:00.
     * @return TimeSlot
     *            The created time slot.
     */
    public static function create($startTime, $endTime)
    {
        $sql = "INSERT INTO time_slots (start_time, end_time) "
            . "VALUES (:start_time, :end_time)";
        $id = DB::exec($sql, array(
            "start_time" => sprintf("%02d:%02d:00", $startTime / 60, $startTime % 60),
            "end_time" => sprintf("%02d:%02d:00", $endTime / 60, $endTime % 60)
        ), "time_slot_id");
        $timeSlot = new TimeSlot($id, $startTime, $endTime);
        self::$timeSlotIndex[$id] = $timeSlot;
        return $timeSlot;
    }
    
    /**
     * Returns all time slots.
     * 
     * @return array
     *            The array with all time slots.
     */
    public static function getAll()
    {
        if (!self::$timeSlots)
        {
            self::$timeSlots = array(); 
            $sql = "SELECT id, start_time, end_time FROM time_slots ORDER BY start_time ASC"; 
            foreach (DB::query($sql) as $row)
            {
                $id = +$row["id"];
                $values = explode(":", $row["start_time"]);
                $startTime = +$values[0] * 60 + $values[1];
                $values = explode(":", $row["end_time"]);
                $endTime = +$values[0] * 60 + $values[1];
                $timeSlot = new TimeSlot($id, $startTime, $endTime);
                self::$timeSlots[] = $timeSlot;
                self::$timeSlotIndex[$id] = $timeSlot;
            }
        }
        return self::$timeSlots;
    }
    
    /**
     * Returns the time slot with the specified ID
     * 
     * @param int $id
     *            The time slot ID.
     * @return TimeSlot
     *            The time slot.
     * @throws RuntimeException
     *            When there is no time slot with the specified ID. 
     */
    public static function getById($id)
    {
        if (array_key_exists($id, self::$timeSlotIndex))
        {
            $timeSlot = self::$timeSlotIndex[$id];
        }
        else
        {
            $sql = "SELECT start_time, end_time FROM time_slots "
                . "WHERE id=:id";
            $data = DB::querySingle($sql, array("id" => $id));
            if (!$data) throw new RuntimeException("No time slot with ID $id");
            $values = explode(":", $data["start_time"]);
            $startTime = +$values[0] * 60 + $values[1];
            $values = explode(":", $data["end_time"]);
            $endTime = +$values[0] * 60 + $values[1];
            $timeSlot = new TimeSlot($id, $startTime, $endTime);
            self::$timeSlotIndex[$id] = $timeSlot;
        }
        return $timeSlot;
    }
        
    /**
     * Deletes all time slots.
     */
    public static function deleteAll()
    {
        $sql = "DELETE FROM time_slots";
        DB::exec($sql);
    }   
    
    /**
     * Deletes this time slot from the database. The time slot object is no 
     * longer valid after this call so don't use it anymore.
     */
    public function delete()
    {
        self::deleteById($this->getId());
    }
    
    /**
     * Deletes a single time slot.
     * 
     * @param integer $timeSlotId
     *            The ID of the time slot to delete.
     */
    public static function deleteById($id)
    {
        $sql = "DELETE FROM time_slots WHERE id=:id";
        DB::exec($sql, array("id" => $id));
    }   
}