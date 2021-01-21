<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 *
 * The page header.
 */

use PhoolKit\HTML as h;
use ESTAP\Session;
use ESTAP\Config;

$config = Config::get();
$session = Session::get();
$logo = $config->getLogo();
$background = $config->getBackground();
$styles = $config->getStylesUrl();

?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <head>
    <meta charset="UTF-8" />
    <title>
      <?php if (isset($pageId) || isset($pageTitle)): ?>
        <?php if (isset($pageTitle)): ?>
          <?php h::text($pageTitle) ?>
        <?php else: ?>
          <?php h::msg($pageId . ".title") ?>
        <?php endif ?>
        -
      <?php endif ?>
      <?=htmlspecialchars($config->getTitle())?>
    </title>
    <style>
    @import "<?php h::url("styles/screen.css")?>" screen;
    @import "<?php h::url("styles/print.css")?>" print;
    <?php if ($styles): ?>
      @import "<?=$styles?>";
    <?php endif?>
    </style>
    <!--[if lt IE 9]><script src="<?php h::url("scripts/standards/IE9.js")?>">IE7_PNG_SUFFIX=".png";</script><![endif]-->
    <script src="<?php h::url("scripts/standards/es5-shim.js")?>"></script>
    <script src="<?php h::url("scripts/standards/console-shim.js")?>"></script>
    <script src="<?php h::url("scripts/jquery/jquery.js")?>"></script>
    <script type="text/javascript" src="<?php h::url("scripts/phoolkit.js.php")?>"></script>
    <script src="<?php h::url("scripts/estap.js")?>"></script>
  </head>
  <?php if ($background): ?>
  <body background = "<?php echo $background ?>">
  <?php else: ?>
  <body>
  <?php endif ?>
    <div <?php if (isset($pageId)): ?>id="<?php echo $pageId ?>-page"<?php endif ?> class="page">
      <header>
        <div class="content">
          <h1>
            <?php if ($logo): ?>
              <a href="/"><img src="<?php echo $logo?>" alt="" /></a>
	    <?php endif ?>
            <?=htmlspecialchars($config->getTitle())?>
          </h1>
          <nav>
            <ul>
              <?php if ($session->isParent()): ?>
                <li><a href="<?php h::url("login.php") ?>"><?php h::msg("mainNav.loginAnotherPupil") ?></a></li>
              <?php endif ?>
              <?php if ($session->isLoggedIn()): ?>
                <li><a href="<?php h::url("actions/logout.php") ?>"><?php h::msg("mainNav.logout") ?></a></li>
              <?php endif ?>
            </ul>
          </nav>
        </div>
      </header>
