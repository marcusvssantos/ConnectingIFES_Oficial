<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta os dados do formulário
    $nome = $_POST["nome"];
    $sobrenome = $_POST["sobrenome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $matricula = $_POST["matricula"];
    $curso = $_POST["curso"];
    $periodo = $_POST["periodo"];

    // Verifica se uma foto de perfil foi enviada
    if (isset($_FILES["fotoPerfil"])) {
        $fotoPerfilNome = $_FILES["fotoPerfil"]["name"];
        $fotoPerfilTempName = $_FILES["fotoPerfil"]["tmp_name"];
        $fotoPerfilTamanho = $_FILES["fotoPerfil"]["size"];
        $fotoPerfilErro = $_FILES["fotoPerfil"]["error"];

        // Verifica se não ocorreu nenhum erro no upload
        if ($fotoPerfilErro === 0) {
            // Diretório onde a foto de perfil será armazenada (altere para o seu diretório)
            $diretorioDestino = "../estudante/uploads/foto/";

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

    // Verificar se a matrícula já está cadastrada no banco de dados
    $sqlVerificaMatricula = "SELECT idUsuario FROM Estudantes WHERE matricula = ?";
    $stmtVerificaMatricula = $conexao->prepare($sqlVerificaMatricula);

    if ($stmtVerificaMatricula === false) {
        die("Erro na preparação da consulta: " . $conexao->error);
    }

    $stmtVerificaMatricula->bind_param("s", $matricula);
    $stmtVerificaMatricula->execute();
    $stmtVerificaMatricula->store_result();

    if ($stmtVerificaMatricula->num_rows > 0) {
        echo "Esta matrícula já está cadastrada. Por favor, use outra matrícula.";
        $stmtVerificaMatricula->close();
        $conexao->close();
        exit;
    }

    $stmtVerificaMatricula->close();

    // Agora você pode inserir os dados do estudante, incluindo o nome do arquivo da foto de perfil,
    // no banco de dados.

    // Insere o usuário na tabela "usuarios"
    $sqlUsuario = "INSERT INTO Usuarios (nome, sobrenome, email, senha, fotoPerfil, tipo) VALUES (?, ?, ?, ?, ?, 'estudante')";
    $stmtUsuario = $conexao->prepare($sqlUsuario);

    if ($stmtUsuario === false) {
        die("Erro na preparação da consulta: " . $conexao->error);
    }

    $stmtUsuario->bind_param("sssss", $nome, $sobrenome, $email, $senha, $caminhoCompleto);

    if (!$stmtUsuario->execute()) {
        echo "Erro ao cadastrar o usuário: " . $stmtUsuario->error;
        $conexao->close();
        exit;
    }

    // Obtém o ID do usuário recém-cadastrado
    $idUsuario = $stmtUsuario->insert_id;

    // Insere o estudante na tabela "estudantes" com base no ID do usuário
    $sqlEstudante = "INSERT INTO Estudantes (matricula, curso, periodo, idUsuario) VALUES (?, ?, ?, ?)";
    $stmtEstudante = $conexao->prepare($sqlEstudante);

    if ($stmtEstudante === false) {
        die("Erro na preparação da consulta: " . $conexao->error);
    }

    $stmtEstudante->bind_param("ssii", $matricula, $curso, $periodo, $idUsuario);

    if ($stmtEstudante->execute()) {
        echo "Cadastro de estudante realizado com sucesso!";
    } else {
        echo "Erro ao cadastrar o estudante: " . $stmtEstudante->error;
    }

    $stmtUsuario->close();
    $stmtEstudante->close();
    $conexao->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Estudante</title>
</head>
<body>
    <h2>Cadastro de Estudante</h2>
    <form method="post" action="cadastro_estudante.php" enctype="multipart/form-data">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for "sobrenome">Sobrenome:</label>
        <input type="text" id="sobrenome" name="sobrenome" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br><br>

        <label for="matricula">Matrícula:</label>
        <input type="text" id="matricula" name="matricula" required><br><br>

        <label for="curso">Curso:</label>
        <input type="text" id="curso" name="curso" required><br><br>

        <label for="periodo">Período:</label>
        <input type="number" id="periodo" name="periodo" required><br><br>

        <label for="fotoPerfil">Foto de Perfil:</label>
        <input type="file" id="fotoPerfil" name="fotoPerfil" required><br><br>

        <input type="submit" value="Cadastrar Estudante">
    </form>
</body>
</html>
