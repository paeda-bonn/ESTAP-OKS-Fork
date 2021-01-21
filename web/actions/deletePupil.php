<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action deletes a single pupil.
 */

require_once "../estap.php";

use ESTAP\Pupil;
use ESTAP\Session;
use PhoolKit\I18N;
use PhoolKit\Messages;
use PhoolKit\Request;

Session::get()->requireAdmin();
$pupilId = +$_REQUEST["id"];

try {
    Pupil::deleteById($pupilId);
    Messages::addInfo(I18N::getMessage("pupils.pupilDeleted"));
    Request::redirect("../pupils.php");
} catch (Exception $e) {
    Messages::addError($e->getMessage());
    include "../deletePupil.php";
}
