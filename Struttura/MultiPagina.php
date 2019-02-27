<?php
include_once 'LibreriaQx7-php/PaginaHTML.php';
include_once 'LibreriaQx7-php/Pannello.php';
include_once 'LibreriaQx7-php/BarraMenu.php';
include_once 'LibreriaQx7-php/Menu.php';

class MultiPagina extends PaginaHTML {
    
    const LUNGHEZZA_PAGINA  = '700px';
    const LUNGHEZZA_PANNELLO_SX  = '270px';
    const FONT_TESTO_R      = 'LibreriaQx7-php/Amita-Regular.ttf';
    const FONT_INTESTAZIONE_R = 'LibreriaQx7-php/Akronim-Regular.ttf';
    //const FONT_TESTO_C      = 'AnonymousPro-Italic.ttf';
    //const FONT_TESTO_C_B    = 'AnonymousPro-BoldItalic.ttf';
    
    protected $barraMenu;
    protected $vociMenu = array();
    
    protected $indice = 0;
    protected $argomento = 'home';

    
    
    protected $intestazione;
    
    
    protected $paginaTesto;
    protected $indiceLateraleSx;
    protected $suggerimentoLateraleDx;
   
    
    

    public function __construct($titolo){
        parent::__construct($titolo);
        if(!is_null($_GET['pagina']))
            $this->indice = $_GET['pagina'];
        
        $this->creaPannelloLaterale();
        $this->creaTitoloPaggina();
        $this->creaPaginaDiTesto();
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
    
    private function creaPannelloLaterale(){
        $this->indiceLateraleSx = new Pannello(self::LUNGHEZZA_PANNELLO_SX, '400px', '#999', '#000');
        $this->indiceLateraleSx->posiziona(Posizione::ASSOLUTA,'0','50px');
    }
    
    private function creaTitoloPaggina(){
        $this->intestazione = new Pannello(self::LUNGHEZZA_PAGINA, 'auto', '#999', '#000');
        $this->intestazione->posiziona(Posizione::ASSOLUTA,'280px','55px');
        
        $this->intestazione->aggiungi('->'.$this->indice.'<-');
        $this->intestazione->aggiungi(
            new Stile(
                [
                    new DichiarazioneCSS('font-family',"'Akronim', cursive"),
                    new DichiarazioneCSS('font-size',"50px"),
                    new DichiarazioneCSS('padding',"5px"),
                ]
            )
        );
    }
    
    private function creaPaginaDiTesto(){
        $this->paginaTesto = new Pannello(self::LUNGHEZZA_PAGINA, '200%', '#ddd', 'black');
        $this->paginaTesto->posiziona(Posizione::ASSOLUTA,'280px','200px');
        $this->paginaTesto->aggiungi(
            "Silvia, rimembri ancora
            Quel <b>tempo</b> della tua vita mortale,
            Quando beltà splendea
            Negli occhi tuoi ridenti e fuggitivi,
            E tu, lieta e pensosa, il limitare            
            Di gioventù salivi?
            
            Sonavan le quiete
            Stanze, e le vie dintorno,
            Al tuo perpetuo canto,
            Allor che all'opre femminili intenta        
            Sedevi, assai contenta
            Di quel vago avvenir che in mente avevi.
            Era il maggio odoroso: e tu solevi
            Così menare il giorno.
            
            Io gli studi leggiadri                
            Talor lasciando e le sudate carte,
            Ove il tempo mio primo
            E di me si spendea la miglior parte,
            D'in su i veroni del paterno ostello
            Porgea gli orecchi al suon della tua voce,    
            Ed alla man veloce
            Che percorrea la faticosa tela.
            Mirava il ciel sereno,
            Le vie dorate e gli orti,
            E quinci il mar da lungi, e quindi il monte.    
            Lingua mortal non dice
            Quel ch'io sentiva in seno.
            
            Che pensieri soavi,
            Che speranze, che cori, o Silvia mia!
            Quale allor ci apparia                
            La vita umana e il fato!
            Quando sovviemmi di cotanta speme,
            Un affetto mi preme
            Acerbo e sconsolato,
            E tornami a doler di mia sventura.        
            O natura, o natura,
            Perchè non rendi poi
            Quel che prometti allor? perchè di tanto
            Inganni i figli tuoi?
            
            Tu pria che l'erbe inaridisse il verno,        
            Da chiuso morbo combattuta e vinta,
            Perivi, o tenerella. E non vedevi
            Il fior degli anni tuoi;
            Non ti molceva il core
            La dolce lode or delle negre chiome,        
            Or degli sguardi innamorati e schivi;
            Nè teco le compagne ai dì festivi
            Ragionavan d'amore.
            
            Anche peria fra poco
            La speranza mia dolce: agli anni miei        
            Anche negaro i fati
            La giovanezza. Ahi come,
            Come passata sei,
            Cara compagna dell'età mia nova,
            Mia lacrimata speme!                
            Questo è quel mondo? questi
            I diletti, l'amor, l'opre, gli eventi
            Onde cotanto ragionammo insieme?
            Questa la sorte dell'umane genti?
            All'apparir del vero                    
            Tu, misera, cadesti: e con la mano
            La fredda morte ed una tomba ignuda
            Mostravi di lontano."
            );
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
    
    
    
    /**
     * {@inheritDoc}
     * @see PaginaHTML::__toString()
     */
    public function __toString(){
        self::creaMenu();
        parent::aggiungi($this->paginaTesto);
        parent::aggiungi($this->intestazione);
        parent::aggiungi($this->indiceLateraleSx);
        
        $this->cssBody();
        return parent::__toString();
    }
    
}

?>