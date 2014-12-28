<?php
if (!defined("IN_FUSION")) { header("Location: index.php"); exit; }

$farba = true;

function mini_status(&$data) {	 		
	if ( !$gameserver = new HLServer($data['ip'], $data['port'])) {
		$data['hracov'] = '0/<strong>0</strong>';	
		$data['mapa'] = '';	
	} else { 
		// Bug
		$gameserver->get_infos();
		$temp = $gameserver->infos[5];
		if($temp) {
			$data['mapa'] = $gameserver->infos[2];
			$data['hracov'] = $gameserver->infos[5] . '/<strong>' . $gameserver->infos[6].'</strong>';
		} else {
			$data['hracov'] = '0/<strong>0</strong>';	
			$data['mapa'] = '';	
		}
	}		
}
	
function ServerThemeList() { // Nezadal konkretne
	Header::Title('Servery');
	$sql = DB::Query("SELECT s.*, user_name FROM `cstrike`.`servers` s
							LEFT JOIN ( SELECT user_name, user_id FROM `cstrike`.`fusion_users` ) u ON s.headadmin = u.user_id
						");
	echo '<table cellspacing="0" cellpadding="0" border="0" width="100%">';
	
	while($data = $sql->fetch_assoc()) {
		mini_status($data);
		echo '
		<tr class="tblborder">
			<td class="srvavatar">
				', Buttons::ServerAvatar($data), '
			</td>
			<td>
				<div class="srvname">
					<a href="', ROOT, 'server/', $data['id'], ',', BaseSTR::uri_out($data['nazov']), '/">', $data['nazov'], ' </a><br/>
					<span class="info_gray"> Adresa servera: ', $data['ip'], ':', $data['port'], ' <br/>Headadmin: </span>
					<span class="srvname_sv">
					<a href="'.ROOT.'hrac/', BaseSTR::uri_out($data['user_name']), '/">', $data['user_name'], '</a></span>
				</div>
			</td>
			<td class="srvplyr">', $data['hracov'], '</td>
			<td><div class="srvmap">', $data['mapa'], '</div></td>
		</tr>';
	}
	echo '</table>';
}	

function ServerThemeItem($id) {	// Mame port		
	
    $sql = DB::Query("SELECT * FROM `cstrike`.`servers` s
							LEFT JOIN ( SELECT user_name, user_id FROM `cstrike`.`fusion_users` ) u ON s.headadmin = u.user_id
						WHERE id = '".DB::Vstup($id)."'");
	
	if(!$sql->num_rows) {
		Mess::Alert('Server', 'Server nen&aacute;jden&yacute;...');
		return;
		/*$sql = DB::Query("SELECT * FROM `cstrike`.`servers` s
							LEFT JOIN ( SELECT user_name, user_id FROM `cstrike`.`fusion_users` ) u ON s.headadmin = u.user_id
						WHERE port = '27015'");	// default port*/
	}
	$data = $sql->fetch_assoc();					
	Header::Title($data['nazov']);		
	global $farba;
    $farba = true;
	
	if ( !$gameserver = new HLServer($data['ip'], $data['port'])) {
		$data['hracov'] = '0/<strong>0</strong>';	
		$data['mapa'] = '';	
	} else { 
		$gameserver->get_infos();
		$gameserver->get_players();
		// Bug
		$temp = $gameserver->infos[5];
		if($temp) {
			$data['mapa'] = $gameserver->infos[2];
			$data['hracov'] = $gameserver->infos[5] . '/<strong>' . $gameserver->infos[6].'</strong>';
		} else {
			$data['hracov'] = '0/<strong>0</strong>';	
			$data['mapa'] = '';	
		}
	}
    ServerThemeItemHeader($data);
    	
    // Status hracov 
	$farba = true;
    sheader('Online hr&aacute;&#269;i');
	$i=0;
	while($gameserver->players[$i]) {
		$i++;
		spanel($gameserver->players[$i][0], $gameserver->players[$i][2], $gameserver->players[$i][1]);
	}
	echo '</table>';
	
	if(!$data['liga']) {
        // Kandidaty 
        sheader('Kandit&uacute;ra na servery');
		$sql = DB::Query("SELECT u.user_name, u.user_id, COALESCE(hlasov, 0) AS hlasov, `start` FROM `cstrike`.`kandidati` k					
						JOIN ( SELECT user_name, user_id, user_icq, user_groups, vip, slot FROM `cstrike`.`fusion_users` ) u
                            ON k.user = u.user_id    
                        LEFT JOIN ( SELECT id, COUNT(id) AS hlasov FROM `phpbanlist`.`web2_hlasovanie` WHERE `typ`='4' GROUP BY id ) h
                            ON k.id = h.id							
						WHERE k.server = '".$data['id']."'
						ORDER BY hlasov DESC");
					
		$farba = true;
		while($buffer = $sql->fetch_assoc()) {
			spanel('<a href="'.ROOT.'kandidovat/kandidat/'.BaseSTR::uri_out($buffer['user_name']).'/">'.$buffer['user_name'].'</a>',  date("Y-m-d", $buffer['start']), $buffer['hlasov']);
		}
		echo '</table>';
	}

	// Nakupene VIP
    sheader('Zak&uacute;pene VIP');
	$sql = DB::Query("SELECT user_name, vip FROM `cstrike`.`fusion_users` WHERE vip IS NOT NULL ORDER BY vip DESC");
	$farba = true;
	while($buffer = $sql->fetch_assoc()) {
		spanel('<a href="', ROOT, 'hrac/'.BaseSTR::uri_out($buffer['user_name']).'/">'.$buffer['user_name'].'</a>', date("Y-m-d", $buffer['vip']));
	}
	echo '</table>';
	

	// Nakupene Sloty 
    sheader('Zak&uacute;peny SLOT');
	$sql = DB::Query("SELECT user_name, slot FROM `cstrike`.`fusion_users` WHERE slot IS NOT NULL ORDER BY slot DESC");
    $farba = true;
	while($buffer = $sql->fetch_assoc()) {
		spanel('<a href="'.ROOT.'hrac/'.BaseSTR::uri_out($buffer['user_name']).'/">'.$buffer['user_name'].'</a>', date("Y-m-d", $buffer['slot']));
	}
	echo '</table>
	<br><br><br><br>';

}
function spasik() {
    global $farba;
    $farba  = !$farba;
    if($farba) { return 'class="server_status_pasik"';} 
}
function spanel($a, $c, $b = '&nbsp') {
    echo '
    <tr ', spasik(), '>
        <td>', $a, '</td>
        <td width="50" align="center">', $b, '</td>
        <td width="110" align="center">', $c, '</td>
    </tr>';
}
function sheader($a) {
    echo '
    <br>
    <table width="100%" cellspacing="0" cellpadding="2" class="bb">
        <tr><td width="100%" align="left" colspan="3">
            <strong>', $a, '</strong>
        </td></tr>
    </table>
    
    <table class="shadow_list" width="100%" cellspacing="1" cellpadding="2">';
}
function ServerThemeItemHeader($data) {
    echo '
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td class="srvavatar">
				', Buttons::ServerAvatar($data), '
			</td>
            <td>
                <div class="srvname">
                    <a href="', ROOT, 'server/', $data['id'], ',', BaseSTR::uri_out($data['nazov']), '/">', $data['nazov'], ' </a><br/>
                    <span class="info_gray"> Adresa servera: ', $data['ip'], ':', $data['port'], ' <br/>Headadmin: </span>
                    <span class="srvname_sv"><a href="', ROOT, 'hrac/', BaseSTR::uri_out($data['user_name']), '/">'.$data['user_name'], '</a></span>
                </div>
            </td>
            <td class="srvplyr">', $data['hracov'], '</td>
            <td><div class="srvmap">', $data['mapa'], '</div></td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" cellpadding="2" align="center">
        <tr>
            <td>
                <a href="', ROOT, 'obchod/#vip">
                    <img border="0" align="absmiddle" src="', ROOT, 'web2/images/tool/vip.png" alt="VIP" />
                    K&uacute;pi&#357; VIP 
                </a> 
                |
                <a href="' ,ROOT, 'obchod/#slot">
                    <img border="0" align="absmiddle" src="', ROOT, 'web2/images/tool/goldkey.png" alt="Slot" />
                    K&uacute;pi&#357; SLOT 
                </a>
                |
                <a href="', ROOT, 'kandidovat/ziadost/">
                    <img align="absmiddle" border="0" src="', ROOT, 'web2/images/tool/plus1.png" alt="Prida&#357;" />
                    Nap&iacute;sa&#357; &#382;iados&#357; na admina
                </a>
            </td>
        </tr>
    </table>';
}
?>
