<?php 
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page all teachers can be deleted.
 */

require_once "estap.php";

use ESTAP\Session;
use ESTAP\Teacher;
use PhoolKit\HTML as h;

$session = Session::get()->requireAdmin();

?>
<?php $pageId = "deleteTeachers"; include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
  <h2><?php h::msg("deleteTeachers.title") ?></h2>
  <?php h::messages() ?>
  <p>
    <?php h::msg("deleteTeachers.question") ?>
  </p>

  <div class="buttons">
    <form action="<?php h::url("actions/deleteTeachers.php")?>" method="post" novalidate>
      <input type="submit" value="<?php h::msg("deleteTeachers.confirm") ?>" />
    </form>
    <a href="<?php h::url("teachers.php") ?>">
      <?php h::msg("deleteTeacher.cancel") ?>
    </a>
  </div>
</div> 
<?php include "parts/footer.php" ?>
