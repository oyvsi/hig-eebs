<?php
/**
 * Abstract class for models. 
 * Implements common model functionality 
 * 
 * @author Team Henkars
 */
abstract class BaseModel {
	protected $db;

	/**
	 * Constructor. Sets up database object.
	 */
	public function __construct() {
		$this->db = new Database(__DB_TYPE, __DB_HOST, __DB_NAME, __DB_USER, __DB_PASS);
	}
   
   	/**
   	 * Store info in object
   	 * @param array associative array
   	 */
	protected function setInfo($assArray) {
		foreach($assArray as $key => $value) {
			$this->$key = $value;
		}	
	}
   
   /**
    * Function to update a view count in database 
    * @param int $id the id to select from when looking for unique
    * @param string $field the field to select from
    * @param string $table the table name
    */
   public function updateViewCount($id, $field, $table) {
		
		$ipAddress = Helpers::getRealIpAddress();
		$reReadLimit = 24;
		$limitTime = strtotime('-' . $reReadLimit . ' hours');
		$check = $this->db->selectOne('SELECT viewID FROM ' . $table . ' 
			WHERE ' . $field . ' = :ID AND ipAddress = :ipAddress AND timestamp BETWEEN :startTime AND :stopTime',
			array(':ID' => $id, ':ipAddress' => $ipAddress, ':startTime' => $limitTime, ':stopTime' => time()));

		// User has not seen this post yet, or not since timelimit. Insert a post view.
		if($check === false) {
			$query = 'INSERT INTO ' . $table . '(' . $field . ', timestamp, ipAddress) VALUES (:ID, :timestamp, :ipAddress)';
			$values = array(':ID' => $id, ':timestamp' => time(), 'ipAddress' => $ipAddress); 
			$this->db->insert($query, $values);
		}
	}
	

}