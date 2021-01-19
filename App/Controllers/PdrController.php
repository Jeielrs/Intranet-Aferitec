<?php

namespace App\Controllers;

date_default_timezone_set('America/Fortaleza');

use INTRA\Controller\Action;
use INTRA\Model\Container;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use INTRA\Model\Pagination;

class PDRController extends Action {

//Renderização de views
	public function index() {

		if (!isset($_SESSION)) {session_start();}

		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
			$this->render('index', 'layoutPDR');
		} else {
			header('Location: /?login=erro');
		}

	}

	public function nova_rc() {

		if (!isset($_SESSION)) {session_start();}

		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
			$this->render('nova_rc', 'layoutPDR');
		} else {
			header('Location: /?login=erro');
		}
	}

	public function liberar() {

		if (!isset($_SESSION)) {session_start();}

		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
			$this->render('liberar', 'layoutPDR');
		} else {
			header('Location: /?login=erro');
		}

		//Caso a pessoa errada acesse
		if ($_SESSION['id'] != 1 and $_SESSION['id'] != 2
			and $_SESSION['id'] != 3 and $_SESSION['id'] != 6 and $_SESSION['id'] != 7
			and $_SESSION['id'] != 8 and $_SESSION['id'] != 14) {
			echo "
        		<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http:/pdr'>
        		<script type=\"text/javascript\">
        		    alert(\"Como chegou até aqui? você não tem acesso!\");
        		</script>
        		  ";
		}

	}

	public function aprovar() {

		if (!isset($_SESSION)) {session_start();}

		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
			$this->render('aprovar', 'layoutPDR');
		} else {
			header('Location: /?login=erro');
		}

		//Caso a pessoa errada acesse
		if ($_SESSION['id'] != 1 and $_SESSION['id'] != 2
			and $_SESSION['id'] != 7 and $_SESSION['id'] != 8) {
			echo "
        		<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http:/pdr'>
        		<script type=\"text/javascript\">
        		    alert(\"Como chegou até aqui? você não tem acesso!\");
        		</script>
        		  ";
		}

	}

	public function provedores() {
		if (!isset($_SESSION)) {session_start();}

		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
			$this->render('provedores', 'layoutPDR');
		} else {
			header('Location: /?login=erro');
		}
	}

	public function relatorios() {
		if (!isset($_SESSION)) {session_start();}

		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
			$this->render('relatorios', 'layoutPDR');
		} else {
			header('Location: /?login=erro');
		}
	}

	public function classificarRC() {

		if (!isset($_SESSION)) {session_start();}

		//Caso a pessoa acesse de outro lugar
		if (!isset($_POST['codreq'])) {
			echo "
        		<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http:/pdr'>
        		<script type=\"text/javascript\">
        		    alert(\"Acesso negado!\");
        		</script>
        		  ";
		} else {

			$this->view->codreq = $_POST['codreq'];
			if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
				$this->render('classificarRC', 'layoutPDR');
			} else {
				header('Location: /?login=erro');
			}

		}

	}

	public function editar() {

		if (!isset($_SESSION)) {session_start();}
		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
			$this->render('editar', 'layoutPDR');
		} else {
			header('Location: /?login=erro');
		}
	}

	public function pesquisar() {

		if (!isset($_SESSION)) {session_start();}
		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
			$this->render('pesquisar', 'layoutPDR');
		} else {
			header('Location: /?login=erro');
		}
	}
//

//Buscar Nº Pedido e Fornecedor dos itens de RCs já sincronizadas
	public function listaPedidos($codreq) {
		$itemrc = Container::getModel('itensRC');
		$lista = $itemrc->buscaPedidoPesquisar($codreq);
		return json_encode($lista);
	}
//

//buscando rc por usuario
	public function listaAll($limite) {

		$rc = Container::getModel('RC');
		$lista = $rc->buscaPorUserAll($_SESSION['id'], $limite);
		return json_encode($lista);
	}

	public function lista() {

		$rc = Container::getModel('RC');
		$lista = $rc->buscaPorUser($_SESSION['id']);
		return json_encode($lista);
	}

	public function qtdByUser() {
		$rc = Container::getModel('RC');
		$qtd = $rc->buscaQtdByUser($_SESSION['id']);
		return json_encode($qtd);
	}
	public function listaItens() {

		$rc = Container::getModel('itensRC');
		$lista = $rc->buscaPorUserItens($_SESSION['id']);
		return json_encode($lista);
	}

	public function listaFiles() {

		$rc = Container::getModel('RCfiles');
		$lista = $rc->buscaPorUserFiles($_SESSION['id']);
		return json_encode($lista);
	}

//

//buscando rc pelo nº
	public function procurarRC($codreq) {
		$rc = Container::getModel('RC');
		$lista = $rc->buscaPorRC($codreq);
		return json_encode($lista);
	}
	public function procurarRCItens($codreq) {
		$rc = Container::getModel('itensRC');
		$lista = $rc->buscaPorRC($codreq);
		return json_encode($lista);
	}
	public function procurarRCFiles($codreq) {
		$rc = Container::getModel('RCfiles');
		$lista = $rc->buscaPorRC($codreq);
		return json_encode($lista);
	}
//

//buscando rc p/ liberação
	public function listaLiberacao() {

		$rc = Container::getModel('RC');
		if ($_SESSION['id'] == 14) {
			$owner = 'ROBERTO';
			$lista = $rc->buscaLiberacao($owner);
		} elseif ($_SESSION['id'] == 3) {
			$owner = 'MAICON';
			$lista = $rc->buscaLiberacao($owner);
		} elseif ($_SESSION['id'] == 6) {
			$owner = 'VINICIUS';
			$lista = $rc->buscaLiberacao($owner);
		} else {
			$lista = $rc->buscaLiberacaoAll();
		}
		return json_encode($lista);
	}

	public function listaLiberacaoItens() {

		$rc = Container::getModel('itensRC');
		if ($_SESSION['id'] == 14) {
			$owner = 'ROBERTO';
			$lista = $rc->buscaLiberacaoItens($owner);
		} elseif ($_SESSION['id'] == 3) {
			$owner = 'MAICON';
			$lista = $rc->buscaLiberacaoItens($owner);
		} else {
			$lista = $rc->buscaLiberacaoAllItens();
		}
		return json_encode($lista);
	}

	public function listaLiberacaoFiles() {

		$rc = Container::getModel('RCfiles');
		if ($_SESSION['id'] == 14) {
			$owner = 'ROBERTO';
			$lista = $rc->buscaLiberacaoFiles($owner);
		} elseif ($_SESSION['id'] == 3) {
			$owner = 'MAICON';
			$lista = $rc->buscaLiberacaoFiles($owner);
		} else {
			$lista = $rc->buscaLiberacaoAllFiles();
		}
		return json_encode($lista);
	}
//

//buscando rc p/ aprovação
	public function listaAprovacao() {

		$rc = Container::getModel('RC');
		if ($_SESSION['id'] == 14) {
			$owner = 'ROBERTO';
			$lista = $rc->buscaAprovacao($owner);
		} elseif ($_SESSION['id'] == 3) {
			$owner = 'MAICON';
			$lista = $rc->buscaAprovacao($owner);
		} else {
			$lista = $rc->buscaAprovacaoAll();
		}
		return json_encode($lista);
	}

	public function listaAprovacaoItens() {

		$rc = Container::getModel('itensRC');
		if ($_SESSION['id'] == 14) {
			$owner = 'ROBERTO';
			$lista = $rc->buscaAprovacaoItens($owner);
		} elseif ($_SESSION['id'] == 3) {
			$owner = 'MAICON';
			$lista = $rc->buscaAprovacaoItens($owner);
		} else {
			$lista = $rc->buscaAprovacaoAllItens();
		}
		return json_encode($lista);
	}

	public function listaAprovacaoFiles() {

		$rc = Container::getModel('RCfiles');
		if ($_SESSION['id'] == 14) {
			$owner = 'ROBERTO';
			$lista = $rc->buscaAprovacaoFiles($owner);
		} elseif ($_SESSION['id'] == 3) {
			$owner = 'MAICON';
			$lista = $rc->buscaAprovacaoFiles($owner);
		} else {
			$lista = $rc->buscaAprovacaoAllFiles();
		}
		return json_encode($lista);
	}
//

