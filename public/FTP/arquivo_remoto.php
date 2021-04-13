<?php

/**
 * 
 */
class arq_remoto //extends AnotherClass
{
	private $name_file;
	private $extensao;
	private $tamanho;
	private $local;

	
	public function getName_file() {
	    return $this->name_file;
	}
	 
	public function setName_file($arq) {
	    $this->name_file = substr($arq,0,(strrpos($arq, '.')));
	}

	public function getExtensao() {
	    return $this->extensao;
	}
	 
	public function setExtensao($arq) {
	    $this->extensao = substr($arq,(strrpos($arq, '.')+1));
	}


	public function getTamanho() {
	    return $this->tamanho;
	}
	 
	public function setTamanho($tamanho) {
	    $this->tamanho = $tamanho;
	}
	

	public function getLocal() {
	    return $this->local;
	}
	 
	public function setLocal($local) {
	    $this->local = $local;
	}

	public function download($con_ftp, $remote_file) {
		
		// copiando arquivo remoto para local
			date_default_timezone_set('America/Sao_Paulo');
			$local_file = 'temp/temp-'.date("Y-m-d H-i-s").'.'.$this->extensao;
			$handle = fopen($local_file, 'w');
			ftp_fget($con_ftp, $handle, $remote_file, FTP_BINARY, 0);
			return $local_file;
	}
}

?>