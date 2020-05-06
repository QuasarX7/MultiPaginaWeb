<?php

include_once 'Tag.php';
include_once 'Menu.php';

class BarraMenu extends Tag{
    
    protected $livello;
    protected $menu = array();
    protected static $coloreSfondo; 
    protected static $coloreTesto;
    
    protected static $coloreSfondoVoce;
    protected static $coloreTestoVoce;
    
    protected static $coloreSfondoVoce2liv;
    protected static $coloreTestoVoce2liv;
    
    protected static $coloreSeleziona;


    /**
     * Costruisce un tag 'nav'.
     */
    public function __construct($coloreSfondo,$coloreTesto,$coloreSeleziona){
        $this->livello = 0;
        $this->nome = 'nav';
        $this->contenuto = ' ';
        self::$coloreSfondo = $coloreSfondo;
        self::$coloreTesto = $coloreTesto;
        self::$coloreSeleziona = $coloreSeleziona; 
        
        if(!isset(self::$coloreSfondoVoce)){
            self::$coloreSfondoVoce = isset($coloreSfondo) ? $coloreSfondo : 'white';
        }
        if(!isset(self::$coloreTestoVoce)){
            self::$coloreTestoVoce = isset($coloreTesto) ? $coloreTesto : 'black';
        }
        if(!isset(self::$coloreSfondoVoce2liv)){
            self::$coloreSfondoVoce2liv = self::$coloreSfondoVoce;
        }
        if(!isset(self::$coloreTestoVoce2liv)){
            self::$coloreTestoVoce2liv = self::$coloreTestoVoce;
        }
    }
    
    /**
     * Numero di voci presenti.
     * @return int
     */
    public function numeroVoci():int{
        return count($this->menu);
    }

    public function menuPrincipale($coloreSfondo,$coloreTesto){
        self::$coloreSfondo = $coloreSfondo;
        self::$coloreTesto = $coloreTesto;
    }
    
    public function menuPrimoLivello($coloreSfondo,$coloreTesto){
        self::$coloreSfondoVoce = $coloreSfondo;
        self::$coloreTestoVoce = $coloreTesto;
    }
    
    
    public function menuSecondoLivello($coloreSfondo,$coloreTesto){
        self::$coloreSfondoVoce2liv = $coloreSfondo;
        self::$coloreTestoVoce2liv = $coloreTesto;
    }
    


    /**
     * Aggiungi menu.
     * @param Menu $valore
     */
    public function aggiungi($valore){
        if($valore instanceof Menu){
            $this->menu[$valore->nome()] = $valore;
        }else{
            parent::aggiungi($valore);
        }
    }
    
    /**
     * 
     * @param string $nome
     * @return Menu
     */
    public function cercaVoce($nome){
        if(isset($this->menu[$nome])){
            return $this->menu[$nome];
        }
        return null;
    }
    /**
     * nessun menu associato.
     * 
     * @return boolean
     */
    public function nessunaVoce(){
        return count($this->menu) == 0;
    }
    
    /**
     * Lista delle voci menu.
     * 
     * @return array Menu
     */
    public function vociMenu(){
        return $this->menu;
    }
    
 
   
    private function creaLogoBarra(string $logo){
         /*
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
         </button>
         */
        $pulsante= new Tag('button',[
        //<a class="navbar-brand" href="#">Navbar</a>
            new Attributo("class", "navbar-toggler"),
            new Attributo("type", "button"),
            new Attributo("data-toggle", "collapse"),
            new Attributo("data-target", "#navbarSupportedContent"),
            new Attributo("aria-controls", "navbarSupportedContent"),
            new Attributo("aria-expanded", "false"),
            new Attributo("aria-label", "Toggle navigation")
        ]);
        $pulsante->aggiungi(new Stile([
            new DichiarazioneCSS('background-color',self::$coloreTesto),
            new DichiarazioneCSS('color',self::$coloreSfondo)
        ]));
        $pulsante->aggiungi(new Tag("span",[new Attributo("class", "navbar-toggler-icon")]),'');
        $pulsante->aggiungi("🌫");
        $this->aggiungi($pulsante);
        $link= new Tag('a',[new Attributo("class", "navbar-brand"),new Attributo("href", "#")],$logo);
        $this->aggiungi($link);
        
        
    }
    
    protected function creaAreaMenu(){
            $area = new Tag("div",[
                new Attributo('class','collapse navbar-collapse'),
                new Attributo('id','navbarSupportedContent')
            ]);
            $this->aggiungi($area);
            
            $lista = new Tag('ul',new Attributo('class',"navbar-nav mr-auto"));
            foreach ($this->menu as  $voce) {
                $lista->aggiungi($voce);
            }
            $this->aggiungi($lista);
    }
    
    
    private function stileSeleziona() {
        $css = new Tag('style');
        $selezione = new  RegolaCSS('nav a:hover, .dropdown-item:hover', [
            new DichiarazioneCSS('background-color', self::$coloreSeleziona)
        ]);
        $css->aggiungi($selezione.'');
        
        $selezione = new  RegolaCSS('.navbar, .dropdown-menu', [
            new DichiarazioneCSS('padding', '0'),
            new DichiarazioneCSS('border-radius', '0'),
            new DichiarazioneCSS('box-shadow','4px 4px 5px #111')
        ]);
        $css->aggiungi($selezione.'');
        
        $menuRadice = new  RegolaCSS('.dropdown-item', [
            new DichiarazioneCSS('padding-top', '0'),
            new DichiarazioneCSS('padding-bottom', '0'),
            new DichiarazioneCSS('padding-left', '20px')
        ]);
        $css->aggiungi($menuRadice.'');
        
        $menuRadice = new  RegolaCSS('nav a', [
            new DichiarazioneCSS('line-height','40px')
        ]);
        $css->aggiungi($menuRadice.'');

        $this->aggiungi($css);
    }
    

    /**
     * {@inheritDoc}
     * @see Tag::__toString()
     */
    public function __toString(){
        if($this->numeroVoci() > 0){
            if($this->nome == 'nav'){ 
                $this->stileSeleziona();
                // se è un menu principale
                $this->aggiungi(new Attributo('class','navbar navbar-expand-md sticky-top'));
                $this->aggiungi(new Stile('background-color',self::$coloreSfondo));
                $this->creaLogoBarra(date("d-m-Y H:i").'');
                $this->creaAreaMenu();
            }else{
                // se è un elemento derivato (Menu)
                $this->creaAreaMenu();
            }
        }
        return parent::__toString();
    }
    
    
}