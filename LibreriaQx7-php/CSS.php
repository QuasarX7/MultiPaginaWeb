<?php

/**
 * Classe che implementa una regola di un foglio di stile (CSS). 
 *
 * @author Dott. Domenico della PERUTA
 */
class RegolaCSS {
    protected $selettore;
    protected $dichiarazioni;
    
    /**
     * 
     * @param string $selettore
     * @param array DichiarazioneCSS $dichiarazioni
     */
    public function __construct($selettore,$dichiarazioni=array()) {
        $this->selettore = $selettore;
        $this->dichiarazioni = $dichiarazioni;
    }
    
    /**
     * Aggiungi dichiarazione.
     * 
     * @param DichiarazioneCSS $dichiarazioneCSS
     */
    public function aggiungi($dichiarazioneCSS) {
        if($dichiarazioneCSS instanceof DichiarazioneCSS){
            $this->dichiarazioni[$dichiarazioneCSS->proprietà()] = $dichiarazioneCSS;
        }
    }
    
    /**
     * Selettore della regola CSS.
     * 
     * @return string
     */
    public function selettore() {
        return $this->selettore;
    }

    /**
     * Lista dichiarazioni.
     * 
     * @return string
     */
    public function dichiarazioni() {
        $stringa = '';
        foreach ($this->dichiarazioni as $dichiarazioneCSS) {
            $stringa .= $dichiarazioneCSS . '';
        }
        return $stringa;
    }
    
    public function __toString() {
        $stringa = '';
        if(is_string($this->selettore)){
            $stringa .= $this->selettore . '{' . $this->dichiarazioni() . '}';
        }
        return $stringa;
    }

    
}

/**
 * Classe che implementa una dichiarazzione di un foglio di stile (CSS). 
 *
 * @author Dott. Domenico della PERUTA
 */
class DichiarazioneCSS{
    protected $proprietà;
    protected $valore;
    
    /**
     * 
     * @param string $proprietà
     * @param string $valore
     */
    public function __construct($proprietà, $valore) {
        $this->proprietà = $proprietà;
        $this->valore = $valore;
    }
    
    /**
     * 
     * @return string
     */
    function proprietà() {
        return $this->proprietà;
    }

    /**
     * 
     * @return string
     */
    function valore() {
        return $this->valore;
    }

      
    /**
     * 
     * @return string
     */
    public function __toString() {
        if (is_string($this->proprietà) && is_string($this->valore)) {
            return ' ' . $this->proprietà . ': ' . $this->valore . ';';
        } else {
            return '';
        }
    }

}

abstract class PropritàCSS{
    const COLORE = 'color';
    const COLORE_SFONDO = 'background-color'; 
    const COLORE_BORDO = 'border-color';
    const ALTEZZA = 'height';
    const LUNGHEZZA = 'width';
    
    const ALTO = 'top';
    const BASSO = 'bottom';
    const DESTRA = 'right';
    const SINISTRA = 'left';
    const SOVRAPPOSIZIONE = 'z-index';
    
    
}




/**
 * Classe che implementa delle regole di un foglio di stile (CSS)
 * associato a una particolare risoluzione dello schermo.
 *
 * @author Dott. Domenico della PERUTA
 */
class SchermoCSS extends RegolaCSS{

    /**
     * Costruttore.
     * 
     * @param array $regole
     * @param int $max  massima risoluzione dello schermo
     * @param int $min  minima risoluzione dello schermo
     */
    public function __construct(array $regole, int $max, int $min=0) {
        
        $this->dichiarazioni = $regole;
        
        if($min <=0 && $max >0){
            $this->selettore = '@media only screen and (max-width:'.$max.'px)';
        }elseif ($min > 0 && $max >0){
            $this->selettore = '@media only screen  and (min-width:'.$min.'px) and (max-width:'.$max.'px)';
        }elseif ($min > 0 && $max <=0){
            $this->selettore = '@media only screen  and (min-width:'.$min.'px)';
        }else{
            $this->selettore = '';
        }
        
    }
    
    public function aggiungi($regolaCSS) {
        if($regolaCSS instanceof RegolaCSS){
            $this->dichiarazioni[] = $regolaCSS;
        }
    }
    
    
}