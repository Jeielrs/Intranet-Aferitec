<html>
<head>
<meta http-equiv="Content-Type"; content="text/html"; charset="utf-8">
<?php
// Dados do servidor
//$servidor = '187.115.24.49:50880'; // Endereço
$servidor = '10.10.1.4';
$usuario = 'ti.aferitec'; // Usuário
$senha = 'Afe@183ti'; // Senha

// set up a connection or die
$con_ftp = ftp_connect($servidor) or die("Não foi possível conectar-se à $servidor");

// try to login
if (@ftp_login($con_ftp, $usuario, $senha)) {
    echo "Conectado ao usuário $usuario no servidor $servidor\n";
} else {
    echo "Não foi possível conectar-se com $usuario\n";
}

// Habilita o modo Passivo
ftp_pasv($con_ftp, true);

// Define o diretório atual
ftp_chdir($con_ftp, 'documentos/readers');

// Descreve o diretório atual da conexão
echo "<br> Diretório atual: " . ftp_pwd($con_ftp) . "\n";

// Lista itens do diretório
$lista = ftp_nlist($con_ftp, '.');
sort($lista);
echo "<br>";
$ultimo = count($lista)-1;
date_default_timezone_set('America/Sao_Paulo');
for ($x=0; $x <= $ultimo ; $x++) { 
	echo $lista[$x]."<br>";
}

// copiando arquivo remoto para local
$remote_file = 'Log.png';
$local_file = 'temp/teste1-'.date("Y-m-d H-i-s").'.png';
$name_file = substr($local_file, 5);
// open some file to write to
$handle = fopen($local_file, 'w');

// try to download $remote_file and save it to $handle
if (ftp_fget($con_ftp, $handle, $remote_file, FTP_ASCII, 0)) {
 echo "<br> Arquivo gravado com sucesso em $local_file\n";
} else {
 echo "<br> Houve um problema enquanto baixava o arquivo $remote_file to $local_file\n";
}

//Aula Robson Leite link= https://www.youtube.com/watch?v=zsDKPNLMYFE

$baseDir = 'temp/';
//Ternária: Se GET[dir] existir, terá valor de dir, senão(:), dir terá valor da $baseDir
$abreDir = (array_key_exists('dir', $_GET) ? $_GET['dir'] : $baseDir); 

//função dir define que é um diretório
$openDir = dir($abreDir);

//substr terá escrito o caminho total do diretorio -1 (/)...strrpos irá contar caracteres na variavel até a /
$strrdir = strrpos(substr($abreDir, 0,-1), '/');
$backdir = substr($abreDir,0, $strrdir+1);
?>
</head>
<body>

	<table width="200" border="1" cellspacing="0" cellpadding="5">
		
<?php

while ($arq = $openDir -> read()) {
	if ($arq == $name_file) {
		echo '<tr>
				<td align="center"><a href="'.$abreDir.$arq.'" target="blank">'.$arq.'</a></td>	
			  <tr>';
	}
}

/*
while ($arq = $openDir -> read()) {
	if ($arq != '.' && $arq != '..'){
		if (is_dir($abreDir.$arq)){	//verificando se é pasta
			echo '<br>
				  <tr>
					<td align="center"><a href="testeftp.php?dir='.$abreDir.$arq.'/" >'.$arq.' >></a></td>	
				  <tr>';
		}else{
			echo '<tr>
					<td align="center"><a href="'.$abreDir.$arq.'" target="blank">'.$arq.'</a></td>	
				  <tr>';
			  }
	}
}
*/
?>

	</table>
</body>

<?php

if ($abreDir != $baseDir) {
	echo "<a href='testeftp.php?dir=".$backdir."'>voltar</a>";
}
/*----------- testes ----------
echo "<br><br>substr é : ".substr($abreDir, 0,-1)."<br>
		backdir é: ".$backdir."<br>
		abredir é : ".$abreDir."<br>
       strrpos é : ".strrpos(substr($abreDir, 0,-1), '/').".";
*/

$openDir -> close();


/*
$diretorio = '../teste/temp'; //////////////////////////////////definindo o diretório a ser listado
$ponteiro = opendir($diretorio); ////////////////////função opendir é que abre e lê o diretório

while ($nome_itens = readdir($ponteiro)) { //////////colocando todos itens do diretório na array
	$itens[] = $nome_itens;
}
sort($itens);	/////////////////////////////////////ordenando a array

foreach ($itens as $listar) {	/////////////////////fazendo listagem inserindo no array pastas
	$pastas[] = $listar;
}
echo "<body><div><ul>";
if ($pastas != '') {
	foreach ($pastas as $listar) {
		echo "<li>".$listar."</li>";
}
}
echo "</ul></div></body>";
*/

//code Fecha conexão ftp
ftp_close($con_ftp);
fclose($handle);
?>
</html>