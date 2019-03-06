
<?php
include_once 'Struttura/MultiPagina.php';

$pagina = new MultiPagina('llu');

$pagina->formatoCarattereDiIntestazione('LibreriaQx7-php/FrederickatheGreat-Regular.ttf');

$pagina->creaBarraMenu("#00f","#0f0","#f00");
//$pagina->inizializzaPrimoLivelloMenu('#f7f', '#f00');
//$pagina->inizializzaSecondoLivelloMenu('#222', '#fff');
$pagina->creaPannelloLaterale('red', 'blue', 'black');
$argomento1 = new Argomento('Argomento Test');
$argomento1->aggiungiPagina('Pagina 1','PagineWeb/pagina1.txt');
$argomento1->aggiungiPagina('Pagina 2','PagineWeb/pagina2.txt');
$pagina->aggiungiArgomento($argomento1);

$tr = 'ventesimo';
$pagina->aggiungiMenu("primo", $argomento1);
$pagina->aggiungiMenu("secondo");
$pagina->aggiungiMenu("terzo");
$pagina->aggiungiMenu('ventesimo', null,'secondo');
$pagina->aggiungiMenu($tr, null,'secondo');
$pagina->aggiungiMenu('duecentesimo', null,$tr);


$pagina->crea();

?>
