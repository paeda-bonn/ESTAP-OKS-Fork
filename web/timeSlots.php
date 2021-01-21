<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page the admin can create and edit time slots.
 */

require_once "estap.php";

use ESTAP\Forms\AddTimeSlotsForm;
use ESTAP\Session;
use ESTAP\TimeSlot;
use PhoolKit\HTML as h;

$session = Session::get()->requireAdmin();
$timeSlots = TimeSlot::getAll();

?>
<?php $pageId = "timeSlots";
include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
    <h2><?php h::msg("timeSlots.title") ?></h2>
    <?php h::messages() ?>

    <h3><?php h::msg("timeSlots.create.title") ?></h3>

    <div>
        <?php h::bindForm(AddTimeSlotsForm::get()) ?>
        <form id="DateTimeForm" action="<?php h::url("actions/addTimeSlots.php") ?>" method="post"
              novalidate <?php h::form() ?>>
            <div class="fields">
                <div class="field">
                    <?php h::bindField("duration") ?>
                    <label <?php h::label() ?>><?php h::msg("timeSlots.duration") ?></label>
                    <select <?php h::select() ?>>
                        <?php h::options(TimeSlot::getDurations()) ?>
                    </select>
                    <?php h::messages() ?>
                </div>

                <div class="field">
                    <?php h::bindField("startHour") ?>
                    <label <?php h::label() ?>><?php h::msg("timeSlots.startTime") ?></label>
                    <select <?php h::select() ?>>
                        <?php h::options(TimeSlot::getHours()) ?>
                    </select>
                    <?php h::bindField("startMinute") ?>
                    <select <?php h::select() ?>>
                        <?php h::options(TimeSlot::getMinutes()) ?>
                    </select>
                    <?php h::bindField("startHour") ?>
                    <?php h::messages() ?>
                    <?php h::bindField("startMinute") ?>
                    <?php h::messages() ?>
                </div>

                <div class="field">
                    <?php h::bindField("endHour") ?>
                    <label <?php h::label() ?>><?php h::msg("timeSlots.endTime") ?></label>
                    <select <?php h::select() ?>>
                        <?php h::options(TimeSlot::getHours()) ?>
                    </select>
                    <?php h::bindField("endMinute") ?>
                    <select <?php h::select() ?>>
                        <?php h::options(TimeSlot::getMinutes()) ?>
                    </select>
                    <?php h::bindField("endHour") ?>
                    <?php h::messages() ?>
                    <?php h::bindField("endMinute") ?>
                    <?php h::messages() ?>
                </div>


                <div class="field">
                    <?php h::bindField("day") ?>
                    <label <?php h::label() ?>><?php h::msg("timeSlots.day") ?></label>
                    <select <?php h::select() ?>>
                        <?php h::options(TimeSlot::getDays()) ?>
                    </select>
                    <?php h::bindField("month") ?>
                    <select <?php h::select() ?>>
                        <?php h::options(TimeSlot::getMonths()) ?>
                    </select>
                    <?php h::bindField("year") ?>
                    <select <?php h::select() ?>>
                        <?php h::options(TimeSlot::getYears()) ?>
                    </select>
                    <?php h::bindField("day") ?>
                    <?php h::messages() ?>
                    <?php h::bindField("month") ?>
                    <?php h::messages() ?>
                    <?php h::bindField("year") ?>
                    <?php h::messages() ?>
                </div>
            </div>
            <div class="buttons">
                <input type="submit" value="<?php h::msg("timeSlots.create") ?>"/>
            </div>

        </form>

        <?php if ($timeSlots): ?>
            <h3><?php h::msg("timeSlots.current.title") ?></h3>
            <div class="buttons">
                <a href="<?php h::url("deleteTimeSlots.php") ?>">
                    <?php h::msg("timeSlots.deleteAll") ?>
                </a>
            </div>

            <?php foreach (TimeSlot::getDistinctDates($timeSlots) as $date): ?>
                <h3><?php $dateTime = new DateTime($date);
                    h::text($dateTime->format("d.m.Y")) ?>
                </h3>
                <br>

                <div class="buttons">
                    <a href="<?php h::url("deleteTimeSlots.php?date=" . $date) ?>">
                        <?php h::msg("timeSlots.deleteForDate") ?>
                    </a>
                </div>
                <table>
                    <tr>
                        <th><?php h::msg("timeSlots.startTime") ?></th>
                        <th><?php h::msg("timeSlots.endTime") ?></th>
                        <th class="buttons"></th>
                    </tr>
                    <?php foreach (TimeSlot::getTimeSlotsForDate($timeSlots, $date) as $timeSlot): ?>
                        <tr>
                            <td><?php h::text($timeSlot->getStartTimeString()) ?></td>
                            <td><?php h::text($timeSlot->getEndTimeString()) ?></td>
                            <td class="buttons">
                                <a href="<?php h::url("deleteTimeSlot.php?id=" . $timeSlot->getId()) ?>">
                                    <?php h::msg("timeSlots.delete") ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </table>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</div>
<?php include "parts/footer.php" ?>
