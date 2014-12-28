<?php
/*	Biding:

	(UN)SIGNED TINYINT: I
	(UN)SIGNED SMALLINT: I
	(UN)SIGNED MEDIUMINT: I
	SIGNED INT: I
	UNSIGNED INT: S
	(UN)SIGNED BIGINT: S
	
	http://php.net/manual/en/book.mysqli.php
*/

/*	Tips:
	NOW() + INTERVAL 1 DAY 
	TIMEDIFF(NOW(), vlozeno)

	SELECT users.*,s.listitems FROM users, sessions s WHERE s.sessionid='$sessionid' AND s.expires > now() AND s.ip='$REMOTE_ADDR' " .
        		 "AND s.userid=users.userid
*/

/*
This function returns the query result as an array of objects, or an empty array on failure. Typically you'll use this in a foreach loop, like this:
$query = $this->db->query("YOUR QUERY");

foreach ($query->result() as $row)
{
   echo $row->title;
   echo $row->name;
   echo $row->body;
}    
// TODO: function mysql_safe($q) {
    $x = array_shift(func_get_args());
    return vsprintf(preg_replace('/%([1-9]):(d|s)/','%$1$$2',$q), array_map('mysql_escape_string',$x));
}
?>
example
<?php
$query = mysql_safe("select * from somewhere where mood = %2:s and some_id = %1:d order by %3:s desc", $id, 'happy', $order); 
*/


class DB
{
    protected static $links, $link;    
    protected static $i = 0, $queries = array(0); 
    protected static $Zakazane = array("'", '"', "/", "\\", "<", ">", "&");
    protected static $Replace = array("x01x", 'x02x', "x03x", "x04x", "x05x", "x06x", "x07x");
   
    public static function Start() { }
    public static function StaticInit() {
        // Turn of error reporting
        //mysqli_report(MYSQLI_REPORT_OFF);
        require(S_PUBLIC.'web2/const_db.php');
        
        // Nacitaj
        foreach($databases as $data) {            
            if(!self::Connect($data)) return;
        }
        self::Main();
    }
    // Hlavny link, lachsie sa vola
    public static function Connect($data) {
    	 self::$links[] = new Mysqli($data[0], $data[1], $data[2], $data[3]);	
		if( mysqli_connect_errno() ) {
            error_log('DB::Connect() '.mysqli_connect_error().' - '.mysqli_connect_errno()); 
            die('Some problems with '.$data[0].'. database.');
            return false;
        }
        return true;
    }
    public static function Main() { self::SetInstance(0); }
    public static function Second() { self::SetInstance(1); }
    public static function SetInstance($i = 0) { self::$i=$i; self::$link = &self::$links[$i]; }
    public static function &GetInstance($i = 0) { return self::$links[$i]; }
    public static function Queries($i = -1) { 
        if($i == -1) {
            $pocet = 0; 
            foreach(self::$queries as $a) $pocet += $a;
            return $pocet; 
        }
        return self::$queries[$i];
    }

    
    // Jednotlive DB prikazi
    
    		
	public static function query($sql) { 
		self::$queries[self::$i]++; 
		$data = self::$link->query($sql); 
		if( $data === FALSE) { 
			throw new Exception("Query: \n".$sql . "\n" .self::$link->error); 
			return FALSE; 
		} return $data; 
	}
	
	// In / Out
		
	public static function Vystup($data) { return STR::XSS($data); }	
	public static function Vstup($data) { 
		$value = stripslashes($data);
		$value = htmlentities($value);
		self::$link->real_escape_string($value); 
		return $value;
	}
    public static function Clear($sprava) { return str_replace(self::$Zakazane, "", trim($sprava)); }
    public static function Replace($txt) { return str_replace(self::$Zakazane, self::$Replace, $txt); }    
    public static function Unreplace($txt) { return str_replace(self::$Replace, self::$Zakazane, $txt); }
    public static function mysql_safe($q) {
        $x = array_shift(func_get_args());
        return vsprintf(preg_replace('/%([1-9]):(d|s)/','%$1$$2',$q), array_map('mysql_escape_string',$x));
    }
    
    // Vlastne procedury
    
