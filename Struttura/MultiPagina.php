<?php
include_once 'LibreriaQx7-php/PaginaHTML.php';
include_once 'LibreriaQx7-php/Pannello.php';
include_once 'LibreriaQx7-php/BarraMenu.php';
include_once 'LibreriaQx7-php/Menu.php';
include_once 'Argomento.php';

class MultiPagina extends PaginaHTML {
    const ID_ELENCO = 'ElencoPagine'; // ID
    const CHIAVE_PAGINA = 'pagina';
    const CHIAVE_ARGOMENTO = 'argomento';
    const HOME = 'home';
    
    const LUNGHEZZA_PAGINA  = '700px';
    const LUNGHEZZA_PANNELLO_SX  = '270px';
    const FONT_TESTO_R      = 'Struttura/Amita-Regular.ttf';
    
    const FONT_TITOLO_PAGINA_R = 'Struttura/ProstoOne-Regular.ttf';
    const FONT_MENU_R = 'Struttura/Anton-Regular.ttf';
    
    const NOME_FONT_INTESATAZIONE = 'intestazione';
   
    protected $fontIntestazione = 'Struttura/FrederickatheGreat-Regular.ttf';
    protected $barraMenu;
    protected $vociMenu = array();
    
    protected $indice;
    protected $argomento;

    protected $argomenti = array();
    
    protected $indiceDiPagina;
    protected $titoloArgomento;
    protected $titoloPagina;
    
    
    protected $paginaTesto;
    protected $indiceLateraleSx;
    protected $suggerimentoLateraleDx;
    
    protected $coloreSelezionaIndicePagina;
   
    
    
