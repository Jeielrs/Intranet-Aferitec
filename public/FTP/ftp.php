<?php

/**
 * 
 */
class ftp //extends AnotherClass
{
	protected $host;
	protected $user;
	protected $senha;
	private   $conexao_ftp;
	public    $conectado;

	public function __construct()
	{
		$this->host = "10.10.1.4";
		$this->user = "ti.aferitec";
		$this->senha = "Afe@1994ti";
	}

	public function conectar()
	{
		$this->conexao_ftp = ftp_connect($this->host);
		if (ftp_login($this->conexao_ftp, $this->user, $this->senha)){
			echo "<h3 style='color:green';>Acesso ao Servidor-AF02</h3>";
		} else{
			echo "Não foi possível se conectar à $this->host com esse usuário.";
			exit;
		}
	}

	public function desconectar()
	{	
		$conexao = $this->conexao_ftp;
		if(ftp_close($conexao)){
		echo "<br>Desconectado do FTP.";
		} else{
			echo "<br>Não foi possível desconectar do FTP.";
		}
	}

	public function getConexao_ftp() {
	    return $this->conexao_ftp;
	}
	 
	public function setConexao_ftp($conexao_ftp) {
	    $this->conexao_ftp = $conexao_ftp;
	}

	
}

?>