<?php

namespace App\Controllers;

use App\Models\Api;
use DateTime;
use INTRA\Controller\Action;
use INTRA\Model\Container;

class ApiController extends Action {

	//busca nome do fornecedor
	public function fornecedor($codfornec) {
		if ($codfornec == 0) {
			$fornecedor = "N.A";
		} else {
			$fornecedor = Api::consultaFornec($codfornec);
		}
		return $fornecedor;
	}
	//cria lista com todas requisicoes
	public function listarRequisicoes($r) {
		$listaReq = Api::PesquisarReq($r);
		$requisicoes = array();
		$x = 0;

		foreach ($listaReq->requisicaoCadastro as $key => $rc) {
			$requisicoes[$x] = array(
				"codReqCompra" => $rc->codReqCompra,
				"numReq" => substr($rc->obsIntReqCompra, 2, 7), //numero no portal
				"count" => count($rc->ItensReqCompra),
			);
			$x++;
		}

		if ($listaReq->registros == 50) {
			$continue = "S";
		} else {
			$continue = "N";
		}

		$retorno = array(
			"continue" => $continue,
			"requisicoes" => $requisicoes,
			"total_de_paginas" => $listaReq->total_de_paginas,
		);

		return $retorno;
	}
	//cria lista com todos pedidos
	public function listarPedidos($p) {
		$listaPed = Api::PesquisarPedCompra($p);
		$pedidos = array();
		$x = 0;

		foreach ($listaPed->pedidos_pesquisa as $key => $ped) {
			$pedidos[$x] = array(
				"numPedido" => $ped->cabecalho_consulta->cNumero,
				"statusPedido" => $ped->cabecalho_consulta->cEtapa,
				"numeroRC" => $ped->cabecalho_consulta->cNumero,
				"count" => count($ped->produtos_consulta),
				"nCodPed" => $ped->cabecalho_consulta->nCodPed,
				"nCodFor" => $ped->cabecalho_consulta->nCodFor,
				"obs" => $ped->cabecalho_consulta->cObsInt,
				"itens" => $ped->produtos_consulta,
			);
			$x++;
		}

		if ($listaPed->nPagina > 3) {
			$continue = "N";
		} else {
			$continue = "S";
		}

		$retorno = array(
			"continue" => $continue,
			"pedidos" => $pedidos,
			"total_de_paginas" => $listaPed->nTotalPaginas,
		);
		return $retorno;
	}

