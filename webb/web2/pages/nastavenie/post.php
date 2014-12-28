<?php

function NastaveniaPost() {
	// Vseobecne
	global $userdata;
	if(isset($_POST['submit'])) {
		$mysql = "`user_id`=`user_id`";
		// Steam ucet
		if(isset($_POST['cs_steam'])) {
			// Zmeni meno
			$cs_meno = DB::Vstup(trim($_POST['cs_steam']));
			$sql = DB::Query("SELECT user_id FROM `cstrike`.`fusion_users` WHERE user_id != '".$userdata['user_id']."' AND cs_steam LIKE '".$cs_meno."'");
			$cs_meno = ($sql->num_rows) ? '`cs_steam`' : "'".$cs_meno."'";	
			$mysql .= ", `cs_steam`='".$cs_meno."'";			
		} else {
			$mysql .= ", `cs_steam`=`cs_steam`";
		}		
		
		// Herne meno
		if(isset($_POST['cs_meno'])) {			
			$cs_meno = DB::Vstup(trim($_POST['cs_meno']));
			$mysql .= ", `cs_meno`='".$cs_meno."'";		
		} else {
			$mysql .= ", `cs_meno`=`cs_meno`";
		}
		
		// Ostatne
		$mysql .= ($_POST['liga_tema']) ? ", liga_tema = '1'" : ", liga_tema = '0'";
		$mysql .= ($_POST['liga_zapasy']) ? ", liga_zapasy = '1'" : ", liga_zapasy = '0'";
		$mysql .= ($_POST['liga_zrusene']) ? ", liga_zrusene = '1'" : ", liga_zrusene = '0'";
		$mysql .= ($_POST['liga_pozvanky']) ? ", liga_pozvanky = '1'" : ", liga_pozvanky = '0'";
		$mysql .= ($_POST['liga_hraci']) ? ", liga_hraci= '1'" : ", liga_hraci = '0'";

		DB::Query("UPDATE `cstrike`.`fusion_users` SET ".$mysql." WHERE user_id = '".$userdata['user_id']."'");
		WebLog::Add(0, 22, $userdata['user_id']);
		unset($mysql);
	}

	// Odhlasi sa od clanu	
	if(isset($_POST['clan'])) {
		Liga_OdhlasitZclanu($userdata['user_id'], $userdata['clan_id'], $userdata['clan_hodnost']); 
	}
			
	// Moduly
	if(isset($_POST['liga']))	{
		if($userdata['liga']) {
			// Zapina modul
			if($userdata['cs_steam']) {
				DB::Query("UPDATE `cstrike`.`fusion_users` SET `liga` = '1' WHERE user_id = '".$userdata['user_id']."'");
				WebLog::Add(0, 22, $userdata['user_id']);
			} else {
				echo Mess::Alert('Ligov&yacute; modul ' , 'Najprv mus&iacute;&scaron; vyplni&#357; Steam &#269;&iacute;slo');
			}
		} else {
			// Vypina
			WebLog::Add(0, 22, $userdata['user_id']);
			DB::Query("UPDATE `cstrike`.`fusion_users` SET `liga` = '0' WHERE user_id = '".$userdata['user_id']."'");
		}
	}		
	if(isset($_POST['amx']))	{
		if($userdata['amx']) {
			// Zapina modul
			if($userdata['cs_steam']) {
				DB::Query("UPDATE `cstrike`.`fusion_users` SET `amx` = '1' WHERE user_id = '".$userdata['user_id']."'");
				WebLog::Add(0, 22, $userdata['user_id']);
			} else {
				echo Mess::Alert('Hern&yacute; modul ' , 'Najprv mus&iacute;&scaron; vyplni&#357; Steam &#269;&iacute;slo');
			}
		} else {
			// Vypina
			WebLog::Add(0, 22, $userdata['user_id']);
			DB::Query("UPDATE `cstrike`.`fusion_users` SET `amx` = '0' WHERE user_id = '".$userdata['user_id']."'");
		}
	}	
}	
	
function Liga_OdhlasitZclanu($user, $clan, $hodnost) {
	$__cesta = S_PUBLIC.'cup/lib/';
	require_once($__cesta."class.SLSHodnost.php");
	require_once($__cesta."class.SLSClan.php");	
	require_once($__cesta."class.SLS.php");
	unset($__cesta);
	
	SLSClan::OdhlasitZclanu($user, $clan, $hodnost);
}
