
<?php
include_once 'Struttura/MultiPagina.php';

const COLORE_TESTO_MENU     = "#0f0";
const COLORE_SELEZIONE_MENU = "#fff";

const MENU_HOME                  = "Home";
const MENU_SORRISO               = "Sorriso";
const MENU_APPUNTI               = "Appunti";
const MENU_INFORMATICA           = "Informatica";
const MENU_IA                    = "Intelligenza Artificiale";
const MENU_SICUREZZA_INFORMATICA = "Sicurezza Informatica";
const MENU_ART_ASCII = "ASCII Art";

$pagina = new MultiPagina('Domenico dellaPERUTA');

$pagina->creaBarraMenu("#333",COLORE_TESTO_MENU,COLORE_SELEZIONE_MENU);
$pagina->inizializzaPrimoLivelloMenu('#555', COLORE_TESTO_MENU);
$pagina->inizializzaSecondoLivelloMenu('#777', COLORE_TESTO_MENU);

$pagina->creaPannelloLaterale("#333", COLORE_TESTO_MENU, COLORE_SELEZIONE_MENU);

//Eventuali note
$nota = new Pannello('100%', 'auto','#fafad2');
$nota->aggiungi("Chiunque si accinga ad eleggere se stesso a giudice del vero e della conoscenza, naufraga sotto le risate degli dei.<br><br><i>Albert Einstein</i>");
$pagina->aggiungiNoteMarginePagina($nota);

$pagina->aggiungiHome(new Pagina(MultiPagina::HOME,'PagineWeb/home.html'));

$argomentoIA = new Argomento('Intelligenza Artificiale');
$argomentoIA->aggiungiPagina('Introduzione','PagineWeb/IA/IA.html');
$argomentoIA->aggiungiPagina('Algoritmi Genetici','PagineWeb/IA/algoritmi_genetici/algoritmi_genetici.html');
$argomentoIA->aggiungiPagina('Reti Neurali','PagineWeb/IA/reti_neurali/reti_neurali.html');
$argomentoIA->aggiungiPagina('Percettrone','PagineWeb/IA/reti_neurali/percettrone/Percettrone.html');
$argomentoIA->aggiungiPagina('MLP','PagineWeb/IA/reti_neurali/MLP/MLP.html');
$argomentoIA->aggiungiPagina('SOM','PagineWeb/IA/reti_neurali/SOM/SOM.html');
$argomentoIA->aggiungiPagina('RBF','PagineWeb/IA/reti_neurali/RBF/RBF.html');
$argomentoIA->aggiungiPagina('Hopfield','PagineWeb/IA/reti_neurali/hopfield/Hopfield.html');
$pagina->aggiungiArgomento($argomentoIA);

$argomentoArtASCII = new Argomento('Immagine Art ASCII');
$argomentoArtASCII->aggiungiPagina('Lamù - la ragazza dello Spazio','PagineWeb/pagina3.txt');
$pagina->aggiungiArgomento($argomentoArtASCII);

$argomentoPenTest = new Argomento('Sicurezza Informatica');
$argomentoPenTest->aggiungiPagina('Penetration Test','PagineWeb/penetration_test/penetration_test.html');
$argomentoPenTest->aggiungiPagina('Footprinting','PagineWeb/penetration_test/footprinting.html');
$argomentoPenTest->aggiungiPagina('Scansione delle porte','PagineWeb/penetration_test/scansione_delle_porte.html');
$argomentoPenTest->aggiungiPagina('Enumerazione','PagineWeb/penetration_test/enumerazione.html');
$argomentoPenTest->aggiungiPagina('Exploit','PagineWeb/penetration_test/exploit.html');
$argomentoPenTest->aggiungiPagina('Forza bruta','PagineWeb/penetration_test/forza_bruta.html');
$argomentoPenTest->aggiungiPagina('Spoofing','PagineWeb/penetration_test/spoofing.html');
$pagina->aggiungiArgomento($argomentoPenTest);




/* MENU PRINCIPALE */

// Primo livello
$pagina->aggiungiMenu(MENU_HOME, null);
$pagina->aggiungiMenu(MENU_APPUNTI);
$pagina->aggiungiMenu(MENU_SORRISO);

// sottovoci
$pagina->aggiungiMenu(MENU_INFORMATICA,null,MENU_APPUNTI);
$pagina->aggiungiMenu(MENU_IA, $argomentoIA,MENU_INFORMATICA);
$pagina->aggiungiMenu(MENU_SICUREZZA_INFORMATICA, $argomentoPenTest,MENU_INFORMATICA);

$pagina->aggiungiMenu(MENU_ART_ASCII, $argomentoArtASCII,MENU_SORRISO);



$pagina->crea();

?>
