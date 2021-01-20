<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 *
 * The main page for parents.
 */

require_once "estap.php";

use ESTAP\Session;
use ESTAP\Teacher;
use ESTAP\Config;
use ESTAP\Appointment;
use PhoolKit\HTML as h;

$session = Session::get()->requireParent();
$config = Config::get();

?>
<?php $pageId = "appointments"; include "parts/header.php" ?>
<div id="content">
  <h2><?php h::msg("appointments.title") ?></h2>

  <?php h::messages() ?>

  <?php if ($config->isParentReservationEnabled()): ?>
    <div class="createAppointment">
      <h3><?php h::msg("appointments.create.title") ?></h3>
      <p><?php h::msg("appointments.create.help") ?></p>
      <form action="<?php h::url("createAppointment.php") ?>" method="get" novalidate>
        <div class="fields">
          <div class="field">
            <label for="pupil"><?php h::msg("appointments.pupil") ?></label>
            <select id="pupil" name="pupil">
              <?php foreach ($session->getPupils() as $pupil): ?>
                <option value="<?php h::text($pupil->getId()) ?>"><?php h::text($pupil->getName()) ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="field">
            <label for="teacher"><?php h::msg("appointments.teacher") ?></label>
            <select id="teacher" name="teacher">
              <?php foreach (Teacher::getAll() as $teacher): ?>
                <?php if ($teacher->isActive()): ?>
                  <option value="<?php h::text($teacher->getId()) ?>"><?php h::text($teacher->getName(Teacher::GENDER_LAST)) ?></option>
                <?php endif; ?>
              <?php endforeach ?>
            </select>
          </div>
        </div>
        <div class="buttons">
          <input type="submit" value="<?php h::msg("appointments.requestTimes") ?>" />
        </div>
      </form>
    </div>
  <?php endif ?>

  <?php $appointments = Appointment::getForPupils($session->getPupilIds()) ?>
  <?php if ($appointments): ?>
    <h3><?php h::msg("appointments.current.title") ?></h3>
    <p class="help">
      <?php if ($config->isParentReservationEnabled()): ?>
        <?php h::msg("appointments.current.help", $config->getReservationEndDate(), $config->getReservationEndTime()) ?>
      <?php else: ?>
        <?php h::msg("appointments.reservationDisabled") ?>
        <?php if ($config->isReservationEnabled() & $config->isBeforeStartTime()): ?>
        <br>
          <?php h::msg("appointments.reservationStart", $config->getReservationStartDate(), $config->getReservationStartTime()) ?>
        <?php endif ?>
      <?php endif ?>
    </p>

    <table class="appointments">
      <tr>
        <th><?php h::msg("appointments.time") ?></th>
        <th><?php h::msg("appointments.date")?></th>
        <th><?php h::msg("appointments.teacher") ?></th>
        <th><?php h::msg("appointments.pupil") ?></th>
        <th><?php h::msg("appointments.room") ?></th>
          <!-- //TODO lang -->
        <th>VCLink</th>
        <?php if ($config->isParentReservationEnabled()): ?>
          <th><?php h::msg("appointments.actions") ?></th>
        <?php endif ?>
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
          	<?php $dateTime = new DateTime($timeSlot->getDate()); h::text($dateTime->format("d.m.Y"))?>
          </td>
          <td>
            <?php h::text($appointment->getTeacher()->getName(Teacher::GENDER_LAST)) ?>
          </td>
          <td>
            <?php h::text($appointment->getPupil()->getName()) ?>
          </td>
          <td>
            <?php h::text($appointment->getTeacher()->getRoom()) ?>
          </td>
          <td>
            <?php h::text($appointment->getTeacher()->getVCLink()) ?>
          </td>
          <?php if ($config->isParentReservationEnabled()): ?>
            <td class="buttons">
              <a href="<?php h::url("createAppointment.php?pupil=" . $appointment->getPupilId() . "&teacher=" . $appointment->getTeacherId())?>">
                <?php h::msg("appointments.change") ?>
              </a>
              <a href="<?php h::url("deleteAppointment.php?id=" . $appointment->getId()) ?>">
                <?php h::msg("appointments.delete") ?>
              </a>
            </td>
          <?php endif ?>
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
      <a href="<?php h::url("printParentAppointments.php") ?>" target="_blank">
      <?php h::msg("appointments.printPDF") ?>
      </a>
    </div>
  <?php else: ?>
    <?php if (!$config->isParentReservationEnabled()): ?>
      <?php h::msg("appointments.reservationDisabled") ?>
      <?php if ($config->isReservationEnabled() & $config->isBeforeStartTime()): ?>
<<<<<<< HEAD
      <br>
=======
        <br>
>>>>>>> parent of edaf919... Revert "no message"
        <?php h::msg("appointments.reservationStart", $config->getReservationStartDate(), $config->getReservationStartTime()) ?>
      <?php endif ?>
    <?php endif ?>
  <?php endif ?>

</div>
<?php include "parts/footer.php" ?>
