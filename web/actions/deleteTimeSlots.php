<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action deletes all teachers from the database.
 */

require_once "../estap.php";

use PhoolKit\Request;
use PhoolKit\Messages;
use PhoolKit\I18N;
use ESTAP\TimeSlot;
use ESTAP\Session;

$session = Session::get()->requireAdmin();

try
{
    TimeSlot::deleteAll();    
    Messages::addInfo(I18N::getMessage("timeSlots.deleted"));
    Request::redirect("../timeSlots.php");
}
catch (Exception $e)
{
    Messages::addError($e->getMessage());
    include "../deleteTimeSlots.php";
}
