<?php 
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page the parents can reserve a time slot of a teacher.
 */

require_once "estap.php";

use ESTAP\Session;
use ESTAP\Teacher;
use ESTAP\Appointment;
use PhoolKit\HTML as h;

$pupilId = $_REQUEST["pupil"];
$teacherId = $_REQUEST["teacher"]; 
$session = Session::get()->requireParent($pupilId);
$pupil = $session->getPupil($pupilId);
$teacher = Teacher::getById($teacherId);

?>
<?php $pageId = "createAppointment"; include "parts/header.php" ?>
<div id="content">
  <h2><?php h::msg("createAppointment.title") ?></h2>
  
  <?php h::messages() ?>
  
  <p class="help"><?php h::msg("createAppointment.help") ?></p>

  <form action="<?php h::url("actions/createAppointment.php") ?>" method="post" novalidate>
    <input type="hidden" name="teacher" value="<?php echo $teacher->getId() ?>" />
    <input type="hidden" name="pupil" value="<?php echo $pupil->getId() ?>" />
    <div class="buttons">
      <input type="submit" value="<?php h::msg("createAppointment.reserve") ?>" />
      <a href="<?php h::url("parents.php") ?>">
        <?php h::msg("createAppointment.cancel") ?>
      </a>
    </div>
    <dl>
      <dt><?php h::msg("createAppointment.pupil") ?></dt>
      <dd><?php h::text($pupil->getName()) ?> </dd>
      <dt><?php h::msg("createAppointment.teacher") ?></dt>
      <dd><?php h::text($teacher->getName()) ?> </dd>
    </dl>
    <table>
      <tr>
        <th class="buttons"></th>
        <th><?php h::msg("createAppointment.time") ?></th>
        <th><?php h::msg("createAppointment.status") ?></th>
      </tr>
      <?php $appointments = Appointment::getForTeacher($teacherId) ?>
      <?php $selected = Appointment::getSelected($appointments, $pupilId, $teacherId) ?>    
      <?php foreach ($appointments as $appointment): ?>
        <?php $timeSlot = $appointment->getTimeSlot() ?>
        <tr class="<?php echo $appointment->isReserved() ? "reserved" : "free" ?> <?php if ($appointment->isReservedTo($pupilId, $teacherId)):?>own<?php endif ?>">
          <td>
            <?php if (!$appointment->isReserved() || $appointment->isReservedTo($pupilId, $teacherId)): ?>
              <input id="timeSlot-<?php echo $appointment->getTimeSlotId()?>" type="radio" name="timeSlot" value="<?php echo $timeSlot->getId() ?>" <?php if ($selected == $appointment): ?>checked<?php endif ?> />
            <?php endif ?>
          </td>
          <td>
            <label for="timeSlot-<?php echo $appointment->getTimeSlotId()?>"><?php h::text($timeSlot->getTimeString()) ?></label>
          </td>
          <td class="buttons">
            <?php if ($appointment->isReserved()): ?>
              <?php if ($appointment->isReservedTo($pupilId, $teacherId)): ?>
                <?php h::msg("createAppointment.current") ?>
              <?php else: ?>
                <?php h::msg("createAppointment.reserved") ?>
              <?php endif ?>
            <?php else: ?>
              <?php h::msg("createAppointment.free") ?>
            <?php endif ?>
          </td>
        </tr>
      <?php endforeach ?>
    </table>
  </form>
  
</div>
<?php include "parts/footer.php" ?>
