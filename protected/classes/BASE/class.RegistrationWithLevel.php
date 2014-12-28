<?
	

class RegistrationWithLevel
{
	public $url = '';
	public $breakonerror = false;		
	protected $level;
	protected $errorlist = array('');
	protected $slevel;
	protected $error;
	protected $maxlevel;
	
	public function __construct($maxlevel = 1) {
		Session::Start();
		if(!$maxlevel) throw new Exception('Level is zero / negative !');
		$this->maxlevel = $maxlevel;
		$this->slevel = isset($_SESSION['reg-level']) ? $_SESSION['reg-level'] : 0;	
		$this->L(0);
		
		echo '<form action="', $url, '" method="post" class="tb-sign-up-form spin-form" id="tb-sign-up-form">
				<ul id="signup-ul">';
	}
	public function __descruct() {
		echo '</ul></form>';	
	}
	public function L($l) {
		$this->level = $l;
		return $this;
	}
	public function Post() {
		// Poslal nieco ?
		$name = 'reg-lvl-'.$this->slevel;
		if(!isset($_POST[$name])) return false;
		if($this->CallPostFunctions()) {
			$this->NextLevel();
			if($this->slevel == $this->maxlevel) $this->LastLevel();	
		}
	}
	protected function CallPostFunctions() {
		$error = true;
		// Poslal takze prejdi prvky...		
		for($i=0; $i < $max; $i++) {
			$name = 'reg-'.$this->slevel.'-'.$i;
			$data = (!isset($_POST[$name])) ? NULL : $_POST[$name];
			$function = 'Post_'.$this->slevel.'_'.$i;
			$this->errorlist[$i] = '';
			
			// Ak post funkcia existuje, inak hodnotu ponechaj
			if(method_exists($this, $function)) {
				// Tu zalezi ze ci chceme pri prvom errore skoncit
				// alebo vypiseme vsetke chyby :);
				$vysledok = $this->$function($data);		
				if(!($vysledok == FALSE)) {
					$error = false;
					$this->errorlist[$i] = $vysledok;
					if($this>breakonerror) break;
				} 
			}
			$_POST[$name] = $data;
		}
		return $error;	
	}
	protected function NextLevel() {
		// Dalsi level
		$this->SendAfterLevel($this->slevel, $_POST);
		$_SESSION['reg-data-'.$this->slevel] = serialize($_POST);
		$this->slevel++;
		$_SESSION['reg-level'] = $this->slevel;
	}	
	protected function LastLevel() {
		// Posledny level bol vykonanay ...
		if($this->slevel == $this->maxlevel) {
			$data = BuildPostdata();
			$this->Send($data);
		}	
	}
	protected function Send($data) {
		return false;	
	}
	protected function BuildPostdata() {
		$data = array();
		for($i=0; $i < $this->maxlevel; $i++) {
			$name = 'reg-data-'.$i;
			$data[] = unserialize($_SESSION[$name]);
			unset($_SESSION[$name]);	
		}
		return $data;
	}
	protected function InThisLevel() {
		return ($this->level == $this->slevel);
	}	
	public function Form($nazov, $popis)
	{
		if(!$this->InThisLevel()) return;
		static $i = -1;
		$i++; // ID aby to pekne spolupracovalo s javascriptom, mozme pouzit kolko raz chceme :)
	    $name = 'reg-'.$this->level.'-'.$i;
	    $value = isset($_POST[$name]) ? DB::Vystup($_POST[$name]) : '';
	    
		echo '
		<li>
			<span class="hint" style="display: none;">', $popis, '<span class="hint-pointer">&nbsp;</span></span>
			<label for="', $name, '">', $nazov, '</label>							
			<input type="text" onfocus="register(this);" value="', $value, '" name="', $name, '" class="inputtext" />';
			if($this->errorlist[$i]) {
				echo '<div class="error-inline">', $this->errorlist[$i], '</div>';
			}	
		echo '
		</li>';
	}
	public function Submit() {
		echo '
		<li class="signup-btn-wrap">
			<table width="420" border="0" cellspacing="1" cellpadding="0">
				<tr>';
					for($i=0; $i < $this->maxlevel; $i++)
					{
						if($this->slevel== $i) {
							echo '
							<td align="center">
								<button id="submit-tb-sign-up-form" type="submit" class="btn-signup active" name="reg-lvl-', $this->slevel, '"></button>	
							</td>';	
							continue;
						}
						echo '
						<td align="center">
							<div id="submit-tb-sign-up-form" class="btn-signup deactive"></div>	
						</td>';
					}
			echo '		
				</tr>
			</table>
		</li>';
	}
	protected function SendAfterLevel($level, $post) {
		// Po kazdom leveli ak chces neico spravit
	}
}