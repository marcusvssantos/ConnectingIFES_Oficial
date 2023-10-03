<?php
include("conexao.php");
session_start();
/*if (isset($_POST['entrar'])) {
    $login = $_POST['email'];
    $senha = $_POST['senha'];

    $login =  mysqli_query($conn, "SELECT * FROM usuarios WHERE email = '$login' AND senha = '$senha'");
    $pegaLogin = mysqli_fetch_assoc($login);


    if (mysqli_num_rows($login)<=0) {
        unset ($_SESSION['idUsuario']);
        unset ($_SESSION['senha']);     
        echo"<script language='javascript' type='text/javascript'>
        alert('Login e/ou senha incorretos');window.location
        .href='login.php';</script>";
    } else {
        $_SESSION['email'] = $login;
        $_SESSION['senha'] = $senha;
        header("location: ./");
    }
} */


$login = $_POST['login'];
$senha = $_POST['senha'];



$result =  mysqli_query($conn, "SELECT * FROM usuarios WHERE email = '$login' AND senha = '$senha'");

if (mysqli_num_rows($result) > 0) {
    $_SESSION['login'] = $login;
    $_SESSION['senha'] = $senha;

    $tipo_de_usuario = mysqli_query($conn, "SELECT * FROM usuarios WHERE email = '$login' AND senha = '$senha'");
    $f_tipo_de_usuario = mysqli_fetch_assoc($tipo_de_usuario);

    if ($f_tipo_de_usuario['tipo'] == "estudante") {
        header("Location: usuarios/estudante/estudante.php");
        exit();
    } elseif ($f_tipo_de_usuario['tipo'] == "professor") {
        header("Location: usuarios/professor/index_professor.php");
        exit();
    } elseif ($f_tipo_de_usuario['tipo'] == "admin") {
        header("Location: usuarios/admin/index_administrador.php");
        exit();
    }
} else {
    unset($_SESSION['login']);
    unset($_SESSION['senha']);
    header('location:index.php');
}
