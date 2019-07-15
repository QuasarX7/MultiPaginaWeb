
<?php
include_once 'Struttura/MultiPagina.php';

$pagina = new MultiPagina('Domenico della PERUTA');

//$pagina->formatoCarattereDiIntestazione('Struttura/Akronim-Regular.ttf');

const COLORE_TESTO_MENU = "#0f0";
const COLORE_SELEZIONE_MENU = "#fff";

$pagina->creaBarraMenu("#333",COLORE_TESTO_MENU,COLORE_SELEZIONE_MENU);
$pagina->inizializzaPrimoLivelloMenu('#555', COLORE_TESTO_MENU);
$pagina->inizializzaSecondoLivelloMenu('#777', COLORE_TESTO_MENU);

$pagina->creaPannelloLaterale("#333", COLORE_TESTO_MENU, COLORE_SELEZIONE_MENU);

$paginaHome = new Argomento(MultiPagina::HOME);
$pagina->aggiungiArgomento($paginaHome);

$argomento1 = new Argomento('IA');
$argomento1->aggiungiPagina('Intelligenza Artificiale','PagineWeb/IA/IA.html');
$argomento1->aggiungiPagina('Leopardi','PagineWeb/pagina2.txt');
$pagina->aggiungiArgomento($argomento1);

//Eventuali note
//$pagina->aggiungiNoteMarginePagina(new Pannello('100%', '40px','#FF0'));



$argomento2 = new Argomento('Lamù - la ragazza dello Spazio');
$argomento2->aggiungiPagina('Immagine Art ASCII','PagineWeb/pagina3.txt');
$pagina->aggiungiArgomento($argomento2);
$tr = 'ventesimo';


/* MENU PRINCIPALE */

// Primo livello
$pagina->aggiungiMenu("HOME", $paginaHome);
$pagina->aggiungiMenu("Appunti");
$pagina->aggiungiMenu("Sorriso");

// Secondo livello
$pagina->aggiungiMenu('Intelligenza Artificiale', $argomento1,'Appunti');
$pagina->aggiungiMenu('Lamu', $argomento2,'Sorriso');

$pagina->crea();

?>