//funções
	public function menu() {

		if ($_SESSION['nivel'] == "gerente") {
			$this->chooseMenu('menu_full');
		} elseif ($_SESSION['nivel'] == "supervisor" or $_SESSION['id'] == 4/*alex*/) {
			$this->chooseMenu('menu_medium');
		} else {
			$this->chooseMenu('menu_low');
		}

	}

	public function saudacao() {

		$saudacao = "Bom dia";

		if (date('H:i:s') > '12:30:00') {
			$saudacao = "Boa tarde";
		} elseif (date('H:i:s') > '18:30:00') {
			$saudacao = "Boa noite";
		}

		return $saudacao;

	}

	public function categorias() {
		$lista = array("2.01 - Despesas Diretas", "2.02 - Despesas de Vendas e Marketing", "2.03 - Despesas com Pessoal", "2.07 - Investimento", "2.08 - Embalagens, Etiquetas e Similares", "2.10 - Materiais de Consumo", "2.11 - Materiais de Insumo", "2.12 - Acreditação CGCRE (RBC E RBLE)", "2.13 - Certificações, Associações e Normas", "2.14 - Transporte e Viagem", "2.17 - Ativos de Pequeno Valor", "2.01.01 - Compras de Mercadorias para Revenda", "2.01.03 - Calibração de Padrões", "2.01.04 - Subcontratação de calibração (previsto no contrato)", "2.01.99 - Subcontratação de calibração (fora do contrato)", "2.01.98 - Subcontratação de manutenção", "2.01.97 - Reposição de equipamento à cliente", "2.01.96 - Ensaios de Proficiência", "2.01.95 - Padrões Consumíveis", "2.02.02 - Publicidade e Propaganda", "2.02.99 - Portais de Negociações com Clientes", "2.03.89 - Uniformes", "2.03.88 - E.P.I. e E.P.C.", "2.03.84 - Treinam., Consult., Desenv. de Pessoal", "2.04.07 - Limpeza e Cozinha", "2.04.08 - Material de Escritório", "2.04.99 - Manutenção de Imóveis", "2.04.97 - Manutenção de Máquinas e Equipamentos", "2.04.98 - Manutenção de Móveis", "2.04.96 - Manutenção de Veículos", "2.07.01 - Instalações", "2.07.02 - Móveis e Utensílios", "2.07.03 - Equipamentos de Informática", "2.07.04 - Máquinas e Equipamentos", "2.07.06 - Software e Programas Complementares", "2.07.99 - Licença, Franquia e Direito de Uso", "2.07.98 - Manutenção de Melhoria em Investimento", "2.08.01 - Etiquetas", "2.08.02 - Embalagens", "2.10.99 - Algodão", "2.10.98 - Fitas (rosca e isolante)", "2.10.97 - Luva", "2.10.96 - Pilhas e Baterias", "2.10.95 - Tinta Lacre", "2.10.94 - Outros (Materiais de Consumo)", "2.11.99 - Água Destilada", "2.11.98 - Ecothiner", "2.11.96 - Outros (Materiais de Insumo)", "2.12.99 - RBC", "2.12.98 - RBLE", "2.12.97 - Auditoria CGCRE", "2.13.99 - ABNT", "2.13.98 - IPEM", "2.13.97 - REMESP", "1.21.02 - Outros (Transporte e Viagem)", "2.14.95 - Passagem Aérea", "2.14.94 - Transporte de Mercadorias", "2.14.93 - Transporte de Pessoas", "2.14.92 - Hospedagem", "2.17.99 - Móveis e Utensílios de Pequeno Valor", "2.17.98 - Equipamentos de Informática de Pequeno Valor", "2.17.97 - Máquinas e Equipamentos de Pequeno Valor", "2.17.96 - Outros Ativos de Pequeno Valor");
		return json_encode($lista);
	}

	public function projetos() {
		$lista = Container::getModel('Departamento');
		$lista = $lista->listarDepartamentos();
		return json_encode($lista);
	}

	public function solicitantes() {
		$user = Container::getModel('Usuario');
		$solicitantes = $user->listaUsuarios();
		return json_encode($solicitantes);
	}

	public function provedoresClassificados() {
		$dados = Container::getModel('ClassifProvedores');
		$lista = $dados->listarProvedores();
		return $lista;
	}

	public function produtos() {
		$produtos = Container::getModel('Produto');
		$lista = $produtos->listarProdutos();
		return $lista;
	}

	public function img_file($extension) {
		if ($extension == 'pdf') {
			$ext = "<img src='img/icones/pdf.png' class='img_file'>";
		} elseif ($extension == 'doc') {
			$ext = "<img src='.img/icones/doc.png' class='img_file'>";
		} elseif ($extension == 'docx') {
			$ext = "<img src='img/icones/docx.png' class='img_file'>";
		} elseif ($extension == 'html') {
			$ext = "<img src='img/icones/html.png' class='img_file'>";
		} elseif ($extension == 'msg') {
			$ext = "<img src='img/icones/msg.png' class='img_file'>";
		} elseif ($extension == 'png') {
			$ext = "<img src='img/icones/png.png' class='img_file'>";
		} elseif ($extension == 'jpg') {
			$ext = "<img src='img/icones/png.png' class='img_file'>";
		} elseif ($extension == 'jpeg') {
			$ext = "<img src='img/icones/png.png' class='img_file'>";
		} elseif ($extension == 'ppt') {
			$ext = "<img src='img/icones/ppt.png' class='img_file'>";
		} elseif ($extension == 'pptx') {
			$ext = "<img src='img/icones/pptx.png' class='img_file'>";
		} elseif ($extension == 'sql') {
			$ext = "<img src='img/icones/sql.png' class='img_file'>";
		} elseif ($extension == 'txt') {
			$ext = "<img src='img/icones/txt.png' class='img_file'>";
		} elseif ($extension == 'xls') {
			$ext = "<img src='img/icones/xls.png' class='img_file'>";
		} elseif ($extension == 'xlsx') {
			$ext = "<img src='img/icones/xlsx.png' class='img_file'>";
		} elseif ($extension == 'xml') {
			$ext = "<img src='img/icones/xml.png' class='img_file'>";
		} else {
			$ext = "<img src='img/icones/file.png' class='img_file'>";
		}

		return $ext;
	}

	public function botaoEdit($codreq) {

		$lista = json_decode($this->lista());
		foreach ($lista as $key => $rc) {
			if ($rc->codreq == $codreq) {
				if (($rc->status == 1) or ($rc->status == 2) or ($rc->status == 7)) {
					echo '
						<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-tt="tooltip" data-placement="right" title="ALTERAR" data-target="#modaledit' . $rc->codreq . '">
            				<i class="fa fa-pencil" aria-hidden="true"></i>
            			</button>
          				';
				} else {
					echo '
						<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-tt="tooltip" data-placement="right" title="ALTERAR" data-target="#modalnope' . $rc->codreq . '">
            				<i class="fa fa-pencil" aria-hidden="true"></i>
            			</button>
						';
				}
			}
		}

	}

	public function botaoClassify($codreq) {

		$lista = json_decode($this->lista());
		foreach ($lista as $key => $rc) {
			if ($rc->codreq == $codreq) {
				if ($rc->status == 5) {
					echo '
						<button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-tt="tooltip" data-placement="right" title="CLASSIFICAR" data-target="#modalclassify' . $rc->codreq . '">
                  			<i class="fa fa-tags" aria-hidden="true"></i>
              			</button>
          				';
				} else {
					echo '
						<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-tt="tooltip" data-placement="right" title="CLASSIFICAR" data-target="#modalnope' . $rc->codreq . '">
            				<i class="fa fa-tag" aria-hidden="true"></i>
            			</button>
						';
				}
			}
		}

	}

	public function loadBoxProd() {

		$lista = $this->produtos(); //here

		//return $lista;
		//echo json_encode($lista);

		$x = 1;
		$numitens = $_POST['numitens'];
		//for ($i = 0; $i < 80000000; $i++) {

		//	# code...
		//}
		while ($x <= $numitens) {
			echo
				"<div class='container produto' id='item_" . $x . "'>
        		  	<label class='labelform'>Item " . $x . ": </label>
        		  	<input type='search' name='produto_" . $x . "' id='produto_" . $x . "' list='produtos' placeholder='Pesquisar produtos ou serviços...' class='custom-select' required>
          			<datalist id='produtos'>";
			foreach ($lista as $prod) {
				if ($prod != NULL) {
					echo '<option value="' . $prod['codigo_omie'] . ' - ' . $prod['descricao'] . '"></option>';
				}
			}

			echo
				"</datalist>
          			<div class='container'>
            			<label class='labelformprod'>Especificações:</label>
            		    <textarea class='form-control' pattern='[a-zA-Z0-9]+'' name='especific_" . $x . "' id='exampleFormControlTextarea_" . $x . "' rows='4'></textarea>
          			</div>

          			<div class='row container justify-content-between'>
           				<div class='col-sm-3 col-4'>
              				<label class='labelformprod'>Quantidade: </label>
              				<input type='number' name='qtd_" . $x . "' min='0' value='1' class='form-control'  required>
            			</div>
            			<div class='col-sm-5 col-8'>
              				<label class='labelformprod' for='dinheiro'>Valor Unitário:</label>
              				<input type='text' id='dinheiro_" . $x . "' name='dinheiro_" . $x . "' class='form-control dinheiro' value='10,00' required>
            			</div>
            		</div><br>
          		</div>";

			$x++;
		}
	}

	public function buscaRelatorio() {
		if (isset($_POST['sql']) != null) {
			$pagina = $_POST['pagina'];
			$sql = $_POST['sql'];
			$sql_qtd = $_POST['sql_qtd'];
			echo '<pre>';
			print_r($pagina);
			print_r($sql);
			print_r($sql_qtd);
			echo '</pre>';
		} else {
			$categoria = ($_POST['categoria'] != null) ? explode(" - ", $_POST['categoria']) : null;
			$departamento = ($_POST['departamento'] != null) ? explode(" - ", $_POST['departamento']) : null;
			$status = ($_POST['status'] != null) ? explode(" - ", $_POST['status']) : null;
			$produto1 = ($_POST['produto1'] != null) ? explode(" - ", $_POST['produto1']) : null;
			$produto2 = ($_POST['produto2'] != null) ? explode(" - ", $_POST['produto2']) : null;
			$produto3 = ($_POST['produto3'] != null) ? explode(" - ", $_POST['produto3']) : null;
			$produto4 = ($_POST['produto4'] != null) ? explode(" - ", $_POST['produto4']) : null;
			$produto5 = ($_POST['produto5'] != null) ? explode(" - ", $_POST['produto5']) : null;

			$dtIniCreate = ($_POST['dtIniCreate'] != null)
				? "and rc.dt_create >= '" . $_POST['dtIniCreate'] . " 00:00:00' "
				: " ";
			$dtFimCreate = ($_POST['dtFimCreate'] != null)
				? "and rc.dt_create <= '" . $_POST['dtFimCreate'] . " 00:00:00' "
				: " ";
			$solicitante = ($_POST['solicitante'] != null)
				? "and solicitante.nome = '".$_POST['solicitante']."' "
				: " ";
			$liberador = ($_POST['liberador'] != null)
				? "and rc.owner = '" . $_POST['liberador'] . "' "
				: " ";
			$categoria = ($categoria != null) ? "and rc.categoria = '" . $categoria[0] . "' " : " ";
			$departamento = ($departamento != null) ? "and rc.dpto = '" . $departamento[0] . "' " : " ";
			$status = ($status != null) ? "and rc.status = " . $status[0] . " " : " ";
			$classificacao = ($_POST['classificacao'] != null)
				? "and rc.conformidade = '" . $_POST['classificacao'] . "' " : " ";
			$alterador = ($_POST['alterador'] != null)
				? "and alterador.nome = '" . $_POST['alterador'] . "' ":" ";
			$produto1 = ($_POST['produto1'] != null) ? "and item.codprod = '".$produto1[0]."' " : " ";
			$produto2 = ($_POST['produto2'] != null) ? "or item.codprod = '".$produto2[0]."' " : " ";
			$produto3 = ($_POST['produto3'] != null) ? "or item.codprod = '".$produto3[0]."' " : " ";
			$produto4 = ($_POST['produto4'] != null) ? "or item.codprod = '".$produto4[0]."' " : " ";
			$produto5 = ($_POST['produto5'] != null) ? "or item.codprod = '".$produto5[0]."' " : " ";
			$provedor = ($_POST['provedor'] != null) ? "or item.fornecedor = '".$_POST['provedor']."' " : " ";

			if ($_POST['produto1'] == null and $_POST['produto2'] != null) {
					$produto2 = str_replace("or", "and", $produto2);
				}
			if ($_POST['produto1'] == null and $_POST['produto2'] == null and $_POST['produto3'] != null) {
					$produto3 = str_replace("or", "and", $produto3);
				}
			if ($_POST['produto1'] == null and $_POST['produto2'] == null and $_POST['produto3'] == null and $_POST['produto4'] != null) {
					$produto4 = str_replace("or", "and", $produto4);
				}
			if ($_POST['produto1'] == null and $_POST['produto2'] == null and $_POST['produto3'] == null and $_POST['produto4'] == null and $_POST['produto5'] != null) {
					$produto5 = str_replace("or", "and", $produto5);
				}

			$sql = "SELECT rc.codreq, rc.cod_omie, cat.descricao, dpto.descricao, rc.dt_create, rc.dt_mov, rc.dtsugestao, solicitante.nome AS solicitante, rc.owner, rc.obs, status.descricao, rc.conformidade, alterador.nome AS alterador, rc.motivo_rep, item.descrprod, item.obsitem, item.precounit, item.qtde, item.fornecedor FROM rc AS rc 
				INNER JOIN itens_rc AS item ON rc.codreq =  item.codreq
				INNER JOIN usuarios AS  solicitante ON solicitante.id = rc.id_user
				LEFT JOIN usuarios AS alterador ON alterador.id = rc.user_alter
				INNER JOIN categoria AS cat ON cat.codigo = rc.categoria
				INNER JOIN departamento AS dpto ON dpto.codigo = rc.dpto
				INNER JOIN rc_status AS status ON status.id = rc.status
				WHERE rc.codreq > 200000 
				";

			$sql_qtd = "SELECT COUNT(rc.codreq) as qtd FROM rc AS rc 
				INNER JOIN itens_rc AS item ON rc.codreq =  item.codreq
				INNER JOIN usuarios AS  solicitante ON solicitante.id = rc.id_user
				LEFT JOIN usuarios AS alterador ON alterador.id = rc.user_alter
				INNER JOIN categoria AS cat ON cat.codigo = rc.categoria
				INNER JOIN departamento AS dpto ON dpto.codigo = rc.dpto
				INNER JOIN rc_status AS status ON status.id = rc.status
				WHERE rc.codreq > 200000 ";

			if ($dtIniCreate != null) { $sql .= $dtIniCreate; $sql_qtd .= $dtIniCreate; }
			if ($dtFimCreate != null) { $sql .= $dtFimCreate; $sql_qtd .= $dtFimCreate; }
			if ($solicitante != null) { $sql .= $solicitante; $sql_qtd .= $solicitante; }
			if ($liberador != null) { $sql .= $liberador; $sql_qtd .= $liberador; }
			if ($categoria != null) { $sql .= $categoria; $sql_qtd .= $categoria; }
			if ($departamento != null) { $sql .= $departamento; $sql_qtd .= $departamento; }
			if ($status != null) { $sql .= $status; $sql_qtd .= $status; }
			if ($classificacao != null) { $sql .= $classificacao; $sql_qtd .= $classificacao; }
			if ($alterador != null) { $sql .= $alterador; $sql_qtd .= $alterador; }
			if ($produto1 != null) { $sql .= $produto1; $sql_qtd .= $produto1; }
			if ($produto2 != null) { $sql .= $produto2; $sql_qtd .= $produto2; }
			if ($produto3 != null) { $sql .= $produto3; $sql_qtd .= $produto3; }
			if ($produto4 != null) { $sql .= $produto4; $sql_qtd .= $produto4; }
			if ($produto5 != null) { $sql .= $produto5; $sql_qtd .= $produto5; }
			if ($provedor != null) { $sql .= $provedor; $sql_qtd .= $provedor; }
		}

		$rc = Container::getModel('RC');
		$qtd = $rc->buscaQueryPronta($sql_qtd);
		$qtd = $qtd[0]->qtd;
		//print_r($result_rc);

		//PAGINAÇÃO
		$objPagination = new Pagination($qtd, $_POST['pagina'] ?? 1);
		$limite = $objPagination->getLimit();
		$limite = explode(',', $limite);
		$inicio = $limite[0];		//posição que se inicia a consulta
		$limiteReg = $limite[1];	//limite maximo de registros por pagina
		$paginas = $objPagination->getPages();

		$sql_pronta = $sql . "ORDER BY 1 LIMIT ".$inicio.", ".$limiteReg." ";
		$consulta = $rc->buscaQueryPronta($sql_pronta);
		//echo "<pre>"; print_r($consulta); echo "</pre>";
?>

<?php
		echo '<form class="form-inline" method="POST" action="/relatorios">
				<input type="hidden" name="sql" value="'.$sql.'">
				<input type="hidden" name="sql_qtd" value="'.$sql_qtd.'">';

		foreach ($paginas as $key => $pagina) {
			if ($pagina['atual'] == 1) {
				echo '<button type="submit" name="paginaAtual" value="'.$pagina['pagina'].'" class="btn mx-1 my-1 btn-sm btn-primary">'.$pagina['pagina'].'</button>';
			}
			else {
				echo '<button type="submit" name="paginaAtual" value="'.$pagina['pagina'].'" class="btn mx-1 my-1 btn-sm btn-dark">'.$pagina['pagina'].'</button>';
			}
		}
		echo '</form>';
		echo '
		<table class="table table-bordered table-responsive table-hover" style="white-space: nowrap;">
                  <thead class="thead-dark">
                    <tr>
                        <th scope="col" class="align-middle text-center">RC</th>
                        <th scope="col" class="align-middle text-center">COD OMIE</th>
                        <th scope="col" class="align-middle text-center">DESCRIÇÃO ITEM</th>
                        <th scope="col" class="align-middle text-center">OBS ITEM</th>
                        <th scope="col" class="align-middle text-center">STATUS</th>
                        <th scope="col" class="align-middle text-center">DT CRIAÇÃO</th>
                        <th scope="col" class="align-middle text-center">DT MOVIMENT.</th>
                        <th scope="col" class="align-middle text-center">DT SUGESTÃO</th>
                        <th scope="col" class="align-middle text-center">SOLICITANTE</th>
                        <th scope="col" class="align-middle text-center">LIBERADOR</th>
                        <th scope="col" class="align-middle" style="max-width: 100px;">OBSERVAÇÃO</th>
                        <th scope="col" class="align-middle text-center">CONFORMIDADE</th>
                        <th scope="col" class="align-middle text-center">ÚLTIMA ALTERAÇÃO</th>
                        <th scope="col" class="align-middle text-center">MOTIVO REPROVAÇÃO</th>
                        <th scope="col" class="align-middle text-center">PREÇO UNIT.</th>
                        <th scope="col" class="align-middle text-center">QTD</th>
                        <th scope="col" class="align-middle text-center">FORNECEDOR</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
			';
		foreach ($consulta as $key => $linha) {
            echo '
              <td class="text-center">'.$linha->codreq.'</td>
              <td class="text-center">'.$linha->cod_omie.'</td>
              <td class="text-center" style="max-width:700px; overflow:hidden; text-overflow:ellipsis;">'.$linha->descrprod.'</td>
              <td class="text-center" style="max-width:700px; overflow:hidden; text-overflow:ellipsis;">'.$linha->obsitem.'</td>
              <td class="text-center">'.$linha->descricao.'</td>
              <td class="text-center">'.date("d/m/Y h:i:s", strtotime($linha->dt_create)).'</td>
              <td class="text-center">'.date("d/m/Y h:i:s", strtotime($linha->dt_mov)).'</td>
              <td class="text-center">'.date("d/m/Y", strtotime($linha->dtsugestao)).'</td>
              <td class="text-center">'.$linha->solicitante.'</td>
              <td class="text-center">'.$linha->owner.'</td>
              <td class="text-center" style="max-width:700px; overflow:hidden; text-overflow:ellipsis;">'.$linha->obs.'</td>
              <td class="text-center">'.$linha->conformidade.'</td>
              <td class="text-center">'.$linha->alterador.'</td>
              <td class="text-center" style="max-width:700px; overflow:hidden; text-overflow:ellipsis;">'.$linha->motivo_rep.'</td>
              <td class="text-center">'.$linha->precounit.'</td>
              <td class="text-center">'.$linha->qtde.'</td>
              <td class="text-center">'.$linha->fornecedor.'</td>
            </tr>
            ';
          }
          echo "</tbody>
              </table>
              <div class='bg-success text-light'><p class='mx-2 my-2'><small>".$sql_pronta."</small></p></div>";
	}

	public function loadProvedor() {
		$qtdPrecoRuim = 0;
		$qtdPrazoRuim = 0;
		$qtdQualidadeRuim = 0;
		$qtdPrecoMedio = 0;
		$qtdPrazoMedio = 0;
		$qtdQualidadeMedio = 0;
		$qtdPrecoBom = 0;
		$qtdPrazoBom = 0;
		$qtdQualidadeBom = 0;
		$filtro = $_POST['filtro'];
		$dados = Container::getModel('ClassifProvedores');
		echo '<table class="table table-bordered">
      			<thead class="thead-dark">
        			<tr>
          				<th scope="col" class="text-center">ID</th>
          				<th scope="col" class="text-center">REQUISIÇÃO</th>
          				<th scope="col" class="text-center">PROVEDOR</th>
          				<th scope="col" class="text-center">DATA</th>
          				<th scope="col" class="text-center">PREÇO</th>
          				<th scope="col" class="text-center">PRAZO</th>
          				<th scope="col" class="text-center">QUALIDADE</th>
          				<th scope="col" class="text-center">OBS</th>
        			</tr>
      			</thead>
      			<tbody>
        			<tr>';

		switch ($filtro) {
		case 1:
			$lista = $dados->buscaGeral();
			foreach ($lista as $key => $row) {
				if ($row->preco == 1) {
					$preco = "Ruim";
					$qtdPrecoRuim++;
				}
				if ($row->preco == 2) {
					$preco = "Médio";
					$qtdPrecoMedio++;
				}
				if ($row->preco == 3) {
					$preco = "Bom";
					$qtdPrecoBom++;
				}
				if ($row->prazo == 1) {
					$prazo = "Ruim";
					$qtdPrazoRuim++;
				}
				if ($row->prazo == 2) {
					$prazo = "Médio";
					$qtdPrazoMedio++;
				}
				if ($row->prazo == 3) {
					$prazo = "Bom";
					$qtdPrazoBom++;
				}
				if ($row->qualidade == 1) {
					$qualidade = "Ruim";
					$qtdQualidadeRuim++;
				}
				if ($row->qualidade == 2) {
					$qualidade = "Médio";
					$qtdQualidadeMedio++;
				}
				if ($row->qualidade == 3) {
					$qualidade = "Bom";
					$qtdQualidadeBom++;
				}
				echo '	<td class="text-center">' . $row->id . '</td>
        				<td class="text-center">' . $row->codreq . '</td>
        				<td class="text-center">' . $row->provedor . '</td>
        				<td class="text-center">' . date("d/m/Y", strtotime($row->data)) . '</td>
        				<td class="text-center">' . $preco . '</td>
        				<td class="text-center">' . $prazo . '</td>
        				<td class="text-center">' . $qualidade . '</td>
        				<td class="text-center">' . $row->obs . '</td>
        			</tr>';
			}
			break;
		case 2:
			$lista = $dados->buscaPorProvedor($_POST['prov']);
			foreach ($lista as $key => $row) {
				if ($row->preco == 1) {
					$preco = "Ruim";
					$qtdPrecoRuim++;
				}
				if ($row->preco == 2) {
					$preco = "Médio";
					$qtdPrecoMedio++;
				}
				if ($row->preco == 3) {
					$preco = "Bom";
					$qtdPrecoBom++;
				}
				if ($row->prazo == 1) {
					$prazo = "Ruim";
					$qtdPrazoRuim++;
				}
				if ($row->prazo == 2) {
					$prazo = "Médio";
					$qtdPrazoMedio++;
				}
				if ($row->prazo == 3) {
					$prazo = "Bom";
					$qtdPrazoBom++;
				}
				if ($row->qualidade == 1) {
					$qualidade = "Ruim";
					$qtdQualidadeRuim++;
				}
				if ($row->qualidade == 2) {
					$qualidade = "Médio";
					$qtdQualidadeMedio++;
				}
				if ($row->qualidade == 3) {
					$qualidade = "Bom";
					$qtdQualidadeBom++;
				}
				echo '	<td class="text-center">' . $row->id . '</td>
        				<td class="text-center">' . $row->codreq . '</td>
        				<td class="text-center">' . $row->provedor . '</td>
        				<td class="text-center">' . date("d/m/Y", strtotime($row->data)) . '</td>
        				<td class="text-center">' . $preco . '</td>
        				<td class="text-center">' . $prazo . '</td>
        				<td class="text-center">' . $qualidade . '</td>
        				<td class="text-center">' . $row->obs . '</td>
        			</tr>';
			}
			break;
		case 3:
			$lista = $dados->buscaPorRC($_POST['codreq']);
			if (!isset($lista[0]->id)) {
				echo '<div class="bg-dark"><h5 class="text-warning text-center">Nenhuma Classificação foi realizada para essa RC!</h5></div>';
			} else {
				foreach ($lista as $key => $row) {
					if ($row->preco == 1) {
						$preco = "Ruim";
						$qtdPrecoRuim++;
					}
					if ($row->preco == 2) {
						$preco = "Médio";
						$qtdPrecoMedio++;
					}
					if ($row->preco == 3) {
						$preco = "Bom";
						$qtdPrecoBom++;
					}
					if ($row->prazo == 1) {
						$prazo = "Ruim";
						$qtdPrazoRuim++;
					}
					if ($row->prazo == 2) {
						$prazo = "Médio";
						$qtdPrazoMedio++;
					}
					if ($row->prazo == 3) {
						$prazo = "Bom";
						$qtdPrazoBom++;
					}
					if ($row->qualidade == 1) {
						$qualidade = "Ruim";
						$qtdQualidadeRuim++;
					}
					if ($row->qualidade == 2) {
						$qualidade = "Médio";
						$qtdQualidadeMedio++;
					}
					if ($row->qualidade == 3) {
						$qualidade = "Bom";
						$qtdQualidadeBom++;
					}
					echo '	<td class="text-center">' . $row->id . '</td>
        					<td class="text-center">' . $row->codreq . '</td>
        					<td class="text-center">' . $row->provedor . '</td>
        					<td class="text-center">' . date("d/m/Y", strtotime($row->data)) . '</td>
        					<td class="text-center">' . $preco . '</td>
        					<td class="text-center">' . $prazo . '</td>
        					<td class="text-center">' . $qualidade . '</td>
        					<td class="text-center">' . $row->obs . '</td>
        				</tr>';
				}
			}
			break;
		case 4:
			$lista = $dados->buscaPorData($_POST['dt1'], $_POST['dt2']);
			if (!isset($lista[0]->id)) {
				echo '<div class="bg-dark"><h5 class="text-warning text-center">Nenhuma Classificação foi realizada nesse período!</h5></div>';
			} else {
				foreach ($lista as $key => $row) {
					if ($row->preco == 1) {
						$preco = "Ruim";
						$qtdPrecoRuim++;
					}
					if ($row->preco == 2) {
						$preco = "Médio";
						$qtdPrecoMedio++;
					}
					if ($row->preco == 3) {
						$preco = "Bom";
						$qtdPrecoBom++;
					}
					if ($row->prazo == 1) {
						$prazo = "Ruim";
						$qtdPrazoRuim++;
					}
					if ($row->prazo == 2) {
						$prazo = "Médio";
						$qtdPrazoMedio++;
					}
					if ($row->prazo == 3) {
						$prazo = "Bom";
						$qtdPrazoBom++;
					}
					if ($row->qualidade == 1) {
						$qualidade = "Ruim";
						$qtdQualidadeRuim++;
					}
					if ($row->qualidade == 2) {
						$qualidade = "Médio";
						$qtdQualidadeMedio++;
					}
					if ($row->qualidade == 3) {
						$qualidade = "Bom";
						$qtdQualidadeBom++;
					}
					echo '	<td class="text-center">' . $row->id . '</td>
        					<td class="text-center">' . $row->codreq . '</td>
        					<td class="text-center">' . $row->provedor . '</td>
        					<td class="text-center">' . date("d/m/Y", strtotime($row->data)) . '</td>
        					<td class="text-center">' . $preco . '</td>
        					<td class="text-center">' . $prazo . '</td>
        					<td class="text-center">' . $qualidade . '</td>
        					<td class="text-center">' . $row->obs . '</td>
        				</tr>';
				}
			}
			break;

		default:

			break;
		}
		echo '	</tbody>
        	</table>
			<script>
				var ctx1 = document.getElementsByClassName("pie-chart1");
				var ctx2 = document.getElementsByClassName("pie-chart2");
				var ctx3 = document.getElementsByClassName("pie-chart3");

				var data1 = {
				    labels : ["Bom", "Médio", "Ruim"],
				    datasets : [
				    	{
				        	label : "Preço",
				        	data : [' . $qtdPrecoBom . ', ' . $qtdPrecoMedio . ', ' . $qtdPrecoRuim . '],
          					backgroundColor : [
          				  	  "#32CD32",
          				  	  "#FFD700",
          				  	  "#FF0000"
          					],
          					borderColor : [
          				  	  "#006400",
          				  	  "#DAA520",
          				  	  "#B22222"
          					],
          					borderWidth : [1, 1, 1]
      					}
    				]
				};

				var data2 = {
				    labels : ["Bom", "Médio", "Ruim"],
				    datasets : [
				      	{
				          	label : "Prazo",
				          	data : [' . $qtdPrazoBom . ', ' . $qtdPrazoMedio . ', ' . $qtdPrazoRuim . '],
          					backgroundColor : [
          					  "#32CD32",
          					  "#FFD700",
          					  "#FF0000"
          					],
          					borderColor : [
          					  "#006400",
          					  "#DAA520",
          					  "#B22222"
          					],
          					borderWidth : [1, 1, 1]

      					}
    				]
				};

				var data3 = {
				    labels : ["Bom", "Médio", "Ruim"],
				    datasets : [
				      {
				          label : "Qualidade",
				          data : [' . $qtdQualidadeBom . ', ' . $qtdQualidadeMedio . ', ' . $qtdQualidadeRuim . '],
				          backgroundColor : [
				            "#32CD32",
				            "#FFD700",
				            "#FF0000"
				          ],
				          borderColor : [
				            "#006400",
				            "#DAA520",
				            "#B22222"
				          ],
				          borderWidth : [1, 1, 1]

				      }
				    ]
				};

				var options = {
				  legend: {
				    display : false
				  }
				}

				var chart1 = new Chart(ctx1, {
				  type: "pie",
				  data: data1,
				  options: options
				});

				var chart2 = new Chart(ctx2, {
				  type: "pie",
				  data: data2,
				  options: options
				});

				var chart3 = new Chart(ctx3, {
				  type: "pie",
				  data: data3,
				  options: options
				});
			</script>';
	}

	public function notificaMail($email, $tipo, $codreq, $user) {

		// Load Composer's autoloader
		//require '../../../intranet/composer/vendor/autoload.php';
		//echo "$email, $tipo, $codreq, $user";
		//exit();

		// Instantiation and passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try {
			//Server settings
			//$mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output
			$mail->isSMTP(); // Send using SMTP
			$mail->Host = 'smtp-mail.outlook.com'; // Set the SMTP server to send through
			$mail->SMTPAuth = true; // Enable SMTP authentication
			$mail->Username = 'ti@aferitec.com.br'; // SMTP username
			$mail->Password = 'Aferitec*3052'; // SMTP password
			$mail->SMTPSecure = 'tls'; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
			$mail->Port = 587; // TCP port to connect to
			$mail->CharSet = 'utf-8';

			//Recipients
			$mail->setFrom('ti@aferitec.com.br', 'Notificação PDR');
			$mail->addAddress($email); // Add a recipient
			//$mail->addAddress('jeielrdp@gmail.com');               // Name is optional
			//$mail->addReplyTo('jeiel@aferitec.com.br', 'jeiel TI');
			//$mail->addCC('jeielrod@gmail.com');
			//$mail->addBCC('bcc@example.com');

			// Attachments
			//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

			// Content
			$mail->isHTML(true); // Set email format to HTML

			//print_r($mail);
			//exit();

			if ($tipo == 'A') {
				$mail->Subject = 'RC nº ' . $codreq . ' aprovada !';
				$mail->Body = "<span style='font-size:14.0pt;color:#0B0B61'><p><b>Intranet Aferitec alerta:</span><br><br><span style='font-size:12.0pt;color:#0B0B61'>" . $user . ", sua requisição de compra nº " . $codreq . " foi aprovada e enviada para o setor de compras! </b></p></span><br>
            	<p><b><i><span style='font-size:9.0pt;color:#A6A6A6'>
            	Essa é uma  mensagem automática gerada pela Intranet.
            	<o:p></o:p></span></i></b></p>";

			} elseif ($tipo == 'L') {
				$mail->Subject = 'RC nº ' . $codreq . ' liberada !';
				$mail->Body = "<span style='font-size:14.0pt;color:#0B0B61'><p><b>Intranet Aferitec alerta:</span><br><br><span style='font-size:12.0pt;color:#0B0B61'>" . $user . ", sua requisição de compra nº " . $codreq . " foi liberada e enviada para aprovação! </b></p></span><br>
            	<p><b><i><span style='font-size:9.0pt;color:#A6A6A6'>
            	Essa é uma  mensagem automática gerada pela Intranet.
            	<o:p></o:p></span></i></b></p>";

			} elseif ($tipo == 'R') {
				$mail->Subject = 'RC nº ' . $codreq . ' reprovada !';
				$mail->Body = "<span style='font-size:14.0pt;color:#0B0B61'><p><b>Intranet Aferitec alerta:</span><br><br><span style='font-size:12.0pt;color:#0B0B61'>" . $user . ", sua requisição de compra nº " . $codreq . " infelizmente foi recusada. Você ainda pode recuperá-la no portal, clicando em ALTERAR! </b></p></span><br>
            	<p><b><i><span style='font-size:9.0pt;color:#A6A6A6'>
            	Essa é uma  mensagem automática gerada pela Intranet.
            	<o:p></o:p></span></i></b></p>";

			} elseif ($tipo == 'C') {
				$mail->Subject = 'RC nº ' . $codreq . ' cancelada !';
				$mail->Body = "<span style='font-size:14.0pt;color:#0B0B61'><p><b>Intranet Aferitec alerta:</span><br><br><span style='font-size:12.0pt;color:#0B0B61'>" . $user . ", sua requisição de compra nº " . $codreq . " foi cancelada! Você pode ver mais detalhes acessando o portal, clicando no botão visualizar na requisição específica. </b></p></span><br>
            	<p><b><i><span style='font-size:9.0pt;color:#A6A6A6'>
            	Essa é uma  mensagem automática gerada pela Intranet.
            	<o:p></o:p></span></i></b></p>";

			}
			elseif ($tipo == 'X') {
								$mail->Subject = "RC's com erros";
								$mail->Body = "<span style='font-size:14.0pt;color:#0B0B61'><p><b>Intranet Aferitec alerta:</span><br><br><span style='font-size:12.0pt;color:#0B0B61'>ADM, algumas requisições não conseguiram entrar no Omie quando foram aprovadas!<br> Utilize esse script no banco de dados pra identificá-las:<br>SELECT count(codreq) FROM rc WHERE status = 3 AND DATEDIFF(CURDATE(), dt_mov) > 5 </b></p></span><br>
				            	<p><b><i><span style='font-size:9.0pt;color:#A6A6A6'>
				            	Essa é uma  mensagem automática gerada pela Intranet.
				            	<o:p></o:p></span></i></b></p>";
			}
			/*
							} elseif ($tipo == 'Z') {
								$mail->Subject = "RC's para aprovação";
								$mail->Body = "<span style='font-size:14.0pt;color:#0B0B61'><p><b>Intranet Aferitec alerta:</span><br><br><span style='font-size:12.0pt;color:#0B0B61'>" . $user . ", há no momento mais de 10 requisições de compras pendentes de sua aprovação! </b></p></span><br>
				            	<p><b><i><span style='font-size:9.0pt;color:#A6A6A6'>
				            	Essa é uma  mensagem automática gerada pela Intranet.
				            	<o:p></o:p></span></i></b></p>";
			*/

			$mail->AltBody = 'Intranet Aferitec | via PHPMailer | by Jeiel Rodrigues.';

			$mail->send();

			$erro = 'OK';
		} catch (Exception $e) {
			$erro = "Erro ao enviar e-mail de notificação: {$mail->ErrorInfo}";
		}
		return $erro;
	}

	public function encurtaString($string) {
		$tamanho = strlen($string);
		if ($tamanho > 20) {
			$string = substr($string, 0, 20) . "...";
		}
		return $string;
	}

