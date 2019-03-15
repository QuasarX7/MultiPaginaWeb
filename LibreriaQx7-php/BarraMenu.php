<?php

include_once 'Tag.php';
include_once 'Menu.php';

class BarraMenu extends Tag{
    
    protected $menu = array();
    protected $coloreSfondo; 
    protected $coloreTesto;
    
    protected $coloreSfondoVoce;
    protected $coloreTestoVoce;
    
    protected $coloreSfondoVoce2liv;
    protected $coloreTestoVoce2liv;
    
    protected $coloreSeleziona;

    

    /**
     * Costruisce un tag 'nav'.
     */
    public function __construct($coloreSfondo,$coloreTesto,$coloreSeleziona,$posizione){
        $this->nome = 'nav';
        $this->contenuto = ' ';
        $this->coloreSfondo = $coloreSfondo;
        $this->coloreTesto = $coloreTesto;
        $this->coloreSeleziona = $coloreSeleziona; 
        
        $this->coloreSfondoVoce2liv = $this->coloreSfondoVoce = $coloreSfondo;
        $this->coloreTestoVoce2liv = $this->coloreTestoVoce = $coloreTesto;
        
        if($posizione == Posizione::FISSA){
            parent::aggiungi(new Attributo('id', 'fisso'));
        }else{
            parent::aggiungi(new Attributo('id', 'mobile'));
        }
    }

    
    public function menuPrimoLivello($coloreSfondo,$coloreTesto){
        $this->coloreSfondoVoce = $coloreSfondo;
        $this->coloreTestoVoce = $coloreTesto;
    }
    
    
    public function menuSecondoLivello($coloreSfondo,$coloreTesto){
        $this->coloreSfondoVoce2liv = $coloreSfondo;
        $this->coloreTestoVoce2liv = $coloreTesto;
    }
    

