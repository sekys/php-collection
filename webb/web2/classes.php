<? 
function AutoLoad($class_name) { 
    Load($class_name); 
    if( method_exists($class_name, 'StaticInit') ) call_user_func($class_name.'::StaticInit');  
    // Od verzie 5.3    $class_name::StaticInit(); 
}
function __autoload($class_name) { return AutoLoad($class_name); } 

// Pouzitie r(new stdClass)->f();
function r($return) { return $return; }

// Na statistyky
$CLASSES_EXECUTE = 0;
$CLASSES_CALLS = 0;

// Triedy
$CLASSES_DIRS = array(
    S_PUBLIC.'web2/classes/BASE/class.',
    S_PUBLIC.'web2/classes/class.',
    S_PUBLIC.'web2/classes/GECOM/class.',
    S_PUBLIC.'web2/classes/TIPS/class.'
);

function Load($class_name) { 
    global $CLASSES_DIRS, $CLASSES_CALLS, $CLASSES_EXECUTE;
    $CLASSES_EXECUTE++;
    
    foreach($CLASSES_DIRS as $dir) {
        $file = $dir . $class_name . '.php';
        $CLASSES_CALLS++; /* is_file je rychlejsi ako file_exist */
        if(is_file($file)) { 
        	require_once($file); 
        	return true;
        }
    }
    // die("Cannot load: ".$class_name);
    throw new ErrorException($class_name);
    return false;
}

?>