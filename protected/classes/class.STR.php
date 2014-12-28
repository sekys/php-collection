<?

class STR extends BaseSTR
{
	public static $badwords;
	
	public static function OUT($txt) { // Ak smailik je zapnuty...
		$txt = self::Smiley($txt);
		//$txt = DB::Vystup($txt);	nemoze byt lebo blokuje vsetke HTMLka	
		return $txt;
	}
	public static function IN($txt) {
		// Dat tu skore, kazde badword ohodnotit zo skore a ked skore prekoci limit tak to porste neposle
		//$txt = trim($txt); je uz v nadavkach a pod
		$txt = DB::Vstup($txt); 
		$txt = stripslashes($txt); 
		$txt = self::Nadavky($txt);
		$txt = self::BBcode($txt); 
		$txt = str_replace("\\n", "<br />", $txt); // medzery postfix
		return $txt;
	}
	public static function BBcode($text) {
		/*	S[ravit JS na sposob FACEBOOKU ?
			Zada odkaz, nabehne ajax, vsetko sntroluje, vytvori MENO, nahlad,
			Prerobi aj do BBcodu ale skrytej casti textarea2
			Ak odosle tak vsetko ulozi.
		*/
		$text = preg_replace('#\[B\](.*?)\[/B\]#si', '<b>\1</b>', $text);
		$text = preg_replace('#\[I\](.*?)\[/I\]#si', '<i>\1</i>', $text);
		$text = preg_replace_callback("#\[IMG\]((http|ftp|https|ftps)://)(.*?)(\.(jpg|jpeg|gif|png|JPG|JPEG|GIF|PNG))\[/IMG\]#", 
			"_str_bbcode_img", $text);			
		
		// Odkazy
		$text = preg_replace('#\[URL\]([\r\n]*)(http://|ftp://|https://|ftps://)([^\s\'\";\+]*?)([\r\n]*)\[/URL\]#sie', "STR::CheckWWW('\\2\\3', '\\2\\3')", $text);
		$text = preg_replace('#\[URL\]([\r\n]*)([^\s\'\";\+]*?)([\r\n]*)\[/URL\]#si', "STR::CheckWWW('\\2', '\\2')", $text);
		$text = preg_replace('#\[URL=([\r\n]*)(http://|ftp://|https://|ftps://)([^\s\'\";\+]*?)\](.*?)([\r\n]*)\[/URL\]#si', "STR::CheckWWW('\\2\\3', '\\4')", $text);
		$text = preg_replace('#\[URL=([\r\n]*)([^\s\'\";\+]*?)\](.*?)([\r\n]*)\[/URL\]#si', "STR::CheckWWW('\\2', '\\3')", $text);
			
		$text = preg_replace('#\[Q](.*?)\[/Q\]#si', '<span class=\'q\'>\1</span>', $text);
		return self::BBcodeclean($text);
	}
	public static function BBcodeclean($text) {
		/* Vycisti vsetke BB code znaky z textu......ak text nieje dokonceny napr... */
		$text = str_replace('[B]','', $text);
		$text = str_replace('[I]','', $text);
		$text = str_replace('[IMG]','', $text);
		$text = str_replace('[Q]','', $text);
		$text = str_replace('[URL]','', $text);
		$text = preg_replace('#\[URL([\r\n]*)\]#si', '', $text);
		return $text;
	}
    public static function Smiley($txt) {
        $smiley = array(
            "#\:\)#si" => "<img src='".ROOT."images/smiley/smiley-smile.gif' alt=':)'>",
            "#\;\)#si" => "<img src='".ROOT."images/smiley/smiley-wink.gif' alt=';)'>",
            "#\:\(#si" => "<img src='".ROOT."images/smiley/smiley-cry.gif' alt=':('>",
            "#\:\|#si" => "<img src='".ROOT."images/smiley/smiley-undecided.gif' alt=':|'>",
            "#\:o#si" => "<img src='".ROOT."images/smiley/smiley-sealed.gif' alt=':o'>",
            "#\:p#si" => "<img src='".ROOT."images/smiley/smiley-tongue-out.gif' alt=':p'>",
            "#B\)#si" => "<img src='".ROOT."images/smiley/smiley-embarassed.gif' alt='B)'>",
            "#\:D#si" => "<img src='".ROOT."images/smiley/smiley-laughing.gif' alt=':D'>",
            "#\:@#si" => "<img src='".ROOT."images/smiley/smiley-innocent.gif' alt=':@'>",
            "#\:4#si" => "<img src='".ROOT."images/smiley/smiley-money-mouth.gif' alt=':4'>",
            "#\:K#si" => "<img src='".ROOT."images/smiley/smiley-kiss.gif' alt=':K'>",
            "#\:cs#si" => "<img src='".ROOT."images/smiley/5.gif' alt=':cs'>",
            "#\:5#si" => "<img src='".ROOT."images/smiley/smiley-cool.gif' alt=':5'>"
        );
        foreach($smiley as $key=>$smiley_img) $txt = preg_replace($key, $smiley_img, $txt);
        return $txt; 
    }
    public static function MenoExist($txt, $vynimka=0) {     // Zisti ci meno existuje ...
        return DB::One("SELECT COUNT(`user_id`) FROM `cstrike`.`fusion_users` WHERE `user_id` != '".$vynimka."' AND cs_meno LIKE '".$txt."' LIMIT 1");
    }
    public static function UserMenoExist($txt, $vynimka=0) {     // Zisti ci meno existuje ...
        return DB::One("SELECT COUNT(`user_id`) FROM `cstrike`.`fusion_users` WHERE `user_id` != '".$vynimka."' AND user_name LIKE '".$txt."' LIMIT 1");
    }
    public static function SteamExist($txt, $vynimka=0) { // Zisti ci steam cislo existuje ...
        return DB::One("SELECT COUNT(`user_id`) `cstrike`.`fusion_users` WHERE `user_id` != '".$vynimka."' AND cs_steam LIKE '".$txt."' LIMIT 1");
    }
    public static function CheckWWW($href, $nazov) {
        // Kontroluje ci nejde o obrazok, ak ano prerobi ho do <img
        // *Pripoji sa cez php a kontroloje HEADER, ci ide o obrazok j[g, gif a pod
        // *Ak v nazve je php alebo v headeri ukaze error linku ako na FB
        // Cez ajax podobne s avola.
        if(self::ObsahujeNevhodnyText($href)) return '';
        if(self::ObsahujeNevhodnyText($nazov)) return '';
        
        $txt = '<a href="'.$href.'" ';
        // Premiena vsetke LINKY na SEO neaktivne ....okrem gecomu
        if(!self::GecomReklama($href)) {
            $txt .= 'rel="nofollow"';
        }
        $txt .= ' target=\'_blank\'>'.$nazov.'</a>';
    	return $txt;
    }
    public static function ObsahujeNevhodnyText($txt) {
		// Ak obsahuje porno, nevhodne veci, vracia bool
		foreach(self::$badwords as $word) {
			if(!(strpos($txt, $word) === FALSE)) return true;
		}
		return false;
    }
    public static function GecomReklama($txt) {
        /*    php funckia hlada IPcky hracov podla portov, bodiek apod.
            * Ak ide o reklamu, tak sa manualne pridat do BAD_WORDS a sa nahradi.
            Ak naslo IP, port a nieje to z gecomu.
            Pripoji sa cez php class, ak je online - spravit velmi rychlu funkciu
            Spravu potom neodosle, uzivatelovy udeli BAN na 10 min.
            Do logov prida zaznam ze ma BAN a podla toho aj kona.
        */
        // Funckia len kontroluje ci dany odkaz obsahuje gecom
        return !(strpos($txt, 'cs.gecom.sk') === FALSE);
    }
    public static function Nadavky($text) { 
        return Words::Censor($text, &self::$badwords); 
    }    
    public static function GetBadWords() { 
        // Je to nacitane
        if(is_array(self::$badwords)) return;
        // Sme vo fusione - preskakujeme
        global $settings;
    	if(!defined("IN_FUSION")) {
            // Nejaky ajax
            $settings['bad_words'] = DB::Query("SELECT bad_words FROM fusion_settings")->fetch_assoc();
        }
        self::$badwords = &$settings['bad_words'];
    }
    
}
function _str_bbcode_img($matches) {
	return "<img src='".$matches[1].str_replace(array('.php', '?', '&', '='), '', $matches[3]).$matches[4]."' style='border:0px'>";
}



