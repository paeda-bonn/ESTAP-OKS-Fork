<?php
/*
 * Copyright 2014 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page a single admin can be added.
 */

require_once "estap.php";

use ESTAP\Config;
use ESTAP\Forms\AddAdminForm;
use ESTAP\Session;
use PhoolKit\HTML as h;

$session = Session::get()->requireAdmin();

?>
<?php $pageId = "addAdmin";
include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
    <h2><?php h::msg("addAdmin.title") ?></h2>

    <?php h::messages() ?>

    <?php h::bindForm(AddAdminForm::get()) ?>
    <form action="<?php h::url("actions/addAdmin.php") ?>" method="post" novalidate <?php h::form() ?>>

        <div class="fields">
            <?php h::bindField("login") ?>
            <label <?php h::label() ?>><?php h::msg("addAdmin.login") ?></label>
            <input type="text" <?php h::input() ?> <?php h::classes() ?> <?php h::autoFocus() ?> />
            <?php h::messages() ?>

            <?php h::bindField("firstName") ?>
            <label <?php h::label() ?>><?php h::msg("addAdmin.firstName") ?></label>
            <input type="text" <?php h::input() ?> <?php h::classes() ?> />
            <?php h::messages() ?>

            <?php h::bindField("lastName") ?>
            <label <?php h::label() ?>><?php h::msg("addAdmin.lastName") ?></label>
            <input type="text" <?php h::input() ?> <?php h::classes() ?> />
            <?php h::messages() ?>

            <?php h::bindField("password") ?>
            <label <?php h::label() ?>><?php h::msg("addAdmin.password", Config::get()->getMinPasswordLength()) ?></label>
            <input type="password" <?= h::input() ?> <?php h::classes() ?> />
            <?php h::messages() ?>

            <?php h::bindField("passwordConfirmation") ?>
            <label <?php h::label() ?>><?php h::msg("addAdmin.passwordConfirmation") ?></label>
            <input type="password" <?= h::input() ?> <?php h::classes() ?> />
            <?php h::messages() ?>

        </div>

        <div class="buttons">
            <input type="submit" value="<?php h::msg("addAdmin.submit") ?>"/>
            <a href="<?php h::url("admins.php") ?>">
                <?php h::msg("addAdmin.cancel") ?>
            </a>
        </div>

    </form>
</div>
<?php include "parts/footer.php" ?>
