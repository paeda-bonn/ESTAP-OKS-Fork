<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 */

namespace ESTAP;

use InvalidArgumentException;
use Phoolkit\I18N;
use ESTAP\Utils\DB;
use ESTAP\Teacher;
use ESTAP\TimeSlot;
use ESTAP\Pupil;

/**
 * A appointment.
 *
 * @author Klaus Reimer <k@ailis.de>
 */
final class Appointment
{
    /**
     * The appointment ID.
     *
     * @var integer;
     */
    private $id;

    /**
     * The ID of the time slot.
     *
     * @var integer
     */
    private $timeSlotId;

    /**
     * The end time in minutes after 00:00.
     *
     * @var integer
     */
    private $endTime;

    /**
     * The ID of the teacher connected to this appointment.
     * Null if not reserved.
     *
     * @var integer
     */
    private $teacherId;

    /**
     * The ID of the pupil connected to this appointment. Null if reserved
     * to no pupil.
     *
     * @var integer
     */
    private $pupilId;

    /**
     * Creates an appointment.
     *
     * @param integer $id
     *           The appointment ID. Null if not reserved.
     * @param integer $timeSlotId
     *           The ID of the time slot. Must not be null.
     * @param integer $teacherId
     *           The ID of the teacher connected to this appointment.
     *           Null if not reserved.
     * @param integer $pupilId
     *           The ID of the pupil connected to this appointment.
     *           Null if not reserved to a pupil.
     */
    public function __construct($id, $timeSlotId, $teacherId, $pupilId)
    {
        $this->id = $id;
        $this->timeSlotId = $timeSlotId;
        $this->teacherId = $teacherId;
        $this->pupilId = $pupilId;
    }

    /**
     * Returns the appointment ID.
     *
     * @return integer
     *            The ID of the appointment. Never null.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the time slot.
     *
     * @return TimeSlot
     *            The time slot.
     */
    public function getTimeSlot()
    {
        return TimeSlot::getById($this->timeSlotId);
    }

    /**
     * Returns the time slot ID.
     *
     * @return TimeSlot
     *            The time slot ID.
     */
    public function getTimeSlotId()
    {
        return $this->timeSlotId;
    }

    /**
     * Returns the teacher connected to this appointment. Null if none.
     *
     * @return Teacher
     *            The teacher connected to this appointment or null if none.
     */
    public function getTeacher()
    {
        if (is_null($this->teacherId)) return null;
        return Teacher::getById($this->teacherId);
    }

    /**
     * Returns the teacher ID. Null if none.
     *
     * @return integer
     *            The teacher ID or null if none.
     */
    public function getTeacherId()
    {
        return $this->teacherId;
    }

    /**
     * Returns the teacher connected to this appointment. Null if none.
     *
     * @return Teacher
     *            The teacher connected to this appointment or null if none.
     */
    public function getPupil()
    {
        if (is_null($this->pupilId)) return null;
        return Pupil::getById($this->pupilId);
    }

    /**
     * Returns the pupil ID. Null if none.
     *
     * @return integer
     *            The pupil ID or null if none.
     */
    public function getPupilId()
    {
        return $this->pupilId;
    }

    /**
     * Checks if appointment is reserved.
     *
     * @return boolean
     *            True if appointment is reserved, false if not.
     */
    public function isReserved()
    {
        return !is_null($this->teacherId);
    }

    /**
     * Checks if appointment is reserved to the specified pupil.
     *
     * @param int $pupilId
     *            The ID of the pupil to check.
     * @param integer $teacherId
     *            The ID of the current teacher.
     * @return boolean
     *            True if appointment is reserved to the specified pupil,
     *            false if not.
     */
    public function isReservedTo($pupilId, $teacherId)
    {
        return $this->pupilId == $pupilId && $this->teacherId == $teacherId;
    }

    /**
     * Checks if this appointment is locked.
     *
     * @return boolean
     *            True if appointment is a break, false if not.
     */
    public function isLocked()
    {
        return $this->isReserved() && is_null($this->pupilId);
    }

