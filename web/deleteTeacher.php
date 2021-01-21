<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page a single teacher can be deleted.
 */

require_once "estap.php";

use ESTAP\Session;
use ESTAP\Teacher;
use PhoolKit\HTML as h;

$session = Session::get()->requireAdmin();
$teacherId = +$_REQUEST["id"];
$teacher = Teacher::getById($teacherId);

?>
<?php $pageId = "deleteTeacher";
include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
    <h2><?php h::msg("deleteTeacher.title") ?></h2>
    <?php h::messages() ?>
    <p>
        <?php h::msg("deleteTeacher.question", $teacher->getName()) ?>
    </p>

    <div class="buttons">
        <form action="<?php h::url("actions/deleteTeacher.php") ?>" method="post" novalidate>
            <input type="hidden" name="id" value="<?php echo $teacherId ?>"/>
            <input type="submit" value="<?php h::msg("deleteTeacher.confirm") ?>"/>
        </form>
        <a href="<?php h::url("teachers.php") ?>">
            <?php h::msg("deleteTeacher.cancel") ?>
        </a>
    </div>
</div>
<?php include "parts/footer.php" ?>
