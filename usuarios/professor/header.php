<?php
include("../../conexao.php");

session_start();

if ((!isset($_SESSION['login']) == true) and (!isset($_SESSION['senha']) == true)) {
  header('location: ../../index.php');
}




if (isset($_POST['sair'])) {
  unset($_SESSION['login']);
  unset($_SESSION['senha']);
}


$logado = $_SESSION['login'];


$tipo_de_usuario = mysqli_query($conn, "SELECT * FROM usuarios WHERE email = '$logado'");
$f_tipo_de_usuario = mysqli_fetch_assoc($tipo_de_usuario);




if ($f_tipo_de_usuario['tipo'] !== "professor") {
  echo '<script>window.history.back();</script>';
}

?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    /* Estilos para o menu da esquerda */
    .sidebar {
      height: 100vh;
      width: 80px;
      background: linear-gradient(to bottom, rgba(70, 180, 85, 1) 0%, rgba(50, 160, 65, 1) 25%, rgba(30, 140, 45, 1) 75%);
      position: fixed;
      top: 0;
      left: 0;
      overflow-x: hidden;
      transition: 0.5s;
      padding-top: 20px;
      margin-top: 60px;
    }

    .sidebar a {
      padding: 10px 15px 10px 30px;
      text-decoration: none;
      font-size: 25px;
      color: white;
      display: block;
      transition: 0.3s;
    }

    .sidebar a:hover {
      color: #E0191E;
    }

    .sidebar-right {
      height: 100vh;
      width: 150px;
      background: linear-gradient(to bottom, rgba(70, 180, 85, 1) 0%, rgba(50, 160, 65, 1) 25%, rgba(30, 140, 45, 1) 75%);
      position: fixed;
      top: 0;
      right: 0;
      overflow-x: hidden;
      transition: 0.5s;
      padding-top: 20px;
      border-left: 1px solid #32A041;
      margin-top: 60px;
    }

    .sidebar-right a {
      padding: 10px 15px 10px 30px;
      text-decoration: none;
      font-size: 25px;
      color: white;
      display: block;
      transition: 0.3s;
    }

    .sidebar-right a:hover {
      color: #E0191E;
    }

    .sidebar-right::-webkit-scrollbar {
      width: 10px;
    }

    .sidebar-right::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }

    .sidebar-right::-webkit-scrollbar-thumb {
      background: #32A041;
      border-radius: 10px;
    }

    .sidebar-right::-webkit-scrollbar-thumb:hover {
      background: #E0191E;
    }

    .sidebar a:hover {
      color: #E0191E;
    }

    .sidebar::-webkit-scrollbar {
      width: 10px;
    }

    .sidebar::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }

    .sidebar::-webkit-scrollbar-thumb {
      background: #32A041;
      border-radius: 10px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
      background: #E0191E;
    }

    .topbar {
      width: 100%;
      height: 60px;
      background: linear-gradient(to bottom, rgba(70, 180, 85, 1) 0%, rgba(50, 160, 65, 1) 25%, rgba(30, 140, 45, 1) 75%);
      position: fixed;
      top: 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      z-index: 1000;
    }

    .topbar a {
      color: #FFFFFF;
      text-decoration: none;
      font-size: 16px;
      font-weight: bold;
      padding: 2px 5px;
    }

    .topbar img {
      border: 1px solid #000000;
      background: #FFFFFF;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      margin-right: 10px;
    }


    .main-content {
      margin-left: 85px;
      margin-right: 155px;
      margin-top: 55px;
      padding: 20px;
      background-color: #FFFFFF;
      /* Branco */
    }

    .friend-item {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .friend-item img {
      border: 1px solid #000000;
      background: #FFFFFF;
      border-radius: 50%;
      width: 25px;
      margin-right: 10px;

    }

    .friend-item a {
      color: #FFFFFF;
      text-decoration: none;
      font-size: 16px;
      font-weight: bold;
      padding: 2px 5px;
    }

    .friend-item:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }
  </style>
</head>

