<?php

class Kategorie
{ 
    protected static $kategoria = array();
     
    public static function Load()  {
        $sql = DB::query("SELECT group_id, group_name, group_image, dolezite FROM `cstrike`.`fusion_user_groups` ORDER BY dolezite DESC");
        while ($data = $sql->fetch_row()) {
                //            ID                Nazov     Obrazok    Dolezite
            self::$kategoria[$data[0]] = array($data[1], $data[2], $data[3]);
        }
    }
    public static function Get($i) { return self::$kategoria[$i]; }
    public static function GDX($id) {
        if(!$id) return '';            
        $user_groups_array = (strpos($id, ".") == 0 ? explode(".", substr($id, 1)) : explode(".", $id));
        $query = "SELECT group_name, group_image FROM fusion_user_groups WHERE group_id IN (" . implode( ", ", $user_groups_array ) . ")";
        $imresult = dbquery( $query );
        while ($imdata = dbarray($imresult)) {
            if( !empty( $imdata['group_image'] ) ) 
                $improfile .= " <img alt='".$imdata['group_image']{0}."' title='".$imdata['group_name']."' src='".ROOT."images/ranks/".$imdata['group_image']."'>";  
        }
        $improfile .= "<br>\n";        
        return $improfile;
    }
    public static function One($udaj) {
        if(!$udaj) return 'U&#382;ivate&#318;';
        $data = explode(".", $udaj);
        return self::$kategoria[$data[1]][0];
    }
    public static function Max2($udaj, $normal = 'U&#382;ivate&#318;') {
        $temp = self::Max($udaj);
        return $temp ? self::$kategoria[$temp][0] : $normal;  
    }
    public static function Max($udaj) {
        if(!$udaj) return 0;
        $data = explode(".", $udaj);
        $pocet = count($data);
        $max_dol = 10;
        $max_id = 0; 
        for($i=1; $i < $pocet; $i++) {
            $id = $data[$i];
            $dol = self::$kategoria[$id][2]; 
            if($dol < $max_dol) {
                $max_dol = $dol;
                $max_id = $id; 
            }
        }
        return $max_id;
    }
}
?>