<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 *
 * The admin page for pupil appointments.
 */

require_once "estap.php";

use ESTAP\Appointment;
use ESTAP\Config;
use ESTAP\Pupil;
use ESTAP\Session;
use ESTAP\Teacher;
use PhoolKit\HTML as h;

$config = Config::get();
$session = Session::get()->requireAdmin();
$pupilId = array();
$pupilId[] = +$_REQUEST["pupil"];
$pupil = Pupil::getById($pupilId[0]);

?>
<?php $pageId = "appointments";
include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
    <h2><?php h::msg("appointments.title") ?></h2>

    <?php h::messages() ?>
    <h2><?php h::text($pupil->getName() . ', ' . $pupil->getClass()) ?></h2>

    <?php $appointments = Appointment::getForPupils($pupilId) ?>
    <?php if ($appointments): ?>

        <table class="appointments">
            <tr>
                <th><?php h::msg("appointments.time") ?></th>
                <th><?php h::msg("appointments.teacher") ?></th>
                <!--
        <th><?php h::msg("appointments.room") ?></th>
        -->
            </tr>
            <?php $conflicts = false ?>
            <?php foreach ($appointments as $appointment): ?>
                <?php $timeSlot = $appointment->getTimeSlot() ?>
                <?php $conflict = Appointment::isConflict($appointment, $appointments) ?>
                <?php $conflicts |= $conflict ?>
                <tr <?php if ($conflict): ?>class="conflict"<?php endif ?>>
                    <td>
                        <?php h::text($timeSlot->getTimeString()) ?>
                    </td>
                    <td>
                        <?php h::text($appointment->getTeacher()->getName(Teacher::GENDER_LAST)) ?>
                    </td>
                    <!--
          <td>
            <?php h::text($appointment->getTeacher()->getRoom()) ?>
          </td>
          -->
                </tr>
            <?php endforeach ?>
        </table>
        <?php if ($conflicts): ?>
            <p class="conflicts">
                <?php h::msg("appointments.conflicts") ?>
            </p>
        <?php endif ?>
        <div class="print">
            <script>
                document.write('<button onclick="javascript:print()"><?php h::msg("appointments.print") ?></button>');
            </script>
            <noscript> <!--
        <p>
          <?php h::msg("appointments.print.noJavaScript") ?>
        </p> -->
            </noscript>
        </div>
        <div class="buttons">
            <a href="<?php h::url("printPupilAppointments.php?pupil=") . h::text($pupilId[0]) ?>" target="_blank">
                <?php h::msg("appointments.printPDF") ?>
            </a>
            <a href="<?php h::url("pupils.php") ?>">
                <?php h::msg("appointments.cancel") ?>
            </a>
        </div>
    <?php else: ?>
        <p>
            <?php h::msg("appointments.nothingFound") ?>
        </p>
        <div class="buttons">
            <a href="<?php h::url("pupils.php") ?>">
                <?php h::msg("appointments.cancel") ?>
            </a>
        </div>
    <?php endif ?>

</div>
<?php include "parts/footer.php" ?>
