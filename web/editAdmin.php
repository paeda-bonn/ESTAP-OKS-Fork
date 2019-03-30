<?php 
/*
 * Copyright 2014 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page a single admin can be edited.
 */

require_once "estap.php";

use ESTAP\Session;
use ESTAP\Config;
use ESTAP\Forms\EditAdminForm;
use ESTAP\Admin;
use PhoolKit\HTML as h;

$session = Session::get()->requireAdmin();
$adminId = +$_REQUEST["id"];

?>
<?php $pageId = "editAdmin"; include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
  <h2><?php h::msg("editAdmin.title") ?></h2>  
  
  <?php h::messages() ?>

  <?php h::bindForm(EditAdminForm::get(Admin::getById($adminId))) ?>
  <form action="<?php h::url("actions/editAdmin.php") ?>" method="post" novalidate <?php h::form() ?>>
    <?php h::bindField("id") ?>
    <input type="hidden" <?php h::input() ?> />

    <div class="fields">
      <?php h::bindField("login") ?>
      <label <?php h::label() ?>><?php h::msg("editAdmin.login") ?></label>
      <input type="text" <?php h::input() ?> <?php h::classes() ?> <?php h::autoFocus() ?> />
      <?php h::messages() ?>

      <?php h::bindField("firstName") ?>
      <label <?php h::label() ?>><?php h::msg("editAdmin.firstName") ?></label>
      <input type="text" <?php h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>
      
      <?php h::bindField("lastName") ?>
      <label <?php h::label() ?>><?php h::msg("editAdmin.lastName") ?></label>
      <input type="text" <?php h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>
      
      <?php h::bindField("password") ?>
      <label <?php h::label() ?>><?php h::msg("editAdmin.password", Config::get()->getMinPasswordLength()) ?></label>
      <input type="password" <?=h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>

      <?php h::bindField("passwordConfirmation") ?>
      <label <?php h::label() ?>><?php h::msg("editAdmin.passwordConfirmation") ?></label>
      <input type="password" <?=h::input() ?> <?php h::classes() ?> />
      <?php h::messages() ?>
      
    </div>

    <div class="buttons">
      <input type="submit" value="<?php h::msg("editAdmin.submit") ?>" />
      <a href="<?php h::url("admins.php") ?>">
        <?php h::msg("editAdmin.cancel") ?>
      </a>
    </div>

  </form>
</div> 
<?php include "parts/footer.php" ?>
