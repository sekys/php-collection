<?	

function rank_user_panel($data, $style='rank_meno') {
	echo '
	<div class="rank_avatar">
		<img border="0" class="rank_avatar2" alt="', $data->user_name, '" width="50" height="50" ', $data->Avatar(), ' />
	</div>
	<div class="', $style, '">
		<span class="info_gray">Nick:</span> ', $data->Render($GLOBALS['href']), '<br/>';
		hlasovanie(DEFAULT_TYP, $data->user_id, $data->znamka, hlasovanie_cihlasoval($data));
	echo '</div>';
}
function rank_server_sql($id, $limit=3, $order='znamka') {
    Debug::Oblast('RANKADMIN_SQL');
    $sql = "SELECT DISTINCT user_id, user_name, user_avatar, vip, slot, 
    ".hlasovanie_sql_head()." FROM `phpbanlist`.`amx6_admins_servers` a
        JOIN ( SELECT user_name, user_id, user_avatar, vip, cs_meno, slot, amxid FROM `cstrike`.`fusion_users` ) u
            ON a.admin_id = u.amxid
        ".hlasovanie_sql_body()."    
    WHERE a.server_id = '".$id."'
    ORDER BY ".$order." DESC LIMIT ".$limit;
    $res = DB::Query($sql);
    Debug::Oblast('RANKADMIN_SQL'); 
    return $res;
}	
function rank_rank($data, $name, $rank) {
    echo '            
    <div class="rank_admin_pozadie"';
    if ($rank < 11 ) {    
        echo 'style="background-image:url(', ROOT, 'web2/images/rank/'.$rank.'th_place.png);">';
    } else {
        echo '>';
    }
    rank_user_panel($data, 'rank_adminmeno');
    echo '</div>';
    if($name) {      
        echo '<div class="rank_adminfooter"><span class="info_gray"> ', $name, ' </span></div>';
    }
    echo '</div>';
}		
?>