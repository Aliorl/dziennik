<?php
require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';
require_once 'include/Oceny.php';
require_once 'include/Stopnie.php';
require_once 'include/Rodzaje_ocen.php';
require_once 'include/Zajecia.php';
require_once 'include/Semestry.php';
require_once 'include/Prowadzacy.php';
require_once 'include/Studenci.php';

$AKTYWNY=basename(__FILE__);
$TRESC="";
$KOMUNIKAT="";

$pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$Oceny=Oceny::getInstance();
$Oceny->setPDO($pdo);
$Stopnie=Stopnie::getInstance();
$Stopnie->setPDO($pdo);
$Rodzaje_ocen=Rodzaje_ocen::getInstance();
$Rodzaje_ocen->setPDO($pdo);
$Zajecia=Zajecia::getInstance();
$Zajecia->setPDO($pdo);
$Semestry=Semestry::getInstance();
$Semestry->setPDO($pdo);
$Prowadzacy=Prowadzacy::getInstance();
$Prowadzacy->setPDO($pdo);
$Studenci=Studenci::getInstance();
$Studenci->setPDO($pdo);

$ID=""; $OCENA=""; $RODZAJ_OCENY=""; $ID_ZAJEC=""; $ID_SEMESTRU=""; $ID_PROWADZACEGO=""; $ID_STUDENTA="";
if (isset($_SESSION['username']))
{
    if (isset($_GET['op']) && $_GET['op']==='0'){
        //edycja
        if(isset($_GET['id'])){
            $ocena=$Oceny->getOcena((int)$_GET['id']);
            $ID=$ocena['id_oceny'];
            $OCENA=$ocena['id_stopnia'];
            $RODZAJ_OCENY=$ocena['id_rodzaju_ocen'];
            $ID_ZAJEC=$ocena['id_zajec'];
            $ID_SEMESTRU=$ocena['id_semestru'];
            $ID_PROWADZACEGO=$ocena['id_prowadzacego'];
            $ID_STUDENTA=$ocena['id_studenta'];
        }
    }
    if (isset($_GET['op']) && $_GET['op']==='1'){
        //usuń
        if(isset($_GET['id'])){
            $Oceny->delete((int)$_GET['id']);
        }
    }
    if (isset($_POST['dodaj'])){
        //Dodawanie nowej pozycji
        $Oceny->insert($_POST['id_zajec'], $_POST['id_prowadzacego'], $_POST['id_semestru'], $_POST['id_studenta'], $_POST['id_rodzaju_ocen'], $_POST['id_stopnia']);
        if ($Oceny->getError()){
            $TRESC=$Oceny->getErrorDescription();
            include_once 'szablony/oceny.php';
            exit();
        }
    }
    if (isset($_POST['zmien'])){
        //Modyfikacja istniejącej pozycji
        $Oceny->update($_POST['id'], $_POST['id_zajec'], $_POST['id_prowadzacego'], $_POST['id_semestru'], $_POST['id_studenta'], $_POST['id_rodzaju_ocen'], $_POST['id_stopnia']);
        $OCENA=$_POST['id_stopnia']; $RODZAJ_OCENY=$_POST['id_rodzaju_ocen']; $ID_STUDENTA=$_POST['id_studenta']; $ID_SEMESTRU=$_POST['id_semestru']; $ID_PROWADZACEGO=$_POST['id_prowadzacego']; $ID_ZAJEC=$_POST['id_zajec']; $ID=$_POST['id'];
        if ($Oceny->getError()){
            $TRESC=$Oceny->getErrorDescription();
            include_once 'szablony/oceny.php';
            exit();
        }
    }
}
$OCENY=$Oceny->getOceny();//zmienna szablonowa, do której zapisujemy listę ocen, gdy wykonamy powyższe operacje
$TRESC=array();
$TRESC[0]="szablony/oceny.php";

$STOPNIE=$Stopnie->getStopnie();//zmienna szablonowa, do której zapisujemy listę ocen, gdy wykonamy powyższe operacje
$TRESC=array();
$TRESC[0]="szablony/oceny.php";

$RODZAJE_OCEN=$Rodzaje_ocen->getRodzaje_ocen();//zmienna szablonowa, do której zapisujemy listę rodzajów ocen, gdy wykonamy powyższe operacje
$TRESC=array();
$TRESC[0]="szablony/oceny.php";

$ZAJECIA=$Zajecia->getZajeciaList();//zmienna szablonowa, do której zapisujemy listę zajęć, gdy wykonamy powyższe operacje
$TRESC=array();
$TRESC[0]="szablony/oceny.php";

$SEMESTRY=$Semestry->getSemestry();//zmienna szablonowa, do której zapisujemy listę semestrów, gdy wykonamy powyższe operacje
$TRESC=array();
$TRESC[0]="szablony/oceny.php";

$PROWADZACY=$Prowadzacy->getProwadzacyList();//zmienna szablonowa, do której zapisujemy listę prowadzących, gdy wykonamy powyższe operacje
$TRESC=array();
$TRESC[0]="szablony/oceny.php";

$STUDENCI=$Studenci->getStudenci();//zmienna szablonowa, do której zapisujemy listę zajęć, gdy wykonamy powyższe operacje
$TRESC=array();
$TRESC[0]="szablony/oceny.php";

include_once 'szablony/oceny.php';