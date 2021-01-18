<?php

namespace App\Models;

use INTRA\Model\Model;

class RCfiles extends Model {

	private $id;
	private $rc;
	private $id_file;
	private $filename;
	private $extension;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}

	//Adicionar
	public function addFile($codreq) {

		$query = "INSERT INTO rc_files (rc, id_file, filename, extension) VALUES ($codreq, :id_file,
				:filename, :extension)";
		$stmt = $this->db->prepare($query);
		$stmt->bindvalue(':id_file', $this->__get('id_file'));
		$stmt->bindvalue(':filename', $this->__get('filename'));
		$stmt->bindvalue(':extension', $this->__get('extension'));

		if ($stmt->execute()) {
			$result = "ok";
		} else {
			$result = "Erro ao anexar arquivo: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	public function buscaPorRC($codreq) {
		$query = "SELECT rc.codreq, file.id, file.rc, file.id_file,
				file.filename, file.extension
				FROM rc AS rc
				LEFT JOIN rc_files AS file ON file.rc = rc.codreq
				LEFT JOIN usuarios AS user ON user.id = rc.id_user
				LEFT JOIN rc_status AS status ON status.id = rc.status
				WHERE rc.codreq = $codreq
				ORDER BY 1";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	public function buscaPorUserFiles($user) {
		$query = "SELECT rc.codreq, file.id, file.rc, file.id_file,
				file.filename, file.extension
				FROM rc AS rc
				LEFT JOIN rc_files AS file ON file.rc = rc.codreq
				LEFT JOIN usuarios AS user ON user.id = rc.id_user
				LEFT JOIN rc_status AS status ON status.id = rc.status
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

	//busca p/ liberação por $owner
	public function buscaAprovacaoFiles($owner) {
		$query = "SELECT rc.codreq, file.id, file.rc, file.id_file,
				file.filename, file.extension
				FROM rc AS rc
				LEFT JOIN rc_files AS file ON file.rc = rc.codreq
				LEFT JOIN usuarios AS user ON user.id = rc.id_user
				LEFT JOIN rc_status AS status ON status.id = rc.status
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

	public function buscaLiberacaoFiles($owner) {
		$query = "SELECT rc.codreq, file.id, file.rc, file.id_file,
				file.filename, file.extension
				FROM rc AS rc
				LEFT JOIN rc_files AS file ON file.rc = rc.codreq
				LEFT JOIN usuarios AS user ON user.id = rc.id_user
				LEFT JOIN rc_status AS status ON status.id = rc.status
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

	//busca p/ liberação (todos)
	public function buscaAprovacaoAllFiles() {
		$query = "SELECT rc.codreq, file.id, file.rc, file.id_file,
				file.filename, file.extension
				FROM rc AS rc
				LEFT JOIN rc_files AS file ON file.rc = rc.codreq
				LEFT JOIN usuarios AS user ON user.id = rc.id_user
				LEFT JOIN rc_status AS status ON status.id = rc.status
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

	public function buscaLiberacaoAllFiles() {
		$query = "SELECT rc.codreq, file.id, file.rc, file.id_file,
				file.filename, file.extension
				FROM rc AS rc
				LEFT JOIN rc_files AS file ON file.rc = rc.codreq
				LEFT JOIN usuarios AS user ON user.id = rc.id_user
				LEFT JOIN rc_status AS status ON status.id = rc.status
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

	////buscar por rc
	//public function buscaPorRC($rc) {
	//	$query = "SELECT rc.codint, rc.codreq, re.categoria, rc.dpto,
	//			rc.dt_create, rc.dt_mov, rc.dtsugestao, rc.id_user,
	//			rc.owner, rc.obs, rc.status, item.coditem, item.codprod,
	//			item.obsitem, item.precounit, item.qtde, file.id_file,
	//			file.filename, file.extension
	//			FROM rc AS rc
	//			LEFT JOIN itens_rc AS item
	//			LEFT JOIN rc_files AS file
	//			LEFT JOIN usuarios AS user WHERE rc.id_user = $user";
	//	$stmt = $this->db->prepare($query);
	//	$stmt->execute;

	//	return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	//}

}

?>