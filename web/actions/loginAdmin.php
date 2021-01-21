<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action performs an admin login.
 */

require_once "../estap.php";

use ESTAP\Forms\LoginForm;
use ESTAP\Session;
use PhoolKit\Messages;
use PhoolKit\Request;

$form = LoginForm::parse("../loginAdmin.php");

$session = Session::get();
try {
    $session->loginAdmin($form->login, $form->password);
    Request::redirect("../admins.php");
} catch (Exception $e) {
    Messages::addError($e->getMessage());
    include "../loginAdmin.php";
}
