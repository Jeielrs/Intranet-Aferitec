<?php 
	include_once 'head.php';
	require_once 'ftp.php';
	require_once 'diretorio_remoto.php';
	require_once 'arquivo_remoto.php';
?>
<body class="bg-light">
	<div class="container">
<?php
	//Iniciando a sessão:
	if (session_status() !== PHP_SESSION_ACTIVE) {		
	  session_start();
	}
	//Acessando $caminho dentro de uma sessão:
	$caminho = $_SESSION['caminho'];
	
	//Verificando id recebido via GET
	$id = $_GET['id'];
	
	//obj conexao
	$con = new ftp();
	$con->conectar();
	$conexaoFTP = $con->getConexao_ftp();

	//obj diretorio
	$diremoto = new dir_remoto();
	$caminho = $caminho;
	$diremoto->setCaminho($caminho);
	$_SESSION['caminho'] = $diremoto->getCaminho();

	//obj arquivo
	$arqremoto = new arq_remoto();

	if ($id == 1) {
		$diremoto->setCaminho($caminho);			//Voltar
		$diremoto->escolheDir($conexaoFTP);
		$diremoto->backDir($conexaoFTP);
		$_SESSION['caminho'] = $diremoto->caminho;
		echo "
        	<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http://intranet.aferitec.br/FTP'>
        	 ";
    }
    else if ($id == 2) {
    	$pasta = utf8_decode($_GET['pasta']);
    	$novoCaminho = $diremoto->caminho.'/'.$pasta;	//Avançar
    	$diremoto->setCaminho($novoCaminho);
    	$_SESSION['caminho'] = $diremoto->getCaminho();
    	echo "
        	<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http://intranet.aferitec.br/FTP'>
        	 ";
    }
    else if ($id == 3) {
    	$diretorio = $_GET['dir'];
    	$arq = utf8_decode($_GET['arq']);						//Visualizar/Baixar
    	$arqq = $diretorio.'/'.$arq;
    	$diremoto->setCaminho($_SESSION['caminho']);
    	$diremoto->escolheDir($conexaoFTP);
    	$arqremoto->setName_file($arq);
    	$arqremoto->setLocal($diretorio);
    	$arqremoto->setExtensao($arq);
    	$diremoto->habilitaPassivo($conexaoFTP);
    	$local_file = $arqremoto->download($conexaoFTP, $arq);
    	echo "<b>Baixar: </b><a href='".$local_file."' download>".utf8_encode($arq)."</a>
    			<br>
    		<b>Visualizar: </b><a href='".$local_file."' target='blank'>".utf8_encode($arq)."</a>
    			<br>
    		<a href='http://intranet.aferitec.br/FTP'>Voltar</a>";
    }

?>
	</div>
</body>