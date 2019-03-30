<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * The main page for teachers.
 */

require_once "estap.php";

use ESTAP\Session;
use ESTAP\Teacher;
use ESTAP\Config;
use ESTAP\Appointment;
use PhoolKit\HTML as h;
use ESTAP\Pupil;
use PhoolKit\I18N;

if (isset($_REQUEST["teacher"])) 
{
    $session = Session::get()->requireAdmin();
    $teacherId = +$_REQUEST["teacher"];
    $teacher = Teacher::getById($teacherId);
    $admin = true;
}
 else 
 {
    $session = Session::get()->requireTeacher();
    $teacher = $session->getTeacher();
    $teacherId = $teacher->getId();
    if (isset($_REQUEST["changed"])):
        $teacher = Teacher::getById($teacherId);
    endif;
    $admin = false;
}
$pageId = "teacherAppointments";
$pageTitle = I18N::getMessage("teacherAppointments.title", $teacher->getName(Teacher::GENDER_ACC_LAST), $teacher->getRoom());
$config = Config::get();
$isReservationEnabled = ($teacher->isActive() & $config->isTeacherReservationEnabled()) || $admin;

?>
<?php include "parts/header.php" ?>
<?php if ($admin): ?>
    <?php include "parts/adminNav.php" ?>
<?php else: ?>
    <?php include "parts/teacherNav.php" ?>
