<?php

class GaleriaItem extends DBTable
{
	/* Struktura DB :
		ID - autoinc
		USER - NULL je system
		LINK - ak je mimo projektu
		TIME - cas vytvorenia
		GROUP - ak ma skupinu
		TYP - -1 nahrany image, -2 link na image, 
			  1 nahrane video, 2 youtobevideo
		NAZOV
	*/
	
	// Nastavenia
	private static $size = array(120, 90, 1); // 3. je sizeK ako zooom,...
	
	public function GaleriaItem($sql='') {
		if($sql) {
			$this->__data = DB::Query($sql);
		}	
	}
	public static function Render2() {
		 echo '
		<div class="preview_item">
			<div id="',  $this->group, '" class="preview_id">';
			$this->ImgAName2();
			echo '</div>';
			$this->PreviewTools2();
		echo '</div>';
	}
	public function Render() {
		$meno = $this->__data['name'];
		$action = $this->ActionName();
		
		echo '
		<li class="ui-widget-content ui-corner-tr" id="', $this->__data['id'], '">
			<h5 class="ui-widget-header">', $meno, '</h5>
			<img src="', $this->Nahlad(), '" alt="" title="', $meno, 
			'" width="', $this->size[0], '" height="', $this->size[1], '" />
			<a href="#" class="ui-icon ui-icon-zoomin">', $action, '</a>
			<a href="#" title="Vymaza&#357;" class="ui-icon ui-icon-trash">Vymaza&#357;</a>
		</li>';
	}
	public function Size($i=4) {
		$this->size[0] *= $i;
		$this->size[1] *= $i;
		$this->size[2] = $i;
	}
	private function ActionName() { return $this->JeObrazok() ? 'Zv&auml;&#269;&scaron;i&#357;' : 'Prehra&#357;'; }	
	public function JeObrazok() { return ( $this->__data['typ'] < 0 ); }
	private function Nahlad()  {
		switch($this->data['typ']) {
			// Obrazok mimo  
            case -2 : { return $this->__data['link']; }
			case -1 : { return $this->WEBNahlad(); }
			case 1 : { return $this->VideoNahlad(); }
			case 2 : { return $this->YTBNahlad(); }			
		}
		return '';
	}
    private function WEBNahlad() { // Obrazok u nas 
        return AJAX.'photo.php?id='.$this->__data['id'].'&w='.$this->size[0].'&h='.$this->size[1];
    }
    private function YTBNahlad() { // Youtube nahlad
        // http://i.ytimg.com/vi/kFeQ2rEjjhI/0.jpg 
        if($this->size[2] == 4) return 'http://i2.ytimg.com/vi/'.$this->__data['link'].'/hqdefault.jpg';
        return 'http://i2.ytimg.com/vi/'.$this->data['link'].'/hqdefault.jpg';
    }
	private function VideoNahlad() {
		// TODO: Neskor implementovat   dalsi parser
		return ROOT.'web2/images/no-img-thumb.jpg';
	}
	private function Href2Page() {
        return ROOT.'galeria/?type=item&id='.$this->__data['id'];
    }
    public function ImgAName2() {
        $this->ImgAName($this->Href2Page(), $this->__data['name'], $this->Nahlad());  
    }
	private function ImgAName($link, $name, $scr) {
	    echo '    
        <a href="',$link,'">
            ', $name, '<br>
            <img src="', $scr, '" width="150" height="120" style="border:1px solid #cccccc;">
        </a>';
    }
    public function PreviewTools2() {
        $this->PreviewTools($this->__data['id'], $this->__data['pocet'], $this->__data['komentarov']);
    }    
    private function PreviewTools($id, $pocet, $komentarov) {     
        echo '
        <table width="70%" cellspacing="0" cellpadding="0" >
            <tr>
                <td>';
                if($pocet) {
                    echo '
                    <a href="javascript:galerianext(', $id, ', 0);"><img align="absmiddle" src="http://www.cs.gecom.sk/web2/web2/images/left.gif" border="0" title="Dal&scaron;&iacute;" alt="Dal&scaron;&iacute;"></a>
                    ', $pocet, ' fotiek 
                    <a href="javascript:galerianext(', $id, ', 1);"><img align="absmiddle" src="http://www.cs.gecom.sk/web2/web2/images/right.gif" border="0" title="Sp&auml;&#357;" alt="Sp&auml;&#357;"></a>
                   ';
                }
                echo '</td>
                <td align="right" class="info_gray">'; 
                    if($komentarov) {
                        echo $komentarov, ' <img border="0" alt="Koment&aacute;rov" title="Koment&aacute;rov" src="/web2/web2/images/comments3.png"/>';
                    }
                echo '</td>
            </tr>
        </table>
        <br>';
	}
}
  /* function:  generates thumbnail */
function make_thumb($src,$dest,$desired_width) {
    /* read the source image */
    $source_image = imagecreatefromjpeg($src);
    $width = imagesx($source_image);
    $height = imagesy($source_image);
    /* find the "desired height" of this thumbnail, relative to the desired width  */
    $desired_height = floor($height*($desired_width/$width));
    /* create a new, "virtual" image */
    $virtual_image = imagecreatetruecolor($desired_width,$desired_height);
    /* copy source image at a resized size */
    imagecopyresized($virtual_image,$source_image,0,0,0,0,$desired_width,$desired_height,$width,$height);
    /* create the physical thumbnail image to its destination */
    imagejpeg($virtual_image,$dest);
}

/* function:  returns files from dir */
function get_files($images_dir,$exts = array('jpg')) {
    $files = array();
    if($handle = opendir($images_dir)) {
        while(false !== ($file = readdir($handle))) {
            $extension = strtolower(get_file_extension($file));
            if($extension && in_array($extension,$exts)) {
                $files[] = $file;
            }
        }
        closedir($handle);
    }
    return $files;
}

/* function:  returns a file's extension */
function get_file_extension($file_name) {
    return substr(strrchr($file_name,'.'),1);
}

?>