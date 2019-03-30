<?php 
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * The page footer.
 */

use PhoolKit\HTML as h 

?>
      <footer>
        <div class="content">       
          <nav>
            <ul>
              <li><a href="<?php h::url("login.php") ?>"><?php h::msg("subNav.parents") ?></a></li>
              <li><a href="<?php h::url("teacherAppointments.php") ?>"><?php h::msg("subNav.teachers") ?></a></li>
              <li><a href="<?php h::url("admins.php") ?>"><?php h::msg("subNav.admins") ?></a></li>
            </ul>
          </nav>
        </div>
        <div class="copyright">
          <a target="_blank" href="<?php h::url("http://bitbucket.org/acg-bonn/estap") ?>">ESTAP</a>
          <?php h::msg("copyright", date('Y')) ?>
          <?php h::msg("copyright-annotation")?>
          <?php h::msg("copyright-annotation-name")?>
        </div>
      </footer>
    </div>
  </body>
</html>