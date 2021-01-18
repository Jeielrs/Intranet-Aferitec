<?php

namespace App;

class Connection {

	public static function getDb() {
		try {

			$conn = new \PDO(
				"mysql:host=localhost;dbname=db_intranet;charset=utf8",
				"admintranet",
				"passamundo"
			);

			return $conn;

		} catch (\PDOException $e) {
			echo $e->getMessage();
		}
	}
}

?>