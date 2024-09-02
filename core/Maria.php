<?php
namespace Core;
use PDO;
use PDOException;

class Maria {
    public $_db;
    public $confd;
	//connect to maria/mysql
    public function __construct(string $database = ''){
            $dbhost="localhost";
            $dbuser="root";
            $dbpass="n130177!";
            try	{
                //mysql:unix_socket=/var/run/mysqld/mysqld.sock;charset=utf8mb4;dbname=$dbname
                $this->_db = new PDO("mysql:host=$dbhost;dbname=$database",$dbuser,$dbpass,
                    array(
                        PDO::ATTR_ERRMODE,
                        PDO::ERRMODE_EXCEPTION,
                        PDO::ERRMODE_WARNING,
                        PDO::ATTR_EMULATE_PREPARES => FALSE,
                        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
                        PDO::ATTR_PERSISTENT => false
                    ));

            }	catch(PDOException $error)	{
               throw new Exception("Database connection failed: " . $error->getMessage());
            }
    }
	//setting table
	public   function is(string $name): bool|string{
		$fetch = $this->db->f("SELECT en FROM globs WHERE name=?", array($name));
		if (!empty($fetch)) {
			return urldecode($fetch['en']);
		} else {
			return false;
		}
	}
    /*
     *	Fetch MANY result
     *	Updated with memcache
     */
    public   function   fjsonlist($query){
        $res=$this->fa($query);		
		if (!$res) {
			return FALSE;
		}else{
			$tags=array();
			for($i=0;$i<count($res);$i++){	
				if($res[$i]['json']!='[]'){
				$jsdecod=json_decode($res[$i]['json'],true);
			if(!empty($jsdecod)){
				foreach($jsdecod as $jsid => $jsval){		
					$tags[]=trim($jsval);
						}
			}
					}		
			}
		return $tags;
		}
        $res->closeCursor();
    }
    /*
   * INSERT WITH RETURN ID ,
   * UPDATE
   * A) RETURNS FALSE,
   * B) Autoincrement with NULL or insert $id NO NEED FOR fetchMax function  
   * c) NO NEED FOR QUESTIONMARKS
     * This function   works only if we insert all params except id
     * sequential array = array('apple', 'orange', 'tomato', 'carrot');
     * associative array = array('fruit1' => 'apple',
                    'fruit2' => 'orange',
                    'veg1' => 'tomato',
                    'veg2' => 'carrot');
     * if we want to insert specified number of params we need array('uid'=>$uid,'content'=>$content,etc)
   * */
    public function inse(string $table, array $params = array(),$id=NULL): int|bool{
        $qmk = implode(',', array_fill(0, count($params), '?'));
        if (is_assoc($params)) {
//            $rows= $k= '("'.implode('","',array_keys($params)).'")';
            $rows = $k = '(' . implode(',', array_keys($params)) . ')';
            $values = "$rows VALUES ($qmk)";
            $params = array_values($params);
        } else {
            $values = count($params) != count($this->columns($table)) && $id != NULL ? "VALUES ($id,$qmk)" : "VALUES ($qmk)";
        }
        $sql= "INSERT INTO $table $values";
        $res = $this->_db->prepare($sql);
        $res->execute($params);
        if (!$res){return false;}else{
        return !$this->_db->lastInsertId() ? true: $this->_db->lastInsertId(); //CASE OF CORRECT INSERT BUT WITH NO RETURN VALUE (eg NO ID table)
		}
        $res->closeCursor();
    }
    /*
get max value from table
*/
    public function  fetchMax(string $row, string $table, $clause = ''): int{
        $selecti = $this->f("SELECT MAX($row) as max FROM $table $clause");
        return $selecti['max'];
    }

