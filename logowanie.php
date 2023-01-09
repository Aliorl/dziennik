<?php
require_once 'config/obsluga_sesji.php';
require_once 'config/settings.php';

$AKTYWNY="logowanie.php";
$TRESC="";
$LOGIN="";
$KOMUNIKAT="";

if (isset($_POST['submit']))
{   // Obsługa danych przesyłanych z formularza
    if ($_POST['submit']==="Zaloguj"){
        if (
            (isset($_POST['login']) && $_POST['login']!=="")
            &&
            (isset($_POST['password']) && $_POST['password']!=="")
            )
        {
            try
            {//sprawdzenie, czy jest użytkownik o podanym loginie
                $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName;port=$DBPort", $DBUser, $DBPass);
                $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $stmt = $pdo -> prepare(
                    'SELECT
                        `uzytkownicy`.`imie`,
                        `uzytkownicy`.`nazwisko`,
                        `uzytkownicy`.`haslo`
                    FROM `uzytkownicy`
                        WHERE `uzytkownicy`.`login`= :login;
                        '
                    );
                $stmt->bindValue(':login', $_POST['login']);
                $result = $stmt -> execute();
                if ($stmt->rowCount()>1) throw new PDOException("Błąd w bazie danych. Więcej niż jeden użytkownik o takim samym loginie");
                if ($stmt->rowCount()==0){
                    //Podano błędny login, ponieważ nie ma użytkownika o podanym loginie
                    $KOMUNIKAT="Podano błędny login. Spróbuj jeszcze raz";
                    $LOGIN=$_POST['login'];
                    $TRESC=array();
                    $TRESC[0]="szablony/logowanie.php";
                }
                else
                {
                    $row=$stmt->fetch(PDO::FETCH_ASSOC);
                    if ($row['haslo']== md5($_POST['password'].$TAJNY_KLUCZ))
                    {//Prawidłowe logowanie
                        $_SESSION['username']=$row['imie']." ".$row['nazwisko'];
                        include_once 'szablony/strona_glowna.php';
                    }
                    else
                    {//Błędne hasło
                        $KOMUNIKAT="Podano błędne hasło. Spróbuj jeszcze raz";
                        $LOGIN=$_POST['login'];
                        $TRESC=array();
                        $TRESC[0]="szablony/logowanie.php";
                    }
                    
                }
                $stmt->closeCursor();
            }
            catch(PDOException $e)
            {
                $TRESC.='Wystapil blad biblioteki PDO: ' . $e->getMessage();
            }
            
            
            
        }
        else
        {
            $KOMUNIKAT="Podaj swoje dane do logowania";
            $TRESC=array();
            $TRESC[0]="szablony/logowanie.php";
        }
    }
    else if ($_POST['submit']==="Wyloguj"){
        if (isset($_SESSION['username']))
        {
            unset($_SESSION['username']);
            $KOMUNIKAT="Zostałeś wylogowany";
//             $TRESC=array();
            // $TRESC[0]="szablony/logowanie.php";
        }
        $KOMUNIKAT="Login: Kowalski i hasło: zaq12wsx";
        $TRESC[0]="szablony/logowanie.php";
    }
}
else
{   //Wyświetlenie formularza logowania lub wylogowania
    if (isset($_SESSION['username'])) $KOMUNIKAT="Jesteś zalogowany jako ".$_SESSION['username'];
    $TRESC=array();
    $TRESC[0]="szablony/strona_glowna.php";
}

include 'szablony/logowanie.php';