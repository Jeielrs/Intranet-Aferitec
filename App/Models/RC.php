<?php

namespace App\Models;

use INTRA\Model\Model;

class RC extends Model {

	private $codint;
	private $codreq;
	private $categoria;
	private $dpto;
	private $dt_create;
	private $dt_mov;
	private $dtsugestao;
	private $id_user;
	private $owner;
	private $obs;
	private $status;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}

//criar RC
	public function criarRC() {
		$query = "INSERT INTO rc (categoria, dpto, dt_mov, dtsugestao, id_user, owner, obs, status, user_alter) VALUES (:categoria, :dpto, :dt_mov, :dtsugestao, :id_user, :owner, :obs, :status, 0)";

		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':categoria', $this->__get('categoria'));
		$stmt->bindvalue(':dpto', $this->__get('dpto'));
		$stmt->bindvalue(':dt_mov', $this->__get('dt_mov'));
		$stmt->bindvalue(':dtsugestao', $this->__get('dtsugestao'));
		$stmt->bindvalue(':id_user', $this->__get('id_user'));
		$stmt->bindvalue(':owner', $this->__get('owner'));
		$stmt->bindvalue(':obs', $this->__get('obs'));
		$stmt->bindvalue(':status', $this->__get('status'));

		if ($stmt->execute()) {
			$result = $this->db->lastInsertId();
		} else {
			$result = "Erro no cadastro da requisição: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}
//

//Atualiza Status
	public function alteraStatus($codreq, $status) {
		if ($status == 6) {
			$query = "UPDATE rc SET status = $status, conformidade = 'Recebido e Conforme' WHERE codreq = $codreq";
		}elseif ($status == 5 or $status == 4) {
			$query = "UPDATE rc SET status = $status WHERE codreq = $codreq";
		}
		$stmt = $this->db->prepare($query);
		if (!$stmt->execute()) {
			$update = "Erro ao atualizar status da requisição " . $codreq . " :" . print_r($stmt->errorInfo(), true);
		} else {
			$update = "Status da requisição " . $codreq . " atualizado com sucesso para ".$status."!";
		}

		return $update;
	}
//

//Atualiza Conformidade
	public function attConformidade() {
		$query = "UPDATE RC set STATUS = 6, conformidade = :conformidade, user_alter = :user_alter, 
					dt_mov = :dt_mov WHERE codreq = :codreq";
		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':conformidade', $this->__get('conformidade'));
		$stmt->bindvalue(':codreq', $this->__get('codreq'));
		$stmt->bindvalue(':user_alter', $this->__get('user_alter'));
		$stmt->bindvalue(':dt_mov', $this->__get('dt_mov'));

		if (!$stmt->execute()) {
			$update = "Erro ao atualizar a requisição: " . print_r($stmt->errorInfo(), true);
		} else {
			$update = "Requisição atualizada com sucesso!";
		}

		return $update;
	}
//

//Atualizar RC
	public function atualizarRC() {
		$query = "UPDATE rc SET categoria = :categoria, dpto = :dpto,
				dt_mov = :dt_mov, dtsugestao = :dtsugestao, status = :status, obs = :obs
				WHERE codreq = :codreq ";
		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':categoria', $this->__get('categoria'));
		$stmt->bindvalue(':dpto', $this->__get('dpto'));
		$stmt->bindvalue(':dt_mov', $this->__get('dt_mov'));
		$stmt->bindvalue(':dtsugestao', $this->__get('dtsugestao'));
		$stmt->bindvalue(':status', $this->__get('status'));
		$stmt->bindvalue(':obs', $this->__get('obs'));
		$stmt->bindvalue(':codreq', $this->__get('codreq'));

		if (!$stmt->execute()) {
			$update = "Erro ao atualizar a requisição: " . print_r($stmt->errorInfo(), true);
		} else {
			$update = "Requisição atualizada com sucesso!";
		}

		return $update;
	}
//

