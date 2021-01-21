<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 *
 * On this page the admin can edit the custom CSS of the application.
 */

require_once "estap.php";

use ESTAP\Config;
use ESTAP\Session;
use PhoolKit\HTML as h;

$session = Session::get()->requireAdmin();
$config = Config::get();

?>
<?php $pageId = "styles";
include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
    <h2><?php h::msg("styles.title") ?></h2>
    <?php h::messages() ?>

    <p><?php h::msg("styles.help") ?></p>

    <form action="<?php h::url("actions/saveStyles.php") ?>" method="post" novalidate>
        <textarea name="css"><?= htmlspecialchars($config->getStyles()) ?></textarea>
        <input type="submit" value="<?php h::msg("styles.save") ?>"/>
    </form>

</div>
<?php include "parts/footer.php" ?>
