<?php
include("header.php");
$professor = $_GET["id"];

$perfil_professor = mysqli_query($conn, "SELECT * FROM usuarios WHERE idUsuario = '$professor'");
$f_perfil_professor = mysqli_fetch_assoc($perfil_professor);

?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="img\Logo ConnectingIFES.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFFFFF;
            /* Branco */
        }

        .publicacao-card {
            border: none;
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 15px;
            background-color: #f8f9fa;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            z-index: 2;
        }

        .publicacao-card:hover {
            transform: translateY(-10px);
        }

        .publicacao-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .publicacao-card-header img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .publicacao-card-header h5 {
            margin: 0;
            color: #32A041;
            flex-grow: 1;
        }

        .publicacao-card-content img {
            max-width: 100%;
            border-radius: 15px;
            margin-bottom: 10px;
        }

        .publicacao-card-description {
            color: #343a40;
        }

    
        .publicacao-card-content img {
            width: 100%;
            max-width: 600px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    
<div class="main-content">
        <div class="container mt-5">
            <h2 class="mb-4" style="color: #32A041;">Publicações do professor <?php echo $f_perfil_professor['nome'] . " " . $f_perfil_professor['sobrenome']; ?></h2>
            <?php
            $idUsuarioAtual = $f_tipo_de_usuario['idUsuario'];

            $sqlPublicacoes = "
            SELECT p.*, u.nome, u.sobrenome, u.fotoPerfil, g.nome AS nomeGrupo, g.idGrupo
            FROM publicacoes p 
            JOIN usuarios u ON p.idProfessor = u.idUsuario 
            JOIN publicacoesgrupos pg ON p.idPublicacao = pg.idPublicacao
            JOIN grupos g ON pg.idGrupo = g.idGrupo
            WHERE p.idProfessor = :idProfessor
            ORDER BY p.dataPublicacao DESC";

            $stmt = $pdo->prepare($sqlPublicacoes);
            $stmt->bindParam(':idProfessor', $professor);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($publicacao = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='card publicacao-card'>";
                    echo "<div class='publicacao-card-header'>";
                    echo "<img src='" . $publicacao['fotoPerfil'] . "' alt='Foto de perfil'>";
                    echo "<h5>" . $publicacao['nome'] . " " . $publicacao['sobrenome'] . "</h5>";

                    // Início da div que envolve o botão e as datas
                    echo "<div style='display: flex; flex-direction: column; align-items: flex-end;'>";
                    echo "<span class='text-muted'>" . "Postagem feita dia: " . date("d/m/Y", strtotime($publicacao['dataPublicacao'])) . "</span>";
                    echo "<span class='text-muted'>" . "&nbsp às " . date("H:i", strtotime($publicacao['dataPublicacao'])) .  "</span>";
                    echo "</div>"; // Fim da div que envolve o botão e as datas

                    echo "</div>";
                    echo "<div class='publicacao-card-content'>";
                    echo "<p><strong>Grupo:</strong> " . $publicacao['nomeGrupo'] . "</p>";
                    echo "<h3 style='text-align:center;'> " . $publicacao['titulo'] . "</h3>";
                    if (!empty($publicacao['imagemPublicacao'])) {
                        echo "<img src='" . $publicacao['imagemPublicacao'] . "' alt='Imagem da postagem'>";
                    }
                    echo "</div>";
                    echo "<div class='card-body publicacao-card-description'>";
                    echo "<p>" . $publicacao['conteudo'] . "</p>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>Nenhuma publicação encontrada.</p>";
            }
            ?>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
    <title>Meu Perfil</title>
</body>

</html>