<?php endif ?>
<div id="content">
    <h2><?php h::msg("teacherAppointments.title", $teacher->getName(Teacher::GENDER_ACC_LAST), $teacher->getRoom()) ?></h2>

    <?php h::messages() ?>

    <?php if ($isReservationEnabled): ?>
        <div class="createAppointment">
            <h3><?php h::msg("teacherAppointments.create.title") ?></h3>
            <p><?php h::msg("teacherAppointments.create.help") ?></p>
            <form action="<?php h::url("createTeacherAppointment.php") ?>" method="get" novalidate>
                <?php if ($admin): ?>
                    <input type="hidden" name="teacher" value="<?php h::text($teacherId) ?>" />
                <?php endif ?>
                <div class="fields">
                    <div class="field">
                        <label for="pupil"><?php h::msg("teacherAppointments.pupil") ?></label>
                        <select id="pupil" name="pupil">
                            <?php foreach (Pupil::getAll() as $pupil): ?>
                                <option value="<?php h::text($pupil->getId()) ?>"><?php h::text($pupil) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="buttons">
                    <input type="submit" value="<?php h::msg("teacherAppointments.requestTimes") ?>" />
                </div>
            </form>
        </div>
    <?php endif ?>

    <?php $appointments = Appointment::getForTeacher($teacherId) ?>

    <?php if ($admin): ?>
        <h3><?php h::msg("teachers.editAtDay") ?></h3>
        <form action="<?php h::url("actions/createLockForDate")?>" method="post">
            <input type="hidden" name="teacher" value="<?php h::text($teacherId) ?>"/>
            <div class="field">
                <select name="date">
                    <?php foreach (\ESTAP\TimeSlot::getDistinctDates(\ESTAP\TimeSlot::getAll()) as $date):?>
                        <?php $dateTime = new DateTime($date);?>
                        <option value="<?php h::text($dateTime->format("Y-m-d")); ?>"><?php h::text($dateTime->format("d.m.Y")) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="buttons">
                <input type="submit" value="<?php h::msg("teachers.blockDay")?>"/>
            </div>
        </form>
    <?php endif; ?>

    <?php if ($appointments): ?>
        <h3><?php h::msg("teacherAppointments.current.title") ?></h3>
        <p class="help">
            <?php if (!$admin): ?>
                <?php if($isReservationEnabled): ?>
                    <?php h::msg("teacherAppointments.current.help", $config->getReservationEndDate(), $config->getReservationEndTime()) ?>
                <?php else: ?>
                    <?php h::msg("teacherAppointments.reservationDisabled") ?>
                <?php endif?>
            <?php endif?>
        </p>

        <table class="appointments">
            <tr>
                <th><?php h::msg("teacherAppointments.time") ?></th>
                <th><?php h::msg("appointments.date") ?></th>
                <th><?php h::msg("teacherAppointments.pupil") ?></th>
                <th><?php h::msg("teacherAppointments.class") ?></th>
                <?php if ($isReservationEnabled): ?>
                    <th class="buttons"><?php h::msg("teacherAppointments.actions") ?></th>
                <?php endif ?>
            </tr>
            <?php $conflicts = false ?>
            <?php foreach ($appointments as $appointment): ?>
                <?php $timeSlot = $appointment->getTimeSlot() ?>
                <tr class="<?php echo $appointment->isReserved() ? $appointment->isLocked() ? "locked" : "reserved" : "free" ?>">
                    <td>
                        <?php h::text($timeSlot->getTimeString()) ?>
                    </td>
                    <td>
                        <?php $dateTime = new DateTime($timeSlot->getDate());
                        h::text($dateTime->format("d.m.Y")) ?>
                    </td>
                    <?php if ($appointment->isReserved()): ?>
                        <?php if ($appointment->isLocked()): ?>
                            <td class="status" colspan="2"><?php h::msg("teacherAppointments.locked") ?></td>
                            <?php if ($isReservationEnabled): ?>
                                <td class="buttons">
                                    <form action="<?php h::url("actions/deleteLock.php") ?>" method="post">
                                        <?php if ($admin): ?>
                                            <input type="hidden" name="teacher" value="<?php h::text($teacherId) ?>" />
                                        <?php endif ?>
                                        <input type="hidden" name="timeSlot" value="<?php h::text($appointment->getTimeSlotId()) ?>" />
                                        <input type="submit" value="<?php h::msg("teacherAppointments.deleteLock") ?>" />
                                    </form>
                                </td>
                            <?php endif ?>
                        <?php else: ?>
                            <td>
                                <?php h::text($appointment->getPupil()->getName()) ?>
                            </td>
                            <td>
                                <?php h::text($appointment->getPupil()->getClass()) ?>
                            </td>
                            <?php if ($isReservationEnabled): ?>
                                <td class="buttons">
                                    <?php if ($admin): ?>
                                        <a href="<?php h::url(sprintf("createTeacherAppointment.php?pupil=%d&teacher=%d", $appointment->getPupilId(), $teacherId)) ?>">
                                            <?php h::msg("teacherAppointments.change") ?>
                                        </a>
                                        <a href="<?php h::url(sprintf("deleteTeacherAppointment.php?id=%d&teacher=%d", $appointment->getId(), $teacherId)) ?>">
                                            <?php h::msg("teacherAppointments.delete") ?>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php h::url(sprintf("createTeacherAppointment.php?pupil=%d", $appointment->getPupilId())) ?>">
                                            <?php h::msg("teacherAppointments.change") ?>
                                        </a>
                                        <a href="<?php h::url(sprintf("deleteTeacherAppointment.php?id=%d", $appointment->getId())) ?>">
                                            <?php h::msg("teacherAppointments.delete") ?>
                                        </a>
                                    <?php endif ?>
                                </td>
                            <?php endif ?>
                        <?php endif ?>
                    <?php else: ?>
                        <td class="status" colspan="2"><?php h::msg("teacherAppointments.free") ?></td>
                        <?php if ($isReservationEnabled): ?>
                            <td class="buttons">
                                <form action="<?php h::url("actions/createLock.php") ?>" method="post">
                                    <?php if ($admin): ?>
                                        <input type="hidden" name="teacher" value="<?php h::text($teacherId) ?>" />
                                    <?php endif ?>
                                    <input type="hidden" name="timeSlot" value="<?php h::text($appointment->getTimeSlotId()) ?>" />
                                    <input type="submit" value="<?php h::msg("teacherAppointments.createLock") ?>" />
                                </form>
                            </td>
                        <?php endif ?>
                    <?php endif ?>
                </tr>
            <?php endforeach ?>
        </table>
        <div class="print">
            <script>
                document.write('<button onclick="javascript:print()"> <?php h::msg("teacherAppointments.print") ?></button>');
            </script>
            <noscript> <!--
        <p>
          <?php h::msg("teacherAppointments.print.noJavaScript") ?>
        </p> -->
            </noscript>
        </div>
        <div class="buttons">
            <?php if ($admin): ?>
                <a href="<?php h::url(sprintf("printTeacherAppointments.php?teacher=%d&admin=%d", $teacherId, 1)) ?>" target="_blank">
                    <?php h::msg("teacherAppointments.printPDF") ?>
                </a>
            <?php else: ?>
                <a href="<?php h::url(sprintf("printTeacherAppointments.php?teacher=%d", $teacherId)) ?>" target="_blank">
                    <?php h::msg("teacherAppointments.printPDF") ?>
                </a>
            <?php endif ?>
        </div>
    <?php endif ?>

</div>
<?php include "parts/footer.php" ?>
