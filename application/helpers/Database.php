<?php
/**
 * Wrapper class for PDO
 * Exposes most used DB-functions
 *  
 * @author Team Henkars
 */

class Database extends PDO {

	/**
	* Constructor. sets up database-connection.
	* 
	* @param string $dbType
	* @param string $dbHost
	* @param string $dbName
	* @param string $dbUser
	* @param string $dbPassword
	*/
	public function __construct($dbType, $dbHost, $dbName, $dbUser, $dbPassword) {
		parent::__construct($dbType . ':host=' . $dbHost . '; dbname=' . $dbName, $dbUser, $dbPassword, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
		);
	}

	/**
	* Select one row from database. returns the first hit or false.
	* 
	* @param string $sqlQuery
	* @param array $params defaults to false
	* @return bool|array
	*/
	public function selectOne($sqlQuery, $params = false) {
		$result = $this->select($sqlQuery, $params);
		if(count($result) == 0) {
			return false;
		} else {
			return $result[0];
		}
	}

	/**
	* Select rows from database. Returns results as two dim assoc array or false.
	* 
	* @param string $sqlQuery
	* @param array $params
	* @return bool|array
	*/
	public function select($sqlQuery, $params = false) {	
		$handler = $this->prepare($sqlQuery);
		if($params !== false) {
			foreach($params as $param => $value) {
				// Workaround for https://bugs.php.net/bug.php?id=44639, which did not take me an evening to figure out. I want to cry now
				if(is_int($value)) {
					$handler->bindValue($param, $value, PDO::PARAM_INT);	
				} else {
					$handler->bindValue($param, $value);
				}
			}
		}

		if($handler->execute()) {
			return $handler->fetchAll(PDO::FETCH_ASSOC);
		} else {
			return false;
		}
	}

	/**
	* Function to insert or update the database.
	* 
	* @param string $sqlQuery
	* @param array $params
	* @return bool|int
	*/
	public function insert($sqlQuery, $params = false) {
		$handler = $this->prepare($sqlQuery);
		if($params !== false) {
			foreach($params as $param => $value) {
				if(is_int($value)) {
					$handler->bindValue($param, $value, PDO::PARAM_INT);	
				} else {
					$handler->bindValue($param, $value);
				}
			}
		}

		if($handler->execute()) {
			return $this->lastInsertId();	// You would think this should be in a transaction. But according to manual this is done per connection.
		} else {
			return false;
		}
	}
}
