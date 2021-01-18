<?php

switch (isset($_GET['mode']) ? $_GET['mode'] : null) {
case 'caixa':
	require '/ApiController';
	break;

default:
	echo "Falha ao Carregar, o arquivo não foi encontrado durante a requisição.";
	break;
}

?>