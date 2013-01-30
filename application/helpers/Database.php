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

	public function update() {}
	public function insert() {}
	public function delete() {}
}