<body>
  <div class="topbar">
    <div class="left-items">
      <a><img src="<?php echo $f_tipo_de_usuario["fotoPerfil"]; ?>"></a>
      <a style="color: #FFFFFF;"> <?php echo $f_tipo_de_usuario["nome"] . " " . $f_tipo_de_usuario["sobrenome"] ; ?> </a>
    </div>
    <div class="center-items">
      <a style="color: #FFFFFF;"> Área do Professor </a>
    </div>
    <div class="right-items">
      <a style="color: #FFFFFF;"> ConnectingIFES </a>
    </div>


  </div>
  <div class="sidebar">
    <a href="#home"><i class="bi bi-house-door"></i></a>
    <a href="#services"><i class="bi bi-gear"></i></a>
    <a href="#clients"><i class="bi bi-people"></i></a>
    <a href="#contact"><i class="bi bi-envelope"></i></a>
    <a href="#contact" data-toggle="modal" data-target="#postModal" title="Adicionar Nova Publicação"><i class="bi bi-file-earmark-plus"></i></a>
  </div>


  <div class="sidebar-right">
    <div class="friend-item">
      <a><i style="color: #FFFFFF;" class="bi bi-chat-dots"></i></a>
      <a style="color: #FFFFFF;"> Conversas </a>
    </div>
    <div class="friend-item">
      <a href="#friend1"><img src="../../img/ifes-vertical-cor.png" alt="Amigo 1"></a>
      <a href="#friend1" style="color: #FFFFFF;">João</a>
    </div>
    <div class="friend-item">
      <a href="#friend1"><img src="../../img/ifes-vertical-cor.png" alt="Amigo 1"></a>
      <a href="#friend1" style="color: #FFFFFF;">Pedro</a>
    </div>
    <div class="friend-item">
      <a href="#friend1"><img src="../../img/ifes-vertical-cor.png" alt="Amigo 1"></a>
      <a href="#friend1" style="color: #FFFFFF;">Lucas</a>
    </div>
    <div class="friend-item">
      <a href="#friend1"><img src="../../img/ifes-vertical-cor.png" alt="Amigo 1"></a>
      <a href="#friend1" style="color: #FFFFFF;">João</a>
    </div>
    <div class="friend-item">
      <a href="#friend1"><img src="../../img/ifes-vertical-cor.png" alt="Amigo 1"></a>
      <a href="#friend1" style="color: #FFFFFF;">Pedro</a>
    </div>
    <div class="friend-item">
      <a href="#friend1"><img src="../../img/ifes-vertical-cor.png" alt="Amigo 1"></a>
      <a href="#friend1" style="color: #FFFFFF;">Lucas</a>
    </div>
    <div class="friend-item">
      <a href="#friend1"><img src="../../img/ifes-vertical-cor.png" alt="Amigo 1"></a>
      <a href="#friend1" style="color: #FFFFFF;">João</a>
    </div>
    <div class="friend-item">
      <a href="#friend1"><img src="../../img/ifes-vertical-cor.png" alt="Amigo 1"></a>
      <a href="#friend1" style="color: #FFFFFF;">Pedro</a>
    </div>
    <div class="friend-item">
      <a href="#friend1"><img src="../../img/ifes-vertical-cor.png" alt="Amigo 1"></a>
      <a href="#friend1" style="color: #FFFFFF;">Lucas</a>
    </div>
    <div class="friend-item">
      <a href="#friend1"><img src="../../img/ifes-vertical-cor.png" alt="Amigo 1"></a>
      <a href="#friend1" style="color: #FFFFFF;">João</a>
    </div>
    <div class="friend-item">
      <a href="#friend1"><img src="../../img/ifes-vertical-cor.png" alt="Amigo 1"></a>
      <a href="#friend1" style="color: #FFFFFF;">Pedro</a>
    </div>
    <div class="friend-item">
      <a href="#friend1"><img src="../../img/ifes-vertical-cor.png" alt="Amigo 1"></a>
      <a href="#friend1" style="color: #FFFFFF;">Lucas</a>
    </div>
  </div>




  <script src="../../bootstrap/js/bootstrap.min.js"></script>
</body>

</html>