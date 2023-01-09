<?php

/**
 * @author Alicja Orlik
 *
 */

final class Kierunki
{
    private static ?Kierunki $instance = null;
    private $pdo;
    private $is_error;
    private $error_description;
    public $kierunki_array;
    
    
    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): Kierunki
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
     */
    public function insert ($nazwa_kierunku)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('INSERT INTO `kierunki` (`nazwa_kierunku`) VALUES (:nazwa_kierunku);');
            $stmt->bindValue(':nazwa_kierunku', $nazwa_kierunku, PDO::PARAM_STR);
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
     * @param string $nazwa_kierunku
     */
    public function update ($id, $nazwa_kierunku)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('UPDATE `kierunki` SET
                                            `nazwa_kierunku`=:nazwa_kierunku
                                            WHERE id_kierunku=:id_kierunku;');
            $stmt->bindValue(':id_kierunku', $id, PDO::PARAM_INT);
            $stmt->bindValue(':nazwa_kierunku', $nazwa_kierunku, PDO::PARAM_STR);
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
            $stmt = $this->pdo -> prepare ('DELETE FROM `kierunki`
                                            WHERE id_kierunku=:id_kierunku;');
            $stmt->bindValue(':id_kierunku', $id, PDO::PARAM_INT);
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
            $this->error_description="Nie udało się usunąć danych kierunku: ".$e->getMessage();
            return;
        }
        
    }
    /**
     * Metoda pobierająca listę kierunków
     * @param string $order_by default nazwa_kierunku - względem której kolumny sortować wynik
     * @param bool $narastajaco default true - czy sortować narastająco
     */
    public function getKierunki ($order_by="`nazwa_kierunku`", $narastajaco=TRUE)
    {
        $this->kierunki_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $query='SELECT `id_kierunku`, `nazwa_kierunku` FROM `kierunki`';
            $query.='ORDER BY '.$order_by;
            if ($narastajaco) $query.= " ASC ;";
            else $query.= " DESC ;";
            
            $stmt = $this->pdo -> prepare ($query);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->kierunki_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się odczytać bazy danych: ".$e->getMessage();
            return;
        }
        return $this->kierunki_array;
    }
    /**
     * Metoda pobierająca rekord o podanym id
     * @param int $id
     * @param int $nazwa_kierunku
     */
    public function getKierunek ($id)
    {
        $this->kierunki_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('SELECT `id_kierunku`, `nazwa_kierunku` FROM `kierunki`
                                            WHERE id_kierunku=:id_kierunku');
            $stmt->bindValue(':id_kierunku', $id, PDO::PARAM_INT);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->kierunki_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się usunąć danych kierunku: ".$e->getMessage();
            return;
        }
        return $this->kierunki_array[0];
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