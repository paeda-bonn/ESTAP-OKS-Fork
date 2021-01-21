<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page all pupils can be deleted.
 */

require_once "estap.php";

use ESTAP\Session;
use PhoolKit\HTML as h;

$session = Session::get()->requireAdmin();

?>
<?php $pageId = "deletePupils";
include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
    <h2><?php h::msg("deletePupils.title") ?></h2>
    <?php h::messages() ?>
    <p>
        <?php h::msg("deletePupils.question") ?>
    </p>

    <div class="buttons">
        <form action="<?php h::url("actions/deletePupils.php") ?>" method="post" novalidate>
            <input type="submit" value="<?php h::msg("deletePupils.confirm") ?>"/>
        </form>
        <a href="<?php h::url("pupils.php") ?>">
            <?php h::msg("deletePupils.cancel") ?>
        </a>
    </div>
</div>
<?php include "parts/footer.php" ?>
