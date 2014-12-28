<?
class Referral
{
    const ID = 'referral';
    
    public static function StaticInit() {
        Session::Start();
    }
    public static function PreSet($id) {
		if(!isset($_GET[$id])) return false;
		$_SESSION[self::ID] = $_GET[$id];
		return true;
    }
    public static function Set($id) { 
        // Je prihlaseny
        if(User::Logged()) return false;
         // Uz mame nastavene ...
        if(isset($_SESSION[self::ID])) return false;
        $_SESSION[self::ID] = $id;
        return false;
    }
    public static function Get() {
         // Uz mame nastavene ...
        if(!isset($_SESSION[self::ID])) return 0;    
        return $_SESSION[self::ID];
    }
}