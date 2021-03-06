<?php

include_once 'Oggetto.php';
include_once 'Tag.php';
include_once 'Stile.php';
include_once 'javascript.php';
include_once 'Browser.php';


/**
 * Classe che implementa la costruzione di una pagina html.
 *
 * @author Dott. Domnico della PERUTA
 */
class PaginaHTML extends Oggetto{
  
    const LIMITE_DESKTOP = 1280;
    const LIMITE_PORTATILE = 1024;
    const LIMITE_TABLET  = 737;
    const LIMITE_SMARTPHONE = 320;//480;
    
    const JQUERY = 'LibreriaQx7-php/jquery-3.3.1.slim.min.js';//NON FUNZIONALITA AJAX
    const BOOTSTRAP_CSS = 'LibreriaQx7-php/bootstrap-4.4.1/css/bootstrap.min.css';
    const BOOTSTRAP_JS = 'LibreriaQx7-php/bootstrap-4.4.1/js/bootstrap.min.js';
    const PRISMA_JS = 'LibreriaQx7-php/prism.js';
    const PRISMA_CSS = 'LibreriaQx7-php/prism.css';
    
    protected $titolo;
    protected $logo = "";
    protected $ricerca = '';
    protected $css = array();
    protected $file =''; ///< importa file esterni
    protected $javascript = '';
    protected $jquery = '';
    /**
     * 
     * @param string   $titolo
     */
    public function __construct($titolo) {
        self::titolo($titolo);
    }
    
    /**
     * Aggiungi titolo alla pagina.
     * 
     * @param string $titolo
     */
    public function titolo($titolo){
        if(is_string($titolo)){
            $this->titolo = $titolo;
            self::parareChiaviDiRicerca($titolo);
        }
        
    }
    
    /**
     * Aggiungi file icona alla pagina web.
     * 
     * @param string $file
     */
    public function logoPNG($file){
        if(is_string($file)){
            $this->logo = $file;
        }
    }
    
    /**
     * Aggiungi una parola di suggerimenrto per la ricerca web.
     * 
     * @param string $parola
     */
    public function parareChiaviDiRicerca($parola) {
        if(is_string($parola)){
            $this->ricerca .= (strlen($this->ricerca) > 0 ? ' ,' : '') . $parola;
        }
    }

    /**
     * Permette di aggiungere al 'head' codice JavaScript e CSS, oppure quasiasi elemento o attributo HTML al 'body'.
     * 
     * @param  $valore
     *
     * {@inheritDoc}
     * @see Oggetto::aggiungi()
     */
    public function aggiungi($valore) {
        
        if ($valore instanceof JQuery) {
            $this->jquery .= $valore->vedi();
            
        }elseif ($valore instanceof JavaScript) {
            $this->javascript .= $valore->vedi();
            
        }elseif ($valore instanceof RegolaCSS) {
            $this->css[$valore->selettore()] = $valore;
        }else{
            parent::aggiungi($valore);
        }
    }
    
    /**
     * Carica file esterni.
     * 
     * @param string $file
     */
    public function importaCSS($file){
        if(is_string($file)){
            $this->file .= "@import url(".$file.");";
        }
    }

    /**
     * Metodo che assegna un valore predefinito di stile.
     */
    protected function inizializzaCSS(){
            /* unione delle due colonne */
            /*
            @media (max-width: 500px) {
                th,td { 
                    float:none; 
                    display:block; 
                } 
            }
            */
        $schermoCellulare = new SchermoCSS([
            new RegolaCSS('th:nth-child(1),td',[
                new DichiarazioneCSS('float','none'),
                new DichiarazioneCSS('display','block'),
                new DichiarazioneCSS('width','100% !important')
            ]),
            new RegolaCSS('th:nth-child(2),th:nth-child(3)',[
                new DichiarazioneCSS('display','none')
            ]),
            new RegolaCSS('td:nth-child(3)',[
                new DichiarazioneCSS('color','#4169E1')
            ]),
            //tbody tr td:nth-child(1) span{color: #DC143C;

        ],500);
        return $schermoCellulare . '';
    }
    
    public function importaFont($nome,$file){
        if(is_string($file) && is_string($nome)){
            $this->file .= "@font-face {font-family: '".$nome."';src: url('".$file."') format('truetype');}";
        }
    }
    
    private function importaLibreriaPrismaCSS(){
        return new Tag(
            "link",
            [
                new Attributo("rel", "stylesheet"),
                new Attributo("href", self::PRISMA_CSS)
            ]
            ).'';
    }
    
    private function importaLibreriaPrismaJS(){
        return new Tag('script',[new Attributo('src',self::PRISMA_JS)],' ').'';
    }
    
