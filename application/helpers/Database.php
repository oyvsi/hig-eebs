<?php

class Database extends PDO {

	/**
	* constructor. sets up class database.
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
	* fuction to select one object from database. returns the first hit or false.
	* @param string $sqlQuery
	* @param array $params
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
	* fuction to select objects from database. returns results or false.
	* @param string $sqlQuery
	* @param array $params
	* @return bool|array
	*/
	public function select($sqlQuery, $params = false) {
		//	echo "SQL query! $sqlQuery <br />";	
		$handler = $this->prepare($sqlQuery);
		if($params !== false) {
			foreach($params as $param => $value) {
				// Workaround for https://bugs.php.net/bug.php?id=44639, which did not take me an evening to figure out. I want to cry now
				//echo "param $param Value $value";
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
			// WHAT IS THIS? fucks up the profile css. regards, lulaf
			//echo "Fool"; print_r($handler->errorInfo());
			return false;
		}
	}

	/**
	* Function to insert or update the database.
	* @param string $sqlQuery
	* @param array $params
	* @return bool|int
	*/
	public function insert($sqlQuery, $params = false) {
		//		echo "Query was $sqlQuery";
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
			return $this->lastInsertId();	// You would think this should be in a transaction. But according to manual this is done per connection.
		} else {
			echo "Fool"; print_r($handler->errorInfo());
			return false;
		}
	}
}
