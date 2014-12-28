<?
// Teraz chceme mat zapnute inak do jednotlivych suborov ...


if(!DEBUG)  {
	// Debug nemame povoleny ...nic nerobyme :)
	class Debug {	
		public static function Start() { }
		public static function Descrutor() { }
		public static function Oblast($nazov, $stats=false) {}
		public static function Kroky() { }
		public static function dvar($var) { }
		public static function Info() { }
		public static function Ram() { }
		public static function Ram_format($mem_usage) {	}	
		public static function Ram_format2($mem_usage) { }
		public static function Cpu() { }
		public static function Globalne() {}
		public static function out($txt) { }
		public static function Output($txt) { }
		// Prida spravu na konci debugu
		//public static function Msg($txt) { }
	}
} else {	
	/*	0- je zaznamenany cas
		1 - stav ram
		2- rozdiel ram
		3 - stav cpu
		4 - rozdiel cpu

		Pri testovani:
		DEBUG_OBLAST zabera od 0.0060 do 0.032 PRE aj POST oblasti su skoro rovnake.
		Priemerne 0.007 co je velmi dobre...
	*/
	
	class Debug extends HardwareUsage
	{
		// Const
		const HOBLAST = 'CELA STRANKA'; // nazov hlavenj oblasti
		private static $g_oblasti;
		private static $out_function = 'Debug::DefaultOutput';
		
		public static function Start() { }
		public static function StaticInit() {
			self::Input();
			self::Oblast(self::HOBLAST);
			// Ak mame constructor treba aj deconstructor :)
			register_shutdown_function('Debug::Descrutor');
		}
		public static function Descrutor() {
			static $bool; 
			// Ak chceme mozme DEBUG ukoncit skor
			if($bool) { return false; }
			$bool = true;		
			// Volany na a konci stranky a iba raz
			self::Oblast(self::HOBLAST);
			self::VypisOblasti(self::HOBLAST);
		}
		public static function Oblast($nazov, $stats=false) {	
			// Velmi pekne som to spravyl :)
			$nazov = strtoupper($nazov);
			
			if(!isset(self::$g_oblasti[$nazov][0])) {
				self::out('START - '.$nazov.' - START');
				self::_Time(self::$g_oblasti[$nazov][0]);
				self::$g_oblasti[$nazov][2] = self::Ram();
				//self::$g_oblasti[$nazov][3] = self::Cpu();
			} else {
				// Nastavyme
				self::$g_oblasti[$nazov][0] = self::_Time(self::$g_oblasti[$nazov][0]);
				self::$g_oblasti[$nazov][1] = self::Ram_format(self::Ram());
				self::$g_oblasti[$nazov][2] = self::Ram_format2(self::Ram() - self::$g_oblasti[$nazov][2]);
				//self::$g_oblasti[$nazov][3] = self::Cpu();
				//self::$g_oblasti[$nazov][4] = self::Cpu() - self::$g_oblasti[$nazov][3];
				if(!$stats) {
					self::out('END - '.$nazov.' - END');
					return true;							
				} 
				
				// + Statistyky
				$data = "\n\tStats:\n";
				$data .= "\t\tLoading: ".self::$g_oblasti[$nazov][0]."sec\n";
				$data .= "\t\tRAM: ".self::$g_oblasti[$nazov][1]."\n";
				$data .= "\t\t+/-RAM: ".self::$g_oblasti[$nazov][2]."\n";
				//$data .= "\t\tCPU: ".self::$g_oblasti[$nazov][3]."%\n";
				//$data .= "\t\t+/-CPU: ".self::$g_oblasti[$nazov][4]."%\n";
				$data .= "\tEND - ".$nazov." - END\n";
				self::out($data);
			}
			return true;
		}
		private static function Input()  {
			$save = "\n Adresa: ".$_SERVER['PHP_SELF']."\n\n";
			$save .= " INPUT:\n";			
		// GET
			$pocet = 0;
			$save .= "\n\tGET: \n";
			foreach($_GET as $key => $i){
				$save .= "\t\t".$key."=".$_GET[$key]." \n";
				$pocet++;
			}
			$save .= "\tCelkovo ".$pocet.".\n";	
		// SESSION
			Session::Start();
			$pocet = 0;
			$save .= "SESSION: \n";
			foreach($_SESSION as $key => $i) {
				$save .= $key."=".$_SESSION[$key]." \n";
				$pocet++;
			}
			$save .= "\n...celkovo ".$pocet." .\n";
		// COOKIE
			$pocet = 0;
			$save .= "\n\tCOOKIE: \n";
			foreach($_COOKIE as $key => $i){
				$save .= "\t\t".$key."=".$_COOKIE[$key]." \n";
				$pocet++;
			}
			$save .= "\tCelkovo ".$pocet.".\n";			
		// Post
			$pocet = 0;
			$save .= "\n\tPOST: \n";
			foreach($_POST as $key => $i){
				$save .= "\t\t".$key."=".$_POST[$key]." \n";
				$pocet++;
			}
			$save .= "\tCelkovo ".$pocet.".\n";		
		// Globalne
			$save .= "\n\tCelkovo ".count($GLOBALS)." GLOBALNYCH. \n";
			self::out($save);	
		}
		public static function Kroky() {
			// Velmi podrobny debug o kazdom jednom kroku .....nebezspecne pre ludi
			echo "<pre>"; debug_print_backtrace(); echo "</pre>";
		}
		public static function dvar($var) {
			echo "\n <!--PREMENNA: \n"; if(is_array($var)) { print_r($var); } else { var_dump($var); }	 echo "\n-->\n";
		}
		public static function Info() { phpinfo(); }
		public static function VypisOblasti($oblast=false) {
			$save = "Zhrnutie: \n\n Oblasti:\n";			
			// Zistime maximum				 vzhladom_na_oblast ? 
			$max = $oblast ? self::$g_oblasti[$oblast][0] : self::TimeMax();
			if($max==0) return false; 		
			// Oblasti ... 
			$pocet = $sucet = 0;
			$save .= sprintf("%15s%15s%15s%15s%15s\n", // %15s%15s
			"Nazov", "Loading", "Loading %","Stav RAM", "+/- RAM"); //, "Stav CPU", "+/- CPU"
			
			foreach(self::$g_oblasti as $key => $i) {			
				if(isset($i[1])) { // je oblast ukoncena ?
					$save .= sprintf("%15s%15s", $key, $i[0])."sec";	// cas
					$save .= sprintf("%15s", number_format((100*$i[0]) / $max, 1, '.', ''))."%"; //% casu
					$save .= sprintf("%15s", $i[1]); // stav ramky
					$save .= sprintf("%15s", $i[2]); // +/- ramky
					//$save .= sprintf("%15s", $i[3]."%"); // stav cpu
					//$save .= sprintf("%15s", $i[4]."%"); // +/- cpu
					$save .= "\n";
					if($oblast != $key) {
						$sucet += $i[0];
						$pocet++;
					}
				} else {
					$save .= sprintf("%15s", $key)." oblast nieje ukoncena.\n";
				}
			}
			// INA oblast
			$temp = $max - $sucet;
			$save .= sprintf("%15s%15s", "Nezaznamene", $temp)."sec";	// cas
			$save .= sprintf("%15s", number_format((100*$temp) / $max, 1, '.', '')."%\n"); //% casu			
			$save .= "\n\tSpolu ".$sucet." sec na ".$pocet." oblasti.\n";				
			// Footer
			if($oblast) {
				$save .= "\tVzhladom na oblast: ".$oblast."\n";
			}
			self::out($save);
		}
		private static function TimeMax() {
			// Vracia cas za ktory sa nacitala stranka ....
			// Ak sa to zavloa skor ako je uplny koniec tak to vrati cas po poslednu oblast
			$sucet = 0; 		
			foreach(self::$g_oblasti as $key => $i) if($i[1]) $sucet += $i[0]; // Je oblast ukoncena ?	
			return $sucet;	
		}	
		public static function Globalne()  {
			// Globalne, pozor aby uzivatel to nevidel !!!!!
			$save = "\n GLOBALNE: \n";
			foreach($GLOBALS as $key => $i) $save .= "\t\t".$key."=".$GLOBALS[$key]." \n";	
			self::out($save);
		}
		// Pomocne funkcie
		public static function Output($fun) { self::$out_function = $fun; }
		public static function out($txt) {  call_user_func(self::$out_function, $txt); }
		public static function DefaultOutput($txt) { echo "\n<!-- "; echo $txt;  echo " -->\n";  }
		public static function Test()  {
			// Je to debug na DEBUG a zistujeme kolko debug ubera %
			self::Oblast('debug_oblast_pre');
			self::Oblast('test');
			self::Oblast('debug_oblast_pre');
			self::Oblast('debug_oblast_post');
			self::Oblast('test');
			self::Oblast('debug_oblast_post');
		}
	}
}

?>