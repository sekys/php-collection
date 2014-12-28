<?

/**
	It is a good idea to call session_write_close() before proceeding to a redirection using
	header("Location: URL");
	exit();
**/

class Session 
{
	protected static $started = false;
	
	# Kedze pouzivame ENGINE co posiela neskor HEADER, nemsime mat autostart
	public static function StaticInit() { 
		self::SessionConfig();
		session_start();
		session_cache_limiter('nocache');
		self::Save();
	}
	protected static function SessionConfig() { 
	    // sesiony svojim linkom ovplivnuju SEO
        //These commands must be set BEFORE the session is started
        ini_set('session.use_trans_sid', false);
        ini_set('session.use_only_cookies', true);
        ini_set('url_rewriter.tags', '');

        //ini_set('session.name', 'WORLD');			// specificke pre projekt aby na inej stranky nebolo rovnake
		/*
			ini_set('session.cookie_domain', $_SERVER['HTTP_HOST']);
			// After this number of seconds, stored data will be seen as 'garbage' and
			// ; cleaned up by the garbage collection process.
			// ini_set("session.gc_maxlifetime", "1800");  // 30min pre sessiony,...
			ini_set('session.referer_check', $_SERVER['HTTP_HOST']);
			
			//	0, until browser is restarted.
			//	Inac normalny cas na cookie.
			//ini_set('session.cookie_lifetime', 0);
			ini_set('session.cookie_httponly', true); // ; Whether or not to add the httpOnly flag to the cookie, which makes it inaccessible to browser scripting languages such as JavaScript.
		*/
	}
	public static function Save() { 
		// Uloz vsetke premenne v sesione, ak by nahodov doslo k neocakavanemu koncu
		session_write_close();
		session_start();
	} 
	public static function Start() { } // aby bolo co volat	
	public static function Get($data) { return $_SESSION[$data]; }	
	public static function Set($co, $data) { $_SESSION[$co] = $data; }
	public static function ID() { return session_id(); }
	
	public static function Destroy() { 
		if (isset($_COOKIE[session_name()])) setcookie(session_name(), '', time() - 42000, '/');
		// Finally, destroy the session.
		session_unset();
		session_destroy();
		$_SESSION = array();
	}
}

?>