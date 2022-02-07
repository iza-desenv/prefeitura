<?php
/*
CRUD completo do Cadastro de Protocolo
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
    $id = filter_input(INPUT_POST, 'id');
    $numero = filter_input(INPUT_POST, 'numero');
    $descricao = filter_input(INPUT_POST, 'descricao');
	$dataprot = filter_input(INPUT_POST, 'dataprot');
    $prazo = filter_input(INPUT_POST, 'prazo');
    $pessoa = filter_input(INPUT_POST, 'pessoa');
    	
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
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $numero != "") {
    try {
			// Validando os campos
		if(empty($numero) || empty($descricao) || empty($dataprot) || empty($prazo) || empty($pessoa)) {	
			
			if(empty($numero)) {
				echo "<font color='red'>Número do Protocolo deve ser informado!</font><br/>";
			}
			
			if(empty($descricao)) {
				echo "<font color='red'>Descrição deve ser informada!</font><br/>";
			}
			
			if(empty($dataprot)) {
				echo "<font color='red'>Data do protocolo deve ser informada!</font><br/>";
			}
			if(empty($prazo)) {
				echo "<font color='red'>Prazo deve ser informado!</font><br/>";
			}
			if(empty($pessoa)) {
				echo "<font color='red'>Pessoa deve ser informada!</font><br/>";
			}
		} else {
		} 
	
        if ($id != "") {
			$stmt = $conexao->prepare("UPDATE Prefeitura.Protocolo SET numero=?, descricao=?, dataprot=?, prazo=?, pessoa=? WHERE id = ?"); 
	     	$stmt->bindParam(6, $id);
        } else {
						
          $stmt = $conexao->prepare("INSERT INTO Prefeitura.Protocolo (numero, descricao, dataprot, prazo, pessoa) VALUES (?, ?, ?, ?, ?)");
        }
        $stmt->bindParam(1, $numero);
        $stmt->bindParam(2, $descricao);
		$stmt->bindParam(3, $dataprot);
        $stmt->bindParam(4, $prazo);
        $stmt->bindParam(5, $pessoa);
	        
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "<p class=\"bg-success\">.                              Dados cadastrados com sucesso!</p>"; 
                $id = null;
                $numero = null;
                $descricao = null;
                $dataprot = null;
                $prazo = null;
				$pessoa = null;
				                
            } else {
                echo "<p class=\"bg-danger\">  Erro ao tentar efetivar cadastro</p>";
            }
        } else {
            echo "<p class=\"bg-danger\">  Erro: Não foi possível executar a declaração sql</p>";
        }
    } catch (PDOException $erro) {
        echo "<p class=\"bg-danger\">Erro: " . $erro->getMessage() . "</p>";
	}	
}

// Bloco if que recupera as informações no formulário, etapa utilizada pelo Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM Prefeitura.Protocolo WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->id;
            $numero = $rs->numero;
            $descricao = $rs->descricao;
			$dataprot = $rs->dataprot;
			$prazo = $rs->prazo;
			$pessoa = $rs->pessoa;
			            
        } else {
            echo "<p class=\"bg-danger\">Erro: Não foi possível executar a declaração sql</p>";
        }
    } catch (PDOException $erro) {
        echo "<p class=\"bg-danger\">Erro: " . $erro->getMessage() . "</p>";
    }
}

// Bloco if utilizado pela etapa Delete
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    try {
        $stmt = $conexao->prepare("DELETE FROM Prefeitura.Protocolo WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "<p class=\"bg-success\">.           Registo foi excluído com êxito!</p>";
            $id = null;
        } else {
            echo "<p class=\"bg-danger\">Erro: Não foi possível executar a declaração sql</p>";
        }
    } catch (PDOException $erro) {
        echo "<p class=\"bg-danger\">Erro: " . $erro->getMessage() . "</a>";
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
                            <h3>CADASTRO DE PROTOCOLOS <h3>
							<h3> Sistema de Atendimento ao Contribuinte da Prefeitura XYZ  <h3>
							<P>
								
								
                            </div>
                            <div class="panel-body">

                                <input type="hidden" name="id" value="<?php
                                                             echo (isset($id) && ($id != null || $id != "")) ? $id : '';

                                ?>" />
                                <div class="form-group">
                                    <label for="numero" class="col-sm-50 control-label">Número:</label>
                                    <div class="col-md-100">
                                        <input type="int" name="numero" value="<?php
                                                                              echo (isset($numero) && ($numero != 0 || $numero != "")) ? $numero : '';

                                        ?>" class="form-control"/>
                                    </div>
                                    <label for="descricao" class="col-sm-50 control-label">Descrição do Protocolo:</label>
                                    <div class="col-md-200">
                                        <input type="text" name="descricao" value="<?php
                                        echo (isset($descricao) && ($descricao != null || $descricao != "")) ? $descricao : '';
                                        ?>" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dataprot" class="col-sm-50 control-label">Data do Protocolo:</label>
                                    <div class="col-md-50">
									
									
									<input type="date" name="dataprot" value="<?php 
									echo (isset($dataprot) && ($dataprot != null || $dataprot != "")) ? $dataprot : '';
                                    ?>" class="form-control" />
									
									 	
																		
										
                                    </div>
                                    <label for="prazo" class="col-sm-50 control-label">Prazo:</label>
                                    <div class="col-md-50">
                                        <input type="text" name="prazo" value="<?php
                                        echo (isset($prazo) && ($prazo != null || $prazo != "")) ? $prazo : '';

                                        ?>" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pessoa" class="col-sm-50 control-label">Pessoa:</label>
                                    <div class="col-md-50">
                                        <input type="text" name="pessoa" value="<?php
                                    
                                        echo (isset($pessoa) && ($pessoa != null || $pessoa != "")) ? $pessoa : '';

                                        ?>" class="form-control" />
                                  </div>
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
								    <th>'     '</th>
                                    <th>Número do Protocolo </th>
                                    <th>Descrição do Protocolo</th>
                                    <th>Data Protocolada</th>
									<th>Prazo </th>
                                    <th>Pessoa </th>
                                
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                /**
                                 *  Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
                                 */
                                try {
                                    $stmt = $conexao->prepare("SELECT * FROM Prefeitura.Protocolo");
                                    if ($stmt->execute()) {
                                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {

                                            ?><tr>
												<th>'     '</th>
                                                <td><?php echo $rs->numero; ?></td>
                                                <td><?php echo $rs->descricao; ?></td>
                                                <td><?php echo $rs->dataprot; ?></td>
                                                <td><?php echo $rs->prazo; ?></td>
                                                <td><?php echo $rs->pessoa; ?></td>
                                              
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
        </div>
    </body>