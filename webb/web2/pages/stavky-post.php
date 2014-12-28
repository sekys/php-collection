<?
if (!defined("IN_FUSION")) { header("Location: index.php"); exit; }

$pocet_ticketov = 10;	

// Akcia - ideme stavit
if(isset($_POST['Submit'])) {			
	// Overujeme peniaze
	$vklad = $_POST['stavky_vklad'];
	if( is_numeric($vklad) and $vklad >= 10 ) 
	{
		if( $vklad <= $userdata['korun'] ) 
		{					
			// Citame cookies
			$stavky = explode(".", $_COOKIE['tickety']);
			$pocet = count($stavky);	
								
			if($pocet > 1) {
				$odsuhlasene = isset($_POST['odsuhlasene']) ? true : false; 	
				$pocet_ticketov = 5;
				$poslednydatum = 0;
				$tickety_id = '';
				
				// Kontrolujeme duplicitu	
				function zapasy_duplicita($id) 
				{
					global $tickety_id;
					if(strpos($tickety_id, '.'.$id.'-') === false) {
						$tickety_id .= '.'.$id;
						return true;
					} 
					return false;
				}
				
				if($odsuhlasene)
				{					
					DB::Query("INSERT INTO `cstrike`.`tickety` ( `id`, `user`, `cas`, `vklad`, `koniec` ) VALUES ( NULL , '".$userdata['user_id']."', '".Time::$TIME."', '".$vklad."', '0');");														
					$id = mysql_insert_id();

					for($i=1; $i < $pocet; $i++) 
					{
						$data = explode("-", $stavky[$i]);													
						// Kontrola pred vstupom
						if( is_numeric($data[0]) and is_numeric($data[1]) )
						{
							$data[0] = DB::Vstup($data[0]);
							// Kontrola ci existuje					
							$sql = DB::Query("SELECT c.datum FROM `phpbanlist`.`acp_vyzva` c
								JOIN ( SELECT id FROM `phpbanlist`.`acp_clans` GROUP BY id ) h
									ON c.ziada = h.id								
								JOIN ( SELECT id FROM `phpbanlist`.`acp_clans` GROUP BY id ) f
									ON c.prijal = f.id	
								JOIN ( SELECT id FROM `cstrike`.`kurzy`) k
									ON c.id = k.id		
								WHERE c.id = '".$data[0]."'");
								
							$mysql = $sql->fetch_row();
							if($mysql[0]) {	
								if( zapasy_duplicita($data[0]) )
								{
									// posledny datum
									if($poslednydatum < $mysql[0]) {
										$poslednydatum = $mysql[0];
									}
									DB::Query("INSERT INTO `cstrike`.`ticket` ( `id` , `zapas` , `nakoho` ) VALUES ( '".$id."', '".$data[0]."', '".( $data[1]==1 ? "0" : "1" )."')");
								}
							}	
						}	
					}
					
					// Ak nebol zmeneny posledny datum -> ziadny zapas nebol uspesny .....
					if($poslednydatum ==0 ) {
						DB::Query("DELETE FROM `cstrike`.`tickety` WHERE id ='".$id."'");
						echo 'Ticket nebol poslan&yactue;.';
					} else {
						DB::Query("UPDATE `cstrike`.`tickety` SET `koniec` = '".$poslednydatum."' WHERE id ='".$id."'");
						setcookie("tickety", "");
						echo "<script type='text/javascript'> stavky_vymaz_all(); </script>";		
						echo '<br><p align="center"><strong>Ticket pridan&yacute;.</strong></p><br>';
					}
				// Este neodsuhlasene .....		
				} else { 
					$kurz = 1;																
					echo '
				<div class="pravidla_head legenda">	
					<form method="post" action="'.ROOT.'stavky/">
						<table width="500" border="0" cellspacing="0" cellpadding="0" id="tickety" align="center">
							<tr>
								<td colspan="4" align="center">GeCom::Lekos</td>
							</tr>
							<tr>
								<td colspan="2" align="right"><strong>S t a v t e sa . . .</strong></td>
								<td colspan="3">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="2" class="dolnaciara"> Z&aacute;pas</td>
								<td width="80" align="center" class="dolnaciara" >Tip</td>
								<td width="80" align="center" class="dolnaciara" >Kurz</td>
							</tr>';	
							
					// Konretne zapasy	
						for($i=1; $i < $pocet; $i++) 
						{
							$data = explode("-", $stavky[$i]);					
							// Kontrola pred vstupom
							if( is_numeric($data[0]) and is_numeric($data[1]) )
							{
								// Kontrola ci existuje
								$sql = DB::Query("SELECT ".( $data[1]==1 ? 'k.stavky_ziada' : 'k.stavky_prijal' ).", h.ziada_meno, f.prijal_meno, c.datum FROM `phpbanlist`.`acp_vyzva` c
									JOIN ( SELECT id as ziada_id, meno as ziada_meno FROM `phpbanlist`.`acp_clans` GROUP BY id ) h
										ON c.ziada = h.ziada_id								
									JOIN ( SELECT id as prijal_id, meno as prijal_meno FROM `phpbanlist`.`acp_clans` GROUP BY id ) f
										ON c.prijal = f.prijal_id	
									JOIN ( SELECT * FROM `cstrike`.`kurzy`) k
										ON c.id = k.id		
									WHERE c.id = '".DB::Vstup($data[0])."'");
									
								$mysql = $sql->fetch_row();
								if($mysql[0]) {	
									if( zapasy_duplicita($data[0]) )
									{
										// posledny datum
										if($poslednydatum < $mysql[3]) {
											$poslednydatum = $mysql[3];
										}
										
										echo '
										  <tr id="ticket-'.$data[0].'">
												<td colspan="2">
													<a href="', ROOT, '">';
												if($data[1]==1) {
													echo '<strong>'.DB::Vystup($mysql[1]).'</strong>-'.DB::Vystup($mysql[2]);
												} else {
													echo DB::Vystup($mysql[1]).'-<strong>'.DB::Vystup($mysql[2]).'</strong>';			
												}	
											echo '</a><br>
													<span class="info_gray">Za&#269;iatok: '.date('j.n H:m', $mysql[3]).'</span>									
												</td>
												<td align="center">'.( $data[1]==1 ? "1" : "2" ).'</td>
												<td align="center">'.$mysql[0].'</td>
										  </tr>';
										$kurz *= $mysql[0];
									}
								}	
							}	
						}
						setcookie("tickety", $tickety_id);	
						echo '
							<tr>
								<td colspan="4">&nbsp;</td>
							 </tr>
							<tr>
								<td>
									Vsaden&aacute; &#269;iastka :<br>
									Mo&#382;n&aacute; v&yacute;hra : <br>
									Ticket ukon&#269;en&yacute; :
								</td>
								<td>'.$vklad.' SVK<br>'.round($vklad*$kurz, 2).' SVK<br>'.date("d-m-Y", $poslednydatum).'</td>
								<td colspan="2" valign="top">Celkov&yacute; kurz: <strong>'.number_format(round($kurz, 2), 2).'</strong> </td>
							</tr>
							<tr>
								<td colspan="4" align="center">
									<br>
									<input type="hidden" name="odsuhlasene" value="1" />
									<input type="hidden" name="stavky_vklad" value="'.$vklad.'" />
									<input type="submit" name="Submit" value="Submit" id="Submit" />
									<br><br>										
								</td>
							</tr>
						</table>
					</form>
				</div>';
					unset($kurz);
				}	
				unset($tickety_id);
				unset($poslednydatum);
			} else {
				Mess::Alert('St&aacute;vky', 'V tickete mus&iacute;&scaron; ma&#357; nejak&eacute; z&aacute;pasy.' );
			}	
		} else {
			Mess::Alert('St&aacute;vky', 'Nem&aacute;&scaron; dostatok kor&uacute;n.');
		}	
		unset($stavky);
		unset($pocet);
	} else {
		Mess::Alert('St&aacute;vky', 'Minim&aacute;lny vklad je 10 SVK.');
	}	
	unset($vklad);	
}
	
?>		