<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 *
 * The start page for ESTAP.
 */
require_once "estap.php";

use PhoolKit\Request;
use ESTAP\Config;
use PhoolKit\HTML as h;
$config = Config::get();
if ($config->isTeacherLoginEnabled() & !$config->isParentLoginEnabled()): 
  Request::redirect("loginTeacher.php");
elseif (!$config->isTeacherLoginEnabled() & $config->isParentLoginEnabled()):
  Request::redirect("parents.php");
else: 
?>

  <?php $pageId = "start"; include "parts/header.php" ?>
  <div id="content">
    <?php if ($config->isTeacherLoginEnabled() & $config->isParentLoginEnabled()): ?>
      <h2><?php h::msg("start.title") ?></h2>
      <p class="help"><?php h::msg("start.help") ?></p>
      <div class="startbuttons">
        <a href="parents.php">
          <?php h::msg("start.parents") ?>
        </a>
        <a href="loginTeacher.php">
          <?php h::msg("start.teacher") ?>
        </a>
      </div>            
    <?php else: ?>  
      <h2><?php h::msg("start.loginDisabled") ?></h2>
    <?php endif ?>  
  </div>
  <?php include "parts/footer.php" ?>
<?php endif ?>
