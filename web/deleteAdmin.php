<?php 
/*
 * Copyright 2014 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page a single admin can be deleted.
 */

require_once "estap.php";

use ESTAP\Session;
use ESTAP\Admin;
use PhoolKit\HTML as h;

$session = Session::get()->requireAdmin();
$adminId = +$_REQUEST["id"];
$admin = Admin::getById($adminId);

?>
<?php $pageId = "deleteAdmin"; include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
  <h2><?php h::msg("deleteAdmin.title") ?></h2>
  <?php h::messages() ?>
  <p>
    <?php h::msg("deleteAdmin.question", $admin->getName()) ?>
  </p>

  <div class="buttons">
    <form action="<?php h::url("actions/deleteAdmin.php")?>" method="post" novalidate>
      <input type="hidden" name="id" value="<?php echo $adminId ?>" />
      <input type="submit" value="<?php h::msg("deleteAdmin.confirm") ?>" />
    </form>
    <a href="<?php h::url("admins.php") ?>">
      <?php h::msg("deleteAdmin.cancel") ?>
    </a>
  </div>
</div> 
<?php include "parts/footer.php" ?>
