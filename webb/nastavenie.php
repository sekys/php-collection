<?php
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";

User::MustLogged();
Header::Js('web2_checkbox_load();');

// Akcia
require SPAGES."nastavenie/func.php";
require SPAGES."nastavenie/post.php";
require SPAGES."nastavenie/herny.php";
require SPAGES."nastavenie/ligovy.php";
require SPAGES."nastavenie/zombie.php";

function NastaveniaMain() {
	global $userdata;
	echo '		
	<table cellspacing="0" cellpadding="5" align="center" width="100%">
		<tr>
			<td valign="top" align="center" rowspan="2" width="133">
				<img align="left" alt="Nastavenia" src="', RIMAGE, 'theme/settings.gif"/>
			</td>
		</tr>
		<tr>
			<td valign="top" align="center">
			
			<form method="post" action="/nastavenie/">
				<table width="100%" cellspacing="0" cellpadding="0">	
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td align="right"><strong>Hern&eacute; meno:</strong> </td>
						<td> <input type="text" size="21" name="cs_meno" value="', $userdata['cs_meno'], '"/></td>
					</tr>			
					<tr>
						<td align="right"><strong>Steam ID:</strong> </td>
						<td> <input type="text" size="21" name="cs_steam" value="', $userdata['cs_steam'], '" /></td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>';	
					NastaveniaPost();
					LigovyModul();	
					HernyModul();	
					ZombieModul();	
		            echo'
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" name="submit" value="Odosla&#357;"/></td>
					</tr>								
					<tr><td colspan="2">&nbsp;</td></tr>								
				</table>				
			</form>	
			</td>
		</tr>
	</table>';			
}
Theme::Window('Nastavenia', 'NastaveniaMain');
require_once "side_right.php";
require_once "footer.php";