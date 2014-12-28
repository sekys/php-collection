<?

class Tip
{
	// Plna podpora AJAX, seo ak treba tak normalne spravu a nie TIP !
	protected static $user = 0; // systemove - alebo id uzivatela	
	public static function StaticInit() {
		self::$user = User::ID();
	}
		
	// Na tomto mieste ma skontrolovat polozku ID - 17 - alebo nazov ?
	// $callkontrola ak je falne je to len informativny tip, ktory sa mzoe len schovat
	public function Render() {
		if($this->SystemCheck()) {
			$this->Draw($this->Msg());
			return true;	
		}
		return false;
	}
	protected function SystemCheck() {
		// Ziskaj udaje
		$status = $this->Get();
		switch($status) {
			// case 2: // Bolo vykonane
			// case 1: { // Skryl
			//	return false;
			case 0: { // Stale ukazuje	
				return true;
			}
			case -1: { // Nenastala situacia
				if($this->Test()) {
					// Pridaj do zoznamu uloh
					$this->Add();
					return true;
				}
			}
		}
		return false;
	}

	// Klikol na Skryt 
	public function Hide() {
		$this->Update(1);
	}
	public function Success() {
		// Spravil to
		$this->Update(2);	
		$this->Accept();
	}
	/*public function File($name, $max=3) {
		$tips = array();
		require_once S_PUBLIC.'web2/tips/'.$name.'php';
		$pocet = 0;
		foreach($tips as $tip) {
			$x = self::Action($tip[0], $tip[1], $tip[2]);	
			if($x) {
				$pocet++;
				if($pocet == $max) break;	
			}
		}
		$this = new FbTip2; 
	}*/
	
	// Pomocne
	// Hento extendovat ak  chces zmenit typ vystupu
	protected function Draw() {
		echo Mess::Tip(
			'<a class="tipaction" id="'.$this->ID().'">'.$this->Msg().'</a>'
			, 'close tiphide'
		);	
	}
	
	
	
	
	// Toto  extendovat a mozme zmenit typ a bude upravovat cookie
	protected function Get() {
		return DB::One("SELECT `status` FROM `cstrike`.`tips` WHERE `id`='".$this->ID()."' AND `user`='".self::$user."'");	
	}
	protected function Update($i) {
		DB::Query("UPDATE `cstrike`.`tips` SET `status`='".$i."' WHERE `id`='".$this->ID()."' AND `user`='".self::$user."'");	
	}
	public function Add() {
		DB::Query("INSERT INTO `cstrike`.`tips` (`id`, `user`, `status`) VALUES ('".$this->ID()."', '".self::$user."', '0')");	
	}
	public function Delete() {
		DB::Query("DELETE FROM `cstrike`.`tips` WHERE `id`='".$this->ID()."' AND `user`='".self::$user."'");
	}
	protected function Msg() {
		return '';
	}
	protected function ID() {
		return get_class($this);
	}
	protected function Test() {
		return true;	
	}
	protected function Accept() {
		
	}
}