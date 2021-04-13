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

		if ($listaPed->nPagina == $listaPed->nTotalPaginas) {
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
//

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

		if ($listaPed->nPagina == $listaPed->nTotalPaginas) {
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
//



######################## Sincroniza omie com intranet (PEDIDOS) ########################
	public function sincPedido() {

		$tempo_inicio = microtime( true );

		if (!isset($_SESSION)) {session_start();}

		//[cEtapa] => 20 ----> REQUISIÇÃO
		//[cEtapa] => 10 ----> PEDIDO

		$log = '';
		$log = "<h3>Sincronização dos pedidos realizada, confira o resultado nos logs abaixo:</h3><br>";

		//buscando pedidos no omie
			$i = 1;
			$page = 0;
			while ($i < 2) {
				$page = $page + 1;
				$retorno = $this->listarPedidos($page);
				$continue = $retorno['continue'];
				if ($page < 2) {
					$pedidos = $retorno['pedidos'];
				} else {
					$pedidos = array_merge($pedidos, $retorno['pedidos']);
				}
				if ($continue == "N") {
					$i++;
				}
			}
		//

		#echo "<pre>" . print_r($pedidos, true);exit();

		//buscando pedidos finalizados no omie
			$i = 1;
			$page = 0;
			while ($i < 2) { // não mexer, ligado ao continue
				$page = $page + 1;
				$retorno = $this->listarPedidosFinalizados($page);
				$continue = $retorno['continue'];
				if ($page < 2) {
					$pedidosFinalizados = $retorno['pedidos'];
				} else {
					$pedidosFinalizados = array_merge($pedidosFinalizados, $retorno['pedidos']);
				}
				if ($continue == "N") {
					$i++;
				}
			}
		//

		#echo "<pre>Pedidos finalizados::<br>" . print_r($pedidosFinalizados, true);exit();

		$todos_pedidos = array_merge($pedidos, $pedidosFinalizados);

		#echo "<pre>todos_pedidos::<br>" . print_r($todos_pedidos, true);exit();


		//criando array dos itens da rc (PDR)
			$itensRC = Container::getModel('itensRC');
			$rc = Container::getModel('RC');
			$arrayItensPDR = $itensRC->buscaSincAllItens();
		//

		#echo "<pre>" . print_r($arrayItensPDR, true) . "</pre>";exit();

		//criando array das rc (PDR)
	
			$rc = Container::getModel('RC');
			$arrayPDR = $rc->buscaSincAllRC();
		//

		#echo "<pre>" . print_r($arrayPDR, true);//exit();

		//Inserindo numero do pedido do omie na intranet e atualizando status p/ 4 (Em processo de compra)
			$log .= "Procurando por requisições com os pedidos não finalizados... <br>";
	
			$x = 1;
			foreach ($arrayPDR as $key => $pdr) {
				foreach ($pedidos as $key => $omie) {
					if ($pdr->status == 3) {
						if ($pdr->codreq == substr($omie['obs'], 2, 7)) {
							$rc->__set('numPedido', $omie['numPedido']);
							$rc->__set('codreq', $pdr->codreq);
							$log .= $x . ' - ' . $rc->atualiza_cod_omie($pdr->codreq) . '<br>';
							if ($omie['statusPedido'] == 10){
								$log .= $rc->alteraStatus($pdr->codreq, 4) . "<br>";
							}
							$x++;
						}
					}
				}
			}
			if ($x < 2) {
				$log .= "Nenhuma Requisição para cadastrar o número do pedido. <br>";
			}
			
		//

		//*O script abaixo irá comparar apenas as rcs do PDR que estão como aprovadas junto com os pedidos que não estão pendentes, atualizar os campos do PDR e criar o log*/
			$log .= "Procurando por requisições com os pedidos finalizados... <br>";

			foreach ($arrayItensPDR as $key => $pdr) {
				foreach ($pedidosFinalizados as $key => $finalizados) {
					if ($finalizados['numPedido'] == $pdr->num_pedido) {

						//Buscando por cancelados
							$obs_cancelado = substr($finalizados['obs'], -9);
							//echo "obs_cancelado no pedido ".$pdr->num_pedido." é ".$obs_cancelado."<br>";
							if ($obs_cancelado == "CANCELADO") {
								//$log .= "Finalizou a ".$pdr->codreq." por causa disso: ".$finalizados['obs'].";<br>";
								$log .= $rc->alteraStatus($pdr->codreq, 8) . "<br>";
							}
						//

						//Avançando o status p/ 5 (INSPEÇÃO)
							if ($pdr->status == 4) {
								//$dt_create = new DateTime($pdr->dt_create);
								//$today = new DateTime(date('Y/m/d H:i:s'));
								//$interval = $dt_create->diff($today);
								//$dias_intervalo = $interval->days;
								//if ($pdr->codprod == 'PRD00074' or $pdr->codprod == 'PRD00081' 
								//	or $pdr->codprod == 'PRD00089'	or $pdr->codprod == 'PRD00073') {
								//	$days = 5;
								//} else {
								//	$days = 20;
								//}
								//if ($dias_intervalo > $days) {
								//	//echo "interval days = ".$interval->days." NO PEDIDO ".$finalizados['numPedido']."<br>";
								//	if ($pdr->codprod == 'PRD00074' or $pdr->codprod == 'PRD00081' 
								//		or $pdr->codprod == 'PRD00089'	or $pdr->codprod == 'PRD00073') {
								//		$log .= $rc->alteraStatus($pdr->codreq, 6) . "<br>";
								//	}else {
								//		$log .= $rc->alteraStatus($pdr->codreq, 5) . "<br>";
								//	}
								if ($pdr->codprod == 'PRD00074' or $pdr->codprod == 'PRD00081' 
									or $pdr->codprod == 'PRD00089'	or $pdr->codprod == 'PRD00073') {
									$log .= $rc->alteraStatus($pdr->codreq, 6) . "<br>";
								}else {
									$log .= $rc->alteraStatus($pdr->codreq, 5) . "<br>";
								}							
							}
						//
					}
				}
			}
		//

		//ATUALIZAÇÃO Da tabela de produtos

		$log .= "Atualizando os itens conforme Omie... <br>";
		//echo $log;

		$x = 1;
		$y = 1;

		//echo "<pre>" . print_r($todos_pedidos, true);exit();

		foreach ($todos_pedidos as $key => $rc_omie) {
			$pedidoAnterior = 1;
			$reqAnterior = 1;

			foreach ($arrayItensPDR as $key => $rc_pdr) {
				if ($y == 1) {
					$codreqAnterior = $rc_pdr->codreq - 1;
				}
				$pedido = $itensRC->verificaNumPedido($rc_pdr->codreq, $rc_pdr->coditem)['num_pedido'];
				if ($rc_pdr->codreq == substr($rc_omie['obs'], 2, 7)){
					if ($rc_pdr->num_pedido == 0) {
						$fornecedor = $this->fornecedor($rc_omie['nCodFor']);
						$log .= $itensRC->sincronizaRC($rc_pdr->codreq,
							$rc_omie['numPedido'], $fornecedor, $rc_pdr->coditem) . "<br>";
						$codreqAnterior = $rc_pdr->codreq;
					}
					//Para mostrar se já está sincronizada
					else {
						$log .= " Requisição " . $rc_pdr->codreq . " já sincronizada (Pedido " . $rc_pdr->num_pedido . ", " . $rc_pdr->descrprod . ", R$ " . $rc_pdr->total . ")<br>";
					}
				}
				elseif ($rc_pdr->codreq == substr($rc_omie['obs'], 2, 7) and $codreqAnterior != $rc_pdr->codreq) {

					//ATUALIZANDO ITENS A PARTIR DAQUI
					if ($pedido == $rc_omie['numPedido']) {
						$i = 0;
						//se for a mesma rc, com outro pedido, incrementa o coditem da rc
						if ($pedido != $pedidoAnterior and $rc_pdr->codreq == $reqAnterior) {
							if ($rc_pdr->coditem != 1) {
								$coditem = 1;
							}
						} else {
							$coditem = $rc_pdr->coditem;
						}
						while ($i < $rc_omie['count']) {
							if ($i == ($coditem - 1) and $rc_omie['itens'][$i]->cProduto == $rc_pdr->codprod) {
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
			$y++;
		}
		if ($y == 1) {
			$log .= "Nada para atualizar! <br> O Status das Requisições só será atualizado para Inspeção quando seus Pedidos de Compras não estiverem mais pendentes no Omie. <br>";
		}

		$tempo_fim = microtime( true );
		$tempo_execucao = ($tempo_fim - $tempo_inicio);
		$logCompleto = '<b>Tempo de Execução:</b> '.$tempo_execucao.' Segs <br><br>';
		$logCompleto .= $log;
		echo $logCompleto; //para aparecer na tela
		return $logCompleto; //para aparecer no arquivo de log
	}
//

//sincronizar PRODUTOS
	public function sincronizar() {

		$tempo_inicio = microtime( true );

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
		$tempo_fim = microtime( true );
		$tempo_execucao = ($tempo_fim - $tempo_inicio);
		$logCompleto = '<b>Tempo de Execução:</b> '.$tempo_execucao.' Segs <br><br>';
		$logCompleto .= $log;
		echo $logCompleto; //para aparecer na tela
		return $logCompleto; //para aparecer no arquivo de log
	}
//
}

?>