<?php

class Member extends DBTable 
{
    const AVATAR_DEFAULT = "/webb/web2/images/theme/user-64x64.png";
    const REQUEST = "user_id, user_name, postava, vip, slot";

    public function OnlineStatus() {
        return $this->__data['user_lastvisit'] > Time::$ONLINE ? 'Online' : 'Offline';
    }
    public function ICQStatus() {
        return r(new ICQ($this->user_icq))->Status2(1);
    }
    public function AFinded() {   
        if(!is_array($this->__data)) {
            Ajax::cExit('Hr&aacute;&#269; nen&aacute;jden&yacute;.');
        }
    }
    public function InfoBar() {
    	$name = DB::Vystup($this->__data['user_name']);
		$url = ROOT."hrac/".STR::uri_out($name).'/';
	    // Bonusi najprv
	    echo $this->Bonuses();	
		// Pridat si ho tam,...
		echo Buttons::FB($url, $name, 16), ' ';
    	echo Buttons::Twitter($url, $name, 16), ' ';
    
    	// Informacie
	    echo '
	    [<a href="#top" onclick="friend_add(\'', $this->__data['user_id'], '\', this);" title="Pridaj k priate&#318;om">+</a>] 
	    [<a href="/psychostats/index.php?q=', STR::uri_out($this->__data['cs_meno']), '\" title="&Scaron;tatistyky">Stats</a>]      
	    ';
	    if($this->__data['cs_steam']) {
	        echo Buttons::SteamSmall('http://steamcommunity.com/id/'.$this->__data['cs_steam'].'/');
	    }
	    if($this->user_hide_email != '1' || iADMIN) {
	    	$mail = str_replace('@', '&#64;', $this->__data['user_email']);
	        echo '[<a  href="mailto:', $mail, '" title="', $mail, '">@</a>]';
	    }
	    if($this->__data['user_web']) {
	        $urlprefix = !strstr($this->__data['user_web'], 'http://') ? 'http://' : '';
	        echo '[<a rel="nofollow" href="', $urlprefix, $this->__data['user_web'], '" title="', $urlprefix, $this->__data['user_web'], '" target="_blank">WWW</a>]';
	    }
	    if($this->__data['user_aim']) {
			echo '<a href="aim:goim?screenname=', $this->uout('user_aim'), '" target="_blank"><img src="', RIMAGE, 'forum/aim.gif" alt=""></a>';
		}
		if($this->__data['user_icq']) {
			echo '<a href="http://www.icq.com/people/', $this->__data['user_icq'], '" target="_blank"><img src="', RIMAGE, 'forum/icq.gif" alt="', $this->__data['user_icq'], '"></a>';
		}
		if($this->__data['user_msn']) {
			echo '<a href="mailto:', $this->__data['user_msn'], '"><img src="', RIMAGE, 'forum/msn.gif" alt="', $this->__data['user_msn'], '"></a>';
		}
		if($this->__data['user_yahoo']) {
			echo '<a href="http://uk.profiles.yahoo.com/', $this->__data['user_yahoo'], ' target="_blank"><img src="', RIMAGE, 'forum/yahoo.gif" alt="', $this->__data['user_yahoo'], '"></a>';
		}
	    echo '[<a href="', $url, 'posli-spravu/" title="Posla&#357; spr&aacute;vu">PM</a>]';  	
	}
    public function Bonuses() {
        $data = '';
        // VIP
        if($this->__data['vip'])
            $data .= '<img align="absmiddle" border="0" title="VIP akt&iacute;vne" alt="VIP" src="'.ROOT.'web2/images/tool/vip.png">';
        // Slot
        if($this->__data['slot'])
            $data .= '<img align="absmiddle" border="0" title="Slot akt&iacute;vny" alt="Slot" src="'.ROOT.'web2/images/tool/goldkey.png">';
        return $data;
    }    
    public function AvatarCesta() {
        return self::AvatarStatic($this->__data['user_avatar']);
    }
    public static function AvatarStatic($avatar) {
        if($avatar != "") { 
            // + Overujeme ci AVATAR existuje na localhoste
            if(file_exists("/home/cstrike/public_html/images/avatars/".$avatar)) return "/images/avatars/".$avatar;
            // + Ci je to postavicka ..
            if(is_numeric($avatar)) return self::Postavicka($avatar, 256);
            // + Overujeme ze ci existuje mimo
            if (@GetImageSize($avatar)) return $avatar;
        }
        return self::AVATAR_DEFAULT;
    }
    public function Kategorie() {
        if($this->__data['user_groups'] == NULL) return '>';
        if(!$this->__data['user_groups']) {                                                                                                       // TODO: Zistit adresu normalneho uzivatela.
            return '><img align="absmiddle" border="0" alt="U&#382;ivate&#318;" title="Status: U&#382;ivate&#318;" src="'.ROOT.'web2/images/'.$x[1].'">';
        }    
        // Ma nejaku hodnost ...
        $data = explode(".", $this->__data['user_groups']);
        for($i=1; $i < count($data); $i++) {    //nulte nepocitame :)
            $x = Kategorie::Get($data[$i]);
            $string .= '<img align="top" border="0" alt="'.$x[0].'" title="Status: '.$x[0].'" src="'.ROOT.'images/ranks/'.$x[1].'">';
        }
        return 'class="h'.$x[2].'" >'.$string; // class zistime lebo posledne je najdolezitejsie
    }
    public function Avatar() { return "src='". $this->AvatarCesta()."'"; }
    public function Render($href = false) 
    {
	    /* 	Informacie zapiname zadanim v UDAJI :)	*/
	    // Cely objekt mena
		$temp  = '<span class="user" id="'.$this->__data['user_id'].'" onmouseover="fto(this);" onmouseout="ftu(this);" >';    
        // Odkaz.... napr.  na profil 
        $temp  .= '<a href="'.$this->Link($href).'" onmouseover="fu(this);" ';
	    // Farba textu podla kategorie
		$temp .=  self::Kategorie();
	    // Postavicka 
	    if($this->__data['postava']) $temp .= $this->MiniPostava();
	    // Clan hodnosti 
	    /*if($this->__data['clan_hodnost']) {
		    $x = $GLOBALS['hodnost'][$this->__data['clan_hodnost']];
		    $temp .='<img align="absmiddle" src="/cup/styles/styles_web2/hodnost/'.$this->__data['clan_hodnost'].'.png" title="Hodnos&#357;: '.$x.'" alt="'.$x.'" title="'.$x.'" border="0" align="absmiddle" width="16" height="16" />';
	    }*/
	    $temp .= $this->Bonuses();
        // Meno 
		$temp .= (isset($this->__data['class'])) ? '<span class="'.$this->__data['class'].'">'.$this->__data['user_name'].'</span></a>' : $this->__data['user_name'].'</a>';
	    // Zobrazime ikonku na pridanie priatelov
        $temp .= '<img onclick="fa(this);" class="a b" align="abmiddle" title="Pridaj k priate&#318;om" alt="+" src="'.ROOT.'web2/images/tool/plus1.png" />';        
		$temp .= '</span>';		
	    // Koniec
	    return $temp;
    }
    public function Link($href=false) {
        if($href) return sprintf($href, $this->uout('user_name'));
        else return ROOT.'hrac/'.$this->uout('user_name').'/';
    }
    public function MiniPostava() {
        return '<img align="absmiddle" border="0" alt="" width="12" width="12" src="'.self::Postavicka($this->__data['postava'], 16).'">';
    }
    public static function Postavicka($id, $velkost) { return ROOT.'web2/images/postavicky/'.$id.'/'.$velkost.'.png'; }
    public function Posta() {
	    $meno = $this->out('user_name');
        echo '<a href="', ROOT ,'hrac/', STR::uri_out($meno), '/posli-spravu/">
				    <img src="', ROOT, 'web2/images/tool/pm0.gif" alt="Po&scaron;li spr&aacute;vu ', $meno, '" title="Po&scaron;li spr&aacute;vu ', $meno, '" border="0" align="absmiddle">
			    </a>';
    }
    public static function SuperAdmin() {
        return ($this->__data['user_level'] == 103);
    }
    public function MiniItem() {
	    echo '
	    <tr>
		    <td width="5%"> </td>
		    <td width="75%">
			    <img hspace="4" vspace="3" width="24" align="absmiddle" border="0" height="24" alt="', $this->out('user_name'), '" ', $this->Avatar(), ' />
			     ', self::Render().'
		    </td>
		    <td width="10%">', self::Posta(), '</td>
		    <td width="10%"> </td>
	    </tr>';
    } 
    public function is_friend($id) {
        return DB::One("SELECT id FROM `priatelia` WHERE `id`='".$id."' AND `friend`='".$this->__data['user_id']."'");
    }
    public function Get($co = '*', $kde = '') {
        return DB::Query("SELECT ".$co." FROM `cstrike`.`fusion_users` ".$kde);
    }   
    public function GetID($id, $data = '*') {
        $sql = $this->Get($data, "WHERE `user_id`='".$id."'");
        @$this->__data = $sql->fetch_assoc();
    }
}   
