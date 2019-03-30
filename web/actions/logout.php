<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */


use PhoolKit\Request;
use ESTAP\Session;

require_once "../estap.php";

Session::get()->logout();
Request::redirect("..");
