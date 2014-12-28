<?	
// Fusion header
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
debug::oblast('RANKADMIN');

/*
Adresy
rankadmin/seky/
rankadmin/server/Public/	
	- podla nazvu hladame 
	- v DB GeCom::Lekos dame prec a dame ako define v include
	- IP pouzivat v scriptoch, najst a opravit
*/

// Globalne
$typ = array(1, 2, 3);
$href = ROOT.'rank-admin/%s/';
$user = $userdata['user_id'];
				
// Konkretne
Resource::Css('rank', 'hlasovanie');
require_once "web2/hlasovanie.php";	
require SPAGES."rankadmin/functions.php";    
require SPAGES."rankadmin/user-func.php";    
require SPAGES."rankadmin/user.php";    
require SPAGES."rankadmin/server.php";    
require SPAGES."rankadmin/servers.php";    
require SPAGES."rankadmin/top.php";	

echo '	
	<div class="pravidla_head">
	
	<div id="tabs_rankadmin">
		<ul class="ui-tabs-nav">';
			$id = isset($_GET['id']) ? $_GET['id'] : false;
            if($id) {
				$id = DB::Clear($id); 
				echo '
				<li><a href="', ROOT, 'rank-admin/"><span> TOP Rank </span></a></li>
				<li><a href="', ROOT, 'rank-admin/servers/"><span> Servery </span></a></li>';
                 echo '<li class="ui-tabs-selected"><a href="', ROOT;
                if(isset($_GET['server'])) {
                    echo 'rank-admin/server/';
				} else {
                    echo 'rank-admin/';
                }
                echo urlencode($id), '/"><span> ', DB::Vystup($id), ' </span></a></li>';
            } elseif (isset($_GET['servers'])) {
				echo '		
				<li><a href="', ROOT, 'rank-admin/"><span> TOP Rank </span></a></li>
				<li class="ui-tabs-selected"><a href="', ROOT, 'rank-admin/servers/"><span> Servery </span></a></li>';
			} else {
				echo '		
				<li class="ui-tabs-selected"><a href="', ROOT, 'rank-admin/"><span> TOP Rank </span></a></li>
				<li><a href="', ROOT, 'rank-admin/servers/"><span> Servery </span></a></li>';
			}
		echo '	
		</ul>
		<div id="rank_admin">';
		
		if($id)	{ 
			if(isset($_GET['server'])) {;
				rank_server($id); 				
			} else {
				rank_user($id);	
			}	
			echo '</div>';
		} elseif(isset($_GET['servers'])) {
			rank_servers(); 
		} else {
			rank_toprank();	
		}
		echo '
		</div>
	</div>	
</div>';

unset($typ);
unset($id);
unset($href);
unset($user);

// Fusion footer
debug::oblast('RANKADMIN');
require_once "side_right.php";
require_once "footer.php";
			
?>
