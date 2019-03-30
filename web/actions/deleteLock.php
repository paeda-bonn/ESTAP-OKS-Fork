<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action deletes a lock to the database.
 */

require_once "../estap.php";

use PhoolKit\Request;
use PhoolKit\Messages;
use PhoolKit\I18N;
use ESTAP\Session;
use ESTAP\Appointment;

$timeSlotId = +$_REQUEST["timeSlot"];
if (isset($_REQUEST["teacher"]))
{
    $session = Session::get()->requireAdmin();
    $teacherId = +$_REQUEST["teacher"];
    $target = "../teacherAppointments.php?teacher=$teacherId";
}
else
{
    $session = Session::get()->requireTeacher();
    $teacherId = $session->getTeacher()->getId(); 
    $target = "../teacherAppointments.php";
}

try
{
    Appointment::deleteBreak($timeSlotId, $teacherId, null);
    Messages::addInfo(I18N::getMessage("teacherAppointments.lockDeleted"));
    Request::redirect($target);    
}
catch (Exception $e)
{
    Messages::addError($e->getMessage());
    include "../teacherAppointments.php";
}
