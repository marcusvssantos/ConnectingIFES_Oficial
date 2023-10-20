<?php
include("header.php");

if (isset($_POST['delete_publicacao'])) {
    $idPublicacao = $_POST['idPublicacao'];

    // 1. Selecione o caminho da imagem da publicação
    $sql = "SELECT imagemPublicacao FROM publicacoes WHERE idPublicacao = :idPublicacao";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idPublicacao', $idPublicacao);
    $stmt->execute();
    $publicacao = $stmt->fetch(PDO::FETCH_ASSOC);
    $caminhoImagem = $publicacao['imagemPublicacao'];

    // 2. Exclua o registro da tabela publicacoesgrupos
    $sql = "DELETE FROM publicacoesgrupos WHERE idPublicacao = :idPublicacao";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idPublicacao', $idPublicacao);
    $stmt->execute();

    // 3. Exclua o registro da tabela publicacoes
    $sql = "DELETE FROM publicacoes WHERE idPublicacao = :idPublicacao";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idPublicacao', $idPublicacao);
    $stmt->execute();

    if (file_exists($caminhoImagem)) {
        unlink($caminhoImagem);
    }
}

if (isset($_POST['edit_publicacao'])) {
    $idPublicacao = $_POST['editIdPublicacao'];
    $titulo = $_POST['editTituloPost'];
    $conteudo = $_POST['editConteudoPost'];
    $novoGrupo = $_POST['editGrupo'];

    // Obtenha o caminho da imagem atual
    $sqlImg = "SELECT imagemPublicacao FROM publicacoes WHERE idPublicacao = :idPublicacao";
    $stmtImg = $pdo->prepare($sqlImg);
    $stmtImg->bindParam(':idPublicacao', $idPublicacao);
    $stmtImg->execute();
    $imagemAtual = $stmtImg->fetch(PDO::FETCH_ASSOC)['imagemPublicacao'];

    // Se um arquivo de imagem foi enviado, processe-o e atualize o caminho da imagem
    if (isset($_FILES['editImagemPost']) && $_FILES['editImagemPost']['error'] == 0) {
        // Exclua a imagem antiga do servidor
        if (file_exists($imagemAtual)) {
            unlink($imagemAtual);
        }

        $caminhoImagem = "../../uploads/publicações/" . $_FILES['editImagemPost']['name'];
        move_uploaded_file($_FILES['editImagemPost']['tmp_name'], $caminhoImagem);
        $sql = "UPDATE publicacoes SET titulo = :titulo, conteudo = :conteudo, imagemPublicacao = :imagemPublicacao WHERE idPublicacao = :idPublicacao";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':conteudo', $conteudo);
        $stmt->bindParam(':imagemPublicacao', $caminhoImagem);
        $stmt->bindParam(':idPublicacao', $idPublicacao);
        $stmt->execute();
    } else {
        // Se nenhum arquivo de imagem foi enviado, apenas atualize o título e o conteúdo
        $sql = "UPDATE publicacoes SET titulo = :titulo, conteudo = :conteudo WHERE idPublicacao = :idPublicacao";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':conteudo', $conteudo);
        $stmt->bindParam(':idPublicacao', $idPublicacao);
        $stmt->execute();
    }

    // Atualizar a tabela publicacoesgrupos se necessário
    $sqlGrupo = "SELECT idGrupo FROM publicacoesgrupos WHERE idPublicacao = :idPublicacao";
    $stmtGrupo = $pdo->prepare($sqlGrupo);
    $stmtGrupo->bindParam(':idPublicacao', $idPublicacao);
    $stmtGrupo->execute();
    $grupoAtual = $stmtGrupo->fetch(PDO::FETCH_ASSOC)['idGrupo'];

    if ($grupoAtual != $novoGrupo) {
        $sqlUpdateGrupo = "UPDATE publicacoesgrupos SET idGrupo = :novoGrupo WHERE idPublicacao = :idPublicacao";
        $stmtUpdateGrupo = $pdo->prepare($sqlUpdateGrupo);
        $stmtUpdateGrupo->bindParam(':novoGrupo', $novoGrupo);
        $stmtUpdateGrupo->bindParam(':idPublicacao', $idPublicacao);
        $stmtUpdateGrupo->execute();
    }
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editButtons = document.querySelectorAll('.editBtn');
            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var idPublicacao = this.getAttribute('data-idpublicacao');
                    var titulo = this.getAttribute('data-titulo');
                    var conteudo = this.getAttribute('data-conteudo');
                    var grupo = this.getAttribute('data-grupo');

                    document.getElementById('editIdPublicacao').value = idPublicacao;
                    document.getElementById('editTituloPost').value = titulo;
                    document.getElementById('editConteudoPost').value = conteudo;
                    document.getElementById('editGrupo').value = grupo;
                });
            });
        });
    </script>
