<?

class BaseSTR 
{
    public static function uri_out($txt) { return urlencode($txt); }
    public static function uri_in($txt) { return urldecode($txt); }
	public static function AntiRelativePath($txt) { return str_replace('/', '', $txt); }
	public static function XSS($txt) { return htmlspecialchars($txt); }
	public static function HtmlZakaz($text) { return strip_tags($text); }
	public static function remove_xhtml_entities($xhtml) { return html_entity_decode(preg_replace('~&(lt|gt|amp);~', '&amp;\\1;', $xhtml), ENT_NOQUOTES, "utf-8"); }
	
	public static function FriendlyUrl($nadpis) {
		$url = $nadpis;
		$url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
		$url = trim($url, "-");
		$url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
		$url = strtolower($url);
		$url = preg_replace('~[^-a-z0-9_]+~', '', $url);
		return $url;
	}
    public static function repeater($string, $pocet) { $str=''; for($i=0; $i < $pocet; $i++) { $str .= $string; } return $str; }    
    public static function repeater2($string, $pocet) { for($i=0; $i < $pocet; $i++) echo $string; }
    public static function ToBool($a) { return $a ? "Ano" : "Nie"; }
    
    /*
        // Použitie
        // email
        $text = "you@example.com";
        echo makeClickableLinks($text);
        // URL
        $text = "http://www.example.com";
        echo makeClickableLinks($text);
        // FTP URL
        $text = "ftp://ftp.example.com";
        echo makeClickableLinks($text)
    */
    public static function makeClickableLinks($text) {
          $text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)',
            '<a href="\\1">\\1</a>', $text);
          $text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)',
            '\\1<a href="http://\\2">\\2</a>', $text);
          $text = eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})',
            '<a href="mailto:\\1">\\1</a>', $text);   
            return $text;    
    }
 
}
