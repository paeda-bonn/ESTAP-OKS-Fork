<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page a single pupil can be added.
 */

require_once "estap.php";

use ESTAP\Config;
use ESTAP\Forms\AddPupilForm;
use ESTAP\Session;
use PhoolKit\HTML as h;

$session = Session::get()->requireAdmin();

?>
<?php $pageId = "addPupil";
include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
    <h2><?php h::msg("addPupil.title") ?></h2>

    <?php h::messages() ?>

    <?php h::bindForm(AddPupilForm::get()) ?>
    <form action="<?php h::url("actions/addPupil.php") ?>" method="post" novalidate <?php h::form() ?>>

        <div class="fields">
            <?php h::bindField("login") ?>
            <label <?php h::label() ?>><?php h::msg("addPupil.login") ?></label>
            <input type="text" <?php h::input() ?> <?php h::classes() ?> <?php h::autoFocus() ?> />
            <?php h::messages() ?>

            <?php h::bindField("firstName") ?>
            <label <?php h::label() ?>><?php h::msg("addPupil.firstName") ?></label>
            <input type="text" <?php h::input() ?> <?php h::classes() ?> />
            <?php h::messages() ?>

            <?php h::bindField("lastName") ?>
            <label <?php h::label() ?>><?php h::msg("addPupil.lastName") ?></label>
            <input type="text" <?php h::input() ?> <?php h::classes() ?> />
            <?php h::messages() ?>

            <?php h::bindField("class") ?>
            <label <?php h::label() ?>><?php h::msg("addPupil.class") ?></label>
            <input type="text" <?php h::input() ?> <?php h::classes() ?> />
            <?php h::messages() ?>

            <?php h::bindField("password") ?>
            <label <?php h::label() ?>><?php h::msg("addPupil.password", Config::get()->getMinPasswordLength()) ?></label>
            <input type="password" <?= h::input() ?> <?php h::classes() ?> />
            <?php h::messages() ?>

            <?php h::bindField("passwordConfirmation") ?>
            <label <?php h::label() ?>><?php h::msg("addPupil.passwordConfirmation") ?></label>
            <input type="password" <?= h::input() ?> <?php h::classes() ?> />
            <?php h::messages() ?>

        </div>

        <div class="buttons">
            <input type="submit" value="<?php h::msg("addPupil.submit") ?>"/>
            <a href="<?php h::url("pupils.php") ?>">
                <?php h::msg("addPupil.cancel") ?>
            </a>
        </div>

    </form>
</div>
<?php include "parts/footer.php" ?>