    /**
     * Creates a new appointment in the database and returns it.
     *
     * @param integer $timeSlotId
     *            The ID of the time slot to reserve. Must not be null.
     * @param integer $teacherId
     *            The ID of the teacher to connect to the appointment. Must
     *            not be null.
     * @param integer $pupilId
     *            The ID of the pupil to connect to the appointment. Null
     *            if none (Break).
     * @return Appointment
     *            The created appointment.
     */
    public static function create($timeSlotId, $teacherId, $pupilId)
    {
        $sql = "INSERT INTO appointments (time_slot_id, teacher_id, pupil_id) "
            . "VALUES (:time_slot_id, :teacher_id, :pupil_id)";
        $id = DB::exec($sql, array(
            "time_slot_id" => $timeSlotId,
            "teacher_id" => $teacherId,
            "pupil_id" => $pupilId
        ), "appointment_id");
        $appointment = new Appointment($id, $timeSlotId, $teacherId, $pupilId);
        return $appointment;
    }

    /**
     * Deletes a break from the database.
     *
     * @param integer $timeSlotId
     *            The ID of the time slot. Must not be null.
     * @param integer $teacherId
     *            The ID of the teacher. Must not be null.
     */
    public static function deleteBreak($timeSlotId, $teacherId)
    {
        $sql = "DELETE FROM appointments WHERE time_slot_id=:time_slot_id "
            . "AND teacher_id=:teacher_id";
        DB::exec($sql, array(
            "time_slot_id" => $timeSlotId,
            "teacher_id" => $teacherId));
    }

    /**
     * Deletes all appointments from the database.
     *
     */
    public static function deleteAll()
    {
        $sql = "DELETE FROM appointments";
        DB::exec($sql);
    }

    /**
     * Returns all appointments (Event unreserved ones) for the given teacher.
     *
     * @param int $teacherId
     *            The ID of the teacher to return the appointments for.
     * @return array
     *            The array with all matching appointments.
     */
    public static function getForTeacher($teacherId)
    {
        // Preload all time slots. We do this because the caller of this
        // method will need all time slots anyway and we don't want them to
        // be loaded one by one.
        TimeSlot::getAll();

        $appointments = array();
        $sql = "SELECT r.id AS id, ts.id AS time_slot_id, "
            . "r.teacher_id AS teacher_id, r.pupil_id AS pupil_id FROM "
            . "time_slots AS ts LEFT JOIN appointments AS r ON "
            . "ts.id=r.time_slot_id AND r.teacher_id=:teacher_id "
            . "ORDER BY ts.start_time ASC";
        $params = array("teacher_id" => $teacherId);
        foreach (DB::query($sql, $params) as $row)
        {
            $id = $row["id"];
            $teacherId = $row["teacher_id"];
            $pupilId = $row["pupil_id"];
            $appointment = new Appointment(
                is_null($id) ? null : +$id,
                +$row["time_slot_id"],
                is_null($teacherId) ? null : +$teacherId,
                is_null($pupilId) ? null : +$pupilId);
            $appointments[] = $appointment;
        }
        return $appointments;
    }

    /**
     * Returns all appointments (Event unreserved ones) for the given teacher
     * and the given pupil
     *
     * @param int $teacherId
     *            The ID of the teacher to return the appointments for.
     * @param int $pupilId
     *            The ID of the pupil to return the appointments for.
     * @return array
     *            The array with all matching appointments.
     */
    public static function getForTeacherAndPupil($teacherId, $pupilId)
    {
        // Preload all time slots. We do this because the caller of this
        // method will need all time slots anyway and we don't want them to
        // be loaded one by one.
        TimeSlot::getAll();

        $appointments = array();
        $sql = "SELECT r.id AS id, ts.id AS time_slot_id, "
            . "r.teacher_id AS teacher_id, r.pupil_id AS pupil_id FROM "
            . "time_slots AS ts LEFT JOIN appointments AS r ON "
            . "ts.id=r.time_slot_id AND (r.teacher_id=:teacher_id "
            . "OR r.pupil_id=:pupil_id) "
            . "ORDER BY ts.start_time ASC";
        $params = array("teacher_id" => $teacherId, "pupil_id" => $pupilId);
        foreach (DB::query($sql, $params) as $row)
        {
            $id = $row["id"];
            $teacherId = $row["teacher_id"];
            $pupilId = $row["pupil_id"];
            $appointment = new Appointment(
                is_null($id) ? null : +$id,
                +$row["time_slot_id"],
                is_null($teacherId) ? null : +$teacherId,
                is_null($pupilId) ? null : +$pupilId);
            $appointments[] = $appointment;
        }
        return $appointments;
    }

