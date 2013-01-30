<?php

class Database extends PDO {

	public function __construct($dbType, $dbHost, $dbName, $dbUser, $dbPassword) {
		parent::__construct($dbType . ':host=' . $dbHost . '; dbname=' . $dbName, $dbUser, $dbPassword);
	}

	public function select($sqlQuery) {
		$handler = $this->prepare($sqlQuery);
		if($handler->execute()) {
			return $handler->fetchAll(PDO::FETCH_ASSOC);
		} else {
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