    public   function  fetchList1(array $rows): ?array{
        if(is_array($rows)){
            $fetch=$this->fa("SELECT {$rows[0]} FROM {$rows[1]} {$rows[2]}");
            for($i=0;$i<count($fetch);$i++){
                $list[]=strpos($rows[0], '.') !== false	? $fetch[$i][explode('.',$rows[0])[1]] : $fetch[$i][$rows[0]];
            }
        }
        return $list;
    }


    public function column_primary(string $table):array{
        $q = $this ->_db->prepare("SHOW columns FROM $table WHERE Key_name = 'PRIMARY'");
        $q->execute();
        return  $q->fetchAll(PDO::FETCH_COLUMN);
        $q->closeCursor();
    }

    /*
     *
     * meta retuns table all columns and types
     * LONG -> int
     * TINY ->tinyint
     * VAR_STRING ->varchar
     * STRING -> char
     * INT24 -> mediumint
     * */
	 public function  list_tables():array{
        $query = $this->_db->query('SHOW TABLES');
        return $query->fetchAll(PDO::FETCH_COLUMN);
    }

    public function  types($table){
        $sel=array();
        $select = $this ->_db->query("SELECT * FROM $table");
        foreach($this->columns($table) as $colid => $col) {
            $meta= $select->getColumnMeta($colid);
            $sel[$meta['name']] = $meta['native_type'];
        }
        return $sel;
    }

    public function  comments(string $table): array{
        $sel=array();
        foreach($this->columns($table) as $colid => $col) {
            $select = $this->f("SHOW full columns from $table WHERE Field='$col'");
            $sel[$select['Field']] = $select['Comment'];
        }
        return $sel;
    }
    /*
     * RETURN TABLE char, varchar, text types
     *
     * */
    public function  char_types($table){
        $res = $this->types($table);
        foreach($res as $col => $type){
            if(in_array($type,array('VAR_STRING','STRING','BLOB'))){
                $cols[] = $col;
            }
        }
        return $cols;
    }

    public function  sqlite_version($datapath){
        if(file_exists($datapath)) //make sure file exists before getting its contents
        {
            $content = strtolower(file_get_contents($datapath, NULL, NULL, 0, 40)); //get the first 40 characters of the database file
            $p = strpos($content, "** this file contains an sqlite 2"); //this text is at the beginning of every SQLite2 database
            if($p!==false) //the text is found - this is version 2
                return 2;
            else
                return 3;
        }
        else //return -1 to indicate that it does not exist and needs to be created
        {
            return -1;
        }
    }
	
