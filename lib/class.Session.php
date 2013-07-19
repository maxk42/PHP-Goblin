<?php
/**
 * Session handler class.
 *
 */

require_once('class.MySQL.php');

// Automatically set up the session handler, unless the programmer defines SESSION_HANDLER_INVOKED
if(!defined('SESSION_HANDLER_INVOKED')) {
	define('SESSION_HANDLER_INVOKED', true);
	global $sessionHandler;
	//session_write_close();
	$sessionHandler = new Session();
	session_set_save_handler(
		array($sessionHandler, 'open'),
		array($sessionHandler, 'close'),
		array($sessionHandler, 'read'),
		array($sessionHandler, 'write'),
		array($sessionHandler, 'destroy'),
		array($sessionHandler, 'gc')
	);
	session_start();
	register_shutdown_function('session_write_close');	// Ensure the session is written at the close of the program.
}

/**
 * 
 */
class Session {
	public $db;						// MySQL database object
	public $id;						// Session ID
	
	public function __construct($db = null) {
		// Set up the database object.
		if(get_class($db) !== 'MySQL')
			$this->db = MySQL::create();
	}
	
	public function __destruct() {
		
	}
	
	public function open($savePath, $sessName) {
		$this->id = session_id();
		
		return true;
	}
	
	public function close() {		// Nothing to do -- sessions persist
		return true;
	}
	
	public function read($id) {
		if($data = $this->db->query("SELECT * FROM `sessions` WHERE `id` = '" . $id . "';"))
			return $data[0]['data'];										// Undocumented PHP quirk: this must return the ENCODED data from session_encode() in string format.
		
		$this->db->query("REPLACE INTO `sessions` SET `id` = '" . $id . "', `data` = '', `timestamp` = '" . time() . "';");
		return '';
	}
	
	public function write($id, $data) {
		return $this->db->query("REPLACE INTO `sessions` SET `id` = '" . $id . "', `data` = '" . $this->db->escape(session_encode()) . "', `timestamp` = '" . time() . "';");
	}
	
	public function destroy($id) {
		return $this->db->query("REPLACE INTO `sessions` SET `data` = '' WHERE `id` = " . $id . "';");
	}
	
	// Garbage collector - purge old sessions from the database.
	public function gc($maxLifetime) {
		return $this->db->query("DELETE FROM `sessions` WHERE `timestamp` < '" . (time() - 51840000) . "';");		// Sessions will be kept for 600 days since the last action.  De-hardcode this at some point.
	}
}


