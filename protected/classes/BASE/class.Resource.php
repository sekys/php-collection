<?	

class Resource
{
    /* Vyuziva sa na strankach */
    public static $compress = true;
    private static $resource;
    
    //public static Image($meno, $w=-1, $h=-1) { return  }
    public static function Css($meno) { 
    	$b = array(); 
    	foreach(func_get_args() as $a) { $b[] = $a; } 
    	self::$resource[1][] = $b; 
    }
    public static function Js($meno) { 
    	$b = array(); 
    	foreach(func_get_args() as $a) { $b[] = $a; } 
    	self::$resource[0][] = $b;
    }        
    public static function All($meno) {
    	$b = array(); 
    	foreach(func_get_args() as $a) { $b[] = $a; } 
    	self::$resource[0][] = $b;
    	self::$resource[1][] = $b;
    }        
  
    public static function StaticInit() {
		Engine::SetCallback('Resource::Out');
    }
    public static function Out() {
        if(is_array(self::$resource[1])) {
            foreach(self::$resource[1] as $b) {
                echo '<link rel="stylesheet" href="',
                self::BuildLink(1, implode(",", $b)),
                '" type="text/css" />';
            }
        }
        if(is_array(self::$resource[0])) {
            foreach(self::$resource[0] as $b) {
                echo '<script type="text/javascript" language="JavaScript" src="',
                self::BuildLink(0, implode(",", $b)), 
                '"></script>';
            }
        }
    }       
    protected static function BuildLink($t, $f) {
        return ROOT.'resource.php?c='.intval(self::$compress).'&t='.$t.'&fs='.$f;
    } 
}
 
?>