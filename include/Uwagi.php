<?php

/**
 * @author Alicja Orlik
 *
 */

final class Uwagi
{
    private static ?Uwagi $instance = null;
    private $pdo;
    private $is_error;
    private $error_description;
    public $uwagi_array;
    
    
    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): Uwagi
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
     * @param string $uwaga
     * @param int $id_prowadzacego
     * @param int $id_studenta
     */
    public function insert ($uwaga, $id_prowadzacego, $id_studenta)
    {//sprawdzamy, czy mamy połączenie z bazą danych
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('INSERT INTO `uwagi` (`tresc`, `id_prowadzacego`, `id_studenta`) VALUES (:tresc, :prowadzacy, :student);');
            $stmt->bindValue(':tresc', $uwaga, PDO::PARAM_STR);
            $stmt->bindValue(':prowadzacy', $id_prowadzacego, PDO::PARAM_INT);
            $stmt->bindValue(':student', $id_studenta, PDO::PARAM_INT);
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
     * @param string $uwaga
     * @param int $id_prowadzacego
     * @param int $id_studenta
     */
    public function update ($id, $uwaga, $id_prowadzacego, $id_studenta)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('UPDATE `uwagi` SET
                                            `tresc`=:tresc,
                                            `id_prowadzacego`=:prowadzacy,
                                            `id_studenta`=:student
                                            WHERE id_uwagi=:id_uwagi;');
            $stmt->bindValue(':id_uwagi', $id, PDO::PARAM_INT);
            $stmt->bindValue(':tresc', $uwaga, PDO::PARAM_STR);
            $stmt->bindValue(':prowadzacy', $id_prowadzacego, PDO::PARAM_INT);
            $stmt->bindValue(':student', $id_studenta, PDO::PARAM_INT);
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
            $stmt = $this->pdo -> prepare ('DELETE FROM `uwagi`
                                            WHERE id_uwagi=:id_uwagi;');
            $stmt->bindValue(':id_uwagi', $id, PDO::PARAM_INT);
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
            $this->error_description="Nie udało się usunąć uwagi: ".$e->getMessage();
            return;
        }
        
    }
    /**
     * Metoda pobierająca listę uwag
     * @param string $order_by default tresc - względem której kolumny sortować wynik
     * @param bool $narastajaco default true - czy sortować narastająco
     */
    public function getUwagi($order_by="`tresc`", $narastajaco=TRUE)
    {
        $this->uwagi_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        if (isset($_POST['id_studenta'])){
        try{
            $query='SELECT `id_uwagi`, `tresc`, `uwagi`.`id_prowadzacego`, `studenci`.`id_studenta`,';
            $query.='`prowadzacy`.`stopien_naukowy`, `prowadzacy`.`imie`, `prowadzacy`.`nazwisko`, `studenci`.`imieS`,';
            $query.='`studenci`.`nazwiskoS` FROM `uwagi` JOIN `prowadzacy`';
            $query.=' ON `uwagi`.`id_prowadzacego` = `prowadzacy`.`id_prowadzacego`';
            $query.='JOIN `studenci` ON `uwagi`.`id_studenta` = `studenci`.`id_studenta` ';
            $query.='WHERE `studenci`.`id_studenta`=:id_studenta ORDER BY '.$order_by;
            if ($narastajaco) $query.= " ASC ;";
            else $query.= " DESC ;";
            
            $stmt = $this->pdo -> prepare ($query);
            $stmt->bindValue(':id_studenta', $_POST['id_studenta'], PDO::PARAM_INT);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->uwagi_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się odczytać bazy danych: ".$e->getMessage();
            return;
        }
        return $this->uwagi_array; //zwrócenie tablicy pobranych rekordów
        }
        try{
            $query='SELECT `id_uwagi`, `tresc`, `uwagi`.`id_prowadzacego`, `studenci`.`id_studenta`,';
            $query.='`prowadzacy`.`stopien_naukowy`, `prowadzacy`.`imie`, `prowadzacy`.`nazwisko`, `studenci`.`imieS`,';
            $query.='`studenci`.`nazwiskoS` FROM `uwagi` JOIN `prowadzacy`';
            $query.=' ON `uwagi`.`id_prowadzacego` = `prowadzacy`.`id_prowadzacego`';
            $query.='JOIN `studenci` ON `uwagi`.`id_studenta` = `studenci`.`id_studenta` ORDER BY '.$order_by;
            if ($narastajaco) $query.= " ASC ;";
            else $query.= " DESC ;";
            
            $stmt = $this->pdo -> prepare ($query);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->uwagi_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się odczytać bazy danych: ".$e->getMessage();
            return;
        }
        return $this->uwagi_array; //zwrócenie tablicy pobranych rekordów
    }
    /**
     * Metoda pobierająca rekord o podanym id
     * @param int $id
     */
    public function getUwaga ($id)
    {
        $this->uwagi_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('SELECT `id_uwagi`, `tresc`, `id_prowadzacego`, `id_studenta` FROM `uwagi`
                                            WHERE id_uwagi=:id_uwagi');
            $stmt->bindValue(':id_uwagi', $id, PDO::PARAM_INT);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->uwagi_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się usunąć uwagi: ".$e->getMessage();
            return;
        }
        return $this->uwagi_array[0]; //zwrócenie wartości pojedyńczego rekordu
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