<?php
/*
 * Copyright 2014 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action deletes a single time slot.
 */

require_once "../estap.php";

use PhoolKit\Request;
use PhoolKit\Messages;
use PhoolKit\I18N;
use ESTAP\Session;
use ESTAP\TimeSlot;

if (isset($_REQUEST["admin"]))
{
    $session = Session::get()->requireAdmin();
    $admin = true;
}
else
{
    $session = Session::get()->requireTeacher();
    $admin = false;
}
$timeSlotId = +$_REQUEST["id"];
$teacherId = +$_REQUEST["teacher"];
try
{
    TimeSlot::deleteById($timeSlotId);
    Messages::addInfo(I18N::getMessage("timeSlots.timeSlotDeleted"));
	if($admin){
		Request::redirect("../editTeacher.php?id=".$teacherId);
	}else{
		Request::redirect("../editTeacher.php");	
	}
}
catch (Exception $e)
{
    Messages::addError($e->getMessage());
	if($admin){
		Request::redirect("../teachers.php");
	}else{
		Request::redirect("../editTeacher.php");	
	}
}
