<?php

namespace App\Controllers;

//os recursos do miniframework
use INTRA\Controller\Action;
use INTRA\Model\Container;

class AuthController extends Action {

	public function autenticar() {

		$usuario = Container::getModel('Usuario');

		$usuario->__set('conta', $_POST['conta']);
		$usuario->__set('senha', $_POST['senha']);

		$usuario->autenticar();

		if ($usuario->__get('id') != '' && $usuario->__get('nome')) {

			session_start();

			$_SESSION['id'] = $usuario->__get('id');
			$_SESSION['nome'] = $usuario->__get('nome');
			$_SESSION['dpto'] = $usuario->__get('dpto'); //Atribuindo os dados do obj
			$_SESSION['nivel'] = $usuario->__get('nivel'); // na super global $_SESSION
			$_SESSION['key_omie'] = $usuario->__get('key_omie');
			$_SESSION['responsavel_rc'] = $usuario->__get('responsavel_rc');
			$_SESSION['email'] = $usuario->__get('email');
			$_SESSION['ramal'] = $usuario->__get('ramal');
			$_SESSION['conta'] = $usuario->__get('conta');

			header('Location: /intranet');

		} else {
			header('Location: /?login=erro');
		}

	}

	public function loginTeampass() {

		session_start();

		//quebrando
		session_destroy();

		//limpando
		session_unset();

		header('location: http://intranet.aferitec.br/teampass-master');
	}

	public function sair() {
		session_start();
		session_destroy();
		header('Location: /');
	}
}