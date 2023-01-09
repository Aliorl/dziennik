<?php

/**
 * @author Alicja Orlik
 *
 */

final class Uzytkownicy
{
    private static ?Uzytkownicy $instance = null;
    private $pdo;
    private $is_error;
    private $error_description;
    public $uzytkownicy_array;
    
    
    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): Uzytkownicy
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }
    
    /**
     * is not allowed to call from outside to prevent from creating multiple instances,
     * to use the singleton, you have to obtain the instance from Singleton::getInstance() instead
     */
    private function __construct()
    {
    }
    /**
     * prevent the instance from being cloned (which would create a second instance of it)
     */
    private function __clone()
    {
    }
    /**
     * prevent from being unserialized (which would create a second instance of it)
     */
    public function __wakeup()
    {
    }
    /**
     * Ustawienie połączenia z bazą danych
     * @param PDO object $pdo
     */
    public function setPDO($pdo)
    {
        $this->pdo=$pdo;
        $this->is_error=FALSE;
    }
    /**
     * Metoda dodająca nowy rekord do bazy danych
     * @param string $imie
     * @param string $nazwisko
     * @param string $login
     * @param string $haslo
     * @param int $id_dostepu
     */
    public function insert ($imie, $nazwisko, $login, $haslo, $id_dostepu)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('INSERT INTO `uzytkownicy` (`imie`, `nazwisko`, `login`, `haslo`, `id_dostepu`) VALUES (:imie, :nazwisko, :login, :haslo, :id_dostepu);');
            $stmt->bindValue(':imie', $imie, PDO::PARAM_STR);
            $stmt->bindValue(':nazwisko', $nazwisko, PDO::PARAM_STR);
            $stmt->bindValue(':login', $login, PDO::PARAM_STR);
            $stmt->bindValue(':haslo', $haslo, PDO::PARAM_STR);
            $stmt->bindValue(':id_dostepu', $id_dostepu, PDO::PARAM_INT);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
            }
            $stmt->closeCursor();
            
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się dodać rekordu do bazy danych: ".$e->getMessage();
            return;
        }
    }
    
    /**
     *Metoda modyfikująca dane rekordu o podanym id
     * @param int $id
     * @param string $imie
     * @param string $nazwisko
     * @param string $login
     * @param string $haslo
     * @param int $id_dostepu
     */
    public function update ($id, $imie, $nazwisko, $login, $haslo, $id_dostepu)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('UPDATE `uzytkownicy` SET
                                            `imie`=:imie,
                                            `nazwisko`=:nazwisko,
                                            `login`=:login,
                                            `haslo`=:haslo,
                                            `id_dostepu`=:id_dostepu
                                            WHERE id_uzytkownika=:id_uzytkownika;');
            $stmt->bindValue(':id_uzytkownika', $id, PDO::PARAM_INT);
            $stmt->bindValue(':imie', $imie, PDO::PARAM_STR);
            $stmt->bindValue(':nazwisko', $nazwisko, PDO::PARAM_STR);
            $stmt->bindValue(':login', $login, PDO::PARAM_STR);
            $stmt->bindValue(':haslo', $haslo, PDO::PARAM_INT);
            $stmt->bindValue(':id_dostepu', $id_dostepu, PDO::PARAM_INT);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
            }
            $stmt->closeCursor();
            
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się dodać rekordu do bazy danych: ".$e->getMessage();
            return;
        }
        
    }
    /**
     *Metoda usuwająca rekord o podanym id
     * @param int $id
     */
    public function delete ($id)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('DELETE FROM `uzytkownicy`
                                            WHERE id_uzytkownika=:id_uzytkownika;');
            $stmt->bindValue(':id_uzytkownika', $id, PDO::PARAM_INT);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
            }
            $stmt->closeCursor();
            
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się usunąć danych uzytkownika: ".$e->getMessage();
            return;
        }
        
    }
    /**
     * Metoda pobierająca listę użytkowników
     * @param string $order_by default nazwisko - względem której kolumny sortować wynik
     * @param bool $narastajaco default true - czy sortować narastająco
     */
    public function getUzytkownicy($order_by="`nazwisko`", $narastajaco=TRUE)
    {
        $this->uzytkownicy_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;
        try{
            $query='SELECT `imie`.`nazwisko`, `login`.`haslo`, `id_dostepu`,';
            $query.='FROM `uzytkownicy` ORDER BY '.$order_by;
            if ($narastajaco) $query.= " ASC ;";
            else $query.= " DESC ;";
            
            $stmt = $this->pdo -> prepare ($query);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->uzytkownicy_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się odczytać bazy danych: ".$e->getMessage();
            return;
        }
        return $this->uzytkownicy_array;
        }
    }

    /**
     * Metoda pobierająca rekord o podanym id
     * @param int $id
     */
    public function getUzytkownik ($id)
    {
        $this->uzytkownicy_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('SELECT `imie`.`nazwisko`, `login`.`haslo`, `id_dostepu` FROM `uzytkownicy` 
                                            WHERE id_uzytkownika=:id_uzytkownika');
            $stmt->bindValue(':id_uzytkownika', $id, PDO::PARAM_INT);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->uzytkownicy_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się usunąć danych uzytkownika: ".$e->getMessage();
            return;
        }
        return $this->uzytkownicy_array[0];
    }
    /**
     *Metoda pobierająca status błędu
     */
    public function getError ()
    {
        $error=$this->is_error;
        $this->is_error=FALSE;
        return $error;
    }
    /**
     *Metoda pobierająca opis błędu
     */
    public function getErrorDescription ()
    {
        $error_description=$this->error_description;
        $this->error_description="";
        return $error_description;}
}