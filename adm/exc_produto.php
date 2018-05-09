<?php
	$titulo = "Exclusão de Produto";
	include_once('../includes/cabecalho.php');
	
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
						<p>Seu registro foi incluido com sucesso!</p>
						<p>Aguarde... Redirecionando!</p>";
			
			echo "<div class='alert alert-success'>$sucesso</div>";
			
			echo "<meta HTTP-EQUIV='refresh' CONTENT='3; URL=menu_produto.php'>";
		}
		else {
			$erro = "<h1><strong>Erro no Sistema</strong></h1>
					 <p>Você não pode ser registrado devido a um erro do sistema. Pedimos desculpas por qualquer inconveniente.</p>";
			
			$erro .= '<p>' . mysqli_error($dbc) . '<br /> Query: ' . $q . '</p>';
			
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
				<input type="text" name="descricao" maxlength="50" class="form-control" value="<?php echo $row[1]; ?>">
			</div>

			<div class="form group col-md-4">
				<label>* Código Interno</label>
				<input type="text" name="cd_interno" maxlength="10" class="form-control" value="<?php echo $row[2]; ?>">
			</div>

			<div class="form group col-md-2">
				<label>* Valor da diária</label>
				<input type="text" name="valor_diaria" maxlength="8" class="form-control" value="<?php echo $row[3]; ?>">
			</div>

			<div class="form group col-md-2">
			<br />
				<label for="">* Status</label>
				<select class="form-control" name="status">
					<option value="">Selecione</option>
					<option value="S" <?php if ($row[4] == "S") echo "selected"; ?>>Ativo</option>
					<option value="N" <?php if ($row[4] == "N") echo "selected"; ?>>Inativo</option>
				</select>
			</div>

		

			<div class="form-group col-md-2" >
			<br />
			<label for="sel1">* Marca:</label>
			<select class="form-control" id="sel1" name="marca">
				<option value="">Selecione</option>
				<option value="Bosh" <?php if ($row[7] == "Bosh") echo "selected"; ?>>Bosh</option>
				<option value="3M" <?php if ($row[7] == "3M") echo "selected"; ?>>3M</option>
				<option value="Bracol" <?php if ($row[7] == "Bracol") echo "selected"; ?>>Bracol</option>
			</select>
			</div>

			<div class="form-group col-md-2" >
			<br />
				<label for="sel1">* Categoria:</label>
				<select class="form-control" id="sel1" name="categoria">
					<option value="">Selecione</option>
					<option value="pecas" <?php if ($row[8] == "pecas") echo "selected"; ?>>Peças</option>
					<option value="maquinas" <?php if ($row[8] == "maquinas") echo "selected"; ?>>Máquinas</option>
					<option value="ferramentas" <?php if ($row[8] == "ferramentas") echo "selected"; ?>>Ferramentas</option>
				</select>
			</div>

			<div class="form-group col-md-4" >
			<br />
				<label for="sel1">* Fornecedor:</label>
				<select class="form-control" id="sel1" name="fornecedor">
					<option value="">Selecione</option>
					<option value="fornecedor 1" <?php if ($row[9] == "fornecedor 1") echo "selected"; ?>>Fornecedor 1</option>
					<option value="fornecedor 2" <?php if ($row[9] == "fornecedor 2") echo "selected"; ?>>Fornecedor 2</option>
					<option value="fornecedor 3" <?php if ($row[9] == "fornecedor 3") echo "selected"; ?>>Fornecedor 3</option>
				</select>
			</div>
			
			<div class="form-group col-md-2" >
				<br/>
				<label for="sel1">Destaque:</label>
				<select class="form-control" id="sel1" name="destaque">
					<option value="">Selecione</option>
					<option value="S" <?php if ($row[14] == "S") echo "selected"; ?>>Sim</option>
					<option value="N" <?php if ($row[14] == "N") echo "selected"; ?>>Não</option>
				</select>
			</div>

				 <div class="form-group col-md-12">
    			<label for="exampleFormControlTextarea1">Caracteristicas</label>
    			<textarea class="form-control" id="exampleFormControlTextarea1" rows="7" name="caracteristicas"><?php echo $row[6]; ?></textarea>
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