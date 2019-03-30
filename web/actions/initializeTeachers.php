<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action initializes all teachers in the database.
 */

require_once "../estap.php";

use PhoolKit\Request;
use PhoolKit\Messages;
use PhoolKit\I18N;
use ESTAP\Teacher;
use ESTAP\Appointment;
use ESTAP\Session;

$session = Session::get()->requireAdmin();

try
{
    Teacher::activateAll();
    Appointment::deleteAll();
    Messages::addInfo(I18N::getMessage("teachers.initialized"));
    Request::redirect("../teachers.php");
}
catch (Exception $e)
{
    Messages::addError($e->getMessage());
    include "../initializeTeachers.php";
}
