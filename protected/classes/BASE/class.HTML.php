<?

class HTML
{
    public $type, $attributes;
    private static $self_closers = array('input','img','hr','br','meta','link');
    
    public function __construct($type) { $this->type = strtolower($type); }
    public function get($attribute) { return $this->attributes[$attribute]; }
    public function set($attribute, $value = '') {
        if(!is_array($attribute)) {
            $this->attributes[$attribute] = $value;
        } else{
            $this->attributes = array_merge($this->attributes,$attribute);
        }
        return $this;
    }
    public function remove($att){ if(isset($this->attributes[$att])) { unset($this->attributes[$att]); } }
    public function clear() { $this->attributes = array(); }
    public function inject($object) { if(@get_class($object) == __class__) { $this->attributes['text'].= $object->build(); } }
    
    // build
    public function build() {
        $build = '<'.$this->type;        
        //add attributes
        if(count($this->attributes)) {
            foreach($this->attributes as $key=>$value) {
                if($key != 'text') { $build.= ' '.$key.'="'.$value.'"'; }
            }
        }        
        //closing
        if(!in_array($this->type,$this->self_closers)) {
            $build.= '>'.$this->attributes['text'].'</'.$this->type.'>';
        } else{
            $build.= ' />';
        }       
        //return it
        return $build;
    }
    public function output() { echo $this->build(); }
    public static function js($txt) { return '<script type="text/javascript">'.$txt.'</script>'; }    

    echo nbs(3);
    &nbsp;&nbsp;&nbsp;
    
    
    
    // TODO: Doplnit http://codeigniter.com/user_guide/helpers/html_helper.html#br
    echo br(3);
    <br /><br /><br />
    
    
    
    
    function __toString() {
return $this->build();
}



    heading('Welcome!', 3);

    
    
        public static function Meta() {
       /* 
            echo meta('description', 'My Great site');
            // Generates: <meta name="description" content="My Great Site" />
            echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); // Note the third parameter. Can be "equiv" or "name"
            // Generates: <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        */
    }
    
    
    
}

/* test case - simple link
$my_anchor = new html_element('a');
$my_anchor->set('href','http://davidwalsh.name');
$my_anchor->set('title','David Walsh Blog');
$my_anchor->set('text','Click here!');
$my_anchor->output();
//<a href="http://davidwalsh.name" title="David Walsh Blog">Click here!</a>

/* test case - br tag
echo '<pre>';
$my_anchor = new html_element('br');
$my_anchor->output();
//<br />

/* test case - sending an array to set
echo '<pre>';
$my_anchor = new html_element('a');
$my_anchor->set('href','http://davidwalsh.name');
$my_anchor->set(array('href'=>'http://cnn.com','text'=>'CNN'));
$my_anchor->output();
//<a href="http://cnn.com">CNN</a>

/* test case - injecting another element
echo '<pre>';
$my_image = new html_element('img');
$my_image->set('src','cnn-logo.jpg');
$my_image->set('border','0');
$my_anchor = new html_element('a');
$my_anchor->set(array('href'=>'http://cnn.com','title'=>'CNN'));
$my_anchor->inject($my_image);
$my_anchor->output();
//<a href="http://cnn.com" title="CNN"><img src="cnn-logo.jpg" border="0" /></a>

$el = new html_element();
$el->set(?href?,'http://??)->set(?title?,'??)->set(?text?,'Click here!?)->output();


Thanks for the class David! Here are my alterations? My goal was to make it a little more like Prototype JS element creation.
*/