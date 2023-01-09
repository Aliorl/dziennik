<?php

/**
 * @author Alicja Orlik
 *
 */

final class Studenci
{
    private static ?Studenci $instance = null;
    private $pdo;
    private $is_error;
    private $error_description;
    public $studenci_array;
    
    
    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): Studenci
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
     * @param string $imieS
     * @param string $nazwiskoS
     * @param string $email
     * @param int $id_grupy
     * @param int $id_kierunku
     */
    public function insert ($imieS, $nazwiskoS, $email, $id_grupy, $id_kierunku)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('INSERT INTO `studenci` (`imieS`, `nazwiskoS`, `email`, `id_grupy`, `id_kierunku`) VALUES (:imieS, :nazwiskoS, :email, :id_grupy, :id_kierunku);');
            $stmt->bindValue(':imieS', $imieS, PDO::PARAM_STR);
            $stmt->bindValue(':nazwiskoS', $nazwiskoS, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':id_grupy', $id_grupy, PDO::PARAM_INT);
            $stmt->bindValue(':id_kierunku', $id_kierunku, PDO::PARAM_INT);
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
     * @param string $imieS
     * @param string $nazwiskoS
     * @param string $email
     * @param int $id_grupy
     * @param int $id_kierunku
     */
    public function update ($id, $imieS, $nazwiskoS, $email, $id_grupy, $id_kierunku)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('UPDATE `studenci` SET
                                            `imieS`=:imieS,
                                            `nazwiskoS`=:nazwiskoS,
                                            `email`=:email,
                                            `id_grupy`=:id_grupy,
                                            `id_kierunku`=:id_kierunku
                                            WHERE id_studenta=:id_studenta;');
            $stmt->bindValue(':id_studenta', $id, PDO::PARAM_INT);
            $stmt->bindValue(':imieS', $imieS, PDO::PARAM_STR);
            $stmt->bindValue(':nazwiskoS', $nazwiskoS, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':id_grupy', $id_grupy, PDO::PARAM_INT);
            $stmt->bindValue(':id_kierunku', $id_kierunku, PDO::PARAM_INT);
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
            $stmt = $this->pdo -> prepare ('DELETE FROM `studenci`
                                            WHERE id_studenta=:id_studenta;');
            $stmt->bindValue(':id_studenta', $id, PDO::PARAM_INT);
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
            $this->error_description="Nie udało się usunąć danych studenta: ".$e->getMessage();
            return;
        }
        
    }
    /**
     * Metoda pobierająca listę studentów
     * @param string $order_by default nazwisko - względem której kolumny sortować wynik
     * @param bool $narastajaco default true - czy sortować narastająco
     */
    public function getStudenci($order_by="`nazwiskoS`", $narastajaco=TRUE)
    {
        $this->studenci_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        if(isset($_POST['id_grupy'])){
        try{
            $query='SELECT `kierunki`.`id_kierunku`, `kierunki`.`nazwa_kierunku`, `studenci`.`id_studenta`, `studenci`.`imieS`,`studenci`.`nazwiskoS`,';
            $query.='`studenci`.`email`, `studenci`.`id_grupy` FROM `kierunki` JOIN `studenci` ON `studenci`.`id_kierunku` = `kierunki`.`id_kierunku` ';
            $query.='WHERE `id_grupy`='.$_POST['id_grupy'].' ORDER BY '.$order_by;
            if ($narastajaco) $query.= " ASC ;";
            else $query.= " DESC ;";
            
            $stmt = $this->pdo -> prepare ($query);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->studenci_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się odczytać bazy danych: ".$e->getMessage();
            return;
        }
        return $this->studenci_array;
        }
        else
            try{
                $query='SELECT `kierunki`.`id_kierunku`, `kierunki`.`nazwa_kierunku`, `studenci`.`id_studenta`, `studenci`.`imieS`,`studenci`.`nazwiskoS`,';
                $query.='`studenci`.`email`, `studenci`.`id_grupy` FROM `kierunki`JOIN `studenci` ON `studenci`.`id_kierunku` = `kierunki`.`id_kierunku` ';
                $query.='ORDER BY '.$order_by;
                if ($narastajaco) $query.= " ASC ;";
                else $query.= " DESC ;";
                
                $stmt = $this->pdo -> prepare ($query);
                $result = $stmt -> execute();
                if ($result==true){
                    $this->is_error=FALSE;
                    $this->error_description="";
                    $this->studenci_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się odczytać bazy danych: ".$e->getMessage();
            return;
        }
        return $this->studenci_array;
    }

    /**
     * Metoda pobierająca rekord o podanym id
     * @param int $id
     * @param string $imieS
     * @param string $nazwiskoS
     * @param int $id_grupy
     */
    public function getStudent ($id)
    {
        $this->studenci_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('SELECT `id_studenta`, `imieS`, `nazwiskoS`, `email`, `id_grupy`, `id_kierunku` FROM `studenci`
                                            WHERE id_studenta=:id_studenta');
            $stmt->bindValue(':id_studenta', $id, PDO::PARAM_INT);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->studenci_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się usunąć danych studenta: ".$e->getMessage();
            return;
        }
        return $this->studenci_array[0];
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