    /**
     * Returns all appointments for the given pupils.
     *
     * @param array $pupilIds
     *            Array with pupil IDs to return the appointments for.
     * @return array
     *            The array with all matching appointments.
     */
    public static function getForPupils($pupilIds)
    {
        $appointments = array();
        $sql = sprintf("SELECT r.id AS id, ts.id AS time_slot_id, "
            . "r.teacher_id AS teacher_id, r.pupil_id AS pupil_id FROM "
            . "appointments AS r INNER JOIN time_slots AS ts ON "
            . "ts.id=r.time_slot_id INNER JOIN teachers AS t ON "
            . "t.id=r.teacher_id WHERE r.pupil_id IN (%s) "
            . "AND t.active=1 ORDER BY ts.start_time ASC",
            implode(",", $pupilIds));
        foreach (DB::query($sql) as $row)
        {
            $id = $row["id"];
            $teacherId = $row["teacher_id"];
            $pupilId = $row["pupil_id"];
            $appointment = new Appointment(
                is_null($id) ? null : +$id,
                +$row["time_slot_id"],
                is_null($teacherId) ? null : +$teacherId,
                is_null($pupilId) ? null : +$pupilId);
            $appointments[] = $appointment;
        }
        return $appointments;
    }

    /**
     * Deletes the appointment with the specified pupil and teacher.
     *
     * @param integer $pupilId
     *            The ID of the pupil.
     * @param integer $pupilId
     *            The ID of the teacher.
     */
    public static function deleteByPupilTeacher($pupilId, $teacherId)
    {
        $sql = "DELETE FROM appointments WHERE pupil_id=:pupil_id AND "
            . "teacher_id=:teacher_id";
        DB::exec($sql, array(
            "pupil_id" => $pupilId,
            "teacher_id" => $teacherId
        ));
    }

    /**
     * Returns the selected appointment. This is either the first free
     * appointment or the appointment which is already reserved to the
     * specified pupil. If there is no free appointment then null is returned.
     *
     * @param array $appointments
     *            The appointments
     * @param integer $pupilId
     *            The ID of the current pupil.
     * @param integer $teacherId
     *            The ID of the current teacher.
     * @return Appointment
     *            The selected appointment or null if there is no free one.
     */
    public static function getSelected($appointments, $pupilId, $teacherId)
    {
        $selected = null;
        foreach ($appointments as $appointment)
        {
            if (!$appointment->isReserved() && is_null($selected))
                $selected = $appointment;
            if ($appointment->isReservedTo($pupilId, $teacherId))
                return $appointment;
        }
        return $selected;
    }

    /**
     * Checks if the specified appointment conflicts with one of the other
     * appointments.
     *
     * @return boolean
     *            True if appointment has a conflict, false if not.
     */
    public static function isConflict($appointment, $appointments)
    {
        foreach ($appointments as $other)
        {
            if ($other === $appointment) continue;
            if ($other->getTimeSlotId() == $appointment->getTimeSlotId())
                return true;
        }
        return false;
    }

    /**
     * Returns the appointment with the specified ID.
     *
     * @param int $id
     *            The appointment ID.
     * @throws RuntimeException
     *            When there is no appointment with the specified ID.
     */
    public static function getById($id)
    {
        $sql = "SELECT time_slot_id, teacher_id, pupil_id FROM appointments WHERE id=:id";
        $data = DB::querySingle($sql, array("id" => $id));
        if (!$data) throw new InvalidArgumentException("There is no appointment with the ID $id");
        return new Appointment($id, $data["time_slot_id"], $data["teacher_id"],
            $data["pupil_id"]);
    }
}