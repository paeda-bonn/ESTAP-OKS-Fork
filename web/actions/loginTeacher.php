<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action performs an teacher login.
 */

require_once "../estap.php";

use ESTAP\Config;
use ESTAP\Forms\LoginForm;
use ESTAP\Session;
use PhoolKit\Messages;
use PhoolKit\Request;

$form = LoginForm::parse("../loginTeacher.php");

$session = Session::get();
try {
    Config::get()->requireTeacherLoginEnabled();
    $session->loginTeacher($form->login, $form->password);
    Request::redirect("../teacherAppointments.php");
} catch (Exception $e) {
    Messages::addError($e->getMessage());
    include "../loginTeacher.php";
}
