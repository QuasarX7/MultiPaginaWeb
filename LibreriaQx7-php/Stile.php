<?php

include_once 'Attributo.php';
include_once 'CSS.php';

/**
 * Classe che implementa il codice CSS (fogli di stile) all'interno dei Tag
 *
 * @author Dr. Domenico della PERUTA
 */
class Stile extends Attributo{
    
    /**
     * Es.:
     * <pre><code>
     * $stile = new Stile(new DichiarazioneCSS('background-color','#FF00FF')));
     * $stile = new Stile('background-color','#FF00FF'));
     * $stile = new Stile([
     *      new DichiarazioneCSS('background-color','#FF00FF'),
     *      new DichiarazioneCSS('color','#FFFFFF')
     * ]);
     * </code></pre>
     */
    public function __construct() {
        
        if(func_num_args() == 1){
                $valore = func_get_arg(0);
            if (is_array($valore)) {
                $regolaCSS = '';
                foreach ($valore as $dichiarazione) {
                    if ($dichiarazione instanceof DichiarazioneCSS) {
                        $regolaCSS .= $dichiarazione . '';
                    }
                }
                parent::__construct('style', $regolaCSS);

            } elseif ($valore instanceof DichiarazioneCSS) {
                parent::__construct('style', $valore . '');
            }
        } elseif (func_num_args() == 2) {
            $proprietà = func_get_arg(0);
            $valore = func_get_arg(1);
            if(!is_null($proprietà) && !is_null($valore)){
                $css = new DichiarazioneCSS(''.$proprietà,''.$valore);
                parent::__construct('style', $css . '');
            }
            
        }elseif (func_num_args() == 0) {
            parent::__construct('style', '');
        }
    }
    
    /**
     * 
     * @param string $valore
     */
    public function concatenaStringaCSS($valore) {
        if(is_string($valore)){
            $this->valore .= $valore;
        }
    }
    
    /**
     * Aggiungi una dichiarazione CSS.
     * 
     * @param string $proprietà
     * @param string $valore
     */
    public function aggiungi($proprietà,$valore) {
        $css = new DichiarazioneCSS(''.$proprietà,''.$valore);
        $this->valore .= $css . '';
    }

    
    
}