</head>

<body>
    <div class="main-content">
        <div class="container mt-5">
            <h2 class="mb-4" style="color: #32A041;">Minhas Publicações</h2>
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
            $stmt->bindParam(':idProfessor', $idUsuarioAtual);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($publicacao = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='card publicacao-card'>";
                    echo "<div class='publicacao-card-header'>";
                    echo "<img src='" . $publicacao['fotoPerfil'] . "' alt='Foto de perfil'>";
                    echo "<h5>" . $publicacao['nome'] . " " . $publicacao['sobrenome'] . "</h5>";

                    // Início da div que envolve o botão e as datas
                    echo "<div style='display: flex; flex-direction: column; align-items: flex-end;'>";
                    echo "<form method='POST' action=''>";
                    echo "<input type='hidden' name='idPublicacao' value='" . $publicacao['idPublicacao'] . "'>";
                    echo "<button type='button' data-toggle='modal' data-target='#editPostModal' 
                    data-idpublicacao='" . $publicacao['idPublicacao'] . "' 
                    data-titulo='" . $publicacao['titulo'] . "' 
                    data-conteudo='" . $publicacao['conteudo'] . "' 
                    data-grupo='" . $publicacao['idGrupo'] . "' 
                    class='btn btn-success btn-sm mb-2 editBtn'>
                    <i class='bi bi-pencil'></i>
                    </button>&nbsp";
                    echo "<button type='submit' name='delete_publicacao' class='btn btn-danger btn-sm mb-2'><i class='bi bi-trash'></i></button>"; // Botão de apagar
                    echo "</form>";
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

    <div class="modal fade" id="editPostModal" tabindex="-1" role="dialog" aria-labelledby="editPostModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPostModalLabel">Editar Postagem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="meu_perfil.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="editTituloPost">Título</label>
                            <input type="text" class="form-control" id="editTituloPost" name="editTituloPost" placeholder="Digite o título da postagem">
                        </div>

                        <div class="form-group">
                            <label for="editConteudoPost">Conteúdo</label>
                            <textarea class="form-control" id="editConteudoPost" name="editConteudoPost" rows="4" placeholder="Digite o conteúdo da postagem"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="editImagemPost">Imagem</label>
                            <input type="file" class="form-control" onchange="checkFiles(event)" id="editImagemPost" name="editImagemPost">
                        </div>

                        <div class="form-group">
                            <label for="editGrupo">Grupo</label>
                            <select class="form-control" id="editGrupo" name="editGrupo">
                                <?php
                                $query_grupos = mysqli_query($conn, "SELECT g.idGrupo, g.nome FROM professoresgrupos pg JOIN grupos g ON pg.idGrupo = g.idGrupo WHERE pg.idProfessor = '$id_professor'");
                                while ($grupo = mysqli_fetch_assoc($query_grupos)) {
                                    echo "<option value='{$grupo['idGrupo']}'>{$grupo['nome']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <input type="hidden" name="editIdPublicacao" id="editIdPublicacao">

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" name='edit_publicacao' class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
    <title>Meu Perfil</title>
</body>

</html>