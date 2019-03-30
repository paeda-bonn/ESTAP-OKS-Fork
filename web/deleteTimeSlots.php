<?php 
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page all time slots can be deleted.
 */

require_once "estap.php";

use ESTAP\Session;
use ESTAP\TimeSlot;
use PhoolKit\HTML as h;

$session = Session::get()->requireAdmin();
$date = $_REQUEST["date"];
$frontString = "";
if($date === null){ 
	$frontString = "deleteTimeSlots";
}
else{
	$frontString = "deleteTimeSlotsForDate";
}
?>
<?php $pageId = "deleteTimeSlots"; include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
  <h2><?php h::msg($frontString . ".title") ?></h2>
  <?php h::messages() ?>
  <p>
    <?php h::msg($frontString . ".question", $date) ?>
  </p>

  <div class="buttons">
    <form action="<?php h::url("actions/deleteTimeSlots.php?date=" . $date)?>" method="post" novalidate>
      <input type="submit" value="<?php h::msg($frontString . ".confirm") ?>" />
    </form>
    <a href="<?php h::url("timeSlots.php") ?>">
      <?php h::msg($frontString . ".cancel") ?>
    </a>
  </div>
</div> 
<?php include "parts/footer.php" ?>
