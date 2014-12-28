<?

class DDSmoothGenerator extends ObjectArray 
{    
    public function add($name, $href=false, $img=false) {
        $data['name'] = $name;
        if($href) $data['href'] = $href; 
        if($img) $data['img'] = $img;
        parent::add($data);     
    }
} 
?>