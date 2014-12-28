<?php
	

class FBDownloadFriendsTip extends Tip {
	public function __construct() {
		$this->Draw($this->Msg());
	}
	protected function Msg() {
		return 'Registroval si sa cez facebook, chces mat zoznam priatelov aj tu ?';
	}	
	protected function Test() {
		if(User::Logged() != 3) return false;	
		return true;
	}
	protected function Accept() {
		if(!FB::GetCookie()) return;
		$friends = FB::Get('friends');
		$friends = $friends['data'];
		if(!count($friends)) return;
		$f = '';
		foreach($friends as $friend) $f .= $friend['id'].', ';
		$f = substr($f, 0, -2); 
		$id = User::ID();
		$sql = DB::Query("
			SELECT `user_id`, `priatel` FROM `cstrike`.`fusion_users` `u`
				LEFT JOIN ( SELECT `priatel` FROM `cstrike`.`priatelia` WHERE `id`='".$id."' LIMIT 1 ) `p`
					on `u`.`user_id` = `p`.`priatel`
			WHERE `fbid` IN (".$f.")
		");
		while( $data = $sql->fetch_row()) {
			if($data[1]) continue; // uz ho ma pridaneho,...
			DB::Query("INSERT INTO `cstrike`.`priatelia` (`id`, `priatel`) VALUES ('".$id."', '".$data[0]."')");
		}		
	}
}