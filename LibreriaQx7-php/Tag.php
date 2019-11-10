<?php
include_once 'Oggetto.php';


/**
 * Classe che implementa un tag HTML.
 * 
 * @author Dott. Domenico della PERUTA
 */
class Tag extends Oggetto{
    
    protected $nome;
    
    /**
     * Costruttore di un Tag HTML.
     * <pre><code>
     * $tag = new Tag('br');
     * tag->crea(); // <br>
     * 
     * $tag = new Tag('title','Questo è un titolo');
     * tag->crea(); // <title>Questo è un titolo</title>
     * 
     * $attr = array(new Attributo("charset","UTF-8"));
     * $tag = new Tag('meta',attr);
     * tag->crea(); // <meta charset="UTF-8">
     * 
     * $attr = array(new Attributo("charset","UTF-8"));
     * $tag = new Tag('meta',attr);
     * tag->crea(); // <meta charset="UTF-8">
     * 
     * $tag = new Tag(
     *          'a',
     *          [new Attributo("title","testo in aiuto")],
     *          "Etichetta collegamento"
     * );
     * tag->crea();//<a href="URL" title="testo in aiuto">Etichetta collegamento</a>
     * 
     * </code></pre>
     */
    function __construct() {
        // primo argomento (stringa)
        if(func_num_args() > 0){
            $nome = func_get_arg(0);
            if(is_string($nome)){
                $this->nome = Tag::controllo($nome,10);
            }
        }
        // secondo argomento (stringa, array di attributi, attributo o tag)
        if (func_num_args() > 1 && !is_null($this->nome)) {
            
            if (is_string(func_get_arg(1))) {
                $this->contenuto = func_get_arg(1);
                
            }elseif (is_array(func_get_arg(1))) {
                $this->attributi = func_get_arg(1);
                
            }elseif (func_get_arg(1) instanceof Tag) {
                $this->contenuto = func_get_arg(1).'';
                
            }elseif (func_get_arg(1) instanceof Attributo) {
                $this->attributi[func_get_arg(1)->nome()] = func_get_arg(1);
            }
        }  
        // terzo argomento (stringa o Tag)
        if (func_num_args() > 2 && !is_null($this->nome) && is_array($this->attributi)) {
            if (is_string(func_get_arg(2))) {
                $this->contenuto = func_get_arg(2);
            }elseif (func_get_arg(2) instanceof Tag) {
                $this->contenuto = func_get_arg(2) . '';
            }
        }
    }

    
        
    public function __toString() {
        $tag = '';
        if(strlen($this->nome) > 0){
            $attributi = '';
            if (is_array($this->attributi)) {
                foreach ($this->attributi as $attributo) {
                    if ($attributo instanceof Attributo) {
                        $attributi .= ' ' . $attributo;
                    }
                }
            }
            $tag .= '<'. $this->nome . $attributi .'>';
            
            if (strlen($this->contenuto) > 0) {
                $tag .= $this->contenuto . '</' . $this->nome . '>';
            }
        }
        return $tag;
    }
    
    /**
     * Controlla che la corretta lunghezza e formattazione (con caratteri italiani) della stringa.
     * 
     * @param string $stringa
     * @param int $lunghezza                numero limite di caratteri
     * @param string $caratteriSpeciali     eccezione ai caratteri alfanumerici italiani
     * 
     * @return null in caso di errore oppure la stringa analizzata.
     */
    public static function controllo($stringa,$lunghezza=20,$caratteriSpeciali="_\-"){
        if(!preg_match("/^[".$caratteriSpeciali."àèéìòùa-zA-Z0-9]{0,$lunghezza}$/",$stringa)){
            return null;
        }
        return $stringa;
    }
}
