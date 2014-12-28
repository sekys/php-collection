<?    

class Tpl      // extends Cache - nastavit automaticky na -1 a subzlozka theme
{
    const ZLOZKA = '/html/';
    public static function Start() {
    }
    public static function StaticInit() {
        
    }
    public static function name($meno) {  // 'kategorie/profil'
        ZLOZKA . $meno . '.tpl';
    }
    public static function widget($cast) {
        
    }
    public static function render() {
        
    }

    // tpl_set('feed_stories', $feed_stories);
    // render_template($_SERVER['PHP_ROOT'].'/html/home.phpt');
    
    
    
/*    
Inspired from the users microvalen at NOSPAM dot microvalen dot com and javis, i combined their notes and came up with a very simple "template engine", for testing purposes (to save the time and trouble of downloading and including a real template engine like the Smarty):
<?php
$var = 'dynamic content';
echo eval('?>' . file_get_contents('template.phtml') . '<?');
?>
and the  template.phtml:
<html>
    <head>
    <!-- ... -->
    </head>
    <body>
        <!-- ... -->
        <?=$var?>
        <!-- ... -->
    </body>
</html>
*/








/*
<?php
function parseTemplate($template, $params=array()) {
  foreach ($params as $k=>$v) {
     $$k = $v;
  }
  ob_start();
  eval("?>" . implode("", file($template)) . "<?");
  $c = ob_get_contents();
  ob_end_flush();
  return $c;
}
?>

Example:
<?php
echo parseTemplate("myTemplate.php", array('account'=>$row));
?>
and myTemplate.php can be like
<?php foreach($account as $k=>$v) : ?>
  <?php echo $k; ?>: <?php echo $v; ?>
<?php endforeach; ?>
*/





// token_get_all('<?'.'php '.$this->source.' ?'.'>')





/*
A wonderful world of eval() applications
You certainly know how to simulate an array as a constant using eval(), not ? See the code below:
<?php
if( ! defined('MY_ARRAY') ) {
  define( 'MY_ARRAY' , 'return ' . var_export( array( 1, 2, 3, 4, 5 ) , true ) . ';' );
}
?>
And far, far away in your code...
<?php
$my_array = eval( MY_ARRAY );
?>
?>
*/
