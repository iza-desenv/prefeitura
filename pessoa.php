<?php
/*
CRUD completo do Cadastro de Pessoas
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = filter_input(INPUT_POST, 'id');
    $nome = filter_input(INPUT_POST, 'nome');
    $nascimento = filter_input(INPUT_POST, 'nascimento');
    $cpf = filter_input(INPUT_POST, 'cpf');
    $sexo = filter_input(INPUT_POST, 'sexo');
    $rua = filter_input(INPUT_POST, 'rua');
    $numero = filter_input(INPUT_POST, 'numero');
    $bairro = filter_input(INPUT_POST, 'bairro');
    $cidade = filter_input(INPUT_POST, 'cidade');
    $complemento = filter_input(INPUT_POST, 'complemento');
	
} else if (!isset($id)) {
// Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
}

// Cria a conexão com o banco de dados
try {
    $conexao = new PDO("mysql:host=localhost;dbname=Prefeitura", "root", "");
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->exec("set names utf8");
} catch (PDOException $erro) {
    echo "<p class=\"bg-danger\">Erro na conexão:" . $erro->getMessage() . "</p>";
}

// Bloco If que Salva os dados no Banco - atua como Create e Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "") {
    try {
		
		// Validando os campos
		if(empty($nome) || empty($nascimento) || empty($cpf) || empty($sexo)) {	
			
			if(empty($nome)) {
				echo "<font color='red'>Nome deve ser informado!</font><br/>";
			}
			
			if(empty($nascimento)) {
				echo "<font color='red'>Data de Nascimento deve ser informada!</font><br/>";
			}
			
			if(empty($cpf)) {
				echo "<font color='red'>CPF deve ser informado!</font><br/>";
			}
			if(empty($sexo)) {
				echo "<font color='red'>Sexo deve ser informado!</font><br/>";
			}
			} else {
			} 
	
			if ($id != "") {
				$stmt = $conexao->prepare("UPDATE Prefeitura.Pessoa SET nome=?, nascimento=?,cpf=?, sexo=?, rua=?, numero=?, bairro=?, cidade=?, complemento=? WHERE id = ?");
				$stmt->bindParam(10, $id);
			} else {
				$stmt = $conexao->prepare("INSERT INTO Prefeitura.Pessoa (nome, nascimento, cpf, sexo, rua, numero, bairro, cidade, complemento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
			}
			$stmt->bindParam(1, $nome);
			$stmt->bindParam(2, $nascimento);
			$stmt->bindParam(3, $cpf);
			$stmt->bindParam(4, $sexo);
			$stmt->bindParam(5, $rua);
			$stmt->bindParam(6, $numero);
			$stmt->bindParam(7, $bairro);
			$stmt->bindParam(8, $cidade);
			$stmt->bindParam(9, $complemento);
			
			if ($stmt->execute()) {
				if ($stmt->rowCount() > 0) {
					echo "<p class=\"bg-success\">.         Dados cadastrados com sucesso!</p>";
					$id = null;
					$nome = null;
					$nascimento = null;
					$cpf = null;
					$sexo = null;
					$rua = null;
					$numero = null;
					$bairro = null;
					$cidade = null;
					$complemento = null;
					
				} else {
					echo "<p class=\"bg-danger\">.         Erro ao tentar efetivar cadastro</p>";
				}
			} else {
				echo "<p class=\"bg-danger\">.         Erro: Não foi possível executar a declaração sql</p>";
			}
		} catch (PDOException $erro) {
			echo "<p class=\"bg-danger\">Erro: " . $erro->getMessage() . "</p>";
	}
}

// Bloco if que recupera as informações no formulário, etapa utilizada pelo Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM Prefeitura.Pessoa WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->id;
            $nome = $rs->nome;
            $nascimento = $rs->nascimento;
            $cpf = $rs->cpf;
            $sexo = $rs->sexo;
			$rua = $rs->rua;
			$numero = $rs->numero;
			$bairro = $rs->bairro;
			$cidade = $rs->cidade;
            $complemento = $rs ->complemento;
            
        } else {
            echo "<p class=\"bg-danger\">.         Erro: Não foi possível executar a declaração sql</p>";
        }
    } catch (PDOException $erro) {
        echo "<p class=\"bg-danger\">Erro: " . $erro->getMessage() . "</p>";
    }
}

// Bloco if utilizado pela etapa Delete
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    try {
        $stmt = $conexao->prepare("DELETE FROM Prefeitura.Pessoa WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "<p class=\"bg-success\">.         Registo foi excluído com êxito</p>";
            $id = null;
        } else {
            echo "<p class=\"bg-danger\">.         Erro: Não foi possível executar a declaração sql</p>";
        }
    } catch (PDOException $erro) {
        echo "<p class=\"bg-danger\">.         Erro: " . $erro->getMessage() . "</a>";
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
  
	<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
    </head>

    <body>
        <div class="container">
            <header class="row">
                <br />
            </header>
            <article>
                <div class="row">
                    <form action="?act=save" method="POST" name="form1" class="form-horizontal" >
                        <div class="panel panel-default">
                            <div class="panel-heading">
							    <h3>CADASTRO DE PESSOAS - Sistema de Atendimento ao Contribuinte da Prefeitura XYZ </h3>
                              <P>
								
								
                            </div>
                            <div class="panel-body">

                                <input type="hidden" name="id" value="<?php
                                 echo (isset($id) && ($id != null || $id != "")) ? $id : '';
					                                ?>" />
                                <div class="form-group">
                                    <label for="nome" class="col-sm-50 control-label">Nome:</label>
                                    <div class="col-md-100">
                                        <input type="text" name="nome" value="<?php
                                         echo (isset($nome) && ($nome != null || $nome != "")) ? $nome : '';
							 
                                       ?>" class="form-control"/>
                                    </div>
                                    <label for="nascimento" class="col-sm-50 control-label">Dt.Nascimento:</label>
                                    <div class="col-md-50">
                                        <input type="date" name="nascimento" value="<?php
                                        echo (isset($nascimento) && ($nascimento != null || $nascimento != "")) ? $nascimento : '';
                                        ?>" class="form-control" />
                                    </div>
                                </div>
				
                                <div class="form-group">
                                    <label for="cpf" class="col-sm-50 control-label">CPF:</label>
                                    <div class="col-md-50">
                                        <input type="text" name="cpf" value="<?php
                                         echo (isset($cpf) && ($cpf != null || $cpf != "")) ? $cpf : '';

                                        ?>" class="form-control" />
                                    </div>
                                    <label for="sexo" class="col-sm-50 control-label">Sexo:</label>
                                    <div class="col-md-50">
                                        <input type="text" name="sexo" value="<?php
                                        echo (isset($sexo) && ($sexo != null || $sexo != "")) ? $sexo : '';

                                        ?>" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="rua" class="col-sm-50 control-label">Rua:</label>
                                    <div class="col-md-50">
                                        <input type="text" name="rua" value="<?php
                                    
                                        echo (isset($rua) && ($rua != null || $rua != "")) ? $rua : '';

                                        ?>" class="form-control" />
                                    </div>
                                    <label for="numero" class="col-sm-50 control-label">Número:</label>
                                    <div class="col-md-50">
                                        <input type="text" name="numero" value="<?php
                                        echo (isset($numero) && ($numero != null || $numero != "")) ? $numero : '';

                                        ?>" class="form-control" />
                                    </div>
                                    <label for="bairro" class="col-sm-50 control-label">Bairro:</label>
                                    <div class="col-md-50">
                                        <input type="text" name="bairro" value="<?php
                                        echo (isset($bairro) && ($bairro != null || $bairro != "")) ? $bairro : '';

                                        ?>" class="form-control" />
                                    </div>
									      <label for="cidade" class="col-sm-50 control-label">Cidade:</label>
                                    <div class="col-md-50">
                                        <input type="text" name="cidade" value="<?php
                                        echo (isset($cidade) && ($cidade != null || $cidade != "")) ? $cidade : '';

                                        ?>" class="form-control" />
                                    </div>
                                </div>
                                </div>
                                    </div>
                                    <label for="complemento" class="col-sm-100 control-label">Complemento:</label>
                                    <div class="col-md-100">
                                        <input type="text" name="complemento" value="<?php
                                         echo (isset($complemento) && ($complemento != null || $complemento != "")) ? $complemento : '';
								
                                        ?>" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div class="clearfix">
                                    <div class="pull-right">
                                        <button type="submit" class="btn btn-primary" /><span class="glyphicon glyphicon-ok"></span> salvar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="panel panel-default">
                        <table class="table table-striped">
                            <thead>
                                <tr>
										<th>.          .</th>
								    <th>Nome </th>
                                    <th>Nascimento </th>
                                    <th>C.P.F.</th>
									<th>Sexo </th>
                                    <th>Rua </th>
                                    <th>Número </th>
                                    <th>Bairro </th>
                                    <th>Cidade </th>
                                    <th>Complemento </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                /**
                                 *  Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
                                 */
                                try {
                                    $stmt = $conexao->prepare("SELECT * FROM Prefeitura.Pessoa");
                                    if ($stmt->execute()) {
                                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {

                                            ?><tr>
													<th>.          .</th>
                                                <td><?php echo $rs->nome; ?></td>
                                                <td><?php echo $rs->nascimento; ?></td>
                                                <td><?php echo $rs->cpf; ?></td>
                                                <td><?php echo $rs->sexo; ?></td>
                                                <td><?php echo $rs->rua; ?></td>
                                                <td><?php echo $rs->numero; ?></td>
                                                <td><?php echo $rs->bairro; ?></td>
												<td><?php echo $rs->cidade; ?></td>
												<td><?php echo $rs->complemento; ?></td>
                                                <td><center>
                                            <a href="?act=upd&id=<?php echo $rs->id; ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span> Editar</a>
                                            <a href="?act=del&id=<?php echo $rs->id; ?>" class="btn btn-danger btn-xs" ><span class="glyphicon glyphicon-remove"></span> Excluir</a>
                                        </center>
                                        </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "Erro: Não foi possível recuperar os dados do banco de dados";
                                }
                            } catch (PDOException $erro) {
                                echo "Erro: " . $erro->getMessage();
                            }

                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>





