<?php
/**
 * @service ProjetosCadastroJsonClient
 * @author omie
 */
namespace App\Models\API;

//define("OMIE_APP_KEY", "887760778905");
//define("OMIE_APP_SECRET", "0fddf600b7ff808389137379650e858f");

class ProjetosCadastroJsonClient {
	/**
	 * The WSDL URI
	 *
	 * @var string
	 */
	public static $_WsdlUri = 'http://app.omie.com.br/api/v1/geral/projetos/?WSDL';
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
	public static $_EndPoint = 'http://app.omie.com.br/api/v1/geral/projetos/';

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
	 * Inclui um novo projeto
	 *
	 * @param projIncluirRequest $projIncluirRequest Solicitação de Inclusão de um projeto
	 * @return projIncluirResponse Resposta da Solicitação de inclusão de um projeto.
	 */
	public function IncluirProjeto($projIncluirRequest) {
		return self::_Call('IncluirProjeto', Array(
			$projIncluirRequest,
		));
	}

	/**
	 * Altera um projeto
	 *
	 * @param projAlterarRequest $projAlterarRequest Solicitação de Alteração de um projeto
	 * @return projAlterarResponse Resposta da Solicitação de alteração de um projeto.
	 */
	public function AlterarProjeto($projAlterarRequest) {
		return self::_Call('AlterarProjeto', Array(
			$projAlterarRequest,
		));
	}

	/**
	 * Inclui / Altera um projeto.
	 *
	 * @param projUpsertRequest $projUpsertRequest Solicitação de Inclusão/Alteração de um projeto
	 * @return projUpsertResponse Resposta da Solicitação de inclusão/alteração de um projeto.
	 */
	public function UpsertProjeto($projUpsertRequest) {
		return self::_Call('UpsertProjeto', Array(
			$projUpsertRequest,
		));
	}

	/**
	 * Exclui um projeto
	 *
	 * @param projExcluirRequest $projExcluirRequest Solicitação de Exclusão de um projeto.
	 * @return projExcluirResponse Resposta da Solicitação de exclusão de um projeto.
	 */
	public function ExcluirProjeto($projExcluirRequest) {
		return self::_Call('ExcluirProjeto', Array(
			$projExcluirRequest,
		));
	}

	/**
	 * Consulta um projeto
	 *
	 * @param projConsultarRequest $projConsultarRequest Solicitação da Consulta de projeto
	 * @return projConsultarResponse Resposta da Consulta de Projeto
	 */
	public function ConsultarProjeto($projConsultarRequest) {
		return self::_Call('ConsultarProjeto', Array(
			$projConsultarRequest,
		));
	}

	/**
	 * Lista os projetos cadastrados
	 *
	 * @param projListarRequest $projListarRequest Solicitação de Listagem de Projetos
	 * @return projListarResponse Resposta da listagem de Projetos
	 */
	public function ListarProjetos($projListarRequest) {
		return self::_Call('ListarProjetos', Array(
			$projListarRequest,
		));
	}
}

/**
 * Cadastro de Projetos
 *
 * @pw_element integer $codigo Código do projeto.
 * @pw_element string $codInt Código de Integração do projeto.
 * @pw_element string $nome Nome do projeto.
 * @pw_element string $inativo Projeto inativo [S/N].
 * @pw_complex cadastro
 */
class cadastro {
	/**
	 * Código do projeto.
	 *
	 * @var integer
	 */
	public $codigo;
	/**
	 * Código de Integração do projeto.
	 *
	 * @var string
	 */
	public $codInt;
	/**
	 * Nome do projeto.
	 *
	 * @var string
	 */
	public $nome;
	/**
	 * Projeto inativo [S/N].
	 *
	 * @var string
	 */
	public $inativo;
}

/**
 * Solicitação de Alteração de um projeto
 *
 * @pw_element integer $codigo Código do projeto.
 * @pw_element string $codInt Código de Integração do projeto.
 * @pw_element string $nome Nome do projeto.
 * @pw_element string $inativo Projeto inativo [S/N].
 * @pw_complex projAlterarRequest
 */
class projAlterarRequest {
	/**
	 * Código do projeto.
	 *
	 * @var integer
	 */
	public $codigo;
	/**
	 * Código de Integração do projeto.
	 *
	 * @var string
	 */
	public $codInt;
	/**
	 * Nome do projeto.
	 *
	 * @var string
	 */
	public $nome;
	/**
	 * Projeto inativo [S/N].
	 *
	 * @var string
	 */
	public $inativo;
}

/**
 * Resposta da Solicitação de alteração de um projeto.
 *
 * @pw_element integer $codigo Código do projeto.
 * @pw_element string $codInt Código de Integração do projeto.
 * @pw_element string $status Status do processamento
 * @pw_element string $descricao Descrição do status
 * @pw_complex projAlterarResponse
 */
class projAlterarResponse {
	/**
	 * Código do projeto.
	 *
	 * @var integer
	 */
	public $codigo;
	/**
	 * Código de Integração do projeto.
	 *
	 * @var string
	 */
	public $codInt;
	/**
	 * Status do processamento
	 *
	 * @var string
	 */
	public $status;
	/**
	 * Descrição do status
	 *
	 * @var string
	 */
	public $descricao;
}

/**
 * Solicitação da Consulta de projeto
 *
 * @pw_element integer $codigo Código do projeto.
 * @pw_element string $codInt Código de Integração do projeto.
 * @pw_complex projConsultarRequest
 */
class projConsultarRequest {
	/**
	 * Código do projeto.
	 *
	 * @var integer
	 */
	public $codigo;
	/**
	 * Código de Integração do projeto.
	 *
	 * @var string
	 */
	public $codInt;
}

