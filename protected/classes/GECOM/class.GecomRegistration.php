<?

class GecomRegistration extends RegistrationWithLevel 
{
	// Ked chces poslat po vsetkych leveloch
	protected function Send($post) {
		$l = $post[0];
		reg_sendmail($l[0], $l[1], $l[2]);
		//reg_send($l[1], $l[2], $l[0], 1);
		
		//  Vsetko je OK .
		echo '
		<li>
			<div align="center">
				<p>Blaho&#382;el&aacute;m registr&aacute;cia bola &uacute;spe&scaron;n&aacute;.</p>
				<p>Poslali sme V&aacute;m mail v ktorom je aktiva&#269;n&yacute; link.</p>
 				<p>Kliknut&iacute;m na tento link dokon&#269;&iacute;te registr&aacute;ciu. </p>
			</div>
		</li>';
		// Prihlasit
		//User::SetLogin($id, $password);
		//Engine::Presmeruj($$this->presmeruj);
		echo '</div></li>';
	}
	public function Text() {
		echo '<li class="terms-wrap">
			Registr&aacute;ciou s&uacute;hlas&iacute;&scaron; s na&scaron;imy <a target="_blank" href="'.ROOT.'pravidla/">pravidlamy</a>.
		</li>';	
	}
	// Uprava a kontrola levelu 0 a casti 1
	protected function Post_0_0(&$data) 
	{
		// Nezadal
		if(!$data) return 'Nezadal si svoj E-mail.';	
		$data = stripinput(trim(eregi_replace(" +", "", $data)));
		
		$email_domain = substr(strrchr($data, "@"), 1);
		// Overime ci este neexistuje / nieje bloknute ...
		$result = DB::One("SELECT COUNT(*) as pocet FROM fusion_blacklist WHERE blacklist_email='".DB::Vstup($data)."' OR blacklist_email='".DB::Vstup($email_domain)."'");
		if($result) return 'Tento e-mail je zabanovan&yacute;.';
		
		$result = DB::One("SELECT COUNT(user_id) as pocet FROM fusion_users WHERE user_email='".DB::Vstup($data)."'");
		if($result) return 'Tento email u&#382; niekto m&aacute;...';
		
		// Nesedi ...
		if(!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $data)) {
			return 'Zadal si nespr&aacute;vny email.';
		}

		return false; // bezchyby	
	}
	protected function Post_0_1(&$data) 
	{
		if(!$data) return 'Nezadal si svoje prihlasovacie meno.';
		$data = stripinput(trim(eregi_replace(" +", " ", $data)));
		if(!preg_match("/^[-0-9A-Z_@\s]+$/i", $data)) {
			return 'Zadal si nespr&aacute;vne meno.';;
		}
		// Dlzka ....
		if(!(strlen($data) > 4)) {
			return 'Meno mus&iacute; ma&#357; aspo&#328; 4 znakov.';
		}
		if(STR::UserMenoExist($data)) { // TODO: FUnkcia asi neexistuje
			return 'Toto meno u&#382; niekto m&aacute;.';
		}
		return FALSE;
	}
	protected function Post_0_2(&$data) {
	
		if(!isset($data)) {
			$errorlist[2] = 'Nezadal si svoje heslo.';
			return false;
		}	
		$data = stripinput(trim(eregi_replace(" +", "", $data)));
		
		if(!preg_match("/^[0-9A-Z@]{6,20}$/i", $data)) {
			$errorlist[2] = 'Zadal si nespr&aacute;vne heslo.';
			return false;
		}
		if(strlen($data) < 6) {
			$errorlist[2] = 'Heslo mus&iacute; ma&#357; aspo&#328; 6 znakov.';
			return false;
		}
		$data = User::Createpass($data);	
	}
}
