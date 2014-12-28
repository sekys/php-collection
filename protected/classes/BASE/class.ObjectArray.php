<?

class ObjectArray {
    protected $childs, $activechild, $lastchild;
    public $data;
    
    public function __contruct() {
        $this->childs = NULL;
        $this->data = NULL;
        $this->activechild = false;
        $this->lastchild = NULL;   
    }
    public function add($data) {
        if($this->IsActive()) {
            $this->lastchild->add($data);
            return;
        }
        $this->childs[] = new ObjectArray();
        $this->lastchild = &$this->childs[count($this->childs)-1];
        $this->lastchild->data = $data;
    }
    public function next() {
       if($this->IsActive()) {
            $this->lastchild->next();
            return;
        }
        $this->activechild = true;
    }  
    public function back() { 
        if($this->IsActive()) {
            if($this->lastchild->activechild == true) {
                $this->lastchild->back();
                return;
             }
        }
        $this->activechild = false;
    }
    /*public function find($data) { 
        if($this->lastchild != NULL ) {
            $this->lastchild->find($data);
            return;
        }
        foreach($this->data as $a) {
            if($a == $data) return true;
        }
        return false;
    } */
    public function GenerateArray() { 
        $out = $this->data;
        if($this->lastchild != NULL) {
            $data = array();
            foreach($this->childs as $ch) {
                $data[] = $ch->GenerateArray();
            }
            $out['data'] = $data;
        }
        return $out;
    }
    public function DataGenerateArray() {
        // Len oprava maleho bugu
        $data = $this->GenerateArray();
        return $data['data']; 
    }
    protected function IsActive() {
        return ($this->lastchild != NULL and $this->activechild);
    }
}
?>
