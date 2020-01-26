<?php
/**
 * La classe Pagina permette di leggere il contenuto di un file di testo.
 * 
 * @author Dott. Domenico della Peruta
 *
 */
class Pagina {
    
    protected $nome;
    protected $file;
    
    public function __construct($nome,$file){
        $this->nome = $nome;
        $this->file = $file;
    }
    
    /**
     * Nome pagina.
     * 
     * @return string
     */
    public function nome(){
        return $this->nome;
    }

    /**
     * File contenente il testo della pagina.
     * @return string
     */
    public function file(){
        return $this->file;
    }

    /**
     * Contenuto del file.
     * 
     * @return string
     */
    public function testo(){
        if(is_string($this->file)){
            $testo = file_get_contents($this->file);
            if($testo){
                return $testo;
            }
        }
        return '';
    }
    
    
}