    private function importaLibreriaJQuery(){
        return new Tag('script',[new Attributo('src',self::JQUERY)],' ').'';
    }
    
    private function importaLibreriaBootstrapJavaScript(){
        return new Tag('script',[new Attributo('src',self::BOOTSTRAP_JS)],' ').'';
    }
    
    private function importaLibreriaBootstrapCSS(){
        return new Tag(
            "link",
            [
                new Attributo("rel", "stylesheet"),
                new Attributo("href", self::BOOTSTRAP_CSS)
            ]
            ).'';
    }

    public function cssCellulareVerticale(array $regoleCss){
        $this->aggiungi(
            new SchermoCSS(
                $regoleCss,
                self::LIMITE_SMARTPHONE // limite massimo
            )
        );
    }
    
    public function cssCellulareOrizzontale(array $regoleCss){
        $this->aggiungi(
            new SchermoCSS(
                $regoleCss,
                self::LIMITE_TABLET, // limite massimo
                self::LIMITE_SMARTPHONE // limite minimo
            )
        );
    }
    
    public function cssTablet(array $regoleCss){
        $this->aggiungi(
            new SchermoCSS(
                $regoleCss,
                self::LIMITE_PORTATILE, // limite massimo
                self::LIMITE_TABLET // limite minimo
            )
        );
    }
    
    public function cssTabletVerticale(array $regoleCss){
        $this->aggiungi(
            new SchermoCSS(
                $regoleCss,
                self::LIMITE_PORTATILE, // limite massimo
                self::LIMITE_TABLET, // limite minimo
                SchermoCSS::VERTICALE
            )
        );
    }
    
    public function cssTabletOrizzontale(array $regoleCss){
        $this->aggiungi(
            new SchermoCSS(
                $regoleCss,
                self::LIMITE_PORTATILE, // limite massimo
                self::LIMITE_TABLET, // limite minimo
                SchermoCSS::ORIZZONTALE
            )
        );
    }
    
    public function cssMiniDesktop(array $regoleCss){
        $this->aggiungi(
            new SchermoCSS(
                $regoleCss,
                SchermoCSS::INDEFINITO, // limite massimo
                self::LIMITE_PORTATILE// limite minimo
            )
        );
    }
    
    public function cssDesktop(array $regoleCss){
        $this->aggiungi(
            new SchermoCSS(
                $regoleCss,
                SchermoCSS::INDEFINITO, // limite massimo
                self::LIMITE_DESKTOP// limite minimo
            )
        );
    }
        
       

      
    
    public function __toString() {
        $intestazione = "<!DOCTYPE html>";
        
        $body = new Tag("body", $this->attributi, $this->contenuto . self::importaLibreriaBootstrapJavaScript() .self::importaLibreriaPrismaJS() );
        $dispositiviMobili = new Tag(
            "meta",
            [
                new Attributo('name','viewport'),
                new Attributo('content','width=device-width'),
                new Attributo('initial-scale','1'),
                new Attributo('maximum-scale','1')
            ]
        );
        //<link rel="icon" href="/favicon.png" type="image/png" />
        $logo = new Tag(
            "link",
            [
                new Attributo("rel", "icon"),
                new Attributo("href", $this->logo),
                new Attributo("type", "image/png")
            ]
        );
        
        //<meta charset="utf-8">
        $codifica = new Tag("meta",[new Attributo('charset','utf-8')]);
        $ricercaWeb = new Tag(
            "meta",
            [
                new Attributo('name','keywords'),
                new Attributo('content',$this->ricerca)
            ]
        );
        $correzioneIE = new Tag( // forza IE ad aprirla nella modalità più recente possibile
            'meta',
            [
                new Attributo('http-equiv', 'X-UA-Compatible'),
                new Attributo('content','IE=edge')
            ]
        );
        $titolo = new Tag("title", $this->titolo . ' ');
        $regoleCSS = ' ' . $this->file;
        $regoleCSS .= $this->inizializzaCSS();
        foreach ($this->css as $regola) {
            $regoleCSS .= $regola . '';
        }
        
        $stile = new Tag('style',new Attributo('type','text/css'),$regoleCSS);
        $head = new Tag("head", $logo. self::importaLibreriaPrismaCSS(). self::importaLibreriaBootstrapCSS(). $correzioneIE . $codifica . $ricercaWeb .$dispositiviMobili. self::importaLibreriaJQuery() . $this->javascript . $titolo . $stile  );
        $html = new Tag("html",  $head  . $this->jquery . $body . '');
        return $intestazione . $html->vedi();
    }

}
