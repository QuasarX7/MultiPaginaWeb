<?php
include_once 'Struttura/MultiPagina.php';
include_once 'Struttura/PaginaCerca.php';
include_once 'Struttura/PaginaRicercaWeb.php';
include_once 'LibreriaQx7-php/BaseDatiMySQL.php';

define(PASSWORD,'vvv');


const COLORE_TESTO_MENU     = "#B0E0E6";
const COLORE_SELEZIONE_MENU = "#483D8B";

const MENU_HOME                  = "Home";
const MENU_SORRISO               = "Sorriso";
const MENU_APPUNTI               = "Appunti";
const MENU_INFORMATICA           = "Informatica";
const MENU_IA                    = "Intelligenza_Artificiale";
const MENU_SICUREZZA_INFORMATICA = "Sicurezza_Informatica";
const MENU_INGLESE               = "Inglese";
const MENU_FONETICA_INGLESE      = "Fonetica";
const MENU_DIZIONARIO_INGLESE    = "Dizionario";
const MENU_PROGRAMMAZIONE        = "Programmazione";
const MENU_CPP                   = "C++";
const MENU_ART_ASCII = "ASCII Art";

$pagina = new MultiPagina('Domenico dellaPERUTA');
$pagina->logoPNG('PagineWeb/logo.png');

$pagina->aggiungi(new RegolaCSS(
    '#home',
    [
        new DichiarazioneCSS('position', 'fixed'),
        new DichiarazioneCSS('top', '0'),
        new DichiarazioneCSS('left', '0'),
        new DichiarazioneCSS('z-index', '-10000'),
        new DichiarazioneCSS('min-width', '100%'),
        new DichiarazioneCSS('min-height', '100%'),
        new DichiarazioneCSS('height', 'auto'),
        new DichiarazioneCSS('width', 'auto')
        
    ]
));




$pagina->creaBarraMenu("#333",COLORE_TESTO_MENU,COLORE_SELEZIONE_MENU);
$pagina->inizializzaPrimoLivelloMenu('#555', COLORE_TESTO_MENU);
$pagina->inizializzaSecondoLivelloMenu('#777', COLORE_TESTO_MENU);

$pagina->creaPannelloLaterale('black', COLORE_TESTO_MENU, COLORE_SELEZIONE_MENU);

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

$argomentoInglese = new Argomento('Inglese');
$argomentoInglese->aggiungiPagina('Pronuncia', 'PagineWeb/Inglese/pronuncia_inglese_a_z.html');
$pagina->aggiungiArgomento($argomentoInglese);

$argomentoDizionarioInglese = new Argomento('Dizionario di Inglese');

$schemaMySQL = 'my_domenicodellaperuta';

$db = new BaseDatiMySQL();

$tabella = $db->tabella('SELECT * FROM dizionario_inglese;',$schemaMySQL,'root','Orione');
$infoTabella = $db->infoTabella('dizionario_inglese',$schemaMySQL,'root','Orione');

if($tabella != null && $infoTabella != null){
    $dizionario = new PaginaCerca($tabella,0,$infoTabella);
    $argomentoDizionarioInglese->aggiungiPaginaCodice('Voce', $dizionario->vedi());
}

$pagina->aggiungiArgomento($argomentoDizionarioInglese);

