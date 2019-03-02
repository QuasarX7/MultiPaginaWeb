
<?php
include_once 'Struttura/MultiPagina.php';

$pagina = new MultiPagina('llu');

$pagina->creaBarraMenu("#00f","#0f0","#f00");
$pagina->inizializzaPrimoLivelloMenu('#f7f', '#f00');
$pagina->inizializzaSecondoLivelloMenu('#222', '#fff');

$pagina->aggiungiMenu("primo", 'Argomento Test', 1);
$pagina->aggiungiMenu("secondo", 'Argomento Test2', 13);
$pagina->aggiungiMenu("terzo", 'pippo', 193);
$pagina->aggiungiMenu("ventesimo", 'kkkkk', 9193,'secondo');
$pagina->aggiungiMenu("ventunesimo", 'kkkkk999', 300,'secondo');
$pagina->aggiungiMenu("duecentesimo", 'kkkkk999', 2300,'ventunesimo');

$argomento1 = new Argomento('Argomento Test');
$argomento1->aggiungiPagina('Pagina 1','PagineWeb/pagina1.txt');
$argomento1->aggiungiPagina('Pagina 2','PagineWeb/pagina2.txt');
$pagina->aggiungiArgomento($argomento1);



$pagina->crea();

?>
