<?php

include_once 'LibreriaQx7-php/Tag.php';
include_once 'LibreriaQx7-php/Attributo.php';
include_once 'LibreriaQx7-php/CSS.php';
include_once 'LibreriaQx7-php/javascript.php';

class PaginaCerca extends Tag{
    
    const FONT_CAMPO     = 'campo';
    const FONT_ETICHETTA = 'etichetta';
    const FILE_FONT_CAMPO     = 'Struttura/FrederickatheGreat-Regular.ttf';
    const FILE_FONT_ETICHETTA = 'Struttura/Anton-Regular.ttf';
    
    
    private $tabella;
    private $colonna;
    
    private $riga;
    
    const INPUT_AJAX = 'riga';
    
    const CLASSE_RIGA = 'riga';
    
    const ID_PAGINA = 'ricerca';
    /**
     * Crea un Tag di tipo 'div' con id = ID_PAGINA e caria i dati da una base di dati MySQL
     * 
     * @param array $tabella
     * @param string $colonnaRicerca
     * @param array $info   contiene le informazioni sul tipo di campo
     */
    public function __construct(array $tabella,int $colonnaRicerca,array $info){
         $this->colonna = $colonnaRicerca;
        $this->tabella = $tabella;
        parent::__construct('div',[new Attributo('id', self::ID_PAGINA)]);
        self::css();
        self::creaCampo();
        self::script($info);
        self::menu();
        
    }
    
    /**
     * determina il tipo di campo input html, in funzione del tipo di dato mysql.
     * 
     * @param string $valore
     * @return string
     */
    private function tipo(string $valore){
        switch ($valore) {
            case 'tinyint':case 'smallint':case 'mediumint':case 'int':case 'bigint': 
            case 'float':case 'double': case 'decimal':
                return 'number';
                
            case 'bool':
            case 'boolean': 
                return 'checkbox';
                
            case 'varchar':
            case 'text': return 'text';
            
            case 'date': return 'date';
            case 'time': return 'time';
            case 'datetime':case 'timestamp':
                 return 'datetime-local';
            
            default:
                return 'text';
        }
    }
    
    private function creaCampiRicerca($info){
        $codice = '';
        $i=0;
        if (is_array($info)) {
            foreach ($info as $record) {
                if($i++ == $this->colonna)continue;
                $nome = $record[0];
                $tipo = $record[1];
                
                
                $riga = new Tag('div',[new Attributo('class', self::CLASSE_RIGA)]);
                
                $etichetta = new Tag('label',[new Attributo('for', $nome.'')],$nome.': ');
                $riga->aggiungi($etichetta);
                
                
                $campo = new Tag(
                    'input',
                    [
                        new Attributo('id', $nome . ''),
                        new Attributo('type', self::tipo($tipo)),
                        new Attributo('readonly','readonly')
                    ]
                    );
                
                $riga->aggiungi($campo);
                $codice .= $riga->vedi();
                
            }
        }
        return $codice;
    }
    
    /**
     * Crea una variabile JavaScript che contiene i dati array bidimensionale (tabbella) in php. 
     * @param array $tabella
     * @return string
     */
    private function creaTabella(){
        $codice  = 'var tabella = ['; // inizio
        $primo = true;
        foreach ($this->tabella as $riga) {
            if($primo != true){
                $codice .= ', ';
            }
            $codice .= '[';
            for($i=0; $i < count($riga); $i++){
                if($i != 0) $codice .= ', ';
                $cella = str_replace('"', '“', $riga[$i]); //... « “ » è un carattere UNICODE diverso da « " »
                $codice .= '"'.strip_tags($cella).'"';
            }
            $codice .= ']';
            $primo = false;
        }
        $codice .= '];'; //fine
        return $codice;
    }
    
    private function inizializzaCampiRicerca($info){
        
    }
    
