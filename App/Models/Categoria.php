<?php

namespace App\Models;

use INTRA\Model\Model;

class Categoria extends Model {

	private $codigo;
	private $descricao;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}

	//criar Categoria
	public function criarCategoria() {
		$query = "INSERT INTO categoria (codigo, descricao) VALUES (:codigo, :descricao)";

		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':codigo', $this->__get('codigo'));
		$stmt->bindvalue(':descricao', $this->__get('descricao'));

		if ($stmt->execute()) {
			$result = $this->db->lastInsertId();
		} else {
			$result = "Erro no cadastro da categoria" . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}
//

//Update Categoria
	public function atualizarCategoria() {
		$query = "UPDATE categoria SET codigo = :codigo, descricao = :descricao
					WHERE codigo = :codigo";

		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':codigo', $this->__get('codigo'));
		$stmt->bindvalue(':descricao', $this->__get('descricao'));

		if (!$stmt->execute()) {
			$update = "Erro ao atualizar a categoria: " . print_r($stmt->errorInfo(), true);
		} else {
			$update = "Categoria atualizada!";
		}

		return $update;
	}
//

//Delete Categoria
	public function excluirCategoria() {
		$query = "DELETE FROM categoria WHERE codigo = :codigo";

		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':codigo', $this->__get('codigo'));

		if (!$stmt->execute()) {
			$delete = "Erro ao excluir a categoria: " . print_r($stmt->errorInfo(), true);
		} else {
			$delete = "Categoria excluída!";
		}

		return $delete;
	}
//

//Buscar Categoria
	public function buscarCategoria($codigo) {
		$query = "SELECT codigo, descricao
					FROM categoria WHERE codigo = $codigo";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}
//

//Listar Categorias
	public function listarCategorias() {
		$query = "SELECT codigo, descricao
					FROM categoria ORDER BY 2";
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