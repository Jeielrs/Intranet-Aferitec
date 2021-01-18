<?php
####################### By: Jeiel Rodrigues #######################
#### Esse script chama as funções de sincronização do arquivo  ####
#### APIController do PDR na Intranet                          ####
###################################################################
require_once "../../../www/intranet/vendor/autoload.php";

//$route = new \App\Route;

use App\Controllers\PdrController;
use App\Controllers\ApiController;

class Pdr extends PdrController {
	function NotificaErroAprovacao() {
		$email = "ti@aferitec.com.br";
		$tipo = "X";
		$codreq = 000;
		$user  = "ADM";
		$num = $this->consultaRCaprovada();
		if ($num['num'] > 0) {
			$envio = $this->notificaMail($email, $tipo, $codreq, $user);
			$texto = "E-mail enviado = " . $envio;
		}
		else {
			$texto = "Todas requisições aprovadas estão sincronizadas.";
		}
		return $texto;
		
	}
}

class Classe extends ApiController {

	function sincronismo() {
		$data = date("d-m-Y");
		$hora = date('H:i');

		if ($hora == "04:30") {
			$texto = $this->sincRequisicao();
		} elseif ($hora == "04:00") {
			$texto = $this->sincPedido();
		} elseif ($hora == "05:00") {
			$texto = $this->sincronizar(); //(produtos)
		}
		return $texto;
	}

}
$classe = new Classe();
$pdr = new Pdr();

$texto = $pdr->NotificaErroAprovacao();
$texto .= $classe->sincronismo();

$data = date("Y-m-d");
$hora = date('H-i');

$filename = '../../../www/intranet/public/log/' . $data . '_' . $hora . '.html';
/*  o "a+" abaixo significa:
- Abre o arquivo para leitura e gravação;
- coloca o ponteiro no fim do arquivo.
- Se o arquivo não existir, tentar criá-lo.
 */
//$log = fopen("log/" . $data . "_" . $hora . ".txt", "a");
//$escreve = fwrite($log, $texto);
//fclose($log);

//criamos o arquivo
$arquivo = fopen($filename, 'a+');
//verificamos se foi criado
if ($arquivo == false) {
	die('Não foi possível criar o arquivo.');
}

//escrevemos no arquivo
$escreve = fwrite($arquivo, $texto);
if ($escreve == false) {
	die('Não foi possível escrever no arquivo.');
}
//Fechamos o arquivo após escrever nele
fclose($arquivo);

?>

