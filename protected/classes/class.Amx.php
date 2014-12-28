<?
class Amx
{    
    const DB = '`phpbanlist`.`amx6';

    public static function WGet($id, $co = 'level') {
        return DB::Query('SELECT '.$co.' FROM '.self::DB.'_webadmins` WHERE `id`='.$id);
    }
    public static function AGet($id, $co = 'level') {
        return DB::Query('SELECT '.$co.' FROM '.self::DB.'_amxadmins` WHERE `id`='.$id);
    }
    public static function ServerCount($id) {
		return DB::One('SELECT COUNT(admin_id) FROM '.self::DB.'_admins_servers` WHERE `id`='.$id);
    }
    public static function getid($userid) {
        $data = user_get($userid, 'amxid');
        return $data['amxid']; 
    }
    public static function addlog($name, $action, $sprava) {
       DB::Query("INSERT INTO ".self::DB."_logs` 
        ( `id` ,`timestamp`, `ip`, `username`, `action`, `remarks` )
        VALUES ( 
            NULL , UNIX_TIMESTAMP(), '".$_SERVER["REMOTE_ADDR"]."', 
            '".$name."', '".$action."', '".$sprava."'
        );
        ");
    }
    public static function addadmin( 
            $username, $password, $access, 
            $flags, $steamid, $nickname, 
            $show = 1, $exp = 0, $days = 0
    ) {
         DB::Query("INSERT INTO ".self::DB."_amxadmins` 
            (`username`,`password`,`access`,`flags`,`steamid`,`nickname`,`ashow`,`created`,`expired`,`days`) 
            VALUES (
            '".$username."',
            '".$password."',
            '".$access."',
            '".$flags."',
            '".$steamid."',
            '".$nickname."',
            ".$show.",
            UNIX_TIMESTAMP(),
            ".$exp."
            ".$days."
            )");
    }
    public static function addadmintoserver($admin, $server, $custom_flags='', $sban = false) {
        $sban = $sban ? 'yes' : 'no';
        return DB::Query("INSERT INTO ".self::DB."_admins_servers` 
        (`admin_id`,`server_id`,`custom_flags`,`use_static_bantime`) 
        VALUES ('".$adminid."','".$server."','".$custom_flags."','".$sban."')");
    }
    public static function logsearch($text, $co = '*', $count = 10) {
        return DB::Query("SELECT ".$co." FROM ".self::DB."_logs` 
        WHERE remarks LIKE '%".$text."%'
        ORDER BY time_stamp DESC
        LIMIT ".$count);
    }
}
?>