/**
 * Resposta da Consulta de Projeto
 *
 * @pw_element integer $codigo Código do projeto.
 * @pw_element string $codInt Código de Integração do projeto.
 * @pw_element string $nome Nome do projeto.
 * @pw_element string $inativo Projeto inativo [S/N].
 * @pw_complex projConsultarResponse
 */
class projConsultarResponse {
	/**
	 * Código do projeto.
	 *
	 * @var integer
	 */
	public $codigo;
	/**
	 * Código de Integração do projeto.
	 *
	 * @var string
	 */
	public $codInt;
	/**
	 * Nome do projeto.
	 *
	 * @var string
	 */
	public $nome;
	/**
	 * Projeto inativo [S/N].
	 *
	 * @var string
	 */
	public $inativo;
}

/**
 * Solicitação de Exclusão de um projeto.
 *
 * @pw_element integer $codigo Código do projeto.
 * @pw_element string $codInt Código de Integração do projeto.
 * @pw_complex projExcluirRequest
 */
class projExcluirRequest {
	/**
	 * Código do projeto.
	 *
	 * @var integer
	 */
	public $codigo;
	/**
	 * Código de Integração do projeto.
	 *
	 * @var string
	 */
	public $codInt;
}

/**
 * Resposta da Solicitação de exclusão de um projeto.
 *
 * @pw_element integer $codigo Código do projeto.
 * @pw_element string $codInt Código de Integração do projeto.
 * @pw_element string $status Status do processamento
 * @pw_element string $descricao Descrição do status
 * @pw_complex projExcluirResponse
 */
class projExcluirResponse {
	/**
	 * Código do projeto.
	 *
	 * @var integer
	 */
	public $codigo;
	/**
	 * Código de Integração do projeto.
	 *
	 * @var string
	 */
	public $codInt;
	/**
	 * Status do processamento
	 *
	 * @var string
	 */
	public $status;
	/**
	 * Descrição do status
	 *
	 * @var string
	 */
	public $descricao;
}

/**
 * Solicitação de Inclusão de um projeto
 *
 * @pw_element string $codInt Código de Integração do projeto.
 * @pw_element string $nome Nome do projeto.
 * @pw_element string $inativo Projeto inativo [S/N].
 * @pw_complex projIncluirRequest
 */
class projIncluirRequest {
	/**
	 * Código de Integração do projeto.
	 *
	 * @var string
	 */
	public $codInt;
	/**
	 * Nome do projeto.
	 *
	 * @var string
	 */
	public $nome;
	/**
	 * Projeto inativo [S/N].
	 *
	 * @var string
	 */
	public $inativo;
}

/**
 * Resposta da Solicitação de inclusão de um projeto.
 *
 * @pw_element integer $codigo Código do projeto.
 * @pw_element string $codInt Código de Integração do projeto.
 * @pw_element string $status Status do processamento
 * @pw_element string $descricao Descrição do status
 * @pw_complex projIncluirResponse
 */
class projIncluirResponse {
	/**
	 * Código do projeto.
	 *
	 * @var integer
	 */
	public $codigo;
	/**
	 * Código de Integração do projeto.
	 *
	 * @var string
	 */
	public $codInt;
	/**
	 * Status do processamento
	 *
	 * @var string
	 */
	public $status;
	/**
	 * Descrição do status
	 *
	 * @var string
	 */
	public $descricao;
}

/**
 * Solicitação de Listagem de Projetos
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
 * @pw_element string $nome_projeto Nome do projeto.
 * @pw_complex projListarRequest
 */
class projListarRequest {
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
	/**
	 * Nome do projeto.
	 *
	 * @var string
	 */
	public $nome_projeto;
}

/**
 * Resposta da listagem de Projetos
 *
 * @pw_element integer $pagina Número da página que será listada.
 * @pw_element integer $total_de_paginas Total de páginas encontradas.
 * @pw_element integer $registros Número de registros por página.
 * @pw_element integer $total_de_registros Total de registros encontrados.
 * @pw_element cadastroArray $cadastro Cadastro de Projetos
 * @pw_complex projListarResponse
 */
class projListarResponse {
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
	 * Cadastro de Projetos
	 *
	 * @var cadastroArray
	 */
	public $cadastro;
}

/**
 * Solicitação de Inclusão/Alteração de um projeto
 *
 * @pw_element integer $codigo Código do projeto.
 * @pw_element string $codInt Código de Integração do projeto.
 * @pw_element string $nome Nome do projeto.
 * @pw_element string $inativo Projeto inativo [S/N].
 * @pw_complex projUpsertRequest
 */
class projUpsertRequest {
	/**
	 * Código do projeto.
	 *
	 * @var integer
	 */
	public $codigo;
	/**
	 * Código de Integração do projeto.
	 *
	 * @var string
	 */
	public $codInt;
	/**
	 * Nome do projeto.
	 *
	 * @var string
	 */
	public $nome;
	/**
	 * Projeto inativo [S/N].
	 *
	 * @var string
	 */
	public $inativo;
}

/**
 * Resposta da Solicitação de inclusão/alteração de um projeto.
 *
 * @pw_element integer $codigo Código do projeto.
 * @pw_element string $codInt Código de Integração do projeto.
 * @pw_element string $status Status do processamento
 * @pw_element string $descricao Descrição do status
 * @pw_complex projUpsertResponse
 */
class projUpsertResponse {
	/**
	 * Código do projeto.
	 *
	 * @var integer
	 */
	public $codigo;
	/**
	 * Código de Integração do projeto.
	 *
	 * @var string
	 */
	public $codInt;
	/**
	 * Status do processamento
	 *
	 * @var string
	 */
	public $status;
	/**
	 * Descrição do status
	 *
	 * @var string
	 */
	public $descricao;
}
