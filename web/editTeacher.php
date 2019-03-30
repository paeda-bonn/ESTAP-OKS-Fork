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
      <?php h::messages() ?>
      
      <?php h::bindField("password") ?>
      <label <?php h::label() ?>><?php h::msg("editTeacher.password", Config::get()->getMinPasswordLength()) ?></label>
      <input type="password" <?=h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>

      <?php h::bindField("passwordConfirmation") ?>
      <label <?php h::label() ?>><?php h::msg("editTeacher.passwordConfirmation") ?></label>
      <input type="password" <?=h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>
      
    </div>

    <div class="buttons">
      <input type="submit" value="<?php h::msg("editTeacher.submit") ?>" />
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
  </form>

</div> 
<?php include "parts/footer.php" ?>
