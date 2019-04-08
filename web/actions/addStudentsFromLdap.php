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

$session = Session::get()->requireAdmin();
function loadLdapData(){
    $json = file_get_contents(API_HOST."/user.php?user=getStudents&secret=".API_SECRET);
    $data = json_decode($json,true);
    return $data;
}

$data = loadLdapData();

foreach ($data as $student){
    try{
        Pupil::create($student["samaccountname"], $student["givenname"], $student["sn"],$student["class"]);
    }
    catch (PDOException $e){
        echo "error with: ".$student["samaccountname"]."<br>";
    }
}
?>
    <a href="../pupils.php">
        Zur Übersicht
    </a>
