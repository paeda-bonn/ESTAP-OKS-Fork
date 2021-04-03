<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action edits a existing teacher.
 */

require_once "../estap.php";

use ESTAP\Forms\EditTeacherForm;
use ESTAP\Session;
use ESTAP\Teacher;
use ESTAP\TimeSlot;
use PhoolKit\I18N;
use PhoolKit\Messages;
use PhoolKit\Request;

if (isset($_REQUEST["admin"])) {
    $session = Session::get()->requireAdmin();
    $admin = true;
} else {
    $session = Session::get()->requireTeacher();
    $admin = false;
}


$form = EditTeacherForm::parse("../editTeacher.php");

try {
    $teacher = Teacher::getById($form->id);
    $teacher->update($form->login, $form->password, $form->firstName, $form->lastName, $form->gender, $form->room, $form->vConferenceLink, $form->vConferenceId, $form->vConferencePass);
    $startTime = $form->startHour * 60 + $form->startMinute;
    $endTime = $form->endHour * 60 + $form->endMinute;


    $date = $form->year . "-" . $form->month . "-" . $form->day;


    for ($i = $startTime; $i < $endTime; $i += $form->duration) {
        if ($i == $startTime) {
            //$timeSlot = TimeSlot::createTeacher($i, $i + $form->duration,$form->id,false,"");
        }
        $timeSlot = TimeSlot::createTeacher($i, $i + $form->duration, $form->id, false, $date);
    }
    Messages::addInfo(I18N::getMessage("teachers.teacherEdited"));
    if ($admin):
        Request::redirect("../teachers.php");
    else:
        Request::redirect("../teacherAppointments.php?changed");
    endif;
} catch (PDOException $e) {
    if ($e->getCode() == 23000)
        $form->addError("login", I18N::getMessage("editTeacher.loginAlreadyUsed"));
    else
        Messages::addError($e->getMessage());
    include "../editTeacher.php";
} catch (Exception $e) {
    Messages::addError($e->getMessage());
    include "../editTeacher.php";
}
