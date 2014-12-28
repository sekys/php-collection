<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/globals.php');  
Ajax::Start();
if(!User::Logged()) exit;
Input::issets('widget');

switch($widget)
{
    case 'uvod': {    
		obchod_uvodna();
        break;
    }    
    case 'zmena': {    
		obchod_zmena();
        break;
    }   
     
    case 'vip': {    
		Ajax::Js('obchod(1, 2);');
		echo '
		<img align="absmiddle" border="0" alt="VIP" src="', ROOT, 'web2/images/shop/vip_logo.png" />
		<div align="center" id="obchod-1">&nbsp;</div>';
        break;
    } 
     
    case 'slot': {    
		Ajax::Js('obchod(2, 2);');
		echo '
		<img align="absmiddle" border="0" alt="SLOT" src="', ROOT, 'web2/images/shop/slot_logo.png" />
		<div align="center" id="obchod-2">&nbsp;</div>';
        break;
    } 
      
    case 'admin': {    
		echo '
		<table width="400" align="center" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="100"><img src="', ROOT, 'web2/images/shop/Administrator.png" alt="Administrator" width="128" height="128" hspace="20" vspace="20" /></td>
				<td align="center" valign="middle">Koruny m&ocirc;&#382;e&scaron; vyu&#382;i&#357; aj na<br />k&uacute;pu pr&aacute;v, respekt&iacute;ve na<br /> &#382;iados&#357; o pr&aacute;va</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="center">				
					<a href="', ROOT, 'kandidovat/ziadost/">
						<img align="absmiddle" border="0" src="', ROOT, 'web2/images/tool/plus1.png" alt="Prida&#357;" />
						<b>Nap&iacute;sa&#357; &#382;iados&#357; na admina</b>
					</a>
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
		</table>';
        break;
    }   
    case 'body': {    
		obchod_body();
        break;
    }   
    case 'panacikov': {    
		obchod_panacikov();
        break;
    }     
	case 'logy': {    
		obchod_zaznamy();
        break;
    }
    default : {
		echo Mess::Error();
    }
}
		