    public function  mysql_con(string $dbhost,string $dbname,string $dbuser,string $dbpass){
        try	{
			//mysql:unix_socket=/var/run/mysqld/mysqld.sock;charset=utf8mb4;dbname=$dbname
            return new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass,
                array(
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION,
                    PDO::ERRMODE_WARNING,
                    PDO::ATTR_EMULATE_PREPARES => FALSE,
					PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
                    PDO::ATTR_PERSISTENT => false
                ));

        }	catch(PDOException $error)	{
            return $error->getCode();
        }

    }

    public function  pgsql_connect(string $dbname,$dbuser,$dbpass){
        try	{
            return new PDO("pgsql:host=localhost;port=5432;dbname=$dbname;user=$dbuser;password=$dbpass");
        }	catch(PDOException $error)	{
            echo $error->getMessage();
            return false;
        }
    }

    //key listing exists in one db return which db
    public function  redisdb(string $key){
        global $GLOBAL;
        $key= is_array($key) ? $key[0] : $key;
        $fkey= strpos($key, '_') !== false ? explode('_',$key)[0] : $key;
        $r= array_key_exists($key,$GLOBAL['rservices']) ? $GLOBAL['rservices'][$key] : (array_key_exists($fkey,$GLOBAL['rservices']) ? $GLOBAL['rservices'][$fkey] :'false');
        return (int)$r;
    }
	
	public function  create_db(string $dbname,string $dbhost,string $dbuser,string $dbpass){
	try {
		$this->_db = new PDO("mysql:host=$dbhost", $dbuser, $dbpass);
		$this->_db->exec("CREATE DATABASE `$dbname`;
				CREATE USER '$dbuser'@'localhost' IDENTIFIED BY '$dbpass';
				GRANT ALL ON `$dbname`.* TO '$dbuser'@'localhost';
				FLUSH PRIVILEGES;") 
		or die(print_r($this->_db->errorInfo(), true));

	} catch (PDOException $e) {
		die("DB ERROR: ". $e->getMessage());
	}
}
    /*
     * BASIC function
     * f FETCH
     * fa FETCH ALL
     * q QUERY (INSERT AND UPDATE)
     * INS
     * exec
    */
    public function exec(string $q){
		 $s= $this->_db->exec($q);
		 return $s;
	}	
   public function f(string $q, array $params = []): array|string|bool {
            $res = $this->_db->prepare($q);
            $res->execute($params);
            if (!$res) return FALSE;
            return $res->fetch(PDO::FETCH_ASSOC);
    }

 public function select(string $table, array $where = [], string $orderBy = '', int $limit = 0): ?array {
        // 1. Build the SQL query string
        $sql = "SELECT * FROM `$table`";
        if (!empty($where)) {
            $whereClause = implode(' AND ', array_map(function  ($key, $value) {
                return "`$key` = ?";
            }, array_keys($where), $where));
            $sql .= " WHERE $whereClause";
        }
        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }
        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }

        // 2. Prepare and execute the query using your $this->db object
        $result = $this->fa($sql, array_values($where));

        // 3. Return the result (e.g., an array of data)
        return $result;
    }

    /*
    *	Fetch MANY result
    *	Updated with memcache
    */
    public function  fa(string $query, array $params = array()): bool|array    {
		$res = $this->_db->prepare($query);
            $res->execute($params);
		if(!$res) return FALSE;
            return $res->fetchAll(PDO::FETCH_ASSOC);
            $res->closeCursor();
    }

    /*
    *Query Method replaces standard pdo query method
    Usage: with	INSERT, UPDATE, DELETE queries
    Updated with memcache
    */
    public function  q(string $q, array $params = array()):bool {
            $res = $this->_db->prepare($q);
            $res->execute($params);
            if (!$res)return FALSE;            
            return true;
            
            $res->closeCursor();
    }
    /*
   * INSERT WITH RETURN ID ,
   * UPDATE
   * A) RETURNS FALSE,
   * B) Autoincrement with NULL or insert $id NO NEED FOR fetchMax function  
   * c) NO NEED FOR QUESTIONMARKS
   * */
       public function  ins(string $table, array $params = array(),$id='NULL'): ?int{
		$qmk= implode(',', array_fill(0, count($params), '?'));
         if(is_assoc($params)){
            $rows= $k= '("'.implode('","',array_keys($params)).'")';
            $values = "$rows VALUES ($qmk)";
            $params= array_values($params);
        $res = $this->_db->prepare("INSERT INTO $table $values");
        $res->execute($params);
        if (!$res) {return false;} else {return $this->_db->lastInsertId();}
        $res->closeCursor();
		 }
		}

    //count_ results
    public function  count_(string $rowt, $table, $clause = null, $params = array()): ?int {
            $result = $this->_db->prepare("SELECT COUNT($rowt) FROM $table $clause");
            $result->execute($params);
            if (!$result) return FALSE;
            return $result->fetchColumn();
    }

    //count_ results
    public function  counter(string $query = null, $params = array()){
            $result = $this->_db->prepare($query);
            $result->execute($params);
            if (!$result) return FALSE;
            return $result->fetchColumn();
    }

    public function  columns(string $table): ?array{
	//	return array_keys(jsonget(GAIAROOT."schema.json")[$table]);
        $q = $this->_db->prepare("DESCRIBE $table");
        $q->execute();
        return $q->fetchAll(PDO::FETCH_COLUMN);
    }
    /*
create key->value list with two rows from database
    fPairs to replace fetchCoupleList
    UPDATE WITH PDO::FETCH_KEY_PAIR
    NEW METHOD 1
*/
    public function  fPairs(string $row1, string $row2, string $table, $clause = ''): ?array {
        return $this->_db->query("SELECT $row1,$row2 FROM $table $clause")->fetchAll(PDO::FETCH_KEY_PAIR);
    }

