<?php

namespace INTRA\Model;

/**
 * Trata a Paginação das Consultas
 */
class Pagination {
	/**
	*Número máximo de registros por página
	*@var integer
	**/
	private $limit;

	/**
	*Quantidade total de resultados do banco
	*@var integer
	**/
	private $results;

	/**
	*Quantidade de Páginas
	*@var integer
	**/
	private $pages;

	/**
	*Página atual
	*@var integer
	**/
	private $currentPage;

	/**
	*Construtor da Classe
	**/
	public function __construct($results, $currentPage = 1, $limit = 20){ //<--aqui(10) é definido o limite
		$this->results = $results;
		$this->limit = $limit;
		$this->currentPage = (is_numeric($currentPage) and $currentPage > 0) ? $currentPage : 1;
		$this->calculate();
	}

	/**
	*Método responsável por calcular a paginação
	**/
	private function calculate() {
		//Calcula Total de Páginas     /	ceil() é para arredondar número pra cima
		$this->pages = $this->results > 0 ? ceil($this->results / $this->limit): 1;

		//Verifica se a página atual não excede o número de páginas
		$this->currentPage = $this->currentPage <= $this->pages ? $this->currentPage : $this->pages;

	}

	/**
	*Método responsável por retornar a cláusula limit do SQL
	**/
	public function getLimit() {
		$offset = ($this->limit * ($this->currentPage - 1));
		return $offset.','.$this->limit;
	}

	/**
	*Método responsável por retornar as opções de páginas disponíveis
	**/
	public function getPages() {
		//NÃO RETORNA PAGINAS
		if ($this->pages == 1) return [];

		//PAGINAS
		$paginas = [];
		for ($i=1; $i <= $this->pages; $i++) { 
			$paginas[] = [
				'pagina' => $i,
				'atual' => $i == $this->currentPage
			];

		}
		return $paginas;
	}


}


?>