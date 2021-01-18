<?php

namespace App\Models;

use INTRA\Model\Model;

class HorariosHO extends Model {

	private $data;
	private $tipo;
	private $horario;
	private $id_user;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}

//Registrar dia
	public function registraHora() {
		$query = "INSERT INTO horarios_home_office (data, tipo, horario, id_user) VALUES (:data, :tipo, :horario, :id_user)";

		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':data', $this->__get('data'));
		$stmt->bindvalue(':tipo', $this->__get('tipo'));
		$stmt->bindvalue(':horario', $this->__get('horario'));
		$stmt->bindvalue(':id_user', $this->__get('id_user'));

		if ($stmt->execute()) {
			$result = $this->db->lastInsertId();
		} else {
			$result = "Erro ao registrar o horário: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}
//

//Consulta dia
	public function consultaHora($data, $id_user) {
		$query = "SELECT id, data, tipo, horario, id_user FROM horarios_home_office WHERE data = '$data' and id_user = $id_user";

		$stmt = $this->db->prepare($query);

		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_OBJ);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}
//

}
?>