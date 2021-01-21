<?php
/*
 * Copyright 2014 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action edits a existing admin.
 */

require_once "../estap.php";

use ESTAP\Admin;
use ESTAP\Forms\EditAdminForm;
use ESTAP\Session;
use PhoolKit\I18N;
use PhoolKit\Messages;
use PhoolKit\Request;

$form = EditAdminForm::parse("../editAdmin.php");
$session = Session::get()->requireAdmin();
try {
    $admin = Admin::getById($form->id);
    $admin->update($form->login, $form->password, $form->firstName,
        $form->lastName);
    Messages::addInfo(I18N::getMessage("admins.adminEdited"));
    Request::redirect("../admins.php");
} catch (PDOException $e) {
    if ($e->getCode() == 23000)
        $form->addError("login", I18N::getMessage("editAdmin.loginAlreadyUsed"));
    else
        Messages::addError($e->getMessage());
    include "../editAdmin.php";
} catch (Exception $e) {
    Messages::addError($e->getMessage());
    include "../editAdmin.php";
}
