<?php

/**
 * @author Alicja Orlik
 *
 */

final class Grupy
{
    private static ?Grupy $instance = null;
    private $pdo;
    private $is_error;
    private $error_description;
    public $grupy_array;
    
    
    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): Grupy
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
     * @param string $nazwa_grupy
     */
    public function insert ($nazwa_grupy)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('INSERT INTO `grupy` (`nazwa_grupy`) VALUES (:nazwa_grupy);');
            $stmt->bindValue(':nazwa_grupy', $nazwa_grupy, PDO::PARAM_STR);
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
     * @param string $nazwa_grupy
     */
    public function update ($id, $nazwa_grupy)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('UPDATE `grupy` SET
                                            `nazwa_grupy`=:nazwa_grupy
                                            WHERE id_grupy=:id_grupy;');
            $stmt->bindValue(':id_grupy', $id, PDO::PARAM_INT);
            $stmt->bindValue(':nazwa_grupy', $nazwa_grupy, PDO::PARAM_STR);
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
            $stmt = $this->pdo -> prepare ('DELETE FROM `grupy`
                                            WHERE id_grupy=:id_grupy;');
            $stmt->bindValue(':id_grupy', $id, PDO::PARAM_INT);
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
            $this->error_description="Nie udało się usunąć danych grupy: ".$e->getMessage();
            return;
        }
        
    }
    /**
     * Metoda pobierająca listę grup
     * @param string $order_by default nazwa_grupy względem której kolumny sortować wynik
     * @param bool $narastajaco default true - czy sortować narastająco
     */
    public function getGrupy ($order_by="`nazwiskoS`", $narastajaco=TRUE)
    {
        $this->grupy_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        if(isset($_POST['id_grupy'])){
            try {
                $query='SELECT `grupy`.`id_grupy`, `grupy`.`nazwa_grupy`,`kierunki`.`id_kierunku`, `kierunki`.`nazwa_kierunku`,';
                $query.='`studenci`.`id_studenta`, `studenci`.`imieS`,`studenci`.`nazwiskoS`,`studenci`.`email` ';
                $query.='FROM `grupy` JOIN `studenci` ON `studenci`.`id_grupy` = `grupy`.`id_grupy` ';
                $query.='JOIN `kierunki` ON `kierunki`.`id_kierunku` = `studenci`.`id_kierunku` ';
                $query.='WHERE `grupy`.`id_grupy`='.$_POST['id_grupy'].' ORDER BY '.$order_by;
                if ($narastajaco) $query.= " ASC ;";
                else $query.= " DESC ;";
                
                $stmt = $this->pdo -> prepare ($query);
                $result = $stmt -> execute();
                if ($result==true){
                    $this->is_error=FALSE;
                    $this->error_description="";
                    $this->grupy_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                $stmt->closeCursor();
            }
            catch (PDOException $e)
            {
                $this->is_error=TRUE;
                $this->error_description="Nie udało się odczytać bazy danych: ".$e->getMessage();
                return;
            }
        }
        else
        try{
            $query='SELECT `grupy`.`id_grupy`, `grupy`.`nazwa_grupy`,`kierunki`.`id_kierunku`, `kierunki`.`nazwa_kierunku`,'; 
            $query.='`studenci`.`id_studenta`, `studenci`.`imieS`,`studenci`.`nazwiskoS`,`studenci`.`email` ';
            $query.='FROM `grupy` JOIN `studenci` ON `studenci`.`id_grupy` = `grupy`.`id_grupy` ';
            $query.='JOIN `kierunki` ON `kierunki`.`id_kierunku` = `studenci`.`id_kierunku` ';
            $query.='ORDER BY '.$order_by;
            if ($narastajaco) $query.= " ASC ;";
            else $query.= " DESC ;";
            
            $stmt = $this->pdo -> prepare ($query);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->grupy_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się odczytać bazy danych: ".$e->getMessage();
            return;
        }
        return $this->grupy_array;
    }
    /**
     * Metoda pobierająca rekord o podanym id
     * @param int $id
     */
    public function getGrupa ($id)
    {
        $this->grupy_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('SELECT `id_grupy`, `nazwa_grupy` FROM `grupy`
                                            WHERE id_grupy=:id_grupy');
            $stmt->bindValue(':id_grupy', $id, PDO::PARAM_INT);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->grupy_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się usunąć danych grupy: ".$e->getMessage();
            return;
        }
        return $this->grupy_array[0];
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