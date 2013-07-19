<?php

//////////////////////////////////////////
// MySQL Object Interface for PHP 4
// Version 0.5
// 
// 
// = Release History =
// Version 0.5 - Removed underutilized activeTable property.
// Version 0.4 - Added create() method.  Over a year of reliable service has intervened since the first release.
// Version 0.2 - First release.  Surprisingly stable.
// 
// 

class MySQL {
	public $server;
//	public $port;					// specify in the $server string instead
	public $userName;
	public $password;
	public $dataBase;				// database to work with
	
	public $query;
	
	public $errorMsg;
	
	public $dbp;					// database pointer/resource
	public $rp;					// result pointer/resource (result of a query)
	
	public $results;				// result of last query
	
	function MySQL($server = FALSE, $userName = FALSE, $password = FALSE, $dataBase = FALSE) {
		// assign default / uninitialized values
		$this->dbp = FALSE;
		$this->errorMsg = "";
		
		return $this->connect($server, $userName, $password, $dataBase);
	}
	
	function connect($server = FALSE, $userName = FALSE, $password = FALSE, $dataBase = FALSE) {
		// assign values as appropriate, ignoring FALSE / unset values
		if($server !== FALSE)			// we're not using a call to $this->MySQL
			$this->server = $server;	// because it will set values that have not
		if($userName !== FALSE)			// been provided to FALSE.
			$this->userName = $userName;
		if($password !== FALSE)
			$this->password = $password;
		if($dataBase !== FALSE)
			$this->dataBase = $dataBase;
		
		// establish the connection, reporting errors
		$this->dbp = mysql_connect($this->server, $this->userName, $this->password);
		if(!$this->dbp) {
			$this->errorMsg = mysql_error();
			return FALSE;			// return false to indicate failure
		}
		
		// connect to the correct database, if appropriate, reporting errors
		if($this->dataBase) {
			$this->rp = mysql_select_db($this->dataBase, $this->dbp);
			if(!$this->rp) {
				$this->errorMsg = mysql_error();
				return FALSE;
			}
		}
		
		return(TRUE);
	}
	
	function query($query) {
	// returns the results of a given query in an associative array on
	// success or FALSE on failure
		if(!$this->dbp)				// if the connection has not been established, attempt to establish it
			$this->connect();
		
		$this->query = $query;
		return $this->execute();
	}
	
	function execute() {
		$this->rp = mysql_query($this->query, $this->dbp);
		if(!$this->rp) {
			$this->errorMsg = mysql_error($this->dbp);
			return FALSE;
		}
		
		if($this->rp === TRUE)			// for INSERT, UPDATE, DELETE, DROP, etc. returning a bool
			return TRUE;
		
		$this->results = array();
		while($row = mysql_fetch_assoc($this->rp))
			$this->results[] = $row;
		
		return $this->results;
	}
	
	function close() {
		$tmp = mysql_close($this->dbp);
		if(!$tmp)
			$this->errorMsg = mysql_error($dbp);
		return $tmp;
	}
	
	function escape($string) {			// shorter way of utilizing mysql_real_escape_string()
		return mysql_real_escape_string($string, $this->dbp);
	}
	
	function &create() {
		$db = new MySQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DBNAME);
		return $db;
	}
}




