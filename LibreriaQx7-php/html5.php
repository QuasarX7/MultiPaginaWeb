<?php
/****
 * File contenente tutti le classi che implementano i tag HTML5.
 */

include_once 'Pannello.php';

/**
 * Identifica l'intestazione della pagina.
 * 
 * @author Dott. Domenico della PERUTA
 */
class IntestazionePagina extends Pannello {
    public function __construct($altezza, $coloreSfondo=null, $coloreTesto=null) {
        parent::__construct('100%', $altezza,$coloreSfondo, $coloreTesto);
        $this->nome = 'header';
        $this->posiziona(Posizione::ASSOLUTA,0,0);
    }
}


/**
 * Identifica eventuali note a fondo pagina.
 * 
 * @author Dott. Domenico della PERUTA
 *
 */
class FinePagina extends Pannello {
    public function __construct($altezza, $coloreSfondo=null, $coloreTesto=null) {
        parent::__construct('100%', $altezza,$coloreSfondo, $coloreTesto);
        $this->nome = 'footer';
        
        $posizione = new Stile('position', Posizione::ASSOLUTA);
        $posizione->aggiungi(PropritàCSS::SINISTRA, '0');
        $posizione->aggiungi(PropritàCSS::BASSO , '0');
        $this->aggiungi($posizione);
        
    }
}

class TestoPagina extends Pannello {
    public function __construct($lunghezza, $altezza, $coloreSfondo=null, $coloreTesto=null) {
        parent::__construct($lunghezza, $altezza,$coloreSfondo, $coloreTesto);
        $this->nome = 'article';
    }
}








