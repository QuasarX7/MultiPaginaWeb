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
        
        
        $css[] = self::maxSchermo();
        $css[] = self::miniSchermo();
        return $css;
        
    }
    
    private function maxSchermo(){
        $regole = array();
        $logo = new RegolaCSS(
            '.logo',
            [
                new DichiarazioneCSS(' display', 'none')
            ]
            );
        $regole[] = $logo;
        
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
        $regole[] = $nav;
        
        $nav_fisso = new RegolaCSS(
            'nav#fisso',
            [
                new DichiarazioneCSS('position',Posizione::FISSA)
            ]
            );
        $regole[] = $nav_fisso;
        
        $nav_ul = new RegolaCSS(
            'nav ul',
            [
                new DichiarazioneCSS('margin','0'),
                new DichiarazioneCSS('padding','0'),
                new DichiarazioneCSS('list-style','none')
            ]
            );
        $regole[] = $nav_ul;
        
        // colora celle menu (primo livello)
        $regole[] = $this->stileCellaMenu('nav > ul > li > a', $this->coloreTesto);
        
        //colora celle menu (secondo livello)
        $regole[] = $this->stileCellaMenu('nav li > ul li a', $this->coloreTestoVoce);
        
        // colore voce selezionata
        $nav_li_hover = new RegolaCSS(
            'nav li:hover',
            [
                new DichiarazioneCSS('background-color',$this->coloreSeleziona)
            ]
            );
        $regole[] = $nav_li_hover;
        
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
        
        $nav_li_ul = new RegolaCSS(
            'nav li > ul',
            [
                new DichiarazioneCSS('display','none'),
                new DichiarazioneCSS('background-color',$this->coloreSfondoVoce),
                new DichiarazioneCSS('margin-top','1px')
            ]
            );
        $regole[] = $nav_li_ul;
        
        $nav_li_ul_li = new RegolaCSS(
            'nav li > ul li',
            [
                new DichiarazioneCSS('display','block'),
                new DichiarazioneCSS('width','max-content')
            ]
            );
        $regole[] = $nav_li_ul_li;
        
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
        
        // colora voci menu 3 livello
        $nav_li_ul_li_ul= new RegolaCSS(
            'nav li > ul > li ul',
            [
                new DichiarazioneCSS('background-color',$this->coloreSfondoVoce2liv),
                new DichiarazioneCSS('display','none')
                
            ]
            );
        $regole[] = $nav_li_ul_li_ul;
        
        $nav_li_ul_li_ul_a= new RegolaCSS(
            'nav li > ul > li ul a',
            [
                new DichiarazioneCSS('color',$this->coloreTestoVoce2liv)
            ]
            );
        $regole[] = $nav_li_ul_li_ul_a;
        
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
        
        $nav_ul_li_sub= new RegolaCSS(
            'nav ul > li.sub',
            [
                new DichiarazioneCSS('background','url(LibreriaQx7-php/freccia_verticale.png) right center no-repeat')
            ]
            );
        $regole[] = $nav_ul_li_sub;
        
        $nav_ul_li_sub_li_sub= new RegolaCSS(
            'nav ul > li.sub li.sub',
            [
                new DichiarazioneCSS('background','url(LibreriaQx7-php/freccia_orizzontale.png) right center no-repeat')
            ]
            );
        $regole[] = $nav_ul_li_sub_li_sub;
        
        return new SchermoCSS($regole, SchermoCSS::INDEFINITO, MultiPagina::LIMITE_PORTATILE+1); // più grande di un tablet
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
        
        $barre_logo = new RegolaCSS(
            '.barra1, .barra2, .barra3',
            [
                new DichiarazioneCSS('width', '4.0vmax'),
                new DichiarazioneCSS('height', '0.7vmax'),
                new DichiarazioneCSS('background-color', $this->coloreSfondo),
                new DichiarazioneCSS('margin', '0.7vmax 0.6vmax 0 0.6vmax'),
                new DichiarazioneCSS('transition', '0.4s')
            ]
            );
        $regole[] = $barre_logo;
        
        $barre_1 = new RegolaCSS(
            '.attiva .barra1',
            [
                new DichiarazioneCSS('-webkit-transform', 'rotate(-45deg) translate(-1vmax, 1vmax)'),
                new DichiarazioneCSS('transform', 'rotate(-45deg) translate(-1vmax, 1vmax)')
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
        
        $barre_3 = new RegolaCSS(
            '.attiva .barra3',
            [
                new DichiarazioneCSS('-webkit-transform', 'rotate(45deg) translate(-1vmax, -1vmax)'),
                new DichiarazioneCSS('transform', 'rotate(45deg) translate(-1vmax, -1vmax)')
            ]
            );
        $regole[] = $barre_3;
       
        $nav= new RegolaCSS(
            'nav',
            [
                new DichiarazioneCSS('background-color',$this->coloreSfondo),
                new DichiarazioneCSS('width','100%'),
                new DichiarazioneCSS('max-height', '100%'),
                new DichiarazioneCSS('overflow', 'auto'),
                new DichiarazioneCSS('display','block'),
                new DichiarazioneCSS('font-size','2.0vmax'),
                new DichiarazioneCSS('transition', "top 0.5s")//eventuale effetto...
                
                
            ]
            );
        $nav_fisso = new RegolaCSS(
            'nav#fisso',
            [
                new DichiarazioneCSS('position',Posizione::FISSA)
            ]
            );
        $regole[] = $nav_fisso;
        $nav_mobile = new RegolaCSS(
            'nav#mobile',
            [
                new DichiarazioneCSS('position',Posizione::STATICA)
            ]
            );
        $regole[] = $nav_mobile;
        
        $regole[] = $nav;
        
        $nav_ul= new RegolaCSS(
            'nav ul',
            [
                new DichiarazioneCSS('margin','0'),
                new DichiarazioneCSS('padding','0'),
                new DichiarazioneCSS('background-color',$this->coloreSfondo),
                new DichiarazioneCSS('display','none'),
                new DichiarazioneCSS('list-style-type','none')
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
        
        // voci 
   
        $li_a = new RegolaCSS(
            'nav li a',
            [
                new DichiarazioneCSS('display','block'),
                new DichiarazioneCSS('padding','10px'),
                new DichiarazioneCSS('color',$this->coloreTesto),
                new DichiarazioneCSS('text-decoration','none'),
                new DichiarazioneCSS('border-bottom', '1px solid #555')
            ]
            );
        $regole[] = $li_a;
        
        $nav_li_ul = new RegolaCSS(
            'nav li > ul',
            [
                new DichiarazioneCSS('background-color',$this->coloreSfondoVoce)
            ]
            );
        $regole[] = $nav_li_ul;
        
        $nav_li_ul_li_ul= new RegolaCSS(
            'nav li > ul > li ul',
            [
                new DichiarazioneCSS('background-color',$this->coloreSfondoVoce2liv)
            ]
            );
        $regole[] = $nav_li_ul_li_ul;
        
        //Visualizza feccia verticale
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
        
        return new SchermoCSS($regole, MultiPagina::LIMITE_PORTATILE);
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
            $logoMiniMenu = new Pannello('5.0vmax', '5.0vmax');
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
                    // blocca scoll body
                    'if($("body").css("overflow") !== "hidden"){'.
                        '$("body").css("overflow","hidden");'.
                    '}else{'.
                        '$("body").css("overflow","visible");'.
                    '}'.
                '}'.
            ');'
            
            
         
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