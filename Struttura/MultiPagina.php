<?php
include_once 'LibreriaQx7-php/PaginaHTML.php';
include_once 'LibreriaQx7-php/html5.php';
include_once 'LibreriaQx7-php/BarraMenu.php';
include_once 'LibreriaQx7-php/Menu.php';
include_once 'LibreriaQx7-php/BaseDatiMySQL.php';
include_once 'LibreriaQx7-php/javascript.php';
include_once 'Argomento.php';

class MultiPagina extends PaginaHTML {
    const ID_ELENCO = 'ElencoPagine'; // ID indici
    
    const CHIAVE_PAGINA = 'pagina';
    const CHIAVE_ARGOMENTO = 'argomento';
    const HOME = 'home';
    
    const ALTEZZA_INTESTAZIONE_SITO  = 'auto';
    const ALTEZZA_MENU  = '50px';
    
    const LUNGHEZZA_PANNELLO_SX  = '22%';
    const LUNGHEZZA_AREA_PRINCIPALE  = '78%';
    
    const LUNGHEZZA_AREA_ARGOMENTO = '70%';
    const LUNGHEZZA_PANNELLO_DX  = '29%';
    
    const FONT_TESTO_STANDARD      = 'Struttura/Inter-Regular.ttf';
    const FONT_TESTO_SPECIALE      = 'Struttura/Niconne-Regular.ttf';
    const FONT_TESTO_SPACEMONO     = 'Struttura/SpaceMono-Regular.ttf';
    const FONT_TITOLO_PAGINA_SPECIALE = 'FrederickatheGreat-Regular.ttf';
    
    const FONT_TITOLO_PAGINA_STANDARD = 'Struttura/ProstoOne-Regular.ttf';
    const FONT_MENU_STANDARD = 'Struttura/Anton-Regular.ttf';
    
    const NOME_FONT_INTESATAZIONE = 'intestazione';
    
    

   
    protected $fontIntestazione = 'Struttura/FrederickatheGreat-Regular.ttf';
    
    protected $barraMenu = null;
    protected $vociMenu = array();
    
    protected $indice = 0;
    protected $argomento = self::HOME;

    protected $argomenti = array(); 
    protected $note = array();
    
    protected $indiceDiPagina;
    protected $titoloArgomento;
    protected $titoloPagina;
    
    protected $intestazioneSito = null;
    
    protected $paginaTesto;
    protected $indiceLateraleSx = null;
    protected $noteLateraleDx = null;
    
    protected $coloreSelezionaIndicePagina;
    protected $coloreIndicePagina;
    protected $coloreMenu;
   
    protected $dati = array();
    

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
    
    /**
     * Distruttore
     */
    function __destruct() {
        if($this->baseDati instanceof BaseDatiMySQL) {
             $this->baseDati->chiudi();
        }
    }
    
    

    
    /**
     * Associa i dati di un'interrogazione SQL del DB MySQL alle pagine di un argomento.
     * @param string $SQL
     * @param string $argomento
     * @return mixed
     */
    public function associaDatiPagina(string $SQL, string $schemaMySQL, string $utente, string $password,  Argomento& $argomento,int $pagina){
        $baseDati = new BaseDatiMySQL();
            $codiceDati = $baseDati->datiJavaScript($SQL,$schemaMySQL,$utente,$password);
            if(isset($codiceDati))
                $this->dati[$argomento->nome().$pagina.''] = $codiceDati;
        
    }
    
    /**
     * Crea una barra menu orizzontale posizionata dopo l'intestazione di pagina.
     * 
     * @param string $coloreSfondo
     * @param string $coloreTesto
     * @param string $coloreSeleziona
     * @param string $posizone          se Posizione::FISSA non varia con lo scorrimento della pagina.
     */
    public function creaBarraMenu(string $coloreSfondo, string $coloreTesto, string $coloreSeleziona){
        $this->barraMenu = new BarraMenu($coloreSfondo, $coloreTesto, $coloreSeleziona,Posizione::RELATIVA);
        $this->coloreMenu = $coloreSfondo;
    }
    
