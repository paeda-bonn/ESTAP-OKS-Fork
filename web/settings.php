<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 *
 * On this page the admin can see and modify the ESTAP settings.
 */

require_once "estap.php";

use ESTAP\Config;
use ESTAP\Forms\ConfigForm;
use ESTAP\Session;
use PhoolKit\HTML as h;

$session = Session::get()->requireAdmin();
$config = Config::get();
$logo = $config->getLogo();
$background = $config->getBackground();

?>

<?php $pageId = "settings";
include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
    <h2><?php h::msg("settings.title") ?></h2>
    <?php h::messages() ?>

    <?php h::bindForm(ConfigForm::get()) ?>
    <form action="<?php h::url("actions/saveSettings.php") ?>" enctype="multipart/form-data" method="post" novalidate <?php h::form() ?>>

        <div class="buttons">
            <input type="submit" value="<?php h::msg("settings.save") ?>"/>
        </div>

        <div class="fields">

            <?php h::bindField("parentLoginEnabled") ?>
            <div class="field checkbox">
                <input type="checkbox" <?= h::checkbox() ?> <?php h::classes() ?> <?php h::autoFocus() ?> />
                <label <?php h::label() ?>><?php h::msg("settings.parentLoginEnabled") ?></label>
            </div>
            <?php h::messages() ?>

            <?php h::bindField("teacherLoginEnabled") ?>
            <div class="field checkbox">
                <input type="checkbox" <?= h::checkbox() ?> <?php h::classes() ?> />
                <label <?php h::label() ?>><?php h::msg("settings.teacherLoginEnabled") ?></label>
            </div>
            <?php h::messages() ?>

            <?php h::bindField("reservationEnabled") ?>
            <div class="field checkbox">
                <input type="checkbox" <?= h::checkbox() ?> <?php h::classes() ?> />
                <label <?php h::label() ?>><?php h::msg("settings.reservationEnabled") ?></label>
            </div>
            <?php h::messages() ?>

            <?php h::bindField("duplicatesEnabled") ?>
            <div class="field checkbox">
                <input type="checkbox" <?= h::checkbox() ?> <?php h::classes() ?> />
                <label <?php h::label() ?>><?php h::msg("settings.duplicatesEnabled") ?></label>
            </div>
            <?php h::messages() ?>

            <?php h::bindField("roomsEnabled") ?>
            <div class="field checkbox">
                <input type="checkbox" <?= h::checkbox() ?> <?php h::classes() ?> />
                <label <?php h::label() ?>><?php h::msg("settings.roomsEnabled") ?></label>
            </div>
            <?php h::messages(); ?>

            <?php h::bindField("vConferencesEnabled") ?>
                <div class="field checkbox">
                    <input type="checkbox" <?= h::checkbox() ?> <?php h::classes() ?> />
                    <label <?php h::label() ?>><?php h::msg("settings.vConferencesEnabled") ?></label>
                </div>
            <?php h::messages(); ?>


            <h3><?php h::msg("settings.reservationSettings") ?></h3>
            <?php h::msg("settings.reservationHint") ?>
            <label <?php h::label() ?>><?php h::msg("settings.startTime") ?></label>
            <div class="fields">

                <div class="field">
                    <?php h::bindField("reservationStartDay") ?>
                    <label <?php h::label() ?>><?php h::msg("settings.day") ?></label>
                    <select <?php h::select() ?>>
                        <?php h::options(Config::getDays()) ?>
                    </select>
                </div>

                <div class="field">
                    <?php h::bindField("reservationStartMonth") ?>
                    <label for="reservationStartMonth"><?php h::msg("settings.month") ?></label>
                    <select <?php h::select() ?>>
                        <?php h::options(Config::getMonths()) ?>
                    </select>
                </div>

                <div class="field">
                    <?php h::bindField("reservationStartYear") ?>
                    <label for="reservationStartYear"><?php h::msg("settings.year") ?></label>
                    <select <?php h::select() ?>>
                        <?php h::options(Config::getYears()) ?>
                    </select>
                </div>

                <div class="field">
                    <?php h::bindField("reservationStartHour") ?>
                    <label for="reservationStartHour"><?php h::msg("settings.hour") ?></label>
                    <select <?php h::select() ?>>
                        <?php h::options(Config::getHours()) ?>
                    </select>
                </div>

                <div class="field">
                    <?php h::bindField("reservationStartMinute") ?>
                    <label for="reservationStartMinute"><?php h::msg("settings.minute") ?></label>
                    <select <?php h::select() ?>>
                        <?php h::options(Config::getMinutes()) ?>
                    </select>
                </div>
            </div>

            <label <?php h::label() ?>><?php h::msg("settings.endTime") ?></label>
            <div class="fields">

                <div class="field">
                    <?php h::bindField("reservationEndDay") ?>
                    <label for="reservationEndDay"><?php h::msg("settings.day") ?></label>
                    <select <?php h::select() ?>>
                        <?php h::options(Config::getDays()) ?>
                    </select>
                </div>

                <div class="field">
                    <?php h::bindField("reservationEndMonth") ?>
                    <label for="reservationEndMonth"><?php h::msg("settings.month") ?></label>
                    <select <?php h::select() ?>>
                        <?php h::options(Config::getMonths()) ?>
                    </select>
                </div>

                <div class="field">
                    <?php h::bindField("reservationEndYear") ?>
                    <label for="reservationEndYear"><?php h::msg("settings.year") ?></label>
                    <select <?php h::select() ?>>
                        <?php h::options(Config::getYears()) ?>
                    </select>
                </div>

                <div class="field">
                    <?php h::bindField("reservationEndHour") ?>
                    <label for="reservationEndHour"><?php h::msg("settings.hour") ?></label>
                    <select <?php h::select() ?>>
                        <?php h::options(Config::getHours()) ?>
                    </select>
                </div>

                <div class="field">
                    <?php h::bindField("reservationEndMinute") ?>
                    <label for="reservationEndMinute"><?php h::msg("settings.minute") ?></label>
                    <select <?php h::select() ?>>
                        <?php h::options(Config::getMinutes()) ?>
                    </select>
                </div>
            </div>

            <h3><?php h::msg("settings.logo") ?></h3>
            <input type="file" name="logo"/><br/><br/>
            <?php if ($logo): ?>
                <img src="<?php echo $logo ?>" style="max-height:64px" alt=""/><br/>
                <?php h::bindField("deleteLogo") ?>
                <div class="field checkbox">
                    <input type="checkbox" <?= h::checkbox() ?> <?php h::classes() ?> />
                    <label <?php h::label() ?>><?php h::msg("settings.deleteLogo") ?></label>
                </div>
                <?php h::messages() ?>
            <?php endif ?>

            <h3><?php h::msg("settings.background") ?></h3>
            <input type="file" name="background"/><br/><br/>
            <?php if ($background): ?>
                <?php h::bindField("deleteBackground") ?>
                <div class="field checkbox">
                    <input type="checkbox" <?= h::checkbox() ?> <?php h::classes() ?> />
                    <label <?php h::label() ?>><?php h::msg("settings.deleteBackground") ?></label>
                </div>
                <?php h::messages() ?>
            <?php endif ?>

            <h3><?php h::msg("settings.appTitle") ?></h3>
            <?php foreach ($config->getLocales() as $locale): ?>
                <?php h::bindField("title", $locale) ?>
                <label <?php h::label() ?>><?php h::msg("settings.language", $locale) ?></label>
                <input size="80" type="text" <?php h::input() ?> <?php h::classes() ?> />
                <?php h::messages() ?>
            <?php endforeach ?>

            <h3><?php h::msg("settings.greeting") ?></h3>
            <?php foreach ($config->getLocales() as $locale): ?>
                <?php h::bindField("greeting", $locale) ?>
                <label <?php h::label() ?>><?php h::msg("settings.language", $locale) ?></label>
                <textarea rows="5" cols="80" <?php h::textarea() ?><?php h::classes() ?>><?php h::textareaValue() ?></textarea>
                <?php h::messages() ?>
            <?php endforeach ?>

            <h3><?php h::msg("settings.timeSlotSettings") ?></h3>
            <?php h::bindField("timeSlotDurations") ?>
            <label <?php h::label() ?>><?php h::msg("settings.timeSlotDurations") ?></label>
            <input type="text" <?php h::input() ?> <?php h::classes() ?> />
            <?php h::messages() ?>

            <?php h::bindField("defaultTimeSlotDuration") ?>
            <label <?php h::label() ?>><?php h::msg("settings.defaultTimeSlotDuration") ?></label>
            <input type="text" <?php h::input() ?> <?php h::classes() ?> />
            <?php h::messages() ?>

            <?php h::bindField("minTimeSlotStartHour") ?>
            <label <?php h::label() ?>><?php h::msg("settings.minTimeSlotStartHour") ?></label>
            <input type="text" <?php h::input() ?> <?php h::classes() ?> />
            <?php h::messages() ?>

            <?php h::bindField("maxTimeSlotEndHour") ?>
            <label <?php h::label() ?>><?php h::msg("settings.maxTimeSlotEndHour") ?></label>
            <input type="text" <?php h::input() ?> <?php h::classes() ?> />
            <?php h::messages() ?>

            <h3><?php h::msg("settings.userSettings") ?></h3>
            <?php h::bindField("minPasswordLength") ?>
            <label <?php h::label() ?>><?php h::msg("settings.minPasswordLength") ?></label>
            <input type="text" <?php h::input() ?> <?php h::classes() ?> />
            <?php h::messages() ?>

            <h3><?php h::msg("settings.localeSettings") ?></h3>
            <?php h::bindField("locales") ?>
            <label <?php h::label() ?>><?php h::msg("settings.locales") ?></label>
            <input type="text" <?php h::input() ?> <?php h::classes() ?> />
            <?php h::messages() ?>

            <?php h::bindField("defaultLocale") ?>
            <label <?php h::label() ?>><?php h::msg("settings.defaultLocale") ?></label>
            <input type="text" <?php h::input() ?> <?php h::classes() ?> />
            <?php h::messages() ?>

        </div>

        <div class="buttons">
            <input type="submit" value="<?php h::msg("settings.save") ?>"/>
        </div>
    </form>

</div>
<?php include "parts/footer.php" ?>