	public static function Insert($tbl, $data) { 
		foreach ($data as $key => $value) { 
			$a .= '`'.$key.'`,'; 
			$b .= "'".$value."',"; 
		} 
		$a = substr($a, 0, -1);
		$b = substr($b, 0, -1); 
		return self::query("INSERT INTO ".$tbl." (".$a.") VALUES (".$b.")"); 
	}
    public static function InsertArray($tbl, $stlpce, $data) { 
        $a = implode(',', $stlpce); 
        $b = implode(',', $data); 
        return self::query("INSERT INTO ".$tbl." (".$a.") VALUES (".$b.")"); 
    }
	public static function RandomRow($table, $column, $what = '*', $max = 0) {
		// Otestovane - najrychlejsia metoda
		// - $random_number ked nacachujeme tak perfektne,..
		if(!$max) {
			$max_sql = 'SELECT max(' . $column . ')  AS max_id FROM ' . $table;
			$max = self::One($max_sql);
			echo $max;
		}
		$random_number = mt_rand(1, $max);

		$random_sql = 'SELECT '.$what.' FROM '.$table.' WHERE '.$column.' >= '.$random_number.' ORDER BY '.$column.' ASC LIMIT 1';
		$random_row = self::Query($random_sql);

		if(!$random_row->num_rows) {
			$random_sql = 'SELECT '.$what.' FROM '.$table.' WHERE '.$column.' < '.$random_number.' ORDER BY '.$column.' ASC LIMIT 1';
			$random_row = self::Query($random_sql);
		}
		return $random_row;
	}
	public static function InsertOne($tbl, $a, $b) { return self::query("INSERT INTO ".$tbl." (`".$a."`) VALUES ('".$b."')"); }
	public static function Update($tbl, $data, $where='') { 
        foreach ($data as $key => $value)  $str .= "`".$key."` = '".$value."',"; 
        $str = substr($str, 0, -1); 
        return self::query("UPDATE ".$tbl." SET ".$str." ".$where); 
    }
	public static function One($txt) { 
		$sql = self::query($txt);
		if(!$sql->num_rows) {
			return -1;	
		} else {
			$data = $sql->fetch_row();
			return $data[0];
		} 
	}	
	public static function Pocet($sql) { $data = self::query($sql)->fetch_assoc(); return $data['pocet']; }
	public static function Select($query) { return self::query($query)->fetch_assoc(); }	
    public static function ID() { return mysqli_insert_id(); }    
	public static function UTFConnection() { 
		self::query("SET NAMES utf8"); 
		self::query("SET CHARACTER SET utf8");
	}	
    public static function Error() { return mysqli_error(DB::$link); }
    public static function select_db($db) { return self::$link->select_db($db); }
    
    // Dalsie
    
	public static function rexec($call, $types, $params) { return self::exec($call, $types, $params)->result_metadata(); }	
	public static function exec($call, $types, $params) {
		self::$queries[self::$i]++;  
		$stmt = self::$link->stmt_init();
		if($stmt->prepare($call)) {
			$bind_names[] = $types;
			for ($i=0; $i < count($params);$i++) {
				$bind_name = 'bind' . $i;
				$$bind_name = $params[$i];
				$bind_names[] = &$$bind_name;
			}
			//echo $stmt->param_count." parameters\n";
			call_user_func_array( array($stmt,'bind_param'), $bind_names);
			$stmt->execute();
			return $stmt;
		}
		throw new Exception("DB::Exec(".$call.")");
		return FALSE;
	}	
	public static function FullFetch() {
		/*	Pouzite
			$userPassArr = DataAccess::fetch('SELECT * FROM users WHERE username = ? AND password = ?', $uname, $pass);
			print_r($userPassArr);
			Array (
				[0] => Array (
						[id] => 1
						[username] => mahees
						[password] => mahees
					)
			)
			$userPassArr = DataAccess::fetch('SELECT * FROM users');
			print_r($userPassArr);
			Array (
				[0] => Array  (
						[id] => 1
						[username] => mahees
						[password] => mahees
					)

				[1] => Array  (
						[id] => 4
						[username] => foo
						[password] => bar
					)
			)
		*/
		$args = func_get_args();
		$sql = array_shift($args);
		$stmt = self::$link->stmt_init();;
		if(!$stmt->prepare($sql)) throw new Exception('Please check your sql statement : unable to prepare');
		
		// Paprametre
		$count = count($args);
		if($count > 1) {
			$types = str_repeat('s', $count);
			array_unshift($args, $types);
			array_unshift($args, $stmt);
			call_user_func_array('mysqli_stmt_bind_param', $args);
		}
		
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_result_metadata($stmt);
		$fields = array();
		while ($field = mysqli_fetch_field($result)) {
			$name = $field->name;
			$fields[$name] = &$$name;
		}
		array_unshift($fields, $stmt);
		call_user_func_array('mysqli_stmt_bind_result', $fields);

		array_shift($fields);
		$results = array();
		while (mysqli_stmt_fetch($stmt)) {
			$temp = array();
			foreach($fields as $key => $val) { $temp[$key] = $val; }
			array_push($results, $temp);
		}
		mysqli_free_result($result);
		mysqli_stmt_close($stmt);
		return $results; 
	}
   public static function ArraySave($data) {
        // Serializacia je skoro ako json_decode, premeni array do retazca
        // Kedze databaza blokuje " ''" tak je tam base decode
        //To safely serialize
        return base64_encode(serialize($data));
   }
    public static function ArrayGet($data) { 
        //To unserialize...
        return unserialize(base64_decode($encoded_serialized_string));
    }


}

/*
1)prepare()
2)execute()
3)store_result()
4)bind_result() 

$stmt = $mysqli->prepare("INSERT INTO CountryLanguage VALUES (?, ?, ?, ?)");
$stmt->bind_param('sssd', $code, $language, $official, $percent);
$code = 'DEU';
$language = 'Bavarian';
$official = "F";
$percent = 11.2;
$stmt->execute();
printf("%d Row inserted.\n", $stmt->affected_rows);
// close statement and connection 
$stmt->close();


$stmt->execute();
// store result 
$stmt->store_result();
printf("Number of rows: %d.\n", $stmt->num_rows);
// free result 
$stmt->free_result();
// close statement 
$stmt->close();

	

$statement = $mysqli->stmt_init();
$statement->prepare("CALL some_procedure( ? )");
// Bind, execute, and bind.
$statement->bind_param("i", 1);
$statement->execute();
$statement->bind_result($results);
while($statement->fetch()) {
// Do what you want with your results.
} 
$statement->close(); 
*/
?>
