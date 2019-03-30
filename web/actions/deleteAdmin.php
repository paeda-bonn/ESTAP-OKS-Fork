<?php
/*
 * Copyright 2014 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action deletes a single admin.
 */

require_once "../estap.php";

use PhoolKit\Request;
use PhoolKit\Messages;
use PhoolKit\I18N;
use ESTAP\Session;
use ESTAP\Admin;

$session = Session::get()->requireAdmin();
$adminId = +$_REQUEST["id"];

if ($adminId == $session->getAdmin()->getId())
{
    Messages::addInfo(I18N::getMessage("admins.cantDeleteYourself"));
    Request::redirect("../admins.php");
}

try
{
    Admin::deleteById($adminId);
    Messages::addInfo(I18N::getMessage("admins.adminDeleted"));
    Request::redirect("../admins.php");
}
catch (Exception $e)
{
    Messages::addError($e->getMessage());
    include "../deleteAdmin.php";
}
