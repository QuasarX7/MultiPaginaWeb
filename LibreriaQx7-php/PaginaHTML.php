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

    
    public function __toString() {
        $intestazione = new Tag("!DOCTYPE html");
        
        $body = new Tag("body", $this->attributi, $this->contenuto);
        $titolo = new Tag("title", $this->titolo . '');
        $regoleCSS = ' ';
        foreach ($this->css as $regola) {
            $regoleCSS .= $regola . '';
        }
        $stile = new Tag('style',new Attributo('type','text/css'),$regoleCSS);
        $head = new Tag("head", $titolo . $stile);
        $html = new Tag("html", $intestazione . $head . $body . '');
        return $html->vedi();
    }

}
