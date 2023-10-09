<?php
include("header.php");

function conectarDB()
{
    $conexao = new mysqli("localhost", "root", "", "connecting_ifes_oficial");
    if ($conexao->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
    }
    return $conexao;
}

function realizarUploadImagem($imagem)
{
    $nome = $imagem["name"];
    $tempNome = $imagem["tmp_name"];
    $erro = $imagem["error"];
    if ($erro === 0) {
        $diretorioDestino = "uploads/publicações/";
        $nomeUnico = time() . '_' . $nome;
        $caminhoCompleto = $diretorioDestino . $nomeUnico;
        move_uploaded_file($tempNome, $caminhoCompleto);
        return $caminhoCompleto;
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["tituloPost"];
    $conteudo = $_POST["conteudoPost"];
    $dataPublicacao = date('Y-m-d H:i:s');
    $idProfessor = $f_tipo_de_usuario['idUsuario'];
    $idGrupo = $_POST['grupo'];
    $imagemPublicacao = realizarUploadImagem($_FILES["imagemPost"]);

    $conexao = conectarDB();

    // Insere a publicação na tabela "publicacoes"
    $sqlPublicacao = "INSERT INTO connecting_ifes_oficial.publicacoes (titulo, conteudo, imagemPublicacao, dataPublicacao, idProfessor) VALUES (?, ?, ?, ?, ?)";
    $stmtPublicacao = $conexao->prepare($sqlPublicacao);
    $stmtPublicacao->bind_param("ssssi", $titulo, $conteudo, $imagemPublicacao, $dataPublicacao, $idProfessor);
    if ($stmtPublicacao->execute()) {
        $idPublicacao = $stmtPublicacao->insert_id;
        $sqlPublicacaoGrupo = "INSERT INTO connecting_ifes_oficial.publicacoesgrupos (idPublicacao, idGrupo) VALUES (?, ?)";
        $stmtPublicacaoGrupo = $conexao->prepare($sqlPublicacaoGrupo);
        $stmtPublicacaoGrupo->bind_param("ii", $idPublicacao, $idGrupo);
        if ($stmtPublicacaoGrupo->execute()) {
            echo "Publicação realizada com sucesso!";
        } else {
            echo "Erro ao inserir na tabela publicacoesgrupos: " . $conexao->error;
        }
    } else {
        echo "Erro ao inserir na tabela publicacoes: " . $conexao->error;
    }

    $conexao->close();
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
            z-index: 1;
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
            /* Verde IFSC */
            flex-grow: 1;
        }

        .publicacao-card-content img {
            max-width: 100%;
            border-radius: 15px;
            margin-bottom: 10px;
        }

        .publicacao-card-description {
            color: #343a40;
            /* Cor de texto padrão do Bootstrap */
        }
    </style>
</head>

<body>
    <div class="main-content">
        <!-- Modal de Publicação -->
        <div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="postModalLabel">Adicionar Postagem</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="index_professor.php" method="post" enctype="multipart/form-data">
                            <!-- Título da postagem -->
                            <div class="form-group">
                                <label for="tituloPost">Título</label>
                                <input type="text" class="form-control" id="tituloPost" name="tituloPost" placeholder="Digite o título da postagem">
                            </div>

                            <!-- Conteúdo da postagem -->
                            <div class="form-group">
                                <label for="conteudoPost">Conteúdo</label>
                                <textarea class="form-control" id="conteudoPost" name="conteudoPost" rows="4" placeholder="Digite o conteúdo da postagem"></textarea>
                            </div>

                            <!-- Upload de imagem -->
                            <div class="form-group">
                                <label for="imagemPost">Imagem</label>
                                <input type="file" class="form-control" id="imagemPost" name="imagemPost">
                            </div>

                            <!-- Seleção do grupo -->
                            <div class="form-group">
                                <label for="grupo">Grupo</label>
                                <select class="form-control" id="grupo" name="grupo">
                                    <?php
                                    $query_professor_id = "SELECT idProfessor FROM professores WHERE idUsuario = '{$f_tipo_de_usuario['idUsuario']}'";
                                    $resultado_professor_id = mysqli_query($conn, $query_professor_id);
                                    if (!$resultado_professor_id) {
                                        die("Erro na consulta: " . mysqli_error($conn));
                                    }
                                    $professor_data = mysqli_fetch_assoc($resultado_professor_id);
                                    $id_professor = $professor_data['idProfessor'];

                                    $query_grupos = mysqli_query($conn, "SELECT g.idGrupo, g.nome FROM professoresgrupos pg JOIN grupos g ON pg.idGrupo = g.idGrupo WHERE pg.idProfessor = '$id_professor'");

                                    // Preencher o <select> com os grupos
                                    while ($grupo = mysqli_fetch_assoc($query_grupos)) {
                                        echo "<option value='{$grupo['idGrupo']}'>{$grupo['nome']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary">Publicar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



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
                    echo "<h5>" . $publicacao['nome'] . " " . $publicacao['sobrenome'] ."</h5>";
                    echo "<span class='text-muted ml-auto'>" . "Postagem feita dia: " . date("d/m/Y", strtotime($publicacao['dataPublicacao'])) . "</span>";
                    echo "<span class='text-muted ml-auto'>" . "&nbsp às " . date("H:i", strtotime($publicacao['dataPublicacao'])) .  "</span>";

                    echo "</div>";
                    echo "<div class='publicacao-card-content'>";
                    echo "<p><strong>Grupo:</strong> " . $publicacao['nomeGrupo'] . "</p>"; 
                    echo "<img src='" . $publicacao['imagemPublicacao'] . "' alt='Imagem da postagem'>";
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