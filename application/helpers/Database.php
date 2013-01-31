<?php

class Database extends PDO {

	public function __construct($dbType, $dbHost, $dbName, $dbUser, $dbPassword) {
		parent::__construct($dbType . ':host=' . $dbHost . '; dbname=' . $dbName, $dbUser, $dbPassword);
	}

	public function select($sqlQuery, $params = false) {
		
		$handler = $this->prepare($sqlQuery);
		if($params !== false) {
			foreach($params as $param => $value) {
				// Workaround for https://bugs.php.net/bug.php?id=44639, which did not take me an evening to figure out. I want to cry now
				if(is_int($value)) {
					$handler->bindParam($param, $value, PDO::PARAM_INT);	
				} else {
					$handler->bindParam($param, $value);
				}
			}
		}

		if($handler->execute()) {
			return $handler->fetchAll(PDO::FETCH_ASSOC);
		} else {
			echo "Fool"; print_r($handler->errorInfo());
			return false;
		}
	}

/* DUNNO HOW TO TEST IT DUE SHIT, mvh Laff
	public function update($sqlQuery, $values) {

		try {

			$stmt = $this->prepare($sqlQuery);
			$stmt->execute($values);
		} catch (PDOexception $excpt) {
			echo "Database operation failed!";
			throw $excpt;
		}
	}
 */

/* DUNNO HOW TO TEST IT DUE SHIT, mvh Laff
	public function insert($sqlQuery, $values) {

		try {

			$stmt = $this->prepare($sqlQuery);
			$stmt->execute($values);

		} catch (PDOexception $excpt) {
			echo "Database operation failed!";
			throw $excpt;
		}
	}
 */
	public function delete() {}
}
