<?php
/**
 * @service RequisicaoCompraJsonClient
 * @author omie
 */
namespace App\Models\API;

define("OMIE_APP_KEY", "887760778905");
define("OMIE_APP_SECRET", "0fddf600b7ff808389137379650e858f");

class RequisicaoCompraJsonClient {
	/**
	 * The WSDL URI
	 *
	 * @var string
	 */
	public static $_WsdlUri = 'http://app.omie.com.br/api/v1/produtos/requisicaocompra/?WSDL';
	/**
	 * The PHP SoapClient object
	 *
	 * @var object
	 */
	public static $_Server = null;
	/**
	 * The endpoint URI
	 *
	 * @var string
	 */
	public static $_EndPoint = 'http://app.omie.com.br/api/v1/produtos/requisicaocompra/';

	/**
	 * Send a SOAP request to the server
	 *
	 * @param string $method The method name
	 * @param array $param The parameters
	 * @return mixed The server response
	 */
	public static function _Call($method, $param) {
		$call = Array(
			"call" => $method,
			"param" => $param,
			"app_key" => OMIE_APP_KEY,
			"app_secret" => OMIE_APP_SECRET,
		);
		return json_decode(file_get_contents(self::$_EndPoint . "?JSON=" . urlencode(json_encode($call))));
	}

	/**
	 * Inclui uma Requisição de Compras
	 *
	 * @param requisicaoCadastro $requisicaoCadastro Cadastro de Requisição de Compras&nbsp;
	 * @return rcStatus Status da Requisição de Compras
	 */
	public function IncluirReq($requisicaoCadastro) {
		return self::_Call('IncluirReq', Array(
			$requisicaoCadastro,
		));
	}

	/**
	 * Alterar Requisição de Compras
	 *
	 * @param requisicaoCadastro $requisicaoCadastro Cadastro de Requisição de Compras&nbsp;
	 * @return rcStatus Status da Requisição de Compras
	 */
	public function AlterarReq($requisicaoCadastro) {
		return self::_Call('AlterarReq', Array(
			$requisicaoCadastro,
		));
	}

	/**
	 * Exclui uma Requisição de Compras
	 *
	 * @param rcChave $rcChave Dados para pesquisa da Requisição de Compras&nbsp;
	 * @return rcStatus Status da Requisição de Compras
	 */
	public function ExcluirReq($rcChave) {
		return self::_Call('ExcluirReq', Array(
			$rcChave,
		));
	}

	/**
	 * Upsert Requisição de Compras
	 *
	 * @param requisicaoCadastro $requisicaoCadastro Cadastro de Requisição de Compras&nbsp;
	 * @return rcStatus Status da Requisição de Compras
	 */
	public function UpsertReq($requisicaoCadastro) {
		return self::_Call('UpsertReq', Array(
			$requisicaoCadastro,
		));
	}

	/**
	 * Consultar Requisição de Compras
	 *
	 * @param rcChave $rcChave Dados para pesquisa da Requisição de Compras&nbsp;
	 * @return requisicaoCadastro Cadastro de Requisição de Compras&nbsp;
	 */
	public function ConsultarReq($rcChave) {
		return self::_Call('ConsultarReq', Array(
			$rcChave,
		));
	}

	/**
	 * Pesquisar Requisição de Compras
	 *
	 * @param rcListarRequest $rcListarRequest Solicitação de Listagem de Requisição de Compras
	 * @return rcListarResponse Resposta da listagem de Requisição de Compras
	 */
	public function PesquisarReq($rcListarRequest) {
		return self::_Call('PesquisarReq', Array(
			$rcListarRequest,
		));
	}
}

/**
 * Itens da Requisição de Compras
 *
 * @pw_element integer $codItem Código do Item da Requisição de Compras
 * @pw_element string $codIntItem Código de integração do Item da requisição de Compra.
 * @pw_element integer $codProd Código do Produto
 * @pw_element string $codIntProd Código do Integração do Produto
 * @pw_element decimal $qtde Quantidade
 * @pw_element decimal $precoUnit Preço unitário sugerido
 * @pw_element string $obsItem Observações do item,
 * @pw_complex ItensReqCompra
 */
