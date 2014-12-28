<?php

/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright ? 2002 - 2006 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php"; 
Debug::Oblast('NEWS');

// Predefined variables, do not edit these values
if ($settings['news_style'] == "1") {  
    $rc = 0; 
    $ncount = 1; 
    $ncolumn = 1;  
}

// This number should be an odd number to keep layout tidy
require SPAGES.'news.php';
 
if (!isset($_GET['readmore'])) { 
	$m = new Zoznam;
    $m->list = 6;
    $rowstart = Input::Num('rowstart', 0);
    $m->actual = $rowstart;
    
	// ----- Cache objekt
	$objekt = new Cache('news_'. $m->actual, 180);
    $objekt->Zlozka('page');
	if($objekt->File()) {
	// ----- Cache objekt
	$m->celkovo = dbcount("(news_id)", "news", groupaccess('news_visibility')." AND (news_start='0'||news_start<=".Time::$TIME.") AND (news_end='0'||news_end>=".Time::$TIME.")");
	if ($m->celkovo != 0) {
		$result = DB::Query(
			"SELECT tn.*, tc.*, user_id, user_name FROM ".DB_PREFIX."news tn
			LEFT JOIN ".DB_PREFIX."users tu ON tn.news_name=tu.user_id
			LEFT JOIN ".DB_PREFIX."news_cats tc ON tn.news_cat=tc.news_cat_id
			WHERE ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".Time::$TIME.") AND (news_end='0'||news_end>=".Time::$TIME.")
			ORDER BY news_sticky DESC, news_datestamp DESC LIMIT $rowstart,$m->list"
		);		
		if ($settings['news_style'] == "1") $nrows = round((dbrows($result) - 1) / 2);
		$c = new Comments;
		echo '<div id="newsaccordion">';
		while ($data = dbarray($result)) 
		{	
			$news_news = $data['news_breaks'] == "y" ? nl2br(stripslashes($data['news_news'])) : stripslashes($data['news_news']);
			if ($data['news_cat_image']) { // TODO: Style do class
				$txt = "<a href='".ROOT."news_cats.php?cat_id=".$data['news_cat_id']."'><img src='".IMAGES."news_cats/".$data['news_cat_image']."' alt='".$data['news_cat_name']."'></a>";
				$news_news = $txt.$news_news;
			} else {
				$news_cat_image = "";
			}
			
			// Neskor dat left join , fusion to je horor, kto to tak mohol spravit
            $c->Set("N", "news", "news_id", $data['news_id'], ROOT.'novinka/'.$readmore.'/');
            $data["news_comments"] = $c->Pocet();				
			News($news_news, $data, $c); 
		}
		
		echo '</div>';
		if ($settings['news_style'] == "1") {
			opentable($locale['046']);
			echo "<table cellpadding='0' cellspacing='0' style='width:100%'>\n<tr>\n<td colspan='3' style='width:100%'>\n";
			echo "</td>\n</tr>\n<tr>\n<td style='width:50%;vertical-align:top;'>\n";
			echo "</td>\n<td style='width:10px'><img src='".THEME."images/blank.gif' alt='' width='10' height='1'></td>\n<td style='width:50%;vertical-align:top;'>\n";
			echo "</td>\n</tr>\n</table>\n";
			closetable();
		}
		echo '<div align="center" class="newsmore">';
		if ($m->celkovo > $m->list) {
			$m->Make(ROOT.'novinky/%s/');	
		}
		echo '</div>';
		
		echo '<div class="theme-footerpanels"><div class="block">';
		// Admin
		Theme::Widget('Hlasuj za Admina !', 'admin', 
		    array( 
		        array('Rank', 'rankadmin'),
		        array('Kandid&aacute;ti', 'kandidati')
		    ), ' normal rank'
		);
		
		// Admini
		echo '<div class="themepanel admin">';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo '</div></div>';
				
		// Galeria
		echo '<div class="themepanel galeria">';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'sfasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo 'safasfasfas afasva asfsava asfsafsava afsafsav asfsa';
		echo '</div>';
		 
		 
		echo '</div>';
	} else {
		opentable($locale['046']);
		echo "<center><br />\n".$locale['047']."<br /><br />\n</center>\n";
		closetable();
	}
	// ----- Cache objekt
	}
	$objekt->File();
	unset($objekt);
	// ----- Cache objekt
} else {
	$readmore = Input::CoolURI('readmore');
    
	/*$result = DB::Query(
		"SELECT tn.*, user_id, user_name FROM ".DB_PREFIX."news tn
		LEFT JOIN ".DB_PREFIX."users tu ON tn.news_name=tu.user_id
		WHERE news_subject LIKE '".$readmore."'"
	);
	if (dbrows($result)==0) {*/
		$result = DB::Query(
			"SELECT tn.*, user_id, user_name FROM ".DB_PREFIX."news tn
			LEFT JOIN ".DB_PREFIX."users tu ON tn.news_name=tu.user_id
			WHERE news_id='".$readmore."'"
		);
	//}
	if ($result->num_rows == 0) {
        die('2');
		redirect(PAGE.ROOT);
	} else {		
		$data = $result->fetch_assoc();
		Header::Title($data['news_subject']);
		if (checkgroup($data['news_visibility'])) {
			$news_cat_image = "";
			if (!isset($_POST['post_comment']) && !isset($_POST['post_rating'])) {
				 $result2 = DB::Query("UPDATE ".DB_PREFIX."news SET news_reads=news_reads+1 WHERE news_id='$readmore'");
				 $data['news_reads']++;
			}
			if ($data['news_cat'] != 0) {
				$result2 = DB::Query("SELECT * FROM ".DB_PREFIX."news_cats WHERE news_cat_id='".$data['news_cat']."'");
				if (dbrows($result2)) {
					$data2 = dbarray($result2);
					$news_cat_image = "<a href='news_cats.php?cat_id=".$data2['news_cat_id']."'><img src='".IMAGES_NC.$data2['news_cat_image']."' alt='".$data2['news_cat_name']."' align='left' style='border:0px;margin-top:3px;margin-right:5px'></a>";
				}
			}
			$news_news = stripslashes($data['news_extended'] ? $data['news_extended'] : $data['news_news']);
			if ($data['news_breaks'] == "y") { $news_news = nl2br($news_news); }
			if ($news_cat_image != "") $news_news = $news_cat_image.$news_news;
            
            $c = new Comments;
            $c->Set("N", "news", "news_id", $data['news_id'], ROOT.'novinka/'.$readmore.'/');
            $data["news_comments"] = $c->Pocet();

		    // ----- Cache objekt
            $objekt = new Cache(180, 'newsitem_'. $data['news_id']);
            $objekt->SubZlozka('page');
            if($objekt->File()) {
            // ----- Cache objekt            
                NewsNext($news_news);
                AutorPre($data);
                PodobneNovinky($data['user_id'], $data['news_id']);
                AutorPo();
            // ----- Cache objekt
            }
            $objekt->File();
            unset($objekt);
            // ----- Cache objekt
			if ($data['news_allow_comments']) $c->Render();
			if ($data['news_allow_ratings']) {
				include INCLUDES."ratings_include.php";
				showratings("N",$readmore, ROOT.'novinka/'.$readmore.'/');
			}
		} else {
			redirect(PAGE.ROOT);
		}
	}
}
Debug::Oblast('NEWS');
require_once "side_right.php";
require_once "footer.php";
