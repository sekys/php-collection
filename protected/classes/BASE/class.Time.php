<? 

class Time 
{
	public static $cas = array( // Musia byt rovnakej velkosti
		array( 'sec', 'min', 'h', 'd', 't', 'm', 'r'), // nazvy
		array( '1', '60', '60', '24', '7', '4.35', '12') // na vypocet
	);	
	public static $TIME, $DNESOK, $DEN, $ONLINE;
	
	public static function StaticInit() {
		self::$TIME = time();
		self::$DNESOK = mktime(0, 0, 0);
		self::$DEN = 60*60*24;
        self::$ONLINE = self::$TIME - 600; // 10min
	}
    public static function Dni($i) {
        return 1*60*60*24*$i;
    }
	public static function Gentime(&$a) {
		if($a == 0) $a = microtime(true);
		else $a = microtime(true)-$a;
	}
	public static function Rozdiel($rozdiel, $i=6)  {// od akeho zacne pocitat ?	
		for($a=$i; $a > -1 and $rozdiel > 0; $a--) {
			// Dany cas je nasobok predchadzajucich
			$temp = 1;
			for($j=$a; $j > -1; $j-- ) {
				$temp *= self::$cas[1][$j];
			}
			$pocet = $rozdiel / $temp;
			if( $pocet >= 1.0) {
				$pocet = floor($pocet);	
				$vysledok .= $pocet.self::$cas[0][$a].' ';	// zvysok
				$rozdiel -= $temp*$pocet; // zvysok     
			}
		}
		return $vysledok;
	}
	public static function DniRozdiel($datum) 	{
		return floor(( $datum - mktime(0, 0, 0) ) / 86400);
	}
}
?>