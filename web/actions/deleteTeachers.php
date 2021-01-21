<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action deletes all teachers from the database.
 */

require_once "../estap.php";

use ESTAP\Session;
use ESTAP\Teacher;
use PhoolKit\I18N;
use PhoolKit\Messages;
use PhoolKit\Request;

$session = Session::get()->requireAdmin();

try {
    Teacher::deleteAll();
    Messages::addInfo(I18N::getMessage("teachers.deleted"));
    Request::redirect("../teachers.php");
} catch (Exception $e) {
    Messages::addError($e->getMessage());
    include "../deleteTeachers.php";
}
