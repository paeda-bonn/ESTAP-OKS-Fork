<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * The login page for teachers.
 */

require_once "estap.php";

use ESTAP\Config;
use ESTAP\Forms\LoginForm;
use PhoolKit\HTML as h;

?>
<?php $pageId = "loginTeacher";
include "parts/header.php" ?>
<div id="content">
    <h2><?php h::msg("loginTeacher.title") ?></h2>

    <?php h::messages() ?>

    <?php if (Config::get()->isTeacherLoginEnabled()): ?>
        <?php h::bindForm(LoginForm::get()) ?>
        <form action="<?php h::url("actions/loginTeacher.php") ?>" method="post" novalidate <?php h::form() ?>>

            <div class="fields">
                <?php h::bindField("login") ?>
                <label <?php h::label() ?>><?php h::msg("loginTeacher.loginLabel") ?></label>
                <input type="text" <?php h::input() ?> <?php h::classes() ?> <?php h::autoFocus() ?> />
                <?php h::messages() ?>

                <?php h::bindField("password") ?>
                <label <?php h::label() ?>><?php h::msg("loginTeacher.passwordLabel") ?></label>
                <input type="password" <?= h::input() ?> <?php h::classes() ?> />
                <?php h::messages() ?>
            </div>

            <div class="buttons">
                <input type="submit" value="<?php h::msg("loginTeacher.loginBtn") ?>"/>
            </div>

        </form>
    <?php else: ?>
        <p><?php h::msg("loginTeacher.disabled") ?></p>
    <?php endif ?>
</div>
<?php include "parts/footer.php" ?>
