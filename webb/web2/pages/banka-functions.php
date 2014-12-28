<?php

// Rank
function rank($include, $zoznam, $pocet) {
    $z = new Zoznam;
    $z->actual = is_numeric($zoznam) ? $zoznam : 0;
	$rank = $zoznam ? $zoznam : 0;
	$farba=false; //siva farba
	
	if(isset($_POST['auth'])) {
		$prikaz = " WHERE auth LIKE '%".DB::Vstup($_POST['auth'])."%' ";
	}
	global $banka;
	
	$z->celkovo = DB::One("SELECT count(id) as pocet FROM ".$banka['mysql']." ".$prikaz);
	
	// <h1>banka Banka Rank</h1>
	if($include) {
		echo '
		<div align="right">
			<form method="post" action="'.$banka['cesta'].'rank/">
				<input name="auth" type="text" value="Meno" maxlength="33" />
				<input type="submit" name="Submit" value="H&#318;ada&#357;" id="Submit" />
			</form>
		</div>';
	}
	
	echo '
	<table class="ps-table ps-weapon-table" align="center" width="500" cellspacing="0" cellpadding="3">
		<tr>
			<th><a href="#" class="desc">'.( isset($_POST["auth"]) ? "#" : "Rank").'</span></a></th>
			<th><a href="#" class="desc">Meno</a></th>
			<th colspan="2"><a href="#" class="desc">Bodov</a></th>
		</tr>';
	if($z->celkovo) {
		$sql= DB::Query("SELECT * FROM ".$banka['mysql']." ".$prikaz." ORDER BY amount desc ".$z->mysql() );
		while($row=$sql->fetch_assoc())  {  
			$rank++; 
			$farba = !$farba;			
			echo ($farba) ? '<tr>' : '<tr style="background-color: white;">';
			
			$row['auth'] = DB::Vystup($row['auth']);
			echo '<td width="30" >' . $rank . '.</td>
				<td width="300"><a href="http://www.cs.gecom.sk/psychostats/index.php?q=' . BaseSTR::uri_out($row['auth']) . '" >' . $row['auth'] . '</a></td>
				<td width="50" align="center">' . $row['amount'] . '</td>
				<td width="20" align="center">
					<a href="'.$banka['cesta'].'daruj/hrac/' . $row['id'] . '/">
						<img border="0" src="'.ROOT.'web2/images/body_add.png" title="Darova&#357; '.$banka['nazov'].' body" alt="Darova&#357; '.$banka['nazov'].' body"/>
					</a>
				</td>
			</tr>';
		} 
	}
	echo '
	</table>
	<br />';
    $z->vzdialenost = 5;
	$z->Make($include ? $banka['cesta'].'rank/%s/' : $banka['cesta'].'%s/');
    echo '<br />';
}
function prihlasenie($error) {
	global $banka;
	echo '
	<div align="center">
		<img alt="" src="', RIMAGE, 'banka.jpg"/>
		<p> '.$error.' </p>
		<form method="post" action="'.$banka['cesta'].'">
			<input type="submit" name="Submit" value="Prihl&aacute;si&#357;" /> 
		</form>
		<br /><br /><br />
	</div>';
	// 			<input name="banka_meno" type="text" value="Hern&eacute; meno" />
	//			<input name="banka_heslo" type="text" value="Heslo" />
}

