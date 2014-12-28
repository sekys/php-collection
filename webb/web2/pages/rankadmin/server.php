<?	
function rank_server($id) {
    $id = BaseSTR::uri_in($id);
    $sname = DB::Vystup($id);
    Header::Title('Rank-Admin - '.$sname); 
    
    $objekt = new Cache(20, 'server_'.$sname);
    $objekt->SubZlozka('page/rank');       
    if($objekt->File()) {
        $aid = DB::One("SELECT id FROM `cstrike`.`servers` WHERE nazov LIKE '%".DB::Vstup($id)."%'"); 
        if(!$aid)    {
            Mess::Alert('Rank-Admin', 'Server nen&aacute;jden&yacute;...');
            $objekt->Cant();
            $objekt->File();
            return false;
        }
        rank_server_cache($aid, $sname);      
    }
    $objekt->File();  
}
function rank_server_cache($aid, $sname) {
    global $user, $dnesok, $minulost;
    Debug::Oblast('RANKADMIN_SERVER');
    $sql = rank_server_sql($aid, 20);
                     
    echo '<div class="rank_servermeno">', $sname, '</div>
    <table cellspacing="0" cellpadding="0" width="100%" id="rankadmin">';
    $m= new Member;
	while($m->next($sql)) {
        echo '<tr>';
        rank_server_item($m);            
        // To iste po 2x            
        if( $m->next($sql) ) {           
            rank_server_item($m);
        } else {
            echo '<td width="50%" valign="top"> </td>';
        }
        echo '</tr>';     
    }               
    echo '</table>';
    Debug::Oblast('RANKADMIN_SERVER');
}
function rank_server_item($data) {
    static $Rank = 0;
    $Rank++;
    echo '<td width="50%" valign="top">';
    rank_rank($data, false, $Rank);
    echo '</td>';  
}
			
?>
