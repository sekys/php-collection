<?
$id = isset($_GET['id']) ? $_GET['id'] : '';
$error = is_numeric($id) ? $id : ''; 

echo '
<style type="text/css">
<!--
.error1 {color: black; font-size: 32px; }
-->
</style>

<div id="error" align="center" >';
	if($error==500) {
		echo '<span class="error1">Serverov&aacute; chyba.</span>';
	} elseif($error==403) {
		echo '<span class="error1">Zak&aacute;zan&yacute; pr&iacute;stup.</span>';
	} else {
		echo '<span class="error1">Str&aacute;nka nen&aacute;jden&aacute;.</span>';
	}	
	echo '<br />', $error, 'error<br />
	<img src="http://www.cs.gecom.sk/themes/seky_web2/img/eror.png" alt="error" />
</div>';