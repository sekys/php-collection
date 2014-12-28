<?

// Tieto tipy budu ulozene kym sa neodhlasi...
class SessionTip extends Tip
{
	public function StaticInit() {
		Session::Start();
	}
	protected function Get() {
		// user nemusime kontrolovat - kedze to stale funguje len na nasho
		$id = $this->ID();
		if(!isset($_SESSION['tips'][$id])) {
			return -1;	
		} else {
			return $_SESSION['tips'][$id];
		}
	}
	protected function Update($i) {
		$_SESSION['tips'][$this->ID()] = $i;	
	}
	protected function Add() {
		$_SESSION['tips'][$this->ID()] = 0;	
	}
	protected function Delete() {
		unset($_SESSION['tips'][$this->ID()]);
	}
}