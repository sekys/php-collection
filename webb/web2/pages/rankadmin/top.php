<?
function rank_toprank() {
    Header::Title('Rank-Admin');
    $objekt = new Cache('rank_top_nocache', 30);
    $objekt->Zlozka('rank');
    $objekt->OutputFunction();  
}
function rank_top_nocache() {
    global $typ, $user, $dnesok, $minulost;
    Header::Title('Rank-Admin'); 
    
    echo '
    <table cellspacing="0" cellpadding="0" width="100%" id="rankadmin">
        <tr>
            <td colspan="2" />';
            rank_top_pozicie();                 
            echo '
            </td>
        <tr>
            <td colspan="2" />
        </tr>
        <tr>';            
        // Nahodne server zvolime a cele na druhu
        $sql_server = DB::Query("SELECT id, nazov FROM `cstrike`.`servers` ORDER BY RAND() LIMIT 2");
        $data = new Member;
                            
        for($i=0; $i < 2; $i++) {                            
            echo '<td width="50%" valign="top">';
            if($server = $sql_server->fetch_row()) {
                /*$objekt = new Cache(10, 'rankadmin_topserver'.$server[0]);
                $objekt->SubZlozka('page/rank');
                if($objekt->File()) { */
                    $Rank = 0;
                    $sql = rank_server_sql($server[0], 10);
                    echo '
                    <div class="rank_server_head" >
                        <span class="rank_servermeno">', $server[1], '</span>
                    </div>';                        
                    while($data->Next($sql)) {
                        $Rank++;
                        rank_rank($data, false, $Rank);           
                    }
                /*}
                $objekt->File(); */   
            } 
            echo '</td>';
        }                    
        echo '    
        </tr>
    </table>';
}
function rank_top_sql() {
    // Fiiiha aky query xD
    Debug::Oblast('RANKADMIN_SQL');
    // TODO: nazov / server_name zistovat neskor z amxid -> serverid -> nazov
    // Ak vysledny pocet viac ako 1 -> Admin na viacerych serveroch
    $sqltxt = "
    SELECT DISTINCT user_id, user_name, user_avatar, vip, slot, `sb`.`nazov`, 
    ";
    $sqltxt .= hlasovanie_sql_head();
    // TODO: Tu maju byt vsade len JOIN, ale kedze admini a fusion_user neje synchronizovane
    // WARNING: Pri admin servers nemoze byt LIMIT 1 lebo nahodou vytiahne to kde nic nieje
    $sqltxt .= " FROM `phpbanlist`.`amx6_amxadmins` a
    	JOIN ( SELECT user_name, user_id, user_avatar, vip, slot, amxid, cs_meno FROM `cstrike`.`fusion_users` ) u
            ON a.id = u.amxid
        JOIN ( SELECT admin_id, server_id FROM `phpbanlist`.`amx6_admins_servers` ) sa
            on `a`.`id` = `sa`.`admin_id`     
        JOIN ( SELECT amx, nazov FROM `cstrike`.`servers` LIMIT 1 ) sb
            on `sa`.`server_id` = `sb`.`amx`
    ";
    $sqltxt .= hlasovanie_sql_body();
    $sqltxt .= " ORDER BY znamka DESC LIMIT 3";
    $sql = DB::Query($sqltxt);
    Debug::Oblast('RANKADMIN_SQL');
    //echo $sqltxt;
    return $sql;
}
function rank_top_pozicie() {
    /*$objekt = new Cache(10, 'rankadmin_toppozicie');
    $objekt->SubZlozka('page/rank');
    if($objekt->File()) { */     
        $sql = rank_top_sql();
		$data = new Member;
        for($i=1;  $i<= 3; $i++ ) {
            echo '<div class="rank_hore"';
            if($i == 1) {
                $data->next($sql);
                $temp = $data->GetAll();
                $rank = 2; 
                $data->next($sql);
                echo 'style="padding-top: 20px;">';
            } else if($i == 3) {
                $data->next($sql);
                echo 'style="padding-top: 40px;">';
                $rank = 3; 
            } else {
                $data->Set($temp);
                $rank = 1;
                echo '>';
            }                                         
            if($data->exist()) {
                echo '<div class="rank_obrazok" style="background-image:url(', ROOT, 'web2/images/rank/', $rank, 'th_place.png)"></div>
                    <div class="rank_ciara"> </div>';
                        rank_user_panel($data);
                    echo '
                    <div class="rank_server">
                        <span class="info_gray"> ', $data->nazov, ' </span>
                    </div>';
            }   
            echo '</div>';
        } 
    /*}
    $objekt->File(); */
}
?>