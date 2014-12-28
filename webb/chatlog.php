<?
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";

require '/home/cstrike/www/cstrike/chatlog-func.php';

 
echo '<div class="pravidla_head legenda">  
    <pre>';
    ChatLog();
echo '</pre>
</div>';

require_once "side_right.php";
require_once "footer.php";