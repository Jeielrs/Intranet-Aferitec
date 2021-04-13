<?php

require_once 'ftp.php';
require_once 'diretorio_remoto.php';

/**
 * 
 */
class conFTP extends ftp
{
	private $id_acao;

	public function getId_acao() {
	    return $this->id_acao;
	}
	 
	public function setId_acao($id_acao) {
	    $this->id_acao = $id_acao;
	}

	public function acao() {
		if ($this->id_acao == 1) {
		ftp_cdup($this->conexao_ftp);
		echo "
        <META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http://intranet.aferitec.br/teste'>
        <script type=\"text/javascript\">
            alert(\"surpresa, eu voltei\");
        </script>
          ";
		}
	}
}

acao();



?>