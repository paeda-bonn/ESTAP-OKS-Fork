<?php 
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * On this page the admin can upload and edit pupil data.
 */

require_once "estap.php";

use ESTAP\Session;
use ESTAP\Pupil;
use PhoolKit\Request;
use PhoolKit\HTML as h;
use PhoolKit\FormatUtils as f;

$search = Request::getParam("search", "");
$rowCount = 50;
$numPupils = Pupil::count($search);
$totalPupils = $search ? Pupil::count() : $numPupils;
$page = intval(Request::getParam("page", 0));
$pages = ceil($numPupils / $rowCount);
$session = Session::get()->requireAdmin();
$pupils = Pupil::search($search, $rowCount, $page);

?>
<?php $pageId = "pupils"; include "parts/header.php" ?>
<?php include "parts/adminNav.php" ?>
<div id="content">
  <h2><?php h::msg("pupils.title") ?></h2>  
  
  <?php h::messages() ?>

  <h3><?php h::msg("pupils.upload.title") ?></h3>
  <p>
    <?php h::msg("pupils.upload.description") ?>
  </p>
  <pre><?php h::msg("pupils.upload.format") ?></pre>
  <p>
    <?php h::msg("pupils.upload.hints", f::toBytes(f::fromBytes(ini_get("upload_max_filesize")))) ?>
  </p>
  <div class="buttons">
    <form action="<?php h::url("actions/uploadPupils.php") ?>" enctype="multipart/form-data" method="post">
      <input type="file" name="pupils" />
      <input type="submit" value="<?php h::msg("pupils.uploadPupils") ?>" />
    </form>
  </div>
  
  <h3><?php h::msg("pupils.current.title") ?></h3>
  <div class="buttons">
    <a href="<?php h::url("addPupil.php") ?>">
      <?php h::msg("pupils.addPupil") ?>
    </a>
    <?php if ($totalPupils): ?>
      <a href="<?php h::url("deletePupils.php") ?>">
        <?php h::msg("pupils.deleteAll") ?>
      </a>
      <form action="<?php h::url("pupils.php")?>" method="GET">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search) ?>" />
        <input type="submit" value="<?php h::msg("pupils.search") ?>" />
      </form>
    <?php endif ?>
  </div>
  
  <?php if ($pupils): ?>
    <?php if ($pages > 1): ?>
      <p class="paging">
        <?php h::paging($page, $pages) ?>
        <span class="info">
          <?php h::msg("pupils.pagingInfo", $rowCount * $page + 1,
              $rowCount * $page + count($pupils), $numPupils) ?>
        </span>      
      </p>
    <?php endif ?>
    <table>
      <tr>
        <th><?php h::msg("pupils.login") ?></th>
        <th><?php h::msg("pupils.class") ?></th>
        <th><?php h::msg("pupils.lastName") ?></th>
        <th><?php h::msg("pupils.firstName") ?></th>
        <th><?php h::msg("pupils.actions") ?></th>
      </tr>
      <?php foreach ($pupils as $pupil):?>
        <tr>
          <td><?php h::text($pupil->getLogin()) ?></td>
          <td><?php h::text($pupil->getClass()) ?></td>
          <td><?php h::text($pupil->getLastName()) ?></td>
          <td><?php h::text($pupil->getFirstName()) ?></td>
          <td class="buttons">
            <a href="<?php h::url("editPupil.php?id=" . $pupil->getId()) ?>">
              <?php h::msg("pupils.edit") ?>
            </a>
            <a href="<?php h::url("deletePupil.php?id=" . $pupil->getId()) ?>">
              <?php h::msg("pupils.delete") ?>
            </a>
            <a href="<?php h::url("pupilAppointments.php?pupil=" . $pupil->getId()) ?>">
              <?php h::msg("pupils.appointments") ?>
            </a>
          </td>
        </tr>
      <?php endforeach?>
    </table>
    <?php if ($pages > 1): ?>
      <p class="paging">
        <?php h::paging($page, $pages) ?>
        <span class="info">
          <?php h::msg("pupils.pagingInfo", $rowCount * $page + 1,
              $rowCount * $page + count($pupils), $numPupils) ?>
        </span>      
      </p>
    <?php endif ?>
  <?php elseif ($totalPupils): ?>
    <p>
      <?php h::msg("pupils.nothingFound") ?>
    </p>
  <?php endif ?>
</div> 
<?php include "parts/footer.php" ?>
