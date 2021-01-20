<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 * 
 * This action receives a list of teachers in CSV format and writes them
 * into the database.
 */

require_once "../estap.php";

use PhoolKit\Request;
use PhoolKit\Messages;
use PhoolKit\I18N;
use ESTAP\Teacher;
use ESTAP\Session;
use ESTAP\Utils\DB;

$session = Session::get()->requireAdmin();

try
{
    DB::beginTransaction();
    $filename = $_FILES["teachers"]["tmp_name"];
    if (!$filename) 
        throw new RuntimeException(I18N::getMessage("errors.noFile"));
    $file = fopen($filename, "rt");
    while (($row = fgets($file)) !== false)
    {
        $data = str_getcsv($row, ";");
        if (count($data) != 7)
        {
            throw new RuntimeException(
                I18N::getMessage("errors.invalidTeacherRow", $row)); 
        }
        Teacher::create($data[0], $data[1], $data[3], $data[2], $data[4], $data[5], $data[6]);
    }
    fclose($file);
    DB::commit();
    Messages::addInfo(I18N::getMessage("teachers.uploaded"));
    Request::redirect("../teachers.php");
}
catch (Exception $e)
{
    DB::rollback();
    Messages::addError($e->getMessage());
    include "../teachers.php";
}
