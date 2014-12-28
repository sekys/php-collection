<?	
require_once "maincore.php";
require_once "subheader.php";
Theme::$left = false;
require_once "side_left.php";
DB::Oblast('GALERIA');

$type = Input::Isset('type');
$id = Input::Num('id');
$id = DB::Vstup($id);
Resource::All('galeria');

switch($type) {
	case 'shop' : Galeria::Shop($id); break;
	case 'albums' : Galeria::UserAlbums($id); break; 
	case 'album' : Galeria::Album($id); break; 
	case 'user' : Galeria::User($id); break;  
	case 'item' : Galeria::Item($id); break;  
	//case 'last' : Galeria::Last(); break;  
	default : Galeria::Last(); break;
}

// Fusion footer
DB::Oblast('GALERIA');
require_once "side_right.php";
require_once "footer.php";
?>	