<?

class BaseFunctions
{
    public static function rand_array(array $pole) { return rand($pole[0], $pole[1]); }
    public static function Percenta($cast, $cele) {
        if($cele == 0 ) return 0;
        $num = floor((100 * $cast ) / $cele);
        if($num < 0) return 0;
        if($num > 100) return 100;
        return $num;
    }
    public static function Presmeruj($url) {
        if(class_exists('Engine')) Engine::Presmeruj($url);
        else Ajax::Presmeruj($url); 
    }
    public static function RID() { return microtime(false); }   
    public static function uriRID() { return "?rid=".self::RID(); }
    /** Vygenerov?n? n?hodn?ho ?et?zce
    * @param int [$count] d?lka vr?cen?ho ?et?zce
    * @param int [$chars] pou?it? znaky: <=10 ??slice, <=36 +mal? p?smena, <=62 +velk? p?smena
    * @return string n?hodn? ?et?zec
    * @copyright Jakub Vr?na, http://php.vrana.cz
    */
    public static function rand_chars($count = 8, $chars = 36) {
        $return = "";
        for ($i=0; $i < $count; $i++) {
            $rand = rand(0, $chars - 1);
            $return .= chr($rand + ($rand < 10 ? ord('0') : ($rand < 36 ? ord('a') - 10 : ord('A') - 36)));
        }
        return $return;
    }
    public static function rand_string() { return md5(microtime().rand(0,999)); }
    public static function rand_string2() { return  md5(uniqid(mt_rand(), true)); }
    public static function object2Array($object) {
        if(!is_object($object) && !is_array( $object )) return $object;
        if( is_object( $object )) $object = get_object_vars( $object );
        return array_map( 'objectToArray', $object );
    }
    public static function rand_hex_color($length=6, $values='abcdef0123456789')
    {
        $num_characters = strlen($characters) - 1;
        while (strlen($code) < $length) $return.= $characters[mt_rand(0,$num_characters)];
        return '#'.$return;
    }
    public static function byte_format($octets, $units = array('B', 'kB', 'MB', 'GB', 'TB')) {
        for ($i=0, $size =$octets; $size>1024; $size=$size/1024) $i++;
        return number_format($size, 2) . ' ' . $units[min($i, count($units) -1 )];
    }
    public static function Link($link, $data) {  foreach ($data as $key => $value) $link .= "&".$key."=".$value."'"; return $link; } 
    
    // http://www.phpblog.sk/clanok/44/dynamicka-tvorba-url/
    public function getWholeUrl($params=array(), $delparams=array(), $separator='&') 
    { 
       $makeParams = array_merge($_GET, $params); // spoji obe polia starych (_get) a novych (params) parametrov dokopy.  
       if (sizeof($delparams) > 0) {
            foreach ($delparams as $key => $val) { // z pola vymaze dane parametre
                unset($makeParams[$key]);
            }
       }  
       $url = $_SERVER['PHP_SELF']."?"; // vytvori URL
       $url .= http_build_query($makeParams,'',$separator);
       return $url;   
    }
    /*
        // do URL sa prid? ako get parameter ?parameter1=hodnota
        echo getWholeUrl(array("parameter1"=>"hodnota"));     
        // do URL sa prid? ako get parameter ?parameter1=hodnota a vyma?e sa GET parameter vymazat parameter
        echo getWholeUrl(array("parameter1"=>"hodnota"),array("vymazatparameter"=>""));
    */
    public function showAlphabet() {
      foreach(range('A','Z') as $i) {
           $r .= ' '.$i.' ';
      }
      return $r;
    }
}