	//cria lista com todos pedidos finalizados
	public function listarPedidosFinalizados($p) {
		$listaPed = Api::PesquisarPedCompraFinalizados($p);
		$pedidos = array();
		$x = 0;

		foreach ($listaPed->pedidos_pesquisa as $key => $ped) {
			$pedidos[$x] = array(
				"numPedido" => $ped->cabecalho_consulta->cNumero,
				"statusPedido" => $ped->cabecalho_consulta->cEtapa,
				"numeroRC" => $ped->cabecalho_consulta->cNumero,
				"count" => count($ped->produtos_consulta),
				"nCodPed" => $ped->cabecalho_consulta->nCodPed,
				"nCodFor" => $ped->cabecalho_consulta->nCodFor,
				"obs" => $ped->cabecalho_consulta->cObsInt,
				"itens" => $ped->produtos_consulta,
			);
			$x++;
		}

		if ($listaPed->nPagina > 3) {
			$continue = "N";
		} else {
			$continue = "S";
		}

		$retorno = array(
			"continue" => $continue,
			"pedidos" => $pedidos,
			"total_de_paginas" => $listaPed->nTotalPaginas,
		);
		return $retorno;
	}

//sincroniza omie com intranet (REQUISIÇÕES)
	public function sincRequisicao() {
		if (!isset($_SESSION)) {session_start();}
		$log = '';
		$log = "<h3>Sincronização das Requisições de Compras realizada, confira o resultado nos logs abaixo:</h3><br>";

		//buscando requisicoes no omie
		$page = 0;
		$consulta_paginas = $this->listarRequisicoes($page);
		$total_paginas = $consulta_paginas['total_de_paginas'];
		if ($total_paginas > 4) {			//Att 08/12/2020
				$page = $total_paginas - 4;	//se tiver mais que 3 paginas, pegará  total e começará 5 abaixo
			}
		//echo "<pre>" . print_r($page, true);exit();
		$i = 1;
		$p = $page;
		while ($i < 2) {
			$p = $p + 1;			
			$retorno = $this->listarRequisicoes($p);
			$continue = $retorno['continue'];
			if ($p == 1 or $p == $page+1) {
				$requisicoes = $retorno['requisicoes'];
			} else {
				$requisicoes = array_merge($requisicoes, $retorno['requisicoes']);
			}
			if ($continue == "N") {
				$i++;
			}
		}
		//
		//echo "<pre>" . print_r($requisicoes, true);

		//buscando pedidos no omie
		$i = 1;
		$p = 0;
		while ($i < 2) {
			$p = $p + 1;
			$retorno = $this->listarPedidos($p);
			$continue = $retorno['continue'];
			if ($p < 2) {
				$pedidos = $retorno['pedidos'];
			} else {
				$pedidos = array_merge($pedidos, $retorno['pedidos']);
			}
			if ($continue == "N") {
				$i++;
			}
		}
		//
		//echo "<pre>" . print_r($pedidos, true);exit();

		//criando array de pedidos (omie)
		$x = 0;
		$arrayOmie = array();
		foreach ($requisicoes as $key => $r) {
			foreach ($pedidos as $key => $p) {
				if ($r['codReqCompra'] == $p['nCodPed']) {
					$arrayOmie[$x] = array(
						"numReq" => $r['numReq'],
						"numPedido" => $p['numPedido'],
						"statusPedido" => $p['statusPedido'],
						"countP" => $p['count'],
						"countR" => $r['count'],
						"fornec" => $this->fornecedor($p['nCodFor']),
						"itens" => $p['itens'],
					);
					$x++;
				}// else {
				//	echo "<pre>".$r['codReqCompra']." é diferente de ".$p['nCodPed']."</pre>";
				//}
			}
		}

		//echo "<pre>" . print_r($arrayOmie, true);

		$rc = Container::getModel('RC');
		$arrayPDR = $rc->buscaSincAllRC();

		//echo "<pre>" . print_r($arrayPDR, true);//exit();

		$x = 1;

		foreach ($arrayPDR as $key => $pdr) {
			foreach ($arrayOmie as $key => $omie) {
				if ($pdr->status == 3 or $pdr->status == 4 or $pdr->status == 5 or $pdr->status == 6) {
					if ($pdr->cod_omie == 0 and $pdr->codreq == $omie['numReq']) {
						$rc->__set('numPedido', $omie['numPedido']);
						$rc->__set('codreq', $pdr->codreq);
						$log .= $x . ' - ' . $rc->atualiza_cod_omie($pdr->codreq) . '<br>';
						$x++;
					}
				}
			}
		}
		if ($x < 2) {
			$log .= "Nenhuma Requisição para atualizar. <br>";
		}

		//echo $log; //para aparecer na tela
		return $log; //para aparecer no arquivo de log

	}
//

//sincroniza omie com intranet (PEDIDOS)
	public function sincPedido() {

		if (!isset($_SESSION)) {session_start();}

		//[cEtapa] => 20 ----> REQUISIÇÃO
		//[cEtapa] => 10 ----> PEDIDO

		$log = '';
		$log = "<h3>Sincronização dos pedidos realizada, confira o resultado nos logs abaixo:</h3><br>";

		$page = 0;
		$consulta_paginas = $this->listarRequisicoes($page);
		$total_paginas = $consulta_paginas['total_de_paginas'];
		if ($total_paginas > 5) {					//Att 08/12/2020
				$page = $total_paginas - 5;		//se tiver mais que 5, pegará  total e começará 5 abaixo
			}
		$p = $page;
		$i = 1;

		//buscando requisicoes no omie
		while ($i < 2) {
			$p = $p + 1;
			$retorno = $this->listarRequisicoes($p);
			$continue = $retorno['continue'];
			if ($p == 1 or $p == $page+1) {
				$requisicoes = $retorno['requisicoes'];
			} else {
				$requisicoes = array_merge($requisicoes, $retorno['requisicoes']);
			}
			if ($continue == "N") {
				$i++;
			}
		}
		//
		//echo "<pre>" . print_r($requisicoes, true);exit();

		//buscando pedidos no omie
		
		$p = 0;
		$i = 1;

		while ($i < 5) {
			$p = $p + 1;
			$retorno = $this->listarPedidos($p);
			$continue = $retorno['continue'];
			if ($p < 2) {
				$pedidos = $retorno['pedidos'];
			} else {
				$pedidos = array_merge($pedidos, $retorno['pedidos']);
			}
			if ($continue == "N") {
				$i++;
			}
		}
		//
		//echo "<pre>" . print_r($pedidos, true);exit();

		//buscando pedidos finalizados no omie
		$i = 1;
		$p = 0;
		while ($i < 5) {
			$p = $p + 1;
			$retorno = $this->listarPedidosFinalizados($p);
			$continue = $retorno['continue'];
			if ($p < 2) {
				$pedidosFinalizados = $retorno['pedidos'];
			} else {
				$pedidosFinalizados = array_merge($pedidosFinalizados, $retorno['pedidos']);
			}
			if ($continue == "N") {
				$i++;
			}
		}
		//

		//echo "<pre>" . print_r($requisicoes, true) . "</pre>";

		//echo "<pre>" . print_r($pedidos, true) . "</pre>";

		//criando array de pedidos (omie)
		$x = 0;
		$array = array();
		foreach ($requisicoes as $key => $r) {
			foreach ($pedidos as $key => $p) {
				if ($r['codReqCompra'] == $p['nCodPed']) {
					$array[$x] = array(
						"numReq" => $r['numReq'],
						"numPedido" => $p['numPedido'],
						"statusPedido" => $p['statusPedido'],
						"countP" => $p['count'],
						"countR" => $r['count'],
						"fornec" => $this->fornecedor($p['nCodFor']),
						"itens" => $p['itens'],
					);
					$x++;
				}
			}
		}
		echo "<pre>" . print_r($array, true) . "</pre>";


		//criando obj de dos itens da rc (PDR)
		$itensRC = Container::getModel('itensRC');
		$rc = Container::getModel('RC');
		$array2 = $itensRC->buscaSincAllItens();
		echo "<pre>" . print_r($array2, true) . "</pre>";

		//echo "<pre>" . print_r($pedidosFinalizados, true);exit();


		/*O script abaixo irá comparar apenas as rcs do PDR que estão como aprovadas junto
		  com os pedidos que não estão pendentes, atualizar os campos do PDR e criar o log*/
		$x = 1;
		$y = 1;

		foreach ($array2 as $key => $pdr) {
			//Atualiza status para Inspeção
			foreach ($pedidosFinalizados as $key => $finalizados) {
				$obs_cancelado = substr($finalizados['obs'], -9);

				if ($finalizados['numPedido'] == $pdr->num_pedido) {
					if ($obs_cancelado == "CANCELADO") {
						$log .= $rc->alteraStatus($pdr->codreq, 8) . "<br>";
					}
					if ($pdr->status == 4) {
						$dt_create = new DateTime($pdr->dt_create);
						$today = new DateTime(date('Y/m/d H:i:s'));
						$interval = $dt_create->diff($today);
						//dias para ir para INSPEÇÃO:
						if ($pdr->codprod == 'PRD00074' or $pdr->codprod == 'PRD00089'
							or $pdr->codprod == 'PRD00073') {
							$days = 3;
						} else {
							$days = 20;
						}
						if ($interval->days > $days) {
							if ($pdr->codprod == 'PRD00074' or $pdr->codprod == 'PRD00081' 
								or $pdr->codprod == 'PRD00089'	or $pdr->codprod == 'PRD00073') {
								$log .= $rc->alteraStatus($pdr->codreq, 6) . "<br>";
							}else {
								$log .= $rc->alteraStatus($pdr->codreq, 5) . "<br>";
							}
							
						}
					}
				}
			}
		}

		foreach ($array as $key => $rc_omie) {
			$pedidoAnterior = 1;
			$reqAnterior = 1;
			foreach ($array2 as $key => $rc_pdr) {
				if ($y == 1) {
					$codreqAnterior = $rc_pdr->codreq - 1;
				}
				$pedido = $itensRC->verificaNumPedido($rc_pdr->codreq, $rc_pdr->coditem)['num_pedido'];
				if ($rc_pdr->codreq == $rc_omie['numReq'] and $codreqAnterior != $rc_pdr->codreq) {
					for ($i = 0; $i < $rc_omie['countP']; $i++) {
						$dt_create = new DateTime($rc_pdr->dt_create);
						$today = new DateTime(date('Y/m/d H:i:s'));
						$interval = $dt_create->diff($today);
						if ($rc_omie['statusPedido'] == 10 and
							$rc_omie['itens'][$i]->cProduto == $rc_pdr->codprod) {
							$y++;
							$log .= $x . " - " . $itensRC->sincronizaRC($rc_pdr->codreq,
								$rc_omie['numPedido'], $rc_omie['fornec'], $rc_pdr->coditem) . "<br>";

							if ($rc_pdr->status == 3) {
								$log .= $rc->alteraStatus($rc_pdr->codreq, 4) . "<br>";
							}
							$codreqAnterior = $rc_pdr->codreq;
						}
					}

					//Para mostrar se já está sincronizada
					//else {
					//	$log .= $x . " - Requisição " . $rc_pdr->codreq . " já sincronizada (Pedido " . $rc_pdr->num_pedido . ", " . $rc_pdr->descrprod . ", R$ " . $rc_pdr->total . ")<br>";
					//}
					$pedido = $itensRC->verificaNumPedido($rc_pdr->codreq, $rc_pdr->coditem)['num_pedido'];

					//ATUALIZANDO ITENS A PARTIR DAQUI
					if ($pedido == $rc_omie['numPedido'] and $interval->days < 60) {
						$i = 0;
						//se for a mesma rc, com outro pedido, incrementa o coditem da rc
						if ($pedido != $pedidoAnterior and $rc_pdr->codreq == $reqAnterior) {
							if ($coditem != 1) {$coditem = 1;}
						} else {
							$coditem = $rc_pdr->coditem;
						}
						while ($i < $rc_omie['countP']) {
							if ($i == ($coditem - 1) /*and $rc_omie['itens'][$i]->cProduto == $rc_pdr->codprod*/) {
								$log .= "Item " . $coditem . ", produto " . $rc_omie['itens'][$i]->cDescricao . " atualizado no Pedido nº " . $pedido . " - " . $itensRC->sincronizaItemRC($rc_pdr->codreq,
									$rc_pdr->coditem, $rc_omie['itens'][$i]->cProduto,
									$rc_omie['itens'][$i]->cDescricao,
									$rc_omie['itens'][$i]->cObs,
									$rc_omie['itens'][$i]->nQtde,
									$rc_omie['itens'][$i]->nValUnit,
									$rc_omie['itens'][$i]->nValTot) . "<br>";
							} /* else {
								$log .= "i = ". $i . ", item omie = ". $rc_omie['itens'][$i]->cProduto . ", item PDR = ". $rc_pdr->codprod. "<br>";
							}*/
							$i++;
						}
					}
				}
				$pedidoAnterior = $pedido;
				$reqAnterior = $rc_pdr->codreq;
			}
			$x++;
		}
		if ($y == 1) {
			$log .= "Todas as Requisições atualizadas! <br><br> O Status das Requisições só será atualizado para Inspeção quando seus Pedidos de Compras não estiverem mais pendentes no Omie.";
		}
		echo $log; //para aparecer na tela
		return $log; //para aparecer no arquivo de log

	}
//

//sincronizar PRODUTOS
	public function sincronizar() {

		if (!isset($_SESSION)) {session_start();}

		$prod = Container::getModel('Produto');

		$i = 1;
		if (isset($_SESSION['id'])) {
			$id = $_SESSION['id'];
		} else {
			$id = 1;
		}

		echo "<h3>Sincronização de produtos realizada, confira o resultado nos logs abaixo:</h3>";

		$listaOmie = Api::listaProdutos(1);
		$count_pages = $listaOmie->total_de_paginas;
		$log = '';

		for ($c = 1; $c <= $count_pages; $c++) {
			$omie[$c] = Api::listaProdutos($c);
			$listao[$c] = $omie[$c]->produto_servico_cadastro;

			//echo "<pre>" . $c . " - " . print_r($listao[$c], true);

			foreach ($listao[$c] as $key => $lista) {

				if ($lista->codigo_familia != 0) {

					$produto = $prod->buscarProduto($lista->codigo_produto);

					if ($produto != NULL) {

						$produto = $prod->buscarProduto($lista->codigo_produto);

						$dt_alt_omie = date("Y-m-d", strtotime($lista->info->dAlt));
						$dt_alt_intranet = date("Y-m-d", strtotime($produto['dt_alter']));

						if ($dt_alt_omie == '1970-01-01') {$dt_alt_omie = date("Y-m-d");}

						if (strtotime($dt_alt_intranet) < strtotime($dt_alt_omie)) {

							//Atualizando produto
							$prod->__set('codigo_produto', $lista->codigo_produto);
							$prod->__set('codigo_omie', $lista->codigo);
							$prod->__set('codigo_integracao', $i);
							$prod->__set('descricao', $lista->descricao);
							$prod->__set('dt_alter', date("Y-m-d"));
							$prod->__set('user_alter', $id);

							$update = $prod->atualizarProduto();

							if (substr($update, 0, 4) != 'Erro') {
								$log .= "<p class='text-primary' style='font-size: 11px;'>Produto " . $lista->codigo . " - " . $lista->descricao . " atualizado com sucesso!</p><br>";
							} else {
								$log .= "<p class='text-primary' style='font-size: 11px;'>" . $update . "</p>";
							}

						} else {
							$log .= "<p style='font-size: 11px;'>Produto " . $produto['codigo_omie'] . " - " . $produto['descricao'] . " já está atualizado!</p><br>";
						}

					} else {

						//Cadastrando Produto
						$prod->__set('codigo_produto', $lista->codigo_produto);
						$prod->__set('codigo_omie', $lista->codigo);
						$prod->__set('codigo_integracao', $i);
						$prod->__set('descricao', $lista->descricao);
						$prod->__set('user_alter', $id);

						$insert = $prod->criarProduto();

						if (substr($insert, 0, 4) != 'Erro') {
							$log .= "<p class='text-success' style='font-size: 11px;'>Produto " . $lista->codigo . " - " . $lista->descricao . " cadastrado com sucesso!</p><br>";

						} else {
							$log .= "<p class='text-success' style='font-size: 11px;'>" . $insert . "</p>";
						}
					}
				}
				$i++;
				$x = $i;
			}

		}
		echo $log; //para aparecer na tela
		return $log; //para aparecer no arquivo de log
	}
//
}

?>