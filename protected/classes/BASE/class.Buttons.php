<?

class Buttons 
{	
	// Pouzit: FUSION_REQUEST
    public static function Bool($a) { return $a ? 'Ano' : 'Nie'; }
    public static function Span($name, $js) {
		return '<span class="ui-icon2 ui-icon-'.$name.'" onclick="javascript:'.$js.'"></span>';
	}
    public static function MultyShare() {
		echo '<a class="a2a_dd" href="http://www.addtoany.com/share_save?linkurl=', STR::uri_out(self::$URL), '&amp;linkname="><img src="http://static.addtoany.com/buttons/share_save_171_16.png" width="171" height="16" border="0" alt="Share/Bookmark"/></a>
		<script type="text/javascript">
			var a2a_config = a2a_config || {};
			a2a_config.linkurl = "', self::$URL, '";
		</script>
		<script type="text/javascript" src="http://static.addtoany.com/menu/locale/sk.js" charset="utf-8"></script>
		<script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>';
	}
	public static function Report($type = 0) {  // icon-error.gif ?
		 echo '<a href="#" title="Nahl&aacute;si&#357;" onclick="report(\'', $url, ', ', self::$URL, '\')" class="reportb"> Nahl&aacute;si&#357; </a>';         
	}
	public static function ICQ($uin, $type = 26) {
		echo '<a href="http://www.icq.com/', $uin, '"><img src="http://status.icq.com/online.gif?icq=', $uin, '&img=', $type, '" border="0" align="absmiddle" alt="ICQ" title="ICQ Status" /></a>';
	}
	public static function Lupa($url) {
		echo '<a href="', $url, '"><img src="', ROOT ,'web2/images/tool/detail.gif" border="0" align="absmiddle" alt="Detail" title="Detail serveru" /></a>';
	}
	public static function SteamSmall($url) {
		echo '<a href="', $url, '"><img src="', ROOT, 'web2/images/tool/steam.jpg" border="0" align="absmiddle" alt="STEAM" title="Pripoji&#357; cez STEAM" /></a>'; 
	}	
	public static function Steam($url) {
		echo '<a href="', $url, '"><img src="', ROOT, 'web2/images/tool/steam.gif" border="0" align="absmiddle" alt="STEAM" title="Pripoji&#357; cez STEAM" /></a>'; 
	}
	public static function HLSW($url) {
		echo '<a href="hlsw://', $url, '"><img src="', ROOT, 'web2/images/tool/hlsw.gif" border="0" align="absmiddle" alt="HLSW" title="Pripoji&#357; cez HLSW" /></a>';
	}
    public static function FBShare($url) {
         echo '<a href="http://www.facebook.com/sharer.php?u=', STR::uri_out($url), '" type="button_count" name="fb_share" style="text-decoration: none;">
         <span class="fb_share_size_Small ">
             <span style="cursor: pointer;" class="FBConnectButton FBConnectButton_Small">
                <span class="FBConnectButton_Text">Na facebook</span>
             </span>
             <span class="fb_share_count_nub_right "> </span>
             <span class="fb_share_count  fb_share_count_right">
             <span class="fb_share_count_inner">6</span>
             </span>
         </span></a>';
    }	
    public static function Precitane($a) {
        return '<img src="'.ROOT.'web2/images/tool/citane.png" alt="Prezret&eacute;" title="Prezret&eacute; '.$a.'-kr&aacute;t" />'; 
    }
    public static function Komentarov($a) {
        return '<img border="0" alt="Koment&aacute;rov" title="'.$a.' koment&aacute;rov" src="'.ROOT.'web2/images/tool/comments3.png" />';
    }
    public static function FB($url='', $name='', $width=10) {
     	echo '<a href="javascript:fb(\'', $url, '\', \'', $name, '\')"><img title="Facebook" width="', $width, '" height="', $width, '" alt="Facebook" src="', ROOT, 'web2/images/tool/icon-facebook.gif" /></a>';
	}	
	public static function FB_LIKE($url) {
     	echo '<iframe src="http://www.facebook.com/widgets/like.php?href=', $url, '" scrolling="no" frameborder="0"></iframe>';
	}	
	public static function Twitter($url='', $name='', $width=10) {
     	echo '<a href="javascript:twitter(\'', $url, '\', \'', $name, '\')"><img title="Twitter" width="', $width, '" height="', $width, '" alt="Twitter" src="', ROOT, 'web2/images/tool/icon-twitter.gif" /></a>';
	}
	public static function LIKE_UP($kat, $id, $title='P&aacute;&#269;i sa mi to') {
        echo '<a href="javascript:lbc(\'', $kat, '\', \'', $id, '\', 1)"><img title="', $title, '" alt="Ano" src="', ROOT, 'web2/images/tool/thumb-up.gif" onmouseout="lbh(this, \'1\');" onmouseover="lbh(this, 1);" /></a>';         
	}  
	public static function LIKE_DOWN($kat, $id, $title='Nep&aacute;&#269;i sa mi to') {
        echo '<a href="javascript:lbc(\'', $kat, '\', \'', $id, '\', 0)"><img title="', $title, '" alt="Nie" src="', ROOT, 'web2/images/tool/thumb-down.gif" onmouseout="lbh(this, \'0\');" onmouseover="lbh(this, 0);" /></a>';        
	}
	public static function Favorite($url = '', $name='') {
     	echo '<a href="javascript:addfav(\'', $url, '\', \'', $name, '\')"><img title="Add to Favorite" alt="Add to Favorite" src="', ROOT, 'web2/images/tool/fav_off.png" onmouseout="fav(this);" onmouseover="fav(this);" /></a>';         
	}
    public static function Checkbox($id, $screen='green', $disambled=false, $checked=false) {    
        echo '<input name="', $id, '" id="ch-i-', $id, '" type="hidden" value="',  $checked ? "1" : "0" ,'" />
        <img class="cursor" ',
        // Vypnute
        ($disambled) ? 'style="opacity:0.5;"' : 'onclick="web2_checkbox(\''.$id.'\', \''.$screen.'\');"', 
        ' align="top" id="ch-s-', $id, '" border="0" alt="', $id, '" src="'.ROOT.'web2/images/tool/',  $checked ? $screen."-hilite" : $screen , '.png" />';       
    }
     public static function ServerAvatar($data) {
		return '<img border="0" alt="'.$data['nazov'].'" src="'.ROOT.'web2/images/server/'.$data['img'].'.png" />';
	}
}
?>