<?

class Header
{
    public static $title;
    protected static $css = array('', '');
    protected static $js = array('', '');
    protected static $header = array('', '');
    
    public static function Add($txt, $i=0) { self::$header[$i] .= $txt; }
    public static function Js($txt, $i=0) { self::$js[$i] .= $txt; }
    public static function Css($txt, $i=0) { self::$css[$i] .= $txt; }
    public static function CssFile($link, $i=0) { self::Add('<link rel="stylesheet" href="'.$link.'" type="text/css" />', $i); }
    public static function JsFile($link, $i=0) { self::Add('<script type="text/javascript" language="JavaScript" src="'.$link.'"></script>', $i); }           
    public static function Title($nazov) { self::$title = $nazov; }

    public static function StaticInit() {
		Engine::SetCallback('Header::GetHeader');
		Engine::SetCallback('Header::GetFooter', 1);
    }
    private static function CustomJS($i) {
        $data = '';
        if(self::$js[$i]) { 
            $data .= '<script type="text/javascript">';
            $data .= self::$js[$i];
            $data .= '</script>';
        }
        return $data;
    }    
    private static function CustomCSS($i) {
        $data = '';
        if(self::$css[$i]) { 
            $data .= '<style type="text/css">';
            $data .= self::$css[$i];
            $data .= '</style>';
        }
        return $data;
    }
    public static function GetHeader() {
        // Hlavny header
        if(self::$title) self::Add('<title>'.self::$title.'</title>');                                           
        echo self::$header[0];
        echo self::CustomCSS(0);
        echo self::CustomJS(0);   
    }
    public static function GetFooter() {
        echo self::$header[1]; 
        echo self::CustomCSS(1);
        echo self::CustomJS(1);       
    }
}

/*
    <meta name="copyright" content="2008-2010, Seky">
    <meta name="author" content="Seky & er2^cko?!">
    <meta name="robots" content="index, follow" >'; 
*/