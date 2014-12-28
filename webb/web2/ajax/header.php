<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/globals.php');  
Ajax::Start();

Input::issets('widget', 'item', 'id');
$objekt = new Cache('ajax_header.php'.$widget.$item.$id, 120);
$objekt->Zlozka('ajax');
$objekt->ClientCacheTO();
$objekt->FullFile();

switch($widget) {
    case 'Portal' : {      
        switch($item)
        {
	        //================================================================================================================================================
	        // Novinky
	        case "novinky": {
		        $result = DB::Query(
			        "SELECT news_id, LEFT(news_subject, 50) as `news_subject`, news_sticky, news_reads  
			        FROM `fusion_news` ORDER BY `news_id` DESC LIMIT 7");
		        if(@$result->num_rows != 0) {
			        while($news = $result->fetch_assoc()) {
				        if (1 == $news['news_sticky']) { 
					        $subject1 = $news['news_subject'];
					        $subject2 = "<span style='color:orange'>".$subject1."</span>";
				        } else {
					        $subject1 = $subject2 = $news['news_subject'];
				        }
				        GButtons::abingo('novinka/'.$news['news_id'].','.STR::uri_out($subject1).'/', 
                                $subject1, 
                                '<span class="papier"></span> '.$subject2.' <span class="info_gray">('.$news['news_reads'].')</span>'
                        );
			        }
		        } else {
			        Mess::Warning('&#381;iadne novinky nen&aacute;jden&eacute;.');
		        }
		        break;
	        }

	        // Navody / clanky
	        case "navody": {
		        $result = DB::Query(
			        "SELECT article_id, article_cat, article_subject, article_reads, article_datestamp, article_cat_access, article_cat_id 
			        FROM fusion_articles ta
			        INNER JOIN fusion_article_cats tac ON ta.article_cat=tac.article_cat_id
			        WHERE article_cat_access='0' ORDER BY article_datestamp DESC LIMIT 7"
		        );
		        if (@$result->num_rows != 0) {
			        while($data = $result->fetch_assoc()) {
				        GButtons::abingo2( 'clanok/'.$data['article_id'].','.STR::uri_out($data["article_subject"]).'/',
                            $data['article_subject'], 
                            $data['article_reads']
                        );
			        }
		        } else {
			        Mess::Warning('&#381;iadne &#269;l&aacute;nky nen&aacute;jden&eacute;.');
		        }
		        break;
	        }

	        //Ankety
	        case "anketa": {
		        $result = DB::Query("SELECT * FROM `cstrike`.`fusion_polls` ORDER BY poll_started DESC LIMIT 1");
		        if($result->num_rows != 0) {
			        $data = $result->fetch_assoc();
			        $iMEMBER = isset($_COOKIE['fusion_user']) ? true : false; // staci takto ... nic neidentifikujeme
			        $poll_title = $data['poll_title'];
			        for ($i=0; $i<=9; $i++) {
				        if ($data["poll_opt_".$i]) $poll_option[$i] = $data["poll_opt_".$i];
			        }
			        if ($iMEMBER) {
				        $result2 = DB::Query("SELECT * FROM `cstrike`.`fusion_poll_votes` WHERE vote_user='".$userdata['user_id']."' AND poll_id='".$data['poll_id']."'");
			        }
				    $i = 0; 
				    $num_opts = count($poll_option);
				        
			        echo "
			        <table width='200' cellspacing='0' align='center' cellpadding='0' border='0'>		
				        <tr>
					        <td class='info_gray'>			
						        <p align='center'><b>$poll_title</b></p>
							        <div id='anketa_voteoption'>";		
						        
			        if ((!$iMEMBER || !$result2->num_rows) and $data['poll_ended'] == 0) 
			        {			
				        while ($i < $num_opts) {
					        echo "<input type='radio' name='voteoption' value='$i'> $poll_option[$i]<br>\n";
					        $i++;
				        }
				        // Hlasujeme cez ajax
				        echo "</div>
				        <center>";
				        if ($iMEMBER) {
					        echo "<input type='submit' onclick=\"javascript:return anketa(".$data['poll_id'].");\" name='cast_vote' value='Hlasuj' class='button'>";
				        } else {
					        echo "* Mus&iacute;&scaron; sa prihl&aacute;si&#357; *";
				        }
				        echo"</center>";
			        } else {			
				        $poll_votes = DB::One("SELECT COUNT(vote_opt) as pocet FROM `cstrike`.`fusion_poll_votes` WHERE poll_id='".$data['poll_id']."'");
				        while ($i < $num_opts) {
					        $num_votes = DB::One("SELECT COUNT(vote_opt) as pocet FROM `cstrike`.`fusion_poll_votes` WHERE vote_opt='$i' AND poll_id='".$data['poll_id']."'");
					        $opt_votes = ($poll_votes ? round(100 / $poll_votes * $num_votes, 1) : 0);
					        echo "<div>".$poll_option[$i]."</div>
					        <div>
						        <img src='".ROOT."images/theme/pbar-ani.gif' alt='".$poll_option[$i]."' height='12' width='$opt_votes' class='poll'> ".$opt_votes."% 
					        </div>";
					        $i++;
				        }
				        echo "</div><br>	
					        <center>Hlasovalo ".$poll_votes." &#318;ud&iacute; od ".date("j.n", $data['poll_started']);
				        if ($data['poll_ended'] > 0) {
					        echo " do ".date("j.n", $data['poll_ended'])."";
				        }
				        echo "</center>";
			        }
			        //  <div id="anketa_ajax"> </div>
                    echo '
					        </td>
				        </tr>
			        </table>';
		        } else {
			        Mess::Warning('&#381;iadne akt&iacute;vne ankety nen&aacute;jden&eacute;.');
		        }
		        break;
	        }

	        // Download
	        case "download": {
		        $result = DB::Query("SELECT LEFT(`download_title`, 50) as `download_title`, `download_id`, `download_count` FROM `fusion_downloads` ORDER BY `download_count` DESC LIMIT 7");
		        if (@$result->num_rows != 0) {
                    while($data = $result->fetch_assoc()) {
                        GButtons::abingo2(
                        	'downloads.php?download_id='.$data['download_id'], 
                            $data['download_title'], 
                            $data['download_count']
                        );
			        }
		        } else {
			        Mess::Warning('&#381;iadne downloady nen&aacute;jden&eacute;.');
		        }
		        break;
	        }

	        // Forum
	        case "forum": {
		        $result = DB::Query("
			        SELECT * FROM fusion_threads
			        INNER JOIN fusion_forums ON fusion_threads.forum_id=fusion_forums.forum_id
			        WHERE forum_access='0' ORDER BY thread_lastpost DESC LIMIT 7");
		        if (@$result->num_rows != 0) {
			        while($data = $result->fetch_assoc()) {		
				        GButtons::abingo2( 'forum/viewthread.php?forum_id='.$data['forum_id'].'&amp;thread_id='.$data['thread_id'], 
                            $data['thread_subject'],
                            $data['thread_views']
                        );	
			        }
		        } else {
			        Mess::Warning('&#381;iadne >pr&iacute;spevky nen&aacute;jden&eacute;.');
		        }
		        break;
	        }
            default : {
                $objekt->cant();
                break;
            }          
        } 
        break;
    }        
	case 'Liga' : {      
        switch($item)
        {        
	        //================================================================================================================================================						
	        //Vyzvy
	        case "vyzvy": {
		        @$sql_vyzva = DB::Query("SELECT c.id, LEFT(c.meno, 35) as `meno`, v.datum, v.server, c.narod FROM `phpbanlist`.`acp_vyzva` v
										        LEFT JOIN ( SELECT id, meno, narod FROM `phpbanlist`.`acp_clans` ) c 
											        ON v.ziada = c.id
									        WHERE prijal IS NULL AND sukromna = 0 ORDER BY datum LIMIT 7");
		        
		        if($sql_vyzva->num_rows != 0)
		        {		 
                    while($sql_vyzva->fetch_assoc()) 
			        { 			
				        if(!$vyzva['meno']) $vyzva['meno'] = '-';				        		
				        if(!$vyzva['server']) $vyzva['server'] = '0';
				        $vyzva['meno'] = $vyzva['meno'];
				        GButtons::abingo2( 'cup/vyzva/'.date("Y-m-d\/H", $vyzva['datum']).'/'.$vyzva['server'].'/', 
                                '<img src="'.ROOT.'cup/styles/styles_web2/vlajka_'.$vyzva['narod'].'.gif" alt="N&aacute;rodnos&#357; clanu" title="N&aacute;rodnos&#357; clanu" align="absmiddle" hspace="5" border="0"/>', 
                                $vyzva['meno'],
                                '<img height="16" width="16" border="0" title="Prija&#357;" alt="Prija&#357;" src="'.ROOT.'cup/styles/styles_web2/cup_ok.gif" align="middle" />'
                        );	
			        }
		        } else {
			        echo Mess::Warning('&#381;iadne v&yacute;zvy nen&aacute;jden&eacute;.');
		        }
		        break;
	        }

	        // Stavky
	        case "stavky": {
		        @$sql_vyzva = DB::Query("SELECT v.id, v.ziada, v.prijal, LEFT(c.menoz, 35) as `menoz`, LEFT(h.menop, 35) as `menop`, narodz, narodp, k.stavky_prijal, k.stavky_ziada FROM `phpbanlist`.`acp_vyzva` v
										        LEFT JOIN ( SELECT id, meno as menoz, narod as narodz FROM `phpbanlist`.`acp_clans` ) c ON v.ziada = c.id
										        LEFT JOIN ( SELECT id, meno as menop, narod as narodp FROM `phpbanlist`.`acp_clans` ) h ON v.prijal = h.id	
										        JOIN ( SELECT * FROM `cstrike`.`kurzy` ) k on v.id = k.id
									        WHERE v.prijal IS NOT NULL ORDER BY v.id DESC LIMIT 3");
						        
		        if($sql_vyzva->num_rows != 0) {		
			        while($vyzva = $sql_vyzva->fetch_assoc())		
			        {			
				        $vyzva['menoz'] = $vyzva['menoz'];
                        GButtons::abingo2( 'cup/vyzva/'.$vyzva['id'].'/', 
                                '<img src="'.ROOT.'cup/styles/styles_web2/vlajka_'.$vyzva['narodz'].'.gif" alt="N&aacute;rodnos&#357; clanu" title="N&aacute;rodnos&#357; clanu" align="absmiddle" hspace="5" border="0"/>', 
                                $vyzva['menoz'],
                                '<img height="16" width="16" border="0" title="Prija&#357;" alt="Prija&#357;" src="'.ROOT.'cup/styles/styles_web2/cup_ok.gif" align="middle" />',
                                '<span class="info_gray">'.$vyzva['stavky_ziada'].'</span>'
                        );				       
                        GButtons::abingo2( 'cup/vyzva/'.$vyzva['id'].'/', 
                                '<img src="'.ROOT.'cup/styles/styles_web2/vlajka_'.$vyzva['narodz'].'.gif" alt="N&aacute;rodnos&#357; clanu" title="N&aacute;rodnos&#357; clanu" align="absmiddle" hspace="5" border="0"/>', 
                                $vyzva['menop'],
                                '<img height="16" width="16" border="0" title="Prija&#357;" alt="Prija&#357;" src="'.ROOT.'cup/styles/styles_web2/cup_ok.gif" align="middle" />',
                                '<span class="info_gray">'.$vyzva['stavky_prijal'].'</span>'
                        );
			        }
			        //  Iba 7 cize jedno mozme pridat ...
			        echo '<p align="right"><a href="'.ROOT.'cup/vyzvy/" class="info_gray"> &raquo; &#270;al&scaron;ie</p>';
		        } else {
			        echo Mess::Warning('&#381;iadne v&yacute;zvy nen&aacute;jden&eacute;.');
		        }
		        break;
	        }

	        // Aktualny zapas + status serveru 
	        case "zapasy": {		        
                $cas = mktime(date("H"), 0, 0, date("n"), date("j"), date("Y"));
		        $result = DB::Query("SELECT server FROM `phpbanlist`.`acp_vyzva` WHERE `datum` = '".$cas."' LIMIT 1");
		        if($result->num_rows) {
                    $data = $result->fetch_row();
			        ServerStatus::Render( $data[0] ? 4 : 5);
		        } else {
			        echo Mess::Warning('Moment&aacute;lne sa nehr&aacute; z&aacute;pas.');
		        }	
		        break;
	        }

	        // Historia / posledne zapasy
	        case "historia": {	
		        $sql_vyzva=DB::Query("SELECT ziada_skore, prijal_skore, datum, server, ziadatag, prijaltag, COALESCE(komentarov, 0) as komentarov FROM `phpbanlist`.`acp_zapas` h
									        JOIN ( SELECT id, tag as ziadatag FROM `phpbanlist`.`acp_clans` ) z
										        on h.ziada = z.id								
									        JOIN ( SELECT id, tag as prijaltag FROM `phpbanlist`.`acp_clans` ) p
										        on h.prijal = p.id
									        LEFT JOIN ( SELECT comment_item_id, COUNT(comment_item_id) as komentarov FROM `cstrike`.`fusion_comments` WHERE comment_type = 'Z' GROUP BY comment_item_id ) c	
										        on h.id = c.comment_item_id
									        ORDER BY h.datum DESC LIMIT 7");
		        if($sql_vyzva->num_rows != 0) {
                    while($vyzva=$sql_vyzva->fetch_assoc()) { 
		            echo '	
			            <a class="bingo" href="'.ROOT.'cup/zapas/'.date("Y-m-d \/H", $vyzva['datum']).'/'.$vyzva['server'].'/">
							<img align="absmiddle" src="'.ROOT.'web2/images/tool/csko.png" alt="Z&aacute;pas" title="Z&aacute;pas" hspace="5" border="0"/> 
						    ', htmlentities($vyzva['ziadatag'], ENT_QUOTES).' vz '.htmlentities($vyzva['prijaltag'], ENT_QUOTES).'<span class="info_gray">('.$vyzva['komentarov'].')</span>
						    <span class="r"><span class="'.( $vyzva['ziada_skore'] > $vyzva['prijal_skore'] ? 'team_t' : 'team_ct' ).'">'.$vyzva['ziada_skore'].'</span> : <span class="'.( $vyzva['ziada_skore'] < $vyzva['prijal_skore'] ? 'team_t' : 'team_ct' ).'">'.$vyzva['prijal_skore'].'</span></span>
			            </a>';	
		            }
                } else {
                    echo Mess::Warning('&#381;iadne z&aacute;pasy nen&aacute;jden&eacute;.');
                }
		        break;
	        } 
            default : {
                $objekt->cant();
                break;
            }          
        } 
        break;
    }
	case 'Servery' : {      
        switch($item)
        {       
            case 'server' : {
            //================================================================================================================================================
	        // Servery - jednoduchystatus serverov
                if(is_numeric($id)) ServerStatus::Render($id);              
                break;
            }        
	        // Ventrilo 
	        case "ventrilo": {		
		        echo '	
		        <div class="header-server">
					<img src="'.ROOT.'web2/images/theme/ventrilo.jpg" alt="Ventrilo" title="Ventrilo" width="128" height="128" />
				    <div class="info">
						Ventrilo<br><strong>178.162.190.230:3777</strong><br>
				    </div>
		        </div>';
		        break;
	        }
	        default : {
		        $objekt->cant();
		        break;
	        }
        }
        break;
    }
    default : {
        $objekt->cant();
        break;
    }
}

