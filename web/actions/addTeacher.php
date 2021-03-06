<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action adds a new teacher.
 */

require_once "../estap.php";

use ESTAP\Forms\AddTeacherForm;
use ESTAP\Session;
use ESTAP\Teacher;
use PhoolKit\I18N;
use PhoolKit\Messages;
use PhoolKit\Request;

$form = AddTeacherForm::parse("../addTeacher.php");

$session = Session::get()->requireAdmin();
try {
    Teacher::create($form->login, $form->password, $form->firstName, $form->lastName, $form->gender, $form->room, $form->vcLink, $form->vcId, $form->vcCode);
    Messages::addInfo(I18N::getMessage("teachers.teacherAdded"));
    Request::redirect("../teachers.php");
} catch (PDOException $e) {
    if ($e->getCode() == 23000)
        $form->addError("login", I18N::getMessage("addTeacher.loginAlreadyUsed"));
    else
        Messages::addError($e->getMessage());
    include "../addTeacher.php";
} catch (Exception $e) {
    Messages::addError($e->getMessage());
    include "../addTeacher.php";
}
