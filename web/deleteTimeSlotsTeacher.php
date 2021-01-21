<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page all time slots can be deleted.
 */

require_once "estap.php";

use ESTAP\Session;
use PhoolKit\HTML as h;

if (isset($_REQUEST["admin"])) {
    $session = Session::get()->requireAdmin();
    $admin = true;
} else {
    $session = Session::get()->requireTeacher();
    $admin = false;
}
$teacherId = htmlspecialchars($_GET["teacher"])

?>
<?php $pageId = "deleteTimeSlots";
include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
    <h2><?php h::msg("deleteTimeSlots.title") ?></h2>
    <?php h::messages() ?>
    <p>
        <?php h::msg("deleteTimeSlots.question") ?>
    </p>

    <div class="buttons">
        <?php if ($admin){ ?>
        <form action="<?php h::url("actions/deleteTimeSlotsTeacher.php?admin=1&teacher=" . $teacherId) ?>" method="post" novalidate>
            <?php }else{ ?>
            <form action="<?php h::url("actions/deleteTimeSlotsTeacher.php?teacher=" . $teacherId) ?>" method="post" novalidate>
                <?php } ?>
                <input type="submit" value="<?php h::msg("deleteTimeSlots.confirm") ?>"/>
            </form>
            <a href="<?php h::url("editTeacher.php") ?>">
                <?php h::msg("deleteTimeSlots.cancel") ?>
            </a>
    </div>
</div>
<?php include "parts/footer.php" ?>
