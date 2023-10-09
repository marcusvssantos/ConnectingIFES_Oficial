<?php
include("header.php");
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
            background-color: #FFFFFF;
            /* Branco */
        }

        .post {
            border: 1px solid #E0191E;
            /* Vermelho IFSC */
            margin: 20px;
            padding: 20px;
            border-radius: 10px;
            background-color: #FFFFFF;
            /* Branco */
        }

        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .post-header img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .post-header h5 {
            margin: 0;
            color: #32A041;
            /* Verde IFSC */
        }

        .post-content img {
            max-width: 100%;
            border-radius: 10px;
        }

        .post-description {
            margin-top: 10px;
            color: #000000;
            /* Preto */
        }
    </style>
</head>

<body>

    <div class="main-content">

        <div class="post">
            <div class="post-header">
                <img src="path/to/profile/image.jpg" alt="Foto de perfil">
                <h5>Nome do Usuário</h5>
            </div>
            <div class="post-content">
                <img src="path/to/post/image.jpg" alt="Imagem da postagem">
            </div>
            <div class="post-description">
                <p>Esta é a legenda da postagem. Aqui você pode escrever o que quiser sobre a imagem.</p>
            </div>
        </div>
    </div>



    <script src="../../bootstrap/js/bootstrap.min.js"></script>
    <title>Header Lateral IFSC</title>
</body>

</html>