<?
function LigovyModul()
{
	// Ligovy modul								
	global $userdata;
	nastavenia_header('liga', '<strong>Ligov&yacute; modul</strong> '.je_aktivny($userdata['liga']));
    // Povoleny
	if($userdata['m_liga']) {											
		echo Mess::Tip("Ak vypne&scaron; ligov&yacute; modul, nez&uacute;&#269;astni&scaron; sa ligy.<br />");	
		$aktivovane = false;
	} else {
		$aktivovane = true;
		echo "<div>
			Hern&yacute; modul je najd&ocirc;le&#382;itej&scaron;ia aplik&aacute;cia samozrejme len zapnut&aacute; ,ktor&aacute; je potrebn&aacute; pre :<br />
			- Adminsk&eacute; pr&aacute;va. <br />
			- VIP<br />
			- V&aacute;&scaron; NICK nikto nebude pou&#382;&iacute;va&#357;. <br />
			- Rezervovan&yacute; slot  <br />
			- Je potrebn&aacute; aj pre Zombie banku <br /></div>";
	}
	
	$alert = (!$userdata['cs_steam']) ? true : false;
	
	// Dalsie
	echo  '
	<br />
	', Buttons::CheckBox('m_liga_tema', 'orange', $aktivovane, $userdata['m_liga_tema']), ' Pou&#382;&iacute;va&#357; clan t&eacute;mu <br />
	', Buttons::CheckBox('m_liga_zapasy', 'orange', $aktivovane, $userdata['m_liga_zapasy']), ' Prijímať spr&aacute;vy o z&aacute;pasoch<br />
	', Buttons::CheckBox('m_liga_zrusene', 'orange', $aktivovane, $userdata['m_liga_zrusene']), ' Prijímať spr&aacute;vy o zru&scaron;en&yacute;ch z&aacute;pasoch<br />
	', Buttons::CheckBox('m_liga_pozvanky', 'orange', $aktivovane, $userdata['m_liga_pozvanky']), ' Prijímať pozv&aacute;nky<br />
	', Buttons::CheckBox('m_liga_hraci', 'orange', $aktivovane, $userdata['m_liga_hraci']), ' Prijímať spr&aacute;vy o nov&yacute;ch hr&aacute;&#269;och<br />
	<br />
	<div align="right"><a href="/forum/">Požiadať o pomoc &raquo;</a></div>												
	<table cellspacing="0" cellpadding="0" width="100%">
		<tr>';
		if($userdata['clan_id']) {
			echo '<td align="center"><input type="button" name="clan" value="Od&iacute;s&#357; z clanu"/></td>';
		}
		echo '	
			<td align="center"><input type="button" name="m_liga" '.( $alert ? "onclick=\"alert('Najprv mus&iacute;&scaron; vyplni&#357; <strong>Steam &#269;&iacute;slo</strong>')\"" : "" ).' value="'.( $userdata['m_liga'] ? "Vypn&uacute;&#357;" : "Zapn&uacute;&#357;" ).'"/></td>
		</td>	
	</table>';
	/*
	echo Buttons::CheckBox('dr', 'green', false, false).'<br />';
	echo Buttons::CheckBox('dr1', 'green', true, false).'<br />';
	echo Buttons::CheckBox('dr2', 'green', true, true).'<br />';
	echo Buttons::CheckBox('dr3', 'green', false, true).'<br />';
	echo Buttons::CheckBox('dr4', 'orange', false, true).'<br />';
	echo Buttons::CheckBox('dr5', 'default', false, false).'<br />';
	echo Buttons::CheckBox('dr6', 'default', true, true).'<br />';
	*/
	nastavenia_footer();
}