    /**
     * Restituisce l'insieme delle regole di stile aggiunte alla pagina HTML.
     *
     * @return array RegoleCSS
     */
    public function regoleCSS(){
        $css = array();
        /*
          nav {
                background-color: #333;
                border: 1px solid #333;
                display: block;
                margin: 0;
                overflow: hidden;
            }
         */
        $nav = new RegolaCSS(
            'nav',
            [
                new DichiarazioneCSS('width','100%'),
                new DichiarazioneCSS('background-color',$this->coloreSfondo),
                new DichiarazioneCSS('border','1px solid '.$this->coloreSfondo),
                new DichiarazioneCSS('display','block'),
                new DichiarazioneCSS('margin','0'),
                new DichiarazioneCSS('z-index','10000'),
                new DichiarazioneCSS('overflow','hidden')
            ]
        );
        /*
        if($this->posizione == Posizione::FISSA){
            $nav->aggiungi(new DichiarazioneCSS('position',Posizione::FISSA));
        }*/
        $css[] = $nav;
        
        $nav_fisso = new RegolaCSS(
            'nav#fisso',
            [
                new DichiarazioneCSS('position',Posizione::FISSA)
            ]
            );
        $css[] = $nav_fisso;
        
        /*nav ul{
            margin: 0;
            padding: 0;
            list-style: none;
        }
        */
        $nav_ul = new RegolaCSS(
            'nav ul',
            [
                new DichiarazioneCSS('margin','0'),
                new DichiarazioneCSS('padding','0'),
                new DichiarazioneCSS('list-style','none')
            ]
        );
        $css[] = $nav_ul;
        
        
        /*
         
         nav ul li {
            margin: 0;
            display: inline-block;
            list-style-type: none;
            transition: all 0.2s;
        }
         */
        $nav_ul_li = new RegolaCSS(
            'nav ul li',
            [
                new DichiarazioneCSS('margin','0'),
                new DichiarazioneCSS('display','inline-block'),
                new DichiarazioneCSS('list-style-type','none'),
                new DichiarazioneCSS('transition','all 0.2s')
            ]
        );
        $css[] = $nav_ul_li;
        
        /*
        nav > ul > li > a {
            color: #aaa;
        }
        */
        $css[] = $this->stileCellaMenu('nav > ul > li > a', $this->coloreTesto);
        
        
        /*
        nav li > ul{
            display : none;
            margin-top:1px;
            background-color: #bbb;
        
        }
         */
        $nav_li_ul = new RegolaCSS(
            'nav li > ul',
            [
                new DichiarazioneCSS('display','none'),
                new DichiarazioneCSS('background-color',$this->coloreSfondoVoce),
                new DichiarazioneCSS('margin-top','1px')
            ]
        );
        $css[] = $nav_li_ul;
        
        
        /*
         nav li > ul li{
            display: block;
        }
         */
        $nav_li_ul_li = new RegolaCSS(
            'nav li > ul li',
            [
                new DichiarazioneCSS('display','block')
            ]
            );
        $css[] = $nav_li_ul_li;
        
        /*
        nav  li > ul li a {
            color: #111;
            display: block;
            line-height: 2em;
            padding: 0.5em 2em;
            text-decoration: none;
        }
         */
        $css[] = $this->stileCellaMenu('nav li > ul li a', $this->coloreTestoVoce);
        /*
         
         nav li:hover {
            background-color: #666;
        }
        
        */
        $nav_li_hover = new RegolaCSS(
            'nav li:hover',
            [
                new DichiarazioneCSS('background-color',$this->coloreSeleziona)
            ]
        );
        $css[] = $nav_li_hover;
        /*
        nav li:hover > ul{
            position:absolute; //* N.B.: variate
            display : block;
        }
        */
        $nav_li_hover_ul= new RegolaCSS(
            'nav li:hover > ul',
            [
                new DichiarazioneCSS('display','block')
            ]
        );
        //$css[] = $nav_li_hover_ul;
        
        $nav_fissa_li_hover_ul= new RegolaCSS(
            'nav#fisso li:hover > ul',
            [
                new DichiarazioneCSS('position',Posizione::FISSA),new DichiarazioneCSS('display','block')
            ]
            );
        $css[] = $nav_fissa_li_hover_ul;
        
        $nav_mobile_li_hover_ul= new RegolaCSS(
            'nav#mobile li:hover > ul',
            [
                new DichiarazioneCSS('position',Posizione::ASSOLUTA),new DichiarazioneCSS('display','block')
            ]
            );
        $css[] = $nav_mobile_li_hover_ul;
        
        
        /*
        nav li > ul > li ul  {
            display: none;
            background-color: #888;
        }
        */
        $nav_li_ul_li_ul= new RegolaCSS(
            'nav li > ul > li ul',
            [
                new DichiarazioneCSS('background-color',$this->coloreSfondoVoce2liv),
                new DichiarazioneCSS('display','none')
                
            ]
        );
        $css[] = $nav_li_ul_li_ul;
        
        /*
         nav li > ul > li ul a{
            color: #888;
        }
         */
        $nav_li_ul_li_ul_a= new RegolaCSS(
            'nav li > ul > li ul a',
            [
                new DichiarazioneCSS('color',$this->coloreTestoVoce2liv)
            ]
        );
        $css[] = $nav_li_ul_li_ul_a;
        
        /*
        nav li > ul > li:hover > ul  {
            position:absolute;
            display : block;
            margin-left:100%;
            margin-top:-3em;
        }
        */
        $nav_li_ul_li_hover_ul= new RegolaCSS(
            'nav li > ul > li:hover > ul',
            [
                
                new DichiarazioneCSS('display','block'),
                new DichiarazioneCSS('margin-left','100%'),
                new DichiarazioneCSS('margin-top','-3em')
                
            ]
            );
        $css[] = $nav_li_ul_li_hover_ul;
        
        $nav_fisso_li_ul_li_hover_ul= new RegolaCSS(
            'nav#fisso li > ul > li:hover > ul',
            [
                new DichiarazioneCSS('position',Posizione::ASSOLUTA)
            ]
            );
        $css[] = $nav_fisso_li_ul_li_hover_ul;
        
        
        
        /*
        nav ul > li.sub{
            background: url(LibreriaQx7-php/freccia_verticale.png) right center no-repeat;
        }
        */
        $nav_ul_li_sub= new RegolaCSS(
            'nav ul > li.sub',
            [
                new DichiarazioneCSS('background','url(LibreriaQx7-php/freccia_verticale.png) right center no-repeat')
            ]
        );
        $css[] = $nav_ul_li_sub;
        /*
        nav ul > li.sub li.sub{
            background: url(LibreriaQx7-php/freccia_orizzontale.png) right center no-repeat;
        }
         */
        $nav_ul_li_sub_li_sub= new RegolaCSS(
            'nav ul > li.sub li.sub',
            [
                new DichiarazioneCSS('background','url(LibreriaQx7-php/freccia_orizzontale.png) right center no-repeat')
            ]
        );
        $css[] = $nav_ul_li_sub_li_sub;
        
        return $css;
        
    }
    
    
    private function stileCellaMenu($selettore,$colore){
        return new RegolaCSS(
            $selettore,
            [
                new DichiarazioneCSS('color',$colore),
                new DichiarazioneCSS('display','block'),
                new DichiarazioneCSS('line-height','2em'),
                new DichiarazioneCSS('padding','0.5em 1.5em'),
                new DichiarazioneCSS('text-decoration','none')
            ]
            );
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
        return $this->menu[$nome];
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

    /**
     * {@inheritDoc}
     * @see Tag::__toString()
     */
    public function __toString(){
        if(count($this->menu) > 0){
            
            
                
            
            parent::aggiungi(new Attributo('class','sub')); 
            
            $lista = new Tag('ul');
            foreach ($this->menu as  $voce) {
                $lista->aggiungi($voce);
            }
            $this->contenuto .= $lista->vedi();
        }
        return parent::__toString();
    }
    
}