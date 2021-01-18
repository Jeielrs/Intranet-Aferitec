<?php

namespace App\Controllers;

//os recursos do miniframework
use INTRA\Controller\Action;

class IndexController extends Action {

	public function index() {

		session_start();

		#if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
		#	header('Location:/intranet');
		#} else {
		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
		$this->render('index', 'layoutLogin');
		#}

	}

}

?>