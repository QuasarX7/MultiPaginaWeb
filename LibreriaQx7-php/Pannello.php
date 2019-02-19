<?php

include_once 'Oggetto.php';
include_once 'Tag.php';
include_once 'Stile.php';
include_once 'CSS.php';

/**
 * 
 * Stile bordo	Descrizione
none	l’elemento non presenta alcun bordo e lo spessore equivale a 0
hidden	equivalente a none
dotted	bordo a puntini
dashed	bordo a lineette
solid	bordo solido e continuo
double	bordo solido, continuo e doppio
groove	tipo di bordo in rilievo
ridge	altro tipo di bordo in rilievo
inset	effetto ‘incastonato’
outset	effetto ‘sbalzato’
 * @author quasar
 *
 */
class StileBordo{
    const NESSUNO = 'none';
    const PUNTINI = 'dotted';
    const LINEETTE = 'dashed';
    const CONTINUO = 'solid';
    const DOPPIA = 'double';
    const SOLCO = 'groove';
    const RILIEVO = 'ridge';
    const INCASTONATO = 'inset';
    const SBALZATO = 'outset';
    
    static function appartiene($valore){
        return  StileBordo::NESSUNO == $valore  || StileBordo::PUNTINI == $valore     || StileBordo::LINEETTE == $valore ||
                StileBordo::CONTINUO == $valore || StileBordo::DOPPIA == $valore      || StileBordo::SOLCO == $valore    || 
                StileBordo::RILIEVO == $valore  || StileBordo::INCASTONATO == $valore || StileBordo::SBALZATO == $valore;
    }
}

class Lato{
    const ALTO = 'top';
    const DESTRA = 'right';
    const BASSO = 'bottom';
    const SINISTRA = 'left';
    
    static function appartiene($valore){
        return Lato::ALTO == $valore || Lato::DESTRA == $valore || Lato::BASSO== $valore || Lato::SINISTRA == $valore;
    }
}

class Posizione {
    const STATICA = 'static';
    const RELATIVA = 'relative';
    const ASSOLUTA = 'absolute';
    const FISSA = 'fixed';
    
    static function appartiene($valore){
        return Posizione::ASSOLUTA == $valore || Posizione::STATICA == $valore 
                || Posizione::RELATIVA == $valore || Posizione::FISSA == $valore;
    }
}

class Dimensione{
    const ALTEZZA = PropritàCSS::ALTEZZA;
    const LUNGHEZZA = PropritàCSS::LUNGHEZZA;
    
    static function appartiene($valore){
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
    /**
     * Imposta la distanza del pannello dagli altri componenti grafici adiacenti.
     * 
     * @param string $alto
     * @param string $basso
     * @param string $sinistra
     * @param string $destra
     */
    public function margine($alto,$basso,$sinistra,$destra){
        $css = new Stile('margin', $alto . ' ' . $destra . ' ' . $basso . ' ' . $sinistra);
        $this->aggiungi($css);
    }
    
    /**
     * Imposta la distanza dal bordo dei componenti grafici interni al pannello.
     * 
     * @param string $alto
     * @param string $basso
     * @param string $sinistra
     * @param string $destra
     */
    public function padding($alto,$basso,$sinistra,$destra){
        $css = new Stile('padding', $alto . ' ' . $destra . ' ' . $basso . ' ' . $sinistra);
        $this->aggiungi($css);
    }
    
    /**
     * Imposta caratteristiche di un bordo.
     * 
     * @param $lato
     * @param $colore
     * @param $spessore
     * @param $stile
     */
    public function bordo($lato,$colore,$spessore,$stile) {
        if(Lato::appartiene($lato) && StileBordo::appartiene($stile) && is_string($colore)){
            $this->aggiungi(
                new Stile('border-'.$lato, $spessore . ' ' . $stile . ' ' . $colore)
            );
        }
    }
    
    /**
     * Pone i bordi del pannello in evidenza.
     * @param $colore
     * @param $spessore
     * @param $stile
     */
    public function bordoInEvidenza($colore,$spessore,$stile) {
        if(StileBordo::appartiene($stile) && is_string($colore)){
            $this->aggiungi(
                new Stile('outline', $spessore . ' ' . $stile . ' ' . $colore)
                );
        }
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
        if (Posizione::appartiene($tipo)) {
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
        if((is_int($valore) || is_string($valore)) && Dimensione::appartiene($dimensione) && Limite::è($limite)){
            $css = new Stile($limite.'-'.$dimensione , $valore . '');
            $this->aggiungi($css);
        }
    }
}


