<?php
require_once "maincore.php";
require_once "subheader.php";

// Ak je prihlaseny ....
if(User::Logged()) Engine::GoHome();

// Registracia ....
Debug::Oblast('REGISTER');
require SPAGES."register-post.php";
reg_getmail();
Referral::PreSet('refferal');
Header::Title('Registr&aacute;cia');
Resource::All('register');
$locale['charset'] = 'UTF-8';

// Uvod
echo '
<td valign="top" class="main-bg" align="left" valign="top">	
<div id="signup">				
	<div class="clearfix" id="content">				
		<div class="pod-white rev2">			
			<h2 id="signup-header-copy">Registruj sa, len 3 kroky !</h2>		
			<div class="form-wrap">';	
			
		// FB cast
		reg_fb();
		
		$zoznam = array(	
			array('name' => 'name'), 
			array('name' => 'birthday'), 
			array('name' => 'gender'), 
			array('name' => 'location'), 
			array('name' => 'email'),
			array(	'name' => 'nickname', 
					'description' => 'Nickname', 
					'type' =>'text'
			)
		);		
		FB::Register(PAGE.ROOT.'registrovat/', $zoznam, 600); 
				
		// Nasa cast...
		$r = new GecomRegistration();
		$r->Post();
		// $r->L(0)->Form
		$r->L(0)->Form('Email adresa', 'Vitaj na GeCom::Lekos.<br />Na za&#269;iatok zadaj svoj email.');
		$r->Form('Username', 'Zadaj svoje meno / prez&yacute;vku.<br/>Meno mus&iacute; ma&#357; aspo&#328; 4 znaky.<br />Povolen&eacute; znaky: 0-9, Aa-Zz.');
		$r->Form('Password', 'Va&scaron;e heslo by malo by&#357; &#269;o najdlh&scaron;ie.Minim&aacute;lne v&scaron;ak 6 znakov.');
		$r->Text();
		$r->Submit();
		unset($r);
		echo '</div>
		</div>
	</div>
</div>
';

Debug::Oblast('REGISTER');
require_once "footer.php";