<?php 
/*
 * Copyright 2014 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page the admin can add/delete/edit admin accounts.
 */

require_once "estap.php";

use ESTAP\Session;
use ESTAP\Admin;
use PhoolKit\HTML as h;
use PhoolKit\FormatUtils as f;

$session = Session::get()->requireAdmin();
$admins = Admin::getAll();

?>
<?php $pageId = "admins"; include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
  <h2><?php h::msg("admins.title") ?></h2>  
  
  <?php h::messages() ?>

  <div class="buttons">
    <a href="<?php h::url("addAdmin.php") ?>">
      <?php h::msg("admins.addAdmin") ?>
    </a>
  </div>
  
  <?php if ($admins): ?>
    <table>
      <tr>
        <th><?php h::msg("admins.login") ?></th>
        <th><?php h::msg("admins.lastName") ?></th>
        <th><?php h::msg("admins.firstName") ?></th>
        <th class="buttons"><?php h::msg("admins.action") ?></th>
      </tr>
      <?php foreach (Admin::getAll() as $admin):?>
        <tr>
          <td><?php h::text($admin->getLogin()) ?></td>
          <td><?php h::text($admin->getLastName()) ?></td>
          <td><?php h::text($admin->getFirstName()) ?></td>
          <td class="buttons">
            <?php if (($session->getAdmin()->getLogin() == "admin")||($admin->getLogin() != "admin")): ?>
              <a href="<?php h::url("editAdmin.php?id=" . $admin->getId()) ?>">
                <?php h::msg("admins.edit") ?>
              </a>
            <?php endif ?>  
            <?php if (($admin->getId() != $session->getAdmin()->getId()) & ($admin->getLogin() != "admin")): ?>
              <a href="<?php h::url("deleteAdmin.php?id=" . $admin->getId()) ?>">
                <?php h::msg("admins.delete") ?>
              </a>
            <?php endif ?>
            </td>
        </tr>
      <?php endforeach?>
    </table>
  <?php endif ?>
  
</div> 
<?php include "parts/footer.php" ?>
