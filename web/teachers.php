<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page the admin can upload and edit teacher data.
 */

require_once "estap.php";

use ESTAP\Config;
use ESTAP\Session;
use ESTAP\Teacher;
use PhoolKit\FormatUtils as f;
use PhoolKit\HTML as h;

$session = Session::get()->requireAdmin();
$teachers = Teacher::getAll();
$config = Config::get();

?>
<?php $pageId = "teachers";
include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
    <h2><?php h::msg("teachers.title") ?></h2>

    <?php h::messages() ?>

    <h3><?php h::msg("teachers.upload.title") ?></h3>
    <p>
        <?php h::msg("teachers.upload.description") ?>
    </p>
    <pre><?php h::msg("teachers.upload.format") ?></pre>
    <p>
        <?php h::msg("teachers.upload.hints", f::toBytes(f::fromBytes(ini_get("upload_max_filesize")))) ?>
    </p>
    <div class="buttons">
        <form action="<?php h::url("actions/uploadTeachers.php") ?>" enctype="multipart/form-data" method="post">
            <input type="file" name="teachers"/>
            <input type="submit" value="<?php h::msg("teachers.uploadTeachers") ?>"/>
        </form>
    </div>

    <h3><?php h::msg("teachers.current.title") ?></h3>
    <div class="buttons">
        <a href="<?php h::url("addTeacher.php") ?>">
            <?php h::msg("teachers.addTeacher") ?>
        </a>
        <?php if ($teachers): ?>
            <a href="<?php h::url("deleteTeachers.php") ?>">
                <?php h::msg("teachers.deleteAll") ?>
            </a>
            <a href="<?php h::url("initializeTeachers.php") ?>">
                <?php h::msg("teachers.initialize") ?>
            </a>
            <a href="<?php h::url("printAllTeacherAppointments.php") ?>" target="_blank">
                <?php h::msg("teachers.printAllTeacherAppointments") ?>
            </a>
        <?php endif ?>
    </div>

    <?php if ($teachers): ?>
        <table>
            <tr>
                <th><?php h::msg("teachers.login") ?></th>
                <th><?php h::msg("teachers.lastName") ?></th>
                <th><?php h::msg("teachers.firstName") ?></th>
                <th><?php h::msg("teachers.gender") ?></th>
                <?php if ($config->isRoomsEnabled()): ?>
                    <th><?php h::msg("teachers.room") ?></th>
                <?php endif?>
                <th><?php h::msg("teachers.actions") ?></th>
                <?php if ($config->isMeetingsEnabled()): ?>
                    <th><?php h::msg("teachers.vcLink") ?></th>
                    <th><?php h::msg("teachers.vcId") ?></th>
                    <th><?php h::msg("teachers.vcCode") ?></th>
                <?php endif?>
            </tr>
            <?php foreach (Teacher::getAll() as $teacher): ?>
                <tr class="<?php echo $teacher->isActive() ? "active" : "inactive" ?>">
                    <td><?php h::text($teacher->getLogin()) ?></td>
                    <td><?php h::text($teacher->getLastName()) ?></td>
                    <td><?php h::text($teacher->getFirstName()) ?></td>
                    <td><?php h::msg("gender." . $teacher->getGender()) ?></td>
                    <?php if ($config->isRoomsEnabled()): ?>
                        <td><?php h::text($teacher->getRoom()) ?></td>
                    <?php endif?>
                    <td class="buttons">
                        <a href="<?php h::url("editTeacher.php?id=") . h::text($teacher->getId()) ?>">
                            <?php h::msg("teachers.edit") ?>
                        </a>
                        <a href="<?php h::url("deleteTeacher.php?id=") . h::text($teacher->getId()) ?>">
                            <?php h::msg("teachers.delete") ?>
                        </a>
                        <a href="<?php h::url("teacherAppointments.php?teacher=") . h::text($teacher->getId()) ?>">
                            <?php h::msg("teachers.appointments") ?>
                        </a>
                        <?php if ($teacher->isActive()): ?>
                            <form action="<?php h::url("actions/deactivateTeacher.php") ?>" method="post">
                                <input type="hidden" name="id" value="<?php h::text($teacher->getId()) ?>"/>
                                <input type="submit" value="<?php h::msg("teachers.deactivate") ?>"/>
                            </form>
                        <?php else: ?>
                            <form action="<?php h::url("actions/activateTeacher.php") ?>" method="post">
                                <input type="hidden" name="id" value="<?php h::text($teacher->getId()) ?>"/>
                                <input type="submit" value="<?php h::msg("teachers.activate") ?>"/>
                            </form>
                        <?php endif; ?>
                    </td>
                    <?php if ($config->isMeetingsEnabled()): ?>
                        <td><?php h::text($teacher->getVcLink()) ?></td>
                        <td><?php h::text($teacher->getVcId()) ?></td>
                        <td><?php h::text($teacher->getVcCode()) ?></td>
                    <?php endif ?>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>

</div>
<?php include "parts/footer.php" ?>
