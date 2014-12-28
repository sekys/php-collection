<?php

class Comments
{
    const GUESTPOST = false;
	const FORM_ID = 'c_t';
    
    public $ctype;
    public $cdb;
    public $ccol; 
    public $cid; 
    public $sirka=540;
    public $clink;
    
    public function Pocet() {
        return DB::One("SELECT COUNT(comment_id) FROM fusion_comments WHERE comment_type='".$this->ctype."' AND comment_item_id='".$this->cid."'");
    } 
    public function Fusion($ctype, $cdb, $ccol, $cid, $clink, $sirka=540) {
         $this->Set($ctype, $cdb, $ccol, $cid, $clink);
         $this->sirka = $sirka;
         $this->Render();
    }
    public function Set2($ctype, $cid) {
         // Najdolezitejsie
    	 $this->ctype = $ctype;
         $this->cid = $cid;
    }
    public function Set($ctype, $cdb, $ccol, $cid, $clink) {
    	 $this->ctype = $ctype;
         $this->cid = $cid;
         $this->cdb = $cdb;
         $this->ccol = $ccol;
         $this->cid = $cid;
         $this->clink = $clink;
    }
    public function AjaxPost($smileys, $flood_int = 1.0) {
        // POST udaje
        if ((User::Logged() || self::GUESTPOST) && isset($_POST[self::FORM_ID])) 
        {
            $flood = false;
            if($this->cdb == true) {   // Toto je len overenie ci clanok existuje 
                if (DB::Query("SELECT ".$this->ccol." FROM fusion_".$this->cdb." WHERE ".$this->ccol."='".$this->cid."'")->num_rows ==0 ) {
                    return false;
                }
            }    
            // Sprava
            $messsage = STR::IN($_POST[self::FORM_ID]); 
            if($message != '' && !preg_match("#\[url\](.*?)\[/url\]#si", $message)) {
                $result = DB::Query("SELECT MAX(comment_datestamp) AS last_comment FROM fusion_comments WHERE comment_ip='".$_SERVER['REMOTE_ADDR']."'");
                if(!User::$m->SuperAdmin() || $result->num_rows > 0) {
                    $data = $result->fetch_assoc();
                    if ((time() - $data['last_comment']) < $flood_int) {
                        $flood = true;
                        DB::Query("INSERT INTO fusion_flood_control (flood_ip, flood_timestamp) VALUES ('".$_SERVER['REMOTE_ADDR']."', '".time()."')");
                        $floot_count = DB::One("SELECT COUNT(flood_ip) FROM fusion_flood_control WHERE flood_ip='".$_SERVER['REMOTE_ADDR']."'");
                        if($floot_count > 4) {
                            if(User::Logged()) {
                                DB::Query("UPDATE fusion_users SET user_status='1' WHERE user_id='".User::$m->user_id."'");
                            }
                        }
                    }
                }
                if (!$flood) {
                    Shop::String(User::$m->user_id, 8, strlen($message));
                    DB::Query("INSERT INTO fusion_comments 
                        (comment_item_id, comment_type, comment_name, comment_message, comment_smileys, comment_datestamp, comment_ip) VALUES 
                        ('".$this->cid."', '".$this->ctype."', '".User::$m->user_id."', '".$message."', '".intval($smileys)."', '".time()."', '".$_SERVER['REMOTE_ADDR']."')");
                    $this->AjaxResponse($message);
                    return true;
                }
            }
        }   
        return false; 
    }
    protected function AjaxResponse($message) {
        // Sprav kopiu
        $data = User::$m;
        $data->comment_message = $message;
        $pasik = (bool) $_GET['passik'];
        $data->comment_id = DB::ID();
        $data->comment_datestamp = time();
        $this->Item($data, !$pasik); // treba opak
    }
    public function Render() {
        // Zoznam
        tablebreak();
        $result = DB::Query($this->SQL().'ORDER BY comment_datestamp ASC');
        
        // Vypisujeme ...
        if ($result->num_rows != 0) {
            $pasik = true;
            $m = new Member;
            echo '<div class="comments">';
            while($m->next($result)) { 
                $pasik = !$pasik;
                $this->Item($m, $pasik);
            }
            echo '<div>';
            
        // Ak je admin ...
        if (checkrights("C")) {
            $pasik = !$pasik;
            echo '<div colspan="2" align="right" class="', 
                    ($pasik ? "tbl1" : "tbl2"), '>
                        <a href="', ADMIN, '"comments.php?ctype=', $this->ctype, '&cid=', $this->cid, '">Administr&aacute;cia</a>
                    </div>';
            }
        } else {
            echo '<div align="center" class="info_gray">&#381;iadny koment&aacute;r e&scaron;te nebol pridan&yacute;. Bu&#271;te prv&yacute; kto prid&aacute; koment&aacute;r.</div>';
        }
        tablebreak();
        
        // Poslat odpoved ....
        $this->Formular();
    }
    protected function Formular() {
        // Tu bol aj ciselny overovaci kod 2+ 2 =  ...nie zly napad
        if (User::Logged()) { 
            echo '
            <table id="comment-action" align="center" width="100%" cellspacing="0" cellpadding="0" class="tbl">
                <tr>
                    <td align="center" class="bbcode">';
                        $this->BBCode(self::FORM_ID); 
                    echo '    
                    </td>
                    <td>
                        <textarea name="', self::FORM_ID, '" id="', self::FORM_ID, '" rows="7" class="textbox" ></textarea>
                    </td> 
                </tr>    
                <tr>    
                    <td align="center" colspan="2">';
                        displaysmileys(self::FORM_ID);
                echo '        
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="2">
                        <input type="submit" name="post_comment" value="Okomentova&#357;" onclick="sendcomment(this, \'', $this->ctype, '\', ', $this->cid, ');" class="button"><br>
                    </td>
                </tr>
            </table>';
        } else {
            echo '<div align="center" class="info_gray">Iba registrovan&yacute; m&ocirc;&#382;u prid&aacute;va&#357; koment&aacute;re.</div>';
        }
    }
    protected function SQL() {
		$sql = "SELECT comment_datestamp, comment_message, comment_smileys, comment_name, comment_id,
            user_name, user_id, user_avatar, user_groups, vip, slot, cs_meno, korun FROM fusion_comments tcm
                LEFT JOIN fusion_users tcu ON tcm.comment_name=tcu.user_id
            WHERE comment_item_id='$this->cid' AND comment_type='$this->ctype'";
        return $sql;
    }
    public function Last($url) {
		$result = DB::Query($this->SQL().'ORDER BY comment_datestamp DESC LIMIT 1');
        
        // Vypisujeme ...
        if ($result->num_rows != 0) {
            $m = new Member;
            $m->next($result);
            $this->Item($m, true, $url);
        }
    }
    protected function Item($data, $pasik, $urlcomment=false) {
        $class = $pasik ? "white" : "black";
        echo '
        <div class="comment" id="', $data->comment_id , '-', $this->ctype, '-', $data->user_id, '">
            <div class="avatar ', $class, '">
                <a href="', $data->Link(), '"><img height="96" border="0" width="96" ', $data->Avatar(), '></a>
                ', $this->VypisKredity($data->korun), '
            </div>
            <div class="right ', $class, '">';
            // Header komentu ...                
        		echo $data->Render();
                echo '<div class="calendar">', 
                	date("j.n H:m", $data->comment_datestamp), '
                </div>
                <div class="text">';                            
            // Stred                    
            	//echo nl2br(str_bbcode(str_smiley($data["comment_message"]))); 
           		echo STR::OUT($data->comment_message); 
                echo '</div>
				<div class="p">';                   
            // Footer tu ide
            		if($urlcomment) {
						echo '<a href="', $urlcomment, '">';
            		} else {
                       	 echo '<a href="#c_t" onclick="cq(this);">';
                    }
                    echo '
                        <span class="ui-icon2 ui-icon-comment"></span> 
                        Komentova&#357;
                    </a>
                    <a href="', $data->Link(), '?comment=', $this->ctype, ',', $data->comment_id, '">
                        <span class="ui-icon2 ui-icon-person"></span>
                        Na stenu
                    </a>
                    <a href="#" onclick="likec(\'', $this->ctype, '\'', $data->comment_id, ');">
                        <span class="ui-icon2 ui-icon-check"></span>
                        P&aacute;&#269;i sa mi to
                    </a>    
                    <span class="more"></span>
            	</div>    
            </div>
        </div>';
    }

    protected function BBCode($textarea) {
        echo '
        <img src="', ROOT, 'web2/images/tool/bold.gif" alt="B" onClick="addText(\'', $textarea, '\', \'[B]\', \'[/B]\');"><br>
        <img src="', ROOT, 'web2/images/tool/italicize.gif" alt="I" onClick="addText(\'', $textarea, '\', \'[I]\', \'[/I]\');"><br>
        <img src="', ROOT, 'web2/images/tool/quote.gif" alt="Q" onClick="addText(\'', $textarea, '\', \'[Q]\', \'[/Q]\');"><br>
        <img src="', ROOT, 'web2/images/tool/link.gif" alt="O" onClick="bbcode(\'', $textarea, '\', 0);"><br>
        <img src="', ROOT, 'web2/images/tool/icon-linkto-image.gif" alt="I" onClick="bbcode(\'', $textarea, '\', 1);"><br>
        <img src="', ROOT, 'web2/images/tool/icon-linkto-gl.gif" alt="GL" onClick="bbcode(\'', $textarea, '\', 2);"><br>
        <img src="', ROOT, 'web2/images/tool/icon-linkto-youtube.gif" alt="Y" onClick="bbcode(\'', $textarea, '\', 3);"><br>
        '; 
    }  
    protected function VypisKredity($penazi) {
        $vysledok = '<img title="'.$penazi.' Kreditov" border="0" alt="20" src="'.ROOT;
        if($penazi > 100) { 
            $vysledok .= 'web2/images/tool/med4.png"/>';
        } elseif( $penazi > 20 ) {        
            $vysledok .= 'web2/images/tool/med3.png"/>';
        } elseif ( $penazi > 6 ) {
            $vysledok .= 'web2/images/tool/med1.png"/>';
        } elseif ( $penazi == 0 ) {
            $vysledok .= 'web2/images/tool/med0.png"/>';
        }
        return $vysledok;
    }
}
