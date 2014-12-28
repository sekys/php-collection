<?php
class Galeria extends GaleriaTheme 
{	
	public static function Album($id) 
	{
		$sql = "SELECT * FROM `galeria_groups` WHERE id`='".$id."'";                     
		$album = new GaleriaAlbum($sql);
		if(!$album->exist) return Error('Album nen&aacute;jden&yacute;.');
		$error = $album->CheckPrivate();
		if($error) return self::Error($error);
		self::Header($album->name);
		self::AlbumTheme($album);		
	}
	public static function Item($id) 
	{
		$sql = "SELECT g.*, COALESCE(komentarov, 0) as komentarov
			FROM `cstrike`.`galeria_item` g
				LEFT JOIN ( SELECT id, name, visible FROM `cstrike`.`galeria_groups` ) a                                   
					on g.group = a.id          
				LEFT JOIN ( SELECT comment_item_id, COUNT(comment_item_id) AS komentarov FROM `cstrike`.`fusion_comments` WHERE comment_type = 'GI' GROUP BY comment_item_id ) c                                    
					on g.id = c.comment_item_id
				WHERE id`='".$id."'"; 
		$item = new GaleriaItem($sql);							
		if(!$item->exist()) return self::Error('Obr&aacute;zok nen&aacute;jden&yacute;.');
		$error = GaleriaAlbum::CheckPrivate2($item->visible);
		if($error) return self::Error($error);
		self::Title($item->name);
		self::ItemTheme($item);
	}
	public static function Last() 
	{                               
		self::Title('Posledne pridan&eacute;');
		$sql = DB::Query("SELECT g.*, COALESCE(komentarov, 0) as komentarov
			FROM `cstrike`.`galeria_item` g
				LEFT JOIN ( SELECT id, name, visible FROM `cstrike`.`galeria_groups` ) a                                   
					on g.group = a.id          
				LEFT JOIN ( SELECT comment_item_id, COUNT(comment_item_id) AS komentarov FROM `cstrike`.`fusion_comments` WHERE comment_type = 'GI' GROUP BY comment_item_id ) c                                    
					on g.id = c.comment_item_id  
			WHERE g.group IS NULL OR a.visible IN (".self::GaleriaAlbum::GetPrivate().")
			ORDER BY id DESC LIMIT 10");			
		self::LastTheme($sql);
	}
	public static function Shop($tovar) {
		# Ohodnotit v galerii tovar / obrazok 
		//- ustredne funkcie na hlasovanie
		$pocet = DB::One("SELECT COUNT(id) FROM `cstrike`.`galeria_shop`");
		if($id > $pocet) $id = $pocet;
		if($id < 1) $id = 1;
		$id--;
		@$sql = DB::Query("SELECT * FROM `cstrike`.`galeria_shop` ORDER BY id LIMIT ".$id.", 1");
		$data = $sql->fetch_row();
		$id++;
		self::Title($data[1]); 
		self::ShopTheme($data, $id, $pocet);
	}
	public static function UserAlbums($id) 
	{
		$sql = DB::Query("SELECT user_id, user_name, user_avatar, user_icq, user_groups FROM `cstrike`.`fusion_users` WHERE user_id='".$id."'");                       
		if(!$sql->num_rows) return self::Error('U&#382;iva&#357;e&#318; nen&aacute;jden&yacute;....');   
		$user = new Member;
		$user->Next($sql); 
		self::Title($user->user_name.' albumy');
		
		$sql = DB::Query("SELECT g.*, komentarov, fotiek,
				FROM `galeria_groups` g
					LEFT JOIN ( SELECT comment_item_id, COUNT(comment_item_id) AS komentarov FROM `cstrike`.`fusion_comments` WHERE comment_type = 'GA' GROUP BY comment_item_id ) c                                    
						on g.id = c.comment_item_id  
					LEFT JOIN ( SELECT id, COUNT(id) AS fotiek FROM `cstrike`.`galeria_item` GROUP BY id ) i                                    
						on g.id = i.group  
				WHERE `owner`='".$id."' AND `visible` IN (".GaleriaAlbum::GetPrivate($id).") )		
				");
		
		// Cez ajax nacitavat dalsie obrazky v zozname,...
		self::UserAlbumsTheme($sql, $user);
	}
	public static function function User($id) 
	{
		$sql = DB::Query("SELECT user_id, user_name, user_avatar, user_icq, user_groups FROM `cstrike`.`fusion_users` WHERE user_id='".$id."'");                       
		if(!$sql->num_rows) return self::Error('U&#382;iva&#357;e&#318; nen&aacute;jden&yacute;....');   
		$user = new Member;
		$user->Next($sql); 
		self::Title($user->user_name.' gal&eacute;ria');
		
		$sql = DB::Query("SELECT g.*, COALESCE(komentarov, 0) as komentarov
			FROM `cstrike`.`galeria_item` g
				LEFT JOIN ( SELECT id, name, visible FROM `cstrike`.`galeria_groups` ) a                                   
					on g.group = a.id          
				LEFT JOIN ( SELECT comment_item_id, COUNT(comment_item_id) AS komentarov FROM `cstrike`.`fusion_comments` WHERE comment_type = 'GI' GROUP BY comment_item_id ) c                                    
					on g.id = c.comment_item_id  
			WHERE g.owner='".$id."' AND 
			( g.group IS NULL OR a.visible IN (".GaleriaAlbum::GetPrivate($id).") )
			ORDER BY id DESC LIMIT 10");
		
		// Cez ajax nacitavat dalsie obrazky v zozname,...
		self::UserTheme($sql, $user);
	}
	
	
	
	
	protected static function Error($txt) {
		echo Mess:Alert($txt);
		return false;
	}
	protected static function Title($txt) {
		Header::Title($txt);
	}
}

?>