<?

class Engine
{
	protected static $bol, $compression, $calls = array(array(), array());
	
	// Pomocky
	public static function Adresa() {
		return isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != "" ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME'];
	}
	public static function Destroy() { self::$bol = true; ob_end_flush(); }
	public static function CExit($error) { self::$bol = true; ob_end_clean(); echo $error; exit; }
	public static function SetCallback($func, $t=0) { self::$calls[$t][] = $func; }

	public static function Compression($zlib = true, $self = false) { 
        ini_set('zlib.output_compression', $zlib ? 'On' : 'Off');
        self::$compression = $self;
    }
	public static function Presmeruj($www) { 
        if (!headers_sent()) { 
			header("Location: ".$www);
			self::CExit(''); // Lebo posle presmerovanie ale server dalej ide
		} else {
			self::CExit("
			<script type='text/javascript'>document.location.href='".$www."'</script>
			<noscript><meta http-equiv='refresh' content='0;url=".$www."' /></noscript>"); 
		}
	}	
	// Hlavna cast
	public static function Start() {
		// Start engine
        if(DEBUG) {
            error_reporting(E_ALL^E_NOTICE);
            //$info = $_SERVER ["HTTP_HOST"] . "-" . $_SERVER ["REMOTE_ADDR"] . "-" . $_SERVER["REQUEST_METHOD"]."-" . str_replace ("/", "|", $_SERVER ["REQUEST_URI"]);
            //@ini_set("error_log", "/home/cstrike/scripts/web/errors/php.err-".$info);       
        }
        ob_start();    // "ob_gzhandler" pre compresiu, ale lepsia je zlib.output_compression
		register_shutdown_function('Engine::Deconstructor');
	}
	public static function Deconstructor() {	
		// Funkcia sa spusta len raz....
		if(self::$bol) return;
        if(is_null($e = error_get_last()) === false) self::ErrorHandler($e);
        self::$bol = true;
		self::Page();
		Debug::Descrutor();
	}
    private static function ErrorHandler($e) {
        // nefunguje to dalsie volanie funkcii lebo script uz skoncil
        //if(DEBUG) {          
            print_r($e);
        //} else {
            // mail('your.email@example.com', 'Error from auto_prepend', print_r($e, true));
        //    self::CExit('Something is wrong, contact technical support.'); 
        //}
    }
    public static function GoHome() { self::Presmeruj(ROOT); }
    private static function Page() {
        Debug::Oblast('Engine::Page');   
        // Header
        $body = ob_get_contents();
        ob_end_clean();
        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head>';     
        self::RunCallback(0);
        echo '</head>'; 
        flush();
        // Body
        echo "<body>"; 
        self::Out($body);
        echo "</body>";
        flush();
        self::RunCallback(1);
        echo '</html>';
        flush(); 
        Debug::Oblast('Engine::Page');        
    }
    protected static function RunCallback($i) {
		if(is_array(self::$calls[$i])) {
            foreach(self::$calls[$i] as $b) call_user_func($b);
        }
    }
	public static function Out($txt) {
		//  zlib.output_compression is preferred over ob_gzhandler(). 
		// OFF compress
        if(self::$compression) self::_Compression($txt);
		echo $txt;
		/* ON compress  
			- pozor dviha ENGINE_DECONSTRUCTOR o 20%
			- zo 48kb ostalo len 44kb nic moc ...
		*/
		// echo self::Compression($txt);
	}
	private static function _Compression(&$txt) { 
        strtr($txt,  array("\t" => "", "\n" => "", "\r" => "")); 
    }
}
/*
// Be 100% sure the timezone is set
if (ini_get("date.timezone") === "" && function_exists("date_default_timezone_set")) {
    date_default_timezone_set("UTC");
}

*/
