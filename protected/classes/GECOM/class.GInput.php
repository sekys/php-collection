<?

class GInput
{
	public static function URIMeno2id($get, $error=true) {
		if(!isset($_GET[$get])) {
			if($error) {
                echo Mess::Alert('Chyba', 'Hr&aacute;&#269; nen&aacute;jden&yacute; ...');
            }    
            return 0;
		}
		$meno = $_GET[$get];
		$sql = self::Meno2SQL($meno, $error);
		$data = $sql->fetch_row();
		return $data[0];
	}
	public static function Meno2SQL($meno, $error=true) {
        $temp = DB::Clear($meno);
        $temp = DB::Vstup($temp);
        @$sql = DB::One("SELECT user_id FROM ".DB_PREFIX."users WHERE user_name LIKE '".$temp."' LIMIT 1");         

        if($sql->num_rows) {
            return $sql;
        } else {
            if($error) {
                echo Mess::Alert('Chyba', 'Hr&aacute;&#269; nen&aacute;jden&yacute; ...');
            }    
            return 0;
        }
    }
	public static function URIMeno2Member($lookup) {
	    // Hladame
	    $lookup = isset($_GET[$lookup]) ? $_GET[$lookup] : '';
	    $lookup = DB::Clear($lookup);
	    $lookup = DB::Vstup($lookup);
	    $result = DB::Query("SELECT * FROM `cstrike`.`fusion_users` WHERE user_name LIKE '".$lookup."'");

	    // Vyberame ....
	    $p = new Member;
	    if($p->mysqlexist($result)) {
    		$p->setout('cs_meno');  
    		// TODO: plrdid priamo do fusion_users
    		// TODO: spychostats nejd e taze nezapinat  
	        //$p->plrid = DB::One("SELECT `plrid` as pocet FROM `psychostats`.`ps_plr` WHERE `uniqueid` LIKE '".$p->cs_meno."'");
		} else {	    	
	    	Engine::GoHome();
		}
	    return $p; 
	}
	
}