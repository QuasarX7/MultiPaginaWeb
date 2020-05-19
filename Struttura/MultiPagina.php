<?php
include_once 'LibreriaQx7-php/PaginaHTML.php';
include_once 'LibreriaQx7-php/html5.php';
include_once 'LibreriaQx7-php/BarraMenu.php';
include_once 'LibreriaQx7-php/Menu.php';
include_once 'LibreriaQx7-php/javascript.php';
include_once 'Argomento.php';
include_once 'PaginaLogin.php';

/**
 * Classe principale implementa la struttura di un semplice sito web multi-pagina
 * Gestito tramite un file di configurazione di tipo JSON.
 * 
 * @author Dr. Domenico della Peruta
 *
 */
class MultiPagina extends PaginaHTML {
    const ID_ELENCO = 'ElencoPagine'; // ID indici
    
    const CHIAVE_CAMPO_RICERCA = BarraMenu::CAMPO_RICERCA;
    
    const CHIAVE_PAGINA = 'pagina';
    const CHIAVE_ARGOMENTO = 'argomento';
    
    const CHIAVE_NOME_ROOT = PaginaLogin::CHIAVE_UTENTE;
    const CHIAVE_PASSWORD_ROOT = PaginaLogin::CHIAVE_PASSWORD;
    
    const HOME = 'home';
    const RICERCA = 'ricerca';
    const LOGIN = 'Utente Amministratore';
    
    const ALTEZZA_INTESTAZIONE_SITO  = 'auto';
    const ALTEZZA_MENU  = '50px';
    
    const LUNGHEZZA_PANNELLO_SX  = '22%';
    const LUNGHEZZA_AREA_PRINCIPALE  = '78%';
    
    const LUNGHEZZA_AREA_ARGOMENTO = '70%';
    const LUNGHEZZA_PANNELLO_DX  = '29%';
    
    const FONT_TESTO_STANDARD      = 'Struttura/Inter-Regular.ttf';
    const FONT_TESTO_SPECIALE      = 'Struttura/Aaargh.ttf';//'Struttura/Niconne-Regular.ttf';
    const FONT_TESTO_SPACEMONO     = 'Struttura/SpaceMono-Regular.ttf';
    const FONT_TITOLO_PAGINA_SPECIALE = 'FrederickatheGreat-Regular.ttf';
    
    const FONT_TITOLO_PAGINA_STANDARD = 'Struttura/ProstoOne-Regular.ttf';
    const FONT_MENU_STANDARD = 'Struttura/Anton-Regular.ttf';
    
    const NOME_FONT_INTESATAZIONE = 'intestazione';
    
    const MENU_HOME = 'Home';
    
    
    
    
    protected $fontIntestazione = 'Struttura/FrederickatheGreat-Regular.ttf';
    
    protected $barraMenu = null;
    protected $vociMenu = array();
    
    protected $indice = 0;
    protected $argomento = Argomento::HOME;

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
    protected $coloreIntestazione = 'black';
   
