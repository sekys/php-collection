<?

class Words
{
    // Here is a nice text string consisting of eleven words.
    // Returns: Here is a nice? 
    public static function Limiter($string, $word_limit) {
        $words = explode(' ', $string);
        return implode(' ', array_slice($words, 0, $word_limit));
    }
    // $disallowed = array('darn', 'shucks', 'golly', 'phooey');
    // $string = word_censor($string, $disallowed, 'Beep!');
    public static function Censor($str, &$censored, $replacement = '*')
    {
        if(!is_array($censored)) return $str;
        $str = ' '.$str.' ';
        foreach ($censored as $badword) {
            if ($replacement != '') {
                $str = preg_replace("/\b(".str_replace('\*', '\w*?', preg_quote($badword)).")\b/i", $replacement, $str);
            } else {
                $str = preg_replace("/\b(".str_replace('\*', '\w*?', preg_quote($badword)).")\b/ie", "str_repeat('#', strlen('\\1'))", $str);
            }
        }        
        return trim($str);
    }
    public static function Sklonovania($num, $text_1, $text_2_4, $text_5) {
        $abs = abs($num);
        return "$num " . (
            $abs == 1 ? $text_1 : 
            ($num == 0 || $abs >= 5 ? $text_5 : $text_2_4)
        );
    }  
    /*Here is a simple string
    of text that will help
    us demonstrate this*/  
    public static function utfwrap($str, $width, $break = '\n')
    {
        $return = '';
        $br_width = mb_strlen($break, 'UTF-8');
        for($i = 0, $count = 0; $i < mb_strlen($str, 'UTF-8'); $i++, $count++)
        {
            if (mb_substr($str, $i, $br_width, 'UTF-8') == $break) {
                $count = 0;
                $return .= mb_substr($str, $i, $br_width, 'UTF-8');
                $i += $br_width - 1;
            }          
            if ($count > $width) {
                $return .= $break;
                $count = 0;
            }           
            $return .= mb_substr($str, $i, 1, 'UTF-8');
        }       
        return $return;
    }
    public static function BezDiaktriky($str) {
        // Odtrani diaktriku
        setlocale(LC_CTYPE, "sk_SK.utf-8");
        return iconv('UTF-8', 'ASCII//TRANSLIT', $str);
    } 
}
