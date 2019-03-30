<?php 
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page a single pupil can be edited.
 */

require_once "estap.php";

use ESTAP\Session;
use ESTAP\Config;
use ESTAP\Forms\EditPupilForm;
use ESTAP\Pupil;
use PhoolKit\HTML as h;

$session = Session::get()->requireAdmin();
$pupilId = +$_REQUEST["id"];

?>
<?php $pageId = "editPupil"; include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
  <h2><?php h::msg("editPupil.title") ?></h2>  
  
  <?php h::messages() ?>

  <?php h::bindForm(EditPupilForm::get(Pupil::getById($pupilId))) ?>
  <form action="<?php h::url("actions/editPupil.php") ?>" method="post" novalidate <?php h::form() ?>>
    <?php h::bindField("id") ?>
    <input type="hidden" <?php h::input() ?> />

    <div class="fields">
      <?php h::bindField("login") ?>
      <label <?php h::label() ?>><?php h::msg("editPupil.login") ?></label>
      <input type="text" <?php h::input() ?> <?php h::classes() ?> <?php h::autoFocus() ?> />
      <?php h::messages() ?>

      <?php h::bindField("firstName") ?>
      <label <?php h::label() ?>><?php h::msg("editPupil.firstName") ?></label>
      <input type="text" <?php h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>
      
      <?php h::bindField("lastName") ?>
      <label <?php h::label() ?>><?php h::msg("editPupil.lastName") ?></label>
      <input type="text" <?php h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>
      
      <?php h::bindField("class") ?>
      <label <?php h::label() ?>><?php h::msg("editPupil.class") ?></label>
      <input type="text" <?php h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>
      
      <?php h::bindField("password") ?>
      <label <?php h::label() ?>><?php h::msg("editPupil.password", Config::get()->getMinPasswordLength()) ?></label>
      <input type="password" <?=h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>

      <?php h::bindField("passwordConfirmation") ?>
      <label <?php h::label() ?>><?php h::msg("editPupil.passwordConfirmation") ?></label>
      <input type="password" <?=h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>
      
    </div>

    <div class="buttons">
      <input type="submit" value="<?php h::msg("editPupil.submit") ?>" />
      <a href="<?php h::url("pupils.php") ?>">
        <?php h::msg("editPupil.cancel") ?>
      </a>
      </div>

  </form>
</div> 
<?php include "parts/footer.php" ?>
