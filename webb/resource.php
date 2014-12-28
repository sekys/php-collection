<?
require_once($_SERVER["DOCUMENT_ROOT"].'/globals.php');
define("S_STATIC", "/home/cstrike/public_html/webb/web2/static/");
$a = new ResServer();

// Const
$a->paths = array(
    array( S_STATIC.'js/%s.js'),
    array( S_STATIC.'css/%s.css')
);
$a->compress = false; // (isset($_GET['c']) and $_GET['c'] == '1');
$a->SetTyp( isset($_GET['t']) ? $_GET['t'] : 0  );

// Popridavaj subory
if(isset($_GET['fs'])) $a->Files($_GET['fs']);
if(isset($_GET['f'])) $a->File($_GET['f']);
 
// Vystup
$a->Start(); 
$a->Info();
$a->EchoData();
$a->End(); 

/*
<script type="text/javascript">

 // Add a script element as a child of the body
 function downloadJSAtOnload() {
 var element = document.createElement("script");
 element.src = "deferredfunctions.js";
 document.body.appendChild(element);
 }

 // Check for browser support of event handling capability
 if (window.addEventListener)
 window.addEventListener("load", downloadJSAtOnload, false);
 else if (window.attachEvent)
 window.attachEvent("onload", downloadJSAtOnload);
 else window.onload = downloadJSAtOnload;

</script>
*/