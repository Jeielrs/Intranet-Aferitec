<?php

namespace App\Models;

use INTRA\Model\Model;

class Produto extends Model {

	private $codigo_produto;
	private $codigo_omie;
	private $codigo_integracao;
	private $descricao;
	private $dt_alter;
	private $user_alter;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}

//criar Produto
	public function criarProduto() {
		$query = "INSERT INTO produto (codigo_produto, codigo_omie, codigo_integracao, descricao, user_alter) VALUES (:codigo_produto, :codigo_omie, :codigo_integracao, :descricao,
			 :user_alter)";

		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':codigo_produto', $this->__get('codigo_produto'));
		$stmt->bindvalue(':codigo_omie', $this->__get('codigo_omie'));
		$stmt->bindvalue(':codigo_integracao', $this->__get('codigo_integracao'));
		$stmt->bindvalue(':descricao', $this->__get('descricao'));
		$stmt->bindvalue(':user_alter', $this->__get('user_alter'));

		if ($stmt->execute()) {
			$result = $this->db->lastInsertId();
		} else {
			$result = "Erro no cadastro do produto" . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}
//

//Update Produto
	public function atualizarProduto() {
		$query = "UPDATE produto SET codigo_omie = :codigo_omie,
				codigo_integracao = :codigo_integracao, descricao = :descricao, dt_alter = :dt_alter,
				user_alter = :user_alter WHERE codigo_produto = :codigo_produto";

		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':codigo_produto', $this->__get('codigo_produto'));
		$stmt->bindvalue(':codigo_omie', $this->__get('codigo_omie'));
		$stmt->bindvalue(':codigo_integracao', $this->__get('codigo_integracao'));
		$stmt->bindvalue(':descricao', $this->__get('descricao'));
		$stmt->bindvalue(':dt_alter', $this->__get('dt_alter'));
		$stmt->bindvalue(':user_alter', $this->__get('user_alter'));

		if (!$stmt->execute()) {
			$update = "Erro ao atualizar o produto: " . print_r($stmt->errorInfo(), true);
		} else {
			$update = "Produto atualizado!";
		}

		return $update;
	}
//

//Delete Produto
	public function excluirProduto() {
		$query = "DELETE FROM produto WHERE codigo_produto = :codigo_produto";

		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':codigo_produto', $this->__get('codigo_produto'));

		if (!$stmt->execute()) {
			$delete = "Erro ao excluir o produto: " . print_r($stmt->errorInfo(), true);
		} else {
			$delete = "Produto excluído!";
		}

		return $delete;
	}
//

//Buscar Produto
	public function buscarProduto($codigo_produto) {
		$query = "SELECT codigo_produto, codigo_omie, codigo_integracao,
					descricao, dt_alter, user_alter
					FROM produto WHERE codigo_produto = $codigo_produto";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}
//

//Buscar Produto pelo codigo PRD
	public function buscaCodigoOmie($codprod) {
		$query = "SELECT codigo_produto
					FROM produto WHERE codigo_omie = '$codprod'";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetch(\PDO::FETCH_ASSOC);
			//print_r($result['codigo_produto']);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}
//

//Listar Produtos
	public function listarProdutos() {
		$query = "SELECT codigo_produto, codigo_omie, codigo_integracao,
					descricao, dt_alter, user_alter
					FROM produto ORDER BY 2";
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