<?php
/*
 * Copyright 2014 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page a single time slot can be deleted.
 */

require_once "estap.php";

use ESTAP\Session;
use ESTAP\TimeSlot;
use PhoolKit\HTML as h;

$session = Session::get()->requireAdmin();
$timeSlotId = +$_REQUEST["id"];
$timeSlot = TimeSlot::getById($timeSlotId);

?>
<?php $pageId = "deleteTimeSlot";
include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
    <h2><?php h::msg("deleteTimeSlot.title") ?></h2>
    <?php h::messages() ?>
    <p>
        <?php h::msg("deleteTimeSlot.question", $timeSlot->getTimeString()) ?>
    </p>

    <div class="buttons">
        <form action="<?php h::url("actions/deleteTimeSlot.php") ?>" method="post" novalidate>
            <input type="hidden" name="id" value="<?php echo $timeSlotId ?>"/>
            <input type="submit" value="<?php h::msg("deleteTimeSlot.confirm") ?>"/>
        </form>
        <a href="<?php h::url("timeSlots.php") ?>">
            <?php h::msg("deleteTimeSlot.cancel") ?>
        </a>
    </div>
</div>
<?php include "parts/footer.php" ?>
