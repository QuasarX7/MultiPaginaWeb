<?php
include_once 'Tag.php';

/**
 * Classe che contiene il codice JavaScript da inserire nella pagina HTML (in head).
 * 
 * @author Dott. Domenico della Peruta
 *
 */
class JavaScript extends Tag{
    /**
     * Costruttore.
     * 
     * @param string|Tag $codice
     */
    public function __construct($codice) {
        parent::__construct('script',[new Attributo('type','text/javascript')],$codice);
    }
}