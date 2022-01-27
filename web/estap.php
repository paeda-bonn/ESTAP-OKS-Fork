<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * This is the ESTAP bootstrap script which must be included from all views
 * and actions.
 */

require "config.php";

use PhoolKit\I18N;
use PhoolKit\Request;

call_user_func(function () {
    // Install class autoloader.
    spl_autoload_register(function ($className) {
        $className = preg_replace("/\\\\/", "/", $className);
        return include_once "$className.php";
    });

    // Determine the base directory
    $baseDir = dirname(__FILE__);

    // Setup the include path
    set_include_path("$baseDir/library" . PATH_SEPARATOR . get_include_path());

    // Setup request
    Request::setBaseDir($baseDir);

    // Load messages for browser locale
    $config = ESTAP\Config::get();
    $locale = Request::getLocale($config->getLocales(),
        $config->getDefaultLocale());
    I18N::loadMessages("$baseDir/messages/estap_$locale.php");

    // Start the session
    session_start();
});