    /**
     * Inizializza i colori delle voci del primo livello della barraMenu.
     * 
     * @param string $coloreSfondo
     * @param string $coloreTesto
     */
    public function inizializzaPrimoLivelloMenu(string $coloreSfondo, string $coloreTesto){
        if(!is_null($this->barraMenu)){
            $this->barraMenu->menuPrimoLivello($coloreSfondo, $coloreTesto);
        }
    }
    /**
     * Inizializza i colori delle voci del secondo livello della barraMenu.
     * 
     * @param string $coloreSfondo
     * @param string $coloreTesto
     */
    public function inizializzaSecondoLivelloMenu(string $coloreSfondo, string $coloreTesto){
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
    public function aggiungiMenu(string $etichetta,Argomento $argomento=null,string $menu=null){
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
                    }
                }else{
                    self::aggiungiSottomenu($this->vociMenu, $nuovoMenu, $menu);
                }
            }
        }
    }
    
    /**
     * Collegamento ipertestuale ad un altra pagina di un argomento.
     * In caso di $argomento nullo esso punta alla pagina 'home' (se esiste),
     * 
     * @param   string|Argomento  $argomento
     * @param   int     $pagina
     * @return string
     */
    private function link($argomento,$pagina){
        if(!is_null($argomento)){
            return '?pagina='.$pagina.'&argomento='.rawurlencode($argomento);
        }
        return '?pagina=0&argomento='.self::HOME;
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
     * Carica font dei caratteri e specifica il comportamento dell'intestazione
     * del sito (CSS).
     */
    private function creaIntestazioneSito(){
        if(!is_null($this->fontIntestazione)){
            parent::importaFont('intestazione', $this->fontIntestazione);
        }
        $this->intestazioneSito = new IntestazionePagina(self::ALTEZZA_INTESTAZIONE_SITO,'black','white');
        $this->intestazioneSito->aggiungi($this->titolo);
        $this->intestazioneSito->aggiungi(
            new Stile(
                [
                    new DichiarazioneCSS('font-family',"'".self::NOME_FONT_INTESATAZIONE."', cursive"),
                    new DichiarazioneCSS('font-size',"50px"),
                    new DichiarazioneCSS('font-size',"4.5vw")//adatta alle dimensioni
                ]
                )
            );
        parent::aggiungi($this->intestazioneSito->vedi());
        
    }
  
    /**
     * Se è stato definito un menu viene aggiunto alla codice html.
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
    public function aggiungiArgomento(Argomento $argomento){
        $this->argomenti[$argomento->nome()] = $argomento;
    }
    
    /**
     * Permette di specificare l'indirizzo dove trovare il file contenente
     * il codice "body" da inseire nella pagina iniziale.
     * 
     * @param Pagina $file
     */
    public function aggiungiHome(Pagina $file){
        $this->argomenti[self::HOME] = $file;
    }
    
    /**
     * Aggiungi annotazioni permanenti alla pagina sul margine destro.
     * 
     * @param Pannello $note
     */    
    public function aggiungiNoteMarginePagina(Pannello $note){
        $this->note[] = $note;
    }

    /**
     * Imposta un eventuale pannello laterale
     * 
     * @param string $coloreSfondo
     * @param string $coloreTesto
     * @param string $coloreSeleziona
     */
    public function creaPannelloLaterale($coloreSfondo, $coloreTesto, $coloreSeleziona){
        $this->indiceLateraleSx = new NotePagina(self::LUNGHEZZA_PANNELLO_SX,'auto', $this->coloreMenu != null ? $this->coloreMenu : $coloreSfondo, $coloreTesto);
        $this->coloreSelezionaIndicePagina = $coloreSeleziona;
        $this->coloreIndicePagina = $coloreSfondo;
        $this->indiceLateraleSx->allineamentoVerticale(Lato::ALTO);
        
    }
    
 
    private function inizializzaTitoloArgomentoMultipagina(){
        // Crea titolo dell'argomento della multipagina
        if(isset($this->argomento))
            if($this->argomento != self::HOME){
            $this->titoloArgomento = new Pannello('100%', 'auto', '#fff', '#000');
            
            $this->titoloArgomento->aggiungi($this->argomento.' ');
            $this->titoloArgomento->aggiungi(
                new Stile(
                    [
                        new DichiarazioneCSS('font-family',"'".self::NOME_FONT_INTESATAZIONE."', cursive"),
                        new DichiarazioneCSS('font-size',"50px"),
                        new DichiarazioneCSS('text-align', 'center')
                    ]
                    )
                );
        }
    }
    
    private function inizializzaTitoloPagina(Argomento $argomento){
        
        $this->titoloPagina = new Pannello('100%', 'auto', '#fff', '#000');
        
        $pagina = $argomento->nomePagina($this->indice);
        self::titolo($pagina);
       $this->titoloPagina->aggiungi($pagina);
       $this->titoloPagina->aggiungi(
            new Stile(
                [
                    new DichiarazioneCSS('font-family',"'Prosto One', cursive"),
                    new DichiarazioneCSS('color','red'),
                    new DichiarazioneCSS('font-size',"30px"),
                    new DichiarazioneCSS('text-align', 'center')
                ]
            )
        );
        $this->titoloPagina->aggiungi(' ');
    }
    
    private function inizializzaIndiceDiPagina(){
        $maxPagina = $this->limiteIndicePagina();
        if($maxPagina > 1){
            $this->indiceDiPagina = new Pannello('100%', 'auto', '#fff', '#000');
            
            $frecciaSx = new Tag(
                'a',
                [new Attributo('href', self::link($this->argomento, $this->indice > 0 ? $this->indice - 1 : '0'))],
                new Tag(
                    'img',
                    [
                        new Attributo('src', $this->indice > 0 ? 'LibreriaQx7-php/freccia_sinistra.png' : 'LibreriaQx7-php/stop.png'),
                        new Attributo('align','center'),
                        new Attributo('height', '45px'),
                        new Attributo('width', '45px')
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
                        new Attributo('src', $this->indice < $maxPagina -1 ? 'LibreriaQx7-php/freccia_destra.png' : 'LibreriaQx7-php/stop.png'),
                        new Attributo('align','center'),
                        new Attributo('height', '45px'),
                        new Attributo('width', '45px')
                    ]
                )
            );
            
            $this->indiceDiPagina->aggiungi($frecciaSx .'pag. '. ($this->indice+1). $frecciaDx);
            http://locahttp://localhost/index.php?pagina=0&argomento=Argomento%20Testlhost/index.php?pagina=1&argomento=Argomento%20Test
            $this->indiceDiPagina->aggiungi(
                new Stile(
                    [
                        new DichiarazioneCSS('font-family',"'Prosto One', cursive"),
                        new DichiarazioneCSS('font-size',"28px"),
                        new DichiarazioneCSS('text-align', 'center')
                    ]
                )
            );
        }
    }
    
    private function inizializzaPaginaDiTesto(){
        $this->paginaTesto = new TestoPagina(null, 'auto', '#fff', 'black');
        
        $this->paginaTesto->allineamentoVerticale(Lato::ALTO);
        $this->paginaTesto->aggiungi(' ');
        $this->paginaTesto->aggiungi(
            new Stile(
                [
                    new DichiarazioneCSS('font-family',"'Inter', sans-serif"),
                    new DichiarazioneCSS('font-size',"16px")
                ]
            )
        );
    }
    
    /**
     * Inizializza e crea il pannello laterale destro con i sugerimenti e le note aggiunte.
     */
    private function creaNoteMargineDx(){
        $this->noteLateraleDx = new NotePagina(self::LUNGHEZZA_PANNELLO_DX, 'auto');
        $this->noteLateraleDx->comportamento(Comportamento::BLOCCO_LINEA);
        $this->noteLateraleDx->allineamentoVerticale(Lato::ALTO);
        foreach ($this->note as $pannello) {
            $this->noteLateraleDx->aggiungi($pannello);
        }
        $this->noteLateraleDx->aggiungi(' ');
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
        parent::importaFont('Inter', self::FONT_TESTO_STANDARD);
        parent::importaFont('Prosto One', self::FONT_TITOLO_PAGINA_STANDARD);
        parent::importaFont('Anton', self::FONT_MENU_STANDARD);
        parent::importaFont('Niconne', self::FONT_TESTO_SPECIALE);
        parent::importaFont('Space Mono', self::FONT_TESTO_SPACEMONO);
        
        $body = new RegolaCSS(
            'body', 
            [
                new DichiarazioneCSS('margin','0'),
                new DichiarazioneCSS('padding','0'),
                // N.B.:si applica soprattuto alla 'barra menu'
                new DichiarazioneCSS('font-size','16px'),
                new DichiarazioneCSS('font-family', "'Anton', sans-serif"),
                new DichiarazioneCSS('word-wrap', 'break-word')//forza il ritorno a capo
            ]
            );
        $this->aggiungi($body);
        
        $aside = new RegolaCSS(
            'aside',
            [
                new DichiarazioneCSS('font-family', "'Prosto One', sans-serif")
            ]
            );
        $this->aggiungi($aside);
        self::classeTesto();//casse speciale dei tag di tipo div
    }
    
    private function classeTesto(){
        $classe = new RegolaCSS(
            '.testo',
            [
                new DichiarazioneCSS('margin','2px'),
                new DichiarazioneCSS('padding','2px'),
                new DichiarazioneCSS('text-align','justify'),
                new DichiarazioneCSS('font-size','26px'),
                new DichiarazioneCSS('font-family', "'Niconne', sans-serif")
            ]
            );
        $this->aggiungi($classe);
        
        $codice = new RegolaCSS(
            'code',
            [
                new DichiarazioneCSS('background','#eee'),
                new DichiarazioneCSS('color', '#000'),
                new DichiarazioneCSS('font-size','14px'),
                new DichiarazioneCSS('font-family', "'Space Mono', sans-serif")
            ]
            );
        $this->aggiungi($codice);
        $pre = new RegolaCSS(
            'pre',
            [
                new DichiarazioneCSS('background','#000'),
                new DichiarazioneCSS('color', '#fff'),
                new DichiarazioneCSS('font-size','14px'),
                new DichiarazioneCSS('font-family', "'Space Mono', sans-serif"),
                new DichiarazioneCSS('overflow','auto')
            ]
            );
        $this->aggiungi($pre);
        $pre_b = new RegolaCSS(
            'pre b',
            [
                new DichiarazioneCSS('color', 'yellow')
            ]
            );
        $this->aggiungi($pre_b);
        $grossetto = new RegolaCSS(
            '.testo b',
            [
                new DichiarazioneCSS('font-size','14px'),
                new DichiarazioneCSS('font-family', "'Prosto One', sans-serif")
            ]
            );
        $this->aggiungi($grossetto);
        $link_interno = new RegolaCSS(
            '.testo a.linkInterno:link,.testo a.linkInterno:visited',
            [
                new DichiarazioneCSS('color', '#5499C7'),
                new DichiarazioneCSS('text-decoration', 'none')
            ]
            );
        $this->aggiungi($link_interno);
        $link_prima = new RegolaCSS(
            '.testo a:link,.testo a:visited',
            [
                new DichiarazioneCSS('color', 'green'),
                new DichiarazioneCSS('text-decoration', 'none')
            ]
            );
        $this->aggiungi($link_prima);
        
        $link_focus = new RegolaCSS(
            '.testo a:hover,.testo a:active,.testo a.linkInterno:hover,.testo a.linkInterno:active',
            [
                new DichiarazioneCSS('color', 'orange'),
                new DichiarazioneCSS('text-decoration', 'underline')
            ]
            );
        $this->aggiungi($link_focus);
        
        $titolo = new RegolaCSS(
            '.testo h1,.testo h2,.testo h3',
            [
                new DichiarazioneCSS('font-weight','normal'),
                new DichiarazioneCSS('text-align','center'),
                new DichiarazioneCSS('color', '#FFF'),
                new DichiarazioneCSS('background','#111'),
                new DichiarazioneCSS('font-family', "'intestazione', script")
            ]
            );
        $this->aggiungi($titolo);
        
        $tabella = new RegolaCSS(
            '.testo table',
            [
                new DichiarazioneCSS('width','100%'),
                new DichiarazioneCSS('font-size', "16px"),
                new DichiarazioneCSS('font-family', "'Inter', sans-serif")
            ]
            );
        $this->aggiungi($tabella);
        
        $riga = new RegolaCSS(
            '.testo table tr:hover td',
            [
                new DichiarazioneCSS('background-color','#ffff99')
            ]
            );
        $this->aggiungi($riga);
        
        $riga_b = new RegolaCSS(
            '.testo table tr:hover td b',
            [
                new DichiarazioneCSS('color','red')
            ]
            );
        $this->aggiungi($riga_b);
        
        $immagine = new RegolaCSS(
            '.testo img',
            [
                new DichiarazioneCSS('float','left'),
                new DichiarazioneCSS('margin','15px 15px 15px 15px'),
                new DichiarazioneCSS('max-width','60%'),
                new DichiarazioneCSS('min-width','30%')
            ]
            );
        $this->aggiungi($immagine);
        
        $immagine_mouse = new RegolaCSS(
            '.testo img:hover',
            [
                new DichiarazioneCSS('transform','scale(0.9)'),
                new DichiarazioneCSS('border','0px solid gray;')
            ]
            );
        $this->aggiungi($immagine_mouse);
        $immagine_estesa = new RegolaCSS(
            '.testo img.estesa',
            [
                new DichiarazioneCSS('max-width','100%'),
                new DichiarazioneCSS('float','none')
            ]
            );
        $this->aggiungi($immagine_estesa);
        
        $immagine_estesa_mouse = new RegolaCSS(
            '.testo img.estesa:hover',
            [
                new DichiarazioneCSS('transform','scale(1.1)'),
                new DichiarazioneCSS('border','1px solid gray;')
            ]
            );
        $this->aggiungi($immagine_estesa_mouse);
    }
    
    private function cssFormattazionePagina(){
        $primaColonna = new RegolaCSS(
            'aside:first-child',
            [
                new DichiarazioneCSS('display', 'inline-block')
            ]
            );
        //$this->aggiungi($primaColonna);
        
        $colonnaTesto = new RegolaCSS(
            'article:first-child',
            [
                new DichiarazioneCSS('display', 'inline-block'),
                new DichiarazioneCSS('width', self::LUNGHEZZA_AREA_ARGOMENTO)
            ]
            );
        //$this->aggiungi($colonnaTesto);
        
        $areaPrincipale =  new RegolaCSS(
            'section',
            [
                new DichiarazioneCSS('display', 'inline-block'),
                new DichiarazioneCSS('width', self::LUNGHEZZA_AREA_PRINCIPALE)
            ]
            );
        //$this->aggiungi($areaPrincipale);
        
        $scomparsaPrimaColonna = new RegolaCSS(//elimina la prima colonna
            'aside:first-child',
            [
                new DichiarazioneCSS('display', 'none')
            ]
            );
        $espandiColonnaTesto = new RegolaCSS(//elimina la prima colonna
            'article:first-child',
            [
                new DichiarazioneCSS('display', 'block'),
                new DichiarazioneCSS('width', '100%')
            ]
            );
        $espandiAreaPrincipale = new RegolaCSS(
            'section',
            [
                new DichiarazioneCSS('display', 'block'),
                new DichiarazioneCSS('width', '100%')
            ]
            );
        
        $this->cssMiniDesktop([$primaColonna,$colonnaTesto,$areaPrincipale]);
        $this->cssTablet([$colonnaTesto,$areaPrincipale,$scomparsaPrimaColonna]);
        $this->cssTabletOrizzontale([$colonnaTesto,$areaPrincipale,$scomparsaPrimaColonna]);
        $this->cssTabletVerticale([$areaPrincipale,$scomparsaPrimaColonna,$espandiAreaPrincipale]);
        $this->cssCellulareOrizzontale([$areaPrincipale,$scomparsaPrimaColonna,$espandiAreaPrincipale]);
        $this->cssCellulareVerticale([$scomparsaPrimaColonna,$espandiColonnaTesto,$espandiAreaPrincipale]);

    }
    
    private function cssElencoPagine(){
        if($this->indiceLateraleSx instanceof Pannello) {
            /*
             #ElencoPagine{
             'width' : ...;
             }*/
             $elenco = new RegolaCSS(
                '#'.self::ID_ELENCO,
                [
                    new DichiarazioneCSS('width', '200px'),
                    new DichiarazioneCSS('overflow-y','auto'),
                    new DichiarazioneCSS('max-height', '80%'),
                    new DichiarazioneCSS('min-height', '0'),
                    new DichiarazioneCSS('box-shadow', '2px 2px 2px 3px rgba(0,0,0,0.25)')
                    
                ]
                );
            $this->aggiungi($elenco);
            
            
            /*
              #ElencoPagine a:link, #ElencoPagine a:visited {
                  background-color: #....;
                  color: white;
                  padding: 5px 10px;
                  text-decoration: none;
                  display: inline-block;
             }
    
             */
            $voceVisibile = new RegolaCSS(
                '#'.self::ID_ELENCO.' a:link, '.'#'.self::ID_ELENCO.' a:visited',
                [
                    new DichiarazioneCSS('background-color',$this->coloreIndicePagina),
                    new DichiarazioneCSS('color',$this->indiceLateraleSx->coloreTesto()),
                    new DichiarazioneCSS('padding','5px 10px'),
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
                    new DichiarazioneCSS('margin-left','-40px'),
                    new DichiarazioneCSS('margin-top','1px'),
                    new DichiarazioneCSS('width', 'auto'),
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
            for ($indice=0;$this->limiteIndicePagina() > 1 && $indice < $argomento->numeroPagine(); $indice++){
                $listaPagine->aggiungi(
                    new Tag(
                        'li',
                        [new Stile('list-style-type','none')],
                        new Tag(
                            'a',
                            [new Attributo('href', self::link($argomento->nome(), $indice))],
                            self::indicePagMenu($indice).$argomento->nomePagina($indice)
                        )
                    )
                );
            }
        }
        $this->indiceLateraleSx->aggiungi($listaPagine);
    }
    
    private function indicePagMenu($indice) {
        
        return '<b style="color:white">pag. '.($indice+1).'</b>&nbsp ';
    }
    
    private function animaMenu(){
        if(!is_null($this->barraMenu)){
            $this->paginaTesto->aggiungi($this->barraMenu->azioneStickyMenu(self::ID_ELENCO));
            
        }
    }
    
    /**
     * {@inheritDoc}
     * @see PaginaHTML::__toString()
     */
    public function __toString(){
        
        $controllo = new Browser();
        SchermoCSS::$mobile = $controllo->telefono();
        if($controllo->html5()){

            self::creaIntestazioneSito();
            self::creaMenu();
            $pagina = new AreaPagina();
            
            if(isset($this->argomenti[$this->argomento])){
                if($this->argomento == self::HOME){
                    $pagina->margine('0', '0', '0', '0');
                    $home = $this->argomenti[$this->argomento];
                    if($home instanceof Pagina)
                        $pagina->aggiungi($home->testo());
                    
                }else{
                    $pagina->margine('10px', '0', '0', '0');
                    // Creazione (opzionale) della vista indice di pagine nella colonna di sinistra
                    if(!is_null($this->indiceLateraleSx)){ 
                        self::creaListaPagine();
                        $pagina->aggiungi($this->indiceLateraleSx);
                    }
                   $argomento = $this->argomenti[$this->argomento];
                    
                    self::inizializzaTitoloArgomentoMultipagina();
                    self::inizializzaIndiceDiPagina();
                    self::inizializzaTitoloPagina($argomento);
                    self::inizializzaPaginaDiTesto();
                    self::creaNoteMargineDx();
                
                    
                    $this->paginaTesto->aggiungi($this->titoloArgomento);
                    $this->paginaTesto->aggiungi($this->indiceDiPagina);
                    $this->paginaTesto->aggiungi($this->titoloPagina);
                    // crea pagina aggiugendo il testo per argomento e indice
                    $testo = '';
                    if($argomento instanceof Argomento){
                        $testo = $argomento->pagina($this->indice);
                        $this->paginaTesto->aggiungi($testo);
                    }
                    //visualizza i pulsanti di navigazione pagina alla fine del testo
                    //se il testo è di notevole dimensione.
                    if(strlen($testo) > 100){
                        $this->paginaTesto->aggiungi($this->indiceDiPagina);
                    }
                    //script inserito in '$this->paginaTesto'
                    self::animaMenu();
                    $sezionePrincipale = new ParagrafoPagina(null, 'auto');
                    $sezionePrincipale->aggiungi($this->paginaTesto);
                    $sezionePrincipale->aggiungi($this->noteLateraleDx);
                    $pagina->aggiungi($sezionePrincipale);
                   
                
                }
                $script= $this->dati[$this->argomento.$this->indice.''];
                if(isset($script)){
                    self::aggiungi($script);
                }
                self::aggiungi($pagina);
                self::cssBody();
                self::cssElencoPagine();
                self::cssFormattazionePagina();
            }
        }else{
            self::aggiungi(
                '<h1>Il browser non è compatibile con il codice HTML5 della pagina</h1><br><br><h2>'.$controllo.'</h2>'
            );
        }
        
        return parent::__toString();
    }
    
    
}

?>