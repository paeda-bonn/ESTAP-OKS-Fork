<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 * 
 * This action performs a parent login.
 */

require_once "../estap.php";

use PhoolKit\Request;
use PhoolKit\Messages;
use PhoolKit\I18N;
use ESTAP\Forms\LoginForm;
use ESTAP\Session;
use ESTAP\Config;

$form = LoginForm::parse("../login.php");

$session = Session::get();
try
{
    Config::get()->requireParentLoginEnabled();
    $pupil = $session->loginParent($form->login, $form->password);
    if ($form->another)
    {
        Messages::addInfo(I18N::getMessage("login.loggedIn", 
            $pupil->getName())); 
        Request::redirect("../login.php");
    }
    else
    {
        Request::redirect("../parents.php");
    }
}

catch (Exception $e)
{
    Messages::addError($e->getMessage());
    include "../login.php";
}
