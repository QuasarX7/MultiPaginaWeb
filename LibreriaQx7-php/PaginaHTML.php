<?php

include_once 'Oggetto.php';
include_once 'Tag.php';
include_once 'Stile.php';
include_once 'javascript.php';
include_once 'Browser.php';

/**
 * Classe che implementa la costruzione di una pagina html.
 *
 * @author Dott. Domnico della PERUTA
 */
class PaginaHTML extends Oggetto{
  
    protected $titolo;
    protected $ricerca = '';
    protected $css = array();
    protected $file =''; ///< importa file esterni
    protected $javascript = '';

    /**
     * 
     * @param string   $titolo
     */
    public function __construct($titolo) {
        self::titolo($titolo);
    }
    
    /**
     * Aggiungi titolo alla pagina.
     * 
     * @param string $titolo
     */
    public function titolo($titolo){
        if(is_string($titolo)){
            $this->titolo = $titolo;
            self::parareChiaviDiRicerca($titolo);
        }
    }
    
    /**
     * Aggiungi una parola di suggerimenrto per la ricerca web.
     * 
     * @param string $parola
     */
    public function parareChiaviDiRicerca($parola) {
        if(is_string($parola)){
            $this->ricerca .= (strlen($this->ricerca) > 0 ? ' ,' : '') . $parola;
        }
    }

    /**
     * Permette di aggiungere al 'head' codice JavaScript e CSS, oppure quasiasi elemento o attributo HTML al 'body'.
     * 
     * @param  $valore
     *
     * {@inheritDoc}
     * @see Oggetto::aggiungi()
     */
    public function aggiungi($valore) {
        if ($valore instanceof JavaScript) {
            $this->javascript .= $valore->vedi();
            
        }elseif ($valore instanceof RegolaCSS) {
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
        
        $codifica = new Tag("meta",[new Attributo('encoding','utf-8')]);
        $ricercaWeb = new Tag(
            "meta",
            [
                new Attributo('name','keywords'),
                new Attributo('content',$this->ricerca)
            ]
        );
        $correzioneIE = new Tag( // forza IE ad aprirla nella modalità più recente possibile
            'meta',
            [
                new Attributo('http-equiv', 'X-UA-Compatible'),
                new Attributo('content','IE=edge')
            ]
        );
        $titolo = new Tag("title", $this->titolo . '');
        $regoleCSS = ' ' . $this->file;
        foreach ($this->css as $regola) {
            $regoleCSS .= $regola . '';
        }
        
        $stile = new Tag('style',new Attributo('type','text/css'),$regoleCSS);
        $head = new Tag("head", $correzioneIE . $codifica . $ricercaWeb . $this->javascript . $titolo . $stile  );
        $html = new Tag("html",  $head . $body . '');
        return $intestazione . $html->vedi();
    }

}
