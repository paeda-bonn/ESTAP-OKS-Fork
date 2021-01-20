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

if (isset($_REQUEST["admin"]))
{
    $session = Session::get()->requireAdmin();
    $admin = true;
}
else
{
    $session = Session::get()->requireTeacher();
    $admin = false;
}

$timeSlotId = +$_REQUEST["id"];
$timeSlot = TimeSlot::getById($timeSlotId);

?>
<?php $pageId = "deleteTimeSlot"; include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
  <h2><?php h::msg("deleteTimeSlot.title") ?></h2>
  <?php h::messages() ?>
  <p>
    <?php h::msg("deleteTimeSlot.question", $timeSlot->getTimeString()) ?>
  </p>

  <div class="buttons">
	  <?php if($admin){ ?>
	  <form action="<?php h::url("actions/deleteTimeSlotTeacher.php?admin=1")?>" method="post" novalidate>
	  <?php }else{ ?>
	  <form action="<?php h::url("actions/deleteTimeSlotTeacher.php")?>" method="post" novalidate>
	  <?php } ?>
    
      <input type="hidden" name="id" value="<?php echo $timeSlotId ?>" />
      <input type="submit" value="<?php h::msg("deleteTimeSlot.confirm") ?>" />
    </form>
    <a href="<?php h::url("teachers.php") ?>">
      <?php h::msg("deleteTimeSlot.cancel") ?>
    </a>
  </div>
</div> 
<?php include "parts/footer.php" ?>
