<?php
 
class Captcha 
{
	public static $CESTA;
	const NO = -1;
	const BAD = 0;
	const OK = 1;
	
	public $level;
	private $secretkey;
	private $post = array();
	
	public static function StaticInit() {
		self::$CESTA = AJAX.'captcha.php';
		Session::Start(); // Sessiony musia byt povolene ....
	}
	public function Captcha($_secretkey = 'captcha') {
		$this->secretkey = $_secretkey;
		$this->level = self::NO;
	}
	public function PostSave($call, $name = 'Submit') {
		if(isset($_POST[$name])) {                
            $_SESSION['captcha_'.$name] = serialize($_POST);                    
        }	
        $this->post[0] = $name;
        $this->post[1] = $call;
	}
	public function Debug() {
		$this->Check2();
		echo "Level ".$this->level;
		if(isset($_SESSION[$this->secretkey])) echo $_SESSION[$this->secretkey];	
	}
	public static function Img() {
		echo '
		<a href="#" onclick="CaptchaReload(this,\''.self::$CESTA.'\');" id="captcha-change">	
			<img src="'. self::$CESTA . Functions::uriRID() .'" class="captcha-screen" />
			<span class="catcha-text">Ne&#269;&iacute;tate&#318;n&eacute; ? Zme&#328; text.</span>
		</a>';
	}
	public function Check($callback_error = 'captcha_default') { 
		// Parameter upravime
		return $this->level = SystemCheck($callback_error);
	}
	private function SystemCheck($callback_error) { 
		// Neodoslal 
		if (empty($_REQUEST[$this->secretkey])) return self::NO;
		// Odoslal  ale je to chybne ...
		if (empty($_SESSION[$this->secretkey]) || trim(strtolower($_REQUEST[$this->secretkey])) != $_SESSION[$this->secretkey]) {
			if($callback_error) call_user_func($callback_error); // chybne zadal
			unset($_SESSION[$this->secretkey]);
			return self::BAD;
		}
		// Vsetko ok :)
		if($this->post[0]) {
			$name = 'captcha_'.$this->post[0];
			if(isset($_SESSION[$name]) {
				call_user_func($this->post[1], unserialize($_SESSION[$name])); 
				unset($_SESSION[$name]);
			}
		}	
		unset($_SESSION[$this->secretkey]);
		return self::OK;
	}
	/*
	public static function Test() {
		$a = new Captcha();
		$a->$ajax = true;
		if( $a->Check() ) {
			echo "A";
		}

		//	ALEBO
			
		$b = new Captcha();
		if( $b->Check("captcha_default") ) { // funckai co sa spusti ak zle
			echo "B";
		}

		//	ALEBO
			
		$c = new Captcha();
		$c->Check();
		switch($c->$level) {
			case Captcha::NO: echo "C -1";
			case Captcha::BAD: echo "C 0";
			case Captcha::OK: echo "C 1";
		}
	}
	*/
	
	// Formulare
	
	public function DefaultFormular($txt = '') {
		echo '
		<div align=""center>', $txt, '
			<form method="GET">';
			self::Img();
			echo '
				<br/><br/>
				<input type="text" name="captcha" id="captcha" /><br/>
				<input type="submit" />
			</form>
		</dvi>';
	}
	public function AjaxFormular($txt = '') {
		echo $txt;
		echo '<div align="center">';
		self::Img();
		echo '
			<br/><br/>
			<input type="text" name="captcha" id="captcha" /><br/>
		</div>';
	}
	public function WindowAjax() {
	    switch($c->Check()) {
	        case Captcha::NO: {
	            $c->AjaxFormular();
	            break;
	        }
	        case Captcha::BAD: {
	            $txt = 'Nespr&aacute;vne op&iacute;san&yacute; k&oacute;d.';
	            $c->AjaxFormular($txt);
	            break;
	        }
	        case Captcha::OK: {
	            echo Mess::Tip('K&oacute;d bol op&iacute;san&yacute; sp&aacute;vne, akcia bola vykonan&aacute;.');
	            return true;
	            break;
	        }
   		}
   		return false;	
	}
}
function captcha_default() {
	echo 'Nespr&aacute;vne op&iacute;san&yacute; k&oacute;d.';
}

