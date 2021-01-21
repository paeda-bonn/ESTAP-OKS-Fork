<?php
/*
 * Copyright 2014 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 *
 * This action saves the custom CSS.
 */

require_once "../estap.php";

use ESTAP\Config;
use ESTAP\Exceptions\ConfigException;
use ESTAP\Session;
use PhoolKit\I18N;
use PhoolKit\Messages;
use PhoolKit\Request;

$session = Session::get()->requireAdmin();
$config = Config::get();

try {
    $config->setStyles(Request::getParam("css", ""));
    $config->save();
    Messages::addInfo(I18N::getMessage("styles.saved"));
    Request::redirect("../styles.php");
} catch (ConfigException $e) {
    Messages::addError(I18N::getMessage("settings.cantWriteStyles"));
    include "../styles.php";
} catch (Exception $e) {
    Messages::addError($e->getMessage());
    include "../styles.php";
}
