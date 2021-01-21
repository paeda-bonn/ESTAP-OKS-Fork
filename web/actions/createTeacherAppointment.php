<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action adds an appointment of a teacher to the database.
 */

require_once "../estap.php";

use ESTAP\Appointment;
use ESTAP\Config;
use ESTAP\Session;
use ESTAP\Utils\DB;
use PhoolKit\I18N;
use PhoolKit\Messages;
use PhoolKit\Request;

if (isset($_REQUEST["teacher"])) {
    $session = Session::get()->requireAdmin();
    $teacherId = +$_REQUEST["teacher"];
    $target = "../teacherAppointments.php?teacher=$teacherId";
    $admin = true;
} else {
    $session = Session::get()->requireTeacher();
    $teacherId = $session->getTeacher()->getId();
    $target = "../teacherAppointments.php";
    $admin = false;
}
$pupilId = +$_REQUEST["pupil"];
$timeSlotId = +$_REQUEST["timeSlot"];

if ($admin)
    try {
        DB::beginTransaction();
        Appointment::deleteByPupilTeacher($pupilId, $teacherId);
        $appointment = Appointment::create($timeSlotId, $teacherId, $pupilId);
        DB::commit();
        Messages::addInfo(I18N::getMessage("teacherAppointments.created"));
        Request::redirect($target);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000)
            Messages::addError(I18N::getMessage("errors.alreadyReserved"));
        else
            Messages::addError($e->getMessage());
        DB::rollBack();
        include "../createTeacherAppointment.php";
    } catch (Exception $e) {
        Messages::addError($e->getMessage());
        DB::rollBack();
        include "../createTeacherAppointment.php";
    }
else
    try {
        DB::beginTransaction();
        Config::get()->requireTeacherReservationEnabled();
        Appointment::deleteByPupilTeacher($pupilId, $teacherId);
        $appointment = Appointment::create($timeSlotId, $teacherId, $pupilId);
        DB::commit();
        Messages::addInfo(I18N::getMessage("teacherAppointments.created"));
        Request::redirect($target);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000)
            Messages::addError(I18N::getMessage("errors.alreadyReserved"));
        else
            Messages::addError($e->getMessage());
        DB::rollBack();
        include "../createTeacherAppointment.php";
    } catch (Exception $e) {
        Messages::addError($e->getMessage());
        DB::rollBack();
        include "../createTeacherAppointment.php";
    }
