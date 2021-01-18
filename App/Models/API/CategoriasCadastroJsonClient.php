<?php
/**
 * @service CategoriasCadastroJsonClient
 * @author omie
 */
namespace App\Models\API;

define("OMIE_APP_KEY", "887760778905");
define("OMIE_APP_SECRET", "0fddf600b7ff808389137379650e858f");

class CategoriasCadastroJsonClient {
	/**
	 * The WSDL URI
	 *
	 * @var string
	 */
	public static $_WsdlUri = 'http://app.omie.com.br/api/v1/geral/categorias/?WSDL';
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
	public static $_EndPoint = 'http://app.omie.com.br/api/v1/geral/categorias/';

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
	 * Consulta uma categoria
	 *
	 * @param categoria_consultar $categoria_consultar Consulta um código de categoria
	 * @return categoria_cadastro Cadastro de Categorias
	 */
	public function ConsultarCategoria($categoria_consultar) {
		return self::_Call('ConsultarCategoria', Array(
			$categoria_consultar,
		));
	}

	/**
	 * Lista as categorias cadastradas
	 *
	 * @param categoria_list_request $categoria_list_request Lista as categorias cadastradas.
	 * @return categoria_listfull_response Retorna a lista de categorias existentes no Omie.
	 */
	public function ListarCategorias($categoria_list_request) {
		return self::_Call('ListarCategorias', Array(
			$categoria_list_request,
		));
	}
}

/**
 * Cadastro de Categorias
 *
 * @pw_element string $codigo Código para a Categoria
 * @pw_element string $descricao Descrição para a Categoria
 * @pw_element string $descricao_padrao Descrição Padrão para a Categoria
 * @pw_element string $conta_inativa Indica que a conta está inativo
 * @pw_element string $definida_pelo_usuario Indica que a conta financeira é definida pelo usuário
 * @pw_element integer $id_conta_contabil ID da Conta Contábil
 * @pw_element string $tag_conta_contabil Tag para Conta Contábil
 * @pw_element string $conta_despesa Quando S, indica que é conta de despesa
 * @pw_element string $nao_exibir Indica que a Categoria não deve ser exibida em ComboBox
 * @pw_element string $natureza Descrição da Natureza da conta
 * @pw_element string $conta_receita Quando S, indica que é conta de receita
 * @pw_element string $totalizadora Quando S, indica que é totalizadora de conta
 * @pw_element string $transferencia Quando S, indica que é categoria de transferência
 * @pw_element string $codigo_dre Código no DRE
 * @pw_element dadosDRE $dadosDRE Detalhes da conta do DRE.
 * @pw_complex categoria_cadastro
 */
class categoria_cadastro {
	/**
	 * Código para a Categoria
	 *
	 * @var string
	 */
	public $codigo;
	/**
	 * Descrição para a Categoria
	 *
	 * @var string
	 */
	public $descricao;
	/**
	 * Descrição Padrão para a Categoria
	 *
	 * @var string
	 */
	public $descricao_padrao;
	/**
	 * Indica que a conta está inativo
	 *
	 * @var string
	 */
	public $conta_inativa;
	/**
	 * Indica que a conta financeira é definida pelo usuário
	 *
	 * @var string
	 */
	public $definida_pelo_usuario;
	/**
	 * ID da Conta Contábil
	 *
	 * @var integer
	 */
	public $id_conta_contabil;
	/**
	 * Tag para Conta Contábil
	 *
	 * @var string
	 */
	public $tag_conta_contabil;
	/**
	 * Quando S, indica que é conta de despesa
	 *
	 * @var string
	 */
	public $conta_despesa;
	/**
	 * Indica que a Categoria não deve ser exibida em ComboBox
	 *
	 * @var string
	 */
	public $nao_exibir;
	/**
	 * Descrição da Natureza da conta
	 *
	 * @var string
	 */
	public $natureza;
	/**
	 * Quando S, indica que é conta de receita
	 *
	 * @var string
	 */
	public $conta_receita;
	/**
	 * Quando S, indica que é totalizadora de conta
	 *
	 * @var string
	 */
	public $totalizadora;
	/**
	 * Quando S, indica que é categoria de transferência
	 *
	 * @var string
	 */
	public $transferencia;
	/**
	 * Código no DRE
	 *
	 * @var string
	 */
	public $codigo_dre;
	/**
	 * Detalhes da conta do DRE.
	 *
	 * @var dadosDRE
	 */
	public $dadosDRE;
}