//Liberar RC
	public function liberarRC() {
		$query = "UPDATE rc SET user_alter = :user_alter, dt_mov = :dt_mov,
				status = :status, obs = :obs
				WHERE codreq = :codreq ";
		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':user_alter', $this->__get('user_alter'));
		$stmt->bindvalue(':codreq', $this->__get('codreq'));
		$stmt->bindvalue(':dt_mov', $this->__get('dt_mov'));
		$stmt->bindvalue(':status', $this->__get('status'));
		$stmt->bindvalue(':obs', $this->__get('obs'));

		if (!$stmt->execute()) {
			$update = "Erro ao liberar a requisição: " . print_r($stmt->errorInfo(), true);
		} else {
			$update = "Requisição enviada para Liberação!";
		}

		return $update;
	}
//

//aprovar RC
	public function aprovarRC() {
		$query = "UPDATE rc SET user_alter = :user_alter, dt_mov = :dt_mov,
				status = :status, obs = :obs
				WHERE codreq = :codreq ";
		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':user_alter', $this->__get('user_alter'));
		$stmt->bindvalue(':codreq', $this->__get('codreq'));
		$stmt->bindvalue(':dt_mov', $this->__get('dt_mov'));
		$stmt->bindvalue(':status', $this->__get('status'));
		$stmt->bindvalue(':obs', $this->__get('obs'));

		if (!$stmt->execute()) {
			$update = "Erro ao aprovar a requisição: " . print_r($stmt->errorInfo(), true);
		} else {
			$update = "Requisição aprovada e enviada para o Omie!";
		}

		return $update;
	}
//

//Reprovar RC
	public function reprovarRC() {
		$query = "UPDATE rc SET user_alter = :user_alter, dt_mov = :dt_mov,
				status = :status, motivo_rep = :motivo
				WHERE codreq = :codreq ";
		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':user_alter', $this->__get('user_alter'));
		$stmt->bindvalue(':codreq', $this->__get('codreq'));
		$stmt->bindvalue(':dt_mov', $this->__get('dt_mov'));
		$stmt->bindvalue(':status', $this->__get('status'));
		$stmt->bindvalue(':motivo', $this->__get('motivo'));

		if (!$stmt->execute()) {
			$update = "Erro ao reprovar a requisição: " . print_r($stmt->errorInfo(), true);
		} else {
			$update = "Requisição reprovada!";
		}

		return $update;
	}
//

//Cancelar RC
	public function cancelarRC() {
		$query = "UPDATE rc SET user_alter = :user_alter, dt_mov = :dt_mov,
				status = :status, motivo_rep = :motivo
				WHERE codreq = :codreq ";
		$stmt = $this->db->prepare($query);

		$stmt->bindvalue(':user_alter', $this->__get('user_alter'));
		$stmt->bindvalue(':codreq', $this->__get('codreq'));
		$stmt->bindvalue(':dt_mov', $this->__get('dt_mov'));
		$stmt->bindvalue(':status', $this->__get('status'));
		$stmt->bindvalue(':motivo', $this->__get('motivo'));

		if (!$stmt->execute()) {
			$update = "Erro ao cancelar a requisição: " . print_r($stmt->errorInfo(), true);
		} else {
			$update = "Requisição cancelada!";
		}

		return $update;
	}
//

