<?
define('MAX_HLASOV', 1);
define('NEHLASUJ', -1);
define('HLAS_AKTIVITA', 100000); // kolko sec povazujeme za plnu aktivitu....
define('HLAS_AKTIVITA_DNI', 6); // na kolko dn isa to vztahuje
define('DEFAULT_TYP', 1); // na kolko dn isa to vztahuje

$dnesok = date("Y-m-d", Time::$TIME);
$minulost = date("Y-m-d", Time::$TIME - Time::Dni(HLAS_AKTIVITA_DNI));

function hlasovanie_cihlasoval($data)  {
	/* Tak ako chceme halsovanie aby islo kazde zvlast,
		alebo konstatne na jedno
		alebo vsetke spolu
		
		$data['cihlas_0']
		$data['cihlas_1']
		$data['cihlas_2']
	*/	
	// Defaultne asi dame 
	return $data->cihlas_0;
}
function hlasovanie($typ, $id, $kolko, $hlasov=0, $nadpis_hore=true)   {		
	// Upravujeme
	$kolko = $kolko <= 0 ? 0 : $kolko;
	$kolko = ($kolko > 5.0) ? 5.0 : round($kolko, 2);
	$img = ROOT.'web2/images/aktivita/';
	$title = 'Priemer: '.number_format($kolko, 2);
	
	// Header
	echo '<div id="'.$typ.'-'.$id.'" class="hlasovanie cursor">'; // onmouseover="hlas(0, this);" 
	if($nadpis_hore) echo '<span class="info_gray">Hodnotenie:</span>'.$kolko.'<br/>';
	/*
		$hlasov je kolko krat uzivatel hlasoval
		Urcuje sa tu maximum ak dame 3 moze hlasovat 3x	
		Ak dame -1 == NEKONECNO_HALSOV hlasuje sa donekonecna....
	*/
	$mozme_hlasovat = ($hlasov < MAX_HLASOV AND $typ!=NEHLASUJ) ? true : false;
	
	//Vypisujeme 
	for($i=1; $i <= 5; $i++)  {
		if($kolko < 0.5) {
			hlasovanie_img($img, 1, $mozme_hlasovat, $i, $title);            
		} elseif($kolko < 1.0) {
			hlasovanie_img($img, 2, $mozme_hlasovat, $i, $title);         
		} else {
			hlasovanie_img($img, 3, $mozme_hlasovat, $i, $title);
		}		
		$kolko -= 1.0;	
	}
	echo '<br />';
	if(!$nadpis_hore) echo '<span class="info_gray">'.$title.'</span>';
	echo '</div>';
}
function hlasovanie_img($img, $a, $mozme, $i, $title) {
    echo '<img src="', $img, $a, '.gif" ', 
    ( $mozme ? 'class="'.$a.'"' : ''),
    ' alt="*" id="', $i, '" title="', $title, 
    '" border="0" align="absmiddle" />';
}
function hlasovanie_sql_head() {
	return " 
	`cihlas_0`, `cihlas_1`, `cihlas_2`,
	(  	(
			(COALESCE(`hlas_0`, 0)+COALESCE(`hlas_1`, 0) +COALESCE(`hlas_2`, 0)) *2 
		) / 7
	) as `znamka`";
	/*return " 
	`cihlas_0`, `cihlas_1`, `cihlas_2`,
	(  	(
			(COALESCE(`hlas_0`, 0)+COALESCE(`hlas_1`, 0) +COALESCE(`hlas_2`, 0)) *2 
			+ IF(`akt` > 5.0 , 5.0,  IF(`akt`< 0.0 , 0.0, COALESCE(`akt`, 0)) )
		) / 7
	) as `znamka`";*/
}	
function hlasovanie_sql_body() {
	global $typ, $user, $dnesok, $minulost;		
	return hlasovanie_sql_body2('u.user_id'); 
		/*."									
		LEFT JOIN (  SELECT `plrid`, `uniqueid` FROM `psychostats`.`ps_plr`  ) ps
			on `u`.`cs_meno` = `ps`.`uniqueid` COLLATE utf8_slovak_ci												
		LEFT JOIN (  SELECT `plrid`,  COALESCE((( SUM(`onlinetime`)*5 ) / ".HLAS_AKTIVITA."), 0) as akt  FROM `psychostats`.`ps_plr_data` WHERE  `statdate` < '".$dnesok."' AND `statdate` > '".$minulost."' GROUP BY `plrid` )  online
			on `ps`.`plrid` = `online`.`plrid`	";	*/
}
function hlasovanie_sql_body2($tbl) {
	global $typ, $user;
	$txt='';
	for($i=0; $i < count($typ); $i++) {
		$txt .= "
		LEFT JOIN ( SELECT id, SUM(kolko) / COUNT(id) as `hlas_".$i."` FROM `phpbanlist`.`web2_hlasovanie` WHERE typ='".$typ[$i]."' GROUP BY id ) `h_".$i."`
			on ".$tbl." = `h_".$i."`.`id`
		LEFT JOIN ( SELECT id, COUNT(id) as `cihlas_".$i."` FROM `phpbanlist`.`web2_hlasovanie` WHERE typ='".$typ[$i]."' AND user='".$user."' GROUP BY id ) `ci_".$i."`
			on ".$tbl." = `ci_".$i."`.`id`";
	}
	return $txt;	
}
function hlas_znamka($id, $typ) {
	global $dnesok, $minulost;
	
	$sqltxt = "SELECT (  	
		(
			(COALESCE(`hlas_0`,0)+COALESCE(`hlas_1`,0)+COALESCE(`hlas_2`,0)) *2 
			+ IF(`akt` > 5.0 , 5.0,  IF(`akt`< 0.0 , 0.0, COALESCE(`akt`, 0)) )
		) / 7
	) as `znamka` FROM `cstrike`.`fusion_users` u ";
	for($i=0; $i < count($typ); $i++) {
		$sqltxt .= 
			"LEFT JOIN ( SELECT id, SUM(kolko) / COUNT(id) as `hlas_".$i."` FROM `phpbanlist`.`web2_hlasovanie` WHERE typ='".$typ[$i]."' GROUP BY id ) `h_".$i."`
				on `u`.`user_id` = `h_".$i."`.`id`";
	}
	$sqltxt .= "									
	LEFT JOIN (  SELECT `plrid`, `uniqueid` FROM `psychostats`.`ps_plr`  ) ps
		on `u`.`cs_meno` = `ps`.`uniqueid` COLLATE utf8_slovak_ci												
	LEFT JOIN (  SELECT `plrid`, SUM(`onlinetime`) as `onlinecas`,( SUM(`onlinetime`)*5  / ".HLAS_AKTIVITA.") as `akt`  FROM `psychostats`.`ps_plr_data` WHERE  `statdate` < '".$dnesok."' AND `statdate` > '".$minulost."' GROUP BY `plrid` )  `online`
		on `ps`.`plrid` = `online`.`plrid`
	WHERE user_id='".$id."'";

	$sql = DB::One($sqltxt);
	return $sql[0];
}
function hlasov($typ, $id) { 
	return DB::One("SELECT SUM(kolko) / COUNT(id) FROM `phpbanlist`.`web2_hlasovanie` WHERE typ='".$typ."' AND id='".$id."'");
}
function web2_hlasuj($typ2, $id, $user_id, $i, $pocet, $shop, $maxhlasov = 0) {
	if(!is_numeric($this->typ2) or !is_numeric($i)) ajax_exit();                    
	if($i < $pocet[0] or $i > $pocet[1]) ajax_exit();
	$txt = "SELECT COUNT(id) AS pocet FROM `phpbanlist`.`web2_hlasovanie` WHERE `typ`='".$this->typ2."' AND `id`='".$id."' AND `user`='".$user_id."'";
	$pocet = DB::One($txt);
	 // Max. pocet hlasov na osobu
	if($pocet > $maxhlasov ) return false;                    
	// Posleme
	DB::Query("INSERT INTO `phpbanlist`.`web2_hlasovanie` (`typ`, `id`, `user`, `kolko`) VALUES ('".$this->typ2."', '".$id."', '".$user_id."', '".$i."')");
	// Obchod
	Shop::Kup($user_id, $shop);
	return true;
}
function web2_deletehlasy($typ, $id) {
	DB::Query("DELETE FROM `phpbanlist`.`web2_hlasovanie` WHERE `typ`='".$typ."' AND id`='".$id."'");
}
/*unction rank_hlasovanie($id, $meno, $hlasov) 
{
	$nazov = array('Kult&uacute;ra', 'Objekt&iacute;vnos&#357;', 'Asertivita');
	$nahoda= random(1, 3);
	hlasovanie(1, $id, hlasov_vseobecne($id, $meno), $hlasov, 'Hlasuj ');			
}
unction hlasov($typ, $id, $user)
{ 
	// Nacitame udaje
	$sql = dbquery("SELECT ( SELECT COUNT(id) FROM `phpbanlist`.`web2_hlasovanie` WHERE typ='".$typ."' AND id='".$id."' AND user='".$user."' LIMIT 1 ) as uzivatel,
					SUM(kolko) / COUNT(id) as pocet
					FROM `phpbanlist`.`web2_hlasovanie` WHERE typ='".$typ."' AND id='".$id."' ");
	return mysql_fetch_row($sql);
}
unction hlasovanie_aktivita($aktivita) {
	$aktivita = ($aktivita * 5 ) / HLAS_AKTIVITA;
	if($aktivita > 5.0) { $aktivita=5.0; }
	else if($aktivita < 0.0) { $aktivita=0.0; }
	return round($aktivita, 2); 
}*/			
?>