<?
class PLSql
{    
    // Hladame log ....spolocny pocet je 7
    public $id;
    public $a;
    public $b = 0;
    public $limit = 1;
    public $nahlad = 70;
     
   	protected $sql = '';
   
   	public function __construct() {
		$this->Clear();
	}
    public function Comments() {
	    $this->sql .= " UNION	    
	    SELECT 1, 1, 1, comment_item_id, comment_type, LEFT(comment_message, ".$this->nahlad.") as comment_message, comment_datestamp FROM `fusion_comments`
		    WHERE '".$this->a."' > comment_datestamp AND comment_datestamp > '".$this->b."' AND comment_name = '".$this->id."'	
	    ";
    }
    public function Galeria() {
	    $this->sql .= " UNION
	    SELECT 2, `id`,`name`,`type`,`link`,`description`,`created_time` FROM `galeria_item`
		    WHERE user='".$this->id."' AND '".$this->a."' > created_time AND created_time > '".$this->b."'	
	    ";
    }
    public function Forum() {
	    // Hladame log ....spolocny pocet je 7
	    $this->sql .= " UNION	
	    SELECT 3, forum_id, thread_id, post_id, post_subject, LEFT(post_message, ".$this->nahlad.") as post_message, post_datestamp	FROM `fusion_posts`
		    WHERE '".$this->a."' > post_datestamp AND post_datestamp > '".$this->b."'	AND post_author = '".$this->id."'
	    ";
	}
	public function All() {
		$this->Comments();
		//$this->Galeria();
		$this->Forum();
    }
    public function Build() {
		// Posledne upravy
		$this->sql .=	" ORDER BY 7 DESC";
	    if($this->limit > 0) $this->sql .= " LIMIT ".$this->limit;	    
		//echo $this->sql;
		return $this->sql;
    }
    public function Clear() {
    	// aby isiel posledny union :)
		$this->sql = "SELECT 1, 2, 3, 4, 5, 6, 7";
    }
}