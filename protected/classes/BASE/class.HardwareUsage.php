<?

class HardwareUsage 
{
	const PRESNOST = 5; 			// na kolko miest vypocitavat hodnoty

	public static function _Time(&$a) { // Toto potrebuje pointer 
		if($a == 0) $a = microtime(true);
		else return number_format(microtime(true)-$a, self::PRESNOST, '.', '');
		//round(microtime(true)-$a, 8); je nespolahlive
	}
	public static function Ram() { return memory_get_usage(true); }
	public static function Ram_format($mem_usage) {	
		if ($mem_usage < 1024) return  $mem_usage." b";
		elseif ($mem_usage < 1048576)
			return number_format($mem_usage/1024, self::PRESNOST, '.', '')." kb";
		else
			return number_format($mem_usage/1048576, self::PRESNOST, '.', '')." mb";				
	}	
	public static function Ram_format2($mem_usage) {		
		if($mem_usage < 0) {
			$mem_usage = -1 * $mem_usage;	// Absolutna hodnota
			$data = '-';
		} else {
			$data = '+';
		}
		return $data.self::Ram_format($mem_usage);
	}
	public static function Cpu() {
		$stats = exec('uptime');
		preg_match('/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/', $stats, $regs);
		//return $regs[1].', '.$regs[2].', '.$regs[3];
	}
	public static function Cpu2() {
		$stats = explode(' ', substr(exec('uptime'), -14));
		$av = round((($stats[0] + $stats[1] + $stats[2]) / 3)*100);
		return ($av);
	}		
}
?>