    protected static $accessoRoot = false;
    protected $password='';
    protected $utente='';
    
    
    /**
     * Costruttore.
     * 
     * @param string $titolo
     */
    protected function __construct($titolo,$utente='',$password=''){
        parent::__construct($titolo);
        $this->utente = $utente;
        $this->password = $password;
        
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
                $this->argomento = Argomento::HOME;
            }
        }
        if(filter_has_var(INPUT_GET, self::CHIAVE_CAMPO_RICERCA)){
            $valore = filter_input(INPUT_GET, self::CHIAVE_CAMPO_RICERCA, FILTER_SANITIZE_STRING);
            if ($valore !== false) {
                $this->argomento = urldecode($this->argomento);
            }
        }
        
        
        if(filter_has_var(INPUT_POST, self::CHIAVE_NOME_ROOT) && filter_has_var(INPUT_POST, self::CHIAVE_PASSWORD_ROOT)){
            $utente = filter_input(INPUT_POST, self::CHIAVE_NOME_ROOT, FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, self::CHIAVE_PASSWORD_ROOT, FILTER_SANITIZE_STRING);
            if ($password !== false && $utente !== false && $this->controllo($password,$utente)) {
                self::$accessoRoot = true;
            }else{
                $this->argomento = Argomento::HOME;
            }
        }
    }
    
    private function controllo(string $password,string $utente){
        if(strlen($this->password) > 0 && strlen($this->utente) > 0){
            if(strcmp($this->password,$password) == 0 && strcmp($this->utente,$utente) == 0)
                return true;
        }
        return false;
    }
    
    /**
     * Verifica l'esistenza dell'argomento.
     * @param string $cerca
     * @return boolean
     */
    private function verificaArgomento(string $cerca){
        foreach ($this->argomenti as $argomento) {
            if($argomento instanceof Argomento)
                if($argomento->nome() === $cerca)
                    return true;
        }
        return false;
    }
    
    /**
     * Metodo statico di costruzione del nostro sito web, usando un file di configurazione
     * JSON.
     *
     * @param string $file
     * @return MultiPagina
     */
    static public function costruisciDaFileJSON(string $file){
        return  MultiPagina::costruisciDaJSON(MultiPagina::datiJSON($file));
    }
    
    /**
     * Metodo statico di costruzione del nostro sito web, usando dati di tipo
     * JSON.
     * 
     * @param array $dati
     * @return MultiPagina
     */
    static public function costruisciDaJSON(array $dati){
        
        $sito = new MultiPagina(MultiPagina::cerca($dati,'titolo'),MultiPagina::cerca($dati, 'utente'),MultiPagina::cerca($dati, 'password'));
        $sito->logoPNG(MultiPagina::cerca($dati,'logo'));
        $sito->aggiungi(new RegolaCSS(
            '#home',
            [
                new DichiarazioneCSS('position', 'fixed'),
                new DichiarazioneCSS('top', '0'),
                new DichiarazioneCSS('left', '0'),
                new DichiarazioneCSS('z-index', '-10000'),
                new DichiarazioneCSS('min-width', '100%'),
                new DichiarazioneCSS('min-height', '100%'),
                new DichiarazioneCSS('height', 'auto'),
                new DichiarazioneCSS('width', 'auto')
                
            ]
            )
        );
        $coloreTesto=MultiPagina::cerca($dati, 'colore-testo','#B0E0E6');
        $coloreSeleziona=MultiPagina::cerca($dati, 'colore-seleziona','#483D8B');
        $coloreIntestazione=MultiPagina::cerca($dati, 'colore-intestazione','blue');
        $coloreMenu=MultiPagina::cerca($dati, 'colore-menu','#111');
        $coloreSottoMenuPrimoLivello=MultiPagina::cerca($dati, 'colore-sottomenu-primo',$coloreMenu);
        $coloreSottoMenuSecondoLivello=MultiPagina::cerca($dati, 'colore-sottomenu-secondo',$coloreSottoMenuPrimoLivello);
        
        $sito->coloreIntestazione($coloreIntestazione);
        $sito->creaBarraMenu($coloreMenu,$coloreTesto,$coloreSeleziona);
        $sito->inizializzaPrimoLivelloMenu($coloreSottoMenuPrimoLivello, $coloreTesto);
        $sito->inizializzaSecondoLivelloMenu($coloreSottoMenuSecondoLivello, $coloreTesto);
        
        $sito->creaPannelloLaterale($coloreIntestazione, $coloreTesto, $coloreSeleziona);
        
        $sito->aggiungiMenu(self::MENU_HOME);
        foreach ($dati as $etichetta => $menu) {
            if($etichetta == 'menu' && is_array($menu)){
                MultiPagina::creaVoceMenu($menu, $sito);
            }
        }
        
        foreach ($dati as $etichetta => $note) {
            if($etichetta == 'note' && is_array($note)){
                foreach ($note as $nota) {
                    $campo = new Pannello('auto', 'auto',$nota['colore']);
                    $campo->aggiungi(new Attributo('class', 'alert alert-dismissible fade show'));
                    $campo->aggiungi(new Attributo('role', 'alert'));
                    $campo->margine('20px', '5px', '2px', '2px');
                    $pulsanteChiudi= new Tag('button',[
                        new Attributo('type', 'button'),
                        new Attributo('class', 'close'),
                        new Attributo('data-dismiss', 'alert'),
                        new Attributo('aria-label', 'Close')
                    ]);
                    $campo->aggiungi('<p>'.$nota['testo']);
                    $campo->aggiungi($pulsanteChiudi);
                    
                    
                    $sito->aggiungiNoteMarginePagina($campo);
                }
            }
        }
        
        $paginaHome=MultiPagina::cerca($dati, self::HOME,'');
        
        $sito->aggiungiHome(new Pagina(self::HOME,$paginaHome));
        
        return $sito;
    }
    
    
    
    static private function datiJSON(string $file){
        $json = new Pagina('file JSON',$file);
        $dati = json_decode($json->testo(),true);
        return $dati;
    }
    
    static private function cerca(array $dati,string $cerca,string $predefinito=''){
        foreach ($dati as $etichetta => $valore) {
            if($etichetta == $cerca){
                return $valore .'';
            }
        }
        return $predefinito;
    }
    
    static private function creaVoceMenu(array $menu,MultiPagina $sito,string $genitore=null){
        if(count($menu) > 0){
            foreach ($menu as $lista) {
                $menuGenitore = null;
                if(isset($lista['visibile']) && $lista['visibile'] === false && self::$accessoRoot === false ) continue;
                foreach ($lista as $etichetta => $voce) {
                    if($etichetta == 'etichetta'){
                        $argomento = MultiPagina::creaArgomento($lista);
                        if($argomento != null)
                            $sito->aggiungiArgomento($argomento);
                        $sito->aggiungiMenu($voce,$argomento,$genitore);
                        $menuGenitore = $voce;
                    } else if($etichetta == 'contenuto')
                        MultiPagina::creaVoceMenu($voce, $sito,$menuGenitore);
                        
                }
            }
            
        }
    }
    
    static private function creaArgomento(array $menu){
        if(isset($menu['argomento'])){
            $lista = $menu['argomento'];
            if($lista != null){
                $argomento = new Argomento($lista['titolo']);
                foreach ($lista['pagine'] as $voce) {
                    $argomento->aggiungiPagina($voce['sotto-titolo'], $voce['file']);
                }
                return $argomento;
            }
        }
        return null;
    }
    

    /**
     * Aggiungi nuova pagina ad un argomento specificato nel menu.
     *
     * @param string $fileJSON
     * @param array $menu               es.: ['voce_menu','voce_sottomenu',...,'voce_argomento']
     * @param string $titoloPagina
     * @param string $filePagina        path dove dovrà risiederà il file contenente (testo o html) della pagina
     * @param string $htmlPagina        testo...
     */
    static public function aggiungiPaginaAlFileJSON(string $fileJSON,array $menu,string $titoloPagina,string $filePagina,string $htmlPagina){
        $dati = MultiPagina::datiJSON($fileJSON);
        
        $json = json_encode($dati,JSON_PRETTY_PRINT);
        file_put_contents($fileJSON, $json);
    }
    
    
    protected function coloreIntestazione(string $colore) {
        $this->coloreIntestazione = $colore;
    }
    
    /**
     * Crea una barra menu orizzontale posizionata dopo l'intestazione di pagina.
     * 
     * @param string $coloreSfondo
     * @param string $coloreTesto
     * @param string $coloreSeleziona
     */
    protected  function creaBarraMenu(string $coloreSfondo, string $coloreTesto, string $coloreSeleziona){
        $this->barraMenu = new BarraMenu($coloreSfondo, $coloreTesto, $coloreSeleziona,Posizione::RELATIVA);
        $this->coloreMenu = $coloreSfondo;
    }
    
    /**
     * Inizializza i colori delle voci del primo livello della barraMenu.
     * 
     * @param string $coloreSfondo
     * @param string $coloreTesto
     */
    protected function inizializzaPrimoLivelloMenu(string $coloreSfondo, string $coloreTesto){
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
    protected function inizializzaSecondoLivelloMenu(string $coloreSfondo, string $coloreTesto){
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
    protected function aggiungiMenu(string $etichetta,Argomento $argomento=null,string $menu=null){
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
        return Argomento::link($argomento, $pagina);
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
                            self::aggiungiSottomenu($menu->vociMenu(), $nuovoMenu, $etichetta);
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
        $this->intestazioneSito = new IntestazionePagina(self::ALTEZZA_INTESTAZIONE_SITO,$this->coloreIntestazione,(self::$accessoRoot === true) ? 'red' : 'white');
        $this->intestazioneSito->aggiungi($this->creaAccessoUtente() . $this->titolo);
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
    private function creaAccessoUtente(){
        $link = new Tag('a',
            [
                new Attributo('href', '?pagina=0&argomento='.self::LOGIN),
                
            ]);
        $immagine = new Tag(
            'img',
            [
                new Attributo('src', 'Struttura/accesso_utente.png'),
                new Attributo('style', 'display:inline;margin: 0 10px')
            ]);
        $link->aggiungi($immagine->vedi());
        return $link->vedi();
    }
    
  
    /**
     * Se è stato definito un menu viene aggiunto alla codice html.
     */
    private function creaMenu(){
        if(!is_null($this->barraMenu)){
            foreach ($this->vociMenu as $voce) {
                $this->barraMenu->aggiungi($voce);
            }
            $this->creaMenuRicercaInfo();
            parent::aggiungi($this->barraMenu->vedi());// disegna corpo
        }
    }
    
    

    /**
     * Crea un menu a tendina nella campo di ricerca della barra menu principale.
     */
    private function creaMenuRicercaInfo(){
        BarraMenu::aggiungiListaRicercaURL($this->listaRicerca());
    }
    
    /**
     *  Lista di tutti gli URL delle pagine dei singoli argomenti presenti nel sito.
     * @return array
     */
    private function listaRicerca(){
        $lista = array();
        foreach ($this->argomenti as $argomento) {
            if($argomento instanceof Argomento)
                $lista = array_merge($lista,$argomento->listaIndici());
        }
        return $lista;
    }
    
    private function creaLogin(){
        $argomento = new Argomento(self::LOGIN);
        $paginaLogin = new PaginaLogin();
        $argomento->aggiungiPaginaCodice(self::LOGIN, $paginaLogin->vedi());
        $this->aggiungiArgomento($argomento);
    }
    
    /**
     * Aggiungi un argomento alla multi-pagina.
     * 
     * @param Argomento $argomento
     */
    protected function aggiungiArgomento(Argomento $argomento){
        $this->argomenti[$argomento->nome()] = $argomento;
    }
    
    /**
     * Permette di specificare l'indirizzo dove trovare il file contenente
     * il codice "body" da inseire nella pagina iniziale.
     * 
     * @param Pagina $file
     */
    protected function aggiungiHome(Pagina $file){
        $this->argomenti[Argomento::HOME] = $file;
    }
    
    /**
     * Aggiungi annotazioni permanenti alla pagina sul margine destro.
     * 
     * @param Pannello $note
     */    
    protected function aggiungiNoteMarginePagina(Pannello $note){
        $this->note[] = $note;
    }

    /**
     * Imposta un eventuale pannello laterale
     * 
     * @param string $coloreSfondo
     * @param string $coloreTesto
     * @param string $coloreSeleziona
     */
    protected function creaPannelloLaterale($coloreSfondo, $coloreTesto, $coloreSeleziona){
        $this->indiceLateraleSx = new NotePagina(self::LUNGHEZZA_PANNELLO_SX,'100%', $this->coloreMenu != null ? $this->coloreMenu : $coloreSfondo, $coloreTesto);
        $this->coloreSelezionaIndicePagina = $coloreSeleziona;
        $this->coloreIndicePagina = $coloreSfondo;
        $this->indiceLateraleSx->allineamentoVerticale(Lato::ALTO);
        $this->indiceLateraleSx->aggiungi(new Attributo('class', 'd-none d-md-block col-md-4 col-lg-2 col-xl-2')); //////////////////////////////////////////
        
    }
    
 
    private function inizializzaTitoloArgomentoMultipagina(){
        // Crea titolo dell'argomento della multipagina
        if(isset($this->argomento))
            if($this->argomento != Argomento::HOME){
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
        $this->paginaTesto->aggiungi(new Attributo('class', 'col-12 col-sm-12 col-md-8 col-lg-7'));
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
        $this->noteLateraleDx = new NotePagina('auto', 'auto');
        $this->noteLateraleDx->aggiungi(new Attributo('class', 'col-12 col-sm-12 col-md-12 col-lg-3')); 
        
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
    protected function formatoCarattereDiIntestazione($font) {
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
        parent::importaFont(/*'Niconne'*/'Aaargh', self::FONT_TESTO_SPECIALE);
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
        
        //classi speciale di formattazione del testo
        $this->classeTesto();
        $this->classeTecnico();
    }
    
    private function classeTecnico(){
        
        $classe = new RegolaCSS(
            '.tecnico',
            [
                new DichiarazioneCSS('margin','4px'),
                new DichiarazioneCSS('padding','4px'),
                new DichiarazioneCSS('text-align','justify'),
                
                new DichiarazioneCSS('font-size','15px'),
                new DichiarazioneCSS('font-family', "'Aaargh', sans-serif")
            ]
            );
        $this->aggiungi($classe);
        
        $kbd = new RegolaCSS(
            '.tecnico kbd',
            [
                new DichiarazioneCSS('font-size','16px'),
                new DichiarazioneCSS('font-family', "'Aaargh', sans-serif"),
                new DichiarazioneCSS('font-weight', 'bold'),
                new DichiarazioneCSS('color','#000'),
                new DichiarazioneCSS('background','#FFF')
            ]
            );
        $this->aggiungi($kbd);
        
        $titolo = new RegolaCSS(
            '.tecnico h1,.tecnico h2,.tecnico h3',
            [
                new DichiarazioneCSS('margin-top','30px'),
                new DichiarazioneCSS('margin-bottom','20px'),
                new DichiarazioneCSS('font-weight','normal'),
                new DichiarazioneCSS('text-align','center'),
                new DichiarazioneCSS('color','#000'),
                new DichiarazioneCSS('background','#FFF'),
                new DichiarazioneCSS('font-family', "'intestazione', script")
            ]
            );
        $this->aggiungi($titolo);
        
        $link = new RegolaCSS(
            '.tecnico a.linkInterno',
            [
                new DichiarazioneCSS('color', '#3CB371'),
                new DichiarazioneCSS('text-decoration', 'none'),
                new DichiarazioneCSS('font-weight', 'bold')
            ]
            );
        $this->aggiungi($link);
        
        $link_focus = new RegolaCSS(
            '.tecnico a:hover,.tecnico a:active,.tecnico a.linkInterno:hover,.tecnico a.linkInterno:active',
            [
                new DichiarazioneCSS('color', 'orange'),
                new DichiarazioneCSS('text-decoration', 'underline')
            ]
            );
        $this->aggiungi($link_focus);
        
        $tabella = new RegolaCSS(
            '.tecnico td, .tecnico th',
            [
                new DichiarazioneCSS('margin','4px'),
                new DichiarazioneCSS('padding','4px')
            ]
            );
        $this->aggiungi($tabella);
        
        $schedario = new RegolaCSS(
            '.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active',
            [
                new DichiarazioneCSS('color',$this->coloreSelezionaIndicePagina),
                new DichiarazioneCSS('background-color',$this->coloreMenu),
                new DichiarazioneCSS('font-size','16px'),
                new DichiarazioneCSS('font-family', "'Anton', sans-serif")
            ]
            );
        $this->aggiungi($schedario);
        /*
         * .nav-tabs .nav-link {
    /* border: 1px solid transparent; *
    border-top-left-radius: .25rem;
    border-top-right-radius: .25rem;

         */
        $schedario = new RegolaCSS(
            '.tecnico .nav-tabs .nav-link',
            [
                new DichiarazioneCSS(' border', '1px solid grey')
            ]
            );
        $this->aggiungi($schedario);
        
        $schedario_focus = new RegolaCSS(
            '.tecnico .nav-tabs a:hover,.tecnico .nav-tabs a:active,.tecnico .nav-tabs a.linkInterno:hover,.tecnico .nav-tabsa.linkInterno:active',
            [
                new DichiarazioneCSS('color', 'white'),
                new DichiarazioneCSS('text-decoration', 'none')
            ]
            );
        $this->aggiungi($schedario_focus);
    }
    
    /**
     * Imposta lo stile "testo"
     */
    private function classeTesto(){
        $classe = new RegolaCSS(
            '.testo',
            [
                new DichiarazioneCSS('margin','2px'),
                new DichiarazioneCSS('padding','2px'),
                new DichiarazioneCSS('text-align','justify'),
                //new DichiarazioneCSS('font-size','26px'),
                //new DichiarazioneCSS('font-family', "'Niconne', sans-serif")
                
                new DichiarazioneCSS('font-size','15px'),
                new DichiarazioneCSS('font-family', "'Aaargh', sans-serif")
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
    
    
    
    private function cssElencoPagine(){
        if($this->indiceLateraleSx instanceof Pannello) {
            /*
             #ElencoPagine{
             'width' : ...;
             }*/
             $elenco = new RegolaCSS(
                '#'.self::ID_ELENCO,
                [
                    new DichiarazioneCSS('margin-top', '10px'),
                    new DichiarazioneCSS('margin-left', '-10px'),
                    new DichiarazioneCSS('width', '100%'),
                    new DichiarazioneCSS('overflow-y','auto'),
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
        //$listaPagine->aggiungi(new Attributo('class', 'fixed-bottom'));
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
    
    
    
    /**
     * {@inheritDoc}
     * @see PaginaHTML::__toString()
     */
    public function __toString(){
        
        $controllo = new Browser();
        SchermoCSS::$mobile = $controllo->telefono();
        if($controllo->html5()){

            $this->creaIntestazioneSito();
            $this->creaMenu();
            $this->creaLogin();
            
            $pagina = new AreaPagina();
            $contenitorePagina= new Tag('div',[new Attributo('class', 'container-fluid')]);
            $areaPagina= new Tag('div',[new Attributo('class', 'row')]);
            
            if(isset($this->argomenti[$this->argomento])){
                if($this->argomento == Argomento::HOME){
                    $pagina->margine('0', '0', '0', '0');
                    $home = $this->argomenti[$this->argomento];
                    if($home instanceof Pagina)
                        $areaPagina->aggiungi($home->testo());
                    
                }else{
                    $pagina->margine('10px', '0', '0', '0');
                    // Creazione (opzionale) della vista indice di pagine nella colonna di sinistra
                    if(!is_null($this->indiceLateraleSx)){ 
                        $this->creaListaPagine();
                        $areaPagina->aggiungi($this->indiceLateraleSx);
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
                        if(self::$accessoRoot !== true){
                            $testo = $argomento->pagina($this->indice);
                            $this->paginaTesto->aggiungi($testo);
                        }else{
                            $this->paginaTesto->aggiungi('<b>Ciao Mondo</b>');
                        }
                    }
                    //visualizza i pulsanti di navigazione pagina alla fine del testo
                    //se il testo è di notevole dimensione.
                    if(strlen($testo) > 100){
                        $this->paginaTesto->aggiungi($this->indiceDiPagina);
                    }
                    $areaPagina->aggiungi($this->paginaTesto);
                    $areaPagina->aggiungi($this->noteLateraleDx);
                }

            }
            $contenitorePagina->aggiungi($areaPagina);
            $pagina->aggiungi($contenitorePagina);
            $this->aggiungi($pagina);
            $this->cssBody();
            $this->cssElencoPagine();
        }else{
            $this->aggiungi(
                '<h1>Il browser non è compatibile con il codice HTML5 della pagina</h1><br><br>'.
                '<h2>' .'dispositivo: '.$controllo->dispositivo().'</h2>'.
                '<h2>' .'browser: '.$controllo->nome().'</h2>'.
                '<h2>' .'versione: '.$controllo->versione().'</h2>'.
                '<h2> </h2>'.
                ':\'('
            );
        }
        
        return parent::__toString();
    }
    
    
}

?>