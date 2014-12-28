<?
function rank_user($id) {
    $id = BaseSTR::uri_in($id);
    $aid = DB::Vystup($id);
    Header::Title('Rank-Admin - '.$aid);
    
    $objekt = new Cache('user_'.$aid, 30);
    $objekt->Zlozka('rank');      
    if($objekt->File()) {     
        Debug::Oblast('RANKADMIN_SQL');
        $sql = DB::Query(rank_user_sql($id));
        if(!$sql->num_rows)    {
            $objekt->Cant();
            $objekt->File();
            Mess::Alert('Rank-Admin', 'Admin nen&aacute;jden&yacute;...');
            return false; 
        } 
        $data = new Member;
        $data->next($sql);
        rank_user_cache($data, $aid);
        Debug::Oblast('RANKADMIN_SQL');
    }
    $objekt->File();
} 
function rank_user_cache(Member $data, $aid)  
{        
    global $typ, $user;    
    $data->setout('cs_meno', 'user_name');
    echo '    
    <table cellspacing="0" cellpadding="0" width="100%" align="center">
        <tr>
            <td width="110" valign="top" align="center">
                <br /><br /><br /><br />
                <img border="0" alt="', $data->user_name, '" ', $data->avatar(), '/>
                <br /><br />
                V&scaron;eobecn&yacute; rank<br/>';
                hlasovanie(NEHLASUJ, $data->user_id, $data->znamka, NEHLASUJ, false);
                echo '    
            </td>
            
            <td width="250">
                <table width="100%">';
                $vek = DB::One("SELECT rokov as pocet FROM `cstrike`.`kandidati` WHERE `user`='".$data->user_id."'");
                rank_user_tr('Nick', $data->user_name);
                rank_user_tr('Hern&eacute; meno', $data->cs_meno);
                rank_user_tr('Meno', $data->Render);
                rank_user_tr('Bydlisko', $data->user_location);
                rank_user_tr('Vek', $vek ? $vek : '?');
                rank_user_tr('Celkovo nahral', Time::Rozdiel($data->onlinecas));
                rank_user_tr('Aktivita', $data->onlinecas);
                echo '
                </table>
            </td>
            
            <td width="20" />
            <td width="122">
                <table cellpadding="0" align="center" border="0">
                    <tr>
                        <td align="center">
                            Kult&uacute;ra<br />';
                            hlasovanie($typ[0], $data->user_id, $data->hlas_0, $data->cihlas_0, false);
                        echo '                        
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            Objekt&iacute;vnos&#357;<br />';
                            hlasovanie($typ[1], $data->user_id, $data->hlas_1, $data->cihlas_1, false);
                        echo '                        
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            Asertivita<br/>';
                            hlasovanie($typ[2], $data->user_id, $data->hlas_2, $data->cihlas_2, false);
                        echo '
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            Aktivita<br />';
                            // + tot nieje zabezspecene ale vo vnutri sa opravy....
                            hlasovanie(NEHLASUJ, $data->user_id, $data->akt);                            
                        echo ' 
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    
    
    <br /><br /><br />
    <table cellspacing="0" cellpadding="0" width="100%" align="center" class="rank_user_profil_box">
        <tr>
            <td class="info_gray" align="center">
                <a href="', ROOT, 'kandidovat/kandidat/', BaseSTR::uri_out($data->user_name), '/">Prejdi k &#382;iadosti</a> &middot; 
                <a href="', ROOT, 'hrac/', BaseSTR::uri_out($data->user_name), '/">Prejdi do profilu</a>  &middot; 
                <a href="/psychostats/?id=', BaseSTR::uri_out($data->cs_meno), '">Prejdi k &scaron;tatistyk&aacute;m </a>
            </td>
        </tr>    
    </table>               
    <br /><br /><br /><br /><br /><br />';
    rank_user_popis(); 
    echo '<br /><br /><br />';
    $c = new Comments;
    $c->Fusion("R", 0, 0, $data->user_id, ROOT.'rank-admin/'.$aid.'/', 500);
}
?>
