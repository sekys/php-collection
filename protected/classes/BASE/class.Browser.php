<?	

class Browser 
{
	// Informacie o aktualnom
	private static $info = array("version" => "0.0.0",
                                "name" => "",
                                "agent" => "",
                                "platform" => "");
			   																
    private static $bot; 
    public static $ListOfBrowsers = array(
			"firefox", "msie", "opera", "chrome", "safari",
			"mozilla", "seamonkey", "konqueror", "netscape",
			"gecko", "navigator", "mosaic", "lynx", "amaya",
			"omniweb", "avant", "camino", "flock", "aol"); 	

	public static function UniqueID() { return md5($_SERVER['HTTP_USER_AGENT']); }
	public static function Get($data) { return self::$info[$data]; }	

    public static function SetInfo() {
		if(self::$info["name"]) return;
		self::$info["agent"] = $agent = strtolower($_SERVER['HTTP_USER_AGENT']);		
        foreach(self::$ListOfBrowsers as $browser)  {
            if (preg_match("#($browser)[/ ]?([0-9.]*)#", $agent, $match)) {
                self::$info["name"] = $match[1] ;
                self::$info["version"] = $match[2] ;
                break;
            }
        }
		if (preg_match('/linux/', $agent)) {
            self::$info["platform"] = 'linux';
        } elseif (preg_match('/macintosh|mac os x/', $agent)) {
            self::$info["platform"] = 'mac';
        } elseif (preg_match('/windows|win32/', $agent)) {
            self::$info["platform"] = 'windows';
        }
	}    
    public static function StaticInit() { 
        self::SetInfo();
        self::TestBot(); 
    }
    public static function is_bot() {
        /* Funkcia zisit ci je to google, yahho bot, crawler  apod.
            Viuzitie :
                - Vypneme cahce
                - Ajax povolime za kazdej okolnost
                - Zmenime stranku atd atd..
        
        */
        return self::$bot;
    }
    private static function TestBot() {
        // Ak ma cookies povolene tak bot to nieje :)
        if(isset($_COOKIES['fusion_visited']))  {
            self::$bot = false;
            return;
        }
        self::$bot = (!self::$info["name"]);
    }
    public static function SeoRovnakaStranka($url) {
        // Toto sa pouziva ked nejaka stranka ma podobny obsah ako ina
        // A 1. stranka dostane lepsie rank a 2. nebude zaznamenana co je vlemi uzitocne aj nie ....
        return '<link rel="canonical" href="'.$url.'"/>';
    }
    public static function isAjax() { return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'); }
    public static function isHomeReferer() { return strpos($_SERVER['HTTP_REFERER'], 'cs.gecom.sk'); }	
    public static function ip() { return $_SERVER['HTTP_USER_IP']; }
    public static function isGoogleBot() { return (bool) substr_count($_SERVER['HTTP_USER_AGENT'], 'Googlebot'); }
    public static function referrer() {
        return ( ! isset($_SERVER['HTTP_REFERER']) OR $_SERVER['HTTP_REFERER'] == '') ? '' : trim($_SERVER['HTTP_REFERER']);
    }
    public static function charsets() {    
         if(isset($_SERVER['HTTP_ACCEPT_CHARSET']) AND $_SERVER['HTTP_ACCEPT_CHARSET'] != '') {
             $charsets = preg_replace('/(;q=.+)/i', '', strtolower(trim($_SERVER['HTTP_ACCEPT_CHARSET'])));            
             return explode(',', $charsets);
         }
         return '';
    }
    public static function accept_charset($charset = 'utf-8') {
         return (in_array(strtolower($charset), self::charsets(), TRUE)) ? TRUE : FALSE;
    }
    public static function Language() { 
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) AND $_SERVER['HTTP_ACCEPT_LANGUAGE'] != '') {
             $languages = preg_replace('/(;q=[0-9\.]+)/i', '', strtolower(trim($_SERVER['HTTP_ACCEPT_LANGUAGE'])));            
             return explode(',', $languages);
        }
        return '';
    }
} 
?>