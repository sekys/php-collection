<?php

// Normal stranka ...
function header_kandidatury() {   
    // Kandit&uacute;ra na serveroch
    // ----- Cache objekt
    $objekt = new Cache('headerkandidatury', 180);
    $objekt->Zlozka('page');
    if($objekt->File()) {
    // ----- Cache objekt 
    
    echo '
        <p align="center">
            <img align="absmiddle" border="0" src="', ROOT, 'web2/images/tool/plus1.png" alt="Prida&#357;" />
            <a class="b_link" href="', ROOT, 'kandidovat/ziadost/">Nap&iacute;sa&#357; &#382;iados&#357; na admina</a>
        </p>
        <table cellspacing="0" cellpadding="2" class="serverlist_table bb" width="100%">
            <tr>
                <td width="100%" align="left" colspan="3">
                    <strong>Kandidova&#357; na servery</strong>
                </td>
            </tr>
        </table>

        <table cellspacing="0" cellpadding="0" class="shadow_list">
            <tr>
                <td>';
            $sql = DB::Query("SELECT `user_name`, `img`, `nazov`, `id`,
                COALESCE(pocet, 0) as pocet
                FROM `cstrike`.`servers` `s`
                    LEFT JOIN ( SELECT user_id, user_name FROM `cstrike`.`fusion_users` ) `u`
                       on `s`.`headadmin` = `u`.`user_id`              
                    LEFT JOIN ( SELECT `server`, COUNT(id) as `pocet` FROM `cstrike`.`kandidati` GROUP BY `server` ) `k`
                       on `s`.`id` = `k`.`server`                                                
                ORDER BY `s`.`id` ASC"); 
            
            while($server = $sql->fetch_assoc())
            {         
                $meno = BaseSTR::uri_out($server['nazov']);
                echo '    
                <div class="serverlist_background" ', STYLE_HOVER, '>
                        <table cellspacing="1" width="500px" cellpadding="1" class="serverlist_table">
                            <tr>
                                <td class="serverlist_icon" width="25">
                                    <img vspace="0" hspace="0" width="24" height="24" border="0" alt="" title="', $server['nazov'], '" src="', ROOT, 'web2/images/server/', $server['img'], '.png"/>
                                </td>
                                <td class="serverlist_servername">
                                    <a class="server" href="', ROOT, 'kandidovat/server/', $server['id'], ',', $meno, '/">', $server['nazov'], '</a>
                                    <span class="info_gray"><br/>
                                        Headadmin: ';
                                        if( $server['user_name'] ) {
                                            $server['user_name'] = BaseSTR::XSS($server['user_name']);
											echo '<a target="_blank" href="', ROOT, 'kandidovat/kandidat/', BaseSTR::uri_out( $server['user_name']), '/">', $server['user_name'], '</a>';
                                        } else {
                                            echo '-';
                                        }
                                echo '</span>
                                </td>
                                <td class="serverlist_info" align="right" width="100"> 
                                    <img vspace="0" hspace="0" border="0" alt="" src="', ROOT, 'web2/images/user2g.png"/>
                                    <a class="server_showfont" href="', ROOT, 'kandidovat/server/', $server['id'], ',', $meno, '/">
                                        ', $server['pocet'], ' kandid&aacute;tov
                                    </a>
                                </td>
                            </tr>
                        </table>
                </div>';                        
            }        
    echo '        </td>
            </tr>
        </table>';
    
    // ----- Cache objekt
    }
    $objekt->File();
    // ----- Cache objekt      
}

function normal() {
    Header::Title('Kandidova&#357; na Admina');
    
    // Hladanie
    // TODO: Upravim a dat prec CSS
    echo '
    <form method="post" action="', ROOT, 'kandidovat/">
        <br />
        <table cellspacing="0" cellpadding="0" align="right" style="padding-right: 6px;">
            <tr> 
                <td>
                    <span class="search_where_font"> H&#318;adaj pod&#318;a nicku:</span>
                </td>
                <td style="padding-left: 5px;">
                    <input type="text" class="inputbox" style="width: 150px;" value="', $_POST['search'], '" name="search"/>                
                </td>
                <td style="padding-left: 5px;">
                    <input type="Submit" value="Submit" name="submit_search" />
                </td>
            </tr>
        </table>
    </form>
    <br /><br />';
        
        // Script
        $prikaz1 = '';
        $prikaz2 = '';
        if(isset($_POST['search'])) { 
            $search = DB::Vstup(trim($_POST['search']));
            $prikaz1 = "WHERE `user_name` LIKE '%".$search."%'";
        } else {
            if(isset($_GET['server'])) {
                $server = Input::CoolURI('server', 0);
                $prikaz2 = "WHERE server ='".DB::Vstup($server)."'";
            }
        }

        $txt = "SELECT user_name, u.user_id, user_icq, user_groups, vip, slot, 
        server, server_name, 
        COALESCE(hlasov, 0) as hlasov, 
        COALESCE(komentarov, 0) as komentarov 
         FROM `cstrike`.`kandidati` k
                        JOIN ( SELECT user_name, user_id, user_icq, user_groups, vip, slot FROM `cstrike`.`fusion_users` ".$prikaz1." ) u
                            ON k.user = u.user_id    
                        LEFT JOIN ( SELECT id, COUNT(id) AS hlasov FROM `phpbanlist`.`web2_hlasovanie` WHERE `typ`='4' GROUP BY id ) h
                            ON k.id = h.id
                        LEFT JOIN ( SELECT comment_item_id, COUNT(comment_item_id) AS komentarov FROM `cstrike`.`fusion_comments` WHERE comment_type = '".$GLOBALS['komentar_id']."' GROUP BY comment_item_id ) c
                            on k.id = c.comment_item_id 
                        JOIN ( SELECT id, nazov as server_name FROM `cstrike`.`servers` ) p
                            on k.server = p.id
                    ".$prikaz2." ORDER BY hlasov, hlasov DESC";
        $sql = DB::Query($txt);
    
    // Vypisuje    
    echo '
        <table width="100%" cellspacing="1" cellpadding="2" class="bb">
            <tr>
                <td><strong>Nick kandid&aacute;ta</strong></td>
                <td width="140"><strong>Server</strong></td>
                <td width="110" align="center"><strong>Hlasov</strong></td>
                <td width="50" align="right"><img border="0" alt="Koment&aacute;rov" title="Koment&aacute;rov" src="', ROOT, 'web2/images/tool/comments3.png"/></td>
                <td width="5"> </td>
            </tr>
        </table>
        <table valign="top" width="100%" cellspacing="1" cellpadding="2" class="shadow_list">';
        $url = ROOT.'kandidovat/kandidat/%s/';
		$data = new Member;
        while($data->next($sql))        
        {    
            $meno = $data->out('server_name');
            $href = $data->Link($url);
            echo '
                <tr ', STYLE_HOVER, '>
                    <td>', $data->Render($url), '</td>
                    <td width="140" class="">
                        <img align="absmiddle" border="0" alt="', $meno, '" title="', $meno, '" src="', ROOT, 'web2/images/tool/cstrike_smalll.png"/>
                        <a href="', ROOT, 'kandidovat/server/', $data->server, ',', BaseSTR::uri_out($meno), '/"> ', $meno, ' </a>
                    </td>
                    <td width="110" align="center">
                        <a href="', $href, '">
                            ', $data->hlasov, ' <img border="0" align="absmiddle" alt="Hlasov" title="', $data->hlasov, ' hlasov" src="', ROOT, 'web2/images/tool/user1.png"/>
                        </a>
                    </td>
                    <td width="50" align="right" class="info_gray"> 
                         <a href="', $href, '"> ',
                        $data->komentarov, '
                        </a>
                    </td>
                    <td width="5"> </td>
                </tr>';
        }    
        
    echo '</table>';
}
?>