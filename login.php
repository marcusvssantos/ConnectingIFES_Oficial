<?php
include("conexao.php");




if (isset($_POST['entrar'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $login =  mysqli_query($conn, "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'");
    $pegaLogin = mysqli_fetch_assoc($login);


    if (mysqli_num_rows($login)<=0) {     
        echo"<script language='javascript' type='text/javascript'>
        alert('Login e/ou senha incorretos');window.location
        .href='login.php';</script>";
    } else {
        setcookie('login', $pegaLogin['idUsuario']); // Usuário Logado!
        header("location: ./");
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connecting IFES</title>
    <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
</head>

<body>
    <img src="img/logoIFES.svg">
    <h2> Login </h2>
    <form method="POST" action="login.php">
        <input type="email" placeholder="Email" name="email"><br />
        <input type="password" placeholder="Senha" name="senha"><br />
        <input type="submit" value="Entrar" name="entrar">
    </form>
    <h3>Ainda não Possui Conta? <a href="registrar.php">Criar Conta</a></h3>
</body>

</html>