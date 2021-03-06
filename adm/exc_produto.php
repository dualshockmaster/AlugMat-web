<?php
	$titulo = "Exclusão de Produto";
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
		
		$qry = "delete from produto where id = $id";
		$res = @mysqli_query($dbc,$qry);
		
		if ($res) {
			$sucesso = "<h1><strong>Sucesso!</strong></h1>
						<p>Seu registro foi excluido com sucesso!</p>
						<p>Aguarde... Redirecionando!</p>";
			
			echo "<div class='alert alert-success'>$sucesso</div>";
			
			echo "<meta HTTP-EQUIV='refresh' CONTENT='3; URL=menu_produto.php'>";
		}
		else {
			$erro = "<h1><strong>Erro no Sistema</strong></h1>
					 <p>O registro não pode ser excluido devido a um erro do sistema. Pedimos desculpas por qualquer inconveniente.</p>";
			
			$erro .= '<p>' . mysqli_error($dbc) . '<br /> Query: ' . @$q . '</p>';
			
			echo "<div class='alert alert-danger'>$erro</div>";
		}
	}
	
	//Pesquisa para exibir o registro para alteração
	$qry = "select * from produto where id = $id";
	$res = @mysqli_query($dbc, $qry);
  
	if (mysqli_num_rows($res) == 1) {
		$row = mysqli_fetch_array($res, MYSQLI_NUM);
?>


	<div id="main" class="container-fluid">
		<h3 class="page-header">Cadastro de Produto</h3>
		<form method="post" action="">
		  <div id="actions"> 
			<div class="form group col-md-6">
				<label>* Descrição</label>
				<input type="text" name="descricao" maxlength="50" class="form-control" value="<?php echo $row[1]; ?>" disabled>
			</div>

			<div class="form group col-md-4">
				<label>* Código Interno</label>
				<input type="text" name="cd_interno" maxlength="10" class="form-control" value="<?php echo $row[2]; ?>" disabled>
			</div>

			<div class="form group col-md-2">
				<label>* Valor da diária</label>
				<input type="text" name="valor_diaria" maxlength="8" class="form-control" value="<?php echo $row[3]; ?>" disabled>
			</div>

			<div class="form group col-md-2">
			<br />
				<label for="">* Status</label>
				<select class="form-control" name="status" disabled>
					<option value="">Selecione</option>
					<option value="S" <?php if ($row[4] == "S") echo "selected"; ?>>Ativo</option>
					<option value="N" <?php if ($row[4] == "N") echo "selected"; ?>>Inativo</option>
				</select>
			</div>

		

			<div class="form-group col-md-2" >
			<br />
			<label for="sel1">* Marca:</label>
			<select class="form-control" id="sel1" name="marca" disabled>
				<option value="">Selecione</option>
				<option value="Gedore" <?php if ($row[7] == "Gedore") echo "selected"; ?>>Gedore</option>
				<option value="Belzer" <?php if ($row[7] == "Belzer") echo "selected"; ?>>Belzer</option>
				<option value="Vonder" <?php if ($row[7] == "Vonder") echo "selected"; ?>>Vonder</option>
			</select>
			</div>

			<div class="form-group col-md-2" >
			<br />
				<label for="sel1">* Categoria:</label>
				<select class="form-control" id="sel1" name="categoria" disabled>
					<option value="">Selecione</option>
					<option value="Peças" <?php if ($row[8] == "Peças") echo "selected"; ?>>Peças</option>
					<option value="Máquinas" <?php if ($row[8] == "Máquinas") echo "selected"; ?>>Máquinas</option>
					<option value="Ferramentas" <?php if ($row[8] == "Ferramentas") echo "selected"; ?>>Ferramentas</option>
				</select>
			</div>

			<div class="form-group col-md-4" >
			<br />
				<label for="sel1">* Fornecedor:</label>
				<select class="form-control" id="sel1" name="fornecedor" disabled>
					<option value="">Selecione</option>
					<option value="MACTEC" <?php if ($row[9] == "MACTEC") echo "selected"; ?>>MACTEC</option>
					<option value="FERMAC" <?php if ($row[9] == "FERMAC") echo "selected"; ?>>FERMAC</option>
					<option value="SIMANTECNO" <?php if ($row[9] == "SIMANTECNO") echo "selected"; ?>>SIMANTECNO</option>
				</select>
			</div>
			
			<div class="form-group col-md-2" >
				<br/>
				<label for="sel1">Destaque:</label>
				<select class="form-control" id="sel1" name="destaque" disabled>
					<option value="">Selecione</option>
					<option value="S" <?php if ($row[14] == "S") echo "selected"; ?>>Sim</option>
					<option value="N" <?php if ($row[14] == "N") echo "selected"; ?>>Não</option>
				</select>
			</div>

				 <div class="form-group col-md-12">
    			<label for="exampleFormControlTextarea1">Caracteristicas</label>
    			<textarea class="form-control" id="exampleFormControlTextarea1" rows="7" name="caracteristicas" style="resize: none;" disabled><?php echo $row[6]; ?></textarea>
  			 </div>
			
			<div class="col-md-12">
			<button type="submit" class="btn btn-danger">Excluir</button>
			<a href="menu_produto.php" class="btn btn-default">Cancelar</a>
			<input type="hidden" name="enviou" value="True" />
			<input type="hidden" name="id" value="<?php echo $row[0]; ?>" />
			</div>
			</div>
		</form>
		<?php
		include_once('../includes/rodape.php');
		?>
	</div>

				
</html>

<?php
  }
  
  mysqli_close($dbc);
  
  include_once('../includes/rodape.php');
?>	
