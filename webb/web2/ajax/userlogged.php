<?
require_once($_SERVER["DOCUMENT_ROOT"].'/globals.php'); 
Ajax::Start();
if(!User::Logged()) exit; // bez spravy treba
Input::issets('akcia');
require '../../fusion/fusion.php';
                                    
switch($akcia)
{
    case 'headerpanel' : 
    {        
		echo '
		<div class="name">
			Prihl&aacute;sen&yacute; ako:
			<a href="', ROOT, 'hrac/', STR::uri_out(User::$m->user_name), '/"> ', User::$m->user_name, ' </a>          
		</div>
		<img align="absmiddle" border="0" alt="SVK" title="Kor&uacute;n" src="', ROOT, 'web2/images/tool/korun.png" /> ';
		echo User::$m->korun;
		$data = HeaderUserPanel();                
		echo '       
		<a href="', ROOT, 'messages.php" title="Web po&scaron;ta">
		    <img align="absmiddle" border="0" alt="PM" src="', ROOT, 'web2/images/tool/pm.png" />
		    PM (', NeprecitaneSpravy(User::$m->user_id), ')
		</a>';
		// LogOut na Fb ?, nebude bude stale prihlaseny ak je aj na fb,...
		// Alebo odkaz na FB kde sa odhlasuje ?
		echo '
		<div class="status">
		<a href="', ROOT, 'setuser.php?logout=yes" title="Odhl&aacute;si&#357;">
		    <img height="17" width="70" align="absmiddle" onmouseover="this.src=\'', ROOT, 'web2/images/theme/logout2.gif\'" onmouseout="this.src=\'', ROOT, 'web2/images/theme/logout1.gif\'" border="0" alt="Odhl&aacute;si&#357;" src="', ROOT, 'web2/images/theme/logout1.gif" />
		</a>
		<table cellspacing="0" cellpadding="0">';            
		    hlstatus();              
		    echo'
		    <tr>
		        <td><span class="page_infof">Užívatelia online:</span></td>
		        <td><span class="page_infof"> <strong>', $data['B'], '</strong> / '.$data['A'].'</span></td>
		    </tr>
		</table>
		</div>';
        break;
    }
    case 'usersidepanel-left' : 
    {
    	if(!User::$m->clan_id) exit;   	
    	$sql = DB::Query('SELECT id, avatar, meno, bodov FROM `phpbanlist`.`acp_clans` WHERE `id`='.User::$m->clan_id);
    	if($sql->num_rows) {
    		$data = new DBTable;
    		$data->next($sql);
    		$data->setout('meno');
    		openside($data->meno);            
	        SidebarClanPannel($data);
	        closeside(); 
		}
		break;
	}
    case 'usersidepanel-right' : 
    {
        $objekt = new Cache('userlogged'.User::$m->user_id, 30);
		$objekt->Zlozka('ajax');
		$objekt->ClientCacheTO();
		if($objekt->File()) {
	        $name = User::$m->out('user_name');
	        openside($name);            
	        SidebarUserPannel($name);
	        closeside(); 
	        
	        openside('Priatelia');            
	        Priatelia(User::$m->user_id);
	        closeside();
	                    
	        // Liga
	        if(User::$m->clan_id) {
       			openside('Spoluhr&aacute;&#269;i');
	    		ClanSpoluhraci(User::$m->clan_id, User::$m->user_id);             
		    } else {
	     		openside('Vo&#318;n&eacute; miesta');  
	       		VolneClany(7);
		    }
		    closeside();  
		}
	    $objekt->File();  
        break;
    }
}
function ClanSpoluhraci($clanid, $userid)
{
	$sql = DB::Query("SELECT user_id, user_avatar, postava, cs_meno, user_lastvisit, user_icq, user_name, vip, slot FROM `fusion_users`	
						WHERE `clan_id` = '".$clanid."' AND `user_id` != '".$userid."'");

	$m = new Member();	
	echo'<table width="180" border="0" cellspacing="0" cellpadding="0" >';				
	while($m->Next($sql))	{
        $m->setout('user_name');
			 echo '<tr>
				<td width="90%">
					<img width="24" align="absmiddle" border="0" height="24" alt="', $m->user_name, '" ', $m->Avatar(), ' >
					', $m->Render(), '
				</td>
				<td align="right">', $m->Posta(), '</td>
			 </tr>';
	}
	echo '</table>';		
}
function VolneClany($limit) 
{
	$sql = DB::Query("
		SELECT id, meno, bodov FROM `phpbanlist`.`acp_clans` 
		WHERE volne ='1' 
		ORDER BY hracov desc 
		LIMIT ".$limit);
				
	while($data = $sql->fetch_assoc())	{
    	GButtons::abingo2( 
        '/cup/ziadat-pozvanku/'.$data['id'].'/', 
        	'<img src="'.ROOT.'cup/styles/styles_web2/right.png" alt="" align="absmiddle" hspace="5" border="0"/>', 
        	DB::Vystup($row['meno']),
        	$row['bodov']
		);	
	}
}
function SidebarUserPannel($name)
{    
    echo '
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>            
            <td>
                <a href="', ROOT, 'hrac/', User::$m->uout('user_name'), '/" >
                    <img vspace="5" hspace="5" ', User::$m->Avatar(), ' alt="', $name, '" title="', $name, '" border="0" width="96" height="96" /></a>    
            </td>
            <td valign="middle">
                <p align="center">', Kategorie::GDX(User::$m->user_groups), '</p>        
                <a href="http://www.cs.gecom.sk/psychostats/index.php?q=', (User::$m->cs_meno ? User::$m->uout('cs_meno') : $name), '">P-Stats profil</a><br>
                ';
                if( User::$m->clan_id)
                {
                    if( User::$m->clan_hodnost) { 
                        echo "<a ", CHOVER, " href='", ROOT, "cup/vyzvy/'>N&aacute;js&#357; z&aacute;pas</a><br>";
                    } else {    
                        echo "<a ", CHOVER, " href='", ROOT, "cup/clan/", User::$m->clan_id, "/'>M&ocirc;j clan</a><br>";
                    }                    
                } else {
                    echo "<a ", CHOVER, " href='", ROOT, "cup/miesto/'>N&aacute;js&#357; clan</a><br>";
                }
                if(iADMIN && (iUSER_RIGHTS != "" || iUSER_RIGHTS != "C")) {
                    global $aidlink;
                    echo "<a ", CHOVER, " href='", ROOT, "administration/index.php".$aidlink."'>Admin Menu</a><br>";
                }                
                echo '
                <a href="', ROOT, 'upravit-profil/" ', CHOVER, '>Upravi&#357; profil</a>
            </td>
        </tr>    
    </table>';	
}
function SidebarClanPannel($data)
{
    /*
    	na pravo oproti profil menu, bude clan men ua hned 1. ako funckiu len
		bude tam rovnako clan avatar v strede, hore namiesto hodnosti budu body
		a dole nejake veci, pouzit rovnaku funkciu na struktury  
    */
    
    echo '
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>            
            <td>
                <a href="', ROOT, 'cup/clan/', $data->id, '/" >
                    <img vspace="5" hspace="5" ', 
                	($data->avatar) ? 'src="'.$data->avatar.'"' : 'src="'.ROOT.'cup/styles/styles_web2/no_avatar.png"', 
                    ' alt="', $name, '" title="', $name, '" border="0" width="96" height="96" /></a>    
            </td>
            <td valign="middle">
                <p align="center">', $data->bodov, 'bodov</p>        
                <a href="', ROOT, 'cup/clan/', $data->id, '/">Profil</a><br>
                <a href="', ROOT, 'cup/nastavenia/', $data->id, '/">Nastavenia</a><br>
                <a href="', ROOT, 'cup/nastavenia-hraci/', $data->id, '/">Spr&aacute;va hr&aacute;&#269;ov</a><br>
                <a href="', ROOT, 'cup/nastavenia-vyzvy/', $data->id, '/">Moje v&yacute;zvy</a><br>
            </td>
        </tr>    
    </table>';	
}
function Priatelia($id)
{
	$sql = DB::Query("SELECT user_id, user_avatar, postava, cs_meno, user_lastvisit, user_icq, user_name, vip, slot, COALESCE( s.sprav, 0) as sprav FROM `priatelia` p
							JOIN ( SELECT user_id, user_avatar, postava, cs_meno, user_lastvisit, user_icq, user_name, vip, slot FROM `fusion_users` ) u
								ON p.priatel = u.user_id
							LEFT JOIN ( SELECT message_from, COUNT(message_to) AS sprav FROM `fusion_messages` WHERE message_read='0' AND message_folder='0' AND message_to='".$id."' GROUP BY message_to ) s
								ON p.priatel = s.message_from	
						WHERE id = '".$id."'");
	echo '<div align="center" class="priatelia_panel">
			<p>
				<img align="top" border="0" alt="Prida&#357;" title="Prida&#357;" src="', ROOT, 'web2/images/tool/plus1.png">
				<a class="b_link" href="', ROOT, 'hladaj/">Prida&#357; priate&#318;a </a>
			</p>';	
	$m = new Member();					
	echo '<table id="priatel-', $m->user_id, '" width="180" border="0" cellspacing="0" cellpadding="0" >';
	while($m->Next($sql))	{
		$m->class = ($m->sprav) ? 'neprecitana_sprava' : '';
		if($m->postava) {
			// Ak ma postavicku
			$m->user_avatar = $m->postava;
			$m->postava = false;
		}
        $m->setout('user_name');
		 echo '<tr>
			<td width="90%" ', ($m->sprav ? "title=\"Nov&aacute; spr&aacute;va od ".$m->user_name."\"" : ""), '>',
				Buttons::Span('close', 'fd(this, '.$m->user_id.')'),
				'<img width="24" align="absmiddle" border="0" height="24" alt="', $m->user_name, '" ', $m->avatar(), ' >
				', $m->Render(), '
			</td>
			<td align="right">', $m->Posta(), '</td>
		 </tr>';
	}
	echo '</table>';			
	echo '</div>';
}	
function HeaderUserPanel() {  
    /** Staviame SQL query **/
    $txt = "SELECT 
    count(`user_id`) as `A`, 
    ( SELECT count(`user_id`) FROM fusion_users WHERE user_lastvisit > '".Time::$ONLINE."') AS `B`";

    if(User::$m->zombieid)
        $txt .= ", (SELECT `amount` FROM `phpbanlist`.`zp_bank` WHERE id ='".User::$m->zombieid."') AS `Z`";
    if(User::$m->m_dr)
        $txt .= ", (SELECT `amount` FROM `phpbanlist`.`dr_bank` WHERE id ='".User::$m->m_dr."') AS `D`";

    $txt .= " FROM `cstrike`.`fusion_users`";
    $sql = DB::Query($txt);
    $data = $sql->fetch_assoc(); 
    
    // Kontrolujeme dalej jednotlive veci...
    if(User::$m->zombieid)    {
        if( isset($data['Z']) ) {
            User::$m->zombieid = 0;
            DB::Query("UPDATE `cstrike`.`fusion_users` SET `zombieid` = '0' WHERE user_id = '".User::$m->user_id."'");
        } else {
            echo '&nbsp;&nbsp;<img align="absmiddle"  border="0" alt="Zombie bodov" title="Zombie bodov" src="'.ROOT.'web2/images/tool/zombie_body.png" />';
            echo $data['Z'];
        }
    }                                
    if(User::$m->m_dr)    {
        if( isset($data['D']) ) {
            User::$m->m_dr = 0;
            DB::Query("UPDATE `cstrike`.`fusion_users` SET `m_dr` = '0' WHERE user_id = '".User::$m->user_id."'");
        } else {
            echo '&nbsp;&nbsp;<img align="absmiddle"  border="0" alt="Deathrun bodov" title="Deathrun bodov" src="'.ROOT.'web2/images/tool/dr_body.png" />';
            echo $data['D'];
        }    
    } 
    return array('A'=> $data['A'], 'B'=> $data['B']);
}
function NeprecitaneSpravy($id) {
    $msg_count = DB::One('SELECT COUNT(message_id) FROM `cstrike`.`fusion_messages` WHERE message_to="'.$id.'" AND message_read="0" AND message_folder="0"');    
    if(!$msg_count) return '0'; 
    if($msg_count >= 10)
        return '<strong><span class="color_red">'.$msg_count.'</span></strong>';

    return '<strong><span class="color_green">'.$msg_count.'</span></strong>';
}
function hlstatus() {
    $objekt = new Cache('hlstatus', 30);
    $objekt->bot = false;
    $objekt->Zlozka('ajax');
    if($objekt->File()) {  
         
	    $sql = DB::Query("SELECT ip, port FROM `cstrike`.`servers` WHERE `liga`='0'");    
	    while($server = $sql->fetch_row()) {    
	        if ( $gameserver = new HLServer($server[0], $server[1], 1) ) { 
	            $gameserver->get_pocethracov();
	            $pocet[0] += $gameserver->serv_infos[5];
	            $pocet[1] += $gameserver->serv_infos[6];
	        }                
	    }  
	    echo '<tr>
	        <td><span class="page_infof">Hr&aacute;&#269;ov online:</span></td>
	        <td><span class="page_infof"> <strong>', $pocet[0], '</strong> / ', $pocet[1], '</span></td>
	    </tr>';            
     }
     $objekt->File();   
}