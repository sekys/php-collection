<?

class GaleriaControl
{
	public static function AddAlbum() {
	
	
	}	
	protected static function AddAlbumPost($data) {
	
	
	}
	public static function DeleteAlbum() {
	
	
	}	
	protected static function DeleteAlbumPost($id) {
	
	
	}
	public static function UpdateAlbum() {
	
	
	}	
	protected static function UpdateAlbumPost($data) {
	
	
	}
	
	/* Items */
	
	public static function AddItem() {
	
	
	}
	protected static function AddItemPost($data) {
	
	
	}
	public static function DeleteItem() {
	
	
	}
	protected static function DeleteItemPost($id) {
	
	
	}
	public static function UpdateItem() {
	
	
	}
	protected static function UpdateItemPost($data) {
	
	
	}
	
	
		function kategorie($query='ORDER BY i.id DESC LIMIT 10') {
		// Vyberame
		$sql = mysql_query2("SELECT user, meno, i.kat as kat, g.meno as popis, url, i.typ as typ, i.id as id FROM `cstrike`.`web2_galeria` g
								LEFT JOIN ( SELECT id, kat, url, typ FROM `cstrike`.`web2_galeria_item` ORDER BY typ ASC LIMIT 1 ) i
									on g.id = i.kat
							 ".$query);		
		$this->galeria_header($sql);
		$this->kategoria_add();	
		$this->kategoria_delete();	
		$this->galeria_footer();		
	}
	function kategoria_add() {	
		// Vytvarame kategoriu
		$this->_kategoria_add(); 
		
		// Struktura formularu
		$this->panel_add(' <span class="ui-icon ui-icon-document-b"> </span> Vytvori&#357; nov&uacute; gal&eacute;riu', 
		'
		<form method="post" action="'.$this->href.'">
			<table width="100%" id="galeria_upload" border="0" cellspacing="10" cellpadding="0">
				<tr>
					<td align="right" valign="middle"> N&aacute;zov </td>
					<td> <input name="kategoria_meno" type="text" size="25" maxlength="47"></td>
				</tr>
				<tr>
					<td align="center" colspan="2"> <input type="submit" name="kategoria_add" value="Submit" > </td>
				</tr>
			</table>
			<br><br><br><br>
		</form>');
	}	
	function _kategoria_add() {	
		// POST udaje
		if(!isset($_POST['kategoria_add'])) return false;
		if(!isset($_POST['kategoria_meno'])) {
			web2_alert('Gal&eacute;ria', 'Nezadal si <strong>n&aacute;zov </strong>novej gal&eacute;rie.');
			return false;
		}
		
		$meno = mysql_clear($_POST['kategoria_meno']);
		$dlzka = strlen($meno);
		if($dlzka < 5 or $dlzka > 31) {
			web2_alert('Gal&eacute;ria', '<strong>N&aacute;zov </strong>mus&iacute; ma&#357; <strong>5</strong> a&#382; <strong>32</strong> znakov. ');
			return false;
		}
		
		// ok posielame
		mysql_query2("INSERT INTO `cstrike`.`web2_galeria` (`typ`, `user`, `meno`) VALUES ('".$this->typ."', '".$this->user."', '".$meno."')");
		// na FTP 
		mkdir(GALERIA_S_ZLOZKA."user_".$this->user."/cat_".mysql_insert_id(), 0700);
	}	
	function kategoria_delete() {
		// POST
		$this->_kategoria_delete();
		
		// STRUKTURA 
		$this->panel_add(' <span class="ui-icon ui-icon-trash"> K&ocirc;&scaron;</span> K&ocirc;&scaron;', 
		'	
		<div id="trash"> </div>
		<br>
		<div align="center" >
			<form method="post" action="'.$this->href.'">
				<input type="hidden" name="kategoria_delete" value="0" />
				<input type="hidden" name="zlozka_delete" value="0" />	
				<input type="submit" name="kategoria_add" value="Submit" >
			</form>
		</div>');
	}
	function _kategoria_delete()  {			
		// Vymazeme kategoriu
		if(!isset($_POST['kategoria_delete'])) return false;
		
		// Moze odstranit viacero kategorii naraz
		$data = explode(',', $_POST['kategoria_delete']);
		
		// Vytvarame SQL prikaz
		$zoznam = ''; 
		foreach($data as $i) {
			if(is_numeric($i)) {
				$zoznam .= "'".$i."',"; 
			}
		}			
		// Pofix na ciarku
		$zoznam = substr($zoznam, 0, -1);		
		// Zistujeme ci sa nikto nepokusa o hack - filtrujeme IDcka
		$sql = mysql_query2("SELECT id FROM `cstrike`.`web2_galeria` WHERE `id` IN (".$zoznam.") AND `user`='".$this->user."' AND `typ`='".$this->typ."'");
		$zoznam = '';
		while($udaj = mysql_fetch_row($sql)) {
			// Roby sa novy zoznam
			$zoznam .= "'".$udaj[0]."',"; 
			// file_delete(GALERIA_S_ZLOZKA.'user_'.$this->user.'/cat_'.$udaj[0]); // subory sa mazu jednotlivo
		}

		// Pofix na ciarku
		$zoznam = substr($zoznam, 0, -1);
		
		// + Vsetke obrazky v nej
		mysql_query2("DELETE FROM `cstrike`.`web2_galeria` WHERE id IN (".$id.")");
		mysql_query2("DELETE FROM `cstrike`.`web2_galeria_item` WHERE kat IN (".$id.")");
		return true;
	}
	  /* function:  generates thumbnail */
function make_thumb($src,$dest,$desired_width) {
    /* read the source image */
    $source_image = imagecreatefromjpeg($src);
    $width = imagesx($source_image);
    $height = imagesy($source_image);
    /* find the "desired height" of this thumbnail, relative to the desired width  */
    $desired_height = floor($height*($desired_width/$width));
    /* create a new, "virtual" image */
    $virtual_image = imagecreatetruecolor($desired_width,$desired_height);
    /* copy source image at a resized size */
    imagecopyresized($virtual_image,$source_image,0,0,0,0,$desired_width,$desired_height,$width,$height);
    /* create the physical thumbnail image to its destination */
    imagejpeg($virtual_image,$dest);
}

/* function:  returns files from dir */
function get_files($images_dir,$exts = array('jpg')) {
    $files = array();
    if($handle = opendir($images_dir)) {
        while(false !== ($file = readdir($handle))) {
            $extension = strtolower(get_file_extension($file));
            if($extension && in_array($extension,$exts)) {
                $files[] = $file;
            }
        }
        closedir($handle);
    }
    return $files;
}

/* function:  returns a file's extension */
function get_file_extension($file_name) {
    return substr(strrchr($file_name,'.'),1);
}
function zlozka_user($user, $order='ORDER BY id DESC LIMIT 50') {
		return zlozka_id("( SELECT id FROM `cstrike`.`web2_galeria` WHERE user='".$user."' AND typ='".$this->typ."')", $order);				
	}
	function zlozka_kat($id, $order='ORDER BY id DESC LIMIT 50') {
		$sql = mysql_query2("SELECT * FROM `cstrike`.`web2_galeria_item` WHERE kat=".$id." ".$order);
		return zlozka($sql);
	}
	function zlozka($sql) {									
		$this->galeria_header($sql);
		//$this->zlozka_delete();
		//$this->zlozka_add();
		$this->galeria_footer();
	}
	function zlozka_delete() {
		// POST
		$this->_zlozka_delete();		
		// STRUKTURA
		$this->panel_add('Vymaza&#357; gal&eacute;riu', 
		'
		<form method="post" action="'.$this->href.'">
			<input type="hidden" name="kategoria_delete" value="0" />
			<input type="hidden" name="zlozka_delete" value="0" />	
			<input type="submit" name="kategoria_add" value="Submit" >
		</form>');
	}
	function _zlozka_delete()  {	
		// Vymazeme nejake obrazky
		if(!isset($_POST['zlozka_delete'])) {
			return false;
		}
		// Moze odstranit viacero kategorii naraz
		$data = explode(',', $_POST['zlozka_delete']);
		
		// Vytvarame SQL prikaz
		$zoznam = ''; 
		foreach($data as $i) {
			if(is_numeric($i)) {
				$zoznam .= "'". $i."',"; 
			}
		}	
		
		// Pofix na ciarku
		$zoznam = substr($zoznam, 0, -1);
		
		// Zistujeme ci sa nikto nepokusa o hack - filtrujeme IDcka
		$sql = mysql_query2("SELECT id FROM `cstrike`.`web2_galeria` WHERE `id` IN (".$zoznam.") AND `user`='".$this->user."' AND `typ`='".$this->typ."'");
		$zoznam = '';
		while($udaj = mysql_fetch_row($sql))
		{
			// Roby sa novy zoznam
			$zoznam .= "'".$udaj[0]."',"; 
			// file_delete(GALERIA_S_ZLOZKA.'user_'.$this->user.'/cat_'.$udaj[0]); // subory sa mazu jednotlivo
		}

		// Pofix na ciarku
		$zoznam = substr($zoznam, 0, -1);
		
		// + Vsetke obrazky v nej
		mysql_query2("DELETE FROM `cstrike`.`web2_galeria` WHERE id IN (".$zoznam.")");
		mysql_query2("DELETE FROM `cstrike`.`web2_galeria_item` WHERE kat IN (".$zoznam.")");
		return true;
	}
	function zlozka_add() {	
		// POST
		$this->_zlozka_add();		
		// Struktura
		echo '
		<p onclick="mShowMe(\'galeria_upload\');" class="bb cursor paddingtop"><strong>Prida&#357; do gal&eacute;rii </strong></strong></p>
		<form method="post" action="'.$this->href.'" ENCTYPE="multipart/form-data">
			<table width="500" id="galeria_upload" style="display:none;" border="0" cellspacing="10" cellpadding="0">
				<tr>
					<th colspan="2" align="center">
						<input type="radio" name="zlozka_typ" value="0">
						Obr&aacute;zok
						<input type="radio" name="zlozka_typ" value="1">
						Video
					</th>
				</tr>
				<tr>
					<td align="right" width="150">Adresa  : </td>
					<td><input name="zlozka_adresa" type="text" size="60" maxlength="60"></td>
				</tr>
				<tr>
					<td align="right">alebo uploadni ... </td>
					<td><input name="zlozka_upload" type="file" size="45" maxlength="45"></td>
				</tr>
				<tr>
					<td align="right">N&aacute;zov : </td>
					<td><input name="zlozka_meno" type="text" size="32" maxlength="32"></td>
				</tr>				
				<tr>
					<td align="right">popis : </td>
					<td><input name="zlozka_popis" type="text" size="47" maxlength="47"></td>
				</tr>
				<tr>
					<td align="right">&nbsp;</td>
					<td><input type="submit" name="zlozka_add" value="Submit" id="Submit"></td>
				</tr>
			</table>
		</form>';
	}	
	function _zlozka_add() {
		if(!isset($_POST['zlozka_add']))  return false;

		// Kontrolujeme MENO
		$meno = mysql_clear($_POST['zlozka_meno']);
		$dlzka = strlen($meno);
		if($dlzka < 5 or $dlzka > 47) {
			web2_alert('Gal&eacute;ria', '<strong>N&aacute;zov </strong>mus&iacute; ma&#357; aspo&#328; <strong>5</strong> a&#382; <strong>47</strong> znakov. ');
			return false;
		}
		
		// Kontrolujeme POPIS
		$meno = mysql_clear($_POST['zlozka_popis']);
		$dlzka = strlen($meno);
		if($dlzka < 5 or $dlzka > 47) {
			web2_alert('Gal&eacute;ria', '<strong>N&aacute;zov </strong>mus&iacute; ma&#357; aspo&#328; <strong>5</strong> a&#382; <strong>47</strong> znakov. ');
			return false;
		}
		
		// Teraz delime ci bude UPLOAD alebo len ADRESA
		if(isset($_POST['zlozka_adresa'])) {
			$url = $this->_zlozka_add_adress();
			$typ = $_POST['zlozka_typ'] ? 2 : 1;
		} else {
			$url = $this->_zlozka_add_upload();
			$typ = 0;
		}
		// Ak funkcie vratily error
		if(!$url) {
			return false;
		}
	
		// MYSQL query	
		mysql_query2("INSERT INTO `cstrike`.`web2_galeria_item` 
					(`kat`, `meno`, `url`, `typ`, `popis`) VALUES 
					('".$this->temp."', '".$meno."', '".$url."', '".$this->typ."', '".$popis."')");
		return true;
	}
	function _zlozka_add_adress() {
		/* adresa_V_poriadku($adresa, $data)	// ale co ked zada odkaz z youtobe ? ...bude nas image LOGA gecom a odkaz na video ....
		{
			if(strpos($adresa, 'http')===false){
				return false;
			}				
			$koncovka = explode('.', $adresa);
			$koncovka = $koncovka[ count($koncovka) - 1];
			
		}*/
	
		$meno = mysql_clear($_POST['meno']);
		if(strlen($meno) < 5) {
			web2_alert('Gal&eacute;ria', '<strong>N&aacute;zov </strong>mus&iacute; ma&#357; aspo&#328; <strong>5</strong> znakov. ');
			return false;
		}
	}
	function _zlozka_add_upload($meno) {	
		if(!$_POST['adresa'] and $_POST['upload']) {
			web2_alert('Gal&eacute;ria', 	'Mus&iacute;&scaron; zada&#357; <strong>adresu</strong> obr&aacute;zka:<br>
											<strong>napr</strong>. http://www.cs.gecom.sk/logo.png<br>
											alebo <strong>uploadni </strong>obr&aacute;zok <br>');
			return false;
		}
		
		// Kontrolujeme koncovku 
		/*$subor 
		
				    Simple way : Don't allow special chars in variables.Simple way : filter the dot "." 
			Another way : Filter "/" , "\" and "." .

		
		
		
		if($_POST['adresa']) {
			$adresa = mysql_vstup($_POST['adresa']);
			if(strpos($adresa, 'http') ) {
				web2_alert('Gal&eacute;ria', '<strong>N&aacute;zov </strong>mus&iacute; ma&#357; aspo&#328; <strong>5</strong> znakov. ');
				return false;
			}
			// rozmery
			
		} else {		
			// datovu velkost
			 unlink ("./31/hloupost.txt");
			
			if ($_FILES['hloupost']['size']>300) die ("Soubor je pøíliš velký ;-(");
			if (!is_file($_FILES['hloupost']['tmp_name'])) die ("Žádný soubor jste neuploadovali !!!");
			if (move_uploaded_file($_FILES['hloupost']['tmp_name'], "./31/hloupost.txt"))
			{
				echo "Soubor <B>".$_FILES['hloupost']['name']."</B> z Vašeho PC";
				echo " typu <B>".$_FILES['hloupost']['type']."</B>";
				echo " o velikosti <B>".$_FILES['hloupost']['size']."</B> bajtù";
				echo " byl na serveru uložen pod doèasným názevem <B>".$_FILES['hloupost']['tmp_name']."</B>";
				echo " a následnì zpracován. Obsah souboru je:<P><pre>";
				readfile ("./31/hloupost.txt");
				echo "</pre>";
			};
		}*/ 
		
		// posielame

		
		
		
		
		
		
		
		
		/*if(!isset($_POST['adresa']) or isset($_POST['upload']) ) {
			web2_alert('Gal&eacute;ria', 	'<strong>Videa</strong> sa <strong>nedaj&uacute;</strong> uploadova&#357;.<br>
											M&ocirc;&#382;e&scaron; na ne len prida&#357; odkaz v <strong>adrese</strong>
											');
			return false;
		} 
		
		// kontrolujeme koncovku
		$adresa = mysql_vstup($_POST['adresa']);
		$koncovka = explode('.', $adresa);
		$koncovka = $koncovka[ count($koncovka) - 1];
		
		if((strpos($adresa, 'http')===false) 
			or ($koncovka!='swf' )
		) {
			web2_alert('Gal&eacute;ria', 	'Nespr&aacute;vny form&aacute;t <strong>adresy </strong>alebo video m&aacute; zl&uacute; <strong>pr&iacute;ponu</strong>.<br>
											<strong>Povolen&eacute;</strong> pr&iacute;pony: swf, avi, mpg
											');
			return false;
		}*/
	}
		function pamet_free() {
		$maximum = ($this->user == 0) ? GALERIA_P_HDD : GALERIA_U_HDD;
		$pocet = 0;
		
		// Scitavame ...
		$sql = mysql_query2("SELECT id FROM `cstrike`.`web2_galeria` WHERE `user`='".$this->user."' AND `typ`='".$this->typ."'");
		while($data = mysql_fetch_row($sql)) {	
			$pocet += file_pamet(GALERIA_S_ZLOZKA.$data[0]);
		}
		
		// O kolko bytov to presiahol alebo kolko ma volne .......
		$pocet -= $maximum;
		return $pocet;
	}
}