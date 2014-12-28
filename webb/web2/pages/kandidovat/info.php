<?php

function info($id) {    
    global $userdata;
    $id = BaseSTR::uri_in(trim($id));
    $sql = DB::Query("SELECT k.*, user_id, user_name, user_avatar, cs_meno, user_location, user_joined, user_icq, user_groups FROM `cstrike`.`fusion_users` u
                        JOIN ( SELECT * FROM `cstrike`.`kandidati` ) k
                            on u.user_id = k.user                       
                    WHERE user_name LIKE '".DB::Vstup($id)."'");
                        
    $m = new Member;
    if(!$m->mysqlexist($sql)) {
        Mess::Alert('Kandidat&uacute;ra', DB::Vystup($id).' nieje zaregistrovan&yacute; alebo nekandiduje !');
        return false;
    }
    $m->setout('user_name', 'cs_meno'); 
    Header::Title('Kandid&aacute;t - '.$m->user_name);
                   
    // Vypisime viac detailov o kadidatovy
    $server_name = DB::One("SELECT nazov as pocet FROM `cstrike`.`servers` WHERE id = '".$m->server."'");    
    $cesta = ROOT.'kandidovat/kandidat/'.BaseSTR::uri_out($m->user_name).'/';
    
    echo '<br>'; 
    info_parser('app', '&#381;iados&#357;'); 
    echo '
    <table width="100%" cellspacing="1" cellpadding="5" id="app" ', ( $m->amxid > 0 ? 'style="color: silver;"' : ""), '>
        <tr>
            <td align="right"><strong>Nick</strong></td>
            <td>', $m->user_name, '</td>
            <td rowspan="6"><img alt="', $m->cs_meno, '" ', $m->Avatar(), ' /></td>
        </tr>
        <tr>
            <td align="right"><strong>Hern&eacute; meno:</strong></td>
            <td>', $m->cs_meno, '</td>
        </tr>
        <tr>
            <td align="right"><strong>Steam ID:</strong></td>
            <td>', $m->cs_steam, '</td>
        </tr>
        <tr>
            <td align="right"><strong>Bydlisko:</strong></td>
            <td>', $m->user_location, '</td>
        </tr>
        <tr>
            <td align="right" ><strong>Vek:</strong></td>
            <td>', $m->rokov, ' rokov</td>
        </tr>        
        <tr>
            <td align="right" ><strong>Server:</strong></td>
            <td>', $server_name, '</td>
        </tr>
        <tr>
            <td align="right"><strong>&Scaron;tatistiky:</strong></td>
            <td colspan="2"><a href="/psychostats/index.php?q=', BaseSTR::uri_out($m->cs_meno), '">Prejs&#357; na &scaron;tatistiky &raquo;</a></td>
        </tr>
        <tr>
            <td align="right" ><strong>Profil:</strong></td>
            <td colspan="2"><a href="', ROOT, 'hrac/', BaseSTR::uri_out($m->user_name), '/">Prejs&#357; do profilu &raquo;</a></td>
        </tr>
        <tr>
            <td align="right" ><strong>Zaregistrovan&yacute;:</strong></td>
            <td colspan="2">', date("Y-m-d H:i:s", $m->user_joined), '</td>
        </tr>            
        <tr>
            <td align="right" ><strong>ICQ:</strong></td>
            <td colspan="2">', DB::Vystup($m->user_icq), '</td>
        </tr>            
        <tr>
            <td align="right"><strong>Bol u&#382; niekedy Admin ?</strong></td>
            <td colspan="2">', STR::ToBool($m->admin), '</td>
        </tr>            
        <tr>
            <td align="right"><strong>Vie rozozna&#357; skill od cheatera ?<strong></td>
            <td colspan="2">', STR::ToBool($m->cheater), '</td>
        </tr>            
        <tr>
            <td align="right" ><strong>Pozn&aacute; Amxx pr&iacute;kazy ?</strong></td>
            <td colspan="2">', STR::ToBool($m->amx), '</td>
        </tr>
        <tr>
            <td colspan="3" align="center" >
                <strong>Dovod prihlasenia:</strong>
                <br><br>
                <div align="center" class="textbox kandidovat_popis" >
                    ', DB::Vystup($m->adminovat), '
                </div>    
            </td>
        </tr> 
        <tr>
            <td colspan="3" align="center" >
                <strong>Ako n&aacute;m pomohol:</strong>
                <br><br>
                <div align="center" class="textbox kandidovat_popis" >
                    ', DB::Vystup($m->pomoc), '
                </div>            
            </td>
        </tr>
    </table>';
    info_status($m, $cesta);
    info_hodnosti($m->user_groups);
    info_komentare($m->user_id, $cesta);     
    return true;
}

?>