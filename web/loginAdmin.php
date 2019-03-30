<?php 
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * The login page for admins.
 */

require_once "estap.php";

use ESTAP\Forms\LoginForm;
use PhoolKit\HTML as h;

?>
<?php $pageId = "loginAdmin"; include "parts/header.php" ?>
<div id="content">
  <h2><?php h::msg("loginAdmin.title") ?></h2>

  <?php h::messages() ?>

  <?php h::bindForm(LoginForm::get()) ?>
  <form action="<?php h::url("actions/loginAdmin.php") ?>" method="post" novalidate <?php h::form() ?>>

    <div class="fields">
      <?php h::bindField("login") ?>
      <label <?php h::label() ?>><?php h::msg("loginAdmin.loginLabel") ?></label>
      <input type="text" <?php h::input() ?> <?php h::classes() ?> <?php h::autoFocus() ?> />
      <?php h::messages() ?>

      <?php h::bindField("password") ?>
      <label <?php h::label() ?>><?php h::msg("loginAdmin.passwordLabel") ?></label>
      <input type="password" <?=h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>
    </div>

    <div class="buttons">
      <input type="submit" value="<?php h::msg("loginAdmin.loginBtn") ?>" />
    </div>

  </form>
</div>
<?php include "parts/footer.php" ?>
