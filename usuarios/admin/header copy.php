<?php
include("../../conexao.php");
session_start();

if((!isset ($_SESSION['login']) == true) and (!isset ($_SESSION['senha']) == true))
{
  header('location: ../../index.php');
}



if (isset($_POST['sair'])) {
    unset($_SESSION['login']);
    unset($_SESSION['senha']);
}


$logado = $_SESSION['login'];

$tipo_de_usuario = mysqli_query($conn, "SELECT * FROM usuarios WHERE email = '$logado'");
$f_tipo_de_usuario = mysqli_fetch_assoc($tipo_de_usuario);

if ($f_tipo_de_usuario['tipo'] !== "Administrador") {
    echo '<script>window.history.back();</script>';
}

?>

<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
    <style>
        .nav-pills .nav-link {
            color: white;
        }


        .nav-pills .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.7);
            color: white;
        }

        .nav-pills .active {
            background-color: rgba(144, 238, 144, 0.7) ! important;
            
        }
        
    </style>
</head>

<body>

    <header class="d-flex justify-content py-3 bg-success">
        <ul class="nav nav-pills">
            <li class="nav-item"><a href="admin.php" class="nav-link active" aria-current="page">Painel de Usuários</a></li>
            <form class="nav navbar-nav navbar-right" method="POST">
            <button type="submit" class="btn btn-danger" name="sair">Sair</button>
        </form>
        </ul>
        
        
    </header>
        

</body>

</html>