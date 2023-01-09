<?php
require_once 'include/Prowadzacy.php';

/**
 * @author Alicja Orlik
 *
 */

final class Tematy
{
    private static ?Tematy $instance = null;
    private $pdo;
    private $is_error;
    private $error_description;
    public $tematy_array;
    
    
    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): Tematy
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
     * @param string $temat
     * @param int $id_prowadzacego
     * @param int $id_zajec
     */
    public function insert ($temat, $id_prowadzacego, $id_zajec)
    {//sprawdzamy, czy mamy połączenie z bazą danych
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('INSERT INTO `tematy_zajec` (`temat`, `id_prowadzacego`, `id_zajec`) VALUES (:temat, :prowadzacy, :zajecia);');
            $stmt->bindValue(':temat', $temat, PDO::PARAM_STR);
            $stmt->bindValue(':prowadzacy', $id_prowadzacego, PDO::PARAM_INT);
            $stmt->bindValue(':zajecia', $id_zajec, PDO::PARAM_INT);
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
     * @param string $temat
     * @param int $id_prowadzacego
     * @param int $id_zajec
     */
    public function update ($id, $temat, $id_prowadzacego, $id_zajec)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('UPDATE `tematy_zajec` SET
                                            `temat`=:temat,
                                            `id_prowadzacego`=:prowadzacy,
                                            `id_zajec`=:zajecia
                                            WHERE id_tematu=:id_tematu;');
            $stmt->bindValue(':id_tematu', $id, PDO::PARAM_INT);
            $stmt->bindValue(':temat', $temat, PDO::PARAM_STR);
            $stmt->bindValue(':prowadzacy', $id_prowadzacego, PDO::PARAM_INT);
            $stmt->bindValue(':zajecia', $id_zajec, PDO::PARAM_INT);
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
            $stmt = $this->pdo -> prepare ('DELETE FROM `tematy_zajec`
                                            WHERE id_tematu=:id_tematu;');
            $stmt->bindValue(':id_tematu', $id, PDO::PARAM_INT);
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
            $this->error_description="Nie udało się usunąć danych tematu: ".$e->getMessage();
            return;
        }
        
    }
    /**
     * Metoda pobierająca listę tematów
     * @param string $order_by default temat - względem której kolumny sortować wynik
     * @param bool $narastajaco default true - czy sortować narastająco
     */
    public function getTematy($order_by="`temat`", $narastajaco=TRUE)
    {
        $this->tematy_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        if(isset($_POST['nazwa_zajec'])){
        try{
            $query='SELECT `id_tematu`, `temat`, `tematy_zajec`.`id_prowadzacego`, `tematy_zajec`.`id_zajec`,';
            $query.='`prowadzacy`.`stopien_naukowy`, `prowadzacy`.`imie`, `prowadzacy`.`nazwisko`, `zajecia`.`nazwa_zajec`';
            $query.='FROM `tematy_zajec` JOIN `prowadzacy` ON `tematy_zajec`.`id_prowadzacego` = `prowadzacy`.`id_prowadzacego`';
            $query.='JOIN `zajecia` ON `tematy_zajec`.`id_zajec` = `zajecia`.`id_zajec` WHERE `zajecia`.`nazwa_zajec`=:nazwa_zajec ORDER BY '.$order_by;
            if ($narastajaco) $query.= " ASC ;";
            else $query.= " DESC ;";
            
            $stmt = $this->pdo -> prepare ($query);
            $stmt->bindValue(':nazwa_zajec', $_POST['nazwa_zajec'], PDO::PARAM_STR);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->tematy_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się odczytać bazy danych: ".$e->getMessage();
            return;
        }
        return $this->tematy_array; //zwrócenie tablicy pobranych rekordów
        }
        else 
            try{
                $query='SELECT `id_tematu`, `temat`, `tematy_zajec`.`id_prowadzacego`, `tematy_zajec`.`id_zajec`,';
                $query.='`prowadzacy`.`stopien_naukowy`, `prowadzacy`.`imie`, `prowadzacy`.`nazwisko`, `zajecia`.`nazwa_zajec`';
                $query.='FROM `tematy_zajec` JOIN `prowadzacy` ON `tematy_zajec`.`id_prowadzacego` = `prowadzacy`.`id_prowadzacego`';
                $query.='JOIN `zajecia` ON `tematy_zajec`.`id_zajec` = `zajecia`.`id_zajec` ORDER BY '.$order_by;
                if ($narastajaco) $query.= " ASC ;";
                else $query.= " DESC ;";
                
                $stmt = $this->pdo -> prepare ($query);
                $result = $stmt -> execute();
                if ($result==true){
                    $this->is_error=FALSE;
                    $this->error_description="";
                    $this->tematy_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się odczytać bazy danych: ".$e->getMessage();
            return;
        }
        return $this->tematy_array; //zwrócenie tablicy pobranych rekordów
    }
    /**
     * Metoda pobierająca rekord o podanym id
     * @param int $id
     */
    public function getTemat ($id)
    {
        $this->tematy_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('SELECT `id_tematu`, `temat`, `id_prowadzacego`, `id_zajec` FROM `tematy_zajec`
                                            WHERE `id_tematu`=:id_tematu');
            $stmt->bindValue(':id_tematu', $id, PDO::PARAM_INT);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->tematy_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się usunąć danych tematu: ".$e->getMessage();
            return;
        }
        return $this->tematy_array[0]; //zwrócenie wartości pojedyńczego rekordu
    }
    /**
     *Metoda pobierająca status błędu
     */
    public function getError ()
    {
        $error=$this->is_error; //ustawienie roboczej zmiennej error na wartość naszej zmiennej prywatnej
        $this->is_error=FALSE; //kasowanie wartości o błędzie
        return $error; //zwrócenie wartości zmiennej error
    }//w ten sposób metoda getError zwróci nam informację, 
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