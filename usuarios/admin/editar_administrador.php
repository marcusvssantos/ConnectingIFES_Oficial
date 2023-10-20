<?php
include("header.php");

// Verifique se o ID do administrador foi passado via parâmetro na URL
if (isset($_GET["id"])) {
    $idAdministrador = $_GET["id"];

    // Consulta SQL para obter os detalhes do administrador com base no ID
    $sql = "SELECT Usuarios.idUsuario, Usuarios.nome, Usuarios.sobrenome, Usuarios.email, Usuarios.fotoPerfil FROM Usuarios INNER JOIN Administradores ON Usuarios.idUsuario = Administradores.idUsuario WHERE Usuarios.idUsuario = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $idAdministrador);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $linha = $resultado->fetch_assoc();
        // Dados do administrador
        $nome = $linha["nome"];
        $sobrenome = $linha["sobrenome"];
        $email = $linha["email"];
        $fotoPerfil = $linha["fotoPerfil"];
    } else {
        echo "Administrador não encontrado.";
        exit;
    }

    $stmt->close();
} else {
    echo "ID do administrador não especificado na URL.";
    exit;
}

// Verifique se o formulário foi enviado para atualizar os detalhes do administrador
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta os dados do formulário
    $nome = $_POST["nome"];
    $sobrenome = $_POST["sobrenome"];
    $email = $_POST["email"];

    // Verifica se uma nova foto de perfil foi enviada
    if (isset($_FILES["novaFotoPerfil"]) && $_FILES["novaFotoPerfil"]["error"] === 0) {
        // Diretório onde a nova foto de perfil será armazenada (altere para o seu diretório)
        $diretorioDestino = "../../uploads/foto/";

        // Gere um nome único para a nova foto de perfil com base no timestamp atual
        $nomeUnico = time() . '_' . $_FILES["novaFotoPerfil"]["name"];

        // Monta o caminho completo para salvar a nova foto
        $caminhoCompleto = $diretorioDestino . $nomeUnico;

        // Move a nova foto de perfil para o diretório de destino
        move_uploaded_file($_FILES["novaFotoPerfil"]["tmp_name"], $caminhoCompleto);

        // Atualiza o caminho da foto de perfil no banco de dados
        $sqlAtualizaFotoPerfil = "UPDATE Usuarios SET fotoPerfil = ? WHERE idUsuario = ?";
        $stmtAtualizaFotoPerfil = $conn->prepare($sqlAtualizaFotoPerfil);

        if ($stmtAtualizaFotoPerfil === false) {
            die("Erro na preparação da consulta: " . $conn->error);
        }

        $stmtAtualizaFotoPerfil->bind_param("si", $caminhoCompleto, $idAdministrador);

        if (!$stmtAtualizaFotoPerfil->execute()) {
            echo "Erro ao atualizar a foto de perfil: " . $stmtAtualizaFotoPerfil->error;
            $stmtAtualizaFotoPerfil->close();
        }

        $stmtAtualizaFotoPerfil->close();
    }

    // Atualiza os dados do administrador no banco de dados
    $sqlAtualizaAdministrador = "UPDATE Usuarios SET nome = ?, sobrenome = ?, email = ? WHERE idUsuario = ?";
    $stmtAtualizaAdministrador = $conn->prepare($sqlAtualizaAdministrador);

    if ($stmtAtualizaAdministrador === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmtAtualizaAdministrador->bind_param("sssi", $nome, $sobrenome, $email, $idAdministrador);

    if (!$stmtAtualizaAdministrador->execute()) {
        echo "Erro ao atualizar os dados do administrador: " . $stmtAtualizaAdministrador->error;
        $stmtAtualizaAdministrador->close();
        exit;
    }

    echo "Dados do administrador atualizados com sucesso!";
    $stmtAtualizaAdministrador->close();
    header('location: gerenciar_administrador.php');
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
    <title>Editar Administrador</title>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Editar Administrador</h2>
        <form method="post" action="editar_administrador.php?id=<?php echo $idAdministrador; ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="text" class="form-control" id="nome" placeholder="Nome" name="nome" value="<?php echo $nome; ?>" required><br>
            </div>

            <div class="mb-3">
                <input type="text" class="form-control" id="sobrenome" placeholder="Sobrenome" name="sobrenome" value="<?php echo $sobrenome; ?>" required><br>
            </div>

            <div class="mb-3">
                <input type="email"  class="form-control" id="email" placeholder="Email" name="email" value="<?php echo $email; ?>" required><br>
            </div>

            <div class="mb-3">
                <label for="novaFotoPerfil">Nova Foto de Perfil:</label><br>
                <input type="file" class="form-control" id="novaFotoPerfil" name="novaFotoPerfil"><br>
            </div>

            <div class="mb-3">
                <label for="fotoPerfil">Foto de Perfil Atual:</label><br>
                <img src="<?php echo $fotoPerfil; ?>" class="form-control" style="border-radius: 50%; width: 50px; height: 50px;" alt="Foto de Perfil Atual"><br>
            </div>

            <input type="submit" class="btn btn-primary" value="Atualizar Dados">
        </form>
    </div>
</body>

</html>
