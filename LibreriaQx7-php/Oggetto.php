<?php

/**
 * Classe che descrive un oggetto grafico HTML generico. Un oggtto HTML è definito
 * da un contenuto e da degli attributi.
 * Il contenuto può essere del semplice testo o un altro oggetto HTML, mentre rappresentano
 * le proprietà grafiche dell'oggetto.
 *
 * @author Dott. Domenico della PERUTA
 */
abstract class Oggetto {
    
    protected $attributi = array();///< lista stili o attributi
    protected $contenuto = '';

    /**
     * Aggiungi un attributo, uno stile o un altro elemento html.
     * 
     * @param  $valore
     */
    public function aggiungi($valore) {
        if ($valore instanceof Stile) {
            if(!array_key_exists('style',$this->attributi)){
                $this->attributi[$valore->nome()] = $valore;
            }else{
                $this->attributi[$valore->nome()]->concatenaStringaCSS($valore->valore());
            }
            
        }elseif ($valore instanceof Attributo) {
            $this->attributi[$valore->nome()] = $valore;
            
        } elseif ($valore instanceof Tag) {
            $this->contenuto .= $valore .'';
            
        }elseif (is_string ($valore)) {
            $this->contenuto .= $valore ;
        }
    }
    
    /**
     * 
     * @return string
     */
    public function __toString() {
        return "metodo '__toString' non definito!!!!";
    }
    
    public function vedi() {
        return $this->__toString();
    }
    
    public function crea() {
        echo $this->__toString();
    }
    
}
