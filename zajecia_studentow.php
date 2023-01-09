<?php
require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';
require_once 'include/Grupy.php';

$AKTYWNY="zajecia_studentow.php";
$TRESC="";

$pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$Grupy=Grupy::getInstance();
$Grupy->setPDO($pdo);

if (isset($_POST['id_grupy'])){

try
{
    $stmt = $pdo -> prepare(
        'SELECT `nazwa_zajec`, `rodzaj_zajec`, prowadzacy.`imie`, prowadzacy.`nazwisko`, prowadzacy.`stopien_naukowy`
        FROM `zajecia`
        JOIN `prowadzacy` ON zajecia.`id_prowadzacego`=prowadzacy.`id_prowadzacego`
        JOIN `kierunki` ON kierunki.`id_kierunku`=zajecia.`id_kierunku`
        JOIN `studenci` ON studenci.`id_kierunku`=zajecia.`id_kierunku`
        JOIN `grupy` ON grupy.`id_grupy`=studenci.`id_grupy`
        WHERE grupy.`id_grupy`='.$_POST['id_grupy'].'
        GROUP BY `nazwa_zajec`'
        );
    $result = $stmt -> execute();
    $rows=$stmt->fetchAll();
    $stmt->closeCursor();
}
catch(PDOException $e)
{
    $TRESC.='Wystapil blad biblioteki PDO: ' . $e->getMessage();
}
}
else 
    try
{
    $stmt = $pdo -> prepare(
        'SELECT `nazwa_zajec`, `rodzaj_zajec`, prowadzacy.`imie`, prowadzacy.`nazwisko`, prowadzacy.`stopien_naukowy`
        FROM `zajecia`
        JOIN `prowadzacy` ON zajecia.`id_prowadzacego`=prowadzacy.`id_prowadzacego`
        JOIN `kierunki` ON kierunki.`id_kierunku`=zajecia.`id_kierunku`
        JOIN `studenci` ON studenci.`id_kierunku`=zajecia.`id_kierunku`
        JOIN `grupy` ON grupy.`id_grupy`=studenci.`id_grupy`
        WHERE grupy.`id_grupy`=1
        GROUP BY `nazwa_zajec`'
        );
    $result = $stmt -> execute();
    $rows=$stmt->fetchAll();
    $stmt->closeCursor();
}
catch(PDOException $e)
{
    $TRESC.='Wystapil blad biblioteki PDO: ' . $e->getMessage();
}
$GRUPY=$Grupy->getGrupy();
if ($Grupy->getError()){
    $TRESC=$Grupy->getErrorDescription();
    include_once 'szablony/zajecia_studentow.php';
    exit();
}
include_once 'szablony/zajecia_studentow.php';