/**
 * Detalhes da conta do DRE.
 *
 * @pw_element string $codigoDRE Código da Conta do DRE.
 * @pw_element string $descricaoDRE Descrição da Conta do DRE.
 * @pw_element string $naoExibirDRE Indica se a Conta está marcada para não exibir no DRE.
 * @pw_element integer $nivelDRE Nível da Conta da DRE.
 * @pw_element string $sinalDRE Sinal da Conta para o DRE.
 * @pw_element string $totalizaDRE Indica se a Conta do DRE é Totalizadora.
 * @pw_complex dadosDRE
 */
class dadosDRE {
	/**
	 * Código da Conta do DRE.
	 *
	 * @var string
	 */
	public $codigoDRE;
	/**
	 * Descrição da Conta do DRE.
	 *
	 * @var string
	 */
	public $descricaoDRE;
	/**
	 * Indica se a Conta está marcada para não exibir no DRE.
	 *
	 * @var string
	 */
	public $naoExibirDRE;
	/**
	 * Nível da Conta da DRE.
	 *
	 * @var integer
	 */
	public $nivelDRE;
	/**
	 * Sinal da Conta para o DRE.
	 *
	 * @var string
	 */
	public $sinalDRE;
	/**
	 * Indica se a Conta do DRE é Totalizadora.
	 *
	 * @var string
	 */
	public $totalizaDRE;
}

/**
 * Consulta um código de categoria
 *
 * @pw_element string $codigo Código para a Categoria
 * @pw_complex categoria_consultar
 */
class categoria_consultar {
	/**
	 * Código para a Categoria
	 *
	 * @var string
	 */
	public $codigo;
}

/**
 * Lista as categorias cadastradas.
 *
 * @pw_element integer $pagina Número da página retornada
 * @pw_element integer $registros_por_pagina Número de registros retornados na página.
 * @pw_element string $apenas_importado_api DEPRECATED
 * @pw_element string $ordenar_por DEPRECATED
 * @pw_element string $ordem_descrescente DEPRECATED
 * @pw_complex categoria_list_request
 */
class categoria_list_request {
	/**
	 * Número da página retornada
	 *
	 * @var integer
	 */
	public $pagina;
	/**
	 * Número de registros retornados na página.
	 *
	 * @var integer
	 */
	public $registros_por_pagina;
	/**
	 * DEPRECATED
	 *
	 * @var string
	 */
	public $apenas_importado_api;
	/**
	 * DEPRECATED
	 *
	 * @var string
	 */
	public $ordenar_por;
	/**
	 * DEPRECATED
	 *
	 * @var string
	 */
	public $ordem_descrescente;
}

/**
 * Retorna a lista de categorias existentes no Omie.
 *
 * @pw_element integer $pagina Número da página retornada
 * @pw_element integer $total_de_paginas Número total de páginas
 * @pw_element integer $registros Número de registros retornados na página.
 * @pw_element integer $total_de_registros total de registros encontrados
 * @pw_element categoria_cadastroArray $categoria_cadastro Cadastro de Categorias
 * @pw_complex categoria_listfull_response
 */
class categoria_listfull_response {
	/**
	 * Número da página retornada
	 *
	 * @var integer
	 */
	public $pagina;
	/**
	 * Número total de páginas
	 *
	 * @var integer
	 */
	public $total_de_paginas;
	/**
	 * Número de registros retornados na página.
	 *
	 * @var integer
	 */
	public $registros;
	/**
	 * total de registros encontrados
	 *
	 * @var integer
	 */
	public $total_de_registros;
	/**
	 * Cadastro de Categorias
	 *
	 * @var categoria_cadastroArray
	 */
	public $categoria_cadastro;
}
