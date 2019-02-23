<?php

include_once 'BarraMenu.php';
include_once 'Oggetto.php';

class Menu extends BarraMenu{
    
    
    /**
     * 
     * @param string $nome
     * @param string $link
     */
    public function __construct($nome,$link){
        $this->nome = 'li';
        $this->contenuto = ' ';
        if(is_string($nome) && is_string($link)){
            $riferimento = new Tag('a',[new Attributo('href', $link.'')],$nome);
            $this->aggiungi($riferimento);
        }
    }

    /**
     * {@inheritDoc}
     * @see Tag::__toString()
     *
    public function __toString(){
        
        return Tag::__toString();
    }
    */
    
    
    
    
    
    
 
    
}