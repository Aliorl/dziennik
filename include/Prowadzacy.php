<?php

/**
 * @author Alicja Orlik
 *
 */

final class Prowadzacy
{
    private static ?Prowadzacy $instance = null;
    private $pdo;
    private $is_error;
    private $error_description;
    public $prowadzacy_array;
    
    
    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): Prowadzacy
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
        $this->is_error=FALSE; //domyślna wartość informująca, że nie wystąpił błąd przy operacjach na bazie danych
    }
    /**
     * Metoda dodająca nowy rekord do bazy danych
     * @param string $imie
     * @param string $nazwisko
     * @param string $stopien_naukowy
     */
    public function insert ($imie, $nazwisko, $stopien_naukowy)
    {//sprawdzamy, czy mamy połączenie z bazą danych
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('INSERT INTO `prowadzacy` (`imie`, `nazwisko`, `stopien_naukowy`) VALUES (:imie, :nazwisko, :stopien_naukowy);');
            $stmt->bindValue(':imie', $imie, PDO::PARAM_STR);
            $stmt->bindValue(':nazwisko', $nazwisko, PDO::PARAM_STR);
            $stmt->bindValue(':stopien_naukowy', $stopien_naukowy, PDO::PARAM_STR);
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
     * @param string $stopien_naukowy
     */
    public function update ($id, $imie, $nazwisko, $stopien_naukowy)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('UPDATE `prowadzacy` SET
                                            `imie`=:imie,
                                            `nazwisko`=:nazwisko,
                                            `stopien_naukowy`=:stopien_naukowy
                                            WHERE id_prowadzacego=:id_prowadzacego;');
            $stmt->bindValue(':id_prowadzacego', $id, PDO::PARAM_INT);
            $stmt->bindValue(':imie', $imie, PDO::PARAM_STR);
            $stmt->bindValue(':nazwisko', $nazwisko, PDO::PARAM_STR);
            $stmt->bindValue(':stopien_naukowy', $stopien_naukowy, PDO::PARAM_STR);
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
            $stmt = $this->pdo -> prepare ('DELETE FROM `prowadzacy`
                                            WHERE id_prowadzacego=:id_prowadzacego;');
            $stmt->bindValue(':id_prowadzacego', $id, PDO::PARAM_INT);
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
            $this->error_description="Nie udało się usunąć danych prowadzącego: ".$e->getMessage();
            return;
        }
        
    }
    /**
     * Metoda pobierająca listę prowadzących
     * @param string $order_by default nazwisko - względem której kolumny sortować wynik
     * @param bool $narastajaco default true - czy sortować narastająco
     */
    public function getProwadzacyList($order_by="`nazwisko`", $narastajaco=TRUE)
    {
        $this->prowadzacy_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $query='SELECT `id_prowadzacego`, `imie`, `nazwisko`, `stopien_naukowy` FROM `prowadzacy`';
            $query.='ORDER BY '.$order_by;
            if ($narastajaco) $query.= " ASC ;";
            else $query.= " DESC ;";
            
            $stmt = $this->pdo -> prepare ($query);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->prowadzacy_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się odczytać bazy danych: ".$e->getMessage();
            return;
        }
        return $this->prowadzacy_array; //zwrócenie tablicy pobranych rekordów
    }
    /**
     * Metoda pobierająca rekord o podanym id
     * @param int $id
     */
    public function getProwadzacy ($id, $imie, $nazwisko, $stopien_naukowy)
    {
        $this->prowadzacy_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('SELECT `id_prowadzacego`, `imie`, `nazwisko`, `stopien_naukowy` FROM `prowadzacy`
                                            WHERE id_prowadzacego=:id_prowadzacego');
            $stmt->bindValue(':id_prowadzacego', $id, PDO::PARAM_INT);
            $stmt->bindValue(':imie', $imie, PDO::PARAM_STR);
            $stmt->bindValue(':nazwisko', $nazwisko, PDO::PARAM_STR);
            $stmt->bindValue(':stopien_naukowy', $stopien_naukowy, PDO::PARAM_STR);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->prowadzacy_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się usunąć danych prowadzącego: ".$e->getMessage();
            return;
        }
        return $this->prowadzacy_array[0]; //zwrócenie wartości pojedyńczego rekordu
    }
    /**
     *Metoda pobierająca status błędu
     */
    public function getError ()
    {
        $error=$this->is_error; //ustawienie roboczej zmiennej error na wartość naszej zmiennej prywatnej
        $this->is_error=FALSE; //kasowanie wartości o błędzie
        return $error; //zwrócenie wartości zmiennej error
    }//w ten sposób metodagetError zwróci nam informację,
    //czy wystąpił błąd do tej pory wykonywanych czynności na obiekcie danej klasy, skasuje ten błąd
    //i ustawi jako nowy błąd
    /**
     *Metoda pobierająca opis błędu
     */
    public function getErrorDescription ()
    {
        $error_description=$this->error_description;//zapisywanie opisu błędu w zmiennej lokalnej
        $this->error_description="";//skasowanie prywatnej zmiennej związanej z opisem błędu
        return $error_description;}//zwrócenie wartości lokalnej zmiennej
}