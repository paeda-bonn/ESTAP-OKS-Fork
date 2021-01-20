<?php 
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page a single teacher can be added.
 */

require_once "estap.php";

use ESTAP\Session;
use ESTAP\Config;
use ESTAP\Forms\AddTeacherForm;
use ESTAP\Teacher;
use PhoolKit\HTML as h;

$session = Session::get()->requireAdmin();

?>
<?php $pageId = "addTeacher"; include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
  <h2><?php h::msg("addTeacher.title") ?></h2>  
  
  <?php h::messages() ?>

  <?php h::bindForm(AddTeacherForm::get()) ?>
  <form action="<?php h::url("actions/addTeacher.php") ?>" method="post" novalidate <?php h::form() ?>>

    <div class="fields">
      <?php h::bindField("login") ?>
      <label <?php h::label() ?>><?php h::msg("addTeacher.login") ?></label>
      <input type="text" <?php h::input() ?> <?php h::classes() ?> <?php h::autoFocus() ?> />
      <?php h::messages() ?>

      <?php h::bindField("firstName") ?>
      <label <?php h::label() ?>><?php h::msg("addTeacher.firstName") ?></label>
      <input type="text" <?php h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>
      
      <?php h::bindField("lastName") ?>
      <label <?php h::label() ?>><?php h::msg("addTeacher.lastName") ?></label>
      <input type="text" <?php h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>
      
      <?php h::bindField("gender") ?>
      <label <?php h::label() ?>><?php h::msg("addTeacher.gender") ?></label>
      <select <?php h::select() ?> <?php h::classes() ?>>
        <?php h::options(Teacher::getGenders()) ?>
      </select>
      <?php h::messages() ?>
      
      <?php h::bindField("room") ?>
      <label <?php h::label() ?>><?php h::msg("addTeacher.room") ?></label>
      <input type="text" <?php h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>

      <?php h::bindField("vcLink") ?>
      <label <?php h::label() ?>>VCLink</label>
      <input type="text" <?php h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>
      
      <?php h::bindField("password") ?>
      <label <?php h::label() ?>><?php h::msg("addTeacher.password", Config::get()->getMinPasswordLength()) ?></label>
      <input type="password" <?=h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>

      <?php h::bindField("passwordConfirmation") ?>
      <label <?php h::label() ?>><?php h::msg("addTeacher.passwordConfirmation") ?></label>
      <input type="password" <?=h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>
      
    </div>

    <div class="buttons">
      <input type="submit" value="<?php h::msg("addTeacher.submit") ?>" />
      <a href="<?php h::url("teachers.php") ?>">
        <?php h::msg("addTeacher.cancel") ?>
      </a>
    </div>

  </form>
</div> 
<?php include "parts/footer.php" ?>
