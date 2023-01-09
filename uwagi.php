<?php
require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';
require_once 'include/Uwagi.php';
require_once 'include/Prowadzacy.php';
require_once 'include/Studenci.php';

$AKTYWNY=basename(__FILE__);
$TRESC="";
$KOMUNIKAT="";

$pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$Uwagi=Uwagi::getInstance();
$Uwagi->setPDO($pdo);
$Prowadzacy=Prowadzacy::getInstance();
$Prowadzacy->setPDO($pdo);
$Studenci=Studenci::getInstance();
$Studenci->setPDO($pdo);

$ID=""; $UWAGA=""; $ID_PROWADZACEGO=""; $ID_STUDENTA="";
if (isset($_SESSION['username']))
{
    if (isset($_GET['op']) && $_GET['op']==='0'){
        //edycja
        if(isset($_GET['id'])){
            $uwaga=$Uwagi->getUwaga((int)$_GET['id']);
            $ID=$uwaga['id_uwagi'];
            $UWAGA=$uwaga['tresc'];
            $ID_PROWADZACEGO=$uwaga['id_prowadzacego'];
            $ID_STUDENTA=$uwaga['id_studenta'];
        }
    }
    if (isset($_GET['op']) && $_GET['op']==='1'){
        //usuń
        if(isset($_GET['id'])){
            $Uwagi->delete((int)$_GET['id']);
        }
    }
    if (isset($_POST['dodaj'])){
        //Dodawanie nowej pozycji
        $Uwagi->insert($_POST['tresc'], $_POST['id_prowadzacego'], $_POST['id_studenta']);
        if ($Uwagi->getError()){
            $TRESC=$Uwagi->getErrorDescription();
            include_once 'szablony/uwagi.php';
            exit();
        }
    }
    if (isset($_POST['zmien'])){
        //Modyfikacja istniejącej pozycji
        $Uwagi->update($_POST['id'], $_POST['tresc'], $_POST['id_prowadzacego'], $_POST['id_studenta']);
        $ID_STUDENTA=$_POST['id_studenta']; $ID_PROWADZACEGO=$_POST['id_prowadzacego']; $UWAGA=$_POST['tresc']; $ID=$_POST['id'];
        if ($Uwagi->getError()){
            $TRESC=$Uwagi->getErrorDescription();
            include_once 'szablony/uwagi.php';
            exit();
        }
    }
}
$UWAGI=$Uwagi->getUwagi();//zmienna szablonowa, do której zapisujemy listę uwag, gdy wykonamy powyższe operacje
$TRESC=array();
$TRESC[0]="szablony/uwagi.php";

$PROWADZACY=$Prowadzacy->getProwadzacyList();//zmienna szablonowa, do której zapisujemy listę prowadzących, gdy wykonamy powyższe operacje
$TRESC=array();
$TRESC[0]="szablony/uwagi.php";

$STUDENCI=$Studenci->getStudenci();//zmienna szablonowa, do której zapisujemy listę zajęć, gdy wykonamy powyższe operacje
$TRESC=array();
$TRESC[0]="szablony/uwagi.php";

include_once 'szablony/uwagi.php';