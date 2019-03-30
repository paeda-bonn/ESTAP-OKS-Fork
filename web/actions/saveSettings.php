<?php
/*
 * Copyright 2014 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information.
 *
 * This action saves the ESTAP settings.
 */

require_once "../estap.php";

use PhoolKit\Request;
use PhoolKit\Messages;
use PhoolKit\I18N;
use ESTAP\Config;
use ESTAP\Forms\ConfigForm;
use ESTAP\Session;
use ESTAP\Exceptions\ConfigException;

$form = ConfigForm::parse("../settings.php");
$session = Session::get()->requireAdmin();
$config = Config::get();
$config->setParentLoginEnabled(!!$form->parentLoginEnabled);
$config->setTeacherLoginEnabled(!!$form->teacherLoginEnabled);
$config->setReservationEnabled(!!$form->reservationEnabled);
$config->setDuplicatesEnabled(!!$form->duplicatesEnabled);
$config->setLocales(preg_split("/[\\s,]+/", $form->locales));
$config->setDefaultLocale($form->defaultLocale);
$values = preg_split("/[\\s,]+/", $form->timeSlotDurations);
array_walk($values, function(&$a) { $a = intval($a); });
$config->setTimeSlotDurations($values);
$config->setDefaultTimeSlotDuration(intval($form->defaultTimeSlotDuration));
$config->setMinPasswordLength(intval($form->minPasswordLength));
$config->setMinTimeSlotStartHour(intval($form->minTimeSlotStartHour));
$config->setMaxTimeSlotEndHour(intval($form->maxTimeSlotEndHour));
$config->setReservationStartDay(intval($form->reservationStartDay));
$config->setReservationStartMonth(intval($form->reservationStartMonth));
$config->setReservationStartYear(intval($form->reservationStartYear));
$config->setReservationStartHour(intval($form->reservationStartHour));
$config->setReservationStartMinute(intval($form->reservationStartMinute));
$config->setReservationEndDay(intval($form->reservationEndDay));
$config->setReservationEndMonth(intval($form->reservationEndMonth));
$config->setReservationEndYear(intval($form->reservationEndYear));
$config->setReservationEndHour(intval($form->reservationEndHour));
$config->setReservationEndMinute(intval($form->reservationEndMinute));
foreach ($form->title as $locale => $title)
    $config->setTitle($locale, $title);
foreach ($form->greeting as $locale => $greeting)
    $config->setGreeting($locale, $greeting);

try
{
    $start = $form->reservationStartYear . '.' .
      str_pad($form->reservationStartMonth,2,'0',STR_PAD_LEFT) . '.' .
      str_pad($form->reservationStartDay,2,'0',STR_PAD_LEFT) . '.' .
      str_pad($form->reservationStartHour,2,'0',STR_PAD_LEFT) . '.' .
      str_pad($form->reservationStartMinute,2,'0',STR_PAD_LEFT);
    $stop = $form->reservationEndYear . '.' .
      str_pad($form->reservationEndMonth,2,'0',STR_PAD_LEFT) . '.' .
      str_pad($form->reservationEndDay,2,'0',STR_PAD_LEFT) . '.' .
      str_pad($form->reservationEndHour,2,'0',STR_PAD_LEFT) . '.' .
      str_pad($form->reservationEndMinute,2,'0',STR_PAD_LEFT);
    if ($stop <= $start):
      Messages::addError(I18N::getMessage("settings.emptyTimeSlot"));
      throw new Exception();
    endif;
    $config->setLogo($form->deleteLogo ? null : $_FILES["logo"]);
    $config->setBackground($form->deleteBackground ? null : $_FILES["background"]);
    $config->save();
    Messages::addInfo(I18N::getMessage("settings.saved"));
    Request::redirect("../settings.php");
}
catch (ConfigException $e)
{
    Messages::addError(I18N::getMessage("settings.cantWriteConfig"));
    include "../settings.php";
}
catch (Exception $e)
{
    Messages::addError($e->getMessage());
    include "../settings.php";
}
