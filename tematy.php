<?php
require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';
require_once 'include/Tematy.php';
require_once 'include/Prowadzacy.php';
require_once 'include/Zajecia.php';

$AKTYWNY=basename(__FILE__);
$TRESC="";
$KOMUNIKAT="";

$pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$Tematy=Tematy::getInstance();
$Tematy->setPDO($pdo);
$Prowadzacy=Prowadzacy::getInstance();
$Prowadzacy->setPDO($pdo);
$Zajecia=Zajecia::getInstance();
$Zajecia->setPDO($pdo);

$ID=""; $TEMAT=""; $ID_PROWADZACEGO=""; $ID_ZAJEC="";
if (isset($_SESSION['username']))
{
    if (isset($_GET['op']) && $_GET['op']==='0'){
        //edycja
        if(isset($_GET['id'])){
            $temat=$Tematy->getTemat((int)$_GET['id']);
            if ($Tematy->getError()){
                $TRESC=$Tematy->getErrorDescription();
                include_once 'szablony/tematy.php';
                exit();
            }
            $ID=$temat['id_tematu'];
            $TEMAT=$temat['temat'];
            $ID_PROWADZACEGO=$temat['id_prowadzacego'];
            $ID_ZAJEC=$temat['id_zajec'];
        }
    }
    if (isset($_GET['op']) && $_GET['op']==='1'){
        //usuń
        var_dump($_REQUEST);
        if(isset($_GET['id'])){
            $Tematy->delete((int)$_GET['id']);
            if ($Tematy->getError()){
                $TRESC=$Tematy->getErrorDescription();
                include_once 'szablony/tematy.php';
                exit();
            }
        }
    }
    if (isset($_POST['dodaj'])){
        //Dodawanie nowej pozycji
        $Tematy->insert($_POST['temat'], $_POST['id_prowadzacego'], $_POST['id_zajec']);
        if ($Tematy->getError()){
            $TRESC=$Tematy->getErrorDescription();
            include_once 'szablony/tematy.php';
            exit();
        }
    }
    if (isset($_POST['zmien'])){
        //Modyfikacja istniejącej pozycji
        $Tematy->update($_POST['id'], $_POST['temat'], $_POST['id_prowadzacego'], $_POST['id_zajec']);
        $ID_ZAJEC=$_POST['id_zajec']; $ID_PROWADZACEGO=$_POST['id_prowadzacego']; $TEMAT=$_POST['temat']; $ID=$_POST['id'];
        if ($Tematy->getError()){
            $TRESC=$Tematy->getErrorDescription();
            include_once 'szablony/tematy.php';
            exit();
        }
    }
    
}
$TEMATY=$Tematy->getTematy();//zmienna szablonowa, do której zapisujemy listę tematów, gdy wykonamy powyższe operacje
$TRESC=array();
$TRESC[0]="szablony/tematy.php";

$PROWADZACY=$Prowadzacy->getProwadzacyList();//zmienna szablonowa, do której zapisujemy listę prowadzących, gdy wykonamy powyższe operacje
$TRESC=array();
$TRESC[0]="szablony/tematy.php";

$ZAJECIA=$Zajecia->getZajeciaList();//zmienna szablonowa, do której zapisujemy listę zajęć, gdy wykonamy powyższe operacje
$TRESC=array();
$TRESC[0]="szablony/tematy.php";

include_once 'szablony/tematy.php';