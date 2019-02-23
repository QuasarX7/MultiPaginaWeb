
<?php
include_once 'Struttura/MultiPagina.php';

$pagina = new MultiPagina('llu');

$menu = new BarraMenu("#00f","#0f0","#f00");
$menu->menuPrimoLivello('#f7f', '#f00');
$menu->menuSecondoLivello('#222', '#fff');

$menu->aggiungi(new Menu('ciaoooo stronzo',''));

$menu2 = new Menu('voce2aaaaaaaa .','....');
$menu2->aggiungi(new Menu('voce2.1',''));
$menu3 = new Menu('voce4aaaaaaaa','....');
$menu3->aggiungi(new Menu('voce2.1',''));
$menu4 = new Menu('...Prrr','....');
$menu2->aggiungi($menu4);
$menu2->aggiungi($menu3);
$menu->aggiungi($menu2);

$menu->aggiungi(new Menu('voce3',''));

$pagina->aggiungi($menu);

$pagina->crea();

?>
