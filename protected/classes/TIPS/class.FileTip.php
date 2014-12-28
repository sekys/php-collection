<?

class FileTip extends Tip
{
	const ZLOZKA = '/home/cstrike/protected/tips/';
	
	protected function Get() {
		$name = self::Name();
		if(!file_exists($name)) {
			return -1;	
		} else {
			$f = fopen($name, "r");
			$x = fread($f, 2);
			fclose($f);
			return $x;
		}
	}
	protected function Update($i) {
		$f = fopen(self::Name(), "w");
		fwrite($f, $i);
		fclose($f);		
	}
	protected function Add() {
		$f = fopen(self::Name(), "w");
		fwrite($f, "0");
		fclose($f);	
	}
	protected function Name() {
		return self::ZLOZKA.'/'.self::$user.'/'.$this->ID();	
	}
	protected function Delete() {
		return @unlink(self::Name());
	}
}