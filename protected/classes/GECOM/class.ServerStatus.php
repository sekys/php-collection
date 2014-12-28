<?

class ServerStatus
{
	protected static function ServerData($id) {	 		
	    // Servery	
		$sql = DB::Query("SELECT ip, port, skratka, nazov, id FROM `servers` WHERE `id`='".$id."'");    
	    $server = $sql->fetch_row();

	    $data[0] = $server[2];
	    $data[1] = $server[0].':'.$server[1];   
	    $data[4] = $server[3];   
	    $data[5] = $server[4];   
		
		if ( !$gameserver = new HLServer($server[0], $server[1]) ) {
			$data[2] = 'Offline';	
			$data[3] = '-';	
			$data[4] = '-';	
		} else { 
			$gameserver->get_infos();
			$data[3] = $gameserver->infos[2];
			//$data[4] = $gameserver->serv_rules[ 0 ]; // alebo 4
			$data[2] = $gameserver->infos[5] . '/' . $gameserver->infos[6];
		}		
		return $data;
	}
	public static function Render($id) {
		/*
			$server[0] meno
			$server[1] ip
			$server[2] hraci 
	        $server[3] mapa
			$server[4] plne meno
		*/
		$server = self::ServerData($id);
		echo '		
		<div class="header-server">
			<img src="', self::Mapa($server[3]), '" alt="'.$server[3].'" title="'.$server[3].'" width="160" height="120" />
			<div class="info">
			', $server[0], '<br>
			<strong>', $server[1], '</strong><br>
			Hr&aacute;&#269;ov ', $server[2], '<br>
			Mapa ', $server[3], '<br>
			Headadmin: ', 'lol', '<br><br>';
			
			$url = ROOT.'server/'.$server[5].','.BaseSTR::uri_out($server[4]).'/';
	        Buttons::SteamSmall('steam://connect/'.$server[1]);
			Buttons::Lupa($url);
			Buttons::HLSW($server[1]);
		echo '
			</div>
		</div>';
	}
	public static function Mapa($mapa) { 
		return 'http://image.www.gametracker.com/images/maps/160x120/cs/'.$mapa.'.jpg'; 
	}
}
