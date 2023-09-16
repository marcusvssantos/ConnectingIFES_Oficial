<?php
include("conexao.php");


$login_cookie = $_COOKIE['login'];
if (!isset($login_cookie)) { //Verificação de Login do Usuário
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">




<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            background: #F6F6F6;
        }
    </style>
</head>

<body>
    <!-- Início Menu Superior -->
    <div id="topo">
        <a href="index.php"><img src="img/logoIFES.svg" width="90" name="logo"></a>
        <form method="GET" action="pesquisa.php">
            <input type="text" placeholder="Pesquisar Usuário" name="query" autocomplete="off"><input type="submit" hidden>
        </form>
        <a href="inbox.php"><img src="img/chat.svg" width="20" name="menu"></a>
        <a href="solicitacoes.php"><img src="img/solicitacoes.svg" width="20" name="menu"></a>
        <a href="meuperfil.php"><img src="img/perfil.svg" width="20" name="menu"></a>
    </div>
    <!-- Fim Menu Superior -->

</body>

</html>