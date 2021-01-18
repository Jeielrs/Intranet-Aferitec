<?php

namespace App\Models;

use INTRA\Model\Model;

class ItensRC extends Model {

	private $id_rc;
	private $coditem;
	private $codprod;
	private $obsitem;
	private $precounit;
	private $qtde;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}

	//Adicionar
	public function addItem($codreq) {

		$query = "INSERT INTO itens_rc (codreq, coditem, codprod, descrprod, obsitem, precounit, qtde, total) VALUES
				($codreq, :coditem, :codprod, :descrprod, :obsitem, :precounit, :qtde, :total)";
		$stmt = $this->db->prepare($query);
		$stmt->bindvalue(':coditem', $this->__get('coditem'));
		$stmt->bindvalue(':codprod', $this->__get('codprod'));
		$stmt->bindvalue(':descrprod', $this->__get('descrprod'));
		$stmt->bindvalue(':obsitem', $this->__get('obsitem'));
		$stmt->bindvalue(':precounit', $this->__get('precounit'));
		$stmt->bindvalue(':qtde', $this->__get('qtde'));
		$stmt->bindvalue(':total', $this->__get('total'));

		if ($stmt->execute()) {
			$result = "ok";
		} else {
			$result = "Erro ao adicionar item: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	//Atualiza numero do pedido, fornecedor da RC
	public function sincronizaRC($codreq, $numpedido, $fornec, $coditem) {

		$query = "UPDATE itens_rc SET num_pedido = $numpedido, fornecedor = '$fornec'
		WHERE  codreq = $codreq and coditem = $coditem";

		$stmt = $this->db->prepare($query);

		if (!$stmt->execute()) {
			$update = "Erro ao atualizar a requisição " . $codreq . " (Pedido " . $numpedido . ") :" . print_r($stmt->errorInfo(), true);
		} else {
			$update = "Requisição " . $codreq . " atualizada com sucesso! (Pedido " . $numpedido . ")";
		}

		return $update;

	}

	//Atualiza qtd e valor item rc
	public function sincronizaItemRC($codreq, $coditem, $codprod, $descrprod, $obsitem, $qtd, $vlrunit, $vlrtotal) {

		$query = "UPDATE itens_rc SET codprod = '$codprod', descrprod = '$descrprod',
			obsitem = '$obsitem', precounit = $vlrunit, qtde = $qtd, total = $vlrtotal
			WHERE  codreq = $codreq and coditem = $coditem";

		$stmt = $this->db->prepare($query);

		if (!$stmt->execute()) {
			$update = "Problema ao atualizar o Item " . $codprod . " na RC nº " . $codreq . " (R$ " . $vlrtotal . ") :" . print_r($stmt->errorInfo(), true);
		} else {
			$update = "Item " . $codprod . " atualizado na RC nº " . $codreq . " (R$ " . $vlrtotal . ")";
		}

		return $update;

	}

	public function buscaPorUserItens($user) {
		$query = "SELECT rc.codreq, rc.id_user,	item.coditem, item.codprod,
				item.descrprod, item.obsitem, item.precounit, item.qtde,
				item.total
				FROM rc AS rc
				LEFT JOIN itens_rc AS item ON item.codreq = rc.codreq
				WHERE rc.id_user = $user
				ORDER BY 1";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	public function buscaLiberacaoItens($owner) {
		$query = "SELECT rc.codreq, rc.id_user,	item.coditem, item.codprod,
				item.descrprod, item.obsitem, item.precounit, item.qtde,
				item.total
				FROM rc AS rc
				LEFT JOIN itens_rc AS item ON item.codreq = rc.codreq
				WHERE rc.status = 1
				AND rc.owner = '$owner'
				ORDER BY 1";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	public function buscaLiberacaoAllItens() {
		$query = "SELECT rc.codreq, rc.id_user,	item.coditem, item.codprod,
				item.descrprod, item.obsitem, item.precounit, item.qtde,
				item.total
				FROM rc AS rc
				LEFT JOIN itens_rc AS item ON item.codreq = rc.codreq
				WHERE rc.status = 1
				ORDER BY 1";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	public function buscaAprovacaoItens($owner) {
		$query = "SELECT rc.codreq, rc.id_user,	item.coditem, item.codprod,
				item.descrprod, item.obsitem, item.precounit, item.qtde,
				item.total
				FROM rc AS rc
				LEFT JOIN itens_rc AS item ON item.codreq = rc.codreq
				WHERE rc.status = 2
				AND rc.owner = '$owner'
				ORDER BY 1";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	public function buscaAprovacaoAllItens() {
		$query = "SELECT rc.codreq, rc.id_user,	item.coditem, item.codprod,
				item.descrprod, item.obsitem, item.precounit, item.qtde,
				item.total
				FROM rc AS rc
				LEFT JOIN itens_rc AS item ON item.codreq = rc.codreq
				WHERE rc.status = 2
				ORDER BY 1";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	//buscar pra sincronizar pedidos
	public function buscaSincAllItens() {
		$query = "SELECT rc.codreq, rc.status, rc.id_user,	item.coditem, item.codprod,
				item.descrprod, item.obsitem, item.precounit, item.qtde, rc.dt_create,
				item.total, item.num_pedido, item.fornecedor
				FROM rc AS rc
				LEFT JOIN itens_rc AS item ON item.codreq = rc.codreq
				WHERE rc.status in (3, 4, 5, 6)
				ORDER BY 1,4";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_OBJ);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	//buscar por rc
	public function buscaPorRC($rc) {
		$query = "SELECT rc.codreq, rc.id_user,	item.coditem, item.codprod,
				item.descrprod, item.obsitem, item.precounit, item.qtde,
				item.total
				FROM rc AS rc
				LEFT JOIN itens_rc AS item ON item.codreq = rc.codreq
			 	WHERE rc.codreq = $rc";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	//deletar itens
	public function deleteItens($rc) {
		$query = "DELETE FROM  itens_rc WHERE codreq = $rc";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = "ok";
		} else {
			$result = "Erro ao excluir item: " . print_r($stmt->errorInfo(), true);
		}

		return $result;

	}

	//busca Pedidos para pesquisa da caixa Pesquisar
	public function buscaPedidoPesquisar($codreq) {
		$query = "SELECT DISTINCT rc.codreq, item.codprod, item.descrprod, item.fornecedor,
		item.num_pedido
		FROM rc AS rc
		LEFT JOIN itens_rc AS item ON item.codreq = rc.codreq
		WHERE item.codreq = $codreq
		ORDER BY 2";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_OBJ);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	//busca num_pedido para saber se já foi sincronizada
	public function verificaNumPedido($codreq, $coditem) {
		$query = "SELECT num_pedido FROM itens_rc WHERE codreq = $codreq AND coditem = $coditem";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetch();
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

}

?>