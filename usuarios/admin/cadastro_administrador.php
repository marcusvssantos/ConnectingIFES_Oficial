<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta os dados do formulário
    $nome = $_POST["nome"];
    $sobrenome = $_POST["sobrenome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    // Verifica se uma foto de perfil foi enviada
    if (isset($_FILES["fotoPerfil"])) {
        $fotoPerfilNome = $_FILES["fotoPerfil"]["name"];
        $fotoPerfilTempName = $_FILES["fotoPerfil"]["tmp_name"];
        $fotoPerfilTamanho = $_FILES["fotoPerfil"]["size"];
        $fotoPerfilErro = $_FILES["fotoPerfil"]["error"];

        // Verifica se não ocorreu nenhum erro no upload
        if ($fotoPerfilErro === 0) {
            // Diretório onde a foto de perfil será armazenada (altere para o seu diretório)
            $diretorioDestino = "../admin/uploads/foto/";

            // Gere um nome único para a foto de perfil com base no timestamp atual
            $nomeUnico = time() . '_' . $fotoPerfilNome;

            // Monta o caminho completo para salvar a foto
            $caminhoCompleto = $diretorioDestino . $nomeUnico;

            // Move a foto de perfil para o diretório de destino
            move_uploaded_file($fotoPerfilTempName, $caminhoCompleto);
        } else {
            echo "Erro no upload da foto de perfil.";
            exit;
        }
    } else {
        echo "Nenhuma foto de perfil foi enviada.";
        exit;
    }

    // Verificar se o e-mail já está cadastrado no banco de dados
    $conexao = new mysqli("localhost", "root", "", "connecting_ifes_oficial");

    if ($conexao->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
    }

    $sqlVerificaEmail = "SELECT idUsuario FROM Usuarios WHERE email = ?";
    $stmtVerificaEmail = $conexao->prepare($sqlVerificaEmail);

    if ($stmtVerificaEmail === false) {
        die("Erro na preparação da consulta: " . $conexao->error);
    }

    $stmtVerificaEmail->bind_param("s", $email);
    $stmtVerificaEmail->execute();
    $stmtVerificaEmail->store_result();

    if ($stmtVerificaEmail->num_rows > 0) {
        echo "Este e-mail já está cadastrado. Por favor, use outro e-mail.";
        $stmtVerificaEmail->close();
        $conexao->close();
        exit;
    }

    $stmtVerificaEmail->close();

    // Agora que os dados do administrador foram verificados com sucesso, insira o registro do administrador no banco de dados.

    // Insere o usuário na tabela "usuarios"
    $sqlUsuario = "INSERT INTO Usuarios (nome, sobrenome, email, senha, fotoPerfil, tipo) VALUES (?, ?, ?, ?, ?, 'admin')";
$stmtUsuario = $conexao->prepare($sqlUsuario);

if ($stmtUsuario === false) {
    die("Erro na preparação da consulta: " . $conexao->error);
}

$stmtUsuario->bind_param("sssss", $nome, $sobrenome, $email, $senha, $caminhoCompleto);

if (!$stmtUsuario->execute()) {
    echo "Erro ao cadastrar o administrador: " . $stmtUsuario->error;
    $conexao->close();
    exit;
}

// Obtém o ID do usuário recém-cadastrado
$idUsuario = $stmtUsuario->insert_id;

$stmtUsuario->close();

// Insere o administrador na tabela "administradores" com base no ID do usuário
$sqlAdministrador = "INSERT INTO Administradores (idUsuario) VALUES (?)";
$stmtAdministrador = $conexao->prepare($sqlAdministrador);

if ($stmtAdministrador === false) {
    die("Erro na preparação da consulta: " . $conexao->error);
}

$stmtAdministrador->bind_param("i", $idUsuario);

if ($stmtAdministrador->execute()) {
    echo "Cadastro de administrador realizado com sucesso!";
} else {
    echo "Erro ao cadastrar o administrador: " . $stmtAdministrador->error;
}

$stmtAdministrador->close();
$conexao->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Administrador</title>
</head>
<body>
    <h2>Cadastro de Administrador</h2>
    <form method="post" action="cadastro_administrador.php" enctype="multipart/form-data">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="sobrenome">Sobrenome:</label>
        <input type="text" id="sobrenome" name="sobrenome" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br><br>

        <label for="fotoPerfil">Foto de Perfil:</label>
        <input type="file" id="fotoPerfil" name="fotoPerfil" required><br><br>

        <input type="submit" value="Cadastrar Administrador">
    </form>
</body>
</html>
