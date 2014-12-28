<?
class DDSmoothMenu {
    public $fl = array('<h2>', '</h2>');
    
    public function Render($data) {
        echo '<ul class="ddsmoothmenu_pod">'; 
        $this->FirstLevel($data);
        echo '<ul>'; 
    } 
    protected function FirstLevel($data) {
        foreach($data as $h) {
            echo '<li>', $this->fl[0];
            if(isset($h['href'])) {
                echo '<a href="', $h['href'], '">', $h['name'], '</a>';
            } else {           
                echo $h['name'];
            }
            echo $this->fl[1];
            if(isset($h['data'])) $this->Recursive($h['data']);
            echo '</li>';            
        }
    }
    protected function Recursive($data) {
        echo '<ul>';
        foreach($data as $h) {
            echo '<li>
                <a href="', $h['href'], '" > <img src="', $h['img'], '" border="0" align="absmiddle" alt="', $h['name'], '" />', $h['name'], '</a>';
                if(isset($h['data'])) $this->Recursive($h['data']);
            echo '</li>'; 
        }
        echo '</ul>';
    }
}
?>