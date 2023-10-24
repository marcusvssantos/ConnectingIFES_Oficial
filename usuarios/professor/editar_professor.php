<?php
ob_start(); // Inicia o buffer de saída
include("header.php");

$emailError = "";

try {
    // Supondo que você já tenha uma conexão PDO estabelecida em header.php como $pdo
    $stmt = $pdo->prepare("SELECT * FROM Usuarios WHERE email = :email");
    $stmt->bindParam(':email', $logado);
    $stmt->execute();
    $f_professor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST['nome'];
        $sobrenome = $_POST['sobrenome'];
        $email = $_POST['email'];

        // Verificação de email duplicado
        $stmtEmail = $pdo->prepare("SELECT * FROM Usuarios WHERE email = :email AND email != :currentEmail");
        $stmtEmail->bindParam(':email', $email);
        $stmtEmail->bindParam(':currentEmail', $logado);
        $stmtEmail->execute();

        if ($stmtEmail->rowCount() > 0) {
            $emailError = "Este email já está sendo usado por outro usuário!";
        } else {
            $_SESSION['login'] = $email;
            // Verifica se uma nova foto de perfil foi enviada
            if (isset($_FILES["fotoPerfil"]) && $_FILES["fotoPerfil"]["error"] === 0) {
                $fotoPerfilNome = $_FILES["fotoPerfil"]["name"];
                $fotoPerfilTempName = $_FILES["fotoPerfil"]["tmp_name"];
                $diretorioDestino = "../../uploads/foto/";
                $nomeUnico = time() . '_' . $fotoPerfilNome;
                $caminhoCompleto = $diretorioDestino . $nomeUnico;
                
                if (file_exists($f_professor['fotoPerfil'])) {
                    unlink($f_professor['fotoPerfil']);
                }
                // Move a nova foto de perfil para o diretório de destino
                move_uploaded_file($fotoPerfilTempName, $caminhoCompleto);

                // Atualiza o caminho da foto de perfil no banco de dados
                $stmt = $pdo->prepare("UPDATE Usuarios SET nome=:nome, sobrenome=:sobrenome, email=:email, fotoPerfil=:fotoPerfil WHERE email = :logado");
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':sobrenome', $sobrenome);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':fotoPerfil', $caminhoCompleto);
                $stmt->bindParam(':logado', $logado);
                $stmt->execute();
            } else {
                // Se nenhuma nova foto de perfil foi enviada, apenas atualize os outros campos
                $stmt = $pdo->prepare("UPDATE Usuarios SET nome=:nome, sobrenome=:sobrenome, email=:email WHERE email = :logado");
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':sobrenome', $sobrenome);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':logado', $logado);
                $stmt->execute();
            }

            header('location: editar_professor.php');
            exit;
        }
    }
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <!-- Seu código de cabeçalho aqui -->
</head>

<body>
    <div class="main-content">
        <div class="container mt-5">
            <h2 class="mb-4" style="color: #32A041;">Editar Perfil</h2>

            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $f_professor['nome']; ?>">
                </div>
                <div class="form-group">
                    <label for="sobrenome">Sobrenome:</label>
                    <input type="text" class="form-control" id="sobrenome" name="sobrenome" value="<?php echo $f_professor['sobrenome']; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $f_professor['email']; ?>">
                    <?php if ($emailError) : ?>
                        <div class="alert alert-danger mt-2">
                            <?php echo $emailError; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="fotoPerfil">Foto de Perfil:</label>
                    <input type="file" onchange="checkFiles(event)" class="form-control" id="fotoPerfil" name="fotoPerfil">
                </div><br>
                <button type="submit" class="btn btn-primary" name="atualizar">Atualizar</button>
            </form>
        </div>
    </div>
</body>

</html>
<?php
ob_end_flush();
?>