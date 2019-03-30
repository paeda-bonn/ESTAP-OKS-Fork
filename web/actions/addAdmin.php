<?php
/*
 * Copyright 2014 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action adds a new admin.
 */

require_once "../estap.php";

use PhoolKit\Request;
use PhoolKit\Messages;
use PhoolKit\I18N;
use ESTAP\Admin;
use ESTAP\Forms\AddAdminForm;
use ESTAP\Session;

$form = AddAdminForm::parse("../addAdmin.php");

$session = Session::get()->requireAdmin();
try
{
    Admin::create($form->login, $form->password, $form->firstName, 
        $form->lastName);
    Messages::addInfo(I18N::getMessage("admins.adminAdded"));
    Request::redirect("../admins.php");
}
catch (PDOException $e)
{
    if ($e->getCode() == 23000)
        $form->addError("login", I18N::getMessage("addAdmin.loginAlreadyUsed"));
    else 
        Messages::addError($e->getMessage());
    include "../addAdmin.php";
}
catch (Exception $e)
{
    Messages::addError($e->getMessage());
    include "../addAdmin.php";
}
