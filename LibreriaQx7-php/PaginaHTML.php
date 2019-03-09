<?php

include_once 'Oggetto.php';
include_once 'Tag.php';
include_once 'Stile.php';

/**
 * Classe che implementa la costruzione di una pagina html.
 *
 * @author Dott. Domnico della PERUTA
 */
class PaginaHTML extends Oggetto{
  
    protected $titolo;
    protected $css = array();
    protected $file =''; ///< importa file esterni

    /**
     * 
     * @param string   $titolo
     */
    public function __construct($titolo) {
        $this->titolo = $titolo;
    }

    public function aggiungi($valore) {
        if ($valore instanceof RegolaCSS) {
            $this->css[$valore->selettore()] = $valore;
        }else{
            parent::aggiungi($valore);
        }
    }
    /**
     * Carica file esterni.
     * 
     * @param string $file
     */
    public function importaCSS($file){
        if(is_string($file)){
            $this->file .= "@import url(".$file.");";
        }
    }
    
    public function importaFont($nome,$file){
        if(is_string($file) && is_string($nome)){
            $this->file .= "@font-face {font-family: '".$nome."';src: url('".$file."') format('truetype');}";
        }
    }

    
    public function __toString() {
        $intestazione = "<!DOCTYPE html>";
        
        $body = new Tag("body", $this->attributi, $this->contenuto);
        $titolo = new Tag("title", $this->titolo . '');
        $regoleCSS = ' ' . $this->file;
        foreach ($this->css as $regola) {
            $regoleCSS .= $regola . '';
        }
        $stile = new Tag('style',new Attributo('type','text/css'),$regoleCSS);
        $head = new Tag("head", $titolo . $stile);
        $html = new Tag("html",  $head . $body . '');
        return $intestazione . $html->vedi();
    }

}
