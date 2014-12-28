<?php	
require_once($_SERVER["DOCUMENT_ROOT"].'/globals.php');  
Ajax::Start(); 
Input::NumsA('id');

// Uzivatel ....
$p = new Member($id, 'user_name, user_id, zombieid');
$p->AFinded();
$akcia = isset($_GET['akcia']) ? $_GET['akcia'] : '';
	
switch($akcia) {
	case "galery" : {
		/*
			v strede oznam jeho galerii 
			pouzijeme G a prechadzame medzi kategoriamy
			v sidebare jeho najlepsie hodnotene screeny a obarzky su velke
		*/
		
		Debug::Oblast('GALERIA');
		$hrac = new Galeria(ROOT.'hrac/'.$p['user_name'].'/', 'A');
		$hrac->kategorie();
		Debug::Oblast('GALERIA');
		break;
	}
	case "stenanext" : {
        Input::NumsA('cas');	
		// Ostatne  
		$time = ProfilLogs::MaxPocet(1, $cas, $p->user_id, $p->Render());
		if($time > 0) Ajax::Js('PLogsTime = '.$time.';');
		//ProfilLogs::Plogs($p->user_id, $p->Render(), $cas);
		break;
	}
	case "zombie" : {
		// Vyberame udaje
		@$banka_sql = DB::Query("SELECT * FROM `phpbanlist`.`zp_bank` WHERE id='".$p->zombieid."'");
		$banka = $banka_sql->fetch_row();
		
		// Filtrujeme ...
		if(!$banka[0]) {
			Ajax::cExit('Zombie &uacute;&#269;et nen&aacute;jden&yacute;.');
		}
		require_once('/home/cstrike/www/web2/pages/profil/stavba.php');
		
		// Zombie header
		function profil_header() {
			global $banka;			
			// Vypis utctu
			echo '
			<table width="500" cellspacing="0" cellpadding="2" border="0"><tr><td><strong>'.DB::Vystup($banka[1]).' &uacute;&#269;et</strong></td></tr></table>
			<br>

			<table width="500" border="0" cellspacing="0" cellpadding="0">	
				<tr>
					<td rowspan="6">
						<img border="0" hspace="20" alt="Zombie banka" src="'.ROOT.'web2/images/Chart.png"/>
					</td>
				</tr>		
				<tr>
					<td>Vytvoren&yacute; : </td>
					<td>', $banka[5], '</td>
				</tr>		
				<tr>
					<td>Naposledy pou&#382;it&yacute;  : </td>
					<td width="100">', $banka[6], '</td>
				</tr>
				<tr>
					<td>Stav : </td>
					<td width="100">', $banka[3], ' bodov </td>
				</tr>		
				<tr>
					<td> VIP : </td>
					<td>', STR::Tobool($banka[4]), '</td>
				</tr>
			</table>
			<br><br><br><br>';
			profil_footer();
		}
		// Zombie chart dole .........
		function profil_footer()	
		{
			global $banka, $banka_sql;		

			// Udaje uz su vybrane :)
			$pocet = count($banka);
			if(!$pocet > 7) {
				echo Mess::tip('Data nen&aacute;jden&eacute;.');
				return false;
			}
			//echo print_r($banka);
			
			// Zaklad
			$data = 't:'; $mena = ''; $najvacsie = 0;	
			
			// Najvacsie cislo
			for($i=7; $i < $pocet; $i++) {
				if($najvacsie < $banka[$i]) {
					$najvacsie = $banka[$i];
				}
			}
			if(!$najvacsie) { 
				echo Mess::tip('Data nen&aacute;jden&eacute;.');
				return false; 
			}
			// Robime zoznam
			for($i=7; $i < $pocet; $i++) {
				$data .= Functions::Percenta($banka[$i], $najvacsie).',';					
				$banka_sql->field_seek($i);
                $finfo = $banka_sql->fetch_field()->name;
                $mena .= $finfo.'|';
			}
			
			// SUfix na ciarku
			$data = substr($data, 0, -1);
			//$mena = substr($mena, 0, -1);
			
			$next = '&chs=666x150&chxt=x,y,r,x&chxl=0:|'.$mena.'1:|20%|40%|60%|80%|100%|2:|20%|40%|60%|80%|100%&chtt=Vyvoj+bodov+podla+dni&chf=bg,s,ECF1F5&chd='.$data; //&chco=ffffff,ffffFF
			echo Chart::Google('lc', $next);
			/* 
				http://chart.apis.google.com/chart?cht=lc&chd=s:cEAELFJHHHKUju9uuXUc&chco=76A4FB&chls=2.0,0.0,0.0&chs=200x125&chg=20,50,3,3,10,20&chxt=x,y&chxl=0:|0|1|2|3|4|5|1:|0|50|100
				http://chart.apis.google.com/chart?chxt=x,y,r,x&chxl=0:|Jan|July|Jan|July|Jan|1:|0|50|100|2:|A|B|C|3:|2005|2006|2007&cht=lc&chd=s:cEAELFJHHHKUju9uuXUc&chco=76A4FB&chls=2.0&chs=200x125
			echo chart('ls', $data, $mena, '500x150', '&chco=4d89f9,c6d9fd&chxl=0:|Sep|Oct|Nov|Dec|1:||50|100&');
			*/
		}
		// ZOmbie chart v sidebare
		function profil_sidebar()
		{
			global $p, $banka;
			$x = Functions::Percenta($banka[3], mysql_pocet("SELECT SUM(amount) as pocet FROM `phpbanlist`.`zp_bank") );
			if(!$x) $x = 1; // chceme zeby aspon palicku ukazalo...
			echo Chart::Google('p3', '&chs=280x100&chd=t:100,'.$x.'&chf=bg,s,ECF1F5&chtt=Z+celkovo+bodov&chl=Celkovo|'.$p['user_name']);		
			echo '<br><br>';
			profil_sidebar_log();
		}
		// Zombie log na boku ...
		function profil_sidebar_log()
		{
			global $p;
			$sql = DB::Query("SELECT * FROM `cstrike`.`web2_logs` WHERE 
									kat='1' AND kto='".$p['user_id']."' AND typ IN ('12', '13', '14', '15', '20')					
								ORDER BY kedy DESC LIMIT 20");			
			
			echo '<table border="0" cellspacing="0" id="widget_logy" cellpadding="0" align="right" width="222">';	
			while($udaje = $sql->fetch_assoc())	{
				WebLog::MiniItem($udaje);
			}
			echo '</table>';
		}
		profil_stavba('zombie'); // how simple ;)
		break;
	}
	default : { break; }
}
?>		
