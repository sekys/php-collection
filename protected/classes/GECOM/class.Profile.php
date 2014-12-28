<?

class Profile
{
	protected static $text_velkost;
	
	public static function Find($lookup) {
	    return GInput::URIMeno2Member($lookup);  
	}
	protected static function Avatar() {
	    global $p;
	    $avatar = $p->AvatarCesta();
	    if($avatar != AVATAR_DEFAULT) {
	        echo "<img vspace='0' hspace='10' src='", $avatar, "' alt='", $p->user_name, "' />";
	        self::$text_velkost = 62;
	    } else {
	        self::$text_velkost = 112;
	    }
	}
	protected static function Name() {
	    //Mattusko
	    global $p;
	    $pocet = strlen($p->user_name);
	    if($pocet > 8 )    {
	        /*
	            NEPriama umera funguje paradne ...
	            112px    8 pismen
	            xpx        $pocet pismen
	            
	            $pocet / 8= 112 /x
	            $pocet * x = 8 * 112
	            x = 112 *8 / $pocet
	        */
	        $pocet = floor(self::$text_velkost * 8 / $pocet);
	    } else {
	        $pocet = self::$text_velkost; // maximum
	    }
	    echo "<td class='profil_meno' style='font-size: ", $pocet, "px;'>", $p->user_name, "</td>";
	}
	public static function Header() {
		global $p;
		echo '
		<table align="center" cellpadding="0" border="0" cellspacing="0" width="100%">	
			<tr>
				<td align="center" valign="center" width="128">';
		        self::Avatar();
				echo '</td>
				<td width="60%"> 
					<table align="center" cellpadding="0" cellspacing="1" width="100%">
						<tr>';
		                self::Name();
					    echo '	
						</tr>	
						<tr>	
							<td class="tbl1" align="center">';
		                    $p->InfoBar();				
						echo '</td>
						</tr>
					</table>
				</td>
				<td align="center" valign="center" width="128" >
					<div class="pod about-me">	
					<div class="bd" align="center">';
						global $p;
						if(!$p->profil_popis) {
							echo '<br />', $p->user_name, ' nem&aacute; vyplnen&yacute; popis.<br />';
						} else {				
							echo DB::Vystup($p->profil_popis);
						}	
					echo '	
						<table>
							<tr class="first">
								<td>Zlato:</td>
								<td align="right" class="last">', number_format($p->korun), ',</td>
							</tr>
							<tr>
								<td>Zaregistrovan&yacute;:</td>
								<td align="right" class="last">', date("M d, Y", $p->user_joined), '</td>
							</tr>
							<tr>
								<td>Naposledy prihl.:</td>
								<td align="right" class="last">', date("M d, Y", $p->user_lastvisit), '</td>
							</tr>
						</table>
					</div>
					</div>
				</td>	
			</tr>
		</table>';
	}
}
