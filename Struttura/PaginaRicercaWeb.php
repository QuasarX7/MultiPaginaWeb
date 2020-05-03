<?php

include_once 'Struttura/PaginaCerca.php';

/**
 * Classe che implementa una pagina di ricerca dei documenti HTML presenti nel sito per 
 * argomento.
 * 
 * @author Dott. Domenico della Peruta
 *
 */
class PaginaRicercaWeb extends PaginaCerca{


    /**
     * Costruttore.
     * 
     * @param array $lista degli indirizzi web (URL) es.: ['pagina 1' => '/pagina1.html']
     */
    public function __construct(array $lista){
        parent::__construct($lista, 0, []);
        
    }
    
    protected function script($info){
        $script = new JavaScript(
            $this->creaTabella().
            '$(\'button.icona\').on(\'click\',function(e){'.
                'var valoreCampo = $(\'#cerca\').val();'.
                'for(var [chiave, valore] of tabella){'.
                    'if(chiave == valoreCampo){'.
                        'window.open(valore,\'_blank\');'.
                    '}'.
                '}'.
            '});'
            );
        self::aggiungi($script);
       
    }
    
    protected function menu() {
        $lista = new Tag('datalist',[new Attributo('id', 'input_campo')]);
        foreach ($this->tabella as $chiave => $riga) {
            if (isset($chiave)){
                self::voceMenu($lista, $chiave);
            }
        }
        self::aggiungi($lista);
    }
    
    protected function creaTabella(){
        $codice  = 'let tabella = new Map();'; // inizio
        foreach ($this->tabella as $chiave => $riga) {
            $codice .= 'tabella.set(\''.$chiave . '\',\''.$riga.'\');';
        }
        return $codice;
    }
}

