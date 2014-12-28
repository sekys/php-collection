<?
class GaleriaTheme extends GaleriaFunc
{
	private static function Comments($sid, $id) {
		$c = new Comments;
		$c->Fusion("G".$sid, 0, 0, $id, ROOT.'galeria/?type=shop&id='.$id);
	}
	protected static function AlbumTheme(GaleriaAlbum $album) {
		echo '<td class="side-border-left">';
		
		echo '</td>'; 
		echo '<td valign="top" class="main-bg">';

		$sql = DB::Query("SELECT g.*, COALESCE(komentarov, 0) as komentarov
							FROM `cstrike`.`galeria_item` g
								LEFT JOIN ( SELECT comment_item_id, COUNT(comment_item_id) AS komentarov FROM `cstrike`.`fusion_comments` WHERE comment_type = 'GI' GROUP BY comment_item_id ) c                                    
									on g.id = c.comment_item_id   
							WHERE `group`='".$album->id."'");
			
		self::Zoznam($sql);
		self::Comments('A', $album->id);
		
		echo '</td>'; 
		echo '<td width="200" valign="top" class="side-border-right" ';
		if(self::$right) call_user_func(self::$right);
		echo '</td>';
	}
	protected static function Zoznam($sql) {
		$foto = new GaleriaItem;
		while($foto->Next($sql)) {
			$foto->Render();
		}
	}
	protected static function ItemTheme(GaleriaItem $item) {
		$item->Render2();   
		self::Comments('I', $item->id);
	}
	protected static function LastTheme($sql) {
		self::Zoznam($sql);
	}
	protected static function ShopTheme($data, $id, $pocet) {
		// TODO: Prerobit na ITEM
		self::Header();     
		echo '<a href="'.$data[3].'"><br>
		<img src="'.$data[2].'" width="150" height="120" style="border:1px solid #cccccc;">
		</a><br><br>
		<a href="'.ROOT.'galeria/'.( $id==1 ? $pocet : $id-1 ).'/"><img align="absmiddle" src="'.RIMAGE.'tool/left.gif" border="0" title="Dal&scaron;&iacute;" alt="Dal&scaron;&iacute;"></a>
		'. $id .'/'.$pocet.' 
		<a href="'.ROOT.'galeria/'.( $id==$pocet ? 1 : $id+1 ).'/"><img align="absmiddle" src="'.RIMAGE.'tool/right.gif" border="0" title="Sp&auml;&#357;" alt="Sp&auml;&#357;"></a>
		<br>';
		self::Comments('S', $data[0]);
		self::Footer();	
	}
	protected static function UserAlbumsTheme($sql, $user) {
		$foto = new GaleriaAlbum; 
		while($foto->Next($sql)) {
			$foto->Render();
		}
		self::Comments('U', $id);
	}
	protected static function UserTheme($sql, $user) {
		self::Zoznam($sql);
	}
}