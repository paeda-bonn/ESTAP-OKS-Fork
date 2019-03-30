<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 *
 * The login page for parents.
 */

require_once "estap.php";

use ESTAP\Forms\LoginForm;
use PhoolKit\HTML as h;
use ESTAP\Config;

$pageId = "login";

?>
<?php $pageId = "login"; include "parts/header.php" ?>
<div id="content">
    <?php $greeting = Config::get()->getGreeting() ?>
    <?php if ($greeting): ?>
      <p>
        <?=nl2br(htmlspecialchars($greeting))?>
      </p>
    <?php endif ?>

  <h2><?php h::msg("login.title") ?></h2>

  <?php h::messages() ?>

  <?php if (Config::get()->isParentLoginEnabled()): ?>

    <p class="help"><?php h::msg("login.help") ?></p>

    <?php h::bindForm(LoginForm::get()) ?>
    <form action="<?php h::url("actions/login.php") ?>" method="post" novalidate <?php h::form() ?>>

      <div class="fields">
        <?php h::bindField("login") ?>
        <div class="field">
          <label <?php h::label() ?>><?php h::msg("login.loginLabel") ?></label>
          <input type="text" <?php h::input() ?> <?php h::classes() ?> <?php h::autoFocus() ?> />
        </div>
        <?php h::messages() ?>

        <?php h::bindField("password") ?>
        <div class="field">
          <label <?php h::label() ?>><?php h::msg("login.passwordLabel") ?></label>
          <input type="password" <?=h::input() ?> <?php h::classes() ?> />
        </div>
        <?php h::messages() ?>

        <?php h::bindField("another") ?>
        <div class="field checkbox">
          <input type="checkbox" <?=h::checkbox() ?> <?php h::classes() ?> />
          <label <?php h::label() ?>><?php h::msg("login.another") ?></label>
        </div>
        <?php h::messages() ?>
      </div>

      <div class="buttons">
        <input type="submit" value="<?php h::msg("login.loginBtn") ?>" />
        <?php if ($session->isParent()): ?>
          <a href="<?php h::url("parents.php") ?>">Abbrechen</a>
        <?php endif ?>
      </div>

    </form>
  <?php else: ?>
    <p><?php h::msg("login.disabled") ?></p>
  <?php endif ?>
</div>
<?php include "parts/footer.php" ?>
