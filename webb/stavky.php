<?
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
Debug::Oblast('STAVKY');

if(!$userdata['user_id'])	{
	User::Unlogin();
} else {	
	// TODO: Vypnut neskor sa zapne, ked bude liga a cas.
    // Akcia - ideme stavit
	Header::Title('St&aacute;vkovanie');
	require SPAGES."stavky-post.php";	
    $z = new Zoznam;
    $z->list = $pocet_ticketov;
    $z->actual = Input::Num('zoznam');
	$z->celkovo = DB::One("SELECT count(id) as pocet FROM `cstrike`.`tickety` WHERE user='".$userdata['user_id']."'");	
	
	if(!$z->celkovo) {
		echo '<br /><p align="center"><strong>Za&#357;ia&#318; si nestavil na &#382;iadn&yacute; z&aacute;pas.</strong></p><br />';
	} else {
		$sql = DB::Query("SELECT * FROM `cstrike`.`tickety` WHERE user='".$userdata['user_id']."' ORDER BY cas DESC ".$z->mysql());	

		// Zoznamy	
		$buffer = '';
		$kurz = 1;
		$pocet = NULL;
		
		while($stavka = $sql->fetch_assoc()) {				
			// Pripravyme stred					
				$sql_zapasy = DB::Query("SELECT t.zapas, t.nakoho, v.id as vyzva, z.id as zapasbol FROM `cstrike`.`ticket` t
												LEFT JOIN ( SELECT id FROM `phpbanlist`.`acp_vyzva` ) v	
													on t.zapas = v.id											
												LEFT JOIN ( SELECT id FROM `phpbanlist`.`acp_zapas` ) z	
													on t.zapas = z.id
											WHERE t.id='".$stavka['id']."'"); // v.prijal IS NOT NULL skusit ci nepojde rychlejsie
							// Hore - mudro som spravyl v SLS lige ze ID vyzvy sa presuva aj do zapasu
							// Takto vieme zistis co bolo zo zapasom
				while($zapas = $sql_zapasy->fetch_assoc()) {
					// Hladame co je vlastne zo zapasom
					if($zapas['vyzva']) {									// Dany zapas sa este neodohral .....
						// Delime podla coho stavil ....					
						$podzapas = DB::Query("SELECT datum, ziada_meno, prijal_meno, stavky_ziada, stavky_prijal FROM `phpbanlist`.`acp_vyzva` v
																	LEFT JOIN ( SELECT id, meno as ziada_meno FROM `phpbanlist`.`acp_clans` ) c on v.ziada = c.id
																	LEFT JOIN ( SELECT id, meno as prijal_meno FROM `phpbanlist`.`acp_clans` ) h on v.prijal = h.id
																	LEFT JOIN ( SELECT * FROM `cstrike`.`kurzy` ) k on v.id = k.id
																	WHERE v.id ='".$zapas['vyzva']."'")->fetch_assoc();																					
						if(!$zapas['nakoho']) {
							$temp[0] = $podzapas['stavky_ziada'];
							$temp[1] = '<strong>'.DB::Vystup($podzapas['ziada_meno']).'</strong> - '.DB::Vystup($podzapas['prijal_meno']);
													
						} else {
							$temp[0] = $podzapas['stavky_prijal'];
							$temp[1] = DB::Vystup($podzapas['ziada_meno']).' - <strong>'.DB::Vystup($podzapas['prijal_meno']).'</strong>';					
						}
						
						$buffer .= '
						<tr>
							<td width="30" align="center" valign="top" class="stavky_nezn">?</td>
							<td><a href="'.ROOT.'cup/vyzva/'.$zapas['zapas'].'/">'.$temp[1].'</a></td>
							<td width="100">'.$temp[0].'</td>
							<td width="110">'.date("j.n H:m", $podzapas['datum']).'</td>
						</tr>';	
						$kurz *=  $temp[0];
					} elseif($zapas['zapasbol'])  {							// Dany zapas sa uz odohral										
						$podzapas = DB::Query("SELECT ziada_skore, prijal_skore, ziada_meno, prijal_meno, stavky_ziada, stavky_prijal FROM `phpbanlist`.`acp_zapas` v
																	LEFT JOIN ( SELECT id, meno as ziada_meno FROM `phpbanlist`.`acp_clans` ) c on v.ziada = c.id
																	LEFT JOIN ( SELECT id, meno as prijal_meno FROM `phpbanlist`.`acp_clans` ) h on v.prijal = h.id
																	LEFT JOIN ( SELECT * FROM `cstrike`.`kurzy` ) k on v.id = k.id
																	WHERE v.id='".$zapas['zapas']."'")->fetch_assoc();
						if(!$zapas['nakoho']) {
							$temp[0] = $podzapas['stavky_ziada'];
							$temp[1] = '<a href="'.ROOT.'cup/zapas/"><strong>'.DB::Vystup($podzapas['ziada_meno']).'</strong> - '.DB::Vystup($podzapas['prijal_meno']).'</a>';
													
						} else {
							$temp[0] = $podzapas['stavky_prijal'];
							$temp[1] = '<a href="'.ROOT.'cup/zapas/">'.DB::Vystup($podzapas['ziada_meno']).' - <strong>'.DB::Vystup($podzapas['prijal_meno']).'</strong></a>';					
						}	
							
					// Delime ci vyhral alebo nie .....	
						if ( 
								( $podzapas['ziada_skore'] == $podzapas['prijal_skore'] ) or
								( !$zapas['nakoho'] and $podzapas['ziada_skore'] > $podzapas['prijal_skore'] ) or
								( $zapas['nakoho'] and $podzapas['ziada_skore'] < $podzapas['prijal_skore'] )
							) {
					
								$kurz *=  $temp[0];
								$pocet[1]++;
								$buffer .= '
								<tr>
									<td width="30" align="center" valign="top" class="stavky_ukon">ok</td>
									<td>'.$temp[1].'</td>
									<td width="100">'.$temp[0].'</td>
									<td width="110" class="stavky_ukon">vyhral</td>
								</tr>';								
							} else {
								$kurz *=  $temp[0];
								$pocet[2]++;
								$buffer .= '<tr>
									<td width="30" align="center" valign="top" class="stavky_zle">x</td>
									<td>'.$temp[1].'</td>
									<td width="100">'.$temp[0].'</td>
									<td width="110" class="stavky_zle">prehral</td>	
								</tr>';
							}					
					} else {											// Zapas bol vymazany
						$buffer .= '
							<tr>
								<td width="30" align="center" valign="top" class="stavky_nezn">-</td>
								<td>Z&aacute;pas bol zmazan&yacute;, nepo&#269;&iacute;ta sa ...</td>
								<td  width="100">1.0</td>
								<td  width="110"> </td>
							</tr>';	
					}									
					$pocet[0]++;
				}	
				$kurz = round($kurz, 2);
			
			
			// Vrch
				// Farba
				if($stavka['vybaveny']) {
					$temp[0] = $pocet[2] ? '#FF6600' : '#009966';
				} else {
					$temp[0] = $pocet[2] ? '#FF6600' : '#0099FF';
				}
			echo '
				<table width="510" border="0" cellpadding="0" align="center" cellspacing="0" bgcolor="'.$temp[0].'" class="stavka_tab">
					<tr>
						<td class="stavka_tab_td" width="50" id="stavka-'.$stavka['id'].'-id"> <strong>#'.$stavka['id'].'</strong></td>
						<td class="stavka_tab_td">'.$stavka['vklad'].' vklad </td>';
						IF($pocet[2]) { $stavka['vklad'] = 0; }
			
			echo '		<td class="stavka_tab_td">'. $kurz*$stavka['vklad'] .' v&yacute;hra</td>
						<td class="stavka_tab_td">'.$kurz.' kurz </td>	
						<td class="stavka_tab_td">'.date("j.n", $stavka['cas']).' podan&yacute; </td>
					</tr>
					<tr style="display: none;" id="stavka-'.$stavka['id'].'-row"> 
						<td colspan="5" >
							<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';					
			// Stred
						echo $buffer;
			// Spodok					
			echo '
						  </table>	
						</td>
					</tr>
					<tr>
						<td class="stavka_tab_td" align="center" onclick="stavka_toggle('.$stavka['id'].');" >+</td>
						<td class="stavka_tab_td">'.( $pocet[0] ? $pocet[0] : 0 ).' z&aacute;pasov</td>
						<td class="stavka_tab_td">'.( $pocet[1] ? $pocet[1] : 0 ).' vyhran&eacute;</td>
						<td class="stavka_tab_td">'.( $pocet[2] ? $pocet[2] : 0 ).' prehran&eacute; </td>
						<td class="stavka_tab_td">'.date("j.n", $stavka['koniec']).' koniec</td>
					</tr>
				</table>';
				
			// Po kazdom cykle sa buffer uvolni	
			$buffer = '';
			$kurz = 1;
			$pocet = NULL;	
		}
		unset($temp);
		unset($podzapas);
		unset($zapas);
		unset($stavka);
	}
	
	echo '
	<table width="500" border="0" cellpadding="0" align="center" cellspacing="0">
		<tr>
			<td width="50%">';
				$z->Make('/stavky/%d/');
			echo '</td>
			<td align="right"><a href="'.ROOT.'cup/zapasy/">Stavi&#357; na z&aacute;pas <img border="0" align="absmiddle" src="/web2/web2/images/forward.png" alt="&#270;alej"/></a><td>	
		</tr>	
	</table>
	<br /><br /><br />';
	unset($pocet_ticketov);
	// ak niekto zrusil posledny zapas ....tak vynuluj ho z ticketov a v TYZKETYak bol vacsi tak prestav  koniecny datum
	// A program v perle automaticky hlada vsetke zapasy co NESUUKONCENE a su starsie ako dnesy datum
	
	// zapasy musime pridat VYMAZANY a takto identifikujeme ci dany zapas sa odohral alebo bol zruseny
	// ( zaroven vsetke nepotrebne hodnoty v DB nulovat )
	
	// Do cron-tab na den
	// Prejde tabulku stavky a poriesi vsetke udaje ak sa zapas odohral 
	// Ak vyhral zapise do stavky_logs
	
	// Ak by sme mali LOGY tak STAVKA moze byt cislo 3 a teda zapas bude vymazany
}
Debug::Oblast('STAVKY');
require_once "side_right.php";
require_once "footer.php";	
?>		
