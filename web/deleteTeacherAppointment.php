<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page the deletion of a teacher appointment can be confirmed.
 */

require_once "estap.php";

use ESTAP\Appointment;
use ESTAP\Session;
use ESTAP\Teacher;
use PhoolKit\HTML as h;

if (isset($_REQUEST["teacher"])) {
    $session = Session::get()->requireAdmin();
    $admin = true;
} else {
    $session = Session::get()->requireTeacher();
    $admin = false;
}
$appointmentId = +$_REQUEST["id"];
$appointment = Appointment::getById($appointmentId);
$pupil = $appointment->getPupil();
$timeSlot = $appointment->getTimeSlot();

?>
<?php $pageId = "deleteTeacherAppointment";
include "parts/header.php" ?>
<?php if ($admin): ?>
    <?php include "parts/adminNav.php" ?>
<?php else: ?>
    <?php include "parts/teacherNav.php" ?>
<?php endif ?>
<div id="content">
    <h2><?php h::msg("deleteTeacherAppointment.title") ?></h2>
    <?php h::messages() ?>
    <p>
        <?php h::msg("deleteTeacherAppointment.question") ?>
    </p>
    <dl>
        <dt><?php h::msg("deleteTeacherAppointment.time") ?></dt>
        <dd><?php h::text($timeSlot->getTimeString()) ?></dd>
        <?php if ($admin): ?>
            <dt><?php h::msg("deleteTeacherAppointment.teacher") ?></dt>
            <dd><?php h::text($appointment->getTeacher()->getName(Teacher::GENDER_LAST)) ?></dd>
        <?php endif ?>
        <dt><?php h::msg("deleteTeacherAppointment.pupil") ?></dt>
        <dd><?php h::text($pupil->getName()) ?></dd>
    </dl>

    <div class="buttons">
        <form action="<?php h::url("actions/deleteTeacherAppointment.php") ?>" method="post" novalidate>
            <?php if ($admin): ?>
                <input type="hidden" name="teacher" value="<?php echo $appointment->getTeacherId() ?>"/>
            <?php endif ?>
            <input type="hidden" name="pupil" value="<?php echo $appointment->getPupilId() ?>"/>
            <input type="submit" value="<?php h::msg("deleteTeacherAppointment.confirm") ?>"/>
        </form>
        <?php if ($admin): ?>
            <a href="<?php h::url("teacherAppointments.php?teacher=" . $appointment->getTeacherId()) ?>">
                <?php h::msg("deleteTeacherAppointment.cancel") ?>
            </a>
        <?php else: ?>
            <a href="<?php h::url("teacherAppointments.php") ?>">
                <?php h::msg("deleteTeacherAppointment.cancel") ?>
            </a>
        <?php endif ?>
    </div>
</div>
<?php include "parts/footer.php" ?>