//

	################# ACTIONS ###############

//Cria RC
	public function criaRC() {

		//print_r($_POST);

		if (!isset($_SESSION)) {session_start();}

		if ($_POST['numitens'] != "") {
			$numitens = $_POST['numitens'];
			for ($i = 1; $i <= $numitens; $i++) {
				$codprod[$i] = substr($_POST['produto_' . $i], 0, 3); //pega "PRD"
				if ($codprod[$i] != "PRD") {
					echo "
        				<script type=\"text/javascript\">
        		    		alert(\"Item " . $_POST['produto_' . $i] . " não existe no sistema Omie!\");
							history.go(-1)
        				</script>
        		  		";
					exit();
				}
			}

			$owner = $_SESSION['responsavel_rc'];
			if ($owner == 'WANDERSON') {
				$status = 2;
			} else {
				$status = 1;
			}

			$erros = '';

			$categoria = explode(" -", $_POST['categoria']); // explode separa strings em array

			$dpto = explode(" -", $_POST['dpto']);

			$today = date('Y-m-d');

			if ($_POST['entrega'] == "") {
				$dtsugestao = date($today, strtotime("+15 days"));
			} else {
				$dtsugestao = $_POST['entrega'];
			}

			//Cadastrando Requisição
			$rc = Container::getModel('RC');
			$rc->__set('categoria', $categoria[0]);
			$rc->__set('dpto', $dpto[0]);
			$rc->__set('dt_mov', date('Y-m-d H:i:s'));
			$rc->__set('dtsugestao', $dtsugestao);
			$rc->__set('id_user', $_SESSION['id']);
			$rc->__set('owner', $owner);
			$rc->__set('obs', $_POST['obs']);
			$rc->__set('status', $status);

			$result_rc = $rc->criarRC();

			if (substr($result_rc, 0, 4) != 'Erro') {

				$codreq = $result_rc;

				//inserção dos arquivos
				if ($_FILES['arquivo']['size'][0] > 0) {

					$countfiles = count($_FILES['arquivo']['name']);

					for ($i = 0; $i < $countfiles; $i++) {
						$id_file = $i + 1;
						$filename = $_FILES['arquivo']['name'][$i];
						$extension = explode('.', $filename); //array mode, because the function
						$ext = end($extension); //explode separate in array

						$files = Container::getModel('RCfiles');
						$files->__set('id_file', $id_file);
						$files->__set('filename', $filename);
						$files->__set('extension', $ext);

						//faz upload dos anexos
						if (move_uploaded_file($_FILES['arquivo']['tmp_name'][$i],
							'anexosPDR/' . $codreq . '_' . $id_file . '.' . $ext)) {

							//cadastra o upload
							$result_files = $files->addFile($codreq);
							if ($result_files != "ok") {
								$erros .= "Falha ao cadastrar anexos: " . $result_files . " <br> ";
							}

						} else { $erros .= "Falha ao anexar arquivos <br> ";}
					}
				}

				//inserção dos itens
				for ($i = 1; $i <= $numitens; $i++) {

					$qsj_usa[$i] = str_replace(",", ".",
						str_replace(".", "", ($_POST["dinheiro_" . $i . ""])));
					$qsj[$i] = floatval($qsj_usa[$i]);
					$qtd[$i] = $_POST['qtd_' . $i];
					$total[$i] = floatval($qsj[$i]) * $qtd[$i];
					$codprod[$i] = substr($_POST['produto_' . $i], 0, 8); //pega nº do item
					$descrprod[$i] = substr($_POST['produto_' . $i], 11); //pega a descrição

					$itens = Container::getModel('itensRC');
					$itens->__set('coditem', $i);
					$itens->__set('codprod', $codprod[$i]);
					$itens->__set('descrprod', $descrprod[$i]);
					$itens->__set('obsitem', $_POST['especific_' . $i]);
					$itens->__set('precounit', $qsj[$i]);
					$itens->__set('qtde', $qtd[$i]);
					$itens->__set('total', $total[$i]);
					$result_itens = $itens->addItem($codreq);

					if ($result_itens != "ok") {
						$erros .= "Falha ao cadastrar os itens da RC: " . $result_itens . " <br> ";
					}
				}

			} else { $erros .= "Requisição não cadastrada: " . $result_rc . " <br> ";}
			#if ($erros == '') {
			#	echo "sem erros";
			#} else {
			#	echo $erros;
			#}
			#exit();
			if ($erros != '') {
				echo "
					<h5 class='exit'>" . $erros . "</h5>
        			<a type='button' href='/nova_rc' class='btn-success'>Voltar</a>
        			  ";
			} else {
				echo "
        			<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http:/pdr'>
        			<script type=\"text/javascript\">
        			    alert(\"Requisição cadastrada com sucesso!\");
        			</script>
        			  ";
			}
		} else {
			echo "
        		<script type=\"text/javascript\">
        		    alert(\"Impossível cadastrar RC sem itens!\");
					history.go(-1)
        		</script>
        		  ";
		}

	}
//

//Libera RC
	public function liberaRC() {

		if (!isset($_SESSION)) {session_start();}

		$rc = Container::getModel('RC');
		$busca = $rc->buscaPorRC($_POST['codreq']);
		$owner = $_SESSION['nome'];
		$obs = $busca['obs'];
		if ($obs != '' and $_POST['obs_aprov'] != '') {
			$obs = $obs . " | " . $owner . ": " . $_POST['obs_aprov'];
		} elseif ($obs == '' and $_POST['obs_aprov'] != '') {
			$obs = $owner . ": " . $_POST['obs_aprov'];
		}

		if (isset($_POST['liberar']) and (isset($_POST['reprovar']))) {
			echo "
        		<script type=\"text/javascript\">
        		    alert(\"Deseja liberar ou reprovar essa requisição? Escolha apenas uma opção!\");
					history.go(-1)
        		</script>
        		  ";
		} elseif (isset($_POST['liberar']) and !isset($_POST['reprovar'])) {
			$rc->__set('user_alter', $_SESSION['id']);
			$rc->__set('codreq', $_POST['codreq']);
			$rc->__set('dt_mov', date('Y-m-d H:i:s'));
			$rc->__set('obs', $obs);
			$rc->__set('status', 2);

			$result_rc = $rc->liberarRC();

			if (substr($result_rc, 0, 4) == 'Erro') {
				echo "
				<h5 class='exit'>" . $result_rc . "</h5>
        		<a type='button' href='/liberar' class='btn-success'>Voltar</a>
        		  ";
			} else {
				$erro = $this->notificaMail($busca['email'], "L", $_POST['codreq'], $busca['nome']);
				if ($erro != 'OK') {
					echo "<h5 class='exit'>Requisição liberada com sucesso, porém: " . $erro . "</h5>
					<a type='button' href='/liberar' class='btn-success'>Voltar</a>";
				} else {
					echo "
					<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http:/liberar'>
        			<script type=\"text/javascript\">
        			    alert(\"" . $result_rc . "\");
        			</script>
        			  ";
				}
			}

		} elseif (!isset($_POST['liberar']) and isset($_POST['reprovar'])) {
			$rc->__set('user_alter', $_SESSION['id']);
			$rc->__set('codreq', $_POST['codreq']);
			$rc->__set('dt_mov', date('Y-m-d H:i:s'));
			$rc->__set('motivo', $_POST['motivo']);
			$rc->__set('status', 7);

			$result_rc = $rc->reprovarRC();

			if (substr($result_rc, 0, 4) == 'Erro') {
				echo "
				<h5 class='exit'>" . $result_rc . "</h5>
        		<a type='button' href='/liberar' class='btn-success'>Voltar</a>
        		  ";
			} else {
				$erro = $this->notificaMail($busca['email'], "R", $_POST['codreq'], $busca['nome']);
				if ($erro != 'OK') {
					echo "<h5 class='exit'>Requisição reprovada, porém: " . $erro . "</h5>
					<a type='button' href='/liberar' class='btn-success'>Voltar</a>";
				} else {
					echo "
					<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http:/liberar'>
        			<script type=\"text/javascript\">
        			    alert(\"" . $result_rc . "\");
        			</script>
        			  ";
				}
			}
		}

	}
//

//Aprova RC
	public function aprovaRC() {

		if (!isset($_SESSION)) {session_start();}

		$rc = Container::getModel('RC');
		$busca = $rc->buscaPorRC($_POST['codreq']);
		$owner = $_SESSION['nome'];
		$obs = $busca['obs'];
		if ($obs != '' and $_POST['obs_aprov'] != '') {
			$obs = $obs . " | " . $owner . ": " . $_POST['obs_aprov'];
		} elseif ($obs == '' and $_POST['obs_aprov'] != '') {
			$obs = $owner . ": " . $_POST['obs_aprov'];
		}

		if (isset($_POST['aprovar']) and isset($_POST['reprovar']) and isset($_POST['cancelar'])) {
			echo "
        		<script type=\"text/javascript\">
        		    alert(\"Deseja aprovar, reprovar ou cancelar essa requisição? Escolha apenas uma opção!\");
					history.go(-1)
        		</script>
        		  ";
		} elseif (!isset($_POST['aprovar']) and isset($_POST['reprovar']) and isset($_POST['cancelar'])) {
			echo "
        		<script type=\"text/javascript\">
        		    alert(\"Deseja aprovar, reprovar ou cancelar essa requisição? Escolha apenas uma opção!\");
					history.go(-1)
        		</script>
        		  ";
		} elseif (isset($_POST['aprovar']) and !isset($_POST['reprovar']) and isset($_POST['cancelar'])) {
			echo "
        		<script type=\"text/javascript\">
        		    alert(\"Deseja aprovar, reprovar ou cancelar essa requisição? Escolha apenas uma opção!\");
					history.go(-1)
        		</script>
        		  ";
		} elseif (isset($_POST['aprovar']) and isset($_POST['reprovar']) and !isset($_POST['cancelar'])) {
			echo "
        		<script type=\"text/javascript\">
        		    alert(\"Deseja aprovar, reprovar ou cancelar essa requisição? Escolha apenas uma opção!\");
					history.go(-1)
        		</script>
        		  ";
		} elseif (isset($_POST['aprovar']) and !isset($_POST['reprovar']) and !isset($_POST['cancelar'])) {
			$rc->__set('user_alter', $_SESSION['id']);
			$rc->__set('codreq', $_POST['codreq']);
			$rc->__set('dt_mov', date('Y-m-d H:i:s'));
			$rc->__set('obs', $obs);
			$rc->__set('status', 3);

			$rc_api = Container::getModel('Api');
			$result_api = $rc_api->criaRC($_POST['codreq']);
			$result_api = json_decode($result_api);

			if (isset($result_api->cCodStatus)) {
				$result_rc = $rc->aprovarRC();	//atualiza no banco de dados para status 3 aprovado

				if (substr($result_rc, 0, 4) == 'Erro') {
					echo "
					<h5 class='exit'>" . $result_rc . "</h5>
        			<a type='button' href='/aprovar' class='btn-success'>Voltar</a>
        		  	";
				} else {
					$erro = $this->notificaMail($busca['email'], "A", $_POST['codreq'], $busca['nome']);
					if ($erro != 'OK') {
						echo "<h5 class='exit'>Requisição aprovada com sucesso, porém: " . $erro . "</h5>
						<a type='button' href='/aprovar' class='btn-success'>Voltar</a>";
					} else {
						echo "
						<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http:/aprovar'>
        				<script type=\"text/javascript\">
        			    	alert(\"" . $result_rc . "\");
        				</script>
        			  	";
					}
				}
				
			} else {
				echo "
				<h5 class='exit'>Problemas foram encontrados ao sincronizar com o Omie, favor contaatar o Suporte!</h5>
        		<a type='button' href='/aprovar' class='btn-success'>Voltar</a>
        		  ";
			}


		} elseif (!isset($_POST['aprovar']) and isset($_POST['reprovar']) and !isset($_POST['cancelar'])) {
			$rc->__set('user_alter', $_SESSION['id']);
			$rc->__set('codreq', $_POST['codreq']);
			$rc->__set('dt_mov', date('Y-m-d H:i:s'));
			$rc->__set('motivo', $_POST['motivo']);
			$rc->__set('status', 7);

			$result_rc = $rc->reprovarRC();

			if (substr($result_rc, 0, 4) == 'Erro') {
				echo "
				<h5 class='exit'>" . $result_rc . "</h5>
        		<a type='button' href='/aprovar' class='btn-success'>Voltar</a>
        		  ";
			} else {
				$erro = $this->notificaMail($busca['email'], "R", $_POST['codreq'], $busca['nome']);
				if ($erro != 'OK') {
					echo "<h5 class='exit'>Requisição reprovada, porém: " . $erro . "</h5>
					<a type='button' href='/aprovar' class='btn-success'>Voltar</a>";
				} else {
					echo "
					<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http:/aprovar'>
        			<script type=\"text/javascript\">
        			    alert(\"" . $result_rc . "\");
        			</script>
        			  ";
				}
			}
		} elseif (!isset($_POST['aprovar']) and !isset($_POST['reprovar']) and isset($_POST['cancelar'])) {
			$rc->__set('user_alter', $_SESSION['id']);
			$rc->__set('codreq', $_POST['codreq']);
			$rc->__set('dt_mov', date('Y-m-d H:i:s'));
			$rc->__set('motivo', $_POST['motivo']);
			$rc->__set('status', 8);

			$result_rc = $rc->cancelarRC();

			if (substr($result_rc, 0, 4) == 'Erro') {
				echo "
				<h5 class='exit'>" . $result_rc . "</h5>
        		<a type='button' href='/aprovar' class='btn-success'>Voltar</a>
        		  ";
			} else {
				$erro = $this->notificaMail($busca['email'], "C", $_POST['codreq'], $busca['nome']);
				if ($erro != 'OK') {
					echo "<h5 class='exit'>Requisição cancelada, porém: " . $erro . "</h5>
					<a type='button' href='/aprovar' class='btn-success'>Voltar</a>";
				} else {
					echo "
					<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http:/aprovar'>
        			<script type=\"text/javascript\">
        			    alert(\"" . $result_rc . "\");
        			</script>
        			  ";
				}
			}
		}

	}
//

//Edita RC

	public function editaRC() {

		//print_r($_POST);exit();

		if (!isset($_SESSION)) {session_start();}

		if ($_POST['numitens'] != "") {
			$numitens = $_POST['numitens'];
			for ($i = 1; $i <= $numitens; $i++) {
				$codprod[$i] = substr($_POST['produto_' . $i], 0, 3); //pega "PRD"
				if ($codprod[$i] != "PRD") {
					echo "
        				<script type=\"text/javascript\">
        		    		alert(\"Item " . $_POST['produto_' . $i] . " não existe no sistema Omie!\");
							history.go(-1)
        				</script>
        		  		";
					exit();
				}
			}

			$owner = $_SESSION['responsavel_rc'];
			if ($owner == 'WANDERSON') {
				$status = 2;
			} else {
				$status = 1;
			}

			$erros = '';

			$categoria = explode(" -", $_POST['categoria']); // explode separa strings em array

			$dpto = explode(" -", $_POST['dpto']);

			$today = date('Y-m-d');

			if ($_POST['entrega'] == "") {
				$dtsugestao = date($today, strtotime("+15 days"));
			} else {
				$dtsugestao = $_POST['entrega'];
			}

			//Atualizando Requisição
			$rc = Container::getModel('RC');
			$rc->__set('categoria', $categoria[0]);
			$rc->__set('dpto', $dpto[0]);
			$rc->__set('dt_mov', date('Y-m-d H:i:s'));
			$rc->__set('dtsugestao', $dtsugestao);
			$rc->__set('obs', $_POST['obs']);
			$rc->__set('status', $status);
			$rc->__set('codreq', $_POST['codreq']);

			$result_rc = $rc->atualizarRC();

			if (substr($result_rc, 0, 4) != 'Erro') {

				$codreq = $_POST['codreq'];

				//inserção dos arquivos
				if ($_FILES['arquivo']['size'][0] > 0) {

					$countfiles = count($_FILES['arquivo']['name']);

					for ($i = 0; $i < $countfiles; $i++) {
						$id_file = $i + 1;
						$filename = $_FILES['arquivo']['name'][$i];
						$extension = explode('.', $filename); //array mode, because the function
						$ext = end($extension); //explode separate in array

						$files = Container::getModel('RCfiles');
						$files->__set('id_file', $id_file);
						$files->__set('filename', $filename);
						$files->__set('extension', $ext);

						//faz upload dos anexos
						if (move_uploaded_file($_FILES['arquivo']['tmp_name'][$i],
							'anexosPDR/' . $codreq . '_' . $id_file . '.' . $ext)) {

							//cadastra o upload
							$result_files = $files->addFile($codreq);
							if ($result_files != "ok") {
								$erros .= "Falha ao cadastrar anexos: " . $result_files . " <br> ";
							}

						} else { $erros .= "Falha ao anexar arquivos <br> ";}
					}
				}

				$itens = Container::getModel('itensRC');

				//deletando itens antigos

				$delete_itens = $itens->deleteItens($codreq);
				if ($delete_itens != "ok") {
					$erros .= "Falha ao excluir os itens da RC: " . $delete_itens . " <br> ";
				}

				//inserção dos itens
				for ($i = 1; $i <= $numitens; $i++) {

					$qsj_usa[$i] = str_replace(",", ".",
						str_replace(".", "", ($_POST["dinheiro_" . $i . ""])));
					$qsj[$i] = floatval($qsj_usa[$i]);
					$qtd[$i] = $_POST['qtd_' . $i];
					$total[$i] = floatval($qsj[$i]) * $qtd[$i];
					$codprod[$i] = substr($_POST['produto_' . $i], 0, 8); //pega nº do item
					$descrprod[$i] = substr($_POST['produto_' . $i], 11); //pega a descrição

					$itens->__set('coditem', $i);
					$itens->__set('codprod', $codprod[$i]);
					$itens->__set('descrprod', $descrprod[$i]);
					$itens->__set('obsitem', $_POST['especific_' . $i]);
					$itens->__set('precounit', $qsj[$i]);
					$itens->__set('qtde', $qtd[$i]);
					$itens->__set('total', $total[$i]);
					$result_itens = $itens->addItem($codreq);

					if ($result_itens != "ok") {
						$erros .= "Falha ao cadastrar os itens da RC: " . $result_itens . " <br> ";
					}
				}

			} else { $erros .= "Requisição não atualizada: " . $result_rc . " <br> ";}
			#if ($erros == '') {
			#	echo "sem erros";
			#} else {
			#	echo $erros;
			#}
			#exit();
			if ($erros != '') {
				echo "
					<h5 class='exit'>" . $erros . "</h5>
        			<a type='button' href='/nova_rc' class='btn-success'>Voltar</a>
        			  ";
			} else {
				echo "
        			<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http:/pdr'>
        			<script type=\"text/javascript\">
        			    alert(\"Requisição atualizada com sucesso!\");
        			</script>
        			  ";
			}
		} else {
			echo "
        		<script type=\"text/javascript\">
        		    alert(\"Impossível cadastrar RC sem itens!\");
					history.go(-2);
        		</script>
        		  ";
		}

	}

	public function EditarRC() {

		$rc = Container::getModel('RC');

		if (!isset($_SESSION)) {session_start();}

		if (isset($_POST['editar']) and !isset($_POST['cancelar'])) {

			$this->editar();

		} elseif (!isset($_POST['editar']) and isset($_POST['cancelar'])) {
			$rc->__set('user_alter', $_SESSION['id']);
			$rc->__set('codreq', $_POST['codreq']);
			$rc->__set('dt_mov', date('Y-m-d H:i:s'));
			$rc->__set('motivo', $_POST['motivo']);
			$rc->__set('status', 8);

			$result_rc = $rc->cancelarRC();

			if (substr($result_rc, 0, 4) == 'Erro') {
				echo "
				<h5 class='exit'>" . $result_rc . "</h5>
        		<a type='button' href='/pdr' class='btn-success'>Voltar</a>
        		  ";
			} else {
				echo "
				<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http:/pdr'>
        		<script type=\"text/javascript\">
        		    alert(\"" . $result_rc . "\");
        		</script>
        		  ";
			}

		} elseif (!isset($_POST['editar']) and !isset($_POST['cancelar'])) {
			echo "
				<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http:/pdr'>
        		<script type=\"text/javascript\">
        		    alert(\"Escolha ao menos 1 opção!\");
        		</script>
        		  ";

		} elseif (isset($_POST['editar']) and isset($_POST['cancelar'])) {
			echo "
				<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http:/pdr'>
        		<script type=\"text/javascript\">
        		    alert(\"Escolha somente 1 opção!\");
        		</script>
        		  ";
		}
	}
//

//Pesquisa RC (QUANDO NÃO COLOCA NADA NA CAIXA DE PESQUISA)
	public function pesquisarAll() {
		if (!isset($_SESSION)) {session_start();}
		$user = $_SESSION['nome'];
		$qtd   = json_decode($this->qtdByUser());

		//PAGINAÇÃO
		$objPagination = new Pagination($qtd->qtd, $_POST['pagina'] ?? 1);
		$limite = $objPagination->getLimit();		
		$lista = json_decode($this->listaAll($limite));
		$itens = json_decode($this->listaItens());
		$files = json_decode($this->listaFiles());


		$paginas = $objPagination->getPages();
		//echo "<pre>";
		//	print_r($objPagination);
 		//	print_r($objPagination->getLimit());
		//echo "</pre>";

		echo '<form class="form-inline" action="/pesquisar" method="POST">
				<input type="hidden" name="rc" value="0">';
		foreach ($paginas as $key => $pagina) {
			if ($pagina['atual'] == 1) {
				echo '<button type="submit" name="pagina" value="'.$pagina['pagina'].'" class="btn mx-1 my-1 btn-sm btn-primary">'.$pagina['pagina'].'</button>';
			}
			else {
				echo '<button type="submit" name="pagina" value="'.$pagina['pagina'].'" class="btn mx-1 my-1 btn-sm btn-dark">'.$pagina['pagina'].'</button>';
			}
		}
		echo '</form>';
		echo '
		<table class="table table-sm table-bordered table-responsive-sm table-hover ">
      		<thead class="thead-dark">
       	 		<tr>
          			<th scope="col" class="align-middle text-center dont-break-out">RC</th>
          			<th scope="col" class="align-middle text-center dont-break-out">DATA ALTERAÇÃO</th>
          			<th scope="col" class="align-middle text-center dont-break">ÚLTIMA ALTERAÇÃO</th>
          			<th scope="col" class="align-middle text-center dont-break">CATEGORIA</th>
          			<th scope="col" class="align-middle text-center dont-break">STATUS</th>
          			<th scope="col" class="align-middle text-center dont-break">CONFORMIDADE</th>
          			<th scope="col" class="align-middle text-center dont-break"><i class="fa fa-eye" aria-hidden="true"></i></th>
        		</tr>
      		</thead>
      		<tbody>
        		<tr>
			';
		$n = 1;
		foreach ($lista as $key => $rc) {
			$alterador = ($rc->alterador == NULL) ? $rc->nome : $rc->alterador;
			echo '
                <td class="align-middle text-center dont-break">' . $rc->codreq . '</td>
                <td class="align-middle text-center dont-break">' . date("d/m/Y H:i:s", strtotime($rc->dt_mov)) . '</td>
                <td class="align-middle text-center dont-break">' . $alterador . '</td>
                <td class="align-middle text-cenetr dont-break-out">' . $rc->categoria . '</td>';

			switch ($rc->status) {
			case 1:
				echo "<td bgcolor='#6495ED' class='align-middle' style='font-weight:bold; color:white; text-align: center'>" . $rc->descricao . "</td>";
				break;
			case 2:
				echo "<td bgcolor='#0000CD' class='align-middle' style='font-weight:bold; color:white; text-align: center'>" . $rc->descricao . "</td>";
				break;
			case 3:
				echo "<td bgcolor='#006400' class='align-middle' style='font-weight:bold; color:white; text-align: center'>" . $rc->descricao . "</td>";
				break;
			case 4:
				echo "<td bgcolor='#00EE00' class='align-middle' style='color:white; text-align: center'>" . $rc->descricao . "</td>";
				break;
			case 5:
				echo "<td bgcolor='#FFFF00' class='align-middle' style='font-weight:bold; color:black; text-align: center'>" . $rc->descricao . "</td>";
				break;
			case 6:
				echo "<td bgcolor='#CDC9C9' class='align-middle' style='font-weight:bold; color:white; text-align: center'>" . $rc->descricao . "</td>";
				break;
			case 7:
				echo "<td bgcolor='#FF0000' class='align-middle' style='font-weight:bold; color:white; text-align: center'>" . $rc->descricao . "</td>";
				break;
			case 8:
				echo "<td bgcolor='#8B2323' class='align-middle' style='font-weight:bold; color:white; text-align: center'>" . $rc->descricao . "</td>";
				break;

			default:
				echo "<td bgcolor='#8B2323' class='align-middle' style='font-weight:bold; color:white; text-align: center'>" . $rc->descricao . "</td>";
				break;
			}

			echo '
            <td class="align-middle text-center dont-break-out">' . $rc->conformidade . '</td>
            <td class="align-middle text-center dont-break-out">
              <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-tt="tooltip" data-placement="left" title="VISUALIZAR" data-target="#modalvisu' . $rc->codreq . '">
                  <i class="fa fa-eye" aria-hidden="true"></i>
              </button>
            </td>
            </tr>';
			$n++;
		}
		echo '
			</tbody>
    	</table>
        ';
		//-------------------- modal visualizar

		foreach ($lista as $key => $modal) {
			echo "
		<div class='modal fade' id='modalvisu" . $modal->codreq . "' tabindex='-1' role='dialog' aria-labelledby='exampleModalLongTitle' aria-hidden= 'true'>
			<div class='modal-dialog modal-lg modal-dialog-centered' role='document'>
    			<div class='modal-content'>
    				<div class='modal-header'>
    					<h3 class='modal-title' id='exampleModalLongTitle'>
    						RC " . $modal->codreq . "
    					</h3>
    					<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
          					<span aria-hidden='true'>&times;</span>
        				</button>
      				</div>
      				<div class='modal-body'>
      					<div class='row'>
          					<div class='col-lg-9'>
            					<div class='container'>
              						<h3>Anexos</h3>
              						<div class='row'>";
			foreach ($files as $key => $file) {
				$img = $this->img_file($file->extension);
				if ($file->codreq == $modal->codreq) {
					if ($file->filename != '') {
						echo "<div class='col-md-3'>
                      						<a href='anexosPDR/" . $file->codreq . "_" . $file->id_file . "." . $file->extension . "' target='_blank' class='link_arquivo'>
                      							<div class='divAnexos'>
                            						<p class='dont-break-out'>$img $file->filename</p>
                          						</div>
                        					</a>
										</div>";
					} else {
						echo "<div class='container'><p>Sem anexos</p></div>";
					}
				}
			} //fechamento do foreach
			echo '</div>
            					</div>
            			<table class="table table-sm table-bordered table-responsive-sm table-hover">
              				<thead class="thead-dark">
              				  <tr>
              				    <th scope="col" class="align-middle text-center dont-break-out">ITEM</th>
              				    <th scope="col" class="align-middle text-center dont-break">ESPECIFICAÇÃO</th>
              				    <th scope="col" class="align-middle text-center dont-break">PREÇO UNIT.</th>
              				    <th scope="col" class="align-middle text-center dont-break">QTD</th>
              				    <th scope="col" class="align-middle text-center dont-break">TOTAL</th>
              				  </tr>
              				</thead>
              				<tbody>
                				<tr>';
			foreach ($itens as $key => $item) {
				if ($item->codreq == $modal->codreq) {
					echo '<td class="align-middle text-center dont-break">
                      					' . $item->descrprod . '
                      					</td>
                      					<td class="align-middle text-center dont-break-out">
                      					' . $item->obsitem . '
                      					</td>
                      					<td class="align-middle text-center dont-break">
                      					' . $item->precounit . '
                      					</td>
                      					<td class="align-middle text-center dont-break">
                      					' . $item->qtde . '
                      					</td>
                      					<td class="align-middle text-center dont-break">
                      					' . $item->total . '
                      					</td>
                      				</tr>';
				}
			} //fechamento foreach
			echo '
							</tbody>
            			</table>
            		</div>
					<div class="col-lg-3">';
			if ($modal->status == 7 or $modal->status == 8) {
				echo '
						<div class="divVisu">
                			<div class="divLabelVisu">
                  				<span class="labelVisu2">MOTIVO DA REPROVAÇÃO</span>
                			</div>
                			<div class="pVisu">
                			  <p class="dont-break-out">' . $modal->motivo_rep . '</p>
                			</div>
              			</div>';
			}

			if ($modal->status >= 3) {
				$rc_pedido = Container::getModel('itensRC');
				$lista_pedido = $rc_pedido->buscaPedidoPesquisar($modal->codreq);
				echo '
					<div class="divVisu">
              		  <div class="divLabelVisu">
            		    <span class="labelVisu2">Nº RC no OMIE</span>
            		  </div>
            		  <div class="pVisu">
            		    <p class="dont-break-out">' . $modal->cod_omie . '</p>
            		  </div>
            		</div>
					<div class="divVisu">
                		<div class="divLabelVisu">
                  			<span class="labelVisu2">Nº PEDIDO DE COMPRA</span>
                		</div>
                		<div class="pVisu">';
				foreach ($lista_pedido as $key => $pedido) {
					if ($pedido->num_pedido != 0) {
						echo '<p class="dont-break-out"><b>' . $pedido->num_pedido . '</b> (' . $this->encurtaString($pedido->descrprod) . ')</p>';
					}
				}
				echo '  </div>
                  </div>

          <div class="divVisu">
                    <div class="divLabelVisu">
                        <span class="labelVisu2">FORNECEDOR (Nº Pedido)</span>
                    </div>
                    <div class="pVisu">';
				foreach ($lista_pedido as $key => $pedido) {
					if ($pedido->num_pedido != 0) {
						echo '<p class="dont-break-out"><b>' . $pedido->fornecedor . '</b> (' . $this->encurtaString($pedido->num_pedido) . ')</p>';
					}
				}
				echo '	</div>
              		</div>
				';
			}
			echo '
						<div class="divVisu">
              			  <div class="divLabelVisu">
            			    <span class="labelVisu2">SOLICITANTE</span>
            			  </div>
            			  <div class="pVisu">
            			    <p class="dont-break-out">' . $modal->nome . '</p>
            			  </div>
            			</div>

            			<div class="divVisu">
            			  <div class="divLabelVisu">
            			    <span class="labelVisu2">DEPARTAMENTO</span>
            			  </div>
            			  <div class="pVisu">
            			    <p class="dont-break-out">' . $modal->departamento . '</p>
            			  </div>
            			</div>

            			<div class="divVisu">
            			  <div class="divLabelVisu">
            			    <span class="labelVisu2">DATA SUGESTÃO</span>
            			  </div>
            			  <div class="pVisu">
            			    <p class="dont-break-out">' . date("d/m/Y", strtotime($modal->dtsugestao)) . '</p>
            			  </div>
            			</div>

            			<div class="divVisu">
            			  <div class="divLabelVisu">
            			    <span class="labelVisu2">OBSERVAÇÃO</span>
            			  </div>
            			  <div class="pVisu">
            			    <p class="dont-break-out">' . $modal->obs . '</p>
            			  </div>
            			</div>

            			<div class="divVisu">
            			  <div class="divLabelVisu">
            			    <span class="labelVisu2">STATUS</span>
            			  </div>
            			  <div class="pVisu">
            			    <p class="dont-break-out">' . $modal->descricao . '</p>
            			  </div>
            			</div>';
			if (isset($modal->alterador)) {
				echo '
						<div class="divVisu">
              			  <div class="divLabelVisu">
              			    <span class="labelVisu2">ALTERADO POR</span>
              			  </div>
              			  <div class="pVisu">
              			    <p class="dont-break-out">' . $modal->alterador . '</p>
              			  </div>
              			</div>

              			<div class="divVisu">
              			  <div class="divLabelVisu">
              			    <span class="labelVisu2">DATA ALTERAÇÃO</span>
              			  </div>
              			  <div class="pVisu">
              			    <p class="dont-break-out">' . date("d/m/Y H:i:s", strtotime($modal->dt_mov)) . '</p>
              			  </div>
              			</div>';

			}
			echo "</div>
    	        		</div>
    	  			</div>
    	  			<div class='modal-footer'>
    	    			<button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>
    	  			</div>
    			</div>
  			</div>
		</div>";
		}

	}
//

//PESQUISA RC POR NUMERO
	public function pesquisarRC() {
		if (!isset($_SESSION)) {session_start();}
		$codreq = $_POST['rc'];
		$user = $_SESSION['nome'];
		$rc = json_decode($this->procurarRC($codreq));
		if ($rc == NULL) {
			echo '<div class="text-danger bg-dark"><h3>Número de RC não encontrado.</h3></div>';
		} else {

			//verificando se possui acesso
			if (($rc->nome != $_SESSION['nome']) and ($_SESSION['nivel'] != 'gerente')) {
				echo '<div class="text-danger bg-dark"><h3>Você não tem permissão para visualizar essa RC.</h3></div>';
			} else {
				$itens = json_decode($this->procurarRCItens($codreq));
				$files = json_decode($this->procurarRCFiles($codreq));
				echo '
		<table class="table table-sm table-bordered table-responsive-sm table-hover ">
      		<thead class="thead-dark">
       	 		<tr>
          			<th scope="col" class="align-middle text-center dont-break-out">RC</th>
          			<th scope="col" class="align-middle text-center dont-break-out">DATA ALTERAÇÃO</th>
          			<th scope="col" class="align-middle text-center dont-break">ÚLTIMA ALTERAÇÃO</th>
          			<th scope="col" class="align-middle text-center dont-break">CATEGORIA</th>
          			<th scope="col" class="align-middle text-center dont-break">STATUS</th>
          			<th scope="col" class="align-middle text-center dont-break">CONFORMIDADE</th>
          			<th scope="col" class="align-middle text-center dont-break"><i class="fa fa-eye" aria-hidden="true"></i></th>
        		</tr>
      		</thead>
      		<tbody>
        		<tr>
			';
				$alterador = ($rc->alterador == NULL) ? $rc->nome : $rc->alterador;
				echo '
                <td class="align-middle text-center dont-break">' . $rc->codreq . '</td>
                <td class="align-middle text-center dont-break">' . date("d/m/Y H:i:s", strtotime($rc->dt_mov)) . '</td>
                <td class="align-middle text-center dont-break">' . $alterador . '</td>
                <td class="align-middle text-cenetr dont-break-out">' . $rc->categoria . '</td>';

				switch ($rc->status) {
				case 1:
					echo "<td bgcolor='#6495ED' class='align-middle' style='font-weight:bold; color:white; text-align: center'>" . $rc->descricao . "</td>";
					break;
				case 2:
					echo "<td bgcolor='#0000CD' class='align-middle' style='font-weight:bold; color:white; text-align: center'>" . $rc->descricao . "</td>";
					break;
				case 3:
					echo "<td bgcolor='#006400' class='align-middle' style='font-weight:bold; color:white; text-align: center'>" . $rc->descricao . "</td>";
					break;
				case 4:
					echo "<td bgcolor='#00EE00' class='align-middle' style='color:white; text-align: center'>" . $rc->descricao . "</td>";
					break;
				case 5:
					echo "<td bgcolor='#FFFF00' class='align-middle' style='font-weight:bold; color:black; text-align: center'>" . $rc->descricao . "</td>";
					break;
				case 6:
					echo "<td bgcolor='#CDC9C9' class='align-middle' style='font-weight:bold; color:white; text-align: center'>" . $rc->descricao . "</td>";
					break;
				case 7:
					echo "<td bgcolor='#FF0000' class='align-middle' style='font-weight:bold; color:white; text-align: center'>" . $rc->descricao . "</td>";
					break;
				case 8:
					echo "<td bgcolor='#8B2323' class='align-middle' style='font-weight:bold; color:white; text-align: center'>" . $rc->descricao . "</td>";
					break;

				default:
					echo "<td bgcolor='#8B2323' class='align-middle' style='font-weight:bold; color:white; text-align: center'>" . $rc->descricao . "</td>";
					break;
				}

				echo '
            <td class="align-middle text-center dont-break-out">' . $rc->conformidade . '</td>
            <td class="align-middle text-center dont-break-out">
              <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-tt="tooltip" data-placement="right" title="VISUALIZAR" data-target="#modalvisu' . $rc->codreq . '">
                  <i class="fa fa-eye" aria-hidden="true"></i>
              </button>
            </td>
            </tr>';
				echo '
			</tbody>
    	</table>
        ';
				//-------------------- modal visualizar
				echo "
		<div class='modal fade' id='modalvisu" . $rc->codreq . "' tabindex='-1' role='dialog' aria-labelledby='exampleModalLongTitle' aria-hidden= 'true'>
			<div class='modal-dialog modal-lg modal-dialog-centered' role='document'>
    			<div class='modal-content'>
    				<div class='modal-header'>
    					<h3 class='modal-title' id='exampleModalLongTitle'>
    						RC " . $rc->codreq . "
    					</h3>
    					<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
          					<span aria-hidden='true'>&times;</span>
        				</button>
      				</div>
      				<div class='modal-body'>
      					<div class='row'>
          					<div class='col-lg-9'>
            					<div class='container'>
              						<h3>Anexos</h3>
              						<div class='row'>";
				foreach ($files as $key => $file) {
					$img = $this->img_file($file->extension);
					if ($file->codreq == $rc->codreq) {
						if ($file->filename != '') {
							echo "<div class='col-md-3'>
                      						<a href='anexosPDR/" . $file->codreq . "_" . $file->id_file . "." . $file->extension . "' target='_blank' class='link_arquivo'>
                      							<div class='divAnexos'>
                            						<p class='dont-break-out'>$img $file->filename</p>
                          						</div>
                        					</a>
										</div>";
						} else {
							echo "<div class='container'><p>Sem anexos</p></div>";
						}
					}
				} //fechamento do foreach
				echo '</div>
            					</div>
            			<table class="table table-sm table-bordered table-responsive-sm table-hover">
              				<thead class="thead-dark">
              				  <tr>
              				    <th scope="col" class="align-middle text-center dont-break-out">ITEM</th>
              				    <th scope="col" class="align-middle text-center dont-break">ESPECIFICAÇÃO</th>
              				    <th scope="col" class="align-middle text-center dont-break">PREÇO UNIT.</th>
              				    <th scope="col" class="align-middle text-center dont-break">QTD</th>
              				    <th scope="col" class="align-middle text-center dont-break">TOTAL</th>
              				  </tr>
              				</thead>
              				<tbody>
                				<tr>';
				foreach ($itens as $key => $item) {
					if ($item->codreq == $rc->codreq) {
						echo '<td class="align-middle text-center dont-break">
                      					' . $item->descrprod . '
                      					</td>
                      					<td class="align-middle text-center dont-break-out">
                      					' . $item->obsitem . '
                      					</td>
                      					<td class="align-middle text-center dont-break">
                      					' . $item->precounit . '
                      					</td>
                      					<td class="align-middle text-center dont-break">
                      					' . $item->qtde . '
                      					</td>
                      					<td class="align-middle text-center dont-break">
                      					' . $item->total . '
                      					</td>
                      				</tr>';
					}
				} //fechamento foreach
				echo '
							</tbody>
            			</table>
            		</div>
					<div class="col-lg-3">';
				if ($rc->status == 7 or $rc->status == 8) {
					echo '
						<div class="divVisu">
                			<div class="divLabelVisu">
                  				<span class="labelVisu2">MOTIVO DA REPROVAÇÃO</span>
                			</div>
                			<div class="pVisu">
                			  <p class="dont-break-out">' . $rc->motivo_rep . '</p>
                			</div>
              			</div>';
				}

				if ($rc->status >= 4) {
					$rc_pedido = Container::getModel('itensRC');
					$lista_pedido = $rc_pedido->buscaPedidoPesquisar($rc->codreq);
					echo '
					<div class="divVisu">
              		  <div class="divLabelVisu">
            		    <span class="labelVisu2">Nº RC no OMIE</span>
            		  </div>
            		  <div class="pVisu">
            		    <p class="dont-break-out">' . $rc->cod_omie . '</p>
            		  </div>
            		</div>
					<div class="divVisu">
                		<div class="divLabelVisu">
                  			<span class="labelVisu2">Nº PEDIDO DE COMPRA</span>
                		</div>
                		<div class="pVisu">';
					foreach ($lista_pedido as $key => $pedido) {
						if ($pedido->num_pedido != 0) {
							echo '<p class="dont-break-out"><b>' . $pedido->num_pedido . '</b> (' . $this->encurtaString($pedido->descrprod) . ')</p>';
						}
					}
					echo '  </div>
                  </div>

          <div class="divVisu">
                    <div class="divLabelVisu">
                        <span class="labelVisu2">FORNECEDOR (Nº Pedido)</span>
                    </div>
                    <div class="pVisu">';
					foreach ($lista_pedido as $key => $pedido) {
						if ($pedido->num_pedido != 0) {
							echo '<p class="dont-break-out"><b>' . $pedido->fornecedor . '</b> (' . $this->encurtaString($pedido->num_pedido) . ')</p>';
						}
					}
					echo '	</div>
              		</div>
				';
				}
				echo '
						<div class="divVisu">
              			  <div class="divLabelVisu">
            			    <span class="labelVisu2">SOLICITANTE</span>
            			  </div>
            			  <div class="pVisu">
            			    <p class="dont-break-out">' . $rc->nome . '</p>
            			  </div>
            			</div>

            			<div class="divVisu">
            			  <div class="divLabelVisu">
            			    <span class="labelVisu2">DEPARTAMENTO</span>
            			  </div>
            			  <div class="pVisu">
            			    <p class="dont-break-out">' . $rc->departamento . '</p>
            			  </div>
            			</div>

            			<div class="divVisu">
            			  <div class="divLabelVisu">
            			    <span class="labelVisu2">DATA SUGESTÃO</span>
            			  </div>
            			  <div class="pVisu">
            			    <p class="dont-break-out">' . date("d/m/Y", strtotime($rc->dtsugestao)) . '</p>
            			  </div>
            			</div>

            			<div class="divVisu">
            			  <div class="divLabelVisu">
            			    <span class="labelVisu2">OBSERVAÇÃO</span>
            			  </div>
            			  <div class="pVisu">
            			    <p class="dont-break-out">' . $rc->obs . '</p>
            			  </div>
            			</div>

            			<div class="divVisu">
            			  <div class="divLabelVisu">
            			    <span class="labelVisu2">STATUS</span>
            			  </div>
            			  <div class="pVisu">
            			    <p class="dont-break-out">' . $rc->descricao . '</p>
            			  </div>
            			</div>';
				if (isset($rc->alterador)) {
					echo '
						<div class="divVisu">
              			  <div class="divLabelVisu">
              			    <span class="labelVisu2">ALTERADO POR</span>
              			  </div>
              			  <div class="pVisu">
              			    <p class="dont-break-out">' . $rc->alterador . '</p>
              			  </div>
              			</div>

              			<div class="divVisu">
              			  <div class="divLabelVisu">
              			    <span class="labelVisu2">DATA ALTERAÇÃO</span>
              			  </div>
              			  <div class="pVisu">
              			    <p class="dont-break-out">' . date("d/m/Y H:i:s", strtotime($rc->dt_mov)) . '</p>
              			  </div>
              			</div>';

				}
				echo "</div>
    	        		</div>
    	  			</div>
    	  			<div class='modal-footer'>
    	    			<button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>
    	  			</div>
    			</div>
  			</div>
		</div>";
			}

		}} //fim dos ifs de verificação
	//
	//

//Classifica provedores externos quando RCs estão recebidas e conformes
	public function classifica() {

		if (isset($_POST['numfornec'])) {

			for ($i = 1; $i <= $_POST['numfornec']; $i++) {

				$cp = Container::getModel('ClassifProvedores');
				$cp->__set('provedor', $_POST['fornec_' . $i . '']);
				$cp->__set('prazo', $_POST['prazo_' . $i . '']);
				$cp->__set('preco', $_POST['preco_' . $i . '']);
				$cp->__set('qualidade', $_POST['qualidade_' . $i . '']);
				$cp->__set('obs', $_POST['obs_' . $i . '']);
				$cp->__set('codreq', $_POST['codreq_' . $i . '']);

				$result_cp = $cp->addClassify();

				if ($result_cp != "ok") {
					if ($i == $_POST['numfornec']) {
						echo $result_cp . '<a href="/pdr"><br><br><b>Voltar</b></a>';
					}
				} else {
					if ($i == $_POST['numfornec']) {
						echo "
        					<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http:/pdr'>
        					<script type=\"text/javascript\">
        			   			alert(\"Classificação de provedor cadastrada com sucesso!\");
        					</script>
        			  	";
					}
				}
			}

		} else {
			echo "
        		<script type=\"text/javascript\">
        		   alert(\"Erro ao encontrar provedores externos, contate o suporte!" . $result_cp . "\");
					history.go(-2);
        		</script>
        		  ";
		}
	}
//

//Consulta RCs aprovadas com erro
	public function consultaRCaprovada() {
		$rc = Container::getModel('RC');
		$num = $rc->rc_nao_sincronizada();
		return $num;
	}
//

}

?>