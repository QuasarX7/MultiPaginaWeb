<?php
class Browser {
    protected $dati;
    
    protected $nome = '?';
    protected $versione = '?';
    
    /**
     * Costruttore che inizializza le variabili.
     * 
     */
    public function __construct() {
        if(isset($_SERVER['HTTP_USER_AGENT'])){
            $this->dati = $_SERVER['HTTP_USER_AGENT'];
            $lista = array('MSIE','Trident','Firefox','Opera','OPR','Edge','Chrome','Safari');
            
            foreach($lista as $browser){
                if (preg_match("#($browser)[/ ]?([0-9.]*)#", $this->dati, $match)){
                    $this->nome = $match[1] ===  'Trident' ? 'Explorer' : ($match[1] ===  'OPR' ? 'Opera' :  $match[1] );
                    $this->versione = $match[1] ===  'Trident'?  $match[2] + 4.0 : $match[2];
                    break ;
                }
            }
        }
    }
    
    
    /**
     * @return string
     */
    public function nome(){
        return $this->nome;
    }
    
    /**
     * @return string
     */
    public function versione(){
        return $this->versione;
    }

    
    
    /**
     * Verifica la compatibilità (o quasi) con lo standard HTML5
     */
    public function html5(){
        switch ($this->nome) {
            case 'Explorer': return $this->versione() > 9;
            case 'Opera':return $this->versione() > 10;
            case 'Firefox':return $this->versione() > 3;
            case 'Chrome':return $this->versione() > 5;
            case 'Safari':return $this->versione() > 525.13;
            case 'Edge': return true;
            default:
                return false;
        }
    }
    
    public function __toString() {
        return $this->dati.'';
    }
    
}