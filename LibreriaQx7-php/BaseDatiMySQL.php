<?php

include_once 'Tag.php';
include_once 'Attributo.php';
include_once 'javascript.php';

class BaseDatiMySQL{
    private $host;
    public const NOME_VARIABILE = 'tabella';
    
    /**
     * Costruttore.
     * 
     * @param string $host
     */
    public function __costruct(string $host='localhost'){
        $this->host = $host;
    }
    
    /**
     * Metodo che restituisce i dati grezzi dell'interrogazione SQL.
     * 
     * @param string $query     interrogazione SQL
     * @param string $nome_db   nome schema MySQL
     * @param string $utente    autorizzato ad accedere allo schema
     * @param string $password  relativa all'utente
     * 
     * @return mixed tabella dei record di query
     */
    public function SQL(string $query,string $nome_db, string $utente="root",string $password=""){
        $connessione = mysqli_connect($this->host, $utente, $password,$nome_db);
        mysqli_set_charset($connessione,'utf8');
        $risultato = mysqli_query($connessione,$query);
        
        mysqli_close($connessione);
        return $risultato;
    }
    
    /**
     * Genera un tag JavaScript che contiene i dati dell'interrogazione SQL; i dati 
     * sono memorizatti in un array bidimensionale di nome 'tabella'
     * 
     * Es.:
     * <code><pre>
     * 
     * < script type="text/javascript" >
     *      var tabella = [["Mario ROSSI", "1234567", "AMMINISTRATORE"]];
     * < /script >
     * 
     * </pre></code>
     * 
     * @param string $query     interrogazione SQL
     * @param string $nome_db   nome schema MySQL
     * @param string $utente    autorizzato ad accedere allo schema
     * @param string $password  relativa all'utente
     * 
     * @return JavaScript | null
     */
    public function datiJavaScript(string $query,string $nome_db, string $utente="root",string $password="") {
        $risultato = self::SQL($query, $nome_db,$utente,$password);
        if ($risultato->num_rows > 0) {
            $codice  = 'var '.self::NOME_VARIABILE.' = ['; // inizio
            $primo = true;
            while($riga = mysqli_fetch_row($risultato)) {
                if($primo != true){
                    $codice .= ', ';
                }
                $codice .= '[';
                for($i=0; $i < count($riga); $i++){
                    if($i != 0) $codice .= ', ';
                    $cella = str_replace('"', '“', $riga[$i]); //... « “ » è un carattere UNICODE diverso da « " »
                    $codice .= '"'.strip_tags($cella).'"';
                }
                $codice .= ']';
                $primo = false;
            }
            $codice .= '];'; //fine
            return new JavaScript($codice);
        
        }
        
    
    }
    
    /**
     * Restituisce restituisce il nome e il tipo dei campi relativi alla tabella del database MySQL richiesto.
     * @param string $tabella   nome relativo alla tabella
     * @param string $nome_db   nome dello schema mysql
     * @param string $utente
     * @param string $password
     * @return null | array bidimensionale
     */
    public function infoTabella(string $tabella,string $nome_db, string $utente="root",string $password=""){
        $query = "select column_name , data_type from information_schema.columns where table_schema = '$nome_db' and table_name = '$tabella';";
        return self::tabella($query, $nome_db,$utente,$password);
    }
    
    /**
     * Tabella query.
     * 
     * @param string $query
     * @param string $nome_db
     * @param string $utente
     * @param string $password
     * @return null | array bidimensionale
     */
    public function tabella(string $query,string $nome_db, string $utente="root",string $password="") {
        $risultato = self::SQL($query, $nome_db,$utente,$password);
        if ($risultato->num_rows > 0) {
            $tabella = array();
            $j=1;
            while($riga = mysqli_fetch_row($risultato)) {
                $r =  array();
                for($i=0; $i < count($riga); $i++){
                    $r[$i] = str_replace('"', '“', $riga[$i]); //... « “ » è un carattere UNICODE diverso da « " »
                 
                }
                $tabella[$j++]=$r;
            }
            return $tabella;
        }
    }
    
    
    
}

?>
