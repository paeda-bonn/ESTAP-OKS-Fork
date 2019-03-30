<?php 
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page the deletion of an appointment can be confirmed.
 */

require_once "estap.php";

use ESTAP\Session;
use ESTAP\Appointment;
use ESTAP\Teacher;
use PhoolKit\HTML as h;

$appointmentId = +$_REQUEST["id"];
$appointment = Appointment::getById($appointmentId);
$session = Session::get()->requireParent($appointment->getPupilId());
$teacher = $appointment->getTeacher();
$pupil = $appointment->getPupil();
$timeSlot = $appointment->getTimeSlot();

?>
<?php $pageId = "deleteAppointment"; include "parts/header.php" ?>
<div id="content">
  <h2><?php h::msg("deleteAppointment.title") ?></h2>
  <?php h::messages() ?>
  <p>
    <?php h::msg("deleteAppointment.question") ?>
  </p>
  <dl>
    <dt><?php h::msg("deleteAppointment.time") ?></dt>
    <dd><?php h::text($timeSlot->getTimeString()) ?></dd>
    <dt><?php h::msg("deleteAppointment.teacher") ?></dt>
    <dd><?php h::text($teacher->getName(Teacher::GENDER_LAST)) ?></dd>
    <dt><?php h::msg("deleteAppointment.pupil") ?></dt>
    <dd><?php h::text($pupil->getName()) ?></dd>
  </dl>

  <div class="buttons">
    <form action="<?php h::url("actions/deleteAppointment.php")?>" method="post" novalidate>
      <input type="hidden" name="pupil" value="<?php echo $pupil->getId() ?>" />
      <input type="hidden" name="teacher" value="<?php echo $teacher->getId() ?>" />
      <input type="submit" value="<?php h::msg("deleteAppointment.confirm") ?>" />
    </form>
    <a href="<?php h::url("parents.php") ?>">
      <?php h::msg("deleteAppointment.cancel") ?>
    </a>
  </div>
</div> 
<?php include "parts/footer.php" ?>
