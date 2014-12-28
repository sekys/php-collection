<?
require_once($_SERVER["DOCUMENT_ROOT"].'/globals.php');  
Ajax::Start();

Input::issets('widget', 'item'); 

switch($widget) {
    case "random" : 
    {
        $sql = DB::Query("SELECT g.*, COALESCE(komentarov, 0) as komentarov
            FROM `cstrike`.`galeria_item` g
                LEFT JOIN ( SELECT comment_item_id, COUNT(comment_item_id) AS komentarov FROM `cstrike`.`fusion_comments` WHERE comment_type = 'G' GROUP BY comment_item_id ) c                                    
                    on g.id = c.comment_item_id   
                ORDER BY RAND() LIMIT 3");
        
        $foto = new GaleriaItem();
        while($foto->Next($sql)) {
            echo '
            <div class="preview_item">
                <div id="',  $foto->group, '" class="preview_id">';
                $foto->ImgAName2();
                echo '</div>';
                $foto->PreviewTools2();
            echo '</div>';
        }
        break;    
    }
    case "reccomend" : 
    {
        
        break;    
    }
    case "shop" : 
    {
        $objekt = new Cache('ajax_galeria_'.$item, 600);
        $objekt->Zlozka('ajax');
        $objekt->ClientCacheTO();
        if($objekt->File()) {        
            if(is_numeric($item)) {
                $id = $item;
            } else { 
                $id = 1;
                $objekt->cant();
            }
            $pocet = DB::One("SELECT COUNT(id) FROM `cstrike`.`galeria_shop`");           
            if($id > $pocet) $id = $pocet;
            if($id < 1) $id = 1;
            
            $id--;
            @$sql = DB::Query("SELECT id, meno, img, COALESCE(komentarov, 0) as komentarov FROM `cstrike`.`galeria_shop` g
                                    LEFT JOIN ( SELECT comment_item_id, COUNT(comment_item_id) AS komentarov FROM `cstrike`.`fusion_comments` WHERE comment_type = 'G' GROUP BY comment_item_id ) c                                    
                                        on g.id = c.comment_item_id
                                    ORDER BY g.id LIMIT ".$id.", 1 ");
            $data = $sql->fetch_row();
            $id++;
            
            echo '    
            <a href="', ROOT, 'galeria/?type=shop&id='.$id.'">', $data[1], '
                <img class="img" src="', $data[2], '" width="150" height="120">
            </a>
            <div class="control">
            	<div class="l">
                    <a href="javascript:galeria(', ( $id==1 ? $pocet : $id-1 ), ');"><img align="absmiddle" src="', ROOT, 'web2/images/tool/left.gif" border="0" title="Dal&scaron;&iacute;" alt="Dal&scaron;&iacute;"></a>
                    ',  $id , '/', $pocet, ' 
                    <a href="javascript:galeria(', ( $id==$pocet ? 1 : $id+1 ), ');"><img align="absmiddle" src="', ROOT, 'web2/images/tool/right.gif" border="0" title="Sp&auml;&#357;" alt="Sp&auml;&#357;"></a>
            	</div>
                <div class="r">', $data[3], ' <img border="0" alt="Koment&aacute;rov" title="Koment&aacute;rov" src="', ROOT, 'web2/images/tool/comments3.png"/></div>
			</div>';
        }
        $objekt->File();
    }
    default : {
        $objekt->cant(); 
        break;
    }
} 
