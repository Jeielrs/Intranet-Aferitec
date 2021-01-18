<?php

namespace App\Models;

use INTRA\Model\Model;

class Usuario extends Model {

	private $id;
	private $conta;
	private $senha;
	private $nome;
	private $nivel;
	private $dpto;
	private $key_omie;
	private $responsavel_rc;
	private $email;
	private $ramal;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}

	public function getRamais() {
		$query = "SELECT nome, dpto, ramal FROM usuarios WHERE ramal != 0 ORDER BY 3";
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$ramais = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		return $ramais;

	}

	public function autenticar() {

		$query = "SELECT id, nome, conta, dpto, nivel, key_omie, responsavel_rc, email, ramal FROM usuarios WHERE conta = :conta AND senha = :senha";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':conta', $this->__get('conta'));
		$stmt->bindValue(':senha', $this->__get('senha'));
		$stmt->execute();

		$usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

		if ($usuario['id'] != '' && $usuario['nome'] != '') {
			$this->__set('id', $usuario['id']);
			$this->__set('nome', $usuario['nome']);
			$this->__set('dpto', $usuario['dpto']); //setando no obj usuario, todos os
			$this->__set('nivel', $usuario['nivel']); // dados da tabela usuarios
			$this->__set('key_omie', $usuario['key_omie']);
			$this->__set('responsavel_rc', $usuario['responsavel_rc']);
			$this->__set('email', $usuario['email']);
			$this->__set('ramal', $usuario['ramal']);
			$this->__set('conta', $usuario['conta']);

		}

		return $this;
	}

//Consulta funcionarios
	public function buscaFuncionarios($id_user) {
		switch ($id_user) {

		case 1:
			$query = "SELECT id, nome FROM usuarios WHERE responsavel_rc = 'WANDERSON'";
			break;

		case 2:
			$query = "SELECT id, nome FROM usuarios WHERE responsavel_rc = 'WANDERSON'";
			break;

		case 3:
			$query = "SELECT id, nome FROM usuarios WHERE responsavel_rc = 'MAICON'";
			break;

		case 14:
			$query = "SELECT id, nome FROM usuarios WHERE responsavel_rc = 'ROBERTO'";
			break;

		case 6:
			$query = "SELECT id, nome FROM usuarios WHERE responsavel_rc = 'VINICIUS'";
			break;
		}

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

//Consulta nomes
	public function getNames($id) {
		$query = "SELECT nome FROM usuarios WHERE id = $id";

		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}
//

//Lista Usuarios
	public function listaUsuarios() {
		$query = "SELECT nome FROM usuarios";

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