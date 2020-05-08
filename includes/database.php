<?php
	define("DB_NAME", "graphical_password_authentication");
	define("DB_USER", "root");
	define("DB_PASSWORD", "");
	define("DB_SERVER", "localhost");
?>

<?php

    class MySQLDatabase {

        private $connection;

        function __construct() {
            $this->open_connection();
        }

    	public function open_connection() {
    		$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD);
    		if(!$this->connection){
    			die("Database connection failed: " . mysqli_connect_error());
    		}else{
    			$db_select = mysqli_select_db($this->connection, DB_NAME);
    			if(!$db_select) {
    				die("Database selection failed: " . mysqli_connect_error());
    			}
    		}
    	}

    	public function close_connection(){
    		if(isset($this->connection)){
    			mysqli_close($this->connection);
    			unset($this->connection);
    		}
    	}

    	public function query($sql){
    		$result = mysqli_query($this->connection, $sql);
    		$this->confirm_query($result);
    		return $result;
    	}

    	public function free_result($result){
    		mysqli_free_result($result);
    	}
    	
    	//database-neutral methods
    	public function fetch_array($result_set) {
    	   return mysqli_fetch_array($result_set);
    	}
    	  
    	public function num_rows($result_set) {
    	   return mysqli_num_rows($result_set);
    	}
    	  
    	public function insert_id() {
    	   //Get the last id inserted over the current db connection
    	   return mysqli_insert_id($this->connection);
    	}
    	  
    	public function affected_rows() {
    	   return mysqli_affected_rows($this->connection);
    	}

    	public function mysqli_prep($value){
    	   return mysqli_real_escape_string($this->connection, $value);
    	}

    	private function confirm_query($result) {
            if (!$result){
                $output = "Database query failed: " . mysqli_error($this->connection) . "<br /><br />";
                die( $output );
        	}
    	}	
    }

    $database = new MySQLDatabase();
    $db =& $database;

?>