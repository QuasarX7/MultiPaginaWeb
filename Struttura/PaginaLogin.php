<?php

include_once 'LibreriaQx7-php/Tag.php';
include_once 'LibreriaQx7-php/Attributo.php';
include_once 'LibreriaQx7-php/CSS.php';
include_once 'LibreriaQx7-php/javascript.php';

class PaginaLogin extends Tag{
    
    const CHIAVE_UTENTE = 'utente';
    const CHIAVE_PASSWORD = '_p4ssw0rd';
    
    const ID_PAGINA = 'accesso';
    
    const FILE_FONT_CAMPO     = 'Struttura/FrederickatheGreat-Regular.ttf';
    const FILE_FONT_ETICHETTA = 'Struttura/Anton-Regular.ttf';
    const FONT_CAMPO     = 'campo';
    const FONT_ETICHETTA = 'etichetta';
    
    public function __construct(){
        parent::__construct('form',[new Attributo('id', self::ID_PAGINA),new Attributo('method', 'post'),new Attributo('action', '')]);
        self::creaForm();
        self::css();
    }
      
    protected function creaForm() {
        $idNome = self::CHIAVE_UTENTE;
        $riga1 = new Tag('p');
        $etichettaNome = new Tag('label',[new Attributo('for', $idNome)],'Nome: ');
        $riga1->aggiungi($etichettaNome);
        $utente = new Tag('input',[new Attributo('id', $idNome),new Attributo('name', $idNome),new Attributo('type', 'field')]);
        $riga1->aggiungi($utente);
        $this->aggiungi($riga1);
        
        $idPassword = self::CHIAVE_PASSWORD;
        $riga2 = new Tag('p');
        $etichettaPass = new Tag('label',[new Attributo('for', $idPassword)],'Password: ');
        $riga2->aggiungi($etichettaPass);
        $password = new Tag('input',[new Attributo('id', $idPassword),new Attributo('name', $idPassword),new Attributo('type', 'password')]);
        $riga2->aggiungi($password);
        $this->aggiungi($riga2);
        
        $riga3 = new Tag('p');
        $pulsante = new Tag('input',[new Attributo('type', 'submit')]);
        $riga3->aggiungi($pulsante);
        $this->aggiungi($riga3);
    }
    
    protected function css() {
        
        $stile = new Tag('style',[new Attributo('type', 'text/css')]);
        $stile->aggiungi("@font-face {font-family: '".self::FONT_CAMPO."';src: url('".self::FILE_FONT_CAMPO."') format('truetype');}");
        $stile->aggiungi("@font-face {font-family: '".self::FONT_ETICHETTA."';src: url('".self::FILE_FONT_ETICHETTA."') format('truetype');}");
        
        $form = new RegolaCSS(
            'form',
            [
                new DichiarazioneCSS('background-color','#eee'),
            ]
            );
        
        $stile->aggiungi($form.'');
        
        $input = new RegolaCSS(
            'input',
            [
                
                new DichiarazioneCSS('border-style','ridge'),
                new DichiarazioneCSS('margin','10px'),
                new DichiarazioneCSS('padding-left','10px'),
                new DichiarazioneCSS('width','95%'),
                new DichiarazioneCSS('height','50px'),
                new DichiarazioneCSS('font-family',"'".self::FONT_CAMPO."', cursive"),
                new DichiarazioneCSS('font-size',"30px")
            ]
            );
        $stile->aggiungi($input.'');
        
        $etichetta = new RegolaCSS(
            'label',
            [
                new DichiarazioneCSS('margin','10px'),
                new DichiarazioneCSS('font-family',"'".self::FONT_ETICHETTA."', cursive"),
                new DichiarazioneCSS('font-size',"18px")
            ]
            );
        $stile->aggiungi($etichetta.'');
        
        $this->aggiungi($stile);
  
    }
    
}