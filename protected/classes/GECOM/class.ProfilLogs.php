<?php

class ProfilLogs
{
    const LOGS_NAHLAD = 70;
	protected static $VCERA;
	
    public static function StaticInit() {
		self::$VCERA = Time::$DNESOK - Time::Dni(1);
    }
    public static function Render($sqltxt, $userstring)
    {
	    // Header ....
	    $sql = DB::Query($sqltxt);
        if(!$sql->num_rows) return 0;
        $staridatum = '1';
        $cas = 0;
	    while($data = $sql->fetch_row()) { // jedine array povolene :(
		    $cas = $data[6];
		    $datum = self::ParseCas($cas);	    
		    if( $datum != $staridatum) {
				// Ak ohodnotenie stareho a dalsieho casu nieje
				// rovnake tak napis parser
				self::Separator($datum);
		    }
		    self::Item($userstring, $cas, self::Type($data));
	    }
        return $cas; // vrati automaticky poslednu hodnotu casu..
    }
    protected static function ParseCas($cas) {
	    if($cas > Time::$DNESOK) {
		    return 'dnes';
	    } elseif($cas > self::$VCERA) {
		    return 'v&#269;era';
	    } else {
	    	return date("Y-m-d", $cas);
	    }
    }
    protected static function Separator($header) {
	    echo '<li class="date-separator">', $header, '</li>';
    }
    public static function Item($userstring, $cas, $temp)
    {
	    echo '
	    <li class="clearfix friend">
		    <div class="activity-type ', $temp[0], '">
			    <div class="activity-description clearfix">
				    <p class="clearfix">
					    <b>', $userstring, ' ', $temp[1], '</b>  <span class="date">', date("H:i", $cas), '</span>
				    </p> 
				    ', $temp[2], '	
			     </div>  
		    </div>
	    </li>';
    }
    protected static function Type($data)
    {
	    /*	Musime vratit 4 hodnoty
		    - css objekt
		    - uvodna veta
		    - dodatocne HTML
	    */		
	    switch($data[0])
	    {
		    case 1: { 
			    $item[0] = "icon-comment";
			    $item[1] = self::TypeComment($data[4], $data[3]);				
			    $item[2] = "<div><p>".self::string($data[5])."</p></div>";
			    break;	
		    }
		    /*case 2: { 
			    $item[0] = "icon-forum";
			    $item[1] = "odpovedal v t&eacute;me <a href='".ROOT."".$data[2]."-".$data[1]."'>".$data[4]."</a>";
			    $item[2] = "<div><p>".self::string($data[5])."</p></div>";
			    break;	
		    }	*/	
		    case 3: { 
			    $item[0] = "icon-forum";
			    $item[1] = "odpovedal v t&eacute;me <a href='".ROOT."".$data[2]."-".$data[1]."'>".$data[4]."</a>";
			    $item[2] = "<div><p>".self::string($data[5])."</p></div>";
			    break;	
		    }
		    default : { 
			    $item[0] = "";
			    $item[1] = "...nie&#269;o sa udialo";
			    $item[2] = "";
			    break;	
		    }
	    }
	    return $item;
    }
    public static function string($txt) {
	    if(strlen($txt) > self::LOGS_NAHLAD) {
		    $txt .= ' ...'; // pridame akoze dalej inak nie	
	    }
	    // Usporiadanie ako chceme ....
	    $txt = STR::OUT($txt);
	    /*$txt = str::smiley($txt);
	    $txt = str::bbcode($txt);
	    $txt = nl2br($txt);
        $txt = utf8_decode($txt);
	    $txt = str_replace("\\n", "<br />", $txt);*/
	    return $txt;
    }
    protected static function TypeComment($pismeno, $id)
    {	
	    switch($pismeno)
	    {
		    case 'N' : {
			    $data = DB::One("SELECT news_subject FROM `fusion_news` WHERE `news_id` ='".$id."'");
			    $str = 'komentoval novinku <a href="'.ROOT.'novinka/'.$id.'/">'.$data.'</a> .';
			    break;
		    }		
		    case 'Z' : {
			    @$sql = DB::Query("SELECT tag_a, tag_b FROM `phpbanlist`.`acp_zapas` z
									    LEFT JOIN ( SELECT id, tag as tag_a FROM `phpbanlist`.`acp_clans` ) a
										    on z.ziada = a.id
									    LEFT JOIN ( SELECT id, tag as tag_b FROM `phpbanlist`.`acp_clans` ) b
										    on z.prijal = b.id
								    WHERE z.id = '".$id."'");
			    $data = $sql->fetch_row(); 		
			    $str = 'komentoval <a href="'.ROOT.'cup/zapas/'.$id.'/">'.$data[0].'::'.$data[1].'</a> z&aacute;pas.';
			    break;
		    }		
		    case 'K' : {
			    $data = DB::One("SELECT user_name FROM `cstrike`.`fusion_users` WHERE user_id='".$id."'");
			    $str = 'komentoval <a href="'.ROOT.'kandidovat/kandidat/'.$data[0].'/">'.$data[0].'</a> kandid&aacute;ta.';
			    break;
		    }		
		    case 'A' : {
			    $data = DB::One("SELECT article_subject FROM `fusion_articles` WHERE `article_id` ='".$id."'");
			    $str = 'komentoval <a href="'.ROOT.'clanok/'.$id.'/">'.$data[0].'</a> .';
			    break;
		    }		
		    case 'G-' : {
			    $str = 'komentoval <a href="'.ROOT.''.$temp[0].'">fsafafa</a> obr&aacute;zok.';
			    break;
		    }			
		    case 'R' : {
			    $data = DB::One("SELECT user_name FROM `cstrike`.`fusion_users` WHERE user_id='".$id."'");
			    $str = 'komentoval <a href="'.ROOT.'rank-admin/'.$data[0].'/">'.$data[0].'</a> v admin-ranku.';
			    break;
		    }
		    default: {
			    $str = 'komentoval nie&#269;o.';
			    break;
		    }
	    }
	    return $str;
    }
    public static function MaxPocetALeboDni($pocet, $max_dni, $id, $userstring) {
		$a = new PLSql;
	    $a->id = $id;
	    $a->a = Time::$TIME;
	    $a->b = Time::$TIME - Time::Dni($max_dni);
	    $a->limit = $pocet;
		$a->All();	
		return self::Render($a->Build(), $userstring);		
    }
    public static function MaxPocet($pocet, $time, $id, $userstring) {
		$a = new PLSql;
	    $a->id = $id;
	    $a->a = $time;
	    $a->limit = $pocet;
		$a->All();
			
		return self::Render($a->Build(), $userstring);
    }
}
/*
	<li class="clearfix friend">
		<div class="activity-type icon-levelup">
			<div class="activity-description clearfix">
				<p class="clearfix"><b>
					<a class="s" href="/users/Sen/">Sen</a> 
					has reached Level 32!  <span class="date">5:57 pm</span>
				</p>       						 
			 </div>  
		</div>
	</li>
										
	<li class="clearfix friend">
		<div class="activity-type icon-vid-comment">
			<div class="activity-description clearfix">
				<p class="clearfix"><b>
					<a class="s" href="/users/Sen/">Sen</a> 
					commented on a video: </b>
					<span class="date">5:04 pm</span>
				</p>       						            						       
				<div>
					<a href="/watch/wendon-2500-3v3-lumberjack-cleave/#comment-463129" class="thumb">   	            					                															
						<img alt="Wendon 2500 3v3 Lumberjack Cleave" src="http://s3-llnw-videos.wegame.com/248371/248371-170x128.jpg"/>																														
					</a>																												
					<div class="activity-desc comment-block">
					   <a href="/watch/wendon-2500-3v3-lumberjack-cleave/#comment-463129">Wendon 2500 3v3 Lumberjack Cleave</a>
						<p>I dont prefer healer POV and I do prefer fancy edż/p>
					</div>														
				</div>
																													
			</div>  
		</div>
	</li>
	
	<li class="clearfix friend">
		<div class="activity-type icon-forum">
			<div class="activity-description clearfix">
				<p class="clearfix"><b>
					<a class="s" href="/users/Sen/">Sen</a>
					 added a forum post to <a href="/forums/General-Gaming-Discussion/sad-fayce-osu-ruined-my-entire-night/page1/#post-462505">Sad Fayce.... osu! ruined my entire night...</a>:</b>
					<span class="date">7:48 pm</span>
				</p>       						            						        
				<div><p>I love this song but osu kinda ruins shit :P</p></div>            		    					        		    					
			</div>  
		</div>
	</li>

										
	<li class="clearfix friend">
		<div class="activity-type icon-comment">
			<div class="activity-description clearfix">
				<p class="clearfix"><b>
					<a class="s" href="/users/Sen/">Sen</a>
						 commented on a screenshot:</b>
						<span class="date">7:14 pm</span>
				</p>       						            						        
				<div>
					<a href="/view/soldier-vs-demo/#comment-461829" class="thumb">               						                															
						<img alt="Soldier VS Demo" src="http://s3-llnw-screenshots.wegame.com/9-4639605641777562/4639605641777562_thumb_s.jpg"/>																														
					</a>
																					
					<div class="activity-desc comment-block">
						<a href="/view/soldier-vs-demo/#comment-461829">Soldier VS Demo</a>
						<p>if soldier wins, soldier loses. let the demomen wiż/p>
					</div>													
				</div>
																													
			</div>  
		</div> 
	</li>

		<div class="featured-sm">
			<a href="/view/soldier-vs-demo/" class="thumb">
				<span class="stats">
					<strong>42</strong> <span>Likes</span>
				</span>
				<img alt="Soldier VS Demo" src="http://s3-llnw-screenshots.wegame.com/9-4639605641777562/4639605641777562_thumb_s.jpg"/>
			</a>
																							
			<div class="activity-desc">
				<a href="/view/soldier-vs-demo/">Soldier VS Demo</a>
				<p>halolz</p>
				<a href="/games/tf2/" class="game icon clearfix">
					<img alt="" src="http://database-images.wegame.com/game/4/24.jpg?t=1470073"/>
					<span class="v-center-wrapper">
						<span class="v-center-middle">
							<span class="v-center-object">Team Fortress 2</span>
						</span>
					</span>
				</a>
			</div>
		</div>
*/		
?>
