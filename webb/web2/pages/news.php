<?
function NewsPanel($info, $url) {
    echo
    ' ', $info['news_reads'], ' ', Buttons::Precitane($info['news_reads']), 
    ' ', $info['news_comments'], ' ', Buttons::Komentarov($info['news_comments']),
    ' ', Buttons::FB($url), 
    ' ', Buttons::Twitter($url);
}
function News($news, $info, $c=NULL)
{
    $subject = DB::Vystup($info['news_subject']);
    $url = ROOT.'novinka/'.$info['news_id'].','.$subject.'/';
    echo '
    <li>', Buttons::Favorite($url, $subject), '
		<h3>', $subject, '</h3>
		<span class="date">', date('j.n H:m', $info['news_datestamp']), '</span>
    </li>
    <div class=body">
	    <div class="msg">', $news, '</div>
	    <div class="panel">';
    		NewsPanel($info, $url);
	    echo '</div>';
		if($c!= NULL and $info['news_comments'] > 0) {
    		$c->Last($url);
		}
	echo '</div>';	
}
function NewsNext($news, $info)
{
    $subject = DB::Vystup($info['news_subject']);
    $url = ROOT.'novinka/'.$info['news_id'].','.$subject.'/';
    echo '
    <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td class="capmain">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td>', Buttons::Favorite($url, $subject), ' <h3>', $subject, '</h3></td>
                        <td align="right">', date('j.n H:m', $info['news_datestamp']), '</td>
                        <td width="10" />
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="main-body">', $news, '<br /><br />
                <table align="center" class="news-footer" cellpadding="0" cellspacing="0" width="100%">
                    <tr>';
                        /*echo '
                        <td>
                            Nap&iacute;sal: <b><span class="color_news">', $m->Render(), '</span></b>
                        </td>*/
                        echo '<td></td>
                        <td align="right">';
                            NewsPanel($info, $url);                                                  
                        echo '</td>
                        <td width="10" />';
                                        
                    echo'
                    </tr>
                </table>
            </td>
        </tr>
    </table>';
}
function AutorPre($info) {
    $m = new Member();
    $m->Set($info);
    echo '
    <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td class="capmain">Info o Autorovy</td>
        </tr>
        <tr>
            <td class="main-body">             
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                    <td><img ', $m->Avatar(), '></td>
                    <td>
                        Meno: ', $m->Render(), '<br>
                        Vek: 14<br>
                        Email: ', $m->user_email, '<br>
                        ICQ: ', $m->user_icq, '<br>
                    </td>
                    <td align="right">';
}
function AutorPo() {
                echo '</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>';
}
function PodobneNovinky($id, $newsid) {
    $result = DB::Query(
    "SELECT news_id, LEFT(news_subject, 30) as `news_subject`, news_sticky, news_reads  
    FROM `fusion_news` 
    WHERE news_name=".$id." AND news_id!=".$newsid."
    ORDER BY RAND() LIMIT 5");
    
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
                    GButtons::papier(), 
                    $subject2,
                    '<span class="info_gray">('.$news['news_reads'].')</span>'
            );
        }
    }
} 
function Article($subject, $article, $info) {
    echo '
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td class="capmain"><h5>', $subject, '</h5></td>
        </tr>
        <tr>
            <td class="main-body">
            ', ($info['article_breaks'] == 'y' ? nl2br($article) : $article), '
            </td>
        </tr>
        <tr>
            <td align="center" class="news-footer">';
            echo openform('A', $info['article_id']), articleposter($info,' &middot;'), articleopts($info, '&middot;'), closeform('A', $info['article_id']);
            echo '</td>
        </tr>
    </table>';        
}
function PodobneClanky($id, $newsid) {
    $result = DB::Query(
        "SELECT article_id, article_cat, article_subject, article_reads, article_datestamp, article_cat_access, article_cat_id 
        FROM fusion_articles ta
        INNER JOIN fusion_article_cats tac ON ta.article_cat=tac.article_cat_id
        WHERE article_name=".$id." AND article_id!=".$newsid." AND article_cat_access='0'
        ORDER BY RAND() DESC LIMIT 5"
    );
    if (@$result->num_rows != 0) {
        while($data = $result->fetch_assoc()) {
            abingo2img( 'clanok/'.$data['article_id'].','.STR::uri_out($data["article_subject"]).'/',
                        $data['article_subject'], 
                        '('.$data['article_reads'].')'
            );
        }
    }
}