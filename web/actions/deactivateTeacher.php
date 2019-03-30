<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action deactivates a single teacher.
 */

require_once "../estap.php";

use PhoolKit\Request;
use PhoolKit\Messages;
use PhoolKit\I18N;
use ESTAP\Session;
use ESTAP\Teacher;

Session::get()->requireAdmin();
$teacherId = +$_REQUEST["id"];

try
{
    Teacher::setActiveById($teacherId, false);
    Messages::addInfo(I18N::getMessage("teachers.deactivated"));
    Request::redirect("../teachers.php");
}
catch (Exception $e)
{
    Messages::addError($e->getMessage());
    include "../deactivateTeacher.php";
}
