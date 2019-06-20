<?php 
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page a single teacher can be edited.
 */

require_once "estap.php";

use ESTAP\Session;
use ESTAP\Config;
use ESTAP\Forms\EditTeacherForm;
use ESTAP\Teacher;
use PhoolKit\HTML as h;

use ESTAP\Forms\AddTimeSlotsForm;
use ESTAP\TimeSlot;

if (isset($_REQUEST["id"]))
{
    $session = Session::get()->requireAdmin();
    $teacherId = +$_REQUEST["id"];
    $admin = true;
}
else
{
    $session = Session::get()->requireTeacher();
    $teacher = $session->getTeacher();
    $teacherId = $teacher->getId();
    $admin = false;
}

?>
<?php $pageId = "editTeacher"; include "parts/header.php" ?>
<?php if ($admin): ?>
  <?php include "parts/adminNav.php" ?>
<?php else: ?>
  <?php include "parts/teacherNav.php" ?>
<?php endif ?>
<div id="content">
  <h2><?php h::msg("editTeacher.title") ?></h2>  
  
  <?php h::messages() ?>

  <?php h::bindForm(EditTeacherForm::get(Teacher::getById($teacherId))) ?>
  <?php if ($admin): ?>
    <form action="<?php h::url("actions/editTeacher.php?admin=1") ?>" method="post" novalidate <?php h::form() ?>>
  <?php else: ?>
    <form action="<?php h::url("actions/editTeacher.php") ?>" method="post" novalidate <?php h::form() ?>>
  <?php endif ?>
    <?php h::bindField("id") ?>
    <input type="hidden" <?php h::input() ?> />

    <div class="fields">
      <?php h::bindField("login") ?>
      <label <?php h::label() ?>><?php h::msg("editTeacher.login") ?></label>
      <input type="text" <?php h::input() ?> <?php h::classes() ?> <?php h::autoFocus() ?> />
      <?php h::messages() ?>

      <?php h::bindField("firstName") ?>
      <label <?php h::label() ?>><?php h::msg("editTeacher.firstName") ?></label>
      <input type="text" <?php h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>
      
      <?php h::bindField("lastName") ?>
      <label <?php h::label() ?>><?php h::msg("editTeacher.lastName") ?></label>
      <input type="text" <?php h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>
      
      <?php h::bindField("gender") ?>
      <label <?php h::label() ?>><?php h::msg("editTeacher.gender") ?></label>
      <select <?php h::select() ?> <?php h::classes() ?>>
        <?php h::options(Teacher::getGenders()) ?>
      </select>
      <?php h::messages() ?>
      
      <?php h::bindField("room") ?>
      <label <?php h::label() ?>><?php h::msg("editTeacher.room") ?></label>
      <input type="text" <?php h::input() ?> <?php h::classes() ?> />


      <?php h::bindField("password") ?>
      <label <?php h::label() ?>><?php h::msg("editTeacher.password", Config::get()->getMinPasswordLength()) ?></label>
      <input type="password" <?=h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>

      <?php h::bindField("passwordConfirmation") ?>
      <label <?php h::label() ?>><?php h::msg("editTeacher.passwordConfirmation") ?></label>
      <input type="password" <?=h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>
      
    </div>


        <div class="fields">
            <div class="field">
                <?php h::bindField("duration") ?>
                <label <?php h::label() ?>><?php h::msg("timeSlots.duration") ?></label>
                <select <?php h::select() ?>>
                    <?php h::options(TimeSlot::getDurations()) ?>
                    <?php print_r(TimeSlot::getDurations()) ?>
                </select>
                <?php h::messages() ?>
            </div>

            <div class="field">
                <?php h::bindField("startHour") ?>
                <label <?php h::label() ?>><?php h::msg("timeSlots.startTime") ?></label>
                <select <?php h::select() ?>>
                    <?php h::options(TimeSlot::getHours()) ?>
                </select>
                <?php h::bindField("startMinute") ?>
                <select <?php h::select() ?>>
                    <?php h::options(TimeSlot::getMinutes()) ?>
                </select>
                <?php h::bindField("startHour") ?>
                <?php h::messages() ?>
                <?php h::bindField("startMinute") ?>
                <?php h::messages() ?>
            </div>

            <div class="field">
                <?php h::bindField("endHour") ?>
                <label <?php h::label() ?>><?php h::msg("timeSlots.endTime") ?></label>
                <select <?php h::select() ?>>
                    <?php h::options(TimeSlot::getHours()) ?>
                </select>
                <?php h::bindField("endMinute") ?>
                <select <?php h::select() ?>>
                    <?php h::options(TimeSlot::getMinutes()) ?>
                </select>
                <?php h::bindField("endHour") ?>
                <?php h::messages() ?>
                <?php h::bindField("endMinute") ?>
                <?php h::messages() ?>
            </div>
            <div class="field">
                <?php h::bindField("day") ?>
                <label <?php h::label() ?>><?php h::msg("timeSlots.day") ?></label>
                <select <?php h::select() ?>>
                    <?php h::options(TimeSlot::getDays()) ?>
                </select>
                <?php h::bindField("month") ?>
                <select <?php h::select() ?>>
                    <?php h::options(TimeSlot::getMonths()) ?>
                </select>
                <?php h::bindField("year") ?>
                <select <?php h::select() ?>>
                    <?php h::options(TimeSlot::getYears()) ?>
                </select>
                <?php h::bindField("day") ?>
                <?php h::messages() ?>
                <?php h::bindField("month") ?>
                <?php h::messages() ?>
                <?php h::bindField("year") ?>
                <?php h::messages() ?>
            </div>


                <?php h::messages();
                h::bindField("times")
                ?>
                <label <?php h::label() ?>><?php h::msg("editTeacher.times") ?></label>
                <?php if($admin){ ?>
                <a href="<?php h::url('deleteTimeSlotsTeacher.php?admin=1&teacher='.$teacherId) ?>">
                    <?php }else{ ?>
                    <a href="<?php h::url('deleteTimeSlotsTeacher.php?teacher='.$teacherId) ?>">
                        <?php } ?>



                        <?php h::msg("editTeacher.deleteTimeSlots") ?></a>
        </div>
        <table>
            <?php $i = 0; ?>
            <?php foreach (TimeSlot::getTimeSlotsForTeacher($teacherId) as $timeSlot): ?>
                <?php if($i == 0): ?>
                    <tr>
                        <th><?php h::msg("timeSlots.startTime") ?></th>
                        <th><?php h::msg("timeSlots.endTime") ?></th>
                        <th class="buttons"></th>
                    </tr>
                    <?php $i = 1; ?>
                <?php endif; ?>
                <tr>
                    <td><?php h::text($timeSlot->getStartTimeString()) ?></td>
                    <td><?php h::text($timeSlot->getEndTimeString()) ?></td>
                    <td class="buttons">
                        <?php if($admin){ ?>
                        <a href="<?php h::url("deleteTimeSlotTeacher.php?admin&id=" . $timeSlot->getId().'&teacher='.$teacherId) ?>">
                            <?php }else{ ?>
                            <a href="<?php h::url("deleteTimeSlotTeacher.php?id=" . $timeSlot->getId()) ?>">
                                <?php } ?>
                                <?php h::msg("timeSlots.delete") ?>
                            </a>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
        <div class="buttons">
            <input type="submit" name="leave" value="<?php h::msg("editTeacher.submitLeave") ?>" />
            <input type="submit" name="return" value="<?php h::msg("editTeacher.submitStay") ?>" />
            <?php if ($admin): ?>
                <a href="<?php h::url("teachers.php") ?>">
                    <?php h::msg("editTeacher.cancel") ?>
                </a>
            <?php else: ?>
                <a href="<?php h::url("teacherAppointments.php") ?>">
                    <?php h::msg("editTeacher.cancel") ?>
                </a>
            <?php endif ?>
        </div>
    </div>
</form>


<?php include "parts/footer.php" ?>