//buscar por RC
	public function buscaPorRC($codreq) {
		$query = "SELECT DISTINCT rc.codreq, rc.cod_omie, rc.dt_mov, rc.dt_create,
				user.nome, rc.status, rc.conformidade, status.descricao, rc.owner,
				rc.dtsugestao, rc.obs, rc.id_user, rc.motivo_rep, user.email,
				dpto.descricao AS departamento, cat.descricao AS categoria,
				dpto.codigo AS cod_dpto, cat.codigo AS cod_cat,
        		(SELECT users.nome FROM rc req LEFT JOIN usuarios users
					ON users.id = req.user_alter
					WHERE req.user_alter = rc.user_alter
					AND req.codreq = rc.codreq) AS alterador,
                (SELECT SUM(itens.total) FROM itens_rc AS itens
                 LEFT JOIN rc AS req ON itens.codreq = req.codreq
                 WHERE req.codreq = rc.codreq) AS total
				FROM rc AS rc
				LEFT JOIN usuarios AS user ON user.id = rc.id_user
				LEFT JOIN rc_status AS status ON status.id = rc.status
				LEFT JOIN itens_rc AS itens ON itens.codreq = rc.codreq
				LEFT JOIN departamento AS dpto ON dpto.codigo = rc.dpto
				LEFT JOIN categoria AS cat ON cat.codigo = rc.categoria
				WHERE rc.codreq = $codreq
				ORDER BY 1";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}
	//

//buscar por usuario
	public function buscaPorUserAll($user, $limite) {
		$limite = explode(',', $limite);
		$inicio = $limite[0];		//posição que se inicia a consulta
		$limiteReg = $limite[1];	//limite maximo de registros por pagina
		
		$query = "SELECT rc.codreq, rc.cod_omie, rc.dt_mov, rc.dt_create,
				user.nome, rc.status, rc.conformidade, status.descricao, rc.owner,
				rc.dtsugestao, rc.obs, rc.id_user, rc.motivo_rep,
				dpto.descricao AS departamento, cat.descricao AS categoria,
				dpto.codigo AS cod_dpto, cat.codigo AS cod_cat,
        		(SELECT users.nome FROM rc req LEFT JOIN usuarios users
					ON users.id = req.user_alter
					WHERE req.user_alter = rc.user_alter
					AND req.codreq = rc.codreq) AS alterador,
                (SELECT SUM(itens.total) FROM itens_rc AS itens
                 LEFT JOIN rc AS req ON itens.codreq = req.codreq
                 WHERE req.codreq = rc.codreq) AS total
				FROM rc AS rc
				LEFT JOIN usuarios AS user ON user.id = rc.id_user
				LEFT JOIN rc_status AS status ON status.id = rc.status
				LEFT JOIN departamento AS dpto ON dpto.codigo = rc.dpto
				LEFT JOIN categoria AS cat ON cat.codigo = rc.categoria
				WHERE rc.id_user = $user
				LIMIT $inicio, $limiteReg";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	public function buscaQtdByUser($user) {
		$query = "SELECT COUNT(*) as qtd  FROM rc AS rc
				LEFT JOIN usuarios AS user ON user.id = rc.id_user
				LEFT JOIN rc_status AS status ON status.id = rc.status
				LEFT JOIN departamento AS dpto ON dpto.codigo = rc.dpto
				LEFT JOIN categoria AS cat ON cat.codigo = rc.categoria
				WHERE rc.id_user = $user";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	public function buscaPorUser($user) {
		//verifica se é alex ou bárbara (Pessoal de SIC)
		if ($user == 4 or $user == 38) {
			$dias = 90;
			$query = "SELECT DISTINCT rc.codreq, rc.cod_omie, rc.dt_mov, rc.dt_create,
				user.nome, rc.status, rc.conformidade, status.descricao, rc.owner,
				rc.dtsugestao, rc.obs, rc.id_user, rc.motivo_rep,
				dpto.descricao AS departamento, cat.descricao AS categoria,
				dpto.codigo AS cod_dpto, cat.codigo AS cod_cat,
        		(SELECT users.nome FROM rc req LEFT JOIN usuarios users
					ON users.id = req.user_alter
					WHERE req.user_alter = rc.user_alter
					AND req.codreq = rc.codreq) AS alterador,
                (SELECT SUM(itens.total) FROM itens_rc AS itens
                 LEFT JOIN rc AS req ON itens.codreq = req.codreq
                 WHERE req.codreq = rc.codreq) AS total
				FROM rc AS rc
				LEFT JOIN usuarios AS user ON user.id = rc.id_user
				LEFT JOIN rc_status AS status ON status.id = rc.status
				LEFT JOIN itens_rc AS itens ON itens.codreq = rc.codreq
				LEFT JOIN departamento AS dpto ON dpto.codigo = rc.dpto
				LEFT JOIN categoria AS cat ON cat.codigo = rc.categoria
				WHERE rc.id_user = $user
				AND DATEDIFF(CURDATE(), rc.dt_create) < $dias
				AND rc.codreq NOT IN (
					SELECT DISTINCT codreq
					FROM rc
					WHERE id_user = $user
					AND DATEDIFF(CURDATE(), dt_create) > 30
					AND status = 6
				)
				ORDER BY 1 DESC";
		}else {
			$dias = 122; //AQUIII
			$query = "SELECT DISTINCT rc.codreq, rc.cod_omie, rc.dt_mov, rc.dt_create,
				user.nome, rc.status, rc.conformidade, status.descricao, rc.owner,
				rc.dtsugestao, rc.obs, rc.id_user, rc.motivo_rep,
				dpto.descricao AS departamento, cat.descricao AS categoria,
				dpto.codigo AS cod_dpto, cat.codigo AS cod_cat,
        		(SELECT users.nome FROM rc req LEFT JOIN usuarios users
					ON users.id = req.user_alter
					WHERE req.user_alter = rc.user_alter
					AND req.codreq = rc.codreq) AS alterador,
                (SELECT SUM(itens.total) FROM itens_rc AS itens
                 LEFT JOIN rc AS req ON itens.codreq = req.codreq
                 WHERE req.codreq = rc.codreq) AS total
				FROM rc AS rc
				LEFT JOIN usuarios AS user ON user.id = rc.id_user
				LEFT JOIN rc_status AS status ON status.id = rc.status
				LEFT JOIN itens_rc AS itens ON itens.codreq = rc.codreq
				LEFT JOIN departamento AS dpto ON dpto.codigo = rc.dpto
				LEFT JOIN categoria AS cat ON cat.codigo = rc.categoria
				WHERE rc.id_user = $user
				and DATEDIFF(CURDATE(), rc.dt_create) < $dias
				AND rc.codreq NOT IN (
					SELECT DISTINCT codreq
					FROM rc
					WHERE id_user = $user
					AND DATEDIFF(CURDATE(), dt_create) > 30
					AND status = 6
				)
				ORDER BY 1 DESC";
		}
		
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}
//

	//busca com query já criada e vindo por parâmetro
	public function buscaQueryPronta($query) {
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_OBJ);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	//busca p/ liberação por $owner
	public function buscaLiberacao($owner) {
		$query = "SELECT DISTINCT rc.codreq, rc.cod_omie, rc.dt_mov, rc.dt_create,
				user.nome, rc.status, rc.conformidade, status.descricao, rc.owner,
				rc.dtsugestao, rc.obs, rc.id_user, rc.motivo_rep,
				dpto.descricao AS departamento, cat.descricao AS categoria,
				dpto.codigo AS cod_dpto, cat.codigo AS cod_cat,
        		(SELECT users.nome FROM rc req LEFT JOIN usuarios users
					ON users.id = req.user_alter
					WHERE req.user_alter = rc.user_alter
					AND req.codreq = rc.codreq) AS alterador,
                (SELECT SUM(itens.total) FROM itens_rc AS itens
                 LEFT JOIN rc AS req ON itens.codreq = req.codreq
                 WHERE req.codreq = rc.codreq) AS total
				FROM rc AS rc
				LEFT JOIN usuarios AS user ON user.id = rc.id_user
				LEFT JOIN rc_status AS status ON status.id = rc.status
				LEFT JOIN itens_rc AS itens ON itens.codreq = rc.codreq
				LEFT JOIN departamento AS dpto ON dpto.codigo = rc.dpto
				LEFT JOIN categoria AS cat ON cat.codigo = rc.categoria
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
	public function buscaLiberacaoAll() {
		$query = "SELECT DISTINCT rc.codreq, rc.dt_mov, rc.dt_create,
				user.nome, rc.status, rc.conformidade, status.descricao, rc.owner,
				rc.dtsugestao, rc.obs, rc.id_user, rc.motivo_rep,
				dpto.descricao AS departamento, cat.descricao AS categoria,
				dpto.codigo AS cod_dpto, cat.codigo AS cod_cat,
        		(SELECT users.nome FROM rc req LEFT JOIN usuarios users
					ON users.id = req.user_alter
					WHERE req.user_alter = rc.user_alter
					AND req.codreq = rc.codreq) AS alterador,
                (SELECT SUM(itens.total) FROM itens_rc AS itens
                 LEFT JOIN rc AS req ON itens.codreq = req.codreq
                 WHERE req.codreq = rc.codreq) AS total
				FROM rc AS rc
				LEFT JOIN usuarios AS user ON user.id = rc.id_user
				LEFT JOIN rc_status AS status ON status.id = rc.status
				LEFT JOIN itens_rc AS itens ON itens.codreq = rc.codreq
				LEFT JOIN departamento AS dpto ON dpto.codigo = rc.dpto
				LEFT JOIN categoria AS cat ON cat.codigo = rc.categoria
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

	//busca p/ aprovação por $owner
	public function buscaAprovacao($owner) {
		$query = "SELECT DISTINCT rc.codreq, rc.dt_mov, rc.dt_create,
				user.nome, rc.status, rc.conformidade, status.descricao, rc.owner,
				rc.dtsugestao, rc.obs, rc.id_user, rc.motivo_rep,
				dpto.descricao AS departamento, cat.descricao AS categoria,
				dpto.codigo AS cod_dpto, cat.codigo AS cod_cat,
        		(SELECT users.nome FROM rc req LEFT JOIN usuarios users
					ON users.id = req.user_alter
					WHERE req.user_alter = rc.user_alter
					AND req.codreq = rc.codreq) AS alterador,
                (SELECT SUM(itens.total) FROM itens_rc AS itens
                 LEFT JOIN rc AS req ON itens.codreq = req.codreq
                 WHERE req.codreq = rc.codreq) AS total
				FROM rc AS rc
				LEFT JOIN usuarios AS user ON user.id = rc.id_user
				LEFT JOIN rc_status AS status ON status.id = rc.status
				LEFT JOIN itens_rc AS itens ON itens.codreq = rc.codreq
				LEFT JOIN departamento AS dpto ON dpto.codigo = rc.dpto
				LEFT JOIN categoria AS cat ON cat.codigo = rc.categoria
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

	//busca p/ aprovação (todos)
	public function buscaAprovacaoAll() {
		$query = "SELECT DISTINCT rc.codreq, rc.dt_mov, rc.dt_create,
				user.nome, rc.status, rc.conformidade, status.descricao, rc.owner,
				rc.dtsugestao, rc.obs, rc.id_user, rc.motivo_rep,
				dpto.descricao AS departamento, cat.descricao AS categoria,
				dpto.codigo AS cod_dpto, cat.codigo AS cod_cat,
        		(SELECT users.nome FROM rc req LEFT JOIN usuarios users
					ON users.id = req.user_alter
					WHERE req.user_alter = rc.user_alter
					AND req.codreq = rc.codreq) AS alterador,
                (SELECT SUM(itens.total) FROM itens_rc AS itens
                 LEFT JOIN rc AS req ON itens.codreq = req.codreq
                 WHERE req.codreq = rc.codreq) AS total
				FROM rc AS rc
				LEFT JOIN usuarios AS user ON user.id = rc.id_user
				LEFT JOIN rc_status AS status ON status.id = rc.status
				LEFT JOIN itens_rc AS itens ON itens.codreq = rc.codreq
				LEFT JOIN departamento AS dpto ON dpto.codigo = rc.dpto
				LEFT JOIN categoria AS cat ON cat.codigo = rc.categoria
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
	//

	//busca p/ sincronizar pedidos
	public function buscaPedidos() {

		$query = "SELECT rc.codreq, rc.cod_omie, rc.status, status.descricao
				FROM rc AS rc
				LEFT JOIN rc_status AS status ON status.id = rc.status
				WHERE rc.status = 3 and rc.cod_omie = 0
				ORDER BY 1";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_OBJ);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	public function buscaSincAllRC() {
		$query = "SELECT codreq, cod_omie, status FROM rc";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetchAll(\PDO::FETCH_OBJ);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;
	}

	//atualiza código do omie (nº pedido)
	public function atualiza_cod_omie($codreq) {

		$query = "UPDATE rc SET cod_omie = :numPedido WHERE codreq = :codreq";

		$stmt = $this->db->prepare($query);
		$stmt->bindvalue(':codreq', $this->__get('codreq'));
		$stmt->bindvalue(':numPedido', $this->__get('numPedido'));

		if (!$stmt->execute()) {
			$update = "Erro ao sincronizar nº de pedido na RC $codreq : " . print_r($stmt->errorInfo(), true);
		} else {
			$update = "Requisição $codreq sincronizada!";
		}

		return $update;
	}
	//

	//consulta quantas RC's estão aprovadas à mais de 3 dias sem sincronização com Omie
	public function rc_nao_sincronizada() {
		$query = "SELECT count(codreq) as  num FROM rc WHERE status = 3 AND DATEDIFF(CURDATE(), dt_mov) > 5";
		$stmt = $this->db->prepare($query);
		if ($stmt->execute()) {
			$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		} else {
			$result = "Erro na consulta SQL: " . print_r($stmt->errorInfo(), true);
		}

		return $result;

	}
	//

}

?>