/*
  fUnique SELECT uid,cv.* FROM cv returns [uid]=>array(id=1,title=asdfdsf)
  for cases we want unique id to avoid for loops
  NEW METHOD 2
 * */
    public function  fUnique(string $query): ?array {
        return $this->_db->query($query)->fetchAll(PDO::FETCH_UNIQUE);
    }
    /*
      fGroup SELECT uid,id,title FROM cv returns
      [uid]=>array(
             [0]=>(id=1,title=asdfdsf)
             [1]=>
      good for nested arrays to avoid for loops
      NEW METHOD 3
     * */
    public function  fGroup($query): ?array {
        return $this->_db->query($query)->fetchAll(PDO::FETCH_GROUP);
    }
    /*
      fPairs to replace fetchList and fetchRowList
      returns a simple array list
      NEW METHOD 4
     * */
    public function  fList(string|array $rows, string $table, $clause = ''): ?array {
        return $this->_db->query("SELECT $rows from $table $clause")->fetchAll(PDO::FETCH_COLUMN);
    }

    //FAST NEW function   FROM CMS CLASS
    //update of fetchRowList and fetchCoupleList
    public function  fetchList($rows, string $table, $clause=''): ?array {
        $list=array();
        //fetchRowList
        if(is_array($rows)){
            $row1=$rows[0];$row2=$rows[1];
            $fetch=$this->fa("SELECT $row1,$row2 FROM $table $clause");
            if(!empty($fetch)) {
                $row1 = strpos($row1, '.') !== false ? explode('.', $row1)[1] : $row1;
                $row2 = strpos($row2, '.') !== false ? explode('.', $row2)[1] : $row2;
                for ($i = 0; $i < count($fetch); $i++) {
                    $list[$fetch[$i][$row1]] = $fetch[$i][$row2];
                }
            }else{return false;}
            //fetchCoupleList
        }else{
            $fetch=$this->fa("SELECT $rows FROM $table $clause");
            if(!empty($fetch)) {
                for ($i = 0; $i < count($fetch); $i++) {
                    $list[] = $fetch[$i][$rows];
                }
            }else{return false;}
        }
        return $list;
    }

    public function  truncate(string $table){
            $q = $this->_db->exec("TRUNCATE TABLE $table");
    }

    //update of fetchRowList and fetchCoupleList
    public function  fl(string|array $rows, string $table, $clause=''): bool|array{
            $list = array();
            //fetchRowList
            if (is_array($rows)) {
                //fetchCoupleList
                $row1 = $rows[0];
                $row2 = $rows[1];
                $fetch = $this->fa("SELECT $row1,$row2 FROM $table $clause");
                if (!empty($fetch)) {
                    for ($i = 0; $i < count($fetch); $i++) {
                        $list[$fetch[$i][$row1]] = $fetch[$i][$row2];
                    }
                    return $list;
                } else {
                    return false;
                }
            } else {
      //FETCHrOWLIST
                $fetch = $this->fa("SELECT $rows FROM $table $clause");
                if (!empty($fetch)) {
                    for ($i = 0; $i < count($fetch); $i++) {
                        $list[] = $fetch[$i][$rows];
                    }
                    return $list;
                } else {
                    return false;
                }
            }
    }

    //only for maria
    function   trigger_list(){
        $triggers = $this->fetchAll("SHOW TRIGGERS");
        $list=array();
        if(!empty($triggers)) {
            for ($i = 0; $i < count($triggers); $i++) {
                $list[] = $triggers[$i]['Trigger'];
            }
        }
        return $list;
    }
}
?>