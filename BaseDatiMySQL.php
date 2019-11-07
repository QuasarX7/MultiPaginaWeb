<?php

class BaseDatiMySQL{
    protected $connessione = null;
    
    
    public function __costruct(string $nome_db,string $utente,string $password,string $host='localhost'){
        // mi connetto al MySql Server
        $this->connessione = mysql_connect($host, $utente, $password) or die('Errore connessione MySQL');
        
        mysql_select_db($nome_db, $this->connessione) or die('Errore accesso alla BD "'.$nome_db.'"');
    }
    
    public function SQL($query) {
        return mysql_query($query, $this->connessione) or die('Errore query SQL...');
    }
    
    public function chiudi() {
        if(!is_null($this->connessione)){
            mysql_close($this->connessione);
            $this->connessione = null;
        }
    }
    
    // distruttore
    function __destruct() {
        self::chiudi();
    }
}

