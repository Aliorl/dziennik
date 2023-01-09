<?php

/**
 * @author Alicja Orlik
 *
 */

final class Semestry
{
    private static ?Semestry $instance = null;
    private $pdo;
    private $is_error;
    private $error_description;
    public $semestry_array;
    
    
    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): Semestry
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
     * @param string $numer_semestru
     * @param string $numer_roku
     */
    public function insert ($numer_semestru, $numer_roku)
    {//sprawdzamy, czy mamy połączenie z bazą danych
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('INSERT INTO `uwagi` (`numer_semestru`, `numer_roku`) VALUES (:numer_semestru, :numer_roku);');
            $stmt->bindValue(':numer_semestru', $numer_semestru, PDO::PARAM_INT);
            $stmt->bindValue(':numer_roku', $numer_roku, PDO::PARAM_INT);
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
     * @param string $numer_semestru
     * @param string $numer_roku
     */
    public function update ($id, $numer_semestru, $numer_roku)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('UPDATE `semestry` SET
                                            `$numer_semestru`=:$numer_semestru,
                                            `$numer_roku`=:$numer_roku
                                            WHERE id_semestru=:id_semestru;');
            $stmt->bindValue(':id_semestru', $id, PDO::PARAM_INT);
            $stmt->bindValue(':numer_semestru', $numer_semestru, PDO::PARAM_INT);
            $stmt->bindValue(':numer_roku', $numer_roku, PDO::PARAM_INT);
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
            $stmt = $this->pdo -> prepare ('DELETE FROM `semestry`
                                            WHERE id_semestru=:id_semestru;');
            $stmt->bindValue(':id_semestru', $id, PDO::PARAM_INT);
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
            $this->error_description="Nie udało się usunąć semestru: ".$e->getMessage();
            return;
        }
        
    }
    /**
     * Metoda pobierająca listę semestrów
     * @param string $order_by default tresc - względem której kolumny sortować wynik
     * @param bool $narastajaco default true - czy sortować narastająco
     */
    public function getSemestry($order_by="`numer_semestru`", $narastajaco=TRUE)
    {
        $this->semestry_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $query='SELECT `id_semestru`, `numer_semestru`, `numer_roku` FROM `semestry`';
            $query.='ORDER BY '.$order_by;
            if ($narastajaco) $query.= " ASC ;";
            else $query.= " DESC ;";
            
            $stmt = $this->pdo -> prepare ($query);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->semestry_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się odczytać bazy danych: ".$e->getMessage();
            return;
        }
        return $this->semestry_array; //zwrócenie tablicy pobranych rekordów
    }
    /**
     * Metoda pobierająca rekord o podanym id
     * @param int $id
     */
    public function getSemestr ($id)
    {
        $this->semestry_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('SELECT `id_semestru`, `numer_semestru`, `numer_rou` FROM `semestry`
                                            WHERE id_semestru=:id_semestru');
            $stmt->bindValue(':id_semestru', $id, PDO::PARAM_INT);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->semestry_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się usunąć semestru: ".$e->getMessage();
            return;
        }
        return $this->semestry_array[0]; //zwrócenie wartości pojedyńczego rekordu
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