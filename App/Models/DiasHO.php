<?php

namespace App\Models;

use INTRA\Model\Model;

class DiasHO extends Model {

	private $data;
	private $atividades;
	private $obs;
	private $id_user;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}

//Registrar dia
	public function registraDia() {
		$query = "INSERT INTO dias_home_office (data, atividades, obs, id_user) VALUES (:data, :atividades, :obs, :id_user)";

		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':data', $this->__get('data'));
		$stmt->bindvalue(':atividades', $this->__get('atividades'));
		$stmt->bindvalue(':obs', $this->__get('obs'));
		$stmt->bindvalue(':id_user', $this->__get('id_user'));

		if ($stmt->execute()) {
			$result = $this->db->lastInsertId();
		} else {
			$result = "Erro ao registrar o dia: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}
//

//Consulta dia
	public function consultaDia($data, $id_user) {
		$query = "SELECT data, atividades, obs, id_user FROM dias_home_office WHERE data = '$data' and id_user = $id_user ORDER BY 1";

		$stmt = $this->db->prepare($query);

		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}
//

//Consulta todos os usuarios no dia
	public function consultaDiaAllUsers($data) {
		$query = "SELECT id, data, atividades, obs, id_user FROM dias_home_office WHERE data = '$data' ORDER BY 1";
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

//Consulta todos dias de funcionario
	public function consultaDiaUnicoUser($user) {
		$query = "SELECT data, atividades, obs, id_user FROM dias_home_office WHERE id_user = $user";
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

//Update dia
	public function updateDia() {
		$query = "UPDATE dias_home_office SET atividades = :atividades, obs = :obs
				WHERE data = :data and id_user = :id_user";

		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':data', $this->__get('data'));
		$stmt->bindvalue(':atividades', $this->__get('atividades'));
		$stmt->bindvalue(':obs', $this->__get('obs'));
		$stmt->bindvalue(':id_user', $this->__get('id_user'));

		if (!$stmt->execute()) {
			$update = "Erro ao atualizar campos: " . print_r($stmt->errorInfo(), true);
		} else {
			$update = "campos atualizados!";
		}

		return $update;
	}
//

}
?>