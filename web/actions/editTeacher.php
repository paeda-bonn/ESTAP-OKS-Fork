<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action edits a existing teacher.
 */

require_once "../estap.php";

use PhoolKit\Request;
use PhoolKit\Messages;
use PhoolKit\I18N;
use ESTAP\Teacher;
use ESTAP\Forms\EditTeacherForm;
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


$form = EditTeacherForm::parse("../editTeacher.php");

try
{
    $teacher = Teacher::getById($form->id);
    $teacher->update($form->login, $form->password, $form->firstName, 
        $form->lastName, $form->gender, $form->room);
    Messages::addInfo(I18N::getMessage("teachers.teacherEdited"));
    if ($admin):
      Request::redirect("../teachers.php");
    else:
      Request::redirect("../teacherAppointments.php?changed");
    endif;  
}
catch (PDOException $e)
{
    if ($e->getCode() == 23000)
        $form->addError("login", I18N::getMessage("editTeacher.loginAlreadyUsed"));
    else 
        Messages::addError($e->getMessage());
    include "../editTeacher.php";
}
catch (Exception $e)
{
    Messages::addError($e->getMessage());
    include "../editTeacher.php";
}
