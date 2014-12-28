<?

class HLServer 
{
	// gameserver data
	public $infos = array();
	public $infos2 = array();
	public $rules = array();
	public $rules2 = array();
	public $players = array();
	public $numofplayers = 0;  
	
	// misc
	protected $challengenumber = '';
	protected $error = '';
	protected $connected = false;
	protected $socket;

	public function Connected() {
		return $this->connected;  
	} 
	public function Error() {
		return $this->error;  
	}
	protected function command($command, &$data) {
		// verify we are connected
		if (!$this->connected) return false;
		// send command
		fwrite($this->socket,$command);
		// server return
		do {
			$data .= fread($this->socket, 128);
			$socketstatus = socket_get_status($this->socket);
		} while ($socketstatus["unread_bytes"]);
		return true;
	}
	//	get a challenge number from the server, needed for rules & players
	protected function getchallengenumber() {
		$challengenumber_temp = '';
		if (!$this->command("\xFF\xFF\xFF\xFF\x57\x00", $challengenumber_temp)) { 
			$this->error = 'Error on getting challenge number'; 
			return false; 
		}
		$this->challengenumber = trim(substr($challengenumber_temp,5)) . "\x00";
		return true;
	}
	//	verifiy that challenge number has been gotten and get it otherwise
	protected function verifychallengenumber() {
		if ($this->challengenumber == '') {
			if (!$this->getchallengenumber()) { return false; }
		}
		return true;
	}
	protected function readstr($string, &$i) {
		$start = $i;
		$strlen = strlen($string);
		for ($i; ($i < $strlen) && ($string{$i} != chr(0)); $i++);
		$result = substr($string, $start, $i-$start);
		$i++;
		return $result;
	} 
	public function HLServer($host, $port, $timeout=2) {
		// Deconnect from server
		if ($this->connected) fclose($this->socket);
		$this->connected = false;
		$errors = array('', '');
		$fp = fsockopen(('udp://'.$host), $port, &$errors[0], &$errors[1], $timeout);

		// error control
		if (!$fp) {
			$this->error = 'Socket error: '.$errors[0].$errors[1];
			fclose($fp);
			return false;
		} else {
			// blocking mode
			socket_set_blocking($fp, 1);
			socket_set_timeout($fp, $timeout);
			$this->socket = $fp;
			$this->connected = true;
			return true;
		}
	}
	public function rcon($password, $rcon_cmd, &$buffer) 
	{
		// Command is too long (240 max)'; return false; 
		// retrieve rcon number from server
		$this->command("\xFF\xFF\xFF\xFFchallenge rcon\x00", $temp);
		$temp = trim(substr($temp,19));		
		// send rcon command
		$this->command('\xFF\xFF\xFF\xFFrcon'.' '.$temp.' "'.$password . '" '.$rcon_cmd, $buffer);
		$buffer = trim( substr($buffer, 6) );
	}
	public function get_pocethracov()
	{
		// command
		$infos_brut = '';
		if(!$this->command("\xFF\xFF\xFF\xFF\x54\x53\x6F\x75\x72\x63\x65\x20\x45\x6E\x67\x69\x6E\x65\x20\x51\x75\x65\x72\x79\x00", $infos_brut)) return false;
		$infos_brut{5};
		$i = 6;
		$this->infos[0] = $this->readstr($infos_brut, $i);
		$this->infos[1] = $this->readstr($infos_brut, $i);
		$this->infos[2] = $this->readstr($infos_brut, $i);
		$this->infos[3] = $this->readstr($infos_brut, $i);	
		$this->infos[4] = $this->readstr($infos_brut, $i);
		$this->infos[5] = ord($infos_brut{$i++});
		$this->infos[6] = ord($infos_brut{$i++});
		return true;
	}
	public function get_infos() {
		// command
		$infos_brut = '';
		if(!$this->command("\xFF\xFF\xFF\xFF\x54\x53\x6F\x75\x72\x63\x65\x20\x45\x6E\x67\x69\x6E\x65\x20\x51\x75\x65\x72\x79\x00", $infos_brut)) return false;

		// determine server protocol
		$i = 4;
		$infos_brut{$i++};
		$i = 6;

		$this->infos[0] = $this->readstr($infos_brut, $i);
		$this->infos[1] = $this->readstr($infos_brut, $i);
		$this->infos[2] = $this->readstr($infos_brut, $i);
		$this->infos[3] = $this->readstr($infos_brut, $i);	
		$this->infos[4] = $this->readstr($infos_brut, $i);
		$this->infos[5] = ord($infos_brut{$i++});
		$this->infos[6] = ord($infos_brut{$i++});
		$this->infos[7] = ord($infos_brut{$i++});
		$this->infos[8] = $infos_brut{$i++};
		$this->infos[9] = $infos_brut{$i++};
		$this->infos[10] = ord($infos_brut{$i++});
		$this->infos[11] = ord($infos_brut{$i++});
		$this->infos[12] = $this->readstr($infos_brut, $i);
		$this->infos[13] = $this->readstr($infos_brut, $i);
		$this->readstr($infos_brut, $i);
		$this->infos[14] = ord($infos_brut{$i++} . $infos_brut{$i++} . $infos_brut{$i++} . $infos_brut{$i++});
		$this->infos[15] = ord($infos_brut{$i++} . $infos_brut{$i++} . $infos_brut{$i++} . $infos_brut{$i++});
		$this->infos[16] = ord($infos_brut{$i++});
		$this->infos[17] = ord($infos_brut{$i++});
		$this->infos[18] = ord($infos_brut{$i++});
		$this->infos[19] = ord($infos_brut{$i++});

		// assign number of players (alias)
		$this->numofplayers = $this->infos2['players'];
		return true;
	}
	public function get_players() {
		// check if challenge number has been gotten
		if(!$this->verifychallengenumber()) return false;
		// command
		$this->players_brut = array();
		if(!$this->command("\xFF\xFF\xFF\xFF\x55" . $this->challengenumber, $this->players_brut)) return false;
		// clear header
		$j = 5;
		// re-get&set nb of players (useful if parsing infos is off)
		$this->numofplayers = ord($this->players_brut{$j++});
		$this->players = array();
		
		for ($i=0;$i<($this->numofplayers);$i++)
		{
			$id = ord($this->players_brut{$j++});
			if(!$id) break; // neviem ci nebude narusat poazicie,...
			//player's id
			$this->players[$i][3] = ord($this->players_brut{$j++});
			//nick
			$this->players[$i][0] = htmlspecialchars( $this->readstr($this->players_brut, $j) ); 
			// frags
			$this->players[$i][1] = ord($this->players_brut{$j++} . $this->players_brut{$j++} . $this->players_brut{$j++} . $this->players_brut{$j++});	
			// playingtime
			$tmptime = @unpack('ftime', $this->players_brut{$j++} . $this->players_brut{$j++} . $this->players_brut{$j++} . $this->players_brut{$j++});
			$this->players[$i][2] = round($tmptime['time'], 0) + 82800;
		}
		// trim players list
		return true;
	}
	public function get_rules() {
		if(!$this->verifychallengenumber()) return false;
		// command
		$rules_brut = '';
		if(!$this->command("\xFF\xFF\xFF\xFF\x56" . $this->challengenumber, $rules_brut)) return false;
		// clear header
		$j = 5;
		$this->rules_nb = ord($rules_brut{$j++} . $rules_brut{$j++});

		for ($i=0;$i<20;$i++) {
			$this->readstr($rules_brut, $j);
			$this->rules[$i] = $this->readstr($rules_brut, $j);
		}
		return true;
	}
	
	// if($player_sorting) usort($this->players, $player_sorting);
	function name_desc($a, $b) {   
		if (ord(strtolower($a[0])) == ord(strtolower($b[0]))) return 0;
		return (ord(strtolower($a[0])) > ord(strtolower($b[0]))) ? -1 : 1;
	}
	function name_asc($a, $b) {   
		if (ord(strtolower($a[0])) == ord(strtolower($b[0]))) return 0;
		return (ord(strtolower($a[0])) < ord(strtolower($b[0]))) ? -1 : 1;
	}
	function frag_desc ($a, $b) {   
		if ($a[1] == $b[1]) return 0;
		return ($a[1] > $b[1]) ? -1 : 1;
	}	
	function frag_asc($a, $b) {   
		if ($a[1] == $b[1]) return 0;
		return ($a[1] < $b[1]) ? -1 : 1;
	}
}