    private function script($info){
        
        $script = new JavaScript(
            $this->creaTabella($this->tabella).// var tabella = [ [.., .., ..], [.., .., ..], ... ];
            '$(\'button.icona\').on(\'click\',function(e){'.
                'var valoreCampo = $(\'#cerca\').val();'.
                'var riga;'.
                '$(".'.self::CLASSE_RIGA.'").remove();'.
                'for(riga of tabella){'.
                    'if(riga['.$this->colonna.'] == valoreCampo){'.
                        '$(".contenitore").append(\''.
                            self::creaCampiRicerca($info).
                        '\');'.
                        'break;'.
                    '}'.
                    'riga = null;'.
                '}'.
                'if(riga){'.
                    'var valore;var i=0;'.
                    'for(valore of riga){'.
                        'if(i++ == '.$this->colonna .')continue;'.
                        '$(".contenitore input").eq(i-1).val(valore);'.
                    '}'.
                '}else alert("Voce non presente...");'.
            '});'
            );
        self::aggiungi($script);
    }
    
    private function css() {

        $stile = new Tag('style',[new Attributo('type', 'text/css')]);
        $stile->aggiungi("@font-face {font-family: '".self::FONT_CAMPO."';src: url('".self::FILE_FONT_CAMPO."') format('truetype');}");
        $stile->aggiungi("@font-face {font-family: '".self::FONT_ETICHETTA."';src: url('".self::FILE_FONT_ETICHETTA."') format('truetype');}");
        
        $input = new RegolaCSS(
            'input',
            [
                new DichiarazioneCSS('width','100%'),
                new DichiarazioneCSS('height','50px'),
                new DichiarazioneCSS('border','none'),
                new DichiarazioneCSS('font-family',"'".self::FONT_CAMPO."', cursive"),
                new DichiarazioneCSS('font-size',"30px")
            ]
            );
        $stile->aggiungi($input.'');
        
        $etichetta = new RegolaCSS(
            'label',
            [
                new DichiarazioneCSS('font-family',"'".self::FONT_ETICHETTA."', cursive"),
                new DichiarazioneCSS('font-size',"18px")
            ]
            );
        $stile->aggiungi($etichetta.'');
        
        self::coloraInfoCampo($stile, ':-webkit-input-placeholder'); 
        self::coloraInfoCampo($stile, '-moz-placeholder'); // Firefox 18
        self::coloraInfoCampo($stile, ':-moz-placeholder');// Firefox 19+ 
        self::coloraInfoCampo($stile, '-ms-input-placeholder');
 
        $riga = new RegolaCSS(
            '.'.self::CLASSE_RIGA,
            [
                new DichiarazioneCSS('width','auto'),
                new DichiarazioneCSS('height','auto'),
                new DichiarazioneCSS('padding','20px')
            ]
            );
        $stile->aggiungi($riga.'');
        
        $rigaSelezionata = new RegolaCSS(
            '.'.self::CLASSE_RIGA.':hover',
            [
                new DichiarazioneCSS('background','#ddd')
            ]
            );
        $stile->aggiungi($rigaSelezionata.'');
        
        $campo = new RegolaCSS(
            '#cerca',
            [
                new DichiarazioneCSS('width','90%'),
                new DichiarazioneCSS('height','50px'),
                new DichiarazioneCSS('background','#ddd'),
                new DichiarazioneCSS('border','none'),
                new DichiarazioneCSS('float','left'),
                new DichiarazioneCSS('color','black'),
                new DichiarazioneCSS('padding-left','15px'),
                new DichiarazioneCSS('-webkit-border-radius','5px'),
                new DichiarazioneCSS('-moz-border-radius','5px'),
                new DichiarazioneCSS('border-radius','5px')
            ]
            );
        $stile->aggiungi($campo.'');
        
        $pulsanteAttivato = new RegolaCSS(
            '.contenitore:hover button.icona, .contenitore:active button.icona, .contenitore:focus button.icona',
            [
                new DichiarazioneCSS('outline','none'),
                new DichiarazioneCSS('opacity','1'),
                new DichiarazioneCSS('margin-left','-50px')
            ]
            );
        $stile->aggiungi($pulsanteAttivato.'');
        
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
        foreach ($this->tabella as $riga) {
            self::voceMenu($lista, $riga[$this->colonna]);
        }
        self::aggiungi($lista);
    }
    
    private function voceMenu(Tag $menu,string $valore) {
        $voce = new Tag('option',[new Attributo('value', $valore)]);
        $menu->aggiungi($voce);
    }
    
}