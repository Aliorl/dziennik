<?php

/**
 * @author Alicja Orlik
 *
 */

final class Rodzaje_ocen
{
    private static ?Rodzaje_ocen $instance = null;
    private $pdo;
    private $is_error;
    private $error_description;
    public $rodzaje_ocen_array;
    
    
    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): Rodzaje_ocen
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
     * @param string $rodzaj_oceny
     */
    public function insert ($rodzaj_oceny)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('INSERT INTO `rodzaje_ocen` (`rodzaj_oceny`) VALUES (:rodzaj_oceny);');
            $stmt->bindValue(':rodzaj_oceny', $rodzaj_oceny, PDO::PARAM_STR);
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
     * @param string $rodzaj_oceny
     */
    public function update ($id, $rodzaj_oceny)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('UPDATE `rodzaje_ocen` SET
                                            `rodzaj_oceny`=:rodzaj_oceny
                                            WHERE id_rodzaju_ocen=:id_rodzaju_ocen;');
            $stmt->bindValue(':id_rodzaju_ocen', $id, PDO::PARAM_INT);
            $stmt->bindValue(':rodzaj_oceny', $rodzaj_oceny, PDO::PARAM_STR);
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
            $stmt = $this->pdo -> prepare ('DELETE FROM `rodzaje_ocen`
                                            WHERE id_rodzaju_ocen=:id_rodzaju_ocen;');
            $stmt->bindValue(':id_rodzaju_ocen', $id, PDO::PARAM_INT);
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
            $this->error_description="Nie udało się usunąć danych rodzaju oceny: ".$e->getMessage();
            return;
        }
        
    }
    /**
     * Metoda pobierająca listę rodzajów ocen
     * @param string $order_by default rodzaj_oceny względem której kolumny sortować wynik
     * @param bool $narastajaco default true - czy sortować narastająco
     */
    public function getRodzaje_ocen ($order_by="`rodzaj_oceny`", $narastajaco=TRUE)
    {
        $this->rodzaje_ocen_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
            try {
                $query='SELECT `id_rodzaju_ocen`, `rodzaj_oceny`';
                $query.='FROM `rodzaje_ocen` ';
                $query.='ORDER BY '.$order_by;
                if ($narastajaco) $query.= " ASC ;";
                else $query.= " DESC ;";
                
                $stmt = $this->pdo -> prepare ($query);
                $result = $stmt -> execute();
                if ($result==true){
                    $this->is_error=FALSE;
                    $this->error_description="";
                    $this->rodzaje_ocen_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                $stmt->closeCursor();
            }
            catch (PDOException $e)
            {
                $this->is_error=TRUE;
                $this->error_description="Nie udało się odczytać bazy danych: ".$e->getMessage();
                return;
            }
        return $this->rodzaje_ocen_array;
    }
    /**
     * Metoda pobierająca rekord o podanym id
     * @param int $id
     */
    public function getRodzaj_oceny ($id)
    {
        $this->rodzaje_ocen_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('SELECT `id_rodzaju_ocen`, `rodzaj_ocen` FROM `rodzaje_ocen`
                                            WHERE id_rodzaju_ocen=:id_rodzaju_ocen');
            $stmt->bindValue(':id_rodzaju_ocen', $id, PDO::PARAM_INT);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->rodzaje_ocen_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się usunąć danych rodzaju oceny: ".$e->getMessage();
            return;
        }
        return $this->rodzaje_ocen_array[0];
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