<?	 // 	++++++++++++++++++++++++++++++++++++++++++++ Seky`s Liga System ++++++++++++++++++++++++++++++++++++++++++++++++++

// Zoznam aktivovanych pluginov	
class SLSPlugins
{
	public static $plugin;
	protected static $plugins = array(
		//  	URL nazov	  			 Popis								
		 'info',
		 'rank',			// zoznam najlepsich clanov
		 'registrovat',		// Clan registr&aacute;cia:
		 'admin', 			// admin panel
		 'nastavenia', 		// uprava nastaveni
		 'clan',			// Informacie o clane,profil clan
		 'poslat-pozvanku',	// Poslat pozvanku hracovy
		 'prijat-pozvanku',	// Prijme pozvanku
		 'ziadat-pozvanku',	// ak v profile clanu klikne vstupit
		 'vyzvy',			// Zoznam vyzviev podla datumu
		 'prijat-vyzvu',	// Clan leader prijme vyzvu vo web poste
		 'poslat-vyzvu',	// V profile klikne na tlactiko a da vyzvu
		 'volne-miesta',	// Clany ktore maju volne miesta
		 'nastavenia-hraci',// Clan leader upravuje a hlada hracov
		 'nastavenia-vyvy',	// Clan leader upravuje svoje vyzvy
		 'zapas',			// Zapas info
		 'historia',		// Zoznam zapasov
		 'zapasy' 			// Najblizsie zapasi
	);	
	
	public static function Load($co) {		
		if(!isset($_GET[$co])) {
			Engine::Presmeruj(self::Adresa(0));
			return FALSE;	
		}
		$x = self::Find($_GET[$co]);
		if($x === -1) {			
			Engine::Presmeruj(self::Adresa(0));
			return FALSE;	
		}
		
		// Existuje
		self::$plugin = $x;
		$cesta = SLSSYSTEM.'plugins/'. self::$plugins[$x] . '.php';
		if(!file_exists($cesta)) {
			die('Plugin not found !');
			return FALSE;	
		}
		return $cesta;
	}
	protected static function Find($co) {
		$pocet = count(self::$plugins);
		for($i=0; $i < $pocet; $i++) {										
			if(self::$plugins[$i] == $co) return $i;
		}
		return -1;
	} 
	public static function Adresa($id) {
		return SLSROOT.self::$plugins[$id]."/";
	}	
	public static function Self() {
		return self::Adresa(self::$plugin);
	}	
}