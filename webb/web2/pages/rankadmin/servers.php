<?

function rank_servers() {
    Header::Title('Rank-Admin Servers');
    $objekt = new Cache('rank_servers_nocache', 30);
    $objekt->Zlozka('rank');
    $objekt->OutputFunction();  
}
function rank_servers_nocache() {
    global $typ, $href, $user, $dnesok, $minulost;
    echo '<table cellspacing="0" cellpadding="5" width="500" id="rankadmin">';    
    $sql_server = DB::Query("SELECT id, nazov, img FROM `cstrike`.`servers` ORDER BY id");
    $temp = new Member;
	$data = new Member;
	
    while($server = $sql_server->fetch_assoc() )    {
    echo '<tr>
            <td valign="middle" width="50" class="rank_border cursor">',
            	Buttons::ServerAvatar($server),
            '</td>
            <td class="rank_border" width="230">
                <div class="rank_srvname">
                    <a href="', ROOT, 'rank-admin/server/', BaseSTR::uri_out($server['nazov']), '/"> ', $server['nazov'], ' </a>';
                    $sql = rank_server_sql($server['id'], 3);
                        
                    for($i=1; $i <= 3; $i++) {                        
                        if($i === 1 ) {
                            $temp->next($sql);
                        } else {    
                            if($data->next($sql)) {
                                $data->setout('user_name');
								echo '<div class="info_gray">
                                        <img align="absmiddle" border="0" alt="', $i, '. miesto" title="', $i, '. miesto" src="', ROOT, 'web2/images/trophy_', $i, '.png"/>
                                        <a href="', $data->Link($href), '">', $data->user_name, '</a> - <span class="info_gray">Hodnotenie: ', round($data->znamka, 2), '</span>
                                    </div>';
                            }
                        }
                    }
                echo '    
                </div>
            </td>
            <td class="rank_border rank_srvtopadmin">';
            if($temp->exist()) {                
                rank_user_panel($temp); 
            }    
        echo '    
            </td>
        </tr>';    
    }        
    echo '</table>';
}
?>