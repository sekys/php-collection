<?

class GecomDDSmoothGenerator extends DDSmoothGenerator 
{
    public function gecom_add($name, $href=false, $img=false) {
        if($href) $href = ROOT.$href; 
        if($img) $img = ROOT.'images/menu/menu'.$img.'.png'; 
        $this->Add($name, $href, $img);
    }
}
?>