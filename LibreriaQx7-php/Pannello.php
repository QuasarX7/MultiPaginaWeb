<?php

include_once 'Oggetto.php';
include_once 'Tag.php';
include_once 'Stile.php';
include_once 'CSS.php';


class Posizione{
    const STATICA = 'static';
    const RELATIVA = 'relative';
    const ASSOLUTA = 'absolute';
    const FISSA = 'fixed';
    
    static function è($valore){
        return Posizione::ASSOLUTA == $valore || Posizione::STATICA == $valore 
                || Posizione::RELATIVA == $valore || Posizione::FISSA == $valore;
    }
}

class Dimensione{
    const ALTEZZA = PropritàCSS::ALTEZZA;
    const LUNGHEZZA = PropritàCSS::LUNGHEZZA;
    
    static function è($valore){
        return Dimensione::ALTEZZA == $valore || Dimensione::LUNGHEZZA == $valore;
    }
}

abstract class Limite{
    const MASSIMO = 'max';
    const MINIMO = 'min';
    
    static function è($valore){
        return Limite::MASSIMO == $valore || Limite::MINIMO == $valore;
    }
}


/**
 * Classe che implementa un pannello grafico di una pagina HTML.
 *
 * @author Dott. Domenico della PERUTA
 */
class Pannello extends Tag{
    
    public function __construct($lunghezza, $altezza, $coloreSfondo, $coloreBordo, $coloreTesto) {
        static $z = 0;
        $css = new Stile([
            new DichiarazioneCSS('overflow','hidden'),
            new DichiarazioneCSS(PropritàCSS::SOVRAPPOSIZIONE, ($z++) .''),
            new DichiarazioneCSS(PropritàCSS::ALTEZZA, $altezza.''),
            new DichiarazioneCSS(PropritàCSS::LUNGHEZZA, $lunghezza.''),
            new DichiarazioneCSS(PropritàCSS::COLORE_SFONDO,$coloreSfondo),
            new DichiarazioneCSS(PropritàCSS::COLORE_BORDO,$coloreBordo),
            new DichiarazioneCSS(PropritàCSS::COLORE,$coloreTesto)
        ]);
        parent::__construct('div',$css);
        
    }

    public function bordo($colore,$spessore) {
        
    }
    /**
     * Posizionamento del pannello.
     * 
     * @param string $tipo  di posizionamento 
     *                      Es.: Posizione::ASSOLUTA
     * @param $x
     * @param $y
     */
    public function posiziona($tipo,$x=null,$y=null) {
        if (Posizione::è($tipo)) {
            $css = new Stile('position', $tipo);
            if (!is_null($x)) {
                $css->aggiungi(PropritàCSS::SINISTRA, $x . '');
            }
            if (!is_null($y)) {
                $css->aggiungi(PropritàCSS::ALTO, $y . '');
            }
            $this->aggiungi($css);
        }
    }
    
    /**
     * 
     * @param string $dimensione    Es.: Dimensione::ALTEZZA
     * @param string $limite        Es.: Limite::MINIMO
     * @param  $valore
     */
    public function limite($dimensione,$limite,$valore) {
        if((is_int($valore) || is_string($valore)) && Dimensione::è($dimensione) && Limite::è($limite)){
            $css = new Stile($limite.'-'.$dimensione , $valore . '');
            $this->aggiungi($css);
        }
    }
}


