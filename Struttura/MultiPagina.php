<?php
include_once 'LibreriaQx7-php/PaginaHTML.php';
include_once 'LibreriaQx7-php/Pannello.php';
include_once 'LibreriaQx7-php/BarraMenu.php';
include_once 'LibreriaQx7-php/Menu.php';
include_once 'Argomento.php';

class MultiPagina extends PaginaHTML {
    const ELENCO = 'ElencoPagine'; // ID
    
    const LUNGHEZZA_PAGINA  = '700px';
    const LUNGHEZZA_PANNELLO_SX  = '270px';
    const FONT_TESTO_R      = 'LibreriaQx7-php/Amita-Regular.ttf';
    const FONT_INTESTAZIONE_R = 'LibreriaQx7-php/Akronim-Regular.ttf';
    //const FONT_TESTO_C      = 'AnonymousPro-Italic.ttf';
    //const FONT_TESTO_C_B    = 'AnonymousPro-BoldItalic.ttf';
    
    protected $barraMenu;
    protected $vociMenu = array();
    
    protected $indice = 0;
    protected $argomento = '';

    protected $argomenti = array();
    
    protected $indiceDiPagina;
    protected $titoloArgomento;
    protected $titoloPagina;
    
    
    protected $paginaTesto;
    protected $indiceLateraleSx;
    protected $suggerimentoLateraleDx;
   
    
    

    public function __construct($titolo){
        parent::__construct($titolo);
        if(!is_null($_GET['pagina'])){
            $this->indice = $_GET['pagina'];
        }
        if(!is_null($_GET['argomento'])){
            $this->argomento = $_GET['argomento'];
        }
        
    }
    
    public function creaBarraMenu($coloreSfondo, $coloreTesto, $coloreSeleziona){
        $this->barraMenu = new BarraMenu($coloreSfondo, $coloreTesto, $coloreSeleziona);
        $this->barraMenu->posizioneFissa();
    }
    
    public function inizializzaPrimoLivelloMenu($coloreSfondo, $coloreTesto){
        if(!is_null($this->barraMenu)){
            $this->barraMenu->menuPrimoLivello($coloreSfondo, $coloreTesto);
        }
    }
    
    public function inizializzaSecondoLivelloMenu($coloreSfondo, $coloreTesto){
        if(!is_null($this->barraMenu)){
            $this->barraMenu->menuSecondoLivello($coloreSfondo, $coloreTesto);
        }
    }
    
    
    
    /**
     * Crea una nuova voce di menu, se il parametro '$menu' è nullo, si aggiungera la
     * nuova voce direttamente alla barra menu precedentemente creata, altrimenti se
     * '$menu' contiene il nome di un'altra voce del un menu, sarà associato al sottomenu
     * di quest'ultimo.
     * 
     * @param string $etichetta
     * @param string $argomento
     * @param int $pagina
     * @param string $menu  etichetta della voce menu (padre) a cui è aggangiato
     */    
     public function aggiungiMenu($etichetta,$argomento,$pagina,$menu=null){
        if(!is_null($this->barraMenu) && is_integer($pagina) && is_string($argomento)){
            $nuovoMenu = new Menu($etichetta,$_SERVER['ADD_HOST'].'?pagina='.$pagina.'&argomento='.$argomento);
            if(is_null($menu)){//aggiungi alla barra menu
                $this->vociMenu[$etichetta] = $nuovoMenu;
            }else{//aggiungi al menu
                if($this->vociMenu[$menu] instanceof Menu){
                    $this->vociMenu[$menu]->aggiungi($nuovoMenu);
                }else{
                   self::aggiungiSottomenu($this->vociMenu, $nuovoMenu, $menu); 
                }
            }
        }
    }
    
    /**
     * Collegamento ipertestuale ad un altra pagina di un argomento.
     * 
     * @param string $argomento
     * @param int $pagina
     * @return string
     */
    private function link($argomento,$pagina){
        return $_SERVER['ADD_HOST'].'?pagina='.$pagina.'&argomento='.$argomento;
    }
    
    /**
     * Metodo ricorsivo di ricerca del voce menu associata.
     * 
     * @param array $listaMenu
     * @param Menu $nuovoMenu
     * @param string $etichetta
     */
    private function aggiungiSottomenu($listaMenu,$nuovoMenu,$etichetta){
        if(is_array($listaMenu)){
            foreach ($listaMenu as $menu) {
                if($menu instanceof Menu){
                    if(!$menu->nessunaVoce()){
                        $menuPadre = $menu->cercaVoce($etichetta);
                        if(!is_null($menuPadre)){
                            $menuPadre->aggiungi($nuovoMenu);
                        }else{
                            self::aggiungiSottomenu($menu->voci(), $nuovoMenu, $etichetta);
                        }
                    }
                }
            }
        }
    }
  
