<?php
include("header.php");

// Verifique se o ID do professor foi passado via parâmetro na URL
if (isset($_GET["id"])) {
    $idProfessor = $_GET["id"];

    // Consulta SQL para obter os detalhes do professor com base no ID
    $sql = "SELECT Usuarios.idUsuario, Usuarios.nome, Usuarios.sobrenome, Usuarios.email, Usuarios.fotoPerfil, Professores.departamento, Professores.siape FROM Usuarios INNER JOIN Professores ON Usuarios.idUsuario = Professores.idUsuario WHERE Usuarios.idUsuario = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $idProfessor);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $linha = $resultado->fetch_assoc();
        // Dados do professor
        $nome = $linha["nome"];
        $sobrenome = $linha["sobrenome"];
        $email = $linha["email"];
        $departamento = $linha["departamento"];
        $siape = $linha["siape"];
        $fotoPerfil = $linha["fotoPerfil"];
    } else {
        echo "Professor não encontrado.";
        exit;
    }

    $stmt->close();
} else {
    echo "ID do professor não especificado na URL.";
    exit;
}

// Verifique se o formulário foi enviado para atualizar os detalhes do professor
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta os dados do formulário
    $nome = $_POST["nome"];
    $sobrenome = $_POST["sobrenome"];
    $email = $_POST["email"];
    $departamento = $_POST["departamento"];
    $siape = $_POST["siape"];

    // Verifica se uma nova foto de perfil foi enviada
    if (isset($_FILES["novaFotoPerfil"]) && $_FILES["novaFotoPerfil"]["error"] === 0) {
        $fotoPerfilNome = $_FILES["novaFotoPerfil"]["name"];
        $fotoPerfilTempName = $_FILES["novaFotoPerfil"]["tmp_name"];

        // Diretório onde a nova foto de perfil será armazenada (altere para o seu diretório)
        $diretorioDestino = "../professor/uploads/foto/";

        // Gere um nome único para a nova foto de perfil com base no timestamp atual
        $nomeUnico = time() . '_' . $fotoPerfilNome;

        // Monta o caminho completo para salvar a nova foto
        $caminhoCompleto = $diretorioDestino . $nomeUnico;

        // Move a nova foto de perfil para o diretório de destino
        move_uploaded_file($fotoPerfilTempName, $caminhoCompleto);

        // Atualiza o caminho da foto de perfil no banco de dados
        $sqlAtualizaFotoPerfil = "UPDATE Usuarios SET fotoPerfil = ? WHERE idUsuario = ?";
        $stmtAtualizaFotoPerfil = $conn->prepare($sqlAtualizaFotoPerfil);

        if ($stmtAtualizaFotoPerfil === false) {
            die("Erro na preparação da consulta: " . $conn->error);
        }

        $stmtAtualizaFotoPerfil->bind_param("si", $caminhoCompleto, $idProfessor);

        if (!$stmtAtualizaFotoPerfil->execute()) {
            echo "Erro ao atualizar a foto de perfil: " . $stmtAtualizaFotoPerfil->error;
            $stmtAtualizaFotoPerfil->close();
            exit;
        }

        $stmtAtualizaFotoPerfil->close();
    }

    // Atualiza os dados do professor no banco de dados
    $sqlAtualizaProfessor = "UPDATE Usuarios SET nome = ?, sobrenome = ?, email = ? WHERE idUsuario = ?";
    $stmtAtualizaProfessor = $conn->prepare($sqlAtualizaProfessor);

    if ($stmtAtualizaProfessor === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmtAtualizaProfessor->bind_param("sssi", $nome, $sobrenome, $email, $idProfessor);

    if (!$stmtAtualizaProfessor->execute()) {
        echo "Erro ao atualizar os dados do professor: " . $stmtAtualizaProfessor->error;
        $stmtAtualizaProfessor->close();
        exit;
    }

    // Atualiza os dados específicos do professor na tabela "professores"
    $sqlAtualizaDadosProfessor = "UPDATE Professores SET departamento = ?, siape = ? WHERE idUsuario = ?";
    $stmtAtualizaDadosProfessor = $conn->prepare($sqlAtualizaDadosProfessor);

    if ($stmtAtualizaDadosProfessor === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmtAtualizaDadosProfessor->bind_param("ssi", $departamento, $siape, $idProfessor);

    if (!$stmtAtualizaDadosProfessor->execute()) {
        echo "Erro ao atualizar os dados do professor: " . $stmtAtualizaDadosProfessor->error;
        $stmtAtualizaDadosProfessor->close();
        exit;
    }

    echo "Dados do professor atualizados com sucesso!";
    $stmtAtualizaDadosProfessor->close();
    header('location: gerenciar_professor.php');
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
    <title>Editar Professor</title>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Editar Professor</h2>
        <form method="post" action="editar_professor.php?id=<?php echo $idProfessor; ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="text" id="nome" placeholder="Nome" name="nome" value="<?php echo $nome; ?>" required><br><br>
            </div>

            <div class="mb-3">
                <input type="text" id="sobrenome" placeholder="Sobrenome" name="sobrenome" value="<?php echo $sobrenome; ?>" required><br><br>
            </div>

            <div class="mb-3">
                <input type="email" id="email" placeholder="Email" name="email" value="<?php echo $email; ?>" required><br><br>
            </div>

            <div class="mb-3">
                <input type="text" id="departamento" placeholder="Departamento" name="departamento" value="<?php echo $departamento; ?>" required><br><br>
            </div>

            <div class="mb-3">
                <input type="text" id="siape" placeholder="SIAPE" name="siape" value="<?php echo $siape; ?>" required><br><br>
            </div>

            <div class="mb-3">
                <label for="novaFotoPerfil">Nova Foto de Perfil:</label><br>
                <input type="file" id="novaFotoPerfil" name="novaFotoPerfil"><br><br>
            </div>

            <div class="mb-3">
                <label for="fotoPerfil">Foto de Perfil Atual:</label><br>
                <img src="<?php echo $fotoPerfil; ?>" style="border-radius: 50%; width: 50px; height: 50px;" alt="Foto de Perfil Atual"><br><br>
            </div>

            <input type="submit" class="btn btn-primary" value="Atualizar Dados">
        </form>
    </div>
</body>

</html>