
<?php
include_once 'Struttura/MultiPagina.php';

$pagina = new MultiPagina('Quasar X7');

//$pagina->formatoCarattereDiIntestazione('Struttura/Akronim-Regular.ttf');

$pagina->creaBarraMenu("#00f","#0f0","#f00");
$pagina->inizializzaPrimoLivelloMenu('#f7f', '#f00');
$pagina->inizializzaSecondoLivelloMenu('#222', '#fff');
$pagina->creaPannelloLaterale('red', 'blue', 'black');
$argomento1 = new Argomento('Argomento Test');
$argomento1->aggiungiPagina('Pagina 1','PagineWeb/pagina1.txt');
$argomento1->aggiungiPagina('Pagina 2','PagineWeb/pagina2.txt');
$pagina->aggiungiArgomento($argomento1);
$pagina->aggiungiNoteMarginePagina(new Pannello('100%', '40px','#FF0'));
$pagina->aggiungiNoteMarginePagina(new Pannello('50px', '400px','#0F0'));
$pagina->aggiungiNoteMarginePagina(new Pannello('590px', '400px','#0F0'));
$argomento2 = new Argomento('Lamù - la ragazza dello Spazio');
$argomento2->aggiungiPagina('Immagine Art ASCII','PagineWeb/pagina3.txt');
$pagina->aggiungiArgomento($argomento2);
$tr = 'ventesimo';
$pagina->aggiungiMenu("primo", $argomento1);
$pagina->aggiungiMenu("secondo");
$pagina->aggiungiMenu("terzo");
//$pagina->aggiungiMenu('ventesimo', null,'secondo');
$pagina->aggiungiMenu($tr, null,'secondo');
$pagina->aggiungiMenu('duecentesimo', $argomento2,$tr);


$pagina->crea();

?>
