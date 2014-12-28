<?
require_once('stavba.php');
				
function profil_header() {
	global $p;
	echo '	
	<div class="stena clearfix">
		<div class="hd"> </div>
		<div class="bd">
			<div id="activity-friends-wrapper">
				<ul class="activity-feed" id="stena" style="padding-left:0;">';					
					
					// Novy vypocet ... 
					$time = ProfilLogs::MaxPocet(10, time(), $p->user_id, $p->Link());
					Header::Js('
						ProfilID = '.$p->user_id.';
						PLogsTime = '.$time.';'
					,1);

	echo '		</ul>
			</div>	
			<a href="javascript:stena_next();" id="stena_next"><span class="ui-icon ui-icon-circle-arrow-s"> </span> &#270;alej </a>
		</div>
		<div class="ft"/>
	</div>
	<br /><br />';
	profil_footer();	
}
function profil_footer() {

}
function profil_sidebar() {
	echo '
	<div id="galeria_sidebar" class="ui-widget ui-helper-clearfix">
		<div id="galeria_sidebar" class="ui-widget-content ui-state-default">	';
	profil_poslispravu();
	posledne_hral();
	profil_priatelia();
	echo '</div>
	</div>';
}
function profil_poslispravu() {
	global $p, $userdata;

	// Posielame cez javascript .....
    profil_sparse('Prida&#357; spr&aacute;vu na stenu');
	if(!$userdata['user_id']) {
		profil_login($p->user_name);
		return false;
	}

	// Ak je prihlaseny ...
	Header::Js('stena_autoload();');
    // TODO: Data dat do serialize.
	echo '
	<table width="100%" border="0" cellspacing="10" cellpadding="0">
		<tr>
			<td align="right" valign="middle">Predmet</td>
			<td><input name="stena_predmet" id="stena_predmet" type="text" size="20" maxlength="47"></td>
		</tr>
		<tr>
			<td align="center" colspan="2">
				<textarea name="stena_sprava" id="stena_sprava" cols="34" rows="2"></textarea>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2">
				<input type="submit" name="stena_submit" onclick="stena('.$p->user_id.');" value="Submit" />
			</td>
		</tr>
	</table>';
}
function profil_priatelia() {
	global $p, $userdata;
    profil_sparse('Priatelia');
	priatelia($p->user_id, $userdata['user_id'] ? $userdata['user_id'] : 0);
	// Zoznam priatelov ....
}

