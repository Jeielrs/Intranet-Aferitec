<?php

namespace INTRA\Controller;

abstract class Action {

	protected $view;

	public function __construct() {
		$this->view = new \stdClass();
	}

	protected function render($view, $layout) {
		$this->view->page = $view;

		if (file_exists("../App/Views/" . $layout . ".phtml")) {
			require_once "../App/Views/" . $layout . ".phtml";
		} else {
			$this->content();
		}
	}

	//protected function renderNoLayout($view) {
	//	$this->view->page = $view;
	//
	//	$this->content();
	//
	//}

	protected function chooseMenu($menu) {

		if (file_exists("../App/Views/pdr/menus/" . $menu . ".phtml")) {
			require_once "../App/Views/pdr/menus/" . $menu . ".phtml";
		} else {
			$this->content();
		}

	}

	protected function carregarCaixas() {

	}

	protected function content() {
		$classAtual = get_class($this);

		$classAtual = str_replace('App\\Controllers\\', '', $classAtual);

		$classAtual = strtolower(str_replace('Controller', '', $classAtual));

		require_once "../App/Views/" . $classAtual . "/" . $this->view->page . ".phtml";
	}
}

?>