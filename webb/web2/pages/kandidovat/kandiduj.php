<?php

function kandiduj() {
    Header::Title('Kandidova&#357;');
    // Casova kontrola
    global $userdata;
    if($userdata['user_joined'] + 60*60*24*30 > Time::$TIME ) {
        Mess::Alert('Kandidat&uacute;ra', 'Kandidova&#357; mo&#382;e&scaron; a&#382; po 30 d&#328;och !');
        return false;
    }
    
    // Kontrola ci uz nekandidoval
    $sql = DB::One("SELECT id FROM `cstrike`.`kandidati` WHERE user='".$userdata['user_id']."' LIMIT 1");
    if( $sql > 0) {
        Mess::Alert('Kandidat&uacute;ra', 'U&#382; si kandidoval !');
        return false;
    }    
    
    // Kontrolujeme ci ma v profile vyplnene potrebne veci
    if(!$userdata['user_icq'] or !$userdata['cs_meno'] or !$userdata['cs_meno'] or !$userdata['user_location']) {
        Mess::Alert('Kandidat&uacute;ra', 'V profile mus&iacute;&scaron; ma&#357;  vyplnen&eacute; ICQ, bydlisko a hern&eacute; meno, heslo.');
        return false;
    }
    
    //Akcia poslal ziadost ....
    if(isset($_POST['submit'])) {
        // Ukladame
        $p_server = $_POST['server'];
        if(is_numeric($p_server) and is_numeric($_POST['vek'])) {
            if($_POST['accept'] and $_POST['accept2']) {                                
                $obchod_cennik = Shop::Get(0); 
                if($userdata['korun'] > $obchod_cennik) {
                    $boolean[0] = $_POST['admin'] ? 1 : 0;
                    $boolean[1] = $_POST['cheater'] ? 1 : 0;
                    $boolean[2] = $_POST['amx'] ? 1 : 0;
                    
                    $zakazane = array("'", '"', "/", "\\", "<", ">", "&");
                    $pomoc = DB::Vstup(str_replace($zakazane, "", trim($_POST['pomoc'])));
                    $adminovat = DB::Vstup(str_replace($zakazane, "", trim($_POST['adminovat'])));                   
                    $cas = Time::$TIME;  
                                                 
                    Shop::Kup($userdata['user_id'], 0);
                    DB::Query("INSERT INTO `cstrike`.`kandidati` 
                                (`id`, `user`, rokov, `admin`, `cheater`, `amx`, `pomoc`, `adminovat`, `server` , `start`) 
                            VALUES 
                                (NULL, '".$userdata['user_id']."', '".$_POST['vek']."', '".$boolean[0]."', '".$boolean[1]."', '".$boolean[2]."', '".$pomoc."', '".$adminovat."', '".$p_server."', '".$cas."')");
                    // $id = mysql_insert_id();
                    WebLog::Add(0, 2, $userdata['user_id'], $p_server);

                    // Ukoncime podprogram 
                    unset($boolean);
                    unset($pomoc);
                    unset($adminovat);
                    unset($cas);
                    info($userdata['user_name'], false);
                    return true;
                }  else {    
                    Mess::Alert('Kandidat&uacute;ra', 'Ak chce&scaron; kandidova&#357; potrebuje&scaron; minim&aacute;lne <strong>50</strong> kreditov.');
                }
            } else {
                Mess::Alert('Kandidat&uacute;ra', 'Mus&iacute;&scaron; s&uacute;hlasi&#357; s podmienkamy o kandidovan&iacute;  !');
            }
        }  else {
            Mess::Alert('Kandidat&uacute;ra', 'Nespr&aacute;vne &uacute;daje !');
        }
    }
    
    // Formular
echo '
    <br>
    <table cellspacing="0" cellpadding="2" class="serverlist_table bb" width="100%">
        <tr>
            <td width="100%" align="left" colspan="3">
                <strong>Tvoja &#382;iados&#357;:</strong>
            </td>
        </tbody>
    </table>
    
    <form method="post" action="', ROOT, 'kandidovat/ziadost/">
        <table cellspacing="0" cellpadding="5" class="voteadmin_table" width="100%">
            <tr>
                <td class="voteadmin_td_left voteadmin_td_font" width="40%">Server:</td>
                <td class="voteadmin_td_right" width="60%">
                    <select name="server">';
                $sql = DB::Query("SELECT id, nazov FROM `servers` ORDER BY `id` ASC");    
                while($server = $sql->fetch_row()) { 
                    echo '<option value="'.$server[0].'">'.$server[1].'</option>';
                }
            echo '    </select>
                </td>
            </tr>
            <tr>
                <td class="voteadmin_td_left voteadmin_td_font">Vek</td>
                <td class="voteadmin_td_right">
                    <input type="text" class="inputbox" style="width: 20px;" maxlength="2" value="18" name="vek" />
                </td>
            </tr>        
            <tr>
                <td class="voteadmin_td_left voteadmin_td_font">Bol si u&#382; admin ?</td>
                <td class="voteadmin_td_right">
                    <input type="checkbox" name="admin" value="checkbox" id="checkbox" />
                </td>
            </tr>        
            <tr>
                <td class="voteadmin_td_left voteadmin_td_font">Vie&scaron; dobre rozozna&#357; cheatera od skillera ?</td>
                <td class="voteadmin_td_right">
                    <input type="checkbox" name="cheater" value="checkbox" id="checkbox" />
                </td>
            </tr>        
            <tr>
                <td class="voteadmin_td_left voteadmin_td_font"> Pozna&scaron; aspo&#328; z&aacute;kladne AMXX pr&iacute;ikazy ?</td>
                <td class="voteadmin_td_right">
                    <input type="checkbox" name="amx" value="checkbox" id="checkbox" />
                </td>
            </tr>                                
            <tr>
                <td class="voteadmin_td_left voteadmin_td_font" align="center" colspan="2">
                    Ako si pomohol serveru, webu ....<br>
                    <textarea cols="40" rows="6" name="pomoc" ></textarea>
                </td>
            </tr>            
            <tr>
                <td class="voteadmin_td_left voteadmin_td_font" align="center" colspan="2">                    
                    Ako si predstavuje&scaron; adminova&#357; ...<br>
                    <textarea cols="40" rows="6" name="adminovat"></textarea>
                </td>
            </tr>
            
            <tr>
                <td align="center" class="voteadmin_td_left voteadmin_td_font">                    
                    <input type="checkbox" value="1" name="accept"/>
                </td>
                <td class="voteadmin_td_right">
                    <span style="color: rgb(215, 32, 0); font-weight: bold;"> * </span>
                    S&uacute;hlas&iacute;m s pravidlamy webu, serverov, kandidat&uacute;ry
                </td>
            </tr>            
            <tr>
                <td align="center" class="voteadmin_td_left voteadmin_td_font">                    
                    <input type="checkbox" value="1" name="accept2"/>
                </td>
                <td class="voteadmin_td_right">
                    S&uacute;hlas&iacute;m s odobrat&iacute;m 50 minc&iacute; za &#382;iados&#357;
                </td>
            </tr>    
            <tr>
                <td class="voteadmin_td_left voteadmin_td_font">&nbsp;</td>
                <td class="voteadmin_td_right">
                    <input type="hidden" value="1" name="kontrola"/>
                    <input type="submit" id="add" value="Odo&scaron;li &#382;iados&#357;" name="submit"/>
                </td>
            </tr>
        </table>
    </form>';
    return true;
} 
?>   