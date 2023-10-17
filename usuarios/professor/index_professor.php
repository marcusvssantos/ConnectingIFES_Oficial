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
            <h2 class="mb-4" style="color: #32A041;">Publicações</h2>
            <?php
            $conexao = conectarDB();
            $idUsuarioAtual = $f_tipo_de_usuario['idUsuario'];

            $query_professor_id = "SELECT idProfessor FROM professores WHERE idUsuario = '{$f_tipo_de_usuario['idUsuario']}'";
            $resultado_professor_id = $conexao->query($query_professor_id);
            $professor_data = $resultado_professor_id->fetch_assoc();
            $id_professor = $professor_data['idProfessor'];

            $sqlPublicacoes = "
                SELECT p.*, u.nome, u.sobrenome, u.fotoPerfil, g.nome AS nomeGrupo
                FROM connecting_ifes_oficial.publicacoes p 
                JOIN usuarios u ON p.idProfessor = u.idUsuario 
                JOIN connecting_ifes_oficial.publicacoesgrupos pg ON p.idPublicacao = pg.idPublicacao
                JOIN connecting_ifes_oficial.professoresgrupos profg ON pg.idGrupo = profg.idGrupo
                JOIN connecting_ifes_oficial.grupos g ON pg.idGrupo = g.idGrupo
                WHERE profg.idProfessor = ?
                ORDER BY p.dataPublicacao DESC";

            $stmt = $conexao->prepare($sqlPublicacoes);
            $stmt->bind_param("i", $id_professor);
            $stmt->execute();
            $resultadoPublicacoes = $stmt->get_result();

            if ($resultadoPublicacoes->num_rows > 0) {
                while ($publicacao = $resultadoPublicacoes->fetch_assoc()) {
                    echo "<div class='card publicacao-card'>";
                    echo "<div class='publicacao-card-header'>";
                    echo "<img src='" . $publicacao['fotoPerfil'] . "' alt='Foto de perfil'>";
                    echo "<h5>" . $publicacao['nome'] . " " . $publicacao['sobrenome'] . "</h5>";
                    echo "<span class='text-muted ml-auto'>" . "Postagem feita dia: " . date("d/m/Y", strtotime($publicacao['dataPublicacao'])) . "</span>";
                    echo "<span class='text-muted ml-auto'>" . "&nbsp às " . date("H:i", strtotime($publicacao['dataPublicacao'])) .  "</span>";

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

            $conexao->close();
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
    <title>Header Lateral IFSC</title>
</body>

</html>