<?
require_once($_SERVER["DOCUMENT_ROOT"].'/globals.php');  
Ajax::Start();
Input::issets('widget', 'item');

$objekt = new Cache('ajax.php_'.$widget.'_'.$item, 60);
$objekt->bot = false;
$objekt->Zlozka('ajax');
$objekt->ClientCacheTO();
$objekt->FullFile();
                                              
switch($widget) {
	case "stats" : {
		if( $item == "servery") {
			// Servery
			echo '
			Psychostats hr&#225&#269ov: 
			<span class="info_gray">', 
			//DB::One("SELECT COUNT(plrid) as pocet FROM `psychostats`.`ps_plr`"), 
			'</span><br>
			Zombie banka &#250&#269ov: 
			<span class="info_gray">', DB::One("SELECT COUNT(id) as pocet FROM `phpbanlist`.`zp_bank`"), '
			</span><br>
			Celkovo zombie bodov: 
			<span class="info_gray">', DB::One("SELECT SUM(amount) as pocet FROM `phpbanlist`.`zp_bank`"), '
			</span><br>
			Celkovo VIP &#250&#269tov: 
			<span class="info_gray">', DB::One("SELECT COUNT(user_id) as pocet FROM `cstrike`.`fusion_users` WHERE vip > '".Time::$TIME."'"), '
			</span><br>
			',
			/*<tr>
				<td>Rezervovan&#253ch nickov</td>
				<td>', DB::One("SELECT COUNT(user_id) as pocet FROM `cstrike`.`fusion_users` WHERE m_amx = '1'"), '</td>
			</tr>*/
			'Rezervovan&#253ch slotov: 
			<span class="info_gray">', DB::One("SELECT COUNT(user_id) as pocet FROM `cstrike`.`fusion_users` WHERE slot > '".Time::$TIME."'"), '
			</span><br>
			Hern&yacute;ch adminov: 
			<span class="info_gray">', DB::One("SELECT COUNT(id) as pocet FROM `phpbanlist`.`amx6_amxadmins` WHERE `access` LIKE '%u%'"), '
			</span><br>';
		} elseif( $item == "webu") {
			// Webu
			//	 Kolko uzivatelov
			$pocet = DB::One("SELECT COUNT(user_id) as pocet FROM `cstrike`.`fusion_users`");
			
			//	Novy uzivatelia za posledny mesiac
			$cas = Time::$TIME - Time::Dni(30);
			$uzivatelia =DB::One("SELECT COUNT(user_id) as pocet FROM `cstrike`.`fusion_users` WHERE user_joined > '".$cas."'");

			// 	Aktivnych 40% vypocita za tyzden z lats join
			$cas = Time::$TIME - Time::Dni(7);
			$aktivita = DB::One("SELECT COUNT(user_id) as pocet FROM `cstrike`.`fusion_users` WHERE user_lastvisit > '".$cas."'");
			$aktivita = $pocet != 0 ? ceil($aktivita*100 / $pocet) : '0';

			echo '
			Reg. u&#158&#237vate&#318ov: 
			<span class="info_gray">', $pocet, '</span><br>
			Nov&#253 u&#158&#237vatelia za posledn&#253 mesiac: 
			<span class="info_gray">', $uzivatelia, '</span><br>
			Akt&#237vnych u&#158&#237vate&#318ov: 
			<span class="info_gray">', $aktivita, '%</span><br>
			Celkovo kor&#250n: 
			<span class="info_gray">', DB::One("SELECT SUM(korun) FROM `cstrike`.`fusion_users` "), '</span><br>
			Web po&scaron;ta spr&aacute;v: 
			<span class="info_gray">', DB::One("SELECT COUNT(message_id) as pocet FROM `cstrike`.`fusion_messages` "), '</span><br>
			';
				/*<tr>
					<td>Nak&#250pen&#253ch kor&#250n</td>
					<td>'.$x.'</td>
				</tr>*/
            echo '	
				Moder&aacute;torov: 
				<span class="info_gray">', DB::One("SELECT COUNT(user_id) as pocet FROM `cstrike`.`fusion_users` WHERE user_rights LIKE '%AC%'"), '</span><br>
			';
		} elseif( $item == "liga") {
			// Liga
			//	-zapojenych hracov
			$zapojenych = DB::One("SELECT COUNT(user_id) as pocet FROM `cstrike`.`fusion_users` WHERE m_liga='1'");

			//	-60% v clane
			$pocet = DB::One("SELECT COUNT(user_id) as pocet FROM `cstrike`.`fusion_users` WHERE clan_id != '0'");
			$clan = $zapojenych != 0 ? round( $pocet * 100 / $zapojenych, 1) : '0';

			//	-40% na volnej nohe
			$pocet = DB::One("SELECT COUNT(user_id) as pocet FROM `cstrike`.`fusion_users` WHERE clan_id = '0'");
			$volno = $zapojenych != 0 ? round( $pocet * 100 / $zapojenych, 1) : '0';

			echo 'Celkovo bodov: 
			<span class="info_gray">', DB::One("SELECT SUM(bodov) as pocet FROM `phpbanlist`.`acp_clans`"), '</span><br>
			Celkov&#225 obtia&#382nos&#357: 
			<span class="info_gray">', DB::One("SELECT SUM(ziada_narocnost) + SUM(prijal_narocnost) as pocet FROM `phpbanlist`.`acp_zapas`"), '</span><br>
			Bonus pre clany: 
			<span class="info_gray">', DB::One("SELECT SUM(bonus) as pocet FROM `phpbanlist`.`acp_clans`"), '</span><br>
			Celkovo clanov: 
			<span class="info_gray">', DB::One("SELECT COUNT(id) as pocet FROM `phpbanlist`.`acp_clans`"),'</span><br>
			Z&#225pasov: 
			<span class="info_gray">', DB::One("SELECT COUNT(id) as pocet FROM `phpbanlist`.`acp_zapas`"), '</span><br>
			Aktu&#225lne v&#253ziev: 
			<span class="info_gray">', DB::One("SELECT COUNT(id) as pocet FROM `phpbanlist`.`acp_vyzva`"), '</span><br>
			Zapojen&#253ch hr&#225&#269ov: <span class="info_gray">', $zapojenych, '</span><br>
			V clane: <span class="info_gray">', $clan, '%</span><br>
			Na vo&#318nej nohe: <span class="info_gray">', $volno, '%</span><br>';
		} else {
			$objekt->cant();
		}
	break;	
	}	
	case "top" : {
		$rank=0;
		function top_widget($id) {
            $sql = DB::Query("SELECT user_id, user_name, postava, vip, slot FROM `cstrike`.`fusion_users` 
                                WHERE `".$id."` IS NOT NULL 
                                ORDER BY `".$id."` DESC 
                                LIMIT 10");
            $m = new Member;
            while($m->Next($sql)) {            
                $rank++;
                $pocet = Dni::Rozdiel($m->Get($id));
                if($pocet > 1) {
                    echo'<div class="bingo">
                        <span class="rank">', GButtons::TopRank($rank), '</span>
                        ', $m->Render(), '
                        <span class="r info_gray"> ', $pocet, 'dni </span>
                    </div>';
                }
            }  
        }
        
		// Stranky
		if($item == "najbohatsi") {
			$m = new Member;
			$sql = $m->Get(Member::REQUEST.', korun', 'ORDER BY korun DESC LIMIT 10');
            while($m->Next($sql)) {			
				$rank++;
                echo'<div class="bingo">
                    <span class="rank">', GButtons::TopRank($rank), '</span>
                    ', $m->Render(), '
                    <span class="r info_gray">', $m->korun, '</span>
                </div>';
			}
		} else if($item == "vip") {
			top_widget('vip');	
		} else if($item == "slot") {
	        top_widget('slot');
		} else if($item == "najaktivnejsi") {
			$sql = DB::Query("SELECT user_id, user_name, postava, vip, slot, user_posts FROM `fusion_users` ORDER BY user_posts DESC LIMIT 10");
			$m = new Member;
            while($m->Next($sql)) {		
                $rank++;
			    echo'<tr class="bingo">
					<span class="rank">', GButtons::TopRank($rank), '</span>
                    ', $m->Render(), '
                    <span class="r info_gray"> ', $m->user_posts, '</span>
                </tr>';
			}	
		} else {
			$objekt->cant();
		}		
	break;	
	}
	case "tip" : {
		 if($item == "dennik") { 
            // Novinky budu istou formou logy mame cas a kto napisal   
            $sql = DB::Query("SELECT co, user_name FROM `cstrike`.`web2_logs` l
                                    LEFT JOIN ( SELECT user_id, user_name FROM `cstrike`.`fusion_users` ) u
                                        on l.kto = u.user_id
                                    WHERE kat='0' AND typ='1' 
                                    ORDER BY kedy DESC LIMIT 10");  
            $m = new Member;
            echo '<div id="dennik-zoznam">';
            while($m->Next($sql)) {            
                echo'<div class="bingo">
                    <a href="', $m->Link(), '">', $m->Out('user_name'), ':</a>
                    <span class="denniktext">', $m->Out('co'), ' </span> 
                </div>';                
            }
            echo '</div>            
            <div>
	            <input name="dennik" type="text" value="Nap&iacute;sa&#357; do denn&iacute;ka..." id="dennik" onclick="dennik_click(this);" size="20" maxlength="33">
	            <img src="', ROOT, 'web2/images/tool/plus1.png" alt="ADD" class="cursor" align="absmiddle" onclick="dennik(this);"/>
	            
	            <div id="dennik-text" class="hidden">
	                Pridaj spr&aacute;vu do denn&iacute;ka, ktor&uacute;<br>
	                kazd&yacute; uvid&iacute;,  nejak&uacute; &#382;iadost alebo<br> 
	                len r&yacute;chlu novinku. <span class="info_gray">', Shop::GetAbs(11), ' kreditov</span>    
	            </div>
            </div>';       
        } elseif($item == "vyhry") {			
            WebLog::Item("kat='2' AND typ IN ('23', '2', '3', '4') ORDER BY kedy DESC LIMIT 10");
		} elseif($item == "clany") { // udalosti v v lige a clanoch
			 WebLog::Item("kat='-1' AND 
			                typ IN ('1', '2', '3', '4', '5', '9', '24', '28')
							ORDER BY kedy DESC LIMIT 5",
                          ' bingo_widget');
		} else if($item == "koruny") {
			WebLog::Item("( kat='2' AND typ IN ('7', '6', '10') )
		                    OR
			                ( kat='1' AND typ IN ('15', '16') )							
							ORDER BY kedy DESC LIMIT 5",
                            ' bingo_widget');
		} else {
			$objekt->cant();
		}
			
		/* 
			Kazdy den, kazdu hodinu CRON TAB.
			Do tabulky clanu pridame stara_pozicia 
			Vypocita poziciu a ulozi do starej pozicie.
			Takto zisTime::$Time zhruba rank clanu.
			No a kazdu hodinu do lodu prida ze CLAN postupil o 1 miesto hore.
			Neskor podla logov sa da vytvorit "historia" podla logov ale aj graf a za mesiac bude ukazovat o kolko postupili....
			
		*/
	break;	
	}	
	case "users" : {
		if($item == "online") {
			Kategorie::Load();
			@$sql = DB::Query("SELECT user_id, user_name, postava, cs_meno, user_icq, vip, slot, user_groups as skupina FROM `fusion_users` WHERE user_lastvisit > '".Time::$ONLINE."' ORDER BY user_lastvisit DESC LIMIT 10");
			$m = new Member;
            while($m->Next($sql)) {
                echo'
					<div class="bingo">
						', $m->Render(), 
                        '<span class="info_gray"> (',
                        Kategorie::One($m->skupina),	
                        ') </span> 					
					</div>';
			}
		} else if($item == "novacikovia") {
			$sql = DB::Query("SELECT user_id, user_name, postava, cs_meno, user_icq, vip, slot, user_joined, user_lastvisit FROM `fusion_users` ORDER BY user_joined DESC LIMIT 10");
			$m = new Member; 
            while($m->Next($sql)) {			
                echo'<div class="bingo">
						', $m->Render(), '
						<span class="info_gray"> (', date("Y-m-d", $m->user_joined), ') </span>
					</div>';
			}
		} else {
			$objekt->cant();
		}
		break;	
	} 
    case 'shoutbox' : {
        // Const
        $settings['numofshouts'] = 10;
        
        $numrows = DB::One("SELECT count(shout_id) as pocet FROM ".DB_PREFIX."shoutbox");
        $result = dbquery(
            "SELECT * FROM ".DB_PREFIX."shoutbox LEFT JOIN ".DB_PREFIX."users
            ON ".DB_PREFIX."shoutbox.shout_name=".DB_PREFIX."users.user_id
            ORDER BY shout_datestamp DESC LIMIT 0,".$settings['numofshouts']
        );
        if (mysql_num_rows($result) != 0) {
            $i = 0;
            while ($data = mysql_fetch_assoc($result)) {
                echo "<span class='shoutboxname'><img src='/themes/seky_web2/images/bullet.gif' alt='' /> ";
                if ($data['user_name']) {
                    echo "<a href='".BASEDIR."profile.php?lookup=".$data['shout_name']."' class='side'>".$data['user_name']."</a>\n";
                } else {
                    echo $data['shout_name']."\n";
                }
                echo "</span><br>\n<span class='shoutboxdate'>".date("Y-m-d", $data['shout_datestamp'])."</span>";
                //if (iADMIN && checkrights("S")) {
                //    echo "\n[<a href='".ADMIN."shoutbox.php".$aidlink."&amp;action=edit&amp;shout_id=".$data['shout_id']."' class='side'>".$locale['048']."</a>]";
                //}
                echo "<br>\n<span class='shoutbox'>", str_smiley($data['shout_message']), "</span><br>\n";
                if ($i != $numrows) echo "<br>\n";
            }
            if ($numrows > $settings['numofshouts']) {
                echo "<center>\n<img src='".THEME."images/tool/bullet.gif' alt=''>
                <a href='".INFUSIONS."shoutbox_panel/shoutbox_archive.php' class='side'>a</a>
                <img src='".THEME."images/tool/bulletb.gif' alt=''></center>\n";
            }
        } else {
            echo "<div align='left'>".$locale['127']."</div>\n";
        }
    }
    case 'admin' : { 
            require_once('../hlasovanie.php'); 
            
            if($item == "kandidati") { 
                $sql = DB::Query("SELECT k.id, server, server_name, user_name, u.user_id, user_groups, vip, slot, 
                                    COALESCE(za, 0) as za, 
                                    COALESCE(proti, 0) as proti, 
                                    COALESCE(komentarov, 0) as komentarov, 
                                    COALESCE(`za`+`proti`, 0) as hlasov  
                FROM `cstrike`.`kandidati` k                   
                        JOIN ( SELECT user_name, user_id, user_icq, user_groups, vip, slot FROM `cstrike`.`fusion_users` ) u
                            ON k.user = u.user_id    
                        LEFT JOIN ( SELECT id, COUNT(id) AS `za` FROM `phpbanlist`.`web2_hlasovanie` WHERE `typ`='4' AND `kolko`='1.0' GROUP BY id ) a
                            ON k.id = a.id                        
                        LEFT JOIN ( SELECT id, COUNT(id) AS `proti` FROM `phpbanlist`.`web2_hlasovanie` WHERE `typ`='4' AND `kolko`='1.0' GROUP BY id ) b
                            ON k.id = b.id    
                        LEFT JOIN ( SELECT comment_item_id, COUNT(comment_id) as komentarov FROM `cstrike`.`fusion_comments` WHERE comment_type = 'K' GROUP BY comment_item_id ) c    
                            on k.id = c.comment_item_id    
                        LEFT JOIN ( SELECT id, nazov as server_name FROM `cstrike`.`servers` ) d    
                            on k.server = d.id
                    ORDER BY hlasov DESC LIMIT 10");
                $kat = 4;
                $link = ROOT.'kandidovat/kandidat/%s/';
                $m = new Member;
                
                while($m->Next($sql)) {
                    echo '<div class="bingo" align="center">
                        <span class="l">',                               
                        Buttons::LIKE_UP($kat, $m->id, 'Prija&#357;'),
                        '</span>', $m->Render($link),
                        '<br /><a class="servername_font" href="', ROOT, 'server/', $m->server, ',', $m->uout('server_name'), '/"> ', $m->out('server_name'), ' </a>                         
                        <span class="r">',
                        Buttons::LIKE_DOWN($kat, $m->id, 'Neprija&#357;'),
                        '</span>
                    </div>';
                }
            } elseif ($item == "rankadmin") {
                Kategorie::Load();                
                $sql = DB::Query("SELECT 
                    DISTINCT user_id, user_name, u.user_id, user_groups, vip, slot,
                    COALESCE(`hlasov`, 0) AS `hlasov`, 
                    COALESCE(`komentarov`, 0) as komentarov 
                    FROM `phpbanlist`.`amx6_amxadmins` a                    
                        JOIN ( SELECT user_name, user_id, user_groups, vip, slot, amxid FROM `cstrike`.`fusion_users` ) u
                            ON a.id = u.amxid   
                        LEFT JOIN ( SELECT id, COUNT(id) AS hlasov FROM `phpbanlist`.`web2_hlasovanie` WHERE `typ`='4' GROUP BY id ) h
                            ON u.user_id = h.id    
                        LEFT JOIN ( SELECT comment_item_id, COUNT(comment_id) as komentarov FROM `cstrike`.`fusion_comments` WHERE comment_type = 'R' GROUP BY comment_id ) c    
                            on u.user_id = c.comment_item_id  
                    ORDER BY hlasov DESC LIMIT 10");
                $url = ROOT.'rank-admin/%s/';
                $m = new Member;
                while($m->Next($sql)) {    
                    $temp = $m->user_groups;
                    $m->user_groups = '';                   
                    echo'<div class="bingo">         
                        <div class="new_admin_name">
                            ', $m->Render($url), '<br>
                            <span class="info_gray"> ', Kategorie::Max2($temp), ' </span>
                        </div>
                        <div class="new_admin_comm info_gray">
                            ', $m->hlasov, ' <img border="0" alt="Hlasov" title="', $m->hlasov, ' hlasov" src="', ROOT, 'web2/images/tool/user1.png" /> (', $m->komentarov, ')
                        </div>
                    </div>';
                }
           } else {
               $objekt->cant();
           }
           break;
    }
	default : {
		$objekt->cant(); 
		break;
	}
}
