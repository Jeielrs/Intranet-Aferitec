<?php

namespace App\Models;

use INTRA\Model\Model;

class Departamento extends Model {

	private $codigo;
	private $descricao;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}

//criar Departamento
	public function criarDepartamento() {
		$query = "INSERT INTO departamento (codigo, descricao) VALUES (:codigo, :descricao)";

		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':codigo', $this->__get('codigo'));
		$stmt->bindvalue(':descricao', $this->__get('descricao'));

		if ($stmt->execute()) {
			$result = $this->db->lastInsertId();
		} else {
			$result = "Erro no cadastro do departamento" . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}
//

//Update Departamento
	public function atualizarDepartamento() {
		$query = "UPDATE categoria SET codigo = :codigo, descricao = :descricao
					WHERE codigo = :codigo";

		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':codigo', $this->__get('codigo'));
		$stmt->bindvalue(':descricao', $this->__get('descricao'));

		if (!$stmt->execute()) {
			$update = "Erro ao atualizar o departamento: " . print_r($stmt->errorInfo(), true);
		} else {
			$update = "Departamento atualizado!";
		}

		return $update;
	}
//

//Delete Departamento
	public function excluirDepartamento() {
		$query = "DELETE FROM departamento WHERE codigo = :codigo";

		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':codigo', $this->__get('codigo'));

		if (!$stmt->execute()) {
			$delete = "Erro ao excluir o departamento: " . print_r($stmt->errorInfo(), true);
		} else {
			$delete = "Departamento excluído!";
		}

		return $delete;
	}
//

//Buscar Departamento
	public function buscarDepartamento($codigo) {
		$query = "SELECT codigo, descricao
					FROM departamento WHERE codigo = $codigo AND ativo = 'S'";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}
//

//Listar Departamentos
	public function listarDepartamentos() {
		$query = "SELECT codigo, descricao
					FROM departamento ORDER BY 2";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}
//

}

?>