<?php

/*
    visited - cas kedy navstivil nas projekt
    User - udaje o pouzivatelovy, moze byt prazdne ak je odhlaseny
    referer - ak prisiel na nas projekt z nejakej cudzej stranky, moze byt prazdne

*/

class User extends BaseUser
{    
    public static function StaticInit() { 
        // Najprv sa nacira BaseUser::StaticInit a az potom tototo
        if(!self::$LOGGED) {
        	if(self::GetDataFromCookie()) {	
        		// Tato udalost sa itez kona len raz
				// rovnako ako poslanie udajov a prihalsenie
				// vykona sa ked sa presuvaju udaje z cookie do sessionu, cize len pcoas prihlasenia
				
				// Kontroluj ci uzivatel nema ban + casovy ban
        	    $status = self::$m->user_status;
	            if ($status == 1) {
	                Engine::Presmeruj(ROOT.'setuser/?error=1');
	            } elseif ($status == 2) {
	                Engine::Presmeruj(ROOT.'setuser/?error=2');
	            }
	            // Kontroluj ci nema nemako obmedzenu cinnost
			}		
		}		
        
        // Misc
        self::CheckValues();
    } 
    private static function GetDataFromCookie() {
        // Je to uz na tejto funkcii ci prihlasuje raz cez POST
		// Riesit systemom handler a po kazdom extends pridat do hadle array a tu kontrolovat ?
		if( self::PostLogin() ) return true;
		if( self::FusionCookie() ) return true;
		if( self::FBCookie() ) return true;
		return false;
    }
    private static function FBCookie() {
		// Alebo cez cookie...
        if(!FB::GetCookie()) return false;
        $id = FB::$USER['uid'];       
        $sql = 'SELECT * FROM `cstrike`.`fusion_users` WHERE fbid="'.DB::Vstup($id).'" LIMIT 1';
        $result = DB::Query($sql);
        if(!$result->num_rows) return false;
        
        // Nasli sme..
        self::$m->next($result);
        self::Save(3);
        return true;
    }
    private static function FusionCookie() {
		// Alebo cez cookie...
        if(!isset($_COOKIE['fusion_user'])) return false;
        $cookie_vars = explode(".", $_COOKIE['fusion_user']);
        if(!is_numeric($cookie_vars[0])) {  // pokus o hack ?
            self::DestroyCookies();
            return false;
        }
        $pass = (preg_match("/^[0-9a-z]{32}$/", $cookie_vars[1]) ? $cookie_vars[1] : "");
        $sql = 'SELECT * FROM `cstrike`.`fusion_users` WHERE user_id="'.
            	DB::Vstup($cookie_vars[0]).'" AND user_password="'.DB::Vstup($pass).'" LIMIT 1';
        $result = DB::Query($sql);
        if(!$result->num_rows) {
            self::DestroyCookies();
            return false;
        }
        // Nasli sme..
        self::$m->next($result);
        self::Save(2);
        return true;
    }
    private static function CheckValues() {         
        // Kontroluj stale
        if(isset($_SERVER['HTTP_REFERER'])) {
            // Referer
            if(!isset($_SESSION['referer'])) {
                $_SESSION['referer'] = $_SERVER['HTTP_REFERER'];
            } else {
                $_SESSION['referer'] = '';
            }
            // Last page
            $_SESSION['last_page'] = $_SERVER['HTTP_REFERER'];
        }
               
        // Kontroluj len raz
        if(isset($_SESSION['visited'])) return;
        self::CheckIPBan();
        self::SetCounter();
        $_SESSION['visited'] = Time::$TIME;
    }
    private static function CheckIPBan() {
        // Check if users full or partial ip is blacklisted
        $ip = $_SERVER['REMOTE_ADDR'];
        $sub_ip1 = substr($ip, 0, strlen($ip)-strlen(strrchr($ip,".")));
        $sub_ip2 = substr($sub_ip1, 0, strlen($sub_ip1)-strlen(strrchr($sub_ip1,".")));
        $pocet = DB::One("SELECT COUNT(*) as pocet FROM `cstrike`.`fusion_blacklist` WHERE `blacklist_ip`='".$ip."' OR `blacklist_ip`='$sub_ip1' OR `blacklist_ip`='$sub_ip2' LIMIT 1");
        if($pocet > 0) {
            Engine::Presmeruj('http://www.google.sk/#&q=Na+tejto+stranke+mas+BAN');
        }
    }
    private static function SetCounter() {
        DB::Query("UPDATE fusion_settings SET counter=counter+1");
        setcookie("fusion_visited", "yes", time() + 31536000, "/", "", "0");    
    }
    private static function PostLogin() {
        if(!isset($_POST['login'])) return false;
        $user_pass = self::Createpass($_POST['user_pass']);
        $user_name = preg_replace(array("/\=/","/\#/","/\sOR\s/"), "", DB::Clear($_POST['user_name']));
        $result = DB::Query("SELECT * FROM fusion_users WHERE user_name='".DB::Vstup($user_name)."' AND user_password='".DB::Vstup($user_pass)."' LIMIT 1");
        if($result->num_rows != 0) {
            // Uspech...
            self::$m->next($result);
            self::SetLogin(
                self::$m->user_id, 
                $user_pass, 
                isset($_POST['remember_me'])
            );
            self::Save(2);
            //Engine::GoHome();
            Engine::Presmeruj(ROOT.'setuser/'); // presmerovanie na pomocnu stranku
            return true;                            
        } else {
            Engine::Presmeruj(ROOT."setuser/?error=3");
            return false;
        }   
    }
}   
?>
