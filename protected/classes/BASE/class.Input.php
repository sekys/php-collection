<?

class INPUT 
{
	// $povinne = array("jmeno" => "Jm?no", "prijmeni" => "P??jmen?");
    // echo "<p>Nevypln?n? polo?ky: " . implode(", ", $nevyplnene) . "</p>\n";
	public static function Nevyplnene($povinne) {		
        $nevyplnene = array();
		foreach($povinne as $key => $val) {
			if (!isset($_POST[$key])) $nevyplnene[] = $val;
		}
		return $nevyplnene;
	}


	// 			F		I		X
	
    public static function Nums() {  // asi najpouzivanejsie
        foreach(func_get_args() as $a) {
            if(!isset($_GET[$a]) or !is_numeric($_GET[$a])) return false;
            global $$a;
            $$a = $_GET[$a];
        }
        return true;
    }   
    public static function Num($id, $b=0) {
        if(!isset($_GET[$id])) return $b;
        if(!is_numeric($_GET[$id])) return $b; 
        return $_GET[$id];
    }
    public static function CoolURI($name) {
        if(!isset($_GET[$name])) return 0;
        $data = explode(",", $_GET[$name]);
        if(!is_numeric($data[0])) return 0;
        return DB::Vstup($data[0]);
    }
    public static function numsA() {
        foreach(func_get_args() as $a) {
            if(!isset($_GET[$a]) or !is_numeric($_GET[$a])) Ajax::cExit();
            global $$a;
            $$a = $_GET[$a];
        }
        return true; 
    }
    public static function issets() { // asi najpouzivanejsie
        foreach(func_get_args() as $a) {
             $data = isset($_GET[$a]) ? $_GET[$a] : ''; // NULL
             global $$a;
             $$a = $data;
        }
    }    
	public static function XSSPOSTS() {
        if(!is_array($_POST)) return;
		foreach($_POST as $key => $val) {
			$_POST[$key] = DB::Vystup($val);
		}
	}	
	public static function XSSGETS() {
        if(!is_array($_GET)) return;
		foreach($_GET as $key => $val) {
			$_GET[$key] = DB::Vystup($val);
		}
	}
	public static function SQLPOSTS() {
        if(!is_array($_POST)) return;
		foreach($_POST as $key => $val) {
			if(is_numeric($val)) continue;
			$_POST[$key] = DB::Vystup($val);
		}
	}		
	public static function SQLGETS() {
		if(!is_array($_GET)) return;
        foreach($_GET as $key => $val) {
			if(is_numeric($val)) continue;
			$_GET[$key] = DB::Vstup($val);
		}
	}
    /*                Functions and Filters

    To filter a variable, use one of the following filter functions:

        * filter_var() - Filters a single variable with a specified filter
        * filter_var_array() - Filter several variables with the same or different filters
        * filter_input - Get one input variable and filter it
        * filter_input_array - Get several input variables and filter them with the same or different filters
    */
    
    // If the input variable is a string like this "http://www.W3aaSchooools.com/", the $url variable after the sanitizing will look like this: http://www.W3Schools.com/ 
    public static function REPAIRURL($txt) { return filter_var($txt,  FILTER_SANITIZE_URL); }
    // @return bool - is valid or isnt
    public static function CheckINT($txt) { return filter_var($txt, FILTER_VALIDATE_INT); }
    public static function CheckINT2($txt, $max, $min=0) { 
        $option = array( "options" => array( "min_range"=> $min, "max_range"=> $max ));
        return filter_var($txt, FILTER_VALIDATE_INT, $option); 
    }
    public static function CheckMAIL($txt) { return filter_var($txt, FILTER_VALIDATE_EMAIL); }
    public static function CheckINPUT($txt, $filter, $type = INPUT_POST) {
        if(!filter_has_var($type, $txt)) return -1;
        return filter_input($type, $txt, $filter); 
    }
    
    
    // post() return isset() ? NULL;
    // get() return isset() ? NULL;
}
?>