class ItensReqCompra {
	/**
	 * Código do Item da Requisição de Compras
	 *
	 * @var integer
	 */
	public $codItem;
	/**
	 * Código de integração do Item da requisição de Compra.
	 *
	 * @var string
	 */
	public $codIntItem;
	/**
	 * Código do Produto
	 *
	 * @var integer
	 */
	public $codProd;
	/**
	 * Código do Integração do Produto
	 *
	 * @var string
	 */
	public $codIntProd;
	/**
	 * Quantidade
	 *
	 * @var decimal
	 */
	public $qtde;
	/**
	 * Preço unitário sugerido
	 *
	 * @var decimal
	 */
	public $precoUnit;
	/**
	 * Observações do item,
	 *
	 * @var string
	 */
	public $obsItem;
}

/**
 * Dados para pesquisa da Requisição de Compras
 *
 * @pw_element integer $codReqCompra Código da Requisição de Compras
 * @pw_element string $codIntReqCompra Código de integração da requisição de Compra.
 * @pw_complex rcChave
 */
class rcChave {
	/**
	 * Código da Requisição de Compras
	 *
	 * @var integer
	 */
	public $codReqCompra;
	/**
	 * Código de integração da requisição de Compra.
	 *
	 * @var string
	 */
	public $codIntReqCompra;
}

/**
 * Solicitação de Listagem de Requisição de Compras
 *
 * @pw_element integer $pagina Número da página que será listada.
 * @pw_element integer $registros_por_pagina Número de registros por página.
 * @pw_element string $apenas_importado_api Exibir apenas os registros gerados pela API.
 * @pw_element string $ordenar_por Ordem de exibição dos dados. <BR>O padrão é 'CODIGO'
 * @pw_element string $ordem_descrescente Exibir em Ordem Crescente ou Decrescente
 * @pw_element string $filtrar_por_data_de Filtra os registros até a data especificada.
 * @pw_element string $filtrar_por_data_ate Filtra os registros até a data especificada.
 * @pw_element string $filtrar_apenas_inclusao Filtra os registros exibindos apenas os incluídos.
 * @pw_element string $filtrar_apenas_alteracao Filtra os registros exibindos apenas os alterados.
 * @pw_complex rcListarRequest
 */
class rcListarRequest {
	/**
	 * Número da página que será listada.
	 *
	 * @var integer
	 */
	public $pagina;
	/**
	 * Número de registros por página.
	 *
	 * @var integer
	 */
	public $registros_por_pagina;
	/**
	 * Exibir apenas os registros gerados pela API.
	 *
	 * @var string
	 */
	public $apenas_importado_api;
	/**
	 * Ordem de exibição dos dados. <BR>O padrão é 'CODIGO'
	 *
	 * @var string
	 */
	public $ordenar_por;
	/**
	 * Exibir em Ordem Crescente ou Decrescente
	 *
	 * @var string
	 */
	public $ordem_descrescente;
	/**
	 * Filtra os registros até a data especificada.
	 *
	 * @var string
	 */
	public $filtrar_por_data_de;
	/**
	 * Filtra os registros até a data especificada.
	 *
	 * @var string
	 */
	public $filtrar_por_data_ate;
	/**
	 * Filtra os registros exibindos apenas os incluídos.
	 *
	 * @var string
	 */
	public $filtrar_apenas_inclusao;
	/**
	 * Filtra os registros exibindos apenas os alterados.
	 *
	 * @var string
	 */
	public $filtrar_apenas_alteracao;
}

/**
 * Resposta da listagem de Requisição de Compras
 *
 * @pw_element integer $pagina Número da página que será listada.
 * @pw_element integer $total_de_paginas Total de páginas encontradas.
 * @pw_element integer $registros Número de registros por página.
 * @pw_element integer $total_de_registros Total de registros encontrados.
 * @pw_element requisicaoCadastroArray $requisicaoCadastro Cadastro de Requisição de Compras&nbsp;
 * @pw_complex rcListarResponse
 */
