<?
require_once($_SERVER["DOCUMENT_ROOT"].'/globals.php');  
Ajax::Start();
if(!Input::Nums('mid')) Ajax::cExit();
if(!User::Logged()) exit;

// Chce len skryt,...
if(isset($GET['hide'])) {
	$mid = DB::Vstup($mid);
	Tip::Hide($mid);
	exit;
}

// Chce to vykonat
// Ako zistim nazov funkcie ?

switch($mid)
{
	case 0: { // alebo to dat ako text ?
		// Je to Fb download friends
		//Tips::Success($mid);
		$friends = FB::Get('friends');
		if(!$friends) exit; // exit aby nenapisal ospravu
		$friendsid = array();
		print_r($friends);
		/*foreach($friends as $f) {
			$friendsid[] = $f[];	
		}
		$ftxt = implode(',', $friendsid);
		$sql = DB::Query('SELECT user_id FROM `cstrike`.`fusion_users` WHERE `fbid` IN ('.$ftxt.')');
		// Vykonaj
		while($data = $sql->fetch_row()) {
			DB::Query("INSERT INTO `priatelia` ON UPDATE (`id`, `priatel`) VALUES ('".User::$m->user_id."', '".$data[0]."')");	
		}
		// Posli novy javascript ak treba*/
		echo 'Priatelia stiahnuty,...';
		break;	
	}
	default {
		echo Mess::Error('Tips: ID nenajdene');
		exit;		
	}
}