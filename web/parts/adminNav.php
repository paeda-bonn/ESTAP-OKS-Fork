<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 *
 * The admin navigation.
 */

use PhoolKit\HTML as h;

?>
<nav class="admin">
  <ul>
    <li><a href="<?php h::url("admins.php") ?>"><?php h::msg("adminNav.admins")?></a></li>
    <li><a href="<?php h::url("pupils.php") ?>"><?php h::msg("adminNav.pupils")?></a></li>
    <li><a href="<?php h::url("teachers.php") ?>"><?php h::msg("adminNav.teachers")?></a></li>
    <li><a href="<?php h::url("timeSlots.php") ?>"><?php h::msg("adminNav.timeSlots")?></a></li>
    <li><a href="<?php h::url("settings.php") ?>"><?php h::msg("adminNav.settings")?></a></li>
    <li><a href="<?php h::url("styles.php") ?>"><?php h::msg("adminNav.styles")?></a></li>
  </ul>
</nav>
