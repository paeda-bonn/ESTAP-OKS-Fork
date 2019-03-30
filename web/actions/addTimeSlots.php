<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action adds a bunch of new time slots.
 */

require_once "../estap.php";

use PhoolKit\Request;
use PhoolKit\Messages;
use PhoolKit\I18N;
use ESTAP\Forms\AddTimeSlotsForm;
use ESTAP\Session;
use ESTAP\TimeSlot;
use ESTAP\Utils\DB;

$form = AddTimeSlotsForm::parse("../timeSlots.php");

$session = Session::get()->requireAdmin();
try
{
    DB::beginTransaction();
    $startTime = $form->startHour * 60 + $form->startMinute;
    $endTime = $form->endHour * 60 + $form->endMinute;
    for ($i = $startTime; $i < $endTime; $i += $form->duration)
    {
        $timeSlot = TimeSlot::create($i, $i + $form->duration);
    }
    DB::commit();
    Messages::addInfo(I18N::getMessage("timeSlots.created"));
    Request::redirect("../timeSlots.php");    
}
catch (Exception $e)
{
    DB::rollBack();
    Messages::addError($e->getMessage());
    include "../timeSlots.php";
}
