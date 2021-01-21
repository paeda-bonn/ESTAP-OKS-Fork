<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page a single pupil can be deleted.
 */

require_once "estap.php";

use ESTAP\Pupil;
use ESTAP\Session;
use PhoolKit\HTML as h;

$session = Session::get()->requireAdmin();
$pupilId = +$_REQUEST["id"];
$pupil = Pupil::getById($pupilId);

?>
<?php $pageId = "deletePupil";
include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
    <h2><?php h::msg("deletePupil.title") ?></h2>
    <?php h::messages() ?>
    <p>
        <?php h::msg("deletePupil.question", $pupil->getName(Pupil::FIRST_LAST)) ?>
    </p>

    <div class="buttons">
        <form action="<?php h::url("actions/deletePupil.php") ?>" method="post" novalidate>
            <input type="hidden" name="id" value="<?php echo $pupilId ?>"/>
            <input type="submit" value="<?php h::msg("deletePupil.confirm") ?>"/>
        </form>
        <a href="<?php h::url("pupils.php") ?>">
            <?php h::msg("deletePupil.cancel") ?>
        </a>
    </div>
</div>
<?php include "parts/footer.php" ?>
