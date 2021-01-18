<?php

namespace App\Controllers;

use INTRA\Controller\Action;

class PMDController extends Action {

	public function index() {

		session_start();

		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

			$this->render('index', 'layoutPMD');
		} else {
			header('Location: /?login=erro');
		}

	}

	public function rel() {

		session_start();

		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

			$this->render('rel', 'layoutPMD');
		} else {
			header('Location: /?login=erro');
		}

	}

	public function solicita() {

		session_start();

		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

			$this->render('solicita', 'layoutPMD');
		} else {
			header('Location: /?login=erro');
		}

	}
/*
public function ajax() {

session_start();

if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

$this->render('ajax', 'layoutPMD');
} else {
header('Location: /?login=erro');
}

}
 */
	public function new_me() {

		session_start();

		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

			$this->render('new_me', 'layoutPMD');
		} else {
			header('Location: /?login=erro');
		}

	}

	public function new_de() {

		session_start();

		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

			$this->render('new_de', 'layoutPMD');
		} else {
			header('Location: /?login=erro');
		}

	}
}

?>