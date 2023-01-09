<?php

/**
 * @author Alicja Orlik
 *
 */

final class Oceny
{
    private static ?Oceny $instance = null;
    private $pdo;
    private $is_error;
    private $error_description;
    public $oceny_array;
    
    
    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): Oceny
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
     * @param int $id_zajec
     * @param int $id_prowadzacego
     * @param int $id_semestru
     * @param int $id_studenta
     * @param int $rodzaj_oceny
     * @param int $ocena
     */
    public function insert ($id_zajec, $id_prowadzacego, $id_semestru, $id_studenta, $rodzaj_oceny, $ocena)
    {//sprawdzamy, czy mamy połączenie z bazą danych
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('INSERT INTO `oceny` (`id_zajec`, `id_prowadzacego`, `id_semestru`, `id_studenta`, `id_rodzaju_ocen`, `id_stopnia`) 
            VALUES (:id_zajec, :id_prowadzacego, :id_semestru, :id_studenta, :id_rodzaju_ocen, :id_stopnia);');
            $stmt->bindValue(':id_zajec', $id_zajec, PDO::PARAM_INT);
            $stmt->bindValue(':id_prowadzacego', $id_prowadzacego, PDO::PARAM_INT);
            $stmt->bindValue(':id_semestru', $id_semestru, PDO::PARAM_INT);
            $stmt->bindValue(':id_studenta', $id_studenta, PDO::PARAM_INT);
            $stmt->bindValue(':id_rodzaju_ocen', $rodzaj_oceny, PDO::PARAM_INT);
            $stmt->bindValue(':id_stopnia', $ocena, PDO::PARAM_INT);
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
     * @param int $id_zajec
     * @param int $id_prowadzacego
     * @param int $id_semestru
     * @param int $id_studenta
     * @param int $rodzaj_oceny
     * @param int $ocena
     */
    public function update ($id, $id_zajec, $id_prowadzacego, $id_semestru, $id_studenta, $rodzaj_oceny, $ocena)
    {
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('UPDATE `oceny` SET
                                            `id_zajec`=:id_zajec,
                                            `id_prowadzacego`=:id_prowadzacego,
                                            `id_semestru`=:id_semestru,
                                            `id_studenta`=:id_studenta,
                                            `id_rodzaju_ocen`=:id_rodzaju_ocen,
                                            `id_stopnia`=:id_stopnia
                                            WHERE id_oceny=:id_oceny;');
            $stmt->bindValue(':id_oceny', $id, PDO::PARAM_INT);
            $stmt->bindValue(':id_zajec', $id_zajec, PDO::PARAM_INT);
            $stmt->bindValue(':id_prowadzacego', $id_prowadzacego, PDO::PARAM_INT);
            $stmt->bindValue(':id_semestru', $id_semestru, PDO::PARAM_INT);
            $stmt->bindValue(':id_studenta', $id_studenta, PDO::PARAM_INT);
            $stmt->bindValue(':id_rodzaju_ocen', $rodzaj_oceny, PDO::PARAM_INT);
            $stmt->bindValue(':id_stopnia', $ocena, PDO::PARAM_INT);
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
            $stmt = $this->pdo -> prepare ('DELETE FROM `oceny`
                                            WHERE id_oceny=:id_oceny;');
            $stmt->bindValue(':id_oceny', $id, PDO::PARAM_INT);
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
            $this->error_description="Nie udało się usunąć oceny: ".$e->getMessage();
            return;
        }
        
    }
    /**
     * Metoda pobierająca listę ocen
     * @param string $order_by default ocena - względem której kolumny sortować wynik
     * @param bool $narastajaco default true - czy sortować narastająco
     */
    public function getOceny($order_by="`id_stopnia`", $narastajaco=TRUE)
    {
        $this->oceny_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        if (isset($_POST['student'])){
        try{
            $query='SELECT `id_oceny`, `oceny`.`id_zajec`, `oceny`.`id_prowadzacego`, `prowadzacy`.`stopien_naukowy`, ';
            $query.='`prowadzacy`.`imie`, `prowadzacy`.`nazwisko`, `id_semestru`, `oceny`.`id_studenta`, ';
            $query.='`studenci`.`imieS`, `studenci`.`nazwiskoS`, `id_stopnia`, `rodzaje_ocen`.`id_rodzaju_ocen`, ';
            $query.='`rodzaje_ocen`.`rodzaj_oceny`, `zajecia`.`nazwa_zajec` ';
            $query.='FROM `oceny` JOIN `zajecia` ON `oceny`.`id_zajec`=`zajecia`.`id_zajec` ';
            $query.='JOIN `prowadzacy` ON `oceny`.`id_prowadzacego`=`prowadzacy`.`id_prowadzacego` ';
            $query.='JOIN `studenci` ON `oceny`.`id_studenta`=`studenci`.`id_studenta` ';
            $query.='JOIN `rodzaje_ocen` ON `oceny`.`id_rodzaju_ocen`=`rodzaje_ocen`.`id_rodzaju_ocen` ';
            $query.='WHERE `studenci`.`id_studenta`=:student ORDER BY '.$order_by;
            if ($narastajaco) $query.= " ASC ;";
            else $query.= " DESC ;";
            
            $stmt = $this->pdo -> prepare ($query);
            $stmt->bindValue(':student', $_POST['student'], PDO::PARAM_INT);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->oceny_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się odczytać bazy danych: ".$e->getMessage();
            return;
        }
        return $this->oceny_array; //zwrócenie tablicy pobranych rekordów
    }
    else 
        try{
        $query='SELECT `id_oceny`, `oceny`.`id_zajec`, `oceny`.`id_prowadzacego`, `prowadzacy`.`stopien_naukowy`, ';
        $query.='`prowadzacy`.`imie`, `prowadzacy`.`nazwisko`, `id_semestru`, `oceny`.`id_studenta`, ';
        $query.='`studenci`.`imieS`, `studenci`.`nazwiskoS`, `id_stopnia`, `rodzaje_ocen`.`id_rodzaju_ocen`, ';
        $query.='`rodzaje_ocen`.`rodzaj_oceny`, `zajecia`.`nazwa_zajec` ';
        $query.='FROM `oceny` JOIN `zajecia` ON `oceny`.`id_zajec`=`zajecia`.`id_zajec` ';
        $query.='JOIN `prowadzacy` ON `oceny`.`id_prowadzacego`=`prowadzacy`.`id_prowadzacego` ';
        $query.='JOIN `studenci` ON `oceny`.`id_studenta`=`studenci`.`id_studenta` ';
        $query.='JOIN `rodzaje_ocen` ON `oceny`.`id_rodzaju_ocen`=`rodzaje_ocen`.`id_rodzaju_ocen` ';
        $query.='ORDER BY '.$order_by;
        if ($narastajaco) $query.= " ASC ;";
        else $query.= " DESC ;";
        
        $stmt = $this->pdo -> prepare ($query);
        $result = $stmt -> execute();
        if ($result==true){
            $this->is_error=FALSE;
            $this->error_description="";
            $this->oceny_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        $stmt->closeCursor();
    }
    catch (PDOException $e)
    {
        $this->is_error=TRUE;
        $this->error_description="Nie udało się odczytać bazy danych: ".$e->getMessage();
        return;
    }
    return $this->oceny_array; //zwrócenie tablicy pobranych rekordów
    }
    /**
     * Metoda pobierająca rekord o podanym id
     * @param int $id
     */
    public function getOcena ($id)
    {
        $this->oceny_array=array();
        if ($this->pdo==null){$this->is_error=TRUE; $this->error_description="Brak połączenia z bazą danych"; return;};
        try{
            $stmt = $this->pdo -> prepare ('SELECT `id_oceny`, `id_zajec`, `id_prowadzacego`, `id_semestru`, `id_studenta`, `id_stopnia`, `id_rodzaju_ocen` FROM `oceny`
                                            WHERE id_oceny=:id_oceny');
            $stmt->bindValue(':id_oceny', $id, PDO::PARAM_INT);
            $result = $stmt -> execute();
            if ($result==true){
                $this->is_error=FALSE;
                $this->error_description="";
                $this->oceny_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $stmt->closeCursor();
        }
        catch (PDOException $e)
        {
            $this->is_error=TRUE;
            $this->error_description="Nie udało się usunąć oceny: ".$e->getMessage();
            return;
        }
        return $this->oceny_array[0]; //zwrócenie wartości pojedyńczego rekordu
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