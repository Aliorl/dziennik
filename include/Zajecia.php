<?php

/**
 * @author Alicja Orlik
 *
 */

final class Zajecia
{
    private static ?Zajecia $instance = null;
    private $pdo;
    private $is_error;
    private $error_description;
    public $zajecia_array;
    
    
    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): Zajecia
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
     * @param string $nazwa_zajec
     * @param string $rodzaj_zajec
     * @param int $id_kierunku
     * @param int $id_prowadzacego
     */
    public function insert ($nazwa_zajec, $rodzaj_zajec, $id_kierunku, $id_prowadzacego)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('INSERT INTO `zajecia` (`nazwa_zajec`, `rodzaj_zajec`, `id_kierunku`, `id_prowadzacego`) VALUES (:nazwa_zajec, :rodzaj_zajec, :id_kierunku, :id_prowadzacego');
            $stmt->bindValue(':nazwa_zajec', $nazwa_zajec, PDO::PARAM_STR);
            $stmt->bindValue(':rodzaj_zajec', $rodzaj_zajec, PDO::PARAM_STR);
            $stmt->bindValue(':id_kierunku', $id_kierunku, PDO::PARAM_INT);
            $stmt->bindValue(':id_prowadzacego', $id_prowadzacego, PDO::PARAM_INT);
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
     * @param string $nazwa_zajec
     * @param string $rodzaj_zajec
     * @param int $id_kierunku
     * @param int $id_prowadzacego
     */
    public function update ($id, $nazwa_zajec, $rodzaj_zajec, $id_kierunku, $id_prowadzacego)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('UPDATE `zajecia` SET
                                            `nazwa_zajec`=:nazwa_zajec,
                                            `rodzaj_zajec`=:rodzaj_zajec,
                                            `id_kierunku`=:id_kierunku,
                                            `id_prowadzacego`=:id_prowadzacego
                                            WHERE id_zajec=:id_zajec;');
            $stmt->bindValue(':id_zajec', $id, PDO::PARAM_INT);
            $stmt->bindValue(':nazwa_zajec', $nazwa_zajec, PDO::PARAM_STR);
            $stmt->bindValue(':rodzaj_zajec', $rodzaj_zajec, PDO::PARAM_STR);
            $stmt->bindValue(':id_kierunku', $id_kierunku, PDO::PARAM_INT);
            $stmt->bindValue(':id_prowadzacego', $id_prowadzacego, PDO::PARAM_INT);
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
            $stmt = $this->pdo -> prepare ('DELETE FROM `zajecia`
                                            WHERE id_zajec=:id_zajec;');
            $stmt->bindValue(':id_zajec', $id, PDO::PARAM_STR);
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
            $this->error_description="Nie udało się usunąć danych zajęć: ".$e->getMessage();
            return;
        }
        
    }
    /**
     * Metoda pobierająca listę zajęć
     * @param string $order_by default nazwie zajęć - względem której kolumny sortować wynik
     * @param bool $narastajaco default true - czy sortować narastająco
     */
    public function getZajeciaList($order_by="`nazwa_zajec`", $narastajaco=TRUE)
    {
        $this->zajecia_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $query='SELECT `id_zajec`, `nazwa_zajec`, `rodzaj_zajec`, `id_kierunku`, `id_prowadzacego` FROM `zajecia`';
            $query.='ORDER BY '.$order_by;
            if ($narastajaco) $query.= " ASC ;";
            else $query.= " DESC ;";
            
            $stmt = $this->pdo -> prepare ($query);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->zajecia_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się odczytać bazy danych: ".$e->getMessage();
            return;
        }
        return $this->zajecia_array;
    }
    /**
     * Metoda pobierająca rekord o podanym id
     * @param int $id
     */
    public function getZajeciaElement ($id)
    {
        $this->zajecia_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('SELECT `id_zajec`, `nazwa_zajec`, `rodzaj_zajec`, `id_kierunku`, `id_prowadzacego` FROM `zajecia`
                                            WHERE id_zajec=:id_zajec');
            $stmt->bindValue(':id_zajec', $id, PDO::PARAM_INT);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->zajecia_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się usunąć danych zajęć: ".$e->getMessage();
            return;
        }
        return $this->zajecia_array[0];
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