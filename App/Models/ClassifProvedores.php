<?php

namespace App\Models;

use INTRA\Model\Model;

class ClassifProvedores extends Model {

	private $id;
	private $provedor;
	private $prazo;
	private $preco;
	private $qualidade;
	private $obs;
	private $data;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}

	public function addClassify() {

		$query = "INSERT INTO classif_provedores (provedor, prazo, preco, qualidade, obs, codreq)
				VALUES (:provedor, :prazo, :preco, :qualidade, :obs, :codreq)";

		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':provedor', $this->__get('provedor'));
		$stmt->bindvalue(':prazo', $this->__get('prazo'));
		$stmt->bindvalue(':preco', $this->__get('preco'));
		$stmt->bindvalue(':qualidade', $this->__get('qualidade'));
		$stmt->bindvalue(':obs', $this->__get('obs'));
		$stmt->bindvalue(':codreq', $this->__get('codreq'));

		if ($stmt->execute()) {
			$result = "ok";
		} else {
			$result = "Erro ao cadastrar classificação: " . print_r($stmt->errorInfo(), true);
			echo '<pre>' . print_r($stmt, true);
		}

		return $result;
	}

	public function buscaPorData($date1, $date2) {

		$query = "SELECT id, provedor, prazo, preco, qualidade, obs, data, codreq FROM classif_provedores WHERE data BETWEEN '$date1' and '$date2' ORDER BY 7 DESC";

		$stmt = $this->db->prepare($query);

		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_OBJ);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	public function buscaGeral() {

		$query = "SELECT id, provedor, prazo, preco, qualidade, obs, data, codreq FROM classif_provedores ORDER BY 7 DESC";

		$stmt = $this->db->prepare($query);

		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_OBJ);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	public function buscaPorProvedor($provedor) {

		$query = "SELECT id, provedor, prazo, preco, qualidade, obs, data, codreq FROM classif_provedores WHERE provedor LIKE '$provedor' ORDER BY 7 DESC";

		$stmt = $this->db->prepare($query);

		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_OBJ);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	public function buscaPorRC($rc) {

		$query = "SELECT id, provedor, prazo, preco, qualidade, obs, data, codreq FROM classif_provedores WHERE codreq = $rc ORDER BY 7 DESC";

		$stmt = $this->db->prepare($query);

		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_OBJ);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	public function listarProvedores() {

		$query = "SELECT DISTINCT provedor FROM classif_provedores";

		$stmt = $this->db->prepare($query);

		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_OBJ);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

}

?>