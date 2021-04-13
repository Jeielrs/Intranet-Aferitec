<?php
	
	/**
	 * 
	 */

	class dir_remoto
	{
		
		public $caminho;
		private $tamanho;

		public function getCaminho() {
		    return $this->caminho;
		}
		 
		public function setCaminho($caminho) {
		    $this->caminho = $caminho;
		}


		public function getTamanho() {
		    return $this->tamanho;
		}
		 
		public function setTamanho($tamanho) {
		    $this->tamanho = $tamanho;
		}


		public function escolheDir($con_ftp) {
			ftp_chdir($con_ftp, $this->caminho);
		}

		public function habilitaPassivo($con_ftp) {
			ftp_pasv($con_ftp, true);
		}

		public function criaDir() {

		}

		public function dirAtual($con_ftp) {
			$dirAtual = (ftp_pwd($con_ftp));
			echo utf8_encode("<h5>Diretorio atual: <i>$dirAtual</i></h5>");
		}

		public function renomearDir() {

		}

		public function removeDir() {

		}

		public function backDir($con_ftp) {
			ftp_cdup($con_ftp);
			$this->caminho = (ftp_pwd($con_ftp));
		}

		public function listaDir($con_ftp) {
			$lista = ftp_nlist($con_ftp, '.');
			return $lista;
		}

		//Verifica se é diretório
		public function isDir($con_ftp, $arq) {

			//se tiver pontop antes dos caracteres de
			// formato de arquivo, não pode ser diretorio
			$file3dig = substr($arq, 0,-3);
			$ponto3dig = substr($file3dig, -1);
			$file4dig = substr($arq, 0,-4);
			$ponto4dig = substr($file4dig, -1);

			$formchar3 = substr($file3dig, -5);
			$formchar4 = substr($file4dig, -5);

			if ($ponto3dig == '.' and $formchar3 != 'FORM.') {
				$isDir = 'nao';
			} else{
				if ($ponto4dig == '.' and $formchar4 != 'FORM.') {
					$isDir = 'nao';
				} else {
					$isDir = 'sim';
				}				
			}


			return $isDir;
		}
	}

?>