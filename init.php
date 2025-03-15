<?php
$defaultLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$iso = $defaultLang;
include( _RTT_TRANSLATIONS_DIR_.$iso.'/errors.php');
include( _RTT_TRANSLATIONS_DIR_.$iso.'/fields.php');
include( _RTT_TRANSLATIONS_DIR_.$iso.'/admin.php');

?>