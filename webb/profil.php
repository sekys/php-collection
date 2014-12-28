<?php
require_once 'maincore.php';
require_once 'subheader.php';

Debug::Oblast('PROFIL');
echo '<td valign="top" class="main-bg" align="center">';

$p = Profile::Find('lookup');
Referral::Set($p->user_name);
Header::Title($p->user_name);
Profile::Header();	

// Stranky profilu ... 
Resource::All('profil');
Header::Js('profil_autoload();', 1);
require SPAGES.'profil/stavba.php';
require SPAGES.'profil/stena.php';

echo '	
<table cellpadding="0" border="0" cellspacing="0" width="100%">	
<tr>
	<td valign="top">		
		<div id="tabs_profil">
			<ul class="v-tabs_profil">
				<li><a href="#uvod"> Stena</a></li>';
				// <li><a href="".ROOT."web2/ajax/ajax_profil.php?id=".$p->"user_id"]."&amp;akcia=galery" > Gal&eacute;ria </a></li>
				/*echo '<li><a href="".ROOT."web2/ajax/profil.php?id=".$p->"user_id"]."&amp;akcia=zombie" > Zombie </a></li>
				';
				<li><a href="/web2/web2/ajax_profil.php?id=".$p->"user_id"]."&amp;akcia=prispevky" > Pr&iacute;spevky </a></li>
				<li><a href="/web2/web2/ajax_profil.php?id=".$p->"user_id"]."&amp;akcia=servery" > Servery </a></li>
				<li><a href="/web2/web2/ajax_profil.php?id=".$p->"user_id"]."&amp;akcia=priatelia" > Priatelia </a></li>
				<li><a href="/web2/web2/ajax_profil.php?id=".$p->"user_id"]."&amp;akcia=liga" > Liga </a></li>
				<li><a href="/web2/web2/ajax_profil.php?id=".$p->"user_id"]."&amp;akcia=hodnost" >  Hodnos&#357;  </a></li>
				<li><a href="/web2/web2/ajax_profil.php?id=".$p->"user_id"]."&amp;akcia=admin" >  Admin </a></li>
		*/
		echo '</ul><div id="uvod">'; 
			profil_stavba("index");
		echo '</div></div>
        </td>
	</tr>
</table>';


// Ukoncime main ...
unset($p); 
Debug::Oblast('PROFIL');
require_once 'footer.php';
?>