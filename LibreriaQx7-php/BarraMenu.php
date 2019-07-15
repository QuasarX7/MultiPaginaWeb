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
                new DichiarazioneCSS('overflow','hidden'),
                new DichiarazioneCSS('transition', "top 0.5s"),//eventuale effetto...
                new DichiarazioneCSS('box-shadow', '0px 5px 5px 5px rgba(0,0,0,0.3)')
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
        
        // colora celle menu (primo livello)
        $css[] = $this->stileCellaMenu('nav > ul > li > a', $this->coloreTesto);
        
        //colora celle menu (secondo livello)
        $css[] = $this->stileCellaMenu('nav li > ul li a', $this->coloreTestoVoce);
        
       
        
        $css[] = self::maxSchermo();
        $css[] = self::miniSchermo();
        return $css;
        
    }
    
    private function maxSchermo(){
        $regole = array();
        
        // colore voce selezionata
        $nav_li_hover = new RegolaCSS(
            'nav li:hover',
            [
                new DichiarazioneCSS('background-color',$this->coloreSeleziona)
            ]
            );
        $regole[] = $nav_li_hover;
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
                new DichiarazioneCSS('transition','all 0.2s'),
                new DichiarazioneCSS('box-shadow', '3px 5px 2px rgba(0,0,0,0.3)')
            ]
            );
        $regole[] = $nav_ul_li;
        
        
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
        $regole[] = $nav_li_ul;
        
        
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
        $regole[] = $nav_li_ul_li;
        
        
        
        
        /*
         nav li:hover > ul{
         position:absolute; //* N.B.: variate
         display : block;
         }
         */
        $nav_fissa_li_hover_ul= new RegolaCSS(
            'nav#fisso li:hover > ul',
            [
                new DichiarazioneCSS('position',Posizione::FISSA),new DichiarazioneCSS('display','block')
            ]
            );
        $regole[] = $nav_fissa_li_hover_ul;
        
        $nav_mobile_li_hover_ul= new RegolaCSS(
            'nav#mobile li:hover > ul',
            [
                new DichiarazioneCSS('position',Posizione::ASSOLUTA),new DichiarazioneCSS('display','block')
            ]
            );
        $regole[] = $nav_mobile_li_hover_ul;
        
        
        /*
         nav li > ul > li ul  {
         display: none;
         background-color: #888;
         }
         */ // colora voci menu 3 livello
        $nav_li_ul_li_ul= new RegolaCSS(
            'nav li > ul > li ul',
            [
                new DichiarazioneCSS('background-color',$this->coloreSfondoVoce2liv),
                new DichiarazioneCSS('display','none')
                
            ]
            );
        $regole[] = $nav_li_ul_li_ul;
        
        /*
         nav li > ul > li ul a{
         color: #888;
         }
         */ // colora voci menu 3 livello
        $nav_li_ul_li_ul_a= new RegolaCSS(
            'nav li > ul > li ul a',
            [
                new DichiarazioneCSS('color',$this->coloreTestoVoce2liv)
            ]
            );
        $regole[] = $nav_li_ul_li_ul_a;
        
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
        $regole[] = $nav_li_ul_li_hover_ul;
        
        $nav_fisso_li_ul_li_hover_ul= new RegolaCSS(
            'nav#fisso li > ul > li:hover > ul',
            [
                new DichiarazioneCSS('position',Posizione::ASSOLUTA)
            ]
            );
        $regole[] = $nav_fisso_li_ul_li_hover_ul;
        
        
        
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
        $regole[] = $nav_ul_li_sub;
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
        $regole[] = $nav_ul_li_sub_li_sub;
        
        
        return new SchermoCSS($regole, 0, 800);
    }
    
    private function miniSchermo(){
        $regole = array();
        $logo = new RegolaCSS(
            '.logo',
            [
                new DichiarazioneCSS(' display', 'inline-block'),
                new DichiarazioneCSS('background-color',$this->coloreTesto),
                new DichiarazioneCSS('cursor', 'pointer')
            ]
            );
        $regole[] = $logo;
        /*
           .bar1, .bar2, .bar3 {
              width: 35px;
              height: 5px;
              background-color: #333;
              margin: 6px 0;
              transition: 0.4s;
            }
         */
        $barre_logo = new RegolaCSS(
            '.barra1, .barra2, .barra3',
            [
                new DichiarazioneCSS('width', '40px'),
                new DichiarazioneCSS('height', '7px'),
                new DichiarazioneCSS('background-color', $this->coloreSfondo),
                new DichiarazioneCSS('margin', '7px 6px 0 6px'),
                new DichiarazioneCSS('transition', '0.4s')
            ]
            );
        $regole[] = $barre_logo;
        /*
            .attiva .barra1 {
              -webkit-transform: rotate(-45deg) translate(-10px, 10px);
              transform: rotate(-45deg) translate(-10px, 10px);
            }
         */
        $barre_1 = new RegolaCSS(
            '.attiva .barra1',
            [
                new DichiarazioneCSS('-webkit-transform', 'rotate(-45deg) translate(-10px, 10px)'),
                new DichiarazioneCSS('transform', 'rotate(-45deg) translate(-10px, 10px)')
            ]
            );
        $regole[] = $barre_1;
        /*
         .attiva .barra2 {opacity: 0;}
        */
        $barre_2 = new RegolaCSS(
            '.attiva .barra2',
            [
                new DichiarazioneCSS('opacity', '0')
            ]
            );
        $regole[] = $barre_2;
        /*
         .attiva .barra3 {
         -webkit-transform: rotate(45deg) translate(-10px, -10px);
         transform: rotate(45deg) translate(-10px, -10px);
         }
         */
        $barre_3 = new RegolaCSS(
            '.attiva .barra3',
            [
                new DichiarazioneCSS('-webkit-transform', 'rotate(45deg) translate(-10px, -10px)'),
                new DichiarazioneCSS('transform', 'rotate(45deg) translate(-10px, -10px)')
            ]
            );
        $regole[] = $barre_3;
       
        
        
        $nav_ul= new RegolaCSS(
            'nav > ul',
            [
                new DichiarazioneCSS('background-color',$this->coloreSfondo),
                new DichiarazioneCSS('display','none')
                
            ]
            );
        $regole[] = $nav_ul;
        
        
        // colore voce selezionata
        $nav_li_hover = new RegolaCSS(
            'nav li:hover:not(.sub)',
            [
                new DichiarazioneCSS('background-color',$this->coloreSeleziona)
            ]
            );
        $regole[] = $nav_li_hover;
        
        $nav_ul_li = new RegolaCSS(
            'nav ul li',
            [
                new DichiarazioneCSS('margin','0'),
                new DichiarazioneCSS('display','block'),
                new DichiarazioneCSS('list-style-type','none'),
                new DichiarazioneCSS('box-shadow', '3px 5px 2px rgba(0,0,0,0.3)')
            ]
            );
        $regole[] = $nav_ul_li;
        
        
        $nav_li_ul = new RegolaCSS(
            'nav li > ul',
            [
                new DichiarazioneCSS('display','none'),
                new DichiarazioneCSS('background-color',$this->coloreSfondoVoce),
                new DichiarazioneCSS('margin-top','1px')
            ]
            );
        $regole[] = $nav_li_ul;
        
        
        /*
         nav li > ul > li ul  {
         display: none;
         background-color: #888;
         }*/
          // colora voci menu 3 livello
        $nav_li_ul_li_ul= new RegolaCSS(
            'nav li > ul > li ul',
            [
                new DichiarazioneCSS('background-color',$this->coloreSfondoVoce2liv)
                
            ]
            );
        $regole[] = $nav_li_ul_li_ul;
        
        /*
         nav li > ul > li ul a{
         color: #888;
         }*/
          // colora voci menu 3 livello
        $nav_li_ul_li_ul_a= new RegolaCSS(
            'nav li > ul > li ul a',
            [
                new DichiarazioneCSS('color',$this->coloreTestoVoce2liv)
            ]
            );
        $regole[] = $nav_li_ul_li_ul_a;
        
        /*
         Visualizza feccia verticale
         */
        $nav_ul_li_sub= new RegolaCSS(
            'nav ul li.sub',
            [
                new DichiarazioneCSS('background-image','url(LibreriaQx7-php/freccia_verticale.png)'),
                new DichiarazioneCSS('background-repeat','no-repeat'),
                new DichiarazioneCSS('background-position','95% 10%'),
                new DichiarazioneCSS('background-size','30px 30px')
            ]
            );
        $regole[] = $nav_ul_li_sub;
        /*
        $blocca_evento_click= new RegolaCSS(
            'li.sub > a',
            [
                new DichiarazioneCSS("pointer-events","none"),
                new DichiarazioneCSS("cursor","default")
            ]
            );
        $regole[] = $blocca_evento_click;
        */
        return new SchermoCSS($regole, 800);
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
    
    private function disegnaLogoMiniMenu(){
        if(!($this instanceof Menu)){
            $logoMiniMenu = new Pannello('50px', '50px');
            $logoMiniMenu->aggiungi(new Attributo('class', 'logo'));
            
            //$logoMiniMenu->aggiungi(new Attributo('onclick', 'attivaMenu(this)'));
            
            $barra1 = new Pannello(null, null);
            $barra1->aggiungi(new Attributo('class', 'barra1'));
            $logoMiniMenu->aggiungi($barra1);
            $barra2 = new Pannello(null, null);
            $barra2->aggiungi(new Attributo('class', 'barra2'));
            $logoMiniMenu->aggiungi($barra2);
            $barra3 = new Pannello(null, null);
            $barra3->aggiungi(new Attributo('class', 'barra3'));
            $logoMiniMenu->aggiungi($barra3);
            
            $logoMiniMenu->affianca(Lato::DESTRA);
            $this->contenuto .= $logoMiniMenu->vedi();
        }
    }
    /**
     * Aggiungi comportamento mini-menu
     */
    protected  function azioneMiniMenu(){
        $this->aggiungi(new JQuery(
            
             /* Chiudi il menu quando si scorre la pagina verso il basso*/
            'var su = false;'.
            'var yUltimo = 0;'.
            '$(window).scroll(function(){'.
                'var y = $(window).scrollTop();'.
                'if(y > yUltimo){'.
                    'if(su){'.
                        'su=false;'.
                        'if($(".attiva").height() > 0){'.
                            
                            '$(".logo").toggleClass("attiva");'.
                            'if($(".sub ul").css("display") !== "block"){'.
                                '$(".sub ul").css("display","block")'.
                            '}else{'.
                                '$(".sub ul").css("display","none")'.
                            '}'.
                       '}'.
                    '}'.
                '}else{'.
                    'if(!su){'.
                        'su=true;'.
                    '}'.
                '}'.
                'yUltimo=y;'.
            '});'.
            
            /*Apri e chiudi il menu con il clic sul logo del menu (a destra della barra)*/
            '$(".logo").on("click", '.
            'function(){'.
            '$(this).toggleClass("attiva");'.
                   'if($(".sub ul").css("display") !== "block"){'.
                        '$(".sub ul").css("display","block");'.
                    '}else{'.
                        '$(".sub ul").css("display","none")'.
                        
                    '}'.
            '});'
         
            ));
    }

    /**
     * Aggiorna la paginia al cambiamento delle dimensioni
     */
    protected  function cambiaDimensioneMenu(){
        $this->aggiungi(new JQuery(
            "$(window).resize(function(){location.reload();});"
            ));
    }
    
    
    /**
     * Permette un azione di tipo 'sticky' sul menu scorrevole.
     * 
     * @param string $idNoteLaterali    eventuale ID di un altro elemento 'stick'
     */
    public function azioneStickyMenu($idNoteLaterali='-non-presente-'){
        return new JQuery(
                
                "window.onscroll=function(){stickyMenu()};".
                "var menu=document.getElementById(\"mobile\");".
                "var indice=document.getElementById(\"".$idNoteLaterali."\");".
                "var sticky=menu.offsetTop;".
                "var yPagina=menu.window.pageYOffset;".
                "function stickyMenu(){".
                    "if(window.pageYOffset < sticky){".
                        "menu.id='mobile';".//scorri menu sulla pagina
                        "indice.style.position='static';".//scorri indice pagine
                    "} else {".
                        "menu.id='fisso';". //incolla menu quando il titolo sito scompare
                        "indice.style.position='fixed';".//incolla indice pagine
                        "var y = window.pageYOffset;".
                        "if (yPagina > y) {".
                            "menu.style.top='0';". //comparsa menu quando scorre verso l'alto
                        "}else{".
                            "menu.style.top='-100px';".//scomparsa menu quando scorre verso il basso
                            
                        "}".
                        "yPagina=y;".
                    "}".
                "}"
            );
    }

    /**
     * {@inheritDoc}
     * @see Tag::__toString()
     */
    public function __toString(){
        if(count($this->menu) > 0){
            
            parent::aggiungi(new Attributo('class','sub')); 
            
            self::disegnaLogoMiniMenu();
            
            $lista = new Tag('ul');
            foreach ($this->menu as  $voce) {
                $lista->aggiungi($voce);
            }
            $this->contenuto .= $lista->vedi();
            
            if($this->nome == 'nav'){// impedisce di riscrivere lo script negli elementi derivati (Menu)
                
                self::azioneMiniMenu();
                self::cambiaDimensioneMenu();
            }
            
        }
        return parent::__toString();
    }
    
}