<?php
include("header.php");

function conectarDB() {
    $conexao = new mysqli("localhost", "root", "", "connecting_ifes_oficial");
    if ($conexao->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
    }
    return $conexao;
}

function verificarExistencia($conexao, $tabela, $campo, $valor) {
    $sql = "SELECT idUsuario FROM $tabela WHERE $campo = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $valor);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}

function realizarUploadImagem($imagem) {
    $nome = $imagem["name"];
    $tempNome = $imagem["tmp_name"];
    $erro = $imagem["error"];
    if ($erro === 0) {
        $diretorioDestino = "../../uploads/foto/";
        $nomeUnico = time() . '_' . $nome;
        $caminhoCompleto = $diretorioDestino . $nomeUnico;
        move_uploaded_file($tempNome, $caminhoCompleto);
        return $caminhoCompleto;
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $sobrenome = $_POST["sobrenome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $fotoPerfil = realizarUploadImagem($_FILES["fotoPerfil"]);

    $conexao = conectarDB();

    if (verificarExistencia($conexao, "Usuarios", "email", $email)) {
        echo "Este e-mail já está cadastrado. Por favor, use outro e-mail.";
        echo "<br>";  
    }

    // Insere o usuário na tabela "usuarios"
    $sqlUsuario = "INSERT INTO Usuarios (nome, sobrenome, email, senha, fotoPerfil, tipo) VALUES (?, ?, ?, ?, ?, 'admin')";
    $stmtUsuario = $conexao->prepare($sqlUsuario);
    $stmtUsuario->bind_param("sssss", $nome, $sobrenome, $email, $senha, $fotoPerfil);
    $stmtUsuario->execute();
    $idUsuario = $stmtUsuario->insert_id;

    // Insere o administrador na tabela "administradores"
    $sqlAdministrador = "INSERT INTO Administradores (idUsuario) VALUES (?)";
    $stmtAdministrador = $conexao->prepare($sqlAdministrador);
    $stmtAdministrador->bind_param("i", $idUsuario);
    if ($stmtAdministrador->execute()) {
        echo "Cadastro de administrador realizado com sucesso!";
        header('location: gerenciar_administrador.php');

    } else {
        echo "Erro ao cadastrar o administrador: ";
    }

    $conexao->close();
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
    <title>Cadastro de Administrador</title>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Cadastro de Administrador</h2>
        <form method="post" action="cadastrar_administrador.php" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="text" class="form-control" id="nome" placeholder="Nome" name="nome" required>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" id="sobrenome" placeholder="Sobrenome" name="sobrenome" required>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" id="senha" placeholder="Senha" name="senha" required>
            </div>
            <div class="mb-3">
                <label for="fotoPerfil">Foto de Perfil:</label>
                <input type="file" class="form-control" id="fotoPerfil" name="fotoPerfil" required>
            </div>
            <input type="submit" class="btn btn-success" value="Cadastrar Administrador">
        </form>
    </div>
</body>
</html>