    /**
     * {@inheritDoc}
     * @see PaginaHTML::aggiungi()
     */
    private function creaMenu(){
        if(!is_null($this->barraMenu)){
            foreach ($this->vociMenu as $voce) {
                $this->barraMenu->aggiungi($voce);
            }
            parent::aggiungi($this->barraMenu->vedi());// disegna corpo
            foreach ($this->barraMenu->regoleCSS() as $regolaCSS) {
                parent::aggiungi($regolaCSS);//aggiungi regola al tag style del head della pagina HTML
            }
            
            
        }
    }
    /**
     * Aggiungi un argomento alla multi-pagina.
     * 
     * @param Argomento $argomento
     */
    public function aggiungiArgomento($argomento){
        if($argomento instanceof Argomento){
            $this->argomenti[$argomento->nome()] = $argomento;
        }
    }
    
    private function creaPannelloLaterale(){
        $this->indiceLateraleSx = new Pannello(self::LUNGHEZZA_PANNELLO_SX, '400px', '#999', '#000');
        $this->indiceLateraleSx->posiziona(Posizione::ASSOLUTA,'0','50px');
    }
    
    
    private function creaTitoloPagina(){
        $this->titoloArgomento = new Pannello(self::LUNGHEZZA_PAGINA, 'auto', '#fff', '#000');
        $this->titoloArgomento->posiziona(Posizione::STATICA);
        $this->titoloArgomento->aggiungi($this->argomento.' ');
        $this->titoloArgomento->aggiungi(
            new Stile(
                [
                    new DichiarazioneCSS('font-family',"'Akronim', cursive"),
                    new DichiarazioneCSS('font-size',"50px")
                ]
            )
        );
        
        $this->titoloPagina = new Pannello(self::LUNGHEZZA_PAGINA, 'auto', '#fff', '#000');
        $this->titoloPagina->posiziona(Posizione::STATICA);
        $argomento = $this->argomenti[$this->argomento];
        if($argomento instanceof Argomento){
            $this->titoloPagina->aggiungi('<b>'.$argomento->nomePagina($this->indice).'</b>');
            $this->titoloPagina->aggiungi(
                new Stile(
                    [
                        new DichiarazioneCSS('font-family',"'Amita', cursive"),
                        new DichiarazioneCSS('color','red'),
                        new DichiarazioneCSS('font-size',"30px")
                    ]
                )
            );
        }
        $this->titoloPagina->aggiungi(' ');
    }
    
    private function creaIndiceDiPagina(){
        $this->indiceDiPagina = new Pannello(self::LUNGHEZZA_PAGINA, 'auto', '#fff', '#000');
        $this->indiceDiPagina->posiziona(Posizione::STATICA);
        $frecciaSx = new Tag(
            'a',
            [
                new Attributo('href', self::link($this->argomento, $this->indice > 0 ? $this->indice - 1 : '0'))
            ],
            new Tag(
                'img',
                [
                    new Attributo('src', 'LibreriaQx7-php/freccia_sinistra.png'),
                    new Attributo('height', '40px'),new Attributo('width', '40px')
                ]
            )
        );
        $frecciaDx = new Tag(
            'a',
            [
                new Attributo('href', self::link($this->argomento, $this->indice < $this->limiteIndicePagina() -1 ?  $this->indice + 1 : $this->indice ))
            ],
        
            new Tag(
                'img',
                [
                    new Attributo('src', 'LibreriaQx7-php/freccia_destra.png'),
                    new Attributo('height', '40px'),new Attributo('width', '40px')
                ]
            )
        );
        
        $this->indiceDiPagina->aggiungi($frecciaSx .'pag. '. $this->indice. $frecciaDx);
        
        $this->indiceDiPagina->aggiungi(
            new Stile(
                [
                    new DichiarazioneCSS('font-family',"'Akronim', cursive"),
                    new DichiarazioneCSS('font-size',"35px")
                ]
            )
        );
    }
    
    private function creaPaginaDiTesto(){
        $this->paginaTesto = new Pannello(self::LUNGHEZZA_PAGINA, 'auto', '#fff', 'black');
        $this->paginaTesto->posiziona(Posizione::ASSOLUTA,'280px','60px');
        $this->paginaTesto->aggiungi(' ');
        $this->paginaTesto->aggiungi(
            new Stile(
                [
                    new DichiarazioneCSS('font-family',"'Amita', corsive"),
                    new DichiarazioneCSS('font-size',"18px")
                ]
            )
        );
    }
    
