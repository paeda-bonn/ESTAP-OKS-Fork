<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action adds an appointment to the database.
 */

require_once "../estap.php";

use PhoolKit\Request;
use PhoolKit\Messages;
use PhoolKit\I18N;
use ESTAP\Session;
use ESTAP\Utils\DB;
use ESTAP\Appointment;
use ESTAP\Config;

$teacherId = +$_REQUEST["teacher"];
$pupilId = +$_REQUEST["pupil"];
$timeSlotId = +$_REQUEST["timeSlot"];
$session = Session::get()->requireParent($pupilId);
try
{
    DB::beginTransaction();
    Config::get()->requireParentReservationEnabled();
    Appointment::deleteByPupilTeacher($pupilId, $teacherId);
    $appointment = Appointment::create($timeSlotId, $teacherId, $pupilId);
    DB::commit();
    Messages::addInfo(I18N::getMessage("appointments.created"));
    Request::redirect("../parents.php");    
}
catch (PDOException $e)
{
    if ($e->getCode() == 23000)
        Messages::addError(I18N::getMessage("errors.alreadyReserved"));
    else 
        Messages::addError($e->getMessage());
    DB::rollBack();
    include "../createAppointment.php";
}
catch (Exception $e)
{
    Messages::addError($e->getMessage());
    DB::rollBack();
    include "../createAppointment.php";
}