/*
function unicode_decode($str){
    return preg_replace(
        '#\\\u([0-9a-f]{4})#e',
        "unicode_value('\\1')",
        $str);
}

function unicode_value($code) {
    $value=hexdec($code);
    if($value<0x0080)
        return chr($value);
    elseif($value<0x0800)
        return chr((($value&0x07c0)>>6)|0xc0)
            .chr(($value&0x3f)|0x80);
    else
        return chr((($value&0xf000)>>12)|0xe0)
        .chr((($value&0x0fc0)>>6)|0x80)
        .chr(($value&0x3f)|0x80);
} 

- Teraz my to netreba

function uri_reserve($txt)  {    // Vracia BOOL ci meno ma rezervovanu hodnotu
    // Najrychlejsia metoda porovnavania retazcov ....
    $ochrana = array (    
        // Mena 1. stupna su pre este lepsie SEO tazv /meno/
        "hrac" => True,
        "servers" => True,
        "nastavenie" => True,
        "registrovat" => True,    
        "obchod" => True,            
        "hladaj" => True,
        "stavky" => True,
        "vip-sloty" => True,
        "rank-admin" => True,
        "galeria" => True,
        "kandidovat" => True,
        "zaznamy" => True,
        "pravidla" => True,
        "herne-pravidla" => True,
        "ligove-pravidla" => True,
        "kredity" => True,
        "legenda" => True,
        "admin-team" => True,
        "novinka" => True,
        "novinky" => True,
        "clanok" => True,
        "upravit-profil" => True,
        "zombie-banka" => True,
        "deathrun-banka" => True,    
        
        // Zlozky
        "forum" => True,
        "cache" => True,
        "cup" => True,
        "administration" => True,
        "images" => True,
        "includes" => True,
        "infusions" => True,
        "locale" => True,
        "profil" => True,
        "themes" => True,
        "web2" => True,
        "ban" => True,
        "administration" => True,
        "cstrike" => True,
        "icon" => True,
        "psychostats" => True,
        "stats" => True
    );
        
    if(isset($ochrana[$txt])) return true;
    // Mena 2. stupna su optimalne.....poa,mahu pri seo a pod.
    if(!(strpos($txt, "lekos") === false) or !(strpos($txt, "gecom") === false)) {
        return true;
    }
    return false; 
}
*/ 