function obchod_uvodna() {
	// Konvertovat
	echo '<br>';	
	ShopHeader();
	
	echo '<table cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td valign="top" class="form_left">
				<img src="', ROOT, 'web2/images/shop/obchod_kosik.png" alt="Obchod" width="128" height="128" hspace="20" vspace="20" />
			</td>
			<td class="form_right">
				<div align="center">
					Po v&yacute;po&#269;&iacute;tan&iacute; pros&iacute;m prejdi do zmen&aacute;rni.
					<br/>
					<br/>
					<strong>1 Euro</strong>
					=
					<strong>30 SVK</strong>
					<br/>
					<br/>
					Ko&#318;ko chce&scaron; kor&uacute;n ?
					<br/>
					<input type="text" size="15" class="inputbox" onchange="shopzmena();" id="to_kredit" name="to_kredit" value="30" />
					<br/>
					Potrebuje&scaron; <strong><div id="korun_zmena" class="vriadku"> 1 </div></strong> eur.
					<br/>
					<br/>	
					<br/>
					<a href="javascript:shopzmenit()">Zmeni&#357;</a>
				</div>
			</td>
		</tr>
		<tr>
			<td align="justify" style="padding-top: 20px;" class="info_gray" colspan="2">
				Opa&#269;n&yacute; syst&eacute;m v&yacute;meny  koruny za eura neplat&iacute;, teda nem&ocirc;&#382;e&scaron; meni&#357; kredity nasp&auml;&#357; na eura. Minim&aacute;lna suma na dob&iacute;janie je 1 euro.
				Ak si peniaze u&#382; poslal pros&iacute;m prejdi do zmen&aacute;rni alebo n&aacute;s <a href="javascript:Contact(\'shopcontact\');">kontaktuj </a>.
			</td>
		</tr>
	</table>
</div>';
}
function obchod_zmena() {	
	// Sposb platby
	ShopHeader();
	echo '
	<table cellpadding="2" align="center">
		<tr>
			<td>
				<img alt="Posta" src="', ROOT, 'web2/images/shop/post.png"/>
			</td>
			<td class="wallet_choice_bottom">
				<p>Prvou mo&#382;nos&#357;ou ako si <strong>dobi&#357;  </strong>kredit, je zaslanie pe&#328;az&iacute; pomocou &scaron;eku  na bankov&yacute; &uacute;&#269;et. <a href="javascript:Navod(\'sek\');" >&Uacute;daje &raquo;</a></p>
				<p>Alebo priamo <strong>posla&#357;</strong> peniaze na adresu <a href="javascript:Navod(\'adresa\');">&Uacute;daje &raquo;</a></p>
				<p>Doba vybavenia <strong>1-3</strong> pracovn&eacute; dni. </p>
			</td>
			<td class="wallet_radio">
				<input type="radio" value="posta" name="payment_method" id="payment_method"/>
			</td>
		</tr>						
		<tr>
			<td>
				<img alt="Banka" src="', ROOT, 'web2/images/shop/bank.png" width="128" height="128"/>
			</td>
			<td class="wallet_choice_bottom">
				Bankov&yacute; <strong>prevod</strong> je najlep&scaron;ie rie&scaron;enie a najr&yacute;chlej&scaron;ie .<br />
				<a href="javascript:Navod(\'banka\');">&Uacute;daje &raquo;</a><br />
				Doba vybavenia do <strong>24</strong> hod&iacute;n.
			</td>
			<td class="wallet_radio">
				<input type="radio" checked="checked" value="bp" name="payment_method" id="payment_method" />
			</td>
		</tr>
		<tr>
			<td>
				<img alt="SMS" src="', ROOT, 'web2/images/shop/sms.png"/>
			</td>
			<td style="text-align: center;">
				Dob&iacute;janie pomocou smsky nie je zatial mo&#382;n&eacute;.
			</td>
			<td>
				<input type="radio" value="sms" name="payment_method" disabled="disabled" />
			</td>
		</tr>
		<tr>
			<td class="wallet_accept" colspan="3">
				<br /><input type="submit" value="Submit" name="submit" onclick="shopzmenaren();return false;"/>
			</td>
		</tr>
	</table>';
}
function obchod_body() {
	// Zombie aj deathrun body naraz
	$obchod_cennik = Shop::Get(-1);
	echo '
	<table width="500" align="center" border="0" cellspacing="0" cellpadding="0">
		<tr>		
			<td width="100"><img src="', ROOT, 'web2/images/shop/Calculator.png" alt="Administrator" width="128" height="128" hspace="20" vspace="20" /></td>
			<td align="center" valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr><td>&nbsp;</td></tr>
					<tr><td><strong>Konvertova&#357;</strong> koruny <strong>&lt;=&gt;</strong> body : </td></tr>
					<tr>
						<td><br />
							<div id="tabs_subobchod">
								<ul>			
									<li><a href="#zombie"><span> Zombie </span></a></li>
									<li><a href="#deathrun"><span> DeathRun </span></a></li>
								</ul>';
                                obchod_body_item('Zombie', 'zombieid', array($obchod_cennik[2], $obchod_cennik[3]));
                                obchod_body_item('Deathrun', 'm_dr', array($obchod_cennik[4], $obchod_cennik[5]));
							echo '	
							</div>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
				</table>
			</td>	
		</tr>
	</table>';
	Ajax::Js(' web2_Tabs(\'subobchod\', 190, 160); ');
}
function obchod_body_item($name, $db, $obchod_cennik) 
{
    echo '<table width="100%" id="', $name, '" border="0" cellspacing="0" cellpadding="0">';
    if(!User::$m->$db) {    
        echo '<tr><td><br /><strong>Niesi prihl&aacute;sen&yacute; v <a href="/', $name, '-banka/">', $name, ' banke</a>.</strong><br /></td></tr>';
    } else {
        echo '
        <tr>
            <td colspan="3" align="right"><span "class="form_right">kurz ', $obchod_cennik[0], '<span></td>
        </tr>
        <tr>
            <td valign="top">', $name, ' bodov -&gt;</td>
            <td><input name="', $name, '_body" id="', $name, '_body" value="0" onchange="document.getElementById(\'', $name, '_korun\').value = document.getElementById(\'', $name, '_body\').value / ', $obchod_cennik[0], ';"  type="text" size="3"><br /><input value="10" name="', $name, '_korun" id="', $name, '_korun" type="text" size="3"></td>
            <td valign="bottom">-&gt; kor&uacute;n </td>
        </tr>
        <tr>
            <td colspan="3" align="right"><span "class="form_right">kurz ', $obchod_cennik[1], '<span></td>
        </tr>
        <tr>
            <td valign="top">Kor&uacute;n -&gt;</td>
            <td><input name="', $name, '_body2" id="', $name, '_body2" value="100" onchange="document.getElementById(\'', $name, '_korun2\').value = document.getElementById(\'', $name, '_body2\').value * '.$obchod_cennik[1].';"  type="text" size="3"><br /><input value="10" name="', $name, '_korun2" id="', $name, '_korun2" value="'. $obchod_cennik[3]*100 .'" type="text" size="3"></td>
            <td valign="bottom">-&gt; ', $name, ' bodov </td>
        </tr>
        <tr>
            <td colspan="3" align="center"><br /><input type="Submit" name="Submit" value="Zmeni&#357;" id="Submit" /></td>
        </tr>';
    }
	echo '</table>';
}
function obchod_panacikov() {
	/*	Este som mohol pridat :
		http://www.iconarchive.com/category/avatar/monsters-icons-by-iconblock.html
		http://www.iconarchive.com/category/avatar/monster-icons-by-iconshock.html
	*/
	$celkovy_pocet = 34;
	echo '<div align="center" id="shop_postavicky">';
	for($i=1; $i <= $celkovy_pocet; $i++) {
		echo '<img align="absmiddle" onclick="postava(', $i, ');" width="96" height="96" border="0" alt="" src="', Member::Postavicka($i, 256), '" />';
	}
	// + Specialny na delete
	echo '<img align="absmiddle" onclick="postava(0);" width="96" title="Vypn&uacute;&#357; postavy." height="96" border="0" alt="" src="', ROOT, 'web2/images/postavicky/Remove.png" />';

	// + Nastavit ako AVATAR
	echo '<br /><br />
		<span class="info_gray">(Klikni na postavu pre aktivovanie)
			<br />', Buttons::Checkbox('panacikovia', 'green', false, true), ' Nastavi&#357; aj ako AVATAR
		</span>';
	echo '<br />
		<div id="shop_ajax_panacikovia"> </div>
	</div>';
}
function obchod_zaznamy() {
	echo '<table align="center" width="500" cellspacing="0" cellpadding="3" id="widget_logy">';
    // TODO: Vypnut, neskor dame.
    $zoznam = new Zoznam;
    $zoznam->list = 30;
	$zoznam->actual = is_numeric($_GET['p1']) ? $_GET['p1'] : 0;
	$zoznam->celkovo = DB::One("SELECT COUNT(`typ`) as pocet FROM `cstrike`.`web2_logs` WHERE 7 > typ AND typ > 4");
	$sql= DB::Query("SELECT * FROM `cstrike`.`web2_logs` WHERE 7 > typ AND typ > 4 ORDER BY kedy DESC ".$zoznam->mysql() );
	$farba=false;
	
	while($udaje=$sql->fetch_assoc())  { 
		$farba = !$farba;	
		echo ($farba) ? '<tr>' : '<tr style="background-color: white;">';
		echo '
			<td width="120" align="center" class="info_gray">', date("Y-m-d", $udaje['kedy']), ' | <span class="logs_co">', date("H:m", $udaje['kedy']), '</span></td>
			<td width="5">&nbsp;</td>
			<td>', WebLog::Parse($udaje), '</td>
		</tr>';
	}
	echo '
	</table>
	<p align="right">';
	$zoznam->Make(ROOT.'shop/', $zoznam);
	echo '</p>';		
	/*Header::Js('
	$(document).ready(function(){	
		web2_Tabs(\'tabs_obchod\', 190, 160);
		web2_Tabs(\'tabs_subobchod\', 190, 160);
	});');*/
}		
function ShopHeader() {
	echo '
	<table cellspacing="0" cellpadding="10" align="center">
		<tr>
			<td>
				<table cellpadding="0" align="center">
				<tr>
					<td>
						<div class="wallet_lp">
							<span class="wallet_lp_font">
								Stav &uacute;&#269;tu:
							</span>
						</div>
						<div class="wallet_rp">
							<span class="wallet_rp_font">
								', ( User::$m->korun <= 0 ? '0.00' : round(User::$m->korun, 2) ), ',- SVK
							</span>
						</div>
					</td>
				</tr>
				</table>
			</td>
		</tr>
	</table>';
}
function obchod__clan()
{
	// Nema clan
if(!User::$m->clan_id) {
	echo web2_alert('Clan Shop', 'Nem&aacute;&scaron; &#382;iadny clan ...');
	exit;
}

echo '
<div id="tabs_obchod_clan">
	<ul>			
		<li><a href="#clan_pokladna"><span> Spolo&#269;n&aacute; poklad&#328;a  </span></a></li>
		<li><a href="#clan_pozvanky"><span> Pozv&aacute;nky  </span></a></li>
		<li><a href="#clan_bonusy"><span> Bonusy  </span></a></li>
	</ul>
';	

// Spolocna pokladna
echo '	
	<div id="clan_pokladna">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td><img src="/web2/web2/images/shop/bank.png" alt="Banka"/></td>
				<td>
					<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">';
					$sql = mysql_query2("SELECT * FROM `cstrike`.`web2_logs` WHERE kat='2' AND typ='1' ORDER BY kedy DESC LIMIT 10");			
					while($udaje = mysql_fetch_assoc($sql))	
					{
						echo '	
							<tr '.$style_hover.' >
								<td width="20" align="right" class="info_gray"> '.date("j.n", $udaje['kedy']).' </td>
								<td width="3">&nbsp;</td>
								<td> '.web_logs($udaje).' </td> 
							</tr>';
					}		
						//<tr><td>18.2 Seky daroval 400.</td></tr>																					
					echo '
					</table>	
				</td>
			</tr>	
			<tr>
				<td colspan="2">		
					<br><br><br>
					Daruj body aj ty !
					<input type="text" size="15" class="inputbox" name="to_kredit" value="30" /><input type="Submit" name="Submit" value="Podpori&#357" id="Submit">
				</td>
			</tr>								
		</table>
	</div>
';


// Pozvanky
echo '	
	<div id="clan_pozvanky" align="center">
		<strong>Chce&scaron; si privyrobi&#357; pozv&aacute;kamy ?</strong><br>
		<img src="/web2/web2/images/shop/bank.png" alt="Banka"/>
	</div>
';


// Nakupovanie bonus ....
echo ' 	
	<div id="clan_bonusy">';
	// Potrebujeme prehlad bonusov...lig. configuracia
	// ale chceme len niektore veci a len niektore constanty takze radsej samotne
	$bonus = array(
			// Clany
			array(	// Nazov									oznacenie	bonus			obrazok
				array("1. miesto",							"a",	"15", 	"1miesto.png"),
				array("2. miesto",							"b", 	"10", 	"2miesto.png"),
				array("3. miesto", 							"c", 	"5", 	"3miesto.png"),
				array("Clan s najlep&scaron;ou aktivitou", 	"d", 	"3", "aktivita.png")
			),
			// Hraci
			array(
				array("Najlepsi skill", 					"a", 	"5", 	"5.gif")
			)
		);
		
	// Aplikaciu na clan ale aj hraca
	for($i=0; $i < count($bonus[0]); $i++)
	{
		
		/*
		
		$bonus[0][$i][0]
		$bonus[0][$i][1]
		$bonus[0][$i][2]
		$bonus[0][$i][3]
	
	
	
	
	
	
	
	
	
	
	<table width="400" border="0" cellspacing="0" cellpadding="0">
  
  
  
  
  <tr>
   	 	<td align="center" width="100">
			<img src="www" alt="Bonus +5%" width="75" height="75" border="0">		</td>
		<td  align="center">
			<br>
				wwww<br>aaaa<br>ddd
			<br><br></td>
		<td  align="center" width="100">
			500 kreditov<br><br>
			<input type="submit" name="Submit" value="Submit" id="Submit">
		</td>
  </tr>

  
</table>






	*/
	}


	
echo '	
	</div>
</div>
';
unset($bonus);


echo '
<script language="javascript">
	$(document).ready(function(){	
		web2_Tabs(\'tabs_obchod_clan\', 190, 160);
	});
</script>';
}