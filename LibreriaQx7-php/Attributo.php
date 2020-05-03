<?php

/**
 * Classe che implementa un attributo di un tag HTML.
 *
 * @author Dott. Domenico della PERUTA
 */
class Attributo{
    protected $nome;
    protected $valore;
    
    
    public function __construct($nome, $valore) {
        if(strlen($nome) > 0){
           $this->nome = Tag::controllo($nome,30);
        }
        if(strlen($this->nome) > 0 && !is_null($valore)){
            $this->valore = $valore;
        }
    }
    
    public function nome() {
        return $this->nome;
    }
    
    public function valore() {
        return $this->valore;
    }
    
    public function aggiungiValore($valore) {
        $this->valore=$valore;
    }

    public function __toString() {
        $tag = '';
        if(strlen($this->nome) > 0){
            $tag = $this->nome;
            if (!is_null($tag)) {
                if (!is_null($this->valore)) {
                    $tag .=  "=\"" . $this->valore . "\"";
                }else{
                    $tag .=  "=\"\"";
                }
            }
        }
        return $tag;
    }
}
