<?php

class Zoznam 
{
	public $actual;			// aktualne cislo zaznamu s URL
	public $list = 10;		// po kolko zobrazujeme ....
	public $celkovo;		// celkovy pocet zazznamov v databaz
	public $vzdialenost = 3; // rozsah cisel v zozname
	public $moredetails = false; // bude sa nacitavat dalej ale cez ajax
    // ako tu na ako http://www.cs.gecom.sk/ban/ban_list.php
    
	public function Set($actual, $celkovo) {
        $this->actual = $actual;
        $this->celkovo = $celkovo;
    }
    public function Config($vzdialenost, $list) {
        $this->vzdialenost = $vzdialenost;
        $this->list = $list;
    }
    public function Fusion($actual, $list, $celkovo, $vzdialenost, $link) {
        $this->Set($actual, $celkovo);
        $this->Config($vzdialenost, $list);
        $this->Make($link);
    }
    public function get($id) {
    	$this->actual = (is_numeric($_GET[$id])) ? $_GET[$id] : 0;	
	}
    public function mysql() { return " LIMIT ".$this->list.", ".$this->actual." "; }
	// celkovo, kolko, vzdialenost, link, zoznam 
    public function Make($link)		// link v C++ style  
	{
        $stranok = ceil($this->celkovo / $this->list);
		if ($stranok > 1) {			
			$spet = $this->actual - $this->list;
			$dalej = $this->actual + $this->list;
			$aktualna_stranka=ceil(($this->actual + 1) / $this->list);
			
			echo "<table cellspacing='0' cellpadding='0' border='0' class='tbl-border'><tr>";
			echo "<td class='tbl2'><span class='small'>Strana ".$aktualna_stranka." z ".$stranok."</span></td>";
			
			if ($spet >= 0) { 
				if ($aktualna_stranka > ($this->vzdialenost + 1)) {
					echo "<td class='tbl2'><a class='small' href='".sprintf($link, 1)."'>&lt;&lt;</a></td>";
				}
				echo "<td class='tbl2'><a class='small' href='".sprintf($link, $spet)."'>&lt;</a></td>\n";
			}			
			$prva_stranka = max($aktualna_stranka - $this->vzdialenost, 1);
			$posledna_stranka = min($aktualna_stranka + $this->vzdialenost, $stranok);
			
			if ($this->vzdialenost==0) {
				$prva_stranka = 1;
				$posledna_stranka=$stranok;
			}
			for($i=$prva_stranka; $i<=$posledna_stranka; $i++) {
				$offset_page=($i - 1) * $this->list;
				if ($i==$aktualna_stranka) {
					echo "<td class='tbl1'><span class='small'><b>".$i."</b></span></td>";
				} else {
					echo "<td class='tbl1'><a class='small' href='".sprintf($link, $offset_page)."'>$i</a></td>";
				}
			}
			if ($dalej < $this->celkovo) {
				echo "<td class='tbl2'>
						<a class='small' href='".sprintf($link, $dalej)."'>
							<img alt='&#270;alej' title='&#270;alej' src='".ROOT."web2/images/arrow_right.png' border='0'/>
						</a>			
					</td>";
				$this->list = ($stranok-1)*$this->list;
				if ($aktualna_stranka < ($stranok - $this->vzdialenost)) {
					echo"<td class='tbl2'><a class='small' href='".sprintf($link, $this->list)."'>&gt;&gt;</a></td>";
				}
			}
			echo "</tr>
			</table>";
		}
	}
    // TODO: Do zoznamu pridat,...
    // http://davidwalsh.name/mootools-scrollspy-load  
}
?>