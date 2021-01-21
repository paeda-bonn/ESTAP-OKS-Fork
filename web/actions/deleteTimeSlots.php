<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action deletes all teachers from the database.
 */

require_once "../estap.php";

use ESTAP\Session;
use ESTAP\TimeSlot;
use PhoolKit\I18N;
use PhoolKit\Messages;
use PhoolKit\Request;

$session = Session::get()->requireAdmin();
$date = $_REQUEST["date"];

try {
    if ($date === null) {
        TimeSlot::deleteAll();
    } else {
        TimeSlot::deleteByDate($date);
    }
    Messages::addInfo(I18N::getMessage("timeSlots.deleted"));
    Request::redirect("../timeSlots.php");
} catch (Exception $e) {
    Messages::addError($e->getMessage());
    include "../deleteTimeSlots.php";
}
