<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * The admin navigation.
 */

use PhoolKit\HTML as h; 

?>
<nav class="teacher">
  <ul>
    <li><a href="<?php h::url("teacherAppointments.php") ?>"><?php h::msg("teacherNav.appointments")?></a></li>
    <li><a href="<?php h::url("editTeacher.php") ?>"><?php h::msg("teacherNav.account")?></a></li>
  </ul>
</nav>