$argomentoCpp = new Argomento('C++');
$argomentoCpp->aggiungiPagina('Output standard', 'PagineWeb/C++/video01.html');
$argomentoCpp->aggiungiPagina('Input standard', 'PagineWeb/C++/video02.html');
$argomentoCpp->aggiungiPagina('Variabili e Costanti', 'PagineWeb/C++/video03.html');
$argomentoCpp->aggiungiPagina('Condizioni', 'PagineWeb/C++/video04.html');
$argomentoCpp->aggiungiPagina('Cicli', 'PagineWeb/C++/video05.html');
$argomentoCpp->aggiungiPagina('Vettori', 'PagineWeb/C++/video06.html');
$argomentoCpp->aggiungiPagina('Caratteri', 'PagineWeb/C++/video07.html');
$argomentoCpp->aggiungiPagina('Puntatori', 'PagineWeb/C++/video08.html');
$argomentoCpp->aggiungiPagina('Riferimenti', 'PagineWeb/C++/video09.html');
$argomentoCpp->aggiungiPagina('Stringhe', 'PagineWeb/C++/video10.html');
$argomentoCpp->aggiungiPagina('Matrici', 'PagineWeb/C++/video11.html');
$argomentoCpp->aggiungiPagina('Classe string', 'PagineWeb/C++/video12.html');
$argomentoCpp->aggiungiPagina('Strutture e Unioni', 'PagineWeb/C++/video13.html');
$argomentoCpp->aggiungiPagina('typedef e sizeof', 'PagineWeb/C++/video14.html');
$argomentoCpp->aggiungiPagina('Memoria dinamica', 'PagineWeb/C++/video15.html');
$argomentoCpp->aggiungiPagina('Funzioni', 'PagineWeb/C++/video16.html');
$argomentoCpp->aggiungiPagina('Parametri di una funzione', 'PagineWeb/C++/video17.html');
$argomentoCpp->aggiungiPagina('Puntatori a funzioni', 'PagineWeb/C++/video18.html');
$argomentoCpp->aggiungiPagina('Introduzione alle classi', 'PagineWeb/C++/video19.html');
$argomentoCpp->aggiungiPagina('Costruttore di una classe', 'PagineWeb/C++/video20.html');
$argomentoCpp->aggiungiPagina('Costruttore di copia di una classe', 'PagineWeb/C++/video21.html');
$argomentoCpp->aggiungiPagina('Distruttore di una classe', 'PagineWeb/C++/video22.html');
$argomentoCpp->aggiungiPagina('Funzioni in linea', 'PagineWeb/C++/video23.html');
$argomentoCpp->aggiungiPagina('Funzione ordinaria e parametri input/output', 'PagineWeb/C++/video24.html');
$argomentoCpp->aggiungiPagina('funzioni amiche', 'PagineWeb/C++/video25.html');
$argomentoCpp->aggiungiPagina('Classi derivate', 'PagineWeb/C++/video26.html');
$argomentoCpp->aggiungiPagina('Funzione virtuali', 'PagineWeb/C++/video27.html');
$argomentoCpp->aggiungiPagina('Polimorfismo', 'PagineWeb/C++/video28.html');
$argomentoCpp->aggiungiPagina('Distruttore virtuale', 'PagineWeb/C++/video29.html');
$argomentoCpp->aggiungiPagina('Classe astratta e interfacce', 'PagineWeb/C++/video30.html');
$argomentoCpp->aggiungiPagina('Derivazione multipla', 'PagineWeb/C++/video31.html');
$argomentoCpp->aggiungiPagina('Derivazione virtuale', 'PagineWeb/C++/video32.html');
$argomentoCpp->aggiungiPagina('Variabili e funzioni statiche', 'PagineWeb/C++/video33.html');
$argomentoCpp->aggiungiPagina('Classi modello', 'PagineWeb/C++/video34.html');
$argomentoCpp->aggiungiPagina('Funzioni operatori', 'PagineWeb/C++/video35.html');
$argomentoCpp->aggiungiPagina('Eccezioni', 'PagineWeb/C++/video36.html');
$argomentoCpp->aggiungiPagina('Riferimento a r-value', 'PagineWeb/C++/video37.html');
$argomentoCpp->aggiungiPagina('Costruttore di spostamento', 'PagineWeb/C++/video38.html');
$argomentoCpp->aggiungiPagina('classe std::vector', 'PagineWeb/C++/video39.html');
$argomentoCpp->aggiungiPagina('auto e decltype', 'PagineWeb/C++/video40.html');
$argomentoCpp->aggiungiPagina('funzioni anonime', 'PagineWeb/C++/video41.html');
$argomentoCpp->aggiungiPagina('inizializzazione in C++11', 'PagineWeb/C++/video42.html');
$argomentoCpp->aggiungiPagina('Costruttore delega', 'PagineWeb/C++/video43.html');
$argomentoCpp->aggiungiPagina('Aggiungere o eliminare metodi default', 'PagineWeb/C++/video44.html');
$argomentoCpp->aggiungiPagina('Namespace', 'PagineWeb/C++/video45.html');
$argomentoCpp->aggiungiPagina('Enumerazione', 'PagineWeb/C++/video46.html');
$argomentoCpp->aggiungiPagina('Puntatori intelligenti', 'PagineWeb/C++/video47.html');
$argomentoCpp->aggiungiPagina('Multithreading e sincronizzazione', 'PagineWeb/C++/video48.html');



$pagina->aggiungiArgomento($argomentoCpp);

/* MENU PRINCIPALE */

// Primo livello
$pagina->aggiungiMenu(MENU_HOME, null);
$pagina->aggiungiMenu(MENU_APPUNTI);
$pagina->aggiungiMenu(MENU_SORRISO);

// sottovoci
$pagina->aggiungiMenu(MENU_INFORMATICA,null,MENU_APPUNTI);
$pagina->aggiungiMenu(MENU_IA, $argomentoIA,MENU_INFORMATICA);
$pagina->aggiungiMenu(MENU_SICUREZZA_INFORMATICA, $argomentoPenTest,MENU_INFORMATICA);
$pagina->aggiungiMenu(MENU_CPP,$argomentoCpp,MENU_INFORMATICA);


$pagina->aggiungiMenu(MENU_INGLESE,null,MENU_APPUNTI);
$pagina->aggiungiMenu(MENU_FONETICA_INGLESE,$argomentoInglese,MENU_INGLESE);
$pagina->aggiungiMenu(MENU_DIZIONARIO_INGLESE,$argomentoDizionarioInglese,MENU_INGLESE);

$pagina->aggiungiMenu(MENU_ART_ASCII, $argomentoArtASCII,MENU_SORRISO);

$pagina->crea();

