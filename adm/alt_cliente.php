<?php
	$titulo = "Alteração de Usuário";
	include_once('../includes/cabecalho.php');

	require_once('../includes/funcoes.php');

	authorize_user();
	
	if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) {
		$id = $_GET['id'];
	}
	else if ((isset($_POST['id'])) && (is_numeric($_POST['id']))) {
		$id = $_POST['id'];
	}
	else {
		//header("Location: usuario_menu.php");
		exit();
	}
	
	require_once('../includes/conexao.php');
	
	if (isset($_POST['enviou'])) {
		
		$erros = array();
		
		//TIPO DE PESSOA
		if (isset($_POST['tipo_pessoa']) && $_POST['tipo_pessoa'] == "") {
			$erros[] = 'Por favor, informe o Tipo de Pessoa.';
		}
		else {
			$tipo_pessoa = mysqli_real_escape_string($dbc,$_POST['tipo_pessoa']);
		}
		
		//NOME
		if (empty($_POST['nome'])) {
			$erros[] = 'Por favor, informe o Nome.';
		}
		else {
			$nome = mysqli_real_escape_string($dbc,trim($_POST['nome']));
		}
		
		//CPF
		if ($_POST['tipo_pessoa'] == 'F' && empty($_POST['cpf'])) {
			$erros[] = 'Por favor, informe o CPF.';
		}
		else {
			$cpf = (!empty($_POST['cpf'])) ? mysqli_real_escape_string($dbc,trim($_POST['cpf'])) : NULL;
		}
		
		//RG
		if ($_POST['tipo_pessoa'] == 'F' && empty($_POST['rg'])) {
			$erros[] = 'Por favor, informe o RG.';
		}
		else {
			$rg = (!empty($_POST['rg'])) ? mysqli_real_escape_string($dbc,trim($_POST['rg'])) : NULL;
		}
		
		//CNPJ
		if ($_POST['tipo_pessoa'] == 'J' && empty($_POST['cnpj'])) {
			$erros[] = 'Por favor, informe o CNPJ.';
		}
		else {
			$cnpj = (!empty($_POST['cnpj'])) ? mysqli_real_escape_string($dbc,trim($_POST['cnpj'])) : NULL;
		}
		
		//IE
		if ($_POST['tipo_pessoa'] == 'J' && empty($_POST['ie'])) {
			$erros[] = 'Por favor, informe a IE.';
		}
		else {
			$ie = (!empty($_POST['ie'])) ? mysqli_real_escape_string($dbc,trim($_POST['ie'])) : NULL;
		}
		
		//TIPO DE LOGRADOURO
		if (empty($_POST['logradouro'])) {
			$erros[] = 'Por favor, informe o Tipo de Logradouro.';
		}
		else {
			$logradouro = mysqli_real_escape_string($dbc,trim($_POST['logradouro']));
		}
		
		//LOGRADOURO
		if (empty($_POST['nome_logradouro'])) {
			$erros[] = 'Por favor, informe o Logradouro.';
		}
		else {
			$nome_logradouro = mysqli_real_escape_string($dbc,trim($_POST['nome_logradouro']));
		}
		
		//NÚMERO
		if (empty($_POST['num'])) {
			$erros[] = 'Por favor, informe o Número.';
		}
		else if (!is_numeric($_POST['num'])) {
			$erros[] = 'Formato do Número inválido. Por favor, digite apenas números nesse campo.';
		}
		else {
			$num = mysqli_real_escape_string($dbc,trim($_POST['num']));
		}
		
		//COMPLEMENTO
		if (!empty($_POST['complemento'])) {
			$complemento = mysqli_real_escape_string($dbc,trim($_POST['complemento']));
		}
		else {
			$complemento = "";
		}
		
		//BAIRRO
		if (empty($_POST['bairro'])) {
			$erros[] = 'Por favor, informe o Bairro.';
		}
		else {
			$bairro = mysqli_real_escape_string($dbc,trim($_POST['bairro']));
		}
		
		//CIDADE
		if (empty($_POST['cidade'])) {
			$erros[] = 'Por favor, informe a Cidade.';
		}
		else {
			$cidade = mysqli_real_escape_string($dbc,trim($_POST['cidade']));
		}
		
		//UF
		if (empty($_POST['estado'])) {
			$erros[] = 'Por favor, informe o Estado.';
		}
		else {
			$estado = mysqli_real_escape_string($dbc,trim($_POST['estado']));
		}
		
		//TELEFONE E CELULAR
		if (empty($_POST['telefone']) && empty($_POST['celular'])) {
			$erros[] = 'Por favor, informe ao menos um número de Telefone ou Celular.';
		}
		else {
			//TELEFONE
			if (!empty($_POST['telefone'])) {
				$telefone = mysqli_real_escape_string($dbc,trim($_POST['telefone']));
			}
			else {
				$telefone = NULL;
			}
			//CELULAR
			if (!empty($_POST['celular'])) {
				$celular = mysqli_real_escape_string($dbc,trim($_POST['celular']));
			}
			else {
				$celular = NULL;
			}
		}
		
		//E-MAIL
		if (empty($_POST['email'])) {
			$erros[] = 'Por favor, informe o E-mail.';
		}
		else {
			$email = mysqli_real_escape_string($dbc,trim($_POST['email']));
			
			$qry = "select email from cliente where id = $id";
			$res = @mysqli_query($dbc, $qry);
			$reg = @mysqli_fetch_array($res);
			$confirma_email = $reg['email'];
						
			$email_dup = "select email from cliente where email = trim('$email') and id <> $id";
			$res_email_dup = mysqli_query($dbc,$email_dup);
			
			if (mysqli_num_rows($res_email_dup) > 0) {
				$erros[] = 'Este E-mail já está cadastrado em nosso site. Por favor, informe outro E-mail.';
				unset($email);
			}
		}
		
		//SENHA
		if (!empty($_POST['senha'])) {
			if ($_POST['senha'] != $_POST['senhac']) {
				$erros[] = 'As senhas digitadas não correspondem. Por favor, informe duas senhas iguais nos campos "Senha" e "Confirmar Senha".';
			}
			else {
				$senha = mysqli_real_escape_string($dbc, trim($_POST['senha']));
			}
		}
		else {
			$erros[] = 'Por favor, informe e confirme a Senha.';
		}
		
		//STATUS
		if (empty($_POST['status'])) {
			$erros[] = 'Por favor, informe o Status.';
		}
		else {
			$status = mysqli_real_escape_string($dbc,trim($_POST['status']));
		}
		
		//TIPO USUÁRIO
		if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'ADM' && empty($_POST['tipo_usuario'])) {
			$erros[] = 'Por favor, informe o Tipo de Usuário';
		}
		else if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'ADM' && !empty($_POST['tipo_usuario'])) {
			$tipo_usuario = $_POST['tipo_usuario'];
		}
		else {
			$tipo_usuario = 'USU';
		}
		
		
		
		//Se não há nenhum erro, inserir registro no banco de dados
		if (empty($erros)) {
			$qry = "update cliente set  tipo_pessoa = '$tipo_pessoa',
										nome = '$nome',
										cpf = '$cpf',
										rg = '$rg',
										cnpj = '$cnpj',
										ie = '$ie',
										logradouro = '$logradouro',
										nome_logradouro = '$nome_logradouro',
										num = '$num',
										complemento = '$complemento',
										bairro = '$bairro',
										cidade = '$cidade',
										estado = '$estado',
										telefone = '$telefone',
										celular = '$celular',
										email = '$email',
										senha = '".SHA1('bd_alugmat'.$senha)."',
										data_alt = NOW(),
										status = '$status',
										tipo_usuario = '$tipo_usuario'
					where id = $id";
			$res = @mysqli_query($dbc,$qry);
			
			if ($res) {
				$sucesso = "<h1><strong>Sucesso!</strong></h1>
							<p>Seu registro foi incluido com sucesso!</p>
							<p>Aguarde... Redirecionando!</p>";
				
				if ((isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'USU') || !isset($_SESSION['tipo_usuario'])) {
					echo "<meta HTTP-EQUIV='refresh' CONTENT='3; URL=../index.php'>";
				}
				else if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'ADM') {
					echo "<meta HTTP-EQUIV='refresh' CONTENT='3; URL=menu_cliente.php'>";
				}
			}
			else {
				$erro = "<h1><strong>Erro no Sistema</strong></h1>
						 <p>Você não pode ser registrado devido a um erro do sistema. Pedimos desculpas por qualquer inconveniente.</p>";
				
				$erro .= '<p>' . mysqli_error($dbc) . '<br /> Query: ' . $q . '</p>';
			}
		}
		
		//Se existem erros, exibir para o usuário
		else {
			$erro = "<h1><strong>Erro!</strong></h1>
					 <p>Ocorreram o(s) seguinte(s) erro(s):<br />";
					 
			foreach ($erros as $msg) {
				$erro .= " - $msg <br /> \n";
			}
			$erro .= "</p><p>Por favor, tente novamente.</p>";
		}
	}
	
	//Pesquisa para exibir o registro para alteração
	$qry = "select * from cliente where id = $id";
	$res = @mysqli_query($dbc, $qry);
  
	if (mysqli_num_rows($res) == 1) {
		$row = mysqli_fetch_array($res, MYSQLI_NUM);
	
	if (isset($erro)) echo "<div class='alert alert-danger'>$erro</div>"; 
	
	if (isset($sucesso)) echo "<div class='alert alert-success'>$sucesso</div>"; 
?>


	<div id="main" class="container-fluid">
		<h3 class="page-header">Alteração de Usuário</h3>
		
		<script>
		window.onload = function() {
			tipopessoa(tipo_pessoa);
		}
		
		function tipopessoa(e) { 
					if(e.value == "F") {
						document.getElementById("cpf").disabled = false;
						document.getElementById("rg").disabled = false;
						document.getElementById("cnpj").disabled = true;
						document.getElementById("ie").disabled = true;
						document.getElementById("cnpj").value = "";
						document.getElementById("ie").value = "";
					} if(e.value == "J") {
						document.getElementById("cnpj").disabled = false;
						document.getElementById("ie").disabled = false;
						document.getElementById("cpf").disabled = true;
						document.getElementById("rg").disabled = true;
						document.getElementById("cpf").value = "";
						document.getElementById("rg").value = "";
					}
					if (e.value == "") {
						document.getElementById("cnpj").disabled = true;
						document.getElementById("ie").disabled = true;
						document.getElementById("cpf").disabled = true;
						document.getElementById("rg").disabled = true;
						document.getElementById("cnpj").value = "";
						document.getElementById("ie").value = "";
						document.getElementById("cpf").value = "";
						document.getElementById("rg").value = "";
					}
				 };
				 
		$(document).ready(tipopessoa(tipo_pessoa));
		</script>
		
		<form action="alt_cliente.php" method="post">
		  <div id="actions" class="row"> 
			<div class="form-group col-md-2" >
				<label for="">Tipo de Pessoa</label>
				<select name="tipo_pessoa" id="tipo_pessoa" class="form-control" onchange="tipopessoa(tipo_pessoa)">
					<option value="">Selecione</option>
					<option value="F" <?php if ($row[1] == "F") echo "selected"; ?>>Pessoa Física</option>
					<option value="J" <?php if ($row[1] == "J") echo "selected"; ?>>Pessoa Juridica</option>
				</select>
			</div>		
					
			<div class="form-group col-md-4">
				<label for="nome"> * Nome</label>
				<input type="text" class="form-control" name="nome" id="nome" placeholder="" maxlength="50" value="<?php echo $row[2]; ?>">
			</div>

			<div class="form-group col-md-2">
				<label for="cpf"> * CPF</label>
				<input type="text" class="form-control" id="cpf" name="cpf" placeholder="Ex.: 000.000.000-00" maxlength="14" value="<?php echo $row[3]; ?>" <?php //if (tipo_pessoa != "F") echo "disabled"; ?>>
			</div>
			
			<div class="form-group col-md-2">
				<label for="rg"> * RG</label>
				<input type="text" class="form-control" id="rg" name="rg" placeholder="" maxlength="12" value="<?php echo $row[4]; ?>" <?php //if (tipo_pessoa != "F") echo "disabled"; ?>>
			</div>

			<div class="form-group col-md-2">
				<label for="cnpj"> * CNPJ</label>
				<input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="Ex.: 00.000.000/0000-00" maxlength="18" value="<?php echo $row[5]; ?>" <?php //if (tipo_pessoa != "J") echo "disabled"; ?>>
			</div>

			<div class="form-group col-md-2">
				<label for="ie"> * IE </label>
				<input type="text" class="form-control" id="ie" name="ie" placeholder="000.000.000.000" maxlength="15" value="<?php echo $row[6]; ?>" <?php //if (tipo_pessoa != "J") echo "disabled"; ?>>
			</div>

			<div class="form-group col-md-2">
				<label for="longadouro"> * Tipo de Logradouro </label>
				<input type="text" class="form-control" id="logradouro" name="logradouro" placeholder="" maxlength="20" value="<?php echo $row[7]; ?>">
			</div>

			<div class="form-group col-md-4">
				<label for="nome_longadouro"> * Nome do Logradouro </label>
				<input type="text" class="form-control" id="nome_logradouro" name="nome_logradouro" placeholder="" maxlength="50" value="<?php echo $row[8]; ?>">
			</div>

			<div class="form-group col-md-1">
				<label for="numero"> * Núm. </label>
				<input type="text" class="form-control" id="num" name="num" placeholder="" maxlength="5" value="<?php echo $row[9]; ?>">
			</div>

			<div class="form-group col-md-3">
				<label for="complemento"> Complemento </label>
				<input type="text" class="form-control" id="complemento" name="complemento" placeholder="" maxlength="20" value="<?php echo $row[10]; ?>">
			</div>

			<div class="form-group col-md-3">
				<label for="bairro"> * Bairro </label>
				<input type="text" class="form-control" id="bairro" name="bairro" placeholder="" maxlength="30" value="<?php echo $row[11]; ?>">
			</div>

			<div class="form-group col-md-3">
				<label for="cidade"> * Cidade  </label>
				<input type="text" class="form-control" id="cidade" name="cidade" placeholder="" maxlength="40" value="<?php echo $row[12]; ?>">
			</div>

			<div class="form-group col-md-2" >
				<label for="estado">* Estado:</label>
				<select class="form-control" id="estado" name="estado">
					<option value="">Selecione</option>
					<option value="AC" <?php if ($row[13] == "AC") echo "selected"; ?>>AC</option>
					<option value="AL" <?php if ($row[13] == "AL") echo "selected"; ?>>AL</option>
					<option value="AP" <?php if ($row[13] == "AP") echo "selected"; ?>>AP</option>
					<option value="AM" <?php if ($row[13] == "AM") echo "selected"; ?>>AM</option>
					<option value="BA" <?php if ($row[13] == "BA") echo "selected"; ?>>BA</option>
					<option value="CE" <?php if ($row[13] == "CE") echo "selected"; ?>>CE</option>
					<option value="DF" <?php if ($row[13] == "DF") echo "selected"; ?>>DF</option>
					<option value="ES" <?php if ($row[13] == "ES") echo "selected"; ?>>ES</option>
					<option value="GO" <?php if ($row[13] == "GO") echo "selected"; ?>>GO</option>
					<option value="MA" <?php if ($row[13] == "MA") echo "selected"; ?>>MA</option>
					<option value="MT" <?php if ($row[13] == "MT") echo "selected"; ?>>MT</option>
					<option value="MS" <?php if ($row[13] == "MS") echo "selected"; ?>>MS</option>
					<option value="MG" <?php if ($row[13] == "MG") echo "selected"; ?>>MG</option>
					<option value="PA" <?php if ($row[13] == "PA") echo "selected"; ?>>PA</option>
					<option value="PB" <?php if ($row[13] == "PB") echo "selected"; ?>>PB</option>
					<option value="PR" <?php if ($row[13] == "PR") echo "selected"; ?>>PR</option>
					<option value="PE" <?php if ($row[13] == "PE") echo "selected"; ?>>PE</option>
					<option value="PI" <?php if ($row[13] == "PI") echo "selected"; ?>>PI</option>
					<option value="RJ" <?php if ($row[13] == "RJ") echo "selected"; ?>>RJ</option>
					<option value="RN" <?php if ($row[13] == "RN") echo "selected"; ?>>RN</option>
					<option value="RS" <?php if ($row[13] == "RS") echo "selected"; ?>>RS</option>
					<option value="RO" <?php if ($row[13] == "RO") echo "selected"; ?>>RO</option>
					<option value="RR" <?php if ($row[13] == "RR") echo "selected"; ?>>RR</option>
					<option value="SC" <?php if ($row[13] == "SC") echo "selected"; ?>>SC</option>
					<option value="SP" <?php if ($row[13] == "SP") echo "selected"; ?>>SP</option>
					<option value="SE" <?php if ($row[13] == "SE") echo "selected"; ?>>SE</option>
					<option value="TO" <?php if ($row[13] == "TO") echo "selected"; ?>>TO</option>
			</select>
			</div>


			<div class="form-group col-md-2">
				<label for="telefone"> * Telefone </label>
				<input type="text" class="form-control" id="telefone" name="telefone" placeholder="Ex.: (00)0000-0000" maxlength="13" value="<?php echo $row[14]; ?>">
			</div>

			<div class="form-group col-md-2">
				<label for="email"> * Celular </label>
				<input type="text" class="form-control" id="celular" name="celular" placeholder="Ex.: (00)00000-0000" maxlength="14" value="<?php echo $row[15]; ?>">
			</div>

			<div class="form-group col-md-4">
				<label for="email"> * E-mail </label>
				<input type="email" class="form-control" id="email" name="email" placeholder="Digite o seu endereço de e-mail" maxlength="80" value="<?php echo $row[16]; ?>">
			</div>

			<div class="form-group col-md-3">
				<label for="senha">* Senha</label>
				<input type="password" class="form-control" id="senha" name="senha" maxlength="10" placeholder="Digite a senha">
			</div>	

			<div class="form-group col-md-3">
				<label for="senhac">* Confirmar Senha</label>
				<input type="password" class="form-control" id="senhac" name="senhac" maxlength="10" placeholder="Confirme a senha">
			</div>
			
			<div class="form-group col-md-2" >
				<label for="estado">* Status:</label>
				<select class="form-control" id="status" name="status">
					<option value="">Selecione</option>
					<option value="S" <?php if ($row[20] == "S") echo "selected"; ?>>Ativo</option>
					<option value="N" <?php if ($row[20] == "N") echo "selected"; ?>>Inativo</option>
			</select>
			</div>
			
			<?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'ADM') { ?>
				<div class="form-group col-md-2">
						<label for="tipo_usuario">* Tipo de Usuário:</label>
							<select class="form-control" id="tipo_usuario" name="tipo_usuario">
								<option value="">Selecione</option>
								<option value="ADM" <?php if ($row[21] == "ADM") echo "selected"; ?>>Administrador</option>
								<option value="USU" <?php if ($row[21] == "USU") echo "selected"; ?>>Usuário</option>
							</select>
				</div>
			<?php } ?>
			
			<div class="row">
			</div>

			<hr />
			
			<div class="col-md-12">
			<input type="submit" class="btn btn-primary" value="Salvar"><!-- >Salvar</button> -->
			<?php
			if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'ADM') {
				echo '<a href="menu_cliente.php" class="btn btn-default">Cancelar</a>';
			}
			else if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'USU') {
				echo '<a href="../index.php" class="btn btn-default">Cancelar</a>';
			}
			?>
			<input type="hidden" name="enviou" value="True" />
			<input type="hidden" name="id" value="<?php echo $row[0]; ?>" />
			</div>
		  </div>
		 <?php
			include_once('../includes/rodape.php');
		 ?>
		  
		</form>
		
<?php
  }
  
  mysqli_close($dbc);
  
  include_once('../includes/rodape.php');
?>		