<?php

namespace App\Models;

use App\Models\API\CategoriasCadastroJsonClient;
use App\Models\API\categoria_list_request;
use App\Models\API\ClientesCadastroJsonClient;
use App\Models\API\clientes_cadastro_chave;
use App\Models\API\com_pedido_pesquisar_request;
use App\Models\API\PedidoCompraJsonClient;
use App\Models\API\ProdutosCadastroJsonClient;
use App\Models\API\produto_servico_list_request;
use App\Models\API\ProjetosCadastroJsonClient;
use App\Models\API\projListarRequest;
use App\Models\API\rcListarRequest;
use App\Models\API\requisicaoCadastro;
use App\Models\API\RequisicaoCompraJsonClient;
use INTRA\Model\Container;

class Api {

	public static function ConsultaFornec($codigo_fornec) {

		$fornec = new ClientesCadastroJsonClient();

		$chave = new clientes_cadastro_chave();
		$chave->codigo_cliente_omie = $codigo_fornec;
		$result = $fornec->ConsultarCliente($chave)->nome_fantasia;

		return $result;
	}

	public static function PesquisarReq($pagina) {

		$rc = new RequisicaoCompraJsonClient();

		$request = new rcListarRequest();
		$request->pagina = $pagina;
		$request->registros_por_pagina = 50;
		$request->apenas_importado_api = 'N';

		$result = $rc->PesquisarReq($request);

		return $result;

	}

	public static function PesquisarPedCompra($pagina) {

		$pedido = new PedidoCompraJsonClient();

		$request = new com_pedido_pesquisar_request();
		$request->nPagina = $pagina;
		$request->nRegsPorPagina = 50;
		$request->lApenasImportadoApi = 'N';
		//$request->lExibirPedidosRecebidos = 'S';
		//$request->lExibirPedidosPendentes = 'N';

		$result = $pedido->PesquisarPedCompra($request);

		return $result;

	}

	public static function PesquisarPedCompraFinalizados($pagina) {

		$pedido = new PedidoCompraJsonClient();

		$request = new com_pedido_pesquisar_request();
		$request->nPagina = $pagina;
		$request->nRegsPorPagina = 50;
		$request->lApenasImportadoApi = 'N';
		//$request->lExibirPedidosRecebidos = 'S';
		$request->lExibirPedidosPendentes = 'N';

		$result = $pedido->PesquisarPedCompra($request);

		return $result;

	}

	public static function listaProdutos($pagina) {

		$produtos = new ProdutosCadastroJsonClient();

		$request = new produto_servico_list_request();
		$request->pagina = $pagina;
		$request->registros_por_pagina = 500;
		$request->apenas_importado_api = "N";
		$request->filtrar_apenas_omiepdv = "N";

		$lista = $produtos->ListarProdutos($request);

		return $lista;
	}

	public static function consultaRC() {

		$rc = new RequisicaoCompraJsonClient();

		$request = new rcListarRequest();

		$request->pagina = 1;
		$request->registros_por_pagina = 50;
		$request->apenas_importado_api = 'N';
		$result = $rc->PesquisarReq($request)->requisicaoCadastro;

		return json_encode($result);
	}

	public static function listaCategorias() {

		$categorias = new CategoriasCadastroJsonClient();

		$request = new categoria_list_request();
		$request->pagina = 1;
		$request->registros_por_pagina = 500;

		$lista = $categorias->ListarCategorias($request)->categoria_cadastro;

		return $lista;
	}

	public static function listaProjetos() {

		$projetos = new ProjetosCadastroJsonClient();

		$request = new projListarRequest();
		$request->pagina = 1;
		$request->registros_por_pagina = 500;
		$request->apenas_importado_api = 'N';

		$lista = $projetos->ListarProjetos($request)->cadastro;

		return $lista;
	}

	public static function criaRC($codreq) {

		$rc_mysql = Container::getModel('RC');
		$itensRc_mysql = Container::getModel('itensRC');
		$produto = Container::getModel('Produto');

		$lista = $rc_mysql->buscaPorRC($codreq);

		$lista_itens = $itensRc_mysql->buscaPorRC($codreq);

		$countLista = count($lista_itens);

		$rc = new RequisicaoCompraJsonClient();

		$reqCad = new requisicaoCadastro();

		for ($i = 0; $i < $countLista; $i++) {

			$codProduto = $produto->buscaCodigoOmie($lista_itens[$i]['codprod']);
			$codProd = $codProduto['codigo_produto'];
			$obsItem = $lista_itens[$i]['obsitem'];
			$precounit = $lista_itens[$i]['precounit'];
			$qtde = $lista_itens[$i]['qtde'];
			$x = $i + 1;

			$produtos[$i] = array("codItem" => "$x", "codProd" => "$codProd", "obsItem" => "$obsItem", "precoUnit" => "$precounit", "qtde" => "$qtde");
		}

		//echo "<pre>" . print_r($produtos, true);

		//$reqCad->codReqCompra = 0; //Código da Requisição de Compras

		$obs = $lista['obs'];
		$cod_cat = $lista['cod_cat'];
		$cod_dpto = $lista['cod_dpto'];
		$dt_sugestao = date('d/m/Y', strtotime($lista['dtsugestao']));
		$dt_criacao = date('d/m/Y H:i:s', strtotime($lista['dt_mov']));
		$solicitante = $lista['nome'];

		$reqCad->codIntReqCompra = $codreq; //código de integração da requisição de Compra.

		$reqCad->codCateg = $cod_cat; //Código da Categoria

		$reqCad->codProj = $cod_dpto; //código do projeto

		$reqCad->dtSugestao = $dt_sugestao; //sugestão da entrega

		//$reqCad->obsReqCompra = ;	//elas serão impressas na carta de cotação para o fornecedor

		$reqCad->obsIntReqCompra = "RC " . $codreq . "|" . $solicitante . "|" . $dt_criacao . "|" . $obs;
		//elas serão exibidas apenas aqui

		$reqCad->ItensReqCompra = $produtos; //Itens da Requisição de Compras [Array]

		//echo "<pre>" . print_r($reqCad, true);exit();

		$result = $rc->IncluirReq($reqCad);

	}

}

?>