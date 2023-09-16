<?php
include("conexao.php");

if (isset($_POST['entrar'])) {
    $verifica = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND senha=?");
    $verifica->execute(array($_POST['email'], $_POST['senha']));

    if ($verifica->rowCount() <= 0) {     // Validação do usuário
        echo "<h3>Email ou Senha Incorretos!</h3>";
    } else {
        setcookie('login', $_POST['email']); // Usuário Logado!
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
    <link rel="stylesheet" href="style.css" />
    <title>Connecting IFES</title>
    <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <style type="text/css">
        img {
            display: block;
            margin: auto;
            margin-top: 2rem;
            width: 20rem;
        }

        form {
            text-align: center;
            margin-top: 2rem;
        }

        input[type="email"],
        [type="password"] {
            border: .1rem solid #CCC;
            width: 25rem;
            height: 2.5rem;
            padding-left: 1rem;
            border-radius: .3rem;
            margin-top: 2rem;
        }

        input[type="submit"] {
            border: none;
            width: 8rem;
            height: 2.5rem;
            border-radius: .3rem;
            margin-top: 2rem;
            background-color: #CCC;
        }

        input[type="submit"]:hover {
            background-color: #33ff00;
            color: cornsilk;
            cursor: pointer;
        }

        h2,
        h3 {
            text-align: center;
            margin-top: 2rem;
        }
    </style>
</head>

<body>
    <img src="img/logoIFES.svg">
    <h2> Login </h2>
    <form method="POST">
        <input type="email" placeholder="Endereço Email" name="email"><br />
        <input type="password" placeholder="Palavra-Passe" name="senha"><br />
        <input type="submit" value="Entrar" name="entrar">
    </form>
    <h3>Ainda não Possui Conta? <a href="registrar.php">Criar Conta</a></h3>
</body>

</html>