function priatelia($id, $priatel, $max=30) {
	$bol[0] = false;
	$bol[1] = false;
	// TODO: Viac tlacidlo nejde a ldajeto nemam dokoncene, treba vytvorit ajax okno.
    $pocet = DB::One("SELECT COUNT(`priatel`) as pocet FROM `cstrike`.`priatelia` WHERE `id`='".$id."'");
						 
	$sql = DB::Query("SELECT spolocny, p.priatel as user_id, user_name, vip, slot, user_avatar FROM `cstrike`.`priatelia` p
							LEFT JOIN ( SELECT priatel as spolocny FROM `cstrike`.`priatelia` WHERE `id`='".$priatel."') s
								on p.priatel = s.spolocny
							JOIN ( SELECT user_id, user_name, vip, slot, user_avatar FROM `cstrike`.`fusion_users` )	u
								on p.priatel = u.user_id
						 WHERE `id`='".$id."'
						 ORDER BY spolocny DESC LIMIT ".$max);
	echo '<table width="100%" id="stena_priatelia" border="0" cellspacing="0" cellpadding="0">';
	$data = new Member;
	while($data->next($sql))
	{
		// Nadpisy
		if($data->spolocny and !$bol[0]) {
			echo '<tr><td colspan="3" width="100%" class="profilNadpis">Spolo&#269;n&yacute; priatelia :</td></tr>';
			$bol[0] = true;
		}
		if(!$data->spolocny and !$bol[1] and $bol[0]) {
			echo '<tr><td colspan="3" width="100%"><br /><br /></td></tr>'; //Ostatn&yacute; priatelia 
			$bol[1] = true;
		}	
		$data->MiniItem();			
	}
	echo '
	</table>';
	if($pocet > $max) {
		$pocet -= $max;
		profil_viac('friends', ' a '.$pocet.' &#271;al&scaron;&iacute;ch priate&#318;ov');
	} /*else {
		profil_viac(ROOT.'hrac/Seky/#ui-tabs-21');
	}*/
}

function posledne_hral()  {
	return false;
	// Pomocne konstanty
	global $p;
	// ----- Cache objekt
	$objekt = new Cache('posledne_hral_'.$p->plrid, 180);
	if($objekt->File()) {
	// ----- Cache objekt
		// Header
        profil_sparse('Ned&aacute;vno hral ...');
		$max = DB::One("SELECT MAX(`sessionend` - `sessionstart`) as `pocet` FROM `psychostats`.`ps_plr_sessions` WHERE `plrid` = '".$p->plrid."'");			
		if(!$max) { 
			echo '<br />'; 
			return false; 
		}
		
		$sql = DB::Query2("
			SELECT `sessionend` - `sessionstart` as `cas`, `mapa`, `s`.`mapid` FROM `psychostats`.`ps_plr_sessions` s 
				JOIN ( SELECT `mapid`, `uniqueid` as `mapa` FROM `psychostats`.`ps_map` LIMIT 0 , 30  ) m
					on `s`.`mapid` = `m`.`mapid`
			WHERE `plrid` = '".$p->plrid."' ORDER BY `sessionstart` DESC
			LIMIT 5");
			
		echo '
		<div id="posledne_hrali">
			<ul class="profile-games-list" style="padding-left:0;">';

		// Vypisujeme
		while($data = $sql->fetch_assoc())
		{			
			/*
				$max     100%
				$data ....  x%
			*/
			$x = ($data['cas'] * 100 ) / $max;
			echo '
				<li>
					<a href="/psychostats/map.php?id='.$data['mapid'].'" class="icon clearfix">
						<img align="absmiddle" border="0" width="24" height="24" alt="'.$data['mapa'].'" src="'.mapa($data['mapa']).'"/>
						<span class="v-center-wrapper">
							<span class="v-center-middle">
								<span class="v-center-object">'.$data['mapa'].'</span>
							</span>
						</span>
					</a>';
					bar($x, Time::Rozdiel($data['cas']));			
			echo '</li>';
		}
		
		// Footer
		echo '</ul>
		</div>';
		profil_viac('lastgame');
	// ----- Cache objekt
	}
	$objekt->File();
	// ----- Cache objekt
}

/*function posledne_hral_server()  {
	// Pomocne konstanty
	global $p;
	require_once('/home/cstrike/www/web2/web2/lib/server.php');
	
	// Header
	echo '<h4 class="ui-widget-header header_fix header_fix2"> Ned&aacute;vno hral ...  </h4>';
	$max = DB::One("SELECT SUM(onlinetime) as pocet FROM `psychostats`.`ps_plr_data` WHERE `plrid`= '".$p->plrid']."'");			
	if(!$max) {
		echo '<br />';
		return false;
	}
	
	$sql = DB::Query("
		SELECT port, nazov, headadmin, user_name FROM `cstrike`.`servers` s
				LEFT JOIN ( SELECT user_name, user_id FROM `cstrike`.`fusion_users` ) u ON s.headadmin = u.user_id
		");
		
	echo '
	<div id="posledne_hrali">
		<ul class="profile-games-list" style="padding-left:0;">';

	// Vypisujeme
	while($data = $sql->fetch_assoc()) {			
		//	$max     100%
		//	$data ....  x%
		//$x = ($data * 100 ) / $max;
		echo '
			<li>
				<a href="'.ROOT.'server/'.$data['port'].'/" class="icon clearfix">
					<img align="absmiddle" border="0" width="24" height="24" alt="'.$data['nazov'].'" src="'.ROOT.'web2/images/'.($data['port'] == 27020 ? "27020" : "27015" ).'.gif"/>
					<span class="v-center-wrapper">
						<span class="v-center-middle">
							<span class="v-center-object">'.$data['nazov'].'</span>
						</span>
					</span>
				</a>';
				bar($x, Time::$TIME_rozdiel($rozdiel));			
		echo '</li>';
	}
	
	// Footer
	echo '</ul>
	</div>';
	unset($data);
	profil_viac('lastgameserver');
} */
?>