<?php
	include_once('head.php');
?>
<body class="bg-light">
	<div class="container">
<?php

		session_unset();
	  	session_start();


		ini_set('display_errors', 0 );
		error_reporting(0);

	
	//Gravando $caminho dentro da sessão aberta:
	  	if (!$_SESSION['caminho'] or $_SESSION['caminho'] == '/') {
	  		$_SESSION['caminho'] = '/documentos';
	  	}


		ini_set('display_errors', 1 );
		error_reporting(1);
		
	
	//Acessando $caminho dentro de uma sessão:
		$caminho = $_SESSION['caminho'];

	require_once 'ftp.php';
	require_once 'diretorio_remoto.php';
	require_once 'arquivo_remoto.php';

	$con = new ftp();
	$diremoto = new dir_remoto();

	//obj conexão
	$con->conectar();
	$conexaoFTP = $con->getConexao_ftp(); 

	//obj diretorio
	$diremoto->setCaminho($caminho);
	$diremoto->habilitaPassivo($conexaoFTP);
	$diremoto->escolheDir($conexaoFTP);
	$diremoto->dirAtual($conexaoFTP);

	//obj arquivo
	$arq = new arq_remoto();


	$lista = $diremoto->listaDir($conexaoFTP);

	$ultimo = count($lista)-1;

	if ($ultimo >= -1) {
		echo "<table class='table table-dark table-sm table-bordered table-responsive-sm table-hover'>";	
		for ($x=0; $x <= $ultimo ; $x++) {
			$arquivo[$x] = $diremoto->caminho.$lista[$x]; 
			if ($diremoto->isDir($conexaoFTP, $arquivo[$x]) == 'sim' ) {
				echo utf8_encode("<tr><td>".$lista[$x]."</td><td>
					<a href='action.php?id=2&pasta=".$lista[$x]."'>Abrir</a>
					</td></tr>");
			} else{
				echo utf8_encode("<tr><td>".$lista[$x]."</td><td><a href='action.php?id=3&arq=$lista[$x]&dir=$diremoto->caminho'>Executar</a></td></tr>");
			}			
		}
		echo "<tr><td colspan='2' class='text-right'><a href='action.php?id=1&dir=$diremoto->caminho'><img src='img/voltar.png'></a></td></tr></table>";
	}
?>

<script type="text/javascript">
function chamarPhpAjax() {
   $.ajax({
      url:'ajax.php',
      complete: function (response) {
         alert('deu certo?');
      },
      error: function () {
          alert('Erro');
      }
  });  

  return false;
}
</script>

	</div>
</body>
</html>

