<?php

include_once 'Pagina.php';

/**
 * Classe che contiene i riferimenti dei file di testo/html da caricare per argomento.
 * @author Dott. Domenico della Peruta
 *
 */
class Argomento{
    protected $nome;
    protected $pagine = array();
    
    /**
     * Costruttore
     * @param string $nome
     */
    public function __construct($nome){
        $this->nome = $nome;
    }
    /**
     * Argomento.
     * 
     * @return string
     */
    public function nome() {
        return $this->nome;
    }
    
    /**
     * Aggiungi Nuova pagina.
     * 
     * @param string $nome
     * @param string $file    url del file contenente il testo della pagina
     * 
     */
    public function aggiungiPagina(string $nome,string $file) {
        $this->pagine[] = new Pagina($nome, $file);
    }
    
    /**
     * Aggiungi del codice HTML.
     * @param string $nome
     * @param string $codice
     */
    public function aggiungiPaginaCodice(string $nome,string $codice) {
        $this->pagine[] = $codice;
    }
    
    
    /**
     * Restituisce il testo della pagina se esiste.
     * 
     * @param int $indice
     * @return string   testo del file esterno
     */
    public function pagina($indice){
        $pagina = $this->pagine[$indice];
        if($pagina instanceof Pagina){
            return $pagina->testo();
        }else if(is_string($pagina)){
            return $pagina;
        }
        return '';
    }
    /**
     * Titolo della pagina.
     * @param int $indice
     * @return string
     */
    public function nomePagina($indice){
        $pagina = $this->pagine[$indice];
        if($pagina instanceof Pagina){
            return $pagina->nome();
        }
        return '';
    }
    
    /**
     * Numero di pagine.
     * @return int
     */
    public function numeroPagine() {
        return count($this->pagine);
    }
    
    
}