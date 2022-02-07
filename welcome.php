<?php
// Inicialize a sessão
session_start();
 
// Verifique se o usuário está logado, se não, redirecione-o para uma página de login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Bem vindo!</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <h1 class="my-5">Oi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?>
	<p>
	</b> Bem vindo ao sistema de Atendimentos do Setor de Protocolo da Prefeitura XYZ! </h1>
    <p>
	</h1> Escolha abaixo a opção desejada: </h1>
    <p>
        <a href="pessoa.php" class="btn btn-warning">Cadastro de Pessoas</a>
	    <a href="protocolo.php" class="btn btn-warning">Cadastro de Protocolo</a>
        <a href="logout.php" class="btn btn-danger ml-3">Sair/Logoff</a>
    </p>
</body>
</html>