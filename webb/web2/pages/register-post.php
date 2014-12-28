<?	
function reg_referal($id, $referal)
{
	if(!$referal) return false;
	$ref = GInput::Meno2SQL($referal, false);
	if(!$ref) return false;

	// REF je ID alebo MENo ? uz neviem
	$shopitem = 1;
	WebLog::Add(2, 1, $id, $ref);
	$money = Shop::Kup($ref, $shopitem, $id);
	$meno = DB::One("SELECT user_name FROM fusion_users WHERE user_id='".$ref."'");
	
	// Radsej posli spravu propagandovy
	$link = PAGE.ROOT.'hrac/'.$referal.'/';
	Web2::Posta($ref, $id, 
		"Ahoj,
		Registroval som sa ako tvoj refferal, za co si ziskal ".$money." korun.\n
		Zbieraj viac refferalov a ziskaj tak viac korun !
		Staci len propagovat link na tvoj profil...
		".$link."
	");
	// A sprava referalovy
	$link = PAGE.ROOT.'hrac/'.$meno.'/';
	Web2::Posta($id, $ref, 
		"Ahoj,
		Registroval si sa ako ".$referal." refferal, za co ziskal ".$money." korun.\n
		Zbieraj aj ty refferalov a ziskaj tak viac korun !
		Staci len propagovat link na tvoj profil...
		".$link."
	");
	return true;
}
function reg_fb()
{
	$post = FB::RegisterPost();
	if($post === FALSE) return false;
	/* Ak uz je normalne prihlaseny ale chce ist cez FB, 
		nastav len UPDATE
		- Ale ked je regnuty tak sa sem nedostane,...
		
	function reg_loggedfb($id) {
		global $userdata;	
		if($userdata['fbid']) return false; // uz ma fb id
		DB::Query("UPDATE ".DB_PREFIX."users SET `fbid`='".$id."' WHERE `user_id`='".$userdata['user_id']."'");
		reg_updatefbid($id, $userdata['user_id']);
		return true;
	}
	function reg_updatefbid($id, $user) {
		DB::Query("UPDATE ".DB_PREFIX."users SET `fbid`='".$id."' WHERE `user_id`='".$user."'");
	}
	*/

	$id = $post['user_id'];
	$reg = $post['registration'];
	if(strlen($reg['nickname']) > 3) {
		$reg['name'] = trim($reg['nickname']);
		$username = DB::Vstup($username);
	} else {
		$username = $reg['name'];
	}
	$user_web = 'http://www.facebook.com/profile.php?id='.$id;
	$user_avatar = FB::Avatar($id);
	$user_gender = ($reg['gender'] == "male") ? 0 : 1;
	$password = User::Createpass('gecom-fb-1234-'.$id);
	
	$vysledok = reg_check($username, $reg['email']);
	if(!($vysledok === FALSE)) {
		echo '<div><strong>', $vysledok, '<strong></div>';
		return false;
	}
	$loc = isset($reg['location']) ? $reg['location']['name'] : '';
	$birthday = isset($reg['birthday']) ? $reg['birthday'] : '0000-00-00';
	
	// Poposielaj
	$id = reg_send(
		$username, 
		$password, 
		$reg['email'],
		0,
			$loc,
			$birthday, 
			$user_web, 
			$user_avatar,
			$user_gender
		);
	
	reg_referal($id, Referral::Get());
	User::SetLogin($id, $password);
	Engine::GoHome();
	return true;
}
function reg_check($meno, $email) {
	$email_domain = substr(strrchr($email, "@"), 1);
	// Overime ci este neexistuje / nieje bloknute ...
	$result = DB::One("SELECT COUNT(*) as pocet FROM fusion_blacklist WHERE blacklist_email='".$email."' OR blacklist_email='".$email_domain."'");
	if($result) return 'Tento e-mail je zabanovan&yacute;.';
	
	$result = DB::One("SELECT COUNT(user_id) as pocet FROM fusion_users WHERE user_email='".$email."'");
	if($result) return 'Tento email u&#382; niekto m&aacute;...';
	
	if(STR::UserMenoExist($meno)) return 'Toto meno u&#382; niekto m&aacute;.';
	return false;	
}
function reg_send(
	$username,	
	$password, 
	$email,
	$activation,
	
	// Menej dolezite
	$user_location = '',
	$user_birthdate = '0000-00-00',
	$user_web = '',
	$user_avatar = '',
	$user_gender = 0
) {	
	// Tuby mala byt tabulka fusioun_new_user pre normlnu registraciu
	@DB::Query("INSERT INTO ".DB_PREFIX."users 
		( user_name, user_password, user_email, user_hide_email, user_location, user_birthdate, user_gender,
		user_aim, user_icq, user_msn, user_yahoo, user_web, user_theme, user_offset, user_avatar, 
		user_sig, user_posts, user_joined, user_lastvisit, user_ip, user_rights, user_groups, 
		user_level, user_status) 
		VALUES
		('".$username."', '".$password."', '".$email."', '0', '$user_location', '$user_birthdate', '$user_gender', 
		'', '', '', '', '$user_web', 'Default', '0', '$user_avatar', 
		'', '0', '".time()."', '0', '".USER_IP."', '', '', '101', '$activation')");

}	
function reg_getmail() {
	if(!isset($_GET['activate'])) return false;
	$activate = $_GET['activate'];
	if(!preg_check("/^[0-9a-z]{32}$/", $activate)) return false;
	$result = dbquery("SELECT * FROM `cstrike`.`fusion_new_users` WHERE `user_code`='".DB::Vstup($activate)."'");
	if(!dbrows($result)) Engine::GoHome();	
	$data = dbarray($result);
	$user_info = unserialize($data['user_info']);

	reg_send(
		$user_info['user_name'],	
		$user_info['user_password'], 
		$user_info['user_email'],
		0
	);
	$id = DB::ID();
	dbquery("DELETE FROM `cstrike`.`fusion_new_users` WHERE `user_code`='".DB::Vstup($activate)."'");	
	
	// Az teraz poposielaj
	reg_referal($id, $user_info['referal']);
	User::SetLogin($id, $user_info['user_password']);
	Engine::GoHome();
	return true;
}	
function reg_sendmail($email, $username, $password) {
	global $settings;
	mt_srand((double)microtime()*1000000); 
	$user_code = md5(md5($email).'some-secret-key');
	$activation_url = PAGE.ROOT."registrovat/?activate=".$user_code;
	require_once INCLUDES."sendmail_include.php";
	
	// Email Message
	$locale['449'] = "Vítame Vás ".$settings['sitename'];
	$locale['450'] = "Dobrý deň ".$username.",\n
	Vitajte na ".$settings['sitename'].". Toto sú vaše prihlasovacie údaje:\n
	Meno: ".$username."
	Heslo: ".$password."\n
	Prosím aktivujte svoje konto pomocou nasledujúceho odkazu:\n";

	if( sendemail(
			$username, 
			$email, 
			$settings['siteusername'], 
			$settings['siteemail'], 
			$locale['449'], 
			$locale['450'].$activation_url
		)
	) {
		reg_newsend($user_code, $email, $username, $password, $referal);
	} else {
		$locale['457'] = "Odoslanie e-mailu zlyhalo. Skúste neskôr a ak problém pretrváva kontaktujte <a href='mailto:".$settings['siteemail']."'>administrátora</a>.";
		echo Mess::Error($locale['457']);
	}
}
function reg_newsend(
	$user_code,
	$email, 
	$username,
	$password,
	$referal = 0
) {	
	$user_info = serialize(array(
		"user_name" => $username,
		"user_password" => $password,
		"user_email" => $email,
		"referal" => Referral::Get()
	));
	DB::Query("INSERT INTO `cstrike`.`fusion_new_users` (user_code, user_email, user_datestamp, user_info) VALUES('$user_code', '".$email."', '".time()."', '$user_info')");
}