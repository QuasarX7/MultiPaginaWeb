<?php

include_once 'Struttura/MultiPagina.php';
include_once 'Struttura/PaginaRicercaWeb.php';

$pagina = MultiPagina::costruisciDaFileJSON('PagineWeb/argomenti.json');
$pagina->crea();

?>