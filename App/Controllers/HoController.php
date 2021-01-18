<?php

namespace App\Controllers;

date_default_timezone_set('America/Fortaleza');

use INTRA\Controller\Action;
use INTRA\Model\Container;

class HOController extends Action {

	public function index() {

		if (!isset($_SESSION)) {session_start();}

		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
			$this->render('index', 'layoutHO');
		} else {
			header('Location: /?login=erro');
		}

	}

	public function ho_user() {

		if (!isset($_SESSION)) {session_start();}

		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
			$this->render('ho_user', 'layoutHO');
		} else {
			header('Location: /?login=erro');
		}

	}

	public function ho_supervisor() {

		if (!isset($_SESSION)) {session_start();}

		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '' && $_SESSION['nivel'] != 'user') {
			$this->render('ho_supervisor', 'layoutHO');
		} else {
			header('Location: /?login=erro');
		}

	}

	public function ho_gerente() {

		if (!isset($_SESSION)) {session_start();}

		if ($_SESSION['id'] != '' && $_SESSION['nome'] != '' && $_SESSION['nivel'] == 'gerente') {
			$this->render('ho_gerente', 'layoutHO');
		} else {
			header('Location: /?login=erro');
		}

	}

	public function marcaponto() {

		if (!isset($_SESSION)) {session_start();}

		$erros = '';
		$igual = "N";

		//Registrando o dia
		$dia = Container::getModel('DiasHO');
		$dia->__set('data', $_POST['data']);
		$dia->__set('atividades', $_POST['atividades']);
		$dia->__set('obs', $_POST['obs']);
		$dia->__set('id_user', $_SESSION['id']);

		$consulta_dia = $dia->consultaDia($_POST['data'], $_SESSION['id']);

		if ($consulta_dia['data'] != NULL) {
			if ($consulta_dia['atividades'] != NULL) {
				$atividades = $consulta_dia['atividades'] . " | " . $_POST['atividades'];
			} else {
				$atividades = $_POST['atividades'];
			}
			if ($consulta_dia['obs'] != NULL) {
				$obs = $consulta_dia['obs'] . " | " . $_POST['obs'];
			} else {
				$obs = $_POST['obs'];
			}
			$dia->__set('data', $_POST['data']);
			$dia->__set('atividades', $atividades);
			$dia->__set('obs', $obs);
			$dia->__set('id_user', $_SESSION['id']);
			$update_dia = $dia->updateDia();

			if (substr($update_dia, 0, 4) == 'Erro') {
				$erros = $update_dia;
			}
		} else {
			$registro_dia = $dia->registraDia();

			if (substr($registro_dia, 0, 4) == 'Erro') {
				$erros = $registro_dia;
			}
		}

		//Registrando horario
		$hora = Container::getModel('HorariosHO');
		$hora->__set('data', $_POST['data']);
		$hora->__set('tipo', $_POST['tipo']);
		$hora->__set('horario', date('Y-m-d H:i:s'));
		$hora->__set('id_user', $_SESSION['id']);

		$consulta_hora = $hora->consultaHora($_POST['data'], $_SESSION['id']);

		foreach ($consulta_hora as $key => $hour) {
			if ($hour->tipo == $_POST['tipo']) {
				$igual = "S";
			}
		}

		if ($igual == "N") {

			$registro_hora = $hora->registraHora();

			if (substr($registro_hora, 0, 4) == 'Erro') {
				$erros .= $registro_hora;
			}
			if ($erros != '') {
				echo "
					<h5 class='exit'>" . $erros . "</h5>
        			<a type='button' href='/ho_user' class='btn-success'>Voltar</a>
        			  ";
			} else {
				echo "
					<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http:/ho_user'>
					<script type=\"text/javascript\">
					    alert(\"Registro efetuado com sucesso!\");
					</script>
					  ";
			}

		} else {
			echo "
					<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http:/ho_user'>
					<script type=\"text/javascript\">
					    alert(\"Registro de horário NÃO efetuado! Já existe um registro desse mesmo tipo.\");
					</script>
					  ";
		}

	}

	public function showTimes() {
		if (!isset($_SESSION)) {session_start();}

		$dia = Container::getModel('DiasHO');
		$consulta_dia = $dia->consultaDia($_POST['data'], $_SESSION['id']);
		$atividades = $consulta_dia['atividades'];
		$obs = $consulta_dia['obs'];

		$hora = Container::getModel('HorariosHO');
		$consulta_hora = $hora->consultaHora($_POST['data'], $_SESSION['id']);

		echo '<div class="form-group row">
				<label class="col-5 col-form-label labelformprod" style="font-size: 20px;">
					Horários Registrados:
				</label>
            	<div class="col-7">
            	<table class="table table-dark table-bordered table-responsive-sm table-hover ">';

		foreach ($consulta_hora as $key => $hour) {
			echo '
				<tr>
					<td class="align-middle text-center dont-break">
						' . date("H:i:s", strtotime($hour->horario)) . '
					</td>
					<td class="align-middle text-center dont-break">';

			switch ($hour->tipo) {
			case 'inicio':
				echo '<span style="font-weight:bold;color:#00ff00;">INÍCIO</span>';
				break;
			case 'almoco':
				echo '<span style="font-weight:bold;color:#ff8000;">ALMOÇO</span>';
				break;
			case 'retorno':
				echo '<span style="font-weight:bold;color:#b3ff1a;">RETORNO</span>';
				break;
			case 'fim':
				echo '<span style="font-weight:bold;color:#b32400;">FIM</span>';
				break;
			}
			echo '</td>
				</tr>
			';
		}

		echo '			</table>
            		</div>
        		</div>
        	</div>
        </div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label labelformprod" style="font-size: 20px;">Atividades:</label>
            <div class="col-sm-10">
				<p>' . $atividades . '</p>
            </div>
        </div>
        <div class="form-group row">
			<label class="col-sm-2 col-form-label labelformprod" style="font-size: 20px;">Observações:</label>
            <div class="col-sm-10">
				<p>' . $obs . '</p>
            </div>
        </div>


			';

	}

	public function searchDays() {
		if (!isset($_SESSION)) {session_start();}

		$retorno = '';

		if ($_POST['data'] == '' and $_POST['user'] == '') {
			$retorno = '
			<div class="alert alert-danger" role="alert">
  				Escolha pelo menos um filtro!
			</div>
			';
		} else {

			$dia = Container::getModel('DiasHO');
			$hora = Container::getModel('HorariosHO');
			$user = Container::getModel('Usuario');

			if ($_POST['data'] == '') {
				$data = date("d/m/Y", strtotime($_POST['data']));
				$consulta_dia = $dia->consultaDiaUnicoUser($_POST['user']);

				if ($consulta_dia == NULL) {
					$retorno = '<div class="alert alert-warning" role="alert">
  									Funcionário ainda não tem registros.
								</div>';
				} else {
					foreach ($consulta_dia as $key => $day) {
						$atividades = $day->atividades;
						$obs = $day->obs;
						$consulta_hora = $hora->consultaHora($day->data, $day->id_user);

						$retorno .= '<div class="container table-bordered">
										<div class="container"><h4 class="text-center bg-dark container" style="color:#F5F505;border: 2px solid black;">' . $day->data . '</h4></div>
						<div class="form-group row">
					<label class="col-lg-4 col-form-label labelformprod text-right" style="font-size: 20px;">
					Horários Registrados:
					</label>
            		<div class="col-lg-4">
            		<table class="table table-dark table-bordered table-responsive-sm table-hover ">';

						foreach ($consulta_hora as $key => $hour) {
							$retorno .= '
							<tr>
								<td class="align-middle text-center dont-break">
									' . date("H:i:s", strtotime($hour->horario)) . '
								</td>
								<td class="align-middle text-center dont-break">';

							switch ($hour->tipo) {
							case 'inicio':
								$retorno .= '<span style="font-weight:bold;color:#00ff00;">INÍCIO</span>';
								break;
							case 'almoco':
								$retorno .= '<span style="font-weight:bold;color:#ff8000;">ALMOÇO</span>';
								break;
							case 'retorno':
								$retorno .= '<span style="font-weight:bold;color:#b3ff1a;">RETORNO</span>';
								break;
							case 'fim':
								$retorno .= '<span style="font-weight:bold;color:#b32400;">FIM</span>';
								break;
							}
							$retorno .= '</td>
							</tr>
						';
						}

						$retorno .= '			</table>
        			    		</div>
        			</div>
					<div class="form-group row">
						<label class="col-lg-4 col-form-label labelformprod text-right" style="font-size: 		20px;">Atividades:</label>
        		    <div class="col-lg-8">
						<p>' . $atividades . '</p>
        		    </div>
        			</div>
        			<div class="form-group row">
						<label class="col-lg-4 col-form-label labelformprod text-right" style="font-size: 20px;">Observações:</label>
        		    	<div class="col-lg-8">
							<p>' . $obs . '</p>
        		    	</div>
        			</div>
        			</div>
					';
					}
				}

			} elseif ($_POST['user'] == '') {
				$data = date("d/m/Y", strtotime($_POST['data']));
				$consulta_dia = $dia->consultaDiaAllUsers($data);

				if ($consulta_dia == NULL) {
					$retorno = '<div class="alert alert-warning" role="alert">
  									Dia ' . $data . ' ainda sem registros.
								</div>';
				} else {
					foreach ($consulta_dia as $key => $day) {
						$consulta_hora = $hora->consultaHora($data, $day->id_user);
						$atividades = $day->atividades;
						$obs = $day->obs;
						$funcionario = $user->getNames($day->id_user);

						$retorno .= '<div class="container table-bordered">
										<div class="container"><h4 class="text-center bg-dark container" style="color:#F5F505;border: 2px solid black;">' . $funcionario['nome'] . '</h4></div>
						<div class="form-group row">
					<label class="col-lg-4 col-form-label labelformprod text-right" style="font-size: 20px;">
					Horários Registrados:
					</label>
            		<div class="col-lg-4">
            		<table class="table table-dark table-bordered table-responsive-sm table-hover ">';

						foreach ($consulta_hora as $key => $hour) {
							$retorno .= '
							<tr>
								<td class="align-middle text-center dont-break">
									' . date("H:i:s", strtotime($hour->horario)) . '
								</td>
								<td class="align-middle text-center dont-break">';

							switch ($hour->tipo) {
							case 'inicio':
								$retorno .= '<span style="font-weight:bold;color:#00ff00;">INÍCIO</span>';
								break;
							case 'almoco':
								$retorno .= '<span style="font-weight:bold;color:#ff8000;">ALMOÇO</span>';
								break;
							case 'retorno':
								$retorno .= '<span style="font-weight:bold;color:#b3ff1a;">RETORNO</span>';
								break;
							case 'fim':
								$retorno .= '<span style="font-weight:bold;color:#b32400;">FIM</span>';
								break;
							}
							$retorno .= '</td>
							</tr>
						';
						}

						$retorno .= '			</table>
        			    		</div>
        			</div>
					<div class="form-group row">
						<label class="col-lg-4 col-form-label labelformprod text-right" style="font-size: 		20px;">Atividades:</label>
        		    <div class="col-lg-8">
						<p>' . $atividades . '</p>
        		    </div>
        			</div>
        			<div class="form-group row">
						<label class="col-lg-4 col-form-label labelformprod text-right" style="font-size: 20px;">Observações:</label>
        		    	<div class="col-lg-8">
							<p>' . $obs . '</p>
        		    	</div>
        			</div>
        			</div>
					';
					}
				}

			} else {
				$data = date("d/m/Y", strtotime($_POST['data']));
				$consulta_dia = $dia->consultaDia($data, $_POST['user']);
				$atividades = $consulta_dia['atividades'];
				$obs = $consulta_dia['obs'];

				if ($consulta_dia == NULL) {
					$retorno = '<div class="alert alert-warning" role="alert">
  									Dia ' . $data . ' ainda sem registros para esse funcionário.
								</div>';
				} else {

					$consulta_hora = $hora->consultaHora($data, $_POST['user']);

					$retorno = '<div class="form-group row">
					<label class="col-lg-4 col-form-label labelformprod text-right" style="font-size: 20px;">
					Horários Registrados:
					</label>
            		<div class="col-lg-4">
            		<table class="table table-dark table-bordered table-responsive-sm table-hover ">';

					foreach ($consulta_hora as $key => $hour) {
						$retorno .= '
							<tr>
								<td class="align-middle text-center dont-break">
									' . date("H:i:s", strtotime($hour->horario)) . '
								</td>
								<td class="align-middle text-center dont-break">';

						switch ($hour->tipo) {
						case 'inicio':
							$retorno .= '<span style="font-weight:bold;color:#00ff00;">INÍCIO</span>';
							break;
						case 'almoco':
							$retorno .= '<span style="font-weight:bold;color:#ff8000;">ALMOÇO</span>';
							break;
						case 'retorno':
							$retorno .= '<span style="font-weight:bold;color:#b3ff1a;">RETORNO</span>';
							break;
						case 'fim':
							$retorno .= '<span style="font-weight:bold;color:#b32400;">FIM</span>';
							break;
						}
						$retorno .= '</td>
							</tr>
						';
					}

					$retorno .= '			</table>
        			    		</div>
        					</div>
        				</div>
        			</div>
					<div class="form-group row">
						<label class="col-lg-4 col-form-label labelformprod text-right" style="font-size: 		20px;">Atividades:</label>
        		    <div class="col-lg-8">
						<p>' . $atividades . '</p>
        		    </div>
        		</div>
        		<div class="form-group row">
					<label class="col-lg-4 col-form-label labelformprod text-right" style="font-size: 20px;">Observações:</label>
        		    <div class="col-lg-8">
						<p>' . $obs . '</p>
        		    </div>
        		</div>


				';

				}
			}

		}

		echo $retorno;

	}

}

?>