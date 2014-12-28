<?
/*
Forum 	- Najpopularnejsie 10, vyzera ako normalne forum,
		- Najnovsie - Vypisat 5 najnovsich sprav to uz mame hore
		- Najaktivnejsi, zoznam top podla tych co najviac poslali sprav
*/

class ForumHelp
{ 
	public static function Najpopularnejsie() {
		openside('Najpopul&aacute;rnej&scaron;ie');
		$result = DB::Query("
			SELECT tf.forum_id, tt.thread_id, tt.thread_subject, COUNT(tp.post_id) as count_posts 
			FROM ".DB_PREFIX."forums tf
			INNER JOIN ".DB_PREFIX."threads tt USING(forum_id)
			INNER JOIN ".DB_PREFIX."posts tp USING(thread_id)
			WHERE ".groupaccess('forum_access')." GROUP BY thread_id ORDER BY count_posts DESC, thread_lastpost DESC LIMIT 10
		");

		echo '<table cellpadding="0" cellspacing="0" width="134" align="right">';
		while($data = $result->fetch_assoc()) {
			$itemsubject = trimlink($data["thread_subject"], 20);
			echo '<tr>
					<td class="side-small"><img src="".THEME."images/bullet.gif" alt=""> <a href="".FORUM."viewthread.php?forum_id=".$data["forum_id"]."&amp;thread_id=".$data["thread_id"]."" title="".$data["thread_subject"]."" class="side">$itemsubject</a></td>
					<td align="right" class="side-small">[".($data["count_posts"]-1)."]</td>
				</tr>';
		}
		echo '</table>';
		closeside();
	} 
	public static function PisaliVTeme($forumid, $thread) { 
		$objekt = new Cache('ForumHelp::PisaliVTeme'.$forumid.$thread, 600);
		if($objekt->File()) {
			$m = new Member;		
			$sql = DB::Query("
				SELECT DISTINCT user_id, user_name, user_avatar, vip, slot FROM `cstrike`.`fusion_posts` f
					JOIN ( SELECT user_id, user_name, user_avatar, vip, slot FROM `cstrike`.`fusion_users` ) u 
						ON f.post_author = u.user_id
				WHERE forum_id='".$forumid."' AND thread_id='".$thread."'
				ORDER BY f.post_datestamp DESC
			");
								 
			while($m->Next($m)) {
				$m->MiniItem();	
			}
		}
		$objekt->File();
	}

}