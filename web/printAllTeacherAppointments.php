<?php
/*
 * Copyright 2014, Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page the admin can print all teacher appointments as PDF.
 *
 * author Peter Engels
 *
 */

require_once "estap.php";
require_once "ESTAP/estapPDF.php";

use ESTAP\Appointment;
use ESTAP\Session;
use ESTAP\Teacher;
use PhoolKit\I18N;

$session = Session::get()->requireAdmin();
$teachers = Teacher::getAll();

$pdf = new PDF();

// Column headings
$header = array(utf8_decode(I18N::getMessage("printPDF.time")), utf8_decode(I18N::getMessage("printPDF.date")), utf8_decode(I18N::getMessage("printPDF.pupil")), utf8_decode(I18N::getMessage("printPDF.class")));
$pdf->SetFont('Arial', '', 9);
$lines = array();
$data = array();

if ($teachers) {
    foreach ($teachers as $teacher) {
        if ($teacher->isActive()) {
            $pdf->headLine = sprintf(I18N::getMessage("printTeacher.headLine"), $teacher->getName(Teacher::GENDER_ACC_LAST), $teacher->getRoom());
            $pdf->AddPage();
            $appointments = Appointment::getForTeacher($teacher->getId());
            $i = 0;
            foreach ($appointments as $appointment) {
                $lines[0] = $appointment->getTimeSlot()->getTimeString();
                $lines[1] = $appointment->getTimeSlot()->getDateString();
                if (!$appointment->isReserved()) {
                    $lines[2] = I18N::getMessage("printTeacher.free");
                    $lines[3] = "";
                } elseif ($appointment->isLocked()) {
                    $lines[2] = I18N::getMessage("printTeacher.locked");
                    $lines[3] = "";
                } else {
                    $lines[2] = utf8_decode($appointment->getPupil()->getName());
                    $lines[3] = $appointment->getPupil()->getClass();
                }
                $data[$i] = $lines;
                unset($lines);
                $i++;
            }
            $pdf->AppointmentTable($header, $data);
            unset($data);
            unset($appointments);
        }
    }
    unset($teachers);
    $pdf->Output('PDF.pdf', 'I');
}
?>
