<?php
function info_status($m, $cesta) {
    // Status ...
    info_parser('acc', 'Stav &#382;iadosti');
    $pocet = DB::One("SELECT COUNT(id) as pocet FROM `phpbanlist`.`web2_hlasovanie` WHERE `typ`='4' AND `id`='".$m->user_id."'");
    echo '<table cellspacing="0" cellpadding="5" id="acc" width="100%" style="display:none;">
            <tr>
                <td align="right" class="voteadmin_td_left">Hlasovanie</td>
                <td class="voteadmin_td_right padding_left">Celkovo <strong>', $pocet, '</strong> hlasov ...</td>
            </tr>        
            <tr>
                <td align="right" class="voteadmin_td_left">Podan&aacute; &#382;iados&#357;</td>
                <td class="voteadmin_td_right padding_left">
                    <img alt="Cas" src="', ROOT, 'web2/images/tool/clock.png"/>
                    ', date("Y-m-d H:i:s", $m->start), '
                </td>
            </tr>        
            <tr>
                <td align="right" class="voteadmin_td_left">Status</td>
                <td class="voteadmin_td_right" >';
  
    // Ci uz ma prava ?
    $id = $m->amxid;
    if($id)  {
    	$data = Amx::AGet($id, '*');
    	$pocet = Amx::ServerCount($id);
    	if($pocet > 1) {
        	echo "Bol prijat&yacute; za admina.";  
		} else {
        	echo 'Admin na viacer&yacute;ch serveroch.';
		}
		$msg = 'Hr&aacute;&#269; adminuje od <strong>'.date("Y-m-d H:i:s", $data['created']).'</strong>';
        if($data['expired']) {
			$msg .= ' do <strong>'.date("Y-m-d H:m:s", $data['expired']).'</strong>';
        }
        info_status_log($msg);
    } else {
        echo '&#268;ak&aacute; na prijatie ...';
    }
    echo '</td></tr>';
    
    // Logy
    if($id) {        
        echo '
        <tr>
            <td align="right" class="voteadmin_td_left">Z&aacute;znamy</td>
            <td class="td.voteadmin_td_left voteadmin_td_right">';
                $sql = Amx::logsearch($m->cs_steam, 'time_stamp, username, remarks', 5);
				while($admin = $sql->fetch_assoc())     {
					 echo $admin['username'].': '.$admin['remarks'].' <strong>'.date("Y-m-d H:i:s", $admin['time_stamp']).'</strong><br>';
				}                           
            echo '
            </td>
        </tr>';
	}  
	// Info      
    echo '<tr>
    	<td align="right" class="voteadmin_td_left">Info</td>
        <td class="voteadmin_td_right">';
        if($id) {
        	echo ' 
        	<img alt="" title="Admin prijat&yacute;" src="', ROOT, 'web2/images/tool/ok.gif"/> 
            <img alt="" title="Admin pr&acute;va: ', $data['access'], '" src="', ROOT, 'web2/images/tool/flag.png"/>';	
		}
        echo '<img vspace="0" hspace="0" alt="" title="Odobran&eacute; peniaze za &#382;iados&#357;." src="', ROOT, 'web2/images/tool/coins_16x16.png"/>';
    echo '</td></tr>',

    kandidovat_action($m->id);
    echo '</table>';
}
function info_komentare($id, $cesta) {
    // Komentare
    global $komentar_id;
    $c = new Comments;
    $c->Set($komentar_id, 0, 0, $id, $cesta);
    $pocet = $c->Pocet();
    info_parser('kom', 'Koment&aacute;re - ('.$pocet.')'); 
    echo '
    <table width="100%" cellspacing="1" cellpadding="5" id="kom">
        <tr>
            <td align="center">';
            $c->Render();
    echo     '</td>
        </tr>
    </table>'; 
}
function info_hodnosti($id) {
    info_parser('hodnosti', 'Hodnos&#357;i');
    echo '<table cellspacing="0" cellpadding="5" id="hodnosti" width="100%" style="display:none;">';    
  
    $query = DB::Query("SELECT group_id, group_name, group_image FROM `cstrike`.`fusion_user_groups` ORDER BY group_id");
    while( $data = $query->fetch_assoc())
    {
        echo '<tr>';
        info_hodnosti_item($id, $data);
        echo '<td class="voteadmin_td_right" width="5px"> </td>';                
        if($data = $query->fetch_assoc()) {
            info_hodnosti_item($id, $data);
        } else {
            echo '<td class="voteadmin_td_right" colspan="2"> </td>';                
        }               
        echo'</tr>';
    }        
    echo '    
    </table>';
}
function info_hodnosti_item($id, $data) {
    echo '    
    <td align="right" class="voteadmin_td_left" title="', $data['group_description'], '">
        <img alt="', $data['group_image'], '" align="absmiddle" title="', $data['group_name'], '" src="', ROOT, 'images/ranks/', $data['group_image'], '">
        ';
        if($data['group_id'] == 31) {
            echo '<span style="text-decoration: blink; color: rgb(235, 129, 54)>"', $data['group_name'], '</span>';
        } else {        
            echo $data['group_name'];
        }
    echo '</td>
    <td class="voteadmin_td_right">
        <input type="checkbox" class="checkbox" disabled="disabled" ';
    if( !(strpos($id, $data['group_id']) === false)) { 
        echo 'checked="checked"';
    }
    echo ' />
    </td>';
}
?>
