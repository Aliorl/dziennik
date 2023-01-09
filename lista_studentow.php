<?php
require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';
require_once 'include/Kierunki.php';
require_once 'include/Grupy.php';
require_once 'include/Studenci.php';

$AKTYWNY=basename(__FILE__);
$TRESC="";
$KOMUNIKAT="";

$pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$Kierunki=Kierunki::getInstance();
$Kierunki->setPDO($pdo);
$Studenci=Studenci::getInstance();
$Studenci->setPDO($pdo);
$Grupy=Grupy::getInstance();
$Grupy->setPDO($pdo);

$ID=""; $IMIES=""; $NAZWISKOS=""; $EMAIL=""; $ID_GRUPY=""; $ID_KIERUNKU="";
if (isset($_SESSION['username']))
{
    if (isset($_GET['op']) && $_GET['op']==='0'){
        //edycja
        if(isset($_GET['id'])){
            $student=$Studenci->getStudent((int)$_GET['id']);
            if ($Studenci->getError()){
                $TRESC=$Studenci->getErrorDescription();
                include_once 'szablony/lista_studentow.php';
                exit();
            }
            $ID=$student['id_studenta'];
            $IMIES=$student['imieS'];
            $NAZWISKOS=$student['nazwiskoS'];
            $EMAIL=$student['email'];
            $ID_GRUPY=$student['id_grupy'];
            $ID_KIERUNKU=$student['id_kierunku'];
        }
    }
    if (isset($_GET['op']) && $_GET['op']==='1'){
        //usuń
        var_dump($_REQUEST);
        if(isset($_GET['id'])){
            $Studenci->delete((int)$_GET['id']);
            if ($Studenci->getError()){
                $TRESC=$Studenci->getErrorDescription();
                include_once 'szablony/lista_studentow.php';
                exit();
            }
        }
    }
    if (isset($_POST['dodaj'])){
        //Dodawanie nowej pozycji
        $Studenci->insert($_POST['imieS'], $_POST['nazwiskoS'], $_POST['email'], $_POST['id_grupy'], $_POST['id_kierunku']);
        if ($Studenci->getError()){
            $TRESC=$Studenci->getErrorDescription();
            include_once 'szablony/lista_studentow.php';
            exit();
        }
    }
    if (isset($_POST['zmien'])){
        //Modyfikacja istniejącej pozycji
            $Studenci->update($_POST['id'], $_POST['imieS'], $_POST['nazwiskoS'], $_POST['email'], $_POST['id_grupy'], $_POST['id_kierunku']);
            $ID=$_POST['id'];$IMIES=$_POST['imieS']; $NAZWISKOS=$_POST['nazwiskoS']; $EMAIL=$_POST['email']; $ID_GRUPY=$_POST['id_grupy']; $ID_KIERUNKU=$_POST['id_kierunku'];
            if ($Studenci->getError()){
                $TRESC=$Studenci->getErrorDescription();
                include_once 'szablony/lista_studentow.php';
                exit();
            }
    }
    
}
$STUDENCI=$Studenci->getStudenci();
if ($Studenci->getError()){
    $TRESC=$Studenci->getErrorDescription();
    include_once 'szablony/lista_studentow.php';
    exit();
}

$GRUPY=$Grupy->getGrupy();
    if ($Grupy->getError()){
        $TRESC=$Grupy->getErrorDescription();
        include_once 'szablony/lista_studentow.php';
        exit();
    }

$KIERUNKI=$Kierunki->getKierunki();
if ($Kierunki->getError()){
    $TRESC=$Kierunki->getErrorDescription();
    include_once 'szablony/lista_studentow.php';
    exit();
}

$TRESC=array();
$TRESC[0]="szablony/lista_studentow.php";
include_once 'szablony/lista_studentow.php';