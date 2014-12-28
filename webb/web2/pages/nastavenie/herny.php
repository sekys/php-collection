<?
function HernyModul()
{				
	// Povoleny
	global $userdata;
	    nastavenia_header('herny', '<strong>Hern&yacute; modul</strong> '.je_aktivny($userdata['amx']));
	if($userdata['amx']) {
		echo Mess::Tip("Hern&yacute; modul sa star&aacute; o prepojenie port&aacute;lu a serverov. Vypnut&iacute;m sa deaktivuje pr&iacute;padne VIP alebo adminsk&eacute; pr&aacute;va");	
	} else {
		echo Mess::Tip("
		Hern&yacute; modul je najd&ocirc;le&#382;itej&scaron;ia aplik&aacute;cia samozrejme len zapnut&aacute; ,ktor&aacute; je potrebn&aacute; pre :<br />
		- Adminsk&eacute; pr&aacute;va. <br />
		- VIP<br />
		- Rezervovan&yacute; slot  <br />
		- Je potrebn&aacute; aj pre Zombie banku <br />");
	}
	$alert = (!$userdata['cs_steam']) ? true : false;
	// Nastroje - pripadne disabled cez funkciu 	
	echo  '	<br />
		<div align="right"><a href="/forum/">Požiadať o pomoc &raquo;</a></div>												
		<table cellspacing="0" cellpadding="0" width="100%">	
			<tr>
				<td align="center"><input type="button" name="amx" '.( $alert ? "onclick=\"alert('Najprv mus&iacute;&scaron; vyplni&#357; <strong>Steam &#269;&iacute;slo</strong>')\"" : "").' value="'.( $userdata['amx'] ? "Vypn&uacute;&#357;" : "Zapn&uacute;&#357;" ).'"/></td>
			</tr>	
		</table>';
    nastavenia_footer();
}