class rcListarResponse {
	/**
	 * Número da página que será listada.
	 *
	 * @var integer
	 */
	public $pagina;
	/**
	 * Total de páginas encontradas.
	 *
	 * @var integer
	 */
	public $total_de_paginas;
	/**
	 * Número de registros por página.
	 *
	 * @var integer
	 */
	public $registros;
	/**
	 * Total de registros encontrados.
	 *
	 * @var integer
	 */
	public $total_de_registros;
	/**
	 * Cadastro de Requisição de Compras&nbsp;
	 *
	 * @var requisicaoCadastroArray
	 */
	public $requisicaoCadastro;
}

/**
 * Cadastro de Requisição de Compras
 *
 * @pw_element integer $codReqCompra Código da Requisição de Compras
 * @pw_element string $codIntReqCompra Código de integração da requisição de Compra.
 * @pw_element string $codCateg Código da Categoria
 * @pw_element integer $codProj Código do Projeto
 * @pw_element string $dtSugestao Data de Sugestão de Entrega
 * @pw_element string $obsReqCompra Preencha aqui as observações desta requisição (elas serão impressas na carta de cotação para o fornecedor)
 * @pw_element string $obsIntReqCompra Preencha aqui as observações internas desta requisição (elas serão exibidas apenas aqui)
 * @pw_element ItensReqCompraArray $ItensReqCompra Itens da Requisição de Compras
 * @pw_complex requisicaoCadastro
 */
class requisicaoCadastro {
	/**
	 * Código da Requisição de Compras
	 *
	 * @var integer
	 */
	public $codReqCompra;
	/**
	 * Código de integração da requisição de Compra.
	 *
	 * @var string
	 */
	public $codIntReqCompra;
	/**
	 * Código da Categoria
	 *
	 * @var string
	 */
	public $codCateg;
	/**
	 * Código do Projeto
	 *
	 * @var integer
	 */
	public $codProj;
	/**
	 * Data de Sugestão de Entrega
	 *
	 * @var string
	 */
	public $dtSugestao;
	/**
	 * Preencha aqui as observações desta requisição (elas serão impressas na carta de cotação para o fornecedor)
	 *
	 * @var string
	 */
	public $obsReqCompra;
	/**
	 * Preencha aqui as observações internas desta requisição (elas serão exibidas apenas aqui)
	 *
	 * @var string
	 */
	public $obsIntReqCompra;
	/**
	 * Itens da Requisição de Compras
	 *
	 * @var ItensReqCompraArray
	 */
	public $ItensReqCompra;
}

/**
 * Status da Requisição de Compras
 *
 * @pw_element integer $codReqCompra Código da Requisição de Compras
 * @pw_element string $codIntReqCompra Código de Integração da Requisição de Compras
 * @pw_element string $cCodStatus Código do Status
 * @pw_element string $cDesStatus Descrição do Status da Remessa
 * @pw_complex rcStatus
 */
class rcStatus {
	/**
	 * Código da Requisição de Compras
	 *
	 * @var integer
	 */
	public $codReqCompra;
	/**
	 * Código de Integração da Requisição de Compras
	 *
	 * @var string
	 */
	public $codIntReqCompra;
	/**
	 * Código do Status
	 *
	 * @var string
	 */
	public $cCodStatus;
	/**
	 * Descrição do Status da Remessa
	 *
	 * @var string
	 */
	public $cDesStatus;
}

/**
 * Erro gerado pela aplicação.
 *
 * @pw_element integer $code Codigo do erro
 * @pw_element string $description Descricao do erro
 * @pw_element string $referer Origem do erro
 * @pw_element boolean $fatal Indica se eh um erro fatal
 * @pw_complex omie_fail
 */
if (!class_exists('omie_fail')) {
	class omie_fail {
		/**
		 * Codigo do erro
		 *
		 * @var integer
		 */
		public $code;
		/**
		 * Descricao do erro
		 *
		 * @var string
		 */
		public $description;
		/**
		 * Origem do erro
		 *
		 * @var string
		 */
		public $referer;
		/**
		 * Indica se eh um erro fatal
		 *
		 * @var boolean
		 */
		public $fatal;
	}
}