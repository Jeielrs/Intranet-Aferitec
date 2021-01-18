<?php

namespace App;

use INTRA\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['home'] = array(
			'route' => '/',
			'controller' => 'indexController',
			'action' => 'index',
		);

		$routes['autenticar'] = array(
			'route' => '/autenticar',
			'controller' => 'AuthController',
			'action' => 'autenticar',
		);

		$routes['intranet'] = array(
			'route' => '/intranet',
			'controller' => 'AppController',
			'action' => 'intranet',
		);

		$routes['geiko'] = array(
			'route' => '/geiko',
			'controller' => 'AppController',
			'action' => 'geiko',
		);

		$routes['sair'] = array(
			'route' => '/sair',
			'controller' => 'AuthController',
			'action' => 'sair',
		);

		$routes['xmlfinder'] = array(
			'route' => '/xmlfinder',
			'controller' => 'xmlfinderController',
			'action' => 'xmlfinder',
		);

		/* ---------- HO ---------- */

		$routes['HO'] = array(
			'route' => '/ho',
			'controller' => 'HoController',
			'action' => 'index',
		);

		$routes['HO_user'] = array(
			'route' => '/ho_user',
			'controller' => 'HoController',
			'action' => 'ho_user',
		);

		$routes['HO_Supervisor'] = array(
			'route' => '/ho_supervisor',
			'controller' => 'HoController',
			'action' => 'ho_supervisor',
		);

		$routes['HO_Gerente'] = array(
			'route' => '/ho_gerente',
			'controller' => 'HoController',
			'action' => 'ho_gerente',
		);

		$routes['marcaponto'] = array(
			'route' => '/marcaponto',
			'controller' => 'HoController',
			'action' => 'marcaponto',
		);

		$routes['showTimes'] = array(
			'route' => '/showTimes',
			'controller' => 'HoController',
			'action' => 'showTimes',
		);

		$routes['searchDays'] = array(
			'route' => '/searchDays',
			'controller' => 'HoController',
			'action' => 'searchDays',
		);

		/* ---------- PDR ---------- */

		$routes['PDR'] = array(
			'route' => '/pdr',
			'controller' => 'PdrController',
			'action' => 'index',
		);

		$routes['nova_rc'] = array(
			'route' => '/nova_rc',
			'controller' => 'PdrController',
			'action' => 'nova_rc',
		);

		$routes['liberar'] = array(
			'route' => '/liberar',
			'controller' => 'PdrController',
			'action' => 'liberar',
		);

		$routes['aprovar'] = array(
			'route' => '/aprovar',
			'controller' => 'PdrController',
			'action' => 'aprovar',
		);

		$routes['provedores'] = array(
			'route' => '/provedores',
			'controller' => 'PdrController',
			'action' => 'provedores',
		);

		$routes['relatorios'] = array(
			'route' => '/relatorios',
			'controller' => 'PdrController',
			'action' => 'relatorios',
		);

		$routes['classificarRC'] = array(
			'route' => '/classificarRC',
			'controller' => 'PdrController',
			'action' => 'classificarRC',
		);

		$routes['criaRC'] = array(
			'route' => '/criaRC',
			'controller' => 'PdrController',
			'action' => 'criaRC',
		);

		$routes['liberaRC'] = array(
			'route' => '/liberaRC',
			'controller' => 'PdrController',
			'action' => 'liberaRC',
		);

		$routes['aprovaRC'] = array(
			'route' => '/aprovaRC',
			'controller' => 'PdrController',
			'action' => 'aprovaRC',
		);

		$routes['editarRC'] = array(
			'route' => '/editarRC',
			'controller' => 'PdrController',
			'action' => 'editarRC',
		);

		$routes['editar'] = array(
			'route' => '/editar',
			'controller' => 'PdrController',
			'action' => 'editar',
		);

		$routes['editaRC'] = array(
			'route' => '/editaRC',
			'controller' => 'PdrController',
			'action' => 'editaRC',
		);

		$routes['pesquisar'] = array(
			'route' => '/pesquisar',
			'controller' => 'PdrController',
			'action' => 'pesquisar',
		);

		$routes['pesquisarAll'] = array(
			'route' => '/pesquisarAll',
			'controller' => 'PdrController',
			'action' => 'pesquisarAll',
		);

		$routes['pesquisarRC'] = array(
			'route' => '/pesquisarRC',
			'controller' => 'PdrController',
			'action' => 'pesquisarRC',
		);

		$routes['loadProvedor'] = array(
			'route' => '/loadProvedor',
			'controller' => 'PdrController',
			'action' => 'loadProvedor',
		);

		$routes['buscaRelatorio'] = array(
			'route' => '/buscaRelatorio',
			'controller' => 'PdrController',
			'action' => 'buscaRelatorio',
		);

		/*----------API---------------*/

		$routes['loadBoxProd'] = array(
			'route' => '/loadBoxProd',
			'controller' => 'PdrController',
			'action' => 'loadBoxProd',
		);

		$routes['sincronizar'] = array(
			'route' => '/sincronizar',
			'controller' => 'ApiController',
			'action' => 'sincronizar',
		);

		$routes['sincRequisicao'] = array(
			'route' => '/sincRequisicao',
			'controller' => 'ApiController',
			'action' => 'sincRequisicao',
		);

		$routes['sincPedido'] = array(
			'route' => '/sincPedido',
			'controller' => 'ApiController',
			'action' => 'sincPedido',
		);

		$routes['classifica'] = array(
			'route' => '/classifica',
			'controller' => 'PdrController',
			'action' => 'classifica',
		);

		$this->setRoutes($routes);
	}

}

?>