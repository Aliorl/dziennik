<?php

/**
 * @author Alicja Orlik
 *
 */

final class Stopnie
{
    private static ?Stopnie $instance = null;
    private $pdo;
    private $is_error;
    private $error_description;
    public $stopnie_array;
    
    
    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): Stopnie
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
     * @param string $stopien
     */
    public function insert ($stopien)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('INSERT INTO `stopnie` (`stopien`) VALUES (:stopien);');
            $stmt->bindValue(':stopien', $stopien, PDO::PARAM_INT);
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
     * @param string $stopien
     */
    public function update ($id, $stopien)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('UPDATE `stopnie` SET
                                            `stopien`=:stopien
                                            WHERE id_stopnia=:id_stopnia;');
            $stmt->bindValue(':id_stopnia', $id, PDO::PARAM_INT);
            $stmt->bindValue(':stopien', $stopien, PDO::PARAM_INT);
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
            $stmt = $this->pdo -> prepare ('DELETE FROM `stopnie`
                                            WHERE id_stopnia=:id_stopnia;');
            $stmt->bindValue(':id_stopnia', $id, PDO::PARAM_INT);
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
            $this->error_description="Nie udało się usunąć stopnia: ".$e->getMessage();
            return;
        }
        
    }
    /**
     * Metoda pobierająca listę stopni
     * @param string $order_by default stopien względem której kolumny sortować wynik
     * @param bool $narastajaco default true - czy sortować narastająco
     */
    public function getStopnie ($order_by="`stopien`", $narastajaco=TRUE)
    {
        $this->stopnie_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $query='SELECT `id_stopnia`, `stopien`';
            $query.='FROM `stopnie` ';
            $query.='ORDER BY '.$order_by;
            if ($narastajaco) $query.= " ASC ;";
            else $query.= " DESC ;";
            
            $stmt = $this->pdo -> prepare ($query);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->stopnie_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się odczytać bazy danych: ".$e->getMessage();
            return;
        }
        return $this->stopnie_array;
    }
    /**
     * Metoda pobierająca rekord o podanym id
     * @param int $id
     */
    public function getStopien ($id)
    {
        $this->stopnie_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('SELECT `id_stopnia`, `stopien` FROM `stopnie`
                                            WHERE id_stopnia=:id_stopnia');
            $stmt->bindValue(':id_stopnia', $id, PDO::PARAM_INT);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->stopnie_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się usunąć stopnia: ".$e->getMessage();
            return;
        }
        return $this->stopnie_array[0];
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