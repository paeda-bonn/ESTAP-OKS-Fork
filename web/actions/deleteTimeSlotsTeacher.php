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
$teacherID = htmlspecialchars($_GET["teacher"]);
try
{
    TimeSlot::deleteTeacher($teacherID);    
    Messages::addInfo(I18N::getMessage("timeSlots.deleted"));
    if($admin){
       Request::redirect("../editTeacher.php?id=".$teacherID); 
    }else{
        Request::redirect("../editTeacher.php");
    }
    
}
catch (Exception $e)
{
    Messages::addError($e->getMessage());
    include "../deleteTimeSlotsTeacher.php";
}
