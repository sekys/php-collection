<?php
require_once 'maincore.php';
require_once 'subheader.php';
require_once 'side_left.php';
require_once S_WEB2.'includes/comments_include.php';

$rowstart = Input::Num('rowstart', 0);
$article_id = Input::CoolURI('article_id'); 
    
$result = DB::Query(
	"SELECT ta.*,tac.*, tu.user_id,user_name FROM ".DB_PREFIX."articles ta
	INNER JOIN ".DB_PREFIX."article_cats tac ON ta.article_cat=tac.article_cat_id
	LEFT JOIN ".DB_PREFIX."users tu ON ta.article_name=tu.user_id
	WHERE article_id='$article_id'"
);
require_once 'web2/lib/abingo.php';
require 'pages/news.php';

if($result->num_rows != 0) {
	$data = $result->fetch_assoc();
	if (checkgroup($data['article_cat_access'])) {
		if ($rowstart == 0) $result = DB::Query("UPDATE ".DB_PREFIX."articles SET article_reads=article_reads+1 WHERE article_id='$article_id'");
		$article = stripslashes($data['article_article']);
		$article = explode("<--PAGEBREAK-->", $article);
		$pagecount = count($article);
		$article_subject = stripslashes($data['article_subject']);
		
        $data["article_date"] = $data['article_datestamp'];
        
        $c = new Comments;
        $c->Set("A","articles","article_id",$article_id,FUSION_SELF."?article_id=$article_id");
        $data["article_comments"] = $c->Pocet();
        
               // ----- Cache objekt
        $objekt = new Cache(180, 'articleitem_'.$data['article_id']);
        $objekt->SubZlozka('page');
        if($objekt->File()) {
        // ----- Cache objekt  
		    Article($article_subject, $article[$rowstart], $data);
            AutorPre($data);
            PodobneClanky($data['user_id'], $data['article_id']);
            AutorPo();
        // ----- Cache objekt
        }
        $objekt->File();
        unset($objekt);
        // ----- Cache objekt
                
		if($pagecount > 1) {
			$rows = $pagecount;
			echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,1,$rows,3,FUSION_SELF."?article_id=$article_id&amp;")."\n</div>\n";
		}
		if ($data['article_allow_comments']) $c->Render();
		if ($data['article_allow_ratings']) showratings("A",$article_id,FUSION_SELF."?article_id=$article_id");
	}
} else { 
    redirect(PAGE."articles.php");
}

require_once "side_right.php";
require_once "footer.php";
?>