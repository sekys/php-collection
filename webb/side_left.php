<?php
if (!defined("IN_FUSION")) { header("Location: index.php"); exit; }

if(!Theme::$left) {
	echo '<td class="side-border-left"> </td>'; 
	echo "<td valign='top' class='main-bg'>";
} else {

	$objekt = new Cache('side_left_'.(iMEMBER ? '1' : '0'), 30); 
	$objekt->bot = false;
	$objekt->Zlozka('theme');
	
	if($objekt->File()) { 
	    Debug::Oblast('SIDELEFT');
	    echo "<td width='200' valign='top' class='side-border-left'>";

	    // Liga panel
	    if(iMEMBER) echo '<div class="usersidepanel-left"> </div>'; 
		
		echo '<div class="themepanel">';
		// Shop
		echo '<div class="scaptop scaptopfix">';                    
		if(!iMEMBER) {
		    // Ban list a linky
		    echo '<a href="http://www.cs.gecom.sk/ban/">
	    		<img height="45" width="190" border="0" alt="Amx Bans" src="http://www.cs.gecom.sk/images/amxbans.jpg">
	    		</a>';
		    // Path
		    echo '<a href="http://www.cs.gecom.sk/patch/">
	    		<img height="45" width="190" border="0" alt="Protokol" src="http://www.cs.gecom.sk/webb/images/protokol.jpg">
	    		</a>';	    
		    // Path
		    echo '<a href="http://www.cs.gecom.sk/vip/">
	    		<img height="45" width="190" border="0" alt="VIP" src="http://www.cs.gecom.sk/webb/images/vip.gif">
	    		</a>';
		    // Reklama
	   		echo '<a href="javascript:Contact(\'partner\');">
	    		<img height="45" width="190" border="0" src="', RIMAGE, 'theme/adv190x45.jpg">
	    		</a>'; 
	    			
			} 
		echo '</div>';
			   
		$data = array();
		if(iMEMBER) {
		$data[] = array('G/L pre V&aacute;s',
		            array(
		                array('nastavenie/', 'Nastavenie serverov'),
		                array('obchod/', 'Obchod'),
		                //array('stavky/', 'St&aacute;vkova&#357;'),
		                array('kandidovat/', 'Kandidova&#357; na Admina'),
		                array('zaznamy/', 'Z&aacute;znamy')
		            )
		        );
		}
		$data[] = array('G/L pre v&scaron;etk&yacute;ch',
		        array(
		            array('rank-admin/', 'Rank adminov'),
		            array('pravidla/', 'Pravidl&aacute;'),
		            array('kredity/', 'Kredity'),
		            array('vip-sloty/', 'VIP a Sloty'),
		            array('legenda/', 'Legenda'),
		            array('sponzori/', 'Sponzori')
		        )
		    );                
		Theme::Widget2($data);
		unset($data);
		closeside();
			
				
		// Top
		Theme::Widget('TOP U&#382;ivatelia', 'top', 
		    array( 
		        array('TOP', 'najbohatsi'),
		        array('VIP', 'vip'),
		        array('Slot', 'slot'),
		        array('Najakt&iacute;vne&scaron;&iacute;', 'najaktivnejsi')
		    )
		);
		
		// Nahodny uzivatel,...
		if(!iMEMBER) {
			$pocet = 300; 	//DB::One('SELECT COUNT(`user_id`) FROM`cstrike`.`fusion_users` WHERE user_avatar !=""');
	    					// staci raz za cas vypocitat
	     		$m = new Member;    
	    		$data = DB::Query("SELECT user_id, user_name, user_avatar, user_lastvisit, vip, slot  
	    						FROM `cstrike`.`fusion_users` 
	    						WHERE user_avatar !=''
	    						LIMIT ".rand(0, $pocet).", 1");
				if($m->next($data)) {
					openside($m->user_name);       
				    echo '<div align="center">
				        <a href="', ROOT, 'hrac/', $m->user_name, '/" >
				            <img vspace="5" hspace="5" ', $m->Avatar(), ' alt="', $m->user_name, '" title="', $m->user_name, '" border="0" width="96" height="96" /></a>    
						</a><br>', 
							$m->OnlineStatus(), ' ', 
							$m->Bonuses(), ' ', 
							$m->ICQStatus(), 
						'</div>';
						
					closeside();		    
				}
				unset($m);			
		}
		// Shop Galeria
		openside('Shop');
		echo'<div align="center" id="shopgaleria"></div>';
		closeside();	
		
	 		
		// Tip
		Theme::Widget('Udalosti d&#328;a', 'tip', 
		    array( 
		        array('Denn&iacute;k', 'dennik'),
		        array('V&yacute;hry', 'vyhry'),
		        array('Clany', 'clany'),
		        array('Koruny', 'koruny')
		    )
		);
		
	    echo '</td>'; 
	    echo "<td valign='top' class='main-bg'>";
	    Debug::Oblast('SIDELEFT');
	}
	$objekt->File();
	unset($objekt);
}

Web2Action::Specific();