// Stranka ...
function banka() {	
	global $userdata, $banka, $p1, $p2;
	
	// Odhlasit
	if( isset($_POST['logout'])) {
		@DB::Query("UPDATE `cstrike`.`fusion_users` SET `".$banka['users']."` = '0' WHERE user_id = '".$userdata['user_id']."' ");
		$userdata[$banka['users']] = 0;
	}
	
	// Prihlasenie
	if($userdata[$banka['users']] == 0) {
		$banka_meno = $_POST['banka_meno'];
		//$banka_heslo = $_POST['banka_heslo'];
			
		if($banka_meno) {
			$sql = DB::Query("SELECT * FROM ".$banka['mysql']." WHERE steam LIKE '".DB::Vstup($userdata['cs_steam'])."'");
			if( $sql->num_rows != 0) {
				$data = $sql->fetch_assoc();
				//if($data['pass'] == $banka_heslo) {
					DB::Query("UPDATE `cstrike`.`fusion_users` SET `".$banka['users']."` = '".$data['id']."' WHERE user_id = '".$userdata['user_id']."'");
					WebLog::Add(1, $zombie ? 12 : 17, $userdata['user_id']);
					$userdata[$banka['users']] = $data['id'];				
					/*
						SetCookie ("banka_meno", $data['id'], Time::$TIME+10*60);
						SetCookie ("banka_heslo", $data['pass'], Time::$TIME+10*60);
					*/
				/*} else {
					prihlasenie('Nespr&aacute;vne heslo ...');
					return false;
				}*/
			} else {
				prihlasenie('&Uacute;&#269;et nen&aacute;jden&yacute; ...m&aacute;&scaron; spr&aacute;vne zadan&eacute; steam &#269;islo ?');
				return false;
			}
		} else {
			prihlasenie( isset($_POST['logout']) ? '&Uacute;spe&scaron;ne odhl&aacute;sen&yacute; ' : 'Prihl&aacute;senie...');
			return false;
		}
	} else {
		// Uz je prihlaseny
			
			// Vymazat ucet
			if( isset($_POST['delete'])) {
				WebLog::Add(1, $zombie ? 13 : 18, $userdata['user_id'], false, false, DB::Vstup($data['auth']));
				DB::Query("DELETE FROM ".$banka['mysql']." WHERE `id` = '".$userdata[$banka['users']]."'");
				DB::Query("UPDATE `cstrike`.`fusion_users` SET `".$banka['users']."` = '0' WHERE user_id = '".$userdata['user_id']."' ");
				$userdata[$banka['users']] = 0;
				prihlasenie('Uacute;&#269;et vymazan&yacute;.');
				return false;
			}
			
			// Nacitame
			@$sql = DB::Query("SELECT * FROM ".$banka['mysql']." WHERE id = '".$userdata[$banka['users']]."' ");
			$data = $sql->fetch_assoc();
			
			// Zmena udajov ....
			/*if( isset($_POST['stare_heslo'])) 
			{
				if( $_POST['stare_heslo'] == $data['pass'] )
				{
					if( $_POST['nove_heslo'] == $_POST['znova_heslo'] ) {												
						// Ak nemenil meno
						WebLog::Add(1, $zombie ? 14 : 19, $userdata['user_id']);
						if( $data['auth'] == $_POST['nove_meno'])
						{
							@DB::Query("UPDATE ".$banka['mysql']." SET `pass` = '".DB::Vstup($_POST['znova_heslo'])."' WHERE `id`='".$userdata[$banka['users']]."'");					
							$zm_error_zmena = '<td rowspan="3" valign="center" align="center" class="color_red"> Heslo &uacute;spe&scaron;ne zmenen&eacute;.</td>';					
						} else {
							// Aj meno
							@$sql = DB::Query("SELECT id FROM ".$banka['mysql']." WHERE auth LIKE '".DB::Vstup($_POST['nove_meno'])."'");
							if( $sql->num_rows > 0) {
								$zm_error_zmena = '<td rowspan="3" valign="center" align="center"  style="color:red;"> Toto hern&eacute; meno u&#382; niekto pou&#382;&iacute;va.</td>';					
							} else {
								$data['auth'] = $_POST['nove_meno'];
								@DB::Query("UPDATE ".$banka['mysql']." SET `auth` = '".DB::Vstup($_POST['nove_meno'])."', `pass` = '".DB::Vstup($_POST['znova_heslo'])."' WHERE `id` = '".$userdata[$banka['users']]."'");					
								$zm_error_zmena = '<td rowspan="3" valign="center" align="center"  style="color:green;"> Heslo aj meno &uacute;spe&scaron;ne zmenen&eacute;.</td>';											
							}
						}
					} else {
						$zm_error_zmena = '<td rowspan="3" valign="center" align="center" style="color:red;"> Nov&eacute; heslo a znova heslo sa nezhoduj&uacute;. </td>';
					}			
				} else {
					$zm_error_zmena = '<td rowspan="3" valign="center" align="center" style="color:red;"> Star&eacute; heslo sa nezhoduje s tvojim heslom. </td>';
				}
			}*/
	}		
	// Vypis utctu
	echo '
	<table width="500" cellspacing="0" cellpadding="2" border="0"><tbody><tr><td><strong>'.DB::Vystup($data["auth"]).' &uacute;&#269;et</strong></td></tr></tbody></table>
	<br />
	<div id="status">
	<form method="post" action="'.$banka['cesta'].'">
	<table width="500" border="0" cellspacing="0" cellpadding="0">	
		<tr>
			<td rowspan="6"><img border="0" hspace="20" alt="'.$banka['nazov_v'].' banka" src="'.ROOT.'web2/images/Chart.png"/></td>
		</tr>		
		<tr>
			<td>Steam &#269;&iacute;slo : </td>
			<td>'.$data["steam"].'</td>
			<td>&nbsp;</td>
		</tr>		
		<tr>
			<td>Vytvoren&yacute; : </td>
			<td>'.$data["create"].'</td>
			<td>&nbsp;</td>
		</tr>		
		<tr>
			<td>Naposledy pou&#382;it&yacute;  : </td>
			<td width="100">'.$data["last"].'</td>
			<td><input name="delete" type="submit" value="Zmaza&#357;" /></td>
		</tr>
		<tr>
			<td>Stav : </td>
			<td width="100">'.$data["amount"].' bodov </td>
			<td><input name="logout" type="submit" id="delete" value="Odhl&aacute;si&#357;" /></td>
		</tr>		
		<tr>
			<td>'.$banka['nazov_v'].' VIP : </td>
			<td>'.( $data["vip"] ? "Ano" : "Nie" ).'</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	</form>
	</div>';

	// Zmenit heslo
	/*echo '
	<br />
	<table width="500" cellspacing="0" cellpadding="2" border="0">
		<tbody><tr>
			<td onclick="mShowMe(\'heslo\');" style="cursor: pointer; font-size: 12px;"><strong>+ Zmeni&#357; prihlasovacie &uacute;daje </strong></td>
		</tr>
	</tbody>
	</table>
	<br />
	<div id="heslo">
	<form method="post" action="'.$banka['cesta'].'">
	<table width="500" border="0" cellspacing="0" cellpadding="0">		
		<tr>
			<td width="175">Nov&eacute; meno:</td>
			<td width="145"><input name="nove_meno" type="text" value="'.DB::Vystup($data['auth']).'" /></td>';
		if($zm_error_zmena) {	
			echo $zm_error_zmena;
		}
		echo '
		</tr>		
		<tr>
			<td>Star&eacute; heslo :</td>
			<td><input name="stare_heslo" type="text" value="" /></td>
		</tr>		
		<tr>
			<td>Nov&eacute; heslo :</td>
			<td><input name="nove_heslo" type="text" value="" /></td>
		</tr>	
		<tr>
			<td>Znova nov&eacute; heslo :</td>
			<td><input name="znova_heslo" type="text" value="" /></td>
		</tr>';
	echo '	
		<tr>
			<td align="center"><input name="zmena_hesla" type="submit" id="delete" value="Zmeni&#357;" /></td>
			<td>&nbsp;</td>
		</tr>		
	</table>	
	</form>
	</div>';*/
	
	// Darovat banka body ...
	echo '
	<br />
	<table width="500" cellspacing="0" cellpadding="2" border="0">
		<tbody><tr>
			<td  onclick="mShowMe(\'body\');" style="cursor: pointer; font-size: 12px;">
				<strong>+ Darova&#357; body</strong>
			</td>
		</tr>
	</tbody>
	</table>
	<br />
	<div id="body">';
	
	// Ideme darovat
	if($p1 == $banka['nazov']."-hrac" or $p1 == "hrac") {
		
		// Ideme darovat
		if( !is_numeric($p2) ) {
			// Znova ziadne
			default_form('Nespr&aacute;vne &uacute;daje !');
		} else {
			// Stranka	
			if($p1 == $banka['nazov'].'-hrac') {
				// banka hrac
				@$sql=DB::Query("SELECT * FROM ".$banka['mysql']." WHERE id= '".DB::Vstup($p2)."'  ");
			} elseif ( $p1 == 'hrac' ) {
				// Na webe
				$sql=DB::Query("SELECT user_id, cs_meno, ".$banka['users']." FROM `cstrike`.`fusion_users` WHERE user_id = '".DB::Vstup($p2)."'");
				$udaj = $sql->fetch_assoc();
				$udaj_web = $udaj['user_id'];
				if($udaj[$banka['users']] ) {
					@$sql=DB::Query("SELECT * FROM ".$banka['mysql']." WHERE id= '".$udaj[$banka['users']]."'  ");
				} else {
					@$sql=DB::Query("SELECT * FROM ".$banka['mysql']." WHERE auth LIKE '".DB::Vstup($udaj['cs_meno'])."'");
				}
			}
			
			// Potrebujeme vysledne SQL
			if( $sql->num_rows != 0) {				
				$udaj = $sql->fetch_assoc();
				
				// Sam sebe posiela ....
				if( $udaj['id'] == $data["id"] ) {
					default_form('Nem&ocirc;&#382;e&scaron; posla&#357; body s&aacute;m sebe ! ');
				} else {
				
					//Akcia
					$body = $_POST['banka_bodov'];
					if($body and is_numeric($body) ) {			
						if( $body > $data['amount']) {
							$body = $data['amount'];
						}
						// body
						$body = DB::Vstup($body);
						$udaj['amount'] = $udaj['amount'] + $body;
						DB::Query("UPDATE ".$banka['mysql']." SET `amount` = `amount` + '".$body."' WHERE id = '".$udaj['id']."'");					
						DB::Query("UPDATE ".$banka['mysql']." SET `amount` = `amount` - '".$body."' WHERE id = '".$data["id"]."'");
						WebLog::Add(1, $zombie ? 15 : 16, $userdata["user_id"], $body, false, DB::Vstup($udaj['auth']));
						//sprava
						if($udaj_web) {
							$sprava  = DB::Vstup('[b]Tento hr&aacute;&#269; ti poslal '.$body.' bodov do '.$banka['nazov'].' banky, jeho spr&aacute;va:[/b]');
							$sprava .= DB::Vstup($_POST['banka_dovod']);
							
							DB::Query("INSERT INTO `cstrike`.`fusion_messages` 
							(message_to, message_from, message_subject, message_message, message_smileys, message_read, message_datestamp, message_folder) 
							VALUES 
							('".$udaj_web."', ".$userdata['user_id'].", 'Zombie banka','".$sprava."','y','0','".Time::$TIME."','0')");
						}
					} 
					
					// Normalne
					echo '<form method="post" action="'.$banka['cesta'].'?p1='.$p1.'&p2='.$p2.'">
					<table width="350" border="0" align="center" cellspacing="0" cellpadding="0">
								<tr>
									<td align="center"><strong>'.DB::Vystup($udaj['auth']).'</strong> m&aacute; <strong>'.$udaj['amount'].'</strong> bodov.</td>
								</tr>
								<tr>';
									if($body) {
										echo '<td align="center" style="color:green;">Body &uacute;spe&scaron;ne poslan&eacute; !</td></tr><tr>';
									} 
							
									if($data['amount'] > 0) {
										//	input name="banka_komu" type="hidden" value="'.$udaj['id'].'" />								
											echo'
										<td align="center">';
										if($udaj_web) {
											echo '	
											Spr&aacute;va pre pr&iacute;jemcu :
											<textarea name="banka_dovod" cols="40" rows="4" id="textarea">Nema&scaron; za &#269;o :) </textarea><br />
											';
										}
											echo '	
											<input name="banka_bodov" type="text" value="ko&#318;ko" size="10"/><br />
											<input name="nastav_body" type="submit" id="delete" value="ok" />';
									} else {
										echo '<td align="center" class="color_red">Nem&aacute;&scaron; &#382;iadne body ! Vyu&#382;i nab&iacute;janie <a href="'.ROOT.'obchod/#body">bodov</a>.';
									}		
							echo'	</td>					
								</tr>
							</table>';
				}
			} else {
				default_form('&Uacute;&#269;et nen&aacute;jden&yacute; !');
			}		
		}
	} else {
		// Ziadne meno nevybrane, haldame ....
		default_form('');
		if(isset($_POST['auth'])) {
            global $p1;
			rank(false, $p1, 30);
		}
	}	
	echo'
	</form>
</div>'; 
}
function default_form($error) {
	global $banka;
	echo '	
	<form method="post" action="'.$banka['cesta'].'">
		<table width="500" border="0" cellspacing="0" cellpadding="0">		
			<tr>
				<td width="200" class="color_red">'.$error.'</td>
				<td align="right">
					<input name="auth" type="text" value="'.( isset($_POST['auth']) ? DB::Vystup($_POST['auth']) : 'Meno').'" maxlength="33" />
					<input type="submit" name="search" value="Hlada&#357;" id="Submit" />
				</td>
			</tr>	
		</table>
		<br />';		
}

?>