<?php

class BaseUser
{	
	const SESSION = "User";
    const COOKIE = "fusion_user";
    const PASSKEY = "";
	protected static $LOGGED = 0;
	public static $m;
	
	public static function StaticInit() { 
        Session::Start();
        // Ak je prihlaseny nacitaj hodnoty
        if(isset($_SESSION[self::SESSION])) { 
			 self::$LOGGED = 1;
             self::$m = unserialize($_SESSION[self::SESSION]); 	
		} else {
        	self::$m = new Member;
        } 
	}
    public static function Start() { }
	public static function Logged() { return self::$LOGGED; }
	public static function LogOut() { 
        $_SESSION[self::SESSION] = NULL; 
        unset($_SESSION[self::SESSION]);
        self::$LOGGED = 0; 
        self::$m = NULL; 
        self::DestroyCookies();
        Session::Save();
    }	
    public static function MustLogged() { if(!self::$LOGGED) self::Unlogin(); }         // Musi byt prihlaseny inak hned presmeruj..  
 	    
	public static function Unlogin() {
        BaseFunctions::Presmeruj(ROOT.'?action=unlogin'); 
    }
    public static function Save($l=1) { 
        $_SESSION[self::SESSION] = serialize(self::$m);
        self::$LOGGED = $l;
        Session::Save();
    }
    protected static function SetLogin($id, $pass, $remember=true) {
        $cookie_value = $id.".".$pass;
        $cookie_exp = $remember ? 3600*24*30 : 3600*3;
        self::SetCookies($cookie_value, $cookie_exp);
    }
    protected static function DestroyCookies() {
        self::SetCookies('', -3600);
    }
    protected static function SetCookies($value, $time) {
        header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
        setcookie(self::COOKIE, $value, time() + $time, "/", "", "0");
    }
    public static function Createpass($pass) {
        return md5(self::PASSKEY.$pass);
    }
    public static function ID() {
    	// Vrati ID uzivatela inak 0
    	if(self::$LOGGED) return self::$m->user_id;	
    	return 0;
	}
}

?>