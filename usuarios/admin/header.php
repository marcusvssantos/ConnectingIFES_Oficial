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

if ($f_tipo_de_usuario['tipo'] !== "admin") {
    echo '<script>window.history.back();</script>';
}

?>

<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="../../img/Logo ConnectingIFES.png">
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
    <style>
        .nav-pills .nav-link {
            color: white;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }

        .nav-pills .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.7);
            color: white;
        }

        .nav-pills .active {
            background-color: rgba(144, 238, 144, 0.7) !important;
        }

        header {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-danger {
            margin-left: 20px;
        }
    </style>
</head>

<body>

    <header class="d-flex align-items-center py-3 bg-success">
        <div class="d-flex justify-content-center align-items-center flex-grow-1">
            <ul class="nav nav-pills">
                <li class="nav-item"><a href="index_administrador.php" class="nav-link" aria-current="page">Página Inicial</a></li>
                <li class="nav-item"><a href="gerenciar_administrador.php" class="nav-link" aria-current="page">Administradores</a></li>
                <li class="nav-item"><a href="gerenciar_professor.php" class="nav-link" aria-current="page">Professores</a></li>
                <li class="nav-item"><a href="gerenciar_estudante.php" class="nav-link" aria-current="page">Estudantes</a></li>
                <li class="nav-item"><a href="gerenciar_grupos.php" class="nav-link" aria-current="page">Grupos</a></li>
            </ul>
        </div>
        <form class="nav navbar-nav navbar-right" method="POST">
            <button type="submit" class="btn btn-danger" name="sair">Sair</button>
        </form>
    </header>

</body>

</html>