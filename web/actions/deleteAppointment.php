<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action deletes an appointment.
 */

require_once "../estap.php";

use PhoolKit\Request;
use PhoolKit\Messages;
use PhoolKit\I18N;
use ESTAP\Session;
use ESTAP\Appointment;
use ESTAP\Config;

$pupilId = $_REQUEST["pupil"];
$teacherId = $_REQUEST["teacher"];

$session = Session::get()->requireParent($pupilId);

try
{
    Config::get()->requireParentReservationEnabled();
    Appointment::deleteByPupilTeacher($pupilId, $teacherId);
    Messages::addInfo(I18N::getMessage("appointments.deleted"));
    Request::redirect("../parents.php");
}
catch (Exception $e)
{
    Messages::addError($e->getMessage());
    include "../parents.php";
}
