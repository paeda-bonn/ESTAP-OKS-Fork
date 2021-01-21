<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action deletes all pupils from the database.
 */

require_once "../estap.php";

use ESTAP\Pupil;
use ESTAP\Session;
use PhoolKit\I18N;
use PhoolKit\Messages;
use PhoolKit\Request;

$session = Session::get()->requireAdmin();

try {
    Pupil::deleteAll();
    Messages::addInfo(I18N::getMessage("pupils.deleted"));
    Request::redirect("../pupils.php");
} catch (Exception $e) {
    Messages::addError($e->getMessage());
    include "../deletePupils.php";
}
