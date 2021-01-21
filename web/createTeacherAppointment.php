<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page a teacher can reserve a time slot for a pupil.
 */

require_once "estap.php";

use ESTAP\Appointment;
use ESTAP\Config;
use ESTAP\Pupil;
use ESTAP\Session;
use ESTAP\Teacher;
use PhoolKit\HTML as h;

if (isset($_REQUEST["teacher"])) {
    $session = Session::get()->requireAdmin();
    $teacherId = +$_REQUEST["teacher"];
    $teacher = Teacher::getById($teacherId);
    $admin = true;
} else {
    $session = Session::get()->requireTeacher();
    $teacher = $session->getTeacher();
    $teacherId = $teacher->getId();
    $admin = false;
}
$pupilId = $_REQUEST["pupil"];
$pupil = Pupil::getById($pupilId);

?>
<?php $pageId = "createTeacherAppointment";
include "parts/header.php" ?>
<?php if ($admin): ?>
    <?php include "parts/adminNav.php" ?>
<?php else: ?>
    <?php include "parts/teacherNav.php" ?>
<?php endif ?>
<div id="content">
    <h2><?php h::msg("createTeacherAppointment.title") ?></h2>

    <?php h::messages() ?>

    <p class="help"><?php h::msg("createTeacherAppointment.help") ?></p>

    <form action="<?php h::url("actions/createTeacherAppointment.php") ?>" method="post" novalidate>
        <input type="hidden" name="pupil" value="<?php echo $pupilId ?>"/>
        <div class="buttons">
            <input type="submit" value="<?php h::msg("createTeacherAppointment.reserve") ?>"/>
            <?php if ($admin): ?>
                <input type="hidden" name="teacher" value="<?php echo $teacherId ?>"/>
                <a href="<?php h::url("teacherAppointments.php?teacher=" . $teacherId) ?>">
                    <?php h::msg("createTeacherAppointment.cancel") ?>
                </a>
            <?php else: ?>
                <a href="<?php h::url("teacherAppointments.php") ?>">
                    <?php h::msg("createTeacherAppointment.cancel") ?>
                </a>
            <?php endif ?>
        </div>
        <dl>
            <?php if ($admin): ?>
                <dt><?php h::msg("createTeacherAppointment.teacher") ?></dt>
                <dd><?php h::text($teacher->getName()) ?> </dd>
            <?php endif ?>
            <dt><?php h::msg("createTeacherAppointment.pupil") ?></dt>
            <dd><?php h::text($pupil->getName()) ?> </dd>
        </dl>
        <table>
            <tr>
                <th class="buttons"></th>
                <th><?php h::msg("createTeacherAppointment.time") ?></th>
                <th><?php h::msg("createTeacherAppointment.date") ?></th>
                <th><?php h::msg("createTeacherAppointment.status") ?></th>
            </tr>
            <?php if (Config::get()->isDuplicatesEnabled()): ?>
                <?php $appointments = Appointment::getForTeacher($teacherId) ?>
            <?php else: ?>
                <?php $appointments = Appointment::getForTeacherAndPupil($teacherId, $pupilId) ?>
            <?php endif; ?>
            <?php $selected = Appointment::getSelected($appointments, $pupilId, $teacherId) ?>
            <?php foreach ($appointments as $appointment): ?>
                <?php $timeSlot = $appointment->getTimeSlot() ?>
                <tr class="<?php echo $appointment->isReserved() ? "reserved" : "free" ?> <?php if ($appointment->isReservedTo($pupilId, $teacherId)): ?>own<?php endif ?>">
                    <td>
                        <?php if (!$appointment->isReserved() || $appointment->isReservedTo($pupilId, $teacherId)): ?>
                            <input id="timeSlot-<?php echo $appointment->getTimeSlotId() ?>" type="radio" name="timeSlot" value="<?php echo $timeSlot->getId() ?>" <?php if ($selected == $appointment): ?>checked<?php endif ?> />
                        <?php endif ?>
                    </td>
                    <td>
                        <label for="timeSlot-<?php echo $appointment->getTimeSlotId() ?>"><?php h::text($timeSlot->getTimeString()) ?></label>
                    </td>
                    <td>
                        <?php $dateTime = new DateTime($timeSlot->getDate());
                        h::text($dateTime->format("d.m.Y")) ?>
                    </td>
                    <td class="buttons">
                        <?php if ($appointment->isReserved()): ?>
                            <?php if ($appointment->isReservedTo($pupilId, $teacherId)): ?>
                                <?php h::msg("createTeacherAppointment.current") ?>
                            <?php else: ?>
                                <?php h::msg("createTeacherAppointment.reserved") ?>
                            <?php endif ?>
                        <?php else: ?>
                            <?php h::msg("createTeacherAppointment.free") ?>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </form>

</div>
<?php include "parts/footer.php" ?>
