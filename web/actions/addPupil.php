<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action adds a new pupil.
 */

require_once "../estap.php";

use PhoolKit\Request;
use PhoolKit\Messages;
use PhoolKit\I18N;
use ESTAP\Pupil;
use ESTAP\Forms\AddPupilForm;
use ESTAP\Session;

$form = AddPupilForm::parse("../addPupil.php");

$session = Session::get()->requireAdmin();
try
{
    Pupil::create($form->login, $form->firstName,
        $form->lastName, $form->class);
    Messages::addInfo(I18N::getMessage("pupils.pupilAdded"));
    Request::redirect("../pupils.php");
}
catch (PDOException $e)
{
    if ($e->getCode() == 23000)
        $form->addError("login", I18N::getMessage("addPupil.loginAlreadyUsed"));
    else 
        Messages::addError($e->getMessage());
    include "../addPupil.php";
}
catch (Exception $e)
{
    Messages::addError($e->getMessage());
    include "../addPupil.php";
}
