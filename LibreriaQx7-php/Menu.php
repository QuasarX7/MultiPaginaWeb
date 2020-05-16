<?php

include_once 'BarraMenu.php';
include_once 'Oggetto.php';




class Menu extends BarraMenu{
    
    private  static $nuovoID=0;
    protected $id;
    protected $etichetta;
    protected $link;
    
    
    /**
     * 
     * @param string $nome
     * @param string $link
     */
    public function __construct(string $nome,string $link){
        $this->id=self::$nuovoID++;
        $this->livello = 1;
        $this->genitore = null;
        $this->etichetta = $nome;
        $this->link = $link;
        $this->nome = 'li';
        $this->contenuto = ' ';
    }
    
    
    public function nome() {
        return $this->etichetta;
    }
    
    public function link() {
        return $this->etichetta;
    }

    /**
     * {@inheritDoc}
     * @see BarraMenu::creaAreaMenu()
     */
    protected function creaAreaMenu(){
        /*            [area menu]
         * 
         <div class="dropdown-menu aria-labelledby="navbarDropdown3"" >
            <a class="dropdown-item" href="#">Action</a>
            .....
         </div>
         */
        $areaMenu = new Tag("div",[
            new Attributo('class','dropdown-menu'),
            new Attributo('aria-labelledby','navbarDropdown'.$this->id)
        ]);
        $areaMenu->aggiungi(new Stile('background-color',self::$coloreSfondoVoce));
        foreach ($this->menu as  $voce) {
            $voce->livello = 2;
            $areaMenu->aggiungi($voce);
        }
        
        if($this->livello == 1){
            $this->aggiungi($areaMenu);
            
        }elseif($this->livello == 2){
            $areaMenu->aggiungi(new Attributo('style','top:-35px'));
            $areaMenu->aggiungi(new Stile('background-color',self::$coloreSfondoVoce2liv));
            
            /*
             <div class="nav-item dropright" >
                      [area menu]
                
              </div>
             */
            $area = new Tag("div",[
                new Attributo('class','nav-item dropright')
            ]);
            
            $area->aggiungi($areaMenu);
            $this->aggiungi($area);
        }
    }
    
    
    /**
     * Metodo che crea la voce menu
     */
    protected function costruisciVoceMenu(){
        $this->aggiungi(new Stile('position', 'relative'));//permette di allineare le voci del menu
        // menu 1 livello
        if($this->livello == 1){
            if($this->nessunaVoce()){
                //$this->colora($this,$this->coloreSfondoVoce,$this->coloreSfondoVoce);
                /*
                 *  <li class="nav-item">
                 *          <a class="nav-link" href=".....">Home</a>
                 *  </li>
                 */
                $riferimento = new Tag('a',[new Attributo("class", "nav-link"),new Attributo('href', $this->link.'')],$this->etichetta);
                $riferimento->aggiungi(new Attributo('stylepseudo' , ':hover {background-color:'.self::$coloreSeleziona.'}'));
                $riferimento->aggiungi(new Stile('color',self::$coloreTesto));
                
                if($this->id == 0){
                    //se primo elemento
                    /*
                     * <li class="nav-item active"> [1]
                     *      <a class="nav-link" href="#">
                     *          Home 
                     *          <span class="sr-only">(current)</span> [2]
                     *      </a>
                     * </li>
                     */
                    $riferimento->aggiungi(new Attributo('active','')); //[1]
                    $riferimento->aggiungi(new Tag("span",[new Attributo("class", "sr-only")],'(current)'));
                }
                $this->aggiungi($riferimento);
                $this->aggiungi(new Attributo("class", "nav-item"));
            }else{
                /*
                 *  <li class="nav-item dropdown">   [1]
                 *      <a class="nav-link dropdown-toggle"  [2]
                 *         href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                 *         aria-expanded="false">
                 *              nome_voce
                 *         </a>
                 * </li>
                 */
                
                // [1]
                $this->aggiungi(new Attributo("calss", "nav-item dropdown"));
                // [2]
                $riferimento = new Tag('a',
                    [
                        new Attributo("class", "nav-link dropdown-toggle"),
                        new Attributo('href','#'),
                        new Attributo('role','button'),
                        new Attributo('data-toggle','dropdown'),
                        new Attributo('aria-haspopup','true'),
                        new Attributo('aria-expanded','false'),
                        new Attributo('id','navbarDropdown'.$this->id)
                        
                    ],
                    $this->etichetta
                    );
                $riferimento->aggiungi(new Stile('color',self::$coloreTesto));
                $this->aggiungi($riferimento);
            }
        }elseif($this->livello == 2){
            $this->aggiungi(new Stile('color',self::$coloreTestoVoce));
            // menu 2° livello
            $this->nome = 'a';//<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            if(is_string($this->etichetta) && is_string($this->link)){
                if($this->nessunaVoce()){
                    //<a class="dropdown-item" href="....">Action</a>
                    $this->aggiungi(new Attributo("class", "dropdown-item"));
                    $this->aggiungi(new Attributo("href", $this->link));
                    $this->aggiungi($this->etichetta);
                }else{
                    /*
                     * <a class="nav-link dropright-toggle" id='navbarDropdown_0' href="#" role="button" data-toggle="dropdown"
                     *          aria-haspopup="true" aria-expanded="false" >Voce_menu</a>
                     */
                    $this->nome = 'a';
                    $this->aggiungi(new Attributo("id", 'navbarDropdown_'.$this->id));
                    $this->aggiungi(new Attributo("class", "nav-link dropright-toggle"));
                    $this->aggiungi(new Attributo("href", '#'));
                    $this->aggiungi(new Attributo("role", 'button'));
                    $this->aggiungi(new Attributo('data-toggle','dropdown'));
                    $this->aggiungi(new Attributo('aria-haspopup','true'));
                    $this->aggiungi(new Attributo('aria-expanded','false'));
                    $this->aggiungi(new Stile([
                        new DichiarazioneCSS('padding-top', '0'),
                        new DichiarazioneCSS('padding-bottom', '0'),
                        new DichiarazioneCSS('padding-left', '20px')
                    ]));
                    $frecciaDX = new Tag('span',[new Attributo('style', 'float:right;')],'►');
                    $this->aggiungi($this->etichetta . $frecciaDX);
                }
            }
        }
        
    }
    
    
    /**
     * {@inheritDoc}
     * @see BarraMenu::__toString()
     */
    public function __toString(){
        self::costruisciVoceMenu();
        return parent::__toString();
    }
    
    
    
}






