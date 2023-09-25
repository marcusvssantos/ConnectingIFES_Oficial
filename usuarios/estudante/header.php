<?php
include ("../../conexao.php");
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

if ($f_tipo_de_usuario['tipo'] !== "Aluno") {
    echo '<script>window.history.back();</script>';
}