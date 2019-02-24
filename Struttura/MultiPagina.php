<?php
include_once 'LibreriaQx7-php/PaginaHTML.php';
include_once 'LibreriaQx7-php/Pannello.php';
include_once 'LibreriaQx7-php/BarraMenu.php';
include_once 'LibreriaQx7-php/Menu.php';

class MultiPagina extends PaginaHTML {
    
    protected $indice = 0;
    protected $argomento = 'home';

    
    protected $intestazione;
    
    protected $paginaTesto;
    protected $indiceLateraleSx;
    protected $suggerimentoLateraleDx;
   
    
    

    public function __construct($titolo){
        parent::__construct($titolo);
        $this->creaPannelloLaterale();
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
        $this->indiceLateraleSx = new Pannello('200px', '150%', '#999', '#000');
        
    }
    
    /**
     * Inizializza lo stile predefinito della pagina.
     */
    private function cssBody(){
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
        parent::aggiungi($this->indiceLateraleSx);
        $this->cssBody();
        return parent::__toString();
    }
    
}

?>