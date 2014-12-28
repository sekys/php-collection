<?
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
Debug::Oblast('LOGY');
User::MustLogged();

//Stranky 
Header::Title('Z&aacute;znamy');
$zoznam = '';
if(isset( $_GET['p3'])) {
    $zoznam = $_GET['p3'];
} else {
    if(isset( $_GET['p2'])) {
        $zoznam = $_GET['p2'];
    }
}
require SPAGES.'zaznamy/fetch.php';
require SPAGES.'zaznamy/main.php';
$zoznam = is_numeric($zoznam) ? $zoznam : 0;    
$kategoria = isset($_GET['p1']) ? $_GET['p1'] : 'vsetko';
$cas = isset($_GET['p2']) ? $_GET['p2'] : 'vsetko';
ZaznamyTheme($zoznam, 50, $kategoria, $cas);

Debug::Oblast('LOGY');
require_once "side_right.php";
require_once "footer.php";
?>