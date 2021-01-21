<?php
/*
 * Copyright 2014 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action deletes a single time slot.
 */

require_once "../estap.php";

use ESTAP\Session;
use ESTAP\TimeSlot;
use PhoolKit\I18N;
use PhoolKit\Messages;
use PhoolKit\Request;

Session::get()->requireAdmin();
$timeSlotId = +$_REQUEST["id"];

try {
    TimeSlot::deleteById($timeSlotId);
    Messages::addInfo(I18N::getMessage("timeSlots.timeSlotDeleted"));
    Request::redirect("../timeSlots.php");
} catch (Exception $e) {
    Messages::addError($e->getMessage());
    include "../deleteTimeSlot.php";
}
