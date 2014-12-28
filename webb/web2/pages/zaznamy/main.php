<?

function ZaznamyTheme($czoznam, $kategoria, $cas) {
    // Nastavenia
    global $userdata;
    $farba=false;
    $podmienka = "`typ` > 0"; 
	
    // Hladame kategoriu
	if(!ZaznamyFetch(&$kategoria, &$cas, &$podmienka)) return false;
        
    // Mysql udaje tahame
    $zoznam = new Zoznam;
    $zoznam->actual = $czoznam;
    $zoznam->celkovo = DB::One("SELECT COUNT(`typ`) as pocet FROM `cstrike`.`web2_logs` WHERE ".$podmienka);
    $sql= DB::Query("SELECT * FROM `cstrike`.`web2_logs` WHERE ".$podmienka." ORDER BY kedy DESC ".$zoznam->mysql() );
    

    echo '
    <table align="center" width="530" cellspacing="0" cellpadding="0">
        <tr>
            <td align="left">
                <a href="', ROOT, 'zaznamy/vseobecne/', $cas, '/">v&scaron;eobecn&eacute;</a> |
                <a href="', ROOT, 'zaznamy/banka/', $cas, '/">banka</a> |
                <a href="', ROOT, 'zaznamy/obchod/', $cas, '/">obchod</a> |    ';    
                if( $userdata['clan_id'] )
                    echo "<a href=\"", ROOT, "zaznamy/liga/", $cas, "/\">liga</a> |";
                echo '
                <a href="', ROOT, 'zaznamy/vsetko/', $cas, '/"> v&scaron;etko</a>
            </td>
            <td align="right">
                <a href="', ROOT, 'zaznamy/', $kategoria, '/dnes/">dnes</a> |
                <a href="', ROOT, 'zaznamy/', $kategoria, '/tyzden/">t&yacute;&#382;de&#328;</a> |
                <a href="', ROOT, 'zaznamy/', $kategoria, '/mesiac/">mesiac</a> |
                <a href="', ROOT, 'zaznamy/', $kategoria, '/vsetko/">v&scaron;etko</a>
            </td>
        </tr>
    </table>    
    <table align="center" width="540" cellspacing="0" cellpadding="3" id="widget_logy">';

    while($udaje=$sql->fetch_assoc())  { 
        $farba = !$farba;    
        echo ($farba) ? '<tr>' : '<tr style="background-color: white;">';
        echo '
            <td width="120" align="center" class="info_gray">', date("Y-m-d", $udaje['kedy']), ' | <span class="logs_co">', date("H:i", $udaje['kedy']), '</span></td>
            <td width="5">&nbsp;</td>
            <td>', WebLog::Parse($udaje), '</td>
        </tr>';
    } 
    echo '
    </table>
    <br />
    <p align="right">';
    $zoznam->vzdialenost = 5;
    $zoznam->Make(ROOT.'log/'.$kategoria.'/'.$cas.'/%d/');
    echo '</p><br />';
}

?>