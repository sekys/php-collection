<?

class Stavky
{

	public static $stavky = false;
		
		
	protected static function ZoznamStavkarovSQL($id) {
		return DB::Query('
			SELECT `user_name`,
			 	GROUP_CONCAT(DISTINCT `user_id`
                   	ORDER BY `vklad` DESC SEPARATOR ',' ) as `vklad`		
			FROM `cstrike`.`ticket` `t`
				JOIN ( SELECT `id`, `user`, `vklad` FROM `cstrike`.`tickety` ) `ty`
					on `t`.`id` = `ty`.`id`
				JOIN ( SELECT `user_id`, `user_name` FROM `cstrike`.`fusion_users` ) `u`
					on `ty`.`user` = `u`.`user_id`
			WHERE `zapas`='.$id.' 		
			LIMIT 50
		');
	}
	public static function ZoznamStavkarovTheme($id)
	{
		$sql = self::ZoznamStavkarovSQL($id);	
		$m = new Member;
		while($m->next($sql)) {
			echo '<a href="', $m->Link(), '">', $m->out('user_name'), '</a>(', $m->vklad, '), ';	
		}
	}
	public static function Start() {
	    $pocet = 0;
	    $stavky = array();
	    
	    // Kontrolujeme pocet ticketov
	    if(isset($_COOKIE['tickety'])) {
	        $stavky = explode(".", $_COOKIE['tickety']);
	        $pocet = count($stavky);
	    }
	    // Vytvarame oblast na stavky ....
	    if($pocet > 1 or self::$stavky) {
	    	sef::Render($pocet);
	    }
	}
	protected static function Render($pocet)
	{    
	    Resource::Js('stavky'); 
	    $tickety_id = '';
	    $kurz = 1;

	    echo '
	    <div id="stavky_p" align="right">
	        <div id="stavky_inner" class="malepismo">
	            <form class="stavky_innerposun" method="post" action="'.ROOT.'stavky/">
	                <div id="stavky_tip" class="color_red" >';
	    // oznamy        
	            if( $pocet < 2) {                    
	                echo '<strong>Tiket je pr&aacute;zdny</strong>';
	            }    
	    // vsetke tickety        
	            echo '</div>
	                <div id="stavky_tickety">';                        
	                                
	                if($pocet > 1) {                        
	                    for($i=1; $i < $pocet; $i++) {
	                        $data = explode("-", $stavky[$i]);
	                        // kontrola pred vstupom
	                        if( is_numeric($data[0]) and is_numeric($data[1]) )
	                        {
	                            // kontrola ci existuje
	                            $sql = mysql_query("SELECT ".( $data[1]==1 ? 'k.stavky_ziada' : 'k.stavky_prijal' ).", h.ziada_meno, f.prijal_meno  FROM `phpbanlist`.`acp_vyzva` c
	                                JOIN ( SELECT id as ziada_id, meno as ziada_meno FROM `phpbanlist`.`acp_clans` GROUP BY id ) h
	                                    ON c.ziada = h.ziada_id                                
	                                JOIN ( SELECT id as prijal_id, meno as prijal_meno FROM `phpbanlist`.`acp_clans` GROUP BY id ) f
	                                    ON c.prijal = f.prijal_id
	                                LEFT JOIN ( SELECT * FROM `cstrike`.`kurzy`) k
	                                    ON c.id = k.id    
	                                WHERE c.id = '".mysql_vstup($data[0])."'");
	                                
	                            $mysql = mysql_fetch_row($sql);
	                            if($mysql[0]) {    
	                                echo '                    
	                                <table width="100%" cellpadding="0" cellspacing="0" id="ticket-'.$data[0].'">
	                                    <tr>
	                                        <td width="10">
	                                            <a href="javascript:stavky_vymaz('.$data[0].');"><img align="absmiddle" border="0" onmouseout="this.src=\''.ROOT.'web2/images/delete.gif\'" onmouseover="this.src=\''.ROOT.'web2/images/delete_hover.gif\'" src="'.ROOT.'web2/images/delete.gif" title="Zmaza&#357;&quot; alt="X"></a>
	                                        </td>
	                                        <td align="right">';
	                                            if($data[1]==1) {
	                                                echo '<strong>'.mysql_vystup($mysql[1]).'</strong>-'.mysql_vystup($mysql[2]).'<br>';
	                                            } else {
	                                                echo mysql_vystup($mysql[1]).'-<strong>'.mysql_vystup($mysql[2]).'</strong><br>';            
	                                            }                            
	                                        echo 'Kurz: <span id="kurz-'.$data[0].'">'.$mysql[0].'</span>
	                                        </td>
	                                    </tr>
	                                </table>';
	                                $kurz *= $mysql[0];
	                                $tickety_id .= '.'.$stavky[$i];
	                            }
	                        }
	                    }
	                }
	    // ostatne            
	            echo '    
	                </div>    
	                <br>
	                <table width="100%" cellpadding="0" cellspacing="0">
	                    <tbody>
	                        <tr>
	                            <td><strong>Vklad </strong> - +</td>
	                            <td align="right"><input type="text" id="stavky_vklad" name="stavky_vklad" onchange="stavky_vyhra();" class="malepismo" value="100" size="8"></td>
	                        </tr>
	                        <tr>
	                            <td>V&yacute;hra</td><td align="right" id="stavky_vyhra" class="stavky_innerposun">'. round($kurz*100, 2) .'</td>
	                        </tr>    
	                        <tr>
	                            <td>Celkov&yacute; kurz</td><td align="right" id="stavky_kurz" class="stavky_innerposun">'.round($kurz, 2).'</td>
	                        </tr>                            
	                        <tr>                        
	                            <td align="center" colspan="2"> 
	                                <input class="malepismo" type="button" onclick="stavky_vymaz_all();" name="Submit" value="Zmaza&#357;" id="Submit" >
	                                <input class="malepismo" type="submit" name="Submit" value="Stavi&#357;" id="Submit" >                    
	                            </td>                                
	                        </tr>
	                    </tbody>                
	                </table>
	            </form>    
	        </div>
	        <div align="right"><a href="javascript:stavky_toggle()" id="stavky_close"> Zavrie&#357; </a></div>    
	    </div>';
	        
	    // Javascript tickety
	    if($pocet > 5) {
	        Header::Js('document.getElementById("stavky_p").style.position = \'absolute\';', 1);
	    }
	    setcookie("tickety", $tickety_id);
	    engine::js("
	        tickety_id = '".$tickety_id."';
	        celkovy_kurz = ".$kurz.";
	        stavky_toggle();");    
	}
}