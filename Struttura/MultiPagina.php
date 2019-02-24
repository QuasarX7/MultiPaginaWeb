<?php
include_once 'LibreriaQx7-php/PaginaHTML.php';
include_once 'LibreriaQx7-php/Pannello.php';
include_once 'LibreriaQx7-php/BarraMenu.php';
include_once 'LibreriaQx7-php/Menu.php';

class MultiPagina extends PaginaHTML {
    
    const LUNGHEZZA_PAGINA  = '700px';
    const FONT_TESTO_R      = 'Amita-Regular.ttf';
    const FONT_TESTO_B      = 'Amita-Bold.ttf';
    //const FONT_TESTO_C      = 'AnonymousPro-Italic.ttf';
    //const FONT_TESTO_C_B    = 'AnonymousPro-BoldItalic.ttf';
    
    protected $indice = 0;
    protected $argomento = 'home';

    
    protected $intestazione;
    
    protected $paginaTesto;
    protected $indiceLateraleSx;
    protected $suggerimentoLateraleDx;
   
    
    

    public function __construct($titolo){
        parent::__construct($titolo);
        $this->creaPannelloLaterale();
        $this->creaTitoloPaggina();
        $this->creaPaginaDiTesto();
    }
    
  
    /**
     * {@inheritDoc}
     * @see PaginaHTML::aggiungi()
     */
    public function aggiungi($valore){
        if($valore instanceof BarraMenu){
            parent::aggiungi($valore->vedi());// disegna corpo
            foreach ($valore->regoleCSS() as $regolaCSS) {
                parent::aggiungi($regolaCSS);//aggiungi regola al tag style del head della pagina HTML
            }
        }else{
            parent::aggiungi($valore);
        }
    }
    
    private function creaPannelloLaterale(){
        $this->indiceLateraleSx = new Pannello('270px', '400px', '#999', '#000');
        $this->indiceLateraleSx->posiziona(Posizione::FISSA,'0','50px');
    }
    
    private function creaTitoloPaggina(){
        $this->intestazione = new Pannello(self::LUNGHEZZA_PAGINA, '80px', '#999', '#000');
        $this->intestazione->posiziona(Posizione::ASSOLUTA,'280px','55px');
    }
    
    private function creaPaginaDiTesto(){
        $this->paginaTesto = new Pannello(self::LUNGHEZZA_PAGINA, '200%', '#ddd', 'black');
        $this->paginaTesto->posiziona(Posizione::ASSOLUTA,'280px','150px');
        $this->paginaTesto->aggiungi(
            "Silvia, rimembri ancora
Quel tempo della tua vita mortale,
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
        $this->paginaTesto->aggiungi(new Stile('font-family',"'Amita', cursive"));
    }
    
    /**
     * Inizializza lo stile predefinito della pagina.
     */
    private function cssBody(){
        parent::importa(self::FONT_TESTO_R);
        parent::importa(self::FONT_TESTO_B);
        //parent::importa(self::FONT_TESTO_C);
        //parent::importa(self::FONT_TESTO_C_B);
        
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
                //new DichiarazioneCSS('font-family', '"Lucida Grande", "Helvetica Nueue", Arial, sans-serif')
            ]
            );
        $this->aggiungi($body);
    }
    
    /**
     * {@inheritDoc}
     * @see PaginaHTML::__toString()
     */
    public function __toString(){
        parent::aggiungi($this->paginaTesto);
        parent::aggiungi($this->intestazione);
        parent::aggiungi($this->indiceLateraleSx);
        
        $this->cssBody();
        return parent::__toString();
    }
    
}

?>