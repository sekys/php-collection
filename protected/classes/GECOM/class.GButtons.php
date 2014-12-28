<?

class GButtons
{
	public static function abingo($url, $a, $b) {
	    echo '<a class="bingo" href="', ROOT, $url, '" title="', $a, '">', $b, '</a>';
	}
	public static function abingo2($url, $a, $b) {
	    echo '<a class="bingo" href="', ROOT, $url, '" title="', $a, '">
	    	<span class="papier"></span> ', $a, ' <span class="info_gray">(', $b, ')</span></a>';
	}
	/*
	http://davidwalsh.name/contact
	http://buysellads.com/buy/detail/1687
	http://davidwalsh.name/google-ajax-search
	*/

	public static function TopRank($id) {
	    if($id <= 3) {
	        return '<img align="absmiddle" border="0" alt="'.$id.'. miesto" title="'.$id.'. miesto" src="'.ROOT.'web2/images/tool/trophy_'.$id.'.png"/>';
	    }
	    return $id.'.';
	}
}