    /**
     * Inizializza lo stile predefinito della pagina.
     */
    private function cssBody(){
        parent::importaFont('Amita', self::FONT_TESTO_R);
        parent::importaFont('Akronim', self::FONT_INTESTAZIONE_R);
        
        /*
         body{
             margin: 0;
             padding: 0;
             font-size: 15px;
             font-family: "Lucida Grande", "Helvetica Nueue", Arial, sans-serif;
         }
         */
        $body = new RegolaCSS(
            'body',
            [
                new DichiarazioneCSS('margin','0'),
                new DichiarazioneCSS('padding','0'),
                new DichiarazioneCSS('font-size','15px'),
                new DichiarazioneCSS('font-family', '"Lucida Grande", "Helvetica Nueue", Arial, sans-serif')
            ]
            );
        $this->aggiungi($body);
    }
    
    private function cssElencoPagine(){
        /*
          #ElencoPagine a:link, #ElencoPagine a:visited {
              background-color: #f44336;
              color: white;
              padding: 14px 25px;
              text-align: center;
              text-decoration: none;
              display: inline-block;
         }

         */
        $voceVisibile = new RegolaCSS(
            '#'.self::ELENCO.' a:link, '.'#'.self::ELENCO.' a:visited',
            [
                new DichiarazioneCSS('background-color','#f44336'),
                new DichiarazioneCSS('color','white'),
                new DichiarazioneCSS('padding','5px 10px'),
                //new DichiarazioneCSS('text-align', 'center'),
                new DichiarazioneCSS('text-decoration', 'none'),
                new DichiarazioneCSS('display', 'block')
            ]
            );
        $this->aggiungi($voceVisibile);
        /*
         #ElencoPagine a:hover, #ElencoPagine a:active {
              background-color: red;
         }
        */
        $voceSeleziona = new RegolaCSS(
            '#'.self::ELENCO.' a:hover, '.'#'.self::ELENCO.' a:active',
            [new DichiarazioneCSS('background-color','red')]
            );
        $this->aggiungi($voceSeleziona);
        
        /*
         #ElencoPagine li{
            margin-left:-40px;
            margin-top:-5px;
         }
         */
        $voce = new RegolaCSS(
            '#'.self::ELENCO.' li',
            [
                new DichiarazioneCSS('margin-left','-40px'),
                //new DichiarazioneCSS('margin-top','-10px')
            ]
        );
        $this->aggiungi($voce);
    }
    
    /**
     * Numero messimo di pagine relative all'argomento corrente.
     * 
     * @return number
     */
    private function limiteIndicePagina(){
        $argomento = $this->argomenti[$this->argomento];
        if($argomento instanceof Argomento){
            return $argomento->numeroPagine();
        }
        return -1;
    }
    
    private function creaListaPagine(){
        $listaPagine = new Tag('ul',[new Attributo('id', self::ELENCO)]);
        $listaPagine->aggiungi(
            new Stile(
                [
                    new DichiarazioneCSS('font-family',"'Amita', corsive"),
                    new DichiarazioneCSS('font-size',"12px")
                ]
            )
        );
        $argomento = $this->argomenti[$this->argomento];
        if($argomento instanceof Argomento){
            for ($indice=0; $indice < $argomento->numeroPagine(); $indice++){
                $listaPagine->aggiungi(
                    new Tag(
                        'li',
                        [new Stile('list-style-type','none')],
                        new Tag(
                            'a',
                            [new Attributo('href', self::link($argomento->nome(), $indice))],
                            $argomento->nomePagina($indice)
                        )
                    )
                );
            }
        }
        
        
        $this->indiceLateraleSx->aggiungi($listaPagine);
    }
    
    /**
     * {@inheritDoc}
     * @see PaginaHTML::__toString()
     */
    public function __toString(){
        self::creaPannelloLaterale();
        self::creaIndiceDiPagina();
        self::creaPaginaDiTesto();
        self::creaTitoloPagina();
        self::creaMenu();
        $argomento = $this->argomenti[$this->argomento];
        $this->paginaTesto->aggiungi($this->titoloArgomento);
        $this->paginaTesto->aggiungi($this->indiceDiPagina);
        $this->paginaTesto->aggiungi($this->titoloPagina);
        
        if($argomento instanceof Argomento){
            $testo = $argomento->pagina($this->indice);
            $this->paginaTesto->aggiungi($testo);
        }
        if(strlen($testo) > 100){
            $this->paginaTesto->aggiungi($this->indiceDiPagina);
        }
        
        parent::aggiungi($this->paginaTesto);
        
        //parent::aggiungi($this->intestazione);
        self::creaListaPagine();
        parent::aggiungi($this->indiceLateraleSx);
        
        self::cssBody();
        self::cssElencoPagine();
        return parent::__toString();
    }
    
}

?>