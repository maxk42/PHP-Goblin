<?php

require_once('class.MySQL.php');

class Cache {
	public $db;

	function __construct(MySQL $db = null) {
		if($db == null)
			$db = MySQL::create();

		$this->db = $db;
	}

	public function store($key, $data) {
		return $this->db->query("REPLACE INTO `cache` SET `key` = '" . $this->db->escape($key) . "' AND `value` = '" . $this->db->escape((string) $data) . "';");
	}
	
	public function retrieve($key) {
		return $this->db->query("SELECT * FROM `cache` WHERE `key` = '" . $this->db->escape($key) . "'");
	}
	
	public function expire($key) {
		// timeout cache item '$key'
		return $this->db->query("DELETE FROM `cache` WHERE `key` = '" . $this->db->escape($key) . "'");
	}
}

