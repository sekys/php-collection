<?
class SLSClan 
{
	public static function Avatar($adresa) {
		return ($adresa) ? 'src="'.$adresa.'"' : 'src="'. SLS::$STYLE .'no_avatar.png"';
	}
	public static function ZoznamRank($rank) {
		$zoznam = 25; // je to const, po kolko sa zobrazuje v zozname TOP clanov
		if($rank > $zoznam) {
			$pocet = floor($rank / $zoznam) * $zoznam;
			$pocet .= '/';
		}	
		$vysledok = '<a href="'.SLSPlugins::Adresa(1).$pocet.'#miesto'.$rank.'" class="cup_form_text"> ';
		$vysledok .= ($rank < 4) ? '<img src="'.SLS::$STYLE.'miesto_'.$rank.'.png" alt="'.$rank.'." title="'.$rank.'. Miesto" border="0" align="absmiddle">' : $rank.'.';
		$vysledok .= '</a>';
		return $vysledok;
	}
	public static function Posta($id_clanu, $sprava, $subject, $vynimka = 0) {											
		$vynimka = ($vynimka) ? "AND user_id != '".$vynimka."'" : "";
		@$sql= SLS::Query2("SELECT user_id FROM `cstrike`.`fusion_users` WHERE clan_id ='".$id_clanu."' ".$vynimka."");
		while($temp = $sql->fetch_assoc()) {
			SLSUser::Posta($temp['user_id'],$sprava, $subject);
		}	
	}
	public static function ClanMeno($meno) {
		return ($meno) ? DB::Vystup($meno) : '<em>Clan nen&aacute;jden&yacute;</em>';
	}	
	public static function Zapasov($id) {
		return SLS::Count2("SELECT COUNT(id) as pocet FROM `phpbanlist`.`cup_zapas` WHERE ziada = '".$id."' OR prijal = '".$id."'");
	}	
	public static function Hracov($id) {
		return SLS::Count2("SELECT COUNT(user_id) as pocet FROM `cstrike`.`fusion_users` WHERE clan_id = '".$id."'");
	}
	public static function Exist($udaj) {
		if(!$udaj->num_rows) {
			echo SLS::MsgL('clan_nenajdeny',1);
			return false;
		}
		return true;
	}
	function OdhlasitZclanu($user, $clan, $hodnost) {
		if(!$clan) return true;	
		SLS::Log(-1, 9, $user, $clan);			
		if( $hodnost === UserHodnost::LEADER) { //clan leader
			@$sql_clan = DB::Query("SELECT user_id, clan_hodnost FROM `cstrike`.`fusion_users` WHERE clan_id ='".$clan."' AND user_id != '".$user."' ORDER BY RAND()");
			if( $sql_clan->num_rows > 0)  {	//nieje sam
				$hraci = array();
				while($row = $sql_clan->fetch_assoc()) {
					if($row['clan_hodnost'] === UserHodnost::ZASTUPCA ) { // ci maju zastupcu	
						DB::Query("UPDATE `cstrike`.`fusion_users` SET `clan_hodnost` = '".UserHodnost::LEADER."' WHERE user_id = '".$row['user_id']."'");
						return true; // ok koniec
					}
					$hraci[] = $row['user_id'];
				}	
				// Nenaslo nic ale mame este jedneho ...
				$pocet = count($hraci);
				if($pocet)	{
					DB::Query("UPDATE `cstrike`.`fusion_users` SET `clan_hodnost` = '".UserHodnost::LEADER."' WHERE user_id = '".$hraci[ rand(0, count($hraci)) ]."'");						
				}	
				unset($hraci);
			} else { // Tak Zmazeme clan
				self::Delete($clan);
			}					
		} else {	
			DB::Query("UPDATE `cstrike`.`fusion_users` SET `clan_hodnost` = '".UserHodnost::HRAC."' WHERE user_id = '".$user."'");
		}
		return true;
	}
	public static function Delete($clan) {
		$sql = DB::Query("SELECT meno FROM `phpbanlist`.`acp_clans` WHERE `acp_clans`.`id` = ".$clan."");
		$data = $sql->fetch_row();
		WebLog::Add(-1, 3, false, false, false, $data[0]);
		DB::Query("DELETE FROM `phpbanlist`.`acp_clans` WHERE `acp_clans`.`id` = ".$clan."");
		self::DeleteZapasi($clan);
	}
	public static function DeleteZapasi($clan) {
		DB::Query("DELETE FROM `phpbanlist`.`acp_vyzva` WHERE ziada ='".$clan."' OR prijal ='".$clan."'");	
	}
	public static function CheckNametag($meno , $tag) {
		$zakazane = array('?', '/', '\\', '"', "'", '<', '>', '%', '&');		
		$meno = trim($meno);
		$tag = trim($tag);
		
		if($meno and $tag)  {
			if(strlen($meno) >=3 and strlen($tag) >=3) {			
				// Konstrolujeme vstup ... :)
				foreach($zakazane as $znak) {
					if(!(strpos($meno, $znak) === false)) {
						echo SLS::Msg(SLSLang::Msg('clan_znamienka').$znak,1);
						return false;
					}
				}				
				// Sql kontrola 
				$pocet = ( User::$m->clan_id) ? " AND id != '". User::$m->clan_id."' " : '';		
				$pocet = SLS::Count2("SELECT COUNT(id) as pocet FROM `phpbanlist`.`acp_clans` WHERE `meno` LIKE '".DB::Vstup($meno)."' ".$pocet."");
				if($pocet) {
					echo self::MsgL('clan_obsadene_meno', 1);
					return false;
				}									
				return true;
			} else {
				echo self::MsgL('clan_tag', 1);
				return false;
			}
		} else {
			echo self::MsgL('ziadne_udaje');
			return false;
		}
	}
	public static function Rank($id) { // Zisti RANK clanu 
		return SLS::Count2("SELECT COUNT(`id`) as  pocet FROM `phpbanlist`.`acp_clans` WHERE `bodov` > 
							(  SELECT bodov FROM `phpbanlist`.`acp_clans` WHERE `id`='".$id."' )");
	}
	public static function Aktivita($aktivita) {
		$vysledok = "";
		if($aktivita >= 4) {
			$vysledok = '<img src="'.self::$adresy[3].'aktivita/4.gif" border="0" alt="4" align="absmiddle">';
			// Cyklime
			if($aktivita > 4 ) {
				$vysledok .= self::Aktivita( $aktivita - 4);
			}
		} elseif($aktivita <= 0) {
			$vysledok = '<img src="'.self::$adresy[3].'aktivita/0.gif" border="0"  alt="0" align="absmiddle">';
		} else {
			$vysledok = '<img src="'.self::$adresy[3].'aktivita/'.$aktivita.'.gif" border="0"  alt="'.$aktivita.'" align="absmiddle">';
		}
		return $vysledok;
	}
	public static function FindLeader($clan_id) {
		throw new Exception('prerobit');
		//return SLS::Count2("SELECT user_id as pocet FROM `cstrike`.`fusion_users` WHERE clan_id = '".$clan_id."' AND clan_hodnost = '".CLAN_LEADER."' ");
	}
	public static function Narod($id) {
		return '<img src="'.self::$adresy[3].'vlajka_'.$id.'.gif" alt="N&aacute;rodnos&#357; clanu" title="N&aacute;rodnos&#357; clanu"border="0" align="absmiddle">';
	}
}