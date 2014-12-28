<?
function ZombieModul()
{
	global $userdata;
	nastavenia_header('zombie', '<strong>Zombie modul</strong> '.je_aktivny($userdata['zombieid']));
	if($userdata['zombieid']) {
		echo Mess::Tip("
			Neodpor&uacute;&#269;a sa vyp&iacute;na&#357; tento modul, strat&iacute;&scaron; tak v&scaron;etke funkcie zombie banky na port&aacute;li.
			<br /><br />
			<strong>PS:</strong> Modul deaktivuje&scaron; odhl&aacute;sen&iacute;m v zombie banke.
			");
		echo '<br /><div align="right"><a href="/zombie-banka/">Prejs&#357; na stav zombie &uacute;&#269;tu  &raquo;</a></div>';	
	} else {	
		echo Mess::Tip("
		Zapnut&iacute;m tohto modulu z&iacute;skate mo&#382;nos&#357; spravova&#357; V&aacute;&scaron; &uacute;&#269;et v zombie banke. Posiela&#357; body, meni&#357; prihlasovacie &uacute;daje ale aj preh&#318;ad &uacute;&#269;tu....
		Modul prin&aacute;&scaron;a v&yacute;hody aj na servery.
		<br /><br />
		<strong>PS:</strong> Modul aktivuje&scaron; prihl&aacute;sen&iacute;m v zombie banke.
		");
		echo '<br /><div align="right"><a href="'.ROOT.'zombie-banka/">Prejs&#357; na zombie banku &raquo;</a></div>';
	}
        
    nastavenia_footer();				
}