    public function __construct($titolo){
        parent::__construct($titolo);
        
        if(filter_has_var(INPUT_GET, self::CHIAVE_PAGINA)){
            $this->indice = filter_input(INPUT_GET, self::CHIAVE_PAGINA, FILTER_SANITIZE_NUMBER_INT);
            if ($this->indice === false) {
                $this->indice = 0;
            }
        }else{
            $this->indice = 0;
        }
        if(filter_has_var(INPUT_GET, self::CHIAVE_ARGOMENTO)){
            $this->argomento = filter_input(INPUT_GET, self::CHIAVE_ARGOMENTO, FILTER_SANITIZE_STRING);
            if ($this->argomento === false) {
                $this->argomento = self::HOME;
            }
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
     * @param string        $etichetta
     * @param Argomento     $argomento
     * @param string        $menu       etichetta della voce menu (padre) a cui è aggangiata
     */    
     public function aggiungiMenu($etichetta,$argomento=null,$menu=null){
         if(!is_null($this->barraMenu)){
            $nuovoMenu = new Menu(
                $etichetta,
                self::link($argomento != null ? $argomento->nome() : null, 0)
            );
            if(is_null($menu)){//aggiungi alla barra menu
                $this->vociMenu[$etichetta] = $nuovoMenu;
            }else{//aggiungi al menu
                if(isset($this->vociMenu[$menu])){
                    if($this->vociMenu[$menu] instanceof Menu){
                        $this->vociMenu[$menu]->aggiungi($nuovoMenu);
                    }else{
                       self::aggiungiSottomenu($this->vociMenu, $nuovoMenu, $menu); 
                    }
                }
            }
        }
    }
    
    /**
     * Collegamento ipertestuale ad un altra pagina di un argomento.
     * 
     * @param   string  $argomento
     * @param   int     $pagina
     * @return string
     */
    private function link($argomento,$pagina){
        if(!is_null($argomento)){
            return '?pagina='.$pagina.'&argomento='.$argomento;
        }
        return '';
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
    

  
    public function creaPannelloLaterale($coloreSfondo, $coloreTesto, $coloreSeleziona){
        $this->indiceLateraleSx = new Pannello(self::LUNGHEZZA_PANNELLO_SX,'auto', $coloreSfondo, $coloreTesto);
        $this->coloreSelezionaIndicePagina = $coloreSeleziona;
        $this->indiceLateraleSx->posiziona(Posizione::ASSOLUTA,'0','50px');
    }
    
 
    
    
    private function creaTitoloPagina(){
        $this->titoloArgomento = new Pannello(self::LUNGHEZZA_PAGINA, 'auto', '#fff', '#000');
        $this->titoloArgomento->posiziona(Posizione::STATICA);
        $this->titoloArgomento->aggiungi($this->argomento.' ');
        $this->titoloArgomento->aggiungi(
            new Stile(
                [
                    new DichiarazioneCSS('font-family',"'".self::NOME_FONT_INTESATAZIONE."', cursive"),
                    new DichiarazioneCSS('font-size',"50px")
                ]
            )
        );
        
        $this->titoloPagina = new Pannello(self::LUNGHEZZA_PAGINA, 'auto', '#fff', '#000');
        $this->titoloPagina->posiziona(Posizione::STATICA);
        if(isset($this->argomenti[$this->argomento])){
        $argomento = $this->argomenti[$this->argomento];
            if($argomento instanceof Argomento){
                $this->titoloPagina->aggiungi($argomento->nomePagina($this->indice));
                $this->titoloPagina->aggiungi(
                    new Stile(
                        [
                            new DichiarazioneCSS('font-family',"'Prosto One', cursive"),
                            new DichiarazioneCSS('color','red'),
                            new DichiarazioneCSS('font-size',"30px")
                        ]
                    )
                );
            }
        }
        $this->titoloPagina->aggiungi(' ');
    }
    
    private function creaIndiceDiPagina(){
        $maxPagina = $this->limiteIndicePagina();
        if($maxPagina > 0){
            $this->indiceDiPagina = new Pannello(self::LUNGHEZZA_PAGINA, 'auto', '#fff', '#000');
            $this->indiceDiPagina->posiziona(Posizione::STATICA);
            $frecciaSx = new Tag(
                'a',
                [new Attributo('href', self::link($this->argomento, $this->indice > 0 ? $this->indice - 1 : '0'))],
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
                    new Attributo('href', self::link($this->argomento, $this->indice < $maxPagina -1 ?  $this->indice + 1 : $this->indice ))
                ],
            
                new Tag(
                    'img',
                    [
                        new Attributo('src', 'LibreriaQx7-php/freccia_destra.png'),
                        new Attributo('height', '40px'),new Attributo('width', '40px')
                    ]
                )
            );
            
            $this->indiceDiPagina->aggiungi($frecciaSx .'pag. '. ($this->indice+1). $frecciaDx);
            http://locahttp://localhost/index.php?pagina=0&argomento=Argomento%20Testlhost/index.php?pagina=1&argomento=Argomento%20Test
            $this->indiceDiPagina->aggiungi(
                new Stile(
                    [
                        new DichiarazioneCSS('font-family',"'Prosto One', cursive"),
                        new DichiarazioneCSS('font-size',"28px")
                    ]
                )
            );
        }
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
     * Carica il file del font per l'intestazione degli argomenti.
     * @param string $font
     */
    public function formatoCarattereDiIntestazione($font) {
        if(is_string($font)){
            $this->fontIntestazione = $font;
        }
    } 
    
    /**
     * Inizializza lo stile predefinito della pagina.
     */
    private function cssBody(){
        parent::importaFont('Amita', self::FONT_TESTO_R);
        if(!is_null($this->fontIntestazione)){
            parent::importaFont('intestazione', $this->fontIntestazione);
        }
        parent::importaFont('Prosto One', self::FONT_TITOLO_PAGINA_R);
        parent::importaFont('Anton', self::FONT_MENU_R);
        
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
                new DichiarazioneCSS('font-size','16px'),
                new DichiarazioneCSS('font-family', "'Anton', sans-serif")
            ]
            );
        $this->aggiungi($body);
        
        
    }
    
    private function cssElencoPagine(){
        if($this->indiceLateraleSx instanceof Pannello) {
        
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
                '#'.self::ID_ELENCO.' a:link, '.'#'.self::ID_ELENCO.' a:visited',
                [
                    new DichiarazioneCSS('background-color',$this->indiceLateraleSx->coloreSfondo()),
                    new DichiarazioneCSS('color',$this->indiceLateraleSx->coloreTesto()),
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
            if(!is_null($this->coloreSelezionaIndicePagina)){
                $voceSeleziona = new RegolaCSS(
                    '#'.self::ID_ELENCO.' a:hover, '.'#'.self::ID_ELENCO.' a:active',
                    [new DichiarazioneCSS('background-color',$this->coloreSelezionaIndicePagina)]
                    );
                $this->aggiungi($voceSeleziona);
            }
            
            /*
             #ElencoPagine li{
                margin-left:-40px;
                margin-top:-5px;
             }
             */
            $voce = new RegolaCSS(
                '#'.self::ID_ELENCO.' li',
                [
                    new DichiarazioneCSS('margin-left','-40px')
                ]
            );
            $this->aggiungi($voce);
        }
    }
    
    /**
     * Numero messimo di pagine relative all'argomento corrente.
     * 
     * @return number
     */
    private function limiteIndicePagina(){
        if(isset($this->argomenti[$this->argomento])){
            $argomento = $this->argomenti[$this->argomento];
            if($argomento instanceof Argomento){
                return $argomento->numeroPagine();
            }
        }
        return -1;
    }
    
    private function creaListaPagine(){
        $listaPagine = new Tag('ul',[new Attributo('id', self::ID_ELENCO)]);
        $listaPagine->aggiungi(
            new Stile(
                [
                    new DichiarazioneCSS('font-family',"'Prosto One', corsive"),
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
        
        self::creaIndiceDiPagina();
        self::creaPaginaDiTesto();
        self::creaTitoloPagina();
        self::creaMenu();
        if(isset($this->argomenti[$this->argomento])){
            $argomento = $this->argomenti[$this->argomento];
        
            $this->paginaTesto->aggiungi($this->titoloArgomento);
            $this->paginaTesto->aggiungi($this->indiceDiPagina);
            $this->paginaTesto->aggiungi($this->titoloPagina);
            
            $testo = '';
            if($argomento instanceof Argomento){
                $testo = $argomento->pagina($this->indice);
                $this->paginaTesto->aggiungi($testo);
            }
            if(strlen($testo) > 100){
                $this->paginaTesto->aggiungi($this->indiceDiPagina);
            }
            
            parent::aggiungi($this->paginaTesto);
            
            if(!is_null($this->indiceLateraleSx)){
                self::creaListaPagine();
                parent::aggiungi($this->indiceLateraleSx);
            }
        }
        
        self::cssBody();
        self::cssElencoPagine();
        return parent::__toString();
    }
    
}

?>