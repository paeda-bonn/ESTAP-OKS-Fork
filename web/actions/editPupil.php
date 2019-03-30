<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action edits a existing pupil.
 */

require_once "../estap.php";

use PhoolKit\Request;
use PhoolKit\Messages;
use PhoolKit\I18N;
use ESTAP\Pupil;
use ESTAP\Forms\EditPupilForm;
use ESTAP\Session;

$form = EditPupilForm::parse("../editPupil.php");
$session = Session::get()->requireAdmin();
try
{
    $pupil = Pupil::getById($form->id);
    $pupil->update($form->login, $form->password, $form->firstName, 
        $form->lastName, $form->class);
    Messages::addInfo(I18N::getMessage("pupils.pupilEdited"));
    Request::redirect("../pupils.php");
}
catch (PDOException $e)
{
    if ($e->getCode() == 23000)
        $form->addError("login", I18N::getMessage("editPupil.loginAlreadyUsed"));
    else 
        Messages::addError($e->getMessage());
    include "../editPupil.php";
}
catch (Exception $e)
{
    Messages::addError($e->getMessage());
    include "../editPupil.php";
}
