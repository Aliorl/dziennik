<?php

require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';
require_once 'include/Studenci.php';

$AKTYWNY=basename(__FILE__);
$TRESC="";
$KOMUNIKAT="";

$pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$Studenci=Studenci::getInstance();
$Studenci->setPDO($pdo);

$STUDENCI=$Studenci->getStudenci();
if ($Studenci->getError()){
    $TRESC=$Studenci->getErrorDescription();
    include_once 'szablony/frekwencja.php';
    exit();
}
include_once 'szablony/frekwencja.php';