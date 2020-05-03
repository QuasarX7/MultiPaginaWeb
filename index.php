<?php

include_once 'Struttura/MultiPagina.php';
include_once 'Struttura/PaginaRicercaWeb.php';

$pagina = MultiPagina::costruisciDaFileJSON('PagineWeb/argomenti.json');
MultiPagina::aggiungiPaginaAlFileJSON('PagineWeb/argomenti2.json',['gatto','sotto_gatto'],'micio','PagineWeb/test/pippo.html','ciao mondo');
$pagina->crea();

?>