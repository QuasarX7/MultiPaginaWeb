<?php
class Browser {
    protected $dati;
    
    protected $nome = '?';
    protected $versione = '?';
    
    protected $dispositivoMobile = false;
    protected $dispositivo = '?';
    
    /**
     * Costruttore che inizializza le variabili.
     * 
     */
    public function __construct() {
        if(isset($_SERVER['HTTP_USER_AGENT'])){
            $this->dati = $_SERVER['HTTP_USER_AGENT'];
            $lista = array('MSIE','Trident','Firefox','Opera','OPR','Edge','Chrome','Safari');
            
            foreach($lista as $browser){
                $match=array();
                if (preg_match("#($browser)[/ ]?([0-9.]*)#", $this->dati, $match)){
                    $this->nome = $match[1] ===  'Trident' ? 'Explorer' : ($match[1] ===  'OPR' ? 'Opera' :  $match[1] );
                    $this->versione = $match[1] ===  'Trident'?  $match[2] + 4.0 : $match[2];
                    break ;
                }
            }
            
            $device = array("iPhone", "Android", "Windows Phone", "BlackBerry", "iPod");
            foreach ($device as $value) {
                if (strpos($this->dati, $value) !== false) {
                    $this->dispositivoMobile = true;
                    $this->dispositivo = $value;
                    break;
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

    public function telefono(){
        return $this->dispositivoMobile;
    }
    
    public function dispositivo(){
        return $this->dispositivo;
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
    
    static public function infoClient() {
        
        $dati = '';
        $chiaveServer = array(
            'PHP_SELF','argv','argc','GATEWAY_INTERFACE','SERVER_ADDR','SERVER_NAME',
            'SERVER_SOFTWARE','SERVER_PROTOCOL','REQUEST_METHOD','REQUEST_TIME',
            'REQUEST_TIME_FLOAT','QUERY_STRING','DOCUMENT_ROOT','HTTP_ACCEPT','HTTP_ACCEPT_CHARSET',
            'HTTP_ACCEPT_ENCODING','HTTP_ACCEPT_LANGUAGE','HTTP_CONNECTION','HTTP_HOST',
            'HTTP_REFERER','HTTP_USER_AGENT','HTTPS','REMOTE_ADDR','REMOTE_HOST','REMOTE_PORT',
            'REMOTE_USER','REDIRECT_REMOTE_USER','SCRIPT_FILENAME','SERVER_ADMIN','SERVER_PORT',
            'SERVER_SIGNATURE','PATH_TRANSLATED','SCRIPT_NAME','REQUEST_URI','PHP_AUTH_DIGEST',
            'PHP_AUTH_USER','PHP_AUTH_PW','AUTH_TYPE','PATH_INFO','ORIG_PATH_INFO'
        ) ;
        
        
        $dati .= '<table cellpadding="2">' ;
        
        foreach ($chiaveServer as $par) {
            if (isset($_SERVER[$par])) {
                $dati .= '<tr><td>'.$par.'</td><td>' . $_SERVER[$par] . '</td></tr>' ;
            }else {
                $dati .= '<tr><td>'.$par.'</td><td>-</td></tr>' ;
            }
        }
        $dati .= '</table>' ;
        
        return $dati;
    }
}