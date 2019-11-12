<?php

include_once 'LibreriaQx7-php/Tag.php';
include_once 'LibreriaQx7-php/Attributo.php';
include_once 'LibreriaQx7-php/CSS.php';

class PaginaCerca extends Tag{
    
    const ID_PAGINA = 'ricerca';
    /**
     * Crea un Tag di tipo 'div' con id = ID_PAGINA.
     * {@inheritDoc}
     * @see Tag::__construct()
     */
    public function __construct(){
        parent::__construct('div',[new Attributo('id', self::ID_PAGINA)]);
        self::css();
        self::creaCampo();
        self::menu();
        
    }
    
    private function css() {

        $stile = new Tag('style',[new Attributo('type', 'text/css')]);
    
        self::coloraInfoCampo($stile, ':-webkit-input-placeholder'); 
        self::coloraInfoCampo($stile, '-moz-placeholder'); // Firefox 18
        self::coloraInfoCampo($stile, ':-moz-placeholder');// Firefox 19+ 
        self::coloraInfoCampo($stile, '-ms-input-placeholder');
 
        
        $campo = new RegolaCSS(
            '#cerca',
            [
                new DichiarazioneCSS('width','60%'),
                new DichiarazioneCSS('height','50px'),
                new DichiarazioneCSS('background','#ddd'),
                new DichiarazioneCSS('border','none'),
                new DichiarazioneCSS('font-size','10pt'),
                new DichiarazioneCSS('float','left'),
                new DichiarazioneCSS('color','black'),
                new DichiarazioneCSS('padding-left','15px'),
                new DichiarazioneCSS('-webkit-border-radius','5px'),
                new DichiarazioneCSS('-moz-border-radius','5px'),
                new DichiarazioneCSS('border-radius','5px')
            ]
            );
        $stile->aggiungi($campo.'');
        
        $contenitore = new RegolaCSS(
            '.contenitore:hover button.icona, .contenitore:active button.icona, .contenitore:focus button.icona',
            [
                new DichiarazioneCSS('outline','none'),
                new DichiarazioneCSS('opacity','1'),
                new DichiarazioneCSS('margin-left','-50px')
            ]
            );
        $stile->aggiungi($contenitore.'');
        
        $pulsante = new RegolaCSS(
            '.icona',
            [
                new DichiarazioneCSS('-webkit-border-top-right-radius','5px'),
                new DichiarazioneCSS('-webkit-border-bottom-right-radiu','5px'),
                new DichiarazioneCSS('-moz-border-radius-topright','5px'),
                new DichiarazioneCSS('-moz-border-radius-bottomright','5px'),
                new DichiarazioneCSS('border-top-right-radius','5px'),
                new DichiarazioneCSS('border-bottom-right-radius','5px'),
                
                new DichiarazioneCSS('border','none'),
                new DichiarazioneCSS('background','#232833'),
                new DichiarazioneCSS('height','50px'),
                new DichiarazioneCSS('width','50px'),
                new DichiarazioneCSS('color','#4f5b66'),
                new DichiarazioneCSS('opacity','0'),
                new DichiarazioneCSS('font-size','10pt'),
                
                new DichiarazioneCSS('webkit-transition',' all .55s ease'),
                new DichiarazioneCSS('-moz-transition',' all .55s ease'),
                new DichiarazioneCSS('-ms-transition','all .55s ease'),
                new DichiarazioneCSS('-o-transition','all .55s ease'),
                new DichiarazioneCSS('transition','all .55s ease'),
                
                
                new DichiarazioneCSS('background-size','contain'),
                new DichiarazioneCSS('background-repeat','no-repeat'),
                new DichiarazioneCSS('background-position','center center'),
                new DichiarazioneCSS('background-image',"url('Struttura/cerca.png')")
            ]
            );
        $stile->aggiungi($pulsante.'');
        
        self::aggiungi($stile->vedi());
    }
    
    private function coloraInfoCampo(Tag $stile, string $valore){
        $colore = new DichiarazioneCSS('color',' #65737e');
        $campo = new RegolaCSS('.contenitore input#cerca:'.$valore,[$colore]);
        $stile->aggiungi($campo.'');
    }
    
    private function creaCampo() {
        $contenitore = new Tag('div',[new Attributo('class', 'contenitore')]);
        $campo = new Tag(
            'input',
            [
                new Attributo('type', 'search'),
                new Attributo('id', 'cerca'),
                new Attributo('placeholder', 'cerca...'),
                new Attributo('autocomplete', 'off'),
                new Attributo('list', 'input_campo')
            ]
            );
        $contenitore->aggiungi($campo);
        
        $pulsante = new Tag('button',[new Attributo('class', 'icona')]);
        
        $contenitore->aggiungi($pulsante);
        
        
        self::aggiungi($contenitore);
        
    }
    
    private function menu() {
        $lista = new Tag('datalist',[new Attributo('id', 'input_campo')]);
        self::voceMenu($lista, 'ciaoooo');
        self::aggiungi($lista);
    }
    
    private function voceMenu(Tag $menu,string $valore) {
        $voce = new Tag('option',[new Attributo('value', $valore)]);
        $menu->aggiungi($voce);
    }
    
}