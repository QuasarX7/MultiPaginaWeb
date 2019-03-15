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
    }
}

/**
 * Parte contenente il testo principale o un articolo.
 * 
 * @author Dott. Domenico della PERUTA
 *
 */
class TestoPagina extends Pannello {
    public function __construct($lunghezza, $altezza, $coloreSfondo=null, $coloreTesto=null) {
        parent::__construct($lunghezza, $altezza,$coloreSfondo, $coloreTesto);
        $this->nome = 'article';
    }
}

/**
 * Annotazioni o parti marginali della pagina.
 *
 * @author Dott. Domenico della PERUTA
 *
 */
class NotePagina extends Pannello {
    public function __construct($lunghezza, $altezza, $coloreSfondo=null, $coloreTesto=null) {
        parent::__construct($lunghezza, $altezza,$coloreSfondo, $coloreTesto);
        $this->nome = 'aside';
    }
}
/**
 * Paragrafo o sezione del testo principale.
 * 
 * @author Dott. Domenico della PERUTA
 *
 */
class ParagrafoPagina extends Pannello {
    public function __construct($lunghezza, $altezza, $coloreSfondo=null, $coloreTesto=null) {
        parent::__construct($lunghezza, $altezza,$coloreSfondo, $coloreTesto);
        $this->nome = 'section';
    }
}

/**
 * Area principale della pagina non contenuta dalle altri elementi html ma solo dal 'body'.
 * 
 * @author Dott. Domenico della PERUTA
 *
 */
class AreaPagina extends Pannello {
    public function __construct($coloreSfondo=null, $coloreTesto=null) {
        parent::__construct('100%', 'auto',$coloreSfondo, $coloreTesto);
        $this->nome = 'main';
    }
}





