<?php
    include("header.php");

    $result = mysqli_query($conn, "SELECT 
    P.idPublicacao,
    P.usuario,
    P.texto,
    P.imagem,
    P.data,
    A.de,
    A.para,
    A.pedido

FROM
    publicacoes AS P,
    amizades AS A
WHERE
    P.usuario = A.de AND A.para = '$login_cookie' AND A.pedido ='aceito'
    OR P.usuario = A.para AND A.de = '$login_cookie' AND A.pedido ='aceito'
    OR P.usuario = '$login_cookie'
    order by P.idPublicacao DESC;");


    if (isset($_POST['publicar'])) {


        if ($_FILES["file"]["error"] > 0) {
            $texto = $_POST["texto"];
            $hoje = date("Y-m-d");

            if ($texto == "") {
                echo "<h3>O Texto não Pode Ficar em Branco na Publicação!</h3>";
            } else {
                $sql = $pdo->prepare("INSERT INTO publicacoes VALUES (null,?,?,?,?)");
                $sql->execute(array($login_cookie, $texto, "", $hoje));

                if ($sql) {
                    header("Location: ./");
                } else {
                    echo "Ocorreu um erro, tente novamente mais tarde!";
                }
            }
        } else {

            $n = rand(0, 1000000); // Gera um número entre 0 e 1000000.

            $img = $n . $_FILES["file"]["name"]; // Consulta 01
            move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $img);

            $texto = $_POST['texto'];
            $hoje =  date("Y-m-d");

            if ($texto == "") {
                echo "<h3>O Texto não Pode Ficar em Branco na Publicação!</h3>";
            } else {

                $sql = $pdo->prepare("INSERT INTO publicacoes VALUES (null,?,?,?,?)");
                $sql->execute(array($login_cookie, $texto, $img, $hoje));

                if ($sql) {
                    header("Location: ./");
                } else {
                    echo "Ocorreu um erro, tente novamente mais tarde!";
                }
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div id="publicar">
        <form method="POST" enctype="multipart/form-data">
            </br>
            <textarea placeholder="Escreva uma Nova Publicação" name="texto"></textarea>
            <label for="file-input">
                <img src="img/addfoto.svg" title="Inserir Foto" />
            </label>
            <input type="submit" value="Publicar" name="publicar" />

            <input type="file" id="file-input" name="file" hidden />
        </form>
    </div>

    <?php


    while ($pub = mysqli_fetch_assoc($result)) {
        $email = $pub['usuario'];
        $email_usuarioo = mysqli_query($conn, "SELECT * FROM usuarios WHERE email='$email' ");
        $email_usuario = mysqli_fetch_assoc($email_usuarioo);
        $nome = $email_usuario['nome'] . " " . $email_usuario['sobrenome'];
        $id = $pub['idPublicacao'];

        if ($pub['imagem'] == "") {
            echo "<div class='pub' id=' " . $id . " ''> 

                <p><a href='perfil.php?id=" . $email_usuario['idUsuario'] . "'>" . $nome . "</a> -  " . $pub['data'] . " </p>
                <span>" . $pub['texto'] .  "</span> <br/>

            </div> ";
        } else {
            echo "<div class='pub' id=' " . $id . " ''> 

                <p><a href='perfil.php?id=" . $email_usuario['idUsuario'] . "'>" . $nome . "</a> -  " . $pub['data'] . " </p>
                <span>" . $pub['texto'] .  "</span> <br/>
                <img src='upload/" . $pub['imagem'] . "' />

            </div> ";
        }
    }
    ?>
    <br />

    <div id="rodape">
        <p>&copy; ConnectingIFES, 2023 - Todos os direitos reservados</p>
    </div>
</body>

</html>