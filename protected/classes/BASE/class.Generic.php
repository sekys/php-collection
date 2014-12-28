<?
// http://davidwalsh.name/php-generic-objects-organize-code-class
// toto sa mzoe extendovat , inak to nema ani zmysel :)

class Generic
{
    protected $__data = array();
    public function __get($key) { return @$this->__data[ $key ]; }
    public function __set($key, $value) { $this->__data[ $key ] = $value; }
    public function add($array) { if(is_array($array)) { foreach($array as $a) { $this->__data[] = $a; } } else { $this->__data[] = $a; } }
    public function ToString($p= ',') { return implode($p, $this->__data); } 
    public function GetAll() { return $this->__data; }
    public function &iGetAll() { return $this->__data; }
    public function last() { return $this->__data[count($this->__data)-1]; } 
    public function first() { return $this->__data[0]; } 
    public function exist() { return count($this->__data); }
    public function rand() { return $this->__data[rand(0, count($this->__data))]; } 
    public function count() { return count($this->__data); } 
    public function size() { return count($this->__data)-1; } 
    public function __destruct() { unset($this->__data); }
    public function next($sql) { return ( $this->__data = $sql->fetch_assoc() ); }
    public function mysqlexist($sql) { if(!$sql->num_rows) { return false; } $this->__data = $sql->fetch_assoc(); return true; }
    public function Set($data) { $this->__data = $data; }                                                                                
    
    public function del($vars = false) {
        if($vars) {
            if(is_array($vars)) { foreach($vars as $var) { unset($this->__data[$var]); }
            } else { unset($this->__data[$vars]); }
        } else { $this->__data = array(); }
    }
    
}





/* simple usage -- just gets and sets 
$person = new generic();
// set sample variables
$person->set('name','David');
$person->set('age','24');
$person->set('occupation','Programmer');
// echo sample variables
echo '<strong>Name:</strong> ',$person->get('name'),''; // returns Name: David
echo '<strong>Age:</strong> ',$person->get('age'),''; // returns Age: 24
echo '<strong>Job:</strong> ',$person->get('occupation'),''; // returns Job: Programmer
// erase some variables -- first a single variable, then an array of variables
$person->unload('name');
$person->unload(array('age','occupation'));


/* database-related usage 
$query = 'SELECT name,age,occupation FROM users WHERE user_id = 1';
$result = mysql_query($query);
$row = mysql_fetch_assoc($result);
$user = new generic();
$user->load($row);
$_SESSION['person'] = $person->get_all();
/* and on the next page, you'll retrieve it 
$person = new generic();
$person->load($_SESSION['person']);

*/











/*
function __call($method, $args) {
$atributo = strtolower(substr($method, 3, 1)).substr($method, 4);
switch (substr($method, 0, 3)) {
case ?get?: return $this->{$atributo}; break;
case ?set?: $this->{$atributo} = $args[0];
}
}
this work for generic too. Ex:
class person extends persistence {
/* No code here 
}
$person = new person();
$person->setName(?David?);
$person->setAge(?24?);
$person->setOccupation(?Programmer?);
echo $person->getName(); /* etc 

The persistence have too methods for save, delete, contruct load and other things. So this is possible:

$person = new person(1);
$person->setAge(25) /* Next Year :-D 
$person->save();
$person->delete(); /* No more David :-( 

*/






/*
function __get($key) { return $this->vars[ $key ]; }
function __set($key,$value) { $this->vars[ $key ] = $value; }
?
Then you can do something like this:

$person = new generic();
$person->Name = ?David?;
$person->Age = ?24?;
echo $person->Name,? is ?,$person->Age,?;
Much cleaner and, more importantly, less typing :)
 code only in PHP5 so I don?t know for earlier version, but you could simply do:

$person = (object) null; // or $person = new stdClass();
$person->name = ?Marc?;
$person->age = ?28??;
echo $person->age;

*/
