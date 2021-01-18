<?php

namespace App\Controllers;

//os recursos do miniframework
use INTRA\Controller\Action;
use INTRA\Model\Container;

class AppController extends Action {

	public function intranet() {

		if (!isset($_SESSION)) {session_start();}

		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

			//obtenção dos ramais
			$ramal = Container::getModel('usuario');

			$ramais = $ramal->getRamais();

			$this->view->ramais = $ramais;

			$this->render('intranet', 'layoutCentral'); //render('arquivo.phtml', 'layout.phtml')
		} else {
			header('Location: /?login=erro');
		}

	}

	public function PMD() {

		if (!isset($_SESSION)) {session_start();}

		$user = $_SESSION['conta'];
		$senha = $_SESSION['senha'];

		header('Location: ../Intranet_old/PMD/index.php');

	}

	public function geiko() {

		if (!isset($_SESSION)) {session_start();}

		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
			$this->render('geiko', 'layoutCentral'); //render('arquivo.phtml', 'layout.phtml')
		} else {
			header('Location: /?login=erro');
		}

	}
}

?>